<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VhtsControllerv3 extends Controller
{
    public function index()
    {
        return view('vhts.vhtsv3');
    }

    public function processValidation(Request $request)
    {
        try {
            // Validate input (make month, year, reference_tpk optional)
            $request->validate([
                'data' => 'required|string',
                'month' => 'nullable|integer|min:1|max:12',
                'year' => 'nullable|integer|min:2020',
                'reference_tpk' => 'nullable|numeric|min:0|max:100',
            ]);

            $dataInput = $request->input('data');
            $month = $request->input('month');
            $year = $request->input('year');
            $referenceTpk = $request->input('reference_tpk', 0); // Default to 0 if not provided

            // Parse data with logging
            $rows = array_filter(explode("\n", trim($dataInput)));
            $parsedData = [];
            foreach ($rows as $index => $row) {
                $cols = preg_split('/\t|\s+/', trim($row), -1, PREG_SPLIT_NO_EMPTY);
                if (count($cols) < 12) {
                    Log::info("Row $index skipped: Insufficient columns (" . count($cols) . ")");
                    continue;
                }
                $parsedData[] = [
                    'tanggal' => max(0, (int)($cols[0] ?? 0)),
                    'jumlah_kamar_tersedia' => max(0, (int)round($cols[1] ?? 0)),
                    'jumlah_tempat_tidur_tersedia' => max(0, (int)round($cols[2] ?? 0)),
                    'kamar_digunakan_kemarin' => max(0, (int)round($cols[3] ?? 0)),
                    'kamar_baru_dimasuki' => max(0, (int)round($cols[4] ?? 0)),
                    'kamar_ditinggalkan' => max(0, (int)round($cols[5] ?? 0)),
                    'tamu_kemarin_asing' => max(0, (int)round($cols[6] ?? 0)),
                    'tamu_kemarin_indonesia' => max(0, (int)round($cols[7] ?? 0)),
                    'tamu_baru_datang_asing' => max(0, (int)round($cols[8] ?? 0)),
                    'tamu_baru_datang_indonesia' => max(0, (int)round($cols[9] ?? 0)),
                    'tamu_berangkat_asing' => max(0, (int)round($cols[10] ?? 0)),
                    'tamu_berangkat_indonesia' => max(0, (int)round($cols[11] ?? 0)),
                ];
                Log::info("Parsed Row $index: ", $parsedData[count($parsedData) - 1]);
            }
            Log::info("Total parsed rows: " . count($parsedData));

            if (empty($parsedData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data valid yang dapat diproses.',
                ], 400);
            }

            $originalData = $parsedData;
            $validatedData = $parsedData;

            // Step 1: Adjust RLMA and RLMNus (Priority 1)
            $this->adjustRlmaRlmnus($validatedData, $originalData);

            // Step 2: Initial Asing Validation
            $this->initialAsingValidation($validatedData, $originalData);

            // Step 3: Initial Indonesia Validation
            $this->initialIndonesiaValidation($validatedData, $originalData);

            // Recalculate automatic columns after initial adjustments
            $this->recalculateAutomaticColumns($validatedData);

            // Step 4: Enhanced room-guest relationship validation
            $this->enhanceRoomGuestConsistency($validatedData, $originalData);

            // Step 5: Adjust check-ins/check-outs for metrics
            $this->adjustMetrics($validatedData);

            // Validate daily rules
            $isValidationFixed = false;
            $validationMessages = [];
            $maxIterations = 100;
            $iteration = 0;

            while ($iteration < $maxIterations) {
                $iteration++;
                $fixedThisIteration = false;
                $messagesThisIteration = [];

                for ($i = 0; $i < count($validatedData); $i++) {
                    $row = $validatedData[$i];
                    $isFirstRow = ($i == 0);

                    $result = $this->validateRow($row, $i + 1, $isFirstRow, $validatedData, $i, $originalData[$i]);
                    $row = $result['row'];
                    $validatedData[$i] = $row;

                    if ($result['fixed']) {
                        $fixedThisIteration = true;
                        $isValidationFixed = true;
                        $messagesThisIteration[] = $result['message'];
                        $this->recalculateAutomaticColumns($validatedData, $i + 1);
                    }
                }

                // Handle negative values
                $negativeFixMessages = [];
                for ($i = 0; $i < count($validatedData); $i++) {
                    $row = $validatedData[$i];
                    $rowChanged = false;

                    foreach ($row as $key => $value) {
                        if ($value < 0) {
                            $row[$key] = 0;
                            $negativeFixMessages[] = "Tanggal {$row['tanggal']}: Nilai negatif di '$key' ($value). Diperbaiki menjadi 0.";
                            $rowChanged = true;
                        }
                    }

                    if ($rowChanged) {
                        $validatedData[$i] = $row;
                        $fixedThisIteration = true;
                        $isValidationFixed = true;
                        $this->recalculateAutomaticColumns($validatedData, $i + 1);
                    }
                }

                $validationMessages = array_merge($validationMessages, $messagesThisIteration, $negativeFixMessages);

                if (!$fixedThisIteration) {
                    break;
                }
            }

            // Verify consistency of Col 7 and 8 without reducing check-out
            $consistencyMessages = [];
            $consistencyFixed = false;
            for ($i = 1; $i < count($validatedData); $i++) {
                $row = $validatedData[$i];
                $prevRow = $validatedData[$i - 1];
                $originalRow = $originalData[$i];

                $expectedTamuKemarinAsing = max(0, (int)round($prevRow['tamu_kemarin_asing'] + $prevRow['tamu_baru_datang_asing'] - max($prevRow['tamu_berangkat_asing'], $originalRow['tamu_berangkat_asing'])));
                if ($row['tamu_kemarin_asing'] != $expectedTamuKemarinAsing) {
                    $consistencyMessages[] = "Tanggal {$row['tanggal']}: Tamu Kemarin Asing diharapkan $expectedTamuKemarinAsing, ditemukan {$row['tamu_kemarin_asing']}. Diperbaiki.";
                    $validatedData[$i]['tamu_kemarin_asing'] = $expectedTamuKemarinAsing;
                    $consistencyFixed = true;
                }

                $expectedTamuKemarinIndonesia = max(0, (int)round($prevRow['tamu_kemarin_indonesia'] + $prevRow['tamu_baru_datang_indonesia'] - max($prevRow['tamu_berangkat_indonesia'], $originalRow['tamu_berangkat_indonesia'])));
                if ($row['tamu_kemarin_indonesia'] != $expectedTamuKemarinIndonesia) {
                    $consistencyMessages[] = "Tanggal {$row['tanggal']}: Tamu Kemarin Indonesia diharapkan $expectedTamuKemarinIndonesia, ditemukan {$row['tamu_kemarin_indonesia']}. Diperbaiki.";
                    $validatedData[$i]['tamu_kemarin_indonesia'] = $expectedTamuKemarinIndonesia;
                    $consistencyFixed = true;
                }
            }

            if ($consistencyFixed) {
                $isValidationFixed = true;
                $validationMessages = array_merge($validationMessages, $consistencyMessages);
                $this->recalculateAutomaticColumns($validatedData);
            }

            // Calculate monthly metrics
            $metrics = $this->calculateMonthlyMetrics($validatedData);
            $metricsMessages = [];
            $metricsValid = true;

            // Validate TPK against reference
            if ($referenceTpk > 0 && ($metrics['tpk'] < $referenceTpk * 0.8 || $metrics['tpk'] > $referenceTpk * 1.2)) {
                $metricsMessages[] = "TPK ({$metrics['tpk']}%) di luar kisaran ±20% dari referensi ({$referenceTpk}%).";
                $metricsValid = false;
            }

            // Validate RLMA and RLMNus
            if ($metrics['rlma'] < 1.0 || $metrics['rlma'] > 3.0) {
                $metricsMessages[] = "RLMA ({$metrics['rlma']} hari) di luar kisaran 1–3 hari.";
                $metricsValid = false;
            }
            if ($metrics['rlmnus'] < 1.0 || $metrics['rlmnus'] > 3.0) {
                $metricsMessages[] = "RLMNus ({$metrics['rlmnus']} hari) di luar kisaran 1–3 hari.";
                $metricsValid = false;
            }

            // Validate GPR
            if ($metrics['gpr'] < 1.0 || $metrics['gpr'] > 3.0) {
                $metricsMessages[] = "GPR ({$metrics['gpr']}) di luar kisaran 1–3 tamu per kamar.";
                $metricsValid = false;
            }

            // Validate TPTT
            if ($metrics['tptt'] > 100.0) {
                $metricsMessages[] = "TPTT ({$metrics['tptt']}%) melebihi 100%.";
                $metricsValid = false;
            }

            // Detect underreporting
            $underreportingMessages = [];
            for ($i = 0; $i < count($validatedData); $i++) {
                $row = $validatedData[$i];
                $totalGuests = $row['tamu_kemarin_asing'] + $row['tamu_kemarin_indonesia'];
                $gpr = $row['kamar_digunakan_kemarin'] > 0 ? $totalGuests / $row['kamar_digunakan_kemarin'] : 0;
                if ($gpr < 1.0 && $row['kamar_digunakan_kemarin'] > 0) {
                    $underreportingMessages[] = "Tanggal {$row['tanggal']}: Potensi underreporting (GPR {$gpr} < 1.0, tamu {$totalGuests} untuk {$row['kamar_digunakan_kemarin']} kamar).";
                }
            }

            // Track changes
            $changes = [];
            for ($i = 0; $i < count($validatedData); $i++) {
                $originalRow = $originalData[$i];
                $validatedRow = $validatedData[$i];
                $rowChanges = [];

                foreach ($validatedRow as $key => $value) {
                    if ($originalRow[$key] != $value) {
                        $rowChanges[$key] = [
                            'original' => $originalRow[$key],
                            'new' => $value,
                        ];
                    }
                }

                if (!empty($rowChanges)) {
                    $changes[$i] = $rowChanges;
                }
            }

            // Compile messages
            $finalMessage = $isValidationFixed ? implode("\n", $validationMessages) : 'Tidak ada pelanggaran aturan harian ditemukan.';
            if (!$metricsValid) {
                $finalMessage .= "\n\nPeringatan Metrik Bulanan:\n" . implode("\n", $metricsMessages);
            }
            if (!empty($underreportingMessages)) {
                $finalMessage .= "\n\nPeringatan Underreporting:\n" . implode("\n", $underreportingMessages);
            }
            $finalMessage .= "\n\nMetrik Bulanan:\n";
            $finalMessage .= "TPK: {$metrics['tpk']}%\n";
            $finalMessage .= "RLMA: {$metrics['rlma']} hari\n";
            $finalMessage .= "RLMNus: {$metrics['rlmnus']} hari\n";
            $finalMessage .= "GPR: {$metrics['gpr']}\n";
            $finalMessage .= "TPTT: {$metrics['tptt']}%";

            return response()->json([
                'success' => true,
                'message' => $finalMessage,
                'data' => $validatedData,
                'fixed' => $isValidationFixed,
                'changes' => $changes,
                'metrics' => $metrics,
            ]);
        } catch (\Exception $e) {
            Log::error('Validation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses validasi: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function adjustRlmaRlmnus(&$data, $originalData)
    {
        $messages = [];
        $maxIterations = 20;
        $iteration = 0;

        while ($iteration < $maxIterations) {
            $iteration++;
            $metrics = $this->calculateMonthlyMetrics($data);
            $rlma = $metrics['rlma'];
            $rlmnus = $metrics['rlmnus'];

            if ($rlma >= 1 && $rlma <= 3 && $rlmnus >= 1 && $rlmnus <= 3) {
                break;
            }

            $adjusted = false;
            for ($i = 0; $i < count($data) - 1; $i++) { // Avoid last row for next day check
                $row = $data[$i];
                $nextRow = $data[$i + 1];
                $originalRow = $originalData[$i];

                // Adjust RLMA (Asing)
                if ($rlma > 3) {
                    $newCheckIn = (int)round(max(1, $row['tamu_baru_datang_asing'] * 0.9)); // Reduce check-in by 10%
                    if ($newCheckIn < $nextRow['tamu_berangkat_asing']) {
                        $newCheckIn = (int)round($nextRow['tamu_berangkat_asing'] + 1);
                    }
                    if ($newCheckIn != $row['tamu_baru_datang_asing']) {
                        $messages[] = "Tanggal {$row['tanggal']}: RLMA ({$rlma}) > 3. Tamu Baru Datang Asing diperbaiki dari {$row['tamu_baru_datang_asing']} ke $newCheckIn.";
                        $row['tamu_baru_datang_asing'] = $newCheckIn;
                        $adjusted = true;
                    }
                    $newCheckOut = (int)round(max($originalRow['tamu_berangkat_asing'], $row['tamu_berangkat_asing'] + 1));
                    if ($newCheckOut > $row['tamu_berangkat_asing']) {
                        $messages[] = "Tanggal {$row['tanggal']}: RLMA ({$rlma}) > 3. Tamu Berangkat Asing diperbaiki dari {$row['tamu_berangkat_asing']} ke $newCheckOut.";
                        $row['tamu_berangkat_asing'] = $newCheckOut;
                        $adjusted = true;
                    }
                } elseif ($rlma < 1) {
                    $newCheckIn = (int)round($nextRow['tamu_berangkat_asing'] + 1);
                    if ($newCheckIn > $row['tamu_baru_datang_asing']) {
                        $messages[] = "Tanggal {$row['tanggal']}: RLMA ({$rlma}) < 1. Tamu Baru Datang Asing diperbaiki dari {$row['tamu_baru_datang_asing']} ke $newCheckIn.";
                        $row['tamu_baru_datang_asing'] = $newCheckIn;
                        $adjusted = true;
                    }
                }

                // Adjust RLMNus (Indonesia)
                if ($rlmnus > 3) {
                    $newCheckIn = (int)round(max(1, $row['tamu_baru_datang_indonesia'] * 0.9)); // Reduce check-in by 10%
                    if ($newCheckIn < $nextRow['tamu_berangkat_indonesia']) {
                        $newCheckIn = (int)round($nextRow['tamu_berangkat_indonesia'] + 1);
                    }
                    if ($newCheckIn != $row['tamu_baru_datang_indonesia']) {
                        $messages[] = "Tanggal {$row['tanggal']}: RLMNus ({$rlmnus}) > 3. Tamu Baru Datang Indonesia diperbaiki dari {$row['tamu_baru_datang_indonesia']} ke $newCheckIn.";
                        $row['tamu_baru_datang_indonesia'] = $newCheckIn;
                        $adjusted = true;
                    }
                    $newCheckOut = (int)round(max($originalRow['tamu_berangkat_indonesia'], $row['tamu_berangkat_indonesia'] + 1));
                    if ($newCheckOut > $row['tamu_berangkat_indonesia']) {
                        $messages[] = "Tanggal {$row['tanggal']}: RLMNus ({$rlmnus}) > 3. Tamu Berangkat Indonesia diperbaiki dari {$row['tamu_berangkat_indonesia']} ke $newCheckOut.";
                        $row['tamu_berangkat_indonesia'] = $newCheckOut;
                        $adjusted = true;
                    }
                } elseif ($rlmnus < 1) {
                    $newCheckIn = (int)round($nextRow['tamu_berangkat_indonesia'] + 1);
                    if ($newCheckIn > $row['tamu_baru_datang_indonesia']) {
                        $messages[] = "Tanggal {$row['tanggal']}: RLMNus ({$rlmnus}) < 1. Tamu Baru Datang Indonesia diperbaiki dari {$row['tamu_baru_datang_indonesia']} ke $newCheckIn.";
                        $row['tamu_baru_datang_indonesia'] = $newCheckIn;
                        $adjusted = true;
                    }
                }

                $data[$i] = $row;
            }

            if ($adjusted) {
                $this->recalculateAutomaticColumns($data);
            }

            if (!empty($messages)) {
                Log::info("RLMA/RLMNus adjustments: " . implode("\n", $messages));
            }
        }
    }

    private function initialAsingValidation(&$data, $originalData)
    {
        $messages = [];
        for ($i = 0; $i < count($data); $i++) {
            $row = $data[$i];
            $originalRow = $originalData[$i];
            $isLastRow = ($i == count($data) - 1);

            // Store original check-out value
            $originalCheckOut = $originalRow['tamu_berangkat_asing'];

            // Rule: If check-in is 0, set it to the next day's check-out (if not last row)
            if ($row['tamu_baru_datang_asing'] == 0 && !$isLastRow) {
                $nextRow = $data[$i + 1];
                $nextCheckOut = $nextRow['tamu_berangkat_asing'];
                if ($nextCheckOut > 0) {
                    $newCheckIn = (int)round($nextCheckOut);
                    $messages[] = "Tanggal {$row['tanggal']}: Tamu Baru Datang Asing 0. Diperbaiki dengan mengambil Tamu Berangkat Asing hari berikutnya ({$nextCheckOut}, dibulatkan ke $newCheckIn).";
                    $row['tamu_baru_datang_asing'] = $newCheckIn;
                }
            }

            // Rule: Adjust check-out to match check-in if check-out is 0, never reduce check-out
            if ($row['tamu_berangkat_asing'] == 0 && $row['tamu_baru_datang_asing'] > 0) {
                $newCheckOut = (int)round(max($originalCheckOut, $row['tamu_baru_datang_asing']));
                $messages[] = "Tanggal {$row['tanggal']}: Tamu Berangkat Asing 0, tetapi Tamu Baru Datang Asing {$row['tamu_baru_datang_asing']}. Diperbaiki dengan menambah Tamu Berangkat Asing menjadi $newCheckOut.";
                $row['tamu_berangkat_asing'] = $newCheckOut;
            }

            // Rule: Check-out should not exceed available guests, but never reduce below original
            if ($i > 0) {
                $prevRow = $data[$i - 1];
                $availableGuests = (int)round($prevRow['tamu_kemarin_asing'] + $row['tamu_baru_datang_asing']);
                if ($row['tamu_berangkat_asing'] > $availableGuests) {
                    $newCheckOut = (int)round(max($originalCheckOut, $availableGuests));
                    $messages[] = "Tanggal {$row['tanggal']}: Tamu Berangkat Asing ({$row['tamu_berangkat_asing']}) melebihi tamu tersedia ($availableGuests). Dijaga pada nilai asli {$originalCheckOut}, dibulatkan ke $newCheckOut.";
                    $row['tamu_berangkat_asing'] = $newCheckOut;
                }
            }

            $data[$i] = $row;
        }
    }

    private function initialIndonesiaValidation(&$data, $originalData)
    {
        $messages = [];
        for ($i = 0; $i < count($data); $i++) {
            $row = $data[$i];
            $originalRow = $originalData[$i];
            $isLastRow = ($i == count($data) - 1);

            // Store original check-out value
            $originalCheckOut = $originalRow['tamu_berangkat_indonesia'];

            // Rule: If check-in is 0, set it to the next day's check-out (if not last row)
            if ($row['tamu_baru_datang_indonesia'] == 0 && !$isLastRow) {
                $nextRow = $data[$i + 1];
                $nextCheckOut = $nextRow['tamu_berangkat_indonesia'];
                if ($nextCheckOut > 0) {
                    $newCheckIn = (int)round($nextCheckOut);
                    $messages[] = "Tanggal {$row['tanggal']}: Tamu Baru Datang Indonesia 0. Diperbaiki dengan mengambil Tamu Berangkat Indonesia hari berikutnya ({$nextCheckOut}, dibulatkan ke $newCheckIn).";
                    $row['tamu_baru_datang_indonesia'] = $newCheckIn;
                }
            }

            // Rule: Adjust check-out to match check-in if check-out is 0, never reduce check-out
            if ($row['tamu_berangkat_indonesia'] == 0 && $row['tamu_baru_datang_indonesia'] > 0) {
                $newCheckOut = (int)round(max($originalCheckOut, $row['tamu_baru_datang_indonesia']));
                $messages[] = "Tanggal {$row['tanggal']}: Tamu Berangkat Indonesia 0, tetapi Tamu Baru Datang Indonesia {$row['tamu_baru_datang_indonesia']}. Diperbaiki dengan menambah Tamu Berangkat Indonesia menjadi $newCheckOut.";
                $row['tamu_berangkat_indonesia'] = $newCheckOut;
            }

            // Rule: Check-out should not exceed available guests, but never reduce below original
            if ($i > 0) {
                $prevRow = $data[$i - 1];
                $availableGuests = (int)round($prevRow['tamu_kemarin_indonesia'] + $row['tamu_baru_datang_indonesia']);
                if ($row['tamu_berangkat_indonesia'] > $availableGuests) {
                    $newCheckOut = (int)round(max($originalCheckOut, $availableGuests));
                    $messages[] = "Tanggal {$row['tanggal']}: Tamu Berangkat Indonesia ({$row['tamu_berangkat_indonesia']}) melebihi tamu tersedia ($availableGuests). Dijaga pada nilai asli {$originalCheckOut}, dibulatkan ke $newCheckOut.";
                    $row['tamu_berangkat_indonesia'] = $newCheckOut;
                }
            }

            $data[$i] = $row;
        }
    }

    private function enhanceRoomGuestConsistency(&$data, $originalData)
    {
        $messages = [];
        $maxIterations = 10;
        $iteration = 0;

        while ($iteration < $maxIterations) {
            $iteration++;
            $adjusted = false;

            for ($i = 0; $i < count($data); $i++) {
                $row = $data[$i];

                // Calculate guest movements
                $totalNewGuests = $row['tamu_baru_datang_asing'] + $row['tamu_baru_datang_indonesia'];
                $totalDepartingGuests = $row['tamu_berangkat_asing'] + $row['tamu_berangkat_indonesia'];

                // Ensure room check-ins are proportional to guest check-ins (target: 2 guests per room)
                if ($totalNewGuests > 0) {
                    $targetRoomCheckIns = (int)round(max(1, ceil($totalNewGuests / 2)));
                    if ($row['kamar_baru_dimasuki'] == 0 || abs($row['kamar_baru_dimasuki'] - $targetRoomCheckIns) > 1) {
                        $messages[] = "Tanggal {$row['tanggal']}: Optimizing room check-ins. Tamu Baru Datang ($totalNewGuests) → Kamar Baru Dimasuki diperbaiki dari {$row['kamar_baru_dimasuki']} ke $targetRoomCheckIns.";
                        $row['kamar_baru_dimasuki'] = $targetRoomCheckIns;
                        $adjusted = true;
                    }
                }

                // Ensure room check-outs are proportional to guest check-outs (target: 2 guests per room)
                if ($totalDepartingGuests > 0) {
                    $targetRoomCheckOuts = (int)round(max(1, ceil($totalDepartingGuests / 2)));
                    if ($row['kamar_ditinggalkan'] == 0 || abs($row['kamar_ditinggalkan'] - $targetRoomCheckOuts) > 1) {
                        $messages[] = "Tanggal {$row['tanggal']}: Optimizing room check-outs. Tamu Berangkat ($totalDepartingGuests) → Kamar Ditinggalkan diperbaiki dari {$row['kamar_ditinggalkan']} ke $targetRoomCheckOuts.";
                        $row['kamar_ditinggalkan'] = $targetRoomCheckOuts;
                        $adjusted = true;
                    }
                }

                // Note: kamar_digunakan_kemarin is calculated by core equations and should not be overridden here

                $data[$i] = $row;
            }

            if ($adjusted) {
                $this->recalculateAutomaticColumns($data);
            }

            if (!$adjusted) {
                break;
            }
        }

        if (!empty($messages)) {
            Log::info("Room-Guest Consistency Enhancement: " . implode("\n", $messages));
        }
    }

    private function adjustMetrics(&$data)
    {
        $messages = [];
        $maxIterations = 20;
        $iteration = 0;

        while ($iteration < $maxIterations) {
            $iteration++;
            $metrics = $this->calculateMonthlyMetrics($data);
            $gpr = $metrics['gpr'];

            if ($gpr >= 1 && $gpr <= 3) {
                break;
            }

            $adjusted = false;
            for ($i = 0; $i < count($data); $i++) {
                $row = $data[$i];

                // Adjust GPR
                if ($gpr > 3) {
                    $newRooms = (int)round(min($row['jumlah_kamar_tersedia'], $row['kamar_digunakan_kemarin'] + 1));
                    if ($newRooms > $row['kamar_digunakan_kemarin']) {
                        $messages[] = "Tanggal {$row['tanggal']}: GPR ({$gpr}) > 3. Kamar Digunakan Kemarin diperbaiki dari {$row['kamar_digunakan_kemarin']} ke $newRooms.";
                        $row['kamar_digunakan_kemarin'] = $newRooms;
                        $adjusted = true;
                    }
                } elseif ($gpr < 1) {
                    $totalGuests = $row['tamu_kemarin_asing'] + $row['tamu_kemarin_indonesia'];
                    if ($totalGuests < $row['kamar_digunakan_kemarin']) {
                        $newGuestsAsing = (int)round($row['tamu_kemarin_asing'] + 1);
                        $newGuestsIndonesia = (int)round($row['tamu_kemarin_indonesia'] + 1);
                        $messages[] = "Tanggal {$row['tanggal']}: GPR ({$gpr}) < 1. Tamu Kemarin diperbaiki: Asing {$row['tamu_kemarin_asing']} ke $newGuestsAsing, Indonesia {$row['tamu_kemarin_indonesia']} ke $newGuestsIndonesia.";
                        $row['tamu_kemarin_asing'] = $newGuestsAsing;
                        $row['tamu_kemarin_indonesia'] = $newGuestsIndonesia;
                        $adjusted = true;
                    }
                }

                $data[$i] = $row;
            }

            if ($adjusted) {
                $this->recalculateAutomaticColumns($data);
            }

            if (!$adjusted) {
                break;
            }
        }

        if (!empty($messages)) {
            Log::info("Metric adjustments: " . implode("\n", $messages));
        }
    }

    private function recalculateAutomaticColumns(&$data, $startIndex = 0)
    {
        for ($i = $startIndex; $i < count($data); $i++) {
            $row = $data[$i];

            if ($i > 0) {
                $prevRow = $data[$i - 1];
                $row['kamar_digunakan_kemarin'] = (int)round(max(1, $prevRow['kamar_digunakan_kemarin'] + $prevRow['kamar_baru_dimasuki'] - $prevRow['kamar_ditinggalkan']));
                $row['tamu_kemarin_asing'] = (int)round(max(0, $prevRow['tamu_kemarin_asing'] + $prevRow['tamu_baru_datang_asing'] - $prevRow['tamu_berangkat_asing']));
                $row['tamu_kemarin_indonesia'] = (int)round(max(0, $prevRow['tamu_kemarin_indonesia'] + $prevRow['tamu_baru_datang_indonesia'] - $prevRow['tamu_berangkat_indonesia']));
            }

            // Adjust rooms based on guests if necessary
            $totalGuests = $row['tamu_kemarin_asing'] + $row['tamu_kemarin_indonesia'];
            if ($totalGuests > 0 && $row['kamar_digunakan_kemarin'] == 0) {
                $row['kamar_digunakan_kemarin'] = (int)round(min($row['jumlah_kamar_tersedia'], max(1, ceil($totalGuests / 2))));
            }

            // Enhanced room-guest relationship validation
            $this->validateRoomGuestRelationship($row);

            $data[$i] = $row;
        }
    }

    private function validateRoomGuestRelationship(&$row)
    {
        // Calculate required rooms based on guest movements
        $totalNewGuests = $row['tamu_baru_datang_asing'] + $row['tamu_baru_datang_indonesia'];
        $totalDepartingGuests = $row['tamu_berangkat_asing'] + $row['tamu_berangkat_indonesia'];

        // Rule 1: Ensure room check-ins match guest check-ins (aim for ~2 guests per room)
        if ($totalNewGuests > 0) {
            $requiredRoomCheckIns = (int)round(max(1, ceil($totalNewGuests / 2)));
            if ($row['kamar_baru_dimasuki'] < $requiredRoomCheckIns) {
                $row['kamar_baru_dimasuki'] = $requiredRoomCheckIns;
            }
        }

        // Rule 2: Ensure room check-outs match guest check-outs (aim for ~2 guests per room)
        if ($totalDepartingGuests > 0) {
            $requiredRoomCheckOuts = (int)round(max(1, ceil($totalDepartingGuests / 2)));
            if ($row['kamar_ditinggalkan'] < $requiredRoomCheckOuts) {
                $row['kamar_ditinggalkan'] = $requiredRoomCheckOuts;
            }
        }

        // Rule 3: Prevent over-allocation of rooms (preserve core calculation equations)
        if ($row['kamar_digunakan_kemarin'] > $row['jumlah_kamar_tersedia']) {
            $row['kamar_digunakan_kemarin'] = $row['jumlah_kamar_tersedia'];
        }

        // Rule 5: Ensure logical room flow (can't check out more rooms than available)
        if ($row['kamar_ditinggalkan'] > $row['kamar_digunakan_kemarin']) {
            $row['kamar_ditinggalkan'] = max(0, $row['kamar_digunakan_kemarin']);
        }
    }

    private function validateRow($row, $tanggal, $isFirstRow, &$data, $rowIndex, $originalRow)
    {
        $fixed = false;
        $messages = [];
        $buffer = 2;

        // Adjust rooms based on guests
        $totalGuests = $row['tamu_kemarin_asing'] + $row['tamu_kemarin_indonesia'];
        if ($totalGuests > 0 && $row['kamar_digunakan_kemarin'] == 0) {
            $newRooms = (int)round(min($row['jumlah_kamar_tersedia'], max(1, ceil($totalGuests / 2))));
            $messages[] = "Tanggal $tanggal: Tamu Kemarin ($totalGuests) > 0, tetapi Kamar Digunakan Kemarin 0. Diperbaiki menjadi $newRooms.";
            $row['kamar_digunakan_kemarin'] = $newRooms;
            $fixed = true;
        }

        // Enhanced room-guest relationship validation for check-ins
        $totalNewGuests = $row['tamu_baru_datang_asing'] + $row['tamu_baru_datang_indonesia'];
        if ($totalNewGuests > 0) {
            $requiredRoomCheckIns = (int)round(max(1, ceil($totalNewGuests / 2)));
            if ($row['kamar_baru_dimasuki'] < $requiredRoomCheckIns) {
                $messages[] = "Tanggal $tanggal: Tamu Baru Datang ($totalNewGuests) memerlukan $requiredRoomCheckIns kamar. Kamar Baru Dimasuki diperbaiki dari {$row['kamar_baru_dimasuki']} ke $requiredRoomCheckIns.";
                $row['kamar_baru_dimasuki'] = $requiredRoomCheckIns;
                $fixed = true;
            }
        }

        // Enhanced room-guest relationship validation for check-outs
        $totalDepartingGuests = $row['tamu_berangkat_asing'] + $row['tamu_berangkat_indonesia'];
        if ($totalDepartingGuests > 0) {
            $requiredRoomCheckOuts = (int)round(max(1, ceil($totalDepartingGuests / 2)));
            if ($row['kamar_ditinggalkan'] < $requiredRoomCheckOuts) {
                $messages[] = "Tanggal $tanggal: Tamu Berangkat ($totalDepartingGuests) memerlukan $requiredRoomCheckOuts kamar. Kamar Ditinggalkan diperbaiki dari {$row['kamar_ditinggalkan']} ke $requiredRoomCheckOuts.";
                $row['kamar_ditinggalkan'] = $requiredRoomCheckOuts;
                $fixed = true;
            }
        }

        // Rule 1: Jumlah kamar tersedia >= Kamar digunakan kemarin
        if ($row['jumlah_kamar_tersedia'] < $row['kamar_digunakan_kemarin']) {
            if ($isFirstRow) {
                $row['kamar_digunakan_kemarin'] = (int)round($row['jumlah_kamar_tersedia']);
                $messages[] = "Tanggal $tanggal: Kamar tersedia ({$row['jumlah_kamar_tersedia']}) < kamar digunakan kemarin. Diperbaiki menjadi {$row['kamar_digunakan_kemarin']}.";
            } else {
                $prevRowIndex = $rowIndex - 1;
                $prevRow = $data[$prevRowIndex];
                $excess = (int)round($row['kamar_digunakan_kemarin'] - $row['jumlah_kamar_tersedia']);
                $prevRow['kamar_ditinggalkan'] = (int)round($prevRow['kamar_ditinggalkan'] + $excess + $buffer);
                $data[$prevRowIndex] = $prevRow;
                $messages[] = "Tanggal $tanggal: Kamar tersedia ({$row['jumlah_kamar_tersedia']}) < kamar digunakan kemarin. Kamar ditinggalkan di tanggal " . ($tanggal - 1) . " diperbaiki menjadi {$prevRow['kamar_ditinggalkan']}.";
            }
            $fixed = true;
        }

        // Rule 2: Kamar digunakan kemarin >= Kamar ditinggalkan
        if ($row['kamar_digunakan_kemarin'] < $row['kamar_ditinggalkan']) {
            $row['kamar_ditinggalkan'] = (int)round(max(0, $row['kamar_digunakan_kemarin'] - $buffer));
            $messages[] = "Tanggal $tanggal: Kamar digunakan kemarin ({$row['kamar_digunakan_kemarin']}) < kamar ditinggalkan. Diperbaiki menjadi {$row['kamar_ditinggalkan']}.";
            $fixed = true;
        }

        // Rule 3: Tamu asing kemarin >= Tamu asing check-out, but never reduce check-out
        if ($row['tamu_kemarin_asing'] < $row['tamu_berangkat_asing']) {
            if ($isFirstRow) {
                $row['tamu_kemarin_asing'] = (int)round($row['tamu_berangkat_asing'] + $buffer);
                $messages[] = "Tanggal $tanggal: Tamu asing kemarin < tamu asing check-out. Diperbaiki menjadi {$row['tamu_kemarin_asing']}.";
            } else {
                $prevRowIndex = $rowIndex - 1;
                $prevRow = $data[$prevRowIndex];
                $prevRow['tamu_berangkat_asing'] = (int)round(max($prevRow['tamu_berangkat_asing'], $originalRow['tamu_berangkat_asing'] - ($row['tamu_berangkat_asing'] - $row['tamu_kemarin_asing'] + $buffer)));
                $data[$prevRowIndex] = $prevRow;
                $messages[] = "Tanggal $tanggal: Tamu asing kemarin < tamu asing check-out. Tamu asing check-out di tanggal " . ($tanggal - 1) . " diperbaiki.";
                $this->recalculateAutomaticColumns($data, $rowIndex);
            }
            $fixed = true;
        }

        // Rule 4: Tamu Indonesia kemarin >= Tamu Indonesia check-out, but never reduce check-out
        if ($row['tamu_kemarin_indonesia'] < $row['tamu_berangkat_indonesia']) {
            if ($isFirstRow) {
                $row['tamu_kemarin_indonesia'] = (int)round($row['tamu_berangkat_indonesia'] + $buffer);
                $messages[] = "Tanggal $tanggal: Tamu Indonesia kemarin < tamu Indonesia check-out. Diperbaiki menjadi {$row['tamu_kemarin_indonesia']}.";
            } else {
                $prevRowIndex = $rowIndex - 1;
                $prevRow = $data[$prevRowIndex];
                $prevRow['tamu_berangkat_indonesia'] = (int)round(max($prevRow['tamu_berangkat_indonesia'], $originalRow['tamu_berangkat_indonesia'] - ($row['tamu_berangkat_indonesia'] - $row['tamu_kemarin_indonesia'] + $buffer)));
                $data[$prevRowIndex] = $prevRow;
                $messages[] = "Tanggal $tanggal: Tamu Indonesia kemarin < tamu Indonesia check-out. Tamu Indonesia check-out di tanggal " . ($tanggal - 1) . " diperbaiki.";
                $this->recalculateAutomaticColumns($data, $rowIndex);
            }
            $fixed = true;
        }

        // Rule 5: Tamu kemarin (asing + Indonesia) >= Kamar digunakan kemarin
        $totalTamuKemarin = (int)round($row['tamu_kemarin_asing'] + $row['tamu_kemarin_indonesia']);
        if ($totalTamuKemarin < $row['kamar_digunakan_kemarin']) {
            $diff = (int)round($row['kamar_digunakan_kemarin'] - $totalTamuKemarin + $buffer);
            if ($isFirstRow) {
                $row['tamu_kemarin_asing'] = (int)round($row['tamu_kemarin_asing'] + ceil($diff / 2));
                $row['tamu_kemarin_indonesia'] = (int)round($row['tamu_kemarin_indonesia'] + floor($diff / 2));
                $messages[] = "Tanggal $tanggal: Total tamu kemarin < kamar digunakan kemarin. Diperbaiki: Asing {$row['tamu_kemarin_asing']}, Indonesia {$row['tamu_kemarin_indonesia']}.";
            } else {
                $row['tamu_kemarin_asing'] = (int)round($row['tamu_kemarin_asing'] + ceil($diff / 2));
                $row['tamu_kemarin_indonesia'] = (int)round($row['tamu_kemarin_indonesia'] + floor($diff / 2));
                $messages[] = "Tanggal $tanggal: Total tamu kemarin < kamar digunakan kemarin. Tamu kemarin diperbaiki: Asing {$row['tamu_kemarin_asing']}, Indonesia {$row['tamu_kemarin_indonesia']}.";
            }
            $fixed = true;
        }

        // Rule 6: Tamu baru datang >= Kamar baru dimasuki
        $totalTamuBaru = (int)round($row['tamu_baru_datang_asing'] + $row['tamu_baru_datang_indonesia']);
        if ($totalTamuBaru < $row['kamar_baru_dimasuki']) {
            $row['kamar_baru_dimasuki'] = (int)round(max(0, $totalTamuBaru - $buffer));
            $messages[] = "Tanggal $tanggal: Tamu baru datang < kamar baru dimasuki. Diperbaiki menjadi {$row['kamar_baru_dimasuki']}.";
            $fixed = true;
        } elseif ($totalTamuBaru > 0 && $row['kamar_baru_dimasuki'] < ceil($totalTamuBaru / 2)) {
            $newCheckIns = (int)round(ceil($totalTamuBaru / 2));
            $messages[] = "Tanggal $tanggal: Tamu Baru Datang ($totalTamuBaru) requires adjustment. Kamar Baru Dimasuki diperbaiki dari {$row['kamar_baru_dimasuki']} ke $newCheckIns.";
            $row['kamar_baru_dimasuki'] = $newCheckIns;
            $fixed = true;
        }

        // Rule 7: Tamu berangkat >= Kamar ditinggalkan
        $totalTamuBerangkat = (int)round($row['tamu_berangkat_asing'] + $row['tamu_berangkat_indonesia']);
        if ($totalTamuBerangkat < $row['kamar_ditinggalkan']) {
            $diff = (int)round($row['kamar_ditinggalkan'] - $totalTamuBerangkat + $buffer);
            $row['tamu_berangkat_indonesia'] = (int)round($row['tamu_berangkat_indonesia'] + floor($diff / 2));
            $row['tamu_berangkat_asing'] = (int)round($row['tamu_berangkat_asing'] + ceil($diff / 2));
            $messages[] = "Tanggal $tanggal: Tamu berangkat < kamar ditinggalkan. Diperbaiki: Indonesia {$row['tamu_berangkat_indonesia']}, Asing {$row['tamu_berangkat_asing']}.";
            $fixed = true;
        } elseif ($totalTamuBerangkat > 0 && $row['kamar_ditinggalkan'] < ceil($totalTamuBerangkat / 2)) {
            $newCheckOuts = (int)round(ceil($totalTamuBerangkat / 2));
            $messages[] = "Tanggal $tanggal: Tamu Berangkat ($totalTamuBerangkat) requires adjustment. Kamar Ditinggalkan diperbaiki dari {$row['kamar_ditinggalkan']} ke $newCheckOuts.";
            $row['kamar_ditinggalkan'] = $newCheckOuts;
            $fixed = true;
        }

        // Rule 8: Daily GPR (Tamu Kemarin / Kamar Digunakan Kemarin) within 1-3
        $totalGuests = $row['tamu_kemarin_asing'] + $row['tamu_kemarin_indonesia'];
        $dailyGpr = $row['kamar_digunakan_kemarin'] > 0 ? $totalGuests / $row['kamar_digunakan_kemarin'] : 0;
        if ($dailyGpr > 3 && $row['kamar_digunakan_kemarin'] < $row['jumlah_kamar_tersedia']) {
            $newRooms = (int)round(min($row['jumlah_kamar_tersedia'], $row['kamar_digunakan_kemarin'] + 1));
            if ($newRooms > $row['kamar_digunakan_kemarin']) {
                $messages[] = "Tanggal $tanggal: Daily GPR ($dailyGpr) > 3. Kamar Digunakan Kemarin diperbaiki dari {$row['kamar_digunakan_kemarin']} ke $newRooms.";
                $row['kamar_digunakan_kemarin'] = $newRooms;
                $fixed = true;
            }
        } elseif ($dailyGpr < 1 && $totalGuests > 0) {
            $newRooms = (int)round(max(1, $row['kamar_digunakan_kemarin'] - 1));
            if ($newRooms < $row['kamar_digunakan_kemarin']) {
                $messages[] = "Tanggal $tanggal: Daily GPR ($dailyGpr) < 1. Kamar Digunakan Kemarin diperbaiki dari {$row['kamar_digunakan_kemarin']} ke $newRooms.";
                $row['kamar_digunakan_kemarin'] = $newRooms;
                $fixed = true;
            }
        }

        return ['row' => $row, 'fixed' => $fixed, 'message' => implode("\n", $messages)];
    }

    private function calculateMonthlyMetrics($data)
    {
        $sumKamarDigunakan = array_sum(array_column($data, 'kamar_digunakan_kemarin'));
        $sumKamarBaru = array_sum(array_column($data, 'kamar_baru_dimasuki'));
        $sumKamarDitinggalkan = array_sum(array_column($data, 'kamar_ditinggalkan'));
        $sumTamuKemarinAsing = array_sum(array_column($data, 'tamu_kemarin_asing'));
        $sumTamuKemarinIndonesia = array_sum(array_column($data, 'tamu_kemarin_indonesia'));
        $sumTamuBaruAsing = array_sum(array_column($data, 'tamu_baru_datang_asing'));
        $sumTamuBaruIndonesia = array_sum(array_column($data, 'tamu_baru_datang_indonesia'));
        $sumTamuBerangkatAsing = array_sum(array_column($data, 'tamu_berangkat_asing'));
        $sumTamuBerangkatIndonesia = array_sum(array_column($data, 'tamu_berangkat_indonesia'));
        $sumKamarTersedia = array_sum(array_column($data, 'jumlah_kamar_tersedia'));
        $sumTempatTidur = array_sum(array_column($data, 'jumlah_tempat_tidur_tersedia'));

        // TPK: (total_kamar_kemarin + total_kamar_checkin - total_kamar_checkout) * 100 / total_jumlah_kamar_tersedia
        $tpk = $sumKamarTersedia > 0 ? (($sumKamarDigunakan + $sumKamarBaru - $sumKamarDitinggalkan) * 100.0 / $sumKamarTersedia) : 0;

        // RLMA
        $rlma = $sumTamuBaruAsing > 0 ? ((float)round(($sumTamuKemarinAsing + $sumTamuBaruAsing - $sumTamuBerangkatAsing) / $sumTamuBaruAsing, 2)) : 0;

        // RLMNus
        $rlmnus = $sumTamuBaruIndonesia > 0 ? ((float)round(($sumTamuKemarinIndonesia + $sumTamuBaruIndonesia - $sumTamuBerangkatIndonesia) / $sumTamuBaruIndonesia, 2)) : 0;

        // GPR
        $totalGuests = (int)round($sumTamuKemarinAsing + $sumTamuKemarinIndonesia + $sumTamuBaruAsing + $sumTamuBaruIndonesia - $sumTamuBerangkatAsing - $sumTamuBerangkatIndonesia);
        $totalRooms = (int)round($sumKamarDigunakan + $sumKamarBaru - $sumKamarDitinggalkan);
        $gpr = $totalRooms > 0 ? ((float)round($totalGuests / $totalRooms, 2)) : 0;

        // TPTT
        $tptt = $sumTempatTidur > 0 ? ((float)round(($totalGuests / $sumTempatTidur) * 100, 2)) : 0;

        return [
            'tpk' => round($tpk, 2),
            'rlma' => round($rlma, 2),
            'rlmnus' => round($rlmnus, 2),
            'gpr' => round($gpr, 2),
            'tptt' => round($tptt, 2),
        ];
    }
}
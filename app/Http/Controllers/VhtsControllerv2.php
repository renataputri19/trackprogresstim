<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VhtsControllerv2 extends Controller
{
    // Method untuk menampilkan halaman VHTS
    public function index()
    {
        return view('vhts.vhtsv2');
    }

    public function processValidation(Request $request)
    {
        // Validate input
        $request->validate([
            'data' => 'required|string',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
            'reference_tpk' => 'required|numeric|min:0|max:100',
        ]);

        $dataInput = $request->input('data');
        $month = $request->input('month');
        $year = $request->input('year');
        $referenceTpk = $request->input('reference_tpk');

        // Parse data
        $rows = array_filter(explode("\n", trim($dataInput)));
        $parsedData = [];
        foreach ($rows as $row) {
            $cols = preg_split('/\t|\s+/', trim($row), -1, PREG_SPLIT_NO_EMPTY);
            if (count($cols) < 12) continue;

            $parsedData[] = [
                'tanggal' => max(0, (int)($cols[0] ?? 0)),
                'jumlah_kamar_tersedia' => max(0, (int)($cols[1] ?? 0)),
                'jumlah_tempat_tidur_tersedia' => max(0, (int)($cols[2] ?? 0)),
                'kamar_digunakan_kemarin' => max(0, (int)($cols[3] ?? 0)),
                'kamar_baru_dimasuki' => max(0, (int)($cols[4] ?? 0)),
                'kamar_ditinggalkan' => max(0, (int)($cols[5] ?? 0)),
                'tamu_kemarin_asing' => max(0, (int)($cols[6] ?? 0)),
                'tamu_kemarin_indonesia' => max(0, (int)($cols[7] ?? 0)),
                'tamu_baru_datang_asing' => max(0, (int)($cols[8] ?? 0)),
                'tamu_baru_datang_indonesia' => max(0, (int)($cols[9] ?? 0)),
                'tamu_berangkat_asing' => max(0, (int)($cols[10] ?? 0)),
                'tamu_berangkat_indonesia' => max(0, (int)($cols[11] ?? 0)),
            ];
        }

        if (empty($parsedData)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data valid yang dapat diproses.',
            ], 400);
        }

        $originalData = $parsedData;
        $validatedData = $parsedData;

        // Recalculate automatic columns
        $this->recalculateAutomaticColumns($validatedData);

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

                $result = $this->validateRow($row, $i + 1, $isFirstRow, $validatedData, $i);
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

        // Verify consistency of Col 7 and 8
        $consistencyMessages = [];
        $consistencyFixed = false;
        for ($i = 1; $i < count($validatedData); $i++) {
            $row = $validatedData[$i];
            $prevRow = $validatedData[$i - 1];

            $expectedTamuKemarinAsing = max(0, $prevRow['tamu_kemarin_asing'] + $prevRow['tamu_baru_datang_asing'] - $prevRow['tamu_berangkat_asing']);
            if ($row['tamu_kemarin_asing'] != $expectedTamuKemarinAsing) {
                $consistencyMessages[] = "Tanggal {$row['tanggal']}: Tamu Kemarin Asing diharapkan $expectedTamuKemarinAsing, ditemukan {$row['tamu_kemarin_asing']}. Diperbaiki.";
                $validatedData[$i]['tamu_kemarin_asing'] = $expectedTamuKemarinAsing;
                $consistencyFixed = true;
            }

            $expectedTamuKemarinIndonesia = max(0, $prevRow['tamu_kemarin_indonesia'] + $prevRow['tamu_baru_datang_indonesia'] - $prevRow['tamu_berangkat_indonesia']);
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
        if ($metrics['tpk'] < $referenceTpk * 0.8 || $metrics['tpk'] > $referenceTpk * 1.2) {
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
        if ($metrics['gpr'] > 2.0) {
            $metricsMessages[] = "GPR ({$metrics['gpr']}) melebihi 2 tamu per kamar.";
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
    }

    private function recalculateAutomaticColumns(&$data, $startIndex = 0)
    {
        for ($i = $startIndex; $i < count($data); $i++) {
            $row = $data[$i];

            if ($i > 0) {
                $prevRow = $data[$i - 1];
                $row['kamar_digunakan_kemarin'] = max(0, $prevRow['kamar_digunakan_kemarin'] + $prevRow['kamar_baru_dimasuki'] - $prevRow['kamar_ditinggalkan']);
                $row['tamu_kemarin_asing'] = max(0, $prevRow['tamu_kemarin_asing'] + $prevRow['tamu_baru_datang_asing'] - $prevRow['tamu_berangkat_asing']);
                $row['tamu_kemarin_indonesia'] = max(0, $prevRow['tamu_kemarin_indonesia'] + $prevRow['tamu_baru_datang_indonesia'] - $prevRow['tamu_berangkat_indonesia']);
            }

            $data[$i] = $row;
        }
    }

    private function validateRow($row, $tanggal, $isFirstRow, &$data, $rowIndex)
    {
        $fixed = false;
        $messages = [];
        $buffer = 2;

        // Rule 1: Jumlah kamar tersedia >= Kamar digunakan kemarin
        if ($row['jumlah_kamar_tersedia'] < $row['kamar_digunakan_kemarin']) {
            if ($isFirstRow) {
                $row['kamar_digunakan_kemarin'] = $row['jumlah_kamar_tersedia'];
                $messages[] = "Tanggal $tanggal: Kamar tersedia ({$row['jumlah_kamar_tersedia']}) < kamar digunakan kemarin. Diperbaiki menjadi {$row['kamar_digunakan_kemarin']}.";
            } else {
                $prevRowIndex = $rowIndex - 1;
                $prevRow = $data[$prevRowIndex];
                $excess = $row['kamar_digunakan_kemarin'] - $row['jumlah_kamar_tersedia'];
                $prevRow['kamar_ditinggalkan'] += $excess + $buffer;
                $data[$prevRowIndex] = $prevRow;
                $messages[] = "Tanggal $tanggal: Kamar tersedia ({$row['jumlah_kamar_tersedia']}) < kamar digunakan kemarin. Kamar ditinggalkan di tanggal " . ($tanggal - 1) . " diperbaiki menjadi {$prevRow['kamar_ditinggalkan']}.";
            }
            $fixed = true;
        }

        // Rule 2: Kamar digunakan kemarin >= Kamar ditinggalkan
        if ($row['kamar_digunakan_kemarin'] < $row['kamar_ditinggalkan']) {
            $row['kamar_ditinggalkan'] = max(0, $row['kamar_digunakan_kemarin'] - $buffer);
            $messages[] = "Tanggal $tanggal: Kamar digunakan kemarin ({$row['kamar_digunakan_kemarin']}) < kamar ditinggalkan. Diperbaiki menjadi {$row['kamar_ditinggalkan']}.";
            $fixed = true;
        }

        // Rule 3: Tamu asing kemarin >= Tamu asing check-out
        if ($row['tamu_kemarin_asing'] < $row['tamu_berangkat_asing']) {
            if ($isFirstRow) {
                $row['tamu_kemarin_asing'] = $row['tamu_berangkat_asing'] + $buffer;
                $messages[] = "Tanggal $tanggal: Tamu asing kemarin < tamu asing check-out. Diperbaiki menjadi {$row['tamu_kemarin_asing']}.";
            } else {
                $prevRowIndex = $rowIndex - 1;
                $prevRow = $data[$prevRowIndex];
                $prevRow['tamu_berangkat_asing'] = max(0, $prevRow['tamu_berangkat_asing'] - ($row['tamu_berangkat_asing'] - $row['tamu_kemarin_asing'] + $buffer));
                $data[$prevRowIndex] = $prevRow;
                $messages[] = "Tanggal $tanggal: Tamu asing kemarin < tamu asing check-out. Tamu asing check-out di tanggal " . ($tanggal - 1) . " diperbaiki.";
                $this->recalculateAutomaticColumns($data, $rowIndex);
            }
            $fixed = true;
        }

        // Rule 4: Tamu Indonesia kemarin >= Tamu Indonesia check-out
        if ($row['tamu_kemarin_indonesia'] < $row['tamu_berangkat_indonesia']) {
            if ($isFirstRow) {
                $row['tamu_kemarin_indonesia'] = $row['tamu_berangkat_indonesia'] + $buffer;
                $messages[] = "Tanggal $tanggal: Tamu Indonesia kemarin < tamu Indonesia check-out. Diperbaiki menjadi {$row['tamu_kemarin_indonesia']}.";
            } else {
                $prevRowIndex = $rowIndex - 1;
                $prevRow = $data[$prevRowIndex];
                $prevRow['tamu_berangkat_indonesia'] = max(0, $prevRow['tamu_berangkat_indonesia'] - ($row['tamu_berangkat_indonesia'] - $row['tamu_kemarin_indonesia'] + $buffer));
                $data[$prevRowIndex] = $prevRow;
                $messages[] = "Tanggal $tanggal: Tamu Indonesia kemarin < tamu Indonesia check-out. Tamu Indonesia check-out di tanggal " . ($tanggal - 1) . " diperbaiki.";
                $this->recalculateAutomaticColumns($data, $rowIndex);
            }
            $fixed = true;
        }

        // Rule 5: Tamu kemarin (asing + Indonesia) >= Kamar digunakan kemarin
        $totalTamuKemarin = $row['tamu_kemarin_asing'] + $row['tamu_kemarin_indonesia'];
        if ($totalTamuKemarin < $row['kamar_digunakan_kemarin']) {
            $diff = $row['kamar_digunakan_kemarin'] - $totalTamuKemarin + $buffer;
            if ($isFirstRow) {
                $row['tamu_kemarin_asing'] += ceil($diff / 2);
                $row['tamu_kemarin_indonesia'] += floor($diff / 2);
                $messages[] = "Tanggal $tanggal: Total tamu kemarin < kamar digunakan kemarin. Diperbaiki: Asing {$row['tamu_kemarin_asing']}, Indonesia {$row['tamu_kemarin_indonesia']}.";
            } else {
                $row['tamu_berangkat_asing'] = max(0, $row['tamu_berangkat_asing'] - ceil($diff / 2));
                $row['tamu_berangkat_indonesia'] = max(0, $row['tamu_berangkat_indonesia'] - floor($diff / 2));
                $messages[] = "Tanggal $tanggal: Total tamu kemarin < kamar digunakan kemarin. Tamu berangkat diperbaiki.";
            }
            $fixed = true;
        }

        // Rule 6: Tamu baru datang >= Kamar baru dimasuki
        $totalTamuBaru = $row['tamu_baru_datang_asing'] + $row['tamu_baru_datang_indonesia'];
        if ($totalTamuBaru < $row['kamar_baru_dimasuki']) {
            $row['kamar_baru_dimasuki'] = max(0, $totalTamuBaru - $buffer);
            $messages[] = "Tanggal $tanggal: Tamu baru datang < kamar baru dimasuki. Diperbaiki menjadi {$row['kamar_baru_dimasuki']}.";
            $fixed = true;
        }

        // Rule 7: Tamu berangkat >= Kamar ditinggalkan
        $totalTamuBerangkat = $row['tamu_berangkat_asing'] + $row['tamu_berangkat_indonesia'];
        if ($totalTamuBerangkat < $row['kamar_ditinggalkan']) {
            $diff = $row['kamar_ditinggalkan'] - $totalTamuBerangkat + $buffer;
            $row['tamu_berangkat_asing'] += ceil($diff / 2);
            $row['tamu_berangkat_indonesia'] += floor($diff / 2);
            $messages[] = "Tanggal $tanggal: Tamu berangkat < kamar ditinggalkan. Diperbaiki: Asing {$row['tamu_berangkat_asing']}, Indonesia {$row['tamu_berangkat_indonesia']}.";
            $fixed = true;
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

        // TPK
        $tpk = $sumKamarTersedia > 0 ? (($sumKamarDigunakan + $sumKamarBaru - $sumKamarDitinggalkan) / $sumKamarTersedia) * 100 : 0;

        // RLMA
        $rlma = $sumTamuBaruAsing > 0 ? ($sumTamuKemarinAsing + $sumTamuBaruAsing - $sumTamuBerangkatAsing) / $sumTamuBaruAsing : 0;

        // RLMNus
        $rlmnus = $sumTamuBaruIndonesia > 0 ? ($sumTamuKemarinIndonesia + $sumTamuBaruIndonesia - $sumTamuBerangkatIndonesia) / $sumTamuBaruIndonesia : 0;

        // GPR
        $totalGuests = $sumTamuKemarinAsing + $sumTamuKemarinIndonesia + $sumTamuBaruAsing + $sumTamuBaruIndonesia - $sumTamuBerangkatAsing - $sumTamuBerangkatIndonesia;
        $totalRooms = $sumKamarDigunakan + $sumKamarBaru - $sumKamarDitinggalkan;
        $gpr = $totalRooms > 0 ? $totalGuests / $totalRooms : 0;

        // TPTT
        $tptt = $sumTempatTidur > 0 ? ($totalGuests / $sumTempatTidur) * 100 : 0;

        return [
            'tpk' => round($tpk, 2),
            'rlma' => round($rlma, 2),
            'rlmnus' => round($rlmnus, 2),
            'gpr' => round($gpr, 2),
            'tptt' => round($tptt, 2),
        ];
    }
}
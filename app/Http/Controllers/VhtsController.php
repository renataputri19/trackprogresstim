<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VhtsController extends Controller
{
    // Method untuk menampilkan halaman VHTS
    public function index()
    {
        return view('vhts.vhts');
    }

    public function processValidation(Request $request)
    {
        // Ambil data dari request (data yang dipaste dari Excel)
        $data = $request->input('data');
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.',
            ], 400);
        }

        // Parse data menjadi array
        $rows = array_filter(explode("\n", trim($data)));
        $parsedData = [];
        foreach ($rows as $row) {
            $cols = preg_split('/\t|\s+/', trim($row), -1, PREG_SPLIT_NO_EMPTY);
            if (count($cols) < 12) continue; // Pastikan ada 12 kolom

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

        // Hitung ulang kolom otomatis untuk semua baris
        $this->recalculateAutomaticColumns($validatedData);

        // Validasi aturan untuk semua baris hingga tidak ada pelanggaran lagi
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

            // Periksa dan tangani nilai negatif setelah setiap iterasi
            $negativeFixMessagesThisIteration = [];
            for ($i = 0; $i < count($validatedData); $i++) {
                $row = $validatedData[$i];
                $rowChanged = false;

                foreach ($row as $key => $value) {
                    if ($value < 0) {
                        $row[$key] = 0;
                        $negativeFixMessagesThisIteration[] = "Tanggal {$row['tanggal']}: Nilai negatif ditemukan di kolom '$key' ($value). Diperbaiki menjadi 0.";
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

            $validationMessages = array_merge($validationMessages, $messagesThisIteration, $negativeFixMessagesThisIteration);

            if (!$fixedThisIteration) {
                break;
            }
        }

        // Verifikasi konsistensi kolom 7 dan 8 dengan persamaan
        $consistencyMessages = [];
        $consistencyFixed = false;
        for ($i = 1; $i < count($validatedData); $i++) {
            $row = $validatedData[$i];
            $prevRow = $validatedData[$i - 1];

            $expectedTamuKemarinAsing = $prevRow['tamu_kemarin_asing'] + $prevRow['tamu_baru_datang_asing'] - $prevRow['tamu_berangkat_asing'];
            $expectedTamuKemarinAsing = max(0, $expectedTamuKemarinAsing);
            if ($row['tamu_kemarin_asing'] != $expectedTamuKemarinAsing) {
                $consistencyMessages[] = "Tanggal {$row['tanggal']}: Kolom Tamu Kemarin Asing tidak konsisten. Diharapkan $expectedTamuKemarinAsing, tetapi ditemukan {$row['tamu_kemarin_asing']}. Diperbaiki.";
                $validatedData[$i]['tamu_kemarin_asing'] = $expectedTamuKemarinAsing;
                $consistencyFixed = true;
            }

            $expectedTamuKemarinIndonesia = $prevRow['tamu_kemarin_indonesia'] + $prevRow['tamu_baru_datang_indonesia'] - $prevRow['tamu_berangkat_indonesia'];
            $expectedTamuKemarinIndonesia = max(0, $expectedTamuKemarinIndonesia);
            if ($row['tamu_kemarin_indonesia'] != $expectedTamuKemarinIndonesia) {
                $consistencyMessages[] = "Tanggal {$row['tanggal']}: Kolom Tamu Kemarin Indonesia tidak konsisten. Diharapkan $expectedTamuKemarinIndonesia, tetapi ditemukan {$row['tamu_kemarin_indonesia']}. Diperbaiki.";
                $validatedData[$i]['tamu_kemarin_indonesia'] = $expectedTamuKemarinIndonesia;
                $consistencyFixed = true;
            }
        }

        if ($consistencyFixed) {
            $isValidationFixed = true;
            $validationMessages = array_merge($validationMessages, $consistencyMessages);
            $this->recalculateAutomaticColumns($validatedData);
        }

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

        $autoCalcMessage = '';
        for ($i = 0; $i < count($validatedData); $i++) {
            $row = $validatedData[$i];
            $originalRow = $originalData[$i];
            if ($row['kamar_digunakan_kemarin'] != $originalRow['kamar_digunakan_kemarin'] ||
                $row['tamu_kemarin_asing'] != $originalRow['tamu_kemarin_asing'] ||
                $row['tamu_kemarin_indonesia'] != $originalRow['tamu_kemarin_indonesia']) {
                $autoCalcMessage .= "Tanggal {$row['tanggal']}: Kolom otomatis dihitung ulang - Kamar Digunakan Kemarin: {$row['kamar_digunakan_kemarin']}, Tamu Kemarin Asing: {$row['tamu_kemarin_asing']}, Tamu Kemarin Indonesia: {$row['tamu_kemarin_indonesia']}.\n";
            }
        }

        $finalMessage = $isValidationFixed ? implode("\n", $validationMessages) : 'Tidak ada pelanggaran aturan ditemukan.';
        if ($autoCalcMessage) {
            $finalMessage .= "\n\nPerhitungan Otomatis:\n" . $autoCalcMessage;
        }

        return response()->json([
            'success' => true,
            'message' => $finalMessage,
            'data' => $validatedData,
            'fixed' => $isValidationFixed,
            'changes' => $changes,
        ]);
    }

    private function recalculateAutomaticColumns(&$data, $startIndex = 0)
    {
        for ($i = $startIndex; $i < count($data); $i++) {
            $row = $data[$i];

            if ($i == 0) {
                $row['kamar_digunakan_kemarin'] = $row['kamar_digunakan_kemarin'];
            } else {
                $prevRow = $data[$i - 1];
                $kamarDigunakanKemarin = $prevRow['kamar_digunakan_kemarin'] + $prevRow['kamar_baru_dimasuki'] - $prevRow['kamar_ditinggalkan'];
                $row['kamar_digunakan_kemarin'] = max(0, $kamarDigunakanKemarin);
            }

            if ($i == 0) {
                $row['tamu_kemarin_asing'] = $row['tamu_kemarin_asing'];
            } else {
                $prevRow = $data[$i - 1];
                $tamuKemarinAsing = $prevRow['tamu_kemarin_asing'] + $prevRow['tamu_baru_datang_asing'] - $prevRow['tamu_berangkat_asing'];
                $row['tamu_kemarin_asing'] = max(0, $tamuKemarinAsing);
            }

            if ($i == 0) {
                $row['tamu_kemarin_indonesia'] = $row['tamu_kemarin_indonesia'];
            } else {
                $prevRow = $data[$i - 1];
                $tamuKemarinIndonesia = $prevRow['tamu_kemarin_indonesia'] + $prevRow['tamu_baru_datang_indonesia'] - $prevRow['tamu_berangkat_indonesia'];
                $row['tamu_kemarin_indonesia'] = max(0, $tamuKemarinIndonesia);
            }

            $data[$i] = $row;
        }
    }

    private function validateRow($row, $tanggal, $isFirstRow, &$data, $rowIndex)
    {
        $fixed = false;
        $messages = [];
        $buffer = 2; // Buffer yang akan ditambahkan atau dikurangi

        // Aturan Validasi 1: Jumlah kamar tersedia (kolom 2) >= Kamar digunakan kemarin (kolom 4)
        if ($row['jumlah_kamar_tersedia'] < $row['kamar_digunakan_kemarin']) {
            if ($isFirstRow) {
                $row['kamar_digunakan_kemarin'] = $row['jumlah_kamar_tersedia'];
                $messages[] = "Tanggal $tanggal: Jumlah kamar tersedia ({$row['jumlah_kamar_tersedia']}) kurang dari kamar digunakan kemarin. Diperbaiki dengan mengurangi kamar digunakan kemarin menjadi {$row['kamar_digunakan_kemarin']}.";
            } else {
                $prevRowIndex = $rowIndex - 1;
                $prevRow = $data[$prevRowIndex];
                $excess = $row['kamar_digunakan_kemarin'] - $row['jumlah_kamar_tersedia'];
                $prevRow['kamar_ditinggalkan'] += $excess + $buffer; // Tambah buffer
                $data[$prevRowIndex] = $prevRow;
                $messages[] = "Tanggal $tanggal: Jumlah kamar tersedia ({$row['jumlah_kamar_tersedia']}) kurang dari kamar digunakan kemarin ({$row['kamar_digunakan_kemarin']}). Diperbaiki dengan membesarkan kamar ditinggalkan di tanggal " . ($tanggal - 1) . " menjadi {$prevRow['kamar_ditinggalkan']} (dengan buffer +$buffer).";
            }
            $fixed = true;
        }

        // Aturan Validasi 2: Kamar digunakan kemarin (kolom 4) >= Kamar ditinggalkan hari ini (kolom 6)
        if ($row['kamar_digunakan_kemarin'] < $row['kamar_ditinggalkan']) {
            if ($isFirstRow) {
                $row['kamar_digunakan_kemarin'] = $row['kamar_ditinggalkan'];
                $messages[] = "Tanggal $tanggal: Kamar digunakan kemarin ({$row['kamar_digunakan_kemarin']}) kurang dari kamar ditinggalkan. Diperbaiki dengan menambah kamar digunakan kemarin menjadi {$row['kamar_digunakan_kemarin']}.";
            } else {
                $row['kamar_ditinggalkan'] = max(0, $row['kamar_digunakan_kemarin'] - $buffer); // Kurangi dengan buffer
                $messages[] = "Tanggal $tanggal: Kamar digunakan kemarin ({$row['kamar_digunakan_kemarin']}) kurang dari kamar ditinggalkan. Diperbaiki dengan mengurangi kamar ditinggalkan menjadi {$row['kamar_ditinggalkan']} (dengan buffer -$buffer).";
            }
            $fixed = true;
        }

        // Aturan Validasi 7: Tamu berangkat hari ini (kolom 11 + kolom 12) >= Kamar ditinggalkan (kolom 6)
        $totalTamuBerangkat = $row['tamu_berangkat_asing'] + $row['tamu_berangkat_indonesia'];
        if ($totalTamuBerangkat < $row['kamar_ditinggalkan']) {
            if ($isFirstRow) {
                $diff = $row['kamar_ditinggalkan'] - $totalTamuBerangkat + $buffer; // Tambah buffer
                $row['tamu_kemarin_asing'] += ceil($diff / 2);
                $row['tamu_kemarin_indonesia'] += floor($diff / 2);
                $messages[] = "Tanggal $tanggal: Total tamu berangkat ($totalTamuBerangkat) kurang dari kamar ditinggalkan ({$row['kamar_ditinggalkan']}). Diperbaiki dengan menambah tamu kemarin menjadi: Tamu Kemarin Asing: {$row['tamu_kemarin_asing']}, Tamu Kemarin Indonesia: {$row['tamu_kemarin_indonesia']} (dengan buffer +$buffer).";
            } else {
                $diff = $row['kamar_ditinggalkan'] - $totalTamuBerangkat + $buffer; // Tambah buffer
                $row['tamu_berangkat_asing'] += ceil($diff / 2);
                $row['tamu_berangkat_indonesia'] += floor($diff / 2);
                $messages[] = "Tanggal $tanggal: Total tamu berangkat ($totalTamuBerangkat) kurang dari kamar ditinggalkan ({$row['kamar_ditinggalkan']}). Diperbaiki dengan menambah tamu berangkat menjadi: Tamu Berangkat Asing: {$row['tamu_berangkat_asing']}, Tamu Berangkat Indonesia: {$row['tamu_berangkat_indonesia']} (dengan buffer +$buffer).";
            }
            $fixed = true;
        }

        // Aturan Validasi 3: Tamu asing kemarin (kolom 7) >= Tamu asing check-out (kolom 11)
        if ($row['tamu_kemarin_asing'] < $row['tamu_berangkat_asing']) {
            if ($isFirstRow) {
                $row['tamu_kemarin_asing'] = $row['tamu_berangkat_asing'] + $buffer; // Tambah buffer
                $messages[] = "Tanggal $tanggal: Tamu asing kemarin ({$row['tamu_kemarin_asing']}) kurang dari tamu asing check-out ({$row['tamu_berangkat_asing']}). Diperbaiki dengan menambah tamu asing kemarin menjadi {$row['tamu_kemarin_asing']} (dengan buffer +$buffer).";
            } else {
                $prevRowIndex = $rowIndex - 1;
                $prevRow = $data[$prevRowIndex];
                $deficit = $row['tamu_berangkat_asing'] - $row['tamu_kemarin_asing'] + $buffer; // Tambah buffer
                $prevRow['tamu_berangkat_asing'] = max(0, $prevRow['tamu_berangkat_asing'] - $deficit);
                $data[$prevRowIndex] = $prevRow;
                $messages[] = "Tanggal $tanggal: Tamu asing kemarin ({$row['tamu_kemarin_asing']}) kurang dari tamu asing check-out ({$row['tamu_berangkat_asing']}). Diperbaiki dengan mengurangi tamu asing check-out di tanggal " . ($tanggal - 1) . " menjadi {$prevRow['tamu_berangkat_asing']} (dengan buffer -$buffer).";
                $this->recalculateAutomaticColumns($data, $rowIndex);
            }
            $fixed = true;
        }

        // Aturan Validasi 4: Tamu Indonesia kemarin (kolom 8) >= Tamu Indonesia check-out (kolom 12)
        if ($row['tamu_kemarin_indonesia'] < $row['tamu_berangkat_indonesia']) {
            if ($isFirstRow) {
                $row['tamu_kemarin_indonesia'] = $row['tamu_berangkat_indonesia'] + $buffer; // Tambah buffer
                $messages[] = "Tanggal $tanggal: Tamu Indonesia kemarin ({$row['tamu_kemarin_indonesia']}) kurang dari tamu Indonesia check-out ({$row['tamu_berangkat_indonesia']}). Diperbaiki dengan menambah tamu Indonesia kemarin menjadi {$row['tamu_kemarin_indonesia']} (dengan buffer +$buffer).";
            } else {
                $prevRowIndex = $rowIndex - 1;
                $prevRow = $data[$prevRowIndex];
                $deficit = $row['tamu_berangkat_indonesia'] - $row['tamu_kemarin_indonesia'] + $buffer; // Tambah buffer
                $prevRow['tamu_berangkat_indonesia'] = max(0, $prevRow['tamu_berangkat_indonesia'] - $deficit);
                $data[$prevRowIndex] = $prevRow;
                $messages[] = "Tanggal $tanggal: Tamu Indonesia kemarin ({$row['tamu_kemarin_indonesia']}) kurang dari tamu Indonesia check-out ({$row['tamu_berangkat_indonesia']}). Diperbaiki dengan mengurangi tamu Indonesia check-out di tanggal " . ($tanggal - 1) . " menjadi {$prevRow['tamu_berangkat_indonesia']} (dengan buffer -$buffer).";
                $this->recalculateAutomaticColumns($data, $rowIndex);
            }
            $fixed = true;
        }

        // Aturan Validasi 5: Tamu kemarin (kolom 7 + kolom 8) >= Kamar digunakan kemarin (kolom 4)
        $totalTamuKemarin = $row['tamu_kemarin_asing'] + $row['tamu_kemarin_indonesia'];
        if ($totalTamuKemarin < $row['kamar_digunakan_kemarin']) {
            $diff = $row['kamar_digunakan_kemarin'] - $totalTamuKemarin + $buffer; // Tambah buffer
            if ($isFirstRow) {
                $row['tamu_kemarin_asing'] += ceil($diff / 2);
                $row['tamu_kemarin_indonesia'] += floor($diff / 2);
                $messages[] = "Tanggal $tanggal: Total tamu kemarin ($totalTamuKemarin) kurang dari kamar digunakan kemarin ({$row['kamar_digunakan_kemarin']}). Diperbaiki dengan menambah tamu kemarin menjadi: Tamu Kemarin Asing: {$row['tamu_kemarin_asing']}, Tamu Kemarin Indonesia: {$row['tamu_kemarin_indonesia']} (dengan buffer +$buffer).";
            } else {
                $newTamuBerangkatAsing = $row['tamu_berangkat_asing'] - ceil($diff / 2);
                $newTamuBerangkatIndonesia = $row['tamu_berangkat_indonesia'] - floor($diff / 2);
                $row['tamu_berangkat_asing'] = max(0, $newTamuBerangkatAsing);
                $row['tamu_berangkat_indonesia'] = max(0, $newTamuBerangkatIndonesia);
                $messages[] = "Tanggal $tanggal: Total tamu kemarin ($totalTamuKemarin) kurang dari kamar digunakan kemarin ({$row['kamar_digunakan_kemarin']}). Diperbaiki dengan mengurangi tamu berangkat menjadi: Tamu Berangkat Asing: {$row['tamu_berangkat_asing']}, Tamu Berangkat Indonesia: {$row['tamu_berangkat_indonesia']} (dengan buffer -$buffer).";
            }
            $fixed = true;
        }

        // Aturan Validasi 6: Tamu baru datang hari ini (kolom 9 + kolom 10) >= Kamar baru dimasuki (kolom 5)
        $totalTamuBaru = $row['tamu_baru_datang_asing'] + $row['tamu_baru_datang_indonesia'];
        if ($totalTamuBaru < $row['kamar_baru_dimasuki']) {
            $row['kamar_baru_dimasuki'] = max(0, $totalTamuBaru - $buffer); // Kurangi dengan buffer
            $messages[] = "Tanggal $tanggal: Total tamu baru datang ($totalTamuBaru) kurang dari kamar baru dimasuki ({$row['kamar_baru_dimasuki']}). Diperbaiki dengan mengurangi kamar baru dimasuki menjadi {$row['kamar_baru_dimasuki']} (dengan buffer -$buffer).";
            $fixed = true;
        }

        $message = implode("\n", $messages);
        return ['row' => $row, 'fixed' => $fixed, 'message' => $message];
    }
}
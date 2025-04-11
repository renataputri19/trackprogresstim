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
            // Membersihkan data: ganti spasi berulang dengan satu spasi, lalu split menggunakan tab atau spasi berulang
            $cols = preg_split('/\t|\s+/', trim($row), -1, PREG_SPLIT_NO_EMPTY);
            if (count($cols) < 12) continue; // Pastikan ada 12 kolom

            // Parse kolom menjadi integer dan pastikan tidak ada nilai negatif
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

        // Jika tidak ada data yang valid, kembalikan error
        if (empty($parsedData)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data valid yang dapat diproses.',
            ], 400);
        }

        // Simpan data asli sebelum perhitungan otomatis untuk perbandingan
        $originalData = $parsedData;

        // Inisialisasi data yang akan divalidasi
        $validatedData = $parsedData;

        // Hitung ulang kolom otomatis untuk semua baris
        $this->recalculateAutomaticColumns($validatedData);

        // Validasi aturan untuk semua baris hingga tidak ada pelanggaran lagi
        $isValidationFixed = false;
        $validationMessages = [];
        $maxIterations = 100; // Batasi iterasi untuk mencegah infinite loop
        $iteration = 0;

        while ($iteration < $maxIterations) {
            $iteration++;
            $fixedThisIteration = false;
            $messagesThisIteration = [];

            // Validasi semua aturan untuk setiap baris
            for ($i = 0; $i < count($validatedData); $i++) {
                $row = $validatedData[$i];
                $isFirstRow = ($i == 0);

                // Validasi semua aturan (1 hingga 7)
                $result = $this->validateRow($row, $i + 1, $isFirstRow, $validatedData, $i);
                $row = $result['row'];
                $validatedData[$i] = $row;

                if ($result['fixed']) {
                    $fixedThisIteration = true;
                    $isValidationFixed = true;
                    $messagesThisIteration[] = $result['message'];
                    // Hitung ulang kolom otomatis untuk baris berikutnya
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

            // Tambahkan pesan dari iterasi ini
            $validationMessages = array_merge($validationMessages, $messagesThisIteration, $negativeFixMessagesThisIteration);

            // Jika tidak ada perbaikan pada iterasi ini, keluar dari loop
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

            // Verifikasi kolom 7 (tamu_kemarin_asing)
            $expectedTamuKemarinAsing = $prevRow['tamu_kemarin_asing'] + $prevRow['tamu_baru_datang_asing'] - $prevRow['tamu_berangkat_asing'];
            $expectedTamuKemarinAsing = max(0, $expectedTamuKemarinAsing);
            if ($row['tamu_kemarin_asing'] != $expectedTamuKemarinAsing) {
                $consistencyMessages[] = "Tanggal {$row['tanggal']}: Kolom Tamu Kemarin Asing tidak konsisten. Diharapkan $expectedTamuKemarinAsing, tetapi ditemukan {$row['tamu_kemarin_asing']}. Diperbaiki.";
                $validatedData[$i]['tamu_kemarin_asing'] = $expectedTamuKemarinAsing;
                $consistencyFixed = true;
            }

            // Verifikasi kolom 8 (tamu_kemarin_indonesia)
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
            // Hitung ulang kolom otomatis untuk memastikan konsistensi setelah perbaikan
            $this->recalculateAutomaticColumns($validatedData);
        }

        // Bandingkan data asli dengan data setelah validasi untuk menandai perubahan
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

        // Tambahkan informasi tentang perhitungan otomatis dalam pesan
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

        // Gabungkan semua pesan
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

    // Fungsi untuk menghitung ulang kolom otomatis
    private function recalculateAutomaticColumns(&$data, $startIndex = 0)
    {
        for ($i = $startIndex; $i < count($data); $i++) {
            $row = $data[$i];

            // Hitung Kamar Digunakan Kemarin (kolom 4)
            if ($i == 0) {
                $row['kamar_digunakan_kemarin'] = $row['kamar_digunakan_kemarin'];
            } else {
                $prevRow = $data[$i - 1];
                $kamarDigunakanKemarin = $prevRow['kamar_digunakan_kemarin'] + $prevRow['kamar_baru_dimasuki'] - $prevRow['kamar_ditinggalkan'];
                $row['kamar_digunakan_kemarin'] = max(0, $kamarDigunakanKemarin);
            }

            // Hitung Tamu Kemarin Asing (kolom 7)
            if ($i == 0) {
                $row['tamu_kemarin_asing'] = $row['tamu_kemarin_asing'];
            } else {
                $prevRow = $data[$i - 1];
                $tamuKemarinAsing = $prevRow['tamu_kemarin_asing'] + $prevRow['tamu_baru_datang_asing'] - $prevRow['tamu_berangkat_asing'];
                $row['tamu_kemarin_asing'] = max(0, $tamuKemarinAsing);
            }

            // Hitung Tamu Kemarin Indonesia (kolom 8)
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

    // Fungsi untuk memeriksa pelanggaran aturan 3 dan 4
    private function checkRule3And4Violations($row, $nextRow)
    {
        $violations = 0;

        // Aturan 3: Tamu asing kemarin (kolom 7) >= Tamu asing check-out (kolom 11) di baris berikutnya
        if (isset($nextRow) && $nextRow['tamu_kemarin_asing'] < $row['tamu_berangkat_asing']) {
            $violations += $row['tamu_berangkat_asing'] - $nextRow['tamu_kemarin_asing'];
        }

        // Aturan 4: Tamu Indonesia kemarin (kolom 8) >= Tamu Indonesia check-out (kolom 12) di baris berikutnya
        if (isset($nextRow) && $nextRow['tamu_kemarin_indonesia'] < $row['tamu_berangkat_indonesia']) {
            $violations += $row['tamu_berangkat_indonesia'] - $nextRow['tamu_kemarin_indonesia'];
        }

        return $violations;
    }

    // Validasi semua aturan (1 hingga 7)
    private function validateRow($row, $tanggal, $isFirstRow, &$data, $rowIndex)
    {
        $fixed = false;
        $messages = [];

        // Aturan Validasi 1: Jumlah kamar tersedia (kolom 2) >= Kamar digunakan kemarin (kolom 4)
        if ($row['jumlah_kamar_tersedia'] < $row['kamar_digunakan_kemarin']) {
            if ($isFirstRow) {
                $row['kamar_digunakan_kemarin'] = $row['jumlah_kamar_tersedia'];
                $messages[] = "Tanggal $tanggal: Jumlah kamar tersedia ({$row['jumlah_kamar_tersedia']}) kurang dari kamar digunakan kemarin. Diperbaiki dengan mengurangi kamar digunakan kemarin menjadi {$row['kamar_digunakan_kemarin']}.";
            } else {
                $prevRowIndex = $rowIndex - 1;
                $prevRow = $data[$prevRowIndex];
                $excess = $row['kamar_digunakan_kemarin'] - $row['jumlah_kamar_tersedia'];
                $prevRow['kamar_ditinggalkan'] += $excess;
                $data[$prevRowIndex] = $prevRow;
                $messages[] = "Tanggal $tanggal: Jumlah kamar tersedia ({$row['jumlah_kamar_tersedia']}) kurang dari kamar digunakan kemarin ({$row['kamar_digunakan_kemarin']}). Diperbaiki dengan membesarkan kamar ditinggalkan di tanggal " . ($tanggal - 1) . " menjadi {$prevRow['kamar_ditinggalkan']}.";
            }
            $fixed = true;
        }

        // Aturan Validasi 2: Kamar digunakan kemarin (kolom 4) >= Kamar ditinggalkan hari ini (kolom 6)
        if ($row['kamar_digunakan_kemarin'] < $row['kamar_ditinggalkan']) {
            if ($isFirstRow) {
                $row['kamar_digunakan_kemarin'] = $row['kamar_ditinggalkan'];
                $messages[] = "Tanggal $tanggal: Kamar digunakan kemarin ({$row['kamar_digunakan_kemarin']}) kurang dari kamar ditinggalkan. Diperbaiki dengan menambah kamar digunakan kemarin menjadi {$row['kamar_digunakan_kemarin']}.";
            } else {
                $row['kamar_ditinggalkan'] = $row['kamar_digunakan_kemarin'];
                $messages[] = "Tanggal $tanggal: Kamar digunakan kemarin ({$row['kamar_digunakan_kemarin']}) kurang dari kamar ditinggalkan. Diperbaiki dengan mengurangi kamar ditinggalkan menjadi {$row['kamar_ditinggalkan']}.";
            }
            $fixed = true;
        }

        // Aturan Validasi 7: Tamu berangkat hari ini (kolom 11 + kolom 12) >= Kamar ditinggalkan (kolom 6)
        $totalTamuBerangkat = $row['tamu_berangkat_asing'] + $row['tamu_berangkat_indonesia'];
        if ($totalTamuBerangkat < $row['kamar_ditinggalkan']) {
            $diff = $row['kamar_ditinggalkan'] - $totalTamuBerangkat;
            $nextRow = isset($data[$rowIndex + 1]) ? $data[$rowIndex + 1] : null;

            if ($isFirstRow) {
                // Untuk baris pertama, tambah tamu kemarin
                $row['tamu_kemarin_asing'] += ceil($diff / 2);
                $row['tamu_kemarin_indonesia'] += floor($diff / 2);
                $messages[] = "Tanggal $tanggal: Total tamu berangkat ($totalTamuBerangkat) kurang dari kamar ditinggalkan ({$row['kamar_ditinggalkan']}). Diperbaiki dengan menambah tamu kemarin menjadi: Tamu Kemarin Asing: {$row['tamu_kemarin_asing']}, Tamu Kemarin Indonesia: {$row['tamu_kemarin_indonesia']}.";
            } else {
                // Tentukan rentang nilai untuk total tamu berangkat
                $minTotal = $row['kamar_ditinggalkan'];
                $maxTotal = $minTotal + 3; // Coba hingga +3 dari nilai minimum
                $bestTotal = $minTotal;
                $bestViolations = PHP_INT_MAX;
                $bestAsing = 0;
                $bestIndonesia = 0;

                // Iterasi setiap kemungkinan total tamu berangkat dalam rentang
                for ($total = $minTotal; $total <= $maxTotal; $total++) {
                    $extra = $total - $totalTamuBerangkat;
                    $newTamuBerangkatAsing = $row['tamu_berangkat_asing'] + ceil($extra / 2);
                    $newTamuBerangkatIndonesia = $row['tamu_berangkat_indonesia'] + floor($extra / 2);

                    // Simpan nilai sementara untuk memeriksa pelanggaran
                    $tempRow = $row;
                    $tempRow['tamu_berangkat_asing'] = $newTamuBerangkatAsing;
                    $tempRow['tamu_berangkat_indonesia'] = $newTamuBerangkatIndonesia;

                    // Hitung ulang kolom otomatis untuk baris berikutnya
                    $tempData = $data;
                    $tempData[$rowIndex] = $tempRow;
                    $this->recalculateAutomaticColumns($tempData, $rowIndex + 1);
                    $tempNextRow = isset($tempData[$rowIndex + 1]) ? $tempData[$rowIndex + 1] : null;

                    // Periksa pelanggaran aturan 3 dan 4
                    $violations = $this->checkRule3And4Violations($tempRow, $tempNextRow);

                    // Simpan nilai terbaik berdasarkan jumlah pelanggaran
                    if ($violations < $bestViolations) {
                        $bestViolations = $violations;
                        $bestTotal = $total;
                        $bestAsing = $newTamuBerangkatAsing;
                        $bestIndonesia = $newTamuBerangkatIndonesia;
                    }

                    // Jika tidak ada pelanggaran, kita bisa berhenti
                    if ($violations == 0) {
                        break;
                    }
                }

                // Terapkan nilai terbaik
                $row['tamu_berangkat_asing'] = $bestAsing;
                $row['tamu_berangkat_indonesia'] = $bestIndonesia;
                $messages[] = "Tanggal $tanggal: Total tamu berangkat ($totalTamuBerangkat) kurang dari kamar ditinggalkan ({$row['kamar_ditinggalkan']}). Diperbaiki dengan mencoba rentang nilai, dipilih total $bestTotal: Tamu Berangkat Asing: {$row['tamu_berangkat_asing']}, Tamu Berangkat Indonesia: {$row['tamu_berangkat_indonesia']}.";
            }
            $fixed = true;
        }

        // Aturan Validasi 3: Tamu asing kemarin (kolom 7) >= Tamu asing check-out (kolom 11)
        if ($row['tamu_kemarin_asing'] < $row['tamu_berangkat_asing']) {
            if ($isFirstRow) {
                $row['tamu_kemarin_asing'] = $row['tamu_berangkat_asing'];
                $messages[] = "Tanggal $tanggal: Tamu asing kemarin ({$row['tamu_kemarin_asing']}) kurang dari tamu asing check-out ({$row['tamu_berangkat_asing']}). Diperbaiki dengan menambah tamu asing kemarin menjadi {$row['tamu_kemarin_asing']}.";
            } else {
                $prevRowIndex = $rowIndex - 1;
                $prevRow = $data[$prevRowIndex];
                $deficit = $row['tamu_berangkat_asing'] - $row['tamu_kemarin_asing'];
                $prevRow['tamu_berangkat_asing'] -= $deficit;
                $data[$prevRowIndex] = $prevRow;
                $messages[] = "Tanggal $tanggal: Tamu asing kemarin ({$row['tamu_kemarin_asing']}) kurang dari tamu asing check-out ({$row['tamu_berangkat_asing']}). Diperbaiki dengan mengurangi tamu asing check-out di tanggal " . ($tanggal - 1) . " menjadi {$prevRow['tamu_berangkat_asing']}.";
                // Hitung ulang kolom otomatis mulai dari baris saat ini
                $this->recalculateAutomaticColumns($data, $rowIndex);
            }
            $fixed = true;
        }

        // Aturan Validasi 4: Tamu Indonesia kemarin (kolom 8) >= Tamu Indonesia check-out (kolom 12)
        if ($row['tamu_kemarin_indonesia'] < $row['tamu_berangkat_indonesia']) {
            if ($isFirstRow) {
                $row['tamu_kemarin_indonesia'] = $row['tamu_berangkat_indonesia'];
                $messages[] = "Tanggal $tanggal: Tamu Indonesia kemarin ({$row['tamu_kemarin_indonesia']}) kurang dari tamu Indonesia check-out ({$row['tamu_berangkat_indonesia']}). Diperbaiki dengan menambah tamu Indonesia kemarin menjadi {$row['tamu_kemarin_indonesia']}.";
            } else {
                $prevRowIndex = $rowIndex - 1;
                $prevRow = $data[$prevRowIndex];
                $deficit = $row['tamu_berangkat_indonesia'] - $row['tamu_kemarin_indonesia'];
                $prevRow['tamu_berangkat_indonesia'] -= $deficit;
                $data[$prevRowIndex] = $prevRow;
                $messages[] = "Tanggal $tanggal: Tamu Indonesia kemarin ({$row['tamu_kemarin_indonesia']}) kurang dari tamu Indonesia check-out ({$row['tamu_berangkat_indonesia']}). Diperbaiki dengan mengurangi tamu Indonesia check-out di tanggal " . ($tanggal - 1) . " menjadi {$prevRow['tamu_berangkat_indonesia']}.";
                // Hitung ulang kolom otomatis mulai dari baris saat ini
                $this->recalculateAutomaticColumns($data, $rowIndex);
            }
            $fixed = true;
        }

        // Aturan Validasi 5: Tamu kemarin (kolom 7 + kolom 8) >= Kamar digunakan kemarin (kolom 4)
        $totalTamuKemarin = $row['tamu_kemarin_asing'] + $row['tamu_kemarin_indonesia'];
        if ($totalTamuKemarin < $row['kamar_digunakan_kemarin']) {
            $diff = $row['kamar_digunakan_kemarin'] - $totalTamuKemarin;
            if ($isFirstRow) {
                $row['tamu_kemarin_asing'] += ceil($diff / 2);
                $row['tamu_kemarin_indonesia'] += floor($diff / 2);
                $messages[] = "Tanggal $tanggal: Total tamu kemarin ($totalTamuKemarin) kurang dari kamar digunakan kemarin ({$row['kamar_digunakan_kemarin']}). Diperbaiki dengan menambah tamu kemarin menjadi: Tamu Kemarin Asing: {$row['tamu_kemarin_asing']}, Tamu Kemarin Indonesia: {$row['tamu_kemarin_indonesia']}.";
            } else {
                $newTamuBerangkatAsing = $row['tamu_berangkat_asing'] - ceil($diff / 2);
                $newTamuBerangkatIndonesia = $row['tamu_berangkat_indonesia'] - floor($diff / 2);
                $row['tamu_berangkat_asing'] = max(0, $newTamuBerangkatAsing);
                $row['tamu_berangkat_indonesia'] = max(0, $newTamuBerangkatIndonesia);
                $messages[] = "Tanggal $tanggal: Total tamu kemarin ($totalTamuKemarin) kurang dari kamar digunakan kemarin ({$row['kamar_digunakan_kemarin']}). Diperbaiki dengan mengurangi tamu berangkat menjadi: Tamu Berangkat Asing: {$row['tamu_berangkat_asing']}, Tamu Berangkat Indonesia: {$row['tamu_berangkat_indonesia']}.";
            }
            $fixed = true;
        }

        // Aturan Validasi 6: Tamu baru datang hari ini (kolom 9 + kolom 10) >= Kamar baru dimasuki (kolom 5)
        $totalTamuBaru = $row['tamu_baru_datang_asing'] + $row['tamu_baru_datang_indonesia'];
        if ($totalTamuBaru < $row['kamar_baru_dimasuki']) {
            $row['kamar_baru_dimasuki'] = $totalTamuBaru;
            $messages[] = "Tanggal $tanggal: Total tamu baru datang ($totalTamuBaru) kurang dari kamar baru dimasuki ({$row['kamar_baru_dimasuki']}). Diperbaiki dengan mengurangi kamar baru dimasuki menjadi {$row['kamar_baru_dimasuki']}.";
            $fixed = true;
        }

        $message = implode("\n", $messages);
        return ['row' => $row, 'fixed' => $fixed, 'message' => $message];
    }
}
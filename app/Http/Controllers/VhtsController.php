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

            // Parse kolom menjadi integer
            $parsedData[] = [
                'tanggal' => (int)($cols[0] ?? 0),
                'jumlah_kamar_tersedia' => (int)($cols[1] ?? 0),
                'jumlah_tempat_tidur_tersedia' => (int)($cols[2] ?? 0),
                'kamar_digunakan_kemarin' => (int)($cols[3] ?? 0),
                'kamar_baru_dimasuki' => (int)($cols[4] ?? 0),
                'kamar_ditinggalkan' => (int)($cols[5] ?? 0),
                'tamu_kemarin_asing' => (int)($cols[6] ?? 0),
                'tamu_kemarin_indonesia' => (int)($cols[7] ?? 0),
                'tamu_baru_datang_asing' => (int)($cols[8] ?? 0),
                'tamu_baru_datang_indonesia' => (int)($cols[9] ?? 0),
                'tamu_berangkat_asing' => (int)($cols[10] ?? 0),
                'tamu_berangkat_indonesia' => (int)($cols[11] ?? 0),
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

        // Hitung ulang kolom otomatis untuk semua baris
        $validatedData = $parsedData;
        for ($i = 0; $i < count($validatedData); $i++) {
            $row = $validatedData[$i];

            // Hitung Kamar Digunakan Kemarin (kolom 4)
            if ($i == 0) {
                $row['kamar_digunakan_kemarin'] = $row['kamar_digunakan_kemarin'];
            } else {
                $prevRow = $validatedData[$i - 1];
                $row['kamar_digunakan_kemarin'] = $prevRow['kamar_digunakan_kemarin'] + $prevRow['kamar_baru_dimasuki'] - $prevRow['kamar_ditinggalkan'];
            }

            // Hitung Tamu Kemarin Asing (kolom 7)
            if ($i == 0) {
                $row['tamu_kemarin_asing'] = $row['tamu_kemarin_asing'];
            } else {
                $prevRow = $validatedData[$i - 1];
                $row['tamu_kemarin_asing'] = $prevRow['tamu_kemarin_asing'] + $prevRow['tamu_baru_datang_asing'] - $prevRow['tamu_berangkat_asing'];
            }

            // Hitung Tamu Kemarin Indonesia (kolom 8)
            if ($i == 0) {
                $row['tamu_kemarin_indonesia'] = $row['tamu_kemarin_indonesia'];
            } else {
                $prevRow = $validatedData[$i - 1];
                $row['tamu_kemarin_indonesia'] = $prevRow['tamu_kemarin_indonesia'] + $prevRow['tamu_baru_datang_indonesia'] - $prevRow['tamu_berangkat_indonesia'];
            }

            $validatedData[$i] = $row;
        }

        // Validasi aturan untuk setiap baris
        $isValidationFixed = false;
        $validationMessages = []; // Kumpulkan semua pesan validasi
        for ($i = 0; $i < count($validatedData); $i++) {
            $row = $validatedData[$i];

            // Validasi aturan untuk baris ini
            $result = $this->validateRow($row, $i + 1, $i == 0);
            $row = $result['row'];
            $validatedData[$i] = $row;

            // Jika ada perbaikan, tambahkan pesan ke daftar
            if ($result['fixed']) {
                $isValidationFixed = true;
                $validationMessages[] = $result['message'];

                // Setelah perbaikan, hitung ulang kolom otomatis untuk baris berikutnya (jika ada)
                for ($j = $i + 1; $j < count($validatedData); $j++) {
                    $nextRow = $validatedData[$j];

                    // Hitung ulang Kamar Digunakan Kemarin (kolom 4)
                    $prevRow = $validatedData[$j - 1];
                    $nextRow['kamar_digunakan_kemarin'] = $prevRow['kamar_digunakan_kemarin'] + $prevRow['kamar_baru_dimasuki'] - $prevRow['kamar_ditinggalkan'];

                    // Hitung ulang Tamu Kemarin Asing (kolom 7)
                    $nextRow['tamu_kemarin_asing'] = $prevRow['tamu_kemarin_asing'] + $prevRow['tamu_baru_datang_asing'] - $prevRow['tamu_berangkat_asing'];

                    // Hitung ulang Tamu Kemarin Indonesia (kolom 8)
                    $nextRow['tamu_kemarin_indonesia'] = $prevRow['tamu_kemarin_indonesia'] + $prevRow['tamu_baru_datang_indonesia'] - $prevRow['tamu_berangkat_indonesia'];

                    $validatedData[$j] = $nextRow;
                }
            }
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

        // Gabungkan semua pesan validasi
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

    private function validateRow($row, $tanggal, $isFirstRow)
    {
        $fixed = false;
        $messages = []; // Kumpulkan semua pesan validasi untuk baris ini

        // Aturan Validasi 1: Jumlah kamar tersedia (kolom 2) >= Kamar digunakan kemarin (kolom 4)
        if ($row['jumlah_kamar_tersedia'] < $row['kamar_digunakan_kemarin']) {
            if ($isFirstRow) {
                // Baris pertama: Ubah kolom 4 (Kamar Digunakan Kemarin)
                $row['kamar_digunakan_kemarin'] = $row['jumlah_kamar_tersedia'];
                $messages[] = "Tanggal $tanggal: Jumlah kamar tersedia ({$row['jumlah_kamar_tersedia']}) kurang dari kamar digunakan kemarin. Diperbaiki dengan mengurangi kamar digunakan kemarin.";
            } else {
                // Baris berikutnya: Ubah kolom 6 (Kamar Ditinggalkan)
                $row['kamar_ditinggalkan'] = $row['kamar_digunakan_kemarin'];
                $messages[] = "Tanggal $tanggal: Jumlah kamar tersedia ({$row['jumlah_kamar_tersedia']}) kurang dari kamar digunakan kemarin. Diperbaiki dengan mengurangi kamar ditinggalkan.";
            }
            $fixed = true;
        }

        // Aturan Validasi 2: Kamar digunakan kemarin (kolom 4) >= Kamar ditinggalkan hari ini (kolom 6)
        if ($row['kamar_digunakan_kemarin'] < $row['kamar_ditinggalkan']) {
            if ($isFirstRow) {
                // Baris pertama: Ubah kolom 4 (Kamar Digunakan Kemarin)
                $row['kamar_digunakan_kemarin'] = $row['kamar_ditinggalkan'];
                $messages[] = "Tanggal $tanggal: Kamar digunakan kemarin ({$row['kamar_digunakan_kemarin']}) kurang dari kamar ditinggalkan. Diperbaiki dengan menambah kamar digunakan kemarin.";
            } else {
                // Baris berikutnya: Ubah kolom 6 (Kamar Ditinggalkan)
                $row['kamar_ditinggalkan'] = $row['kamar_digunakan_kemarin'];
                $messages[] = "Tanggal $tanggal: Kamar digunakan kemarin ({$row['kamar_digunakan_kemarin']}) kurang dari kamar ditinggalkan. Diperbaiki dengan mengurangi kamar ditinggalkan.";
            }
            $fixed = true;
        }

        // Aturan Validasi 3: Tamu asing kemarin (kolom 7) >= Tamu asing check-out (kolom 11)
        if ($row['tamu_kemarin_asing'] < $row['tamu_berangkat_asing']) {
            if ($isFirstRow) {
                // Baris pertama: Ubah kolom 7 (Tamu Kemarin Asing)
                $row['tamu_kemarin_asing'] = $row['tamu_berangkat_asing'];
                $messages[] = "Tanggal $tanggal: Tamu asing kemarin ({$row['tamu_kemarin_asing']}) kurang dari tamu asing check-out. Diperbaiki dengan menambah tamu asing kemarin.";
            } else {
                // Baris berikutnya: Ubah kolom 11 (Tamu Berangkat Asing)
                $row['tamu_berangkat_asing'] = $row['tamu_kemarin_asing'];
                $messages[] = "Tanggal $tanggal: Tamu asing kemarin ({$row['tamu_kemarin_asing']}) kurang dari tamu asing check-out. Diperbaiki dengan mengurangi tamu asing check-out.";
            }
            $fixed = true;
        }

        // Aturan Validasi 4: Tamu Indonesia kemarin (kolom 8) >= Tamu Indonesia check-out (kolom 12)
        if ($row['tamu_kemarin_indonesia'] < $row['tamu_berangkat_indonesia']) {
            if ($isFirstRow) {
                // Baris pertama: Ubah kolom 8 (Tamu Kemarin Indonesia)
                $row['tamu_kemarin_indonesia'] = $row['tamu_berangkat_indonesia'];
                $messages[] = "Tanggal $tanggal: Tamu Indonesia kemarin ({$row['tamu_kemarin_indonesia']}) kurang dari tamu Indonesia check-out. Diperbaiki dengan menambah tamu Indonesia kemarin.";
            } else {
                // Baris berikutnya: Ubah kolom 12 (Tamu Berangkat Indonesia)
                $row['tamu_berangkat_indonesia'] = $row['tamu_kemarin_indonesia'];
                $messages[] = "Tanggal $tanggal: Tamu Indonesia kemarin ({$row['tamu_kemarin_indonesia']}) kurang dari tamu Indonesia check-out. Diperbaiki dengan mengurangi tamu Indonesia check-out.";
            }
            $fixed = true;
        }

        // Aturan Validasi 5: Tamu kemarin (kolom 7 + kolom 8) >= Kamar digunakan kemarin (kolom 4)
        $totalTamuKemarin = $row['tamu_kemarin_asing'] + $row['tamu_kemarin_indonesia'];
        if ($totalTamuKemarin < $row['kamar_digunakan_kemarin']) {
            $diff = $row['kamar_digunakan_kemarin'] - $totalTamuKemarin;
            if ($isFirstRow) {
                // Baris pertama: Ubah kolom 7 dan 8 (Tamu Kemarin Asing dan Indonesia)
                $row['tamu_kemarin_asing'] += ceil($diff / 2);
                $row['tamu_kemarin_indonesia'] += floor($diff / 2);
                $messages[] = "Tanggal $tanggal: Total tamu kemarin ($totalTamuKemarin) kurang dari kamar digunakan kemarin. Diperbaiki dengan menambah tamu kemarin.";
            } else {
                // Baris berikutnya: Ubah kolom 11 dan 12 (Tamu Berangkat Asing dan Indonesia)
                $row['tamu_berangkat_asing'] = max(0, $row['tamu_berangkat_asing'] - ceil($diff / 2));
                $row['tamu_berangkat_indonesia'] = max(0, $row['tamu_berangkat_indonesia'] - floor($diff / 2));
                $messages[] = "Tanggal $tanggal: Total tamu kemarin ($totalTamuKemarin) kurang dari kamar digunakan kemarin. Diperbaiki dengan mengurangi tamu berangkat.";
            }
            $fixed = true;
        }

        // Aturan Validasi 6: Tamu baru datang hari ini (kolom 9 + kolom 10) >= Kamar baru dimasuki (kolom 5)
        $totalTamuBaru = $row['tamu_baru_datang_asing'] + $row['tamu_baru_datang_indonesia'];
        if ($totalTamuBaru < $row['kamar_baru_dimasuki']) {
            // Tidak diizinkan mengubah kolom 9 dan 10 (Tamu Baru Datang), jadi kita ubah kolom lain
            if ($isFirstRow) {
                // Baris pertama: Ubah kolom 7 dan 8 (Tamu Kemarin Asing dan Indonesia)
                $diff = $row['kamar_baru_dimasuki'] - $totalTamuBaru;
                $row['tamu_kemarin_asing'] += ceil($diff / 2);
                $row['tamu_kemarin_indonesia'] += floor($diff / 2);
                $messages[] = "Tanggal $tanggal: Total tamu baru datang ($totalTamuBaru) kurang dari kamar baru dimasuki. Diperbaiki dengan menambah tamu kemarin.";
            } else {
                // Baris berikutnya: Ubah kolom 11 dan 12 (Tamu Berangkat Asing dan Indonesia)
                $diff = $row['kamar_baru_dimasuki'] - $totalTamuBaru;
                $row['tamu_berangkat_asing'] = max(0, $row['tamu_berangkat_asing'] - ceil($diff / 2));
                $row['tamu_berangkat_indonesia'] = max(0, $row['tamu_berangkat_indonesia'] - floor($diff / 2));
                $messages[] = "Tanggal $tanggal: Total tamu baru datang ($totalTamuBaru) kurang dari kamar baru dimasuki. Diperbaiki dengan mengurangi tamu berangkat.";
            }
            $fixed = true;
        }

        // Aturan Validasi 7: Tamu berangkat hari ini (kolom 11 + kolom 12) >= Kamar ditinggalkan (kolom 6)
        $totalTamuBerangkat = $row['tamu_berangkat_asing'] + $row['tamu_berangkat_indonesia'];
        if ($totalTamuBerangkat < $row['kamar_ditinggalkan']) {
            if ($isFirstRow) {
                // Baris pertama: Ubah kolom 7 dan 8 (Tamu Kemarin Asing dan Indonesia)
                $diff = $row['kamar_ditinggalkan'] - $totalTamuBerangkat;
                $row['tamu_kemarin_asing'] += ceil($diff / 2);
                $row['tamu_kemarin_indonesia'] += floor($diff / 2);
                $messages[] = "Tanggal $tanggal: Total tamu berangkat ($totalTamuBerangkat) kurang dari kamar ditinggalkan. Diperbaiki dengan menambah tamu kemarin.";
            } else {
                // Baris berikutnya: Ubah kolom 11 dan 12 (Tamu Berangkat Asing dan Indonesia)
                $diff = $row['kamar_ditinggalkan'] - $totalTamuBerangkat;
                $row['tamu_berangkat_asing'] += ceil($diff / 2);
                $row['tamu_berangkat_indonesia'] += floor($diff / 2);
                $messages[] = "Tanggal $tanggal: Total tamu berangkat ($totalTamuBerangkat) kurang dari kamar ditinggalkan. Diperbaiki dengan menambah tamu berangkat.";
            }
            $fixed = true;
        }

        // Gabungkan semua pesan menjadi satu string
        $message = implode("\n", $messages);
        return ['row' => $row, 'fixed' => $fixed, 'message' => $message];
    }
}
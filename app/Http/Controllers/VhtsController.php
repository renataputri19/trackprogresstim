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
        $rows = array_filter(explode("\n", $data));
        $parsedData = [];
        foreach ($rows as $row) {
            $cols = array_map('trim', explode("\t", $row));
            if (count($cols) < 12) continue; // Pastikan ada 12 kolom

            $parsedData[] = [
                'tanggal' => (int)$cols[0],
                'jumlah_kamar_tersedia' => (int)$cols[1],
                'jumlah_tempat_tidur_tersedia' => (int)$cols[2],
                'kamar_digunakan_kemarin' => (int)$cols[3], // Akan dihitung ulang
                'kamar_baru_dimasuki' => (int)$cols[4],
                'kamar_ditinggalkan' => (int)$cols[5],
                'tamu_kemarin_asing' => (int)$cols[6], // Akan dihitung ulang
                'tamu_kemarin_indonesia' => (int)$cols[7], // Akan dihitung ulang
                'tamu_baru_datang_asing' => (int)$cols[8],
                'tamu_baru_datang_indonesia' => (int)$cols[9],
                'tamu_berangkat_asing' => (int)$cols[10],
                'tamu_berangkat_indonesia' => (int)$cols[11],
            ];
        }

        // Hitung ulang kolom yang otomatis dan lakukan validasi
        $validatedData = $parsedData;
        $isValidationFixed = false;
        $validationMessage = '';

        // Simpan data asli sebelum perhitungan otomatis untuk perbandingan
        $originalData = $parsedData;

        // Hitung ulang kolom otomatis untuk semua baris
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

        // Validasi aturan untuk setiap baris, tetapi hanya perbaiki satu pelanggaran
        for ($i = 0; $i < count($validatedData); $i++) {
            $row = $validatedData[$i];

            // Validasi aturan untuk baris ini
            $result = $this->validateRow($row, $i + 1); // Kirim tanggal untuk pesan error
            $row = $result['row'];
            $validatedData[$i] = $row;

            // Jika ada pelanggaran yang diperbaiki, simpan pesan dan hentikan validasi
            if ($result['fixed']) {
                $isValidationFixed = true;
                $validationMessage = $result['message'];

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

                break; // Hentikan loop setelah satu pelanggaran diperbaiki
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

        // Kembalikan data yang sudah divalidasi ke frontend
        $finalMessage = $isValidationFixed ? $validationMessage : 'Tidak ada pelanggaran aturan ditemukan.';
        if ($autoCalcMessage) {
            $finalMessage .= "\n\nPerhitungan Otomatis:\n" . $autoCalcMessage;
        }

        return response()->json([
            'success' => true,
            'message' => $finalMessage,
            'data' => $validatedData,
            'fixed' => $isValidationFixed,
        ]);
    }

    private function validateRow($row, $tanggal)
    {
        $fixed = false;
        $message = '';

        // Aturan Validasi:
        // 1. Jumlah kamar tersedia (kolom 2) >= Kamar digunakan kemarin (kolom 4)
        if ($row['jumlah_kamar_tersedia'] < $row['kamar_digunakan_kemarin']) {
            $diff = $row['kamar_digunakan_kemarin'] - $row['jumlah_kamar_tersedia'];
            $row['tamu_kemarin_asing'] = max(0, $row['tamu_kemarin_asing'] - $diff);
            $row['tamu_kemarin_indonesia'] = max(0, $row['tamu_kemarin_indonesia'] - $diff);
            $row['kamar_digunakan_kemarin'] = $row['jumlah_kamar_tersedia'];
            $fixed = true;
            $message = "Tanggal $tanggal: Jumlah kamar tersedia ({$row['jumlah_kamar_tersedia']}) kurang dari kamar digunakan kemarin. Diperbaiki dengan mengurangi tamu kemarin.";
            return ['row' => $row, 'fixed' => $fixed, 'message' => $message];
        }

        // 2. Kamar digunakan kemarin (kolom 4) >= Kamar ditinggalkan hari ini (kolom 6)
        if ($row['kamar_digunakan_kemarin'] < $row['kamar_ditinggalkan']) {
            $diff = $row['kamar_ditinggalkan'] - $row['kamar_digunakan_kemarin'];
            $row['tamu_berangkat_asing'] = max(0, $row['tamu_berangkat_asing'] - $diff);
            $row['tamu_berangkat_indonesia'] = max(0, $row['tamu_berangkat_indonesia'] - $diff);
            $row['kamar_ditinggalkan'] = $row['kamar_digunakan_kemarin'];
            $fixed = true;
            $message = "Tanggal $tanggal: Kamar digunakan kemarin ({$row['kamar_digunakan_kemarin']}) kurang dari kamar ditinggalkan. Diperbaiki dengan mengurangi tamu berangkat.";
            return ['row' => $row, 'fixed' => $fixed, 'message' => $message];
        }

        // 3. Tamu asing kemarin (kolom 7) >= Tamu asing check-out (kolom 11)
        if ($row['tamu_kemarin_asing'] < $row['tamu_berangkat_asing']) {
            $row['tamu_berangkat_asing'] = $row['tamu_kemarin_asing'];
            $fixed = true;
            $message = "Tanggal $tanggal: Tamu asing kemarin ({$row['tamu_kemarin_asing']}) kurang dari tamu asing check-out. Diperbaiki dengan mengurangi tamu asing check-out.";
            return ['row' => $row, 'fixed' => $fixed, 'message' => $message];
        }

        // 4. Tamu Indonesia kemarin (kolom 8) >= Tamu Indonesia check-out (kolom 12)
        if ($row['tamu_kemarin_indonesia'] < $row['tamu_berangkat_indonesia']) {
            $row['tamu_berangkat_indonesia'] = $row['tamu_kemarin_indonesia'];
            $fixed = true;
            $message = "Tanggal $tanggal: Tamu Indonesia kemarin ({$row['tamu_kemarin_indonesia']}) kurang dari tamu Indonesia check-out. Diperbaiki dengan mengurangi tamu Indonesia check-out.";
            return ['row' => $row, 'fixed' => $fixed, 'message' => $message];
        }

        // 5. Tamu kemarin (kolom 7 + kolom 8) >= Kamar digunakan kemarin (kolom 4)
        $totalTamuKemarin = $row['tamu_kemarin_asing'] + $row['tamu_kemarin_indonesia'];
        if ($totalTamuKemarin < $row['kamar_digunakan_kemarin']) {
            $diff = $row['kamar_digunakan_kemarin'] - $totalTamuKemarin;
            $row['tamu_kemarin_asing'] += ceil($diff / 2);
            $row['tamu_kemarin_indonesia'] += floor($diff / 2);
            $fixed = true;
            $message = "Tanggal $tanggal: Total tamu kemarin ($totalTamuKemarin) kurang dari kamar digunakan kemarin. Diperbaiki dengan menambah tamu kemarin.";
            return ['row' => $row, 'fixed' => $fixed, 'message' => $message];
        }

        // 6. Tamu baru datang hari ini (kolom 9 + kolom 10) >= Kamar baru dimasuki (kolom 5)
        $totalTamuBaru = $row['tamu_baru_datang_asing'] + $row['tamu_baru_datang_indonesia'];
        if ($totalTamuBaru < $row['kamar_baru_dimasuki']) {
            $diff = $row['kamar_baru_dimasuki'] - $totalTamuBaru;
            $row['tamu_baru_datang_asing'] += ceil($diff / 2);
            $row['tamu_baru_datang_indonesia'] += floor($diff / 2);
            $fixed = true;
            $message = "Tanggal $tanggal: Total tamu baru datang ($totalTamuBaru) kurang dari kamar baru dimasuki. Diperbaiki dengan menambah tamu baru datang.";
            return ['row' => $row, 'fixed' => $fixed, 'message' => $message];
        }

        // 7. Tamu berangkat hari ini (kolom 11 + kolom 12) >= Kamar ditinggalkan (kolom 6)
        $totalTamuBerangkat = $row['tamu_berangkat_asing'] + $row['tamu_berangkat_indonesia'];
        if ($totalTamuBerangkat < $row['kamar_ditinggalkan']) {
            $diff = $row['kamar_ditinggalkan'] - $totalTamuBerangkat;
            $row['tamu_berangkat_asing'] += ceil($diff / 2);
            $row['tamu_berangkat_indonesia'] += floor($diff / 2);
            $fixed = true;
            $message = "Tanggal $tanggal: Total tamu berangkat ($totalTamuBerangkat) kurang dari kamar ditinggalkan. Diperbaiki dengan menambah tamu berangkat.";
            return ['row' => $row, 'fixed' => $fixed, 'message' => $message];
        }

        return ['row' => $row, 'fixed' => $fixed, 'message' => $message];
    }
}
@extends('layouts.main')
@section('title', 'Rentak - VHTS')
@section('content')
    <div class="container mt-5 vhts">
        <div class="vhts-header">
            <h2>Rentak - Sistem Validasi VHTS</h2>
        </div>

        <!-- Form untuk paste data -->
        <div class="paste-section mt-4">
            <form id="vhtsForm" method="POST" action="{{ route('vhts.validate') }}">
                @csrf
                <div class="mb-3">
                    <label for="pasteData" class="form-label">Paste Data dari Excel</label>
                    <textarea class="form-control" id="pasteData" rows="5" placeholder="Paste data dari Excel di sini (pisahkan dengan tab)"></textarea>
                </div>
                <button type="button" class="btn vhts-btn vhts-btn-primary" id="loadDataBtn">Tampilkan Data</button>
                <button type="submit" class="btn vhts-btn vhts-btn-success" id="validateBtn" disabled>Validasi</button>
            </form>
        </div>

        <!-- Tabel untuk menampilkan data -->
        <div class="table-section mt-4">
            <table class="table table-bordered table-striped" id="vhtsTable">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jumlah Kamar Tersedia</th>
                        <th>Jumlah Tempat Tidur Tersedia</th>
                        <th>Kamar Digunakan Kemarin</th>
                        <th>Kamar Baru Dimasuki Hari Ini (Check-in)</th>
                        <th>Kamar Ditinggalkan Hari Ini (Check-out)</th>
                        <th>Tamu Kemarin (Asing)</th>
                        <th>Tamu Kemarin (Indonesia)</th>
                        <th>Tamu Baru Datang Hari Ini (Asing)</th>
                        <th>Tamu Baru Datang Hari Ini (Indonesia)</th>
                        <th>Tamu Berangkat Hari Ini (Asing)</th>
                        <th>Tamu Berangkat Hari Ini (Indonesia)</th>
                    </tr>
                </thead>
                <tbody id="vhtsTableBody">
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Pesan hasil validasi (jika ada) -->
        <div id="validationMessage" class="mt-4"></div>
    </div>

    <!-- JavaScript untuk menangani paste data dan mengisi tabel -->
    <script>
        // Simpan data awal untuk perbandingan
        let originalData = [];

        // Fungsi untuk mengisi tabel dengan data
        function fillTable(data, changes = {}) {
            const tbody = document.getElementById('vhtsTableBody');
            tbody.innerHTML = ''; // Kosongkan tabel sebelumnya

            data.forEach((row, rowIndex) => {
                const tr = document.createElement('tr');
                const cols = [
                    row.tanggal,
                    row.jumlah_kamar_tersedia,
                    row.jumlah_tempat_tidur_tersedia,
                    row.kamar_digunakan_kemarin,
                    row.kamar_baru_dimasuki,
                    row.kamar_ditinggalkan,
                    row.tamu_kemarin_asing,
                    row.tamu_kemarin_indonesia,
                    row.tamu_baru_datang_asing,
                    row.tamu_baru_datang_indonesia,
                    row.tamu_berangkat_asing,
                    row.tamu_berangkat_indonesia,
                ];

                const colKeys = [
                    'tanggal',
                    'jumlah_kamar_tersedia',
                    'jumlah_tempat_tidur_tersedia',
                    'kamar_digunakan_kemarin',
                    'kamar_baru_dimasuki',
                    'kamar_ditinggalkan',
                    'tamu_kemarin_asing',
                    'tamu_kemarin_indonesia',
                    'tamu_baru_datang_asing',
                    'tamu_baru_datang_indonesia',
                    'tamu_berangkat_asing',
                    'tamu_berangkat_indonesia',
                ];

                cols.forEach((col, colIndex) => {
                    const td = document.createElement('td');
                    const key = colKeys[colIndex];

                    // Periksa apakah sel ini berubah
                    if (changes[rowIndex] && changes[rowIndex][key]) {
                        td.innerHTML = `<span style="color: red;">${col}</span>`;
                    } else {
                        td.textContent = col;
                    }

                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });
        }

        // Tampilkan data awal yang dipaste
        document.getElementById('loadDataBtn').addEventListener('click', function () {
            const pasteData = document.getElementById('pasteData').value;
            if (!pasteData) {
                alert('Harap paste data terlebih dahulu!');
                return;
            }

            // Parse data dari textarea
            // Bersihkan data: ganti semua tab, spasi berulang, dan newline yang tidak diperlukan
            const cleanedData = pasteData.replace(/\r\n/g, '\n').replace(/\r/g, '\n').trim();
            const rows = cleanedData.split('\n');
            const parsedData = [];
            let skippedRows = [];

            rows.forEach((row, index) => {
                // Membersihkan data: ganti spasi berulang dengan satu spasi, lalu split menggunakan tab atau spasi berulang
                const cols = row.trim().split(/\t|\s+/).filter(col => col !== '');
                if (cols.length < 12) {
                    skippedRows.push({ row: index + 1, reason: `Jumlah kolom kurang (${cols.length} dari 12)` });
                    return; // Lewati baris jika kolom kurang dari 12
                }

                // Parse kolom menjadi integer dan periksa NaN
                const parsedRow = {
                    tanggal: parseInt(cols[0]),
                    jumlah_kamar_tersedia: parseInt(cols[1]),
                    jumlah_tempat_tidur_tersedia: parseInt(cols[2]),
                    kamar_digunakan_kemarin: parseInt(cols[3]),
                    kamar_baru_dimasuki: parseInt(cols[4]),
                    kamar_ditinggalkan: parseInt(cols[5]),
                    tamu_kemarin_asing: parseInt(cols[6]),
                    tamu_kemarin_indonesia: parseInt(cols[7]),
                    tamu_baru_datang_asing: parseInt(cols[8]),
                    tamu_baru_datang_indonesia: parseInt(cols[9]),
                    tamu_berangkat_asing: parseInt(cols[10]),
                    tamu_berangkat_indonesia: parseInt(cols[11]),
                };

                // Periksa apakah ada NaN di kolom yang diparse
                const nanColumns = [];
                Object.entries(parsedRow).forEach(([key, val], colIndex) => {
                    if (isNaN(val)) {
                        nanColumns.push(`${key} (kolom ${colIndex + 1})`);
                    }
                });

                if (nanColumns.length > 0) {
                    skippedRows.push({ row: index + 1, reason: `Data tidak valid di kolom: ${nanColumns.join(', ')}` });
                    return; // Lewati baris jika ada NaN
                }

                parsedData.push(parsedRow);
            });

            // Tampilkan pesan jika ada baris yang dilewati
            const messageDiv = document.getElementById('validationMessage');
            if (skippedRows.length > 0) {
                const skipMessages = skippedRows.map(item => `Baris ${item.row}: ${item.reason}`).join('<br>');
                messageDiv.innerHTML = `<div class="alert alert-warning">${skipMessages}</div>`;
            } else {
                messageDiv.innerHTML = '';
            }

            // Jika tidak ada data yang valid, beri peringatan
            if (parsedData.length === 0) {
                alert('Tidak ada data valid yang dapat ditampilkan. Pastikan data yang dipaste lengkap dan berisi angka.');
                return;
            }

            // Simpan data awal untuk perbandingan
            originalData = parsedData;

            // Isi tabel dengan data awal
            fillTable(parsedData);

            // Aktifkan tombol validasi setelah data dimuat
            document.getElementById('validateBtn').disabled = false;
        });

        // Kirim data ke backend menggunakan AJAX
        document.getElementById('vhtsForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const pasteData = document.getElementById('pasteData').value;

            fetch("{{ route('vhts.validate') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ data: pasteData }),
            })
            .then(response => response.json())
            .then(result => {
                const messageDiv = document.getElementById('validationMessage');
                if (result.success) {
                    messageDiv.innerHTML = '<div class="alert alert-success">' + result.message + '</div>';
                    // Hanya perbarui tabel jika ada perbaikan
                    if (result.fixed) {
                        fillTable(result.data, result.changes);
                    }
                } else {
                    messageDiv.innerHTML = '<div class="alert alert-danger">Validasi gagal: ' + result.message + '</div>';
                }
            })
            .catch(error => {
                const messageDiv = document.getElementById('validationMessage');
                messageDiv.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan: ' + error.message + '</div>';
            });
        });
    </script>

    <style>
        .vhts-btn {
            padding: 10px 20px;
            font-size: 16px;
        }
        .vhts-btn-primary {
            background-color: #007bff;
            color: white;
        }
        .vhts-btn-success {
            background-color: #28a745;
            color: white;
        }
        .table-section {
            overflow-x: auto;
        }
    </style>
@endsection
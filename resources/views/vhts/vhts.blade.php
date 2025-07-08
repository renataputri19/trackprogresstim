@extends('new-homepage.layouts.app')
@section('title', 'BAHTERA Version 1 - RENTAK')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-teal-50">
    <!-- Header Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-teal-600 via-emerald-500 to-teal-600 py-16">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">
                    Sistem Validasi BAHTERA
                </h1>
                <div class="mx-auto mt-6 max-w-2xl text-xl text-teal-100">
                    <p>Version 1 - Sistem Validasi BAHTERA Standar</p>
                    <p class="mt-2 text-lg">Batam Harmonisasi dan Evaluasi Terpadu Rekaman Akomodasi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <div class="overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-gray-200/50">
            <div class="border-b border-gray-200 bg-gradient-to-r from-teal-50 to-emerald-50 px-6 py-4">
                <h2 class="text-2xl font-bold text-gray-900">Sistem Validasi BAHTERA</h2>
                <p class="mt-1 text-sm text-gray-600">Validasi data survei tingkat penghunian kamar hotel</p>
            </div>

            <div class="p-6">

                <!-- Form untuk paste data -->
                <div class="mb-8">
                    <form id="vhtsForm" method="POST" action="{{ route('bahtera.v1.validate') }}">
                        @csrf
                        <div class="mb-6">
                            <label for="pasteData" class="block text-sm font-medium text-gray-700 mb-2">Paste Data dari Excel</label>
                            <textarea
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                                id="pasteData"
                                rows="5"
                                placeholder="Paste data dari Excel di sini (pisahkan dengan tab)"></textarea>
                        </div>
                        <div class="flex gap-4">
                            <button type="button"
                                    class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    id="loadDataBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <path d="M14 2v6h6"/>
                                    <path d="M16 13H8"/>
                                    <path d="M16 17H8"/>
                                    <path d="M10 9H8"/>
                                </svg>
                                Tampilkan Data
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                    id="validateBtn"
                                    disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                    <path d="M9 12l2 2 4-4"/>
                                    <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c1.51 0 2.93.37 4.18 1.03"/>
                                </svg>
                                Validasi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tabel untuk menampilkan data -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300 text-sm" id="vhtsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Tanggal</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Jumlah Kamar Tersedia</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Jumlah Tempat Tidur Tersedia</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Kamar Digunakan Kemarin</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Kamar Baru Dimasuki Hari Ini (Check-in)</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Kamar Ditinggalkan Hari Ini (Check-out)</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Tamu Kemarin (Asing)</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Tamu Kemarin (Indonesia)</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Tamu Baru Datang Hari Ini (Asing)</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Tamu Baru Datang Hari Ini (Indonesia)</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Tamu Berangkat Hari Ini (Asing)</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Tamu Berangkat Hari Ini (Indonesia)</th>
                            </tr>
                        </thead>
                        <tbody id="vhtsTableBody" class="bg-white">
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>

                <!-- Pesan hasil validasi (jika ada) -->
                <div id="validationMessage" class="mt-6"></div>
            </div>
        </div>

        <!-- Back to BAHTERA Main -->
        <div class="mt-8 text-center">
            <a href="{{ route('bahtera.main') }}"
               class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-colors duration-200 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                    <path d="M19 12H5"/>
                    <path d="M12 19l-7-7 7-7"/>
                </svg>
                Kembali ke BAHTERA Main
            </a>
        </div>
    </div>
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

            fetch("{{ route('bahtera.v1.validate') }}", {
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
        /* Custom styles for VHTS table cells */
        #vhtsTable td {
            border: 1px solid #d1d5db;
            padding: 8px 16px;
            font-size: 0.875rem;
        }

        /* Responsive table styling */
        @media (max-width: 768px) {
            #vhtsTable {
                font-size: 0.75rem;
            }
            #vhtsTable th,
            #vhtsTable td {
                padding: 6px 8px;
            }
        }
    </style>
@endsection
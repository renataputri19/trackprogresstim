@extends('new-homepage.layouts.app')
@section('title', 'BAHTERA Version 2 - RENTAK')
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
                    <p>Version 2 - Enhanced dengan Parameter Tambahan</p>
                    <p class="mt-2 text-lg">Batam Harmonisasi dan Evaluasi Terpadu Rekaman Akomodasi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <div class="overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-gray-200/50">
            <div class="border-b border-gray-200 bg-gradient-to-r from-teal-50 to-emerald-50 px-6 py-4">
                <h2 class="text-2xl font-bold text-gray-900">Sistem Validasi BAHTERA Versi 2</h2>
                <p class="mt-1 text-sm text-gray-600">Validasi data survei dengan parameter tambahan dan konfigurasi yang dapat disesuaikan</p>
            </div>

            <div class="p-6">

                <!-- Form untuk paste data -->
                <div class="mb-8">
                    <form id="vhtsForm" method="POST" action="{{ route('bahtera.v2.validate') }}">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                            <div class="space-y-4">
                                <div>
                                    <label for="month" class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                                    <select class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20" id="month" name="month" required>
                                        <option value="1">Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3" selected>Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                                    <input type="number"
                                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                                           id="year"
                                           name="year"
                                           value="2025"
                                           required>
                                </div>
                                <div>
                                    <label for="reference_tpk" class="block text-sm font-medium text-gray-700 mb-2">Referensi TPK (%)</label>
                                    <input type="number"
                                           step="0.01"
                                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                                           id="reference_tpk"
                                           name="reference_tpk"
                                           value="60.00"
                                           required>
                                    <p class="mt-1 text-xs text-gray-500">Masukkan TPK referensi (misalnya, Februari: 60%). Digunakan untuk validasi.</p>
                                </div>
                            </div>
                            <div>
                                <label for="pasteData" class="block text-sm font-medium text-gray-700 mb-2">Paste Data dari Excel</label>
                                <textarea
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                                    id="pasteData"
                                    name="data"
                                    rows="8"
                                    placeholder="Paste data dari Excel di sini (pisahkan dengan tab)"></textarea>
                            </div>
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

                <!-- Tabel untuk data yang dipaste -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300 text-sm" id="vhtsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Tanggal</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Jumlah Kamar Tersedia</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Jumlah Tempat Tidur Tersedia</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Kamar Digunakan Kemarin</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Kamar Baru Dimasuki (Check-in)</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Kamar Ditinggalkan (Check-out)</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Tamu Kemarin (Asing)</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Tamu Kemarin (Indonesia)</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Tamu Baru Datang (Asing)</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Tamu Baru Datang (Indonesia)</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Tamu Berangkat (Asing)</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-900">Tamu Berangkat (Indonesia)</th>
                            </tr>
                        </thead>
                        <tbody id="vhtsTableBody" class="bg-white">
                            <!-- Data diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>

                <!-- Metrik Bulanan dan Pesan Validasi -->
                <div id="metricsSection" class="mt-6"></div>
                <div id="validationMessage" class="mt-6"></div>

                <!-- Tombol Ekspor -->
                <div class="mt-6">
                    <button class="inline-flex items-center rounded-lg bg-teal-600 px-4 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            id="exportBtn"
                            disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7,10 12,15 17,10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Ekspor ke Excel
                    </button>
                </div>
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

    <!-- JavaScript -->
    <script>
        let originalData = [];
        let validatedData = [];

        function fillTable(data, changes = {}) {
            const tbody = document.getElementById('vhtsTableBody');
            tbody.innerHTML = '';

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

        function displayMetrics(metrics) {
            const metricsSection = document.getElementById('metricsSection');
            if (!metrics) return;

            metricsSection.innerHTML = `
                <h4>Metrik Bulanan</h4>
                <ul class="list-group">
                    <li class="list-group-item">TPK: ${metrics.tpk}%</li>
                    <li class="list-group-item">RLMA: ${metrics.rlma} hari</li>
                    <li class="list-group-item">RLMNus: ${metrics.rlmnus} hari</li>
                    <li class="list-group-item">GPR: ${metrics.gpr}</li>
                    <li class="list-group-item">TPTT: ${metrics.tptt}%</li>
                </ul>
            `;
        }

        document.getElementById('loadDataBtn').addEventListener('click', function () {
            const pasteData = document.getElementById('pasteData').value;
            if (!pasteData) {
                alert('Harap paste data terlebih dahulu!');
                return;
            }

            const cleanedData = pasteData.replace(/\r\n/g, '\n').replace(/\r/g, '\n').trim();
            const rows = cleanedData.split('\n');
            const parsedData = [];
            let skippedRows = [];

            rows.forEach((row, index) => {
                const cols = row.trim().split(/\t|\s+/).filter(col => col !== '');
                if (cols.length < 12) {
                    skippedRows.push({ row: index + 1, reason: `Jumlah kolom kurang (${cols.length}/12)` });
                    return;
                }

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

                const nanColumns = Object.entries(parsedRow)
                    .filter(([_, val]) => isNaN(val))
                    .map(([key, _], idx) => `${key} (kolom ${idx + 1})`);

                if (nanColumns.length > 0) {
                    skippedRows.push({ row: index + 1, reason: `Data tidak valid di: ${nanColumns.join(', ')}` });
                    return;
                }

                parsedData.push(parsedRow);
            });

            const messageDiv = document.getElementById('validationMessage');
            if (skippedRows.length > 0) {
                messageDiv.innerHTML = `<div class="alert alert-warning">${skippedRows.map(item => `Baris ${item.row}: ${item.reason}`).join('<br>')}</div>`;
            } else {
                messageDiv.innerHTML = '';
            }

            if (parsedData.length === 0) {
                alert('Tidak ada data valid. Pastikan data lengkap dan berisi angka.');
                return;
            }

            originalData = parsedData;
            fillTable(parsedData);
            document.getElementById('validateBtn').disabled = false;
            document.getElementById('exportBtn').disabled = true;
        });

        document.getElementById('vhtsForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch("{{ route('bahtera.v2.validate') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData,
            })
            .then(response => response.json())
            .then(result => {
                const messageDiv = document.getElementById('validationMessage');
                if (result.success) {
                    messageDiv.innerHTML = `<div class="alert alert-success"><pre>${result.message}</pre></div>`;
                    validatedData = result.data;
                    if (result.fixed) {
                        fillTable(result.data, result.changes);
                    }
                    displayMetrics(result.metrics);
                    document.getElementById('exportBtn').disabled = false;
                } else {
                    messageDiv.innerHTML = `<div class="alert alert-danger">Validasi gagal: ${result.message}</div>`;
                }
            })
            .catch(error => {
                document.getElementById('validationMessage').innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
            });
        });

        document.getElementById('exportBtn').addEventListener('click', function () {
            if (!validatedData.length) return;

            let csvContent = "Tanggal,Jumlah Kamar Tersedia,Jumlah Tempat Tidur Tersedia,Kamar Digunakan Kemarin,Kamar Baru Dimasuki,Kamar Ditinggalkan,Tamu Kemarin Asing,Tamu Kemarin Indonesia,Tamu Baru Datang Asing,Tamu Baru Datang Indonesia,Tamu Berangkat Asing,Tamu Berangkat Indonesia\n";
            validatedData.forEach(row => {
                csvContent += `${row.tanggal},${row.jumlah_kamar_tersedia},${row.jumlah_tempat_tidur_tersedia},${row.kamar_digunakan_kemarin},${row.kamar_baru_dimasuki},${row.kamar_ditinggalkan},${row.tamu_kemarin_asing},${row.tamu_kemarin_indonesia},${row.tamu_baru_datang_asing},${row.tamu_baru_datang_indonesia},${row.tamu_berangkat_asing},${row.tamu_berangkat_indonesia}\n`;
            });

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'bahtera_validated.csv';
            link.click();
        });
    </script>

    <style>
        pre {
            white-space: pre-wrap;
        }

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
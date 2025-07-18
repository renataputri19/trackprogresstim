@extends('new-homepage.layouts.app')
@section('title', 'BAHTERA Version 3 - RENTAK')
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
                    <p>Version 3 - Enhanced dengan Validasi Tamu Asing yang Diperbaiki</p>
                    <p class="mt-2 text-lg">Sistem validasi terbaru dengan logika alur tamu yang konsisten</p>
                    <p class="mt-1 text-base">Batam Harmonisasi dan Evaluasi Terpadu Rekaman Akomodasi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <!-- Important Notice Section -->
        <div class="mb-8 overflow-hidden rounded-xl bg-amber-50 border-2 border-amber-200 shadow-lg">
            <div class="bg-gradient-to-r from-amber-100 to-orange-100 px-6 py-4 border-b border-amber-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600">
                            <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-amber-900">⚠️ Penting: Informasi Penggunaan BAHTERA Version 3</h3>
                </div>
            </div>
            <div class="px-6 py-4">
                <div class="space-y-4 text-sm text-amber-800">
                    <div class="bg-white rounded-lg p-4 border border-amber-200">
                        <h4 class="font-semibold text-amber-900 mb-2">🎯 Tujuan Khusus Version 3:</h4>
                        <p>Version 3 <strong>BUKAN</strong> versi yang "lebih baik" dari V1 atau V2. V3 memiliki fungsi khusus untuk <strong>normalisasi indikator</strong> (RLMA, RLMNus, dan GPR) guna meningkatkan kualitas data secara keseluruhan.</p>
                        
                        <div class="mt-3 bg-gray-50 rounded-lg p-3">
                            <h5 class="font-medium text-gray-800 mb-2">📊 Contoh Normalisasi:</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                                <div>
                                    <p class="font-medium text-red-700 mb-1">Sebelum (Data Abnormal):</p>
                                    <ul class="space-y-1 text-gray-700">
                                        <li>TPK: 528,38%</li>
                                        <li>RLMA: 4,68 hari</li>
                                        <li>RLMNus: 9,30 hari</li>
                                        <li>GPR: 1,02 orang/kamar</li>
                                        <li>TPTT: 339,34%</li>
                                    </ul>
                                </div>
                                <div>
                                    <p class="font-medium text-green-700 mb-1">Sesudah (Ternormalisasi):</p>
                                    <ul class="space-y-1 text-gray-700">
                                        <li>TPK: 91,89%</li>
                                        <li>RLMA: 2,67 hari</li>
                                        <li>RLMNus: 2,83 hari</li>
                                        <li>GPR: 1,28 orang/kamar</li>
                                        <li>TPTT: 74,57%</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg p-4 border border-amber-200">
                         <h4 class="font-semibold text-amber-900 mb-2">⚠️ Keterbatasan Penting:</h4>
                         <p>V3 <strong>TIDAK</strong> melakukan validasi data harian individual. V3 hanya menormalisasi indikator, sementara data harian yang mendasarinya mungkin masih mengandung pelanggaran aturan.</p>
                         
                         <div class="mt-3 bg-yellow-50 rounded-lg p-3 border border-yellow-200">
                             <h5 class="font-medium text-yellow-800 mb-2">🔧 Logika Normalisasi V3:</h5>
                             <ul class="text-xs text-yellow-700 space-y-1">
                                 <li>• <strong>RLMA, RLMNus, dan GPR</strong> ditekan ke rentang <strong>1-3</strong></li>
                                 <li>• Jika ada nilai <strong>0</strong>, sistem akan menambah data (toleransi untuk kasus tidak ada tamu)</li>
                                 <li>• Biasanya terjadi pada <strong>tamu asing</strong> yang tidak ada check-in/check-out</li>
                                 <li>• Penvalidasi harus mentoleransi ini karena sistem dibuat untuk kasus non-zero</li>
                             </ul>
                         </div>
                     </div>
                    
                    <div class="bg-white rounded-lg p-4 border border-amber-200">
                        <h4 class="font-semibold text-amber-900 mb-2">📋 Rekomendasi Alur Kerja:</h4>
                        <p>Setelah menggunakan V3 untuk normalisasi indikator, <strong>gunakan V2 atau V1</strong> untuk validasi data harian yang komprehensif guna memastikan semua aturan validasi diterapkan dengan benar.</p>
                    </div>
                    
                    <div class="bg-white rounded-lg p-4 border border-amber-200">
                        <h4 class="font-semibold text-amber-900 mb-2">🔄 Kasus Penggunaan Optimal:</h4>
                        <p>V3 paling baik digunakan sebagai <strong>langkah preprocessing</strong> sebelum menerapkan validasi yang lebih ketat dengan versi lain.</p>
                    </div>
                    
                    <div class="bg-gradient-to-r from-blue-50 to-teal-50 rounded-lg p-4 border border-blue-200">
                         <h4 class="font-semibold text-blue-900 mb-2">💡 Ringkasan Penggunaan:</h4>
                         <ul class="list-disc list-inside space-y-1 text-blue-800">
                             <li><strong>V1:</strong> Validasi standar untuk data harian</li>
                             <li><strong>V2:</strong> Validasi mendalam dengan parameter tambahan</li>
                             <li><strong>V3:</strong> Normalisasi indikator (gunakan sebelum V1/V2)</li>
                         </ul>
                         
                         <div class="mt-3 bg-green-50 rounded-lg p-3 border border-green-200">
                             <h5 class="font-medium text-green-800 mb-1">✅ Rekomendasi Penting:</h5>
                             <p class="text-xs text-green-700">
                                 <strong>Jika data sudah normal dari awal</strong>, direkomendasikan langsung menggunakan <strong>Version 1 atau Version 2</strong> yang lebih stabil untuk validasi.
                             </p>
                         </div>
                     </div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-gray-200/50">
            <div class="border-b border-gray-200 bg-gradient-to-r from-teal-50 to-emerald-50 px-6 py-4">
                <h2 class="text-2xl font-bold text-gray-900">Sistem Validasi BAHTERA Versi 3</h2>
                <p class="mt-1 text-sm text-gray-600">Validasi data survei dengan logika alur tamu asing yang diperbaiki dan sistem prioritas validasi</p>
                <div class="mt-2 text-xs text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full inline-block">
                    ✨ Fitur Baru: Validasi Konsistensi Alur Tamu Asing
                </div>
            </div>

            <div class="p-6">
                <!-- Form untuk paste data -->
                <div class="mb-8">
                    <form id="vhtsForm" method="POST" action="{{ route('bahtera.v3.validate') }}">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                            <div class="space-y-4">
                                <div>
                                    <label for="month" class="block text-sm font-medium text-gray-700 mb-2">Bulan (Opsional)</label>
                                    <select class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20" id="month" name="month">
                                        <option value="">-- Pilih Bulan (Opsional) --</option>
                                        <option value="1">Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
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
                                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Tahun (Opsional)</label>
                                    <input type="number"
                                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                                           id="year"
                                           name="year"
                                           value="2025"
                                           placeholder="Masukkan tahun">
                                </div>
                                <div>
                                    <label for="reference_tpk" class="block text-sm font-medium text-gray-700 mb-2">Referensi TPK (%) - Opsional</label>
                                    <input type="number"
                                           step="0.01"
                                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                                           id="reference_tpk"
                                           name="reference_tpk"
                                           placeholder="Contoh: 60.00">
                                    <p class="mt-1 text-xs text-gray-500">Masukkan TPK referensi untuk validasi metrik bulanan (opsional).</p>
                                </div>
                            </div>
                            <div>
                                <label for="pasteData" class="block text-sm font-medium text-gray-700 mb-2">Paste Data dari Excel</label>
                                <textarea
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                                    id="pasteData"
                                    name="data"
                                    rows="8"
                                    placeholder="Paste data dari Excel di sini (pisahkan dengan tab)&#10;Format: Tanggal | Kamar Tersedia | Tempat Tidur | Kamar Kemarin | Kamar Baru | Kamar Ditinggalkan | Tamu Kemarin Asing | Tamu Kemarin Indonesia | Tamu Baru Asing | Tamu Baru Indonesia | Tamu Berangkat Asing | Tamu Berangkat Indonesia"
                                    required></textarea>
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
                                Validasi dengan V3 Logic
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
                td.className = 'border border-gray-300 px-4 py-2 text-center';
                td.textContent = col;

                // Highlight changed cells
                if (changes[rowIndex] && changes[rowIndex][colKeys[colIndex]]) {
                    td.className += ' bg-yellow-100 font-semibold';
                    td.title = `Diubah dari ${changes[rowIndex][colKeys[colIndex]].original} ke ${changes[rowIndex][colKeys[colIndex]].new}`;
                }

                tr.appendChild(td);
            });

            tbody.appendChild(tr);
        });
    }

    function displayMetrics(metrics) {
        if (!metrics) return;

        const metricsSection = document.getElementById('metricsSection');
        metricsSection.innerHTML = `
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">Metrik Bulanan</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">${metrics.tpk}%</div>
                        <div class="text-sm text-gray-600">TPK</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">${metrics.rlma}</div>
                        <div class="text-sm text-gray-600">RLMA (hari)</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">${metrics.rlmnus}</div>
                        <div class="text-sm text-gray-600">RLMNus (hari)</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600">${metrics.gpr}</div>
                        <div class="text-sm text-gray-600">GPR</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600">${metrics.tptt}%</div>
                        <div class="text-sm text-gray-600">TPTT</div>
                    </div>
                </div>
            </div>
        `;
    }

    function exportToExcel() {
        if (validatedData.length === 0) {
            alert('Tidak ada data untuk diekspor. Silakan validasi data terlebih dahulu.');
            return;
        }

        const headers = [
            'Tanggal', 'Jumlah Kamar Tersedia', 'Jumlah Tempat Tidur Tersedia',
            'Kamar Digunakan Kemarin', 'Kamar Baru Dimasuki', 'Kamar Ditinggalkan',
            'Tamu Kemarin (Asing)', 'Tamu Kemarin (Indonesia)',
            'Tamu Baru Datang (Asing)', 'Tamu Baru Datang (Indonesia)',
            'Tamu Berangkat (Asing)', 'Tamu Berangkat (Indonesia)'
        ];

        let csvContent = headers.join('\t') + '\n';
        validatedData.forEach(row => {
            const rowData = [
                row.tanggal, row.jumlah_kamar_tersedia, row.jumlah_tempat_tidur_tersedia,
                row.kamar_digunakan_kemarin, row.kamar_baru_dimasuki, row.kamar_ditinggalkan,
                row.tamu_kemarin_asing, row.tamu_kemarin_indonesia,
                row.tamu_baru_datang_asing, row.tamu_baru_datang_indonesia,
                row.tamu_berangkat_asing, row.tamu_berangkat_indonesia
            ];
            csvContent += rowData.join('\t') + '\n';
        });

        const blob = new Blob([csvContent], { type: 'text/plain;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'bahtera_validated_data_v3.txt';
        link.click();
    }

    // Event Listeners
    document.getElementById('loadDataBtn').addEventListener('click', function() {
        const pasteData = document.getElementById('pasteData').value;
        if (!pasteData) {
            alert('Harap paste data terlebih dahulu!');
            return;
        }

        // Parse data dari textarea - menggunakan logic yang sama dengan v1 dan v2
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
                tanggal: parseInt(cols[0]) || 0,
                jumlah_kamar_tersedia: parseInt(cols[1]) || 0,
                jumlah_tempat_tidur_tersedia: parseInt(cols[2]) || 0,
                kamar_digunakan_kemarin: parseInt(cols[3]) || 0,
                kamar_baru_dimasuki: parseInt(cols[4]) || 0,
                kamar_ditinggalkan: parseInt(cols[5]) || 0,
                tamu_kemarin_asing: parseInt(cols[6]) || 0,
                tamu_kemarin_indonesia: parseInt(cols[7]) || 0,
                tamu_baru_datang_asing: parseInt(cols[8]) || 0,
                tamu_baru_datang_indonesia: parseInt(cols[9]) || 0,
                tamu_berangkat_asing: parseInt(cols[10]) || 0,
                tamu_berangkat_indonesia: parseInt(cols[11]) || 0,
            };

            // Validasi apakah ada nilai NaN
            const hasNaN = Object.values(parsedRow).some(value => isNaN(value));
            if (hasNaN) {
                skippedRows.push({ row: index + 1, reason: 'Terdapat nilai yang tidak valid (bukan angka)' });
                return;
            }

            parsedData.push(parsedRow);
        });

        // Tampilkan pesan jika ada baris yang dilewati
        if (skippedRows.length > 0) {
            const skippedMessage = skippedRows.map(skip => `Baris ${skip.row}: ${skip.reason}`).join('\n');
            alert(`Beberapa baris dilewati:\n${skippedMessage}`);
        }

        if (parsedData.length > 0) {
            originalData = parsedData;
            fillTable(parsedData);
            document.getElementById('validateBtn').disabled = false;

            // Tampilkan informasi data yang berhasil dimuat
            const messageDiv = document.getElementById('validationMessage');
            messageDiv.innerHTML = `<div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">📊 Data Berhasil Dimuat</h3>
                <p class="text-blue-800">Berhasil memuat ${parsedData.length} baris data. Klik "Validasi dengan V3 Logic" untuk memulai validasi.</p>
            </div>`;
        } else {
            alert('Tidak ada data valid yang dapat diproses. Pastikan format data benar dan memiliki 12 kolom.');
        }
    });

    document.getElementById('vhtsForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const messageDiv = document.getElementById('validationMessage');

        // Show loading state
        messageDiv.innerHTML = '<div class="alert alert-info">Memproses validasi...</div>';

        fetch('{{ route("bahtera.v3.validate") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                messageDiv.innerHTML = `<div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-green-900 mb-2">✅ Hasil Validasi V3</h3>
                    <pre class="text-sm text-green-800 whitespace-pre-wrap">${result.message}</pre>
                </div>`;

                validatedData = result.data;
                if (result.fixed) {
                    fillTable(result.data, result.changes);
                }
                if (result.metrics) {
                    displayMetrics(result.metrics);
                }
                document.getElementById('exportBtn').disabled = false;
            } else {
                messageDiv.innerHTML = `<div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-red-900 mb-2">❌ Validasi Gagal</h3>
                    <p class="text-red-800">${result.message}</p>
                </div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageDiv.innerHTML = `<div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-red-900 mb-2">❌ Error</h3>
                <p class="text-red-800">Terjadi kesalahan saat memproses validasi.</p>
            </div>`;
        });
    });

    document.getElementById('exportBtn').addEventListener('click', exportToExcel);

    // Auto-enable validate button when data is pasted
    document.getElementById('pasteData').addEventListener('input', function() {
        const hasData = this.value.trim().length > 0;
        document.getElementById('validateBtn').disabled = !hasData;
        if (!hasData) {
            document.getElementById('exportBtn').disabled = true;
            document.getElementById('vhtsTableBody').innerHTML = '';
            document.getElementById('validationMessage').innerHTML = '';
            document.getElementById('metricsSection').innerHTML = '';
        }
    });
</script>
@endsection
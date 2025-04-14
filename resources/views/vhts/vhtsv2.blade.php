@extends('layouts.main')
@section('title', 'Rentak - VHTS - V2')
@section('content')
    <div class="container mt-5 vhts">
        <div class="vhts-header">
            <h2>Rentak - Sistem Validasi VHTS Versi 2</h2>
        </div>

        <!-- Form untuk paste data -->
        <div class="paste-section mt-4">
            <form id="vhtsForm" method="POST" action="{{ route('vhtsv2.validate') }}">
                @csrf
                <div class="mb-3">
                    <label for="month" class="form-label">Bulan</label>
                    <select class="form-control" id="month" name="month" required>
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
                <div class="mb-3">
                    <label for="year" class="form-label">Tahun</label>
                    <input type="number" class="form-control" id="year" name="year" value="2025" required>
                </div>
                <div class="mb-3">
                    <label for="reference_tpk" class="form-label">Referensi TPK (%)</label>
                    <input type="number" step="0.01" class="form-control" id="reference_tpk" name="reference_tpk" value="60.00" required>
                    <small class="form-text text-muted">Masukkan TPK referensi (misalnya, Februari: 60%). Digunakan untuk validasi.</small>
                </div>
                <div class="mb-3">
                    <label for="pasteData" class="form-label">Paste Data dari Excel</label>
                    <textarea class="form-control" id="pasteData" name="data" rows="5" placeholder="Paste data dari Excel di sini (pisahkan dengan tab)"></textarea>
                </div>
                <button type="button" class="btn vhts-btn vhts-btn-primary" id="loadDataBtn">Tampilkan Data</button>
                <button type="submit" class="btn vhts-btn vhts-btn-success" id="validateBtn" disabled>Validasi</button>
            </form>
        </div>

        <!-- Tabel untuk data yang dipaste -->
        <div class="table-section mt-4">
            <table class="table table-bordered table-striped" id="vhtsTable">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jumlah Kamar Tersedia</th>
                        <th>Jumlah Tempat Tidur Tersedia</th>
                        <th>Kamar Digunakan Kemarin</th>
                        <th>Kamar Baru Dimasuki (Check-in)</th>
                        <th>Kamar Ditinggalkan (Check-out)</th>
                        <th>Tamu Kemarin (Asing)</th>
                        <th>Tamu Kemarin (Indonesia)</th>
                        <th>Tamu Baru Datang (Asing)</th>
                        <th>Tamu Baru Datang (Indonesia)</th>
                        <th>Tamu Berangkat (Asing)</th>
                        <th>Tamu Berangkat (Indonesia)</th>
                    </tr>
                </thead>
                <tbody id="vhtsTableBody">
                    <!-- Data diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Metrik Bulanan dan Pesan Validasi -->
        <div id="metricsSection" class="mt-4"></div>
        <div id="validationMessage" class="mt-4"></div>

        <!-- Tombol Ekspor -->
        <div class="export-section mt-4">
            <button class="btn vhts-btn vhts-btn-info" id="exportBtn" disabled>Ekspor ke Excel</button>
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

            fetch("{{ route('vhtsv2.validate') }}", {
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
            link.download = 'vhts_validated.csv';
            link.click();
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
        .vhts-btn-info {
            background-color: #17a2b8;
            color: white;
        }
        .table-section {
            overflow-x: auto;
        }
        pre {
            white-space: pre-wrap;
        }
    </style>
@endsection
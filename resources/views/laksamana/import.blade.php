<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Import Data LAKSAMANA - RENTAK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0f766e;
            --primary-light: #14b8a6;
            --primary-dark: #115e59;
            --secondary-color: #0d9488;
        }
        html, body { width: 100%; max-width: 100%; overflow-x: hidden; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f0fdfa 0%, #f8fafc 100%);
            min-height: 100vh;
        }
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(15, 118, 110, 0.3);
            border-bottom: 3px solid var(--primary-light);
        }
        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: calc(100% - 140px);
            min-width: 0;
        }
        @media (min-width: 768px) { .navbar-brand { font-size: 1.25rem; } }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-bottom: 2px solid #e5e7eb;
            padding: 1.5rem;
            border-radius: 16px 16px 0 0 !important;
        }
        .card-title { color: var(--primary-color); font-weight: 600; margin: 0; }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(15, 118, 110, 0.4);
        }
        .btn { border-radius: 10px; font-weight: 600; transition: all 0.3s ease; }
        .btn:hover { transform: translateY(-2px); }
        .upload-area {
            border: 3px dashed #e5e7eb;
            border-radius: 16px;
            padding: 3rem;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }
        .upload-area:hover, .upload-area.dragover {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
            box-shadow: 0 4px 16px rgba(15, 118, 110, 0.15);
        }
        .upload-icon { font-size: 4rem; color: var(--primary-light); margin-bottom: 1rem; }
        .alert { border-radius: 12px; border: none; }
        .alert-info {
            background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
            border-left: 4px solid var(--primary-color);
            color: var(--primary-dark);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('laksamana.index') }}">
                <i class="fas fa-map-marked-alt me-2"></i>LAKSAMANA - Lokasi dan Klasifikasi Sensus Manajemen Usaha
            </a>
            <a href="{{ route('laksamana.index') }}" class="btn btn-light">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </nav>

    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-file-excel me-2"></i>Import Data LAKSAMANA dari Excel</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('laksamana.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                            @csrf
                            <div class="upload-area" id="uploadArea">
                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                <h5>Drag & Drop file di sini</h5>
                                <p class="text-muted mb-3">atau klik untuk memilih file</p>
                                <input type="file" name="file" id="fileInput" class="d-none" accept=".xlsx,.xls,.csv" required>
                                <div id="fileName" class="mt-3 fw-bold text-primary d-none"></div>
                            </div>
                            @error('file')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror

                            <div class="alert alert-info mt-4">
                                <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i>Format File</h6>
                                <p class="mb-1">File Excel/CSV harus memiliki kolom berikut (header baris pertama):</p>
                                <ul class="mb-0">
                                    <li><strong>nama_usaha</strong> - Nama usaha/bisnis</li>
                                    <li><strong>kecamatan</strong> - Nama kecamatan</li>
                                    <li><strong>kelurahan</strong> - Nama kelurahan</li>
                                    <li><strong>idsbr</strong> (opsional) - ID SBR untuk identifikasi unik</li>
                                    <li><strong>alamat</strong> (opsional) - Alamat usaha</li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('laksamana.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <button type="button" class="btn btn-danger" id="deleteAllBtn">
                                    <i class="fas fa-trash-alt me-2"></i>Hapus Semua Data
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                    <i class="fas fa-upload me-2"></i>Import Data
                                </button>
                            </div>

                            <div class="mt-4 d-none" id="progressContainer">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold">Proses Import</span>
                                    <span id="progressStatus" class="text-muted">Menyiapkan...</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 0%" id="progressBar"></div>
                                </div>
                                <div class="mt-2 small">
                                    <span id="progressDetails"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const fileName = document.getElementById('fileName');
        const submitBtn = document.getElementById('submitBtn');
        const importForm = document.getElementById('importForm');
        const progressContainer = document.getElementById('progressContainer');
        const progressBar = document.getElementById('progressBar');
        const progressStatus = document.getElementById('progressStatus');
        const progressDetails = document.getElementById('progressDetails');
        const deleteAllBtn = document.getElementById('deleteAllBtn');
        const supportsRandomUUID = !!(window.crypto && typeof window.crypto.randomUUID === 'function');
        function generateUUIDv4() {
            if (supportsRandomUUID) return window.crypto.randomUUID();
            // Fallback UUIDv4 generator
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }

        // Delete All Data handler
        deleteAllBtn.addEventListener('click', function () {
            Swal.fire({
                title: 'Hapus Semua Data?',
                html: '<div class="text-start">Tindakan ini akan menghapus seluruh data usaha di tabel LAKSAMANA (sbr_businesses).<br><br><strong>Harap pastikan</strong> Anda memiliki file import terbaru sebelum melanjutkan.</div>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus semua',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33',
            }).then((result) => {
                if (!result.isConfirmed) return;

                Swal.fire({
                    title: 'Memproses...',
                    text: 'Menghapus semua data LAKSAMANA',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                fetch('{{ route('laksamana.delete.all') }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                })
                .then(async (response) => {
                    const ct = response.headers.get('content-type') || '';
                    if (ct.includes('application/json')) {
                        return response.json();
                    }
                    if (response.status === 419 || response.status === 403) {
                        throw new Error('Sesi kedaluwarsa atau CSRF tidak valid. Refresh halaman lalu coba lagi.');
                    }
                    const text = await response.text();
                    const plain = text.replace(/<[^>]*>/g, '').trim();
                    throw new Error(plain || 'Server mengembalikan respon non-JSON saat menghapus data.');
                })
                .then(data => {
                    if (!data.success) throw new Error(data.message || 'Gagal menghapus data');
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message || 'Semua data LAKSAMANA berhasil dihapus' });
                })
                .catch(err => {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: err.message });
                });
            });
        });

        uploadArea.addEventListener('click', () => fileInput.click());
        uploadArea.addEventListener('dragover', (e) => { e.preventDefault(); uploadArea.classList.add('dragover'); });
        uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                updateFileName();
            }
        });
        fileInput.addEventListener('change', updateFileName);

        function updateFileName() {
            if (fileInput.files.length) {
                fileName.textContent = fileInput.files[0].name;
                fileName.classList.remove('d-none');
                submitBtn.disabled = false;
            }
        }

        importForm.addEventListener('submit', function (e) {
            e.preventDefault();

            if (!fileInput.files.length) {
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';

            const formData = new FormData(importForm);
            const clientImportId = generateUUIDv4();
            formData.append('import_id', clientImportId);

            fetch(importForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData,
                credentials: 'same-origin',
            })
            .then(async (response) => {
                const ct = response.headers.get('content-type') || '';
                if (ct.includes('application/json')) {
                    return response.json();
                }
                const text = await response.text();
                const plain = text.replace(/<[^>]*>/g, '').trim();
                throw new Error(plain || 'Server mengembalikan respon non-JSON saat memulai import.');
            })
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Gagal memulai proses import.');
                }

                progressContainer.classList.remove('d-none');
                progressStatus.textContent = 'Sedang diproses...';
                progressBar.style.width = '0%';
                progressBar.classList.add('progress-bar-striped', 'progress-bar-animated');
                progressDetails.textContent = 'Menunggu pembacaan data...';

                const importId = data.import_id;
                startPolling(importId);
            })
            .catch(error => {
                // If the initial request timed out (e.g., 504), we may still have a queued job.
                // Start polling with the client-generated ID to provide feedback.
                progressContainer.classList.remove('d-none');
                progressStatus.textContent = 'Mengatasi timeout...';
                progressBar.classList.add('progress-bar-striped', 'progress-bar-animated');
                progressDetails.textContent = 'Koneksi terputus, mencoba memantau progres import.';

                startPolling(clientImportId);

                // Also show a non-blocking notification
                try { console.warn('Import request error:', error); } catch (_) {}
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Import Data';
            });
        });

        function startPolling(importId) {
            const statusUrl = `{{ url('/laksamana/import/status') }}/${importId}`;

            const intervalId = setInterval(() => {
                fetch(statusUrl, {
                    headers: {
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message || 'Gagal mengambil status import.');
                        }

                        const processed = data.processed || 0;
                        const success = data.success_count || 0;
                        const errors = data.error_count || 0;

                        progressDetails.textContent = `${processed} baris diproses (${success} berhasil, ${errors} gagal)`;

                        if (data.status === 'completed') {
                            clearInterval(intervalId);
                            progressStatus.textContent = 'Selesai';
                            progressBar.classList.remove('progress-bar-animated');
                            progressBar.classList.remove('progress-bar-striped');
                            progressBar.style.width = '100%';
                            submitBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Import Data';

                            if (data.message) {
                                alert(data.message);
                            }
                        } else if (data.status === 'failed') {
                            clearInterval(intervalId);
                            progressStatus.textContent = 'Gagal';
                            progressBar.classList.remove('progress-bar-animated');
                            progressBar.classList.add('bg-danger');
                            progressBar.style.width = '100%';
                            submitBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Import Data';

                            if (data.message) {
                                alert(data.message);
                            }
                        } else {
                            // processing / queued
                            progressStatus.textContent = data.status === 'queued' ? 'Dalam antrian...' : 'Sedang diproses...';

                            // We do not know total rows, so approximate with success count
                            const approximateProgress = Math.min(90, success); // avoid showing 100% too early
                            progressBar.style.width = `${approximateProgress}%`;
                        }
                    })
                    .catch(error => {
                        clearInterval(intervalId);
                        progressStatus.textContent = 'Error';
                        progressBar.classList.remove('progress-bar-animated');
                        progressBar.classList.add('bg-danger');
                        progressBar.style.width = '100%';
                        submitBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Import Data';
                        alert(error.message);
                    });
            }, 2000);
        }
    </script>
</body>
</html>


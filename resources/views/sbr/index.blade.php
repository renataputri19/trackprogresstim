<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SBR Tagging - RENTAK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root { --primary-color: #2563eb; --secondary-color: #1e40af; }
        body { background-color: #f8fafc; min-height: 100vh; display: flex; flex-direction: column; }
        .navbar { background-color: var(--primary-color); padding: 0.75rem 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .navbar-brand { color: white !important; font-weight: 700; font-size: 1.25rem; letter-spacing: 0.2px; }
        .nav-actions { display: flex; align-items: center; gap: 0.5rem; }
        .navbar .btn { border-radius: 9999px; padding: 0.5rem 0.9rem; font-weight: 600; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
        .card-header { background-color: white; border-bottom: 1px solid #e5e7eb; padding: 0.875rem 1rem; border-radius: 12px 12px 0 0 !important; }
        .card-title { color: var(--primary-color); font-weight: 600; margin: 0; font-size: 1rem; }
        #map { height: 350px; width: 100%; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); touch-action: pan-x pan-y; }
        .form-control, .form-select { border-radius: 8px; border: 1px solid #e5e7eb; padding: 0.75rem 1rem; font-size: 16px; /* Prevents zoom on iOS */ }
        .form-control:focus, .form-select:focus { border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .btn { padding: 0.75rem 1.25rem; border-radius: 8px; font-weight: 500; min-height: 44px; /* Touch-friendly min size */ }
        .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }
        .btn-primary:hover { background-color: var(--secondary-color); border-color: var(--secondary-color); }
        .business-list { max-height: 350px; overflow-y: auto; -webkit-overflow-scrolling: touch; }
        .business-item { padding: 0.875rem; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 0.5rem; cursor: pointer; transition: all 0.2s; min-height: 60px; }
        .business-item:hover { border-color: var(--primary-color); background-color: #f0f9ff; }
        .business-item.selected { border-color: var(--primary-color); background-color: #dbeafe; border-width: 2px; }
        .business-item.tagged { border-left: 4px solid #10b981; }
        .business-item.untagged { border-left: 4px solid #f59e0b; }
        .status-badge { font-size: 0.7rem; padding: 0.25rem 0.5rem; border-radius: 4px; white-space: nowrap; }
        .status-aktif { background-color: #d1fae5; color: #065f46; }
        .status-tutup { background-color: #fee2e2; color: #991b1b; }
        .location-accuracy { font-size: 0.875rem; color: #6b7280; margin-top: 0.5rem; }
        .stats-card { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border-radius: 12px; padding: 1rem; margin-bottom: 0.75rem; }
        .stats-number { font-size: 1.5rem; font-weight: 700; }
        .loading-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.8); display: flex; align-items: center; justify-content: center; z-index: 1000; }
        .search-container { position: relative; }
        .search-loading { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); }

        /* Form action buttons: avoid cramping and overflow */
        .form-actions { gap: 0.5rem; }
        .form-actions .btn { min-width: 160px; }

        /* Utility: robust text wrapping for long IDs, addresses, and tokens */
        .text-wrap-break { white-space: normal !important; word-break: break-word !important; overflow-wrap: anywhere !important; }

        /* Workflow step indicator */
        .workflow-step { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem; border-radius: 8px; background: #f0f9ff; border: 1px solid #bfdbfe; margin-bottom: 0.75rem; font-size: 0.875rem; color: #1e40af; }
        .workflow-step .step-number { display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; background: var(--primary-color); color: white; border-radius: 50%; font-size: 0.75rem; font-weight: 600; flex-shrink: 0; }
        .workflow-step.active { background: #dbeafe; border-color: var(--primary-color); }
        .workflow-step.completed { background: #d1fae5; border-color: #10b981; color: #065f46; }
        .workflow-step.completed .step-number { background: #10b981; }

        /* Label required indicator */
        .label-required::after { content: ' *'; color: #dc3545; font-weight: 700; }

        /* Mobile-specific styles */
        @media (max-width: 991.98px) {
            .container-fluid { padding-left: 0.75rem; padding-right: 0.75rem; }
            .my-4 { margin-top: 0.75rem !important; margin-bottom: 0.75rem !important; }
            .card-body { padding: 0.875rem; }
            .card-header { padding: 0.75rem 0.875rem; }
            .stats-card { padding: 0.875rem; }
            .stats-number { font-size: 1.35rem; }
            .stats-card small { font-size: 0.7rem; }

            /* Reorder columns for mobile workflow */
            .row.mobile-reorder { flex-direction: column; }
            .mobile-reorder > .col-lg-4 { order: 1; }
            .mobile-reorder > .col-lg-8 { order: 2; }

            /* Business list adjustments */
            .business-list { max-height: 280px; }
            .business-item { padding: 0.75rem; }
            .business-item .fw-bold { font-size: 0.9rem; word-break: break-word; }
            .business-item small { font-size: 0.75rem; }

            /* Map container adjustments */
            #map { height: 280px; }

            /* Form adjustments */
            .form-label { font-size: 0.875rem; }

            /* Button group stacking on small screens */
            .btn-group-mobile { display: flex; flex-direction: column; gap: 0.5rem; }
            .btn-group-mobile .btn { width: 100%; }
        }

        @media (max-width: 767.98px) {
            .navbar { padding: 0.5rem 0; }
            .navbar-brand { font-size: 1rem; }
            .navbar-brand i { display: none; }
            .navbar .btn-sm { padding: 0.375rem 0.75rem; font-size: 0.75rem; }

            #map { height: 250px; margin-bottom: 0.5rem; }
            .stats-number { font-size: 1.2rem; }
            .business-list { max-height: 240px; }

            /* Stack map action buttons */
            .map-actions { flex-direction: column; gap: 0.5rem; }
            .map-actions .btn-group { width: 100%; }
            .map-actions .btn-group .btn { flex: 1; }

            /* Compact alert */
            .alert { padding: 0.625rem 0.875rem; font-size: 0.875rem; }
            .alert h6 { font-size: 0.9rem; }

            /* Form section stacking */
            .form-row-mobile { flex-direction: column; }
            .form-row-mobile > div { margin-bottom: 1rem; }

            /* Pagination mobile */
            .pagination-mobile { gap: 0.25rem; }
            .pagination-mobile .btn { padding: 0.5rem 0.75rem; font-size: 0.75rem; }
            .pagination-mobile .text-muted { font-size: 0.75rem; }
        }

        @media (max-width: 575.98px) {
            .container-fluid { padding-left: 0.5rem; padding-right: 0.5rem; }
            .card { border-radius: 10px; }
            .card-header { border-radius: 10px 10px 0 0 !important; }
            .card-title { font-size: 0.9rem; }
            .stats-card { border-radius: 10px; padding: 0.75rem; }
            .stats-number { font-size: 1.1rem; }

            #map { height: 220px; border-radius: 6px; }
            .business-list { max-height: 200px; }
            .business-item { padding: 0.625rem; border-radius: 6px; }

            .btn { padding: 0.625rem 1rem; font-size: 0.875rem; }
            .btn-sm { padding: 0.5rem 0.75rem; font-size: 0.75rem; }

            /* Selected business info compact */
            #selectedBusinessInfo { padding: 0.5rem 0.75rem !important; }
            #selectedBusinessInfo h6 { font-size: 0.85rem; word-break: break-word; }
            #selectedBusinessInfo small { font-size: 0.7rem; }

            /* Workflow step compact */
            .workflow-step { padding: 0.375rem 0.5rem; font-size: 0.8rem; }
            .workflow-step .step-number { width: 20px; height: 20px; font-size: 0.65rem; }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            .business-item { padding: 1rem 0.875rem; }
            .business-item:active { background-color: #dbeafe; border-color: var(--primary-color); }
            .btn:active { transform: scale(0.98); }

            /* Larger touch targets */
            .form-select, .form-control { min-height: 48px; }
            .btn { min-height: 48px; }
            .btn-sm { min-height: 40px; }
        }

        /* Landscape phone optimization */
        @media (max-height: 500px) and (orientation: landscape) {
            .business-list { max-height: 150px; }
            #map { height: 180px; }
            .stats-card { padding: 0.5rem; }
            .stats-number { font-size: 1rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('sbr.index') }}">
                <i class="fas fa-map-marker-alt me-2 d-none d-sm-inline"></i>
                <span class="d-none d-md-inline">SBR - Survei Bisnis Registrasi</span>
                <span class="d-md-none">SBR Tagging</span>
            </a>
            <div class="nav-actions">
                @auth
                <a href="{{ route('sbr.import.page') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-file-excel me-1"></i><span class="d-none d-sm-inline">Import Excel</span><span class="d-sm-none">Import</span>
                </a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container-fluid my-3 my-md-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Mobile Workflow Guide - Only visible on mobile -->
        <div class="d-lg-none mb-3">
            <div class="workflow-step" id="step1Indicator">
                <span class="step-number">1</span>
                <span>Pilih usaha dari daftar di bawah</span>
            </div>
        </div>

        <div class="row mobile-reorder">
            <!-- Left Panel: Filters & Search -->
            <div class="col-lg-4 mb-3 mb-lg-4">
                <!-- Stats -->
                <div class="stats-card">
                    <div class="row text-center g-0">
                        <div class="col-4">
                            <div class="stats-number" id="statTotal">0</div>
                            <small>Total</small>
                        </div>
                        <div class="col-4">
                            <div class="stats-number" id="statTagged">0</div>
                            <small>Tagged</small>
                        </div>
                        <div class="col-4">
                            <div class="stats-number" id="statUntagged">0</div>
                            <small>Untagged</small>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="fas fa-filter me-2"></i>Filter</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 g-md-3">
                            <div class="col-12 col-md-6 mb-md-2">
                                <label class="form-label fw-semibold small mb-1">Kecamatan</label>
                                <select class="form-select" id="filterKecamatan">
                                    <option value="">Semua Kecamatan</option>
                                    @foreach($kecamatanList as $kec)
                                        <option value="{{ $kec }}">{{ $kec }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold small mb-1">Kelurahan</label>
                                <select class="form-select" id="filterKelurahan" disabled>
                                    <option value="">Pilih Kecamatan dulu</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <div class="card mb-3">
                    <div class="card-header py-2">
                        <h6 class="card-title mb-0"><i class="fas fa-search me-2"></i>Cari Usaha</h6>
                    </div>
                    <div class="card-body py-2">
                        <div class="search-container">
                            <input type="text" class="form-control" id="searchInput" placeholder="Ketik nama usaha..." autocomplete="off" inputmode="search">
                            <div class="search-loading d-none" id="searchLoading">
                                <i class="fas fa-spinner fa-spin text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business List -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                        <h6 class="card-title mb-0"><i class="fas fa-list me-2"></i>Daftar Usaha</h6>
                        <span class="badge bg-primary" id="resultCount">0 hasil</span>
                    </div>
                    <div class="card-body p-2">
                        <div class="business-list" id="businessList">
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-hand-pointer me-2 d-none d-lg-inline"></i>
                                <i class="fas fa-hand-point-down me-2 d-lg-none"></i>
                                <span class="d-lg-none">Pilih kecamatan untuk melihat daftar usaha</span>
                                <span class="d-none d-lg-inline">Pilih filter untuk menampilkan data</span>
                            </div>
                        </div>
                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-2 px-1 pagination-mobile" id="pagination" style="display: none !important;">
                            <button class="btn btn-sm btn-outline-primary" id="prevPage" disabled>
                                <i class="fas fa-chevron-left"></i><span class="d-none d-sm-inline ms-1">Prev</span>
                            </button>
                            <span class="text-muted small" id="pageInfo">Hal 1</span>
                            <button class="btn btn-sm btn-outline-primary" id="nextPage" disabled>
                                <span class="d-none d-sm-inline me-1">Next</span><i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Map & Form -->
            <div class="col-lg-8">
                <!-- Mobile Workflow Step 2 - Shown when business is selected -->
                <div class="d-lg-none mb-2" id="step2Container" style="display: none !important;">
                    <div class="workflow-step active" id="step2Indicator">
                        <span class="step-number">2</span>
                        <span>Tandai lokasi di peta & pilih status</span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header py-2">
                        <h6 class="card-title mb-0"><i class="fas fa-map-marked-alt me-2"></i>Tagging Lokasi</h6>
                    </div>
                    <div class="card-body">
                        <!-- Selected Business Info -->
                        <div class="alert alert-info mb-3 py-2" id="selectedBusinessInfo" style="display: none;">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start gap-2">
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <h6 class="mb-1 fw-bold text-wrap-break" id="selectedBusinessName" style="max-width: 100%;">-</h6>
                                    <small class="text-muted d-block text-wrap-break">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <span id="selectedBusinessLocation">-</span>
                                    </small>
                                    <small class="text-muted d-block text-wrap-break">
                                        <i class="fas fa-id-card me-1"></i>
                                        <span id="selectedBusinessIdsbr">ID SBR: -</span>
                                    </small>
                                    <small class="text-muted d-block text-wrap-break">
                                        <i class="fas fa-home me-1"></i>
                                        <span id="selectedBusinessAlamat">Alamat: -</span>
                                    </small>
                                </div>
                                <span class="status-badge" id="selectedBusinessStatus"></span>
                            </div>
                        </div>

                        <!-- Map Section -->
                        <div class="mb-3">
                            <!-- Map Instructions & Actions -->
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-2 map-actions">
                                <label class="form-label fw-semibold mb-0 small">
                                    <i class="fas fa-hand-pointer me-1 d-none d-md-inline"></i>
                                    <span class="d-none d-sm-inline">Klik pada peta untuk menandai lokasi</span>
                                    <span class="d-sm-none">Tap peta untuk tandai lokasi</span>
                                </label>
                                <div class="d-flex flex-column flex-md-row w-100 w-md-auto gap-2" style="max-width: 320px;">
                                    <button type="button" id="getLocationBtn" class="btn btn-outline-primary flex-fill" title="Gunakan GPS untuk mendapatkan lokasi">
                                        <i class="fas fa-location-arrow me-1"></i><span class="d-none d-sm-inline">Lokasi Saya</span><span class="d-sm-none">GPS</span>
                                    </button>
                                    <button type="button" id="manualInputBtn" class="btn btn-outline-secondary flex-fill" title="Masukkan koordinat secara manual">
                                        <i class="fas fa-keyboard me-1"></i>Manual
                                    </button>
                                </div>
                            </div>
                            <div id="map"></div>
                            <div class="location-accuracy small" id="locationAccuracy"></div>
                            <!-- Location tips for HTTP users -->
                            @if(!request()->secure() && !in_array(request()->getHost(), ['localhost', '127.0.0.1']))
                            <div class="alert alert-warning mt-2 py-2 px-3 small mb-0">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <span class="d-none d-sm-inline"><strong>Tips:</strong> Untuk menggunakan GPS, akses halaman ini melalui HTTPS. Alternatif: klik langsung pada peta atau gunakan tombol "Manual".</span>
                                <span class="d-sm-none"><strong>Tips:</strong> Untuk GPS, gunakan HTTPS. Atau tap peta langsung.</span>
                            </div>
                            @endif
                        </div>

                        <!-- Form Section -->
                        <form id="taggingForm">
                            <input type="hidden" id="businessId">
                            <div class="row g-2 g-md-3">
                                <div class="col-6 col-md-3">
                                    <label class="form-label fw-semibold small mb-1">Latitude</label>
                                    <input type="text" class="form-control form-control-sm" id="latitude" readonly placeholder="Klik peta">
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="form-label fw-semibold small mb-1">Longitude</label>
                                    <input type="text" class="form-control form-control-sm" id="longitude" readonly placeholder="Klik peta">
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label fw-semibold small mb-1 label-required">Status Usaha</label>
                                    <select class="form-select" id="status" required aria-describedby="statusHelp statusError">
                                        <option value="">Pilih Status</option>
                                        <option value="aktif">✓ Aktif (Beroperasi)</option>
                                        <option value="tutup">✗ Tutup (Tidak Beroperasi)</option>
                                    </select>
                                    <div class="form-text" id="statusHelp">Pilih status usaha (✓ Aktif atau ✗ Tutup) untuk mengaktifkan tombol Simpan.</div>
                                    <div class="invalid-feedback" id="statusError">Status usaha wajib dipilih.</div>
                                </div>
                                <div class="col-12">
                                    <div id="formGuidance" class="alert alert-warning py-2 px-3 small mb-2" role="alert" style="display:none;">
                                        <i class="fas fa-info-circle me-1"></i>
                                        <span id="formGuidanceText">Lengkapi data wajib sebelum menyimpan.</span>
                                    </div>
                                </div>
                                <div class="col-12 d-flex align-items-end">
                                    <div class="d-flex w-100 gap-2 flex-column flex-md-row flex-wrap form-actions">
                                        <button type="button" class="btn btn-outline-secondary w-100" id="clearBtn">
                                            <i class="fas fa-undo me-2"></i>Reset
                                        </button>
                                        <button type="button" class="btn btn-danger w-100" id="deleteBtn">
                                            <i class="fas fa-trash me-2"></i>Delete
                                        </button>
                                        <span class="d-inline-block w-100" id="saveBtnWrapper" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" title="Lengkapi data wajib sebelum menyimpan">
                                            <button type="submit" class="btn btn-primary w-100" id="saveBtn" disabled>
                                                <i class="fas fa-save me-2"></i>Simpan Data
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Manual Coordinate Input Modal -->
    <div class="modal fade" id="manualCoordinateModal" tabindex="-1" aria-labelledby="manualCoordinateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
            <div class="modal-content" style="border: none; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 12px 12px 0 0; padding: 1rem;">
                    <h6 class="modal-title text-white" id="manualCoordinateModalLabel">
                        <i class="fas fa-map-pin me-2"></i>Input Koordinat Manual
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 1rem;">
                    <!-- Instructions Card - Collapsible on mobile -->
                    <div class="alert alert-info border-0 mb-3" style="background: linear-gradient(135deg, #e0f2fe, #f0f9ff); border-radius: 10px; padding: 0.75rem;">
                        <div class="d-flex align-items-center justify-content-between" data-bs-toggle="collapse" data-bs-target="#instructionsCollapse" role="button" style="cursor: pointer;">
                            <h6 class="fw-bold mb-0 small" style="color: var(--primary-color);">
                                <i class="fas fa-lightbulb me-2"></i>Cara Mendapatkan Koordinat
                            </h6>
                            <i class="fas fa-chevron-down d-sm-none text-primary"></i>
                        </div>
                        <div class="collapse show" id="instructionsCollapse">
                            <ol class="mb-0 small mt-2" style="padding-left: 1.25rem; color: #1e40af;">
                                <li>Buka <strong>Google Maps</strong></li>
                                <li>Cari lokasi usaha</li>
                                <li class="d-none d-sm-list-item">Klik kanan pada lokasi (atau tekan lama di HP)</li>
                                <li class="d-sm-none">Tekan lama pada lokasi</li>
                                <li>Salin koordinat ke form</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Coordinate Input Form -->
                    <form id="manualCoordinateForm">
                        <div class="row g-2 g-sm-3">
                            <div class="col-12 col-sm-6">
                                <label for="manualLatInput" class="form-label fw-semibold small mb-1">
                                    <i class="fas fa-arrows-alt-v me-1 text-primary"></i>Latitude
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="manualLatInput"
                                       placeholder="1.04404846"
                                       maxlength="10"
                                       pattern="^-?\d{1,2}\.\d{6,8}$"
                                       autocomplete="off"
                                       inputmode="decimal"
                                       style="font-family: 'Consolas', 'Monaco', monospace; font-size: 1rem;">
                                <div class="form-text small d-none d-sm-block">
                                    Format: <code style="background: #f1f5f9; padding: 2px 4px; border-radius: 4px;">1.04404846</code>
                                </div>
                                <div class="invalid-feedback" id="latitudeError"></div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="manualLngInput" class="form-label fw-semibold small mb-1">
                                    <i class="fas fa-arrows-alt-h me-1 text-primary"></i>Longitude
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="manualLngInput"
                                       placeholder="104.03319729"
                                       maxlength="12"
                                       pattern="^-?\d{1,3}\.\d{6,8}$"
                                       autocomplete="off"
                                       inputmode="decimal"
                                       style="font-family: 'Consolas', 'Monaco', monospace; font-size: 1rem;">
                                <div class="form-text small d-none d-sm-block">
                                    Format: <code style="background: #f1f5f9; padding: 2px 4px; border-radius: 4px;">104.03319729</code>
                                </div>
                                <div class="invalid-feedback" id="longitudeError"></div>
                            </div>
                        </div>

                        <!-- Validation Summary -->
                        <div class="alert alert-danger border-0 d-none mt-3 mb-0 py-2" id="validationSummary" style="border-radius: 10px;">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <span id="validationMessage"></span>
                        </div>

                        <!-- Example Card - Hidden on mobile for space -->
                        <div class="card border-0 mt-3 d-none d-sm-block" style="background: #f8fafc; border-radius: 10px;">
                            <div class="card-body py-2">
                                <h6 class="card-title mb-2" style="color: var(--primary-color); font-size: 0.8rem;">
                                    <i class="fas fa-info-circle me-1"></i>Contoh Koordinat:
                                </h6>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Latitude:</small>
                                        <code style="font-size: 0.8rem;">1.04404846</code>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Longitude:</small>
                                        <code style="font-size: 0.8rem;">104.03319729</code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex flex-column flex-sm-row gap-2" style="border-top: 1px solid #e5e7eb; padding: 1rem;">
                    <button type="button" class="btn btn-outline-secondary w-100 w-sm-auto order-2 order-sm-1" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-primary w-100 w-sm-auto order-1 order-sm-2" id="applyCoordinateBtn">
                        <i class="fas fa-check me-1"></i>Terapkan Koordinat
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // State
        let map, marker, locationCircle;
        let currentPage = 1;
        let lastPage = 1;
        let selectedBusiness = null;
        let searchTimeout = null;
        let saveTooltip = null;

        // Helper function to check if mobile
        function isMobile() {
            return window.innerWidth < 992;
        }

        // Update workflow indicators (mobile only)
        function updateWorkflowIndicators(step) {
            const step1 = document.getElementById('step1Indicator');
            const step2Container = document.getElementById('step2Container');
            const step2 = document.getElementById('step2Indicator');

            if (!step1 || !step2Container) return; // Elements don't exist

            if (step === 1) {
                step1.classList.remove('completed');
                step1.classList.add('active');
                step2Container.style.display = 'none';
            } else if (step === 2) {
                step1.classList.remove('active');
                step1.classList.add('completed');
                step2Container.style.display = 'block';
                step2.classList.add('active');

                // Scroll to map section on mobile when business is selected
                if (isMobile()) {
                    setTimeout(() => {
                        step2Container.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                }
            }
        }

        // Initialize map & tooltips
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltip for disabled save button wrapper
            const saveBtnWrapper = document.getElementById('saveBtnWrapper');
            if (saveBtnWrapper && window.bootstrap) {
                saveTooltip = new bootstrap.Tooltip(saveBtnWrapper, { trigger: 'hover focus' });
            }

            // Initialize map with touch support
            map = L.map('map', {
                tap: true,
                touchZoom: true,
                dragging: true,
                scrollWheelZoom: true
            }).setView([1.0456, 104.0304], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Map click handler
            map.on('click', function(e) {
                if (!selectedBusiness) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih Usaha',
                        text: isMobile() ? 'Pilih usaha dari daftar di atas' : 'Silakan pilih usaha terlebih dahulu dari daftar',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                setLocation(e.latlng.lat, e.latlng.lng);
            });

            // Handle window resize - invalidate map size
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    map.invalidateSize();
                }, 200);
            });

            // Load stats
            loadStats();
        });

        // Set location on map
        function setLocation(lat, lng, accuracy = null) {
            if (marker) map.removeLayer(marker);
            if (locationCircle) map.removeLayer(locationCircle);

            marker = L.marker([lat, lng]).addTo(map);

            if (accuracy) {
                locationCircle = L.circle([lat, lng], { radius: accuracy, fillColor: '#3388ff', fillOpacity: 0.1, color: '#3388ff', opacity: 0.3 }).addTo(map);
                document.getElementById('locationAccuracy').innerHTML = `<i class="fas fa-bullseye me-2"></i>Akurasi: ±${Math.round(accuracy)} meter`;
            } else {
                document.getElementById('locationAccuracy').innerHTML = '';
            }

            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);
            map.setView([lat, lng], 16);
            validateForm();
        }

        // Get current location with improved error handling
        document.getElementById('getLocationBtn').addEventListener('click', function() {
            const btn = this;

            // Check if page is served over HTTPS (required for geolocation)
            if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Koneksi Tidak Aman',
                    html: `
                        <p>Geolocation memerlukan koneksi HTTPS yang aman.</p>
                        <p class="text-muted small">Halaman ini diakses melalui HTTP. Silakan akses melalui HTTPS atau gunakan peta untuk menandai lokasi secara manual.</p>
                    `,
                    confirmButtonText: 'Mengerti',
                    showCancelButton: true,
                    cancelButtonText: 'Input Manual',
                }).then((result) => {
                    if (!result.isConfirmed) {
                        showManualInputDialog();
                    }
                });
                return;
            }

            if (!navigator.geolocation) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak Didukung',
                    html: '<p>Browser Anda tidak mendukung Geolocation.</p><p class="text-muted small">Silakan klik langsung pada peta atau gunakan input koordinat manual.</p>',
                    confirmButtonText: 'Mengerti',
                    showCancelButton: true,
                    cancelButtonText: 'Input Manual',
                }).then((result) => {
                    if (!result.isConfirmed) {
                        showManualInputDialog();
                    }
                });
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Mencari lokasi...';
            document.getElementById('locationAccuracy').innerHTML = '<i class="fas fa-info-circle me-1"></i>Mencari lokasi GPS, mohon tunggu...';

            let watchId = null;
            let hasLocation = false;
            let bestPosition = null;
            let attempts = 0;
            const maxAttempts = 3;

            // Function to handle successful location
            const handleSuccess = (position) => {
                hasLocation = true;
                bestPosition = position;

                // Keep the best (most accurate) position
                if (watchId) {
                    navigator.geolocation.clearWatch(watchId);
                }

                setLocation(position.coords.latitude, position.coords.longitude, position.coords.accuracy);
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-location-arrow me-1"></i>Lokasi Saya';

                Swal.fire({
                    icon: 'success',
                    title: 'Lokasi Ditemukan!',
                    text: `Akurasi: ±${Math.round(position.coords.accuracy)} meter`,
                    timer: 2000,
                    showConfirmButton: false
                });
            };

            // Function to handle errors with detailed messages
            const handleError = (error, isFallback = false) => {
                if (hasLocation) return; // Already got location

                let title = 'Gagal Mendapatkan Lokasi';
                let message = '';
                let showManualOption = true;

                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        title = 'Izin Lokasi Ditolak';
                        message = `
                            <p>Anda telah menolak izin akses lokasi.</p>
                            <p class="text-muted small mt-2"><strong>Cara mengaktifkan:</strong></p>
                            <ul class="text-muted small text-start">
                                <li>Klik ikon gembok/info di address bar</li>
                                <li>Cari pengaturan "Location" atau "Lokasi"</li>
                                <li>Ubah ke "Allow" atau "Izinkan"</li>
                                <li>Refresh halaman ini</li>
                            </ul>
                        `;
                        break;
                    case error.POSITION_UNAVAILABLE:
                        title = 'Lokasi Tidak Tersedia';
                        message = `
                            <p>Tidak dapat menentukan lokasi Anda saat ini.</p>
                            <p class="text-muted small mt-2">Kemungkinan penyebab:</p>
                            <ul class="text-muted small text-start">
                                <li>GPS tidak aktif atau tidak tersedia</li>
                                <li>Sinyal GPS terhalang (dalam gedung)</li>
                                <li>Perangkat tidak memiliki GPS</li>
                            </ul>
                            <p class="text-muted small">Coba keluar ruangan atau gunakan peta manual.</p>
                        `;
                        break;
                    case error.TIMEOUT:
                        if (!isFallback && attempts < maxAttempts) {
                            attempts++;
                            document.getElementById('locationAccuracy').innerHTML =
                                `<i class="fas fa-sync fa-spin me-1"></i>Mencoba lagi (percobaan ${attempts}/${maxAttempts})...`;
                            // Try again with lower accuracy (faster)
                            tryGetLocation(true);
                            return;
                        }
                        title = 'Waktu Habis';
                        message = `
                            <p>Permintaan lokasi membutuhkan waktu terlalu lama.</p>
                            <p class="text-muted small mt-2">Saran:</p>
                            <ul class="text-muted small text-start">
                                <li>Pastikan GPS aktif di perangkat</li>
                                <li>Coba di area terbuka (outdoor)</li>
                                <li>Periksa koneksi internet Anda</li>
                                <li>Gunakan peta untuk menandai lokasi manual</li>
                            </ul>
                        `;
                        break;
                    default:
                        message = `<p>Terjadi kesalahan: ${error.message}</p>`;
                }

                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-location-arrow me-1"></i>Lokasi Saya';
                document.getElementById('locationAccuracy').innerHTML = '';

                Swal.fire({
                    icon: 'error',
                    title: title,
                    html: message,
                    confirmButtonText: 'Tutup',
                    showCancelButton: showManualOption,
                    cancelButtonText: '<i class="fas fa-keyboard me-1"></i>Input Koordinat Manual',
                    cancelButtonColor: '#6c757d',
                }).then((result) => {
                    if (!result.isConfirmed && showManualOption) {
                        showManualInputDialog();
                    }
                });
            };

            // Function to try getting location
            const tryGetLocation = (lowAccuracy = false) => {
                const options = {
                    enableHighAccuracy: !lowAccuracy,
                    timeout: lowAccuracy ? 15000 : 30000, // 30s for high accuracy, 15s for low
                    maximumAge: 0
                };

                navigator.geolocation.getCurrentPosition(handleSuccess, (error) => handleError(error, lowAccuracy), options);
            };

            // Start with high accuracy
            tryGetLocation(false);
        });

        // Manual coordinate input dialog - Bootstrap Modal
        const manualCoordinateModal = new bootstrap.Modal(document.getElementById('manualCoordinateModal'));
        const manualLatInput = document.getElementById('manualLatInput');
        const manualLngInput = document.getElementById('manualLngInput');
        const validationSummary = document.getElementById('validationSummary');
        const validationMessage = document.getElementById('validationMessage');
        const latitudeError = document.getElementById('latitudeError');
        const longitudeError = document.getElementById('longitudeError');
        const applyCoordinateBtn = document.getElementById('applyCoordinateBtn');

        // Coordinate validation function
        function validateCoordinate(value, type) {
            const errors = [];

            // Check if empty
            if (!value || value.trim() === '') {
                return { valid: false, errors: ['Koordinat harus diisi'] };
            }

            // Check for valid number format (including negative)
            const coordRegex = /^-?\d+\.?\d*$/;
            if (!coordRegex.test(value.trim())) {
                return { valid: false, errors: ['Format koordinat tidak valid. Gunakan format desimal (contoh: 1.04404846)'] };
            }

            const numValue = parseFloat(value);

            // Check if it's a valid number
            if (isNaN(numValue)) {
                return { valid: false, errors: ['Koordinat harus berupa angka'] };
            }

            // Check length constraints
            if (type === 'latitude' && value.length > 10) {
                errors.push('Latitude maksimal 10 karakter');
            }
            if (type === 'longitude' && value.length > 12) {
                errors.push('Longitude maksimal 12 karakter');
            }

            // Check decimal places (must have 6-8 decimal places)
            const parts = value.split('.');
            if (parts.length !== 2) {
                errors.push('Koordinat harus memiliki angka desimal (contoh: 1.04404846)');
            } else {
                const decimalPlaces = parts[1].length;
                if (decimalPlaces < 6) {
                    errors.push(`Minimal 6 angka desimal (saat ini: ${decimalPlaces})`);
                } else if (decimalPlaces > 8) {
                    errors.push(`Maksimal 8 angka desimal (saat ini: ${decimalPlaces})`);
                }
            }

            // Check coordinate ranges for Indonesia
            if (type === 'latitude') {
                if (numValue < -11 || numValue > 6) {
                    errors.push('Latitude harus dalam rentang -11 sampai 6 (wilayah Indonesia)');
                }
            } else if (type === 'longitude') {
                if (numValue < 95 || numValue > 141) {
                    errors.push('Longitude harus dalam rentang 95 sampai 141 (wilayah Indonesia)');
                }
            }

            return { valid: errors.length === 0, errors, value: numValue };
        }

        // Real-time validation on input
        function validateInputField(input, type, errorElement) {
            const result = validateCoordinate(input.value, type);

            if (!result.valid && input.value.trim() !== '') {
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
                errorElement.textContent = result.errors[0];
            } else if (input.value.trim() !== '' && result.valid) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                errorElement.textContent = '';
            } else {
                input.classList.remove('is-invalid', 'is-valid');
                errorElement.textContent = '';
            }

            return result;
        }

        // Add input event listeners for real-time validation
        manualLatInput.addEventListener('input', function() {
            validateInputField(this, 'latitude', latitudeError);
            validationSummary.classList.add('d-none');
        });

        manualLngInput.addEventListener('input', function() {
            validateInputField(this, 'longitude', longitudeError);
            validationSummary.classList.add('d-none');
        });

        // Show manual input dialog
        function showManualInputDialog() {
            // Reset form
            manualLatInput.value = '';
            manualLngInput.value = '';
            manualLatInput.classList.remove('is-invalid', 'is-valid');
            manualLngInput.classList.remove('is-invalid', 'is-valid');
            latitudeError.textContent = '';
            longitudeError.textContent = '';
            validationSummary.classList.add('d-none');

            // Show modal
            manualCoordinateModal.show();
        }

        // Apply coordinate button click handler
        applyCoordinateBtn.addEventListener('click', function() {
            const latResult = validateCoordinate(manualLatInput.value, 'latitude');
            const lngResult = validateCoordinate(manualLngInput.value, 'longitude');

            // Show validation states
            validateInputField(manualLatInput, 'latitude', latitudeError);
            validateInputField(manualLngInput, 'longitude', longitudeError);

            // Collect all errors
            const allErrors = [];
            if (!latResult.valid) allErrors.push(...latResult.errors.map(e => 'Latitude: ' + e));
            if (!lngResult.valid) allErrors.push(...lngResult.errors.map(e => 'Longitude: ' + e));

            if (allErrors.length > 0) {
                validationMessage.innerHTML = allErrors.join('<br>');
                validationSummary.classList.remove('d-none');
                return;
            }

            // Apply coordinates
            setLocation(latResult.value, lngResult.value);
            manualCoordinateModal.hide();

            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Lokasi Diterapkan',
                text: 'Koordinat manual berhasil diterapkan',
                timer: 1500,
                showConfirmButton: false
            });
        });

        // Handle Enter key in inputs
        manualLatInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                manualLngInput.focus();
            }
        });

        manualLngInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyCoordinateBtn.click();
            }
        });

        // Manual input button click handler
        document.getElementById('manualInputBtn').addEventListener('click', function() {
            showManualInputDialog();
        });

        // Load stats
        function loadStats() {
            fetch('{{ route("sbr.stats") }}')
                .then(r => r.json())
                .then(data => {
                    document.getElementById('statTotal').textContent = data.total;
                    document.getElementById('statTagged').textContent = data.tagged;
                    document.getElementById('statUntagged').textContent = data.untagged;
                });
        }

        // Kecamatan change - load kelurahan
        document.getElementById('filterKecamatan').addEventListener('change', function() {
            const kelurahanSelect = document.getElementById('filterKelurahan');
            kelurahanSelect.innerHTML = '<option value="">Memuat...</option>';
            kelurahanSelect.disabled = true;

            if (!this.value) {
                kelurahanSelect.innerHTML = '<option value="">Pilih Kecamatan dulu</option>';
                return;
            }

            fetch(`{{ url('sbr/kelurahan') }}/${encodeURIComponent(this.value)}`)
                .then(r => r.json())
                .then(data => {
                    kelurahanSelect.innerHTML = '<option value="">Semua Kelurahan</option>';
                    data.forEach(kel => {
                        kelurahanSelect.innerHTML += `<option value="${kel}">${kel}</option>`;
                    });
                    kelurahanSelect.disabled = false;
                    searchBusinesses();
                });
        });

        // Filter changes
        document.getElementById('filterKelurahan').addEventListener('change', () => searchBusinesses());

        // Search with debounce
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => searchBusinesses(), 300);
        });

        // Search businesses
        function searchBusinesses(page = 1) {
            currentPage = page;
            const params = new URLSearchParams();

            const kecamatan = document.getElementById('filterKecamatan').value;
            const kelurahan = document.getElementById('filterKelurahan').value;
            const search = document.getElementById('searchInput').value;

            if (kecamatan) params.append('kecamatan', kecamatan);
            if (kelurahan) params.append('kelurahan', kelurahan);
            if (search) params.append('search', search);
            params.append('page', page);

            document.getElementById('searchLoading').classList.remove('d-none');

            fetch(`{{ route('sbr.search') }}?${params.toString()}`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('searchLoading').classList.add('d-none');
                    renderBusinessList(data);
                    updatePagination(data);
                })
                .catch(err => {
                    document.getElementById('searchLoading').classList.add('d-none');
                    console.error(err);
                });
        }

        // Render business list
        function renderBusinessList(data) {
            const list = document.getElementById('businessList');
            document.getElementById('resultCount').textContent = `${data.total} hasil`;

            if (data.data.length === 0) {
                list.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-search me-2"></i>Tidak ada data ditemukan</div>';
                return;
            }

            list.innerHTML = data.data.map(b => `
                <div class="business-item ${b.latitude ? 'tagged' : 'untagged'} ${selectedBusiness && selectedBusiness.id === b.id ? 'selected' : ''}"
                     data-id="${b.id}" onclick="selectBusiness(${b.id})">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold">${b.nama_usaha}</div>
                            <small class="text-muted">${b.kecamatan} - ${b.kelurahan}</small>
                            ${ (b.idsbr || b.alamat)
                                ? `<small class=\"text-muted d-block text-wrap-break\">${b.idsbr ? 'ID SBR: ' + b.idsbr : ''}${(b.idsbr && b.alamat) ? ' • ' : ''}${b.alamat ? b.alamat : ''}</small>`
                                : '' }
                        </div>
                        ${b.status ? `<span class="status-badge status-${b.status}">${b.status}</span>` : '<span class="badge bg-secondary">Belum</span>'}
                    </div>
                </div>
            `).join('');
        }

        // Update pagination
        function updatePagination(data) {
            lastPage = data.last_page;
            const pagination = document.getElementById('pagination');
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            const pageInfo = document.getElementById('pageInfo');

            if (data.last_page > 1) {
                pagination.style.display = 'flex';
                pageInfo.textContent = `Halaman ${data.current_page} dari ${data.last_page}`;
                prevBtn.disabled = data.current_page <= 1;
                nextBtn.disabled = data.current_page >= data.last_page;
            } else {
                pagination.style.display = 'none';
            }
        }

        // Pagination handlers
        document.getElementById('prevPage').addEventListener('click', () => { if (currentPage > 1) searchBusinesses(currentPage - 1); });
        document.getElementById('nextPage').addEventListener('click', () => { if (currentPage < lastPage) searchBusinesses(currentPage + 1); });

        // Select business
        function selectBusiness(id) {
            fetch(`{{ url('sbr') }}/${id}`)
                .then(r => r.json())
                .then(data => {
                    selectedBusiness = data;

                    // Update UI
                    document.querySelectorAll('.business-item').forEach(el => el.classList.remove('selected'));
                    document.querySelector(`[data-id="${id}"]`)?.classList.add('selected');

                    document.getElementById('selectedBusinessInfo').style.display = 'block';
                    document.getElementById('selectedBusinessName').textContent = data.nama_usaha;
                    document.getElementById('selectedBusinessLocation').textContent = `${data.kecamatan} - ${data.kelurahan}`;
                    document.getElementById('selectedBusinessIdsbr').textContent = `ID SBR: ${data.idsbr ?? '-'}`;
                    document.getElementById('selectedBusinessAlamat').textContent = `Alamat: ${data.alamat ?? '-'}`;

                    const statusEl = document.getElementById('selectedBusinessStatus');
                    if (data.status) {
                        statusEl.textContent = data.status;
                        statusEl.className = `status-badge status-${data.status}`;
                    } else {
                        statusEl.textContent = 'Belum ditag';
                        statusEl.className = 'badge bg-secondary';
                    }

                    document.getElementById('businessId').value = data.id;
                    document.getElementById('status').value = data.status || '';

                    // Show existing location if available
                    if (data.latitude && data.longitude) {
                        setLocation(parseFloat(data.latitude), parseFloat(data.longitude));
                    } else {
                        if (marker) map.removeLayer(marker);
                        if (locationCircle) map.removeLayer(locationCircle);
                        document.getElementById('latitude').value = '';
                        document.getElementById('longitude').value = '';
                        document.getElementById('locationAccuracy').innerHTML = '';
                    }

                    validateForm();

                    // Update workflow indicators for mobile
                    updateWorkflowIndicators(2);

                    // Invalidate map size after scroll (for mobile)
                    if (isMobile()) {
                        setTimeout(() => {
                            map.invalidateSize();
                        }, 300);
                    }
                });
        }

        // Validate form
        function validateForm() {
            const latEl = document.getElementById('latitude');
            const lngEl = document.getElementById('longitude');
            const statusEl = document.getElementById('status');
            const businessEl = document.getElementById('businessId');
            const saveBtn = document.getElementById('saveBtn');
            const saveBtnWrapper = document.getElementById('saveBtnWrapper');
            const guidanceBox = document.getElementById('formGuidance');
            const guidanceText = document.getElementById('formGuidanceText');
            const statusHelp = document.getElementById('statusHelp');

            const hasLocation = latEl.value && lngEl.value;
            const hasStatus = statusEl.value;
            const hasBusiness = businessEl.value;

            const missing = [];
            if (!hasBusiness) missing.push('Pilih usaha dari daftar di kiri');
            if (!hasLocation) missing.push('Tandai lokasi pada peta');
            if (!hasStatus) missing.push('Pilih Status Usaha: ✓ Aktif atau ✗ Tutup');

            // Toggle save button
            saveBtn.disabled = missing.length > 0;

            // Status-specific UI
            if (!hasStatus) {
                statusEl.classList.add('is-invalid');
                if (statusHelp) statusHelp.style.display = 'block';
            } else {
                statusEl.classList.remove('is-invalid');
                if (statusHelp) statusHelp.style.display = 'none';
            }

            // Guidance box
            if (missing.length > 0) {
                if (guidanceBox && guidanceText) {
                    guidanceBox.style.display = 'block';
                    guidanceText.innerHTML = 'Lengkapi sebelum menyimpan: ' + missing.map(m => `<span class="text-danger">${m}</span>`).join(' • ');
                }
                if (saveBtnWrapper) {
                    const tip = 'Perlu dilengkapi: ' + missing.join(' • ');
                    saveBtnWrapper.setAttribute('title', tip);
                    saveBtnWrapper.setAttribute('data-bs-original-title', tip);
                    if (saveTooltip) saveTooltip.enable();
                }
            } else {
                if (guidanceBox) guidanceBox.style.display = 'none';
                if (saveTooltip) { saveTooltip.hide(); saveTooltip.disable(); }
            }
        }

        document.getElementById('status').addEventListener('change', validateForm);

        // Clear/reset button: only clears manual inputs (lat, lng, status) for testing
        document.getElementById('clearBtn').addEventListener('click', function() {
            // Do not modify selected business or imported data; only clear form inputs
            document.getElementById('latitude').value = '';
            document.getElementById('longitude').value = '';
            document.getElementById('status').value = '';

            // Remove map markers and accuracy circle
            if (marker) map.removeLayer(marker);
            if (locationCircle) map.removeLayer(locationCircle);
            document.getElementById('locationAccuracy').innerHTML = '';

            // Re-validate to disable Save button
            validateForm();

            // Optional feedback
            Swal.fire({
                icon: 'info',
                title: 'Form Direset',
                text: 'Latitude, Longitude, dan Status telah dikosongkan',
                timer: 1200,
                showConfirmButton: false
            });
        });

        // Delete button: set latitude, longitude, and status to null on server
        document.getElementById('deleteBtn').addEventListener('click', function() {
            const id = document.getElementById('businessId').value;
            if (!id) {
                Swal.fire({ icon: 'warning', title: 'Pilih Usaha', text: 'Silakan pilih usaha terlebih dahulu.' });
                return;
            }

            Swal.fire({
                icon: 'warning',
                title: 'Hapus Tagging?',
                html: '<p>Tindakan ini akan mengosongkan Latitude, Longitude, dan Status di database.</p><p class="text-muted small">Data usaha dari import tidak dihapus.</p>',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (!result.isConfirmed) return;

                const btn = document.getElementById('deleteBtn');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menghapus...';

                fetch(`{{ url('sbr') }}/${id}/tagging`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(result => {
                    if (result.success) {
                        // Clear UI fields
                        document.getElementById('latitude').value = '';
                        document.getElementById('longitude').value = '';
                        document.getElementById('status').value = '';
                        if (marker) map.removeLayer(marker);
                        if (locationCircle) map.removeLayer(locationCircle);
                        document.getElementById('locationAccuracy').innerHTML = '';

                        // Update selected business status badge
                        const statusEl = document.getElementById('selectedBusinessStatus');
                        statusEl.textContent = 'Belum ditag';
                        statusEl.className = 'badge bg-secondary';

                        if (selectedBusiness) {
                            selectedBusiness.latitude = null;
                            selectedBusiness.longitude = null;
                            selectedBusiness.status = null;
                        }

                        Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Tagging dihapus (null).', timer: 1400, showConfirmButton: false });
                        loadStats();
                        searchBusinesses(currentPage);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: result.message || 'Gagal menghapus tagging.' });
                    }
                })
                .catch(() => {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan saat menghapus.' });
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-trash me-2"></i>Delete';
                    validateForm();
                });
            });
        });

        // Form submit
        document.getElementById('taggingForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const id = document.getElementById('businessId').value;
            const saveBtn = document.getElementById('saveBtn');

            // Disable button and show loading
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';

            const data = {
                latitude: document.getElementById('latitude').value,
                longitude: document.getElementById('longitude').value,
                status: document.getElementById('status').value
            };

            fetch(`{{ url('sbr') }}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(result => {
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: isMobile() ? 'Data tersimpan' : result.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    loadStats();
                    searchBusinesses(currentPage);

                    // Reset workflow for next business on mobile
                    if (isMobile()) {
                        updateWorkflowIndicators(1);
                        // Reset selected business info
                        selectedBusiness = null;
                        document.getElementById('selectedBusinessInfo').style.display = 'none';
                        document.getElementById('businessId').value = '';
                        document.getElementById('latitude').value = '';
                        document.getElementById('longitude').value = '';
                        document.getElementById('status').value = '';
                        if (marker) map.removeLayer(marker);
                        if (locationCircle) map.removeLayer(locationCircle);

                        // Scroll back to business list
                        setTimeout(() => {
                            document.getElementById('step1Indicator')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }, 1600);
                    }
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menyimpan data' });
                }
            })
            .catch(err => {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan' });
            })
            .finally(() => {
                // Re-enable button
                saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>Simpan Data';
                validateForm();
            });
        });
    </script>
</body>
</html>

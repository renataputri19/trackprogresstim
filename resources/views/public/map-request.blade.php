@extends('haloip.layouts.app')
@section('title', 'Lihat Permintaan Peta - ' . $mapRequest->ticket_code)

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    .info-group {
        margin-bottom: 1.5rem;
    }

    .info-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 0.5rem;
        display: block;
    }

    .info-value {
        font-size: 1rem;
        color: #111827;
        font-weight: 500;
        padding: 0.75rem 1rem;
        background: #f9fafb;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }

    .status-pending {
        background: linear-gradient(135deg, #fbbf24, #f59e0b) !important;
        color: #ffffff !important;
        border: none !important;
        padding: 0.5rem 1rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-in_progress {
        background: linear-gradient(135deg, #f97316, #ea580c) !important;
        color: #ffffff !important;
        border: none !important;
        padding: 0.5rem 1rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-completed {
        background: linear-gradient(135deg, #10b981, #059669) !important;
        color: #ffffff !important;
        border: none !important;
        padding: 0.5rem 1rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-block;
    }

    .location-badge {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-block;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .map-type-badge {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-block;
    }

    .photo-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        overflow: hidden;
        background: #ffffff;
        transition: all 0.3s ease;
    }

    .photo-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .photo-header {
        background: #f8fafc;
        padding: 0.75rem 1rem;
        font-weight: 600;
        font-size: 0.875rem;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
    }

    .photo-body {
        padding: 1rem;
    }

    .photo-preview {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
    <div class="haloip-container">
        <!-- Hero Section -->
        <section class="haloip-hero">
            <div class="container mx-auto px-4">
                <div class="haloip-hero-content">
                    <h1 class="haloip-title">Detail Permintaan Peta</h1>
                    <p class="haloip-subtitle">
                        Halaman publik untuk melihat detail dan bukti penyelesaian permintaan peta
                    </p>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <div class="container mx-auto px-4 pb-8 ticketing">
            <!-- Map Request Information Card -->
            <div class="row mt-4">
                <div class="col-lg-8 mx-auto">
                    <div class="card ticket-card shadow-lg border-0">
                        <div class="card-header bg-gradient-to-r from-teal-500 to-emerald-500 text-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1 fw-bold">{{ $mapRequest->title }}</h5>
                                    <div class="ticket-code bg-white text-primary px-3 py-1 rounded-pill d-inline-block">
                                        {{ $mapRequest->ticket_code }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="status-{{ $mapRequest->status }}">
                                        {{ $mapRequest->status == 'pending' ? 'Menunggu' : ($mapRequest->status == 'in_progress' ? 'Sedang Diproses' : 'Selesai') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">
                                            <i class="fas fa-user text-primary me-2"></i>Pengaju
                                        </label>
                                        <div class="info-value">{{ $mapRequest->requestor->name ?? 'Pengaju Tidak Diketahui' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">
                                            <i class="fas fa-map text-primary me-2"></i>Jenis Peta
                                        </label>
                                        <div class="info-value">
                                            <span class="map-type-badge">{{ $mapRequest->map_type_display }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if ($mapRequest->district_display || $mapRequest->village_display || $mapRequest->zone)
                                <div class="col-12">
                                    <div class="info-group">
                                        <label class="info-label">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>Lokasi
                                        </label>
                                        <div class="info-value">
                                            @if ($mapRequest->district_display)
                                                <span class="location-badge">
                                                    <i class="fas fa-building me-1"></i>{{ $mapRequest->district_display }}
                                                </span>
                                            @endif
                                            @if ($mapRequest->village_display)
                                                <span class="location-badge">
                                                    <i class="fas fa-home me-1"></i>{{ $mapRequest->village_display }}
                                                </span>
                                            @elseif ($mapRequest->zone)
                                                <span class="location-badge">
                                                    <i class="fas fa-layer-group me-1"></i>Zona {{ $mapRequest->zone }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">
                                            <i class="fas fa-user-cog text-primary me-2"></i>Staf IT
                                        </label>
                                        <div class="info-value">{{ $mapRequest->itStaff->name ?? 'Belum Ditugaskan' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">
                                            <i class="fas fa-calendar-plus text-primary me-2"></i>Tanggal Diajukan
                                        </label>
                                        <div class="info-value">{{ $mapRequest->created_at->locale('id')->isoFormat('D MMMM Y, HH:mm') }}</div>
                                    </div>
                                </div>
                                @if ($mapRequest->done_at)
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">
                                            <i class="fas fa-calendar-check text-success me-2"></i>Tanggal Selesai
                                        </label>
                                        <div class="info-value">{{ $mapRequest->done_at->locale('id')->isoFormat('D MMMM Y, HH:mm') }}</div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-12">
                                    <div class="info-group">
                                        <label class="info-label">
                                            <i class="fas fa-file-alt text-primary me-2"></i>Deskripsi Permintaan
                                        </label>
                                        <div class="info-value">{{ $mapRequest->description }}</div>
                                    </div>
                                </div>

                            <!-- Photo Section -->
                            @if ($mapRequest->requestor_photo || $mapRequest->it_photo)
                            <hr class="my-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-images text-primary me-2"></i>Dokumentasi
                            </h6>
                            <div class="row g-3">
                                @if ($mapRequest->requestor_photo)
                                <div class="col-md-6">
                                    <div class="photo-card">
                                        <div class="photo-header">
                                            <i class="fas fa-user me-2"></i>Foto Pendukung dari Pengaju
                                        </div>
                                        <div class="photo-body">
                                            <img src="{{ Storage::url($mapRequest->requestor_photo) }}"
                                                 alt="Foto Pengaju" class="photo-preview">
                                            <a href="{{ Storage::url($mapRequest->requestor_photo) }}" target="_blank"
                                               class="btn ticketing-btn ticketing-btn-outline-primary w-100">
                                                <i class="fas fa-external-link-alt me-2"></i>Lihat Foto Penuh
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if ($mapRequest->it_photo)
                                <div class="col-md-6">
                                    <div class="photo-card">
                                        <div class="photo-header">
                                            <i class="fas fa-tools me-2"></i>Foto Bukti Penyelesaian IT
                                        </div>
                                        <div class="photo-body">
                                            <img src="{{ Storage::url($mapRequest->it_photo) }}"
                                                 alt="Foto IT" class="photo-preview">
                                            <a href="{{ Storage::url($mapRequest->it_photo) }}" target="_blank"
                                               class="btn ticketing-btn ticketing-btn-outline-primary w-100">
                                                <i class="fas fa-external-link-alt me-2"></i>Lihat Foto Penuh
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif

                            <!-- Status Alert -->
                            @if ($mapRequest->status === 'pending')
                                <div class="alert alert-info mt-4">
                                    <i class="fas fa-clock me-2"></i>Permintaan peta ini sedang menunggu untuk ditangani oleh tim IT.
                                </div>
                            @elseif ($mapRequest->status === 'in_progress')
                                <div class="alert alert-warning mt-4">
                                    <i class="fas fa-cog me-2"></i>Permintaan peta ini sedang dalam proses penanganan oleh tim IT.
                                </div>
                            @elseif ($mapRequest->status === 'completed')
                                <div class="alert alert-success mt-4">
                                    <i class="fas fa-check-circle me-2"></i>Permintaan peta ini telah selesai ditangani oleh tim IT.
                                    @if ($mapRequest->it_photo)
                                        <br><small>Silakan lihat foto bukti penyelesaian di atas.</small>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <a href="{{ route('map-requests.index') }}" class="haloip-btn-back me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7"/>
                        <path d="M19 12H5"/>
                    </svg>
                    Kembali ke HaloIP
                </a>
                <a href="{{ route('map-requests.index') }}" class="haloip-btn haloip-btn-primary me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="3 6 9 1 15 6 21 1 21 14 15 9 9 14 3 9"></polygon>
                        <line x1="9" y1="1" x2="9" y2="14"></line>
                        <line x1="15" y1="6" x2="15" y2="9"></line>
                    </svg>
                    Lihat Semua Permintaan Peta
                </a>
                <button onclick="window.print()" class="haloip-btn haloip-btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6,9 6,2 18,2 18,9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <polyline points="6,14 6,22 18,22 18,14"></polyline>
                    </svg>
                    Cetak Halaman
                </button>
            </div>
        </div>
    </div>
@endsection

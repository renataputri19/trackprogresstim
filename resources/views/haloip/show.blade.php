@extends('haloip.layouts.app')
@section('title', ($ticket->category === 'Peta Cetak' ? 'Detail Permintaan Peta - ' : 'Detail Tiket - ') . $ticket->ticket_code . ' | HaloIP - RENTAK')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    /* HaloIP Detail Page - Modern Design System */
    .haloip-detail-wrapper {
        font-family: 'Inter', sans-serif;
        color: #111827;
        background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 50%, #ecfdf5 100%);
        min-height: 100vh;
        position: relative;
    }

    .haloip-detail-bg-decoration {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 300px;
        background:
            radial-gradient(circle at 20% 20%, rgba(13, 148, 136, 0.08) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(16, 185, 129, 0.08) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
    }

    .haloip-detail-content {
        position: relative;
        z-index: 1;
        padding: 2rem 0 3rem 0;
    }

    /* Card Styles */
    .haloip-detail-card {
        background: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(13, 148, 136, 0.1);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .haloip-detail-card-header {
        background: linear-gradient(135deg, #f0fdfa 0%, #ecfdf5 100%);
        padding: 1.25rem 1.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .haloip-detail-card-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .haloip-detail-card-title svg {
        color: #0d9488;
    }

    .haloip-detail-card-body {
        padding: 1.5rem;
    }

    /* Info Group Styles */
    .haloip-info-group {
        margin-bottom: 1.25rem;
    }

    .haloip-info-group:last-child {
        margin-bottom: 0;
    }

    .haloip-info-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .haloip-info-label svg {
        color: #9ca3af;
        width: 14px;
        height: 14px;
    }

    .haloip-info-value {
        font-size: 0.9375rem;
        color: #111827;
        font-weight: 500;
        padding: 0.75rem 1rem;
        background: #f9fafb;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }

    /* Photo Card Styles */
    .haloip-photo-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .haloip-photo-header {
        background: linear-gradient(135deg, #f0fdfa 0%, #ecfdf5 100%);
        padding: 0.875rem 1.25rem;
        font-weight: 600;
        font-size: 0.875rem;
        color: #0d9488;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .haloip-photo-header svg {
        color: #0d9488;
    }

    .haloip-photo-body {
        padding: 1.25rem;
    }

    .haloip-photo-preview {
        width: 100%;
        max-height: 400px;
        object-fit: contain;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }

    /* Form Styles */
    .haloip-form-group {
        margin-bottom: 1.25rem;
    }

    .haloip-form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .haloip-form-label svg {
        color: #0d9488;
        width: 14px;
        height: 14px;
    }

    .haloip-form-label .text-danger {
        color: #dc2626;
        margin-left: 0.25rem;
    }

    .haloip-form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 0.9375rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        background: #ffffff;
        color: #111827;
    }

    .haloip-form-control:focus {
        outline: none;
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
    }

    .haloip-form-help {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.375rem;
        display: block;
    }

    .haloip-upload-area {
        position: relative;
    }

    .haloip-upload-area .haloip-form-control {
        border: 2px dashed #d1d5db;
        padding: 1rem;
        cursor: pointer;
    }

    .haloip-upload-area .haloip-form-control:hover {
        border-color: #0d9488;
        background-color: #f0fdfa;
    }

    /* Status Badge Styles */
    .haloip-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .haloip-status-badge-pending {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        border: 1px solid #fbbf24;
    }

    .haloip-status-badge-progress {
        background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%);
        color: #9a3412;
        border: 1px solid #fb923c;
    }

    .haloip-status-badge-completed {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    /* Alert Styles */
    .haloip-alert {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        line-height: 1.6;
    }

    .haloip-alert svg {
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .haloip-alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border: 1px solid #6ee7b7;
        color: #065f46;
    }

    .haloip-alert-danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border: 1px solid #fca5a5;
        color: #991b1b;
    }

    /* Category Badge */
    .haloip-category-badge {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        color: #075985;
        border: 1px solid #7dd3fc;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 600;
    }

    /* Unassigned Text */
    .haloip-unassigned {
        color: #9ca3af;
        font-style: italic;
    }

    /* Responsive Design */
    @media (max-width: 991px) {
        .haloip-detail-content {
            padding: 1.5rem 0 2rem 0;
        }

        .haloip-detail-card-header {
            padding: 1rem 1.25rem;
        }

        .haloip-detail-card-body {
            padding: 1.25rem;
        }
    }

    @media (max-width: 767px) {
        .haloip-info-label {
            font-size: 0.6875rem;
        }

        .haloip-info-value {
            font-size: 0.875rem;
            padding: 0.625rem 0.875rem;
        }

        .haloip-detail-card-title {
            font-size: 1rem;
        }

        .haloip-detail-card {
            margin-bottom: 1rem;
        }
    }
</style>
@endsection

@section('content')
    <div class="haloip-detail-wrapper">
        <!-- Decorative Background -->
        <div class="haloip-detail-bg-decoration"></div>

        <!-- Hero Section -->
        <section class="haloip-hero">
            <div class="container mx-auto px-4">
                <div class="haloip-hero-content">
                    <h1 class="haloip-title">
                        {{ $ticket->category === 'Peta Cetak' ? 'Detail Permintaan Peta' : 'Detail Tiket IT' }}
                    </h1>
                    <p class="haloip-subtitle">
                        Kelola penugasan petugas dan perbarui status {{ $ticket->category === 'Peta Cetak' ? 'permintaan peta' : 'tiket' }}
                    </p>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <div class="haloip-detail-content">
            <div class="container mx-auto px-4">
                <!-- Back Button -->
                <div class="mb-4">
                    <a href="{{ route('haloip.manage') }}" class="haloip-btn-back">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m12 19-7-7 7-7"/>
                            <path d="M19 12H5"/>
                        </svg>
                        Kembali ke HaloIP
                    </a>
                </div>

                <!-- Success Alert -->
                @if (session('success'))
                    <div class="haloip-alert haloip-alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="haloip-alert haloip-alert-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <div>
                            <ul class="mb-0" style="padding-left: 1.25rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="row mt-4">
                    <!-- Left Column - Ticket Information -->
                    <div class="col-lg-7">
                        <!-- Ticket Information Card -->
                        <div class="haloip-detail-card">
                            <div class="haloip-detail-card-header">
                                <h2 class="haloip-detail-card-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg>
                                    Informasi {{ $ticket->category === 'Peta Cetak' ? 'Permintaan Peta' : 'Tiket' }}
                                </h2>
                            </div>
                            <div class="haloip-detail-card-body">
                                <!-- Ticket Code -->
                                <div class="haloip-info-group">
                                    <div class="haloip-info-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
                                            <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                                            <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
                                            <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
                                        </svg>
                                        Kode {{ $ticket->category === 'Peta Cetak' ? 'Permintaan' : 'Tiket' }}
                                    </div>
                                    <div class="haloip-info-value">{{ $ticket->ticket_code }}</div>
                                </div>

                                <!-- Category -->
                                <div class="haloip-info-group">
                                    <div class="haloip-info-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                        </svg>
                                        Kategori
                                    </div>
                                    <div class="haloip-info-value">
                                        <span class="haloip-category-badge">{{ $ticket->category }}</span>
                                    </div>
                                </div>

                                <!-- Title -->
                                <div class="haloip-info-group">
                                    <div class="haloip-info-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                                        </svg>
                                        Judul
                                    </div>
                                    <div class="haloip-info-value">{{ $ticket->title }}</div>
                                </div>

                                <!-- Description -->
                                <div class="haloip-info-group">
                                    <div class="haloip-info-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14,2 14,8 20,8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10,9 9,9 8,9"></polyline>
                                        </svg>
                                        Deskripsi {{ $ticket->category === 'Peta Cetak' ? 'Permintaan' : 'Masalah' }}
                                    </div>
                                    <div class="haloip-info-value">{{ $ticket->description }}</div>
                                </div>

                                <!-- Conditional Fields Based on Category -->
                                @if ($ticket->category === 'Peta Cetak')
                                    <!-- Map Type -->
                                    <div class="haloip-info-group">
                                        <div class="haloip-info-label">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                                                <line x1="8" y1="2" x2="8" y2="18"></line>
                                                <line x1="16" y1="6" x2="16" y2="22"></line>
                                            </svg>
                                            Jenis Peta
                                        </div>
                                        <div class="haloip-info-value">{{ $ticket->map_type_display }}</div>
                                    </div>

                                    <!-- Location -->
                                    @if ($ticket->district_display || $ticket->village_display || $ticket->zone)
                                    <div class="haloip-info-group">
                                        <div class="haloip-info-label">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                <circle cx="12" cy="10" r="3"></circle>
                                            </svg>
                                            Lokasi
                                        </div>
                                        <div class="haloip-info-value">
                                            @if ($ticket->district_display)
                                                <div style="margin-bottom: 0.25rem;">Kecamatan: {{ $ticket->district_display }}</div>
                                            @endif
                                            @if ($ticket->village_display)
                                                <div style="margin-bottom: 0.25rem;">Kelurahan: {{ $ticket->village_display }}</div>
                                            @elseif ($ticket->zone)
                                                <div>Zona: {{ $ticket->zone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                @else
                                    <!-- Ruangan (for non-map requests) -->
                                    <div class="haloip-info-group">
                                        <div class="haloip-info-label">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M13 4h3a2 2 0 0 1 2 2v14"></path>
                                                <path d="M2 20h3"></path>
                                                <path d="M13 20h9"></path>
                                                <path d="M10 12v.01"></path>
                                                <path d="M13 4.562v16.157a1 1 0 0 1-1.242.97L5 20V5.562a2 2 0 0 1 1.515-1.94l4-1A2 2 0 0 1 13 4.561Z"></path>
                                            </svg>
                                            Ruangan
                                        </div>
                                        <div class="haloip-info-value">{{ $ticket->ruangan }}</div>
                                    </div>
                                @endif

                                <!-- Requestor -->
                                <div class="haloip-info-group">
                                    <div class="haloip-info-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        Pengaju
                                    </div>
                                    <div class="haloip-info-value">{{ $ticket->requestor->name ?? 'Pengaju Tidak Diketahui' }}</div>
                                </div>

                                <!-- IT Staff -->
                                <div class="haloip-info-group">
                                    <div class="haloip-info-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                        Staf IT
                                    </div>
                                    <div class="haloip-info-value">
                                        @if ($ticket->itStaff)
                                            {{ $ticket->itStaff->name }}
                                        @else
                                            <span class="haloip-unassigned">Belum Ditugaskan</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="haloip-info-group">
                                    <div class="haloip-info-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                        </svg>
                                        Status
                                    </div>
                                    <div class="haloip-info-value">
                                        @if ($ticket->status === 'pending')
                                            <span class="haloip-status-badge haloip-status-badge-pending">Menunggu</span>
                                        @elseif ($ticket->status === 'in_progress')
                                            <span class="haloip-status-badge haloip-status-badge-progress">Sedang Diproses</span>
                                        @else
                                            <span class="haloip-status-badge haloip-status-badge-completed">Selesai</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Created Date -->
                                <div class="haloip-info-group">
                                    <div class="haloip-info-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                        Tanggal Diajukan
                                    </div>
                                    <div class="haloip-info-value">{{ $ticket->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
                                </div>

                                <!-- Completion Date -->
                                @if ($ticket->done_at)
                                <div class="haloip-info-group">
                                    <div class="haloip-info-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                        Tanggal Selesai
                                    </div>
                                    <div class="haloip-info-value">{{ $ticket->done_at->translatedFormat('d F Y, H:i') }} WIB</div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Requestor Photo -->
                        @if ($ticket->requestor_photo)
                            <div class="haloip-photo-card">
                                <div class="haloip-photo-header">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="9" cy="9" r="2"></circle>
                                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                    </svg>
                                    Foto {{ $ticket->category === 'Peta Cetak' ? 'Pendukung' : '' }} dari Pengaju
                                </div>
                                <div class="haloip-photo-body">
                                    <img src="{{ asset('storage/' . $ticket->requestor_photo) }}"
                                         alt="Foto Pengaju"
                                         class="haloip-photo-preview">
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column - Assignment & Update Forms -->
                    <div class="col-lg-5">
                        <!-- Step 1: Assign IT Staff -->
                        <div class="haloip-detail-card">
                            <div class="haloip-detail-card-header">
                                <h2 class="haloip-detail-card-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                    Langkah 1: Tugaskan Petugas IT
                                </h2>
                            </div>
                            <div class="haloip-detail-card-body">
                                <form method="POST" action="{{ route('haloip.update', $ticket->id) }}">
                                    @csrf
                                    <input type="hidden" name="action" value="assign_only">

                                    <div class="haloip-form-group">
                                        <label for="it_staff_id" class="haloip-form-label">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            </svg>
                                            Pilih Petugas IT
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="haloip-form-control" id="it_staff_id" name="it_staff_id" required>
                                            <option value="">-- Pilih Petugas --</option>
                                            @foreach ($itStaffList as $staff)
                                                <option value="{{ $staff->id }}" {{ $ticket->it_staff_id == $staff->id ? 'selected' : '' }}>
                                                    {{ $staff->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="haloip-form-help">Tugaskan petugas IT untuk menangani {{ $ticket->category === 'Peta Cetak' ? 'permintaan peta' : 'tiket' }} ini</small>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="haloip-btn haloip-btn-primary" style="width: 100%; justify-content: center; padding: 0.75rem 1.5rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6L9 17l-5-5" />
                                            </svg>
                                            Tugaskan Petugas
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Step 2: Update Status -->
                        <div class="haloip-detail-card">
                            <div class="haloip-detail-card-header">
                                <h2 class="haloip-detail-card-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    Langkah 2: Perbarui Status {{ $ticket->category === 'Peta Cetak' ? 'Permintaan' : 'Tiket' }}
                                </h2>
                            </div>
                            <div class="haloip-detail-card-body">
                                <form method="POST" action="{{ route('haloip.update', $ticket->id) }}" enctype="multipart/form-data">
                                    @csrf

                                    <!-- Status Selection -->
                                    <div class="haloip-form-group">
                                        <label for="status" class="haloip-form-label">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                            </svg>
                                            Status
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="haloip-form-control" id="status" name="status" required>
                                            <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>Sedang Diproses</option>
                                            <option value="completed" {{ $ticket->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                        <small class="haloip-form-help">Pilih status terkini dari {{ $ticket->category === 'Peta Cetak' ? 'permintaan peta' : 'tiket' }}</small>
                                    </div>

                                    <!-- IT Photo Upload -->
                                    <div class="haloip-form-group">
                                        <label for="it_photo" class="haloip-form-label">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                <circle cx="9" cy="9" r="2"></circle>
                                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                            </svg>
                                            Foto Bukti Penyelesaian
                                            @if ($ticket->isItPhotoRequired())
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>
                                        <div class="haloip-upload-area">
                                            <input type="file"
                                                   class="haloip-form-control"
                                                   id="it_photo"
                                                   name="it_photo"
                                                   accept="image/*"
                                                   {{ $ticket->isItPhotoRequired() ? 'required' : '' }}>
                                        </div>
                                        <small class="haloip-form-help" id="photo-help">
                                            @if ($ticket->category === 'Peta Cetak')
                                                Wajib upload foto untuk status selesai (maksimal 2MB)
                                            @else
                                                Upload foto bukti penyelesaian (opsional, maksimal 2MB)
                                            @endif
                                        </small>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="d-grid">
                                        <button type="submit" class="haloip-btn haloip-btn-success" style="width: 100%; justify-content: center; padding: 0.75rem 1.5rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                                <polyline points="7 3 7 8 15 8"></polyline>
                                            </svg>
                                            Perbarui {{ $ticket->category === 'Peta Cetak' ? 'Permintaan' : 'Tiket' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- IT Photo Preview -->
                        @if ($ticket->it_photo)
                            <div class="haloip-photo-card">
                                <div class="haloip-photo-header">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="9" cy="9" r="2"></circle>
                                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                    </svg>
                                    Foto Bukti dari Petugas IT
                                </div>
                                <div class="haloip-photo-body">
                                    <img src="{{ asset('storage/' . $ticket->it_photo) }}"
                                         alt="Foto IT"
                                         class="haloip-photo-preview">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Dynamic photo requirement based on status
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const photoInput = document.getElementById('it_photo');
        const photoHelp = document.getElementById('photo-help');
        const isPetaCetak = {{ $ticket->category === 'Peta Cetak' ? 'true' : 'false' }};

        if (statusSelect && photoInput && photoHelp) {
            statusSelect.addEventListener('change', function() {
                const status = this.value;

                if (isPetaCetak && status === 'completed') {
                    photoInput.setAttribute('required', 'required');
                    photoHelp.innerHTML = 'Wajib upload foto untuk status selesai (maksimal 2MB)';
                } else if (isPetaCetak) {
                    photoInput.removeAttribute('required');
                    photoHelp.innerHTML = 'Wajib upload foto untuk status selesai (maksimal 2MB)';
                } else {
                    photoInput.removeAttribute('required');
                    photoHelp.innerHTML = 'Upload foto bukti penyelesaian (opsional, maksimal 2MB)';
                }
            });
        }
    });
</script>
@endsection


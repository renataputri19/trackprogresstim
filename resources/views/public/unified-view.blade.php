@extends('haloip.layouts.app')
@section('title', ($ticket->category === 'Peta Cetak' ? 'Lihat Permintaan Peta - ' : 'Lihat Tiket - ') . $ticket->ticket_code)

@push('head')
<meta name="is-public-page" content="true">
@endpush

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    /* Public View Specific Styles */
    .public-view-wrapper {
        font-family: 'Inter', sans-serif;
        color: #111827;
        background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 50%, #ecfdf5 100%);
        min-height: 100vh;
        position: relative;
        overflow: hidden;
    }

    .public-view-bg-decoration {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 400px;
        background:
            radial-gradient(circle at 20% 20%, rgba(13, 148, 136, 0.08) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(16, 185, 129, 0.08) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
    }

    .public-view-hero {
        position: relative;
        z-index: 1;
        padding: 2rem 0 1.5rem 0;
        background: linear-gradient(135deg, rgba(13, 148, 136, 0.05) 0%, rgba(16, 185, 129, 0.05) 100%);
        border-bottom: 1px solid rgba(13, 148, 136, 0.1);
    }

    .public-view-hero-content {
        text-align: center;
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .public-view-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .public-view-subtitle {
        font-size: 1rem;
        color: #6b7280;
        margin: 0;
    }

    .public-view-content {
        position: relative;
        z-index: 1;
        padding: 2rem 0 3rem 0;
    }

    .public-view-card {
        background: #ffffff;
        border-radius: 1.25rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(13, 148, 136, 0.1);
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out 0.2s both;
    }

    .public-view-card-header {
        background: linear-gradient(135deg, #f0fdfa 0%, #ecfdf5 100%);
        padding: 1.5rem 2rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .public-view-card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }

    .public-view-card-body {
        padding: 2rem;
    }

    .public-info-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .public-info-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
    }

    .public-info-table tbody tr:last-child {
        border-bottom: none;
    }

    .public-info-label {
        padding: 1rem 1.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        width: 35%;
        vertical-align: top;
        background: #f9fafb;
    }

    .public-info-value {
        padding: 1rem 1.5rem;
        font-size: 0.875rem;
        color: #374151;
        font-weight: 500;
    }

    .public-section-divider {
        margin: 2rem 0;
        border: 0;
        border-top: 2px solid #f3f4f6;
    }

    .public-section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1.5rem;
    }

    .public-section-title svg {
        color: #0d9488;
    }

    .public-photo-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 768px) {
        .public-photo-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .public-photo-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .public-photo-header {
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

    .public-photo-header svg {
        color: #0d9488;
    }

    .public-photo-body {
        padding: 1.25rem;
    }

    .public-photo-preview {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        border: 1px solid #e5e7eb;
    }

    .public-photo-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
        color: #ffffff;
        border: none;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
    }

    .public-alert {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-radius: 0.75rem;
        margin-top: 1.5rem;
        font-size: 0.875rem;
        line-height: 1.6;
    }

    .public-alert svg {
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .public-alert-info {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border: 1px solid #93c5fd;
        color: #1e40af;
    }

    .public-alert-warning {
        background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%);
        border: 1px solid #fb923c;
        color: #9a3412;
    }

    .public-alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border: 1px solid #6ee7b7;
        color: #065f46;
    }

    .public-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #f3f4f6;
    }

    .public-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .public-btn-back {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .public-btn-primary {
        background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
        color: #ffffff;
        box-shadow: 0 2px 4px rgba(13, 148, 136, 0.2);
    }

    .public-btn-secondary {
        background: #ffffff;
        color: #0d9488;
        border: 2px solid #0d9488;
    }

    /* Mobile Responsive - Card Layout for Info Table */
    @media (max-width: 767px) {
        .public-view-title {
            font-size: 1.5rem;
        }

        .public-view-subtitle {
            font-size: 0.875rem;
        }

        .public-view-card-header {
            padding: 1.25rem 1.5rem;
        }

        .public-view-card-title {
            font-size: 1.25rem;
        }

        .public-view-card-body {
            padding: 1.5rem;
        }

        .public-info-table,
        .public-info-table tbody,
        .public-info-table tr,
        .public-info-table td {
            display: block;
            width: 100%;
        }

        .public-info-table tbody tr {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f3f4f6;
        }

        .public-info-table tbody tr:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .public-info-label {
            width: 100%;
            padding: 0.5rem 0;
            background: transparent;
            font-size: 0.75rem;
        }

        .public-info-value {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #f9fafb;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        .public-actions {
            flex-direction: column;
        }

        .public-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
    <div class="public-view-wrapper">
        <!-- Decorative Background -->
        <div class="public-view-bg-decoration"></div>

        <!-- Hero Section -->
        <section class="public-view-hero">
            <div class="container mx-auto px-4">
                <div class="public-view-hero-content">
                    <h1 class="public-view-title">
                        {{ $ticket->category === 'Peta Cetak' ? 'Detail Permintaan Peta' : 'Detail Tiket IT' }}
                    </h1>
                    <p class="public-view-subtitle">
                        Halaman publik untuk melihat detail dan bukti penyelesaian {{ $ticket->category === 'Peta Cetak' ? 'permintaan peta' : 'tiket' }}
                    </p>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <div class="public-view-content">
            <div class="container mx-auto px-4">
                <div class="row">
                    <div class="col-lg-10 mx-auto">
                        <!-- Ticket Information Card -->
                        <div class="public-view-card">
                            <!-- Card Header -->
                            <div class="public-view-card-header">
                                <h2 class="public-view-card-title">{{ $ticket->title }}</h2>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <span class="haloip-ticket-code-modern">{{ $ticket->ticket_code }}</span>
                                    @if ($ticket->status === 'pending')
                                        <span class="haloip-status-modern haloip-status-modern-pending">
                                            <span class="haloip-status-dot"></span>
                                            Menunggu
                                        </span>
                                    @elseif ($ticket->status === 'in_progress')
                                        <span class="haloip-status-modern haloip-status-modern-progress">
                                            <span class="haloip-status-dot"></span>
                                            Sedang Diproses
                                        </span>
                                    @else
                                        <span class="haloip-status-modern haloip-status-modern-completed">
                                            <span class="haloip-status-dot"></span>
                                            Selesai
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="public-view-card-body">
                                <!-- Information Table -->
                                <table class="public-info-table">
                                    <tbody>
                                        <!-- Category -->
                                        <tr>
                                            <td class="public-info-label">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                                </svg>
                                                Kategori
                                            </td>
                                            <td class="public-info-value">
                                                <span class="haloip-category-badge">{{ $ticket->category }}</span>
                                            </td>
                                        </tr>

                                        <!-- Requestor -->
                                        <tr>
                                            <td class="public-info-label">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                                Pengaju
                                            </td>
                                            <td class="public-info-value">{{ $ticket->requestor->name ?? 'Pengaju Tidak Diketahui' }}</td>
                                        </tr>

                                        <!-- Conditional Fields Based on Category -->
                                        @if ($ticket->category === 'Peta Cetak')
                                            <!-- Map Type -->
                                            <tr>
                                                <td class="public-info-label">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                                        <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                                                        <line x1="8" y1="2" x2="8" y2="18"></line>
                                                        <line x1="16" y1="6" x2="16" y2="22"></line>
                                                    </svg>
                                                    Jenis Peta
                                                </td>
                                                <td class="public-info-value">{{ $ticket->map_type_display }}</td>
                                            </tr>

                                            <!-- Location -->
                                            @if ($ticket->district_display || $ticket->village_display || $ticket->zone)
                                            <tr>
                                                <td class="public-info-label">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                        <circle cx="12" cy="10" r="3"></circle>
                                                    </svg>
                                                    Lokasi
                                                </td>
                                                <td class="public-info-value">
                                                    @if ($ticket->district_display)
                                                        <div style="margin-bottom: 0.25rem;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.25rem;">
                                                                <rect x="3" y="3" width="7" height="7"></rect>
                                                                <rect x="14" y="3" width="7" height="7"></rect>
                                                                <rect x="14" y="14" width="7" height="7"></rect>
                                                                <rect x="3" y="14" width="7" height="7"></rect>
                                                            </svg>
                                                            Kecamatan: {{ $ticket->district_display }}
                                                        </div>
                                                    @endif
                                                    @if ($ticket->village_display)
                                                        <div style="margin-bottom: 0.25rem;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.25rem;">
                                                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                                            </svg>
                                                            Kelurahan: {{ $ticket->village_display }}
                                                        </div>
                                                    @elseif ($ticket->zone)
                                                        <div>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.25rem;">
                                                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                                            </svg>
                                                            Zona: {{ $ticket->zone }}
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif
                                        @else
                                            <!-- Ruangan (for non-map requests) -->
                                            <tr>
                                                <td class="public-info-label">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                                        <path d="M13 4h3a2 2 0 0 1 2 2v14"></path>
                                                        <path d="M2 20h3"></path>
                                                        <path d="M13 20h9"></path>
                                                        <path d="M10 12v.01"></path>
                                                        <path d="M13 4.562v16.157a1 1 0 0 1-1.242.97L5 20V5.562a2 2 0 0 1 1.515-1.94l4-1A2 2 0 0 1 13 4.561Z"></path>
                                                    </svg>
                                                    Ruangan
                                                </td>
                                                <td class="public-info-value">{{ $ticket->ruangan }}</td>
                                            </tr>
                                        @endif

                                        <!-- IT Staff -->
                                        <tr>
                                            <td class="public-info-label">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="9" cy="7" r="4"></circle>
                                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                </svg>
                                                Staf IT
                                            </td>
                                            <td class="public-info-value">
                                                @if ($ticket->itStaff)
                                                    {{ $ticket->itStaff->name }}
                                                @else
                                                    <span class="haloip-unassigned">Belum Ditugaskan</span>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Created Date -->
                                        <tr>
                                            <td class="public-info-label">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                                </svg>
                                                Tanggal Diajukan
                                            </td>
                                            <td class="public-info-value">{{ $ticket->created_at->translatedFormat('d F Y, H:i') }} WIB</td>
                                        </tr>

                                        <!-- Completion Date -->
                                        @if ($ticket->done_at)
                                        <tr>
                                            <td class="public-info-label">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                </svg>
                                                Tanggal Selesai
                                            </td>
                                            <td class="public-info-value">{{ $ticket->done_at->translatedFormat('d F Y, H:i') }} WIB</td>
                                        </tr>
                                        @endif

                                        <!-- Description -->
                                        <tr>
                                            <td class="public-info-label">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                    <polyline points="14,2 14,8 20,8"></polyline>
                                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                                    <polyline points="10,9 9,9 8,9"></polyline>
                                                </svg>
                                                Deskripsi {{ $ticket->category === 'Peta Cetak' ? 'Permintaan' : 'Masalah' }}
                                            </td>
                                            <td class="public-info-value">{{ $ticket->description }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Photo Section -->
                                @if ($ticket->requestor_photo || $ticket->it_photo)
                                <hr class="public-section-divider">
                                <h3 class="public-section-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="9" cy="9" r="2"></circle>
                                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                    </svg>
                                    Dokumentasi
                                </h3>
                                <div class="public-photo-grid">
                                    @if ($ticket->requestor_photo)
                                    <div class="public-photo-card">
                                        <div class="public-photo-header">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                            Foto {{ $ticket->category === 'Peta Cetak' ? 'Pendukung' : '' }} dari Pengaju
                                        </div>
                                        <div class="public-photo-body">
                                            <img src="{{ Storage::url($ticket->requestor_photo) }}"
                                                 alt="Foto Pengaju" class="public-photo-preview">
                                            <a href="{{ Storage::url($ticket->requestor_photo) }}" target="_blank"
                                               class="public-photo-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                    <polyline points="15 3 21 3 21 9"></polyline>
                                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                                </svg>
                                                Lihat Foto Penuh
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    @if ($ticket->it_photo)
                                    <div class="public-photo-card">
                                        <div class="public-photo-header">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                                            </svg>
                                            Foto Bukti Penyelesaian IT
                                        </div>
                                        <div class="public-photo-body">
                                            <img src="{{ Storage::url($ticket->it_photo) }}"
                                                 alt="Foto IT" class="public-photo-preview">
                                            <a href="{{ Storage::url($ticket->it_photo) }}" target="_blank"
                                               class="public-photo-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                    <polyline points="15 3 21 3 21 9"></polyline>
                                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                                </svg>
                                                Lihat Foto Penuh
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                <!-- Status Alert -->
                                @if ($ticket->status === 'pending')
                                    <div class="public-alert public-alert-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        <div>
                                            {{ $ticket->category === 'Peta Cetak' ? 'Permintaan peta ini' : 'Tiket ini' }} sedang menunggu untuk ditangani oleh tim IT.
                                        </div>
                                    </div>
                                @elseif ($ticket->status === 'in_progress')
                                    <div class="public-alert public-alert-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                        </svg>
                                        <div>
                                            {{ $ticket->category === 'Peta Cetak' ? 'Permintaan peta ini' : 'Tiket ini' }} sedang dalam proses penanganan oleh tim IT.
                                        </div>
                                    </div>
                                @elseif ($ticket->status === 'completed')
                                    <div class="public-alert public-alert-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                        <div>
                                            {{ $ticket->category === 'Peta Cetak' ? 'Permintaan peta ini' : 'Tiket ini' }} telah selesai ditangani oleh tim IT.
                                            @if ($ticket->it_photo)
                                                <br><small>Silakan lihat foto bukti penyelesaian di atas.</small>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="public-actions">
                                    <a href="{{ route('haloip.index') }}" class="public-btn public-btn-back">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m12 19-7-7 7-7"/>
                                            <path d="M19 12H5"/>
                                        </svg>
                                        Kembali ke HaloIP
                                    </a>
                                    <a href="{{ route('haloip.index') }}" class="public-btn public-btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            @if ($ticket->category === 'Peta Cetak')
                                            <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                                            <line x1="8" y1="2" x2="8" y2="18"></line>
                                            <line x1="16" y1="6" x2="16" y2="22"></line>
                                            @else
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14,2 14,8 20,8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            @endif
                                        </svg>
                                        Lihat Semua {{ $ticket->category === 'Peta Cetak' ? 'Permintaan Peta' : 'Tiket' }}
                                    </a>
                                    {{-- <button onclick="window.print()" class="public-btn public-btn-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6,9 6,2 18,2 18,9"></polyline>
                                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h2"></path>
                                            <rect x="6" y="14" width="12" height="8"></rect>
                                        </svg>
                                        Cetak Halaman
                                    </button> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


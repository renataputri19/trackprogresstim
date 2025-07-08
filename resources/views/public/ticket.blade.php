@extends('haloip.layouts.app')
@section('title', 'Lihat Tiket - ' . $ticket->ticket_code)

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
                    <h1 class="haloip-title">Detail Tiket IT</h1>
                    <p class="haloip-subtitle">
                        Halaman publik untuk melihat detail dan bukti penyelesaian tiket
                    </p>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <div class="container mx-auto px-4 pb-8 ticketing">
            <!-- Ticket Information Card -->
            <div class="row mt-4">
                <div class="col-lg-8 mx-auto">
                    <div class="card ticket-card shadow-lg border-0">
                        <div class="card-header bg-gradient-to-r from-teal-500 to-emerald-500 text-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1 fw-bold">{{ $ticket->title }}</h5>
                                    <div class="ticket-code bg-white text-primary px-3 py-1 rounded-pill d-inline-block">
                                        {{ $ticket->ticket_code }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="status-{{ $ticket->status }}">
                                        {{ $ticket->status == 'pending' ? 'Menunggu' : ($ticket->status == 'in_progress' ? 'Sedang Diproses' : 'Selesai') }}
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
                                        <div class="info-value">{{ $ticket->requestor->name ?? 'Pengaju Tidak Diketahui' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">
                                            <i class="fas fa-door-open text-primary me-2"></i>Ruangan
                                        </label>
                                        <div class="info-value">{{ $ticket->ruangan }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">
                                            <i class="fas fa-user-cog text-primary me-2"></i>Staf IT
                                        </label>
                                        <div class="info-value">{{ $ticket->itStaff->name ?? 'Belum Ditugaskan' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">
                                            <i class="fas fa-calendar-plus text-primary me-2"></i>Tanggal Diajukan
                                        </label>
                                        <div class="info-value">{{ $ticket->created_at->locale('id')->isoFormat('D MMMM Y, HH:mm') }}</div>
                                    </div>
                                </div>
                                @if ($ticket->done_at)
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">
                                            <i class="fas fa-calendar-check text-success me-2"></i>Tanggal Selesai
                                        </label>
                                        <div class="info-value">{{ $ticket->done_at->locale('id')->isoFormat('D MMMM Y, HH:mm') }}</div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-12">
                                    <div class="info-group">
                                        <label class="info-label">
                                            <i class="fas fa-file-alt text-primary me-2"></i>Deskripsi Masalah
                                        </label>
                                        <div class="info-value">{{ $ticket->description }}</div>
                                    </div>
                                </div>

                            <!-- Photo Section -->
                            @if ($ticket->requestor_photo || $ticket->it_photo)
                            <hr class="my-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-images text-primary me-2"></i>Dokumentasi
                            </h6>
                            <div class="row g-3">
                                @if ($ticket->requestor_photo)
                                <div class="col-md-6">
                                    <div class="photo-card">
                                        <div class="photo-header">
                                            <i class="fas fa-user me-2"></i>Foto dari Pengaju
                                        </div>
                                        <div class="photo-body">
                                            <img src="{{ Storage::url($ticket->requestor_photo) }}"
                                                 alt="Foto Pengaju" class="photo-preview">
                                            <a href="{{ Storage::url($ticket->requestor_photo) }}" target="_blank"
                                               class="btn ticketing-btn ticketing-btn-outline-primary w-100">
                                                <i class="fas fa-external-link-alt me-2"></i>Lihat Foto Penuh
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if ($ticket->it_photo)
                                <div class="col-md-6">
                                    <div class="photo-card">
                                        <div class="photo-header">
                                            <i class="fas fa-tools me-2"></i>Foto Bukti Penyelesaian IT
                                        </div>
                                        <div class="photo-body">
                                            <img src="{{ Storage::url($ticket->it_photo) }}"
                                                 alt="Foto IT" class="photo-preview">
                                            <a href="{{ Storage::url($ticket->it_photo) }}" target="_blank"
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
                            @if ($ticket->status === 'pending')
                                <div class="alert alert-info mt-4">
                                    <i class="fas fa-clock me-2"></i>Tiket ini sedang menunggu untuk ditangani oleh tim IT.
                                </div>
                            @elseif ($ticket->status === 'in_progress')
                                <div class="alert alert-warning mt-4">
                                    <i class="fas fa-cog me-2"></i>Tiket ini sedang dalam proses penanganan oleh tim IT.
                                </div>
                            @elseif ($ticket->status === 'completed')
                                <div class="alert alert-success mt-4">
                                    <i class="fas fa-check-circle me-2"></i>Tiket ini telah selesai ditangani oleh tim IT.
                                    @if ($ticket->it_photo)
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
                <a href="{{ route('tickets.index') }}" class="haloip-btn-back me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7"/>
                        <path d="M19 12H5"/>
                    </svg>
                    Kembali ke HaloIP
                </a>
                <a href="{{ route('tickets.index') }}" class="haloip-btn haloip-btn-primary me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14,2 14,8 20,8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    Lihat Semua Tiket
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

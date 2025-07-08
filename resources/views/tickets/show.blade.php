@extends('haloip.layouts.app')
@section('title', 'Detail Tiket IT | HaloIP - RENTAK')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
    }

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

    .photo-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        overflow: hidden;
        background: #ffffff;
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

    .upload-area {
        position: relative;
    }

    .upload-area .form-control {
        border: 2px dashed #d1d5db;
        border-radius: 0.75rem;
        padding: 1rem;
        transition: all 0.3s ease;
    }

    .upload-area .form-control:hover {
        border-color: #0d9488;
        background-color: #f0fdfa;
    }

    .status-pending {
        background: linear-gradient(135deg, #fbbf24, #f59e0b) !important;
        color: #ffffff !important;
        border: none !important;
    }

    .status-in_progress {
        background: linear-gradient(135deg, #f97316, #ea580c) !important;
        color: #ffffff !important;
        border: none !important;
    }

    .status-completed {
        background: linear-gradient(135deg, #10b981, #059669) !important;
        color: #ffffff !important;
        border: none !important;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
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
                        Kelola dan perbarui status tiket IT yang sedang ditangani
                    </p>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <div class="container mx-auto px-4 pb-8 ticketing">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Back to HaloIP Button -->
            <div class="mb-4">
                <a href="{{ route('tickets.index') }}" class="haloip-btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7"/>
                        <path d="M19 12H5"/>
                    </svg>
                    Kembali ke HaloIP
                </a>
            </div>

            <!-- Ticket Information Card -->
            <div class="row mt-4">
                <div class="col-lg-8">
                    <div class="card ticket-card shadow-lg border-0">
                        <div class="card-header bg-gradient-primary text-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1 fw-bold">{{ $ticket->title }}</h5>
                                    <div class="ticket-code bg-white text-primary px-3 py-1 rounded-pill d-inline-block">
                                        {{ $ticket->ticket_code }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge status-{{ $ticket->status }} fs-6 px-3 py-2">
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
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>Ruangan
                                        </label>
                                        <div class="info-value">{{ $ticket->ruangan }}</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-group">
                                        <label class="info-label">
                                            <i class="fas fa-file-alt text-primary me-2"></i>Deskripsi
                                        </label>
                                        <div class="info-value">{{ $ticket->description }}</div>
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
                                            <a href="{{ Storage::url($ticket->requestor_photo) }}" target="_blank"
                                               class="btn ticketing-btn ticketing-btn-outline-primary w-100">
                                                <i class="fas fa-external-link-alt me-2"></i>Lihat Foto
                                            </a>
                                            @if (!file_exists(storage_path('app/public/' . $ticket->requestor_photo)))
                                                <small class="text-danger mt-2 d-block">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Foto tidak ditemukan
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if ($ticket->it_photo)
                                <div class="col-md-6">
                                    <div class="photo-card">
                                        <div class="photo-header">
                                            <i class="fas fa-tools me-2"></i>Foto Penyelesaian IT
                                        </div>
                                        <div class="photo-body">
                                            <a href="{{ Storage::url($ticket->it_photo) }}" target="_blank"
                                               class="btn ticketing-btn ticketing-btn-outline-primary w-100">
                                                <i class="fas fa-external-link-alt me-2"></i>Lihat Foto
                                            </a>
                                            @if (!file_exists(storage_path('app/public/' . $ticket->it_photo)))
                                                <small class="text-danger mt-2 d-block">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Foto tidak ditemukan
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-light py-3">
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-edit text-primary me-2"></i>Perbarui Tiket
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('tickets.update', $ticket) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group mb-4">
                                    <label for="status" class="form-label fw-semibold">
                                        <i class="fas fa-flag me-2 text-primary"></i>Status Tiket
                                    </label>
                                    <select name="status" id="status" class="form-select form-select-lg">
                                        <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>
                                            🕐 Menunggu
                                        </option>
                                        <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>
                                            ⚙️ Sedang Diproses
                                        </option>
                                        <option value="completed" {{ $ticket->status == 'completed' ? 'selected' : '' }}>
                                            ✅ Selesai
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="it_photo" class="form-label fw-semibold">
                                        <i class="fas fa-camera me-2 text-primary"></i>Foto Penyelesaian
                                        <span class="text-muted">(Opsional)</span>
                                    </label>
                                    <div class="upload-area">
                                        <input type="file" name="it_photo" id="it_photo" class="form-control" accept="image/*">
                                        <small class="form-text text-muted mt-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Upload foto sebagai bukti penyelesaian tiket
                                        </small>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="haloip-btn haloip-btn-primary">
                                        <i class="fas fa-save me-2"></i>Perbarui Tiket
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Quick Actions Card -->
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-body p-3">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-bolt text-warning me-2"></i>Aksi Cepat
                            </h6>
                            <div class="d-grid gap-2">
                                @if ($ticket->public_token)
                                <a href="{{ route('public.view', $ticket->public_token) }}" target="_blank"
                                   class="haloip-btn haloip-btn-outline">
                                    <i class="fas fa-external-link-alt me-2"></i>Lihat Halaman Publik
                                </a>
                                @endif
                                <a href="{{ route('tickets.manage') }}" class="haloip-btn haloip-btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Kelola Tiket
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
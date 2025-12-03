@extends('haloip.layouts.app')
@section('title', 'Perbarui Status - ' . $ticket->ticket_code . ' | HaloIP - RENTAK')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="{{ asset('css/haloip/haloip.css') }}" rel="stylesheet">
<link href="{{ asset('css/haloip/haloip-manage.css') }}" rel="stylesheet">
<style>
    /* Additional styles for update-status page - consistent with HaloIP design system */
    .haloip-detail-card {
        background: #ffffff;
        border-radius: 1.25rem;
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

    .haloip-detail-card-title i {
        color: #0d9488;
    }

    .haloip-detail-card-body {
        padding: 1.5rem;
    }

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

    .haloip-info-label i {
        color: #9ca3af;
        font-size: 0.875rem;
    }

    .haloip-info-value {
        font-size: 0.9375rem;
        color: #111827;
        font-weight: 500;
    }

    .haloip-form-group {
        margin-bottom: 1.25rem;
    }

    .haloip-form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .haloip-form-label i {
        color: #0d9488;
    }

    .haloip-form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        border: 2px solid #e5e7eb;
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
        display: block;
        margin-top: 0.375rem;
        font-size: 0.8125rem;
        color: #6b7280;
    }

    .haloip-upload-area {
        position: relative;
    }

    .haloip-photo-card {
        background: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(13, 148, 136, 0.1);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .haloip-photo-header {
        background: linear-gradient(135deg, #f0fdfa 0%, #ecfdf5 100%);
        padding: 1rem 1.25rem;
        border-bottom: 2px solid #e5e7eb;
        font-size: 0.875rem;
        font-weight: 600;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .haloip-photo-header i {
        color: #0d9488;
    }

    .haloip-photo-preview {
        width: 100%;
        height: auto;
        display: block;
    }
</style>
@endsection

@section('content')
    <div class="haloip-main-wrapper">
        <div class="haloip-bg-decoration"></div>

        <!-- Hero Section -->
        <section class="haloip-hero-modern">
            <div class="container">
                <div class="haloip-hero-content">
                    <div class="haloip-hero-text">
                        <h1 class="haloip-hero-title">
                            <i class="bi bi-pencil-square"></i>
                            Langkah 2: Perbarui Status {{ $ticket->category === 'Peta Cetak' ? 'Permintaan' : 'Tiket' }}
                        </h1>
                        <p class="haloip-hero-subtitle">Perbarui status dan upload foto bukti penyelesaian</p>
                    </div>
                    <div class="haloip-hero-actions">
                        <a href="{{ route('haloip.manage') }}" class="haloip-btn-hero haloip-btn-hero-secondary">
                            <i class="bi bi-arrow-left"></i>
                            Kembali ke HaloIP
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="haloip-content-section">
            <div class="container">
                <!-- Success Alert -->
                @if (session('success'))
                    <div class="alert alert-success d-flex align-items-center mb-4" role="alert" style="border-radius: 0.75rem; border: 2px solid #10b981;">
                        <i class="bi bi-check-circle-fill me-2" style="color: #10b981;"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-start mb-4" role="alert" style="border-radius: 0.75rem; border: 2px solid #ef4444;">
                        <i class="bi bi-exclamation-circle-fill me-2 mt-1" style="color: #ef4444;"></i>
                        <div>
                            <ul class="mb-0" style="padding-left: 1.25rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <!-- Left Column - Ticket Information -->
                    <div class="col-lg-7">
                        <div class="haloip-detail-card">
                            <div class="haloip-detail-card-header">
                                <h2 class="haloip-detail-card-title">
                                    <i class="bi bi-info-circle-fill"></i>
                                    Informasi {{ $ticket->category === 'Peta Cetak' ? 'Permintaan Peta' : 'Tiket' }}
                                </h2>
                            </div>
                            <div class="haloip-detail-card-body">
                                <!-- Ticket Code -->
                                <div class="haloip-info-group">
                                    <div class="haloip-info-label">
                                        <i class="bi bi-qr-code"></i>
                                        Kode {{ $ticket->category === 'Peta Cetak' ? 'Permintaan' : 'Tiket' }}
                                    </div>
                                    <div class="haloip-info-value">{{ $ticket->ticket_code }}</div>
                                </div>

                                <!-- Category -->
                                <div class="haloip-info-group">
                                    <div class="haloip-info-label">
                                        <i class="bi bi-tag-fill"></i>
                                        Kategori
                                    </div>
                                    <div class="haloip-info-value">
                                        <span class="haloip-category-badge">{{ $ticket->category }}</span>
                                    </div>
                                </div>

                                <!-- Title -->
                                <div class="haloip-info-group">
                                    <div class="haloip-info-label">
                                        <i class="bi bi-card-heading"></i>
                                        Judul
                                    </div>
                                    <div class="haloip-info-value">{{ $ticket->title }}</div>
                                </div>

                                <!-- Description -->
                                <div class="haloip-info-group">
                                    <div class="haloip-info-label">
                                        <i class="bi bi-file-text-fill"></i>
                                        Deskripsi {{ $ticket->category === 'Peta Cetak' ? 'Permintaan' : 'Masalah' }}
                                    </div>
                                    <div class="haloip-info-value">{{ $ticket->description }}</div>
                                </div>

                                <!-- Requestor -->
                                <div class="haloip-info-group">
                                    <div class="haloip-info-label">
                                        <i class="bi bi-person-fill"></i>
                                        Pengaju
                                    </div>
                                    <div class="haloip-info-value">{{ $ticket->requestor->name ?? 'Unknown' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Requestor Photo Preview -->
                        @if ($ticket->requestor_photo)
                            <div class="haloip-photo-card">
                                <div class="haloip-photo-header">
                                    <i class="bi bi-image-fill"></i>
                                    Foto dari Pengaju
                                </div>
                                <div class="p-3">
                                    <img src="{{ asset('storage/' . $ticket->requestor_photo) }}"
                                         alt="Foto Pengaju"
                                         class="haloip-photo-preview">
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column - Status Update Form -->
                    <div class="col-lg-5">
                        <div class="haloip-detail-card">
                            <div class="haloip-detail-card-header">
                                <h2 class="haloip-detail-card-title">
                                    <i class="bi bi-pencil-square"></i>
                                    Perbarui Status {{ $ticket->category === 'Peta Cetak' ? 'Permintaan' : 'Tiket' }}
                                </h2>
                            </div>
                            <div class="haloip-detail-card-body">
                                <form method="POST" action="{{ route('haloip.updateStatus', $ticket->id) }}" enctype="multipart/form-data">
                                    @csrf

                                    <!-- Status Selection -->
                                    <div class="haloip-form-group">
                                        <label for="status" class="haloip-form-label">
                                            <i class="bi bi-flag-fill"></i>
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

                                    <!-- Requestor WhatsApp Number (shown when status is completed) -->
                                    <div class="haloip-form-group" id="phone-number-group" style="display: none;">
                                        <label for="requestor_phone" class="haloip-form-label">
                                            <i class="bi bi-whatsapp" style="color: #25D366;"></i>
                                            Nomor WhatsApp Pengaju
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               class="haloip-form-control"
                                               id="requestor_phone"
                                               name="requestor_phone"
                                               placeholder="+628xxxxxxxxxx atau 08xxxxxxxxxx"
                                               value="{{ $ticket->requestor->phone_number ?? '' }}">
                                        <small class="haloip-form-help">
                                            <i class="bi bi-whatsapp" style="color: #25D366; vertical-align: middle; margin-right: 4px;"></i>
                                            Notifikasi WhatsApp akan dikirim ke pengaju saat tiket selesai
                                        </small>
                                    </div>

                                    <!-- IT Photo Upload -->
                                    <div class="haloip-form-group">
                                        <label for="it_photo" class="haloip-form-label">
                                            <i class="bi bi-camera-fill"></i>
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
                                        <button type="submit" class="haloip-btn-hero haloip-btn-hero-primary" style="width: 100%; justify-content: center;">
                                            <i class="bi bi-save-fill"></i>
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
                                    <i class="bi bi-image-fill"></i>
                                    Foto Bukti dari Petugas IT (Sebelumnya)
                                </div>
                                <div class="p-3">
                                    <img src="{{ asset('storage/' . $ticket->it_photo) }}"
                                         alt="Foto Bukti IT"
                                         class="haloip-photo-preview">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Dynamic photo requirement and phone number visibility based on status
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const photoInput = document.getElementById('it_photo');
        const photoHelp = document.getElementById('photo-help');
        const phoneNumberGroup = document.getElementById('phone-number-group');
        const phoneNumberInput = document.getElementById('requestor_phone');
        const isPetaCetak = {{ $ticket->category === 'Peta Cetak' ? 'true' : 'false' }};

        // Function to toggle phone number field visibility
        function togglePhoneNumberField(status) {
            if (status === 'completed') {
                phoneNumberGroup.style.display = 'block';
                phoneNumberInput.setAttribute('required', 'required');
            } else {
                phoneNumberGroup.style.display = 'none';
                phoneNumberInput.removeAttribute('required');
            }
        }

        // Function to toggle photo requirement
        function togglePhotoRequirement(status) {
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
        }

        // Initial state based on current status
        if (statusSelect) {
            togglePhoneNumberField(statusSelect.value);
            togglePhotoRequirement(statusSelect.value);

            // Listen for status changes
            statusSelect.addEventListener('change', function() {
                const status = this.value;
                togglePhoneNumberField(status);
                togglePhotoRequirement(status);
            });
        }
    });
</script>
@endsection




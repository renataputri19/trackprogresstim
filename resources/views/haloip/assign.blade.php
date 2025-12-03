@extends('haloip.layouts.app')
@section('title', 'Tugaskan Petugas IT - ' . $ticket->ticket_code . ' | HaloIP - RENTAK')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="{{ asset('css/haloip/haloip.css') }}" rel="stylesheet">
<link href="{{ asset('css/haloip/haloip-manage.css') }}" rel="stylesheet">
<style>
    /* Additional styles for assign page - consistent with HaloIP design system */
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

    .haloip-info-note {
        background: #f0fdfa;
        border-radius: 0.5rem;
        border: 1px solid #0d9488;
        padding: 1rem;
        margin-top: 1rem;
    }

    .haloip-info-note p {
        font-size: 0.875rem;
        color: #065f46;
        margin: 0;
    }

    .haloip-info-note i {
        color: #0d9488;
        vertical-align: middle;
        margin-right: 0.5rem;
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
                            <i class="bi bi-person-plus-fill"></i>
                            Langkah 1: Tugaskan Petugas IT
                        </h1>
                        <p class="haloip-hero-subtitle">Tugaskan petugas IT untuk menangani {{ $ticket->category === 'Peta Cetak' ? 'permintaan peta' : 'tiket' }} ini</p>
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
                    <!-- Left Column - Ticket Summary -->
                    <div class="col-lg-7">
                        <div class="haloip-detail-card">
                            <div class="haloip-detail-card-header">
                                <h2 class="haloip-detail-card-title">
                                    <i class="bi bi-info-circle-fill"></i>
                                    Ringkasan {{ $ticket->category === 'Peta Cetak' ? 'Permintaan Peta' : 'Tiket' }}
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
                                        Deskripsi Permintaan
                                    </div>
                                    <div class="haloip-info-value">{{ $ticket->description }}</div>
                                </div>

                                <!-- Status -->
                                <div class="haloip-info-group">
                                    <div class="haloip-info-label">
                                        <i class="bi bi-clock-fill"></i>
                                        Status
                                    </div>
                                    <div class="haloip-info-value">
                                        <span class="haloip-status-badge status-{{ $ticket->status }}">
                                            @if ($ticket->status === 'pending')
                                                Menunggu
                                            @elseif ($ticket->status === 'in_progress')
                                                Sedang Diproses
                                            @else
                                                Selesai
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <!-- Current IT Staff -->
                                @if ($ticket->it_staff_id)
                                    <div class="haloip-info-group">
                                        <div class="haloip-info-label">
                                            <i class="bi bi-person-badge-fill"></i>
                                            Petugas IT Saat Ini
                                        </div>
                                        <div class="haloip-info-value">{{ $ticket->itStaff->name ?? 'Belum Ditugaskan' }}</div>
                                    </div>
                                @endif
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

                    <!-- Right Column - Assignment Form -->
                    <div class="col-lg-5">
                        <div class="haloip-detail-card">
                            <div class="haloip-detail-card-header">
                                <h2 class="haloip-detail-card-title">
                                    <i class="bi bi-person-plus-fill"></i>
                                    Tugaskan Petugas IT
                                </h2>
                            </div>
                            <div class="haloip-detail-card-body">
                                <form method="POST" action="{{ route('haloip.storeAssignment', $ticket->id) }}">
                                    @csrf

                                    <div class="haloip-form-group">
                                        <label for="it_staff_id" class="haloip-form-label">
                                            <i class="bi bi-people-fill"></i>
                                            Pilih Petugas IT
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="haloip-form-control" id="it_staff_id" name="it_staff_id" required>
                                            <option value="">-- Pilih Petugas --</option>
                                            @foreach ($itStaffList as $staff)
                                                <option value="{{ $staff->id }}"
                                                        data-phone="{{ $staff->phone_number ?? '' }}"
                                                        {{ $ticket->it_staff_id == $staff->id ? 'selected' : '' }}>
                                                    {{ $staff->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="haloip-form-help">Tugaskan petugas IT untuk menangani {{ $ticket->category === 'Peta Cetak' ? 'permintaan peta' : 'tiket' }} ini</small>
                                    </div>

                                    <!-- IT Staff WhatsApp Number -->
                                    <div class="haloip-form-group" id="phone-number-group" style="display: none;">
                                        <label for="it_staff_phone" class="haloip-form-label">
                                            <i class="bi bi-whatsapp" style="color: #25D366;"></i>
                                            Nomor WhatsApp Petugas IT
                                        </label>
                                        <input type="text"
                                               class="haloip-form-control"
                                               id="it_staff_phone"
                                               name="it_staff_phone"
                                               placeholder="+628xxxxxxxxxx atau 08xxxxxxxxxx">
                                        <small class="haloip-form-help">
                                            <i class="bi bi-whatsapp" style="color: #25D366; vertical-align: middle; margin-right: 4px;"></i>
                                            Notifikasi WhatsApp akan dikirim ke petugas IT yang ditugaskan
                                        </small>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="haloip-btn-hero haloip-btn-hero-primary" style="width: 100%; justify-content: center;">
                                            <i class="bi bi-check-lg"></i>
                                            Tugaskan Petugas
                                        </button>
                                    </div>
                                </form>

                                @if ($ticket->it_staff_id)
                                    <div class="haloip-info-note">
                                        <p>
                                            <i class="bi bi-info-circle-fill"></i>
                                            <strong>Catatan:</strong> {{ $ticket->category === 'Peta Cetak' ? 'Permintaan peta' : 'Tiket' }} ini sudah ditugaskan ke <strong>{{ $ticket->itStaff->name }}</strong>. Anda dapat mengubah penugasan dengan memilih petugas IT lain.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Dynamic phone number field based on IT staff selection
    document.addEventListener('DOMContentLoaded', function() {
        const itStaffSelect = document.getElementById('it_staff_id');
        const phoneNumberGroup = document.getElementById('phone-number-group');
        const phoneNumberInput = document.getElementById('it_staff_phone');

        // Function to update phone number field based on selected IT staff
        function updatePhoneNumberField() {
            const selectedOption = itStaffSelect.options[itStaffSelect.selectedIndex];

            if (selectedOption && selectedOption.value) {
                // Show the phone number field
                phoneNumberGroup.style.display = 'block';

                // Get the phone number from data attribute
                const phoneNumber = selectedOption.getAttribute('data-phone') || '';
                phoneNumberInput.value = phoneNumber;
            } else {
                // Hide the phone number field when no IT staff selected
                phoneNumberGroup.style.display = 'none';
                phoneNumberInput.value = '';
            }
        }

        // Initial state based on current selection
        if (itStaffSelect) {
            updatePhoneNumberField();

            // Listen for selection changes
            itStaffSelect.addEventListener('change', function() {
                updatePhoneNumberField();
            });
        }
    });
</script>
@endsection




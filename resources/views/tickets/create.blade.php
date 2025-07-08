@extends('haloip.layouts.app')
@section('title', 'Ajukan Tiket IT | HaloIP - RENTAK')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    .upload-area {
        position: relative;
    }

    .upload-area .form-control {
        border: 2px dashed #d1d5db;
        border-radius: 0.75rem;
        padding: 1rem;
        transition: all 0.3s ease;
        background-color: #f9fafb;
    }

    .upload-area .form-control:hover {
        border-color: #0d9488;
        background-color: #f0fdfa;
    }

    .upload-area .form-control:focus {
        border-color: #0d9488;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
    }

    .form-control-lg {
        border-radius: 0.75rem;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .form-control-lg:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
    }

    .card-header {
        background: linear-gradient(135deg, #0d9488 0%, #10b981 100%) !important;
    }
</style>
@endsection

@section('content')
    <div class="haloip-container">
        <!-- Hero Section -->
        <section class="haloip-hero">
            <div class="container mx-auto px-4">
                <div class="haloip-hero-content">
                    <h1 class="haloip-title">Ajukan Tiket IT</h1>
                    <p class="haloip-subtitle">
                        Laporkan masalah IT atau ajukan permintaan bantuan teknis kepada tim IT BPS Kota Batam
                    </p>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <div class="container mx-auto px-4 pb-8 ticketing">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Enhanced Form Card -->
            <div class="row mt-4">
                <div class="col-lg-8 mx-auto">
                    <div class="card ticket-card shadow-lg border-0">
                        <div class="card-header bg-gradient-to-r from-teal-500 to-emerald-500 text-white py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-plus-circle me-2"></i>Form Pengajuan Tiket IT
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="title" class="form-label fw-semibold">
                                                <i class="fas fa-heading text-primary me-2"></i>Judul Masalah
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="title" id="title"
                                                   class="form-control form-control-lg @error('title') is-invalid @enderror"
                                                   value="{{ old('title') }}"
                                                   placeholder="Contoh: Komputer tidak bisa menyala"
                                                   required>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="ruangan" class="form-label fw-semibold">
                                                <i class="fas fa-door-open text-primary me-2"></i>Ruangan
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="ruangan" id="ruangan"
                                                    class="form-control form-control-lg @error('ruangan') is-invalid @enderror"
                                                    required>
                                                <option value="">Pilih Ruangan</option>
                                                @foreach ($ruanganList as $ruangan)
                                                    <option value="{{ $ruangan }}" {{ old('ruangan') == $ruangan ? 'selected' : '' }}>{{ $ruangan }}</option>
                                                @endforeach
                                            </select>
                                            @error('ruangan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="description" class="form-label fw-semibold">
                                                <i class="fas fa-file-alt text-primary me-2"></i>Deskripsi Masalah
                                                <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="description" id="description"
                                                      class="form-control @error('description') is-invalid @enderror"
                                                      rows="5"
                                                      placeholder="Jelaskan masalah IT yang Anda alami dengan detail..."
                                                      required>{{ old('description') }}</textarea>
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Jelaskan masalah IT yang Anda alami dengan detail agar tim IT dapat membantu dengan lebih efektif.
                                            </div>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="requestor_photo" class="form-label fw-semibold">
                                                <i class="fas fa-camera text-primary me-2"></i>Foto Pendukung
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="upload-area">
                                                <input type="file" name="requestor_photo" id="requestor_photo"
                                                       class="form-control @error('requestor_photo') is-invalid @enderror"
                                                       accept="image/*" required>
                                                <div class="form-text mt-2">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Upload foto yang menunjukkan masalah IT yang Anda alami. Format: JPG, PNG. Maksimal 2MB.
                                                </div>
                                                @error('requestor_photo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="d-flex gap-3 justify-content-center">
                                    <button type="submit" class="haloip-btn haloip-btn-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14,2 14,8 20,8"></polyline>
                                            <line x1="12" y1="18" x2="12" y2="12"></line>
                                            <line x1="9" y1="15" x2="15" y2="15"></line>
                                        </svg>
                                        Ajukan Tiket
                                    </button>
                                    <a href="{{ route('tickets.index') }}" class="haloip-btn-back">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m12 19-7-7 7-7"></path>
                                            <path d="M19 12H5"></path>
                                        </svg>
                                        Kembali ke HaloIP
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
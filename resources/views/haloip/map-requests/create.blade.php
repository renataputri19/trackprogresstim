@extends('haloip.layouts.app')
@section('title', 'Ajukan Permintaan Peta | HaloIP - RENTAK')

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
                    <h1 class="haloip-title">Ajukan Permintaan Peta</h1>
                    <p class="haloip-subtitle">
                        Ajukan permintaan produk statistik berupa peta untuk mendukung Sensus Ekonomi 2026 dan kebutuhan lainnya
                    </p>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <div class="container mx-auto px-4 pb-8 ticketing">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Enhanced Form Card -->
            <div class="row mt-4">
                <div class="col-lg-8 mx-auto">
                    <div class="card ticket-card shadow-lg border-0">
                        <div class="card-header bg-gradient-to-r from-teal-500 to-emerald-500 text-white py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-map me-2"></i>Form Pengajuan Permintaan Peta
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('map-requests.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="title" class="form-label fw-semibold">
                                                <i class="fas fa-heading text-primary me-2"></i>Judul Permintaan
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="title" id="title"
                                                class="form-control form-control-lg @error('title') is-invalid @enderror"
                                                value="{{ old('title') }}" required
                                                placeholder="Contoh: Peta Kecamatan untuk Sensus Ekonomi 2026">
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="map_type" class="form-label fw-semibold">
                                                <i class="fas fa-layer-group text-info me-2"></i>Jenis Peta
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="map_type" id="map_type"
                                                class="form-control form-control-lg @error('map_type') is-invalid @enderror" required>
                                                <option value="">Pilih Jenis Peta</option>
                                                @foreach($mapTypes as $key => $value)
                                                    <option value="{{ $key }}" {{ old('map_type') == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('map_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kdkec" class="form-label fw-semibold">
                                                <i class="fas fa-map-marker-alt text-warning me-2"></i>Kecamatan
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="kdkec" id="kdkec"
                                                class="form-control form-control-lg @error('kdkec') is-invalid @enderror" required>
                                                <option value="">Pilih Kecamatan</option>
                                                @foreach($districts as $code => $name)
                                                    <option value="{{ $code }}" {{ old('kdkec') == $code ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('kdkec')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kddesa" class="form-label fw-semibold">
                                                <i class="fas fa-location-dot text-success me-2"></i>Kelurahan
                                                <small class="text-muted">(Opsional untuk Peta Kecamatan)</small>
                                            </label>
                                            <select name="kddesa" id="kddesa"
                                                class="form-control form-control-lg @error('kddesa') is-invalid @enderror">
                                                <option value="">Pilih Kelurahan</option>
                                            </select>
                                            @error('kddesa')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="description" class="form-label fw-semibold">
                                                <i class="fas fa-align-left text-warning me-2"></i>Deskripsi Permintaan
                                                <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="description" id="description" rows="5"
                                                class="form-control form-control-lg @error('description') is-invalid @enderror"
                                                required placeholder="Jelaskan kebutuhan peta Anda secara detail, termasuk tujuan penggunaan dan spesifikasi yang diinginkan...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group upload-area">
                                            <label for="requestor_photo" class="form-label fw-semibold">
                                                <i class="fas fa-camera text-success me-2"></i>Foto Pendukung
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="file" name="requestor_photo" id="requestor_photo"
                                                class="form-control @error('requestor_photo') is-invalid @enderror"
                                                accept="image/*" required>
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Upload foto yang mendukung permintaan peta Anda (contoh: sketsa area, referensi lokasi, dll). Maksimal 2MB.
                                            </div>
                                            @error('requestor_photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="d-flex gap-3 justify-content-center">
                                    <button type="submit" class="haloip-btn haloip-btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polygon points="3 6 9 1 15 6 21 1 21 14 15 9 9 14 3 9"></polygon>
                                            <line x1="9" y1="1" x2="9" y2="14"></line>
                                            <line x1="15" y1="6" x2="15" y2="9"></line>
                                        </svg>
                                        Ajukan Permintaan Peta
                                    </button>
                                    <a href="{{ route('map-requests.index') }}" class="haloip-btn-back">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kdkecSelect = document.getElementById('kdkec');
            const kddesaSelect = document.getElementById('kddesa');
            const mapTypeSelect = document.getElementById('map_type');

            // Function to load villages based on selected district
            function loadVillages(districtCode) {
                if (!districtCode) {
                    kddesaSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                    return;
                }

                fetch(`/api/villages/${districtCode}`)
                    .then(response => response.json())
                    .then(data => {
                        kddesaSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                        Object.entries(data).forEach(([code, name]) => {
                            const option = document.createElement('option');
                            option.value = code;
                            option.textContent = name;
                            kddesaSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading villages:', error);
                        kddesaSelect.innerHTML = '<option value="">Error loading villages</option>';
                    });
            }

            // Handle district change
            kdkecSelect.addEventListener('change', function() {
                loadVillages(this.value);
            });

            // Handle map type change to show/hide village requirement
            mapTypeSelect.addEventListener('change', function() {
                const villageGroup = kddesaSelect.closest('.form-group');
                const villageLabel = villageGroup.querySelector('label');
                
                if (this.value === 'kelurahan') {
                    villageLabel.innerHTML = '<i class="fas fa-location-dot text-success me-2"></i>Kelurahan <span class="text-danger">*</span>';
                    kddesaSelect.required = true;
                } else {
                    villageLabel.innerHTML = '<i class="fas fa-location-dot text-success me-2"></i>Kelurahan <small class="text-muted">(Opsional untuk Peta Kecamatan)</small>';
                    kddesaSelect.required = false;
                }
            });

            // Load villages if district is already selected (for form validation errors)
            if (kdkecSelect.value) {
                loadVillages(kdkecSelect.value);
            }
        });
    </script>
@endsection

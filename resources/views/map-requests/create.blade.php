@extends('haloip.layouts.app')
@section('title', 'Ajukan Permintaan Peta | HaloIP - RENTAK')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <div class="ticket-container mt-4">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form action="{{ route('map-requests.store') }}" method="POST" enctype="multipart/form-data" class="p-4">
                @csrf
                <div class="form-group mb-4">
                    <label for="title" class="form-label">Judul Permintaan</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <label for="map_type" class="form-label">Jenis Peta</label>
                    <select name="map_type" id="map_type" class="form-control @error('map_type') is-invalid @enderror" required>
                        <option value="">Pilih Jenis Peta</option>
                        @foreach ($mapTypes as $value => $label)
                            <option value="{{ $value }}" {{ old('map_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('map_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <label for="kdkec" class="form-label">Kecamatan</label>
                    <select name="kdkec" id="kdkec" class="form-control @error('kdkec') is-invalid @enderror" required>
                        <option value="">Pilih Kecamatan</option>
                        @foreach ($districts as $code => $label)
                            <option value="{{ $code }}" {{ old('kdkec') == $code ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('kdkec')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <label for="kddesa" class="form-label">Kelurahan <span class="text-muted">(Opsional)</span></label>
                    <select name="kddesa" id="kddesa" class="form-control @error('kddesa') is-invalid @enderror">
                        <option value="">Pilih Kelurahan</option>
                    </select>
                    <div class="form-text">Pilih kelurahan spesifik jika diperlukan. Kosongkan jika permintaan untuk seluruh kecamatan.</div>
                    @error('kddesa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <label for="description" class="form-label">Deskripsi Permintaan</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                    <div class="form-text">Jelaskan detail peta yang dibutuhkan, termasuk area spesifik, format yang diinginkan, dan keperluan penggunaan.</div>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <label for="requestor_photo" class="form-label">Foto Pendukung</label>
                    <input type="file" name="requestor_photo" id="requestor_photo" class="form-control-file @error('requestor_photo') is-invalid @enderror" accept="image/*" required>
                    <div class="form-text">Upload foto yang mendukung permintaan peta Anda (contoh: sketsa area, referensi lokasi, dll). Maksimal 2MB.</div>
                    @error('requestor_photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex gap-2">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kdkecSelect = document.getElementById('kdkec');
            const kddesaSelect = document.getElementById('kddesa');

            // Function to populate villages based on selected district
            function populateVillages(districtCode) {
                // Clear current options
                kddesaSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';

                if (!districtCode) {
                    kddesaSelect.disabled = true;
                    return;
                }

                // Enable the village dropdown
                kddesaSelect.disabled = false;

                // Fetch villages for the selected district
                fetch(`/api/villages/${districtCode}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(villages => {
                    // Populate the village dropdown
                    for (const [code, name] of Object.entries(villages)) {
                        const option = document.createElement('option');
                        option.value = code;
                        option.textContent = name;

                        // Check if this was the previously selected value (for form validation errors)
                        if (code === '{{ old("kddesa") }}') {
                            option.selected = true;
                        }

                        kddesaSelect.appendChild(option);
                    }
                })
                .catch(error => {
                    console.error('Error fetching villages:', error);
                    kddesaSelect.innerHTML = '<option value="">Error loading villages</option>';
                });
            }

            // Handle district selection change
            kdkecSelect.addEventListener('change', function() {
                populateVillages(this.value);
            });

            // Initialize villages if district is already selected (for form validation errors)
            if (kdkecSelect.value) {
                populateVillages(kdkecSelect.value);
            } else {
                kddesaSelect.disabled = true;
            }
        });
    </script>
@endsection

@extends('haloip.layouts.app')
@section('title', 'Ajukan Tiket | HaloIP - RENTAK')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    /* Modern Form Styling */
    .haloip-form-wrapper {
        background: #ffffff;
        border-radius: 1.25rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(13, 148, 136, 0.1);
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out 0.3s both;
    }

    .haloip-form-header {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
        border-bottom: 2px solid #e5e7eb;
    }

    .haloip-form-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.5rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
    }

    .haloip-form-body {
        padding: 2rem;
    }

    .haloip-form-group {
        margin-bottom: 1.5rem;
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

    .haloip-form-label svg {
        color: #0d9488;
    }

    .haloip-form-label .text-danger {
        color: #ef4444;
        margin-left: 0.25rem;
    }

    .haloip-form-input,
    .haloip-form-select,
    .haloip-form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        color: #111827;
        background-color: #ffffff;
        transition: all 0.2s;
    }

    .haloip-form-input:focus,
    .haloip-form-select:focus,
    .haloip-form-textarea:focus {
        outline: none;
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
    }

    .haloip-form-textarea {
        resize: vertical;
        min-height: 120px;
    }

    .haloip-form-help {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }

    .haloip-upload-area {
        position: relative;
    }

    .haloip-upload-area .haloip-form-input {
        border: 2px dashed #d1d5db;
        background-color: #f9fafb;
        cursor: pointer;
    }

    .haloip-upload-area .haloip-form-input:hover {
        border-color: #0d9488;
        background-color: #f0fdfa;
    }

    .haloip-form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #f3f4f6;
    }

    .haloip-btn-submit {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
        color: #ffffff;
        border: none;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.3);
    }

    .haloip-btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        background: #ffffff;
        color: #0d9488;
        border: 2px solid #0d9488;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
    }

    .category-dependent {
        display: none;
    }

    @media (max-width: 768px) {
        .haloip-form-body {
            padding: 1.5rem;
        }

        .haloip-form-actions {
            flex-direction: column;
        }

        .haloip-btn-submit,
        .haloip-btn-cancel {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="haloip-main-wrapper">
    <!-- Decorative Background Elements -->
    <div class="haloip-bg-decoration"></div>

    <!-- Hero Section with Modern Design -->
    <section class="haloip-hero-modern">
        <div class="container mx-auto px-4">
            <div class="haloip-hero-text">
                <div class="haloip-badge-new">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14,2 14,8 20,8"></polyline>
                        <line x1="12" y1="18" x2="12" y2="12"></line>
                        <line x1="9" y1="15" x2="15" y2="15"></line>
                    </svg>
                    Pengajuan Tiket Baru
                </div>
                <h1 class="haloip-hero-title">Ajukan Tiket</h1>
                <p class="haloip-hero-subtitle">
                    Ajukan permintaan layanan atau bantuan kepada tim IT dan Pengolahan BPS Kota Batam
                </p>

                <!-- Back to HaloIP Button -->
                <div class="haloip-hero-actions">
                    <a href="{{ route('haloip.index') }}" class="haloip-btn-hero haloip-btn-hero-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m12 19-7-7 7-7"/>
                            <path d="M19 12H5"/>
                        </svg>
                        Kembali ke HaloIP
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <div class="haloip-content-wrapper">
        <div class="container mx-auto px-4 pb-8">
            <!-- Error Messages -->
            @if (session('error'))
                <div class="haloip-alert haloip-alert-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="haloip-alert-close" onclick="this.parentElement.remove()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="haloip-alert haloip-alert-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    <div>
                        <ul style="margin: 0; padding-left: 1.5rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="haloip-alert-close" onclick="this.parentElement.remove()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Modern Form Wrapper -->
            <div class="row mt-4">
                <div class="col-lg-8 mx-auto">
                    <div class="haloip-form-wrapper">
                        <div class="haloip-form-header">
                            <h2 class="haloip-form-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14,2 14,8 20,8"></polyline>
                                    <line x1="12" y1="18" x2="12" y2="12"></line>
                                    <line x1="9" y1="15" x2="15" y2="15"></line>
                                </svg>
                                Form Pengajuan Tiket
                            </h2>
                        </div>
                        <div class="haloip-form-body">
                            <form method="POST" action="{{ route('haloip.store') }}" enctype="multipart/form-data" id="ticketForm">
                                @csrf

                                <!-- Category Selection -->
                                <div class="haloip-form-group">
                                    <label for="category" class="haloip-form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                                            <line x1="4" y1="22" x2="4" y2="15"></line>
                                        </svg>
                                        Kategori
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="haloip-form-select" id="category" name="category" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $key => $value)
                                            <option value="{{ $key }}" {{ old('category', request('category')) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="haloip-form-help">Pilih kategori yang sesuai dengan kebutuhan Anda</small>
                                </div>

                                <!-- Title -->
                                <div class="haloip-form-group">
                                    <label for="title" class="haloip-form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        </svg>
                                        Judul
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="haloip-form-input" id="title" name="title"
                                           value="{{ old('title') }}" required maxlength="255"
                                           placeholder="Contoh: Printer tidak bisa mencetak">
                                    <small class="haloip-form-help">Berikan judul yang jelas dan singkat</small>
                                </div>

                                <!-- Description -->
                                <div class="haloip-form-group">
                                    <label for="description" class="haloip-form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="17" y1="10" x2="3" y2="10"></line>
                                            <line x1="21" y1="6" x2="3" y2="6"></line>
                                            <line x1="21" y1="14" x2="3" y2="14"></line>
                                            <line x1="17" y1="18" x2="3" y2="18"></line>
                                        </svg>
                                        Deskripsi
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="haloip-form-textarea" id="description" name="description"
                                              rows="5" required placeholder="Jelaskan masalah atau permintaan Anda secara detail...">{{ old('description') }}</textarea>
                                    <small class="haloip-form-help">Berikan detail sebanyak mungkin untuk mempercepat penanganan</small>
                                </div>

                                <!-- Ruangan (for non-Peta Cetak categories) -->
                                <div class="haloip-form-group category-dependent" id="ruangan-field">
                                    <label for="ruangan" class="haloip-form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="9" y1="3" x2="9" y2="21"></line>
                                        </svg>
                                        Ruangan
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="haloip-form-select" id="ruangan" name="ruangan">
                                        <option value="">Pilih Ruangan</option>
                                        @foreach ($ruanganList as $room)
                                            <option value="{{ $room }}" {{ old('ruangan') == $room ? 'selected' : '' }}>
                                                {{ $room }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="haloip-form-help">Pilih ruangan tempat masalah terjadi</small>
                                </div>

                                <!-- Map Type (for Peta Cetak category) -->
                                <div class="haloip-form-group category-dependent" id="map-type-field">
                                    <label for="map_type" class="haloip-form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                                            <line x1="8" y1="2" x2="8" y2="18"></line>
                                            <line x1="16" y1="6" x2="16" y2="22"></line>
                                        </svg>
                                        Jenis Peta
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="haloip-form-select" id="map_type" name="map_type">
                                        <option value="">Pilih Jenis Peta</option>
                                        @foreach ($mapTypes as $key => $value)
                                            <option value="{{ $key }}" {{ old('map_type') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="haloip-form-help">Pilih jenis peta yang dibutuhkan</small>
                                </div>

                                <!-- District (for Peta Cetak category) -->
                                <div class="haloip-form-group category-dependent" id="district-field">
                                    <label for="kdkec" class="haloip-form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        Kecamatan
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="haloip-form-select" id="kdkec" name="kdkec">
                                        <option value="">Pilih Kecamatan</option>
                                        @foreach ($districts as $code => $name)
                                            <option value="{{ $code }}" {{ old('kdkec') == $code ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="haloip-form-help">Pilih kecamatan untuk peta</small>
                                </div>

                                <!-- Village (for Peta Cetak category - kelurahan type) -->
                                <div class="haloip-form-group category-dependent" id="village-field" style="display: none;">
                                    <label for="kddesa" class="haloip-form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        Kelurahan
                                    </label>
                                    <select class="haloip-form-select" id="kddesa" name="kddesa">
                                        <option value="">Pilih Kelurahan</option>
                                    </select>
                                    <small class="haloip-form-help">Pilih kelurahan untuk peta (opsional untuk peta kecamatan)</small>
                                </div>

                                <!-- Requestor Photo -->
                                <div class="haloip-form-group haloip-upload-area" id="photo-field">
                                    <label for="requestor_photo" class="haloip-form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                        Foto Bukti
                                        <span class="text-danger" id="photo-required">*</span>
                                    </label>
                                    <input type="file" class="haloip-form-input" id="requestor_photo"
                                           name="requestor_photo" accept="image/*">
                                    <small class="haloip-form-help" id="photo-help">Upload foto bukti masalah (maksimal 2MB)</small>
                                </div>

                                <!-- Form Actions -->
                                <div class="haloip-form-actions">
                                    <button type="submit" class="haloip-btn-submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="22" y1="2" x2="11" y2="13"></line>
                                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                        </svg>
                                        Ajukan Tiket
                                    </button>
                                    <a href="{{ route('haloip.index') }}" class="haloip-btn-cancel">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                        Batal
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category');
    const ruanganField = document.getElementById('ruangan-field');
    const mapTypeField = document.getElementById('map-type-field');
    const districtField = document.getElementById('district-field');
    const villageField = document.getElementById('village-field');
    const photoField = document.getElementById('photo-field');
    const photoRequired = document.getElementById('photo-required');
    const photoHelp = document.getElementById('photo-help');
    const photoInput = document.getElementById('requestor_photo');
    const ruanganInput = document.getElementById('ruangan');
    const mapTypeInput = document.getElementById('map_type');
    const districtInput = document.getElementById('kdkec');
    const villageInput = document.getElementById('kddesa');

    function updateFormFields() {
        const category = categorySelect.value;
        const isPetaCetak = category === 'Peta Cetak';

        // Hide all category-dependent fields first
        ruanganField.style.display = 'none';
        mapTypeField.style.display = 'none';
        districtField.style.display = 'none';
        villageField.style.display = 'none';

        // Remove required attributes
        ruanganInput.removeAttribute('required');
        mapTypeInput.removeAttribute('required');
        districtInput.removeAttribute('required');

        if (isPetaCetak) {
            // Show map-related fields
            mapTypeField.style.display = 'block';
            districtField.style.display = 'block';

            // Make them required
            mapTypeInput.setAttribute('required', 'required');
            districtInput.setAttribute('required', 'required');

            // Photo is optional for Peta Cetak
            photoRequired.style.display = 'none';
            photoInput.removeAttribute('required');
            photoHelp.textContent = 'Upload foto bukti (opsional, maksimal 2MB)';
        } else if (category) {
            // Show ruangan field for other categories
            ruanganField.style.display = 'block';
            ruanganInput.setAttribute('required', 'required');

            // Photo is required for other categories
            photoRequired.style.display = 'inline';
            photoInput.setAttribute('required', 'required');
            photoHelp.textContent = 'Upload foto bukti masalah (maksimal 2MB)';
        }
    }

    // Handle category change
    categorySelect.addEventListener('change', updateFormFields);

    // Handle map type change
    document.getElementById('map_type').addEventListener('change', function() {
        if (this.value === 'kelurahan') {
            villageField.style.display = 'block';
        } else {
            villageField.style.display = 'none';
            villageInput.value = '';
        }
    });

    // Handle district change - load villages
    districtInput.addEventListener('change', function() {
        const districtCode = this.value;
        if (districtCode) {
            fetch(`/api/villages/${districtCode}`)
                .then(response => response.json())
                .then(data => {
                    villageInput.innerHTML = '<option value="">Pilih Kelurahan</option>';
                    Object.entries(data).forEach(([code, name]) => {
                        const option = document.createElement('option');
                        option.value = code;
                        option.textContent = name;
                        villageInput.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading villages:', error));
        }
    });

    // Initialize form on page load
    updateFormFields();
});
</script>
@endsection


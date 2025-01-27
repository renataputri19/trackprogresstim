<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Input Data Usaha - RENTAK</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
        }

        body {
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: var(--primary-color);
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            color: white !important;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1.5rem;
            border-radius: 12px 12px 0 0 !important;
        }

        .card-title {
            color: var(--primary-color);
            font-weight: 600;
            margin: 0;
        }

        #map {
            height: 400px;
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-1px);
        }

        .location-buttons {
            margin-bottom: 1rem;
        }

        .location-accuracy {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.5rem;
        }

        .loading-indicator {
            display: none;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
        }

        .required-field::after {
            content: "*";
            color: #ef4444;
            margin-left: 4px;
        }

        .map-container {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .map-overlay {
            position: absolute;
            bottom: 1rem;
            left: 1rem;
            z-index: 1000;
            background: white;
            padding: 0.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-size: 0.875rem;
            color: #374151;
        }

        .footer {
            margin-top: auto;
            padding: 1rem 0;
            background-color: white;
            border-top: 1px solid #e5e7eb;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            #map {
                height: 300px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-map-marker-alt me-2"></i>
                RENTAK - Direktori Usaha
            </a>
            <a href="{{ route('business.list') }}" class="btn btn-light">
                <i class="fas fa-list me-2"></i>
                Lihat Data
            </a>
        </div>
    </nav>

    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-plus-circle me-2"></i>
                            Input Data Usaha Baru
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="businessForm" method="POST" action="{{ route('business.store') }}">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_usaha" class="form-label required-field">Nama Usaha</label>
                                        <input type="text" class="form-control" id="nama_usaha" name="nama_usaha"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="deskripsi_usaha" class="form-label">Deskripsi Usaha</label>
                                        <textarea class="form-control" id="deskripsi_usaha" name="deskripsi_usaha" rows="3"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="alamat" class="form-label required-field">Alamat</label>
                                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label required-field">Lokasi pada Peta</label>
                                    <div class="location-buttons">
                                        <button type="button" id="getLocationBtn" class="btn btn-primary mb-2">
                                            <i class="fas fa-location-arrow me-2"></i>
                                            Dapatkan Lokasi Saat Ini
                                        </button>
                                        <div class="loading-indicator">
                                            <i class="fas fa-spinner fa-spin"></i>
                                            <span>Mencari lokasi...</span>
                                        </div>
                                    </div>
                                    <div class="map-container">
                                        <div id="map"></div>
                                        <div class="map-overlay d-none" id="mapInstructions">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Klik pada peta untuk menandai lokasi
                                        </div>
                                    </div>
                                    <div class="location-accuracy" id="locationAccuracy"></div>
                                    <input type="hidden" id="latitude" name="latitude" required>
                                    <input type="hidden" id="longitude" name="longitude" required>
                                    <input type="hidden" id="accuracy" name="accuracy">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo me-2"></i>
                                    Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container text-center">
            <small class="text-muted">© 2025 RENTAK - BPS Batam. All rights reserved.</small>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Initialize map
        const map = L.map('map').setView([1.0456, 104.0304], 12);
        let marker;
        let locationCircle;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Show map instructions after map is loaded
        map.on('load', function() {
            document.getElementById('mapInstructions').classList.remove('d-none');
        });

        // Handle map click
        map.on('click', function(e) {
            setLocation(e.latlng.lat, e.latlng.lng);
            document.getElementById('mapInstructions').classList.add('d-none');
        });

        // Function to set location on map
        function setLocation(lat, lng, accuracy = null) {
            if (marker) map.removeLayer(marker);
            if (locationCircle) map.removeLayer(locationCircle);

            marker = L.marker([lat, lng]).addTo(map);

            if (accuracy) {
                locationCircle = L.circle([lat, lng], {
                    radius: accuracy,
                    fillColor: '#3388ff',
                    fillOpacity: 0.1,
                    color: '#3388ff',
                    opacity: 0.3
                }).addTo(map);

                document.getElementById('locationAccuracy').innerHTML =
                    `<i class="fas fa-bullseye me-2"></i>Akurasi lokasi: ±${Math.round(accuracy)} meter`;
                document.getElementById('accuracy').value = accuracy;
            }

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            map.setView([lat, lng], 16);
        }

        // Get current location button
        document.getElementById('getLocationBtn').addEventListener('click', function() {
            if (!navigator.geolocation) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Geolocation tidak didukung oleh browser Anda'
                });
                return;
            }

            const loadingIndicator = document.querySelector('.loading-indicator');
            loadingIndicator.style.display = 'flex';
            this.disabled = true;

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    setLocation(
                        position.coords.latitude,
                        position.coords.longitude,
                        position.coords.accuracy
                    );

                    loadingIndicator.style.display = 'none';
                    document.getElementById('getLocationBtn').disabled = false;
                    document.getElementById('mapInstructions').classList.add('d-none');
                },
                function(error) {
                    let message;
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            message = "Anda menolak permintaan geolokasi.";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message = "Informasi lokasi tidak tersedia.";
                            break;
                        case error.TIMEOUT:
                            message = "Waktu permintaan lokasi habis.";
                            break;
                        case error.UNKNOWN_ERROR:
                            message = "Terjadi kesalahan yang tidak diketahui.";
                            break;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: message
                    });

                    loadingIndicator.style.display = 'none';
                    document.getElementById('getLocationBtn').disabled = false;
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });

        // Form submission
        document.getElementById('businessForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!document.getElementById('latitude').value || !document.getElementById('longitude').value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Lokasi Diperlukan',
                    text: 'Silakan pilih lokasi pada peta atau gunakan lokasi otomatis'
                });
                return;
            }

            const formData = new FormData(this);

            // Show loading state
            // Swal.fire({
            //     title: 'Menyimpan Data',
            //     html: 'Mohon tunggu sebentar...',
            //     allowOutsideClick: false,
            //     didOpen: () => {
            //         Swal.showLoading();
            //     }
            // });

            fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                })
                .then(response => response.json())
                .then(data => {
                    // Hide loading and show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data usaha telah berhasil disimpan',
                        showConfirmButton: true,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#2563eb',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Reset form and map
                            this.reset();
                            if (marker) map.removeLayer(marker);
                            if (locationCircle) map.removeLayer(locationCircle);
                            document.getElementById('locationAccuracy').innerHTML = '';
                            map.setView([1.0456, 104.0304], 12);
                            document.getElementById('mapInstructions').classList.remove('d-none');
                        }
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal menyimpan data. Silakan coba lagi.',
                        confirmButtonColor: '#2563eb'
                    });
                });
        });

        // Reset button handler
        document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Reset Form?',
                text: 'Semua data yang telah diisi akan dihapus',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Reset',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Reset form and map
                    document.getElementById('businessForm').reset();
                    if (marker) map.removeLayer(marker);
                    if (locationCircle) map.removeLayer(locationCircle);
                    document.getElementById('locationAccuracy').innerHTML = '';
                    map.setView([1.0456, 104.0304], 12);
                    document.getElementById('mapInstructions').classList.remove('d-none');

                    Swal.fire({
                        icon: 'success',
                        title: 'Form Direset',
                        text: 'Form telah dikosongkan',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
    </script>
</body>

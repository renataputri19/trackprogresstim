<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Usaha - RENTAK</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <!-- Custom CSS -->
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
        }

        body {
            background-color: #f8fafc;
        }

        .navbar {
            background-color: var(--primary-color);
            padding: 1rem 0;
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

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        #map { 
            height: 400px; 
            width: 100%;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .business-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            background: white;
            transition: all 0.3s ease;
        }

        .business-card:hover {
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            transform: translateY(-2px);
            cursor: pointer;
        }

        .business-name {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .business-info {
            color: #4b5563;
            font-size: 0.9rem;
        }

        .business-icon {
            color: #9ca3af;
            width: 20px;
            margin-right: 0.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .stat-label {
            color: #4b5563;
            font-size: 0.9rem;
        }

        .leaflet-popup-content {
            margin: 13px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .popup-title {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .popup-content {
            color: #4b5563;
            font-size: 0.9rem;
        }

        .swiper {
            width: 100%;
            padding: 20px 0;
        }

        .swiper-slide {
            height: auto;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        .business-card {
            height: 100%;
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .desktop-grid {
                display: none;
            }
        }

        @media (min-width: 769px) {
            .mobile-slider {
                display: none;
            }
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #4b5563;
            font-size: 1rem;
            font-weight: 500;
        }

        .page-link {
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            margin: 0 3px;
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .business-info {
            color: #4b5563;
            font-size: 0.95rem;
            line-height: 1.5;
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
            <a href="{{ route('business.tagging') }}" class="btn btn-light">
                <i class="fas fa-plus-circle me-2"></i>
                Tambah Data Baru
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Statistics Row -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total Usaha</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['weekly'] }}</div>
                    <div class="stat-label">Minggu Ini</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['daily'] }}</div>
                    <div class="stat-label">Hari Ini</div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-map me-2"></i>
                            Peta Lokasi Usaha
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business List Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-list me-2"></i>
                            Daftar Usaha
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Desktop Grid View -->
                        <div class="desktop-grid">
                            <div class="row">
                                @foreach($businesses as $business)
                                <div class="col-md-6 mb-4">
                                    <div class="business-card" data-lat="{{ $business->latitude }}" data-lng="{{ $business->longitude }}">
                                        <div class="business-name">{{ $business->nama_usaha }}</div>
                                        <div class="business-info">
                                            <p class="mb-2">
                                                <i class="fas fa-map-marker-alt business-icon"></i>
                                                {{ $business->alamat }}
                                            </p>
                                            @if($business->deskripsi_usaha)
                                            <p class="mb-2">
                                                <i class="fas fa-info-circle business-icon"></i>
                                                {{ $business->deskripsi_usaha }}
                                            </p>
                                            @endif
                                            <p class="mb-0">
                                                <i class="fas fa-clock business-icon"></i>
                                                {{ $business->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Mobile Slider View -->
                        <div class="mobile-slider">
                            <div class="swiper">
                                <div class="swiper-wrapper">
                                    @foreach($businesses as $business)
                                    <div class="swiper-slide">
                                        <div class="business-card" data-lat="{{ $business->latitude }}" data-lng="{{ $business->longitude }}">
                                            <div class="business-name">{{ $business->nama_usaha }}</div>
                                            <div class="business-info">
                                                <p class="mb-2">
                                                    <i class="fas fa-map-marker-alt business-icon"></i>
                                                    {{ $business->alamat }}
                                                </p>
                                                @if($business->deskripsi_usaha)
                                                <p class="mb-2">
                                                    <i class="fas fa-info-circle business-icon"></i>
                                                    {{ $business->deskripsi_usaha }}
                                                </p>
                                                @endif
                                                <p class="mb-0">
                                                    <i class="fas fa-clock business-icon"></i>
                                                    {{ $business->created_at->format('d/m/Y H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-container">
                            {{ $businesses->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script>
        // Initialize Swiper
        const swiper = new Swiper('.swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },
            breakpoints: {
                // when window width is >= 480px
                480: {
                    slidesPerView: 1
                }
            }
        });

        // Initialize map centered on Batam
        const map = L.map('map').setView([1.0456, 104.0304], 12);
        const markers = [];

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add markers for all businesses
        @foreach($allBusinesses as $business)
            const marker{{ $business->id }} = L.marker([{{ $business->latitude }}, {{ $business->longitude }}]).addTo(map);
            markers.push(marker{{ $business->id }});

            const popupContent{{ $business->id }} = `
                <div class="popup-title">{{ $business->nama_usaha }}</div>
                <div class="popup-content">
                    <p><i class="fas fa-map-marker-alt"></i> {{ $business->alamat }}</p>

                    <p><i class="fas fa-clock"></i> {{ $business->created_at->format('d/m/Y H:i') }}</p>
                </div>
            `;
            marker{{ $business->id }}.bindPopup(popupContent{{ $business->id }});
        @endforeach

        // DISINI YG DIATAS
        // @if($business->deskripsi_usaha)
        //             <p><i class="fas fa-info-circle"></i> {{ $business->deskripsi_usaha }}</p>
        //             @endif


        // Fit map bounds to show all markers
        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }

        // Click handlers for business cards
        document.querySelectorAll('.business-card').forEach(card => {
            card.addEventListener('click', () => {
                const lat = parseFloat(card.dataset.lat);
                const lng = parseFloat(card.dataset.lng);
                map.setView([lat, lng], 16);
                
                // Find and open the corresponding marker popup
                markers.forEach(marker => {
                    const markerLatLng = marker.getLatLng();
                    if (markerLatLng.lat === lat && markerLatLng.lng === lng) {
                        marker.openPopup();
                    }
                });
            });
        });
    </script>
</body>
</html>
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
                    <div class="stat-number">{{ $businesses->count() }}</div>
                    <div class="stat-label">Total Usaha</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number">{{ $businesses->where('created_at', '>=', now()->subDays(7))->count() }}</div>
                    <div class="stat-label">Ditambahkan Minggu Ini</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number">{{ $businesses->where('created_at', '>=', now()->subDay())->count() }}</div>
                    <div class="stat-label">Ditambahkan Hari Ini</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">
                            <i class="fas fa-list me-2"></i>
                            Daftar Usaha
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($businesses as $business)
                            <div class="col-md-6">
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
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialize map centered on Batam
        const map = L.map('map').setView([1.0456, 104.0304], 12);
        const markers = [];

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add markers for all businesses
        document.querySelectorAll('.business-card').forEach(card => {
            const lat = parseFloat(card.dataset.lat);
            const lng = parseFloat(card.dataset.lng);
            const name = card.querySelector('.business-name').textContent;
            const address = card.querySelector('.business-info').textContent;

            const marker = L.marker([lat, lng]).addTo(map);
            markers.push(marker);

            // Create popup content
            const popupContent = `
                <div class="popup-title">${name}</div>
                <div class="popup-content">${address}</div>
            `;
            marker.bindPopup(popupContent);

            // Click on card to focus map
            card.addEventListener('click', () => {
                map.setView([lat, lng], 16);
                marker.openPopup();
            });
        });

        // Fit map bounds to show all markers
        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }
    </script>
</body>
</html>
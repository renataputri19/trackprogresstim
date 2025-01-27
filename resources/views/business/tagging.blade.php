<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Direktori Usaha - RENTAK</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        #map { 
            height: 400px; 
            width: 100%;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .location-buttons {
            margin-bottom: 10px;
        }
        .location-accuracy {
            margin-top: 5px;
            font-size: 0.9em;
            color: #666;
        }
        .loading-indicator {
            display: none;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">Direktori Usaha - Input Data</h2>
                    </div>
                    <div class="card-body">
                        <form id="businessForm" method="POST" action="{{ route('business.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="nama_usaha">Nama Usaha <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_usaha" name="nama_usaha" required>
                            </div>

                            <div class="form-group">
                                <label for="deskripsi_usaha">Deskripsi Usaha</label>
                                <textarea class="form-control" id="deskripsi_usaha" name="deskripsi_usaha" rows="3"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="alamat" name="alamat" required></textarea>
                            </div>

                            <div class="form-group">
                                <label>Lokasi pada Peta <span class="text-danger">*</span></label>
                                <div class="location-buttons">
                                    <button type="button" id="getLocationBtn" class="btn btn-info">
                                        <i class="fas fa-location-arrow"></i> Dapatkan Lokasi Saat Ini
                                    </button>
                                    <span class="loading-indicator">
                                        <i class="fas fa-spinner fa-spin"></i> Mencari lokasi...
                                    </span>
                                </div>
                                <div class="location-accuracy" id="locationAccuracy"></div>
                                <div id="map"></div>
                                <small class="text-muted">Klik pada peta untuk menandai lokasi atau gunakan tombol lokasi otomatis</small>
                                <input type="hidden" id="latitude" name="latitude" required>
                                <input type="hidden" id="longitude" name="longitude" required>
                                <input type="hidden" id="accuracy" name="accuracy">
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                            <a href="{{ route('business.list') }}" class="btn btn-secondary">Lihat Data</a>
                        </form>
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
        let marker;
        let locationCircle;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Handle map click
        map.on('click', function(e) {
            setLocation(e.latlng.lat, e.latlng.lng);
        });

        // Function to set location on map
        function setLocation(lat, lng, accuracy = null) {
            // Remove existing marker and circle
            if (marker) map.removeLayer(marker);
            if (locationCircle) map.removeLayer(locationCircle);
            
            // Add new marker
            marker = L.marker([lat, lng]).addTo(map);
            
            // If accuracy is provided, show accuracy circle
            if (accuracy) {
                locationCircle = L.circle([lat, lng], {
                    radius: accuracy,
                    fillColor: '#3388ff',
                    fillOpacity: 0.1,
                    color: '#3388ff',
                    opacity: 0.3
                }).addTo(map);
                
                // Show accuracy information
                document.getElementById('locationAccuracy').innerHTML = 
                    `Akurasi lokasi: ±${Math.round(accuracy)} meter`;
                document.getElementById('accuracy').value = accuracy;
            }
            
            // Set form values
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            
            // Center map on location
            map.setView([lat, lng], 16);
        }

        // Get current location button handler
        document.getElementById('getLocationBtn').addEventListener('click', function() {
            if (!navigator.geolocation) {
                alert('Geolocation tidak didukung oleh browser Anda');
                return;
            }

            // Show loading indicator
            document.querySelector('.loading-indicator').style.display = 'inline';
            this.disabled = true;

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Success callback
                    setLocation(
                        position.coords.latitude, 
                        position.coords.longitude,
                        position.coords.accuracy
                    );
                    
                    // Hide loading indicator
                    document.querySelector('.loading-indicator').style.display = 'none';
                    document.getElementById('getLocationBtn').disabled = false;
                },
                function(error) {
                    // Error callback
                    let message;
                    switch(error.code) {
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
                    alert(message);
                    
                    // Hide loading indicator
                    document.querySelector('.loading-indicator').style.display = 'none';
                    document.getElementById('getLocationBtn').disabled = false;
                },
                {
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
                alert('Silakan pilih lokasi pada peta atau gunakan lokasi otomatis');
                return;
            }
            
            const formData = new FormData(this);
            
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
                alert(data.message);
                this.reset();
                if (marker) map.removeLayer(marker);
                if (locationCircle) map.removeLayer(locationCircle);
                document.getElementById('locationAccuracy').innerHTML = '';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan data');
            });
        });
    </script>
</body>
</html>
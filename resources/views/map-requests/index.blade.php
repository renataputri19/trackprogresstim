@extends('haloip.layouts.app')
@section('title', 'HaloIP - Sistem Permintaan Peta | RENTAK')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="haloip-container">
        <!-- Hero Section -->
        <section class="haloip-hero">
            <div class="container mx-auto px-4">
                <div class="haloip-hero-content">
                    <h1 class="haloip-title">HaloIP - Sistem Permintaan Peta</h1>
                    <p class="haloip-subtitle">
                        Layanan permintaan produk statistik berupa peta untuk mendukung Sensus Ekonomi 2026 dan kebutuhan lainnya di BPS Kota Batam
                    </p>

                    <!-- Navigation Tabs -->
                    <div class="haloip-nav">
                        <a href="{{ route('tickets.index') }}" class="haloip-nav-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14,2 14,8 20,8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10,9 9,9 8,9"></polyline>
                            </svg>
                            Tiket IT
                        </a>
                        <a href="{{ route('map-requests.index') }}" class="haloip-nav-item active">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="3 6 9 1 15 6 21 1 21 14 15 9 9 14 3 9"></polygon>
                                <line x1="9" y1="1" x2="9" y2="14"></line>
                                <line x1="15" y1="6" x2="15" y2="9"></line>
                            </svg>
                            Permintaan Peta
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <div class="container mx-auto px-4 pb-8 ticketing">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif
        <div class="filter-section mt-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label for="month" class="form-label">Bulan</label>
                    <select name="month" id="month" class="form-control">
                        <option value="">Semua Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="it_staff" class="form-label">Staf IT</label>
                    <select name="it_staff" id="it_staff" class="form-control">
                        <option value="">Semua Staf IT</option>
                        @foreach ($itStaffList as $staff)
                            <option value="{{ $staff->id }}" {{ request('it_staff') == $staff->id ? 'selected' : '' }}>
                                {{ $staff->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="map_type" class="form-label">Jenis Peta</label>
                    <select name="map_type" id="map_type" class="form-control">
                        <option value="">Semua Jenis</option>
                        <option value="kecamatan" {{ request('map_type') == 'kecamatan' ? 'selected' : '' }}>Peta Kecamatan</option>
                        <option value="kelurahan" {{ request('map_type') == 'kelurahan' ? 'selected' : '' }}>Peta Kelurahan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="kdkec" class="form-label">Kecamatan</label>
                    <select name="kdkec" id="kdkec" class="form-control">
                        <option value="">Semua Kecamatan</option>
                        @foreach (\App\Services\LocationService::getDistrictsForDropdown() as $code => $label)
                            <option value="{{ $code }}" {{ request('kdkec') == $code ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <div class="dropdown">
                        <button class="form-control dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Pilih Status
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                            <li>
                                <div class="dropdown-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="status_select_all">
                                        <label class="form-check-label" for="status_select_all">Pilih Semua</label>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <div class="dropdown-item">
                                    <div class="form-check">
                                        <input type="checkbox" name="status[]" id="status_pending" value="pending" class="form-check-input status-checkbox" {{ in_array('pending', \Illuminate\Support\Arr::wrap(request('status', []))) ? 'checked' : '' }}>
                                        <label for="status_pending" class="form-check-label">Menunggu</label>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-item">
                                    <div class="form-check">
                                        <input type="checkbox" name="status[]" id="status_in_progress" value="in_progress" class="form-check-input status-checkbox" {{ in_array('in_progress', \Illuminate\Support\Arr::wrap(request('status', []))) ? 'checked' : '' }}>
                                        <label for="status_in_progress" class="form-check-label">Sedang Diproses</label>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-item">
                                    <div class="form-check">
                                        <input type="checkbox" name="status[]" id="status_completed" value="completed" class="form-check-input status-checkbox" {{ in_array('completed', \Illuminate\Support\Arr::wrap(request('status', []))) ? 'checked' : '' }}>
                                        <label for="status_completed" class="form-check-label">Selesai</label>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn ticketing-btn ticketing-btn-primary w-100">Cari</button>
                </div>
            </form>
        </div>

        @guest
            <div class="text-center mt-4">
                <a href="{{ route('login') }}?redirect={{ route('tickets.create') }}" class="btn ticketing-btn ticketing-btn-success ticketing-btn-lg me-2">Ajukan Tiket</a>
                <a href="{{ route('login') }}?redirect={{ route('map-requests.create') }}" class="btn ticketing-btn ticketing-btn-info ticketing-btn-lg">Ajukan Peta</a>
            </div>
        @else
            <div class="text-center mt-4">
                <a href="{{ route('tickets.create') }}" class="btn ticketing-btn ticketing-btn-success ticketing-btn-lg me-2">Ajukan Tiket</a>
                <a href="{{ route('map-requests.create') }}" class="btn ticketing-btn ticketing-btn-info ticketing-btn-lg me-2">Ajukan Peta</a>
                @if (Auth::user()->is_it_staff)
                    <a href="{{ route('tickets.manage') }}" class="btn ticketing-btn ticketing-btn-danger ticketing-btn-lg me-2">Kelola Tiket</a>
                    <a href="{{ route('map-requests.manage') }}" class="btn ticketing-btn ticketing-btn-warning ticketing-btn-lg">Kelola Peta</a>
                @endif
            </div>
        @endguest

        <div class="row mt-4">
            @forelse ($mapRequests as $mapRequest)
                <div class="col-md-4 mb-4">
                    <div class="card ticket-card">
                        <div class="haloip-card-floating">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                                <polygon points="3 6 9 1 15 6 21 1 21 14 15 9 9 14 3 9"></polygon>
                                <line x1="9" y1="1" x2="9" y2="14"></line>
                                <line x1="15" y1="6" x2="15" y2="9"></line>
                            </svg>
                        </div>
                        <div class="ticket-body">
                            <div class="ticket-code">{{ $mapRequest->ticket_code }}</div>
                            <h5 class="haloip-card-title">{{ $mapRequest->title }}</h5>
                            <p><strong>Pengaju:</strong> {{ $mapRequest->requestor->name ?? 'Pengaju Tidak Diketahui' }}</p>
                            <p><strong>Jenis Peta:</strong> {{ $mapRequest->map_type_display }}</p>
                            @if ($mapRequest->location_display)
                                <p><strong>Lokasi:</strong> {{ $mapRequest->location_display }}</p>
                            @elseif ($mapRequest->zone)
                                <p><strong>Zona:</strong> {{ $mapRequest->zone }}</p>
                            @endif
                            <p><strong>Deskripsi:</strong> {{ Str::limit($mapRequest->description, 100) }}</p>
                            <p><strong>Status:</strong>
                                <span class="status-{{ $mapRequest->status }}">
                                    {{ $mapRequest->status == 'pending' ? 'Menunggu' : ($mapRequest->status == 'in_progress' ? 'Sedang Diproses' : 'Selesai') }}
                                </span>
                            </p>
                            <p><strong>Staf IT:</strong> {{ $mapRequest->itStaff->name ?? 'Belum Ditugaskan' }}</p>
                            <p><strong>Diajukan:</strong> {{ $mapRequest->created_at->locale('id')->isoFormat('D MMMM Y') }}</p>
                            @if ($mapRequest->done_at)
                                <p><strong>Selesai:</strong> {{ $mapRequest->done_at->locale('id')->isoFormat('D MMMM Y') }}</p>
                            @endif
                            <div class="d-flex gap-2 flex-wrap">
                                @if ($mapRequest->requestor_photo)
                                    <a href="{{ Storage::url($mapRequest->requestor_photo) }}" target="_blank"
                                        class="btn ticketing-btn ticketing-btn-secondary btn-sm">Lihat Foto Pengaju</a>
                                    @if (!file_exists(storage_path('app/public/' . $mapRequest->requestor_photo)))
                                        <small class="text-danger"> (Foto tidak ditemukan)</small>
                                    @endif
                                @endif
                                @if ($mapRequest->it_photo)
                                    <a href="{{ Storage::url($mapRequest->it_photo) }}" target="_blank"
                                        class="btn ticketing-btn ticketing-btn-secondary btn-sm">Lihat Foto IT</a>
                                    @if (!file_exists(storage_path('app/public/' . $mapRequest->it_photo)))
                                        <small class="text-danger"> (Foto tidak ditemukan)</small>
                                    @endif
                                @endif
                                @if ($mapRequest->public_token)
                                    <a href="{{ route('public.view', $mapRequest->public_token) }}" target="_blank" class="btn ticketing-btn ticketing-btn-outline-primary btn-sm">Link Publik</a>
                                @endif
                                @if (Auth::user() && Auth::user()->is_it_staff)
                                    <a href="{{ route('map-requests.show', $mapRequest) }}" class="btn ticketing-btn ticketing-btn-primary btn-sm">Tangani Permintaan</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>Tidak ada permintaan peta yang ditemukan.</p>
                </div>
            @endforelse
        </div>
            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $mapRequests->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

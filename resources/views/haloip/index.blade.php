@extends('haloip.layouts.app')
@section('title', 'HaloIP - Sistem Tiket Terpadu | RENTAK')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="{{ asset('css/haloip/haloip-dashboard.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="haloip-main-wrapper">
    <!-- Decorative Background Elements -->
    <div class="haloip-bg-decoration"></div>

    <!-- Hero Section with Modern Design -->
    <section class="haloip-hero-modern">
        <div class="container mx-auto px-4">
            <div class="haloip-hero-grid">
                <!-- Left Side: Text Content -->
                <div class="haloip-hero-text">
                    <div class="haloip-badge-new">
                        <i class="bi bi-info-circle haloip-icon"></i>
                        Sistem Tiket Terpadu
                    </div>
                    <h1 class="haloip-hero-title">HaloIP</h1>
                    <p class="haloip-hero-subtitle">
                        Halo IP! (ai-pi, IT dan Pengolahan) - Solusi terpadu untuk berbagai kebutuhan teknis, informasi, dan layanan di BPS Kota Batam
                    </p>

                    <!-- Quick Action Buttons -->
                    <div class="haloip-hero-actions">
                        @guest
                            <a href="{{ route('login') }}?redirect={{ route('haloip.create') }}" class="haloip-btn-hero haloip-btn-hero-primary">
                                <i class="bi bi-file-earmark-plus haloip-icon"></i>
                                Ajukan Tiket Baru
                            </a>
                        @else
                            <a href="{{ route('haloip.create') }}" class="haloip-btn-hero haloip-btn-hero-primary">
                                <i class="bi bi-file-earmark-plus haloip-icon"></i>
                                Ajukan Tiket Baru
                            </a>
                            @if (Auth::user()->is_it_staff)
                                <a href="{{ route('haloip.manage') }}" class="haloip-btn-hero haloip-btn-hero-secondary">
                                    <i class="bi bi-gear haloip-icon"></i>
                                    Kelola Tiket
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Right Side: Statistics Cards -->
                <div class="haloip-hero-stats">
                    <div class="haloip-stat-card-modern">
                        <div class="haloip-stat-icon-modern haloip-stat-total"><i class="bi bi-file-earmark-text"></i></div>
                        <div class="haloip-stat-info">
                            <div class="haloip-stat-value-modern">{{ $tickets->total() }}</div>
                            <div class="haloip-stat-label-modern">Total Tiket</div>
                        </div>
                    </div>

                    <div class="haloip-stat-card-modern">
                        <div class="haloip-stat-icon-modern haloip-stat-pending"><i class="bi bi-hourglass-split"></i></div>
                        <div class="haloip-stat-info">
                            <div class="haloip-stat-value-modern">{{ $tickets->where('status', 'pending')->count() }}</div>
                            <div class="haloip-stat-label-modern">Menunggu</div>
                        </div>
                    </div>

                    <div class="haloip-stat-card-modern">
                        <div class="haloip-stat-icon-modern haloip-stat-progress"><i class="bi bi-arrow-repeat"></i></div>
                        <div class="haloip-stat-info">
                            <div class="haloip-stat-value-modern">{{ $tickets->where('status', 'in_progress')->count() }}</div>
                            <div class="haloip-stat-label-modern">Diproses</div>
                        </div>
                    </div>

                    <div class="haloip-stat-card-modern">
                        <div class="haloip-stat-icon-modern haloip-stat-completed"><i class="bi bi-check-circle"></i></div>
                        <div class="haloip-stat-info">
                            <div class="haloip-stat-value-modern">{{ $tickets->where('status', 'completed')->count() }}</div>
                            <div class="haloip-stat-label-modern">Selesai</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <div class="haloip-content-wrapper">
        <div class="container mx-auto px-4 pb-8">
            <!-- Success Messages -->
            @if (request()->get('ticket_success'))
                <div class="haloip-alert haloip-alert-success">
                    <i class="bi bi-check-circle haloip-icon"></i>
                    <span>Tiket berhasil diajukan!</span>
                    <button type="button" class="haloip-alert-close">
                        <i class="bi bi-x-lg haloip-icon"></i>
                    </button>
                </div>
            @endif

            @if (request()->get('request_success'))
                <div class="haloip-alert haloip-alert-success">
                    <i class="bi bi-check-circle haloip-icon"></i>
                    <span>Permintaan berhasil diajukan!</span>
                    <button type="button" class="haloip-alert-close">
                        <i class="bi bi-x-lg haloip-icon"></i>
                    </button>
                </div>
            @endif

            <!-- Filter Section with Modern Design -->
            <div class="haloip-filter-modern">
                <div class="haloip-filter-header">
                    <h2 class="haloip-filter-title">
                        <i class="bi bi-funnel haloip-icon"></i>
                        Filter Tiket
                    </h2>
                    <p class="haloip-filter-desc">Temukan tiket yang Anda cari dengan mudah</p>
                </div>

                <form method="GET" action="{{ route('haloip.index') }}" class="haloip-filter-form">
                    <div class="haloip-filter-grid">
                        <div class="haloip-filter-group">
                            <label for="category" class="haloip-filter-label">
                                <i class="bi bi-tags haloip-label-icon"></i>
                                Kategori
                            </label>
                            <select name="category" id="category" class="haloip-filter-select">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $key => $value)
                                    <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="haloip-filter-group">
                            <label for="month" class="haloip-filter-label">
                                <i class="bi bi-calendar3 haloip-label-icon"></i>
                                Bulan
                            </label>
                            <select name="month" id="month" class="haloip-filter-select">
                                <option value="">Semua Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="haloip-filter-group">
                            <label for="it_staff" class="haloip-filter-label">
                                <i class="bi bi-person-badge haloip-label-icon"></i>
                                Petugas IT
                            </label>
                            <select name="it_staff" id="it_staff" class="haloip-filter-select">
                                <option value="">Semua Petugas</option>
                                @foreach ($itStaffList as $staff)
                                    <option value="{{ $staff->id }}" {{ request('it_staff') == $staff->id ? 'selected' : '' }}>
                                        {{ $staff->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="haloip-filter-group haloip-filter-submit">
                            <button type="submit" class="haloip-filter-btn">
                                <i class="bi bi-search haloip-action-icon"></i>
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tickets Table with Modern Design -->
            <div class="haloip-table-modern-wrapper">
                <div class="haloip-table-header">
                    <h2 class="haloip-table-title">
                        <i class="bi bi-journal-text haloip-icon"></i>
                        Daftar Tiket
                    </h2>
                    <div class="haloip-table-count">
                        <span class="haloip-count-badge">{{ $tickets->total() }}</span>
                        <span class="haloip-count-text">Total Tiket</span>
                    </div>
                </div>

                <div class="haloip-table-container-modern">
                    <!-- Desktop/Tablet Table View -->
                    <table class="haloip-table-modern">
                        <thead>
                            <tr>
                                <th>
                                    <div class="haloip-th-content">
                                        <i class="bi bi-hash haloip-label-icon"></i>
                                        Kode Tiket
                                    </div>
                                </th>
                                <th>
                                    <div class="haloip-th-content">
                                        <i class="bi bi-tags haloip-label-icon"></i>
                                        Kategori
                                    </div>
                                </th>
                                <th>
                                    <div class="haloip-th-content">
                                        <i class="bi bi-type haloip-label-icon"></i>
                                        Judul
                                    </div>
                                </th>
                                <th>
                                    <div class="haloip-th-content">
                                        <i class="bi bi-person haloip-label-icon"></i>
                                        Pemohon
                                    </div>
                                </th>
                                <th>
                                    <div class="haloip-th-content">
                                        <i class="bi bi-person-workspace haloip-label-icon"></i>
                                        Petugas IT
                                    </div>
                                </th>
                                <th>
                                    <div class="haloip-th-content">
                                        <i class="bi bi-activity haloip-label-icon"></i>
                                        Status
                                    </div>
                                </th>
                                <th>
                                    <div class="haloip-th-content">
                                        <i class="bi bi-calendar-date haloip-label-icon"></i>
                                        Tanggal
                                    </div>
                                </th>
                                <th>
                                    <div class="haloip-th-content">
                                        <i class="bi bi-three-dots haloip-label-icon"></i>
                                        Aksi
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tickets as $ticket)
                                <tr>
                                    <td>
                                        <span class="haloip-ticket-code-modern">{{ $ticket->ticket_code }}</span>
                                    </td>
                                    <td>
                                        <span class="haloip-category-badge">{{ $ticket->category_display }}</span>
                                    </td>
                                    <td>
                                        <div class="haloip-ticket-title-modern">{{ Str::limit($ticket->title, 40) }}</div>
                                    </td>
                                    <td>
                                        <div class="haloip-user-info">
                                            {{ $ticket->requestor->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="haloip-user-info">
                                            @if($ticket->itStaff)
                                                {{ $ticket->itStaff->name }}
                                            @else
                                                <span class="haloip-unassigned">Belum Ditugaskan</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if ($ticket->status === 'pending')
                                            <span class="haloip-status-modern haloip-status-modern-pending">
                                                <span class="haloip-status-dot"></span>
                                                Menunggu
                                            </span>
                                        @elseif ($ticket->status === 'in_progress')
                                            <span class="haloip-status-modern haloip-status-modern-progress">
                                                <span class="haloip-status-dot"></span>
                                                Diproses
                                            </span>
                                        @else
                                            <span class="haloip-status-modern haloip-status-modern-completed">
                                                <span class="haloip-status-dot"></span>
                                                Selesai
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="haloip-date-info">{{ $ticket->created_at->translatedFormat('d M Y') }}</div>
                                    </td>
                                    <td>
                                        <a href="{{ route('public.view', $ticket->public_token) }}" class="haloip-action-btn">
                                            <i class="bi bi-eye haloip-action-icon"></i>
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="haloip-empty-state">
                                            <i class="bi bi-journal-text" style="font-size: 64px;"></i>
                                            <h3>Belum Ada Tiket</h3>
                                            <p>Tidak ada tiket yang ditemukan. Silakan ajukan tiket baru atau ubah filter pencarian.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Mobile Card View -->
                    <div class="haloip-mobile-cards">
                        @forelse ($tickets as $ticket)
                            <div class="haloip-mobile-card">
                                <div class="haloip-mobile-card-header">
                                    <span class="haloip-ticket-code-modern">{{ $ticket->ticket_code }}</span>
                                    @if ($ticket->status === 'pending')
                                        <span class="haloip-status-modern haloip-status-modern-pending">
                                            <span class="haloip-status-dot"></span>
                                            Menunggu
                                        </span>
                                    @elseif ($ticket->status === 'in_progress')
                                        <span class="haloip-status-modern haloip-status-modern-progress">
                                            <span class="haloip-status-dot"></span>
                                            Diproses
                                        </span>
                                    @else
                                        <span class="haloip-status-modern haloip-status-modern-completed">
                                            <span class="haloip-status-dot"></span>
                                            Selesai
                                        </span>
                                    @endif
                                </div>
                                <div class="haloip-mobile-card-body">
                                    <div class="haloip-mobile-card-row">
                                        <div class="haloip-mobile-card-label">Judul</div>
                                        <div class="haloip-mobile-card-value haloip-ticket-title-modern">{{ $ticket->title }}</div>
                                    </div>
                                    <div class="haloip-mobile-card-row">
                                        <div class="haloip-mobile-card-label">Kategori</div>
                                        <div class="haloip-mobile-card-value">
                                            <span class="haloip-category-badge">{{ $ticket->category_display }}</span>
                                        </div>
                                    </div>
                                    <div class="haloip-mobile-card-row">
                                        <div class="haloip-mobile-card-label">Pemohon</div>
                                        <div class="haloip-mobile-card-value">{{ $ticket->requestor->name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="haloip-mobile-card-row">
                                        <div class="haloip-mobile-card-label">Petugas IT</div>
                                        <div class="haloip-mobile-card-value">
                                            @if($ticket->itStaff)
                                                {{ $ticket->itStaff->name }}
                                            @else
                                                <span class="haloip-unassigned">Belum Ditugaskan</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="haloip-mobile-card-row">
                                        <div class="haloip-mobile-card-label">Tanggal</div>
                                        <div class="haloip-mobile-card-value">{{ $ticket->created_at->translatedFormat('d M Y') }}</div>
                                    </div>
                                </div>
                                <div class="haloip-mobile-card-actions">
                                    <a href="{{ route('public.view', $ticket->public_token) }}" class="haloip-action-btn">
                                        <i class="bi bi-eye haloip-action-icon"></i>
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="haloip-empty-state">
                                <i class="bi bi-journal-text" style="font-size: 64px;"></i>
                                <h3>Belum Ada Tiket</h3>
                                <p>Tidak ada tiket yang ditemukan. Silakan ajukan tiket baru atau ubah filter pencarian.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                @if($tickets->hasPages())
                    <div class="haloip-pagination-wrapper">
                        {{ $tickets->links('vendor.pagination.haloip') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/haloip/haloip-dashboard.js') }}"></script>
@endsection

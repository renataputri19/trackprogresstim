@extends('haloip.layouts.app')
@section('title', 'Kelola Tiket | HaloIP - RENTAK')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="{{ asset('css/haloip/haloip-manage.css') }}" rel="stylesheet">
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
                    <i class="bi bi-gear-wide-connected haloip-icon"></i>
                    Manajemen Tiket IT
                </div>
                <h1 class="haloip-hero-title">Kelola Tiket</h1>
                <p class="haloip-hero-subtitle">
                    Dashboard manajemen tiket untuk staf IT BPS Kota Batam
                </p>

                <!-- Back to HaloIP Button -->
                <div class="haloip-hero-actions">
                    <a href="{{ route('haloip.index') }}" class="haloip-btn-hero haloip-btn-hero-secondary">
                        <i class="bi bi-arrow-left haloip-icon"></i>
                        Kembali ke HaloIP
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <div class="haloip-content-wrapper">
        <div class="container mx-auto px-4 pb-8">
            <!-- Tab Navigation -->
            <div class="haloip-tabs-wrapper">
                <div class="haloip-tabs-container">
                    <a href="{{ route('haloip.manage', array_merge(request()->except(['tab', 'page']), ['tab' => 'my_tickets'])) }}"
                       class="haloip-tab {{ $activeTab === 'my_tickets' ? 'haloip-tab-active' : '' }}">
                        <i class="bi bi-person-badge haloip-icon"></i>
                        <span>Tiket Saya</span>
                        <span class="haloip-tab-count">{{ \App\Models\Ticket::where('it_staff_id', Auth::id())->count() }}</span>
                    </a>
                    <a href="{{ route('haloip.manage', array_merge(request()->except(['tab', 'page']), ['tab' => 'unassigned'])) }}"
                       class="haloip-tab {{ $activeTab === 'unassigned' ? 'haloip-tab-active' : '' }}">
                        <i class="bi bi-info-circle haloip-icon"></i>
                        <span>Belum Ditugaskan</span>
                        <span class="haloip-tab-count">{{ \App\Models\Ticket::whereNull('it_staff_id')->count() }}</span>
                    </a>
                </div>
            </div>

            <!-- Modern Filter Section -->
            <div class="haloip-filter-modern">
                <div class="haloip-filter-header">
                    <h2 class="haloip-filter-title">
                        <i class="bi bi-funnel haloip-icon"></i>
                        Filter Tiket
                    </h2>
                    <p class="haloip-filter-desc">Gunakan filter untuk menemukan tiket yang Anda kelola</p>
                </div>

                <form method="GET" class="haloip-filter-form">
                    <!-- Preserve the active tab when filtering -->
                    <input type="hidden" name="tab" value="{{ $activeTab }}">
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
                            <label class="haloip-filter-label">
                                <i class="bi bi-activity haloip-label-icon"></i>
                                Status
                            </label>
                            <div class="dropdown">
                                <button class="haloip-filter-select dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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

                        <div class="haloip-filter-submit">
                            <button type="submit" class="haloip-filter-btn">
                                <i class="bi bi-search haloip-icon"></i>
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Modern Table Wrapper -->
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

                @if ($tickets->count() > 0)
                    <!-- Desktop Table View -->
                    <div class="haloip-table-container-modern">
                        <table class="haloip-table-modern">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="haloip-th-content">
                                            <i class="bi bi-file-earmark-text haloip-label-icon"></i>
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
                                            <i class="bi bi-check2 haloip-label-icon"></i>
                                            Selesai
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
                                @foreach ($tickets as $ticket)
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
                                            <div class="haloip-date-info">
                                                {{ $ticket->created_at->translatedFormat('d M Y') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="haloip-date-info">
                                                @if ($ticket->done_at)
                                                    {{ $ticket->done_at->translatedFormat('d M Y') }}
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="haloip-actions-grid">
                                                <!-- Assignment Button - Always visible to all IT staff -->
                                                <a href="{{ route('haloip.assign', $ticket->id) }}" class="haloip-action-btn haloip-action-assign" title="Tugaskan Petugas IT">
                                                    <i class="bi bi-person-plus haloip-action-icon"></i>
                                                    Tugaskan
                                                </a>

                                                <!-- Status Update Button - Only visible to assigned IT staff -->
                                                @if ($ticket->it_staff_id === Auth::id())
                                                    <a href="{{ route('haloip.editStatus', $ticket->id) }}" class="haloip-action-btn haloip-action-update" title="Perbarui Status">
                                                        <i class="bi bi-pencil-square haloip-action-icon"></i>
                                                        Perbarui
                                                    </a>
                                                    <button type="button" class="haloip-action-btn haloip-action-delete haloip-delete-btn" title="Hapus Tiket" data-ticket-id="{{ $ticket->id }}" data-ticket-code="{{ $ticket->ticket_code }}" data-ticket-title="{{ $ticket->title }}">
                                                        <i class="bi bi-trash3 haloip-action-icon"></i>
                                                        Hapus
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="haloip-mobile-cards">
                        @foreach ($tickets as $ticket)
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
                                        <span class="haloip-mobile-card-label">Kategori</span>
                                        <span class="haloip-mobile-card-value">
                                            <span class="haloip-category-badge">{{ $ticket->category_display }}</span>
                                        </span>
                                    </div>
                                    <div class="haloip-mobile-card-row">
                                        <span class="haloip-mobile-card-label">Judul</span>
                                        <span class="haloip-mobile-card-value haloip-ticket-title-modern">{{ $ticket->title }}</span>
                                    </div>
                                    <div class="haloip-mobile-card-row">
                                        <span class="haloip-mobile-card-label">Pemohon</span>
                                        <span class="haloip-mobile-card-value">{{ $ticket->requestor->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="haloip-mobile-card-row">
                                        <span class="haloip-mobile-card-label">Tanggal</span>
                                        <span class="haloip-mobile-card-value">{{ $ticket->created_at->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <div class="haloip-mobile-card-row">
                                        <span class="haloip-mobile-card-label">Selesai</span>
                                        <span class="haloip-mobile-card-value">{{ $ticket->done_at ? $ticket->done_at->translatedFormat('d M Y') : '-' }}</span>
                                    </div>
                                </div>
                                <div class="haloip-mobile-card-actions">
                                    <!-- Assignment Button - Always visible to all IT staff -->
                                    <a href="{{ route('haloip.assign', $ticket->id) }}" class="haloip-action-btn haloip-action-assign" title="Tugaskan Petugas IT">
                                        <i class="bi bi-person-plus haloip-action-icon"></i>
                                        Tugaskan
                                    </a>

                                    <!-- Status Update Button - Only visible to assigned IT staff -->
                                    @if ($ticket->it_staff_id === Auth::id())
                                        <a href="{{ route('haloip.editStatus', $ticket->id) }}" class="haloip-action-btn haloip-action-update" title="Perbarui Status">
                                            <i class="bi bi-pencil-square haloip-action-icon"></i>
                                            Perbarui
                                        </a>
                                        <button type="button" class="haloip-action-btn haloip-action-delete haloip-delete-btn" title="Hapus Tiket" data-ticket-id="{{ $ticket->id }}" data-ticket-code="{{ $ticket->ticket_code }}" data-ticket-title="{{ $ticket->title }}">
                                            <i class="bi bi-trash3 haloip-action-icon"></i>
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="haloip-pagination-wrapper">
                        {{ $tickets->links('vendor.pagination.haloip') }}
                    </div>
                @else
                    <div class="haloip-empty-state">
                        <i class="bi bi-journal-text" style="font-size: 64px;"></i>
                        <h3>Tidak Ada Tiket</h3>
                        <p>Tidak ada tiket yang perlu dikelola saat ini</p>
                    </div>
                @endif
</div>
</div>
</div>
</div>
<!-- Delete Confirmation Modal -->
<div id="haloipDeleteModal" class="haloip-modal" data-delete-url-base="{{ url('/haloIP') }}">
    <div class="haloip-modal-backdrop"></div>
    <div class="haloip-modal-dialog">
        <div class="haloip-modal-header">
            <i class="bi bi-trash3 haloip-modal-title-icon"></i>
            <strong>Konfirmasi Hapus Tiket</strong>
        </div>
        <div class="haloip-modal-body">
            <p>Apakah Anda yakin ingin menghapus tiket ini?</p>
            <div id="haloipDeleteDetails" class="haloip-delete-details"></div>
        </div>
        <div class="haloip-modal-footer">
            <button type="button" id="haloipDeleteCancel" class="haloip-btn haloip-btn-secondary">Batal</button>
            <form id="haloipDeleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="haloip-btn haloip-btn-danger">Konfirmasi Hapus</button>
            </form>
        </div>
    </div>
 </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/haloip/haloip-manage.js') }}"></script>

@if(session('whatsapp_url'))
<script>
    // WhatsApp notification after successful assignment
    document.addEventListener('DOMContentLoaded', function() {
        const whatsappUrl = @json(session('whatsapp_url'));
        const staffName = @json(session('assigned_staff_name'));

        if (whatsappUrl) {
            // Create a modal/toast notification with WhatsApp button
            const modalHtml = `
                <div class="haloip-whatsapp-modal" id="whatsappModal">
                    <div class="haloip-whatsapp-modal-backdrop"></div>
                    <div class="haloip-whatsapp-modal-content">
                        <div class="haloip-whatsapp-modal-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="#25D366">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </div>
                        <h3 class="haloip-whatsapp-modal-title">Notifikasi WhatsApp</h3>
                        <p class="haloip-whatsapp-modal-text">
                            Tiket berhasil ditugaskan ke <strong>${staffName}</strong>.<br>
                            Klik tombol di bawah untuk mengirim notifikasi via WhatsApp.
                        </p>
                        <div class="haloip-whatsapp-modal-actions">
                            <a href="${whatsappUrl}" target="_blank" class="haloip-whatsapp-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Kirim WhatsApp
                            </a>
                            <button type="button" class="haloip-whatsapp-btn-secondary" onclick="closeWhatsAppModal()">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
                <style>
                    .haloip-whatsapp-modal {
                        position: fixed;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        z-index: 9999;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                    .haloip-whatsapp-modal-backdrop {
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background: rgba(0, 0, 0, 0.5);
                    }
                    .haloip-whatsapp-modal-content {
                        position: relative;
                        background: #ffffff;
                        border-radius: 1rem;
                        padding: 2rem;
                        max-width: 400px;
                        width: 90%;
                        text-align: center;
                        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                        animation: modalSlideIn 0.3s ease-out;
                    }
                    @keyframes modalSlideIn {
                        from { opacity: 0; transform: translateY(-20px); }
                        to { opacity: 1; transform: translateY(0); }
                    }
                    .haloip-whatsapp-modal-icon {
                        margin-bottom: 1rem;
                    }
                    .haloip-whatsapp-modal-title {
                        font-size: 1.25rem;
                        font-weight: 700;
                        color: #111827;
                        margin: 0 0 0.5rem 0;
                    }
                    .haloip-whatsapp-modal-text {
                        font-size: 0.9375rem;
                        color: #6b7280;
                        margin: 0 0 1.5rem 0;
                        line-height: 1.5;
                    }
                    .haloip-whatsapp-modal-actions {
                        display: flex;
                        flex-direction: column;
                        gap: 0.75rem;
                    }
                    .haloip-whatsapp-btn {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        gap: 0.5rem;
                        padding: 0.75rem 1.5rem;
                        background: #25D366;
                        color: #ffffff;
                        border: none;
                        border-radius: 0.5rem;
                        font-size: 0.9375rem;
                        font-weight: 600;
                        text-decoration: none;
                        cursor: pointer;
                    }
                    .haloip-whatsapp-btn-secondary {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        padding: 0.75rem 1.5rem;
                        background: #f3f4f6;
                        color: #374151;
                        border: none;
                        border-radius: 0.5rem;
                        font-size: 0.9375rem;
                        font-weight: 600;
                        cursor: pointer;
                    }
                </style>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }
    });

    function closeWhatsAppModal() {
        const modal = document.getElementById('whatsappModal');
        if (modal) {
            modal.remove();
        }
    }
</script>
@endif

@if(session('whatsapp_url_completion'))
<script>
    // WhatsApp notification after successful ticket completion
    document.addEventListener('DOMContentLoaded', function() {
        const whatsappUrl = @json(session('whatsapp_url_completion'));
        const requestorName = @json(session('requestor_name'));

        if (whatsappUrl) {
            // Create a modal/toast notification with WhatsApp button
            const modalHtml = `
                <div class="haloip-whatsapp-modal" id="whatsappCompletionModal">
                    <div class="haloip-whatsapp-modal-backdrop"></div>
                    <div class="haloip-whatsapp-modal-content">
                        <div class="haloip-whatsapp-modal-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="#25D366">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </div>
                        <h3 class="haloip-whatsapp-modal-title">Tiket Selesai!</h3>
                        <p class="haloip-whatsapp-modal-text">
                            Tiket telah ditandai selesai.<br>
                            Klik tombol di bawah untuk mengirim notifikasi ke <strong>${requestorName}</strong> via WhatsApp.
                        </p>
                        <div class="haloip-whatsapp-modal-actions">
                            <a href="${whatsappUrl}" target="_blank" class="haloip-whatsapp-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Kirim WhatsApp
                            </a>
                            <button type="button" class="haloip-whatsapp-btn-secondary" onclick="closeWhatsAppCompletionModal()">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
                <style>
                    .haloip-whatsapp-modal {
                        position: fixed;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        z-index: 9999;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                    .haloip-whatsapp-modal-backdrop {
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background: rgba(0, 0, 0, 0.5);
                    }
                    .haloip-whatsapp-modal-content {
                        position: relative;
                        background: #ffffff;
                        border-radius: 1rem;
                        padding: 2rem;
                        max-width: 400px;
                        width: 90%;
                        text-align: center;
                        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                        animation: modalSlideIn 0.3s ease-out;
                    }
                    @keyframes modalSlideIn {
                        from { opacity: 0; transform: translateY(-20px); }
                        to { opacity: 1; transform: translateY(0); }
                    }
                    .haloip-whatsapp-modal-icon {
                        margin-bottom: 1rem;
                    }
                    .haloip-whatsapp-modal-title {
                        font-size: 1.25rem;
                        font-weight: 700;
                        color: #111827;
                        margin: 0 0 0.5rem 0;
                    }
                    .haloip-whatsapp-modal-text {
                        font-size: 0.9375rem;
                        color: #6b7280;
                        margin: 0 0 1.5rem 0;
                        line-height: 1.5;
                    }
                    .haloip-whatsapp-modal-actions {
                        display: flex;
                        flex-direction: column;
                        gap: 0.75rem;
                    }
                    .haloip-whatsapp-btn {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        gap: 0.5rem;
                        padding: 0.75rem 1.5rem;
                        background: #25D366;
                        color: #ffffff;
                        border-radius: 0.5rem;
                        font-size: 0.9375rem;
                        font-weight: 600;
                        text-decoration: none;
                        transition: background 0.2s;
                    }
                    .haloip-whatsapp-btn-secondary {
                        padding: 0.625rem 1.25rem;
                        background: #f3f4f6;
                        color: #374151;
                        border: none;
                        border-radius: 0.5rem;
                        font-size: 0.9375rem;
                        font-weight: 600;
                        cursor: pointer;
                    }
                </style>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }
    });

    function closeWhatsAppCompletionModal() {
        const modal = document.getElementById('whatsappCompletionModal');
        if (modal) {
            modal.remove();
        }
    }
</script>
@endif
@endsection

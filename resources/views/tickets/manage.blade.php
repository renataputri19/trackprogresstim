@extends('haloip.layouts.app')
@section('title', 'Kelola Tiket IT | HaloIP - RENTAK')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="haloip-container">
        <!-- Hero Section -->
        <section class="haloip-hero">
            <div class="container mx-auto px-4">
                <div class="haloip-hero-content">
                    <h1 class="haloip-title">Kelola Tiket IT</h1>
                    <p class="haloip-subtitle">
                        Dashboard manajemen tiket IT untuk staf IT BPS Kota Batam
                    </p>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <div class="container mx-auto px-4 pb-8 ticketing">

        <!-- Back to HaloIP Button -->
        <div class="mb-4">
            <a href="{{ route('tickets.index') }}" class="haloip-btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7"/>
                    <path d="M19 12H5"/>
                </svg>
                Kembali ke HaloIP
            </a>
        </div>

        <div class="filter-section mt-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
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
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <button type="submit" class="haloip-btn haloip-btn-primary">Cari</button>
                </div>
            </form>
        </div>
        <!-- Tickets Table -->
        <div class="haloip-table-container haloip-table-loading w-full overflow-x-auto relative">
            @if ($tickets->count() > 0)
                <table class="haloip-table w-full table-fixed">
                    <thead>
                        <tr>
                            <th class="w-1/3 min-w-0">Tiket & Detail</th>
                            <th class="w-1/4 min-w-0">Pengaju & Ruangan</th>
                            <th class="w-1/4 min-w-0">Status & Tanggal</th>
                            <th class="w-1/3 min-w-0">Aksi & Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $ticket)
                            <tr>
                                <td class="p-3 sm:p-4">
                                    <div class="haloip-table-code">{{ $ticket->ticket_code }}</div>
                                    <div class="haloip-table-title">{{ $ticket->title }}</div>
                                    <div class="haloip-table-meta">{{ Str::limit($ticket->description, 80) }}</div>
                                </td>
                                <td class="p-3 sm:p-4">
                                    <div class="haloip-table-title">{{ $ticket->requestor->name ?? 'Pengaju Tidak Diketahui' }}</div>
                                    <div class="haloip-table-meta">{{ $ticket->ruangan }}</div>
                                </td>
                                <td class="p-3 sm:p-4">
                                    <div class="haloip-table-status status-{{ $ticket->status }}">
                                        {{ $ticket->status == 'pending' ? 'Menunggu' : ($ticket->status == 'in_progress' ? 'Sedang Diproses' : 'Selesai') }}
                                    </div>
                                    <div class="haloip-table-meta mt-2">
                                        <strong>Diajukan:</strong><br>
                                        {{ $ticket->created_at->locale('id')->isoFormat('D MMMM Y') }}
                                    </div>
                                    @if ($ticket->done_at)
                                        <div class="haloip-table-meta mt-2">
                                            <strong>Selesai:</strong><br>
                                            {{ $ticket->done_at->locale('id')->isoFormat('D MMMM Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="p-3 sm:p-4">
                                    <div class="haloip-table-actions flex flex-col sm:flex-row gap-2">
                                        @if ($ticket->requestor_photo)
                                            <a href="{{ Storage::url($ticket->requestor_photo) }}" target="_blank" class="haloip-table-btn haloip-table-btn-secondary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <circle cx="9" cy="9" r="2"></circle>
                                                    <path d="M21 15l-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                                </svg>
                                                Foto Pengaju
                                            </a>
                                        @endif
                                        @if ($ticket->it_photo)
                                            <a href="{{ Storage::url($ticket->it_photo) }}" target="_blank" class="haloip-table-btn haloip-table-btn-secondary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <circle cx="9" cy="9" r="2"></circle>
                                                    <path d="M21 15l-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                                </svg>
                                                Foto IT
                                            </a>
                                        @endif
                                        <a href="{{ route('tickets.show', $ticket) }}" class="haloip-table-btn haloip-table-btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Tangani Tiket
                                        </a>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="haloip-table-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14,2 14,8 20,8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    <p>Tidak ada tiket yang ditugaskan kepada Anda.</p>
                </div>
            @endif
        </div>
        {{ $tickets->links('pagination::bootstrap-5') }}
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status dropdown functionality
    const statusDropdown = document.getElementById('statusDropdown');
    const selectAllCheckbox = document.getElementById('status_select_all');
    const statusCheckboxes = document.querySelectorAll('.status-checkbox');

    // Function to update dropdown button text
    function updateDropdownText() {
        const checkedBoxes = Array.from(statusCheckboxes).filter(cb => cb.checked);
        const dropdownText = statusDropdown.querySelector('span') || statusDropdown;

        if (checkedBoxes.length === 0) {
            dropdownText.textContent = 'Pilih Status';
        } else if (checkedBoxes.length === 1) {
            const statusLabels = {
                'pending': 'Menunggu',
                'in_progress': 'Sedang Diproses',
                'completed': 'Selesai'
            };
            dropdownText.textContent = statusLabels[checkedBoxes[0].value] || checkedBoxes[0].value;
        } else {
            dropdownText.textContent = `${checkedBoxes.length} Status Dipilih`;
        }
    }

    // Select all functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            statusCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateDropdownText();
        });
    }

    // Update select all when individual checkboxes change
    statusCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(statusCheckboxes).every(cb => cb.checked);
            const noneChecked = Array.from(statusCheckboxes).every(cb => !cb.checked);

            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = !allChecked && !noneChecked;
            }
            updateDropdownText();
        });
    });

    // Initialize dropdown text on page load
    updateDropdownText();
});
</script>
@endsection
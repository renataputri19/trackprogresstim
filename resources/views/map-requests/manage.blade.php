@extends('haloip.layouts.app')
@section('title', 'Kelola Permintaan Peta | HaloIP - RENTAK')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="haloip-container">
        <!-- Hero Section -->
        <section class="haloip-hero">
            <div class="container mx-auto px-4">
                <div class="haloip-hero-content">
                    <h1 class="haloip-title">Kelola Permintaan Peta</h1>
                    <p class="haloip-subtitle">
                        Dashboard manajemen permintaan peta untuk staf IT BPS Kota Batam
                    </p>
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

        <!-- Back to HaloIP Button -->
        <div class="mb-4">
            <a href="{{ route('map-requests.index') }}" class="haloip-btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7"/>
                    <path d="M19 12H5"/>
                </svg>
                Kembali ke HaloIP
            </a>
        </div>

        <div class="filter-section mt-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
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
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <button type="submit" class="haloip-btn haloip-btn-primary">Cari</button>
                </div>
            </form>
        </div>

        <!-- Map Requests Table -->
        <div class="haloip-table-container haloip-table-loading w-full overflow-x-auto relative">
            @if ($mapRequests->count() > 0)
                <table class="haloip-table w-full table-fixed">
                    <thead>
                        <tr>
                            <th class="w-1/4 min-w-0">Permintaan & Detail</th>
                            <th class="w-1/5 min-w-0">Pengaju & Jenis Peta</th>
                            <th class="w-1/6 min-w-0">Lokasi</th>
                            <th class="w-1/4 min-w-0">Status & Tanggal</th>
                            <th class="w-1/3 min-w-0">Aksi & Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mapRequests as $mapRequest)
                            <tr>
                                <td class="p-3 sm:p-4">
                                    <div class="haloip-table-code">{{ $mapRequest->ticket_code }}</div>
                                    <div class="haloip-table-title">{{ $mapRequest->title }}</div>
                                    <div class="haloip-table-meta">{{ Str::limit($mapRequest->description, 80) }}</div>
                                </td>
                                <td class="p-3 sm:p-4">
                                    <div class="haloip-table-title">{{ $mapRequest->requestor->name ?? 'Pengaju Tidak Diketahui' }}</div>
                                    <div class="haloip-table-meta">{{ $mapRequest->map_type_display }}</div>
                                </td>
                                <td class="p-3 sm:p-4">
                                    <div class="haloip-table-meta">
                                        @if ($mapRequest->location_display)
                                            {{ $mapRequest->location_display }}
                                        @elseif ($mapRequest->zone)
                                            <strong>Zona:</strong> {{ $mapRequest->zone }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </td>
                                <td class="p-3 sm:p-4">
                                    <div class="haloip-table-status status-{{ $mapRequest->status }}">
                                        {{ $mapRequest->status == 'pending' ? 'Menunggu' : ($mapRequest->status == 'in_progress' ? 'Sedang Diproses' : 'Selesai') }}
                                    </div>
                                    <div class="haloip-table-meta mt-2">
                                        <strong>Diajukan:</strong><br>
                                        {{ $mapRequest->created_at->locale('id')->isoFormat('D MMMM Y') }}
                                    </div>
                                    @if ($mapRequest->done_at)
                                        <div class="haloip-table-meta mt-2">
                                            <strong>Selesai:</strong><br>
                                            {{ $mapRequest->done_at->locale('id')->isoFormat('D MMMM Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="p-3 sm:p-4">
                                    <div class="haloip-table-actions flex flex-col sm:flex-row gap-2">
                                        @if ($mapRequest->requestor_photo)
                                            <a href="{{ Storage::url($mapRequest->requestor_photo) }}" target="_blank" class="haloip-table-btn haloip-table-btn-secondary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <circle cx="9" cy="9" r="2"></circle>
                                                    <path d="M21 15l-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                                </svg>
                                                Foto Pengaju
                                            </a>
                                        @endif
                                        @if ($mapRequest->it_photo)
                                            <a href="{{ Storage::url($mapRequest->it_photo) }}" target="_blank" class="haloip-table-btn haloip-table-btn-secondary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <circle cx="9" cy="9" r="2"></circle>
                                                    <path d="M21 15l-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                                </svg>
                                                Foto IT
                                            </a>
                                        @endif
                                        @if ($mapRequest->public_token)
                                            <a href="{{ route('public.view', $mapRequest->public_token) }}" target="_blank" class="haloip-table-btn haloip-table-btn-outline">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                                </svg>
                                                Link Publik
                                            </a>
                                        @endif
                                        <a href="{{ route('map-requests.show', $mapRequest) }}" class="haloip-table-btn haloip-table-btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polygon points="3 6 9 1 15 6 21 1 21 14 15 9 9 14 3 9"></polygon>
                                                <line x1="9" y1="1" x2="9" y2="14"></line>
                                                <line x1="15" y1="6" x2="15" y2="9"></line>
                                            </svg>
                                            Tangani Permintaan
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
                        <polygon points="3 6 9 1 15 6 21 1 21 14 15 9 9 14 3 9"></polygon>
                        <line x1="9" y1="1" x2="9" y2="14"></line>
                        <line x1="15" y1="6" x2="15" y2="9"></line>
                    </svg>
                    <p>Tidak ada permintaan peta yang ditemukan.</p>
                </div>
            @endif
        </div>
        {{ $mapRequests->links('pagination::bootstrap-5') }}
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

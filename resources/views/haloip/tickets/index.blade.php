@extends('haloip.layouts.app')
@section('title', 'HaloIP - Sistem Tiket IT | RENTAK')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="haloip-container">
    <!-- Floating Particles -->
    <div class="haloip-particle"></div>
    <div class="haloip-particle"></div>
    <div class="haloip-particle"></div>
    <div class="haloip-particle"></div>
    <div class="haloip-particle"></div>
    <div class="haloip-particle"></div>
    <div class="haloip-particle"></div>
    <div class="haloip-particle"></div>
    <div class="haloip-particle"></div>

    <!-- Hero Section -->
    <section class="haloip-hero">
        <div class="container mx-auto px-4">
            <div class="haloip-hero-content">
                <h1 class="haloip-title">HaloIP - Sistem Tiket IT</h1>
                <p class="haloip-subtitle">
                    Halo IP! (ai-pi, IT dan Pengolahan) - Solusi terpadu untuk berbagai kebutuhan teknis dan informasi di BPS Kota Batam
                </p>
                
                <!-- Navigation Tabs -->
                <div class="haloip-nav">
                    <a href="{{ route('tickets.index') }}" class="haloip-nav-item active">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Tiket IT
                    </a>
                    <a href="{{ route('map-requests.index') }}" class="haloip-nav-item">
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
    <div class="container mx-auto px-4 pb-8 overflow-x-visible">
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter Section -->
        <div class="haloip-filter">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label for="month" class="haloip-form-label">Bulan</label>
                    <select name="month" id="month" class="haloip-form-control">
                        <option value="">Semua Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label for="it_staff" class="haloip-form-label">Staf IT</label>
                    <select name="it_staff" id="it_staff" class="haloip-form-control">
                        <option value="">Semua Staf IT</option>
                        @foreach ($itStaffList as $staff)
                            <option value="{{ $staff->id }}" {{ request('it_staff') == $staff->id ? 'selected' : '' }}>
                                {{ $staff->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="haloip-form-label">Status</label>
                    <div class="relative">
                        <button type="button" class="haloip-form-control flex items-center justify-between" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <span>Pilih Status</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>
                        <div class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 hidden" id="statusDropdownMenu">
                            <div class="p-2">
                                <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                    <input class="form-check-input mr-2" type="checkbox" id="status_select_all">
                                    <span class="text-sm">Pilih Semua</span>
                                </label>
                                <hr class="my-2">
                                <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                    <input type="checkbox" name="status[]" id="status_pending" value="pending" class="mr-2 status-checkbox" {{ in_array('pending', \Illuminate\Support\Arr::wrap(request('status', []))) ? 'checked' : '' }}>
                                    <span class="text-sm">Menunggu</span>
                                </label>
                                <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                    <input type="checkbox" name="status[]" id="status_in_progress" value="in_progress" class="mr-2 status-checkbox" {{ in_array('in_progress', \Illuminate\Support\Arr::wrap(request('status', []))) ? 'checked' : '' }}>
                                    <span class="text-sm">Sedang Diproses</span>
                                </label>
                                <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                    <input type="checkbox" name="status[]" id="status_completed" value="completed" class="mr-2 status-checkbox" {{ in_array('completed', \Illuminate\Support\Arr::wrap(request('status', []))) ? 'checked' : '' }}>
                                    <span class="text-sm">Selesai</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="haloip-btn haloip-btn-primary w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Action Buttons -->
        <div class="haloip-actions mb-8">
            @guest
                <a href="{{ route('login') }}?redirect={{ route('tickets.create') }}" class="haloip-btn haloip-btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14,2 14,8 20,8"></polyline>
                        <line x1="12" y1="18" x2="12" y2="12"></line>
                        <line x1="9" y1="15" x2="15" y2="15"></line>
                    </svg>
                    Ajukan Tiket
                </a>
                <a href="{{ route('login') }}?redirect={{ route('map-requests.create') }}" class="haloip-btn haloip-btn-info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="3 6 9 1 15 6 21 1 21 14 15 9 9 14 3 9"></polygon>
                        <line x1="9" y1="1" x2="9" y2="14"></line>
                        <line x1="15" y1="6" x2="15" y2="9"></line>
                    </svg>
                    Ajukan Peta
                </a>
            @else
                <a href="{{ route('tickets.create') }}" class="haloip-btn haloip-btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14,2 14,8 20,8"></polyline>
                        <line x1="12" y1="18" x2="12" y2="12"></line>
                        <line x1="9" y1="15" x2="15" y2="15"></line>
                    </svg>
                    Ajukan Tiket
                </a>
                <a href="{{ route('map-requests.create') }}" class="haloip-btn haloip-btn-info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="3 6 9 1 15 6 21 1 21 14 15 9 9 14 3 9"></polygon>
                        <line x1="9" y1="1" x2="9" y2="14"></line>
                        <line x1="15" y1="6" x2="15" y2="9"></line>
                    </svg>
                    Ajukan Peta
                </a>
                @if (Auth::user()->is_it_staff)
                    <a href="{{ route('tickets.manage') }}" class="haloip-btn haloip-btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                        </svg>
                        Kelola Tiket
                    </a>
                    <a href="{{ route('map-requests.manage') }}" class="haloip-btn haloip-btn-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="3 6 9 1 15 6 21 1 21 14 15 9 9 14 3 9"></polygon>
                            <line x1="9" y1="1" x2="9" y2="14"></line>
                            <line x1="15" y1="6" x2="15" y2="9"></line>
                        </svg>
                        Kelola Peta
                    </a>
                @endif
            @endguest
        </div>

        <!-- Tickets Table -->
        <div class="haloip-table-container haloip-table-loading w-full overflow-x-auto relative">
            @if ($tickets->count() > 0)
                <table class="haloip-table w-full table-fixed">
                    <thead>
                        <tr>
                            <th class="w-1/4 min-w-0">Tiket & Detail</th>
                            <th class="w-1/5 min-w-0">Pengaju & Ruangan</th>
                            <th class="w-1/5 min-w-0">Status & IT Staff</th>
                            <th class="w-1/6 min-w-0">Tanggal</th>
                            <th class="w-1/4 min-w-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $ticket)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
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
                                        <strong>IT Staff:</strong> {{ $ticket->itStaff->name ?? 'Belum Ditugaskan' }}
                                    </div>
                                </td>
                                <td class="p-3 sm:p-4">
                                    <div class="haloip-table-meta">
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
                                        @if ($ticket->public_token)
                                            <a href="{{ route('public.view', $ticket->public_token) }}" target="_blank" class="haloip-table-btn haloip-table-btn-outline">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                                </svg>
                                                Link Publik
                                            </a>
                                        @endif
                                        @if (Auth::user() && Auth::user()->is_it_staff)
                                            <a href="{{ route('tickets.show', $ticket) }}" class="haloip-table-btn haloip-table-btn-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                                </svg>
                                                Tangani Tiket
                                            </a>
                                        @endif
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
                    <p>Tidak ada tiket yang ditemukan.</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            {{ $tickets->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status dropdown functionality
    const statusDropdown = document.getElementById('statusDropdown');
    const statusDropdownMenu = document.getElementById('statusDropdownMenu');
    const selectAllCheckbox = document.getElementById('status_select_all');
    const statusCheckboxes = document.querySelectorAll('.status-checkbox');

    statusDropdown.addEventListener('click', function() {
        statusDropdownMenu.classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!statusDropdown.contains(e.target) && !statusDropdownMenu.contains(e.target)) {
            statusDropdownMenu.classList.add('hidden');
        }
    });

    // Function to update dropdown button text
    function updateDropdownText() {
        const checkedBoxes = Array.from(statusCheckboxes).filter(cb => cb.checked);
        const dropdownText = statusDropdown.querySelector('span');

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
    selectAllCheckbox.addEventListener('change', function() {
        statusCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateDropdownText();
    });

    // Update select all when individual checkboxes change
    statusCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(statusCheckboxes).every(cb => cb.checked);
            const noneChecked = Array.from(statusCheckboxes).every(cb => !cb.checked);

            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = !allChecked && !noneChecked;
            updateDropdownText();
        });
    });

    // Initialize dropdown text on page load
    updateDropdownText();

    // Enhanced mobile table scrolling functionality
    const tableContainer = document.querySelector('.haloip-table-container');
    if (tableContainer) {
        let isScrolling = false;
        let scrollTimeout;

        // Add smooth scrolling class
        tableContainer.classList.add('smooth-scroll');

        // Enhanced responsive table setup
        const table = tableContainer.querySelector('.haloip-table');
        if (table) {
            function setupResponsiveTable() {
                const screenWidth = window.innerWidth;

                // Enhanced mobile setup
                if (screenWidth <= 640) {
                    table.style.minWidth = '800px';
                    table.style.width = '800px';
                    setupColumnWidths(800, 5, ['200px', '160px', '160px', '120px', '160px']);
                } else if (screenWidth <= 768) {
                    table.style.minWidth = '1000px';
                    table.style.width = '1000px';
                    setupColumnWidths(1000, 5, ['250px', '200px', '200px', '150px', '200px']);
                } else if (screenWidth <= 1024) {
                    table.style.minWidth = '1100px';
                    table.style.width = '1100px';
                    setupColumnWidths(1100, 5, ['275px', '220px', '220px', '165px', '220px']);
                } else {
                    // Desktop - allow natural table width
                    table.style.minWidth = '100%';
                    table.style.width = '100%';
                    resetColumnWidths();
                }

                tableContainer.style.overflowX = 'auto';
                tableContainer.style.webkitOverflowScrolling = 'touch';
            }

            function setupColumnWidths(tableWidth, columnCount, widths) {
                const cells = table.querySelectorAll('th, td');
                cells.forEach((cell, index) => {
                    const columnIndex = index % columnCount;
                    cell.style.minWidth = widths[columnIndex];
                    cell.style.maxWidth = widths[columnIndex];
                    cell.style.whiteSpace = 'nowrap';
                    cell.style.overflow = 'hidden';
                    cell.style.textOverflow = 'ellipsis';
                });
            }

            function resetColumnWidths() {
                const cells = table.querySelectorAll('th, td');
                cells.forEach(cell => {
                    cell.style.minWidth = '';
                    cell.style.maxWidth = '';
                    cell.style.whiteSpace = 'normal';
                    cell.style.overflow = '';
                    cell.style.textOverflow = '';
                });
            }

            // Initial setup
            setupResponsiveTable();
        }

        // Touch scroll indicators
        function updateScrollIndicators() {
            const scrollLeft = tableContainer.scrollLeft;
            const scrollWidth = tableContainer.scrollWidth;
            const clientWidth = tableContainer.clientWidth;
            const maxScroll = scrollWidth - clientWidth;

            // Update scroll indicators based on position
            if (scrollLeft <= 0) {
                tableContainer.style.setProperty('--scroll-left-opacity', '0');
            } else {
                tableContainer.style.setProperty('--scroll-left-opacity', '0.7');
            }

            if (scrollLeft >= maxScroll - 5) {
                tableContainer.style.setProperty('--scroll-right-opacity', '0');
            } else {
                tableContainer.style.setProperty('--scroll-right-opacity', '1');
            }
        }

        // Handle scroll events
        tableContainer.addEventListener('scroll', function() {
            isScrolling = true;
            updateScrollIndicators();

            // Clear existing timeout
            clearTimeout(scrollTimeout);

            // Set timeout to detect when scrolling stops
            scrollTimeout = setTimeout(function() {
                isScrolling = false;
            }, 150);
        });

        // Initial indicator update
        updateScrollIndicators();

        // Enhanced window resize handler
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                updateScrollIndicators();

                // Re-setup responsive table on resize
                const table = tableContainer.querySelector('.haloip-table');
                if (table) {
                    const screenWidth = window.innerWidth;

                    if (screenWidth <= 640) {
                        table.style.minWidth = '800px';
                        table.style.width = '800px';
                    } else if (screenWidth <= 768) {
                        table.style.minWidth = '1000px';
                        table.style.width = '1000px';
                    } else if (screenWidth <= 1024) {
                        table.style.minWidth = '1100px';
                        table.style.width = '1100px';
                    } else {
                        table.style.minWidth = '100%';
                        table.style.width = '100%';
                    }

                    tableContainer.style.overflowX = 'auto';
                    tableContainer.style.webkitOverflowScrolling = 'touch';
                }
            }, 150);
        });

        // Touch-friendly scrolling for mobile devices
        if ('ontouchstart' in window) {
            let startX = 0;
            let scrollLeftStart = 0;

            tableContainer.addEventListener('touchstart', function(e) {
                startX = e.touches[0].pageX;
                scrollLeftStart = tableContainer.scrollLeft;
            }, { passive: true });

            tableContainer.addEventListener('touchmove', function(e) {
                if (e.touches.length > 1) return; // Ignore multi-touch

                const currentX = e.touches[0].pageX;
                const diffX = startX - currentX;

                tableContainer.scrollLeft = scrollLeftStart + diffX;
                updateScrollIndicators();
            }, { passive: true });
        }

        // Enhanced horizontal wheel scrolling support
        function handleWheelScroll(e) {
            // Check if horizontal scrolling is needed
            const hasHorizontalScroll = tableContainer.scrollWidth > tableContainer.clientWidth;
            if (!hasHorizontalScroll) return;

            let deltaX = 0;
            let deltaY = e.deltaY;

            // Handle different wheel event types and input methods
            if (e.deltaX !== undefined) {
                // Modern browsers with deltaX support (trackpad horizontal swipe)
                deltaX = e.deltaX;
            } else if (e.wheelDeltaX !== undefined) {
                // Webkit browsers
                deltaX = -e.wheelDeltaX;
            }

            // Handle Shift + vertical scroll as horizontal scroll
            if (e.shiftKey && Math.abs(deltaY) > Math.abs(deltaX)) {
                deltaX = deltaY;
                deltaY = 0;
            }

            // Handle horizontal mouse wheel (rare but exists on some mice)
            if (e.axis !== undefined && e.axis === e.HORIZONTAL_AXIS) {
                deltaX = e.detail;
            }

            // If we have horizontal delta, handle it
            if (Math.abs(deltaX) > 0) {
                e.preventDefault();
                e.stopPropagation();

                // Normalize scroll speed across browsers and devices
                let scrollAmount = deltaX;

                // Adjust scroll speed for different input types
                if (e.deltaMode === 1) {
                    // Line mode (Firefox)
                    scrollAmount *= 33;
                } else if (e.deltaMode === 2) {
                    // Page mode
                    scrollAmount *= tableContainer.clientWidth * 0.8;
                }

                // Apply smooth scrolling with momentum
                const currentScrollLeft = tableContainer.scrollLeft;
                const newScrollLeft = currentScrollLeft + scrollAmount;

                // Use requestAnimationFrame for smooth scrolling
                requestAnimationFrame(() => {
                    tableContainer.scrollLeft = newScrollLeft;
                    updateScrollIndicators();

                    // Brief visual feedback for wheel scrolling
                    tableContainer.style.boxShadow = '0 0 0 2px rgba(13, 148, 136, 0.3)';
                    setTimeout(() => {
                        tableContainer.style.boxShadow = '';
                    }, 150);
                });

                return false;
            }

            // Handle vertical scroll when at horizontal edges (for better UX)
            if (Math.abs(deltaY) > 0 && Math.abs(deltaX) === 0) {
                const atLeftEdge = tableContainer.scrollLeft <= 0;
                const atRightEdge = tableContainer.scrollLeft >= (tableContainer.scrollWidth - tableContainer.clientWidth);

                // Allow vertical scrolling when at horizontal edges
                if (atLeftEdge || atRightEdge) {
                    return true; // Allow default vertical scroll
                }
            }
        }

        // Add wheel event listeners for cross-browser compatibility
        tableContainer.addEventListener('wheel', handleWheelScroll, { passive: false });

        // Legacy support for older browsers
        if (tableContainer.addEventListener) {
            tableContainer.addEventListener('DOMMouseScroll', handleWheelScroll, { passive: false }); // Firefox
            tableContainer.addEventListener('mousewheel', handleWheelScroll, { passive: false }); // Webkit
        }

        // Keyboard navigation support
        tableContainer.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                tableContainer.scrollBy({ left: -50, behavior: 'smooth' });
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                tableContainer.scrollBy({ left: 50, behavior: 'smooth' });
            }
        });

        // Make table container focusable for keyboard and wheel navigation
        if (!tableContainer.hasAttribute('tabindex')) {
            tableContainer.setAttribute('tabindex', '0');
        }

        // Auto-focus on hover to enable wheel scrolling
        tableContainer.addEventListener('mouseenter', function() {
            if (document.activeElement !== tableContainer) {
                tableContainer.focus({ preventScroll: true });
            }
        });

        // Remove focus outline on mouse interaction
        tableContainer.addEventListener('mousedown', function() {
            tableContainer.style.outline = 'none';
        });

        // Restore focus outline for keyboard users
        tableContainer.addEventListener('keydown', function() {
            tableContainer.style.outline = '';
        });
    }
});
</script>
@endsection

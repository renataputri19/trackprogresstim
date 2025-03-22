@extends('layouts.main')
@section('title', 'Halo IPDS - Sistem Tiket IT')
@section('content')
    <div class="container mt-5 ticketing">
        <div class="ticket-header">
            <h2>Halo IPDS - Sistem Tiket IT</h2>
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
                    <button type="submit" class="btn ticketing-btn ticketing-btn-primary w-100">Cari</button>
                </div>
            </form>
        </div>

        @guest
            <div class="text-center mt-4">
                <a href="{{ route('login') }}?redirect={{ route('tickets.create') }}" class="btn ticketing-btn ticketing-btn-success ticketing-btn-lg">Ajukan Tiket</a>
            </div>
        @else
            <div class="text-center mt-4">
                <a href="{{ route('tickets.create') }}" class="btn ticketing-btn ticketing-btn-success ticketing-btn-lg">Ajukan Tiket</a>
                @if (Auth::user()->is_it_staff)
                    <a href="{{ route('tickets.manage') }}" class="btn ticketing-btn ticketing-btn-danger ticketing-btn-lg">Kelola Tiket</a>
                @endif
            </div>
        @endguest

        <div class="row mt-4">
            @forelse ($tickets as $ticket)
                <div class="col-md-4 mb-4">
                    <div class="card ticket-card">
                        <div class="ticket-body">
                            <div class="ticket-code">{{ $ticket->ticket_code }}</div>
                            <h5 class="card-title">{{ $ticket->title }}</h5>
                            <p><strong>Pengaju:</strong> {{ $ticket->requestor->name ?? 'Pengaju Tidak Diketahui' }}</p>
                            <p><strong>Ruangan:</strong> {{ $ticket->ruangan }}</p>
                            <p><strong>Deskripsi:</strong> {{ Str::limit($ticket->description, 100) }}</p>
                            <p><strong>Status:</strong>
                                <span class="status-{{ $ticket->status }}">
                                    {{ $ticket->status == 'pending' ? 'Menunggu' : ($ticket->status == 'in_progress' ? 'Sedang Diproses' : 'Selesai') }}
                                </span>
                            </p>
                            <p><strong>Staf IT:</strong> {{ $ticket->itStaff->name ?? 'Belum Ditugaskan' }}</p>
                            <p><strong>Diajukan:</strong> {{ $ticket->created_at->translatedFormat('Y-m-d') }}</p>
                            @if ($ticket->done_at)
                                <p><strong>Selesai:</strong> {{ $ticket->done_at->translatedFormat('Y-m-d') }}</p>
                            @endif
                            <div class="d-flex gap-2">
                                @if ($ticket->requestor_photo)
                                    <a href="{{ Storage::url($ticket->requestor_photo) }}" target="_blank"
                                        class="btn ticketing-btn ticketing-btn-secondary">Lihat Foto Pengaju</a>
                                    @if (!file_exists(storage_path('app/public/' . $ticket->requestor_photo)))
                                        <small class="text-danger"> (Foto tidak ditemukan)</small>
                                    @endif
                                @endif
                                @if ($ticket->it_photo)
                                    <a href="{{ Storage::url($ticket->it_photo) }}" target="_blank"
                                        class="btn ticketing-btn ticketing-btn-secondary">Lihat Foto IT</a>
                                    @if (!file_exists(storage_path('app/public/' . $ticket->it_photo)))
                                        <small class="text-danger"> (Foto tidak ditemukan)</small>
                                    @endif
                                @endif
                                @if (Auth::user() && Auth::user()->is_it_staff)
                                    <a href="{{ route('tickets.show', $ticket) }}" class="btn ticketing-btn ticketing-btn-primary">Tangani Tiket</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>Tidak ada tiket yang ditemukan.</p>
                </div>
            @endforelse
        </div>
        {{ $tickets->links('pagination::bootstrap-5') }}
    </div>
@endsection
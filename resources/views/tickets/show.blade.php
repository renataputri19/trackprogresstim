@extends('layouts.main')
@section('title', 'Detail Tiket IT')
@section('content')
    <div class="container mt-5 ticketing">
        <div class="ticket-header">
            <h2>Detail Tiket IT</h2>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card ticket-card">
                    <div class="ticket-body">
                        <div class="ticket-code">{{ $ticket->ticket_code }}</div>
                        <h5 class="card-title">{{ $ticket->title }}</h5>
                        <p><strong>Pengaju:</strong> {{ $ticket->requestor->name ?? 'Pengaju Tidak Diketahui' }}</p>
                        <p><strong>Ruangan:</strong> {{ $ticket->ruangan }}</p>
                        <p><strong>Deskripsi:</strong> {{ $ticket->description }}</p>
                        <p><strong>Status:</strong>
                            <span class="status-{{ $ticket->status }}">
                                {{ $ticket->status == 'pending' ? 'Menunggu' : ($ticket->status == 'in_progress' ? 'Sedang Diproses' : 'Selesai') }}
                            </span>
                        </p>
                        <p><strong>Diajukan:</strong> {{ $ticket->created_at->translatedFormat('Y-m-d') }}</p>
                        @if ($ticket->done_at)
                            <p><strong>Selesai:</strong> {{ $ticket->done_at->translatedFormat('Y-m-d') }}</p>
                        @endif
                        @if ($ticket->requestor_photo)
                            <a href="{{ Storage::url($ticket->requestor_photo) }}" target="_blank"
                                class="btn ticketing-btn ticketing-btn-secondary ticketing-btn-sm">Lihat Foto Pengaju</a>
                            @if (!file_exists(storage_path('app/public/' . $ticket->requestor_photo)))
                                <small class="text-danger"> (Foto tidak ditemukan)</small>
                            @endif
                        @endif
                        @if ($ticket->it_photo)
                            <a href="{{ Storage::url($ticket->it_photo) }}" target="_blank"
                                class="btn ticketing-btn ticketing-btn-secondary ticketing-btn-sm">Lihat Foto IT</a>
                            @if (!file_exists(storage_path('app/public/' . $ticket->it_photo)))
                                <small class="text-danger"> (Foto tidak ditemukan)</small>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Perbarui Tiket</h5>
                        <form method="POST" action="{{ route('tickets.update', $ticket) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>
                                        Menunggu</option>
                                    <option value="in_progress"
                                        {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>Sedang Diproses
                                    </option>
                                    <option value="completed" {{ $ticket->status == 'completed' ? 'selected' : '' }}>
                                        Selesai</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="it_photo" class="form-label">Foto Penyelesaian (Opsional)</label>
                                <input type="file" name="it_photo" id="it_photo" class="form-control-file">
                            </div>
                            <button type="submit" class="btn ticketing-btn ticketing-btn-primary">Perbarui Tiket</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ route('tickets.manage') }}" class="btn ticketing-btn ticketing-btn-secondary">Kembali ke Kelola Tiket</a>
        </div>
    </div>
@endsection
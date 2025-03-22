@extends('layouts.main')
@section('title', 'Create IT Ticket')
@section('content')
    <div class="container mt-5 ticketing">
        <div class="ticket-header">
            <h2>Submit an IT Ticket</h2>
        </div>
        <div class="ticket-container mt-4">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="p-4">
                @csrf
                <div class="form-group mb-4">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <label for="ruangan" class="form-label">Ruangan</label>
                    <select name="ruangan" id="ruangan" class="form-control @error('ruangan') is-invalid @enderror" required>
                        @foreach ($ruanganList as $ruangan)
                            <option value="{{ $ruangan }}" {{ old('ruangan') == $ruangan ? 'selected' : '' }}>{{ $ruangan }}</option>
                        @endforeach
                    </select>
                    @error('ruangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <label for="requestor_photo" class="form-label">Photo Evidence</label>
                    <input type="file" name="requestor_photo" id="requestor_photo" class="form-control-file @error('requestor_photo') is-invalid @enderror" required>
                    @error('requestor_photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn ticketing-btn ticketing-btn-primary">Submit Ticket</button>
                <a href="{{ route('tickets.index') }}" class="btn ticketing-btn ticketing-btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
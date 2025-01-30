@extends('layouts.main')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('kms.index') }}">KMS</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('kms.division', $activity->division->slug) }}">{{ $activity->division->name }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a
                        href="{{ route('kms.activity', [$activity->division->slug, $activity->slug]) }}">{{ $activity->name }}</a>
                </li>
                <li class="breadcrumb-item active">Add Document</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header">Add New Document for {{ $activity->name }}</div>
            <div class="card-body">
                <form action="{{ route('kms.documents.store', $activity->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Document Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="document_date" class="form-label">Date</label>
                        <input type="date" class="form-control @error('document_date') is-invalid @enderror"
                            id="document_date" name="document_date" value="{{ old('document_date') }}" required>
                        @error('document_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="onedrive_link" class="form-label">Link</label>
                        <input type="url" class="form-control @error('onedrive_link') is-invalid @enderror"
                            id="onedrive_link" name="onedrive_link" value="{{ old('onedrive_link') }}" required>
                        @error('onedrive_link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Document</button>
                </form>
            </div>
        </div>
    </div>
@endsection

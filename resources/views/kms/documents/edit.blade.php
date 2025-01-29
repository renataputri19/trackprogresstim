@extends('layouts.main')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('kms.index') }}">KMS</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('kms.division', $document->activity->division->slug) }}">{{ $document->activity->division->name }}</a>
                </li>
                <li class="breadcrumb-item"><a
                        href="{{ route('kms.activity', [$document->activity->division->slug, $document->activity->slug]) }}">{{ $document->activity->name }}</a>
                </li>
                <li class="breadcrumb-item active">Edit Document</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header">Edit Document</div>
            <div class="card-body">
                <form action="{{ route('kms.documents.update', $document->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="title" class="form-label">Document Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title" value="{{ old('title', $document->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="document_date" class="form-label">Date</label>
                        <input type="date" class="form-control @error('document_date') is-invalid @enderror"
                            id="document_date" name="document_date"
                            value="{{ old('document_date', $document->document_date->format('Y-m-d')) }}" required>
                        @error('document_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="onedrive_link" class="form-label">Link</label>
                        <input type="url" class="form-control @error('onedrive_link') is-invalid @enderror"
                            id="onedrive_link" name="onedrive_link"
                            value="{{ old('onedrive_link', $document->onedrive_link) }}" required>
                        @error('onedrive_link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $document->description) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Document</button>
                </form>

                <form action="{{ route('kms.documents.destroy', $document->id) }}" method="POST" class="mt-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this document?')">
                        Delete Document
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

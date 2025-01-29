@extends('layouts.main')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('kms.index') }}">KMS</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('kms.division', $activity->division->slug) }}">{{ $activity->division->name }}</a>
                </li>
                <li class="breadcrumb-item active">{{ $activity->name }}</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>{{ $activity->name }}</h1>
            <a href="{{ route('kms.documents.create', $activity->id) }}" class="btn btn-primary">Add New Document</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($documents->isEmpty())
            <div class="alert alert-info">
                No documents have been added to this activity yet.
            </div>
        @else
            @foreach ($documents as $month => $monthDocuments)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ $month }}</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach ($monthDocuments as $document)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $document->title }}</h6>
                                        <small class="text-muted">{{ $document->document_date->format('d F Y') }}</small>
                                        @if ($document->description)
                                            <p class="mb-1 mt-2">{{ $document->description }}</p>
                                        @endif
                                        <a href="{{ $document->onedrive_link }}" target="_blank"
                                            class="btn btn-sm btn-primary mt-2">
                                            <i class="fas fa-external-link-alt"></i> View Document
                                        </a>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('kms.documents.edit', $document->id) }}"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('kms.documents.destroy', $document->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger ms-1"
                                                onclick="return confirm('Are you sure you want to delete this document?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>







@endsection

@extends('layouts.main')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('kms.index') }}">KMS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kms.division', $division->slug) }}">{{ $division->name }}</a>
                </li>
                <li class="breadcrumb-item active">Create Activity</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header">Create New Activity for {{ $division->name }}</div>
            <div class="card-body">
                <form action="{{ route('kms.activities.store', $division->slug) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Activity Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Activity</button>
                </form>
            </div>
        </div>
    </div>
@endsection

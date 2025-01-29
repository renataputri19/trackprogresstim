@extends('layouts.main')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('kms.index') }}">KMS</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('kms.division', $activity->division->slug) }}">{{ $activity->division->name }}</a>
                </li>
                <li class="breadcrumb-item active">Edit Activity</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header">Edit Activity</div>
            <div class="card-body">
                <form action="{{ route('kms.activities.update', $activity->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Activity Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $activity->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $activity->description) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Activity</button>
                </form>

                <form action="{{ route('kms.activities.destroy', $activity->id) }}" method="POST" class="mt-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this activity?')">
                        Delete Activity
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

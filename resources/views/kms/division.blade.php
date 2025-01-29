@extends('layouts.main')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('kms.index') }}">KMS</a></li>
                <li class="breadcrumb-item active">{{ $division->name }}</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>{{ $division->name }} Activities</h1>
            <a href="{{ route('kms.activities.create', $division->slug) }}" class="btn btn-primary">Create New Activity</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="list-group">
            @foreach($division->activities as $activity)
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-1">{{ $activity->name }}</h5>
                        <p class="mb-1">{{ $activity->description }}</p>
                        <a href="{{ route('kms.activity', [$division->slug, $activity->slug]) }}" class="btn btn-sm btn-primary">
                            View Documents
                        </a>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('kms.activities.edit', $activity->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('kms.activities.destroy', $activity->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger ms-1" 
                                    onclick="return confirm('Are you sure you want to delete this activity?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>



@endsection

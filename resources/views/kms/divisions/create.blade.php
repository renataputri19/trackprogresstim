@extends('layouts.main')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('kms.index') }}">KMS</a></li>
            <li class="breadcrumb-item active">Create Division</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">Create New Division</div>
        <div class="card-body">
            <form action="{{ route('kms.divisions.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Division Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Create Division</button>
            </form>
        </div>
    </div>
</div>
@endsection

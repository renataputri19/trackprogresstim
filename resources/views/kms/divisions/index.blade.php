@extends('layouts.main')

@section('content')
    <div class="div-kms-header">
        <h1 class="div-kms-title">Knowledge Management System</h1>
        
        <div class="div-kms-actions">
            <!-- Back Button -->
            <a href="{{ url('/kms') }}" class="div-kms-btn div-kms-btn-secondary">← Back</a>

            <!-- Create New Division Button -->
            <a href="{{ route('kms.divisions.create') }}" class="div-kms-btn div-kms-btn-primary">Create New Division</a>
        </div>
    </div>

    <!-- Divisions -->
    <div class="div-kms-divisions">
        @foreach ($divisions as $division)
            <div class="div-kms-division-card">
                <div class="div-kms-division-body">
                    <h3 class="div-kms-division-title">{{ $division->name }}</h3>
                    <p class="div-kms-division-desc">{{ $division->description }}</p>
                    <div class="div-kms-division-actions">
                        <a href="{{ route('kms.division', $division->slug) }}" class="div-kms-btn div-kms-btn-primary">
                            View Activities
                        </a>
                        <a href="{{ route('kms.divisions.edit', $division->slug) }}" class="div-kms-btn div-kms-btn-secondary">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

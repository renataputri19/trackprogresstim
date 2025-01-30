@extends('layouts.main')


@section('content')
    <div class="div-kms-container">
        <!-- Header -->
        <div class="div-kms-header">
            <h1 class="div-kms-title">Knowledge Management System</h1>
            <a href="{{ route('kms.divisions.create') }}" class="div-kms-btn div-kms-btn-primary">Create New Division</a>
        </div>

        <!-- Filters - Fixed form structure -->
        <div class="div-kms-filters">
            <form action="{{ route('kms.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="month" class="div-kms-form-label">Filter by Month</label>
                    <select name="month" id="month" class="div-kms-form-control">
                        <option value="">All Months</option>
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="year" class="div-kms-form-label">Filter by Year</label>
                    <select name="year" id="year" class="div-kms-form-control">
                        <option value="">All Years</option>
                        @foreach (range(date('Y'), date('Y') - 5) as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="div-kms-btn div-kms-btn-secondary">Filter</button>
                    @if (request('month') || request('year'))
                        <a href="{{ route('kms.index') }}" class="div-kms-btn div-kms-btn-secondary">Clear Filter</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Documents -->
        @if (isset($recentDocuments) && $recentDocuments->count() > 0)
            <div class="div-kms-documents">
                <div class="div-kms-doc-header">
                    {{ request('month') || request('year') ? 'Filtered' : 'Recent' }} Documents
                </div>
                @foreach ($recentDocuments as $document)
                    <div class="div-kms-doc-item">
                        <div class="div-kms-doc-title">{{ $document->title }}</div>
                        <div class="div-kms-doc-meta">
                            {{ $document->document_date->format('d F Y') }}
                            <span class="div-kms-badge div-kms-badge-gray">{{ $document->activity->division->name }}</span>
                            <span class="div-kms-badge div-kms-badge-blue">{{ $document->activity->name }}</span>
                        </div>
                        <div class="mt-2">
                            <a href="{{ $document->onedrive_link }}" target="_blank"
                                class="div-kms-btn div-kms-btn-primary">
                                View Document
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Divisions -->
        <div class="div-kms-divisions">
            @foreach ($divisions as $division)
                <div class="div-kms-division-card">
                    <div class="div-kms-division-body">
                        <h3 class="div-kms-division-title">{{ $division->name }}</h3>
                        <p class="div-kms-division-desc">{{ $division->description }}</p>
                        <div class="div-kms-division-actions">
                            <a href="{{ route('kms.division', $division->slug) }}"
                                class="div-kms-btn div-kms-btn-primary">View Activities</a>
                            <a href="{{ route('kms.divisions.edit', $division->slug) }}"
                                class="div-kms-btn div-kms-btn-secondary">Edit</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection

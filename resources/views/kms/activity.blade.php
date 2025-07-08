@extends('kms.layouts.app')

@section('title', $activity->name . ' - KMS RENTAK')

@section('content')
    <div class="kms-container">
        <div class="container mx-auto px-4 py-8">
            <!-- Hero Section -->
            <div class="kms-hero">
                <div class="kms-hero-content">
                    <h1 class="kms-title">{{ $activity->name }}</h1>
                    <p class="kms-subtitle">
                        {{ $activity->description ?: 'Kelola dokumen dalam aktivitas ini' }}
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="kms-nav">
                <a href="{{ route('kms.index') }}" class="kms-nav-link">KMS</a>
                <span class="kms-nav-separator">/</span>
                <a href="{{ route('kms.divisions.index') }}" class="kms-nav-link">Divisi</a>
                <span class="kms-nav-separator">/</span>
                <a href="{{ route('kms.division', $activity->division->slug) }}" class="kms-nav-link">{{ $activity->division->name }}</a>
                <span class="kms-nav-separator">/</span>
                <span class="text-gray-500">{{ $activity->name }}</span>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <polyline points="20,6 9,17 4,12"></polyline>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="kms-actions">
                <a href="{{ route('kms.division', $activity->division->slug) }}" class="kms-btn kms-btn-secondary kms-btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7"></path>
                        <path d="M19 12H5"></path>
                    </svg>
                    Kembali ke {{ $activity->division->name }}
                </a>
                <a href="{{ route('kms.documents.create', $activity->id) }}" class="kms-btn kms-btn-primary kms-btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                    Tambah Dokumen Baru
                </a>
            </div>

            <!-- Documents Section -->
            @if ($documents->isEmpty())
                <div class="kms-documents">
                    <div class="kms-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="kms-empty-icon">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        <h3 class="kms-empty-title">Belum ada dokumen</h3>
                        <p class="kms-empty-description">
                            Mulai dengan menambahkan dokumen pertama untuk aktivitas ini.
                        </p>
                        <div style="margin-top: 1.5rem;">
                            <a href="{{ route('kms.documents.create', $activity->id) }}" class="kms-btn kms-btn-primary kms-btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5v14"></path>
                                </svg>
                                Tambah Dokumen Pertama
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Documents Accordion by Month -->
                <div class="kms-accordion">
                    @foreach ($documents as $month => $monthDocuments)
                        <div class="kms-accordion-item kms-fade-in" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                            <button class="kms-accordion-header" type="button" aria-expanded="false" aria-controls="accordion-content-{{ $loop->index }}" id="accordion-header-{{ $loop->index }}">
                                <h3 class="kms-accordion-title">{{ $month }}</h3>
                                <div class="kms-accordion-meta">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14,2 14,8 20,8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10,9 9,9 8,9"></polyline>
                                    </svg>
                                    <span>{{ $monthDocuments->count() }} Dokumen</span>
                                </div>
                                <svg class="kms-accordion-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </button>
                            <div class="kms-accordion-content" id="accordion-content-{{ $loop->index }}" aria-labelledby="accordion-header-{{ $loop->index }}" role="region">
                                <div class="kms-accordion-body">
                                    <div class="kms-grid">
                                        @foreach ($monthDocuments as $document)
                                            <div class="kms-card kms-fade-in">
                                                <h3 class="kms-card-title">{{ $document->title }}</h3>
                                                <div class="kms-card-meta">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <polyline points="12,6 12,12 16,14"></polyline>
                                                    </svg>
                                                    {{ $document->document_date->format('d F Y') }}
                                                </div>
                                                @if ($document->description)
                                                    <p class="kms-card-description">{{ $document->description }}</p>
                                                @endif
                                                <div class="kms-card-actions">
                                                    <a href="{{ $document->onedrive_link }}" target="_blank" class="kms-btn kms-btn-primary kms-btn-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                            <polyline points="15,3 21,3 21,9"></polyline>
                                                            <line x1="10" y1="14" x2="21" y2="3"></line>
                                                        </svg>
                                                        Lihat Dokumen
                                                    </a>
                                                    <a href="{{ route('kms.documents.edit', $document->id) }}" class="kms-btn kms-btn-outline kms-btn-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                            <path d="m15 5 4 4"></path>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('kms.documents.destroy', $document->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="kms-btn kms-btn-secondary kms-btn-sm"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')"
                                                                style="background: #fee2e2; color: #dc2626; border-color: #fecaca;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M3 6h18"></path>
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                            </svg>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

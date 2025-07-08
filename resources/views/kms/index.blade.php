@extends('kms.layouts.app')

@section('title', 'Knowledge Management System - RENTAK')

@section('content')
    <div class="kms-container">
        <div class="container mx-auto px-4 py-8">
            <!-- Hero Section -->
            <div class="kms-hero">
                <div class="kms-hero-content">
                    <h1 class="kms-title">Knowledge Management System</h1>
                    <p class="kms-subtitle">
                        Akses dan kelola dokumen pengetahuan organisasi dengan mudah dan terstruktur
                    </p>
                </div>
            </div>

            <!-- Navigation Actions -->
            <div class="kms-actions">
                <a href="{{ route('kms.divisions.index') }}" class="kms-btn kms-btn-primary kms-btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 7V5c0-1.1.9-2 2-2h2"></path>
                        <path d="M17 3h2c1.1 0 2 .9 2 2v2"></path>
                        <path d="M21 17v2c0 1.1-.9 2-2 2h-2"></path>
                        <path d="M7 21H5c-1.1 0-2-.9-2-2v-2"></path>
                        <rect width="7" height="5" x="7" y="7" rx="1"></rect>
                        <rect width="7" height="5" x="10" y="12" rx="1"></rect>
                    </svg>
                    Lihat Semua Divisi
                </a>
            </div>

            <!-- Filters Section -->
            <div class="kms-filters">
                <h3 class="kms-filters-title">Filter Dokumen</h3>
                <form action="{{ route('kms.index') }}" method="GET">
                    <div class="kms-filter-row">
                        <div class="kms-form-group">
                            <label for="month" class="kms-form-label">Filter berdasarkan Bulan</label>
                            <select name="month" id="month" class="kms-form-control">
                                <option value="">Semua Bulan</option>
                                @foreach (range(1, 12) as $month)
                                    <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="kms-form-group">
                            <label for="year" class="kms-form-label">Filter berdasarkan Tahun</label>
                            <select name="year" id="year" class="kms-form-control">
                                <option value="">Semua Tahun</option>
                                @foreach (range(date('Y'), date('Y') - 5) as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="kms-form-group">
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <button type="submit" class="kms-btn kms-btn-primary kms-btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="m21 21-4.35-4.35"></path>
                                    </svg>
                                    Filter
                                </button>
                                @if (request('month') || request('year'))
                                    <a href="{{ route('kms.index') }}" class="kms-btn kms-btn-secondary kms-btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                        </svg>
                                        Hapus Filter
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Divisions Accordion Section -->
            @if($divisions->count() > 0)
                <div class="kms-documents">
                    <div class="kms-documents-header">
                        <h2 class="kms-documents-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; margin-right: 0.5rem;">
                                <path d="M3 7V5c0-1.1.9-2 2-2h2"></path>
                                <path d="M17 3h2c1.1 0 2 .9 2 2v2"></path>
                                <path d="M21 17v2c0 1.1-.9 2-2 2h-2"></path>
                                <path d="M7 21H5c-1.1 0-2-.9-2-2v-2"></path>
                                <rect width="7" height="5" x="7" y="7" rx="1"></rect>
                                <rect width="7" height="5" x="10" y="12" rx="1"></rect>
                            </svg>
                            Divisi & Aktivitas
                        </h2>
                    </div>
                    <div class="kms-documents-content">
                        <div class="kms-accordion">
                            @foreach ($divisions as $division)
                                <div class="kms-accordion-item kms-fade-in" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                                    <button class="kms-accordion-header" type="button" aria-expanded="false" aria-controls="accordion-content-{{ $loop->index }}" id="accordion-header-{{ $loop->index }}">
                                        <h3 class="kms-accordion-title">{{ $division->name }}</h3>
                                        <div class="kms-accordion-meta">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                                <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                                            </svg>
                                            <span>{{ $division->activities->count() }} Aktivitas</span>
                                        </div>
                                        <svg class="kms-accordion-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="m6 9 6 6 6-6"/>
                                        </svg>
                                    </button>
                                    <div class="kms-accordion-content" id="accordion-content-{{ $loop->index }}" aria-labelledby="accordion-header-{{ $loop->index }}" role="region">
                                        <div class="kms-accordion-body">
                                            @if($division->description)
                                                <p class="kms-accordion-description">{{ $division->description }}</p>
                                            @else
                                                <p class="kms-accordion-description">Tidak ada deskripsi untuk divisi ini.</p>
                                            @endif
                                            <div class="kms-accordion-actions">
                                                <a href="{{ route('kms.division', $division->slug) }}" class="kms-btn kms-btn-primary kms-btn-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                                    </svg>
                                                    Lihat Aktivitas
                                                </a>
                                                <a href="{{ route('kms.divisions.edit', $division->slug) }}" class="kms-btn kms-btn-outline kms-btn-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                        <path d="m15 5 4 4"></path>
                                                    </svg>
                                                    Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Documents Section -->
            @if (isset($recentDocuments) && $recentDocuments->count() > 0)
                <div class="kms-documents">
                    <div class="kms-documents-header">
                        <h2 class="kms-documents-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; margin-right: 0.5rem;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14,2 14,8 20,8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10,9 9,9 8,9"></polyline>
                            </svg>
                            {{ request('month') || request('year') ? 'Dokumen Terfilter' : 'Dokumen Terbaru' }}
                        </h2>
                    </div>
                    <div class="kms-documents-content">
                        <div class="kms-grid">
                            @foreach ($recentDocuments as $document)
                                <div class="kms-card kms-fade-in">
                                    <h3 class="kms-card-title">{{ $document->title }}</h3>
                                    <div class="kms-card-meta">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12,6 12,12 16,14"></polyline>
                                        </svg>
                                        {{ $document->document_date->format('d F Y') }}
                                    </div>
                                    <div class="kms-card-description">
                                        <span class="kms-badge kms-badge-secondary">{{ $document->activity->division->name }}</span>
                                        <span class="kms-badge kms-badge-primary">{{ $document->activity->name }}</span>
                                    </div>
                                    <div class="kms-card-actions">
                                        <a href="{{ $document->onedrive_link }}" target="_blank" class="kms-btn kms-btn-primary kms-btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <polyline points="15,3 21,3 21,9"></polyline>
                                                <line x1="10" y1="14" x2="21" y2="3"></line>
                                            </svg>
                                            Lihat Dokumen
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="kms-documents">
                    <div class="kms-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="kms-empty-icon">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        <h3 class="kms-empty-title">Tidak ada dokumen ditemukan</h3>
                        <p class="kms-empty-description">
                            {{ request('month') || request('year') ? 'Tidak ada dokumen yang sesuai dengan filter yang dipilih.' : 'Belum ada dokumen yang tersedia saat ini.' }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

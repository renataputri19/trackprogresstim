@extends('kms.layouts.app')

@section('title', 'Semua Divisi - KMS RENTAK')

@section('content')
    <div class="kms-container">
        <div class="container mx-auto px-4 py-8">
            <!-- Hero Section -->
            <div class="kms-hero">
                <div class="kms-hero-content">
                    <h1 class="kms-title">Semua Divisi</h1>
                    <p class="kms-subtitle">
                        Kelola dan akses divisi dalam Knowledge Management System
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="kms-nav">
                <a href="{{ route('kms.index') }}" class="kms-nav-link">KMS</a>
                <span class="kms-nav-separator">/</span>
                <span class="text-gray-500">Semua Divisi</span>
            </div>

            <!-- Actions -->
            <div class="kms-actions">
                <a href="{{ route('kms.index') }}" class="kms-btn kms-btn-secondary kms-btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7"></path>
                        <path d="M19 12H5"></path>
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('kms.divisions.create') }}" class="kms-btn kms-btn-primary kms-btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                    Buat Divisi Baru
                </a>
            </div>

            <!-- Divisions Accordion -->
            @if($divisions->count() > 0)
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
            @else
                <div class="kms-documents">
                    <div class="kms-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="kms-empty-icon">
                            <path d="M3 7V5c0-1.1.9-2 2-2h2"></path>
                            <path d="M17 3h2c1.1 0 2 .9 2 2v2"></path>
                            <path d="M21 17v2c0 1.1-.9 2-2 2h-2"></path>
                            <path d="M7 21H5c-1.1 0-2-.9-2-2v-2"></path>
                            <rect width="7" height="5" x="7" y="7" rx="1"></rect>
                            <rect width="7" height="5" x="10" y="12" rx="1"></rect>
                        </svg>
                        <h3 class="kms-empty-title">Belum ada divisi</h3>
                        <p class="kms-empty-description">
                            Mulai dengan membuat divisi pertama untuk mengorganisir dokumen pengetahuan.
                        </p>
                        <div style="margin-top: 1.5rem;">
                            <a href="{{ route('kms.divisions.create') }}" class="kms-btn kms-btn-primary kms-btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5v14"></path>
                                </svg>
                                Buat Divisi Pertama
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

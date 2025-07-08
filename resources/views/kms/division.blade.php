@extends('kms.layouts.app')

@section('title', $division->name . ' - KMS RENTAK')

@section('content')
    <div class="kms-container">
        <div class="container mx-auto px-4 py-8">
            <!-- Hero Section -->
            <div class="kms-hero">
                <div class="kms-hero-content">
                    <h1 class="kms-title">{{ $division->name }}</h1>
                    <p class="kms-subtitle">
                        {{ $division->description ?: 'Kelola aktivitas dan dokumen dalam divisi ini' }}
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="kms-nav">
                <a href="{{ route('kms.index') }}" class="kms-nav-link">KMS</a>
                <span class="kms-nav-separator">/</span>
                <a href="{{ route('kms.divisions.index') }}" class="kms-nav-link">Divisi</a>
                <span class="kms-nav-separator">/</span>
                <span class="text-gray-500">{{ $division->name }}</span>
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
                <a href="{{ route('kms.divisions.index') }}" class="kms-btn kms-btn-secondary kms-btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7"></path>
                        <path d="M19 12H5"></path>
                    </svg>
                    Kembali ke Divisi
                </a>
                <a href="{{ route('kms.activities.create', $division->slug) }}" class="kms-btn kms-btn-primary kms-btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                    Buat Aktivitas Baru
                </a>
            </div>

            <!-- Activities Accordion -->
            @if($division->activities->count() > 0)
                <div class="kms-accordion">
                    @foreach($division->activities as $activity)
                        <div class="kms-accordion-item kms-fade-in" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                            <button class="kms-accordion-header" type="button" aria-expanded="false" aria-controls="accordion-content-{{ $loop->index }}" id="accordion-header-{{ $loop->index }}">
                                <h3 class="kms-accordion-title">{{ $activity->name }}</h3>
                                <div class="kms-accordion-meta">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14,2 14,8 20,8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10,9 9,9 8,9"></polyline>
                                    </svg>
                                    <span>{{ $activity->documents->count() }} Dokumen</span>
                                </div>
                                <svg class="kms-accordion-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </button>
                            <div class="kms-accordion-content" id="accordion-content-{{ $loop->index }}" aria-labelledby="accordion-header-{{ $loop->index }}" role="region">
                                <div class="kms-accordion-body">
                                    @if($activity->description)
                                        <p class="kms-accordion-description">{{ $activity->description }}</p>
                                    @else
                                        <p class="kms-accordion-description">Tidak ada deskripsi tersedia untuk aktivitas ini.</p>
                                    @endif
                                    <div class="kms-accordion-actions">
                                        <a href="{{ route('kms.activity', [$division->slug, $activity->slug]) }}" class="kms-btn kms-btn-primary kms-btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                            </svg>
                                            Lihat Dokumen
                                        </a>
                                        <a href="{{ route('kms.activities.edit', $activity->id) }}" class="kms-btn kms-btn-outline kms-btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                <path d="m15 5 4 4"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('kms.activities.destroy', $activity->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="kms-btn kms-btn-secondary kms-btn-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus aktivitas ini?')"
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
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="kms-documents">
                    <div class="kms-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="kms-empty-icon">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                        </svg>
                        <h3 class="kms-empty-title">Belum ada aktivitas</h3>
                        <p class="kms-empty-description">
                            Mulai dengan membuat aktivitas pertama untuk mengorganisir dokumen dalam divisi ini.
                        </p>
                        <div style="margin-top: 1.5rem;">
                            <a href="{{ route('kms.activities.create', $division->slug) }}" class="kms-btn kms-btn-primary kms-btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5v14"></path>
                                </svg>
                                Buat Aktivitas Pertama
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@extends('kms.layouts.app')

@section('title', 'Tambah Dokumen - ' . $activity->name . ' - KMS RENTAK')

@section('content')
    <div class="kms-container">
        <div class="container mx-auto px-4 py-8">
            <!-- Hero Section -->
            <div class="kms-hero">
                <div class="kms-hero-content">
                    <h1 class="kms-title">Tambah Dokumen Baru</h1>
                    <p class="kms-subtitle">
                        Tambahkan dokumen untuk aktivitas {{ $activity->name }}
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
                <a href="{{ route('kms.activity', [$activity->division->slug, $activity->slug]) }}" class="kms-nav-link">{{ $activity->name }}</a>
                <span class="kms-nav-separator">/</span>
                <span class="text-gray-500">Tambah Dokumen</span>
            </div>

            <!-- Actions -->
            <div class="kms-actions">
                <a href="{{ route('kms.activity', [$activity->division->slug, $activity->slug]) }}" class="kms-btn kms-btn-secondary kms-btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7"></path>
                        <path d="M19 12H5"></path>
                    </svg>
                    Kembali ke {{ $activity->name }}
                </a>
            </div>

            <!-- Form -->
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
                        Informasi Dokumen
                    </h2>
                </div>
                <div class="kms-documents-content" style="padding: 2rem;">
                    <form action="{{ route('kms.documents.store', $activity->id) }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="kms-form-group">
                            <label for="title" class="kms-form-label">Judul Dokumen *</label>
                            <input type="text"
                                   class="kms-form-control @error('title') border-red-500 @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   required
                                   placeholder="Masukkan judul dokumen">
                            @error('title')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="kms-form-group">
                            <label for="document_date" class="kms-form-label">Tanggal Dokumen *</label>
                            <input type="date"
                                   class="kms-form-control @error('document_date') border-red-500 @enderror"
                                   id="document_date"
                                   name="document_date"
                                   value="{{ old('document_date') }}"
                                   required>
                            @error('document_date')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="kms-form-group">
                            <label for="onedrive_link" class="kms-form-label">Link Dokumen *</label>
                            <input type="url"
                                   class="kms-form-control @error('onedrive_link') border-red-500 @enderror"
                                   id="onedrive_link"
                                   name="onedrive_link"
                                   value="{{ old('onedrive_link') }}"
                                   required
                                   placeholder="https://example.com/document">
                            @error('onedrive_link')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="kms-form-group">
                            <label for="description" class="kms-form-label">Deskripsi</label>
                            <textarea class="kms-form-control"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Masukkan deskripsi dokumen (opsional)">{{ old('description') }}</textarea>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="kms-btn kms-btn-primary kms-btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20,6 9,17 4,12"></polyline>
                                </svg>
                                Tambah Dokumen
                            </button>
                            <a href="{{ route('kms.activity', [$activity->division->slug, $activity->slug]) }}" class="kms-btn kms-btn-secondary kms-btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 6 6 18"></path>
                                    <path d="M6 6l12 12"></path>
                                </svg>
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

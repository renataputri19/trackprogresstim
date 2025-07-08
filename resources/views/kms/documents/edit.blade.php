@extends('kms.layouts.app')

@section('title', 'Edit ' . $document->title . ' - KMS RENTAK')

@section('content')
    <div class="kms-container">
        <div class="container mx-auto px-4 py-8">
            <!-- Hero Section -->
            <div class="kms-hero">
                <div class="kms-hero-content">
                    <h1 class="kms-title">Edit Dokumen</h1>
                    <p class="kms-subtitle">
                        Perbarui informasi dokumen {{ $document->title }}
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="kms-nav">
                <a href="{{ route('kms.index') }}" class="kms-nav-link">KMS</a>
                <span class="kms-nav-separator">/</span>
                <a href="{{ route('kms.divisions.index') }}" class="kms-nav-link">Divisi</a>
                <span class="kms-nav-separator">/</span>
                <a href="{{ route('kms.division', $document->activity->division->slug) }}" class="kms-nav-link">{{ $document->activity->division->name }}</a>
                <span class="kms-nav-separator">/</span>
                <a href="{{ route('kms.activity', [$document->activity->division->slug, $document->activity->slug]) }}" class="kms-nav-link">{{ $document->activity->name }}</a>
                <span class="kms-nav-separator">/</span>
                <span class="text-gray-500">Edit Dokumen</span>
            </div>

            <!-- Actions -->
            <div class="kms-actions">
                <a href="{{ route('kms.activity', [$document->activity->division->slug, $document->activity->slug]) }}" class="kms-btn kms-btn-secondary kms-btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7"></path>
                        <path d="M19 12H5"></path>
                    </svg>
                    Kembali ke {{ $document->activity->name }}
                </a>
            </div>

            <!-- Form -->
            <div class="kms-documents">
                <div class="kms-documents-header">
                    <h2 class="kms-documents-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; margin-right: 0.5rem;">
                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                            <path d="m15 5 4 4"></path>
                        </svg>
                        Edit Informasi Dokumen
                    </h2>
                </div>
                <div class="kms-documents-content" style="padding: 2rem;">
                    <form action="{{ route('kms.documents.update', $document->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div class="kms-form-group">
                            <label for="title" class="kms-form-label">Judul Dokumen *</label>
                            <input type="text"
                                   class="kms-form-control @error('title') border-red-500 @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $document->title) }}"
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
                                   value="{{ old('document_date', $document->document_date->format('Y-m-d')) }}"
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
                                   value="{{ old('onedrive_link', $document->onedrive_link) }}"
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
                                      placeholder="Masukkan deskripsi dokumen (opsional)">{{ old('description', $document->description) }}</textarea>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="kms-btn kms-btn-primary kms-btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20,6 9,17 4,12"></polyline>
                                </svg>
                                Perbarui Dokumen
                            </button>
                            <a href="{{ route('kms.activity', [$document->activity->division->slug, $document->activity->slug]) }}" class="kms-btn kms-btn-secondary kms-btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 6 6 18"></path>
                                    <path d="M6 6l12 12"></path>
                                </svg>
                                Batal
                            </a>
                        </div>
                    </form>

                    <!-- Delete Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-red-600 mb-4">Zona Bahaya</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Menghapus dokumen akan menghilangkan akses ke dokumen ini secara permanen. Tindakan ini tidak dapat dibatalkan.
                        </p>
                        <form action="{{ route('kms.documents.destroy', $document->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="kms-btn kms-btn-secondary kms-btn-sm"
                                    style="background: #fee2e2; color: #dc2626; border-color: #fecaca;"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                </svg>
                                Hapus Dokumen
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

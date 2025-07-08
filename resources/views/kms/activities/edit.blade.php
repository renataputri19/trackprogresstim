@extends('kms.layouts.app')

@section('title', 'Edit ' . $activity->name . ' - KMS RENTAK')

@section('content')
    <div class="kms-container">
        <div class="container mx-auto px-4 py-8">
            <!-- Hero Section -->
            <div class="kms-hero">
                <div class="kms-hero-content">
                    <h1 class="kms-title">Edit Aktivitas</h1>
                    <p class="kms-subtitle">
                        Perbarui informasi aktivitas {{ $activity->name }}
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
                <span class="text-gray-500">Edit {{ $activity->name }}</span>
            </div>

            <!-- Actions -->
            <div class="kms-actions">
                <a href="{{ route('kms.division', $activity->division->slug) }}" class="kms-btn kms-btn-secondary kms-btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7"></path>
                        <path d="M19 12H5"></path>
                    </svg>
                    Kembali ke {{ $activity->division->name }}
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
                        Edit Informasi Aktivitas
                    </h2>
                </div>
                <div class="kms-documents-content" style="padding: 2rem;">
                    <form action="{{ route('kms.activities.update', $activity->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div class="kms-form-group">
                            <label for="name" class="kms-form-label">Nama Aktivitas *</label>
                            <input type="text"
                                   class="kms-form-control @error('name') border-red-500 @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $activity->name) }}"
                                   required
                                   placeholder="Masukkan nama aktivitas">
                            @error('name')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="kms-form-group">
                            <label for="description" class="kms-form-label">Deskripsi</label>
                            <textarea class="kms-form-control"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Masukkan deskripsi aktivitas (opsional)">{{ old('description', $activity->description) }}</textarea>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="kms-btn kms-btn-primary kms-btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20,6 9,17 4,12"></polyline>
                                </svg>
                                Perbarui Aktivitas
                            </button>
                            <a href="{{ route('kms.division', $activity->division->slug) }}" class="kms-btn kms-btn-secondary kms-btn-sm">
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
                            Menghapus aktivitas akan menghapus semua dokumen yang terkait. Tindakan ini tidak dapat dibatalkan.
                        </p>
                        <form action="{{ route('kms.activities.destroy', $activity->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="kms-btn kms-btn-secondary kms-btn-sm"
                                    style="background: #fee2e2; color: #dc2626; border-color: #fecaca;"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus aktivitas ini? Semua dokumen akan ikut terhapus.')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                </svg>
                                Hapus Aktivitas
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

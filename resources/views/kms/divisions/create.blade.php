@extends('kms.layouts.app')

@section('title', 'Buat Divisi Baru - KMS RENTAK')

@section('content')
    <div class="kms-container">
        <div class="container mx-auto px-4 py-8">
            <!-- Hero Section -->
            <div class="kms-hero">
                <div class="kms-hero-content">
                    <h1 class="kms-title">Buat Divisi Baru</h1>
                    <p class="kms-subtitle">
                        Tambahkan divisi baru untuk mengorganisir dokumen pengetahuan
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="kms-nav">
                <a href="{{ route('kms.index') }}" class="kms-nav-link">KMS</a>
                <span class="kms-nav-separator">/</span>
                <a href="{{ route('kms.divisions.index') }}" class="kms-nav-link">Divisi</a>
                <span class="kms-nav-separator">/</span>
                <span class="text-gray-500">Buat Baru</span>
            </div>

            <!-- Actions -->
            <div class="kms-actions">
                <a href="{{ route('kms.divisions.index') }}" class="kms-btn kms-btn-secondary kms-btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7"></path>
                        <path d="M19 12H5"></path>
                    </svg>
                    Kembali ke Divisi
                </a>
            </div>

            <!-- Form -->
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
                        Informasi Divisi
                    </h2>
                </div>
                <div class="kms-documents-content" style="padding: 2rem;">
                    <form action="{{ route('kms.divisions.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="kms-form-group">
                            <label for="name" class="kms-form-label">Nama Divisi *</label>
                            <input type="text"
                                   class="kms-form-control @error('name') border-red-500 @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="Masukkan nama divisi">
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
                                      placeholder="Masukkan deskripsi divisi (opsional)">{{ old('description') }}</textarea>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="kms-btn kms-btn-primary kms-btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20,6 9,17 4,12"></polyline>
                                </svg>
                                Buat Divisi
                            </button>
                            <a href="{{ route('kms.divisions.index') }}" class="kms-btn kms-btn-secondary kms-btn-sm">
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

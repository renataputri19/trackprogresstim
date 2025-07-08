@extends('new-homepage.layouts.app')

@section('title', 'Under Development - RENTAK')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-teal-50">
    <!-- Header -->
    <div class="relative overflow-hidden bg-gradient-to-r from-teal-600 via-emerald-500 to-teal-600 py-16">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">
                    Sedang Dalam Pengembangan
                </h1>
                <div class="mx-auto mt-6 max-w-2xl text-xl text-teal-100">
                    <p>Aplikasi ini sedang dalam tahap pengembangan dan akan segera tersedia untuk Anda.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">
            <!-- Status Card -->
            <div class="overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-gray-200/50">
                <div class="border-b border-gray-200 bg-gradient-to-r from-teal-50 to-emerald-50 px-6 py-4">
                    <h2 class="text-2xl font-bold text-gray-900">Status Pengembangan</h2>
                    <p class="mt-1 text-sm text-gray-600">Informasi terkini mengenai progres aplikasi</p>
                </div>

                <div class="p-6">
                    <!-- Progress Indicator -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Progres Pengembangan</span>
                            <span class="text-sm font-medium text-teal-600">75%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-teal-500 to-emerald-500 h-2 rounded-full transition-all duration-300" style="width: 75%"></div>
                        </div>
                    </div>

                    <!-- Development Phases -->
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                                        <path d="M20 6L9 17l-5-5"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Analisis Kebutuhan</h3>
                                <p class="text-sm text-gray-600">Identifikasi fitur dan spesifikasi sistem telah selesai</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                                        <path d="M20 6L9 17l-5-5"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Desain Sistem</h3>
                                <p class="text-sm text-gray-600">Arsitektur dan desain antarmuka telah dirancang</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-teal-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-teal-600">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="M12 6v6l4 2"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Pengembangan</h3>
                                <p class="text-sm text-gray-600">Sedang dalam proses implementasi fitur-fitur utama</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="M12 6v6l4 2"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Testing & Quality Assurance</h3>
                                <p class="text-sm text-gray-400">Pengujian sistem dan penjaminan kualitas</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="M12 6v6l4 2"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Deployment</h3>
                                <p class="text-sm text-gray-400">Peluncuran aplikasi untuk pengguna</p>
                            </div>
                        </div>
                    </div>

                    <!-- Estimated Timeline -->
                    <div class="mt-8 rounded-lg bg-gradient-to-r from-teal-50 to-emerald-50 p-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-teal-600">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Estimasi Peluncuran</h3>
                                <p class="text-sm text-gray-600">Kuartal 4 2025 - Kami sedang bekerja keras untuk menyelesaikan aplikasi ini</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Butuh Informasi Lebih Lanjut?</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Jika Anda memiliki pertanyaan atau saran mengenai pengembangan aplikasi ini, 
                            silakan hubungi tim pengembangan kami.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="mailto:it@bpsbatam.go.id" 
                               class="inline-flex items-center justify-center rounded-md bg-teal-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-teal-700 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                Hubungi Tim IT
                            </a>
                            <a href="{{ route('welcome') }}" 
                               class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                    <path d="m12 19-7-7 7-7"/>
                                    <path d="M19 12H5"/>
                                </svg>
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

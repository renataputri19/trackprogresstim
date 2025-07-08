@extends('new-homepage.layouts.app')

@section('title', 'Sistem Validasi BAHTERA - RENTAK')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-teal-50">
    <!-- Header Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-teal-600 via-emerald-500 to-teal-600 py-16">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">
                    Sistem Validasi BAHTERA
                </h1>
                <div class="mx-auto mt-6 max-w-3xl text-xl text-teal-100">
                    <p class="text-2xl font-semibold">Batam Harmonisasi dan Evaluasi Terpadu Rekaman Akomodasi</p>
                    <p class="mt-4 text-lg">Survei Jasa Akomodasi Bulanan (VHTS) di Kota Batam bertujuan untuk memperoleh data berbagai indikator perkembangan usaha akomodasi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <!-- System Description -->
        <div class="mb-8 overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-gray-200/50">
            <div class="border-b border-gray-200 bg-gradient-to-r from-teal-50 to-emerald-50 px-6 py-4">
                <h2 class="text-2xl font-bold text-gray-900">Tentang Sistem BAHTERA</h2>
                <p class="mt-1 text-sm text-gray-600">Batam Harmonisasi dan Evaluasi Terpadu Rekaman Akomodasi</p>
            </div>

            <div class="p-6">
                <div class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed">
                        Survei Jasa Akomodasi Bulanan (VHTS) di Kota Batam bertujuan untuk memperoleh data berbagai indikator perkembangan usaha akomodasi, seperti:
                    </p>
                    <ul class="mt-4 space-y-2 text-gray-700">
                        <li class="flex items-start">
                            <span class="mr-2 text-teal-600">•</span>
                            Tingkat penghunian kamar hotel (TPK)
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2 text-teal-600">•</span>
                            Jumlah malam kamar yang terpakai
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2 text-teal-600">•</span>
                            Jumlah tamu yang datang dan menginap
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2 text-teal-600">•</span>
                            Rata-rata lama menginap tamu, serta
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2 text-teal-600">•</span>
                            Jumlah malam tamu menginap
                        </li>
                    </ul>
                    <p class="mt-4 text-gray-700 leading-relaxed">
                        Survei ini dilaksanakan setiap bulan, mencakup akomodasi berbintang dan nonbintang. Untuk akomodasi berbintang, pendataan dilakukan secara lengkap (sensus), sementara akomodasi nonbintang dicakup melalui metode sampling. Indikator yang dihasilkan dari survei ini didiseminasikan setiap bulan melalui Berita Resmi Statistik (BRS).
                    </p>
                </div>
            </div>
        </div>

        <!-- Background Section -->
        <div class="mb-8 overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-gray-200/50">
            <div class="border-b border-gray-200 bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-4">
                <h2 class="text-2xl font-bold text-gray-900">Latar Belakang BAHTERA</h2>
                <p class="mt-1 text-sm text-gray-600">Inovasi dalam sistem validasi data akomodasi</p>
            </div>

            <div class="p-6">
                <div class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed">
                        Sistem BAHTERA dikembangkan sebagai bagian dari upaya integrasi dengan sistem FASIH (Framework Aplikasi Statistik Inovatif dan Harmonis) untuk meningkatkan efisiensi dan akurasi dalam proses validasi data survei akomodasi. Sistem ini dirancang untuk memberikan solusi komprehensif dalam mengelola dan memvalidasi data VHTS dengan teknologi terdepan.
                    </p>
                </div>
            </div>
        </div>

        <!-- Objectives Section -->
        <div class="mb-8 overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-gray-200/50">
            <div class="border-b border-gray-200 bg-gradient-to-r from-purple-50 to-teal-50 px-6 py-4">
                <h2 class="text-2xl font-bold text-gray-900">Tujuan</h2>
                <p class="mt-1 text-sm text-gray-600">Tujuan utama sistem BAHTERA</p>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div class="rounded-lg bg-teal-50 p-4">
                        <div class="flex items-center">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-teal-100 text-teal-600">
                                <span class="text-sm font-bold">1</span>
                            </div>
                            <h3 class="ml-3 text-lg font-semibold text-gray-900">Efisiensi Validasi</h3>
                        </div>
                        <p class="mt-2 text-sm text-gray-700">Meningkatkan efisiensi proses validasi data survei akomodasi dengan otomatisasi yang cerdas</p>
                    </div>

                    <div class="rounded-lg bg-emerald-50 p-4">
                        <div class="flex items-center">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                                <span class="text-sm font-bold">2</span>
                            </div>
                            <h3 class="ml-3 text-lg font-semibold text-gray-900">Akurasi Data</h3>
                        </div>
                        <p class="mt-2 text-sm text-gray-700">Memastikan akurasi dan konsistensi data melalui algoritma validasi yang komprehensif</p>
                    </div>

                    <div class="rounded-lg bg-purple-50 p-4">
                        <div class="flex items-center">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100 text-purple-600">
                                <span class="text-sm font-bold">3</span>
                            </div>
                            <h3 class="ml-3 text-lg font-semibold text-gray-900">Integrasi Sistem</h3>
                        </div>
                        <p class="mt-2 text-sm text-gray-700">Mengintegrasikan proses validasi dengan sistem FASIH untuk harmonisasi data statistik</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Version Selection -->
        <div class="overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-gray-200/50">
            <div class="border-b border-gray-200 bg-gradient-to-r from-teal-50 to-emerald-50 px-6 py-4">
                <h2 class="text-2xl font-bold text-gray-900">Pilih Versi BAHTERA</h2>
                <p class="mt-1 text-sm text-gray-600">Pilih versi sistem validasi yang ingin Anda gunakan</p>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- BAHTERA Version 1 -->
                    <div class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:border-teal-300 hover:shadow-lg">
                        <div class="flex items-start">
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 text-blue-600 group-hover:bg-blue-200 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <path d="M14 2v6h6"/>
                                    <path d="M16 13H8"/>
                                    <path d="M16 17H8"/>
                                    <path d="M10 9H8"/>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-teal-700">BAHTERA Version 1</h3>
                                <p class="mt-1 text-sm text-gray-600">Versi standar sistem validasi BAHTERA</p>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Fitur:</h4>
                            <ul class="space-y-1 text-sm text-gray-600">
                                <li class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-green-500">
                                        <path d="M9 12l2 2 4-4"/>
                                        <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c1.51 0 2.93.37 4.18 1.03"/>
                                    </svg>
                                    Validasi data survei hotel standar
                                </li>
                                <li class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-green-500">
                                        <path d="M9 12l2 2 4-4"/>
                                        <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c1.51 0 2.93.37 4.18 1.03"/>
                                    </svg>
                                    Import data dari Excel
                                </li>
                                <li class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-green-500">
                                        <path d="M9 12l2 2 4-4"/>
                                        <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c1.51 0 2.93.37 4.18 1.03"/>
                                    </svg>
                                    Laporan hasil validasi
                                </li>
                            </ul>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('bahtera.v1.index') }}"
                               class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Gunakan Version 1
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2">
                                    <path d="M7 17L17 7"/>
                                    <path d="M7 7h10v10"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- BAHTERA Version 2 -->
                    <div class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:border-teal-300 hover:shadow-lg">
                        <div class="flex items-start">
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 group-hover:bg-emerald-200 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <path d="M14 2v6h6"/>
                                    <path d="M16 13H8"/>
                                    <path d="M16 17H8"/>
                                    <path d="M10 9H8"/>
                                    <circle cx="12" cy="12" r="1"/>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-teal-700">BAHTERA Version 2</h3>
                                <p class="mt-1 text-sm text-gray-600">Versi enhanced dengan fitur tambahan</p>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Fitur:</h4>
                            <ul class="space-y-1 text-sm text-gray-600">
                                <li class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-green-500">
                                        <path d="M9 12l2 2 4-4"/>
                                        <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c1.51 0 2.93.37 4.18 1.03"/>
                                    </svg>
                                    Validasi data dengan parameter tambahan
                                </li>
                                <li class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-green-500">
                                        <path d="M9 12l2 2 4-4"/>
                                        <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c1.51 0 2.93.37 4.18 1.03"/>
                                    </svg>
                                    Konfigurasi bulan dan tahun
                                </li>
                                <li class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-green-500">
                                        <path d="M9 12l2 2 4-4"/>
                                        <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c1.51 0 2.93.37 4.18 1.03"/>
                                    </svg>
                                    Reference TPK yang dapat disesuaikan
                                </li>
                                <li class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-green-500">
                                        <path d="M9 12l2 2 4-4"/>
                                        <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c1.51 0 2.93.37 4.18 1.03"/>
                                    </svg>
                                    Analisis data yang lebih detail
                                </li>
                            </ul>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('bahtera.v2.index') }}"
                               class="inline-flex w-full items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                Gunakan Version 2
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2">
                                    <path d="M7 17L17 7"/>
                                    <path d="M7 7h10v10"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- BAHTERA Version 3 -->
                    <div class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:border-teal-300 hover:shadow-lg">
                        <div class="flex items-start">
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 text-purple-600 group-hover:bg-purple-200 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <path d="M14 2v6h6"/>
                                    <path d="M16 13H8"/>
                                    <path d="M16 17H8"/>
                                    <path d="M10 9H8"/>
                                    <circle cx="12" cy="12" r="1"/>
                                    <circle cx="12" cy="8" r="1"/>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-teal-700">BAHTERA Version 3</h3>
                                <p class="mt-1 text-sm text-gray-600">Versi terbaru dengan validasi tamu asing yang diperbaiki</p>
                                <div class="mt-1 text-xs text-purple-700 bg-purple-100 px-2 py-1 rounded-full inline-block">
                                    ✨ TERBARU
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Fitur:</h4>
                            <ul class="space-y-1 text-sm text-gray-600">
                                <li class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-green-500">
                                        <path d="M9 12l2 2 4-4"/>
                                        <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c1.51 0 2.93.37 4.18 1.03"/>
                                    </svg>
                                    Validasi alur tamu asing yang konsisten
                                </li>
                                <li class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-green-500">
                                        <path d="M9 12l2 2 4-4"/>
                                        <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c1.51 0 2.93.37 4.18 1.03"/>
                                    </svg>
                                    Sistem prioritas validasi yang cerdas
                                </li>
                                <li class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-green-500">
                                        <path d="M9 12l2 2 4-4"/>
                                        <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c1.51 0 2.93.37 4.18 1.03"/>
                                    </svg>
                                    Enhanced guest flow logic
                                </li>
                                <li class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-green-500">
                                        <path d="M9 12l2 2 4-4"/>
                                        <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c1.51 0 2.93.37 4.18 1.03"/>
                                    </svg>
                                    Semua fitur v1 & v2 + perbaikan
                                </li>
                            </ul>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('bahtera.v3.index') }}"
                               class="inline-flex w-full items-center justify-center rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                Gunakan Version 3
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2">
                                    <path d="M7 17L17 7"/>
                                    <path d="M7 7h10v10"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mt-8 rounded-lg bg-teal-50 p-6">
                    <div class="flex items-start">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-teal-100 text-teal-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 16v-4"/>
                                <path d="M12 8h.01"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-teal-900">Informasi Penting</h3>
                            <div class="mt-2 text-sm text-teal-700">
                                <p>• Semua versi sistem validasi BAHTERA dapat digunakan sesuai kebutuhan</p>
                                <p>• Version 1: Validasi standar | Version 2: Analisis mendalam | Version 3: Enhanced guest flow logic</p>
                                <p>• Version 3 direkomendasikan untuk data dengan tamu asing yang kompleks</p>
                                <p>• Pastikan data Excel sudah dalam format yang sesuai sebelum melakukan validasi</p>
                                <p>• Untuk bantuan teknis, hubungi tim IT melalui sistem HaloIP</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Dashboard -->
        <div class="mt-8 text-center">
            <a href="{{ route('welcome') }}" 
               class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-colors duration-200 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                    <path d="M19 12H5"/>
                    <path d="M12 19l-7-7 7-7"/>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection

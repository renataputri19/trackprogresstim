@extends('new-homepage.layouts.app')

@section('title', 'Dokumentasi - RENTAK')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/documentation.css') }}" />
@endsection

@section('content')
<div class="documentation py-10 md:py-16">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mx-auto flex max-w-[58rem] flex-col items-center space-y-4 text-center">
            <div class="inline-block rounded-full bg-teal-100 px-4 py-1.5 text-sm font-medium text-teal-700 border border-teal-200">
                Panduan Komprehensif
            </div>
            <h1 class="text-3xl font-bold leading-[1.1] sm:text-3xl md:text-5xl">Dokumentasi RENTAK</h1>
            <p class="max-w-[85%] leading-normal text-gray-500 sm:text-lg sm:leading-7">
                Panduan detail dan bahan referensi untuk semua sistem dan fitur RENTAK
            </p>
        </div>

        <!-- Search Bar -->
        <div class="mt-8 mx-auto max-w-md">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-500">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
                <input
                    type="text"
                    id="search-docs"
                    placeholder="Cari dokumentasi..."
                    class="w-full rounded-md border border-teal-200 bg-white pl-10 pr-4 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500"
                >
            </div>
        </div>

        <div class="mt-12 flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:w-64 shrink-0">
                <div class="sticky top-24 rounded-lg border border-teal-200/50 bg-white p-4 shadow-md">
                    <h3 class="mb-4 text-lg font-semibold">Daftar Isi</h3>
                    <nav class="space-y-1">
                        @foreach($categories as $category)
                            <a
                                href="#{{ Str::slug($category->category) }}"
                                class="flex items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-teal-50 hover:text-teal-700"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                    @if($category->icon === 'rocket')
                                        <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"></path>
                                        <path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"></path>
                                        <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"></path>
                                        <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"></path>
                                    @elseif($category->icon === 'layout-dashboard')
                                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                                        <line x1="3" x2="21" y1="9" y2="9"></line>
                                        <line x1="9" x2="9" y1="21" y2="9"></line>
                                    @elseif($category->icon === 'settings')
                                        <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    @endif
                                </svg>
                                {{ $category->category }}
                            </a>
                        @endforeach
                    </nav>

                    <div class="mt-8 border-t border-teal-100 pt-4">
                        <h4 class="mb-3 text-sm font-medium">Pembaruan Terbaru</h4>
                        <div class="space-y-3">
                            @foreach($recentUpdates as $update)
                                <div class="rounded-md bg-teal-50 p-3">
                                    <h5 class="text-sm font-medium text-teal-700">{{ $update['title'] }}</h5>
                                    <p class="mt-1 text-xs text-gray-500">{{ $update['date'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1">
                <div id="search-results" class="hidden mb-8">
                    <h2 class="text-2xl font-bold mb-4">Hasil Pencarian</h2>
                    <div id="results-container" class="grid gap-4"></div>
                </div>

                <div id="documentation-content">
                    @foreach($categories as $category)
                        <section id="{{ Str::slug($category->category) }}" class="category-section mb-12">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-teal-500/10 to-emerald-500/10 text-teal-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                        @if($category->icon === 'rocket')
                                            <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"></path>
                                            <path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"></path>
                                            <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"></path>
                                            <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"></path>
                                        @elseif($category->icon === 'layout-dashboard')
                                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                                            <line x1="3" x2="21" y1="9" y2="9"></line>
                                            <line x1="9" x2="9" y1="21" y2="9"></line>
                                        @elseif($category->icon === 'settings')
                                            <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l-.43-.25a2 2 0 0 1-2 0l-.15.08a2 2 0 0 0-2.73-.73l-.22-.39a2 2 0 0 0 .73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        @endif
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold">{{ $category->category }}</h2>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                @foreach($category->documents as $document)
                                    <div class="doc-card" data-title="{{ $document->title }}" data-description="{{ $document->description }}">
                                        <a href="{{ url('/documentation/' . $document->slug) }}" class="block rounded-lg border border-teal-200/50 bg-white p-5 shadow-sm hover:shadow-md transition-all duration-300 h-full">
                                            <h3 class="mb-2 text-lg font-semibold">{{ $document->title }}</h3>
                                            <p class="text-sm text-gray-500">{{ $document->description }}</p>
                                            <div class="mt-4 flex items-center text-teal-600 hover:text-teal-700 text-sm font-medium group">
                                                Baca selengkapnya
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4 transition-transform duration-300 group-hover:translate-x-1">
                                                    <path d="m9 18 6-6-6-6"></path>
                                                </svg>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>

                <!-- No Results Message -->
                <div id="no-results" class="hidden text-center">
                    <div class="mx-auto w-24 h-24 rounded-full bg-teal-100/50 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-teal-600">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Dokumentasi tidak ditemukan</h3>
                    <p class="text-gray-500">Coba sesuaikan pencarian Anda untuk menemukan yang Anda cari.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/documentation.js') }}"></script>
@endsection
@extends('new-homepage.layouts.app')

@section('title', 'Dokumentasi - RENTAK')

@section('styles')
<style>
    .doc-content h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    .doc-content h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }
    .doc-content p {
        margin-bottom: 1rem;
        line-height: 1.7;
    }
    .doc-content ul {
        list-style-type: disc;
        margin-left: 1.5rem;
        margin-bottom: 1rem;
    }
    .doc-content ol {
        list-style-type: decimal;
        margin-left: 1.5rem;
        margin-bottom: 1rem;
    }
    .doc-content li {
        margin-bottom: 0.5rem;
    }
    .doc-content pre {
        background-color: #f8f9fa;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-bottom: 1rem;
        overflow-x: auto;
    }
    .doc-content code {
        font-family: monospace;
        font-size: 0.9em;
        padding: 0.2em 0.4em;
        border-radius: 0.25rem;
        background-color: #f1f5f9;
    }
    .doc-content table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
    }
    .doc-content th, .doc-content td {
        border: 1px solid #e2e8f0;
        padding: 0.75rem;
        text-align: left;
    }
    .doc-content th {
        background-color: #f8fafc;
        font-weight: 600;
    }
    .doc-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.375rem;
        margin: 1rem 0;
    }
    .doc-content blockquote {
        border-left: 4px solid #e2e8f0;
        padding-left: 1rem;
        margin-left: 0;
        margin-right: 0;
        font-style: italic;
        color: #64748b;
    }
</style>
@endsection

@section('content')
<div class="py-10 md:py-16">
    <div class="container mx-auto px-4">
        <div class="mx-auto max-w-4xl">
            <!-- Breadcrumb -->
            <nav class="mb-6 flex items-center space-x-2 text-sm text-gray-500">
                <a href="/" class="hover:text-teal-600">Beranda</a>
                <span>/</span>
                <a href="{{ route('documentation.index') }}" class="hover:text-teal-600">Dokumentasi</a>
                <span>/</span>
                <span class="text-gray-900">{{ $document->title }}</span>
            </nav>

            <!-- Documentation Content -->
            <div class="rounded-lg border border-teal-200/50 bg-white overflow-hidden shadow-md">
                <div class="p-6">
                    <div class="mb-2 inline-block rounded-full bg-teal-100/50 px-2 py-1 text-xs font-medium text-teal-700">
                        Dokumentasi
                    </div>
                    <h1 class="text-2xl font-bold mb-4">{{ $document->title }}</h1>

                    <div class="doc-content text-gray-700">
                        {!! $document->content !!}
                    </div>

                    <div class="border-t border-teal-100 pt-6 mt-6">
                        <h2 class="text-xl font-semibold mb-4">Apakah ini membantu?</h2>
                        <div class="flex gap-4">
                            <button class="flex items-center gap-2 rounded-md border border-teal-200 bg-white px-4 py-2 text-sm font-medium text-teal-700 hover:bg-teal-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                    <path d="M7 10v12"></path>
                                    <path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z"></path>
                                </svg>
                                Ya, ini membantu
                            </button>
                            <button class="flex items-center gap-2 rounded-md border border-teal-200 bg-white px-4 py-2 text-sm font-medium text-teal-700 hover:bg-teal-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                    <path d="M17 14V2"></path>
                                    <path d="M9 18.12 10 14H4.17a2 2 0 0 1-1.92-2.56l2.33-8A2 2 0 0 1 6.5 2H20a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.76a2 2 0 0 0-1.79 1.11L12 22h0a3.13 3.13 0 0 1-3-3.88Z"></path>
                                </svg>
                                Tidak, saya butuh info lebih
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Documentation -->
            <div class="mt-8">
                <h2 class="text-xl font-semibold mb-4">Dokumentasi Terkait</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <a href="{{ url('/documentation/pengenalan-rentak') }}" class="block rounded-lg border border-teal-200/50 bg-white p-4 shadow-sm hover:shadow-md transition-all duration-300">
                        <h3 class="font-medium">Pengenalan RENTAK</h3>
                        <p class="mt-2 text-sm text-gray-500">Pengenalan komprehensif tentang RENTAK</p>
                    </a>
                    <a href="{{ url('/documentation/manajemen-pengguna') }}" class="block rounded-lg border border-teal-200/50 bg-white p-4 shadow-sm hover:shadow-md transition-all duration-300">
                        <h3 class="font-medium">Manajemen Pengguna</h3>
                        <p class="mt-2 text-sm text-gray-500">Panduan mengelola akun dan izin pengguna</p>
                    </a>
                </div>
            </div>

            <!-- Navigation -->
            <div class="mt-8 flex justify-between">
                <a href="{{ route('documentation.index') }}" class="flex items-center text-teal-600 hover:text-teal-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                        <path d="m15 18-6-6 6-6"></path>
                    </svg>
                    Kembali ke Dokumentasi
                </a>
                <a href="#" class="flex items-center text-teal-600 hover:text-teal-700">
                    Artikel Berikutnya
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
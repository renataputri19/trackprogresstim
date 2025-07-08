@extends('new-homepage.layouts.app')

@section('title', 'Tutorial - RENTAK')

@section('styles')
<style>
    .video-container {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        height: 0;
        overflow: hidden;
    }
    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
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
                <a href="{{ route('tutorials.index') }}" class="hover:text-teal-600">Tutorial</a>
                <span>/</span>
                <span class="text-gray-900">{{ $tutorial->title }}</span>
            </nav>

            <!-- Tutorial Content -->
            <div class="rounded-lg border border-teal-200/50 bg-white overflow-hidden shadow-md">
                <div class="video-container">
                    <iframe
                        src="{{ $tutorial->video_url }}"
                        title="{{ $tutorial->title }}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                </div>
                <div class="p-6">
                    <div class="mb-2 inline-block rounded-full bg-teal-100/50 px-2 py-1 text-xs font-medium text-teal-700">
                        {{ $tutorial->category }}
                    </div>
                    <h1 class="text-2xl font-bold mb-4">{{ $tutorial->title }}</h1>
                    <p class="text-gray-500 mb-6">
                        {{ $tutorial->description }}
                    </p>

                    <div class="border-t border-teal-100 pt-6 mt-6">
                        <h2 class="text-xl font-semibold mb-4">Bab Tutorial</h2>
                        <div class="space-y-3">
                            @foreach($tutorial->chapters as $index => $chapter)
                                <div class="flex items-center gap-3 p-3 rounded-md {{ $index === 0 ? 'bg-teal-50' : 'hover:bg-teal-50' }}">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $index === 0 ? 'bg-teal-100 text-teal-700' : 'bg-gray-100 text-gray-700' }}">
                                        <span>{{ $index + 1 }}</span>
                                    </div>
                                    <div>
                                        <h3 class="font-medium">{{ $chapter['title'] }}</h3>
                                        <p class="text-sm text-gray-500">{{ $chapter['time'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="border-t border-teal-100 pt-6 mt-6">
                        <h2 class="text-xl font-semibold mb-4">Sumber Daya Terkait</h2>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <a href="{{ route('documentation.index') }}" class="block rounded-lg border border-teal-200/50 bg-white p-4 shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-teal-600">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                    </svg>
                                    <h3 class="font-medium">Dokumentasi</h3>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Baca dokumentasi detail untuk fitur ini</p>
                            </a>
                            <a href="#" class="block rounded-lg border border-teal-200/50 bg-white p-4 shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-teal-600">
                                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                    </svg>
                                    <h3 class="font-medium">Unduh Sumber Daya</h3>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Unduh materi tambahan untuk tutorial ini</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="mt-8 flex justify-between">
                <a href="{{ route('tutorials.index') }}" class="flex items-center text-teal-600 hover:text-teal-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                        <path d="m15 18-6-6 6-6"></path>
                    </svg>
                    Kembali ke Tutorial
                </a>
                <a href="#" class="flex items-center text-teal-600 hover:text-teal-700">
                    Tutorial Berikutnya
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('new-homepage.layouts.app')

@section('title', 'Tutorial Video - RENTAK')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/tutorials.css') }}" />
@endsection

@section('content')
<div class="tutorials py-10 md:py-16">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mx-auto flex max-w-[58rem] flex-col items-center space-y-4 text-center">
            <div class="inline-block rounded-full bg-teal-100 px-4 py-1.5 text-sm font-medium text-teal-700 border border-teal-200">
                Panduan Video
            </div>
            <h1 class="text-3xl font-bold leading-[1.1] sm:text-3xl md:text-5xl">Tutorial Video RENTAK</h1>
            <p class="max-w-[85%] leading-normal text-gray-500 sm:text-lg sm:leading-7">
                Panduan video langkah demi langkah untuk membantu Anda menguasai semua fitur super app RENTAK
            </p>
        </div>

        <!-- Category Filter -->
        <div class="mt-10 flex flex-wrap justify-center gap-2">
            @foreach($categories as $category)
                <button
                    class="category-button rounded-full px-4 py-2 text-sm font-medium border border-teal-200 hover:bg-teal-50 hover:text-teal-700 transition-colors {{ $category === 'Semua' ? 'active' : '' }}"
                    data-category="{{ $category }}"
                >
                    {{ $category }}
                </button>
            @endforeach
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
                    id="search-tutorials"
                    placeholder="Cari tutorial..."
                    class="w-full rounded-md border border-teal-200 bg-white pl-10 pr-4 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500"
                >
            </div>
        </div>

        <!-- Tutorials Grid -->
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" id="tutorials-grid">
            @foreach($tutorials as $tutorial)
                <div class="tutorial-card group" data-category="{{ $tutorial->category }}" data-title="{{ $tutorial->title }}" data-description="{{ $tutorial->description }}">
                    <div class="overflow-hidden rounded-lg border border-teal-200/50 bg-white shadow-md hover:shadow-lg transition-all duration-300 h-full flex flex-col">
                        <div class="relative">
                            <img
                                src="{{ asset($tutorial->thumbnail) }}"
                                alt="{{ $tutorial->title }}"
                                class="h-48 w-full object-cover"
                                onerror="this.src='https://via.placeholder.com/400x225?text=RENTAK+Tutorial'"
                            >
                            <div class="absolute bottom-2 right-2 bg-black/70 text-white text-xs px-2 py-1 rounded">
                                {{ $tutorial->duration }}
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <div class="h-12 w-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-white">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            <div class="mb-2 inline-block rounded-full bg-teal-100/50 px-2 py-1 text-xs font-medium text-teal-700">
                                {{ $tutorial->category }}
                            </div>
                            <h3 class="text-lg font-semibold mb-2">{{ $tutorial->title }}</h3>
                            <p class="text-sm text-gray-500 flex-1">{{ $tutorial->description }}</p>
                            <a href="{{ route('tutorials.show', $tutorial->slug) }}" class="mt-4 inline-flex items-center text-teal-600 hover:text-teal-700 text-sm font-medium group">
                                Tonton tutorial
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4 transition-transform duration-300 group-hover:translate-x-1">
                                    <path d="m9 18 6-6-6-6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Results Message -->
        <div id="no-results" class="hidden mt-12 text-center">
            <div class="mx-auto w-24 h-24 rounded-full bg-teal-100/50 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-teal-600">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold mb-2">Tutorial tidak ditemukan</h3>
            <p class="text-gray-500">Coba sesuaikan pencarian atau filter Anda untuk menemukan yang Anda cari.</p>
        </div>
    </div>
</div>


@endsection


@extends('new-homepage.layouts.app')

@section('title', 'RENTAK - Reformasi dan Integrasi Kinerja')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
@endsection

@section('content')
    <main class="homepage">
        <!-- Hero Section -->
        <section
            class="relative overflow-hidden bg-gradient-to-b from-white via-teal-50/50 to-teal-100/30 pb-8 pt-8 md:pb-10 md:pt-12 lg:pb-16 lg:pt-16">
            <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
            <div
                class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-gradient-to-br from-teal-300/20 to-emerald-300/20 blur-3xl">
            </div>
            <div
                class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-gradient-to-tr from-teal-300/20 to-emerald-300/20 blur-3xl">
            </div>

            <div class="container relative z-10 mx-auto">
                <!-- Side-by-side layout using grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <!-- Left side: Content -->
                    <div class="flex flex-col gap-4 lg:text-left">
                        <h1
                            class="animate-fade-in bg-gradient-to-r from-teal-600 via-emerald-500 to-teal-600 bg-clip-text text-4xl font-extrabold leading-tight tracking-tighter text-transparent sm:text-5xl md:text-6xl lg:text-7xl">
                            RENTAK
                        </h1>
                        <p class="animate-fade-in-delay-1 text-xl font-medium leading-normal text-teal-700 sm:text-2xl">
                            Reformasi dan Integrasi Kinerja
                        </p>
                        <p class="animate-fade-in-delay-2 leading-normal text-gray-500 sm:text-xl sm:leading-8">
                            Super app inovatif yang dirancang untuk mengoptimalkan kinerja dan menyederhanakan operasional BPS Batam
                        </p>
                        <div class="mt-4 flex flex-col gap-4 sm:flex-row lg:justify-start justify-center">
                            @guest
                                <a href="{{ route('login') }}"
                                    class="animate-fade-in-delay-3 inline-flex items-center justify-center rounded-md bg-gradient-to-r from-teal-600 via-emerald-500 to-teal-600 px-5 py-2.5 text-sm font-medium text-white shadow-md hover:shadow-lg hover:from-teal-700 hover:via-emerald-600 hover:to-teal-700 transition-all duration-300">
                                    Akses RENTAK
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="ml-2 h-4 w-4">
                                        <path d="M5 12h14"></path>
                                        <path d="m12 5 7 7-7 7"></path>
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('welcome') }}"
                                    class="animate-fade-in-delay-3 inline-flex items-center justify-center rounded-md bg-gradient-to-r from-teal-600 via-emerald-500 to-teal-600 px-5 py-2.5 text-sm font-medium text-white shadow-md hover:shadow-lg hover:from-teal-700 hover:via-emerald-600 hover:to-teal-700 transition-all duration-300">
                                    Ke Dashboard
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="ml-2 h-4 w-4">
                                        <path d="M5 12h14"></path>
                                        <path d="m12 5 7 7-7 7"></path>
                                    </svg>
                                </a>
                            @endguest
                            <a href="#about"
                                class="animate-fade-in-delay-3 inline-flex items-center justify-center rounded-md border border-teal-500 bg-transparent px-5 py-2.5 text-sm font-medium text-teal-600 hover:bg-teal-50 hover:text-teal-700 transition-all duration-300">
                                Pelajari Lebih Lanjut
                            </a>
                        </div>
                    </div>

                    <!-- Right side: Image Carousel -->
                    <div class="relative z-10">
                        <div
                            class="animate-float relative overflow-hidden rounded-xl border border-teal-200/50 bg-white/90 shadow-2xl backdrop-blur">
                            <div class="absolute inset-0 bg-gradient-to-br from-teal-500/5 to-emerald-500/5"></div>
                            <div class="aspect-[16/9] w-full">
                                <!-- Dashboard Swiper -->
                                <div class="swiper-container" id="dashboard-swiper">
                                    <div class="swiper-wrapper">
                                        <!-- Dashboard Slide 1 -->
                                        <div class="swiper-slide">
                                            <div class="flex h-full items-center justify-center">
                                                <img src="{{ asset('img/new-homepage/bps-batam.jpg') }}"
                                                    alt="Pratinjau Dashboard RENTAK" class="h-full w-full object-contain"
                                                    onerror="this.src='https://via.placeholder.com/1280x720?text=RENTAK+Dashboard+1'">
                                            </div>
                                        </div>
                                        <!-- Dashboard Slide 2 -->
                                        <div class="swiper-slide">
                                            <div class="flex h-full items-center justify-center">
                                                <img src="{{ asset('img/new-homepage/kartini.jpg') }}"
                                                    alt="Pratinjau Analitik RENTAK" class="h-full w-full object-contain"
                                                    onerror="this.src='https://via.placeholder.com/1280x720?text=RENTAK+Dashboard+2'">
                                            </div>
                                        </div>
                                        <!-- Dashboard Slide 3 -->
                                        <div class="swiper-slide">
                                            <div class="flex h-full items-center justify-center">
                                                <img src="{{ asset('img/new-homepage/bps-batam2.jpg') }}"
                                                    alt="Pratinjau Laporan RENTAK" class="h-full w-full object-contain"
                                                    onerror="this.src='https://via.placeholder.com/1280x720?text=RENTAK+Dashboard+3'">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Add pagination -->
                                    <div class="swiper-pagination dashboard-swiper-pagination"></div>
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-teal-500/10 to-transparent opacity-30"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section id="stats" class="border-y border-teal-100 bg-white py-10 md:py-16">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
                    @foreach ($stats as $stat)
                        <div class="flex flex-col items-center justify-center">
                            <div class="text-3xl font-bold text-teal-600 md:text-4xl">{{ $stat['value'] }}</div>
                            <div class="text-center text-sm text-gray-500 md:text-base">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="superapp-section">
            <!-- Background decorations -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                <div
                    class="absolute -top-24 right-1/4 h-64 w-64 rounded-full bg-gradient-to-br from-teal-300/10 to-emerald-300/10 blur-3xl">
                </div>
                <div
                    class="absolute top-1/3 -left-24 h-64 w-64 rounded-full bg-gradient-to-tr from-teal-300/10 to-emerald-300/10 blur-3xl">
                </div>
                <div class="absolute bottom-0 right-0 w-full h-1/2 bg-grid-pattern opacity-[0.03]"></div>
            </div>

            <div class="superapp-container">
                <h2 class="superapp-title">Super App All-in-One</h2>
                <p class="superapp-subtitle">
                    RENTAK menyediakan pusat terintegrasi dengan fitur-fitur canggih untuk meningkatkan efisiensi operasional
                </p>

                <!-- OLD Feature Cards - COMMENTED OUT FOR REDESIGN -->
                {{--
                <div class="superapp-cards-container">
                    <div class="superapp-cards">
                        @foreach ($features as $feature)
                            <div class="superapp-card">
                                <div class="superapp-card-header">
                                    <h3 class="superapp-card-title">{{ $feature['title'] }}</h3>
                                </div>
                                <div class="superapp-card-body">
                                    <p class="superapp-card-description">
                                        {{ $feature['description'] }}
                                    </p>
                                    <ul class="superapp-feature-list">
                                        @foreach ($feature['benefits'] as $benefit)
                                            <li class="superapp-feature-item">
                                                <span class="superapp-feature-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M20 6 9 17l-5-5" />
                                                    </svg>
                                                </span>
                                                <span class="superapp-feature-text">{{ $benefit }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="superapp-card-footer">
                                    <a href="#" class="superapp-link">
                                        Pelajari lebih lanjut
                                        <span class="superapp-link-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14" />
                                                <path d="m12 5 7 7-7 7" />
                                            </svg>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="superapp-navigation">
                    <button class="superapp-nav-button features-swiper-button-prev">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m15 18-6-6 6-6" />
                        </svg>
                    </button>
                    <button class="superapp-nav-button features-swiper-button-next">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </button>
                </div>
                --}}

                <!-- NEW REDESIGNED CAROUSEL-CARD COMPONENT -->
                <div class="superapp-carousel-cards-v2">
                    <div class="superapp-cards-container-v2">
                        <div class="superapp-cards-wrapper-v2" id="superappCardsWrapper">
                            @foreach ($features as $feature)
                                <div class="superapp-card-v2">
                                    <div class="superapp-card-v2-header">
                                        <div class="superapp-card-v2-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        </div>
                                        <h3 class="superapp-card-v2-title">{{ $feature['title'] }}</h3>
                                    </div>
                                    <div class="superapp-card-v2-body">
                                        <p class="superapp-card-v2-description">
                                            {{ $feature['description'] }}
                                        </p>
                                        <div class="superapp-benefits-v2">
                                            @foreach ($feature['benefits'] as $benefit)
                                                <div class="superapp-benefit-v2">
                                                    <span class="superapp-benefit-v2-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M20 6 9 17l-5-5" />
                                                        </svg>
                                                    </span>
                                                    <span class="superapp-benefit-v2-text">{{ $benefit }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="superapp-card-v2-footer">
                                        <a href="#" class="superapp-btn-v2">
                                            Pelajari lebih lanjut
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14" />
                                                <path d="m12 5 7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Carousel Navigation -->
                    <div class="superapp-carousel-navigation-v2">
                        <button class="superapp-nav-btn-v2 superapp-nav-prev-v2" id="superappPrevBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m15 18-6-6 6-6" />
                            </svg>
                        </button>
                        <button class="superapp-nav-btn-v2 superapp-nav-next-v2" id="superappNextBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </button>
                    </div>

                    <!-- Carousel Indicators -->
                    <div class="superapp-carousel-indicators-v2" id="superappIndicators">
                        <!-- Indicators will be generated by JavaScript -->
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="superapp-cta">
                    @guest
                        <a href="{{ route('login') }}" class="superapp-button">
                            Akses RENTAK
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="superapp-button-icon">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('welcome') }}" class="superapp-button">
                            Ke Dashboard
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="superapp-button-icon">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                        </a>
                    @endguest
                </div>
            </div>
        </section>

        <!-- Highlight Systems Section -->
        <section id="highlight-systems" class="bg-gradient-to-b from-white via-teal-50/50 to-teal-100/30 py-10 md:py-16">
            <div class="container mx-auto px-4">
                <div class="mx-auto flex max-w-[58rem] flex-col items-center space-y-4 text-center">
                    <h2 class="text-3xl font-bold leading-[1.1] sm:text-3xl md:text-5xl">Sistem Unggulan</h2>
                    <p class="max-w-[85%] leading-normal text-gray-500 sm:text-lg sm:leading-7">
                        Jelajahi sistem kami yang paling canggih dan inovatif untuk mentransformasi operasional
                    </p>
                </div>

                <div class="mt-12 grid gap-8 md:grid-cols-2">
                    @foreach ($highlightSystems as $system)
                        <div
                            class="highlight-card group overflow-hidden rounded-md shadow-lg transition-all duration-300 hover:shadow-xl flex flex-col">
                            <!-- Card Header with Gradient Background -->
                            <div
                                class="relative p-6 text-white bg-gradient-to-br from-{{ $system['color'] === 'blue' ? 'blue-500' : 'emerald-500' }} to-{{ $system['color'] === 'blue' ? 'blue-700' : 'emerald-700' }}">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-2xl font-bold">{{ $system['title'] }}</h3>
                                        <p class="mt-1 text-sm text-white/90">{{ $system['subtitle'] }}</p>
                                    </div>

                                    <!-- Icon Circle -->
                                    <div
                                        class="highlight-icon flex h-20 w-20 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur-sm border border-white/30">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10">
                                            @if ($system['icon'] === 'ticket')
                                                <path
                                                    d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z">
                                                </path>
                                                <path d="M13 5v2"></path>
                                                <path d="M13 17v2"></path>
                                                <path d="M13 11v2"></path>
                                            @elseif($system['icon'] === 'dollar-sign')
                                                <line x1="12" x2="12" y1="2" y2="22">
                                                </line>
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                            @endif
                                        </svg>
                                    </div>

                                    <!-- Badge -->
                                    <span
                                        class="absolute top-6 right-6 inline-flex items-center rounded-md bg-white px-3 py-1.5 text-xs font-medium text-{{ $system['color'] === 'blue' ? 'blue-600' : 'emerald-600' }} shadow-sm">
                                        {{ $system['id'] === 'ipds' ? 'Eksternal' : 'Keuangan' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Card Body with White Background -->
                            <div class="bg-white p-6 dark:bg-gray-50 flex-1 flex flex-col">
                                <p class="mb-6 text-gray-600">
                                    {{ $system['description'] }}
                                </p>

                                <!-- Feature Tags -->
                                <div class="mb-6 flex flex-wrap gap-2">
                                    @foreach ($system['features'] as $feature)
                                        <span
                                            class="inline-flex items-center rounded-md px-3 py-1.5 text-sm font-medium transition-all duration-200
                                {{ $system['color'] === 'blue'
                                    ? 'bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100'
                                    : 'bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-100' }}">
                                            {{ $feature }}
                                        </span>
                                    @endforeach
                                </div>

                                <!-- Action Button -->
                                <div class="flex mt-auto">
                                    <a href="{{ $system['id'] === 'ipds' ? url('/haloip') : '#' }}"
                                        class="group inline-flex items-center rounded-md px-4 py-2
                                {{ $system['color'] === 'blue' ? 'bg-blue-500 hover:bg-blue-600' : 'bg-emerald-500 hover:bg-emerald-600' }}
                                text-white font-medium shadow-sm hover:shadow-md transition-all duration-200">
                                        Jelajahi {{ $system['title'] }}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="ml-2 h-4 w-4 transition-transform duration-200 group-hover:translate-x-1">
                                            <path d="m9 18 6-6-6-6"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-10 md:py-16">
            <div class="container mx-auto px-4">
                <div class="mx-auto flex max-w-[58rem] flex-col items-center space-y-4 text-center">
                    <h2 class="text-3xl font-bold leading-[1.1] sm:text-3xl md:text-5xl">Cara Kerja RENTAK</h2>
                    <p class="max-w-[85%] leading-normal text-gray-500 sm:text-lg sm:leading-7">
                        Rasakan alur kerja yang efisien untuk meningkatkan produktivitas dan kolaborasi
                    </p>
                </div>

                <div class="mt-12 grid gap-8 md:grid-cols-3">
                    @foreach ($workflowSteps as $index => $step)
                        <div
                            class="workflow-step group relative flex flex-col items-center rounded-lg border border-teal-200/50 bg-white p-6 shadow-md transition-all hover:shadow-lg">
                            <div
                                class="workflow-icon flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-teal-500/10 to-emerald-500/10 text-teal-700 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="h-8 w-8">
                                    @if ($step['icon'] === 'users')
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    @elseif($step['icon'] === 'clock')
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    @elseif($step['icon'] === 'trending-up')
                                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                        <polyline points="16 7 22 7 22 13"></polyline>
                                    @endif
                                </svg>
                            </div>
                            <div class="relative mt-2 flex items-center justify-center">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-teal-100 text-teal-700 text-sm font-bold">
                                    {{ $index + 1 }}
                                </div>
                            </div>
                            <h3 class="mt-4 text-center text-xl font-bold">{{ $step['title'] }}</h3>
                            <div
                                class="my-4 h-1 w-16 rounded-full bg-gradient-to-r from-teal-500 to-emerald-500 transition-all duration-300 group-hover:w-24">
                            </div>
                            <p class="text-center text-gray-500">
                                {{ $step['description'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-10 md:py-16">
            <div class="container mx-auto px-4">
                <div class="mx-auto flex max-w-[58rem] flex-col items-center space-y-4 text-center">
                    <h2 class="text-3xl font-bold leading-[1.1] sm:text-3xl md:text-5xl">Tentang RENTAK</h2>
                    <p class="max-w-[85%] leading-normal text-gray-500 sm:text-lg sm:leading-7">
                        RENTAK (Reformasi dan Integrasi Kinerja) dirancang khusus untuk mendukung kebutuhan operasional BPS Batam
                    </p>
                </div>

                <div class="mx-auto mt-12 max-w-4xl">
                    <div class="relative overflow-hidden rounded-xl border border-teal-200/50 bg-white p-8 shadow-lg">
                        <div
                            class="absolute -right-20 -top-20 h-40 w-40 rounded-full bg-gradient-to-br from-teal-200 to-teal-300 opacity-20 blur-3xl">
                        </div>
                        <div
                            class="absolute -bottom-20 -left-20 h-40 w-40 rounded-full bg-gradient-to-tr from-emerald-200 to-emerald-300 opacity-20 blur-3xl">
                        </div>
                        <div class="relative z-10">
                            <p class="mb-6 text-lg text-gray-500">
                                RENTAK adalah platform terintegrasi yang menggabungkan berbagai sistem dan aplikasi untuk menciptakan lingkungan kerja yang efisien dan terkoordinasi. Dengan menyentralisasi alat dan sumber daya, RENTAK memberdayakan pegawai BPS Batam untuk bekerja secara maksimal sambil memastikan transparansi dan akuntabilitas organisasi.
                            </p>
                            <p class="mb-6 text-lg text-gray-500">
                                Misi kami adalah terus meningkatkan dan memperluas kapabilitas RENTAK untuk memenuhi kebutuhan BPS Batam yang terus berkembang, mendorong inovasi dan keunggulan dalam pelayanan publik.
                            </p>
                            {{-- <div class="mt-8 flex justify-center">
                                <a href="#"
                                    class="inline-flex items-center justify-center rounded-md bg-gradient-to-r from-teal-600 via-emerald-500 to-teal-600 px-5 py-2.5 text-sm font-medium text-white shadow-md hover:shadow-lg hover:from-teal-700 hover:via-emerald-600 hover:to-teal-700 transition-all duration-300">
                                    Pelajari Visi Kami
                                </a>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How to Use RENTAK Section -->
        <section class="how-to-use-section">
            <div class="how-to-use-container">
                <div class="how-to-use-header">
                    <h2 class="how-to-use-title">Cara Menggunakan RENTAK</h2>
                    <p class="how-to-use-subtitle">
                        Akses sumber daya komprehensif ini untuk memulai dan menguasai semua fitur RENTAK
                    </p>
                </div>

                <div class="how-to-use-cards">
                    <!-- Video Tutorials Card -->
                    <div class="how-to-use-card">
                        <div class="how-to-use-card-content">
                            <div class="how-to-use-card-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m22 8-6 4 6 4V8Z"></path>
                                    <rect width="14" height="12" x="2" y="6" rx="2" ry="2"></rect>
                                </svg>
                            </div>
                            <h3 class="how-to-use-card-title">Video Tutorial</h3>
                            <p class="how-to-use-card-description">
                                Tonton panduan video langkah demi langkah yang menjelaskan semua fitur dan fungsi RENTAK. Cocok untuk pembelajar visual.
                            </p>
                            <a href="{{ route('tutorials.index') }}" class="how-to-use-card-link">
                                Tonton tutorial
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="how-to-use-card-link-icon">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Documentation Card -->
                    <div class="how-to-use-card">
                        <div class="how-to-use-card-content">
                            <div class="how-to-use-card-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>
                            </div>
                            <h3 class="how-to-use-card-title">Dokumentasi Lengkap</h3>
                            <p class="how-to-use-card-description">
                                Akses dokumentasi komprehensif yang mencakup semua modul, fitur, dan praktik terbaik RENTAK. Termasuk panduan detail dan bahan referensi.
                            </p>
                            <a href="{{ route('documentation.index') }}" class="how-to-use-card-link">
                                Lihat dokumentasi
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="how-to-use-card-link-icon">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="{{ asset('js/new-homepage/homepage.js') }}"></script>
    <script src="{{ asset('js/new-homepage/homepage-new.js') }}"></script>
    {{-- <script src="{{ asset('js/new-homepage/superapp.js') }}"></script> --}}

    <!-- Custom script for new multi-card carousel component -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initSuperAppCarouselV2();
        });

        function initSuperAppCarouselV2() {
            const wrapper = document.getElementById('superappCardsWrapper');
            const prevBtn = document.getElementById('superappPrevBtn');
            const nextBtn = document.getElementById('superappNextBtn');
            const indicatorsContainer = document.getElementById('superappIndicators');

            if (!wrapper || !prevBtn || !nextBtn) return;

            const cards = wrapper.querySelectorAll('.superapp-card-v2');
            const totalCards = cards.length;

            // Calculate cards per view based on screen size
            function getCardsPerView() {
                if (window.innerWidth >= 768) return 2;
                return 1;
            }

            let cardsPerView = getCardsPerView();
            let currentIndex = 0;
            let maxIndex = Math.max(0, totalCards - cardsPerView);
            let autoPlayInterval;
            let isHovered = false;

            // Create indicators
            function createIndicators() {
                indicatorsContainer.innerHTML = '';
                const totalSlides = maxIndex + 1;

                for (let i = 0; i <= maxIndex; i++) {
                    const indicator = document.createElement('button');
                    indicator.className = `superapp-indicator-v2 ${i === 0 ? 'active' : ''}`;
                    indicator.addEventListener('click', () => goToSlide(i));
                    indicatorsContainer.appendChild(indicator);
                }
            }

            // Update carousel position
            function updateCarousel() {
                const cardWidth = cards[0].offsetWidth;
                const gap = 24; // 1.5rem gap
                const offset = -(currentIndex * (cardWidth + gap));
                wrapper.style.transform = `translateX(${offset}px)`;

                // Update indicators
                const indicators = indicatorsContainer.querySelectorAll('.superapp-indicator-v2');
                indicators.forEach((indicator, index) => {
                    indicator.classList.toggle('active', index === currentIndex);
                });

                // Update button states
                prevBtn.disabled = currentIndex === 0;
                nextBtn.disabled = currentIndex === maxIndex;
            }

            // Go to specific slide
            function goToSlide(index) {
                currentIndex = Math.max(0, Math.min(index, maxIndex));
                updateCarousel();
                resetAutoPlay();
            }

            // Navigation functions
            function goNext() {
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateCarousel();
                }
            }

            function goPrev() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            }

            // Auto-play functionality
            function startAutoPlay() {
                autoPlayInterval = setInterval(() => {
                    if (!isHovered) {
                        if (currentIndex >= maxIndex) {
                            currentIndex = 0;
                        } else {
                            currentIndex++;
                        }
                        updateCarousel();
                    }
                }, 4000);
            }

            function stopAutoPlay() {
                clearInterval(autoPlayInterval);
            }

            function resetAutoPlay() {
                stopAutoPlay();
                if (!isHovered) {
                    startAutoPlay();
                }
            }

            // Event listeners
            nextBtn.addEventListener('click', () => {
                goNext();
                resetAutoPlay();
            });

            prevBtn.addEventListener('click', () => {
                goPrev();
                resetAutoPlay();
            });

            // Hover pause functionality
            wrapper.parentElement.addEventListener('mouseenter', () => {
                isHovered = true;
                stopAutoPlay();
            });

            wrapper.parentElement.addEventListener('mouseleave', () => {
                isHovered = false;
                startAutoPlay();
            });

            // Handle window resize
            window.addEventListener('resize', () => {
                const newCardsPerView = getCardsPerView();
                if (newCardsPerView !== cardsPerView) {
                    cardsPerView = newCardsPerView;
                    maxIndex = Math.max(0, totalCards - cardsPerView);
                    currentIndex = Math.min(currentIndex, maxIndex);
                    createIndicators();
                    updateCarousel();
                    resetAutoPlay();
                }
            });

            // Initialize
            createIndicators();
            updateCarousel();
            startAutoPlay();
        }
    </script>
@endsection
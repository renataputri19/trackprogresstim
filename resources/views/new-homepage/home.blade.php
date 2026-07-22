@extends('new-homepage.layouts.app')

@section('title', 'RENTAK — Reformasi dan Integrasi Kinerja')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/new-homepage/home.css') }}">
@endsection

@php
    $iconPaths = [
        'bar-chart-3' => '<path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/>',
        'book-open'   => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
        'ticket'      => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/>',
        'dollar-sign' => '<line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
        'file-text'   => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/>',
        'check-circle'=> '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
        'users'       => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'clock'       => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
        'trending-up' => '<polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>',
    ];
    $featureAccent = [
        'performance' => '#0ea5e9', 'knowledge' => '#059669', 'ticketing' => '#f43f5e',
        'finance' => '#8b5cf6', 'padamu-negri' => '#6366f1', 'bahtera' => '#0d9488',
    ];
    $hlAccent = ['ipds' => '#2563eb', 'finance' => '#059669'];
@endphp

@section('content')
<main class="hm">

    {{-- ============================== HERO ============================== --}}
    <section class="hm-hero">
        <div class="hm-container">
            <div class="hm-hero-grid">
                <!-- Left: value proposition -->
                <div>
                    <span class="rk-eyebrow">RENTAK · Reformasi dan Integrasi Kinerja</span>
                    <h1 class="hm-hero-title">
                        Satu pintu untuk semua<br>
                        <span class="hm-accent">layanan &amp; aplikasi</span> BPS Batam
                    </h1>
                    <p class="hm-hero-sub">
                        Super app internal yang menyatukan seluruh sistem, layanan, dan data BPS Kota Batam
                        dalam satu tempat — dirancang untuk kinerja yang lebih cepat, rapi, dan terukur.
                    </p>
                    <div class="hm-hero-cta">
                        @guest
                            <a href="{{ route('login') }}" class="rk-btn rk-btn-primary">
                                Akses RENTAK
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        @else
                            <a href="{{ route('welcome') }}" class="rk-btn rk-btn-primary">
                                Ke Dashboard
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        @endguest
                        <a href="#features" class="rk-btn rk-btn-outline">Pelajari Lebih Lanjut</a>
                    </div>

                    <div class="hm-hero-stats">
                        @foreach (array_slice($stats, 0, 3) as $stat)
                        <div>
                            <div class="hm-stat-num">{{ $stat['value'] }}</div>
                            <div class="hm-stat-label">{{ $stat['label'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right: product preview mockup -->
                <div class="hm-mockup-wrap hm-reveal">
                    <div class="hm-mockup-glow"></div>
                    <div class="hm-mockup">
                        <div class="hm-mockup-bar">
                            <span class="hm-dot r"></span><span class="hm-dot y"></span><span class="hm-dot g"></span>
                            <span class="hm-mockup-url">rentak.angkabatam.id</span>
                        </div>
                        <div class="hm-mockup-body">
                            <p class="hm-mockup-greet">Selamat datang kembali, <b>Tim BPS Batam</b></p>
                            <div class="hm-mockup-tiles">
                                <div class="hm-mtile" style="--a:#f59e0b">
                                    <span class="hm-mtile-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg></span>
                                    <span class="hm-mtile-tx"><span class="hm-mtile-tt">OMEGA</span><span class="hm-mtile-st">Pegawai Terbaik</span></span>
                                </div>
                                <div class="hm-mtile" style="--a:#f43f5e">
                                    <span class="hm-mtile-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/></svg></span>
                                    <span class="hm-mtile-tx"><span class="hm-mtile-tt">Halo IP</span><span class="hm-mtile-st">Tiket IT</span></span>
                                </div>
                                <div class="hm-mtile" style="--a:#059669">
                                    <span class="hm-mtile-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></span>
                                    <span class="hm-mtile-tx"><span class="hm-mtile-tt">Knowledge</span><span class="hm-mtile-st">Dokumentasi</span></span>
                                </div>
                                <div class="hm-mtile" style="--a:#0ea5e9">
                                    <span class="hm-mtile-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg></span>
                                    <span class="hm-mtile-tx"><span class="hm-mtile-tt">Kinerja</span><span class="hm-mtile-st">Performance</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================ STATS BAND ============================ --}}
    <section class="hm-band">
        <div class="hm-container">
            <div class="hm-band-grid">
                @foreach ($stats as $stat)
                <div class="hm-band-item hm-reveal">
                    <div class="hm-band-num">{{ $stat['value'] }}</div>
                    <div class="hm-band-label">{{ $stat['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================= FEATURES ============================= --}}
    <section id="features" class="hm-section">
        <div class="hm-container">
            <div class="hm-head hm-reveal">
                <span class="rk-eyebrow">Ekosistem Super App</span>
                <h2 class="hm-title">Semua yang Anda butuhkan, terintegrasi</h2>
                <p class="hm-sub">RENTAK menyatukan sistem inti BPS Kota Batam ke dalam satu pengalaman yang konsisten dan mudah diakses.</p>
            </div>

            <div class="hm-grid">
                @foreach ($features as $feature)
                <div class="hm-feature hm-reveal" style="--a:{{ $featureAccent[$feature['id']] ?? '#0d9488' }}">
                    <span class="hm-feature-ic">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $iconPaths[$feature['icon']] ?? '' !!}</svg>
                    </span>
                    <h3 class="hm-feature-tt">{{ $feature['title'] }}</h3>
                    <p class="hm-feature-ds">{{ $feature['description'] }}</p>
                    <div class="hm-feature-list">
                        @foreach (array_slice($feature['benefits'], 0, 3) as $benefit)
                        <div class="hm-feature-li">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            <span>{{ $benefit }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ========================= HIGHLIGHT SYSTEMS ========================= --}}
    <section class="hm-section" style="background: var(--bg-elevated); border-top:1px solid var(--border); border-bottom:1px solid var(--border);">
        <div class="hm-container">
            <div class="hm-head hm-reveal">
                <span class="rk-eyebrow">Paling Canggih</span>
                <h2 class="hm-title">Sistem Unggulan</h2>
                <p class="hm-sub">Sistem paling inovatif kami untuk mentransformasi cara kerja BPS Kota Batam.</p>
            </div>

            <div class="hm-highlights">
                @foreach ($highlightSystems as $system)
                <div class="hm-hl hm-reveal" style="--a:{{ $hlAccent[$system['id']] ?? '#0d9488' }}">
                    <div class="hm-hl-head">
                        <span class="hm-hl-badge">{{ $system['id'] === 'ipds' ? 'Eksternal' : 'Keuangan' }}</span>
                        <span class="hm-hl-ic">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $iconPaths[$system['icon']] ?? '' !!}</svg>
                        </span>
                        <h3 class="hm-hl-tt">{{ $system['title'] }}</h3>
                        <p class="hm-hl-st">{{ $system['subtitle'] }}</p>
                    </div>
                    <div class="hm-hl-body">
                        <p class="hm-hl-ds">{{ $system['description'] }}</p>
                        <div class="hm-hl-tags">
                            @foreach ($system['features'] as $f)
                            <span class="hm-hl-tag">{{ $f }}</span>
                            @endforeach
                        </div>
                        <a href="{{ $system['id'] === 'ipds' ? url('/haloip') : '#' }}" class="hm-hl-btn">
                            Jelajahi
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =========================== HOW IT WORKS =========================== --}}
    <section class="hm-section">
        <div class="hm-container">
            <div class="hm-head hm-reveal">
                <span class="rk-eyebrow">Alur Kerja</span>
                <h2 class="hm-title">Cara Kerja RENTAK</h2>
                <p class="hm-sub">Alur yang efisien untuk meningkatkan produktivitas dan kolaborasi lintas tim.</p>
            </div>

            <div class="hm-steps">
                @foreach ($workflowSteps as $index => $step)
                <div class="hm-step hm-reveal">
                    <span class="hm-step-ic">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $iconPaths[$step['icon']] ?? '' !!}</svg>
                    </span>
                    <div><span class="hm-step-n">{{ $index + 1 }}</span></div>
                    <h3 class="hm-step-tt">{{ $step['title'] }}</h3>
                    <div class="hm-step-bar"></div>
                    <p class="hm-step-ds">{{ $step['description'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================== ABOUT ============================== --}}
    <section id="about" class="hm-section" style="background: var(--bg-elevated); border-top:1px solid var(--border);">
        <div class="hm-container">
            <div class="hm-head hm-reveal">
                <span class="rk-eyebrow">Tentang</span>
                <h2 class="hm-title">Tentang RENTAK</h2>
            </div>
            <div class="hm-about-card hm-reveal">
                <div class="hm-about-orb" style="top:-40px;right:-40px;width:180px;height:180px;background:radial-gradient(circle,rgba(13,148,136,.5),transparent 70%)"></div>
                <div class="hm-about-orb" style="bottom:-40px;left:-40px;width:180px;height:180px;background:radial-gradient(circle,rgba(16,185,129,.4),transparent 70%)"></div>
                <p>
                    RENTAK (Reformasi dan Integrasi Kinerja) adalah platform terintegrasi yang menggabungkan berbagai
                    sistem dan aplikasi untuk menciptakan lingkungan kerja yang efisien dan terkoordinasi. Dengan
                    menyentralisasi alat dan sumber daya, RENTAK memberdayakan pegawai BPS Batam untuk bekerja secara
                    maksimal sambil memastikan transparansi dan akuntabilitas organisasi.
                </p>
                <p>
                    Misi kami adalah terus meningkatkan dan memperluas kapabilitas RENTAK untuk memenuhi kebutuhan
                    BPS Batam yang terus berkembang, mendorong inovasi dan keunggulan dalam pelayanan publik.
                </p>
            </div>
        </div>
    </section>

    {{-- =========================== HOW TO USE =========================== --}}
    <section class="hm-section">
        <div class="hm-container">
            <div class="hm-head hm-reveal">
                <span class="rk-eyebrow">Panduan</span>
                <h2 class="hm-title">Cara Menggunakan RENTAK</h2>
                <p class="hm-sub">Mulai dan kuasai semua fitur RENTAK melalui sumber daya berikut.</p>
            </div>

            <div class="hm-use">
                <div class="hm-use-card hm-reveal">
                    <span class="hm-use-ic">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 8-6 4 6 4V8Z"/><rect width="14" height="12" x="2" y="6" rx="2" ry="2"/></svg>
                    </span>
                    <h3 class="hm-use-tt">Video Tutorial</h3>
                    <p class="hm-use-ds">Panduan video langkah demi langkah untuk semua fitur dan fungsi RENTAK. Cocok untuk pembelajar visual.</p>
                    <a href="{{ route('tutorials.index') }}" class="hm-use-link">
                        Tonton tutorial
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="hm-use-card hm-reveal">
                    <span class="hm-use-ic">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    </span>
                    <h3 class="hm-use-tt">Dokumentasi Lengkap</h3>
                    <p class="hm-use-ds">Dokumentasi komprehensif yang mencakup semua modul, fitur, dan praktik terbaik RENTAK.</p>
                    <a href="{{ route('documentation.index') }}" class="hm-use-link">
                        Lihat dokumentasi
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================== CTA ============================== --}}
    <section class="hm-section" style="padding-top:0;">
        <div class="hm-container">
            <div class="hm-cta hm-reveal">
                <h2 class="hm-cta-tt">Siap meningkatkan kinerja?</h2>
                <p class="hm-cta-sub">Masuk ke RENTAK dan akses seluruh aplikasi serta layanan BPS Kota Batam dari satu dashboard.</p>
                @guest
                    <a href="{{ route('login') }}" class="hm-cta-btn">
                        Masuk ke RENTAK
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                @else
                    <a href="{{ route('welcome') }}" class="hm-cta-btn">
                        Buka Dashboard
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                @endguest
            </div>
        </div>
    </section>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var els = document.querySelectorAll('.hm-reveal');
        if (!('IntersectionObserver' in window)) {
            els.forEach(function (el) { el.classList.add('is-visible'); });
            return;
        }
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry, i) {
                if (entry.isIntersecting) {
                    entry.target.style.transitionDelay = (Math.min(i, 4) * 60) + 'ms';
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        els.forEach(function (el) { io.observe(el); });
    });
</script>
@endsection

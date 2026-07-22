@extends('new-homepage.layouts.app')

@section('title', 'Dokumentasi — RENTAK')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/new-homepage/docs.css') }}">
@endsection

@section('content')
<div class="dc">
    <section class="dc-hero">
        <div class="dc-container">
            <span class="rk-eyebrow">Pusat Bantuan</span>
            <h1 class="dc-hero-title">Dokumentasi <span class="hm-accent">RENTAK</span></h1>
            <p class="dc-hero-sub">Panduan lengkap penggunaan seluruh aplikasi dan layanan dalam super app BPS Kota Batam. Pilih topik di samping untuk melompat ke bagian yang Anda butuhkan.</p>
        </div>
    </section>

    <div class="dc-container">
        <div class="dc-layout">
            <!-- Sidebar -->
            <aside class="dc-side">
                <div class="dc-side-inner">
                    <p class="dc-side-title">Daftar Isi</p>
                    <nav class="dc-nav" id="dc-nav">
                        @foreach ($sections as $i => $section)
                        <a href="#{{ $section['id'] }}" class="dc-nav-link {{ $i === 0 ? 'is-active' : '' }}" data-target="{{ $section['id'] }}">
                            @include('new-homepage.partials.icon', ['name' => $section['icon'], 'class' => 'h-[18px] w-[18px]'])
                            <span>{{ $section['title'] }}</span>
                        </a>
                        @endforeach
                    </nav>
                </div>
            </aside>

            <!-- Content -->
            <div class="dc-content">
                @foreach ($sections as $section)
                <section id="{{ $section['id'] }}" class="dc-section dc-reveal">
                    <div class="dc-section-head">
                        <span class="dc-section-ic">
                            @include('new-homepage.partials.icon', ['name' => $section['icon'], 'class' => 'h-5 w-5'])
                        </span>
                        <h2 class="dc-section-tt">{{ $section['title'] }}</h2>
                    </div>
                    <p class="dc-section-intro">{{ $section['intro'] }}</p>

                    <div class="dc-steps">
                        @foreach ($section['steps'] as $step)
                        <div class="dc-step"><span>{{ $step }}</span></div>
                        @endforeach
                    </div>

                    @if (!empty($section['note']))
                    <div class="dc-note">
                        @include('new-homepage.partials.icon', ['name' => 'help', 'class' => 'h-4 w-4'])
                        <span>{{ $section['note'] }}</span>
                    </div>
                    @endif
                </section>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Reveal on scroll
        var reveals = document.querySelectorAll('.dc-reveal');
        if ('IntersectionObserver' in window) {
            var ro = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('is-visible'); ro.unobserve(e.target); } });
            }, { threshold: 0.1 });
            reveals.forEach(function (el) { ro.observe(el); });
        } else {
            reveals.forEach(function (el) { el.classList.add('is-visible'); });
        }

        // Scroll-spy for the sidebar
        var links = Array.prototype.slice.call(document.querySelectorAll('#dc-nav .dc-nav-link'));
        if ('IntersectionObserver' in window) {
            var spy = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (e.isIntersecting) {
                        links.forEach(function (l) { l.classList.toggle('is-active', l.dataset.target === e.target.id); });
                    }
                });
            }, { rootMargin: '-45% 0px -50% 0px', threshold: 0 });
            links.forEach(function (l) { var s = document.getElementById(l.dataset.target); if (s) spy.observe(s); });
        }
    });
</script>
@endsection

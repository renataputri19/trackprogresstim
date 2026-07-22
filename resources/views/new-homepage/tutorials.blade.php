@extends('new-homepage.layouts.app')

@section('title', 'Tutorial — RENTAK')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/new-homepage/docs.css') }}?v={{ $rkv }}">
@endsection

@section('content')
<div class="dc">
    <section class="dc-hero">
        <div class="dc-container">
            <span class="rk-eyebrow">Panduan Langkah demi Langkah</span>
            <h1 class="dc-hero-title">Tutorial <span class="hm-accent">RENTAK</span></h1>
            <p class="dc-hero-sub">Panduan praktis untuk menguasai setiap aplikasi RENTAK — ringkas, langsung ke intinya, dan mudah diikuti. Saring berdasarkan kategori di bawah.</p>
        </div>
    </section>

    <div class="dc-container" style="padding-top:2.5rem; padding-bottom:4rem;">
        <!-- Filter -->
        <div class="dc-filter" id="tut-filter">
            @foreach ($categories as $i => $cat)
            <button type="button" class="dc-pill {{ $i === 0 ? 'is-active' : '' }}" data-filter="{{ $cat }}">{{ $cat }}</button>
            @endforeach
        </div>

        <!-- Guides -->
        <div class="tut-grid" id="tut-grid">
            @foreach ($guides as $guide)
            <article class="tut-card dc-reveal" data-category="{{ $guide['category'] }}">
                <div class="tut-card-top">
                    <span class="tut-ic">
                        @include('new-homepage.partials.icon', ['name' => $guide['icon'], 'class' => 'h-5 w-5'])
                    </span>
                    <span class="tut-tag">{{ $guide['category'] }}</span>
                </div>
                <h2 class="tut-tt">{{ $guide['title'] }}</h2>
                <p class="tut-ds">{{ $guide['desc'] }}</p>
                <div class="tut-meta">
                    @include('new-homepage.partials.icon', ['name' => 'help', 'class' => 'h-3.5 w-3.5'])
                    <span>Estimasi {{ $guide['duration'] }}</span>
                </div>
                <div class="tut-steps">
                    @foreach ($guide['steps'] as $step)
                    <div class="tut-step"><span>{{ $step }}</span></div>
                    @endforeach
                </div>
            </article>
            @endforeach

            <div class="dc-empty" id="tut-empty" style="display:none;">Tidak ada tutorial pada kategori ini.</div>
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
            }, { threshold: 0.08 });
            reveals.forEach(function (el) { ro.observe(el); });
        } else {
            reveals.forEach(function (el) { el.classList.add('is-visible'); });
        }

        // Category filter
        var pills = document.querySelectorAll('#tut-filter .dc-pill');
        var cards = document.querySelectorAll('#tut-grid .tut-card');
        var empty = document.getElementById('tut-empty');
        pills.forEach(function (pill) {
            pill.addEventListener('click', function () {
                pills.forEach(function (p) { p.classList.remove('is-active'); });
                pill.classList.add('is-active');
                var f = pill.dataset.filter;
                var shown = 0;
                cards.forEach(function (card) {
                    var match = (f === 'Semua' || card.dataset.category === f);
                    card.style.display = match ? '' : 'none';
                    if (match) shown++;
                });
                if (empty) empty.style.display = shown === 0 ? '' : 'none';
            });
        });
    });
</script>
@endsection

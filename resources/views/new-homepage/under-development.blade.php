@extends('new-homepage.layouts.app')

@section('title', ($app['name'] ?? 'Segera Hadir') . ' — RENTAK')

@section('content')
@php
    $phases = [
        ['title' => 'Analisis Kebutuhan', 'desc' => 'Identifikasi fitur dan spesifikasi sistem.', 'state' => 'done'],
        ['title' => 'Desain Sistem', 'desc' => 'Perancangan arsitektur dan antarmuka pengguna.', 'state' => 'active'],
        ['title' => 'Pengembangan', 'desc' => 'Implementasi fitur-fitur utama aplikasi.', 'state' => 'upcoming'],
        ['title' => 'Pengujian & Penjaminan Mutu', 'desc' => 'Pengujian sistem dan penjaminan kualitas.', 'state' => 'upcoming'],
        ['title' => 'Peluncuran', 'desc' => 'Target ketersediaan: ' . ($app['target'] ?? 'Akhir 2026') . '.', 'state' => 'upcoming'],
    ];
@endphp

<div style="--a: {{ $app['accent'] ?? '#0d9488' }}">
    {{-- Hero --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-brand-700 via-brand-600 to-emerald-500 py-16">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 -left-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>

        <div class="relative mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-2xl border border-white/25 bg-white/15 text-white backdrop-blur-sm">
                @include('new-homepage.partials.icon', ['name' => $app['icon'] ?? 'rocket', 'class' => 'h-10 w-10'])
            </div>

            <span class="inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/15 px-3.5 py-1.5 text-xs font-semibold uppercase tracking-wide text-white backdrop-blur-sm">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white/80"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-white"></span>
                </span>
                Segera Hadir
            </span>

            <h1 class="rk-display mt-5 text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">{{ $app['name'] ?? 'Aplikasi Baru' }}</h1>
            <p class="mt-2 text-lg font-semibold text-teal-50">{{ $app['tagline'] ?? '' }}</p>
            <p class="mx-auto mt-4 max-w-2xl text-base leading-relaxed text-teal-100/90">{{ $app['desc'] ?? '' }}</p>

            <div class="mt-7 inline-flex items-center gap-2.5 rounded-xl border border-white/25 bg-white/10 px-4 py-2.5 text-white backdrop-blur-sm">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <span class="text-sm">Target Peluncuran: <strong class="font-bold">{{ $app['target'] ?? 'Akhir 2026' }}</strong></span>
            </div>
        </div>
    </div>

    {{-- Body --}}
    <div class="mx-auto max-w-3xl px-4 py-14 sm:px-6 lg:px-8">

        {{-- Development status --}}
        <section class="rk-card overflow-hidden">
            <div class="rk-card-head">
                <h2 class="rk-display text-lg font-bold text-[color:var(--text-strong)]">Status Pengembangan</h2>
                <p class="mt-0.5 text-sm text-[color:var(--text-muted)]">Aplikasi ini masih dalam tahap awal. Berikut peta jalannya.</p>
            </div>
            <div class="p-6">
                <ol class="space-y-5">
                    @foreach ($phases as $i => $phase)
                    <li class="flex items-start gap-4">
                        <div class="flex flex-col items-center">
                            @if ($phase['state'] === 'done')
                                <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-400">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                </span>
                            @elseif ($phase['state'] === 'active')
                                <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full text-white shadow-sm" style="background: var(--a); box-shadow: 0 0 0 4px color-mix(in srgb, var(--a) 20%, transparent)">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                                </span>
                            @else
                                <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-[color:var(--surface-muted)] text-[color:var(--text-faint)]">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/></svg>
                                </span>
                            @endif
                            @if (!$loop->last)
                                <span class="mt-1 h-6 w-0.5 rounded-full {{ $phase['state'] === 'done' ? 'bg-emerald-500/40' : 'bg-[color:var(--border)]' }}"></span>
                            @endif
                        </div>
                        <div class="pt-1.5">
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-semibold {{ $phase['state'] === 'upcoming' ? 'text-[color:var(--text-muted)]' : 'text-[color:var(--text-strong)]' }}">{{ $phase['title'] }}</h3>
                                @if ($phase['state'] === 'active')
                                    <span class="rounded-full bg-[color:var(--surface-muted)] px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide" style="color: var(--a)">Berlangsung</span>
                                @endif
                            </div>
                            <p class="mt-0.5 text-sm text-[color:var(--text-muted)]">{{ $phase['desc'] }}</p>
                        </div>
                    </li>
                    @endforeach
                </ol>
            </div>
        </section>

        {{-- Planned features --}}
        @if (!empty($app['features']))
        <section class="rk-card mt-6 overflow-hidden">
            <div class="rk-card-head">
                <h2 class="rk-display text-lg font-bold text-[color:var(--text-strong)]">Fitur yang Direncanakan</h2>
                <p class="mt-0.5 text-sm text-[color:var(--text-muted)]">Kemampuan yang akan hadir pada {{ $app['name'] }}.</p>
            </div>
            <div class="grid gap-3 p-6 sm:grid-cols-2">
                @foreach ($app['features'] as $feature)
                <div class="flex items-start gap-3 rounded-xl border border-[color:var(--border)] bg-[color:var(--bg-elevated)] p-3.5">
                    <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-lg" style="background: color-mix(in srgb, var(--a) 14%, transparent); color: var(--a)">
                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                    <span class="text-sm text-[color:var(--text)]">{{ $feature }}</span>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Target callout --}}
        <div class="mt-6 flex items-center gap-4 rounded-2xl border p-5" style="border-color: color-mix(in srgb, var(--a) 30%, var(--border)); background: color-mix(in srgb, var(--a) 8%, transparent)">
            <span class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl text-white" style="background: var(--a)">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/><circle cx="12" cy="12" r="4"/></svg>
            </span>
            <div>
                <h3 class="rk-display font-bold text-[color:var(--text-strong)]">Estimasi Peluncuran: {{ $app['target'] ?? 'Akhir 2026' }}</h3>
                <p class="mt-0.5 text-sm text-[color:var(--text-muted)]">Kami sedang menyiapkan aplikasi ini dengan cermat. Nantikan kehadirannya di penghujung 2026.</p>
            </div>
        </div>

        {{-- Contact + back --}}
        <div class="mt-8 rounded-2xl border border-[color:var(--border)] bg-[color:var(--surface)] p-6 text-center">
            <h3 class="rk-display text-base font-bold text-[color:var(--text-strong)]">Punya pertanyaan atau masukan?</h3>
            <p class="mx-auto mt-1 max-w-md text-sm text-[color:var(--text-muted)]">Sampaikan pertanyaan atau saran mengenai aplikasi ini kepada tim pengembang RENTAK.</p>
            <div class="mt-5 flex flex-col justify-center gap-3 sm:flex-row">
                <a href="mailto:bps2171@bps.go.id" class="rk-btn rk-btn-primary">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    Hubungi Tim IT
                </a>
                <a href="{{ route('welcome') }}" class="rk-btn rk-btn-ghost">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

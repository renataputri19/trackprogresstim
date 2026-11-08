@extends('new-homepage.layouts.app')

@section('title', 'Dashboard — RENTAK')

@section('content')
@php
    $hour  = (int) now()->format('H');
    $greet = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 19 ? 'Selamat sore' : 'Selamat malam'));
    try { $today = now()->locale('id')->translatedFormat('l, d F Y'); } catch (\Throwable $e) { $today = now()->format('l, d F Y'); }
    $firstName = trim(preg_replace('/,.*$/', '', $user->name ?? 'Pengguna'));
    $firstName = preg_split('/\s+/', $firstName)[0] ?? $firstName;
@endphp

<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

    {{-- ============================ Welcome hero ============================ --}}
    <section class="rk-hero rk-animate p-8 sm:p-10">
        <div class="rk-hero-grid"></div>
        <div class="rk-hero-orb h-40 w-40" style="top:-40px;right:8%;background:radial-gradient(circle,rgba(20,184,166,.55),transparent 70%)"></div>
        <div class="rk-hero-orb h-28 w-28" style="bottom:-30px;left:14%;background:radial-gradient(circle,rgba(16,185,129,.45),transparent 70%);animation-delay:-3s"></div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="min-w-0">
                <p class="text-xs font-medium tracking-wide text-[color:var(--text-muted)]">{{ $today }}</p>
                <h1 class="rk-display mt-2 text-3xl font-extrabold text-[color:var(--text-strong)] sm:text-4xl">
                    {{ $greet }}, <span class="rk-wordmark">{{ $firstName }}</span> 👋
                </h1>
                <p id="dynamic-quote" class="mt-3 max-w-2xl text-sm leading-relaxed text-[color:var(--text-muted)] transition-opacity duration-500 sm:text-base">
                    Akses semua aplikasi dan layanan BPS melalui dashboard terintegrasi RENTAK.
                </p>
                <div class="mt-5 flex flex-wrap items-center gap-2.5">
                    @if($userTim)
                    <span class="inline-flex items-center gap-2 rounded-full border border-[color:var(--border)] bg-[color:var(--surface)] px-3.5 py-1.5 text-xs font-semibold text-[color:var(--text)] shadow-sm">
                        <svg class="h-3.5 w-3.5 text-brand-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m22 21-3-3m0 0a5.5 5.5 0 1 0-7.8-7.8 5.5 5.5 0 0 0 7.8 7.8Z"/></svg>
                        {{ $userTim->name }}
                    </span>
                    @endif
                    <span class="rk-badge px-3 py-1.5">
                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                        Semua sistem aktif
                    </span>
                </div>
            </div>

            <div class="hidden shrink-0 items-center gap-3 lg:flex">
                <a href="#apps" class="rk-btn rk-btn-primary">
                    Jelajahi Aplikasi
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Flash: user created / password changed --}}
    @foreach(['user_created', 'user_password_reset'] as $flashKey)
    @if(session($flashKey))
    <div class="mt-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-300">
        <svg class="mt-0.5 h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
        <p class="text-sm font-medium">{{ session($flashKey) }}</p>
    </div>
    @endif
    @endforeach

    {{-- IT user management now lives as a tile in the app grid below (opens the Add-User modal). --}}

    {{-- ============================ Our apps =============================== --}}
    <section id="apps" class="rk-animate mt-10" style="animation-delay:.1s">
        <div class="flex items-end justify-between">
            <div>
                <span class="rk-eyebrow">Aplikasi Kami</span>
                <h2 class="rk-display mt-2 text-2xl font-bold text-[color:var(--text-strong)]">Ekosistem Super App</h2>
                <p class="mt-1 text-sm text-[color:var(--text-muted)]">Semua layanan digital BPS Kota Batam dalam satu tempat.</p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {{-- OMEGA (featured) --}}
            <a href="{{ route('omega.index') }}" class="rk-tile rk-tile-featured" style="--tile-accent:#f59e0b">
                <span class="rk-chip-new">Baru</span>
                <span class="rk-tile-icon">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                </span>
                <span class="rk-tile-body">
                    <span class="rk-tile-title">OMEGA</span>
                    <span class="rk-tile-sub">Pemilihan Pegawai Terbaik</span>
                </span>
                <svg class="rk-tile-arrow h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>

            {{-- Statistik Sektoral (Monalisa) --}}
            <a href="https://datakita.angkabatam.id/monalisa" target="_blank" rel="noopener" class="rk-tile" style="--tile-accent:#f97316">
                <span class="rk-tile-icon">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><rect x="7" y="10" width="3" height="8" rx="1"/><rect x="12" y="6" width="3" height="12" rx="1"/><rect x="17" y="13" width="3" height="5" rx="1"/></svg>
                </span>
                <span class="rk-tile-body">
                    <span class="rk-tile-title">Statistik Sektoral</span>
                    <span class="rk-tile-sub">Portal Monalisa</span>
                </span>
                <svg class="rk-tile-arrow h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M7 7h10v10"/></svg>
            </a>

            {{-- RB Padamu Negri --}}
            <a href="{{ url('/padamunegri') }}" class="rk-tile" style="--tile-accent:#6366f1">
                <span class="rk-tile-icon">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                </span>
                <span class="rk-tile-body">
                    <span class="rk-tile-title">RB Padamu Negri</span>
                    <span class="rk-tile-sub">Reformasi Birokrasi</span>
                </span>
                <svg class="rk-tile-arrow h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>

            {{-- Knowledge Management --}}
            <a href="{{ route('kms.index') }}" class="rk-tile" style="--tile-accent:#059669">
                <span class="rk-tile-icon">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                </span>
                <span class="rk-tile-body">
                    <span class="rk-tile-title">Knowledge Management</span>
                    <span class="rk-tile-sub">Dokumentasi &amp; Materi</span>
                </span>
                <svg class="rk-tile-arrow h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>

            {{-- Halo IP --}}
            <a href="{{ url('/haloip') }}" class="rk-tile" style="--tile-accent:#f43f5e">
                <span class="rk-tile-icon">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>
                </span>
                <span class="rk-tile-body">
                    <span class="rk-tile-title">Halo IP</span>
                    <span class="rk-tile-sub">Layanan Tiket IT</span>
                </span>
                <svg class="rk-tile-arrow h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>

            {{-- Employee Performance --}}
            <a href="{{ route('employee-performance.index') }}" class="rk-tile" style="--tile-accent:#0ea5e9">
                <span class="rk-tile-icon">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                </span>
                <span class="rk-tile-body">
                    <span class="rk-tile-title">Employee Performance</span>
                    <span class="rk-tile-sub">Integrasi Kinerja</span>
                </span>
                <svg class="rk-tile-arrow h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>

            {{-- Administrasi Keuangan --}}
            <a href="{{ route('financial-administration.index') }}" class="rk-tile" style="--tile-accent:#8b5cf6">
                <span class="rk-tile-icon">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/><path d="M12 15h.01"/><path d="M17 15h.01"/></svg>
                </span>
                <span class="rk-tile-body">
                    <span class="rk-tile-title">Administrasi Keuangan</span>
                    <span class="rk-tile-sub">Integrasi Keuangan</span>
                </span>
                <svg class="rk-tile-arrow h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>

            {{-- BAHTERA (VHTS) --}}
            <a href="{{ url('/bahtera') }}" class="rk-tile" style="--tile-accent:#0d9488">
                <span class="rk-tile-icon">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c1.51 0 2.93.37 4.18 1.03"/><path d="M16 3.13a9 9 0 0 1 0 17.74"/></svg>
                </span>
                <span class="rk-tile-body">
                    <span class="rk-tile-title">BAHTERA</span>
                    <span class="rk-tile-sub">Sistem Validasi VHTS</span>
                </span>
                <svg class="rk-tile-arrow h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>

            {{-- ===== IT-staff-only tiles ===== --}}
            @if(auth()->user()->is_it_staff)
            {{-- Laksamana Export (IT only) --}}
            <a href="{{ route('laksamana.export.page') }}" class="rk-tile" style="--tile-accent:#16a34a">
                <span class="rk-tile-icon">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="7" y1="8" x2="17" y2="8"/><line x1="7" y1="12" x2="17" y2="12"/><line x1="7" y1="16" x2="12" y2="16"/></svg>
                </span>
                <span class="rk-tile-body">
                    <span class="rk-tile-title">Laksamana Export</span>
                    <span class="rk-tile-sub">Ekspor Data XLSX · Khusus IT</span>
                </span>
                <svg class="rk-tile-arrow h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>

            {{-- Manajemen Pengguna (IT only) — opens the user-management modal --}}
            <button type="button" id="open-add-user-modal" class="rk-tile w-full text-left" style="--tile-accent:#0891b2">
                <span class="rk-tile-icon">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                </span>
                <span class="rk-tile-body">
                    <span class="rk-tile-title">Manajemen Pengguna</span>
                    <span class="rk-tile-sub">Tambah akun &amp; ubah password · Khusus IT</span>
                </span>
                <svg class="rk-tile-arrow h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </button>
            @endif
        </div>
    </section>

    {{-- ========================= Helpful links ============================= --}}
    <section class="rk-animate mt-12" style="animation-delay:.15s">
        <span class="rk-eyebrow">Tautan Bermanfaat</span>
        <h2 class="rk-display mt-2 text-2xl font-bold text-[color:var(--text-strong)]">Akses Cepat</h2>
        <p class="mt-1 text-sm text-[color:var(--text-muted)]">Pintasan menuju sumber daya BPS yang sering digunakan.</p>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="https://harian2171.bpskepri.com" target="_blank" rel="noopener" class="rk-tile" style="--tile-accent:#3b82f6">
                <span class="rk-tile-icon">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                </span>
                <span class="rk-tile-body">
                    <span class="rk-tile-title">Laporan Harian</span>
                    <span class="rk-tile-sub">Provinsi Kepri</span>
                </span>
                <svg class="rk-tile-arrow h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M7 7h10v10"/></svg>
            </a>

            <a href="https://s.id/link_bps" target="_blank" rel="noopener" class="rk-tile" style="--tile-accent:#10b981">
                <span class="rk-tile-icon">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                </span>
                <span class="rk-tile-body">
                    <span class="rk-tile-title">Link All Aplikasi</span>
                    <span class="rk-tile-sub">Direktori BPS</span>
                </span>
                <svg class="rk-tile-arrow h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M7 7h10v10"/></svg>
            </a>

            <a href="https://s.id/monumen" target="_blank" rel="noopener" class="rk-tile" style="--tile-accent:#8b5cf6">
                <span class="rk-tile-icon">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 20h12"/><path d="M12 16V4"/><path d="m8 8 4-4 4 4"/></svg>
                </span>
                <span class="rk-tile-body">
                    <span class="rk-tile-title">MONUMEN</span>
                    <span class="rk-tile-sub">Monitoring 2171</span>
                </span>
                <svg class="rk-tile-arrow h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M7 7h10v10"/></svg>
            </a>
        </div>
    </section>
</div>

{{-- ============ IT: User management modal (tabs: add user / change password) ============ --}}
@if(auth()->user()->is_it_staff)
@php
    // Add-user errors use the default bag, change-password errors use their own
    // bag, so a failed submit reopens the modal on the tab that was submitted.
    $resetErrors = $errors->getBag('resetPassword');
    $userModalOpen = $errors->any() || $resetErrors->any();
    $userModalTab = $resetErrors->any() ? 'password' : 'add';
    $tabClass = 'user-tab -mb-px border-b-2 border-transparent px-3 pb-2.5 text-sm font-semibold text-[color:var(--text-muted)] transition-colors hover:text-[color:var(--text-strong)] aria-selected:border-brand-600 aria-selected:text-[color:var(--text-strong)]';
@endphp
<div id="add-user-modal" class="{{ $userModalOpen ? 'flex' : 'hidden' }} fixed inset-0 z-[100] items-center justify-center bg-black/60 p-4 backdrop-blur-sm" aria-modal="true" role="dialog" aria-labelledby="user-modal-title">
    <div class="w-full max-w-lg overflow-hidden rounded-2xl border border-[color:var(--border)] bg-[color:var(--surface)] shadow-2xl">
        <div class="border-b border-[color:var(--border)] bg-gradient-to-r from-brand-500/10 to-emerald-500/5 px-6 pt-4">
            <div class="flex items-center justify-between">
                <h3 id="user-modal-title" class="rk-display text-lg font-bold text-[color:var(--text-strong)]">Manajemen Pengguna</h3>
                <button type="button" id="close-add-user-modal" class="rk-icon-btn h-9 w-9" aria-label="Tutup">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="mt-3 flex gap-2" role="tablist">
                <button type="button" role="tab" id="user-tab-add" data-user-tab="add" aria-controls="user-panel-add" aria-selected="{{ $userModalTab === 'add' ? 'true' : 'false' }}" class="{{ $tabClass }}">Tambah Pengguna</button>
                <button type="button" role="tab" id="user-tab-password" data-user-tab="password" aria-controls="user-panel-password" aria-selected="{{ $userModalTab === 'password' ? 'true' : 'false' }}" class="{{ $tabClass }}">Ubah Password</button>
            </div>
        </div>

        {{-- ---------- Tab: add user ---------- --}}
        <div id="user-panel-add" role="tabpanel" aria-labelledby="user-tab-add" data-user-panel="add" class="{{ $userModalTab === 'add' ? '' : 'hidden' }}">
        <form action="{{ route('users.store') }}" method="POST" class="max-h-[75vh] overflow-y-auto px-6 py-5">
            @csrf
            @php $fieldBorder = fn ($field) => $errors->has($field) ? 'border-red-400' : ''; @endphp

            @if($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-300">
                <p class="font-medium">Periksa kembali isian berikut:</p>
                <ul class="mt-1 list-inside list-disc space-y-0.5">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label for="au-name" class="rk-label">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="au-name" value="{{ old('name') }}" required autocomplete="off" class="rk-input {{ $fieldBorder('name') }}">
                    @error('name')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="au-email" class="rk-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="au-email" value="{{ old('email') }}" required autocomplete="off" class="rk-input {{ $fieldBorder('email') }}">
                    @error('email')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="au-phone" class="rk-label">Nomor Telepon <span class="text-[color:var(--text-faint)]">(opsional)</span></label>
                    <input type="text" name="phone_number" id="au-phone" value="{{ old('phone_number') }}" autocomplete="off" inputmode="tel" class="rk-input {{ $fieldBorder('phone_number') }}">
                    @error('phone_number')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="au-password" class="rk-label">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="au-password" required autocomplete="new-password" class="rk-input {{ $fieldBorder('password') }}">
                    <p class="mt-1 text-xs text-[color:var(--text-muted)]">Minimal 8 karakter.</p>
                    @error('password')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="au-password-confirm" class="rk-label">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" id="au-password-confirm" required autocomplete="new-password" class="rk-input">
                </div>
                <div>
                    <span class="rk-label">Peran</span>
                    <p class="-mt-1 mb-2 text-xs text-[color:var(--text-muted)]">Satu pengguna bisa memiliki lebih dari satu peran. Biarkan kosong untuk pegawai biasa.</p>
                    <div class="space-y-2">
                        <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-[color:var(--border)] px-3 py-2.5 transition-colors hover:bg-[color:var(--surface-muted)]">
                            <input type="checkbox" name="roles[]" value="admin" {{ in_array('admin', old('roles', [])) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                            <span>
                                <span class="block text-sm font-medium text-[color:var(--text-strong)]">Administrator</span>
                                <span class="block text-xs text-[color:var(--text-muted)]">Akses penuh pengelolaan tugas &amp; admin</span>
                            </span>
                        </label>
                        <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-[color:var(--border)] px-3 py-2.5 transition-colors hover:bg-[color:var(--surface-muted)]">
                            <input type="checkbox" name="roles[]" value="it_staff" {{ in_array('it_staff', old('roles', [])) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                            <span>
                                <span class="block text-sm font-medium text-[color:var(--text-strong)]">IT Staff</span>
                                <span class="block text-xs text-[color:var(--text-muted)]">Kelola tiket Halo IP &amp; tambah pengguna</span>
                            </span>
                        </label>
                    </div>
                    @error('roles')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    @error('roles.*')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3 border-t border-[color:var(--border)] pt-4">
                <button type="button" id="cancel-add-user" class="js-close-user-modal rk-btn rk-btn-ghost">Batal</button>
                <button type="submit" class="rk-btn rk-btn-primary">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    Simpan Pengguna
                </button>
            </div>
        </form>
        </div>

        {{-- ---------- Tab: change a user's password (no old password needed) ---------- --}}
        <div id="user-panel-password" role="tabpanel" aria-labelledby="user-tab-password" data-user-panel="password" class="{{ $userModalTab === 'password' ? '' : 'hidden' }}">
        <form action="{{ route('users.password.update') }}" method="POST" id="reset-password-form" class="max-h-[75vh] overflow-y-auto px-6 py-5">
            @csrf
            @method('PUT')

            @if($resetErrors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-300">
                <p class="font-medium">Periksa kembali isian berikut:</p>
                <ul class="mt-1 list-inside list-disc space-y-0.5">
                    @foreach($resetErrors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <p class="mb-4 text-xs text-[color:var(--text-muted)]">Untuk pengguna yang lupa password. Cari penggunanya, lalu isi password baru — password lama tidak diperlukan.</p>

            <div class="space-y-4">
                <div>
                    <label for="rp-search" class="rk-label">Pengguna <span class="text-red-500">*</span></label>
                    <input type="hidden" name="user_id" id="rp-user-id" value="{{ old('user_id') }}">

                    {{-- Chosen user (replaces the search list once picked) --}}
                    <div id="rp-selected" class="hidden items-center justify-between gap-3 rounded-lg border-[1px] border-brand-500/40 bg-brand-500/5 px-3 py-2.5">
                        <span class="min-w-0">
                            <span id="rp-selected-name" class="block truncate text-sm font-semibold text-[color:var(--text-strong)]"></span>
                            <span id="rp-selected-email" class="block truncate text-xs text-[color:var(--text-muted)]"></span>
                        </span>
                        <button type="button" id="rp-change-user" class="shrink-0 text-xs font-semibold text-brand-600 hover:underline">Ganti</button>
                    </div>

                    <div id="rp-picker">
                        <input type="search" id="rp-search" placeholder="Ketik nama atau email…" autocomplete="off" class="rk-input {{ $resetErrors->has('user_id') ? 'border-red-400' : '' }}">
                        <div id="rp-user-list" class="mt-2 max-h-52 overflow-y-auto rounded-lg border-[1px] border-[color:var(--border)]">
                            @foreach($managedUsers as $managed)
                            <button type="button" class="rp-user-option flex w-full flex-col items-start border-b border-[color:var(--border)] px-3 py-2 text-left last:border-b-0 hover:bg-[color:var(--surface-muted)] focus:bg-[color:var(--surface-muted)] focus:outline-none"
                                    data-id="{{ $managed->id }}"
                                    data-name="{{ $managed->name }}"
                                    data-email="{{ $managed->email }}"
                                    data-search="{{ \Illuminate\Support\Str::lower($managed->name . ' ' . $managed->email) }}">
                                <span class="text-sm font-medium text-[color:var(--text-strong)]">{{ $managed->name }}</span>
                                <span class="text-xs text-[color:var(--text-muted)]">{{ $managed->email }}</span>
                            </button>
                            @endforeach
                            <p id="rp-no-results" class="hidden px-3 py-4 text-center text-sm text-[color:var(--text-muted)]">Tidak ada pengguna yang cocok.</p>
                        </div>
                    </div>
                    <p id="rp-user-required" class="mt-1 hidden text-xs text-red-600 dark:text-red-400">Pilih pengguna terlebih dahulu.</p>
                    @error('user_id', 'resetPassword')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="rp-password" class="rk-label">Password Baru <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="rp-password" required autocomplete="new-password" class="rk-input {{ $resetErrors->has('password') ? 'border-red-400' : '' }}">
                    <p class="mt-1 text-xs text-[color:var(--text-muted)]">Minimal 8 karakter.</p>
                    @error('password', 'resetPassword')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="rp-password-confirm" class="rk-label">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" id="rp-password-confirm" required autocomplete="new-password" class="rk-input">
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3 border-t border-[color:var(--border)] pt-4">
                <button type="button" class="js-close-user-modal rk-btn rk-btn-ghost">Batal</button>
                <button type="submit" class="rk-btn rk-btn-primary">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Ubah Password
                </button>
            </div>
        </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('add-user-modal');
    const openBtn = document.getElementById('open-add-user-modal');
    const closeBtn = document.getElementById('close-add-user-modal');
    if (!modal) return;

    const resetForm = document.getElementById('reset-password-form');
    const userIdInput = document.getElementById('rp-user-id');
    const search = document.getElementById('rp-search');
    const picker = document.getElementById('rp-picker');
    const selected = document.getElementById('rp-selected');
    const noResults = document.getElementById('rp-no-results');
    const requiredMsg = document.getElementById('rp-user-required');
    const options = Array.from(modal.querySelectorAll('.rp-user-option'));

    // ---- Tabs ----
    const tabs = modal.querySelectorAll('[data-user-tab]');
    const panels = modal.querySelectorAll('[data-user-panel]');
    let activeTab = 'add';
    tabs.forEach(function (t) { if (t.getAttribute('aria-selected') === 'true') activeTab = t.dataset.userTab; });

    function showTab(name) {
        activeTab = name;
        tabs.forEach(function (t) { t.setAttribute('aria-selected', t.dataset.userTab === name ? 'true' : 'false'); });
        panels.forEach(function (p) { p.classList.toggle('hidden', p.dataset.userPanel !== name); });
    }
    function focusActiveTab() {
        let field = document.getElementById('au-name');
        if (activeTab === 'password') {
            field = document.getElementById(userIdInput.value ? 'rp-password' : 'rp-search');
        }
        if (field) field.focus();
    }
    tabs.forEach(function (t) {
        t.addEventListener('click', function () { showTab(t.dataset.userTab); focusActiveTab(); });
    });

    // ---- Open / close ----
    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        focusActiveTab();
    }
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    if (openBtn) openBtn.addEventListener('click', openModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    modal.querySelectorAll('.js-close-user-modal').forEach(function (b) { b.addEventListener('click', closeModal); });
    modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
    });

    // ---- Change password: find a user, then pick them ----
    function visibleOptions() {
        return options.filter(function (o) { return !o.classList.contains('hidden'); });
    }
    function filterUsers() {
        // Every typed word must match the name or email, e.g. "renata bps".
        const terms = search.value.toLowerCase().split(/\s+/).filter(Boolean);
        options.forEach(function (o) {
            const haystack = o.dataset.search;
            o.classList.toggle('hidden', !terms.every(function (t) { return haystack.indexOf(t) !== -1; }));
        });
        noResults.classList.toggle('hidden', visibleOptions().length > 0);
    }
    function selectUser(option, moveFocus) {
        userIdInput.value = option.dataset.id;
        document.getElementById('rp-selected-name').textContent = option.dataset.name;
        document.getElementById('rp-selected-email').textContent = option.dataset.email;
        selected.classList.remove('hidden');
        selected.classList.add('flex');
        picker.classList.add('hidden');
        requiredMsg.classList.add('hidden');
        if (moveFocus) document.getElementById('rp-password').focus();
    }
    function clearUser() {
        userIdInput.value = '';
        selected.classList.add('hidden');
        selected.classList.remove('flex');
        picker.classList.remove('hidden');
        search.focus();
    }

    options.forEach(function (o) {
        o.addEventListener('click', function () { selectUser(o, true); });
    });
    search.addEventListener('input', filterUsers);
    search.addEventListener('keydown', function (e) {
        // Enter picks the first match instead of submitting the form.
        if (e.key !== 'Enter') return;
        e.preventDefault();
        const first = visibleOptions()[0];
        if (first) selectUser(first, true);
    });
    document.getElementById('rp-change-user').addEventListener('click', clearUser);
    resetForm.addEventListener('submit', function (e) {
        if (userIdInput.value) return;
        e.preventDefault();
        requiredMsg.classList.remove('hidden');
        search.focus();
    });

    // After a failed submit, show the user that was picked again.
    if (userIdInput.value) {
        const previous = options.find(function (o) { return o.dataset.id === userIdInput.value; });
        if (previous) { selectUser(previous, false); } else { userIdInput.value = ''; }
    }

    if (modal.classList.contains('flex')) {
        document.body.style.overflow = 'hidden';
        focusActiveTab();
    }
});
</script>
@endif

{{-- ===================== Rotating quotes (subtitle) ===================== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const quotes = [
        "Akses semua aplikasi dan layanan BPS melalui dashboard terintegrasi RENTAK.",
        "Pantau progres tugas dan kinerja Anda melalui dashboard terintegrasi RENTAK.",
        "Kesuksesan adalah hasil dari persiapan, kerja keras, dan belajar dari kegagalan.",
        "Kualitas bukan suatu tindakan, tetapi sebuah kebiasaan — Aristoteles.",
        "Inovasi membedakan antara pemimpin dan pengikut — Steve Jobs.",
        "Data adalah aset paling berharga di era digital ini.",
        "Statistik yang akurat adalah fondasi kebijakan yang tepat.",
        "Bersama-sama kita membangun Indonesia melalui data yang berkualitas.",
        "Transparansi dan akuntabilitas dimulai dari data yang terpercaya.",
        "Setiap angka yang kita hasilkan berkontribusi untuk kemajuan bangsa."
    ];
    let i = 0;
    const el = document.getElementById('dynamic-quote');
    if (!el) return;
    setInterval(function () {
        el.style.opacity = '0';
        setTimeout(function () {
            i = (i + 1) % quotes.length;
            el.textContent = quotes[i];
            el.style.opacity = '1';
        }, 300);
    }, 5000);
});
</script>
@endsection

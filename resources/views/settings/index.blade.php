@extends('new-homepage.layouts.app')

@section('title', 'Pengaturan — RENTAK')

@section('content')
@php
    $sClean = trim(preg_replace('/,.*$/', '', $user->name ?? 'User'));
    $sParts = preg_split('/\s+/', $sClean) ?: ['U'];
    $sInitials = strtoupper(mb_substr($sParts[0] ?? 'U', 0, 1) . (count($sParts) > 1 ? mb_substr(end($sParts), 0, 1) : ''));
@endphp

<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 text-sm font-medium text-[color:var(--text-muted)] transition-colors hover:text-brand-600 dark:hover:text-brand-300">
        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
        Kembali ke Dashboard
    </a>

    {{-- Header --}}
    <div class="rk-animate mt-5 flex items-center gap-4">
        <span class="rk-avatar h-14 w-14 rounded-2xl text-lg">{{ $sInitials }}</span>
        <div>
            <h1 class="rk-display text-2xl font-bold text-[color:var(--text-strong)]">Pengaturan Akun</h1>
            <p class="mt-0.5 text-sm text-[color:var(--text-muted)]">{{ $sClean }} · Kelola profil dan keamanan akun Anda.</p>
        </div>
    </div>

    {{-- Success --}}
    @if(session('success'))
    <div class="rk-animate mt-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-300">
        <svg class="mt-0.5 h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
        <p class="text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif

    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Profile --}}
        <section class="rk-card rk-animate overflow-hidden" style="animation-delay:.05s">
            <div class="rk-card-head flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600/12 text-brand-600 ring-1 ring-brand-600/20 dark:text-brand-300">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </span>
                <div>
                    <h2 class="rk-display text-base font-bold text-[color:var(--text-strong)]">Informasi Profil</h2>
                    <p class="text-xs text-[color:var(--text-muted)]">Perbarui informasi profil Anda</p>
                </div>
            </div>
            <form action="{{ route('settings.profile.update') }}" method="POST" class="space-y-4 p-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="rk-label">Nama</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="rk-input">
                    @error('name')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="rk-label">Email</label>
                    <input type="email" id="email" value="{{ $user->email }}" disabled
                           class="rk-input cursor-not-allowed opacity-60">
                    <p class="mt-1 text-xs text-[color:var(--text-faint)]">Email tidak dapat diubah.</p>
                </div>
                <button type="submit" class="rk-btn rk-btn-primary w-full">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    Perbarui Profil
                </button>
            </form>
        </section>

        {{-- Password --}}
        <section class="rk-card rk-animate overflow-hidden" style="animation-delay:.1s">
            <div class="rk-card-head flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600/12 text-brand-600 ring-1 ring-brand-600/20 dark:text-brand-300">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </span>
                <div>
                    <h2 class="rk-display text-base font-bold text-[color:var(--text-strong)]">Ubah Kata Sandi</h2>
                    <p class="text-xs text-[color:var(--text-muted)]">Perbarui password untuk keamanan akun</p>
                </div>
            </div>
            <form action="{{ route('settings.password.update') }}" method="POST" class="space-y-4 p-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="password" class="rk-label">Kata Sandi Baru</label>
                    <input type="password" id="password" name="password" required class="rk-input">
                    @error('password')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="rk-label">Konfirmasi Kata Sandi</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="rk-input">
                </div>
                <button type="submit" class="rk-btn rk-btn-primary w-full">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2Z"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Ubah Kata Sandi
                </button>
            </form>
        </section>
    </div>

    {{-- Appearance --}}
    <section class="rk-card rk-animate mt-6 overflow-hidden" style="animation-delay:.15s">
        <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600/12 text-brand-600 ring-1 ring-brand-600/20 dark:text-brand-300">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
                </span>
                <div>
                    <h2 class="rk-display text-base font-bold text-[color:var(--text-strong)]">Tampilan</h2>
                    <p class="text-xs text-[color:var(--text-muted)]">Pilih tema terang atau gelap. Preferensi tersimpan di perangkat ini.</p>
                </div>
            </div>
            <button type="button" id="settings-theme-toggle" class="rk-btn rk-btn-ghost">
                <svg class="rk-sun h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
                <span id="settings-theme-label">Beralih Tema</span>
            </button>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var root = document.documentElement;
        var btn = document.getElementById('settings-theme-toggle');
        var label = document.getElementById('settings-theme-label');
        function sync() { if (label) label.textContent = root.classList.contains('dark') ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'; }
        sync();
        if (btn) btn.addEventListener('click', function () {
            var isDark = root.classList.toggle('dark');
            root.style.colorScheme = isDark ? 'dark' : 'light';
            try { localStorage.setItem('rentak-theme', isDark ? 'dark' : 'light'); } catch (e) {}
            sync();
        });
    });
</script>
@endsection

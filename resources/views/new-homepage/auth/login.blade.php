@extends('new-homepage.layouts.app')

@section('title', 'Masuk — RENTAK')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/new-homepage/login.css') }}">
@endsection

@section('content')
<div class="login-shell">
    <div class="login-card">
        <div class="login-head">
            <div class="login-logo">
                <img src="{{ asset('img/Logo BPS.png') }}" alt="BPS">
            </div>
            <h1 class="login-title">Masuk ke <span>RENTAK</span></h1>
            <p class="login-subtitle">Reformasi dan Integrasi Kinerja — BPS Kota Batam</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf

            <div class="login-field">
                <label for="email" class="login-label">Alamat Email</label>
                <div class="login-input-wrap">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                           placeholder="nama@email.go.id"
                           class="login-input @error('email') is-invalid @enderror">
                    <svg class="login-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                </div>
                @error('email')
                <div class="login-error">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    <span>{{ $message }}</span>
                </div>
                @enderror
            </div>

            <div class="login-field">
                <label for="password" class="login-label">Kata Sandi</label>
                <div class="login-input-wrap">
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           placeholder="••••••••"
                           class="login-input @error('password') is-invalid @enderror">
                    <svg class="login-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <button type="button" class="login-eye" id="toggle-password" aria-label="Tampilkan kata sandi">
                        <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg id="eye-closed" class="hidden" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                    </button>
                </div>
                @error('password')
                <div class="login-error">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    <span>{{ $message }}</span>
                </div>
                @enderror
            </div>

            <button type="submit" class="login-submit">
                <span>Masuk</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
            </button>
        </form>

        <div class="login-foot">
            <p>Sistem Internal BPS Kota Batam · Akses hanya untuk pegawai.</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggle = document.getElementById('toggle-password');
        var input = document.getElementById('password');
        var eyeOpen = document.getElementById('eye-open');
        var eyeClosed = document.getElementById('eye-closed');
        if (toggle && input) {
            toggle.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                eyeOpen.classList.toggle('hidden', show);
                eyeClosed.classList.toggle('hidden', !show);
            });
        }
    });
</script>
@endsection

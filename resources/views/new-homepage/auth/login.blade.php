@extends('new-homepage.layouts.app')

@section('title', 'Login - RENTAK')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/new-homepage/login.css') }}">
@endsection

@section('content')
<div class="login-section">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <div class="relative flex h-12 w-12 items-center justify-center overflow-hidden rounded-full bg-gradient-to-br from-teal-500 via-emerald-500 to-teal-600 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-white">
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                            <line x1="3" x2="21" y1="9" y2="9"></line>
                            <line x1="9" x2="9" y1="21" y2="9"></line>
                        </svg>
                    </div>
                </div>
                <h1 class="login-title">Sign in to RENTAK</h1>
                <p class="login-subtitle">Enter your credentials to access your account</p>
            </div>

            <div class="login-form-container">
                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon">
                                <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                            </svg>
                            <input id="email" type="email" class="form-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="your.email@example.com">
                        </div>
                        @error('email')
                            <div class="error-message">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="error-icon">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" x2="12" y1="8" y2="12"></line>
                                    <line x1="12" x2="12.01" y1="16" y2="16"></line>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon">
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <input id="password" type="password" class="form-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                        </div>
                        @error('password')
                            <div class="error-message">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="error-icon">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" x2="12" y1="8" y2="12"></line>
                                    <line x1="12" x2="12.01" y1="16" y2="16"></line>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="login-button">
                            <span>Sign In</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="button-icon">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                <polyline points="10 17 15 12 10 7"></polyline>
                                <line x1="15" x2="3" y1="12" y2="12"></line>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="login-footer">
                <p>RENTAK - BPS Kota Batam Internal System</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Force light mode
        document.documentElement.classList.remove("dark");
        document.documentElement.classList.add("light");
        document.body.classList.add("light");
        document.body.classList.remove("dark");
        localStorage.setItem("theme", "light");
    });
</script>
@endsection

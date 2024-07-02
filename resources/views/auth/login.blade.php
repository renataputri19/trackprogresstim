@extends('layouts.main')

@section('title', 'Login')

<head>
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>

@section('content')
<div class="login-wrapper">
    <div class="login-box">
        <div class="login-header">
            <span>Login</span>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="input-box">
                <input id="email" type="email" class="input-field @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                <label for="email" class="label">Email Address</label>
                <i class="bx bx-user icon"></i>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="input-box">
                <input id="password" type="password" class="input-field @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                <label for="password" class="label">Password</label>
                <i class="bx bx-lock-alt icon"></i>

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- <div class="remember-forgot">
                <div class="remember-me">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Remember me</label>
                </div>
                @if (Route::has('password.request'))
                    <div class="forgot">
                        <a href="{{ route('password.request') }}">Forgot password?</a>
                    </div>
                @endif
            </div> --}}

            <div class="input-box">
                <button type="submit" class="input-submit">Login</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var inputElements = document.querySelectorAll('.input-field');

        inputElements.forEach(function(input) {
            // Set a placeholder value if the input has a value on page load
            if (input.value !== '') {
                input.placeholder = ' '; // Set to a space to trigger :not(:placeholder-shown)
                input.classList.add('filled');
            }

            // Add an event listener for when the input gains focus
            input.addEventListener('focus', function() {
                this.placeholder = ' '; // Set to a space to trigger :not(:placeholder-shown)
                this.classList.add('filled');
            });

            // Add an event listener for when the input loses focus
            input.addEventListener('blur', function() {
                if (this.value === '') {
                    this.placeholder = ''; // Reset placeholder
                    this.classList.remove('filled');
                }
            });
        });
    });
</script>
@endsection

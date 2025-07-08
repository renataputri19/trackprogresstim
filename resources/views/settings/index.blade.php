@extends('new-homepage.layouts.app')

@section('title', 'Settings - RENTAK')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
                <p class="mt-2 text-gray-600">Kelola informasi profil dan keamanan akun Anda</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Profile Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Informasi Profil</h2>
                        <p class="mt-1 text-sm text-gray-600">Perbarui informasi profil Anda</p>
                    </div>
                    <form action="{{ route('settings.profile.update') }}" method="POST" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-200"
                                       required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" 
                                       id="email" 
                                       value="{{ $user->email }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500 cursor-not-allowed"
                                       disabled>
                                <p class="mt-1 text-xs text-gray-500">Email tidak dapat diubah</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-teal-600 via-emerald-500 to-teal-600 text-white py-2 px-4 rounded-md hover:from-teal-700 hover:via-emerald-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all duration-300 font-medium">
                                Perbarui Profil
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Update -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Ubah Password</h2>
                        <p class="mt-1 text-sm text-gray-600">Perbarui password untuk keamanan akun</p>
                    </div>
                    <form action="{{ route('settings.password.update') }}" method="POST" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                <input type="password" 
                                       id="password" 
                                       name="password"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-200"
                                       required>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-200"
                                       required>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-teal-600 via-emerald-500 to-teal-600 text-white py-2 px-4 rounded-md hover:from-teal-700 hover:via-emerald-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all duration-300 font-medium">
                                Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Back to Dashboard -->
            <div class="mt-8 text-center">
                <a href="{{ route('welcome') }}" 
                   class="inline-flex items-center text-teal-600 hover:text-teal-700 font-medium transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <path d="m12 19-7-7 7-7"/>
                        <path d="M19 12H5"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

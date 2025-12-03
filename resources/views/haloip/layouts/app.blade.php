<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <meta name="is-it-staff" content="{{ Auth::user()->is_it_staff ? 'true' : 'false' }}">
    @endauth

    <title>@yield('title', 'HaloIP - RENTAK')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/new-homepage/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/new-homepage/homepage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/new-homepage/homepage-new.css') }}">
    <link rel="stylesheet" href="{{ asset('css/haloip/haloip.css') }}">
    <!-- Favicon and Social Sharing Images -->
    <link rel="icon" type="image/png" href="{{ asset('img/Logo BPS.png') }}">
    <meta property="og:image" content="{{ asset('img/Logo BPS.png') }}">
    <meta name="twitter:image" content="{{ asset('img/Logo BPS.png') }}">
    @yield('styles')
    @stack('head')
</head>
<body class="font-sans antialiased bg-white min-h-screen light">
    <header class="sticky top-0 z-50 w-full border-b bg-white shadow-sm">
        <div class="container mx-auto flex h-16 items-center space-x-4 sm:justify-between sm:space-x-0 px-4">
            <div class="flex gap-6 md:gap-10">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('img/Logo BPS.png') }}" alt="BPS Logo" class="h-10 w-10 rounded-full shadow-md">
                    <span class="inline-block bg-gradient-to-r from-teal-600 via-emerald-500 to-teal-600 bg-clip-text text-xl font-bold text-transparent">
                        RENTAK
                    </span>
                </a>
                <nav class="hidden gap-6 md:flex">
                    <a href="{{ route('home') }}" class="flex items-center text-sm font-medium {{ request()->routeIs('home') ? 'text-teal-600' : 'text-gray-500' }} transition-colors hover:text-teal-600">
                        Home
                    </a>
                    <a href="{{ route('haloip.index') }}" class="flex items-center text-sm font-medium {{ request()->routeIs('haloip*') || request()->routeIs('tickets*') || request()->routeIs('map-requests*') ? 'text-teal-600' : 'text-gray-500' }} transition-colors hover:text-teal-600">
                        HaloIP
                    </a>
                    @auth
                    <a href="{{ route('welcome') }}" class="flex items-center text-sm font-medium {{ request()->routeIs('welcome') ? 'text-teal-600' : 'text-gray-500' }} transition-colors hover:text-teal-600">
                        Dashboard
                    </a>
                    @endauth
                </nav>
            </div>
            <div class="flex flex-1 items-center justify-end space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="hidden border border-teal-500 text-teal-600 hover:bg-teal-50 hover:text-teal-700 sm:flex px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Log in
                    </a>
                    <a href="{{ route('login') }}" class="bg-gradient-to-r from-teal-600 via-emerald-500 to-teal-600 text-white shadow-md hover:shadow-lg hover:from-teal-700 hover:via-emerald-600 hover:to-teal-700 transition-all duration-300 px-4 py-2 rounded-md text-sm font-medium">
                        Access RENTAK
                    </a>
                @else
                    <div class="relative" id="profile-dropdown">
                        <button id="profile-dropdown-button" class="flex items-center space-x-1 text-teal-600 hover:text-teal-700 font-medium transition-colors duration-200">
                            <span>{{ Auth::user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 transition-transform duration-200" id="profile-dropdown-arrow">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>
                        <div id="profile-dropdown-menu" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 opacity-0 invisible transform scale-95 transition-all duration-200 ease-out">
                            <div class="py-1">
                                <a href="{{ route('welcome') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                    Dashboard
                                </a>
                                @if(Auth::user()->is_it_staff)
                                <a href="{{ route('tickets.manage') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                    Kelola Tiket
                                </a>
                                <a href="{{ route('map-requests.manage') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                    Kelola Peta
                                </a>
                                @endif
                                <div class="border-t border-gray-100"></div>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </header>

    <main class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-teal-50">
        @yield('content')
    </main>

    <footer class="border-t border-teal-100 bg-white py-8">
        <div class="container mx-auto px-4">
            <!-- Main Footer Content -->
            <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                <!-- Logo and Copyright Section -->
                <div class="flex flex-col gap-4">
                    <div class="flex items-center space-x-2">
                        <img src="{{ asset('img/Logo BPS.png') }}" alt="BPS Logo" class="h-10 w-10 rounded-full shadow-md">
                        <span class="inline-block bg-gradient-to-r from-teal-600 via-emerald-500 to-teal-600 bg-clip-text text-xl font-bold text-transparent">
                            RENTAK
                        </span>
                    </div>
                </div>

                <!-- Contact Links -->
                <div class="flex flex-col gap-2">
                    <h3 class="text-lg font-medium">Contact Us</h3>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-gray-500">
                            <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                        </svg>
                        <a href="mailto:bps2171@bps.go.id" class="text-sm text-gray-500 hover:text-teal-600">
                            bps2171@bps.go.id
                        </a>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-gray-500">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                            <path d="M2 12h20"></path>
                        </svg>
                        <a href="https://batamkota.bps.go.id" target="_blank" rel="noopener noreferrer" class="text-sm text-gray-500 hover:text-teal-600">
                            batamkota.bps.go.id
                        </a>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="flex flex-col gap-2">
                    <h3 class="text-lg font-medium">Follow Us</h3>
                    <div class="flex gap-4">
                        <a href="https://www.facebook.com/BPS.KOTA.BATAM/?locale=id_ID" target="_blank" rel="noopener noreferrer" class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-teal-100 hover:text-teal-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                            <span class="sr-only">Facebook</span>
                        </a>
                        <a href="https://www.instagram.com/bps.batam/" target="_blank" rel="noopener noreferrer" class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-teal-100 hover:text-teal-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                            </svg>
                            <span class="sr-only">Instagram</span>
                        </a>
                        <a href="https://www.youtube.com/channel/UCjNSyjtj4Y9fBhxcJG3Xaag" target="_blank" rel="noopener noreferrer" class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-teal-100 hover:text-teal-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                <path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"></path>
                                <path d="m10 15 5-3-5-3z"></path>
                            </svg>
                            <span class="sr-only">YouTube</span>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="flex flex-col gap-2">
                    <h3 class="text-lg font-medium">Quick Links</h3>
                    <a href="https://www.bps.go.id" target="_blank" rel="noopener noreferrer" class="text-sm text-gray-500 hover:text-teal-600">
                        BPS RI
                    </a>
                    <a href="https://kepri.bps.go.id" target="_blank" rel="noopener noreferrer" class="text-sm text-gray-500 hover:text-teal-600">
                        BPS Provinsi Kepri
                    </a>
                    <a href="https://batamkota.bps.go.id" target="_blank" rel="noopener noreferrer" class="text-sm text-gray-500 hover:text-teal-600">
                        BPS Kota Batam
                    </a>
                </div>
            </div>

            <!-- Legal Section -->
            <div class="mt-8 border-t border-teal-100 pt-4 text-center">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} RENTAK - BPS Kota Batam. All rights reserved.
                </p>
                <p class="mt-2 text-sm text-gray-500">
                    Developed by: Renata Putri Henessa | Tim Inovasi SPBE BPS Kota Batam
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/new-homepage/app.js') }}"></script>
    <script src="{{ asset('js/ticketing.js') }}"></script>

    <!-- Profile Dropdown Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownButton = document.getElementById('profile-dropdown-button');
            const dropdownMenu = document.getElementById('profile-dropdown-menu');
            const dropdownArrow = document.getElementById('profile-dropdown-arrow');
            let isOpen = false;

            function toggleDropdown() {
                isOpen = !isOpen;

                if (isOpen) {
                    dropdownMenu.classList.remove('opacity-0', 'invisible', 'scale-95');
                    dropdownMenu.classList.add('opacity-100', 'visible', 'scale-100');
                    dropdownArrow.style.transform = 'rotate(180deg)';
                } else {
                    dropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                    dropdownMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                    dropdownArrow.style.transform = 'rotate(0deg)';
                }
            }

            function closeDropdown() {
                if (isOpen) {
                    isOpen = false;
                    dropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                    dropdownMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                    dropdownArrow.style.transform = 'rotate(0deg)';
                }
            }

            // Toggle dropdown on button click
            if (dropdownButton) {
                dropdownButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleDropdown();
                });
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                const dropdown = document.getElementById('profile-dropdown');
                if (dropdown && !dropdown.contains(e.target)) {
                    closeDropdown();
                }
            });

            // Prevent dropdown from closing when clicking inside the menu
            if (dropdownMenu) {
                dropdownMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Close dropdown on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDropdown();
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html>

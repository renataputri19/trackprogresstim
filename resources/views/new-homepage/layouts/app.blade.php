<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0d9488">

    <title>@yield('title', 'RENTAK — Reformasi dan Integrasi Kinerja')</title>

    {{-- No-FOUC theme init: set the theme class before first paint --}}
    <script>
        (function () {
            try {
                var stored = localStorage.getItem('rentak-theme');
                var theme = stored || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.classList.toggle('dark', theme === 'dark');
                document.documentElement.style.colorScheme = theme;
            } catch (e) {}
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind (Play CDN) + brand config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdfa', 100: '#ccfbf1', 200: '#99f6e4', 300: '#5eead4',
                            400: '#2dd4bf', 500: '#14b8a6', 600: '#0d9488', 700: '#0f766e',
                            800: '#115e59', 900: '#134e4a', 950: '#042f2e',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    },
                    keyframes: {
                        'fade-up': { '0%': { opacity: 0, transform: 'translateY(14px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
                    },
                    animation: { 'fade-up': 'fade-up .6s cubic-bezier(.2,.7,.3,1) forwards' },
                },
            },
        };
    </script>

    <!-- Bootstrap CSS (retained for carousel on landing page) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- RENTAK design system -->
    <link rel="stylesheet" href="{{ asset('css/new-homepage/rentak-theme.css') }}">

    <!-- Shared page styles -->
    <link rel="stylesheet" href="{{ asset('css/new-homepage/app.css') }}">

    <!-- Favicon and Social Sharing Images -->
    <link rel="icon" type="image/png" href="{{ asset('img/Logo BPS.png') }}">
    <meta property="og:image" content="{{ asset('img/Logo BPS.png') }}">
    <meta name="twitter:image" content="{{ asset('img/Logo BPS.png') }}">
    @yield('styles')
</head>
<body class="rk-body min-h-screen antialiased">

    @php
        $rkUser = auth()->user();
        if ($rkUser) {
            $rkClean = trim(preg_replace('/,.*$/', '', $rkUser->name ?? 'User'));
            $rkParts = preg_split('/\s+/', $rkClean) ?: ['U'];
            $rkInitials = strtoupper(mb_substr($rkParts[0] ?? 'U', 0, 1) . (count($rkParts) > 1 ? mb_substr(end($rkParts), 0, 1) : ''));
            $rkRole = $rkUser->is_it_staff ? 'IT Staff' : ($rkUser->is_admin ? 'Administrator' : 'Pegawai BPS');
        }
    @endphp

    <header id="rk-header" class="rk-header">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <!-- Brand + primary nav -->
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('img/Logo BPS.png') }}" alt="BPS" class="rk-logo-mark h-9 w-9">
                    <span class="flex flex-col leading-none">
                        <span class="rk-wordmark text-xl">RENTAK</span>
                        <span class="hidden text-[10px] font-medium tracking-wide text-[color:var(--text-faint)] sm:block">BPS Kota Batam</span>
                    </span>
                </a>
                <nav class="hidden items-center gap-7 md:flex">
                    <a href="{{ route('home') }}" class="rk-nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
                    @auth
                        <a href="{{ route('welcome') }}" class="rk-nav-link {{ request()->routeIs('welcome') ? 'is-active' : '' }}">Dashboard</a>
                    @else
                        <a href="{{ route('documentation.index') }}" class="rk-nav-link {{ request()->routeIs('documentation*') ? 'is-active' : '' }}">Documentation</a>
                        <a href="{{ route('tutorials.index') }}" class="rk-nav-link {{ request()->routeIs('tutorials*') ? 'is-active' : '' }}">Tutorials</a>
                    @endauth
                </nav>
            </div>

            <!-- Right side -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Theme toggle -->
                <button type="button" id="rk-theme-toggle" class="rk-icon-btn rk-theme-toggle relative" aria-label="Ganti tema" title="Ganti tema terang/gelap">
                    <svg class="rk-sun h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
                    <svg class="rk-moon h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                </button>

                @guest
                    <a href="{{ route('login') }}" class="rk-btn rk-btn-outline hidden sm:inline-flex">Log in</a>
                    <a href="{{ route('login') }}" class="rk-btn rk-btn-primary">
                        <span>Masuk RENTAK</span>
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                @else
                    <!-- Profile dropdown -->
                    <div class="relative" id="rk-profile">
                        <button type="button" id="rk-profile-btn" class="rk-profile-trigger" aria-haspopup="true" aria-expanded="false">
                            <span class="rk-avatar h-8 w-8 rounded-full text-xs">{{ $rkInitials }}</span>
                            <span class="hidden min-w-0 flex-col text-left sm:flex">
                                <span class="truncate text-sm font-semibold text-[color:var(--text-strong)]" style="max-width:140px">{{ $rkClean }}</span>
                                <span class="text-[11px] leading-tight text-[color:var(--text-muted)]">{{ $rkRole }}</span>
                            </span>
                            <svg id="rk-profile-arrow" class="h-4 w-4 text-[color:var(--text-faint)] transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </button>

                        <div id="rk-profile-menu" class="rk-menu">
                            <div class="rk-menu-header flex items-center gap-3">
                                <span class="rk-avatar h-11 w-11 flex-shrink-0 rounded-full text-sm">{{ $rkInitials }}</span>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-[color:var(--text-strong)]">{{ $rkClean }}</p>
                                    <p class="truncate text-xs text-[color:var(--text-muted)]">{{ $rkUser->email }}</p>
                                    <span class="rk-badge mt-1">{{ $rkRole }}</span>
                                </div>
                            </div>
                            <div class="py-1.5">
                                <a href="{{ route('welcome') }}" class="rk-menu-item">
                                    <svg class="h-[18px] w-[18px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                                    Dashboard
                                </a>
                                <a href="{{ route('settings') }}" class="rk-menu-item">
                                    <svg class="h-[18px] w-[18px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Pengaturan
                                </a>
                                <button type="button" id="rk-menu-theme" class="rk-menu-item w-full text-left">
                                    <svg class="h-[18px] w-[18px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
                                    <span id="rk-menu-theme-label">Mode Gelap</span>
                                </button>
                                <div class="my-1 border-t border-[color:var(--border)]"></div>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('rk-logout-form').submit();"
                                   class="rk-menu-item is-danger">
                                    <svg class="h-[18px] w-[18px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>
                                    Keluar
                                </a>
                                <form id="rk-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                            </div>
                        </div>
                    </div>
                @endguest

                <!-- Mobile menu button -->
                <button type="button" id="rk-mobile-btn" class="rk-icon-btn md:hidden" aria-label="Menu">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        <!-- Mobile nav -->
        <div id="rk-mobile-menu" class="hidden border-t border-[color:var(--border)] bg-[color:var(--surface)] md:hidden">
            <nav class="mx-auto flex max-w-7xl flex-col gap-1 px-4 py-3">
                <a href="{{ route('home') }}" class="rk-menu-item rounded-lg">Home</a>
                @auth
                    <a href="{{ route('welcome') }}" class="rk-menu-item rounded-lg">Dashboard</a>
                    <a href="{{ route('settings') }}" class="rk-menu-item rounded-lg">Pengaturan</a>
                @else
                    <a href="{{ route('documentation.index') }}" class="rk-menu-item rounded-lg">Documentation</a>
                    <a href="{{ route('tutorials.index') }}" class="rk-menu-item rounded-lg">Tutorials</a>
                    <a href="{{ route('login') }}" class="rk-btn rk-btn-primary mt-2">Masuk RENTAK</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="rk-footer mt-16">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-10 md:grid-cols-4">
                <!-- Brand -->
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('img/Logo BPS.png') }}" alt="BPS" class="rk-logo-mark h-9 w-9">
                        <span class="rk-wordmark text-xl">RENTAK</span>
                    </div>
                    <p class="mt-4 max-w-xs text-sm leading-relaxed text-[color:var(--text-muted)]">
                        Reformasi dan Integrasi Kinerja — super app terintegrasi untuk seluruh layanan BPS Kota Batam.
                    </p>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="rk-display text-sm font-bold text-[color:var(--text-strong)]">Kontak</h3>
                    <div class="mt-4 space-y-3">
                        <a href="mailto:bps2171@bps.go.id" class="flex items-center gap-2.5 text-sm">
                            <svg class="h-4 w-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            bps2171@bps.go.id
                        </a>
                        <a href="https://batamkota.bps.go.id" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2.5 text-sm">
                            <svg class="h-4 w-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/></svg>
                            batamkota.bps.go.id
                        </a>
                    </div>
                </div>

                <!-- Social -->
                <div>
                    <h3 class="rk-display text-sm font-bold text-[color:var(--text-strong)]">Ikuti Kami</h3>
                    <div class="mt-4 flex gap-3">
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="rk-social" aria-label="Facebook">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="rk-social" aria-label="Instagram">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                        </a>
                        <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="rk-social" aria-label="YouTube">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><path d="m10 15 5-3-5-3z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Quick links -->
                <div>
                    <h3 class="rk-display text-sm font-bold text-[color:var(--text-strong)]">Tautan Cepat</h3>
                    <div class="mt-4 flex flex-col gap-2.5 text-sm">
                        <a href="https://www.bps.go.id" target="_blank" rel="noopener noreferrer">BPS RI</a>
                        <a href="https://kepri.bps.go.id" target="_blank" rel="noopener noreferrer">BPS Provinsi Kepri</a>
                        <a href="https://batamkota.bps.go.id" target="_blank" rel="noopener noreferrer">BPS Kota Batam</a>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex flex-col items-center justify-between gap-3 border-t border-[color:var(--border)] pt-6 text-center sm:flex-row sm:text-left">
                <p class="text-xs text-[color:var(--text-muted)]">&copy; {{ date('Y') }} RENTAK — BPS Kota Batam. All rights reserved.</p>
                <p class="text-xs text-[color:var(--text-faint)]">Developed by Renata Putri Henessa · Tim Inovasi SPBE BPS Kota Batam</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS (carousel on landing page) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/new-homepage/app.js') }}"></script>

    <!-- RENTAK shell interactions -->
    <script>
        (function () {
            // Theme toggle (persisted) -------------------------------------
            var root = document.documentElement;
            function applyThemeLabel() {
                var isDark = root.classList.contains('dark');
                var label = document.getElementById('rk-menu-theme-label');
                if (label) label.textContent = isDark ? 'Mode Terang' : 'Mode Gelap';
            }
            function toggleTheme() {
                var isDark = root.classList.toggle('dark');
                root.style.colorScheme = isDark ? 'dark' : 'light';
                try { localStorage.setItem('rentak-theme', isDark ? 'dark' : 'light'); } catch (e) {}
                applyThemeLabel();
            }
            applyThemeLabel();
            var toggleBtn = document.getElementById('rk-theme-toggle');
            var menuThemeBtn = document.getElementById('rk-menu-theme');
            if (toggleBtn) toggleBtn.addEventListener('click', toggleTheme);
            if (menuThemeBtn) menuThemeBtn.addEventListener('click', toggleTheme);

            // Header shadow on scroll --------------------------------------
            var header = document.getElementById('rk-header');
            function onScroll() { if (header) header.classList.toggle('is-scrolled', window.scrollY > 8); }
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });

            // Profile dropdown ---------------------------------------------
            var pBtn = document.getElementById('rk-profile-btn');
            var pMenu = document.getElementById('rk-profile-menu');
            var pArrow = document.getElementById('rk-profile-arrow');
            var pWrap = document.getElementById('rk-profile');
            function closeProfile() {
                if (!pMenu) return;
                pMenu.classList.remove('is-open');
                if (pArrow) pArrow.style.transform = 'rotate(0deg)';
                if (pBtn) pBtn.setAttribute('aria-expanded', 'false');
            }
            if (pBtn) {
                pBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var open = pMenu.classList.toggle('is-open');
                    if (pArrow) pArrow.style.transform = open ? 'rotate(180deg)' : 'rotate(0deg)';
                    pBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
                });
            }
            document.addEventListener('click', function (e) {
                if (pWrap && !pWrap.contains(e.target)) closeProfile();
            });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeProfile(); });

            // Mobile menu --------------------------------------------------
            var mBtn = document.getElementById('rk-mobile-btn');
            var mMenu = document.getElementById('rk-mobile-menu');
            if (mBtn && mMenu) {
                mBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    mMenu.classList.toggle('hidden');
                });
            }
        })();
    </script>

    @yield('scripts')
</body>
</html>

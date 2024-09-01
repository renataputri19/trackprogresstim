<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Rentak')</title>
    @vite('resources/css/app.css')
    @vite('resources/css/homepage.css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- FullCalendar CSS from CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css">
    {{-- <link href="{{ asset('css/login.css') }}" rel="stylesheet"> --}}
</head>
<body>
    <header class="bg-white shadow">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center py-3">
                <a href="{{ url('/') }}" class="h4 text-decoration-none text-dark font-weight-bold">RENTAK</a>
    
                <nav class="d-flex align-items-center">
                    @guest
                        <a href="#about-us" class="mr-3">About Us</a>
                        <a href="#how-it-works" class="mr-3">How it works</a>
                        <a href="{{ route('login') }}" class="btn btn-dark">Login</a>
                    @else
                        <!-- Main Navigation Links -->
                        {{-- <a href="{{ route('welcome') }}" class="mr-3 {{ request()->routeIs('welcome') ? 'active' : '' }}">
                            <i class="fas fa-home"></i> Home
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="mr-3 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a href="{{ route('user.dashboard') }}" class="mr-3 {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-user"></i> My Dashboard
                        </a>
                        <a href="{{ route(auth()->user()->is_admin ? 'admin.tasks.index' : 'user.tasks.index') }}" class="mr-3 {{ request()->routeIs(['admin.tasks.index', 'user.tasks.index']) ? 'active' : '' }}">
                            <i class="fas fa-tasks"></i> Tasks
                        </a> --}}
    
                        <!-- Profile Dropdown -->
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle mr-3 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ route('welcome') }}" class="dropdown-item {{ request()->routeIs('welcome') ? 'active' : '' }}">
                                    <i class="fas fa-home"></i> Home
                                </a>
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                                <a href="{{ route('user.dashboard') }}" class="dropdown-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-user"></i> My Dashboard
                                </a>
                                <a href="{{ route(auth()->user()->is_admin ? 'admin.tasks.index' : 'user.tasks.index') }}" class="dropdown-item {{ request()->routeIs(['admin.tasks.index', 'user.tasks.index']) ? 'active' : '' }}">
                                    <i class="fas fa-tasks"></i> Tasks
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('logout') }}" class="dropdown-item"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </div>
                        </div>
    
                        <!-- Logout Form -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endguest
                </nav>
            </div>
        </div>
    </header>
    

    <main class="container py-5">
        @yield('content')
    </main>

    <footer>
        <div class="footer-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <h5>Contact Us</h5>
                        <ul class="list-unstyled">
                            <li><a href="mailto:bps2171@bps.go.id">bps2171@bps.go.id</a></li>
                            <li><a href="https://batamkota.bps.go.id/">batamkota.bps.go.id</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h5>Follow Us</h5>
                        <ul class="list-unstyled">
                            <li><a href="https://www.facebook.com/BPS.KOTA.BATAM/?locale=id_ID"><i class="fab fa-facebook"></i> Facebook</a></li>
                            <li><a href="https://www.instagram.com/bps.batam/"><i class="fab fa-instagram"></i> Instagram</a></li>
                            <li><a href="https://www.youtube.com/channel/UCjNSyjtj4Y9fBhxcJG3Xaag"><i class="fab fa-youtube"></i> YouTube</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h5>Quick Links</h5>
                        <ul class="list-unstyled">
                            <li><a href="https://harian2171.bpskepri.com">Laporan Harian</a></li>
                            <li><a href="https://s.id/link_bps">Link All Aplikasi BPS</a></li>
                            <li><a href="https://s.id/monumen">MONUMEN 2171</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h5>Renata Putri Henessa</h5>
                        <p>Pelatihan Dasar CPNS 2024</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container text-center">
                <p>&copy; 2024 BPS Kota Batam | <a href="https://batamkota.bps.go.id/" class="text-white">batamkota.bps.go.id</a></p>
            </div>
        </div>
    </footer>


    <input type="hidden" value="{{ route('admin.gantt.update') }}" id="routing-gantt-update">

    @vite('resources/js/app.js')
    @vite('resources/js/homepage.js')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
    <!-- FullCalendar JS from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.js"></script>
    <script src="{{ asset('js/date.js') }}"></script>
    <script src="{{ asset('js/columns-toggle.js') }}"></script>
    <script src="{{ asset('js/gantt_initialization.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.dropdown-toggle').click(function(e) {
                e.preventDefault();
                $(this).next('.dropdown-menu').toggleClass('show');
            });
    
            // Hide the dropdown when clicking outside
            $(document).click(function(e) {
                if (!$(e.target).closest('.dropdown').length) {
                    $('.dropdown-menu').removeClass('show');
                }
            });
        });
    </script>
    


</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rentak')</title>
    @vite('resources/css/app.css')
    @vite('resources/css/homepage.css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- FullCalendar CSS from CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <header class="bg-white shadow">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center py-3">
                <a href="{{ url('/') }}" class="h4 text-decoration-none text-dark">RENTAK</a>

                <nav>
                    
                    
                    @guest
                        <a href="#about-us" class="mr-3">About Us</a>
                        <a href="#how-it-works" class="mr-3">How it works</a>
                        <a href="#links-apps" class="mr-3">Link Apps</a>
                        <a href="#dashboard" class="mr-3">Dashboard</a>
                        <a href="{{ route('login') }}" class="btn btn-dark">Login</a>
                        {{-- <a href="{{ route('register') }}" class="btn btn-dark">Sign Up</a> --}}
                        
                    @else
                        <a href="{{ route(auth()->user()->is_admin ? 'admin.tasks.index' : 'user.tasks.index') }}" class="mr-3">Tasks</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a href="{{ route('logout') }}"
                           class="mr-3"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
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

    @vite('resources/js/app.js')
    @vite('resources/js/homepage.js')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- FullCalendar JS from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.js"></script>
    <script src="{{ asset('js/date.js') }}"></script>
    <script src="{{ asset('js/columns-toggle.js') }}"></script>



</body>
</html>

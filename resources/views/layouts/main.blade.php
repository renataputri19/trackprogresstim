<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Business Tracker')</title>
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
                <div class="h4">i8</div>
                <nav>
                    <a href="#" class="mr-3">About Us</a>
                    <a href="#" class="mr-3">How it works</a>
                    <a href="#" class="mr-3">Pricing</a>
                    <a href="#" class="mr-3">FAQs</a>
                    <a href="{{ route('login') }}" class="mr-3">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-dark">Sign Up</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="container py-5">
        @yield('content')
    </main>

    @vite('resources/js/app.js')
    @vite('resources/js/homepage.js')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- FullCalendar JS from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.js"></script>
</body>
</html>

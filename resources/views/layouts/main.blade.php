<!-- resources/views/layouts/main.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Business Tracker')</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include FullCalendar CSS and JS -->
    <link href="{{ mix('css/fullcalendar.css') }}" rel="stylesheet">
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

    <script src="{{ mix('js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ mix('js/fullcalendar.js') }}"></script>
</body>
</html>

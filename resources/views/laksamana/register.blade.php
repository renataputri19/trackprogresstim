<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Laksamana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0f766e;
            --primary-light: #14b8a6;
            --primary-dark: #115e59;
            --secondary-color: #0d9488;
        }
        html, body { width: 100%; max-width: 100%; overflow-x: hidden; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f0fdfa 0%, #f8fafc 100%);
            min-height: 100vh;
        }
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(15, 118, 110, 0.3);
            border-bottom: 3px solid var(--primary-light);
        }
        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: calc(100% - 140px);
            min-width: 0;
        }
        @media (min-width: 768px) { .navbar-brand { font-size: 1.25rem; } }
        .card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; }
        .card-header { background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-bottom: 2px solid #e5e7eb; padding: 1.25rem; border-radius: 16px 16px 0 0 !important; }
        .card-title { color: var(--primary-color); font-weight: 600; margin: 0; }
        .btn-primary { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); border: none; box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3); font-weight: 600; border-radius: 10px; transition: all 0.3s ease; }
        .btn-primary:hover { background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%); transform: translateY(-2px); box-shadow: 0 6px 16px rgba(15, 118, 110, 0.4); }
        .btn { border-radius: 10px; font-weight: 600; transition: all 0.3s ease; }
        .btn:hover { transform: translateY(-2px); }
        /* Auth actions: stack vertically on small screens */
        .auth-actions { gap: 0.5rem; }
        @media (max-width: 575.98px) {
            .auth-actions { flex-direction: column; align-items: stretch !important; }
            .auth-actions .btn { width: 100%; justify-content: center; }
        }
    </style>
    </head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('laksamana.index') }}">
                <i class="fas fa-map-marked-alt me-2"></i>LAKSAMANA - Lokasi dan Klasifikasi Sensus Manajemen Usaha
            </a>
            <a href="{{ route('laksamana.index') }}" class="btn btn-light">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </nav>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Pendaftaran Akun (Laksamana)</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Mendaftar memungkinkan kontribusi Anda muncul pada <strong>peringkat kontributor</strong>. </p>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('laksamana.register.post') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                            <div class="auth-actions d-flex justify-content-between align-items-center">
                                <a href="{{ route('laksamana.index') }}" class="btn btn-light">Kembali ke Laksamana</a>
                                <button type="submit" class="btn btn-primary">Daftar</button>
                            </div>
                        </form>
                        <hr>
                        <p class="mb-0">Sudah punya akun? <a href="{{ route('laksamana.login') }}">Masuk di sini</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
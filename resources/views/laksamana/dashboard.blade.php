<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengguna Laksamana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0f766e;
            --primary-light: #14b8a6;
            --primary-dark: #115e59;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f0fdfa 0%, #f8fafc 100%);
            min-height: 100vh;
        }
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 0.75rem 0;
            box-shadow: 0 4px 20px rgba(15, 118, 110, 0.25);
            border-bottom: 3px solid var(--primary-light);
        }
        .card-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-bottom: 2px solid #e5e7eb;
        }
        .card-title, .card-header h6 { color: var(--primary-color); font-weight: 600; }
        .table thead th { font-weight: 600; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('laksamana.index') }}">
            <i class="fas fa-map-marked-alt me-2"></i>LAKSAMANA
        </a>
        <a href="{{ route('laksamana.index') }}" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>
</nav>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Dashboard Pengguna</h5>
        <a href="{{ route('laksamana.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Kembali ke Laksamana</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card stat-card hover-lift">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-database text-primary me-2"></i>
                        <div>
                            <div class="small text-muted">Total entri saya</div>
                            <div class="fw-bold">{{ $stats['total_my_entries'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card hover-lift">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-lock text-success me-2"></i>
                        <div>
                            <div class="small text-muted">Final (aktif/tutup)</div>
                            <div class="fw-bold">{{ $stats['finalized'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card hover-lift">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-pencil-alt text-warning me-2"></i>
                        <div>
                            <div class="small text-muted">Masih dapat diedit</div>
                            <div class="fw-bold">{{ $stats['editable'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header py-2 d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="fas fa-briefcase me-2"></i>Data Usaha yang Pernah Saya Submit</h6>
            <form method="GET" class="page-toolbar">
                <label class="small text-muted me-1">Per halaman</label>
                <input type="hidden" name="page" value="1">
                <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                    @php($opts = [10,25,50,100])
                    @foreach($opts as $opt)
                        <option value="{{ $opt }}" {{ (int)($perPage ?? 20) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover table-modern mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Nama Usaha</th>
                        <th>Kecamatan</th>
                        <th>Kelurahan</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($businesses as $biz)
                        <tr>
                            <td class="text-wrap">{{ $biz->nama_usaha }}</td>
                            <td>{{ $biz->kecamatan }}</td>
                            <td>{{ $biz->kelurahan }}</td>
                            <td>
                                @if($biz->status === 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($biz->status === 'tutup')
                                    <span class="badge bg-danger">Tutup</span>
                                @else
                                    <span class="badge bg-secondary">Belum final</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada entri.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-2 d-flex justify-content-between align-items-center">
                <div class="small text-muted">Menampilkan {{ $businesses->firstItem() ?? 0 }}–{{ $businesses->lastItem() ?? 0 }} dari {{ $businesses->total() }} entri</div>
                {{ $businesses->links() }}
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
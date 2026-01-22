<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard Laksamana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
        .card-header { background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-bottom: 2px solid #e5e7eb; padding: 0.75rem 1rem; border-radius: 16px 16px 0 0 !important; }
        .card-title, .card-header h6 { color: var(--primary-color); font-weight: 600; margin: 0; }
        .btn { border-radius: 10px; font-weight: 600; transition: all 0.3s ease; }
        .btn:hover { transform: translateY(-2px); }
        .badge.bg-primary { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); }
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
    <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-start align-items-md-center mb-3 gap-2">
        <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Leaderboard & Statistik Publik</h5>
        <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2 mt-2 mt-md-0 w-100 w-md-auto justify-content-md-end">
            <form method="GET" class="page-toolbar w-100 w-sm-auto">
                <label class="small text-muted me-1">Per halaman</label>
                <input type="hidden" name="users_page" value="1">
                <input type="hidden" name="breakdown_page" value="1">
                <select name="per_page" class="form-select form-select-sm w-100 w-sm-auto" onchange="this.form.submit()">
                    @php($opts = [10,25,50,100])
                    @foreach($opts as $opt)
                        <option value="{{ $opt }}" {{ (int)($perPage ?? 20) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('laksamana.index') }}" class="btn btn-outline-secondary btn-sm w-100 w-sm-auto"><i class="fas fa-arrow-left me-1"></i>Kembali ke Laksamana</a>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="small text-muted">Total usaha</div>
                    <div class="fw-bold">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="small text-muted">Sudah ditandai</div>
                    <div class="fw-bold">{{ $stats['tagged'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="small text-muted">Aktif</div>
                    <div class="fw-bold">{{ $stats['aktif'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="small text-muted">Tutup</div>
                    <div class="fw-bold">{{ $stats['tutup'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header py-2"><h6 class="mb-0"><i class="fas fa-medal me-2"></i>Top Kontributor</h6></div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($leaderboard as $row)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $row['user']->name ?? 'Anonim' }}</div>
                                    <div class="small text-muted">{{ $row['user']->email ?? '—' }}</div>
                                </div>
                                <span class="badge bg-primary">{{ $row['count'] }}</span>
                            </div>
                        @empty
                            <div class="list-group-item text-muted">Belum ada data.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header py-2"><h6 class="mb-0"><i class="fas fa-users me-2"></i>Semua Pengguna & Jumlah Tag</h6></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-modern mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($usersWithCounts as $row)
                                <tr>
                                    <td>{{ $row['user']->name ?? 'Anonim' }}</td>
                                    <td class="text-muted">{{ $row['user']->email ?? '—' }}</td>
                                    <td class="text-end"><span class="badge bg-secondary">{{ $row['count'] }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada data.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-2 d-flex justify-content-between align-items-center">
                        <div class="small text-muted">Menampilkan {{ $usersWithCounts->firstItem() ?? 0 }}–{{ $usersWithCounts->lastItem() ?? 0 }} dari {{ $usersWithCounts->total() }} pengguna</div>
                        {{ $usersWithCounts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header py-2"><h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Rincian Tag per Pengguna (Bisnis Unik & Status)</h6></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-modern mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th class="text-end">Bisnis Unik</th>
                                <th class="text-end">Aktif</th>
                                <th class="text-end">Tutup</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($userBreakdown as $row)
                                <tr>
                                    <td>{{ $row['user']->name ?? 'Anonim' }}</td>
                                    <td class="text-muted">{{ $row['user']->email ?? '—' }}</td>
                                    <td class="text-end"><span class="badge bg-primary">{{ $row['businesses'] }}</span></td>
                                    <td class="text-end"><span class="badge bg-success">{{ $row['aktif'] }}</span></td>
                                    <td class="text-end"><span class="badge bg-danger">{{ $row['tutup'] }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada data.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-2 d-flex justify-content-between align-items-center">
                        <div class="small text-muted">Menampilkan {{ $userBreakdown->firstItem() ?? 0 }}–{{ $userBreakdown->lastItem() ?? 0 }} dari {{ $userBreakdown->total() }} pengguna</div>
                        {{ $userBreakdown->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
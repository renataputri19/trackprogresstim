@extends('layouts.main')

@section('title', 'Welcome')

@section('content')

    <div class="container">
        <h2 class="my-4 text-center">Welcome to Rentak, {{ Auth::user()->name }}!</h2>
        
        <div class="row mb-4">
            <div class="col text-center">
                <p class="lead">Empowering you to achieve more with streamlined task management and insightful dashboards.</p>
            </div>
        </div>

        <div class="row">
            <!-- Company Overview Dashboard -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-chart-line fa-2x text-primary"></i></h5>
                        <h5 class="card-title mt-3">Company Dashboard</h5>
                        <p class="card-text">View and analyze the overall progress of all teams and projects.</p>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Go to Company Dashboard</a>
                    </div>
                </div>
            </div>
        
            <!-- User-Specific Dashboard -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-user fa-2x text-success"></i></h5>
                        <h5 class="card-title mt-3">My Dashboard</h5>
                        <p class="card-text">Monitor your personal tasks and track your individual progress.</p>
                        <a href="{{ route('user.dashboard') }}" class="btn btn-success">Go to My Dashboard</a>
                    </div>
                </div>
            </div>
        
            <!-- Task Management -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-tasks fa-2x text-warning"></i></h5>
                        <h5 class="card-title mt-3">Task Management</h5>
                        <p class="card-text">Create, assign, and manage tasks efficiently to ensure timely completion.</p>
                        @if(Auth::user()->is_admin)
                            <a href="{{ route('admin.tasks.index') }}" class="btn btn-warning">Manage Tasks</a>
                        @else
                            <a href="{{ route('user.tasks.index') }}" class="btn btn-warning">View My Tasks</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        

        

        <div id="links-apps" class="bg-white shadow p-5 rounded-lg d-flex align-items-center mt-5 mb-5">
            <div class="container">
                <h1>Link dan Aplikasi</h1>
                
                <div class="helpful-links">
                    <h2>Helpful Links</h2>
                    <div class="link-grid">
                        <a href="https://harian2171.bpskepri.com" class="link-item">
                            <div class="link-icon"><i class="fas fa-chart-line"></i></div>
                            <div class="link-title">Laporan harian Provinsi</div>
                        </a>
                        <a href="https://s.id/link_bps" class="link-item">
                            <div class="link-icon"><i class="fas fa-link"></i></div>
                            <div class="link-title">Link all aplikasi BPS</div>
                        </a>
                        <a href="https://s.id/monumen" class="link-item">
                            <div class="link-icon"><i class="fas fa-monument"></i></div>
                            <div class="link-title">MONUMEN 2171</div>
                        </a>
                    </div>
                </div>
            
                <div class="our-apps">
                    <h2>Our Apps</h2>
                    <div class="app-grid">
                        <a href="https://monita.bpsbatam.com/" class="app-item">
                            <div class="app-icon"><i class="fas fa-tools"></i></div>
                            <div class="app-title">Statistik Sektoral</div>
                        </a>
                        {{-- <a href="https://rb.bpsbatam.com/" class="app-item">
                            <div class="app-icon"><i class="fas fa-balance-scale"></i></div>
                            <div class="app-title">Reformasi Birokrasi</div>
                        </a> --}}
                        <!-- New Link for Generate Text feature -->
                        <a href="{{ route('generate.form') }}" class="app-item">
                            <div class="app-icon"><i class="fas fa-file-alt"></i></div>
                            <div class="app-title">Generate Text</div>
                        </a>
                        <!-- New Link for Padamu Negri -->
                        <a href="{{ url('/padamunegri') }}" class="app-item">
                            <div class="app-icon"><i class="fas fa-university"></i></div>
                            <div class="app-title">RB Padamu Negri</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col text-center">
                <blockquote class="blockquote">
                    <p class="mb-0">"Success is not the key to happiness. Happiness is the key to success. If you love what you are doing, you will be successful." - Albert Schweitzer</p>
                </blockquote>
            </div>
        </div>
    </div>













 


@endsection

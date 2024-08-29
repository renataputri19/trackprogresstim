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

        {{-- <div class="row text-center mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-tasks"></i> Total Tasks</h5>
                        <p class="card-text display-4">{{ 2 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-check-circle"></i> Completed Tasks</h5>
                        <p class="card-text display-4">{{  2 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-bell"></i> Upcoming Deadlines</h5>
                        <p class="card-text display-4">{{  2 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-chart-line"></i> Progress</h5>
                        <p class="card-text display-4">{{  2 }}%</p>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-chart-pie"></i> Dashboard</h5>
                        <p class="card-text">View task progress and details.</p>
                        <a href="{{ route(auth()->user()->is_admin ? 'admin.dashboard' : 'user.dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-tasks"></i> Task Management</h5>
                        <p class="card-text">Manage and assign tasks.</p>
                        <a href="{{ route(auth()->user()->is_admin ? 'admin.tasks.index' : 'user.tasks.index') }}" class="btn btn-primary">Go to Tasks</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-link"></i> Other Apps & Links</h5>
                        <p class="card-text">Access other applications and resources.</p>
                        <a href="#links-apps" class="btn btn-primary">View Links</a>
                    </div>
                </div>
            </div>
        </div>
        
        

        <div id="links-apps" class="bg-white shadow p-5 rounded-lg d-flex align-items-center mt-3 mb-5">
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
                        <a href="https://rb.bpsbatam.com/" class="app-item">
                            <div class="app-icon"><i class="fas fa-balance-scale"></i></div>
                            <div class="app-title">Reformasi Birokrasi</div>
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













    <div class="container">
        <h2 class="my-4">Welcome to Rentak, {{ Auth::user()->name }}!</h2>
        {{-- <p>Use the navigation above to access your tasks, dashboards, and more.</p> --}}


        <div class="row">
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Dashboard</h5>
                        <p class="card-text">View task progress and details.</p>
                        <a href="{{ route(auth()->user()->is_admin ? 'admin.dashboard' : 'user.dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Task Management</h5>
                        <p class="card-text">Manage and assign tasks.</p>
                        @if(Auth::user()->is_admin)
                            <a href="{{ route('admin.tasks.index') }}" class="btn btn-primary">Go to Tasks</a>
                        @else
                            <a href="{{ route('user.tasks.index') }}" class="btn btn-primary">Go to Tasks</a>
                        @endif
                        {{-- <a href="{{ route('admin.tasks.index') }}" class="btn btn-primary">Go to Tasks</a> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Other Apps & Links</h5>
                        <p class="card-text">Access other applications and resources.</p>
                        <a href="#links" class="btn btn-primary">View Links</a>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Include additional links and apps here -->
        {{-- <div class="mt-5">
            <h3>Our Apps and Links</h3>
            <ul>
                <li><a href="https://link-to-other-app.com">Other App 1</a></li>
                <li><a href="https://link-to-another-app.com">Other App 2</a></li>
                <!-- Add more links as needed -->
            </ul>
        </div> --}}
    </div>

    <div id="links-apps" class="bg-white shadow p-5 rounded-lg d-flex align-items-center mb-5">
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
                    <a href="https://rb.bpsbatam.com/" class="app-item">
                        <div class="app-icon"><i class="fas fa-balance-scale"></i></div>
                        <div class="app-title">Reformasi Birokrasi</div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="dashboard" class="bg-white shadow p-5 rounded-lg d-flex align-items-center mb-5" id="dashboard">
        <div class="container">
            <h1>Dashboard Monitoring Kinerja</h1>
        
            <!-- TIM Filter -->
            <div class="form-group">
                <label for="tim-filter">Filter by TIM</label>
                <select class="form-control" id="tim-filter">
                    <option value="">All TIM</option>
                    <option value="SUBBAGIAN UMUM">SUBBAGIAN UMUM</option>
                    <option value="TIM SOSIAL">TIM SOSIAL</option>
                    <option value="TIM PRODUKSI">TIM PRODUKSI</option>
                    <option value="TIM DISTRIBUSI">TIM DISTRIBUSI</option>
                    <option value="TIM NERWILIS">TIM NERWILIS</option>
                    <option value="TIM PENGOLAHAN DAN IT">TIM PENGOLAHAN DAN IT</option>
                </select>
            </div>
        
            <div id="calendar-home"></div>
        </div>    
    </div>



@endsection

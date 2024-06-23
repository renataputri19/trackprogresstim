{{-- @extends('layouts.app') --}}

{{-- @section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}

<!-- resources/views/home.blade.php -->

@extends('layouts.main')

@section('title', 'Business Tracker - Home')

@section('content')
    <div id="about-us" class="bg-white shadow p-5 rounded-lg d-flex align-items-center mb-5">
        <div class="w-50">
            <h1 class="display-4 font-weight-bold">RENTAK - Integrasi Sistem Data dan Proses Kerja di BPS Batam</h1>
            <p class="lead">Sistem RENTAK (Reformasi dan Integrasi Kinerja) dirancang untuk mengoptimalkan kinerja dan mendukung tugas BPS Batam melalui integrasi sistem data dan proses kerja.</p>
            <a href="#dashboard" class="btn btn-dark my-4">Lihat Dashboard</a>
            {{-- <div class="d-flex align-items-center">
                <a href="harian2171.bpskepri.com" class="btn btn-link mr-2">Laporan Harian</a>
                <a href="https://s.id/link_bps" class="btn btn-link mr-2">Link All Aplikasi BPS</a>
                <a href="https://s.id/monumen" class="btn btn-link">MONUMEN 2171</a>
            </div> --}}
        </div>
        <div class="w-50">
            <img src="{{ asset('img/asset-illus.jpg') }}" alt="Ilustrasi" class="img-fluid">
        </div>
    </div>

    <div id="how-it-works" class="bg-white shadow p-5 rounded-lg d-flex align-items-center mb-5">
        <div class="w-50">
            <img src="{{ asset('img/asset-illus2.jpg') }}" alt="Ilustrasi" class="img-fluid">
        </div>
        <div class="w-50">
            <h2 class="h4 font-weight-bold">Meningkatkan Efisiensi dan Transparansi di BPS Batam</h2>
            <p class="lead">Proyek RENTAK bertujuan untuk mengintegrasikan pengelolaan reformasi birokrasi, EPSS, statistik sektoral, dan monitoring kinerja pegawai di BPS Batam. Sistem ini dirancang untuk mempermudah koordinasi antar unit kerja dan memastikan transparansi data.</p>
            <ul class="list-unstyled">
                <li class="mb-2">✔️ Integrasi Sistem Data dan Proses Kerja</li>
                <li class="mb-2">✔️ Monitoring Kinerja Real-Time</li>
                <li class="mb-2">✔️ Pelatihan Berkelanjutan untuk Pegawai</li>
                <li class="mb-2">✔️ Prototipe UI/UX yang User-Friendly</li>
                <li class="mb-2">✔️ Efisiensi dan Akuntabilitas yang Lebih Tinggi</li>
            </ul>
        </div>
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





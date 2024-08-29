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

@section('title', 'RENTAK')

@section('content')
    <div id="about-us" class="bg-white shadow p-5 rounded-lg d-flex align-items-center mb-5">
        <div class="w-50">
            <h1 class="display-4 font-weight-bold">RENTAK - Integrasi Sistem Data dan Proses Kerja di BPS Batam</h1>
            <p class="lead">Sistem RENTAK (Reformasi dan Integrasi Kinerja) dirancang untuk mengoptimalkan kinerja dan mendukung tugas BPS Batam melalui integrasi sistem data dan proses kerja.</p>
            <a href="{{ route('login') }}" class="btn btn-dark my-4">Login</a>
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



    

    




@endsection





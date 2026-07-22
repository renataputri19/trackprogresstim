@extends('new-homepage.layouts.app')

@section('title', 'Sistem Validasi BAHTERA - RENTAK')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/new-homepage/bahtera.css') }}">
@endsection

@section('content')
<div class="bh-page">
    <!-- Hero -->
    <div class="bh-hero py-16">
        <div class="relative container mx-auto px-4 text-center">
            <h1 class="rk-display text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Sistem Validasi BAHTERA</h1>
            <p class="mx-auto mt-5 max-w-3xl text-xl font-semibold text-teal-50">Batam Harmonisasi dan Evaluasi Terpadu Rekaman Akomodasi</p>
            <p class="mx-auto mt-3 max-w-2xl text-base text-teal-100/90">Survei Jasa Akomodasi Bulanan (VHTS) di Kota Batam untuk memperoleh data indikator perkembangan usaha akomodasi.</p>
        </div>
    </div>

    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <!-- About -->
        <div class="rk-card overflow-hidden">
            <div class="rk-card-head">
                <h2 class="rk-display text-xl font-bold text-[color:var(--text-strong)]">Tentang Sistem BAHTERA</h2>
                <p class="mt-1 text-sm text-[color:var(--text-muted)]">Batam Harmonisasi dan Evaluasi Terpadu Rekaman Akomodasi</p>
            </div>
            <div class="p-6">
                <p class="leading-relaxed text-[color:var(--text)]">
                    Survei Jasa Akomodasi Bulanan (VHTS) di Kota Batam bertujuan untuk memperoleh data berbagai indikator perkembangan usaha akomodasi, seperti:
                </p>
                <ul class="mt-4 grid gap-2.5 sm:grid-cols-2">
                    @foreach ([
                        'Tingkat penghunian kamar hotel (TPK)',
                        'Jumlah malam kamar yang terpakai',
                        'Jumlah tamu yang datang dan menginap',
                        'Rata-rata lama menginap tamu',
                        'Jumlah malam tamu menginap',
                    ] as $item)
                    <li class="flex items-start gap-2.5 text-sm text-[color:var(--text)]">
                        <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-brand-600 dark:text-brand-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <p class="mt-5 leading-relaxed text-[color:var(--text-muted)]">
                    Survei dilaksanakan setiap bulan, mencakup akomodasi berbintang (sensus) dan nonbintang (sampling). Indikatornya didiseminasikan setiap bulan melalui Berita Resmi Statistik (BRS).
                </p>
            </div>
        </div>

        <!-- Background -->
        <div class="rk-card mt-6 overflow-hidden">
            <div class="rk-card-head">
                <h2 class="rk-display text-xl font-bold text-[color:var(--text-strong)]">Latar Belakang</h2>
                <p class="mt-1 text-sm text-[color:var(--text-muted)]">Inovasi dalam sistem validasi data akomodasi</p>
            </div>
            <div class="p-6">
                <p class="leading-relaxed text-[color:var(--text)]">
                    Sistem BAHTERA dikembangkan sebagai bagian dari upaya integrasi dengan sistem FASIH (Framework Aplikasi Statistik Inovatif dan Harmonis) untuk meningkatkan efisiensi dan akurasi dalam proses validasi data survei akomodasi.
                </p>
            </div>
        </div>

        <!-- Objectives -->
        <div class="mt-10">
            <span class="rk-eyebrow">Tujuan</span>
            <h2 class="rk-display mt-2 text-2xl font-bold text-[color:var(--text-strong)]">Tujuan Utama Sistem</h2>
            <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ([
                    ['n' => '1', 'a' => '#0d9488', 't' => 'Efisiensi Validasi', 'd' => 'Meningkatkan efisiensi proses validasi data survei akomodasi dengan otomatisasi yang cerdas.'],
                    ['n' => '2', 'a' => '#10b981', 't' => 'Akurasi Data', 'd' => 'Memastikan akurasi dan konsistensi data melalui algoritma validasi yang komprehensif.'],
                    ['n' => '3', 'a' => '#8b5cf6', 't' => 'Integrasi Sistem', 'd' => 'Mengintegrasikan proses validasi dengan sistem FASIH untuk harmonisasi data statistik.'],
                ] as $obj)
                <div class="rk-card p-5" style="--tile-accent: {{ $obj['a'] }}">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg text-sm font-bold text-white" style="background: {{ $obj['a'] }}">{{ $obj['n'] }}</span>
                        <h3 class="font-semibold text-[color:var(--text-strong)]">{{ $obj['t'] }}</h3>
                    </div>
                    <p class="mt-2.5 text-sm text-[color:var(--text-muted)]">{{ $obj['d'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Version Selection -->
        <div class="mt-12">
            <span class="rk-eyebrow">Mulai Validasi</span>
            <h2 class="rk-display mt-2 text-2xl font-bold text-[color:var(--text-strong)]">Pilih Versi BAHTERA</h2>
            <p class="mt-1 text-sm text-[color:var(--text-muted)]">Pilih versi sistem validasi yang ingin Anda gunakan.</p>

            <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- V1 -->
                <div class="bh-version" style="--a:#3b82f6">
                    <div class="flex items-start gap-4">
                        <span class="bh-version-ic">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>
                        </span>
                        <div>
                            <h3 class="bh-version-tt">BAHTERA Version 1</h3>
                            <p class="bh-version-sub">Versi standar sistem validasi</p>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2">
                        @foreach (['Validasi data survei hotel standar', 'Import data dari Excel', 'Laporan hasil validasi'] as $f)
                        <div class="bh-version-li"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg><span>{{ $f }}</span></div>
                        @endforeach
                    </div>
                    <a href="{{ route('bahtera.v1.index') }}" class="bh-version-btn">Gunakan Version 1
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- V2 -->
                <div class="bh-version" style="--a:#10b981">
                    <div class="flex items-start gap-4">
                        <span class="bh-version-ic">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/><circle cx="12" cy="12" r="1"/></svg>
                        </span>
                        <div>
                            <h3 class="bh-version-tt">BAHTERA Version 2</h3>
                            <p class="bh-version-sub">Enhanced dengan fitur tambahan</p>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2">
                        @foreach (['Validasi dengan parameter tambahan', 'Konfigurasi bulan dan tahun', 'Reference TPK yang dapat disesuaikan', 'Analisis data yang lebih detail'] as $f)
                        <div class="bh-version-li"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg><span>{{ $f }}</span></div>
                        @endforeach
                    </div>
                    <a href="{{ route('bahtera.v2.index') }}" class="bh-version-btn">Gunakan Version 2
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- V3 -->
                <div class="bh-version" style="--a:#8b5cf6">
                    <div class="flex items-start gap-4">
                        <span class="bh-version-ic">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="8" r="1"/></svg>
                        </span>
                        <div>
                            <h3 class="bh-version-tt">BAHTERA Version 3</h3>
                            <p class="bh-version-sub">Normalisasi indikator (RLMA, RLMNus, GPR)</p>
                            <span class="bh-chip-new">✨ Terbaru</span>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2">
                        @foreach (['Validasi alur tamu asing yang konsisten', 'Sistem prioritas validasi yang cerdas', 'Enhanced guest flow logic', 'Normalisasi indikator bulanan'] as $f)
                        <div class="bh-version-li"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg><span>{{ $f }}</span></div>
                        @endforeach
                    </div>
                    <a href="{{ route('bahtera.v3.index') }}" class="bh-version-btn">Gunakan Version 3
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Info callout -->
            <div class="mt-8 flex items-start gap-3 rounded-xl border border-[color:var(--border)] bg-[color:var(--surface-muted)] p-5">
                <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-brand-500/15 text-brand-600 dark:text-brand-300">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                </span>
                <div class="text-sm text-[color:var(--text-muted)]">
                    <p class="font-semibold text-[color:var(--text-strong)]">Informasi Penting</p>
                    <p class="mt-1">• <b class="text-[color:var(--text)]">V1</b>: Validasi standar · <b class="text-[color:var(--text)]">V2</b>: Analisis mendalam · <b class="text-[color:var(--text)]">V3</b>: Normalisasi indikator (gunakan sebelum V1/V2).</p>
                    <p class="mt-1">• Pastikan data Excel sudah dalam format yang sesuai sebelum melakukan validasi.</p>
                    <p class="mt-1">• Untuk bantuan teknis, hubungi tim IT melalui Halo IP.</p>
                </div>
            </div>
        </div>

        <!-- Back -->
        <div class="mt-10 text-center">
            <a href="{{ route('welcome') }}" class="rk-btn rk-btn-ghost">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection

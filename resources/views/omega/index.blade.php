@extends('new-homepage.layouts.app')

@section('title', 'OMEGA — Outstanding Member of Great ASN')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/new-homepage/omega.css') }}">
@endsection

@section('content')
<main class="omega">

    {{-- ============================ HERO ============================ --}}
    @php
        $active = collect($timeline)->firstWhere('status', 'open');
        $next = collect($timeline)->firstWhere('q', ($active['q'] ?? 0) + 1);
        $closesLabel = $next['opens'] ?? 'April';
    @endphp
    <section class="omega-hero">
        <div class="container mx-auto px-4 py-14 md:py-16">
            <div class="omega-hero-grid">
                <div class="omega-hero-main omega-reveal">
                    <p class="omega-kicker">
                        <span>BPS Kota Batam</span>
                        <span class="omega-kicker-sep">&bull;</span>
                        <span>Tahap 1 — Penjaringan Awal</span>
                    </p>

                    <div class="omega-lockup mt-6">
                        <h1 class="omega-name">OMEGA</h1>
                        <p class="omega-expand">Outstanding Member of Great&nbsp;ASN</p>
                    </div>

                    <p class="omega-lede mt-6">
                        Pemilihan Pegawai Terbaik Triwulanan BPS Kota Batam — sebuah apresiasi bagi
                        rekan kerja dengan kinerja dan kontribusi terbaik.
                    </p>

                    <div class="omega-hero-actions mt-8">
                        <a href="#vote" class="omega-hero-cta">
                            Mulai Memilih
                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                        </a>
                        <a href="#tahapan" class="omega-hero-ghost">Pelajari Tahapan</a>
                    </div>
                </div>

                <aside class="omega-status omega-reveal" data-delay="1">
                    <div class="omega-status-head">
                        <span class="omega-status-label">Periode Aktif</span>
                        <span class="omega-status-emblem" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                        </span>
                    </div>
                    <p class="omega-status-period">{{ $periodLabel }}</p>
                    <p class="omega-status-months">{{ $active['months'] ?? '' }}</p>
                    <div class="omega-status-divider"></div>
                    <div class="omega-status-note">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>
                        Ditutup sebelum {{ $closesLabel }}
                    </div>
                </aside>
            </div>

            {{-- Quarter timeline --}}
            <div class="omega-reveal mt-10 md:mt-12" data-delay="2">
                <div class="omega-timeline-card">
                    <div class="omega-timeline-head">
                        <span class="omega-timeline-title">Jadwal Pemilihan {{ $timeline[0]['year'] }}</span>
                        <span class="omega-timeline-note">Terkunci otomatis mengikuti kalender</span>
                    </div>
                    <ol class="omega-timeline">
                        @foreach ($timeline as $tw)
                            <li class="omega-tl-node is-{{ $tw['status'] }}">
                                <div class="omega-tl-dot">
                                    @if ($tw['status'] === 'closed')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    @elseif ($tw['status'] === 'open')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="currentColor" stroke="none"><circle cx="12" cy="12" r="6"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    @endif
                                </div>
                                <div class="omega-tl-meta">
                                    <span class="omega-tl-title">{{ $tw['label'] }}</span>
                                    <span class="omega-tl-months">{{ $tw['months'] }}</span>
                                    <span class="omega-tl-status">
                                        @if ($tw['status'] === 'closed') Ditutup
                                        @elseif ($tw['status'] === 'open') Sedang berlangsung
                                        @else Dibuka {{ $tw['opens'] }}
                                        @endif
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================ VOTE ============================ --}}
    <section id="vote" class="omega-section bg-gradient-to-b from-white via-teal-50/40 to-white">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl">

                {{-- Flash / errors --}}
                @if (session('omega_success'))
                    <div class="omega-alert omega-alert--success mb-6 omega-reveal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 flex-shrink-0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                        <span>{{ session('omega_success') }}</span>
                    </div>
                @endif
                @if (session('omega_info'))
                    <div class="omega-alert omega-alert--info mb-6 omega-reveal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 flex-shrink-0"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                        <span>{{ session('omega_info') }}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="omega-alert omega-alert--warn mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 flex-shrink-0"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                        <div>
                            <p class="font-semibold">Mohon periksa kembali pilihan Anda:</p>
                            <ul class="mt-1 list-inside list-disc space-y-0.5">
                                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if ($hasVoted)
                    {{-- ---------------- Already voted summary ---------------- --}}
                    <div class="omega-thanks p-8 sm:p-10 omega-reveal" id="omega-thanks">
                        <div class="relative">
                            <div class="omega-thanks-check">
                                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </div>
                            <h2 class="mt-5 text-2xl font-bold text-white">Suara Anda sudah terekam</h2>
                            <p class="mt-2 text-emerald-100/80">
                                Terima kasih, <strong class="text-white">{{ $votedAs }}</strong>.
                                Berikut pilihan Anda untuk {{ $periodLabel }}:
                            </p>
                            <div class="mt-6 grid gap-3">
                                @foreach ($myVotes as $team => $candidate)
                                    <div class="omega-summary-row">
                                        <span class="omega-summary-team">{{ $team }}</span>
                                        <span class="omega-summary-name">{{ $candidate }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <p class="mt-6 text-sm text-emerald-100/70">
                                Apresiasi dimulai dari langkah sederhana. Sampai jumpa di triwulan berikutnya!
                            </p>
                        </div>
                    </div>
                @else
                    {{-- ---------------- Voting wizard ---------------- --}}
                    <div class="mb-8 text-center omega-reveal">
                        <span class="omega-eyebrow">Kartu Suara · {{ $periodLabel }}</span>
                        <h2 class="omega-h2 mt-2">Pilih Pegawai Terbaik di Tim Anda</h2>
                        <p class="mt-3 text-slate-500">Satu rekan terbaik untuk setiap tim yang Anda ikuti — cukup ikuti langkahnya.</p>
                    </div>

                    <form method="POST" action="{{ route('omega.vote') }}" id="omega-form" class="omega-wizard omega-reveal" data-delay="1">
                        @csrf

                        {{-- Wizard top bar: identity chip + progress --}}
                        <div class="omega-wizard-top">
                            <div class="omega-chip" id="omega-chip" hidden>
                                <span class="omega-chip-av" id="omega-chip-av"></span>
                                <span class="omega-chip-text">
                                    <span class="omega-chip-name" id="omega-chip-name"></span>
                                    <span class="omega-chip-sub">Pemilih</span>
                                </span>
                                @unless ($lockedIdentity)
                                    <button type="button" class="omega-chip-change" id="omega-change">Ganti</button>
                                @endunless
                            </div>
                            <div class="omega-wizard-progress" id="omega-progress-wrap" hidden>
                                <div class="omega-progress-track"><div class="omega-progress-fill" id="omega-progress"></div></div>
                                <span class="omega-progress-label"><b id="omega-cur">0</b> / <span id="omega-total">0</span> tim</span>
                            </div>
                        </div>

                        {{-- Step 0: identity --}}
                        <div class="omega-panel is-active" id="omega-identity-step" data-step="identity">
                            <div class="omega-panel-head">
                                <span class="omega-panel-kicker">Langkah 1</span>
                                <h3 class="omega-panel-team">Konfirmasi Identitas</h3>
                                <p class="omega-panel-hint">Kami perlu tahu siapa Anda untuk menampilkan tim tempat Anda tergabung.</p>
                            </div>

                            @if ($lockedIdentity)
                                <div class="omega-identity-verified">
                                    <span class="omega-chip-av">{{ \Illuminate\Support\Str::of($lockedIdentity)->explode(' ')->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('') }}</span>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $lockedIdentity }}</p>
                                        <p class="text-xs font-medium text-teal-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:-2px;margin-right:2px"><path d="M20 6 9 17l-5-5"/></svg>
                                            Terverifikasi dari akun Anda
                                        </p>
                                    </div>
                                </div>
                            @else
                                <label for="omega-identity" class="mb-2 block text-sm font-semibold text-slate-700">Siapa Anda?</label>
                                <select name="voter_name" id="omega-identity" class="omega-identity-select">
                                    <option value="" disabled {{ old('voter_name') ? '' : 'selected' }}>— Pilih nama Anda —</option>
                                    @foreach ($memberNames as $name)
                                        <option value="{{ $name }}" @selected(old('voter_name') === $name)>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-xs text-slate-500">Nama Anda belum otomatis terdeteksi dari akun — pilih dari daftar di atas.</p>
                            @endif

                            <div class="mt-6 flex justify-end">
                                <button type="button" class="omega-btn" id="omega-start" @unless ($lockedIdentity) disabled @endunless>
                                    Mulai Memilih
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Team panels + review (built by JS) --}}
                        <div id="omega-wizard-body"></div>

                        {{-- Nav --}}
                        <div class="omega-nav" id="omega-nav" hidden>
                            <button type="button" class="omega-btn-ghost" id="omega-back">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                Kembali
                            </button>
                            <button type="button" class="omega-btn" id="omega-next">
                                Lanjut
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                            </button>
                            <button type="submit" class="omega-submit" id="omega-submit" hidden>
                                Kirim Suara
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2 11 13"/><path d="M22 2 15 22l-4-9-9-4 20-7z"/></svg>
                            </button>
                        </div>
                    </form>
                @endif

                @if ($canViewResults)
                    <div class="mt-8 text-center">
                        <a href="{{ route('omega.results') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-teal-700 hover:text-teal-900">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><rect width="4" height="7" x="7" y="10"/><rect width="4" height="12" x="15" y="5"/></svg>
                            Lihat Rekapitulasi Suara
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ============================ ABOUT + TAHAPAN ============================ --}}
    <section class="omega-section bg-white">
        <div class="container mx-auto px-4">
            <div class="grid gap-10 lg:grid-cols-[1fr_1.1fr] lg:items-start">
                <div class="omega-reveal">
                    <span class="omega-eyebrow">Tentang OMEGA</span>
                    <h2 class="omega-h2 mt-2">Membangun budaya kerja yang positif</h2>
                    <p class="mt-4 leading-relaxed text-slate-600">
                        Sebagai bagian dari upaya membangun budaya kerja yang positif, BPS Kota Batam
                        mengadakan <strong class="text-slate-800">Pemilihan Pegawai Terbaik Triwulanan</strong>,
                        sebagai apresiasi terhadap pegawai yang menunjukkan kinerja terbaik dan berkontribusi
                        nyata dalam pelaksanaan tugas.
                    </p>
                    <p class="mt-4 leading-relaxed text-slate-600">
                        Dengan menjunjung tinggi nilai-nilai <strong class="text-teal-700">BerAKHLAK</strong>,
                        setiap pegawai diharapkan terus memberikan yang terbaik.
                    </p>
                </div>

                <div class="omega-reveal omega-card p-6 sm:p-8" data-delay="1">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-teal-700">Nilai-nilai BerAKHLAK</h3>
                    <p class="mt-1 text-sm text-slate-500">Fondasi dari setiap kontribusi terbaik.</p>
                    <div class="omega-values mt-5">
                        @foreach (['Berorientasi Pelayanan', 'Akuntabel', 'Kompeten', 'Harmonis', 'Loyal', 'Adaptif', 'Kolaboratif'] as $i => $value)
                            <span class="omega-value"><span class="num">{{ $i + 1 }}</span>{{ $value }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Tahapan --}}
            <div id="tahapan" class="mt-16 scroll-mt-24">
                <div class="mx-auto max-w-2xl text-center omega-reveal">
                    <span class="omega-eyebrow">Alur Kegiatan</span>
                    <h2 class="omega-h2 mt-2">Tahapan Pemilihan</h2>
                    <p class="mt-3 text-slate-500">Tiga langkah dari penjaringan awal hingga penetapan oleh Kepala BPS Kota Batam.</p>
                </div>

                <div class="omega-steps mt-10">
                    <div class="omega-step omega-reveal" data-delay="1">
                        <span class="omega-step-num">01</span>
                        <div class="omega-step-icon"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                        <h3>Penjaringan Awal di Masing-Masing Tim</h3>
                        <p>Setiap pegawai berhak memilih <strong>satu nama rekan kerja</strong> di tim masing-masing yang dinilai paling layak, berdasarkan kinerja dan kontribusinya. Pegawai diperbolehkan memilih diri sendiri.</p>
                    </div>
                    <div class="omega-step omega-reveal" data-delay="2">
                        <span class="omega-step-num">02</span>
                        <div class="omega-step-icon"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg></div>
                        <h3>Seleksi Administratif &amp; Penilaian Kinerja</h3>
                        <p class="mb-3">Nama yang terkumpul diseleksi memakai indikator berikut, lalu dipilih minimal tiga kandidat terbaik.</p>
                        <div class="grid gap-2">
                            <div class="omega-metric"><b>CKP</b><span>Capaian Kinerja Pegawai</span></div>
                            <div class="omega-metric"><b>KJK</b><span>Kekurangan Jam Kerja</span></div>
                            <div class="omega-metric"><b>TL / PSW</b><span>Terlambat &amp; Pulang Sebelum Waktunya</span></div>
                        </div>
                    </div>
                    <div class="omega-step omega-reveal" data-delay="3">
                        <span class="omega-step-num">03</span>
                        <div class="omega-step-icon"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg></div>
                        <h3>Penetapan oleh Kepala BPS Kota Batam</h3>
                        <p>Dari kandidat terpilih, satu orang ditetapkan sebagai <strong>Pegawai Terbaik Triwulanan</strong> oleh Kepala BPS Kota Batam berdasarkan pertimbangan menyeluruh.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('scripts')
@unless ($hasVoted)
<script>
window.OMEGA = {
    ballotData: @json($ballotData),
    locked: @json($lockedIdentity),
    oldVoter: @json(old('voter_name')),
    oldVotes: @json(old('votes', new stdClass)),
};

document.addEventListener('DOMContentLoaded', function () {
    const OM = window.OMEGA;
    const data = OM.ballotData || {};

    const form        = document.getElementById('omega-form');
    const idStep      = document.getElementById('omega-identity-step');
    const idSelect    = document.getElementById('omega-identity');
    const startBtn    = document.getElementById('omega-start');
    const changeBtn   = document.getElementById('omega-change');
    const body        = document.getElementById('omega-wizard-body');
    const nav         = document.getElementById('omega-nav');
    const backBtn     = document.getElementById('omega-back');
    const nextBtn     = document.getElementById('omega-next');
    const submitBtn   = document.getElementById('omega-submit');
    const chip        = document.getElementById('omega-chip');
    const chipAv      = document.getElementById('omega-chip-av');
    const chipName    = document.getElementById('omega-chip-name');
    const progWrap    = document.getElementById('omega-progress-wrap');
    const progFill    = document.getElementById('omega-progress');
    const curEl       = document.getElementById('omega-cur');
    const totalEl     = document.getElementById('omega-total');

    let voter = null;
    let teams = [];
    let selections = {};
    let step = 0; // 0..teams.length ; last index === teams.length is the review step
    let editIndex = null; // team index being edited from the review; null = normal flow

    const ARROW_SVG = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>';
    const CHECK_SVG = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>';
    const NEXT_LABEL = 'Lanjut ' + ARROW_SVG;
    const SAVE_LABEL = 'Simpan ' + CHECK_SVG;

    const initials = n => n.split(/\s+/).filter(Boolean).slice(0, 2).map(w => w.charAt(0).toUpperCase()).join('');
    const esc = s => String(s).replace(/[&<>"']/g, c => ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[c]));

    /* ---- enable Start once an identity is chosen (dropdown case) ---- */
    if (idSelect && startBtn) {
        idSelect.addEventListener('change', () => { startBtn.disabled = !idSelect.value; });
    }
    if (startBtn) {
        startBtn.addEventListener('click', () => {
            const name = OM.locked || (idSelect ? idSelect.value : null);
            if (name) startVoting(name);
        });
    }
    if (changeBtn) {
        changeBtn.addEventListener('click', showIdentity);
    }

    function showIdentity() {
        chip.hidden = true;
        progWrap.hidden = true;
        nav.hidden = true;
        setActive(idStep);
        scrollToWizard();
    }

    function startVoting(name) {
        voter = name;
        teams = (data[name] || []);
        editIndex = null;
        // restore any old selections that still apply
        selections = {};
        teams.forEach(t => { if (OM.oldVotes && OM.oldVotes[t.team]) selections[t.team] = OM.oldVotes[t.team]; });

        buildBody();
        chipAv.textContent = initials(name);
        chipName.textContent = name;
        chip.hidden = false;
        progWrap.hidden = false;
        nav.hidden = false;
        totalEl.textContent = teams.length;

        // jump to first team without a selection, else review
        step = teams.findIndex(t => !selections[t.team]);
        if (step === -1) step = teams.length;
        showStep();
        scrollToWizard();
    }

    function buildBody() {
        body.innerHTML = '';

        teams.forEach((entry, i) => {
            const team = entry.team;
            const panel = document.createElement('div');
            panel.className = 'omega-panel';
            panel.dataset.step = i;

            let cands = '';
            entry.candidates.forEach(cand => {
                const self = cand === voter;
                const checked = selections[team] === cand;
                cands += `
                    <label class="omega-cand${checked ? ' is-selected' : ''}">
                        <input type="radio" name="votes[${esc(team)}]" value="${esc(cand)}" ${checked ? 'checked' : ''}>
                        <span class="omega-cand-av">${initials(cand)}</span>
                        <span class="omega-cand-body">
                            <span class="omega-cand-name">${esc(cand)}</span>
                            ${self ? '<span class="omega-cand-tag">Anda</span>' : ''}
                        </span>
                        <span class="omega-cand-tick"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></span>
                    </label>`;
            });

            panel.innerHTML = `
                <div class="omega-panel-head">
                    <span class="omega-panel-kicker">Tim ${i + 1} dari ${teams.length}</span>
                    <h3 class="omega-panel-team"><span class="omega-team-badge">${i + 1}</span> ${esc(team)}</h3>
                    <p class="omega-panel-hint">Pilih satu rekan terbaik di tim ini. Boleh memilih diri sendiri.</p>
                </div>
                <div class="omega-cands">${cands}</div>`;
            body.appendChild(panel);
        });

        // Review panel
        const review = document.createElement('div');
        review.className = 'omega-panel';
        review.dataset.step = 'review';
        review.innerHTML = `
            <div class="omega-panel-head">
                <span class="omega-panel-kicker">Langkah terakhir</span>
                <h3 class="omega-panel-team">Tinjau &amp; Kirim</h3>
                <p class="omega-panel-hint">Periksa pilihan Anda. Suara tidak dapat diubah setelah dikirim.</p>
            </div>
            <div class="omega-review" id="omega-review"></div>`;
        body.appendChild(review);
    }

    function setActive(el) {
        [idStep, ...body.querySelectorAll('.omega-panel')].forEach(p => p.classList.remove('is-active'));
        el.classList.add('is-active');
    }

    function showStep() {
        const panels = body.querySelectorAll('.omega-panel');
        const isReview = step >= teams.length;
        setActive(panels[step]);

        // nav visibility
        nextBtn.hidden = isReview;
        submitBtn.hidden = !isReview;
        backBtn.hidden = false;

        if (isReview) {
            editIndex = null;
            renderReview();
        } else {
            updateNext();
            nextBtn.innerHTML = editIndex !== null ? SAVE_LABEL : NEXT_LABEL;
        }
        updateProgress();
    }

    function updateNext() {
        const team = teams[step] ? teams[step].team : null;
        nextBtn.disabled = team ? !selections[team] : false;
    }

    function updateProgress() {
        const done = teams.filter(t => selections[t.team]).length;
        curEl.textContent = done;
        progFill.style.width = teams.length ? (done / teams.length * 100) + '%' : '0%';
    }

    function renderReview() {
        const wrap = document.getElementById('omega-review');
        let rows = '';
        teams.forEach((t, i) => {
            const pick = selections[t.team];
            rows += `
                <div class="omega-review-row">
                    <div>
                        <span class="omega-review-team">${esc(t.team)}</span>
                        <span class="omega-review-name">${pick ? esc(pick) : '<em>Belum dipilih</em>'}</span>
                    </div>
                    <button type="button" class="omega-review-edit" data-goto="${i}">Ubah</button>
                </div>`;
        });
        wrap.innerHTML = rows;
        wrap.querySelectorAll('.omega-review-edit').forEach(b => {
            b.addEventListener('click', () => { editIndex = parseInt(b.dataset.goto, 10); step = editIndex; showStep(); scrollToWizard(); });
        });
        const allDone = teams.every(t => selections[t.team]);
        submitBtn.disabled = !allDone;
    }

    /* ---- selection ---- */
    body.addEventListener('change', e => {
        if (e.target && e.target.type === 'radio') {
            const team = teams[step].team;
            selections[team] = e.target.value;
            e.target.closest('.omega-cands').querySelectorAll('.omega-cand').forEach(l => l.classList.remove('is-selected'));
            e.target.closest('.omega-cand').classList.add('is-selected');
            updateNext();
            updateProgress();
        }
    });

    /* ---- nav ---- */
    nextBtn.addEventListener('click', () => {
        const team = teams[step] ? teams[step].team : null;
        if (team && !selections[team]) { flashPanel(); return; }
        // Editing a single team from the review → save and jump back to the review.
        if (editIndex !== null) { editIndex = null; step = teams.length; showStep(); scrollToWizard(); return; }
        if (step < teams.length) { step++; showStep(); scrollToWizard(); }
    });
    backBtn.addEventListener('click', () => {
        // While editing from the review, "Kembali" returns to the review, not the previous team.
        if (editIndex !== null) { editIndex = null; step = teams.length; showStep(); scrollToWizard(); return; }
        if (step > 0) { step--; showStep(); scrollToWizard(); }
        else if (!OM.locked) showIdentity();
    });

    function flashPanel() {
        const p = body.querySelector('.omega-panel.is-active');
        if (!p) return;
        p.classList.remove('omega-shake');
        void p.offsetWidth;
        p.classList.add('omega-shake');
    }

    function scrollToWizard() {
        const top = form.getBoundingClientRect().top + window.pageYOffset - 90;
        window.scrollTo({ top, behavior: 'smooth' });
    }

    /* ---- boot: locked identity or restored old input ---- */
    const boot = OM.locked || OM.oldVoter;
    if (boot && data[boot]) {
        startVoting(boot);
    }
});
</script>
@endunless
@endsection

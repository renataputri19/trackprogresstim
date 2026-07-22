@extends('new-homepage.layouts.app')

@section('title', 'OMEGA — Rekapitulasi Suara')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/new-homepage/omega.css') }}?v={{ $rkv }}">
@endsection

@section('content')
<main class="omega">
    {{-- Header --}}
    <section class="omega-hero">
        <div class="container mx-auto px-4 py-12 md:py-16">
            <div class="omega-reveal">
                <span class="omega-badge"><span class="dot"></span> Rekapitulasi · {{ $periodLabel }}</span>
            </div>
            <h1 class="omega-wordmark omega-reveal mt-4 text-4xl sm:text-5xl" data-delay="1">Rekapitulasi Suara OMEGA</h1>
            <p class="omega-reveal mt-3 text-emerald-50/80" data-delay="1">
                Hasil penjaringan awal per tim. Data ini bersifat rahasia dan hanya dapat diakses oleh admin.
            </p>

            <div class="omega-reveal mt-8 grid max-w-2xl grid-cols-3 gap-4" data-delay="2">
                <div class="rounded-2xl bg-white/10 p-4 backdrop-blur">
                    <p class="text-3xl font-bold text-white">{{ $totalVoters }}<span class="text-lg text-emerald-200/70">/{{ $totalPossibleVoters }}</span></p>
                    <p class="text-xs uppercase tracking-wide text-emerald-100/70">Pegawai Memilih</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4 backdrop-blur">
                    <p class="text-3xl font-bold text-white">{{ $totalVotes }}</p>
                    <p class="text-xs uppercase tracking-wide text-emerald-100/70">Total Suara</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4 backdrop-blur">
                    <p class="text-3xl font-bold text-white">{{ $totalPossibleVoters ? round($totalVoters / $totalPossibleVoters * 100) : 0 }}%</p>
                    <p class="text-xs uppercase tracking-wide text-emerald-100/70">Partisipasi</p>
                </div>
            </div>
        </div>
    </section>

    <section class="omega-section omega-tint">
        <div class="container mx-auto px-4">
            <div class="mb-6 flex items-center justify-between">
                <a href="{{ route('omega.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-700 dark:text-brand-300 hover:text-teal-900 dark:hover:text-brand-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Kembali ke OMEGA
                </a>
            </div>

            @if ($totalVotes === 0)
                <div class="omega-card p-10 text-center text-[color:var(--text-muted)]">
                    <p>Belum ada suara yang masuk untuk periode ini.</p>
                </div>
            @else
                <div class="grid gap-5 md:grid-cols-2">
                    @foreach ($results as $team)
                        @continue($team['total'] === 0)
                        <div class="omega-card p-6 omega-reveal">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="flex items-center gap-2 font-bold text-[color:var(--text-strong)]">
                                    <span class="omega-team-badge">{{ $team['tallies'][0]['votes'] ?? 0 }}</span>
                                    {{ $team['team'] }}
                                </h3>
                                <span class="text-xs font-semibold uppercase tracking-wide text-[color:var(--text-muted)]">{{ $team['total'] }} suara</span>
                            </div>
                            @php $topVotes = $team['tallies'][0]['votes'] ?? 0; @endphp
                            <div class="space-y-2.5">
                                @foreach ($team['tallies'] as $row)
                                    @php
                                        $pct = $team['total'] ? round($row['votes'] / $team['total'] * 100) : 0;
                                        // Semua kandidat dengan suara terbanyak diberi bintang (termasuk saat seri).
                                        $isLeader = $topVotes > 0 && $row['votes'] === $topVotes;
                                    @endphp
                                    <div>
                                        <div class="mb-1 flex items-center justify-between text-sm">
                                            <span class="flex items-center gap-2 {{ $isLeader ? 'font-bold text-brand-800 dark:text-brand-300' : 'text-[color:var(--text)]' }}">
                                                @if ($isLeader)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#f5b301" stroke="#f5b301" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg>
                                                @endif
                                                {{ $row['name'] }}
                                            </span>
                                            <span class="text-[color:var(--text-muted)]">{{ $row['votes'] }} · {{ $pct }}%</span>
                                        </div>
                                        <div class="h-2 w-full overflow-hidden rounded-full bg-[color:var(--surface-muted)]">
                                            <div class="h-full rounded-full {{ $isLeader ? 'bg-gradient-to-r from-teal-500 to-emerald-500' : 'bg-slate-300 dark:bg-slate-600' }}" style="width: {{ $pct }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</main>
@endsection

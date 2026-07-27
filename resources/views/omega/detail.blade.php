@extends('new-homepage.layouts.app')

@section('title', 'OMEGA — Detail Suara')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/new-homepage/omega.css') }}?v={{ $rkv }}">
@endsection

@section('content')
@php
    $pendingCount = count($pending);
    $participation = $totalPossibleVoters ? round($totalVoters / $totalPossibleVoters * 100) : 0;
@endphp
<main class="omega">
    {{-- Header --}}
    <section class="omega-hero">
        <div class="container mx-auto px-4 py-12 md:py-16">
            <div class="omega-reveal">
                <span class="omega-badge"><span class="dot"></span> Detail Suara · {{ $periodLabel }}</span>
            </div>
            <h1 class="omega-wordmark omega-reveal mt-4 text-4xl sm:text-5xl" data-delay="1">Siapa Memilih Siapa</h1>
            <p class="omega-reveal mt-3 max-w-2xl text-emerald-50/80" data-delay="1">
                Rincian setiap surat suara yang masuk beserta daftar pegawai yang belum memilih.
                Data ini bersifat rahasia dan hanya dapat diakses oleh admin.
            </p>

            <div class="omega-reveal mt-8 grid max-w-3xl grid-cols-2 gap-4 sm:grid-cols-4" data-delay="2">
                <div class="rounded-2xl bg-white/10 p-4 backdrop-blur">
                    <p class="text-3xl font-bold text-white">{{ $totalVoters }}<span class="text-lg text-emerald-200/70">/{{ $totalPossibleVoters }}</span></p>
                    <p class="text-xs uppercase tracking-wide text-emerald-100/70">Sudah Memilih</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4 backdrop-blur">
                    <p class="text-3xl font-bold {{ $pendingCount ? 'text-amber-300' : 'text-white' }}">{{ $pendingCount }}</p>
                    <p class="text-xs uppercase tracking-wide text-emerald-100/70">Belum Memilih</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4 backdrop-blur">
                    <p class="text-3xl font-bold text-white">{{ $totalVotes }}</p>
                    <p class="text-xs uppercase tracking-wide text-emerald-100/70">Total Suara</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4 backdrop-blur">
                    <p class="text-3xl font-bold text-white">{{ $participation }}%</p>
                    <p class="text-xs uppercase tracking-wide text-emerald-100/70">Partisipasi</p>
                </div>
            </div>
        </div>
    </section>

    <section class="omega-section omega-tint">
        <div class="container mx-auto px-4">
            {{-- Back link + tabs --}}
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('omega.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-700 dark:text-brand-300 hover:text-teal-900 dark:hover:text-brand-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Kembali ke OMEGA
                </a>
                <nav class="omega-tabs">
                    <a href="{{ route('omega.results') }}" class="omega-tab">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><rect x="7" y="10" width="3" height="8" rx="1"/><rect x="13" y="6" width="3" height="12" rx="1"/></svg>
                        Rekapitulasi
                    </a>
                    <a href="{{ route('omega.results.detail') }}" class="omega-tab is-active">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m16 11 2 2 4-4"/></svg>
                        Detail Pemilih
                    </a>
                </nav>
            </div>

            {{-- ================= Siapa Memilih Siapa ================= --}}
            <div class="mb-4">
                <span class="omega-eyebrow">Rincian Surat Suara</span>
                <h2 class="omega-h2 mt-1">Siapa Memilih Siapa</h2>
                <p class="mt-1.5 text-sm text-[color:var(--text-muted)]">
                    Ketik nama siapa pun untuk melihat suaranya — baik sebagai pemilih maupun sebagai pihak yang dipilih.
                </p>
            </div>

            <div class="omega-toolbar mb-4">
                <div class="omega-search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="search" id="omega-q" class="omega-field" placeholder="Cari nama pemilih atau nama yang dipilih…" autocomplete="off">
                </div>
                <select id="omega-team" class="omega-field" aria-label="Saring berdasarkan tim">
                    <option value="">Semua tim</option>
                    @foreach ($teams as $team)
                        <option value="{{ $team }}">{{ $team }}</option>
                    @endforeach
                </select>
                <select id="omega-sort" class="omega-field" aria-label="Urutkan">
                    <option value="name">Urutkan: Nama (A–Z)</option>
                    <option value="latest">Urutkan: Terbaru</option>
                </select>
                <span class="omega-count ml-auto"><b id="omega-shown">{{ $totalVoters }}</b> dari {{ $totalVoters }} pemilih</span>
            </div>

            @if ($totalVoters === 0)
                <div class="omega-card p-10 text-center text-[color:var(--text-muted)]">
                    <p>Belum ada suara yang masuk untuk periode ini.</p>
                </div>
            @else
                <div class="omega-table-wrap omega-reveal">
                    <table class="omega-table">
                        <thead>
                            <tr>
                                <th class="omega-idx">#</th>
                                <th>Pemilih</th>
                                <th>Pilihan per Tim</th>
                                <th>Status</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody id="omega-rows">
                            @foreach ($voters as $i => $voter)
                                @php
                                    $haystack = mb_strtolower($voter['name'] . ' ' . implode(' ', array_column($voter['choices'], 'candidate')));
                                    $rowTeams = implode('|', array_column($voter['choices'], 'team'));
                                @endphp
                                <tr data-search="{{ $haystack }}"
                                    data-teams="{{ $rowTeams }}"
                                    data-name="{{ $voter['name'] }}"
                                    data-time="{{ $voter['submittedAt']?->getTimestamp() ?? 0 }}">
                                    <td class="omega-idx">{{ $i + 1 }}</td>
                                    <td data-label="Pemilih">
                                        <div class="omega-person">
                                            <span class="omega-av">{{ $voter['initials'] }}</span>
                                            <div class="min-w-0">
                                                <div class="omega-person-name">{{ $voter['name'] }}</div>
                                                <div class="omega-person-sub">
                                                    @if (! $voter['onRoster'])
                                                        Di luar roster aktif
                                                    @elseif ($voter['account'])
                                                        Akun: {{ $voter['account'] }}
                                                    @else
                                                        {{ count($voter['choices']) }} suara
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Pilihan per Tim">
                                        <div class="omega-picks">
                                            @foreach ($voter['choices'] as $choice)
                                                <span class="omega-pick" data-team="{{ $choice['team'] }}">
                                                    <span class="omega-pick-team">{{ $choice['team'] }}</span>
                                                    <span class="omega-pick-name">{{ $choice['candidate'] }}</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td data-label="Status">
                                        @if ($voter['complete'])
                                            <span class="omega-pill omega-pill--full">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                                Lengkap
                                            </span>
                                        @else
                                            <span class="omega-pill omega-pill--partial">
                                                {{ count($voter['choices']) }}/{{ $voter['expected'] }} tim
                                            </span>
                                        @endif
                                    </td>
                                    <td class="omega-when" data-label="Waktu">
                                        {{ $voter['submittedAt']?->format('d M Y') ?? '—' }}
                                        <span class="block text-[color:var(--text-faint)]">{{ $voter['submittedAt']?->format('H:i') }} WIB</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="omega-empty" id="omega-empty-rows">Tidak ada pemilih yang cocok dengan filter ini.</div>
                </div>
            @endif

            {{-- ================= Belum Memilih ================= --}}
            <div class="mt-14 mb-4">
                <span class="omega-eyebrow">Tindak Lanjut</span>
                <h2 class="omega-h2 mt-1">Belum Memilih <span class="text-[color:var(--text-faint)]">({{ $pendingCount }})</span></h2>
                <p class="mt-1.5 text-sm text-[color:var(--text-muted)]">
                    Pegawai berikut belum mengirimkan surat suara untuk {{ $periodLabel }}. Filter tim &amp; pencarian di atas juga berlaku di sini.
                </p>
            </div>

            @if ($pendingCount === 0)
                <div class="omega-card flex items-center gap-4 p-6 omega-reveal">
                    <span class="grid h-12 w-12 flex-shrink-0 place-items-center rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                    <div>
                        <p class="font-bold text-[color:var(--text-strong)]">Partisipasi penuh 🎉</p>
                        <p class="text-sm text-[color:var(--text-muted)]">Seluruh {{ $totalPossibleVoters }} pegawai sudah memberikan suara.</p>
                    </div>
                </div>
            @else
                <div class="omega-pending-grid" id="omega-pending">
                    @foreach ($pending as $person)
                        <div class="omega-pending"
                             data-search="{{ mb_strtolower($person['name']) }}"
                             data-teams="{{ implode('|', $person['teams']) }}">
                            <span class="omega-av">{{ $person['initials'] }}</span>
                            <div class="min-w-0">
                                <div class="omega-pending-name">{{ $person['name'] }}</div>
                                <div class="omega-pending-sub">
                                    {{ count($person['teams']) }} tim ·
                                    {{ count($person['teams']) ? implode(', ', array_slice($person['teams'], 0, 2)) . (count($person['teams']) > 2 ? ', +' . (count($person['teams']) - 2) : '') : 'tanpa tim' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="omega-empty" id="omega-empty-pending">Semua pegawai pada filter ini sudah memilih.</div>
            @endif
        </div>
    </section>
</main>
@endsection

@section('scripts')
<script>
(function () {
    const q       = document.getElementById('omega-q');
    const teamSel = document.getElementById('omega-team');
    const sortSel = document.getElementById('omega-sort');
    const tbody   = document.getElementById('omega-rows');
    const shown   = document.getElementById('omega-shown');
    const emptyRows    = document.getElementById('omega-empty-rows');
    const pendingWrap  = document.getElementById('omega-pending');
    const emptyPending = document.getElementById('omega-empty-pending');

    const rows    = tbody ? Array.from(tbody.querySelectorAll('tr')) : [];
    const pending = pendingWrap ? Array.from(pendingWrap.children) : [];

    function matches(el, term, team) {
        if (term && !(el.dataset.search || '').includes(term)) return false;
        if (team && !(el.dataset.teams || '').split('|').includes(team)) return false;
        return true;
    }

    function apply() {
        const term = (q.value || '').trim().toLowerCase();
        const team = teamSel.value;

        let visible = 0;
        rows.forEach(function (row) {
            const ok = matches(row, term, team);
            row.hidden = !ok;
            if (ok) {
                visible++;
                // Renumber and, when a team is selected, show only that team's pick.
                const idx = row.querySelector('.omega-idx');
                if (idx) idx.textContent = visible;
                row.querySelectorAll('.omega-pick').forEach(function (pick) {
                    pick.hidden = !!team && pick.dataset.team !== team;
                });
            }
        });
        if (shown) shown.textContent = visible;
        if (emptyRows) emptyRows.classList.toggle('is-shown', rows.length > 0 && visible === 0);

        let visiblePending = 0;
        pending.forEach(function (card) {
            const ok = matches(card, term, team);
            card.hidden = !ok;
            if (ok) visiblePending++;
        });
        if (emptyPending) emptyPending.classList.toggle('is-shown', pending.length > 0 && visiblePending === 0);
    }

    function sortRows() {
        if (!tbody) return;
        const by = sortSel.value;
        const sorted = rows.slice().sort(function (a, b) {
            if (by === 'latest') return Number(b.dataset.time) - Number(a.dataset.time);
            return a.dataset.name.localeCompare(b.dataset.name, 'id', { sensitivity: 'base' });
        });
        sorted.forEach(function (row) { tbody.appendChild(row); });
        apply();
    }

    if (q) q.addEventListener('input', apply);
    if (teamSel) teamSel.addEventListener('change', apply);
    if (sortSel) sortSel.addEventListener('change', sortRows);
})();
</script>
@endsection

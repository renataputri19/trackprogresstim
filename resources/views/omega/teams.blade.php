@extends('new-homepage.layouts.app')

@section('title', 'OMEGA — Susunan Tim')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/new-homepage/omega.css') }}?v={{ $rkv }}">
@endsection

@section('content')
@php
    $totalPeople = count($people);
    $withTeam = collect($people)->filter(fn ($person) => count($person['teams']) > 0)->count();
    $ownComposition = $source && $source->period === $period;
@endphp
<main class="omega">
    {{-- Header --}}
    <section class="omega-hero">
        <div class="container mx-auto px-4 py-12 md:py-16">
            <div class="omega-reveal">
                <span class="omega-badge"><span class="dot"></span> Susunan Tim · {{ $periodLabel }}</span>
            </div>
            <h1 class="omega-wordmark omega-reveal mt-4 text-4xl sm:text-5xl" data-delay="1">Susunan Tim Pegawai</h1>
            <p class="omega-reveal mt-3 max-w-2xl text-emerald-50/80" data-delay="1">
                Aktifkan tim tempat setiap pegawai tergabung. Pegawai memilih di tim yang aktif,
                dan hanya anggota tim yang muncul sebagai kandidat di tim tersebut.
            </p>

            <div class="omega-reveal mt-8 grid max-w-2xl grid-cols-3 gap-4" data-delay="2">
                <div class="rounded-2xl bg-white/10 p-4 backdrop-blur">
                    <p class="text-3xl font-bold text-white">{{ $withTeam }}<span class="text-lg text-emerald-200/70">/{{ $totalPeople }}</span></p>
                    <p class="text-xs uppercase tracking-wide text-emerald-100/70">Pegawai Bertim</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4 backdrop-blur">
                    <p class="text-3xl font-bold text-white">{{ count($teams) }}</p>
                    <p class="text-xs uppercase tracking-wide text-emerald-100/70">Tim</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4 backdrop-blur">
                    <p class="text-3xl font-bold text-white">{{ $votersInPeriod }}</p>
                    <p class="text-xs uppercase tracking-wide text-emerald-100/70">Sudah Memilih</p>
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
                    <a href="{{ route('omega.results.detail') }}" class="omega-tab">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m16 11 2 2 4-4"/></svg>
                        Detail Pemilih
                    </a>
                    <a href="{{ route('omega.teams') }}" class="omega-tab is-active">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="12" x="2" y="6" rx="6"/><circle cx="16" cy="12" r="2"/></svg>
                        Susunan Tim
                    </a>
                </nav>
            </div>

            {{-- Flash / errors --}}
            @if (session('omega_success'))
                <div class="omega-alert omega-alert--success mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 flex-shrink-0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                    <span>{{ session('omega_success') }}</span>
                </div>
            @endif
            @if (session('omega_info'))
                <div class="omega-alert omega-alert--info mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 flex-shrink-0"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    <span>{{ session('omega_info') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="omega-alert omega-alert--warn mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 flex-shrink-0"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                    <div>
                        <p class="font-semibold">Susunan tim belum tersimpan:</p>
                        <ul class="mt-1 list-inside list-disc space-y-0.5">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- ================= Periode ================= --}}
            <div class="mb-4">
                <span class="omega-eyebrow">Periode</span>
                <h2 class="omega-h2 mt-1">Atur Tim per Triwulan</h2>
                <p class="mt-1.5 text-sm text-[color:var(--text-muted)]">
                    Susunan yang disimpan berlaku mulai triwulan yang dipilih dan terus dipakai di triwulan berikutnya sampai diubah lagi.
                </p>
            </div>

            <div class="mb-4 flex flex-wrap items-center gap-3">
                <nav class="omega-tabs" aria-label="Pilih triwulan">
                    @foreach ($periods as $p)
                        <a href="{{ route('omega.teams', ['period' => $p['period']]) }}" class="omega-tab {{ $p['period'] === $period ? 'is-active' : '' }}">
                            {{ $p['label'] }}
                            <span class="omega-tab-tag">{{ $p['isActive'] ? 'Voting dibuka' : 'Berikutnya' }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>

            <div class="omega-source mb-4">
                <div class="omega-source-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    <p>
                        @if ($ownComposition)
                            Memakai susunan khusus <b>{{ $periodLabel }}</b>, disimpan {{ $source->updated_at?->format('d M Y, H:i') }} WIB
                            @if ($source->editor) oleh <b>{{ $source->editor->name }}</b>@endif.
                        @elseif ($source)
                            Belum ada penyesuaian untuk <b>{{ $periodLabel }}</b> — saat ini mengikuti susunan <b>{{ $sourceLabel }}</b>.
                            Menyimpan di sini membuat susunan baru mulai {{ $periodLabel }}.
                        @else
                            Belum ada penyesuaian — memakai susunan awal dari <b>Tim 2026.xlsx</b>.
                            Menyimpan di sini membuat susunan baru mulai {{ $periodLabel }}.
                        @endif
                    </p>
                </div>
                @if ($ownComposition)
                    <form method="POST" action="{{ route('omega.teams.reset') }}"
                          onsubmit="return confirm('Hapus penyesuaian {{ $periodLabel }}? Susunan tim akan kembali mengikuti periode sebelumnya.');">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="period" value="{{ $period }}">
                        <button type="submit" class="omega-btn-ghost omega-btn--sm">Hapus penyesuaian</button>
                    </form>
                @endif
            </div>

            @if ($isActivePeriod && $votersInPeriod > 0)
                <div class="omega-alert omega-alert--warn mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 flex-shrink-0"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                    <span>
                        Voting {{ $periodLabel }} sedang berlangsung dan <strong>{{ $votersInPeriod }} pegawai</strong> sudah memilih.
                        Suara yang sudah masuk tidak berubah; perubahan hanya berlaku untuk kartu suara pegawai yang belum memilih.
                    </span>
                </div>
            @endif

            {{-- ================= Matrix ================= --}}
            <div class="omega-toolbar mb-4">
                <div class="omega-search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="search" id="omega-q" class="omega-field" placeholder="Cari nama pegawai…" autocomplete="off">
                </div>
                <select id="omega-filter" class="omega-field" aria-label="Saring pegawai">
                    <option value="">Semua pegawai</option>
                    <option value="__none__">Belum masuk tim mana pun</option>
                    @foreach ($teams as $team)
                        <option value="{{ $team }}">Anggota tim: {{ $team }}</option>
                    @endforeach
                </select>
                <span class="omega-count ml-auto"><b id="omega-shown">{{ $totalPeople }}</b> dari <span id="omega-total">{{ $totalPeople }}</span> pegawai</span>
            </div>

            <form method="POST" action="{{ route('omega.teams.save') }}" id="omega-teams-form">
                @csrf
                <input type="hidden" name="period" value="{{ $period }}">
                <div id="omega-team-inputs">
                    @foreach ($teams as $team)
                        <input type="hidden" name="teams[]" value="{{ $team }}">
                    @endforeach
                </div>

                <div class="omega-matrix-wrap">
                    <table class="omega-matrix" id="omega-matrix">
                        <thead>
                            <tr>
                                <th scope="col" class="omega-mx-person">Pegawai</th>
                                @foreach ($teams as $team)
                                    <th scope="col" class="omega-mx-team" data-team="{{ $team }}">
                                        <span class="omega-mx-team-name">{{ $team }}</span>
                                        <span class="omega-mx-count">{{ collect($people)->filter(fn ($person) => in_array($team, $person['teams'], true))->count() }}</span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($people as $i => $person)
                                <tr data-index="{{ $i }}" data-name="{{ mb_strtolower($person['name']) }}" data-label="{{ $person['name'] }}">
                                    <th scope="row" class="omega-mx-person">
                                        <input type="hidden" name="people[{{ $i }}][name]" value="{{ $person['name'] }}">
                                        <div class="omega-person">
                                            <span class="omega-av">{{ $person['initials'] }}</span>
                                            <div class="min-w-0">
                                                <div class="omega-person-name">{{ $person['name'] }}</div>
                                                <div class="omega-person-sub"><span data-row-count>{{ count($person['teams']) }}</span> tim</div>
                                            </div>
                                        </div>
                                    </th>
                                    @foreach ($teams as $team)
                                        <td>
                                            <label class="omega-switch">
                                                <input type="checkbox" name="people[{{ $i }}][teams][]" value="{{ $team }}"
                                                       aria-label="{{ $person['name'] }} — {{ $team }}"
                                                       @checked(in_array($team, $person['teams'], true))>
                                                <span class="omega-switch-track" aria-hidden="true"></span>
                                            </label>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="omega-empty" id="omega-empty-rows">Tidak ada pegawai yang cocok dengan filter ini.</div>
                </div>

                {{-- Add employee / team --}}
                <div class="omega-mx-add">
                    <div>
                        <label for="omega-add-person" class="omega-mx-add-label">Tambah pegawai</label>
                        <div class="omega-mx-add-row">
                            <input type="text" id="omega-add-person" class="omega-field" maxlength="150" placeholder="Nama lengkap beserta gelar" autocomplete="off">
                            <button type="button" class="omega-btn-ghost omega-btn--sm" id="omega-add-person-btn">Tambah</button>
                        </div>
                        <p class="omega-mx-hint" id="omega-add-person-hint"></p>
                    </div>
                    <div>
                        <label for="omega-add-team" class="omega-mx-add-label">Tambah tim</label>
                        <div class="omega-mx-add-row">
                            <input type="text" id="omega-add-team" class="omega-field" maxlength="60" placeholder="Nama tim baru" autocomplete="off">
                            <button type="button" class="omega-btn-ghost omega-btn--sm" id="omega-add-team-btn">Tambah</button>
                        </div>
                        <p class="omega-mx-hint" id="omega-add-team-hint">Tim tanpa anggota tidak ikut disimpan.</p>
                    </div>
                </div>

                {{-- Save bar --}}
                <div class="omega-savebar" id="omega-savebar">
                    <span class="omega-savebar-status">
                        <span class="omega-savebar-dot" aria-hidden="true"></span>
                        <span id="omega-save-status" aria-live="polite">Belum ada perubahan</span>
                    </span>
                    <button type="button" class="omega-btn-ghost omega-btn--sm" id="omega-cancel">Batalkan</button>
                    <button type="submit" class="omega-btn omega-btn--sm" id="omega-save" disabled>
                        Simpan Susunan Tim
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </button>
                </div>
            </form>

            @if (count($leaders))
                <div class="omega-leaders mt-8">
                    <span>Pimpinan — memilih di semua tim dan bukan kandidat:</span>
                    @foreach ($leaders as $leader)
                        <span class="omega-pick"><span class="omega-pick-name">{{ $leader }}</span></span>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</main>
@endsection

@section('scripts')
<script>
(function () {
    const form = document.getElementById('omega-teams-form');
    if (!form) return;

    const LEADERS = @json(array_map('mb_strtolower', $leaders));

    const table      = document.getElementById('omega-matrix');
    const headRow    = table.tHead.rows[0];
    const tbody      = table.tBodies[0];
    const teamInputs = document.getElementById('omega-team-inputs');
    const q          = document.getElementById('omega-q');
    const filter     = document.getElementById('omega-filter');
    const shown      = document.getElementById('omega-shown');
    const total      = document.getElementById('omega-total');
    const emptyRows  = document.getElementById('omega-empty-rows');
    const savebar    = document.getElementById('omega-savebar');
    const status     = document.getElementById('omega-save-status');
    const saveBtn    = document.getElementById('omega-save');
    const cancelBtn  = document.getElementById('omega-cancel');
    const personIn   = document.getElementById('omega-add-person');
    const personBtn  = document.getElementById('omega-add-person-btn');
    const personHint = document.getElementById('omega-add-person-hint');
    const teamIn     = document.getElementById('omega-add-team');
    const teamBtn    = document.getElementById('omega-add-team-btn');
    const teamHint   = document.getElementById('omega-add-team-hint');
    const teamHintDefault = teamHint.textContent;

    let nextIndex = tbody.rows.length;
    let changes = 0;
    let submitting = false;

    const esc = s => String(s).replace(/[&<>"']/g, c => ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[c]));
    const squish = s => s.trim().replace(/\s+/g, ' ');
    const teams = () => Array.from(teamInputs.querySelectorAll('input')).map(i => i.value);
    // Monogram without academic titles: words before the first comma that carry no dot.
    const initials = n => {
        const words = n.split(',')[0].split(/\s+/).filter(w => w && !w.includes('.'));
        if (!words.length) return '?';
        return (words[0][0] + (words.length > 1 ? words[words.length - 1][0] : '')).toUpperCase();
    };

    function switchCell(index, team, name) {
        const td = document.createElement('td');
        td.innerHTML =
            `<label class="omega-switch">` +
                `<input type="checkbox" name="people[${index}][teams][]" value="${esc(team)}" aria-label="${esc(name + ' — ' + team)}">` +
                `<span class="omega-switch-track" aria-hidden="true"></span>` +
            `</label>`;
        return td;
    }

    function hint(el, message, isError) {
        el.textContent = message;
        el.classList.toggle('is-error', !!isError);
    }

    /* ---- counters + unsaved-change tracking ---- */
    function refresh() {
        changes = tbody.querySelectorAll('tr[data-new]').length + headRow.querySelectorAll('th[data-new]').length;

        const perTeam = {};
        tbody.querySelectorAll('input[type=checkbox]').forEach(cb => {
            const dirty = cb.checked !== cb.defaultChecked;
            cb.closest('td').classList.toggle('is-dirty', dirty);
            if (dirty) changes++;
            if (cb.checked) perTeam[cb.value] = (perTeam[cb.value] || 0) + 1;
        });

        Array.from(tbody.rows).forEach(tr => {
            tr.querySelector('[data-row-count]').textContent = tr.querySelectorAll('input[type=checkbox]:checked').length;
        });
        headRow.querySelectorAll('th[data-team]').forEach(th => {
            th.querySelector('.omega-mx-count').textContent = perTeam[th.dataset.team] || 0;
        });

        savebar.classList.toggle('is-dirty', changes > 0);
        status.textContent = changes ? changes + ' perubahan belum disimpan' : 'Belum ada perubahan';
        saveBtn.disabled = changes === 0;
    }

    /* ---- search + filter (evaluated on demand so toggling never hides a row) ---- */
    function applyFilter() {
        const term = q.value.trim().toLowerCase();
        const mode = filter.value;
        let visible = 0;

        Array.from(tbody.rows).forEach(tr => {
            const picked = Array.from(tr.querySelectorAll('input[type=checkbox]:checked')).map(cb => cb.value);
            let ok = !term || tr.dataset.name.includes(term);
            if (ok && mode === '__none__') ok = picked.length === 0;
            else if (ok && mode) ok = picked.includes(mode);
            tr.hidden = !ok;
            if (ok) visible++;
        });

        shown.textContent = visible;
        total.textContent = tbody.rows.length;
        emptyRows.classList.toggle('is-shown', visible === 0);
    }

    /* ---- add a team column ---- */
    function addTeam() {
        const name = squish(teamIn.value).toUpperCase();
        if (!name) return hint(teamHint, 'Isi nama tim terlebih dahulu.', true);
        if (teams().includes(name)) return hint(teamHint, 'Tim ' + name + ' sudah ada.', true);

        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'teams[]';
        hidden.value = name;
        hidden.dataset.new = '1';
        teamInputs.appendChild(hidden);

        const th = document.createElement('th');
        th.scope = 'col';
        th.className = 'omega-mx-team';
        th.dataset.team = name;
        th.dataset.new = '1';
        th.innerHTML = `<span class="omega-mx-team-name">${esc(name)}</span><span class="omega-mx-new">Baru</span><br><span class="omega-mx-count">0</span>`;
        headRow.appendChild(th);

        Array.from(tbody.rows).forEach(tr => {
            const td = switchCell(tr.dataset.index, name, tr.dataset.label);
            td.dataset.new = '1';
            tr.appendChild(td);
        });

        const option = new Option('Anggota tim: ' + name, name);
        option.dataset.new = '1';
        filter.appendChild(option);

        teamIn.value = '';
        hint(teamHint, 'Tim ' + name + ' ditambahkan di kolom paling kanan — aktifkan anggotanya, lalu simpan.');
        refresh();
        table.closest('.omega-matrix-wrap').scrollLeft = table.scrollWidth;
    }

    /* ---- add an employee row ---- */
    function addPerson() {
        const name = squish(personIn.value);
        const key = name.toLowerCase();
        if (!name) return hint(personHint, 'Isi nama pegawai terlebih dahulu.', true);
        if (LEADERS.includes(key)) return hint(personHint, name + ' adalah pimpinan dan sudah memilih di semua tim.', true);
        if (Array.from(tbody.rows).some(tr => tr.dataset.name === key)) return hint(personHint, name + ' sudah ada di daftar.', true);

        const index = nextIndex++;
        const tr = document.createElement('tr');
        tr.dataset.index = index;
        tr.dataset.name = key;
        tr.dataset.label = name;
        tr.dataset.new = '1';
        tr.innerHTML =
            `<th scope="row" class="omega-mx-person">` +
                `<input type="hidden" name="people[${index}][name]" value="${esc(name)}">` +
                `<div class="omega-person">` +
                    `<span class="omega-av">${esc(initials(name))}</span>` +
                    `<div class="min-w-0">` +
                        `<div class="omega-person-name">${esc(name)}</div>` +
                        `<div class="omega-person-sub"><span data-row-count>0</span> tim · <span class="omega-mx-new">Baru</span>` +
                        `<button type="button" class="omega-mx-remove">Hapus</button></div>` +
                    `</div>` +
                `</div>` +
            `</th>`;
        teams().forEach(team => tr.appendChild(switchCell(index, team, name)));
        tbody.appendChild(tr);

        personIn.value = '';
        hint(personHint, name + ' ditambahkan di baris paling bawah — aktifkan timnya, lalu simpan.');
        q.value = '';
        filter.value = '';
        applyFilter();
        refresh();
        tr.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    }

    tbody.addEventListener('change', refresh);
    tbody.addEventListener('click', e => {
        const remove = e.target.closest('.omega-mx-remove');
        if (!remove) return;
        remove.closest('tr').remove();
        applyFilter();
        refresh();
    });

    q.addEventListener('input', applyFilter);
    filter.addEventListener('change', applyFilter);
    teamBtn.addEventListener('click', addTeam);
    personBtn.addEventListener('click', addPerson);
    // Enter in the "add" fields adds the row/column instead of submitting the form.
    [[teamIn, addTeam], [personIn, addPerson]].forEach(([input, add]) => {
        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); add(); }
        });
    });

    cancelBtn.addEventListener('click', () => {
        document.querySelectorAll('[data-new]').forEach(el => el.remove());
        form.reset();
        filter.value = '';
        hint(personHint, '');
        hint(teamHint, teamHintDefault);
        applyFilter();
        refresh();
    });

    form.addEventListener('submit', () => {
        submitting = true;
        saveBtn.disabled = true;
    });
    window.addEventListener('beforeunload', e => {
        if (!submitting && changes > 0) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    refresh();
})();
</script>
@endsection

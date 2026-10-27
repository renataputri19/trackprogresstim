<?php

namespace App\Http\Controllers;

use App\Models\OmegaPeriodRoster;
use App\Models\OmegaVote;
use App\Services\OmegaRoster;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OmegaController extends Controller
{
    public function __construct(private OmegaRoster $roster)
    {
    }

    /**
     * Only the accounts listed in config('omega.results_access') may view the
     * recap and manage team composition — this is intentionally NOT tied to
     * the admin flag.
     */
    private function canViewResults(?\App\Models\User $user): bool
    {
        if (! $user) {
            return false;
        }
        $allowed = array_map('strtolower', (array) config('omega.results_access', []));

        return in_array(strtolower((string) $user->email), $allowed, true);
    }

    /**
     * OMEGA landing + personal ballot (Tahap 1 — Penjaringan Awal).
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $period = $this->roster->period();

        // If the logged-in account maps unambiguously to a roster member we lock
        // their identity; otherwise they pick who they are from the roster.
        $lockedIdentity = $this->roster->matchAccount($user->name);

        // Has this account (or the locked identity) already voted this period?
        $existing = OmegaVote::query()
            ->where('period', $period)
            ->where(function ($q) use ($user, $lockedIdentity) {
                $q->where('user_id', $user->id);
                if ($lockedIdentity) {
                    $q->orWhere('voter_name', $lockedIdentity);
                }
            })
            ->get();

        $hasVoted = $existing->isNotEmpty();
        $votedAs = $existing->first()->voter_name ?? $lockedIdentity;
        $myVotes = $existing->pluck('candidate_name', 'team')->toArray();

        return view('omega.index', [
            'periodLabel' => $this->roster->periodLabel(),
            'timeline' => $this->roster->quarterTimeline(),
            'memberNames' => $this->roster->activeMemberNames(),
            'ballotData' => $this->roster->ballotData(),
            'lockedIdentity' => $lockedIdentity,
            'lockedBallot' => $lockedIdentity ? $this->roster->ballotFor($lockedIdentity) : [],
            'hasVoted' => $hasVoted,
            'votedAs' => $votedAs,
            'myVotes' => $myVotes,
            'canViewResults' => $this->canViewResults($user),
        ]);
    }

    /**
     * Record a completed ballot: one chosen colleague per team the voter belongs to.
     */
    public function vote(Request $request)
    {
        $user = $request->user();
        $period = $this->roster->period();

        // Determine the voter. A matched account may only vote as itself.
        $lockedIdentity = $this->roster->matchAccount($user->name);
        if ($lockedIdentity) {
            $voterName = $lockedIdentity;
        } else {
            $voterName = trim((string) $request->input('voter_name', ''));
            if (! $this->roster->isActiveMember($voterName)) {
                return back()
                    ->withErrors(['voter_name' => 'Silakan pilih nama Anda dari daftar pegawai.'])
                    ->withInput();
            }
        }

        // Block a second submission for this period.
        $already = OmegaVote::query()
            ->where('period', $period)
            ->where(function ($q) use ($voterName, $user) {
                $q->where('voter_name', $voterName)->orWhere('user_id', $user->id);
            })
            ->exists();

        if ($already) {
            return redirect()
                ->route('omega.index')
                ->with('omega_info', 'Anda sudah memberikan suara untuk periode ini. Terima kasih!');
        }

        // Validate one valid choice per team the voter belongs to.
        $submitted = (array) $request->input('votes', []);
        $teams = $this->roster->teamsFor($voterName);
        $clean = [];
        $errors = [];

        foreach ($teams as $team) {
            $choice = isset($submitted[$team]) ? trim((string) $submitted[$team]) : '';
            if ($choice === '') {
                $errors["votes.$team"] = "Silakan pilih satu rekan untuk tim {$team}.";
                continue;
            }
            if (! in_array($choice, $this->roster->teamMembers($team), true)) {
                $errors["votes.$team"] = "Pilihan untuk tim {$team} tidak valid.";
                continue;
            }
            $clean[$team] = $choice;
        }

        if (! empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        try {
            DB::transaction(function () use ($clean, $period, $voterName, $user) {
                foreach ($clean as $team => $candidate) {
                    OmegaVote::create([
                        'period' => $period,
                        'voter_name' => $voterName,
                        'team' => $team,
                        'candidate_name' => $candidate,
                        'user_id' => $user->id,
                    ]);
                }
            });
        } catch (QueryException) {
            // Unique constraint hit (e.g. double submit) — treat as already voted.
            return redirect()
                ->route('omega.index')
                ->with('omega_info', 'Anda sudah memberikan suara untuk periode ini. Terima kasih!');
        }

        return redirect()
            ->route('omega.index')
            ->with('omega_success', 'Terima kasih! Suara Anda untuk ' . count($clean) . ' tim berhasil direkam.');
    }

    /**
     * Rekapitulasi suara — hanya untuk akun di config('omega.results_access').
     */
    public function results(Request $request)
    {
        abort_unless($this->canViewResults($request->user()), 403);

        $period = $this->roster->period();
        $votes = OmegaVote::where('period', $period)->get();

        return view('omega.results', [
            'periodLabel' => $this->roster->periodLabel(),
            'results' => $this->roster->tally($votes),
            'totalVotes' => $votes->count(),
            'totalVoters' => $votes->pluck('voter_name')->unique()->count(),
            'totalPossibleVoters' => count($this->roster->activeMembers()),
        ]);
    }

    /**
     * Detail suara — siapa memilih siapa, plus daftar pegawai yang belum
     * memberikan suara. Akses sama dengan halaman rekapitulasi.
     */
    public function resultsDetail(Request $request)
    {
        abort_unless($this->canViewResults($request->user()), 403);

        $period = $this->roster->period();
        $votes = OmegaVote::with('user:id,name,email')
            ->where('period', $period)
            ->orderBy('id')
            ->get();

        $activeMembers = $this->roster->activeMembers();
        // Keep each ballot's chips in the same order as the configured teams.
        $teamOrder = array_flip($this->roster->teams());

        $voters = [];
        foreach ($votes->groupBy('voter_name') as $name => $rows) {
            $expected = $this->roster->teamsFor($name);
            $account = $rows->first()->user;

            $choices = $rows
                ->sortBy(fn ($vote) => $teamOrder[$vote->team] ?? PHP_INT_MAX)
                ->map(fn ($vote) => [
                    'team' => $vote->team,
                    'candidate' => $vote->candidate_name,
                    'initials' => $this->initials($vote->candidate_name),
                ])
                ->values()
                ->all();

            $voters[] = [
                'name' => $name,
                'initials' => $this->initials($name),
                'account' => $account?->name,
                'email' => $account?->email,
                'submittedAt' => $rows->first()->created_at,
                'choices' => $choices,
                'expected' => count($expected),
                // A ballot is complete when every team the voter belongs to is filled.
                'complete' => count($expected) === 0 || count($choices) >= count($expected),
                'onRoster' => array_key_exists($name, $activeMembers),
            ];
        }

        usort($voters, fn ($a, $b) => strnatcasecmp($a['name'], $b['name']));

        $votedNames = array_column($voters, 'name');
        $pending = [];
        foreach ($this->roster->activeMemberNames() as $name) {
            if (in_array($name, $votedNames, true)) {
                continue;
            }
            $pending[] = [
                'name' => $name,
                'initials' => $this->initials($name),
                'teams' => $this->roster->teamsFor($name),
            ];
        }

        return view('omega.detail', [
            'periodLabel' => $this->roster->periodLabel(),
            'teams' => $this->roster->teams(),
            'voters' => $voters,
            'pending' => $pending,
            'totalVotes' => $votes->count(),
            'totalVoters' => count($voters),
            'totalPossibleVoters' => count($activeMembers),
        ]);
    }

    /**
     * Susunan Tim — switch which teams each employee belongs to, per period.
     * Akses sama dengan halaman rekapitulasi.
     */
    public function teams(Request $request)
    {
        abort_unless($this->canViewResults($request->user()), 403);

        $periods = $this->roster->editablePeriods();
        $period = (string) $request->query('period', '');
        if (! in_array($period, array_column($periods, 'period'), true)) {
            $period = $this->roster->period();
        }

        $roster = $this->roster->forPeriod($period);
        $source = $roster->source();
        $source?->loadMissing('editor:id,name');

        $people = [];
        foreach ($roster->editableMembers() as $name => $teams) {
            $people[] = [
                'name' => $name,
                'initials' => $this->initials($name),
                'teams' => $teams,
            ];
        }

        return view('omega.teams', [
            'periods' => $periods,
            'period' => $period,
            'periodLabel' => $this->roster->labelFor($period),
            'isActivePeriod' => $period === $this->roster->period(),
            'teams' => $roster->teams(),
            'people' => $people,
            'leaders' => $roster->leaderNames(),
            'source' => $source,
            'sourceLabel' => $source ? $this->roster->labelFor($source->period) : null,
            'votersInPeriod' => OmegaVote::where('period', $period)->distinct()->count('voter_name'),
        ]);
    }

    /**
     * Save the team composition for one period (it also applies to later
     * periods until those get their own).
     */
    public function saveTeams(Request $request)
    {
        abort_unless($this->canViewResults($request->user()), 403);

        $period = $this->editablePeriodFrom($request);

        $request->validate([
            'teams' => ['required', 'array', 'max:60'],
            'teams.*' => ['required', 'string', 'max:60'],
            'people' => ['required', 'array', 'max:300'],
            'people.*.name' => ['required', 'string', 'max:150'],
            'people.*.teams' => ['sometimes', 'array'],
            'people.*.teams.*' => ['string', 'max:60'],
        ]);

        $squish = fn ($value) => trim(preg_replace('/\s+/u', ' ', (string) $value));

        $teams = [];
        foreach ((array) $request->input('teams') as $team) {
            $team = mb_strtoupper($squish($team));
            if ($team !== '' && ! in_array($team, $teams, true)) {
                $teams[] = $team;
            }
        }

        $members = [];
        $seen = [];
        foreach ((array) $request->input('people') as $person) {
            $name = $squish($person['name'] ?? '');
            $key = mb_strtolower($name);
            // Leaders are configured in config/omega.php, not on this page.
            if ($name === '' || isset($seen[$key]) || $this->roster->isLeader($name)) {
                continue;
            }
            $seen[$key] = true;

            $picked = array_map(fn ($team) => mb_strtoupper($squish($team)), (array) ($person['teams'] ?? []));
            // Keep each employee's teams in the column order.
            $members[$name] = array_values(array_filter($teams, fn ($team) => in_array($team, $picked, true)));
        }

        // A team nobody belongs to is dropped from the composition.
        $used = array_unique(array_merge(...array_values($members)));
        $teams = array_values(array_filter($teams, fn ($team) => in_array($team, $used, true)));

        if (empty($teams)) {
            return back()
                ->withErrors(['teams' => 'Minimal satu pegawai harus tergabung di satu tim.'])
                ->withInput();
        }

        OmegaPeriodRoster::updateOrCreate(
            ['period' => $period],
            ['teams' => $teams, 'members' => $members, 'updated_by' => $request->user()->id],
        );

        return redirect()
            ->route('omega.teams', ['period' => $period])
            ->with('omega_success', 'Susunan tim untuk ' . $this->roster->labelFor($period) . ' berhasil disimpan.');
    }

    /**
     * Drop a period's own composition so it follows the previous one again.
     */
    public function resetTeams(Request $request)
    {
        abort_unless($this->canViewResults($request->user()), 403);

        $period = $this->editablePeriodFrom($request);
        OmegaPeriodRoster::where('period', $period)->delete();

        return redirect()
            ->route('omega.teams', ['period' => $period])
            ->with('omega_info', 'Penyesuaian untuk ' . $this->roster->labelFor($period) . ' dihapus. Susunan tim kembali mengikuti periode sebelumnya.');
    }

    private function editablePeriodFrom(Request $request): string
    {
        $period = (string) $request->input('period', '');
        abort_unless(in_array($period, array_column($this->roster->editablePeriods(), 'period'), true), 422);

        return $period;
    }

    /**
     * Two-letter monogram for a roster name, with academic titles stripped
     * ("Renata Putri Henessa, S.Tr.Stat." -> "RH").
     */
    private function initials(string $name): string
    {
        $parts = array_values(array_filter(preg_split('/\s+/', $this->roster->normalize($name)) ?: []));

        if (empty($parts)) {
            return '?';
        }

        $first = mb_substr($parts[0], 0, 1);
        $last = count($parts) > 1 ? mb_substr($parts[count($parts) - 1], 0, 1) : '';

        return mb_strtoupper($first . $last);
    }
}

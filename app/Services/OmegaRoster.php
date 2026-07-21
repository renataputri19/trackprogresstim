<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * OMEGA roster helper.
 *
 * Wraps the static team/member data defined in config/omega.php and provides
 * the lookups the OMEGA voting flow needs: the active voting quarter (derived
 * from today's date), who belongs to which team, matching a logged-in account
 * to a roster name, building a personal ballot, and tallying results.
 */
class OmegaRoster
{
    private const ROMAN = ['I', 'II', 'III', 'IV'];

    private const QUARTER_MONTHS = [
        1 => 'Jan – Mar',
        2 => 'Apr – Jun',
        3 => 'Jul – Sep',
        4 => 'Okt – Des',
    ];

    /** Month (abbrev) in which voting for each quarter opens. */
    private const OPENS_MONTH = [1 => 'Apr', 2 => 'Jul', 3 => 'Okt', 4 => 'Jan'];

    /**
     * Academic titles (gelar) to strip when matching names. Compared after
     * lower-casing and removing dots, so 'S.Tr.Stat' -> 'strstat', etc.
     */
    private const GELAR = [
        'se', 'sst', 'sst', 'ssit', 'ssi', 'sst', 'strstat', 'str', 'strkom',
        'amd', 'amdak', 'amdstat', 'amdkom', 'msi', 'msc', 'mti', 'mm', 'sm',
        'smat', 'skom', 'me', 'st', 'ssos', 'mts', 'mp', 'mep', 'sstat',
    ];

    /** @var array<string, array<int, string>> name => list of team names */
    private array $members;

    /** @var array<int, string> */
    private array $teams;

    /**
     * Leaders may vote in every (or specific) teams but are never candidates.
     *
     * @var array<string, string|array<int, string>> name => 'all' | list of teams
     */
    private array $leaders;

    public function __construct()
    {
        $this->teams = array_values(config('omega.teams', []));
        $this->members = config('omega.members', []);
        $this->leaders = config('omega.leaders', []);
    }

    /** Teams a leader is entitled to vote in. */
    private function leaderTeams(string $name): array
    {
        if (! array_key_exists($name, $this->leaders)) {
            return [];
        }
        $spec = $this->leaders[$name];

        return $spec === 'all' ? $this->teams() : array_values((array) $spec);
    }

    /**
     * People who can be voted for (roster members with at least one team).
     * Leaders are intentionally excluded — they cast votes but are not candidates.
     *
     * @return array<string, array<int, string>> name => teams
     */
    public function candidateMembers(): array
    {
        return array_filter($this->members, fn ($teams) => ! empty($teams));
    }

    /* ====================================================================
     |  Voting period (auto-derived from the current date)
     * ==================================================================== */

    /**
     * The active voting quarter: the calendar quarter immediately preceding
     * the current one (you vote for the quarter that just finished).
     *
     * @return array{year:int, q:int}
     */
    public function activeQuarter(): array
    {
        $force = config('omega.force_period');
        if (is_string($force) && preg_match('/^(\d{4})-Q([1-4])$/', $force, $m)) {
            return ['year' => (int) $m[1], 'q' => (int) $m[2]];
        }

        $now = Carbon::now();
        $calendarQuarter = (int) ceil($now->month / 3); // 1..4
        $votingQuarter = $calendarQuarter - 1;
        $year = $now->year;

        if ($votingQuarter === 0) {
            $votingQuarter = 4;
            $year -= 1;
        }

        return ['year' => $year, 'q' => $votingQuarter];
    }

    public function period(): string
    {
        $a = $this->activeQuarter();

        return $a['year'] . '-Q' . $a['q'];
    }

    public function periodLabel(): string
    {
        $a = $this->activeQuarter();

        return 'Triwulan ' . $this->roman($a['q']) . ' ' . $a['year'];
    }

    /**
     * The four quarters of the active cycle year, each tagged with its status
     * relative to now: 'closed' (already ended), 'open' (currently voting), or
     * 'upcoming' (not yet open).
     *
     * @return array<int, array<string, mixed>>
     */
    public function quarterTimeline(): array
    {
        $active = $this->activeQuarter();
        $timeline = [];

        for ($q = 1; $q <= 4; $q++) {
            if ($q < $active['q']) {
                $status = 'closed';
            } elseif ($q === $active['q']) {
                $status = 'open';
            } else {
                $status = 'upcoming';
            }

            $timeline[] = [
                'q' => $q,
                'roman' => $this->roman($q),
                'label' => 'Triwulan ' . $this->roman($q),
                'year' => $active['year'],
                'period' => $active['year'] . '-Q' . $q,
                'status' => $status,
                'months' => self::QUARTER_MONTHS[$q],
                'opens' => self::OPENS_MONTH[$q],
            ];
        }

        return $timeline;
    }

    private function roman(int $q): string
    {
        return self::ROMAN[$q - 1] ?? (string) $q;
    }

    /* ====================================================================
     |  Roster lookups
     * ==================================================================== */

    /** @return array<int, string> */
    public function teams(): array
    {
        return $this->teams;
    }

    /**
     * Everyone who may cast a ballot: candidate-members plus leaders (who vote
     * but are not candidates). Keyed by name => the teams they vote in.
     *
     * @return array<string, array<int, string>> name => teams
     */
    public function activeMembers(): array
    {
        $active = $this->candidateMembers();
        foreach (array_keys($this->leaders) as $name) {
            $active[$name] = $this->leaderTeams($name);
        }

        return $active;
    }

    /** @return array<int, string> sorted list of active member names */
    public function activeMemberNames(): array
    {
        $names = array_keys($this->activeMembers());
        sort($names, SORT_NATURAL | SORT_FLAG_CASE);

        return $names;
    }

    public function isActiveMember(string $name): bool
    {
        return array_key_exists($name, $this->activeMembers());
    }

    /** @return array<int, string> teams this person votes in (member teams, or all teams for a leader) */
    public function teamsFor(string $name): array
    {
        $teams = $this->members[$name] ?? [];
        if (empty($teams) && array_key_exists($name, $this->leaders)) {
            $teams = $this->leaderTeams($name);
        }

        return $teams;
    }

    /** @return array<int, string> candidate names in a team, naturally sorted (leaders excluded) */
    public function teamMembers(string $team): array
    {
        $names = [];
        foreach ($this->candidateMembers() as $name => $teams) {
            if (in_array($team, $teams, true)) {
                $names[] = $name;
            }
        }
        sort($names, SORT_NATURAL | SORT_FLAG_CASE);

        return $names;
    }

    /**
     * Build the personal ballot for a voter: each team they belong to, with the
     * candidates (fellow members of that team, including themselves).
     *
     * @return array<int, array{team: string, candidates: array<int, string>}>
     */
    public function ballotFor(string $name): array
    {
        $ballot = [];
        foreach ($this->teamsFor($name) as $team) {
            $ballot[] = [
                'team' => $team,
                'candidates' => $this->teamMembers($team),
            ];
        }

        return $ballot;
    }

    /**
     * Full roster as a JS-friendly structure: name => list of teams+candidates.
     * Used to build the ballot on the client once the voter picks their identity.
     *
     * @return array<string, array<int, array{team:string, candidates:array<int,string>}>>
     */
    public function ballotData(): array
    {
        $data = [];
        foreach ($this->activeMemberNames() as $name) {
            $data[$name] = $this->ballotFor($name);
        }

        return $data;
    }

    /* ====================================================================
     |  Identity matching (account name  <->  roster name)
     * ==================================================================== */

    /**
     * Normalise a name for matching so an account can be tied to a roster entry
     * regardless of academic titles or comma placement. Titles are stripped
     * whether or not they follow a comma, e.g.
     *   "Renata Putri Henessa"            -> "renata putri henessa"
     *   "Renata Putri Henessa, S.Tr.Stat" -> "renata putri henessa"
     *   "Hanifah Ayu SST"                 -> "hanifah ayu"
     *   "Hogan Da Costa Sinurat S.M."     -> "hogan da costa sinurat"
     */
    public function normalize(string $name): string
    {
        $name = str_replace(',', ' ', mb_strtolower(trim($name)));
        $tokens = preg_split('/\s+/', $name) ?: [];

        $keep = [];
        foreach ($tokens as $token) {
            // Compact form without dots, e.g. "s.tr.stat." -> "strstat".
            $compact = preg_replace('/[^a-z]/', '', $token);
            if ($compact === '') {
                continue;
            }
            // Drop known gelar and any token that carries a dot (gelar shorthand).
            if (in_array($compact, self::GELAR, true) || str_contains($token, '.')) {
                continue;
            }
            $keep[] = $compact;
        }

        return trim(implode(' ', $keep));
    }

    /**
     * Try to match an authenticated account name to a single roster member.
     * Returns the roster name on an unambiguous match, otherwise null (the user
     * will then pick their name from the roster manually).
     */
    public function matchAccount(?string $accountName): ?string
    {
        if (! $accountName) {
            return null;
        }

        $target = $this->normalize($accountName);
        if ($target === '') {
            return null;
        }

        $matches = [];
        foreach (array_keys($this->activeMembers()) as $name) {
            if ($this->normalize($name) === $target) {
                $matches[] = $name;
            }
        }

        return count($matches) === 1 ? $matches[0] : null;
    }

    /* ====================================================================
     |  Tally
     * ==================================================================== */

    /**
     * Tally votes per team.
     *
     * @param  Collection<int, \App\Models\OmegaVote>  $votes
     * @return array<int, array{team: string, total: int, tallies: array<int, array{name: string, votes: int}>}>
     */
    public function tally(Collection $votes): array
    {
        $byTeam = $votes->groupBy('team');
        $results = [];

        foreach ($this->teams() as $team) {
            $teamVotes = $byTeam->get($team, collect());
            $counts = $teamVotes->groupBy('candidate_name')
                ->map->count()
                ->sortDesc();

            $tallies = [];
            foreach ($counts as $candidate => $count) {
                $tallies[] = ['name' => $candidate, 'votes' => $count];
            }

            $results[] = [
                'team' => $team,
                'total' => $teamVotes->count(),
                'tallies' => $tallies,
            ];
        }

        return $results;
    }
}

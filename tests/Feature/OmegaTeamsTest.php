<?php

namespace Tests\Feature;

use App\Models\OmegaPeriodRoster;
use App\Models\OmegaVote;
use App\Models\User;
use App\Services\OmegaRoster;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * OMEGA "Susunan Tim": switching employees between teams per period.
 *
 * Runs on its own in-memory SQLite database with only the tables OMEGA needs,
 * so it does not depend on the MySQL connection configured in .env.
 */
class OmegaTeamsTest extends TestCase
{
    private User $ivana;

    private User $staff;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'database.connections.sqlite.foreign_key_constraints' => false,
        ]);
        DB::purge('sqlite');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_it_staff')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
        (require database_path('migrations/2026_07_20_000001_create_omega_votes_table.php'))->up();
        (require database_path('migrations/2026_10_27_000001_create_omega_period_rosters_table.php'))->up();

        // October 2026: voting is open for Triwulan III, Triwulan IV is next.
        Carbon::setTestNow('2026-10-27 09:00:00');

        $this->ivana = User::forceCreate([
            'name' => 'Ivana Yoselin Purba Siboro, S.Tr.Stat.',
            'email' => 'yoselin.purba@bps.go.id',
            'password' => bcrypt('secret'),
        ]);
        $this->staff = User::forceCreate([
            'name' => 'Renata Putri Henessa, S.Tr.Stat.',
            'email' => 'renata@bps.go.id',
            'password' => bcrypt('secret'),
        ]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    private function roster(): OmegaRoster
    {
        return new OmegaRoster();
    }

    /** Submit a composition the way the page's form does. */
    private function save(string $period, array $members, array $teams)
    {
        $people = [];
        foreach ($members as $name => $memberTeams) {
            $people[] = ['name' => $name, 'teams' => $memberTeams];
        }

        return $this->actingAs($this->ivana)->post(route('omega.teams.save'), [
            'period' => $period,
            'teams' => $teams,
            'people' => $people,
        ]);
    }

    public function test_only_results_access_accounts_can_manage_teams(): void
    {
        $this->actingAs($this->staff)->get(route('omega.teams'))->assertForbidden();
        $this->actingAs($this->staff)->post(route('omega.teams.save'), ['period' => '2026-Q3'])->assertForbidden();
        $this->actingAs($this->staff)->delete(route('omega.teams.reset'), ['period' => '2026-Q3'])->assertForbidden();

        $this->actingAs($this->ivana)->get(route('omega.teams'))
            ->assertOk()
            ->assertSee('Susunan Tim Pegawai')
            ->assertSee('Triwulan III 2026')
            ->assertSee('Triwulan IV 2026')
            ->assertSee('Tim 2026.xlsx');

        // The other admin pages link to it.
        $this->actingAs($this->ivana)->get(route('omega.results'))->assertOk()->assertSee(route('omega.teams'));
        $this->actingAs($this->ivana)->get(route('omega.results.detail'))->assertOk()->assertSee(route('omega.teams'));
    }

    public function test_saving_the_open_period_changes_the_ballot(): void
    {
        $renata = 'Renata Putri Henessa, S.Tr.Stat.';
        $this->assertContains('IT', $this->roster()->teamsFor($renata));

        $members = config('omega.members');
        $members[$renata] = ['HUMAS', 'TIM BARU'];
        $members['Pegawai Baru, S.Tr.Stat.'] = ['tim   baru'];
        // Leaders are configured separately and must be ignored if submitted.
        $members['Devi Indriastuti, SST, M.Si.'] = ['IT'];
        // Nobody left in PROTOKOLER -> the team is dropped.
        foreach ($members as $name => $teams) {
            $members[$name] = array_values(array_diff($teams, ['PROTOKOLER']));
        }

        $this->save('2026-Q3', $members, [...config('omega.teams'), 'Tim Baru'])
            ->assertRedirect(route('omega.teams', ['period' => '2026-Q3']));

        $saved = OmegaPeriodRoster::where('period', '2026-Q3')->firstOrFail();
        $this->assertSame($this->ivana->id, $saved->updated_by);
        $this->assertNotContains('PROTOKOLER', $saved->teams);
        $this->assertContains('TIM BARU', $saved->teams);
        $this->assertArrayNotHasKey('Devi Indriastuti, SST, M.Si.', $saved->members);

        $roster = $this->roster();
        $this->assertSame(['HUMAS', 'TIM BARU'], $roster->teamsFor($renata));
        $this->assertSame(['Pegawai Baru, S.Tr.Stat.', $renata], $roster->teamMembers('TIM BARU'));
        $this->assertTrue($roster->isActiveMember('Pegawai Baru, S.Tr.Stat.'));
        // Leaders still vote everywhere, including the new team.
        $this->assertContains('TIM BARU', $roster->teamsFor('Devi Indriastuti, SST, M.Si.'));

        // The voting page builds Renata's ballot from the new composition.
        $this->actingAs($this->staff)->get(route('omega.index'))
            ->assertOk()
            ->assertSee('TIM BARU');
    }

    public function test_next_period_can_be_prepared_without_touching_the_open_vote(): void
    {
        $renata = 'Renata Putri Henessa, S.Tr.Stat.';
        $members = config('omega.members');
        $members[$renata] = ['SOSIAL'];

        $this->save('2026-Q4', $members, config('omega.teams'))->assertRedirect();

        // Triwulan III (open now) still uses the original composition...
        $this->assertContains('IT', $this->roster()->teamsFor($renata));
        $this->assertNull($this->roster()->source());
        // ...Triwulan IV uses the new one, and it carries over to later periods.
        $this->assertSame(['SOSIAL'], $this->roster()->forPeriod('2026-Q4')->teamsFor($renata));
        $this->assertSame(['SOSIAL'], $this->roster()->forPeriod('2027-Q1')->teamsFor($renata));
        $this->assertSame('2026-Q4', $this->roster()->forPeriod('2027-Q1')->source()->period);

        // In January the new composition becomes the live one automatically.
        Carbon::setTestNow('2027-01-05 09:00:00');
        $this->assertSame(['SOSIAL'], $this->roster()->teamsFor($renata));
    }

    public function test_reset_returns_the_period_to_the_previous_composition(): void
    {
        $members = config('omega.members');
        $members['Renata Putri Henessa, S.Tr.Stat.'] = ['SOSIAL'];
        $this->save('2026-Q3', $members, config('omega.teams'));

        $this->actingAs($this->ivana)
            ->delete(route('omega.teams.reset'), ['period' => '2026-Q3'])
            ->assertRedirect(route('omega.teams', ['period' => '2026-Q3']));

        $this->assertSame(0, OmegaPeriodRoster::count());
        $this->assertContains('IT', $this->roster()->teamsFor('Renata Putri Henessa, S.Tr.Stat.'));
    }

    public function test_only_the_open_and_next_period_are_editable(): void
    {
        $this->save('2026-Q2', config('omega.members'), config('omega.teams'))->assertStatus(422);
        $this->save('2027-Q1', config('omega.members'), config('omega.teams'))->assertStatus(422);
        $this->assertSame(0, OmegaPeriodRoster::count());
    }

    public function test_a_composition_with_no_team_members_is_rejected(): void
    {
        $members = array_map(fn () => [], config('omega.members'));

        $this->save('2026-Q3', $members, config('omega.teams'))->assertSessionHasErrors('teams');
        $this->assertSame(0, OmegaPeriodRoster::count());
    }

    public function test_votes_in_a_team_removed_later_still_show_in_the_tally(): void
    {
        OmegaVote::create([
            'period' => '2026-Q3',
            'voter_name' => 'Arief Tirtana',
            'team' => 'PROTOKOLER',
            'candidate_name' => 'Desmaini, S.Si',
        ]);

        $members = array_map(fn ($teams) => array_values(array_diff($teams, ['PROTOKOLER'])), config('omega.members'));
        $this->save('2026-Q3', $members, config('omega.teams'));

        $tally = collect($this->roster()->tally(OmegaVote::all()))->keyBy('team');
        $this->assertSame(1, $tally['PROTOKOLER']['total']);
    }
}

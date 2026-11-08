<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * IT staff can set a new password for any user (e.g. one who forgot theirs)
 * from the "Manajemen Pengguna" modal on the welcome dashboard, without
 * knowing the old password.
 *
 * Runs on its own in-memory SQLite database, like OmegaTeamsTest.
 */
class UserPasswordResetTest extends TestCase
{
    private User $itStaff;

    private User $employee;

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
            $table->string('phone_number')->nullable();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_it_staff')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
        // The dashboard looks up the user's team.
        Schema::create('user_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tim_id')->nullable();
            $table->timestamps();
        });

        $this->itStaff = User::forceCreate([
            'name' => 'Renata Putri Henessa, S.Tr.Stat.',
            'email' => 'renata@bps.go.id',
            'password' => bcrypt('secret'),
            'is_it_staff' => true,
        ]);
        $this->employee = User::forceCreate([
            'name' => 'Pegawai Lupa Password',
            'email' => 'lupa@bps.go.id',
            'password' => bcrypt('old-password'),
            'remember_token' => 'old-remember-token',
        ]);
    }

    private function reset(User $actor, array $data)
    {
        return $this->actingAs($actor)
            ->from(route('welcome'))
            ->put(route('users.password.update'), $data);
    }

    public function test_it_staff_can_set_a_new_password_without_the_old_one(): void
    {
        $this->reset($this->itStaff, [
            'user_id' => $this->employee->id,
            'password' => 'password-baru-123',
            'password_confirmation' => 'password-baru-123',
        ])
            ->assertRedirect(route('welcome'))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('user_password_reset', 'Password untuk "Pegawai Lupa Password" berhasil diubah.');

        $this->employee->refresh();
        $this->assertTrue(Hash::check('password-baru-123', $this->employee->password));
        $this->assertNotSame('old-remember-token', $this->employee->remember_token);
        // Nobody else is touched.
        $this->assertTrue(Hash::check('secret', $this->itStaff->fresh()->password));
    }

    public function test_only_it_staff_can_change_other_users_passwords(): void
    {
        $this->reset($this->employee, [
            'user_id' => $this->itStaff->id,
            'password' => 'password-baru-123',
            'password_confirmation' => 'password-baru-123',
        ])->assertForbidden();

        $this->assertTrue(Hash::check('secret', $this->itStaff->fresh()->password));
    }

    public function test_invalid_input_is_rejected_into_its_own_error_bag(): void
    {
        $this->reset($this->itStaff, [
            'user_id' => $this->employee->id,
            'password' => 'password-baru-123',
            'password_confirmation' => 'tidak-sama',
        ])
            ->assertRedirect(route('welcome'))
            ->assertSessionHasErrorsIn('resetPassword', ['password']);

        $this->reset($this->itStaff, [
            'user_id' => 999,
            'password' => 'pendek',
            'password_confirmation' => 'pendek',
        ])->assertSessionHasErrorsIn('resetPassword', ['user_id', 'password']);

        $this->reset($this->itStaff, [
            'password' => 'password-baru-123',
            'password_confirmation' => 'password-baru-123',
        ])->assertSessionHasErrorsIn('resetPassword', ['user_id']);

        $this->assertTrue(Hash::check('old-password', $this->employee->fresh()->password));
    }

    public function test_dashboard_lists_users_for_it_staff_only(): void
    {
        $this->actingAs($this->itStaff)->get(route('welcome'))
            ->assertOk()
            ->assertSee('Ubah Password')
            ->assertSee(route('users.password.update'))
            ->assertSee('lupa@bps.go.id');

        $this->actingAs($this->employee)->get(route('welcome'))
            ->assertOk()
            ->assertDontSee(route('users.password.update'))
            ->assertDontSee('renata@bps.go.id');
    }

    public function test_failed_submit_reopens_the_change_password_tab(): void
    {
        $this->reset($this->itStaff, [
            'user_id' => $this->employee->id,
            'password' => 'password-baru-123',
            'password_confirmation' => 'tidak-sama',
        ]);

        $this->actingAs($this->itStaff)->get(route('welcome'))
            ->assertOk()
            ->assertSee('Konfirmasi password tidak cocok.')
            ->assertSee('id="user-tab-password" data-user-tab="password" aria-controls="user-panel-password" aria-selected="true"', false)
            ->assertSee('id="rp-user-id" value="' . $this->employee->id . '"', false);
    }
}

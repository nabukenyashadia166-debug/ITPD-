<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAdminUserCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_admin_with_a_hashed_password_that_can_log_in(): void
    {
        $this->artisan('admin:create', [
            '--name' => 'Sam Admin',
            '--email' => 'admin@example.com',
        ])
            ->expectsQuestion('Password (minimum 8 characters)', 'secure-password')
            ->expectsQuestion('Confirm password', 'secure-password')
            ->expectsOutput('Administrator account created successfully.')
            ->assertExitCode(0);

        $admin = User::where('email', 'admin@example.com')->firstOrFail();

        $this->assertTrue($admin->is_admin);
        $this->assertTrue(Hash::check('secure-password', $admin->password));

        $this->post(route('admin.login.submit'), [
            'email' => 'admin@example.com',
            'password' => 'secure-password',
        ])->assertRedirect(route('AdminDashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_it_does_not_create_an_account_when_passwords_do_not_match(): void
    {
        $this->artisan('admin:create', [
            '--name' => 'Sam Admin',
            '--email' => 'admin@example.com',
        ])
            ->expectsQuestion('Password (minimum 8 characters)', 'secure-password')
            ->expectsQuestion('Confirm password', 'different-password')
            ->expectsOutput('The password field confirmation does not match.')
            ->assertExitCode(1);

        $this->assertDatabaseMissing('users', ['email' => 'admin@example.com']);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_an_authenticated_user_and_shows_the_name_on_dashboard(): void
    {
        $response = $this->post(route('register.submit'), [
            'role' => 'User',
            'name' => 'Amina User',
            'email' => 'amina@example.com',
            'telephone' => '0712345678',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Amina User',
            'email' => 'amina@example.com',
            'telephone' => '0712345678',
        ]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Amina User');
    }

    public function test_existing_user_can_log_in_and_log_out(): void
    {
        $user = User::factory()->create([
            'name' => 'Amina User',
            'email' => 'amina@example.com',
            'password' => 'password123',
        ]);

        $this->post(route('login.submit'), [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Amina User');

        $this->post(route('logout'))
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_admin_login_page_only_authenticates_administrators(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password123',
            'is_admin' => true,
        ]);
        $ordinaryUser = User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password123',
            'is_admin' => false,
        ]);

        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Administrator Login');

        $this->post(route('admin.login.submit'), [
            'email' => $ordinaryUser->email,
            'password' => 'password123',
        ])->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post(route('admin.login.submit'), [
            'email' => $admin->email,
            'password' => 'password123',
        ])->assertRedirect(route('AdminDashboard'));

        $this->assertAuthenticatedAs($admin);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_as_a_client(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Client',
            'email' => 'client@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'client@example.com',
            'role' => 'client',
        ]);
    }

    public function test_user_can_log_in_and_is_redirected_by_role(): void
    {
        $user = User::factory()->create([
            'email' => 'client@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'client',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);

        $response->assertRedirect('/client/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'client@example.com',
            'password' => Hash::make('Password123!'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'client@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login')
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_log_out(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}

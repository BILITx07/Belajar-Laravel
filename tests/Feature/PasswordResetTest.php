<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    }

    public function test_forgot_password_fails_for_non_existent_email(): void
    {
        $response = $this->post('/forgot-password', [
            'email' => 'tidakada@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_forgot_password_generates_token_for_existing_user(): void
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
        ]);

        $response = $this->post('/forgot-password', [
            'email' => 'testuser@example.com',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'testuser@example.com',
        ]);
    }

    public function test_password_can_be_reset(): void
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => Hash::make('oldpassword123'),
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => 'testuser@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/login');
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }
}

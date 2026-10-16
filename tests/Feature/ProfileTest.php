<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Bili User',
            'email' => 'bili@example.com',
        ]);

        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
        $response->assertSee('Bili User');
        $response->assertSee('bili@example.com');
    }

    public function test_user_can_update_profile_name(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Lama',
        ]);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'Nama Baru',
        ]);

        $response->assertSessionHas('success_profile');
        $this->assertEquals('Nama Baru', $user->fresh()->name);
    }

    public function test_user_can_update_password_with_correct_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'password123',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);

        $response->assertSessionHas('success_password');
        $this->assertTrue(Hash::check('newpassword456', $user->fresh()->password));
    }

    public function test_user_cannot_update_password_with_wrong_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'salah123',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('password123', $user->fresh()->password));
    }
}

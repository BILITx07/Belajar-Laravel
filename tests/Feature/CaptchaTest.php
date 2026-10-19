<?php

namespace Tests\Feature;

use Tests\TestCase;

class CaptchaTest extends TestCase
{
    public function test_captcha_image_generation(): void
    {
        $response = $this->get('/captcha');

        $response->assertStatus(200);
        $this->assertEquals('image/png', $response->headers->get('Content-Type'));
        $this->assertNotEmpty(session('captcha_code'));
        $this->assertEquals(5, strlen(session('captcha_code')));
    }

    public function test_login_page_renders_captcha(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('captcha-img');
        $response->assertSee('Kode CAPTCHA');
    }

    public function test_login_fails_with_invalid_captcha(): void
    {
        $this->get('/captcha');
        
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'secret123',
            'captcha' => 'WRONG',
        ]);

        $response->assertSessionHas('loginError', 'Kode CAPTCHA salah atau sudah kedaluwarsa.');
    }

    public function test_bot_honeypot_triggers_block(): void
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'secret123',
            'captcha' => 'ANY',
            '_hp_check' => 'I am a spambot',
        ]);

        $response->assertSessionHas('loginError', 'Akses diblokir: Terdeteksi aktivitas bot.');
    }

    public function test_register_honeypot_triggers_block(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Bot',
            'last_name' => 'Tester',
            'email' => 'bot@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            '_hp_check' => 'I am a spambot',
        ]);

        $response->assertSessionHas('error', 'Akses diblokir: Terdeteksi aktivitas bot.');
    }

    public function test_geojson_directory_exists(): void
    {
        $this->assertTrue(file_exists(public_path('geojson')));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index', [
            'title' => 'Login',
        ]);
    }

    public function authenticate(Request $request)
    {
        // Deteksi Honeypot (bot otomatis biasanya mengisi semua input tersembunyi)
        if (!empty($request->input('_hp_check'))) {
            return back()->with('loginError', 'Akses diblokir: Terdeteksi aktivitas bot.');
        }

        // Validasi input email dan password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Verifikasi Google reCAPTCHA v3 (Invisible) jika secret key dikonfigurasi
        $recaptchaSecret = config('services.recaptcha.secret_key');
        if (!empty($recaptchaSecret)) {
            $token = $request->input('g-recaptcha-response');
            if (empty($token)) {
                return back()
                    ->withInput($request->only('email'))
                    ->with('loginError', 'Verifikasi keamanan reCAPTCHA gagal (token kosong). Silakan refresh halaman.');
            }

            try {
                $response = Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $recaptchaSecret,
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);

                $result = $response->json();
                $minScore = (float) config('services.recaptcha.minimum_score', 0.5);

                if (empty($result['success']) || (isset($result['score']) && $result['score'] < $minScore)) {
                    return back()
                        ->withInput($request->only('email'))
                        ->with('loginError', 'Akses diblokir: Terdeteksi aktivitas mencurigakan oleh reCAPTCHA.');
                }
            } catch (\Throwable $e) {
                logger()->error('reCAPTCHA validation error: ' . $e->getMessage());
            }
        }

        // Ambil kredensial email dan password
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Proses autentikasi login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            Auth::user()->update(['last_login_at' => now()]);

            return redirect()->intended('/dashboard');
        }

        // Jika email atau password salah
        return back()
            ->withInput($request->only('email'))
            ->with('loginError', 'Login gagal! Periksa kembali email dan password Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('peta')->with(
            'success',
            'Anda telah berhasil keluar dari akun petugas.'
        );
    }
}
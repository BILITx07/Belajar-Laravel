<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'captcha' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'captcha.required' => 'Kode CAPTCHA wajib diisi.',
        ]);

        // Cek dan cocokkan CAPTCHA
        $inputCaptcha = strtoupper(trim((string) $request->captcha));
        $sessionCaptcha = session('captcha_code');

        // Hapus kode CAPTCHA dari session setelah dicek agar tidak bisa digunakan ulang (anti-replay)
        session()->forget('captcha_code');

        if (empty($sessionCaptcha) || $inputCaptcha !== $sessionCaptcha) {
            return back()
                ->withInput($request->only('email'))
                ->with('loginError', 'Kode CAPTCHA salah atau sudah kedaluwarsa.');
        }

        // Ambil kredensial email dan password
        $credentials = $request->only('email', 'password');

        // Proses autentikasi login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

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

        return redirect('/login')->with(
            'success',
            'Anda telah berhasil logout.'
        );
    }
}
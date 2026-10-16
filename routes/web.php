<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\ProfileController;
use App\Models\User;

Route::middleware('guest')->group(function () {
    Route::get('/captcha', [CaptchaController::class, 'generate'])->name('captcha');
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
    Route::get('/register', [RegistrasiController::class, 'index'])->name('register');
    Route::post('/register', [RegistrasiController::class, 'store']);
});

// Route Lupa Password & Reset Password (Bisa diakses publik)
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
    ], [
        'email.required' => 'Alamat email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
    ]);

    $normalizedEmail = trim(strtolower($request->email));
    $user = User::where('email', $normalizedEmail)->first();

    if (!$user) {
        return back()->withInput()->withErrors([
            'email' => 'Email tidak terdaftar dalam sistem kami. Pastikan alamat email yang Anda masukkan sudah benar.',
        ]);
    }

    // Buat token reset password resmi Laravel
    $token = Password::broker()->createToken($user);
    $directResetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);

    // Simpan link langsung ke session untuk kenyamanan (terutama di InfinityFree / hosting tanpa SMTP aktif)
    session()->flash('direct_reset_url', $directResetUrl);

    // Coba kirimkan email notifikasi (abaikan jika server hosting memblokir port SMTP)
    try {
        Password::broker()->sendResetLink(['email' => $user->email]);
    } catch (\Throwable $e) {
        // Pada web hosting gratis seperti InfinityFree, port SMTP diblokir
        // Direct reset link di atas memastikan proses reset password tetap 100% berjalan lancar
    }

    return back()->with('status', 'Permintaan reset password berhasil diproses.');
})->name('password.email');

Route::get('/reset-password/{token}', function (Illuminate\Http\Request $request, string $token) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->query('email') ?? $request->email,
    ]);
})->name('password.reset');

// Proses Reset Password
Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => ['required', 'confirmed', 'min:8'],
    ], [
        'token.required' => 'Token reset password tidak valid.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Password baru wajib diisi.',
        'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        'password.min' => 'Password baru minimal harus 8 karakter.',
    ]);

    $status = Password::reset(
        $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        ),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->save();
        }
    );

    if ($status === Password::PasswordReset) {
        return redirect()->route('login')
            ->with('success', 'Password Anda berhasil diperbarui! Silakan login dengan password baru.');
    }

    return back()->withErrors([
        'email' => 'Token reset password tidak valid atau sudah kedaluwarsa. Silakan ajukan ulang permintaan lupa password.',
    ]);
})->name('password.update');

// Route Logout (Hanya untuk yang sudah login)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// =========================================================================
// HALAMAN UTAMA: WEBGIS PETA INTERAKTIF BWS KALIMANTAN I
// Fokus 1 Halaman Utama untuk Dokumentasi & Laporan Magang
// =========================================================================
Route::get('/', [DashboardController::class, 'peta'])->name('peta');
Route::get('/peta', [DashboardController::class, 'peta'])->name('peta.index');

// Operasi Pengelolaan Data Spasial GIS (Upload & Hapus Layer)
Route::post('/upload-geojson', [DashboardController::class, 'uploadGeojson'])->name('peta.upload-geojson');
Route::delete('/delete-geojson', [DashboardController::class, 'deleteGeojson'])->name('peta.delete-geojson');
Route::delete('/delete-geojson-bulk', [DashboardController::class, 'deleteGeojsonBulk'])->name('peta.delete-geojson-bulk');

// Route yang Memerlukan Login (Khusus Manajemen Profil / User jika diperlukan)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Route Manajemen User (Superadmin & Admin)
    Route::middleware('role:superadmin,admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });
    Route::middleware('role:superadmin')->group(function () {
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

// Route penampil file storage (memastikan gambar selalu bisa diakses)
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*')->name('storage.file');

// Route Health Check & Diagnostik Sistem
Route::get('/health-check', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $dbStatus = 'Connected: ' . \Illuminate\Support\Facades\DB::connection()->getDatabaseName();
    } catch (\Throwable $e) {
        $dbStatus = 'Error: ' . $e->getMessage();
    }

    return response()->json([
        'status' => 'ok',
        'php' => PHP_VERSION,
        'app_key' => !empty(config('app.key')),
        'db_connection' => config('database.default'),
        'db_status' => $dbStatus,
        'session_driver' => config('session.driver'),
    ]);
});

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\WebGisController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\ProfileController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes - WebGIS BWS Kalimantan I
|--------------------------------------------------------------------------
*/

// =========================================================================
// 1. HALAMAN PUBLIK / USER (TANPA LOGIN)
// =========================================================================
Route::get('/', [WebGisController::class, 'index'])->name('home');
Route::get('/peta', [WebGisController::class, 'index'])->name('peta');

// =========================================================================
// 2. SISTEM AUTENTIKASI (LOGIN & LOGOUT)
// =========================================================================
Route::middleware('guest')->group(function () {
    Route::get('/captcha', [CaptchaController::class, 'generate'])->name('captcha');
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
    Route::get('/register', [RegistrasiController::class, 'index'])->name('register');
    Route::post('/register', [RegistrasiController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Reset Password
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => ['required', 'email']]);
    $user = User::where('email', trim(strtolower($request->email)))->first();

    if (!$user) {
        return back()->withInput()->withErrors(['email' => 'Email tidak terdaftar dalam sistem kami.']);
    }

    $token = Password::broker()->createToken($user);
    $directResetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);
    session()->flash('direct_reset_url', $directResetUrl);

    try {
        Password::broker()->sendResetLink(['email' => $user->email]);
    } catch (\Throwable $e) {}

    return back()->with('status', 'Permintaan reset password berhasil diproses.');
})->name('password.email');

Route::get('/reset-password/{token}', function (Request $request, string $token) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->query('email') ?? $request->email,
    ]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token'    => ['required'],
        'email'    => ['required', 'email'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill(['password' => \Illuminate\Support\Facades\Hash::make($password)])->save();
        }
    );

    if ($status === Password::PasswordReset) {
        return redirect()->route('login')->with('success', 'Password berhasil diperbarui! Silakan login.');
    }

    return back()->withErrors(['email' => 'Token tidak valid atau sudah kedaluwarsa.']);
})->name('password.update');

// =========================================================================
// 3. HALAMAN ADMIN & DASHBOARD (TERPROTEKSI MIDDLEWARE AUTH)
//    Mendukung rute /dashboard dan /admin/dashboard tanpa error 404
// =========================================================================
Route::middleware('auth')->group(function () {

    // Route /dashboard utama (menghindari error 404 saat redirect login default Laravel)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Grup rute /admin/*
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('index');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/kelola-gis', [AdminController::class, 'kelolaGis'])->name('kelola-gis');
        Route::get('/data-gis', [AdminController::class, 'kelolaGis'])->name('data-gis');

        // Aksi CRUD Data Spasial
        Route::post('/upload', [AdminController::class, 'upload'])->name('upload');
        Route::post('/rename', [AdminController::class, 'rename'])->name('rename');
        Route::delete('/delete', [AdminController::class, 'delete'])->name('delete');
        Route::delete('/delete-bulk', [AdminController::class, 'deleteBulk'])->name('delete-bulk');
    });

    // Profile & Akun
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Manajemen User (Hanya Super Admin)
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

// =========================================================================
// 4. ROUTE UTILITAS
// =========================================================================
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*')->name('storage.file');

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'php'    => PHP_VERSION,
        'app_key'=> !empty(config('app.key')),
        'db'     => config('database.default'),
    ]);
});

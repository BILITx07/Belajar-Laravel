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


Route::middleware('guest')->group(function () {
    Route::get('/captcha', [CaptchaController::class, 'generate'])->name('captcha');
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
    Route::get('/register', [RegistrasiController::class, 'index'])->name('register');
    Route::post('/register', [RegistrasiController::class, 'store']);
});

// Route Lupa Password & Reset Password (Bisa diakses publik maupun user login)
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );
    return $status === Password::ResetLinkSent
        ? back()->with(['status' => 'Link reset password telah dikirim ke email Anda.'])
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

Route::get('/reset-password/{token}', function (Illuminate\Http\Request $request, string $token) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->email
    ]);
})->name('password.reset');

// Proses Reset Password
Route::post('/reset-password', function (Request $request) {

    $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => ['required', 'confirmed', 'min:8'],
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
                'password' => bcrypt($password),
            ])->save();

        }
    );

    return $status === Password::PasswordReset
        ? redirect()->route('login')
            ->with('status', 'Password berhasil diubah. Silakan login.')
        : back()->withErrors([
            'email' => [__($status)]
        ]);

})->name('password.update');


// Route Logout (Hanya untuk yang sudah login)

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route Halaman Publik (Opsional)
Route::get('/contact', function () {
    return view('contact');
});
Route::get('/about', function () {
    return view('About');
});

// Route yang Memerlukan Login (Auth Middleware)

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/profile', function () {
        return view('auth.forgot-password');
    })->name('profile');
    Route::get('/peta', [DashboardController::class, 'peta'])->name('peta');
    Route::post('/upload-geojson', [DashboardController::class, 'uploadGeojson'])->name('peta.upload-geojson');
    Route::delete('/delete-geojson', [DashboardController::class, 'deleteGeojson'])->name('peta.delete-geojson');
    Route::delete('/delete-geojson-bulk', [DashboardController::class, 'deleteGeojsonBulk'])->name('peta.delete-geojson-bulk');
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show');
    Route::get('/produk/{id}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{id}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');
    Route::resource('kategori', KategoriController::class);

    // Route Manajemen User
    // GET /users: bisa diakses superadmin DAN admin (tampilan berbeda)
    Route::middleware('role:superadmin,admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });

    // Update & Delete: hanya superadmin
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

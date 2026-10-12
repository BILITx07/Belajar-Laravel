<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $title ?? 'Login' }} - Toko Kami</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('sbadmin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('sbadmin/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border-radius: 1rem;
            overflow: hidden;
        }
        .login-icon-circle {
            width: 68px;
            height: 68px;
            background: rgba(78, 115, 223, 0.12);
            color: #4e73df;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .form-control-custom {
            height: 48px;
            border-radius: 8px;
            border: 1px solid #d1d3e2;
            padding: 10px 16px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .form-control-custom:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.18);
        }
        .btn-login-submit {
            height: 48px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .btn-login-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.35);
        }
        .captcha-box {
            border: 1px solid #d1d3e2;
            border-radius: 8px;
            background-color: #f8f9fc;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3px;
        }
        .btn-refresh-captcha {
            height: 48px;
            width: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body class="bg-gradient-primary py-4">

    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11">

                <div class="card login-card border-0 shadow-lg my-3">
                    <div class="card-body p-4 p-sm-5">

                        <!-- Header Logo & Judul -->
                        <div class="text-center mb-4">
                            <div class="login-icon-circle mb-3 shadow-sm">
                                <i class="fas fa-store fa-2x"></i>
                            </div>
                            <h1 class="h4 text-gray-900 font-weight-bold mb-1">Selamat Datang Kembali!</h1>
                            <p class="text-muted small mb-0">Silakan masuk ke akun Anda</p>
                        </div>

                        {{-- Pesan Notifikasi Sukses Logout --}}
                        @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <div>{{ session('success') }}</div>
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- Pesan Notifikasi Gagal Login --}}
                        @if(session()->has('loginError'))
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <div>{{ session('loginError') }}</div>
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- Pesan Status (misal setelah reset password) --}}
                        @if (session('status'))
                            <div class="alert alert-info alert-dismissible fade show shadow-sm mb-3" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <div>{{ session('status') }}</div>
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <!-- Form Login -->
                        <form action="{{ route('login') }}" method="POST" autocomplete="off">
                            @csrf

                            <!-- Input Email -->
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700 mb-1" for="email">
                                    <i class="fas fa-envelope text-primary mr-1"></i> Email
                                </label>
                                <input type="email" 
                                       name="email" 
                                       class="form-control form-control-custom @error('email') is-invalid @enderror"
                                       id="email" 
                                       placeholder="nama@email.com" 
                                       value="{{ old('email') }}" 
                                       required autofocus autocomplete="off">
                                @error('email')
                                    <div class="invalid-feedback ml-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Input Password & Lupa Password -->
                            <div class="form-group mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="small font-weight-bold text-gray-700 mb-0" for="password">
                                        <i class="fas fa-lock text-primary mr-1"></i> Password
                                    </label>
                                    <a href="{{ route('password.request') }}"
                                       class="small font-weight-bold text-danger text-decoration-none">
                                        Lupa Password?
                                    </a>
                                </div>
                                <input type="password" 
                                       name="password" 
                                       class="form-control form-control-custom @error('password') is-invalid @enderror"
                                       id="password" 
                                       placeholder="Masukkan kata sandi..." 
                                       required autocomplete="off">
                                @error('password')
                                    <div class="invalid-feedback ml-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Kode CAPTCHA -->
                            <div class="form-group mb-4">
                                <label class="small font-weight-bold text-gray-700 mb-1" for="captcha">
                                    <i class="fas fa-shield-alt text-primary mr-1"></i> Kode Keamanan (CAPTCHA)
                                </label>

                                <div class="d-flex align-items-center mb-2" style="gap: 8px;">
                                    <div class="captcha-box flex-grow-1 overflow-hidden shadow-sm">
                                        <img src="{{ route('captcha') }}" 
                                             id="captcha-img" 
                                             alt="Kode CAPTCHA" 
                                             height="40"
                                             style="cursor: pointer; display: block; max-width: 100%;" 
                                             title="Klik gambar untuk ganti CAPTCHA"
                                             onclick="refreshCaptcha()">
                                    </div>

                                    <button type="button" 
                                            id="btn-refresh-captcha"
                                            class="btn btn-outline-secondary btn-refresh-captcha shadow-sm"
                                            onclick="refreshCaptcha()"
                                            title="Ganti kode CAPTCHA">
                                        <i class="fas fa-sync-alt" id="refresh-icon"></i>
                                    </button>
                                </div>

                                {{-- Honeypot anti-bot otomatis --}}
                                <div style="display:none;" aria-hidden="true">
                                    <input type="text" name="_hp_check" value="" tabindex="-1" autocomplete="off">
                                </div>

                                <input type="text"
                                       name="captcha"
                                       id="captcha"
                                       class="form-control form-control-custom @error('captcha') is-invalid @enderror"
                                       placeholder="Ketik 5 huruf kode di atas..."
                                       autocomplete="off"
                                       maxlength="5"
                                       required>

                                @error('captcha')
                                    <div class="invalid-feedback ml-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Tombol Submit Login -->
                            <button type="submit" class="btn btn-primary btn-block btn-login-submit shadow">
                                <i class="fas fa-sign-in-alt mr-2"></i> Masuk Sekarang
                            </button>
                        </form>

                        <!-- Navigasi Daftar -->
                        <div class="text-center mt-4 pt-3 border-top">
                            <span class="small text-muted">Belum punya akun?</span>
                            <a class="small font-weight-bold text-primary ml-1" href="{{ route('register') }}">
                                Daftar Sekarang!
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('sbadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('sbadmin/js/sb-admin-2.min.js') }}"></script>

    <script>
        function refreshCaptcha() {
            const captchaImg = document.getElementById('captcha-img');
            const refreshIcon = document.getElementById('refresh-icon');

            if (refreshIcon) {
                refreshIcon.classList.add('fa-spin');
            }

            // Tambahkan parameter timestamp unik agar browser tidak mengambil dari cache
            captchaImg.src = "{{ route('captcha') }}?t=" + new Date().getTime();

            captchaImg.onload = function() {
                if (refreshIcon) {
                    refreshIcon.classList.remove('fa-spin');
                }
            };
        }
    </script>
</body>
</html>
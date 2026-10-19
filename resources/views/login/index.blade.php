<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $title ?? 'Login' }} - FEWS BWS Kalimantan I</title>

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
            background: linear-gradient(135deg, #0d2c54 0%, #1e56a0 100%);
        }
        .login-card {
            border-radius: 1rem;
            overflow: hidden;
        }
        .login-icon-circle {
            width: 72px;
            height: 72px;
            background: rgba(15, 59, 117, 0.1);
            color: #0f3b75;
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
            border-color: #0f3b75;
            box-shadow: 0 0 0 0.2rem rgba(15, 59, 117, 0.18);
        }
        .btn-login-submit {
            height: 48px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #0f3b75;
            border-color: #0f3b75;
            transition: all 0.2s ease;
        }
        .btn-login-submit:hover {
            background-color: #092244;
            border-color: #092244;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 59, 117, 0.35);
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

<body class="py-4">

    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11">

                <div class="card login-card border-0 shadow-lg my-3">
                    <div class="card-body p-4 p-sm-5">

                        <!-- Header Logo KemenPU & Judul (Sesuai Gambar Referensi) -->
                        <div class="d-flex align-items-center mb-4">
                            <!-- Logo Resmi Kementerian PU -->
                            <img src="{{ asset('img/logo-pu.png') }}" alt="Logo Kementerian PU" class="mr-3 shadow-sm" style="width: 48px; height: 48px; border-radius: 10px; object-fit: contain;">
                            <div>
                                <h1 class="h5 text-gray-900 font-weight-bold mb-0" style="line-height: 1.2;">Flood Early Warning System</h1>
                                <div class="text-primary font-weight-bold" style="font-size: 0.85rem;">BWS Kalimantan I Pontianak</div>
                            </div>
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

                            <!-- Tombol Submit Login (Persis Screenshot 1) -->
                            <button type="submit" class="btn btn-primary btn-block btn-login-submit shadow" style="background-color: #1b5fb5; border-color: #1b5fb5;">
                                <i class="fas fa-sign-in-alt mr-2"></i> Sign In to FEWS
                            </button>
                        </form>

                        <!-- Versi FEWS -->
                        <div class="text-center mt-4">
                            <span class="badge badge-light border text-muted px-3 py-1 font-weight-normal" style="border-radius: 20px; font-size: 0.75rem;">
                                Version 1.0.0
                            </span>
                        </div>

                    </div>
                </div>

                <!-- Footer Login BWSK -->
                <div class="text-center text-white-50 small my-3" style="font-size: 0.78rem;">
                    &copy; 2026 FEWS BWS Kalimantan I Pontianak
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
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $title ?? 'Masuk' }} - WEB GIS BWS KALIMANTAN I</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/logo-pu-badge.svg') }}">

    <!-- Font & Icons -->
    <link href="{{ asset('sbadmin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- SB Admin 2 & Bootstrap -->
    <link href="{{ asset('sbadmin/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --pu-blue: #1d4ed8;
            --pu-blue-hover: #1e40af;
            --pu-gold: #fad605;
            --pu-navy: #0f2b48;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #092c48 0%, #0d4b68 40%, #12637a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 18px 16px;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Lighting Background Spheres (Glassmorphism Depth) */
        .ambient-glow-1 {
            position: fixed;
            top: -12%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(13, 92, 112, 0.55) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(60px);
            pointer-events: none;
            z-index: 0;
        }

        .ambient-glow-2 {
            position: fixed;
            bottom: -15%;
            right: -10%;
            width: 520px;
            height: 520px;
            background: radial-gradient(circle, rgba(29, 78, 216, 0.4) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(65px);
            pointer-events: none;
            z-index: 0;
        }

        /* Glassmorphism Main Card Container (Compact, Elegan & Modern) */
        .auth-container {
            width: 100%;
            max-width: 860px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.65);
            box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.32),
                        0 0 1px 1px rgba(255, 255, 255, 0.5);
            overflow: hidden;
            display: flex;
            position: relative;
            z-index: 1;
        }

        /* Left Side: Spatial Visual Panel */
        .auth-visual-panel {
            flex: 1;
            position: relative;
            background: linear-gradient(150deg, #093c52 0%, #0d5c70 50%, #117887 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 36px 30px;
            color: #ffffff;
            overflow: hidden;
        }

        /* Geospatial Mesh Background */
        .spatial-mesh-bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.2) 1.2px, transparent 1.2px),
                radial-gradient(circle at 20% 40%, rgba(255, 255, 255, 0.16) 1.5px, transparent 1.5px),
                radial-gradient(circle at 60% 70%, rgba(255, 255, 255, 0.22) 2px, transparent 2px),
                radial-gradient(circle at 35% 85%, rgba(255, 255, 255, 0.15) 1.2px, transparent 1.2px);
            background-size: 100px 100px, 80px 80px, 120px 120px, 70px 70px;
            opacity: 0.85;
            pointer-events: none;
        }

        .spatial-lines {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(45deg, rgba(255, 255, 255, 0.04) 25%, transparent 25%),
                linear-gradient(-45deg, rgba(255, 255, 255, 0.04) 25%, transparent 25%);
            background-size: 50px 50px;
            pointer-events: none;
        }

        /* Subtle Glow */
        .panel-bottom-glow {
            position: absolute;
            bottom: -30px;
            left: -20%;
            width: 140%;
            height: 160px;
            background: radial-gradient(ellipse at 50% 100%, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0) 70%);
            pointer-events: none;
        }

        /* Government / Ministry PU Emblem Badge */
        .emblem-wrapper {
            position: relative;
            z-index: 2;
            margin-bottom: 18px;
        }

        .emblem-badge {
            width: 98px;
            height: 98px;
            background: #ffffff;
            border-radius: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            box-shadow: 0 12px 28px rgba(10, 30, 45, 0.35),
                        0 0 0 3.5px rgba(250, 214, 5, 0.95);
            transition: transform 0.25s ease;
        }

        .emblem-badge:hover {
            transform: scale(1.03);
        }

        .emblem-badge img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .agency-title {
            position: relative;
            z-index: 2;
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: 0.6px;
            line-height: 1.35;
            color: #ffffff;
            margin-bottom: 8px;
            text-transform: uppercase;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .agency-subtitle {
            position: relative;
            z-index: 2;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.92);
            line-height: 1.45;
            text-transform: uppercase;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        /* Right Side: Form Panel */
        .auth-form-panel {
            flex: 1.05;
            padding: 34px 38px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(255, 255, 255, 0.95);
        }

        /* Brand Logo Block (Logo PU Resmi) */
        .brand-header-box {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .brand-icon-glass {
            width: 42px;
            height: 42px;
            background: #ffffff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
            margin-right: 12px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.12),
                        0 0 0 2px rgba(250, 214, 5, 0.9);
            flex-shrink: 0;
        }

        .brand-icon-glass img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-text-block h1 {
            font-size: 1.18rem;
            font-weight: 800;
            color: var(--text-dark);
            margin: 0;
            letter-spacing: 0.8px;
            line-height: 1.1;
        }

        .brand-text-block span {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--pu-blue);
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .auth-form-title {
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 18px;
        }

        /* Modern Inputs */
        .input-wrapper {
            position: relative;
            margin-bottom: 14px;
        }

        .input-glass {
            height: 44px;
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            padding: 8px 14px;
            font-size: 0.9rem;
            color: var(--text-dark);
            background: rgba(255, 255, 255, 0.88);
            transition: all 0.2s ease;
            width: 100%;
        }

        .input-glass:focus {
            border-color: var(--pu-blue);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12);
            outline: none;
        }

        .input-glass::placeholder {
            color: #94a3b8;
            font-size: 0.86rem;
        }

        /* Show/Hide Password Eye Toggle */
        .password-toggle-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            font-size: 0.9rem;
            transition: color 0.2s ease;
        }

        .password-toggle-btn:hover {
            color: var(--text-dark);
        }

        /* Remember Me Box */
        .remember-box {
            background: rgba(248, 250, 252, 0.85);
            border: 1px solid #eef2f6;
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 18px;
        }

        .custom-control-label {
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-dark);
            cursor: pointer;
            line-height: 1.3;
        }

        .remember-subtext {
            font-size: 0.72rem;
            color: var(--text-muted);
            margin-left: 24px;
            margin-top: 1px;
        }

        /* Submit Button */
        .btn-submit-masuk {
            height: 44px;
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.94rem;
            border-radius: 8px;
            border: none;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(29, 78, 216, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            letter-spacing: 0.4px;
        }

        .btn-submit-masuk:hover {
            background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
            box-shadow: 0 6px 18px rgba(29, 78, 216, 0.35);
            transform: translateY(-1px);
            color: #ffffff;
        }

        .btn-submit-masuk:active {
            transform: translateY(0);
        }

        /* Footer Links */
        .home-link {
            display: inline-block;
            margin-top: 14px;
            color: var(--pu-blue);
            font-size: 0.84rem;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .home-link:hover {
            color: var(--pu-blue-hover);
            text-decoration: underline;
        }

        .copyright-text {
            margin-top: 16px;
            font-size: 0.72rem;
            color: #94a3b8;
            text-align: center;
        }

        /* Google reCAPTCHA Badge Hide (Clean look with disclaimer) */
        .grecaptcha-badge {
            visibility: hidden !important;
        }

        /* Responsive Breakpoint */
        @media (max-width: 820px) {
            .auth-container {
                flex-direction: column;
                max-width: 440px;
            }

            .auth-visual-panel {
                padding: 28px 20px;
            }

            .agency-title {
                font-size: 1.05rem;
            }

            .emblem-badge {
                width: 80px;
                height: 80px;
                padding: 10px;
            }

            .auth-form-panel {
                padding: 26px 20px;
            }
        }
    </style>

    {{-- Google reCAPTCHA v3 Script jika Site Key sudah diatur --}}
    @if(!empty(config('services.recaptcha.site_key')))
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    @endif
</head>

<body>

    <!-- Ambient Glow Spheres (Glassmorphism Effect) -->
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <main class="auth-container">
        <!-- 1. LEFT PANEL: BWS Kalimantan I / Ditjen SDA Branding -->
        <section class="auth-visual-panel">
            <div class="spatial-mesh-bg"></div>
            <div class="spatial-lines"></div>

            <div class="w-100" style="position: relative; z-index: 2;">
                <!-- Logo Emblem Kementerian PU (Presisi & Jernih) -->
                <div class="emblem-wrapper">
                    <div class="emblem-badge">
                        <img src="{{ asset('img/logo-pu.svg') }}" alt="Logo Kementerian Pekerjaan Umum">
                    </div>
                </div>

                <!-- Instansi Header -->
                <h2 class="agency-title">
                    BALAI WILAYAH SUNGAI<br>KALIMANTAN I
                </h2>
                <div class="agency-subtitle">
                    DIREKTORAT JENDERAL SUMBER DAYA AIR<br>
                    KEMENTERIAN PEKERJAAN UMUM
                </div>
            </div>

            <div class="panel-bottom-glow"></div>
        </section>

        <!-- 2. RIGHT PANEL: Formulir Login Bersih & Modern (Google reCAPTCHA v3) -->
        <section class="auth-form-panel">
            <!-- Header Logo PU Resmi & Judul Web GIS -->
            <div class="brand-header-box">
                <div class="brand-icon-glass">
                    <img src="{{ asset('img/logo-pu.svg') }}" alt="Logo PU">
                </div>
                <div class="brand-text-block">
                    <h1>WEB GIS</h1>
                    <span>BWS KALIMANTAN I</span>
                </div>
            </div>

            <!-- Subtitle -->
            <div class="auth-form-title">
                Silakan masuk ke akun Anda
            </div>

            {{-- Pesan Notifikasi Sukses Logout / Status --}}
            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show p-2 mb-2" style="font-size: 0.8rem;" role="alert">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                    <button type="button" class="close p-2" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Pesan Notifikasi Gagal Login --}}
            @if(session()->has('loginError'))
                <div class="alert alert-danger alert-dismissible fade show p-2 mb-2" style="font-size: 0.8rem;" role="alert">
                    <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('loginError') }}
                    <button type="button" class="close p-2" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-info alert-dismissible fade show p-2 mb-2" style="font-size: 0.8rem;" role="alert">
                    <i class="fas fa-info-circle mr-1"></i> {{ session('status') }}
                    <button type="button" class="close p-2" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Form Autentikasi -->
            <form action="{{ route('login') }}" method="POST" id="loginForm" autocomplete="off">
                @csrf

                {{-- Honeypot anti-bot tersembunyi --}}
                <div style="display:none;" aria-hidden="true">
                    <input type="text" name="_hp_check" value="" tabindex="-1" autocomplete="off">
                </div>

                {{-- Hidden input token reCAPTCHA v3 --}}
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                <!-- Input Email -->
                <div class="input-wrapper">
                    <label class="small font-weight-bold text-gray-700 mb-1 d-block" for="email" style="font-size: 0.8rem;">
                        Alamat Email
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email"
                           class="input-glass @error('email') is-invalid @enderror" 
                           placeholder="Masukkan alamat email Anda"
                           value="{{ old('email') }}"
                           required 
                           autofocus 
                           autocomplete="off">
                    @error('email')
                        <div class="invalid-feedback ml-1" style="font-size: 0.75rem;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Input Password & Eye Toggle & Lupa Password -->
                <div class="input-wrapper">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="small font-weight-bold text-gray-700 mb-0" for="password" style="font-size: 0.8rem;">
                            Password
                        </label>
                        <a href="{{ route('password.request') }}" class="font-weight-bold text-decoration-none" style="font-size: 0.75rem; color: #1d4ed8;">
                            Lupa Password?
                        </a>
                    </div>
                    <div style="position: relative;">
                        <input type="password" 
                               name="password" 
                               id="password"
                               class="input-glass @error('password') is-invalid @enderror" 
                               placeholder="Masukkan password"
                               required 
                               autocomplete="off">
                        <button type="button" 
                                class="password-toggle-btn" 
                                id="togglePassword" 
                                title="Tampilkan / Sembunyikan Password"
                                onclick="togglePasswordVisibility()">
                            <i class="far fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback ml-1" style="font-size: 0.75rem;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Checkbox: Tetap Masuk (Persis QROP Spasial) -->
                <div class="remember-box">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="remember" id="remember" class="custom-control-input" {{ old('remember') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="remember">
                            Tetap masuk
                        </label>
                    </div>
                    <div class="remember-subtext">
                        Gunakan hanya di perangkat pribadi Anda
                    </div>
                </div>

                <!-- Teks Notifikasi Perlindungan reCAPTCHA (Persis QROP Spasial) -->
                <div class="text-center text-muted mb-2" style="font-size: 0.74rem;">
                    Formulir ini dilindungi oleh reCAPTCHA.
                </div>

                <!-- Tombol Submit: Masuk -->
                <button type="submit" class="btn-submit-masuk" id="btnSubmit">
                    <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                </button>
            </form>

            <!-- Tautan Navigasi Beranda -->
            <div class="text-center">
                <a href="{{ route('peta') }}" class="home-link">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda Peta
                </a>
            </div>

            <!-- Footer Hak Cipta -->
            <div class="copyright-text">
                Copyright &copy; {{ date('Y') }} | BWS Kalimantan I Pontianak
            </div>
        </section>
    </main>

    <!-- Scripts -->
    <script src="{{ asset('sbadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        // Toggle Show/Hide Password
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        // Integrasi Google reCAPTCHA v3 saat form disubmit
        const loginForm = document.getElementById('loginForm');
        const siteKey = "{{ config('services.recaptcha.site_key') }}";

        loginForm.addEventListener('submit', function(e) {
            if (siteKey && typeof grecaptcha !== 'undefined') {
                e.preventDefault();
                const btnSubmit = document.getElementById('btnSubmit');
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memeriksa...';

                grecaptcha.ready(function() {
                    grecaptcha.execute(siteKey, { action: 'login' }).then(function(token) {
                        document.getElementById('g-recaptcha-response').value = token;
                        loginForm.submit();
                    }).catch(function(error) {
                        console.error('reCAPTCHA error:', error);
                        // Fallback submit jika ada error koneksi ke Google
                        loginForm.submit();
                    });
                });
            }
        });
    </script>
</body>
</html>

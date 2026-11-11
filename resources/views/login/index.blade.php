<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $title ?? 'Masuk' }} - SIPDIRA BWS KALIMANTAN I</title>

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
            --pu-navy: #10143a;
            --pu-navy-dark: #090c24;
            --pu-navy-light: #1c2363;
            --pu-blue: #1d4ed8;
            --pu-blue-hover: #1e40af;
            --pu-gold: #f59e0b;
            --pu-gold-bright: #fad605;
            --text-dark: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: radial-gradient(circle at 20% 20%, #151d52 0%, #0d1238 45%, #07091f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 24px 16px;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Glow Background Spheres */
        .ambient-sphere-1 {
            position: fixed;
            top: -100px;
            left: -80px;
            width: 480px;
            height: 480px;
            background: radial-gradient(circle, rgba(29, 78, 216, 0.35) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(70px);
            pointer-events: none;
            z-index: 0;
        }

        .ambient-sphere-2 {
            position: fixed;
            bottom: -120px;
            right: -100px;
            width: 520px;
            height: 520px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.22) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }

        /* ═══ KARTU LOGIN UTAMA (1:1 DENGAN REFERENSI DESAIN) ═══ */
        .login-card-container {
            width: 100%;
            max-width: 960px;
            min-height: 560px;
            background: #ffffff;
            border-radius: 26px;
            box-shadow: 0 25px 70px -15px rgba(5, 8, 30, 0.55),
                        0 0 0 1px rgba(255, 255, 255, 0.1);
            overflow: hidden;
            display: flex;
            position: relative;
            z-index: 2;
        }

        /* ═══ SISI KIRI: HERO PANEL (WELCOME & 3D SPHERES) ═══ */
        .login-hero-panel {
            flex: 1.05;
            background: linear-gradient(145deg, #0d1238 0%, #101748 50%, #1a2468 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 44px 40px;
            color: #ffffff;
        }

        /* 3D Geometric Spheres / Curvature (Persis Referensi Foto) */
        .hero-shape-top-curve {
            position: absolute;
            width: 380px;
            height: 380px;
            top: -70px;
            right: -100px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.85) 0%, rgba(29, 78, 216, 0.95) 100%);
            box-shadow: inset 0 -12px 28px rgba(10, 14, 45, 0.45);
            pointer-events: none;
            z-index: 1;
        }

        .hero-shape-main-sphere {
            position: absolute;
            width: 175px;
            height: 175px;
            bottom: 35px;
            right: 35px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 30%, #60a5fa 0%, #2563eb 40%, #1d4ed8 70%, #0f2468 100%);
            box-shadow: -15px 16px 32px rgba(0, 0, 0, 0.45),
                        inset -8px -8px 16px rgba(0, 0, 0, 0.5),
                        inset 6px 6px 12px rgba(255, 255, 255, 0.5);
            pointer-events: none;
            z-index: 2;
        }

        .hero-shape-bottom-curve {
            position: absolute;
            width: 240px;
            height: 240px;
            bottom: -90px;
            left: -70px;
            border-radius: 50%;
            background: radial-gradient(circle at 40% 40%, #1e3a8a 0%, #0d1238 85%);
            opacity: 0.9;
            pointer-events: none;
            z-index: 1;
        }

        .hero-gold-glow {
            position: absolute;
            width: 140px;
            height: 140px;
            top: 20px;
            left: -20px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.22) 0%, transparent 70%);
            filter: blur(25px);
            pointer-events: none;
            z-index: 1;
        }

        /* Hero Content (Di Atas Layer Bentuk Geometris) */
        .hero-content-wrapper {
            position: relative;
            z-index: 5;
        }

        /* Dual Logo Badge (Logo PU & Logo Website SIPDIRA) */
        .dual-logo-badge {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.95);
            padding: 8px 18px;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
            border: 1.5px solid rgba(255, 255, 255, 0.85);
            margin-bottom: 26px;
        }

        .dual-logo-badge img.logo-item {
            height: 38px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        .dual-logo-divider {
            width: 1.5px;
            height: 28px;
            background: #cbd5e1;
        }

        .hero-sub-agency {
            font-size: 0.73rem;
            font-weight: 800;
            letter-spacing: 1px;
            color: var(--pu-gold-bright);
            text-transform: uppercase;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .hero-title-welcome {
            font-size: 2.25rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            line-height: 1.1;
            margin: 0 0 8px 0;
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.35);
        }

        .hero-headline-name {
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: 0.6px;
            color: #93c5fd;
            text-transform: uppercase;
            margin: 0 0 14px 0;
        }

        .hero-description {
            font-size: 0.83rem;
            line-height: 1.65;
            color: rgba(255, 255, 255, 0.88);
            max-width: 330px;
            margin: 0;
        }

        .hero-footer-status {
            position: relative;
            z-index: 5;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 14px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 0.74rem;
            color: #ffffff;
            width: fit-content;
        }

        /* ═══ SISI KANAN: FORM PANEL (SIGN IN) ═══ */
        .login-form-panel {
            flex: 1;
            background: #ffffff;
            padding: 44px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .form-heading-title {
            font-size: 1.95rem;
            font-weight: 800;
            color: var(--pu-navy);
            letter-spacing: -0.5px;
            margin: 0 0 4px 0;
            line-height: 1.2;
        }

        .form-heading-subtitle {
            font-size: 0.84rem;
            color: var(--text-muted);
            margin: 0 0 22px 0;
            line-height: 1.5;
        }

        /* Custom Input Pill Wrappers (Persis Referensi Input dengan Icon) */
        .form-input-pill {
            position: relative;
            background: #f8fafc;
            border: 1.5px solid var(--border-color);
            border-radius: 12px;
            height: 48px;
            display: flex;
            align-items: center;
            padding: 0 16px;
            transition: all 0.2s ease;
            margin-bottom: 14px;
        }

        .form-input-pill:focus-within {
            background: #ffffff;
            border-color: var(--pu-navy);
            box-shadow: 0 0 0 3px rgba(16, 20, 58, 0.1);
        }

        .form-input-pill.has-error {
            border-color: #ef4444;
            background: #fef2f2;
        }

        .input-icon-left {
            color: #94a3b8;
            font-size: 0.95rem;
            width: 20px;
            text-align: center;
            margin-right: 12px;
            flex-shrink: 0;
            transition: color 0.2s ease;
        }

        .form-input-pill:focus-within .input-icon-left {
            color: var(--pu-navy);
        }

        .form-input-pill input {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 0.88rem;
            color: var(--text-dark);
            font-weight: 500;
            height: 100%;
            outline: none;
            padding: 0;
        }

        .form-input-pill input::placeholder {
            color: #94a3b8;
            font-size: 0.86rem;
            font-weight: 400;
        }

        /* Tombol SHOW / HIDE Persis Referensi Desain */
        .btn-toggle-show {
            background: none;
            border: none;
            color: var(--pu-blue);
            font-weight: 800;
            font-size: 0.74rem;
            letter-spacing: 0.5px;
            cursor: pointer;
            padding: 4px 6px;
            text-transform: uppercase;
            transition: color 0.2s ease;
            flex-shrink: 0;
        }

        .btn-toggle-show:hover {
            color: var(--pu-blue-hover);
        }

        /* Baris Utilitas: Remember Me & Forgot Password */
        .utility-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            margin-top: 2px;
        }

        .remember-checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            color: var(--text-body);
            font-weight: 600;
            cursor: pointer;
            margin: 0;
            user-select: none;
        }

        .remember-checkbox-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--pu-navy);
            cursor: pointer;
            border-radius: 4px;
        }

        .forgot-link {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: var(--pu-navy);
            text-decoration: underline;
        }

        /* Tombol Utama: Sign in (Navy PU Dominan) */
        .btn-signin-primary {
            width: 100%;
            height: 48px;
            background: var(--pu-navy);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.94rem;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 6px 18px rgba(16, 20, 58, 0.25);
        }

        .btn-signin-primary:hover {
            background: var(--pu-navy-light);
            box-shadow: 0 8px 24px rgba(16, 20, 58, 0.35);
            transform: translateY(-1px);
            color: #ffffff;
        }

        .btn-signin-primary:active {
            transform: translateY(0);
        }

        /* Garis Pemisah (Divider "Or") Persis Referensi Foto */
        .auth-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 18px 0;
            color: #94a3b8;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1.5px solid #e2e8f0;
        }

        .auth-divider span {
            padding: 0 12px;
            text-transform: capitalize;
        }

        /* Tombol Alternatif: "Sign in with other" -> "Buka WebGIS Publik" */
        .btn-signin-secondary {
            width: 100%;
            height: 46px;
            background: #ffffff;
            color: var(--text-dark);
            border: 1.5px solid var(--border-color);
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.86rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none !important;
            transition: all 0.2s ease;
        }

        .btn-signin-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: var(--pu-navy);
            box-shadow: 0 3px 10px rgba(16, 20, 58, 0.06);
            transform: translateY(-1px);
        }

        /* Footer Bawah */
        .auth-footer-bottom {
            margin-top: 22px;
            text-align: center;
            font-size: 0.74rem;
            color: #94a3b8;
        }

        .auth-footer-bottom a {
            color: var(--pu-blue);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-footer-bottom a:hover {
            text-decoration: underline;
        }

        /* Sembunyikan Badge Google reCAPTCHA v3 */
        .grecaptcha-badge {
            visibility: hidden !important;
        }

        /* ═══ RESPONSIVE BREAKPOINT (MOBILE & TABLET) ═══ */
        @media (max-width: 860px) {
            .login-card-container {
                flex-direction: column;
                max-width: 460px;
                min-height: auto;
            }

            .login-hero-panel {
                padding: 34px 28px;
                min-height: 280px;
            }

            .hero-shape-top-curve {
                width: 260px;
                height: 260px;
                top: -50px;
                right: -60px;
            }

            .hero-shape-main-sphere {
                width: 120px;
                height: 120px;
                bottom: 20px;
                right: 20px;
            }

            .hero-title-welcome {
                font-size: 1.8rem;
            }

            .login-form-panel {
                padding: 32px 26px;
            }

            .form-heading-title {
                font-size: 1.65rem;
            }
        }
    </style>

    {{-- Google reCAPTCHA v3 Script jika Site Key aktif --}}
    @if(!empty(config('services.recaptcha.site_key')))
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    @endif
</head>

<body>

    <!-- Ambient Glow Spheres -->
    <div class="ambient-sphere-1"></div>
    <div class="ambient-sphere-2"></div>

    <main class="login-card-container">

        <!-- ═════════════════════════════════════════════════════════
             1. SISI KIRI: HERO PANEL (WELCOME, DUAL LOGO & 3D SPHERES)
             ═════════════════════════════════════════════════════════ -->
        <section class="login-hero-panel">
            <!-- 3D Geometric Spheres (1:1 Layout dari Foto Referensi) -->
            <div class="hero-shape-top-curve"></div>
            <div class="hero-shape-main-sphere"></div>
            <div class="hero-shape-bottom-curve"></div>
            <div class="hero-gold-glow"></div>

            <div class="hero-content-wrapper">
                <!-- Dual Logo Badge: Logo PU + Logo SIPDIRA -->
                <div class="dual-logo-badge">
                    <img src="{{ asset('img/logo-pu.svg') }}" class="logo-item" alt="Logo Kementerian Pekerjaan Umum" title="Kementerian Pekerjaan Umum">
                    <div class="dual-logo-divider"></div>
                    <img src="{{ asset('img/logo-sipdira.svg') }}" class="logo-item" alt="Logo SIPDIRA Spasial" title="SIPDIRA Spasial">
                </div>

                <!-- Instansi Sub-label -->
                <div class="hero-sub-agency">
                    <i class="fas fa-shield-alt text-warning"></i>
                    <span>BWS KALIMANTAN I &bull; DITJEN SDA</span>
                </div>

                <!-- Judul WELCOME (Persis Foto Referensi) -->
                <h1 class="hero-title-welcome">WELCOME</h1>

                <!-- Headline Nama Website -->
                <div class="hero-headline-name">
                    SIPDIRA SPASIAL
                </div>

                <!-- Deskripsi Pendukung -->
                <p class="hero-description">
                    Sistem Informasi Peta Daerah Irigasi berbasis spasial untuk pemantauan, pengelolaan lahan baku, fungsional, dan jaringan infrastruktur sumber daya air Kalimantan Barat.
                </p>
            </div>

            <!-- Status Keamanan Resmi di Bagian Bawah Hero -->
            <div class="hero-footer-status">
                <i class="fas fa-lock text-warning"></i>
                <span>Portal Kedinasan Resmi Kementerian PU</span>
            </div>
        </section>

        <!-- ═════════════════════════════════════════════════════════
             2. SISI KANAN: FORM PANEL (SIGN IN, INPUTS & ACTIONS)
             ═════════════════════════════════════════════════════════ -->
        <section class="login-form-panel">
            <!-- Header Judul Form (Persis Foto Referensi) -->
            <h2 class="form-heading-title">Sign in</h2>
            <p class="form-heading-subtitle">
                Silakan masukkan kredensial akun kedinasan Anda untuk mengakses panel.
            </p>

            {{-- Alert Notifikasi Sukses --}}
            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show p-2.5 mb-3" style="font-size: 0.8rem; border-radius: 10px;" role="alert">
                    <i class="fas fa-check-circle mr-1.5"></i> {{ session('success') }}
                    <button type="button" class="close p-2" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Alert Notifikasi Error Login --}}
            @if(session()->has('loginError'))
                <div class="alert alert-danger alert-dismissible fade show p-2.5 mb-3" style="font-size: 0.8rem; border-radius: 10px;" role="alert">
                    <i class="fas fa-exclamation-triangle mr-1.5"></i> {{ session('loginError') }}
                    <button type="button" class="close p-2" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Alert Notifikasi Status --}}
            @if(session('status'))
                <div class="alert alert-info alert-dismissible fade show p-2.5 mb-3" style="font-size: 0.8rem; border-radius: 10px;" role="alert">
                    <i class="fas fa-info-circle mr-1.5"></i> {{ session('status') }}
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

                {{-- Token reCAPTCHA v3 --}}
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                <!-- 1. Input Email / Username (Persis Bentuk Input Foto Referensi) -->
                <div class="form-input-pill @error('email') has-error @enderror">
                    <i class="fas fa-user input-icon-left"></i>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           placeholder="Alamat Email Kedinasan" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus 
                           autocomplete="off">
                </div>
                @error('email')
                    <div class="text-danger ml-2 mb-2 font-weight-bold" style="font-size: 0.74rem; margin-top: -8px;">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </div>
                @enderror

                <!-- 2. Input Password & Tombol SHOW (Persis Foto Referensi) -->
                <div class="form-input-pill @error('password') has-error @enderror">
                    <i class="fas fa-lock input-icon-left"></i>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           placeholder="Password" 
                           required 
                           autocomplete="off">
                    <button type="button" 
                            class="btn-toggle-show" 
                            id="togglePassword" 
                            title="Tampilkan / Sembunyikan Password"
                            onclick="togglePasswordVisibility()">
                        <span id="showText">SHOW</span>
                    </button>
                </div>
                @error('password')
                    <div class="text-danger ml-2 mb-2 font-weight-bold" style="font-size: 0.74rem; margin-top: -8px;">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </div>
                @enderror

                <!-- 3. Baris Remember Me & Forgot Password (Persis Foto Referensi) -->
                <div class="utility-row">
                    <label class="remember-checkbox-label" for="remember">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Remember me</span>
                    </label>

                    <a href="{{ route('password.request') }}" class="forgot-link">
                        Forgot Password?
                    </a>
                </div>

                <!-- 4. Tombol Utama: Sign in (Navy PU Persis Foto Referensi) -->
                <button type="submit" class="btn-signin-primary" id="btnSubmit">
                    <span>Sign in</span>
                    <i class="fas fa-arrow-right ml-1" style="font-size: 0.85rem;"></i>
                </button>
            </form>

            <!-- 5. Garis Pemisah "Or" (Persis Foto Referensi) -->
            <div class="auth-divider">
                <span>Or</span>
            </div>

            <!-- 6. Tombol Sekunder (Persis "Sing in with other" -> WebGIS Publik) -->
            <a href="{{ route('peta') }}" class="btn-signin-secondary" title="Kembali ke Peta Spasial Publik">
                <i class="fas fa-compass text-primary"></i>
                <span>Buka WebGIS Publik (Tanpa Login)</span>
            </a>

            <!-- 7. Footer Hak Cipta -->
            <div class="auth-footer-bottom">
                Hak Cipta &copy; {{ date('Y') }} &bull; Balai Wilayah Sungai Kalimantan I Pontianak
            </div>
        </section>

    </main>

    <!-- Scripts -->
    <script src="{{ asset('sbadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        // Toggle Show / Hide Password dengan tulisan SHOW / HIDE persis foto referensi
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const showText = document.getElementById('showText');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                showText.textContent = 'HIDE';
                showText.style.color = '#dc2626'; // Merah saat terlihat
            } else {
                passwordInput.type = 'password';
                showText.textContent = 'SHOW';
                showText.style.color = '#1d4ed8'; // Biru saat tersembunyi
            }
        }

        // Integrasi Google reCAPTCHA v3 saat submit
        const loginForm = document.getElementById('loginForm');
        const siteKey = "{{ config('services.recaptcha.site_key') }}";

        loginForm.addEventListener('submit', function(e) {
            const btnSubmit = document.getElementById('btnSubmit');
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Memproses...';

            if (siteKey && typeof grecaptcha !== 'undefined') {
                e.preventDefault();
                grecaptcha.ready(function() {
                    grecaptcha.execute(siteKey, { action: 'login' }).then(function(token) {
                        document.getElementById('g-recaptcha-response').value = token;
                        loginForm.submit();
                    }).catch(function(error) {
                        console.error('reCAPTCHA error:', error);
                        loginForm.submit();
                    });
                });
            }
        });
    </script>
</body>

</html>

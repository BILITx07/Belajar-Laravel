<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $title ?? 'Masuk' }} - WEB GIS BWS KALIMANTAN 1</title>

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
            --sda-teal: #0d5c70;
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
            padding: 24px 16px;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Lighting Background Spheres (Glassmorphism Depth) */
        .ambient-glow-1 {
            position: fixed;
            top: -10%;
            left: -10%;
            width: 550px;
            height: 550px;
            background: radial-gradient(circle, rgba(13, 92, 112, 0.6) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(60px);
            pointer-events: none;
            z-index: 0;
        }

        .ambient-glow-2 {
            position: fixed;
            bottom: -15%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(29, 78, 216, 0.45) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(70px);
            pointer-events: none;
            z-index: 0;
        }

        /* Glassmorphism Main Card Container */
        .auth-container {
            width: 100%;
            max-width: 980px;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: 26px;
            border: 1px solid rgba(255, 255, 255, 0.65);
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.35),
                        0 0 1px 1px rgba(255, 255, 255, 0.5);
            overflow: hidden;
            display: flex;
            min-height: 600px;
            position: relative;
            z-index: 1;
        }

        /* Left Side: Spatial Visual Panel */
        .auth-visual-panel {
            flex: 1.1;
            position: relative;
            background: linear-gradient(150deg, #093c52 0%, #0d5c70 50%, #117887 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 48px 36px;
            color: #ffffff;
            overflow: hidden;
        }

        /* Geospatial Mesh / Network Constellation Background */
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
            background-size: 110px 110px, 90px 90px, 140px 140px, 80px 80px;
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
            background-size: 60px 60px;
            pointer-events: none;
        }

        /* Subtle Glow Accents */
        .panel-bottom-glow {
            position: absolute;
            bottom: -40px;
            left: -20%;
            width: 140%;
            height: 200px;
            background: radial-gradient(ellipse at 50% 100%, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0) 70%);
            pointer-events: none;
        }

        /* Government / Ministry PU Emblem Badge */
        .emblem-wrapper {
            position: relative;
            z-index: 2;
            margin-bottom: 24px;
        }

        .emblem-badge {
            width: 124px;
            height: 124px;
            background: #ffffff;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            box-shadow: 0 16px 36px rgba(10, 30, 45, 0.35),
                        0 0 0 4px rgba(250, 214, 5, 0.95);
            transition: transform 0.3s ease;
        }

        .emblem-badge:hover {
            transform: scale(1.04);
        }

        .emblem-badge img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .agency-title {
            position: relative;
            z-index: 2;
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: 0.8px;
            line-height: 1.35;
            color: #ffffff;
            margin-bottom: 10px;
            text-transform: uppercase;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .agency-subtitle {
            position: relative;
            z-index: 2;
            font-size: 0.84rem;
            font-weight: 600;
            letter-spacing: 0.6px;
            color: rgba(255, 255, 255, 0.92);
            line-height: 1.5;
            text-transform: uppercase;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
        }

        /* Right Side: Form Panel (Frosted Glass Finish) */
        .auth-form-panel {
            flex: 1;
            padding: 48px 42px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(255, 255, 255, 0.94);
        }

        /* Brand Logo Block */
        .brand-header-box {
            display: flex;
            align-items: center;
            margin-bottom: 18px;
        }

        .brand-icon-glass {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.3rem;
            margin-right: 14px;
            box-shadow: 0 8px 18px rgba(29, 78, 216, 0.28);
        }

        .brand-text-block h1 {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-dark);
            margin: 0;
            letter-spacing: 1.2px;
            line-height: 1.1;
        }

        .brand-text-block span {
            font-size: 0.86rem;
            font-weight: 700;
            color: var(--pu-blue);
            letter-spacing: 1.6px;
            text-transform: uppercase;
        }

        .auth-form-title {
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 22px;
        }

        /* Modern Glassmorphic Form Inputs */
        .input-wrapper {
            position: relative;
            margin-bottom: 14px;
        }

        .input-glass {
            height: 46px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 10px 14px;
            font-size: 0.92rem;
            color: var(--text-dark);
            background: rgba(255, 255, 255, 0.85);
            transition: all 0.25s ease;
            width: 100%;
        }

        .input-glass:focus {
            border-color: var(--pu-blue);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.12);
            outline: none;
        }

        .input-glass::placeholder {
            color: #94a3b8;
            font-size: 0.88rem;
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
            font-size: 0.95rem;
            transition: color 0.2s ease;
        }

        .password-toggle-btn:hover {
            color: var(--text-dark);
        }

        /* Remember Me Box */
        .remember-box {
            background: rgba(248, 250, 252, 0.85);
            border: 1px solid #eef2f6;
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }

        .custom-control-label {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-dark);
            cursor: pointer;
        }

        .remember-subtext {
            font-size: 0.76rem;
            color: var(--text-muted);
            margin-left: 24px;
            margin-top: 2px;
        }

        /* CAPTCHA Card */
        .captcha-card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 12px;
            background: rgba(248, 250, 252, 0.85);
            margin-bottom: 18px;
        }

        .captcha-preview-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .captcha-img-container {
            flex-grow: 1;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            cursor: pointer;
        }

        .btn-refresh-captcha {
            height: 42px;
            width: 42px;
            border-radius: 8px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-refresh-captcha:hover {
            color: var(--pu-blue);
            border-color: var(--pu-blue);
            background: #f8fafc;
        }

        /* Submit Button */
        .btn-submit-masuk {
            height: 46px;
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.96rem;
            border-radius: 10px;
            border: none;
            width: 100%;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 6px 18px rgba(29, 78, 216, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            letter-spacing: 0.5px;
        }

        .btn-submit-masuk:hover {
            background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
            box-shadow: 0 8px 22px rgba(29, 78, 216, 0.4);
            transform: translateY(-1px);
            color: #ffffff;
        }

        .btn-submit-masuk:active {
            transform: translateY(0);
        }

        /* Footer Links */
        .home-link {
            display: inline-block;
            margin-top: 16px;
            color: var(--pu-blue);
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .home-link:hover {
            color: var(--pu-blue-hover);
            text-decoration: underline;
        }

        .copyright-text {
            margin-top: 22px;
            font-size: 0.76rem;
            color: #94a3b8;
            text-align: center;
        }

        /* Responsive Breakpoint */
        @media (max-width: 820px) {
            .auth-container {
                flex-direction: column;
                max-width: 480px;
            }

            .auth-visual-panel {
                padding: 36px 24px;
            }

            .agency-title {
                font-size: 1.15rem;
            }

            .emblem-badge {
                width: 95px;
                height: 95px;
                padding: 12px;
            }

            .auth-form-panel {
                padding: 34px 24px;
            }
        }
    </style>
</head>

<body>

    <!-- Ambient Glow Spheres (Glassmorphism Effect) -->
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <main class="auth-container">
        <!-- 1. LEFT PANEL: BWS Kalimantan I / Ditjen SDA Branding (Bersih, Gagah, Minimalis) -->
        <section class="auth-visual-panel">
            <div class="spatial-mesh-bg"></div>
            <div class="spatial-lines"></div>

            <div class="w-100" style="position: relative; z-index: 2;">
                <!-- Logo Emblem Kementerian PU (Jernih & Presisi) -->
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

        <!-- 2. RIGHT PANEL: Formulir Login Modern (Style QROP SPASIAL) -->
        <section class="auth-form-panel">
            <!-- Header Logo Aplikasi -->
            <div class="brand-header-box">
                <div class="brand-icon-glass">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="brand-text-block">
                    <h1>WEB GIS</h1>
                    <span>BWS KALIMANTAN 1</span>
                </div>
            </div>

            <!-- Subtitle -->
            <div class="auth-form-title">
                Silakan Login
            </div>

            {{-- Pesan Notifikasi Sukses Logout / Status --}}
            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show p-2 mb-3" style="font-size: 0.82rem;" role="alert">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                    <button type="button" class="close p-2" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Pesan Notifikasi Gagal Login --}}
            @if(session()->has('loginError'))
                <div class="alert alert-danger alert-dismissible fade show p-2 mb-3" style="font-size: 0.82rem;" role="alert">
                    <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('loginError') }}
                    <button type="button" class="close p-2" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-info alert-dismissible fade show p-2 mb-3" style="font-size: 0.82rem;" role="alert">
                    <i class="fas fa-info-circle mr-1"></i> {{ session('status') }}
                    <button type="button" class="close p-2" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Form Autentikasi -->
            <form action="{{ route('login') }}" method="POST" autocomplete="off">
                @csrf

                <!-- Input Email / Username -->
                <div class="input-wrapper">
                    <input type="email" 
                           name="email" 
                           id="email"
                           class="input-glass @error('email') is-invalid @enderror" 
                           placeholder="Username atau Email"
                           value="{{ old('email') }}"
                           required 
                           autofocus 
                           autocomplete="off">
                    @error('email')
                        <div class="invalid-feedback ml-1" style="font-size: 0.78rem;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Input Password & Show/Hide Eye Toggle -->
                <div class="input-wrapper">
                    <input type="password" 
                           name="password" 
                           id="password"
                           class="input-glass @error('password') is-invalid @enderror" 
                           placeholder="Password"
                           required 
                           autocomplete="off">
                    <button type="button" 
                            class="password-toggle-btn" 
                            id="togglePassword" 
                            title="Tampilkan / Sembunyikan Password"
                            onclick="togglePasswordVisibility()">
                        <i class="far fa-eye" id="eyeIcon"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback ml-1" style="font-size: 0.78rem;">
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

                <!-- Kode Keamanan (CAPTCHA) -->
                <div class="captcha-card">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small font-weight-bold text-gray-700" style="font-size: 0.78rem;">
                            <i class="fas fa-shield-alt text-primary mr-1"></i> Kode Keamanan (CAPTCHA)
                        </span>
                        <a href="{{ route('password.request') }}" class="small text-danger font-weight-bold text-decoration-none" style="font-size: 0.75rem;">
                            Lupa Password?
                        </a>
                    </div>

                    <div class="captcha-preview-row">
                        <div class="captcha-img-container shadow-sm" onclick="refreshCaptcha()" title="Klik untuk refresh kode">
                            <img src="{{ route('captcha') }}" id="captcha-img" alt="CAPTCHA" height="36" style="cursor: pointer; max-width: 100%;">
                        </div>
                        <button type="button" class="btn-refresh-captcha shadow-sm" id="btn-refresh-captcha" onclick="refreshCaptcha()" title="Refresh CAPTCHA">
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
                           class="input-glass @error('captcha') is-invalid @enderror" 
                           style="height: 40px; font-size: 0.88rem;"
                           placeholder="Ketik 5 huruf kode di atas..." 
                           maxlength="5" 
                           required 
                           autocomplete="off">
                    @error('captcha')
                        <div class="invalid-feedback ml-1" style="font-size: 0.78rem;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Tombol Submit: Masuk -->
                <button type="submit" class="btn-submit-masuk">
                    Masuk
                </button>
            </form>

            <!-- Tautan Navigasi Beranda -->
            <div class="text-center">
                <a href="{{ route('peta') }}" class="home-link">
                    Beranda
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

        // Refresh CAPTCHA Code
        function refreshCaptcha() {
            const captchaImg = document.getElementById('captcha-img');
            const refreshIcon = document.getElementById('refresh-icon');

            if (refreshIcon) {
                refreshIcon.classList.add('fa-spin');
            }

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

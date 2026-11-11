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
            --bg-dark: #07090e;
            --bg-surface: #0e121d;
            --bg-card: #131826;
            --border-card: rgba(255, 255, 255, 0.08);
            --border-input: #232b3e;
            --pu-navy: #10143a;
            --pu-blue: #2563eb;
            --pu-blue-bright: #3b82f6;
            --pu-gold: #f59e0b;
            --pu-gold-bright: #fad605;
            --text-white: #ffffff;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: var(--bg-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 30px 20px;
            position: relative;
            overflow-x: hidden;
            color: #ffffff;
        }

        /* Ambient Background Light Vignette */
        .ambient-bg-glow-1 {
            position: fixed;
            top: -150px;
            left: -100px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
            z-index: 0;
        }

        .ambient-bg-glow-2 {
            position: fixed;
            bottom: -150px;
            right: -100px;
            width: 650px;
            height: 650px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(110px);
            pointer-events: none;
            z-index: 0;
        }

        /* ═══ TOMBOL KEMBALI DI POJOK KIRI ATAS (1:1 PERSIS REFERENSI) ═══ */
        .btn-top-back {
            position: absolute;
            top: 24px;
            left: 28px;
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.05rem;
            text-decoration: none !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 100;
        }

        .btn-top-back:hover {
            background: rgba(255, 255, 255, 0.18);
            transform: translateX(-3px);
            color: #ffffff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        }

        /* ═══ WRAPPER UTAMA KONTEN (FLEX SPLIT DUA KOLOM) ═══ */
        .auth-split-wrapper {
            width: 100%;
            max-width: 1060px;
            display: grid;
            grid-template-columns: 460px minmax(0, 1fr);
            gap: 56px;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        /* ═════════════════════════════════════════════════════════
           1. SISI KIRI: KARTU VISUAL BERGERAK (CINEMATIC LIVING CARD)
           ═════════════════════════════════════════════════════════ */
        .media-card-container {
            position: relative;
            width: 100%;
            height: 580px;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.8),
                        0 0 0 1px rgba(255, 255, 255, 0.12);
        }

        /* Aura Cahaya Ambient Hijau-Biru Berdenyut di Belakang Kartu */
        .card-ambient-aura {
            position: absolute;
            inset: -15px;
            background: radial-gradient(circle at 40% 70%, rgba(16, 185, 129, 0.45) 0%, rgba(37, 99, 235, 0.4) 50%, transparent 80%);
            border-radius: 36px;
            filter: blur(35px);
            z-index: -1;
            opacity: 0.85;
            animation: pulseAura 7s ease-in-out infinite alternate;
        }

        @keyframes pulseAura {
            0% {
                opacity: 0.65;
                transform: scale(0.98);
            }
            100% {
                opacity: 1;
                transform: scale(1.04);
            }
        }

        /* Lapisan Gambar Bergerak: Efek Kamera Sinematik Ken Burns */
        .cinematic-moving-image {
            position: absolute;
            inset: -10%;
            width: 120%;
            height: 120%;
            background-image: url('{{ asset('img/bws-kalimantan-river.jpg') }}');
            background-size: cover;
            background-position: center center;
            animation: kenBurnsMotion 26s cubic-bezier(0.25, 1, 0.5, 1) infinite alternate;
            will-change: transform;
        }

        @keyframes kenBurnsMotion {
            0% {
                transform: scale(1.05) translate(0%, 0%);
            }
            50% {
                transform: scale(1.18) translate(-2%, -3%);
            }
            100% {
                transform: scale(1.25) translate(2%, -1%);
            }
        }

        /* Lapisan Efek Ombak / Aliran Air yang Berdesir Hidup (Living Water Ripples) */
        .water-flow-ripples {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 60%;
            background: linear-gradient(180deg, transparent 0%, rgba(14, 165, 233, 0.12) 50%, rgba(16, 185, 129, 0.18) 100%);
            mix-blend-mode: overlay;
            pointer-events: none;
            animation: rippleCurrent 6s ease-in-out infinite alternate;
        }

        @keyframes rippleCurrent {
            0% {
                opacity: 0.4;
                transform: translateY(0) scaleY(1);
            }
            100% {
                opacity: 0.85;
                transform: translateY(-8px) scaleY(1.06);
            }
        }

        /* Partikel Kilauan Embun / Cahaya Air Melayang (Floating Light Particles) */
        .ambient-particles-canvas {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            background-image: 
                radial-gradient(circle at 30% 40%, rgba(255, 255, 255, 0.8) 1.5px, transparent 1.5px),
                radial-gradient(circle at 75% 65%, rgba(250, 214, 5, 0.9) 2px, transparent 2px),
                radial-gradient(circle at 45% 80%, rgba(167, 243, 208, 0.8) 2px, transparent 2px),
                radial-gradient(circle at 60% 30%, rgba(255, 255, 255, 0.6) 1.2px, transparent 1.2px),
                radial-gradient(circle at 20% 75%, rgba(147, 197, 253, 0.85) 1.8px, transparent 1.8px);
            background-size: 260px 260px, 320px 320px, 200px 200px, 280px 280px, 240px 240px;
            animation: floatParticles 18s linear infinite;
            opacity: 0.8;
        }

        @keyframes floatParticles {
            0% {
                background-position: 0 0, 0 0, 0 0, 0 0, 0 0;
            }
            100% {
                background-position: -80px -400px, 100px -500px, -50px -300px, 60px -450px, -40px -380px;
            }
        }

        /* Vignette & Gradien Bawah agar Teks dan Logo PU Sangat Kontras & Terbaca */
        .media-card-vignette {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, 
                rgba(0, 0, 0, 0.25) 0%, 
                rgba(0, 0, 0, 0.05) 35%, 
                rgba(7, 9, 14, 0.5) 65%, 
                rgba(7, 9, 14, 0.95) 100%);
            pointer-events: none;
        }

        /* Badge Instansi Bagian Atas Kartu Gambar */
        .media-card-top-tag {
            position: absolute;
            top: 22px;
            left: 22px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 14px;
            border-radius: 20px;
            background: rgba(10, 14, 25, 0.65);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            font-size: 0.73rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.4px;
            z-index: 5;
        }

        .media-card-top-tag .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 8px #10b981;
            animation: blinkDot 2s infinite ease-in-out;
        }

        @keyframes blinkDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }

        /* ═══ KARTU BRANDING BAWAH (1:1 DENGAN "WAVA AI" PADA FOTO REFERENSI) ═══ */
        .brand-overlay-bottom {
            position: absolute;
            bottom: 26px;
            left: 24px;
            right: 24px;
            z-index: 6;
        }

        .brand-emblem-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        /* Logo PU & SIPDIRA di dalam Kapsul Kaca Mewah */
        .brand-glass-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.95);
            padding: 7px 14px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .brand-glass-badge img.logo-pu-icon {
            height: 32px;
            width: auto;
            object-fit: contain;
        }

        .brand-glass-badge .logo-divider {
            width: 1px;
            height: 22px;
            background: #cbd5e1;
        }

        .brand-glass-badge img.logo-sipdira-icon {
            height: 32px;
            width: auto;
            object-fit: contain;
        }

        .brand-text-name {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #ffffff;
            margin: 0;
            line-height: 1.15;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
        }

        .brand-text-tagline {
            font-size: 0.76rem;
            color: #cbd5e1;
            margin: 0;
            line-height: 1.45;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.4);
        }

        /* ═════════════════════════════════════════════════════════
           2. SISI KANAN: FORMULIR LOGIN (DARK MINIMALIST PERSIS FOTO)
           ═════════════════════════════════════════════════════════ */
        .auth-form-column {
            padding: 10px 0;
        }

        .auth-form-title {
            font-size: 2.15rem;
            font-weight: 800;
            letter-spacing: -0.6px;
            color: #ffffff;
            margin: 0 0 6px 0;
            line-height: 1.15;
        }

        .auth-form-desc {
            font-size: 0.86rem;
            color: var(--text-secondary);
            margin: 0 0 28px 0;
            line-height: 1.5;
        }

        /* Capsule Dark Inputs (1:1 Persis Foto Referensi) */
        .dark-input-pill {
            position: relative;
            background: #121724;
            border: 1.5px solid var(--border-input);
            border-radius: 14px;
            height: 52px;
            display: flex;
            align-items: center;
            padding: 0 18px;
            margin-bottom: 16px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .dark-input-pill:focus-within {
            background: #161c2d;
            border-color: var(--pu-blue-bright);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.18);
        }

        .dark-input-pill.has-error {
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.08);
        }

        .input-pill-icon {
            color: var(--text-muted);
            font-size: 1rem;
            width: 22px;
            text-align: center;
            margin-right: 14px;
            flex-shrink: 0;
            transition: color 0.2s ease;
        }

        .dark-input-pill:focus-within .input-pill-icon {
            color: var(--pu-blue-bright);
        }

        .dark-input-pill input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            height: 100%;
            font-size: 0.9rem;
            color: #ffffff;
            font-weight: 500;
            padding: 0;
        }

        .dark-input-pill input::placeholder {
            color: #64748b;
            font-size: 0.88rem;
            font-weight: 400;
        }

        /* Tombol SHOW / HIDE Text Toggle */
        .btn-toggle-show-dark {
            background: none;
            border: none;
            color: var(--pu-blue-bright);
            font-weight: 800;
            font-size: 0.74rem;
            letter-spacing: 0.5px;
            cursor: pointer;
            padding: 4px 6px;
            text-transform: uppercase;
            transition: color 0.2s ease;
            flex-shrink: 0;
        }

        .btn-toggle-show-dark:hover {
            color: #93c5fd;
        }

        /* Baris Checkbox Remember Me & Forgot Password */
        .form-options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            margin-top: 4px;
        }

        .dark-checkbox-label {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 0.82rem;
            color: var(--text-secondary);
            font-weight: 500;
            cursor: pointer;
            margin: 0;
            user-select: none;
        }

        .dark-checkbox-label input[type="checkbox"] {
            width: 17px;
            height: 17px;
            accent-color: var(--pu-blue);
            cursor: pointer;
            border-radius: 4px;
        }

        .dark-forgot-link {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .dark-forgot-link:hover {
            color: var(--pu-blue-bright);
            text-decoration: underline;
        }

        /* ═══ TOMBOL UTAMA: SIGN IN (GRADASI CERAH PERSIS FOTO) ═══ */
        .btn-action-primary {
            width: 100%;
            height: 52px;
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 50%, #3b82f6 100%);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.38);
        }

        .btn-action-primary:hover {
            background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 50%, #2563eb 100%);
            box-shadow: 0 10px 28px rgba(37, 99, 235, 0.52);
            transform: translateY(-1.5px);
            color: #ffffff;
        }

        .btn-action-primary:active {
            transform: translateY(0);
        }

        /* ═══ TOMBOL SEKUNDER (PERSIS "CONTINUE WITH GOOGLE" DENGAN OUTLINE GELAP) ═══ */
        .btn-action-secondary {
            width: 100%;
            height: 48px;
            background: #101522;
            color: #cbd5e1;
            border: 1.5px solid #232c40;
            border-radius: 14px;
            font-weight: 600;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 14px;
            text-decoration: none !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-action-secondary:hover {
            background: #161d30;
            border-color: #3b82f6;
            color: #ffffff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4);
            transform: translateY(-1px);
        }

        /* Teks Footer Bawah */
        .auth-footer-text {
            margin-top: 28px;
            text-align: center;
            font-size: 0.76rem;
            color: var(--text-muted);
        }

        .auth-footer-text a {
            color: var(--pu-blue-bright);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-footer-text a:hover {
            text-decoration: underline;
        }

        /* reCAPTCHA Invisible */
        .grecaptcha-badge {
            visibility: hidden !important;
        }

        /* ═══ RESPONSIVE (TABLET & MOBILE) ═══ */
        @media (max-width: 920px) {
            .auth-split-wrapper {
                grid-template-columns: 1fr;
                gap: 36px;
                max-width: 460px;
            }

            .media-card-container {
                height: 380px;
            }

            .auth-form-title {
                font-size: 1.85rem;
            }
        }
    </style>

    {{-- Google reCAPTCHA v3 Script --}}
    @if(!empty(config('services.recaptcha.site_key')))
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    @endif
</head>

<body>

    <!-- Ambient Lights -->
    <div class="ambient-bg-glow-1"></div>
    <div class="ambient-bg-glow-2"></div>

    <!-- Tombol Kembali ke Peta Spasial (Pojok Kiri Atas Persis Foto) -->
    <a href="{{ route('peta') }}" class="btn-top-back" title="Kembali ke Peta Spasial WebGIS">
        <i class="fas fa-arrow-left"></i>
    </a>

    <main class="auth-split-wrapper">

        <!-- ═════════════════════════════════════════════════════════
             1. SISI KIRI: KARTU VISUAL BERGERAK (CINEMATIC LIVING CARD)
             ═════════════════════════════════════════════════════════ -->
        <section class="media-card-container">
            <!-- Aura Cahaya Ambient Berdenyut di Belakang Kartu -->
            <div class="card-ambient-aura"></div>

            <!-- 1. Lapisan Gambar Bergerak (Ken Burns Motion) -->
            <div class="cinematic-moving-image"></div>

            <!-- 2. Lapisan Ombak & Desir Aliran Air yang Bergerak Hidup -->
            <div class="water-flow-ripples"></div>

            <!-- 3. Partikel Embun dan Cahaya Melayang Sinematik -->
            <div class="ambient-particles-canvas"></div>

            <!-- 4. Vignette Kontras Gradasi Bawah -->
            <div class="media-card-vignette"></div>

            <!-- Tag Instansi Bagian Atas Kartu -->
            <div class="media-card-top-tag">
                <span class="pulse-dot"></span>
                <span>BWS KALIMANTAN I &bull; DITJEN SDA</span>
            </div>

            <!-- 5. Branding Plate di Bawah Kartu (Persis "Wava AI" di Foto) -->
            <div class="brand-overlay-bottom">
                <div class="brand-emblem-row">
                    <!-- Kapsul Kaca: Logo PU + Logo SIPDIRA -->
                    <div class="brand-glass-badge">
                        <img src="{{ asset('img/logo-pu.svg') }}" class="logo-pu-icon" alt="Logo Kementerian Pekerjaan Umum" title="Kementerian Pekerjaan Umum">
                        <div class="logo-divider"></div>
                        <img src="{{ asset('img/logo-sipdira.svg') }}" class="logo-sipdira-icon" alt="Logo SIPDIRA Spasial" title="SIPDIRA Spasial">
                    </div>
                </div>

                <h1 class="brand-text-name">SIPDIRA SPASIAL</h1>
                <p class="brand-text-tagline">
                    Sistem Informasi Peta Daerah Irigasi &bull; Pemantauan Geospasial Sumber Daya Air Kalimantan Barat
                </p>
            </div>
        </section>

        <!-- ═════════════════════════════════════════════════════════
             2. SISI KANAN: FORMULIR LOGIN (DARK MINIMALIST PERSIS FOTO)
             ═════════════════════════════════════════════════════════ -->
        <section class="auth-form-column">
            <!-- Judul Formulir (Persis "Create an account" di Foto) -->
            <h2 class="auth-form-title">Akses Portal Masuk</h2>
            <p class="auth-form-desc">
                Masukkan kredensial akun kedinasan Anda untuk mengelola sistem.
            </p>

            {{-- Pesan Alert Sukses --}}
            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show p-2.5 mb-3" style="font-size: 0.82rem; border-radius: 12px; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7;" role="alert">
                    <i class="fas fa-check-circle mr-1.5"></i> {{ session('success') }}
                    <button type="button" class="close p-2 text-white" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Pesan Alert Gagal Login --}}
            @if(session()->has('loginError'))
                <div class="alert alert-danger alert-dismissible fade show p-2.5 mb-3" style="font-size: 0.82rem; border-radius: 12px; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5;" role="alert">
                    <i class="fas fa-exclamation-triangle mr-1.5"></i> {{ session('loginError') }}
                    <button type="button" class="close p-2 text-white" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Pesan Alert Status --}}
            @if(session('status'))
                <div class="alert alert-info alert-dismissible fade show p-2.5 mb-3" style="font-size: 0.82rem; border-radius: 12px; background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3); color: #93c5fd;" role="alert">
                    <i class="fas fa-info-circle mr-1.5"></i> {{ session('status') }}
                    <button type="button" class="close p-2 text-white" data-dismiss="alert" aria-label="Close">
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

                <!-- 1. Input Email (Capsule Dark Input Persis Foto) -->
                <div class="dark-input-pill @error('email') has-error @enderror">
                    <i class="far fa-envelope input-pill-icon"></i>
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

                <!-- 2. Input Password & Tombol SHOW (Persis Foto) -->
                <div class="dark-input-pill @error('password') has-error @enderror">
                    <i class="fas fa-lock input-pill-icon"></i>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           placeholder="Kata Sandi Akun" 
                           required 
                           autocomplete="off">
                    <button type="button" 
                            class="btn-toggle-show-dark" 
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

                <!-- 3. Baris Remember Me & Lupa Password -->
                <div class="form-options-row">
                    <label class="dark-checkbox-label" for="remember">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Ingat saya</span>
                    </label>

                    <a href="{{ route('password.request') }}" class="dark-forgot-link">
                        Lupa Password?
                    </a>
                </div>

                <!-- 4. Tombol Submit (Gradasi Cerah Persis "Continue with Email") -->
                <button type="submit" class="btn-action-primary" id="btnSubmit">
                    <span>Masuk ke Sistem</span>
                    <i class="fas fa-arrow-right" style="font-size: 0.85rem;"></i>
                </button>
            </form>

            <!-- 5. Tombol Sekunder (Persis "Continue with Google" -> WebGIS Publik) -->
            <a href="{{ route('peta') }}" class="btn-action-secondary" title="Buka Peta Interaktif WebGIS Tanpa Login">
                <i class="fas fa-compass text-primary"></i>
                <span>Buka WebGIS Publik (Tanpa Login)</span>
            </a>

            <!-- 6. Footer Hak Cipta -->
            <div class="auth-footer-text">
                Hak Cipta &copy; {{ date('Y') }} &bull; Balai Wilayah Sungai Kalimantan I Pontianak<br>
                <span class="text-muted" style="font-size: 0.7rem;">Kementerian Pekerjaan Umum Republik Indonesia</span>
            </div>
        </section>

    </main>

    <!-- Scripts -->
    <script src="{{ asset('sbadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        // Toggle Show / Hide Password dengan tulisan SHOW / HIDE
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const showText = document.getElementById('showText');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                showText.textContent = 'HIDE';
                showText.style.color = '#f87171'; // Merah saat terlihat
            } else {
                passwordInput.type = 'password';
                showText.textContent = 'SHOW';
                showText.style.color = '#3b82f6'; // Biru saat tersembunyi
            }
        }

        // Integrasi Google reCAPTCHA v3 saat submit
        const loginForm = document.getElementById('loginForm');
        const siteKey = "{{ config('services.recaptcha.site_key') }}";

        loginForm.addEventListener('submit', function(e) {
            const btnSubmit = document.getElementById('btnSubmit');
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Memeriksa...';

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

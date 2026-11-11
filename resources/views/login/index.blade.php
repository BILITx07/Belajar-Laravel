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
            --bg-canvas: #06080d;
            --bg-surface: #0c101b;
            --bg-pill: #101524;
            --border-subtle: rgba(255, 255, 255, 0.08);
            --border-focus: #3b82f6;
            --pu-navy: #10143a;
            --pu-blue: #1d4ed8;
            --pu-blue-bright: #3b82f6;
            --pu-gold: #f59e0b;
            --pu-gold-bright: #fad605;
            --emerald-water: #10b981;
            --text-primary: #ffffff;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: var(--bg-canvas);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: var(--text-primary);
            overflow-x: hidden;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 40px 24px;
        }

        /* ═══ AMBIENT ATMOSPHERIC BACKGROUND LIGHTS ═══ */
        .ambient-glow-mesh-1 {
            position: fixed;
            top: -120px;
            left: -80px;
            width: 580px;
            height: 580px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, transparent 68%);
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
            z-index: 0;
        }

        .ambient-glow-mesh-2 {
            position: fixed;
            bottom: -140px;
            right: -80px;
            width: 640px;
            height: 640px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.14) 0%, transparent 68%);
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
            z-index: 0;
        }

        /* ═══ TOMBOL KEMBALI DI POJOK KIRI ATAS (1:1 PERSIS REFERENSI) ═══ */
        .btn-top-back-circular {
            position: fixed;
            top: 28px;
            left: 32px;
            width: 46px;
            height: 46px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.05rem;
            text-decoration: none !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            z-index: 99;
        }

        .btn-top-back-circular:hover {
            background: rgba(255, 255, 255, 0.14);
            border-color: rgba(255, 255, 255, 0.25);
            transform: scale(1.06);
            color: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        }

        /* ═══ WRAPPER DUA KOLOM PROPORSIONAL & SEIMBANG ═══ */
        .auth-master-container {
            width: 100%;
            max-width: 960px;
            display: grid;
            grid-template-columns: 420px minmax(0, 420px);
            justify-content: center;
            align-items: center;
            gap: 60px;
            position: relative;
            z-index: 10;
        }

        /* ═════════════════════════════════════════════════════════
           1. SISI KIRI: KARTU VISUAL BERGERAK (CINEMATIC LIVING CARD)
           ═════════════════════════════════════════════════════════ */
        .living-card-frame {
            position: relative;
            width: 100%;
            height: 550px;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 25px 70px -15px rgba(0, 0, 0, 0.85),
                        0 0 0 1px rgba(255, 255, 255, 0.12);
            transform: translateZ(0);
        }

        /* Halo Cahaya Berdenyut Lembut di Belakang Kartu (Persis Aura Foto) */
        .living-card-halo {
            position: absolute;
            inset: -20px;
            background: radial-gradient(circle at 45% 65%, rgba(16, 185, 129, 0.38) 0%, rgba(37, 99, 235, 0.32) 50%, transparent 75%);
            border-radius: 40px;
            filter: blur(40px);
            z-index: -1;
            opacity: 0.85;
            animation: pulseHalo 7s ease-in-out infinite alternate;
        }

        @keyframes pulseHalo {
            0% {
                opacity: 0.65;
                transform: scale(0.97);
            }
            100% {
                opacity: 1;
                transform: scale(1.03);
            }
        }

        /* Foto Bergerak Sinematik (Ken Burns Motion) */
        .living-card-image {
            position: absolute;
            inset: -10%;
            width: 120%;
            height: 120%;
            background-image: url('{{ asset('img/bws-kalimantan-river.jpg') }}');
            background-size: cover;
            background-position: center 30%;
            animation: cinematicPanZoom 26s cubic-bezier(0.25, 1, 0.5, 1) infinite alternate;
            will-change: transform;
        }

        @keyframes cinematicPanZoom {
            0% {
                transform: scale(1.05) translate3d(0%, 0%, 0);
            }
            50% {
                transform: scale(1.16) translate3d(-2%, -2%, 0);
            }
            100% {
                transform: scale(1.22) translate3d(2%, -1%, 0);
            }
        }

        /* Desir Gelombang Air Hidup (Water Flow Wave Overlay) */
        .living-water-ripple {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 55%;
            background: linear-gradient(180deg, transparent 0%, rgba(14, 165, 233, 0.1) 45%, rgba(16, 185, 129, 0.18) 100%);
            mix-blend-mode: overlay;
            pointer-events: none;
            animation: waterFlowWaves 6s ease-in-out infinite alternate;
        }

        @keyframes waterFlowWaves {
            0% {
                opacity: 0.45;
                transform: translateY(0) scaleY(1);
            }
            100% {
                opacity: 0.85;
                transform: translateY(-8px) scaleY(1.05);
            }
        }

        /* Partikel Kilau Embun & Cahaya Melayang (Floating Light Particles) */
        .living-particles-overlay {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            background-image: 
                radial-gradient(circle at 25% 40%, rgba(255, 255, 255, 0.75) 1.5px, transparent 1.5px),
                radial-gradient(circle at 70% 65%, rgba(250, 214, 5, 0.85) 2px, transparent 2px),
                radial-gradient(circle at 45% 75%, rgba(167, 243, 208, 0.75) 1.8px, transparent 1.8px),
                radial-gradient(circle at 80% 30%, rgba(255, 255, 255, 0.6) 1.2px, transparent 1.2px),
                radial-gradient(circle at 18% 70%, rgba(147, 197, 253, 0.8) 1.8px, transparent 1.8px);
            background-size: 240px 240px, 300px 300px, 200px 200px, 260px 260px, 220px 220px;
            animation: floatMotes 20s linear infinite;
            opacity: 0.75;
        }

        @keyframes floatMotes {
            0% {
                background-position: 0 0, 0 0, 0 0, 0 0, 0 0;
            }
            100% {
                background-position: -70px -380px, 90px -480px, -45px -280px, 50px -420px, -35px -350px;
            }
        }

        /* Gradien Vignette Kontras */
        .living-card-vignette {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, 
                rgba(0, 0, 0, 0.28) 0%, 
                rgba(0, 0, 0, 0.02) 35%, 
                rgba(6, 8, 13, 0.6) 68%, 
                rgba(6, 8, 13, 0.96) 100%);
            pointer-events: none;
        }

        /* Badge Instansi Atas */
        .living-card-top-pill {
            position: absolute;
            top: 20px;
            left: 20px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 13px;
            border-radius: 20px;
            background: rgba(8, 11, 20, 0.65);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.16);
            font-size: 0.72rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.4px;
            z-index: 5;
        }

        .living-card-top-pill .pulse-indicator {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 8px #10b981;
            animation: blinkStatus 2s infinite ease-in-out;
        }

        @keyframes blinkStatus {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }

        /* ═══ KARTU BRANDING BAWAH (1:1 DENGAN "WAVA AI" PADA FOTO REFERENSI) ═══ */
        .living-brand-bottom {
            position: absolute;
            bottom: 24px;
            left: 22px;
            right: 22px;
            z-index: 6;
        }

        /* Dual Logo PU & SIPDIRA Kapsul Kaca */
        .living-logo-capsule {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.95);
            padding: 7px 14px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.85);
            margin-bottom: 12px;
        }

        .living-logo-capsule img.logo-item-pu {
            height: 30px;
            width: auto;
            object-fit: contain;
        }

        .living-logo-capsule .logo-item-divider {
            width: 1px;
            height: 20px;
            background: #cbd5e1;
        }

        .living-logo-capsule img.logo-item-sipdira {
            height: 30px;
            width: auto;
            object-fit: contain;
        }

        .living-brand-title {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #ffffff;
            margin: 0 0 3px 0;
            line-height: 1.15;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
        }

        .living-brand-desc {
            font-size: 0.76rem;
            color: #cbd5e1;
            margin: 0;
            line-height: 1.45;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);
        }

        /* ═════════════════════════════════════════════════════════
           2. SISI KANAN: FORMULIR LOGIN (DARK MINIMALIST PERSIS FOTO)
           ═════════════════════════════════════════════════════════ */
        .form-column-panel {
            padding: 6px 0;
            width: 100%;
        }

        .form-main-heading {
            font-size: 2.15rem;
            font-weight: 800;
            letter-spacing: -0.6px;
            color: #ffffff;
            margin: 0 0 6px 0;
            line-height: 1.15;
        }

        .form-sub-heading {
            font-size: 0.86rem;
            color: var(--text-secondary);
            margin: 0 0 26px 0;
            line-height: 1.5;
        }

        /* Capsule Dark Inputs (1:1 Persis Foto Referensi) */
        .capsule-input-box {
            position: relative;
            background: var(--bg-pill);
            border: 1.5px solid var(--border-subtle);
            border-radius: 14px;
            height: 52px;
            display: flex;
            align-items: center;
            padding: 0 18px;
            margin-bottom: 14px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .capsule-input-box:focus-within {
            background: #141b2e;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.16);
        }

        .capsule-input-box.is-invalid-box {
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.08);
        }

        .capsule-icon-prefix {
            color: var(--text-muted);
            font-size: 0.95rem;
            width: 20px;
            text-align: center;
            margin-right: 14px;
            flex-shrink: 0;
            transition: color 0.2s ease;
        }

        .capsule-input-box:focus-within .capsule-icon-prefix {
            color: var(--pu-blue-bright);
        }

        .capsule-input-box input {
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

        .capsule-input-box input::placeholder {
            color: #64748b;
            font-size: 0.88rem;
            font-weight: 400;
        }

        /* Tombol SHOW / HIDE Text Toggle */
        .capsule-btn-toggle {
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

        .capsule-btn-toggle:hover {
            color: #93c5fd;
        }

        /* Baris Checkbox Remember Me & Forgot Password */
        .form-util-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            margin-top: 4px;
        }

        .form-remember-label {
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

        .form-remember-label input[type="checkbox"] {
            width: 17px;
            height: 17px;
            accent-color: var(--pu-blue);
            cursor: pointer;
            border-radius: 4px;
        }

        .form-forgot-anchor {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .form-forgot-anchor:hover {
            color: var(--pu-blue-bright);
            text-decoration: underline;
        }

        /* ═══ TOMBOL UTAMA: SIGN IN (GRADASI CERAH PERSIS FOTO) ═══ */
        .btn-submit-gradient {
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
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.4);
        }

        .btn-submit-gradient:hover {
            background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 50%, #2563eb 100%);
            box-shadow: 0 12px 30px rgba(37, 99, 235, 0.55);
            transform: translateY(-1.5px);
            color: #ffffff;
        }

        .btn-submit-gradient:active {
            transform: translateY(0);
        }

        /* ═══ TOMBOL SEKUNDER (PERSIS "CONTINUE WITH GOOGLE" DENGAN OUTLINE GELAP) ═══ */
        .btn-public-map-outline {
            width: 100%;
            height: 48px;
            background: #0f1422;
            color: #cbd5e1;
            border: 1.5px solid rgba(255, 255, 255, 0.1);
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

        .btn-public-map-outline:hover {
            background: #141b2c;
            border-color: rgba(255, 255, 255, 0.22);
            color: #ffffff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4);
            transform: translateY(-1px);
        }

        /* Teks Footer Bawah */
        .form-footer-copyright {
            margin-top: 26px;
            text-align: center;
            font-size: 0.76rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* reCAPTCHA Invisible */
        .grecaptcha-badge {
            visibility: hidden !important;
        }

        /* ═══ RESPONSIVE (TABLET & MOBILE) ═══ */
        @media (max-width: 900px) {
            body {
                padding: 60px 18px 30px;
            }

            .btn-top-back-circular {
                top: 16px;
                left: 16px;
                width: 40px;
                height: 40px;
            }

            .auth-master-container {
                grid-template-columns: 1fr;
                gap: 32px;
                max-width: 420px;
            }

            .living-card-frame {
                height: 340px;
                border-radius: 22px;
            }

            .form-main-heading {
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

    <!-- Ambient Glow Mesh Lights -->
    <div class="ambient-glow-mesh-1"></div>
    <div class="ambient-glow-mesh-2"></div>

    <!-- Tombol Kembali ke Peta Spasial (Pojok Kiri Atas Persis Foto) -->
    <a href="{{ route('peta') }}" class="btn-top-back-circular" title="Kembali ke Peta Spasial WebGIS">
        <i class="fas fa-arrow-left"></i>
    </a>

    <main class="auth-master-container">

        <!-- ═════════════════════════════════════════════════════════
             1. SISI KIRI: KARTU VISUAL BERGERAK (CINEMATIC LIVING CARD)
             ═════════════════════════════════════════════════════════ -->
        <section class="living-card-frame">
            <!-- Aura Cahaya Ambient Berdenyut di Belakang Kartu -->
            <div class="living-card-halo"></div>

            <!-- 1. Lapisan Foto Bergerak (Cinematic Ken Burns Motion) -->
            <div class="living-card-image"></div>

            <!-- 2. Lapisan Ombak & Desir Aliran Air yang Bergerak Hidup -->
            <div class="living-water-ripple"></div>

            <!-- 3. Partikel Embun dan Cahaya Melayang Sinematik -->
            <div class="living-particles-overlay"></div>

            <!-- 4. Vignette Kontras Gradasi Bawah -->
            <div class="living-card-vignette"></div>

            <!-- Tag Instansi Bagian Atas Kartu -->
            <div class="living-card-top-pill">
                <span class="pulse-indicator"></span>
                <span>BWS KALIMANTAN I &bull; DITJEN SDA</span>
            </div>

            <!-- 5. Plat Branding di Bawah Kartu (Persis Posisi "Wava AI" di Foto) -->
            <div class="living-brand-bottom">
                <!-- Kapsul Kaca: Logo PU + Logo SIPDIRA -->
                <div class="living-logo-capsule">
                    <img src="{{ asset('img/logo-pu.svg') }}" class="logo-item-pu" alt="Logo Kementerian Pekerjaan Umum" title="Kementerian Pekerjaan Umum">
                    <div class="logo-item-divider"></div>
                    <img src="{{ asset('img/logo-sipdira.svg') }}" class="logo-item-sipdira" alt="Logo SIPDIRA Spasial" title="SIPDIRA Spasial">
                </div>

                <h1 class="living-brand-title">SIPDIRA SPASIAL</h1>
                <p class="living-brand-desc">
                    Sistem Informasi Peta Daerah Irigasi &bull; Pemantauan Geospasial Sumber Daya Air Kalimantan Barat
                </p>
            </div>
        </section>

        <!-- ═════════════════════════════════════════════════════════
             2. SISI KANAN: FORMULIR LOGIN (DARK MINIMALIST PERSIS FOTO)
             ═════════════════════════════════════════════════════════ -->
        <section class="form-column-panel">
            <!-- Judul Formulir (Persis "Create an account" di Foto) -->
            <h2 class="form-main-heading">Akses Portal Masuk</h2>
            <p class="form-sub-heading">
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
                <div class="capsule-input-box @error('email') is-invalid-box @enderror">
                    <i class="far fa-envelope capsule-icon-prefix"></i>
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
                <div class="capsule-input-box @error('password') is-invalid-box @enderror">
                    <i class="fas fa-lock capsule-icon-prefix"></i>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           placeholder="Kata Sandi Akun" 
                           required 
                           autocomplete="off">
                    <button type="button" 
                            class="capsule-btn-toggle" 
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
                <div class="form-util-row">
                    <label class="form-remember-label" for="remember">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Ingat saya</span>
                    </label>

                    <a href="{{ route('password.request') }}" class="form-forgot-anchor">
                        Lupa Password?
                    </a>
                </div>

                <!-- 4. Tombol Submit (Gradasi Cerah Persis "Continue with Email") -->
                <button type="submit" class="btn-submit-gradient" id="btnSubmit">
                    <span>Masuk ke Sistem</span>
                    <i class="fas fa-arrow-right" style="font-size: 0.85rem;"></i>
                </button>
            </form>

            <!-- 5. Tombol Sekunder (Persis "Continue with Google" -> WebGIS Publik) -->
            <a href="{{ route('peta') }}" class="btn-public-map-outline" title="Buka Peta Interaktif WebGIS Tanpa Login">
                <i class="fas fa-compass text-primary"></i>
                <span>Buka WebGIS Publik (Tanpa Login)</span>
            </a>

            <!-- 6. Footer Hak Cipta -->
            <div class="form-footer-copyright">
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
                passwordInput.type = 'password';
                showText.textContent = 'SHOW';
                showText.style.color = '#3b82f6';
            } else {
                passwordInput.type = 'text';
                showText.textContent = 'HIDE';
                showText.style.color = '#f87171';
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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard — WebGIS BWS Kalimantan I')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('sbadmin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Core -->
    <link href="{{ asset('sbadmin/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --bws-blue-header: #1553a3;
            --bws-blue-gradient: linear-gradient(90deg, #0d3875 0%, #174ea6 55%, #1b5fb5 100%);
            --bws-accent-yellow: #f59e0b;
            --bws-bg-page: #f8fafc;
            --bws-border: #e2e8f0;
            --bws-text-dark: #1e293b;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bws-bg-page);
            color: var(--bws-text-dark);
            min-height: 100vh;
        }

        /* ── TOPBAR BIRU UTAMA (SESUAI GAMBAR REFERENSI BWS) ── */
        .bws-topbar-primary {
            background: var(--bws-blue-gradient);
            height: 64px;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #fff;
            box-shadow: 0 2px 8px rgba(13, 56, 117, 0.2);
            position: sticky;
            top: 0;
            z-index: 1040;
        }

        .bws-brand-section {
            display: flex;
            align-items: center;
        }

        .bws-logo-circle {
            width: 40px;
            height: 40px;
            background: #ffc107;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
            margin-right: 14px;
            flex-shrink: 0;
        }

        .bws-brand-title {
            font-size: 0.95rem;
            font-weight: 800;
            letter-spacing: 0.4px;
            line-height: 1.2;
            color: #ffffff;
        }

        .bws-brand-sub {
            font-size: 0.72rem;
            color: #dbeafe;
            line-height: 1.1;
        }

        /* Nav Kanan Topbar */
        .bws-topbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .bws-action-icon {
            color: rgba(255, 255, 255, 0.85);
            font-size: 1.05rem;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s ease;
            text-decoration: none;
            position: relative;
            background: rgba(255, 255, 255, 0.08);
        }

        .bws-action-icon:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.2);
        }

        .bws-notif-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #ef4444;
            color: #fff;
            font-size: 0.65rem;
            font-weight: 800;
            width: 17px;
            height: 17px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #174ea6;
        }

        .bws-user-trigger {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 4px 10px 4px 4px;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.12);
            transition: background 0.2s ease;
            text-decoration: none;
            color: #fff;
        }

        .bws-user-trigger:hover {
            background: rgba(255, 255, 255, 0.22);
            color: #fff;
        }

        .bws-user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #3b82f6;
            color: #fff;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            margin-right: 8px;
            border: 2px solid #ffc107;
        }

        /* ── SUB-NAVBAR MENU NAVIGASI ADMIN (SESUAI GAMBAR REFERENSI BWS) ── */
        .bws-subnavbar {
            background: #ffffff;
            border-bottom: 1px solid var(--bws-border);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            position: sticky;
            top: 64px;
            z-index: 1030;
        }

        .bws-nav-link {
            display: inline-flex;
            align-items: center;
            padding: 14px 18px;
            color: #64748b;
            font-weight: 600;
            font-size: 0.88rem;
            text-decoration: none;
            border-bottom: 3px solid transparent;
            transition: all 0.2s ease;
            gap: 8px;
        }

        .bws-nav-link:hover {
            color: #174ea6;
            text-decoration: none;
        }

        .bws-nav-link.active {
            color: #174ea6;
            border-bottom-color: #174ea6;
            font-weight: 700;
        }

        .bws-dropdown-menu {
            border: none;
            border-radius: 10px;
            box-shadow: 0 10px 28px rgba(0,0,0,0.12);
            margin-top: 6px;
            padding: 6px 0;
            font-size: 0.85rem;
        }

        .bws-dropdown-item {
            padding: 9px 18px;
            color: #334155;
            display: flex;
            align-items: center;
            font-weight: 500;
            transition: background 0.15s ease;
        }

        .bws-dropdown-item:hover {
            background-color: #eff6ff;
            color: #1d4ed8;
        }

        /* ── PROFIL DROPDOWN MENU POJOK KANAN (PERSIS GAMBAR REFERENSI BWS) ── */
        .profile-popup-menu {
            width: 210px;
            border-radius: 12px;
            border: none;
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
            padding: 8px 0;
        }

        .profile-popup-item {
            display: flex;
            align-items: center;
            padding: 9px 20px;
            font-size: 0.88rem;
            color: #334155;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .profile-popup-item:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }

        .profile-popup-item i {
            width: 20px;
            font-size: 0.95rem;
            margin-right: 12px;
            color: #64748b;
        }

        .profile-popup-item.logout {
            color: #ef4444;
        }

        .profile-popup-item.logout i {
            color: #ef4444;
        }

        .profile-popup-item.logout:hover {
            background-color: #fef2f2;
        }

        /* ── KONTEN AREA ── */
        .admin-main-container {
            padding: 24px 28px 48px;
            max-width: 1440px;
            margin: 0 auto;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- ═══ 1. NAVBAR UTAMA ATAS (TEMA BIRU PU BWS KALIMANTAN I) ═══ -->
    <header class="bws-topbar-primary">
        <!-- Logo & Instansi -->
        <div class="bws-brand-section">
            <a href="{{ route('peta') }}" class="mr-3 text-white text-decoration-none" title="Lihat Peta Utama">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:rgba(255,255,255,0.18);">
                    <i class="fas fa-arrow-left fa-sm text-white"></i>
                </div>
            </a>
            <div class="bws-logo-circle">
                <svg viewBox="0 0 100 100" width="28" height="28">
                    <circle cx="50" cy="50" r="46" fill="#ffc107" />
                    <path d="M 50,8 A 42,42 0 1,0 92,50 A 42,42 0 0,0 50,8 Z" fill="none" stroke="#0d3875" stroke-width="6" />
                    <path d="M50 15 L55 35 L75 35 L60 48 L68 68 L50 55 L32 68 L40 48 L25 35 L45 35 Z" fill="#0d3875" />
                    <circle cx="50" cy="50" r="10" fill="#ffc107" />
                    <circle cx="50" cy="50" r="5" fill="#0d3875" />
                </svg>
            </div>
            <div>
                <div class="bws-brand-title">FEWS BWS KALIMANTAN I</div>
                <div class="bws-brand-sub">Direktorat Jenderal Sumber Daya Air &bull; Kementerian Pekerjaan Umum</div>
            </div>
        </div>

        <!-- Tombol Aksi & Profil Petugas -->
        <div class="bws-topbar-actions">
            <!-- Fullscreen Toggle -->
            <a href="#" class="bws-action-icon d-none d-sm-flex" onclick="toggleFullScreen(event)" title="Layar Penuh">
                <i class="fas fa-expand"></i>
            </a>

            <!-- Notifikasi Bell -->
            <div class="position-relative">
                <a href="#" class="bws-action-icon" title="Notifikasi Sistem">
                    <i class="far fa-bell"></i>
                    <span class="bws-notif-badge">3</span>
                </a>
            </div>

            <!-- Profile Dropdown (Sesuai Referensi Gambar BWS) -->
            <div class="dropdown">
                <div class="bws-user-trigger" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdownUserMenu">
                    <div class="bws-user-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <span class="font-weight-bold mr-1" style="font-size:0.86rem;">
                        {{ Auth::user()->name ?? 'Admin' }}
                    </span>
                    <i class="fas fa-chevron-down" style="font-size:0.7rem; opacity:0.8;"></i>
                </div>

                <!-- Dropdown Menu Profil -->
                <div class="dropdown-menu dropdown-menu-right profile-popup-menu" aria-labelledby="dropdownUserMenu">
                    <a class="profile-popup-item" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-cog"></i> Admin Panel
                    </a>
                    <a class="profile-popup-item" href="{{ route('profile') }}">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <a class="profile-popup-item d-flex justify-content-between align-items-center" href="{{ route('profile') }}">
                        <div><i class="fas fa-sliders-h"></i> Settings</div>
                        <span class="badge badge-success font-weight-bold" style="background:#10b981;font-size:0.7rem;">11</span>
                    </a>
                    <div class="dropdown-divider my-1"></div>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="profile-popup-item logout border-0 bg-transparent w-100 text-left">
                            <i class="fas fa-power-off"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- ═══ 2. MENU NAVIGASI ADMIN SEKUNDER (SESUAI GAMBAR REFERENSI BWS) ═══ -->
    <nav class="bws-subnavbar">
        <!-- 1. Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="bws-nav-link {{ request()->routeIs('admin.dashboard', 'dashboard', 'admin.index') && !request()->has('type') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>

        <!-- 2. Master Data GIS (Dengan Dropdown) -->
        <div class="dropdown">
            <a href="#" class="bws-nav-link dropdown-toggle {{ request()->has('type') ? 'active' : '' }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-database"></i>
                <span>Master Data GIS</span>
                <i class="fas fa-chevron-down ml-1" style="font-size:0.7rem;"></i>
            </a>
            <div class="dropdown-menu bws-dropdown-menu">
                <a class="dropdown-item bws-dropdown-item" href="{{ route('admin.dashboard', ['type' => 'irigasi']) }}">
                    <i class="fas fa-seedling mr-2 text-success"></i> Daerah Irigasi (Baku, Fung, Pot)
                </a>
                <a class="dropdown-item bws-dropdown-item" href="{{ route('admin.dashboard', ['type' => 'saluran']) }}">
                    <i class="fas fa-stream mr-2 text-primary"></i> Jaringan Saluran Irigasi
                </a>
                <a class="dropdown-item bws-dropdown-item" href="{{ route('admin.dashboard', ['type' => 'bangunan']) }}">
                    <i class="fas fa-landmark mr-2 text-danger"></i> Bangunan Air &amp; Pelengkap
                </a>
                <div class="dropdown-divider my-1"></div>
                <a class="dropdown-item bws-dropdown-item font-weight-bold" href="{{ route('admin.dashboard', ['type' => 'semua']) }}">
                    <i class="fas fa-layer-group mr-2 text-info"></i> Tampilkan Semua Data
                </a>
            </div>
        </div>

        <!-- 3. Peta Utama / WebGIS -->
        <a href="{{ route('peta') }}" class="bws-nav-link" target="_blank" title="Buka Halaman Peta Interaktif Publik">
            <i class="fas fa-map-marked-alt text-warning"></i>
            <span>Peta Utama</span>
            <i class="fas fa-external-link-alt ml-1" style="font-size:0.65rem; opacity:0.6;"></i>
        </a>

        <!-- 4. Manajemen User -->
        @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'superadmin']))
        <a href="{{ route('users.index') }}" class="bws-nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
            <i class="fas fa-users-cog"></i>
            <span>Manajemen User</span>
        </a>
        @endif
    </nav>

    <!-- ═══ 3. KONTEN UTAMA HALAMAN ═══ -->
    <main class="admin-main-container">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="{{ asset('sbadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        function toggleFullScreen(e) {
            if (e) e.preventDefault();
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(() => {});
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
            }
        }
    </script>
    @stack('scripts')
</body>
</html>

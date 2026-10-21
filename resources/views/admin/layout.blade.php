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
            height: 66px;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #fff;
            box-shadow: 0 2px 8px rgba(13, 56, 117, 0.2);
            position: sticky;
            top: 0;
            z-index: 1040;
            border-bottom: 3.5px solid #ff9f1a;
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
            top: 70px;
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
            margin-top: 2px;
            padding: 6px 0;
            font-size: 0.85rem;
            position: absolute;
        }

        /* Area jembatan tak terlihat di atas dropdown agar kursor tidak kehilangan fokus saat berpindah */
        .bws-dropdown-menu::before {
            content: '';
            position: absolute;
            top: -12px;
            left: 0;
            right: 0;
            height: 14px;
            background: transparent;
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

        /* Tampilkan dropdown saat class .show aktif (dikontrol via JS dengan jeda waktu / delay toleransi) */
        .bws-subnavbar .dropdown.show > .bws-dropdown-menu,
        .bws-subnavbar .dropdown:focus-within > .bws-dropdown-menu,
        .bws-dropdown-menu.show {
            display: block !important;
        }

        /* ── MULTI-LEVEL NESTED DROPDOWN (MASTER DATA > INFRASTRUKTUR) ── */
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu > .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -6px;
            margin-left: -1px;
            border-radius: 10px;
            display: none;
            border: none;
            box-shadow: 0 10px 28px rgba(0,0,0,0.12);
            padding: 6px 0;
            min-width: 220px;
        }

        /* Bridge area so mouse moving diagonally into submenu does not close */
        .dropdown-submenu > .dropdown-menu::before {
            content: '';
            position: absolute;
            top: 0;
            left: -15px;
            width: 18px;
            bottom: 0;
            background: transparent;
        }

        .dropdown-submenu:hover > .dropdown-menu,
        .dropdown-submenu.show > .dropdown-menu,
        .dropdown-submenu:focus-within > .dropdown-menu {
            display: block !important;
        }

        .dropdown-submenu > a.dropdown-item::after {
            display: none !important;
        }

        .submenu-arrow {
            font-size: 0.68rem;
            color: #94a3b8;
            margin-left: auto;
            padding-left: 18px;
            transition: color 0.15s ease, transform 0.15s ease;
        }

        .dropdown-submenu:hover .submenu-arrow,
        .dropdown-submenu.show .submenu-arrow {
            color: #1d4ed8;
            transform: translateX(3px);
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

    <!-- ═══ 1. NAVBAR UTAMA ATAS (TEMA BIRU PU BWS KALIMANTAN I SESUAI REFERENSI) ═══ -->
    <header class="bws-topbar-primary" style="background: #1b4f9c; height: 66px; border-bottom: 3.5px solid #f59e0b;">
        <!-- Logo & Instansi -->
        <div class="bws-brand-section">
            <a href="{{ route('admin.dashboard') }}" class="btn rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center mr-3" style="width:36px;height:36px;border:none;text-decoration:none;" title="Kembali ke Dashboard">
                <i class="fas fa-chevron-left" style="font-size:13px; color:#1e293b; font-weight:700;"></i>
            </a>
            <img src="{{ asset('img/logo-pu.png') }}" alt="Logo Kementerian PU" class="mr-3 shadow-sm" style="width: 44px; height: 44px; border-radius: 9px; object-fit: contain;">
            <div class="d-flex flex-column text-white">
                <span class="font-weight-bold" style="font-size:0.95rem; letter-spacing:0.4px; line-height:1.2;">
                    WEBGIS BWS KALIMANTAN I
                </span>
                <span style="font-size:0.72rem; color:rgba(255,255,255,0.9); line-height:1.15; font-weight:500;">
                    Direktorat Jenderal Sumber Daya Air
                </span>
                <span style="font-size:0.72rem; color:rgba(255,255,255,0.9); line-height:1.15; font-weight:500;">
                    Kementerian Pekerjaan Umum
                </span>
            </div>
        </div>

        <!-- Tombol Aksi & Profil Petugas -->
        <div class="bws-topbar-actions">
            <!-- Fullscreen Toggle -->
            <a href="#" class="bws-action-icon d-none d-sm-flex" onclick="toggleFullScreen(event)" title="Layar Penuh" style="opacity:0.95;">
                <i class="fas fa-expand fa-lg text-white"></i>
            </a>

         

            <!-- Profile Dropdown (Sesuai Referensi Gambar BWS) -->
            <div class="dropdown">
                <div class="bws-user-trigger" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdownUserMenu" style="background:rgba(255,255,255,0.16); border-radius:25px; padding:3px 12px 3px 4px;">
                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center mr-2 shadow-sm" style="width:32px; height:32px;">
                        <i class="fas fa-user" style="color:#1d64d8; font-size:14px;"></i>
                    </div>
                    <span class="font-weight-bold mr-1 text-white" style="font-size:0.86rem;">
                        {{ Auth::user()->name ?? 'Admin Dinar' }}
                    </span>
                    <i class="fas fa-chevron-down" style="font-size:0.65rem; opacity:0.8; color:#fff;"></i>
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

    <!-- ═══ 2. MENU NAVIGASI ADMIN SEKUNDER ═══ -->
    <!-- ═══ 2. MENU NAVIGASI ADMIN SEKUNDER (SESUAI GAMBAR REFERENSI FEWS BWS) ═══ -->
    <nav class="bws-subnavbar">
        <!-- 1. Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="bws-nav-link {{ request()->routeIs('admin.dashboard', 'dashboard', 'admin.index') ? 'active' : '' }}">
            <i class="fas fa-th-large text-primary"></i>
            <span>Dashboard</span>
        </a>

        <!-- 2. Master Data (Dropdown Bertingkat Sesuai Data GIS Aktual) -->
        <div class="dropdown">
            <a href="#" class="bws-nav-link dropdown-toggle {{ request()->routeIs('admin.kelola-gis', 'admin.data-gis') ? 'active' : '' }}" id="dropdownMasterData" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-layer-group text-warning"></i>
                <span>Master Data</span>
            </a>
            <ul class="dropdown-menu bws-dropdown-menu" aria-labelledby="dropdownMasterData" style="min-width: 215px;">
                <li>
                    <a class="dropdown-item bws-dropdown-item" href="{{ route('admin.data-gis', ['type' => 'semua']) }}">
                        <i class="fas fa-list fa-fw mr-2" style="color:#64748b;"></i> Semua File
                    </a>
                </li>
                <li><div class="dropdown-divider my-1"></div></li>

                <!-- Submenu 1: Irigasi (DIR) -->
                <li class="dropdown-submenu">
                    <a class="dropdown-item bws-dropdown-item d-flex align-items-center justify-content-between" href="{{ route('admin.data-gis', ['type' => 'irigasi']) }}">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-seedling fa-fw mr-2" style="color:#10b981;"></i>
                            <span>Irigasi</span>
                        </div>
                        <i class="fas fa-chevron-right submenu-arrow"></i>
                    </a>
                    <ul class="dropdown-menu bws-dropdown-menu">
                        <li>
                            <a class="dropdown-item bws-dropdown-item" href="{{ route('admin.data-gis', ['type' => 'baku']) }}">
                                <i class="fas fa-leaf fa-fw mr-2" style="color:#22c55e;"></i> DIR Baku
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item bws-dropdown-item" href="{{ route('admin.data-gis', ['type' => 'fungsional']) }}">
                                <i class="fas fa-water fa-fw mr-2" style="color:#3b82f6;"></i> DIR Fungsional
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item bws-dropdown-item" href="{{ route('admin.data-gis', ['type' => 'potensial']) }}">
                                <i class="fas fa-seedling fa-fw mr-2" style="color:#f97316;"></i> DIR Potensial
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Submenu 2: Infrastruktur (Jaringan Saluran & Bangunan Air) -->
                <li class="dropdown-submenu">
                    <a class="dropdown-item bws-dropdown-item d-flex align-items-center justify-content-between" href="{{ route('admin.data-gis', ['type' => 'infrastruktur']) }}">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-landmark fa-fw mr-2" style="color:#2563eb;"></i>
                            <span>Infrastruktur</span>
                        </div>
                        <i class="fas fa-chevron-right submenu-arrow"></i>
                    </a>
                    <ul class="dropdown-menu bws-dropdown-menu">
                        <li>
                            <a class="dropdown-item bws-dropdown-item" href="{{ route('admin.data-gis', ['type' => 'saluran']) }}">
                                <i class="fas fa-stream fa-fw mr-2" style="color:#ec4899;"></i> Jaringan Saluran
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item bws-dropdown-item" href="{{ route('admin.data-gis', ['type' => 'bangunan']) }}">
                                <i class="fas fa-landmark fa-fw mr-2" style="color:#8b5cf6;"></i> Bangunan Air
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        <!-- 3. Manajemen User (Hanya Super Admin) -->
        @if(Auth::check() && Auth::user()->role === 'superadmin')
        <a href="{{ route('users.index') }}" class="bws-nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fas fa-user-friends text-info"></i>
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
    <!-- shpjs: Otomatis membaca & mengekstrak file .zip Shapefile (ArcGIS/QGIS) ke GeoJSON -->
    <script src="https://cdn.jsdelivr.net/npm/shpjs@6.2.0/dist/shp.js"></script>
    <script>
        function toggleFullScreen(e) {
            if (e) e.preventDefault();
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(() => {});
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
            }
        }

        // Delay / Jeda waktu toleransi saat memilih menu dropdown (Master Data)
        $(document).ready(function() {
            var dropdownTimers = {};

            $('.bws-subnavbar .dropdown').each(function(idx) {
                var $dropdown = $(this);
                var $menu = $dropdown.find('> .bws-dropdown-menu');
                var $toggle = $dropdown.find('> [data-toggle="dropdown"]');
                var timerKey = 'dd_timer_' + idx;

                $dropdown.on('mouseenter', function() {
                    if (dropdownTimers[timerKey]) {
                        clearTimeout(dropdownTimers[timerKey]);
                        dropdownTimers[timerKey] = null;
                    }
                    $dropdown.addClass('show');
                    $menu.addClass('show');
                    $toggle.attr('aria-expanded', 'true');
                });

                $dropdown.on('mouseleave', function() {
                    dropdownTimers[timerKey] = setTimeout(function() {
                        $dropdown.removeClass('show');
                        $menu.removeClass('show');
                        $toggle.attr('aria-expanded', 'false');
                        $dropdown.find('.dropdown-submenu').removeClass('show');
                        $dropdown.find('.dropdown-submenu > .dropdown-menu').removeClass('show');
                    }, 400); // Delay 400ms agar kursor tidak langsung keluar/tertutup saat hendak memilih
                });
            });

            // Submenu delay (Irigasi & Infrastruktur)
            $('.dropdown-submenu').each(function(sIdx) {
                var $submenu = $(this);
                var $subMenuList = $submenu.find('> .dropdown-menu');
                var sTimerKey = 'sub_timer_' + sIdx;

                $submenu.on('mouseenter', function() {
                    if (dropdownTimers[sTimerKey]) {
                        clearTimeout(dropdownTimers[sTimerKey]);
                        dropdownTimers[sTimerKey] = null;
                    }
                    $submenu.addClass('show');
                    $subMenuList.addClass('show');
                });

                $submenu.on('mouseleave', function() {
                    dropdownTimers[sTimerKey] = setTimeout(function() {
                        $submenu.removeClass('show');
                        $subMenuList.removeClass('show');
                    }, 350); // Delay 350ms toleransi kursor berpindah
                });
            });

            // Tutup dropdown jika user klik di luar
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.bws-subnavbar .dropdown').length) {
                    $('.bws-subnavbar .dropdown').removeClass('show');
                    $('.bws-subnavbar .bws-dropdown-menu').removeClass('show');
                    $('.dropdown-submenu').removeClass('show');
                    $('.dropdown-submenu > .dropdown-menu').removeClass('show');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>

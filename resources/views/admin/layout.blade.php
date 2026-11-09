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

        /* Tombol Pintasan Lihat Peta Modern & Mewah */
        .btn-lihat-peta-modern {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: linear-gradient(135deg, #fcd34d 0%, #f59e0b 55%, #d97706 100%);
            color: #0f274a !important;
            font-weight: 800;
            font-size: 0.81rem;
            letter-spacing: 0.2px;
            padding: 6px 15px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.45);
            box-shadow: 0 3px 12px rgba(217, 119, 6, 0.35), inset 0 1px 1px rgba(255, 255, 255, 0.7);
            text-decoration: none !important;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            line-height: 1.2;
        }

        .btn-lihat-peta-modern:hover {
            background: linear-gradient(135deg, #fde68a 0%, #fbbf24 55%, #f59e0b 100%);
            transform: translateY(-1.5px);
            box-shadow: 0 6px 18px rgba(217, 119, 6, 0.5), inset 0 1px 2px #ffffff;
            color: #0a1b33 !important;
        }

        .btn-lihat-peta-modern:active {
            transform: translateY(0.5px);
            box-shadow: 0 2px 6px rgba(217, 119, 6, 0.3);
        }

        .btn-lihat-peta-modern i {
            font-size: 0.84rem;
            color: #0f274a;
            transition: transform 0.2s ease;
        }

        .btn-lihat-peta-modern:hover i {
            transform: scale(1.15);
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
            display: inline-flex;
            align-items: center;
            cursor: pointer;
            padding: 3px 12px 3px 4px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.28);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08), inset 0 1px 1px rgba(255, 255, 255, 0.25);
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none !important;
            color: #ffffff;
            gap: 8px;
        }

        .bws-user-trigger:hover,
        .dropdown.show .bws-user-trigger {
            background: rgba(255, 255, 255, 0.24);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.16), inset 0 1px 2px rgba(255, 255, 255, 0.4);
            transform: translateY(-1px);
            color: #ffffff;
        }

        .bws-user-avatar-frame {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            border: 2px solid rgba(250, 204, 21, 0.95);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease;
            color: #ffffff;
            font-weight: 800;
            font-size: 0.76rem;
            letter-spacing: 0.5px;
        }

        .bws-user-avatar-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .bws-user-trigger:hover .bws-user-avatar-frame {
            transform: scale(1.06);
            border-color: #fde047;
        }

        .bws-user-info {
            display: flex;
            flex-direction: column;
            text-align: left;
            line-height: 1.15;
        }

        .bws-user-name {
            font-weight: 700;
            font-size: 0.82rem;
            color: #ffffff;
            letter-spacing: 0.15px;
            white-space: nowrap;
        }

        .bws-user-role {
            font-size: 0.67rem;
            font-weight: 800;
            letter-spacing: 0.4px;
            color: #fcd34d;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .bws-user-chevron {
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.8);
            transition: transform 0.25s ease;
            margin-left: 1px;
        }

        .dropdown.show .bws-user-chevron {
            transform: rotate(180deg);
            color: #ffffff;
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
        <!-- Logo & Instansi Resmi -->
        <div class="bws-brand-section">
            <div class="mr-3 d-flex align-items-center justify-content-center bg-white shadow-xs" style="width: 44px; height: 44px; min-width: 44px; border-radius: 11px; padding: 4px; border: 1.5px solid rgba(250, 214, 5, 0.95);">
                <img src="{{ asset('img/logo-pu.svg') }}" alt="Logo Kementerian PU" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
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
            <!-- Tombol Pintasan Lihat Peta WebGIS Modern -->
            <a href="{{ route('peta') }}" class="btn-lihat-peta-modern mr-2" title="Buka Halaman Peta WebGIS">
                <i class="fas fa-map-marked-alt"></i>
                <span>Lihat Peta</span>
            </a>

            <!-- Fullscreen Toggle -->
            <a href="#" class="bws-action-icon d-none d-sm-flex" onclick="toggleFullScreen(event)" title="Layar Penuh" style="opacity:0.95;">
                <i class="fas fa-expand fa-lg text-white"></i>
            </a>

            <!-- NOTIFIKASI AKTIVITAS KHUSUS ADMIN & SUPER ADMIN -->
            @php
                $recentLogs = \App\Helpers\ActivityLogger::getRecent(5);
                $countLogs = count($recentLogs);
            @endphp
            <div class="dropdown no-arrow position-relative">
                <a class="bws-action-icon position-relative text-white" 
                   href="#" 
                   id="adminNotifDropdown" 
                   role="button" 
                   data-toggle="dropdown" 
                   aria-haspopup="true" 
                   aria-expanded="false" 
                   title="Pusat Notifikasi Admin">
                    <i class="fas fa-bell"></i>
                    @if($countLogs > 0)
                    <span class="bws-notif-badge">
                        {{ $countLogs }}
                    </span>
                    @endif
                </a>
                
                <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 animated--grow-in mt-2 p-0" 
                     aria-labelledby="adminNotifDropdown" 
                     style="border-radius: 14px; min-width: 330px; max-width: 380px; overflow: hidden; z-index: 1060; box-shadow: 0 10px 30px rgba(15,23,42,0.15) !important;">
                    
                    <!-- Header Dropdown Notifikasi -->
                    <div class="d-flex align-items-center justify-content-between px-3 py-2.5 bg-white border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 28px; height: 28px; background: #eff6ff; color: #1d4ed8; font-size: 0.8rem;">
                                <i class="fas fa-bell"></i>
                            </div>
                            <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">Pemberitahuan Sistem</span>
                        </div>
                        <span class="badge badge-light border text-primary font-weight-bold px-2 py-1" style="font-size: 0.72rem; border-radius: 6px; background: #eff6ff; border-color: #bfdbfe;">
                            {{ $countLogs }} Aktivitas Baru
                        </span>
                    </div>

                    <!-- Daftar Item Notifikasi -->
                    <div class="list-group list-group-flush" style="font-size: 12px; max-height: 320px; overflow-y: auto;">
                        @forelse($recentLogs as $log)
                            @php
                                $act = strtolower($log['action'] ?? 'update');
                                $palette = match($act) {
                                    'upload'     => ['bg' => '#ecfdf5', 'color' => '#059669', 'icon' => 'fa-cloud-upload-alt'],
                                    'delete'     => ['bg' => '#fef2f2', 'color' => '#dc2626', 'icon' => 'fa-trash-alt'],
                                    'restore'    => ['bg' => '#eff6ff', 'color' => '#1d4ed8', 'icon' => 'fa-undo'],
                                    'clear_logs' => ['bg' => '#f1f5f9', 'color' => '#475569', 'icon' => 'fa-broom'],
                                    'rename'     => ['bg' => '#fffbeb', 'color' => '#d97706', 'icon' => 'fa-pen'],
                                    default      => ['bg' => '#f0fdf4', 'color' => '#15803d', 'icon' => 'fa-database'],
                                };
                            @endphp
                            <div class="list-group-item d-flex align-items-start py-2.5 px-3 border-bottom notif-item" style="transition: background 0.15s ease;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-2 mt-1 flex-shrink-0" style="width: 32px; height: 32px; background: {{ $palette['bg'] }}; color: {{ $palette['color'] }}; font-size: 0.78rem;">
                                    <i class="fas {{ $palette['icon'] }}"></i>
                                </div>
                                <div class="flex-grow-1" style="line-height: 1.35;">
                                    <div class="d-flex align-items-center justify-content-between mb-0.5">
                                        <span class="font-weight-bold text-dark" style="font-size: 0.82rem;">{{ $log['user'] ?? 'Petugas' }}</span>
                                        <small class="text-muted" style="font-size: 0.7rem;">
                                            {{ \Carbon\Carbon::parse($log['time'])->locale('id')->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div class="text-secondary small" style="font-size: 0.78rem; word-break: break-word;">
                                        {{ $log['description'] ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted small">
                                <i class="fas fa-check-circle text-success mb-2 d-block fa-2x" style="opacity: 0.6;"></i>
                                <span class="font-weight-bold text-dark d-block">Tidak ada aktivitas baru</span>
                                <span class="text-muted" style="font-size: 0.75rem;">Semua riwayat sistem up-to-date.</span>
                            </div>
                        @endforelse
                    </div>

                    <!-- Footer Link Menuju Riwayat Aktivitas -->
                    <div class="p-2 text-center border-top bg-light">
                        <a href="{{ route('admin.activity-logs') }}" class="small font-weight-bold text-primary d-inline-block py-1 text-decoration-none">
                            <i class="fas fa-history mr-1"></i> Buka Seluruh Riwayat Aktivitas &raquo;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown Ultra-Modern & Elegan -->
            <div class="dropdown">
                <div class="bws-user-trigger" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdownUserMenu" title="Menu Akun: {{ Auth::user()->name ?? 'Petugas' }}">
                    <div class="bws-user-avatar-frame">
                        @if(Auth::check() && Auth::user()->avatar_url)
                            <img src="{{ Auth::user()->avatar_url }}" alt="Foto {{ Auth::user()->name }}">
                        @else
                            <span>{{ Auth::user()->initials ?? 'AB' }}</span>
                        @endif
                    </div>
                    <div class="bws-user-info">
                        <span class="bws-user-name">
                            {{ Auth::user()->name ?? 'Petugas BWSK' }}
                        </span>
                        <span class="bws-user-role">
                            {{ Auth::user()->role_label ?? 'Super Admin' }}
                        </span>
                    </div>
                    <i class="fas fa-chevron-down bws-user-chevron"></i>
                </div>

                <!-- Dropdown Menu Profil Modern -->
                <div class="dropdown-menu dropdown-menu-right profile-popup-menu shadow-lg border-0 animated--grow-in mt-2 p-0" aria-labelledby="dropdownUserMenu" style="border-radius: 14px; min-width: 235px; overflow: hidden; box-shadow: 0 10px 30px rgba(15,23,42,0.18) !important;">
                    <!-- Header Profil Card Mini -->
                    <div class="px-3 py-3 border-bottom" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                        <div class="d-flex align-items-center" style="gap: 12px;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white font-weight-bold shadow-xs overflow-hidden flex-shrink-0" style="width: 42px; height: 42px; background: linear-gradient(135deg, #1d4ed8, #1e40af); font-size: 0.95rem; border: 2.5px solid #facc15; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                @if(Auth::check() && Auth::user()->avatar_url)
                                    <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span>{{ Auth::user()->initials ?? 'AB' }}</span>
                                @endif
                            </div>
                            <div class="d-flex flex-column justify-content-center" style="min-width: 0; flex: 1;">
                                <div class="font-weight-bold text-truncate" style="font-size: 0.9rem; line-height: 1.25; color: #0f172a;">
                                    {{ Auth::user()->name ?? 'Petugas' }}
                                </div>
                                <div class="text-truncate mb-1.5" style="font-size: 0.76rem; line-height: 1.2; color: #64748b;">
                                    {{ Auth::user()->email ?? '' }}
                                </div>
                                <span class="badge border font-weight-bold px-2 py-0.5" style="font-size: 0.68rem; border-radius: 6px; background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; width: fit-content;">
                                    {{ Auth::user()->role_label ?? 'Admin' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Item Menu -->
                    <div class="py-1">
                        <a class="profile-popup-item" href="{{ route('profile') }}">
                            <i class="fas fa-user-cog text-primary"></i>
                            <span>Pengaturan Akun</span>
                        </a>
                    </div>
                    <div class="dropdown-divider my-0"></div>
                    <form action="{{ route('logout') }}" method="POST" class="m-0 p-1">
                        @csrf
                        <button type="submit" class="profile-popup-item logout border-0 bg-transparent w-100 text-left" style="border-radius: 8px;">
                            <i class="fas fa-power-off text-danger"></i>
                            <span>Keluar Sistem</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- ═══ 2. MENU NAVIGASI ADMIN SEKUNDER (SESUAI GAMBAR REFERENSI FEWS BWS) ═══ -->
    @if(!request()->routeIs('profile'))
    <nav class="bws-subnavbar">
        <!-- 1. Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="bws-nav-link {{ request()->routeIs('admin.dashboard', 'dashboard', 'admin.index') ? 'active' : '' }}">
            <i class="fas fa-th-large text-primary"></i>
            <span>Dashboard</span>
        </a>

        <!-- 2. Master Data (Direct Clean Navigation Link) -->
        <a href="{{ route('admin.kelola-gis') }}" class="bws-nav-link {{ request()->routeIs('admin.kelola-gis', 'admin.data-gis') ? 'active' : '' }}">
            <i class="fas fa-layer-group text-warning"></i>
            <span>Master Data</span>
        </a>

        <!-- 3. Manajemen User (Hanya Super Admin) -->
        @if(Auth::check() && Auth::user()->role === 'superadmin')
        <a href="{{ route('users.index') }}" class="bws-nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fas fa-user-friends text-info"></i>
            <span>Manajemen User</span>
        </a>
        @endif

        <!-- 4. Log Aktivitas (Audit Trail Operasional Data) -->
        <a href="{{ route('admin.activity-logs') }}" class="bws-nav-link {{ request()->routeIs('admin.activity-logs*') ? 'active' : '' }}">
            <i class="fas fa-history text-secondary"></i>
            <span>Log Aktivitas</span>
        </a>
    </nav>
    @endif

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

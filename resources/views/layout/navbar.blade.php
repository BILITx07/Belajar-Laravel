<!-- Topbar Resmi BWS Kalimantan I (Real Aesthetic Frosted Glass / Efek Kaca Nyata) -->
<nav class="navbar navbar-expand sticky-top px-3 px-md-4 py-1.5 bws-glass-navbar" style="height: 58px; z-index: 1030;">

    <style>
        /* Efek Kaca Nyata (Real Frosted Ice Glassmorphism) */
        .bws-glass-navbar {
            /* Gradasi kaca dengan bias pendaran es dan pantulan cahaya */
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.78) 0%, rgba(234, 245, 255, 0.65) 100%) !important;
            backdrop-filter: blur(24px) saturate(190%) !important;
            -webkit-backdrop-filter: blur(24px) saturate(190%) !important;
            border-bottom: 1.5px solid rgba(255, 255, 255, 0.85) !important;
            /* Efek kilau kaca (specular highlight & ambient shadow) */
            box-shadow: 0 10px 30px -5px rgba(15, 59, 117, 0.08), 
                        inset 0 1px 2px rgba(255, 255, 255, 0.95),
                        0 1px 3px rgba(0, 0, 0, 0.03) !important;
            transition: all 0.3s ease;
        }

        .bws-nav-link-custom {
            color: #334155 !important;
            font-weight: 600;
            padding: 6px 12px !important;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .bws-nav-link-custom:hover {
            color: #1d4ed8 !important;
            background: rgba(255, 255, 255, 0.6);
            transform: translateY(-1px);
        }

        /* Tombol Masuk Glass Berkilau */
        .btn-nav-masuk-glass {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(239, 246, 255, 0.9) 100%) !important;
            color: #1e3a8a !important;
            border-radius: 12px;
            font-size: 0.86rem;
            font-weight: 700;
            border: 1.5px solid rgba(191, 219, 254, 0.8) !important;
            box-shadow: 0 4px 14px rgba(29, 78, 216, 0.08), inset 0 1px 1px #ffffff !important;
            transition: all 0.25s ease;
        }
        .btn-nav-masuk-glass:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%) !important;
            color: #ffffff !important;
            border-color: #1d4ed8 !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(29, 78, 216, 0.3) !important;
        }
        .btn-nav-masuk-glass:hover i {
            color: #ffffff !important;
        }

        .user-pill-glass {
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.85) !important;
            border: 1.5px solid rgba(255, 255, 255, 0.9) !important;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
            color: #1e293b !important;
            transition: all 0.2s ease;
        }
        .user-pill-glass:hover {
            background: #ffffff !important;
            border-color: #cbd5e1 !important;
        }
    </style>

    <!-- Bagian Kiri: Logo PU & Identitas Instansi (Jernih & Elegan) -->
    <div class="d-flex align-items-center">
        <!-- Logo PU Kotak Squircle Tajam & Berbingkai Emas -->
        <a href="{{ route('peta') }}" class="d-flex align-items-center text-decoration-none">
            <div class="mr-2.5 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; min-width: 36px; background: #ffffff; border-radius: 9px; border: 1.5px solid rgba(250, 214, 5, 0.95); padding: 3px; box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);">
                <img src="{{ asset('img/logo-pu.svg') }}" alt="Logo Kementerian PU" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div class="d-flex flex-column">
                <span class="font-weight-bold" style="font-size: 0.92rem; letter-spacing: -0.2px; line-height: 1.15; color: #0f172a;">
                    WEBGIS BWS KALIMANTAN I
                </span>
                <span class="d-none d-md-block text-muted font-weight-medium" style="font-size: 0.68rem; line-height: 1.2;">
                    Direktorat Jenderal Sumber Daya Air &bull; Kementerian Pekerjaan Umum
                </span>
            </div>
        </a>
    </div>

    <!-- Bagian Kanan: Menu Link Ramping & Tombol Aksi -->
    <ul class="navbar-nav ml-auto align-items-center" style="gap: 10px;">

        <!-- Menu Link Petunjuk Penggunaan -->
        <li class="nav-item d-none d-sm-block">
            <a href="javascript:void(0)" class="nav-link font-weight-bold px-2 bws-nav-link-custom" data-toggle="modal" data-target="#modalPanduanAplikasi" style="font-size: 12px;" title="Petunjuk Penggunaan WebGIS">
                <i class="fas fa-book-reader mr-1" style="color: #2563eb;"></i> Petunjuk Penggunaan
            </a>
        </li>

        <!-- Tombol Layar Penuh -->
        <li class="nav-item">
            <a class="nav-link p-2 d-flex align-items-center justify-content-center bws-nav-link-custom" href="#" onclick="toggleFullScreen(event)" title="Layar Penuh" style="font-size: 13px;">
                <i class="fas fa-expand"></i>
            </a>
        </li>

        @auth
        <!-- TOMBOL KEMBALI KE PANEL ADMIN (SEAMLESS BRIDGE) -->
        <li class="nav-item mr-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-primary font-weight-bold d-inline-flex align-items-center px-3 py-1 shadow-sm text-white" style="border-radius: 18px; font-size: 0.78rem; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); border: none; height: 32px; gap: 6px;" title="Kembali ke Dashboard Administrator">
                <i class="fas fa-th-large" style="font-size: 11px;"></i>
                <span>Panel Admin</span>
            </a>
        </li>

        <!-- NOTIFIKASI AKTIVITAS KHUSUS ADMIN -->
        @php
            $recentLogs = \App\Helpers\ActivityLogger::getRecent(5);
            $countLogs = count($recentLogs);
        @endphp
        <li class="nav-item dropdown no-arrow position-relative">
            <a class="nav-link dropdown-toggle p-2 d-flex align-items-center justify-content-center position-relative bws-nav-link-custom" 
               href="#" 
               id="notifDropdownBtn" 
               role="button" 
               data-toggle="dropdown" 
               aria-haspopup="true" 
               aria-expanded="false" 
               title="Pusat Notifikasi Admin">
                <i class="fas fa-bell fa-lg"></i>
                @if($countLogs > 0)
                <span class="badge badge-danger position-absolute" style="top: 2px; right: 2px; font-size: 0.65rem; border-radius: 12px; padding: 2px 6px; background: #ef4444; font-weight: 800; border: 2px solid #ffffff;">
                    {{ $countLogs }}
                </span>
                @endif
            </a>
            
            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 animated--grow-in mt-2 p-0" 
                 aria-labelledby="notifDropdownBtn" 
                 style="border-radius: 12px; min-width: 320px; max-width: 360px; overflow: hidden;">
                
                <div class="d-flex align-items-center justify-content-between px-3 py-2 text-white" style="background: #1d4ed8;">
                    <div class="font-weight-bold d-flex align-items-center" style="font-size: 13px;">
                        <i class="fas fa-bell mr-2"></i> Log Aktivitas Admin
                    </div>
                    <span class="badge badge-light text-primary font-weight-bold px-2 py-0" style="font-size: 10.5px; border-radius: 10px;">
                        {{ $countLogs }} Riwayat
                    </span>
                </div>

                <div class="list-group list-group-flush" style="font-size: 12px; max-height: 320px; overflow-y: auto;">
                    @forelse($recentLogs as $log)
                        @php
                            $iconColor = match($log['action'] ?? 'update') {
                                'upload' => 'bg-success',
                                'delete' => 'bg-danger',
                                'rename' => 'bg-warning text-dark',
                                default  => 'bg-primary'
                            };
                            $iconClass = match($log['action'] ?? 'update') {
                                'upload' => 'fa-cloud-upload-alt',
                                'delete' => 'fa-trash-alt',
                                'rename' => 'fa-pen',
                                default  => 'fa-database'
                            };
                        @endphp
                        <div class="list-group-item d-flex align-items-start py-2 px-3">
                            <div class="rounded-circle {{ $iconColor }} text-white d-flex align-items-center justify-content-center mr-2 mt-1 flex-shrink-0" style="width: 28px; height: 28px; font-size: 11px;">
                                <i class="fas {{ $iconClass }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="font-weight-bold text-dark" style="font-size: 12px;">{{ $log['user'] ?? 'Petugas' }}</div>
                                <div class="text-secondary small mb-1" style="font-size: 11.5px; line-height: 1.35;">
                                    {{ $log['description'] ?? '-' }}
                                </div>
                                <div class="text-muted" style="font-size: 10px;">
                                    <i class="far fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($log['time'])->locale('id')->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-3 text-center text-muted small">
                            <i class="fas fa-check-circle text-success mr-1"></i> Belum ada aktivitas baru.
                        </div>
                    @endforelse
                </div>

                <div class="p-2 text-center border-top bg-light">
                    <a href="{{ route('admin.kelola-gis') }}" class="small font-weight-bold text-primary d-inline-block py-1">
                        <i class="fas fa-database mr-1"></i> Kelola Seluruh Data GIS &raquo;
                    </a>
                </div>
            </div>
        </li>

        <!-- Profil Admin -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle d-flex align-items-center py-1 px-3 user-pill-glass" href="#" id="userMenuBtn" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-2 shadow-sm" style="width: 28px; height: 28px;">
                    <i class="fas fa-user" style="font-size: 12px;"></i>
                </div>
                <span class="font-weight-bold text-dark d-none d-sm-inline" style="font-size: 0.85rem;">
                    {{ Auth::user()->name }}
                </span>
                <i class="fas fa-chevron-down ml-2 text-muted" style="font-size: 10px;"></i>
            </a>
            
            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 animated--grow-in mt-2" aria-labelledby="userMenuBtn" style="border-radius: 12px; min-width: 210px;">
                <div class="dropdown-header small text-muted font-weight-bold border-bottom pb-2 mb-1">
                    <div class="text-dark font-weight-bold" style="font-size: 13px;">{{ Auth::user()->name }}</div>
                    <span class="badge badge-primary px-2 py-0 mt-1" style="font-size: 10px;">{{ strtoupper(Auth::user()->role ?? 'Petugas') }}</span>
                </div>
                <!-- 0. Dashboard Admin -->
                <a class="dropdown-item py-2 text-primary font-weight-bold d-flex align-items-center" href="{{ route('admin.dashboard') }}" style="font-size: 13px;">
                    <i class="fas fa-th-large fa-sm fa-fw mr-2 text-primary"></i> Dashboard Admin
                </a>
                <div class="dropdown-divider my-1"></div>
                <!-- 1. Pengaturan Akun -->
                <a class="dropdown-item py-2 text-dark font-weight-bold d-flex align-items-center" href="{{ route('profile') }}" style="font-size: 13px;">
                    <i class="fas fa-user-cog fa-sm fa-fw mr-2 text-info"></i> Pengaturan Akun
                </a>
                <div class="dropdown-divider my-1"></div>
                <!-- 2. Keluar (Logout) -->
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="dropdown-item py-2 text-danger font-weight-bold border-0 bg-transparent d-flex align-items-center" style="font-size: 13px;">
                        <i class="fas fa-power-off fa-sm fa-fw mr-2"></i> Keluar
                    </button>
                </form>
            </div>
        </li>
        @else
        <!-- MODE PUBLIK: TOMBOL MASUK GLASS RAMPING & AESTHETIC -->
        <li class="nav-item">
            <a href="{{ route('login') }}" class="btn btn-sm btn-nav-masuk-glass d-flex align-items-center px-3 py-2">
                <i class="fas fa-sign-in-alt mr-2 text-primary"></i> Masuk
            </a>
        </li>
        @endauth
    </ul>

</nav>

<script>
    function toggleFullScreen(e) {
        if (e) e.preventDefault();
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(() => {});
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }
</script>

<!-- Topbar Resmi Kementerian PU & BWS Kalimantan I -->
<nav class="navbar navbar-expand navbar-dark static-top shadow-sm px-3" style="background-color: #1b5fb5; height: 60px; z-index: 1020;">

    <!-- Logo PU & Branding Instansi -->
    <div class="d-flex align-items-center">
        <!-- Lambang Resmi PU (Kuning Emas) -->
        <div class="mr-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #ffc107; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
            <svg viewBox="0 0 100 100" width="30" height="30">
                <!-- Roda Gigi Luar Kuning PU -->
                <circle cx="50" cy="50" r="46" fill="#ffc107" />
                <path d="M 50,8 A 42,42 0 1,0 92,50 A 42,42 0 0,0 50,8 Z" fill="none" stroke="#1b5fb5" stroke-width="5" />
                <!-- Pola Gear & Propeller Khas PU -->
                <path d="M50 15 L55 35 L75 35 L60 48 L68 68 L50 55 L32 68 L40 48 L25 35 L45 35 Z" fill="#1b5fb5" />
                <circle cx="50" cy="50" r="10" fill="#ffc107" />
                <circle cx="50" cy="50" r="5" fill="#1b5fb5" />
            </svg>
        </div>
        <div>
            <div class="text-white font-weight-bold" style="font-size: 0.95rem; line-height: 1.2; letter-spacing: 0.3px;">
                BALAI WILAYAH SUNGAI KALIMANTAN I
            </div>
            <div style="font-size: 0.72rem; color: #dbeafe; line-height: 1.1;">
                Direktorat Jenderal Sumber Daya Air &bull; Kementerian Pekerjaan Umum
            </div>
        </div>
    </div>

    <!-- Menu Tengah & Kanan -->
    <ul class="navbar-nav ml-auto align-items-center">

        <!-- Kalender & Jam Realtime WIB -->
        <li class="nav-item d-none d-lg-block text-white mr-4" style="font-size: 0.82rem;">
            <i class="far fa-calendar-alt text-warning mr-1"></i>
            <span id="topLiveClock">{{ date('d F Y') }} 09:07:57 WIB</span>
        </li>

        <!-- Dropdown Menu Monitoring -->
        <li class="nav-item dropdown no-arrow mr-2">
            <a class="nav-link dropdown-toggle text-white font-weight-bold px-3 py-1 rounded" href="#" id="dropdownMonitoring" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background: rgba(255,255,255,0.15); font-size: 0.83rem;">
                <i class="fas fa-desktop text-warning mr-1"></i> Dashboard Monitoring <i class="fas fa-caret-down ml-1"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 animated--grow-in mt-2" aria-labelledby="dropdownMonitoring" style="border-radius: 8px;">
                <h6 class="dropdown-header font-weight-bold text-primary">Sistem Monitoring Terpadu:</h6>
                <a class="dropdown-item py-2 {{ request()->is('/') || request()->is('home') || request()->is('dashboard') ? 'active font-weight-bold' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-satellite-dish fa-sm fa-fw mr-2 text-primary"></i> Realtime Telemetri (Higertech)
                </a>
                <a class="dropdown-item py-2 {{ request()->is('hidrologi-data*') ? 'active font-weight-bold' : '' }}" href="{{ route('hidrologi.data') }}">
                    <i class="fas fa-table fa-sm fa-fw mr-2 text-success"></i> Dashboard Data Pos Duga Air (FEWS)
                </a>
                <a class="dropdown-item py-2 {{ request()->is('peta*') ? 'active font-weight-bold' : '' }}" href="{{ route('peta') }}">
                    <i class="fas fa-map-marked-alt fa-sm fa-fw mr-2 text-warning"></i> WebGIS Infrastruktur SDA
                </a>
            </div>
        </li>

        <!-- Tombol Fullscreen -->
        <li class="nav-item mr-2 d-none d-sm-block">
            <a class="nav-link text-white" href="#" onclick="toggleFullScreen(event)" title="Layar Penuh" style="opacity: 0.9;">
                <i class="fas fa-expand fa-sm"></i>
            </a>
        </li>

        <!-- Lonceng Notifikasi Peringatan Dini -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle text-white" href="#" id="notifDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Peringatan Dini">
                <i class="fas fa-bell fa-sm"></i>
                <span class="badge badge-danger badge-counter" style="position: absolute; transform: scale(0.75); top: 6px; right: 2px;">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow border-0 animated--grow-in" aria-labelledby="notifDropdown" style="width: 290px;">
                <h6 class="dropdown-header bg-danger text-white border-0 font-weight-bold">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Peringatan Dini (FEWS)
                </h6>
                <a class="dropdown-item small py-2" href="{{ route('dashboard') }}">
                    <strong>AWLR Kembayan</strong> <span class="badge badge-danger float-right">AWAS</span>
                    <div class="text-muted">TMA: 28.52 m (Batas 28.00 m)</div>
                </a>
                <a class="dropdown-item small py-2" href="{{ route('dashboard') }}">
                    <strong>ARR Ambawang</strong> <span class="badge badge-warning float-right">WASPADA</span>
                    <div class="text-muted">Curah Hujan: 38.5 mm/jam</div>
                </a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block" style="border-right: 1px solid rgba(255,255,255,0.2);"></div>

        <!-- Profil Pengguna / Petugas -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle text-white" href="#" id="userMenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="rounded-circle text-white d-flex align-items-center justify-content-center mr-2 shadow-sm" style="width: 32px; height: 32px; background: rgba(255,255,255,0.25); font-weight: bold; font-size: 13px;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'Admin', 0, 1)) }}
                </div>
                <span class="d-none d-md-inline small font-weight-bold">{{ Auth::user()->name ?? 'Admin' }} <i class="fas fa-caret-down ml-1"></i></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 animated--grow-in mt-2" aria-labelledby="userMenu" style="border-radius: 8px; width: 190px;">
                @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'superadmin']))
                    <a class="dropdown-item py-2 font-weight-bold text-dark" href="{{ route('users.index') }}">
                        <i class="fas fa-cog fa-sm fa-fw mr-2 text-primary"></i> Admin Panel
                    </a>
                @endif
                <a class="dropdown-item py-2 text-dark" href="{{ route('profile') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-muted"></i> Profile
                </a>
                <a class="dropdown-item py-2 text-dark" href="{{ route('profile') }}">
                    <i class="fas fa-sliders-h fa-sm fa-fw mr-2 text-muted"></i> Settings
                </a>
                <div class="dropdown-divider my-1"></div>
                <a class="dropdown-item py-2 text-danger font-weight-bold" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-power-off fa-sm fa-fw mr-2"></i> Logout
                </a>
            </div>
        </li>

    </ul>

</nav>

<script>
    function toggleFullScreen(e) {
        e.preventDefault();
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(() => {});
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }
</script>
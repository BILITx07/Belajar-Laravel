<!-- Topbar Resmi Kementerian PU & BWS Kalimantan I - Fokus WebGIS -->
<nav class="navbar navbar-expand navbar-dark static-top shadow-sm px-3" style="background: linear-gradient(90deg, #0d2c54 0%, #174d8c 50%, #1b5fb5 100%); height: 62px; z-index: 1020;">

    <!-- Logo PU & Branding Instansi -->
    <a href="{{ route('peta') }}" class="d-flex align-items-center text-decoration-none">
        <!-- Lambang Resmi PU (Kuning Emas) -->
        <div class="mr-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #ffc107; border-radius: 7px; box-shadow: 0 2px 5px rgba(0,0,0,0.25);">
            <svg viewBox="0 0 100 100" width="32" height="32">
                <circle cx="50" cy="50" r="46" fill="#ffc107" />
                <path d="M 50,8 A 42,42 0 1,0 92,50 A 42,42 0 0,0 50,8 Z" fill="none" stroke="#1b5fb5" stroke-width="5" />
                <path d="M50 15 L55 35 L75 35 L60 48 L68 68 L50 55 L32 68 L40 48 L25 35 L45 35 Z" fill="#1b5fb5" />
                <circle cx="50" cy="50" r="10" fill="#ffc107" />
                <circle cx="50" cy="50" r="5" fill="#1b5fb5" />
            </svg>
        </div>
        <div>
            <div class="text-white font-weight-bold" style="font-size: 0.98rem; line-height: 1.2; letter-spacing: 0.4px;">
                WebGIS DAERAH IRIGASI &amp; WS KAPUAS
            </div>
            <div style="font-size: 0.73rem; color: #dbeafe; line-height: 1.1;">
                Balai Wilayah Sungai Kalimantan I Pontianak &bull; Ditjen SDA Kementerian PU
            </div>
        </div>
    </a>

    <!-- Menu Kanan -->
    <ul class="navbar-nav ml-auto align-items-center">

        <!-- Badge Status Sistem GIS -->
        <li class="nav-item d-none d-lg-block mr-3">
            <span class="badge badge-success px-2 py-1 text-white shadow-sm" style="font-size: 0.75rem; letter-spacing: 0.3px;">
                <i class="fas fa-satellite text-warning mr-1"></i> GIS Online
            </span>
        </li>

        <!-- Tombol Modal Info Laporan Magang -->
        <li class="nav-item mr-2">
            <button type="button" class="btn btn-sm btn-link text-white text-decoration-none px-2" data-toggle="modal" data-target="#modalInfoMagang" title="Informasi Laporan & Dokumentasi">
                <i class="fas fa-info-circle fa-lg"></i>
            </button>
        </li>

        <!-- Tombol Layar Penuh -->
        <li class="nav-item mr-2 d-none d-sm-block">
            <a class="nav-link text-white px-2" href="#" onclick="toggleFullScreen(event)" title="Layar Penuh">
                <i class="fas fa-expand fa-sm"></i>
            </a>
        </li>

        <div class="topbar-divider d-none d-sm-block" style="border-right: 1px solid rgba(255,255,255,0.25);"></div>

        @auth
            <!-- TAMPILAN JIKA ADMIN SUDAH LOGIN -->
            <li class="nav-item mr-2">
                <a href="{{ route('admin.kelola-gis') }}" class="btn btn-sm btn-outline-light font-weight-bold shadow-sm px-2 px-sm-3" style="border-radius:20px;">
                    <i class="fas fa-database text-warning mr-1"></i> <span class="d-none d-md-inline">Manajemen Data GIS</span>
                </a>
            </li>

            <!-- Profil User Login -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle text-white pl-2" href="#" id="userMenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center mr-1 mr-md-2 shadow-sm" style="width: 32px; height: 32px; background: rgba(255,255,255,0.25); font-weight: bold; font-size: 13px;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'Admin', 0, 1)) }}
                    </div>
                    <span class="d-none d-md-inline small font-weight-bold">{{ Auth::user()->name ?? 'Admin' }} <i class="fas fa-caret-down ml-1"></i></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 animated--grow-in mt-2" aria-labelledby="userMenu" style="border-radius: 8px; width: 200px;">
                    <div class="dropdown-header small text-muted font-weight-bold">Akun Terautentikasi</div>
                    <a class="dropdown-item py-2 text-dark font-weight-bold" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt fa-sm fa-fw mr-2 text-primary"></i> Dashboard Admin
                    </a>
                    <a class="dropdown-item py-2 text-dark font-weight-bold" href="{{ route('admin.kelola-gis') }}">
                        <i class="fas fa-database fa-sm fa-fw mr-2 text-primary"></i> Manajemen Data GIS
                    </a>
                    <div class="dropdown-divider my-1"></div>
                    <a class="dropdown-item py-2 text-dark" href="{{ route('profile') }}">
                        <i class="fas fa-user-cog fa-sm fa-fw mr-2 text-info"></i> Pengaturan Akun
                    </a>
                    @if(in_array(Auth::user()->role, ['admin', 'superadmin']))
                        <a class="dropdown-item py-2 text-dark" href="{{ route('users.index') }}">
                            <i class="fas fa-users-cog fa-sm fa-fw mr-2 text-info"></i> Kelola Petugas
                        </a>
                    @endif
                    <div class="dropdown-divider my-1"></div>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger font-weight-bold border-0 bg-transparent">
                            <i class="fas fa-power-off fa-sm fa-fw mr-2"></i> Keluar
                        </button>
                    </form>
                </div>
            </li>
        @endauth

        @guest
            <!-- TAMPILAN USER / PUBLIK: HANYA TOMBOL LOGIN PETUGAS -->
            <li class="nav-item">
                <a href="{{ route('login') }}" class="btn btn-sm btn-warning text-dark font-weight-bold px-3 shadow-sm" style="border-radius:20px;letter-spacing:0.3px;">
                    <i class="fas fa-sign-in-alt mr-1"></i> Login Petugas
                </a>
            </li>
        @endguest

    </ul>

</nav>

<!-- Modal Info Laporan Magang -->
<div class="modal fade" id="modalInfoMagang" tabindex="-1" role="dialog" aria-labelledby="modalInfoMagangLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0d2c54 0%, #1e56a0 100%);">
                <div class="d-flex align-items-center">
                    <div class="mr-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: #ffc107; border-radius: 6px;">
                        <i class="fas fa-map-marked-alt text-primary"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold text-white mb-0" id="modalInfoMagangLabel" style="font-size: 1.1rem;">
                            WebGIS Daerah Irigasi (DIR)
                        </h5>
                        <small class="text-white-50">Balai Wilayah Sungai Kalimantan I Pontianak</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <p class="small text-muted mb-3" style="line-height: 1.6;">
                    Aplikasi WebGIS ini dibangun sebagai platform visualisasi spasial interaktif untuk memetakan dan mengelola data geospasial pada <strong>Balai Wilayah Sungai Kalimantan I Pontianak</strong>.
                </p>
                <div class="card bg-light border-0 mb-3" style="border-radius: 8px;">
                    <div class="card-body p-3 small">
                        <div class="font-weight-bold text-primary mb-2"><i class="fas fa-layer-group mr-1"></i> Ruang Lingkup Data Spasial:</div>
                        <ul class="mb-0 pl-3 text-secondary" style="line-height: 1.7;">
                            <li><strong>Daerah Irigasi (DIR):</strong> Lahan Baku, Lahan Fungsional, dan Lahan Potensial.</li>
                            <li><strong>Jaringan Saluran:</strong> Saluran Primer, Sekunder, dan Tersier.</li>
                            <li><strong>Bangunan Air:</strong> Bendung, Pintu Air, Bangunan Sadap/Bagi.</li>
                            <li><strong>Wilayah Sungai Kapuas:</strong> Batas WS, Batas DAS, dan Jaringan Sungai.</li>
                        </ul>
                    </div>
                </div>
                <div class="text-muted small">
                    <i class="fas fa-check-circle text-success mr-1"></i> Data terintegrasi langsung dari file shapefile/GeoJSON ArcMap tanpa konversi rumit.
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm font-weight-bold px-3" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

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
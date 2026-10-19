<!-- Topbar Resmi Kementerian PU & BWS Kalimantan I - Sesuai Desain Referensi -->
<nav class="navbar navbar-expand navbar-dark static-top px-3 px-md-4" style="background: #1d64d8; height: 66px; z-index: 1030; box-shadow: 0 2px 10px rgba(29, 100, 216, 0.25);">

    <!-- Bagian Kiri: Tombol Back, Logo PU, dan Judul Instansi -->
    <div class="d-flex align-items-center">
        <!-- Tombol Back Lingkaran Putih -->
        <a href="{{ route('home') }}" class="btn rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center mr-3" style="width: 36px; height: 36px; min-width: 36px; border: none; text-decoration: none;" title="Kembali ke Beranda">
            <i class="fas fa-chevron-left" style="font-size: 13px; color: #1e293b; font-weight: 700;"></i>
        </a>

        <!-- Logo PU Kuning Kotak Rounded -->
        <a href="{{ route('peta') }}" class="d-flex align-items-center text-decoration-none">
            <div class="mr-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px; background: #ffc107; border-radius: 9px; box-shadow: 0 2px 6px rgba(0,0,0,0.18);">
                <svg viewBox="0 0 100 100" width="34" height="34">
                    <circle cx="50" cy="50" r="46" fill="#ffc107" />
                    <path d="M 50,8 A 42,42 0 1,0 92,50 A 42,42 0 0,0 50,8 Z" fill="none" stroke="#1b5fb5" stroke-width="5" />
                    <path d="M50 15 L55 35 L75 35 L60 48 L68 68 L50 55 L32 68 L40 48 L25 35 L45 35 Z" fill="#1b5fb5" />
                    <circle cx="50" cy="50" r="10" fill="#ffc107" />
                    <circle cx="50" cy="50" r="5" fill="#1b5fb5" />
                </svg>
            </div>
            <div class="d-flex flex-column text-white">
                <span class="font-weight-bold" style="font-size: 0.96rem; letter-spacing: 0.4px; line-height: 1.2;">
                    WEBGIS BWS KALIMANTAN I
                </span>
                <span style="font-size: 0.72rem; color: rgba(255, 255, 255, 0.9); line-height: 1.15; font-weight: 500;">
                    Direktorat Jenderal Sumber Daya Air
                </span>
                <span style="font-size: 0.72rem; color: rgba(255, 255, 255, 0.9); line-height: 1.15; font-weight: 500;">
                    Kementerian Pekerjaan Umum
                </span>
            </div>
        </a>
    </div>

    <!-- Bagian Kanan: Fullscreen, Notifikasi Badge 54, & Profil Admin Dinar -->
    <ul class="navbar-nav ml-auto align-items-center">

        <!-- Tombol Layar Penuh -->
        <li class="nav-item mr-3">
            <a class="nav-link text-white p-2 d-flex align-items-center justify-content-center" href="#" onclick="toggleFullScreen(event)" title="Layar Penuh" style="opacity: 0.95;">
                <i class="fas fa-expand fa-lg"></i>
            </a>
        </li>

        <!-- Notifikasi Lonceng dengan Badge 54 -->
        <li class="nav-item mr-3 position-relative">
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white p-2 position-relative d-flex align-items-center justify-content-center" title="Kelola File Data Spasial">
                <i class="fas fa-bell fa-lg"></i>
                <span class="badge badge-danger position-absolute" style="top: 0px; right: 0px; font-size: 0.65rem; border-radius: 12px; padding: 2px 6px; background: #ef4444; font-weight: 800; border: 2px solid #1d64d8;">54</span>
            </a>
        </li>

        <!-- Profil User Pill: Admin Dinar -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle text-white d-flex align-items-center py-1 px-3" href="#" id="userMenuBtn" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background: rgba(255, 255, 255, 0.16); border-radius: 25px; transition: background 0.2s ease;">
                <div class="rounded-circle bg-white d-flex align-items-center justify-content-center mr-2 shadow-sm" style="width: 32px; height: 32px;">
                    <i class="fas fa-user" style="color: #1d64d8; font-size: 14px;"></i>
                </div>
                <span class="font-weight-bold" style="font-size: 0.88rem; color: #ffffff;">
                    {{ Auth::check() ? Auth::user()->name : 'Admin Dinar' }}
                </span>
                <i class="fas fa-chevron-down ml-2" style="font-size: 10px; opacity: 0.8;"></i>
            </a>
            
            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 animated--grow-in mt-2" aria-labelledby="userMenuBtn" style="border-radius: 10px; min-width: 220px;">
                <div class="dropdown-header small text-muted font-weight-bold">
                    {{ Auth::check() ? 'Petugas Terautentikasi' : 'Profil Petugas WebGIS' }}
                </div>
                
                @auth
                    <a class="dropdown-item py-2 text-dark font-weight-bold" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt fa-sm fa-fw mr-2 text-primary"></i> Dashboard Admin
                    </a>
                    <a class="dropdown-item py-2 text-dark font-weight-bold" href="{{ route('admin.kelola-gis') }}">
                        <i class="fas fa-database fa-sm fa-fw mr-2 text-primary"></i> Kelola File GIS (54)
                    </a>
                    <a class="dropdown-item py-2 text-dark" href="{{ route('profile') }}">
                        <i class="fas fa-user-cog fa-sm fa-fw mr-2 text-info"></i> Pengaturan Akun
                    </a>
                    <div class="dropdown-divider my-1"></div>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger font-weight-bold border-0 bg-transparent">
                            <i class="fas fa-power-off fa-sm fa-fw mr-2"></i> Keluar
                        </button>
                    </form>
                @else
                    <a class="dropdown-item py-2 text-dark font-weight-bold" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-folder-open fa-sm fa-fw mr-2 text-primary"></i> Kelola File GIS (54)
                    </a>
                    <a class="dropdown-item py-2 text-dark font-weight-bold" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt fa-sm fa-fw mr-2 text-success"></i> Masuk Akun Petugas
                    </a>
                @endauth
            </div>
        </li>

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
<!-- Topbar Resmi WebGIS BWS Kalimantan I (Persis Proporsi Otentik QROP Spasial) -->
<nav class="navbar navbar-expand sticky-top bws-qrop-navbar" style="min-height: 85px; z-index: 1030; background: #ffffff; border-bottom: 2.5px solid #10143a; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03); padding: 12px clamp(20px, 4vw, 72px);">

    <style>
        .bws-qrop-navbar {
            background: #ffffff !important;
            border-bottom: 2.5px solid #10143a !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03) !important;
            min-height: 85px !important;
            padding: 12px clamp(20px, 4vw, 72px) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            transition: all 0.2s ease;
        }

        .bws-navbar-inner-container {
            max-width: 1600px;
            width: 100%;
            margin: 0 auto;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* 1. Lambang Instansi di Kiri */
        .qrop-emblem-wrap {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease;
        }

        .qrop-emblem-wrap:hover {
            transform: scale(1.04);
        }

        .qrop-emblem-text {
            font-size: 8.5px;
            text-align: center;
            text-transform: uppercase;
            line-height: 1.25;
            margin-top: 3px;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .qrop-emblem-kem {
            font-weight: 800;
            letter-spacing: 0.6px;
            color: #10143a;
        }

        .qrop-emblem-balai {
            font-weight: 700;
            letter-spacing: 0.4px;
            color: #475569;
            font-size: 8px;
        }

        /* Garis Pembatas Vertikal Tipis Elegan */
        .qrop-nav-divider {
            height: 52px;
            width: 1.5px;
            background: #e2e8f0;
            margin: 0 20px;
        }

        /* 2. Logo Brand Aplikasi Spasial (Presisi & Proporsional Sesuai Standar QROP) */
        .qrop-brand-title {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }

        .qrop-brand-headline {
            display: flex;
            align-items: baseline;
            gap: 5px;
        }

        .qrop-word-main {
            font-size: 21px;
            font-weight: 800;
            letter-spacing: -0.4px;
            color: #10143a;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .qrop-word-accent {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #f59e0b;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .qrop-word-sub {
            font-size: 11.5px;
            font-weight: 600;
            letter-spacing: 0.2px;
            color: #64748b;
            line-height: 1.2;
            margin-top: 2px;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* 3. Link Teks Navigasi Kanan (Tentang & Hubungi kami) */
        .qrop-nav-text-link {
            font-size: 13.5px;
            font-weight: 600;
            color: #475569 !important;
            text-decoration: none !important;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .qrop-nav-text-link:hover {
            color: #10143a !important;
            background: #f1f5f9;
        }

        /* 4. Tombol Masuk Mewah Khas QROP (Kotak Berborder Halus) */
        .btn-qrop-masuk {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #ffffff;
            color: #0f172a !important;
            font-weight: 600;
            font-size: 13.5px;
            padding: 9px 18px;
            text-decoration: none !important;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .btn-qrop-masuk:hover {
            border-color: #10143a;
            background: #f8fafc;
            color: #10143a !important;
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(16, 20, 58, 0.08);
        }

        .btn-qrop-masuk i {
            color: #0f172a;
            font-size: 13.5px;
            transition: color 0.2s ease;
        }

        .btn-qrop-masuk:hover i {
            color: #10143a;
        }

        .user-pill-qrop {
            border-radius: 25px;
            background: #ffffff !important;
            border: 1.5px solid #e2e8f0 !important;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
            color: #1e293b !important;
            transition: all 0.2s ease;
            padding: 4px 12px 4px 4px !important;
        }
        .user-pill-qrop:hover {
            background: #f8fafc !important;
            border-color: #cbd5e1 !important;
        }
    </style>

    <div class="bws-navbar-inner-container">
    <!-- ═══ BAGIAN KIRI: LOGO INSTANSI & BRANDING SPASIAL PROPORSIONAL & MEWAH ═══ -->
    <div class="d-flex align-items-center">
        <!-- 1. Lambang Daerah / Instansi PU + Teks Nama Instansi di Bawah -->
        <a href="{{ route('peta') }}" class="d-flex flex-column align-items-center text-decoration-none mr-1" title="Kementerian Pekerjaan Umum - Balai Wilayah Sungai Kalimantan I Pontianak">
            <div class="qrop-emblem-wrap">
                <img src="{{ asset('img/logo-pu.svg') }}" alt="Logo Kementerian Pekerjaan Umum" style="width: 48px; height: 48px; object-fit: contain;">
            </div>
            <div class="qrop-emblem-text d-none d-sm-block">
                <div class="qrop-emblem-kem">KEMENTERIAN PEKERJAAN UMUM</div>
                <div class="qrop-emblem-balai">BALAI WILAYAH SUNGAI KALIMANTAN I</div>
            </div>
        </a>

        <!-- Garis Pembatas Vertikal Proporsional -->
        <div class="qrop-nav-divider d-none d-sm-block"></div>

        <!-- 2. Logo Brand Aplikasi Spasial: Logo Resmi SIPDIRA -->
        <a href="{{ route('peta') }}" class="d-flex align-items-center text-decoration-none">
            <!-- Logo Ikon Resmi SIPDIRA (Modular Spatial & Aliran Irigasi BWSK I) -->
            <div class="mr-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                <img src="{{ asset('img/logo-sipdira.svg') }}" alt="Logo SIPDIRA" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div class="d-flex flex-column justify-content-center">
                <div class="qrop-brand-title">
                    <div class="qrop-brand-headline">
                        <span class="qrop-word-main">SIPDIRA</span>
                        <span class="qrop-word-accent">SPASIAL</span>
                    </div>
                    <span class="qrop-word-sub">BWS Kalimantan I Pontianak</span>
                </div>
            </div>
        </a>
    </div>

    <!-- ═══ BAGIAN KANAN: MENU TEKS & TOMBOL AKSI ALA QROP ═══ -->
    <ul class="navbar-nav ml-auto align-items-center" style="gap: 24px;">

        <!-- Menu Link: Tentang SIPDIRA (Scroll ke Section Tentang) -->
        <li class="nav-item d-none d-lg-block">
            <a href="#tentang-sipdira" class="qrop-nav-text-link" title="Tentang Layanan SIPDIRA">
                Tentang SIPDIRA
            </a>
        </li>

        <!-- Menu Link: Petunjuk Penggunaan -->
        <li class="nav-item d-none d-lg-block">
            <a href="javascript:void(0)" class="qrop-nav-text-link" data-toggle="modal" data-target="#modalPanduanAplikasi" title="Petunjuk & Panduan Penggunaan">
                Petunjuk Penggunaan
            </a>
        </li>

        @auth
        <!-- TOMBOL KEMBALI KE PANEL ADMIN (JIKA ADMIN LOGGED IN) -->
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-primary font-weight-bold d-inline-flex align-items-center px-3 py-1.5 shadow-sm text-white" style="border-radius: 10px; font-size: 0.82rem; background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%); border: none; height: 38px; gap: 7px;" title="Kembali ke Dashboard Administrator">
                <i class="fas fa-th-large" style="font-size: 12px;"></i>
                <span>Panel Admin</span>
            </a>
        </li>

        <!-- NOTIFIKASI AKTIVITAS KHUSUS SUPER ADMIN -->
        @if(Auth::check() && (Auth::user()->isSuperAdmin() || Auth::user()->role === 'superadmin'))
        @php
            $recentLogs = \App\Helpers\ActivityLogger::getRecent(5);
            $countLogs = count($recentLogs);
        @endphp
        <li class="nav-item dropdown no-arrow position-relative">
            <a class="nav-link dropdown-toggle p-2 d-flex align-items-center justify-content-center position-relative qrop-icon-btn" 
               href="#" 
               id="notifDropdownBtn" 
               role="button" 
               data-toggle="dropdown" 
               aria-haspopup="true" 
               aria-expanded="false" 
               title="Pusat Notifikasi Admin">
                <i class="fas fa-bell"></i>
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
        @endif

        <!-- Profil Admin -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle d-flex align-items-center user-pill-qrop" href="#" id="userMenuBtn" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Menu Akun: {{ Auth::user()->name }}">
                <div class="rounded-circle text-white d-flex align-items-center justify-content-center mr-2 shadow-xs overflow-hidden flex-shrink-0" style="width: 32px; height: 32px; background: linear-gradient(135deg, #1d4ed8, #1e40af); border: 2px solid #facc15; font-size: 0.78rem; font-weight: 800;">
                    @if(Auth::check() && Auth::user()->avatar_url)
                        <img src="{{ Auth::user()->avatar_url }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span>{{ Auth::user()->initials ?? 'AB' }}</span>
                    @endif
                </div>
                <span class="font-weight-bold text-dark d-none d-sm-inline" style="font-size: 0.85rem;">
                    {{ Auth::user()->name }}
                </span>
                <i class="fas fa-chevron-down ml-2 text-muted" style="font-size: 10px;"></i>
            </a>
            
            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 animated--grow-in mt-2 p-0" aria-labelledby="userMenuBtn" style="border-radius: 14px; min-width: 235px; overflow: hidden; box-shadow: 0 10px 30px rgba(15,23,42,0.18) !important;">
                <div class="px-3 py-3 border-bottom" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                    <div class="d-flex align-items-center" style="gap: 12px;">
                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center shadow-xs overflow-hidden flex-shrink-0" style="width: 42px; height: 42px; background: linear-gradient(135deg, #1d4ed8, #1e40af); border: 2.5px solid #facc15; font-size: 0.95rem; font-weight: 800; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                            @if(Auth::check() && Auth::user()->avatar_url)
                                <img src="{{ Auth::user()->avatar_url }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span>{{ Auth::user()->initials ?? 'AB' }}</span>
                            @endif
                        </div>
                        <div class="d-flex flex-column justify-content-center" style="min-width: 0; flex: 1;">
                            <div class="font-weight-bold text-truncate" style="font-size: 0.9rem; line-height: 1.25; color: #0f172a;">
                                {{ Auth::user()->name }}
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
                <div class="py-1">
                    <!-- 1. Pengaturan Akun -->
                    <a class="dropdown-item py-2 text-dark font-weight-bold d-flex align-items-center" href="{{ route('profile') }}" style="font-size: 13px;">
                        <i class="fas fa-user-cog fa-sm fa-fw mr-2 text-primary"></i>
                        <span>Pengaturan Akun</span>
                    </a>
                </div>
                <div class="dropdown-divider my-0"></div>
                <!-- 2. Keluar (Logout) -->
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-1">
                    @csrf
                    <button type="submit" class="dropdown-item py-2 text-danger font-weight-bold border-0 bg-transparent d-flex align-items-center" style="font-size: 13px; border-radius: 8px;">
                        <i class="fas fa-power-off fa-sm fa-fw mr-2"></i>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </li>
        @else
        <!-- MODE PUBLIK: TOMBOL MASUK PERSIS QROP (KOTAK BERBORDER HALUS) -->
        <li class="nav-item">
            <a href="{{ route('login') }}" class="btn-qrop-masuk">
                <i class="fas fa-sign-in-alt text-dark" style="font-size: 13px;"></i>
                <span>Masuk</span>
            </a>
        </li>
        @endauth
    </ul>
    </div>

</nav>

<!-- MODAL HUBUNGI KAMI (INFORMASI KONTAK RESMI BWS KALIMANTAN I) -->
<div class="modal fade" id="modalHubungiKami" tabindex="-1" role="dialog" aria-labelledby="modalHubungiKamiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 480px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #15803d 0%, #166534 100%);">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-white text-success d-flex align-items-center justify-content-center mr-2.5" style="width: 32px; height: 32px; font-size: 14px;">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0" id="modalHubungiKamiLabel" style="font-size: 1rem;">Hubungi Kami</h5>
                        <small style="color: rgba(255,255,255,0.85); font-size: 0.75rem;">Balai Wilayah Sungai Kalimantan I Pontianak</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" style="background: #ffffff; font-size: 0.88rem;">
                <p class="text-secondary mb-3">
                    Untuk informasi lebih lanjut, permohonan data spasial, maupun laporan terkait jaringan irigasi, silakan hubungi saluran resmi kami:
                </p>

                <div class="list-group list-group-flush border rounded mb-3" style="font-size: 0.85rem;">
                    <div class="list-group-item d-flex align-items-start py-2.5 px-3">
                        <i class="fas fa-map-marked-alt text-success mr-3 mt-1 fa-lg"></i>
                        <div>
                            <strong>Alamat Kantor:</strong><br>
                            Jl. Achmad Sood No. 6, Kota Pontianak, Kalimantan Barat 78121
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-start py-2.5 px-3">
                        <i class="fas fa-phone-alt text-success mr-3 mt-1 fa-lg"></i>
                        <div>
                            <strong>Telepon / Layanan:</strong><br>
                            <a href="tel:+6281349497711" class="text-dark text-decoration-none font-weight-bold">+62 813-4949-7711</a>
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-start py-2.5 px-3">
                        <i class="fas fa-envelope text-success mr-3 mt-1 fa-lg"></i>
                        <div>
                            <strong>Email Resmi:</strong><br>
                            <a href="mailto:bwsk1pontianak@pu.go.id" class="text-primary font-weight-bold">bwsk1pontianak@pu.go.id</a>
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-start py-2.5 px-3">
                        <i class="fas fa-clock text-success mr-3 mt-1 fa-lg"></i>
                        <div>
                            <strong>Jam Operasional:</strong><br>
                            Senin &ndash; Jumat, Pukul 08.00 &ndash; 16.00 WIB
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2.5 px-4 justify-content-end">
                <button type="button" class="btn btn-secondary btn-sm font-weight-bold px-4" data-dismiss="modal" style="border-radius: 8px;">
                    Tutup
                </button>
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

@extends('layout.master')

@section('title', 'Kebijakan Privasi & Tata Kelola Data Spasial — SIPDIRA BWS Kalimantan I')

@push('styles')
<style>
    /* ═══════════════════════════════════════════════════════════════════
       HALAMAN KEBIJAKAN PRIVASI & TATA KELOLA DATA SPASIAL SIPDIRA
       Desain Presisi, Bersih & Resmi Khas Kementerian Pekerjaan Umum
       ═══════════════════════════════════════════════════════════════════ */
    .bws-policy-page {
        background-color: #f8fafc;
        min-height: calc(100vh - 72px);
        padding-bottom: 60px;
    }

    /* Breadcrumb Elegan */
    .bws-policy-breadcrumb {
        padding: 16px 0 8px 0;
        font-size: 13px;
        color: #64748b;
    }
    .bws-policy-breadcrumb a {
        color: #10143a;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.15s ease;
    }
    .bws-policy-breadcrumb a:hover {
        color: #2563eb;
    }
    .bws-policy-breadcrumb .separator {
        margin: 0 8px;
        color: #cbd5e1;
    }

    /* Hero Banner Utama (Gaya Kedinasan PU Navy & Emas) */
    .bws-policy-hero {
        background: linear-gradient(135deg, #10143a 0%, #1a1c54 50%, #0d1230 100%);
        border-radius: 20px;
        padding: 38px 42px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(16, 20, 58, 0.25);
        border-bottom: 4px solid #f59e0b;
        margin-bottom: 30px;
    }
    .bws-policy-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 240px;
        height: 240px;
        background: radial-gradient(circle, rgba(250, 214, 5, 0.12) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .bws-policy-hero-pretitle {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #fad605;
        background: rgba(250, 214, 5, 0.15);
        border: 1px solid rgba(250, 214, 5, 0.35);
        padding: 4px 12px;
        border-radius: 20px;
        margin-bottom: 14px;
    }
    .bws-policy-hero-title {
        font-size: 2.1rem;
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: -0.5px;
        margin-bottom: 10px;
        color: #ffffff;
    }
    .bws-policy-hero-desc {
        font-size: 14.5px;
        color: rgba(255, 255, 255, 0.86);
        max-width: 760px;
        line-height: 1.6;
        margin-bottom: 20px;
    }
    .bws-policy-badge-info {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        color: #ffffff;
        font-weight: 600;
    }

    /* Sidebar Sticky Daftar Isi (Persis QROP Spasial) */
    .bws-toc-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 22px 18px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        position: sticky;
        top: 90px;
    }
    .bws-toc-title {
        font-size: 13.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #0f172a;
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .bws-toc-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .bws-toc-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        font-size: 12.5px;
        font-weight: 600;
        color: #475569;
        border-radius: 9px;
        text-decoration: none;
        transition: all 0.18s ease;
    }
    .bws-toc-link:hover {
        background: #f1f5f9;
        color: #10143a;
        text-decoration: none;
        transform: translateX(3px);
    }
    .bws-toc-link.active {
        background: rgba(16, 20, 58, 0.08);
        color: #10143a;
        font-weight: 700;
        border-left: 3px solid #10143a;
        border-radius: 4px 9px 9px 4px;
    }
    .bws-toc-num {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        font-family: monospace;
    }
    .bws-toc-link.active .bws-toc-num {
        color: #10143a;
    }
    .bws-toc-note {
        margin-top: 18px;
        padding-top: 14px;
        border-top: 1px dashed #e2e8f0;
        font-size: 11.5px;
        color: #64748b;
        line-height: 1.5;
    }

    /* Kartu Konten Pasal Kebijakan */
    .bws-policy-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 28px 32px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        margin-bottom: 22px;
        scroll-margin-top: 96px;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .bws-policy-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 6px 18px rgba(16, 20, 58, 0.04);
    }
    .bws-policy-card-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 16px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }
    .bws-policy-num-pill {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(16, 20, 58, 0.08);
        border: 1.5px solid rgba(16, 20, 58, 0.15);
        color: #10143a;
        font-weight: 800;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .bws-policy-card-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.2px;
    }
    .bws-policy-card-body {
        font-size: 13.5px;
        line-height: 1.7;
        color: #334155;
    }
    .bws-policy-card-body p {
        margin-bottom: 12px;
    }
    .bws-policy-card-body p:last-child {
        margin-bottom: 0;
    }
    .bws-policy-card-body ul {
        padding-left: 20px;
        margin-bottom: 14px;
    }
    .bws-policy-card-body li {
        margin-bottom: 8px;
    }
    .bws-policy-card-body strong {
        color: #0f172a;
    }

    /* Kotak Sorotan Khusus (Callout) */
    .bws-policy-callout {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #10143a;
        border-radius: 10px;
        padding: 14px 18px;
        margin: 16px 0;
        font-size: 13px;
        color: #1e293b;
    }
    .bws-policy-callout.warning {
        background: #fffbeb;
        border-color: #fef3c7;
        border-left: 4px solid #f59e0b;
        color: #92400e;
    }
    .bws-policy-callout.success {
        background: #f0fdf4;
        border-color: #dcfce7;
        border-left: 4px solid #10b981;
        color: #166534;
    }

    /* Tombol Kontak & Aksi */
    .bws-contact-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px 22px;
        margin-top: 14px;
    }
    .bws-btn-official-contact {
        background: #10143a;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 13px;
        padding: 10px 22px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1.5px solid #fad605;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(16, 20, 58, 0.2);
    }
    .bws-btn-official-contact:hover {
        background: #1c2262;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(16, 20, 58, 0.3);
        text-decoration: none;
    }
</style>
@endpush

@section('konten')
<div class="bws-policy-page">
    <div class="container py-3">

        <!-- Breadcrumb Navigasi -->
        <nav class="bws-policy-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('peta') }}"><i class="fas fa-arrow-left mr-1"></i> Kembali ke WebGIS</a>
            <span class="separator">/</span>
            <span>Kebijakan Privasi &amp; Tata Kelola Data Spasial</span>
        </nav>

        <!-- Banner Hero Utama (Resmi Kementerian PU) -->
        <div class="bws-policy-hero">
            <div class="bws-policy-hero-pretitle">
                <i class="fas fa-shield-alt"></i> TATA KELOLA DATA SPASIAL &bull; BWS KALIMANTAN I
            </div>
            <h1 class="bws-policy-hero-title">
                Kebijakan Privasi &amp; Data Spasial
            </h1>
            <p class="bws-policy-hero-desc">
                Informasi data geospasial yang dikelola, landasan hukum keterbukaan data irigasi, serta perlindungan hak pengguna saat mengakses sistem informasi spasial SIPDIRA.
            </p>
            <div class="d-flex flex-wrap align-items-center" style="gap: 12px;">
                <div class="bws-policy-badge-info">
                    <i class="fas fa-calendar-check text-warning"></i> Pemutakhiran: 10 November 2026
                </div>
                <div class="bws-policy-badge-info">
                    <i class="fas fa-database text-info"></i> Katalog Master: 14 Daerah Irigasi Rawa (DIR)
                </div>
                <a href="{{ route('peta') }}" class="btn btn-sm btn-light font-weight-bold ml-auto d-none d-md-inline-flex align-items-center" style="border-radius: 8px;">
                    <i class="fas fa-map-marked-alt mr-1.5 text-primary"></i> Buka WebGIS
                </a>
            </div>
        </div>

        <!-- Wadah Konten Utama: 2 Kolom (Sticky Sidebar + Konten Pasal) -->
        <div class="row">

            <!-- ── KOLOM KIRI: DAFTAR ISI STICKY (PERSIS QROP) ── -->
            <aside class="col-lg-3 col-md-4 mb-4">
                <div class="bws-toc-card">
                    <div class="bws-toc-title">
                        <span>Daftar Isi</span>
                        <i class="fas fa-list-ul text-muted"></i>
                    </div>
                    <ul class="bws-toc-list" id="policyTocList">
                        <li>
                            <a href="#tentang-layanan" class="bws-toc-link active">
                                <span class="bws-toc-num">01.</span>
                                <span>Tentang Layanan</span>
                            </a>
                        </li>
                        <li>
                            <a href="#data-dikelola" class="bws-toc-link">
                                <span class="bws-toc-num">02.</span>
                                <span>Data yang Dikelola</span>
                            </a>
                        </li>
                        <li>
                            <a href="#penggunaan-data" class="bws-toc-link">
                                <span class="bws-toc-num">03.</span>
                                <span>Pemanfaatan Data</span>
                            </a>
                        </li>
                        <li>
                            <a href="#informasi-publik" class="bws-toc-link">
                                <span class="bws-toc-num">04.</span>
                                <span>Informasi Publik &amp; Akses</span>
                            </a>
                        </li>
                        <li>
                            <a href="#cookie-keamanan" class="bws-toc-link">
                                <span class="bws-toc-num">05.</span>
                                <span>Cookie &amp; Sesi Masuk</span>
                            </a>
                        </li>
                        <li>
                            <a href="#layanan-pihak-ketiga" class="bws-toc-link">
                                <span class="bws-toc-num">06.</span>
                                <span>Layanan Pihak Ketiga</span>
                            </a>
                        </li>
                        <li>
                            <a href="#penyimpanan-keamanan" class="bws-toc-link">
                                <span class="bws-toc-num">07.</span>
                                <span>Keamanan Data</span>
                            </a>
                        </li>
                        <li>
                            <a href="#hak-permohonan" class="bws-toc-link">
                                <span class="bws-toc-num">08.</span>
                                <span>Permohonan Data</span>
                            </a>
                        </li>
                        <li>
                            <a href="#perubahan-kebijakan" class="bws-toc-link">
                                <span class="bws-toc-num">09.</span>
                                <span>Perubahan Kebijakan</span>
                            </a>
                        </li>
                        <li>
                            <a href="#hubungi-pengelola" class="bws-toc-link">
                                <span class="bws-toc-num">10.</span>
                                <span>Hubungi Pengelola</span>
                            </a>
                        </li>
                    </ul>
                    <div class="bws-toc-note">
                        <i class="fas fa-info-circle text-primary mr-1"></i>
                        Dokumen ini mengikat seluruh publik, mitra instansi, dan operator teknis SISDA BWS Kalimantan I.
                    </div>
                </div>
            </aside>

            <!-- ── KOLOM KANAN: KONTEN LENGKAP 10 PASAL ── -->
            <main class="col-lg-9 col-md-8">

                <!-- 01. Tentang Layanan -->
                <article class="bws-policy-card" id="tentang-layanan">
                    <header class="bws-policy-card-header">
                        <div class="bws-policy-num-pill">01</div>
                        <h2 class="bws-policy-card-title">Tentang Layanan</h2>
                    </header>
                    <div class="bws-policy-card-body">
                        <p>
                            <strong>SIPDIRA Spasial</strong> (Sistem Informasi Peta Daerah Irigasi Berbasis Data Spasial) merupakan platform pemetaan geospasial resmi yang dikembangkan dan dikelola oleh <strong>Balai Wilayah Sungai Kalimantan I Pontianak</strong>, Direktorat Jenderal Sumber Daya Air, Kementerian Pekerjaan Umum.
                        </p>
                        <p>
                            Sistem ini dirancang untuk menyediakan akses informasi spasial yang transparan, presisi, dan mutakhir mengenai sebaran Daerah Irigasi Rawa (DIR) di bawah kewenangan Balai Wilayah Sungai Kalimantan I, guna menunjang perencanaan, monitoring operasi &amp; pemeliharaan (O&amp;P), serta mendukung ketahanan pangan strategis di Provinsi Kalimantan Barat.
                        </p>
                    </div>
                </article>

                <!-- 02. Data yang Dikelola -->
                <article class="bws-policy-card" id="data-dikelola">
                    <header class="bws-policy-card-header">
                        <div class="bws-policy-num-pill">02</div>
                        <h2 class="bws-policy-card-title">Data Spasial yang Dikelola</h2>
                    </header>
                    <div class="bws-policy-card-body">
                        <p>SIPDIRA Spasial mengelola data geospasial dan atribut teknis irigasi rawa berstandar kartografi nasional, meliputi:</p>
                        <ul>
                            <li>
                                <strong>Katalog Master Daerah Irigasi (DIR):</strong> Rekapitulasi 14 Daerah Irigasi Rawa utama kewenangan BWS Kalimantan I yang tersebar di wilayah kerja Kabupaten Sambas, Kubu Raya, Kayong Utara, Ketapang, dan sekitarnya.
                            </li>
                            <li>
                                <strong>Lahan Baku (Poligon Hijau):</strong> Total luasan <strong>30.370 Hektare</strong> lahan siap tanam dan siap olah dalam batas kewenangan definitif DIR.
                            </li>
                            <li>
                                <strong>Lahan Fungsional (Poligon Biru):</strong> Total luasan <strong>23.760 Hektare</strong> lahan aktif yang teraliri air dan beroperasi secara rutin dalam siklus tanam.
                            </li>
                            <li>
                                <strong>Lahan Potensial (Poligon Kuning):</strong> Area cadangan rencana perluasan jaringan irigasi dan intensifikasi pertanian masa depan.
                            </li>
                            <li>
                                <strong>Jaringan Saluran Pembawa (Garis Cyan):</strong> Trase digital saluran primer, saluran sekunder, dan saluran tersier/kolektor pengatur tata air rawa.
                            </li>
                            <li>
                                <strong>Bangunan Air &amp; Infrastruktur SDA (Titik Oranye):</strong> Titik koordinat pintu air (klep otomatis/manual), bendung pengatur, gorong-gorong, serta pos pemantauan hidrologi.
                            </li>
                            <li>
                                <strong>Data Pengguna Khusus (Operator &amp; Admin):</strong> Informasi nama, alamat email kedinasan, peran hak akses (*role*), dan catatan audit sistem (*activity logs*) yang tersimpan aman untuk akuntabilitas operasional.
                            </li>
                        </ul>
                    </div>
                </article>

                <!-- 03. Pemanfaatan Data -->
                <article class="bws-policy-card" id="penggunaan-data">
                    <header class="bws-policy-card-header">
                        <div class="bws-policy-num-pill">03</div>
                        <h2 class="bws-policy-card-title">Pemanfaatan Data Irigasi</h2>
                    </header>
                    <div class="bws-policy-card-body">
                        <p>Seluruh data spasial dan atribut yang tersaji dalam sistem ini dimanfaatkan untuk:</p>
                        <ul>
                            <li>Mendukung penyusunan program pemeliharaan berkala dan rehabilitasi jaringan irigasi rawa BWS Kalimantan I.</li>
                            <li>Menjadi rujukan resmi bagi Dinas PUPR Provinsi/Kabupaten, Dinas Pertanian, dan P3A/GP3A dalam mengoptimalkan pengelolaan tata air tingkat tersier.</li>
                            <li>Menyediakan data terbuka bagi akademisi, peneliti, dan konsultan perencana dalam studi hidrologi, tata air rawa, dan perencanaan wilayah.</li>
                            <li>Menjamin integritas dan transparansi anggaran pengelolaan sumber daya air melalui rekam jejak digital.</li>
                        </ul>
                    </div>
                </article>

                <!-- 04. Informasi Publik & Batasan Akses -->
                <article class="bws-policy-card" id="informasi-publik">
                    <header class="bws-policy-card-header">
                        <div class="bws-policy-num-pill">04</div>
                        <h2 class="bws-policy-card-title">Informasi Publik dan Pembatasan Akses</h2>
                    </header>
                    <div class="bws-policy-card-body">
                        <p>
                            Peta sebaran daerah irigasi pada halaman publik dapat diakses secara bebas tanpa dipungut biaya oleh seluruh lapisan masyarakat sesuai amanat <strong>Undang-Undang No. 14 Tahun 2008 tentang Keterbukaan Informasi Publik (KIP)</strong> dan <strong>Peraturan Presiden No. 39 Tahun 2019 tentang Satu Data Indonesia</strong>.
                        </p>

                        <div class="bws-policy-callout success">
                            <i class="fas fa-check-circle mr-1.5"></i>
                            <strong>Prinsip Keterbukaan:</strong> Seluruh visualisasi spasial, statistik luas, dan nama daerah irigasi bersifat terbuka untuk publik guna mendukung transparansi pembangunan infrastruktur air.
                        </div>

                        <p>
                            Adapun pembatasan akses diberlakukan secara ketat pada modul panel administratif (*back-office*). Modul penambahan, pembaruan, dan penghapusan data spasial (*GeoJSON Upload &amp; Management*) hanya dapat dioperasikan oleh pejabat berwenang dan operator SISDA yang memiliki kredensial resmi.
                        </p>
                    </div>
                </article>

                <!-- 05. Cookie & Keamanan Sesi -->
                <article class="bws-policy-card" id="cookie-keamanan">
                    <header class="bws-policy-card-header">
                        <div class="bws-policy-num-pill">05</div>
                        <h2 class="bws-policy-card-title">Cookie dan Sesi Pengguna</h2>
                    </header>
                    <div class="bws-policy-card-body">
                        <p>
                            Aplikasi menggunakan *cookie* sesi teknis (*HTTP session cookies*) untuk fungsi-fungsi berikut:
                        </p>
                        <ul>
                            <li><strong>Autentikasi Aman:</strong> Menjaga sesi login admin dan operator agar terlindungi dari pemalsuan permintaan antar-situs (*Cross-Site Request Forgery / CSRF Protection*).</li>
                            <li><strong>Preferensi Tampilan:</strong> Menyimpan preferensi navigasi peta (seperti pilihan peta dasar citra satelit atau OpenStreetMap).</li>
                        </ul>
                        <div class="bws-policy-callout">
                            <i class="fas fa-lock mr-1.5 text-primary"></i>
                            <strong>Bebas Pelacak Pihak Ketiga:</strong> SIPDIRA Spasial <strong>tidak</strong> memasang cookie iklan pihak ketiga, pelacak komersial, atau alat monetisasi data pengguna.
                        </div>
                    </div>
                </article>

                <!-- 06. Layanan Pihak Ketiga -->
                <article class="bws-policy-card" id="layanan-pihak-ketiga">
                    <header class="bws-policy-card-header">
                        <div class="bws-policy-num-pill">06</div>
                        <h2 class="bws-policy-card-title">Layanan Pihak Ketiga &amp; Peta Dasar</h2>
                    </header>
                    <div class="bws-policy-card-body">
                        <p>Dalam menyajikan peta interaktif yang akurat dan responsif, SIPDIRA Spasial memanfaatkan layanan teknologi terpercaya:</p>
                        <ul>
                            <li><strong>OpenStreetMap (OSM):</strong> Digunakan sebagai peta dasar jalan (*street basemap*) dan toponimi wilayah berlisensi terbuka.</li>
                            <li><strong>Esri World Imagery:</strong> Menyediakan citra satelit resolusi tinggi untuk analisis tutupan lahan dan kondisi nyata daerah irigasi.</li>
                            <li><strong>Leaflet JS &amp; Proj4js:</strong> Mesin visualisasi peta sumber terbuka dan konversi sistem proyeksi koordinat WGS 84 (EPSG:4326) / UTM Zona 49N.</li>
                        </ul>
                    </div>
                </article>

                <!-- 07. Penyimpanan & Keamanan Data -->
                <article class="bws-policy-card" id="penyimpanan-keamanan">
                    <header class="bws-policy-card-header">
                        <div class="bws-policy-num-pill">07</div>
                        <h2 class="bws-policy-card-title">Penyimpanan dan Keamanan Sistem</h2>
                    </header>
                    <div class="bws-policy-card-body">
                        <p>
                            Seluruh basis data dan berkas spasial disimpan pada infrastruktur peladen terproteksi milik Balai Wilayah Sungai Kalimantan I. Kami menerapkan protokol keamanan standar meliputi:
                        </p>
                        <ul>
                            <li>Pencadangan data berkala (*routine automated backup*) untuk mencegah kehilangan data akibat anomali teknis.</li>
                            <li>Validasi ketat terhadap integritas berkas GeoJSON sebelum dipublikasikan ke peta publik.</li>
                            <li>Enkripsi kata sandi menggunakan algoritma *Bcrypt* yang kuat untuk setiap akun pengelola sistem.</li>
                            <li>Pencatatan rekam jejak (*audit trail*) pada setiap aktivitas penambahan, revisi, atau penghapusan data geospasial.</li>
                        </ul>
                    </div>
                </article>

                <!-- 08. Permohonan Data -->
                <article class="bws-policy-card" id="hak-permohonan">
                    <header class="bws-policy-card-header">
                        <div class="bws-policy-num-pill">08</div>
                        <h2 class="bws-policy-card-title">Hak Akses dan Permohonan Data</h2>
                    </header>
                    <div class="bws-policy-card-body">
                        <p>
                            Masyarakat umum, mahasiswa, akademisi, instansi pemerintah daerah, dan konsultan teknik berhak mengajukan permohonan data spasial vektor resmi (format Shapefile/GeoJSON) untuk kepentingan penelitian, studi kelayakan, atau perencanaan teknis.
                        </p>
                        <p>
                            Permohonan data resmi dapat diajukan dengan melampirkan surat permohonan kedinasan/institusi yang ditujukan kepada Kepala Balai Wilayah Sungai Kalimantan I Pontianak melalui Unit SISDA.
                        </p>
                    </div>
                </article>

                <!-- 09. Perubahan Kebijakan -->
                <article class="bws-policy-card" id="perubahan-kebijakan">
                    <header class="bws-policy-card-header">
                        <div class="bws-policy-num-pill">09</div>
                        <h2 class="bws-policy-card-title">Pemutakhiran Dokumen Kebijakan</h2>
                    </header>
                    <div class="bws-policy-card-body">
                        <p>
                            Kebijakan privasi dan tata kelola data ini dapat diperbarui sewaktu-waktu guna menyelaraskan dengan regulasi terbaru Kementerian Pekerjaan Umum, kebijakan Satu Data Indonesia, dan perkembangan standar teknologi geospasial.
                        </p>
                        <p>
                            Setiap pembaruan akan segera dicantumkan tanggal revisinya pada halaman ini dan langsung berlaku sejak tanggal publikasi.
                        </p>
                    </div>
                </article>

                <!-- 10. Hubungi Pengelola -->
                <article class="bws-policy-card" id="hubungi-pengelola">
                    <header class="bws-policy-card-header">
                        <div class="bws-policy-num-pill">10</div>
                        <h2 class="bws-policy-card-title">Hubungi Pengelola Sistem</h2>
                    </header>
                    <div class="bws-policy-card-body">
                        <p>
                            Untuk pertanyaan, masukan teknis, atau konfirmasi terkait data geospasial Daerah Irigasi Rawa pada portal SIPDIRA Spasial, silakan hubungi unit pengelola resmi:
                        </p>

                        <div class="bws-contact-box">
                            <div class="font-weight-bold text-dark mb-1" style="font-size: 14px;">
                                <i class="fas fa-building text-warning mr-1.5"></i> Balai Wilayah Sungai Kalimantan I Pontianak
                            </div>
                            <div class="text-muted small mb-2">
                                Direktorat Jenderal Sumber Daya Air &bull; Kementerian Pekerjaan Umum
                            </div>
                            <div class="small text-secondary mb-3" style="line-height: 1.6;">
                                <i class="fas fa-map-marker-alt text-danger mr-1.5"></i> Jl. Ahmad Yani No. 1, Bansir Darat, Kec. Pontianak Tenggara, Kota Pontianak, Kalimantan Barat 78124<br>
                                <i class="fas fa-envelope text-primary mr-1.5"></i> Unit Sistem Informasi Sumber Daya Air (SISDA) BWS Kalimantan I
                            </div>
                            
                            <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                                <a href="https://wa.me/62812567890?text=Halo%20Admin%20SISDA%20BWS%20Kalimantan%20I,%20saya%20ingin%20bertanya%20mengenai%20data%20spasial%20SIPDIRA" target="_blank" class="btn btn-sm btn-success font-weight-bold px-3 py-2" style="border-radius: 8px;">
                                    <i class="fab fa-whatsapp mr-1.5"></i> Layanan Chat SISDA
                                </a>
                                <a href="{{ route('peta') }}" class="bws-btn-official-contact">
                                    <i class="fas fa-map mr-1.5 text-warning"></i> Jelajahi WebGIS
                                </a>
                            </div>
                        </div>
                    </div>
                </article>

            </main>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Smooth Scroll & Active State Highlighting for Table of Contents
    $(document).ready(function() {
        var $tocLinks = $('.bws-toc-link');
        var $sections = $('article.bws-policy-card');

        // Smooth scroll saat klik daftar isi
        $tocLinks.on('click', function(e) {
            e.preventDefault();
            var targetId = $(this).attr('href');
            var $target = $(targetId);
            if ($target.length) {
                $('html, body').stop().animate({
                    scrollTop: $target.offset().top - 85
                }, 500, 'easeInOutExpo');
            }
        });

        // Highlight aktif saat di-scroll (ScrollSpy sederhana & akurat)
        $(window).on('scroll', function() {
            var scrollPos = $(window).scrollTop() + 110;

            $sections.each(function() {
                var top = $(this).offset().top;
                var bottom = top + $(this).outerHeight();
                var id = $(this).attr('id');

                if (scrollPos >= top && scrollPos < bottom) {
                    $tocLinks.removeClass('active');
                    $('.bws-toc-link[href="#' + id + '"]').addClass('active');
                }
            });
        });
    });
</script>
@endpush


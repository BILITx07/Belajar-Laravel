@extends('layout.master')

@section('title', 'SIPDIRA - Sistem Informasi Peta Daerah Irigasi BWS Kalimantan I')
@section('hide_footer', true)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/peta.css') }}?v={{ file_exists(public_path('css/peta.css')) ? filemtime(public_path('css/peta.css')) : time() }}">
@endpush

@section('konten')
<div class="bws-qrop-page">

    <!-- ============================================================ -->
    <!-- 1. KARTU BANNER HERO MEWAH (PERSIS SEPERTI GAMBAR 1 QROP)    -->
    <!-- ============================================================ -->
    <div class="bws-hero-card">
        <div class="bws-hero-content">
            <div class="bws-hero-pretitle">
                <span class="bws-dot-hero"></span> DATA SPASIAL SIPDIRA &bull; BWS KALIMANTAN I
            </div>
            <h1 class="bws-hero-title">
                Peta Daerah Irigasi &amp; Jaringan SDA.
            </h1>
            <p class="bws-hero-desc">
                Temukan sebaran Daerah Irigasi, Jaringan Saluran, dan Bangunan Air di Kalimantan Barat dalam satu peta.
            </p>
        </div>
        <div class="bws-hero-location d-none d-md-flex align-items-center">
            <div class="bws-location-icon-box">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div>
                <div class="font-weight-bold" style="font-size: 13px;">Kalimantan Barat</div>
                <small style="color: rgba(255, 255, 255, 0.75);">BWS Kalimantan I Pontianak</small>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- 2. WADAH UTAMA KONTEN: SIDEBAR + PETA SEJAJAR                -->
    <!-- ============================================================ -->
    <div class="bws-qrop-workspace">

        <!-- ── SIDEBAR KIRI (PERSIS GAMBAR 1 REFERENSI QROP) ── -->
        <aside class="bws-sidebar-card">

            <!-- Header Sidebar dengan Lingkaran Ikon -->
            <div class="bws-sidebar-header-qrop">
                <div class="bws-circle-icon">
                    <i class="fas fa-search"></i>
                </div>
                <div>
                    <h5 class="bws-sidebar-main-title">Pencarian Data Irigasi</h5>
                    <div class="bws-sidebar-main-sub">Cari berdasarkan nama daerah atau wilayah administratif.</div>
                </div>
            </div>

            <!-- Field 1: Cari Usaha atau Alamat / Pos Irigasi -->
            <div class="bws-field-block">
                <label class="bws-field-label">Cari Daerah Irigasi (DIR)</label>
                <div class="bws-input-container">
                    <input type="text"
                           class="form-control bws-text-input"
                           id="sidebarSearchInput"
                           placeholder="Ketik nama daerah atau lokasi irigasi..."
                           autocomplete="off"
                           onkeyup="handleSidebarSearch(this.value)">
                    <button type="button"
                            class="bws-clear-search-btn"
                            id="btnSidebarSearchClear"
                            onclick="clearSidebarSearch()"
                            title="Hapus pencarian">&times;</button>
                </div>
                <small class="bws-field-hint">Klik hasil pencarian untuk memfokuskan lokasi pada peta.</small>
                <!-- Dropdown Hasil Autocomplete -->
                <div id="sidebarSearchResults" class="bws-dropdown-results" style="display: none;"></div>
            </div>

            <!-- Field 2: Wilayah Kecamatan / Kabupaten -->
            <div class="bws-field-block">
                <label class="bws-field-label">Wilayah kabupaten</label>
                <div class="bws-select-container">
                    <i class="fas fa-map-marker-alt bws-pin-icon text-muted"></i>
                    <select class="form-control bws-select-input" id="selectFilterWilayah" onchange="handleFilterWilayah(this.value)">
                        <option value="all" selected>Semua kabupaten (Kalbar)</option>
                        <option value="sambas">Kabupaten Sambas / Pemangkat</option>
                        <option value="kubu_raya">Kabupaten Kubu Raya</option>
                        <option value="ketapang">Kabupaten Ketapang</option>
                    </select>
                </div>
            </div>

            <!-- Field 3: Tampilkan Kategori (KARTU IKON PASTEL ALA GAMBAR 1) -->
            <div class="bws-field-block">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <label class="bws-field-label mb-0">Tampilkan kategori</label>
                    <span class="bws-sublabel-link">Layer peta</span>
                </div>

                <div class="bws-category-stack">
                    <!-- 1. Lahan Baku (Hijau) -->
                    <div class="bws-category-row" onclick="toggleQropCheckbox('chkLayerBaku')">
                        <div class="bws-icon-pastel box-baku">
                            <i class="fas fa-seedling"></i>
                        </div>
                        <div class="bws-category-label-wrap">
                            <div class="bws-category-text">Lahan Baku</div>
                            <small class="text-muted">Daerah Irigasi Baku</small>
                        </div>
                        <div class="bws-check-wrap">
                            <input type="checkbox" id="chkLayerBaku" checked onchange="toggleCategoryLayer('baku', this.checked); event.stopPropagation();">
                            <span class="bws-green-badge"><i class="fas fa-check"></i></span>
                        </div>
                    </div>

                    <!-- 2. Lahan Fungsional (Biru) -->
                    <div class="bws-category-row" onclick="toggleQropCheckbox('chkLayerFungsional')">
                        <div class="bws-icon-pastel box-fungsional">
                            <i class="fas fa-water"></i>
                        </div>
                        <div class="bws-category-label-wrap">
                            <div class="bws-category-text">Lahan Fungsional</div>
                            <small class="text-muted">Aktif Teraliri Air</small>
                        </div>
                        <div class="bws-check-wrap">
                            <input type="checkbox" id="chkLayerFungsional" checked onchange="toggleCategoryLayer('fungsional', this.checked); event.stopPropagation();">
                            <span class="bws-green-badge"><i class="fas fa-check"></i></span>
                        </div>
                    </div>

                    <!-- 3. Lahan Potensial (Oranye) -->
                    <div class="bws-category-row" onclick="toggleQropCheckbox('chkLayerPotensial')">
                        <div class="bws-icon-pastel box-potensial">
                            <i class="fas fa-drafting-compass"></i>
                        </div>
                        <div class="bws-category-label-wrap">
                            <div class="bws-category-text">Lahan Potensial</div>
                            <small class="text-muted">Rencana Pengembangan</small>
                        </div>
                        <div class="bws-check-wrap">
                            <input type="checkbox" id="chkLayerPotensial" checked onchange="toggleCategoryLayer('potensial', this.checked); event.stopPropagation();">
                            <span class="bws-green-badge"><i class="fas fa-check"></i></span>
                        </div>
                    </div>

                    <!-- 4. Jaringan Saluran -->
                    <div class="bws-category-row" onclick="toggleQropCheckbox('chkLayerSaluran')">
                        <div class="bws-icon-pastel box-saluran">
                            <i class="fas fa-route"></i>
                        </div>
                        <div class="bws-category-label-wrap">
                            <div class="bws-category-text">Jaringan Saluran</div>
                            <small class="text-muted">Primer, Sekunder &amp; Tersier</small>
                        </div>
                        <div class="bws-check-wrap">
                            <input type="checkbox" id="chkLayerSaluran" checked onchange="toggleCategoryLayer('saluran', this.checked); event.stopPropagation();">
                            <span class="bws-green-badge"><i class="fas fa-check"></i></span>
                        </div>
                    </div>

                    <!-- 5. Bangunan Air -->
                    <div class="bws-category-row" onclick="toggleQropCheckbox('chkLayerBangunan')">
                        <div class="bws-icon-pastel box-bangunan">
                            <i class="fas fa-archway"></i>
                        </div>
                        <div class="bws-category-label-wrap">
                            <div class="bws-category-text">Bangunan Air</div>
                            <small class="text-muted">Bendung &amp; Pintu Air</small>
                        </div>
                        <div class="bws-check-wrap">
                            <input type="checkbox" id="chkLayerBangunan" checked onchange="toggleCategoryLayer('bangunan', this.checked); event.stopPropagation();">
                            <span class="bws-green-badge"><i class="fas fa-check"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Field 4: Catatan Penggunaan (Persis Gambar 1) -->
            <div class="bws-note-box">
                <i class="fas fa-info-circle text-muted mr-2 mt-1"></i>
                <div class="small text-muted" style="line-height: 1.45;">
                    Klik ikon di peta untuk melihat info, luas, dan koordinat akurat daerah irigasi.
                </div>
            </div>

            <!-- Footer Instansi BWSK I -->
            <div class="bws-sidebar-bottom-footer mt-auto pt-3 border-top">
                <div class="text-muted" style="font-size: 11px;">
                    <div class="font-weight-bold text-dark">Balai Wilayah Sungai Kalimantan I</div>
                    <div>Pontianak &bull; Kementerian Pekerjaan Umum</div>
                </div>
            </div>

        </aside>

        <!-- ── AREA KANAN: PETA INTERAKTIF LUAS (PERSIS GAMBAR 1 QROP) ── -->
        <main class="bws-map-container-column">

            <!-- Header Peta Ramping (Breadcrumb Kiri & Badge Kanan) -->
            <div class="bws-map-topbar">
                <div class="d-flex align-items-center">
                    <span class="bws-dot-green-qrop"></span>
                    <span class="font-weight-bold text-dark ml-2" style="font-size: 13.5px;">Peta Daerah Irigasi</span>
                    <span class="text-muted mx-2">/</span>
                    <span class="text-muted small font-weight-bold" id="currentBreadcrumbLabel">Seluruh Kalimantan Barat</span>
                </div>

                <div class="d-flex align-items-center" style="gap: 8px;">
                    <!-- Switcher Peta Standar vs Satelit Rapi -->
                    <div class="btn-group btn-group-sm bws-basemap-group shadow-sm" role="group">
                        <button type="button" class="btn btn-sm bws-basemap-btn active" id="btnBasemapOsm" onclick="switchBasemap('osm')" title="Ganti ke Peta Standar / Peta Vektor">
                            <i class="fas fa-map mr-1"></i> Peta
                        </button>
                        <button type="button" class="btn btn-sm bws-basemap-btn" id="btnBasemapSat" onclick="switchBasemap('sat')" title="Ganti ke Citra Satelit Google">
                            <i class="fas fa-satellite mr-1"></i> Satelit
                        </button>
                    </div>

                    <button type="button" class="btn btn-sm btn-white bws-action-pill shadow-sm" onclick="resetToKalbar()" title="Kembalikan tampilan ke seluruh Kalimantan Barat">
                        <i class="fas fa-home mr-1 text-primary"></i> Atur Ulang
                    </button>
                </div>
            </div>

            <!-- Card Peta Leaflet -->
            <div class="bws-leaflet-card">
                <div class="bws-map-loading" id="map-loading">
                    <i class="fas fa-spinner fa-spin text-primary"></i>
                    <span class="ml-2 font-weight-bold">Memuat koordinat spasial BWS Kalimantan I...</span>
                </div>
                <div id="map"></div>
            </div>

        </main>

    </div>

    <!-- ============================================================ -->
    <!-- 3. TENTANG SIPDIRA & KRING LAYANAN (PERSIS GAMBAR REFERENSI) -->
    <!-- ============================================================ -->
    <section id="tentang-sipdira" class="sipdira-about-section">
        
        <div class="row align-items-stretch">
            <!-- Kolom Kiri: Kartu Brand Kotak Modern (Persis Card Kiri QROP) -->
            <div class="col-lg-4 col-md-5 mb-4 mb-md-0">
                <div class="sipdira-brand-about-card h-100">
                    <div class="sipdira-about-logo-wrap">
                        <img src="{{ asset('img/logo-sipdira.svg') }}" alt="Logo SIPDIRA" class="sipdira-about-logo-img">
                    </div>
                    <div class="sipdira-about-brand-text">
                        <span class="sipdira-about-title-main">SIPDIRA</span>
                        <span class="sipdira-about-title-sub">SPASIAL</span>
                    </div>
                    <div class="sipdira-about-brand-caption">
                        SISTEM INFORMASI PETA DAERAH IRIGASI
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Teks Layanan & Accordion Panduan -->
            <div class="col-lg-8 col-md-7 d-flex flex-column justify-content-center">
                <div class="sipdira-about-content pl-lg-3">
                    <div class="sipdira-service-tag">TENTANG LAYANAN</div>
                    <h2 class="sipdira-about-headline">
                        Informasi spasial daerah irigasi,<br class="d-none d-lg-block">
                        lebih dekat dan mudah dipahami.
                    </h2>
                    <p class="sipdira-about-desc">
                        SIPDIRA Spasial (Sistem Informasi Peta Daerah Irigasi Berbasis Data Spasial) merupakan sistem informasi pemetaan geospasial Balai Wilayah Sungai Kalimantan I Pontianak, Direktorat Jenderal Sumber Daya Air. Halaman ini menyajikan peta sebaran daerah irigasi kewenangan pusat maupun provinsi, luasan fungsional dan potensial, jaringan saluran primer, sekunder, serta bangunan air penunjang ketahanan pangan Kalimantan Barat.
                    </p>

                    <!-- Accordion Cara Menggunakan Peta (Persis QROP) -->
                    <div class="sipdira-how-to-accordion">
                        <button class="sipdira-how-to-toggle collapsed" type="button" data-toggle="collapse" data-target="#collapseCaraPeta" aria-expanded="false" aria-controls="collapseCaraPeta">
                            <span>Cara menggunakan peta</span>
                            <i class="fas fa-chevron-down sipdira-chevron"></i>
                        </button>
                        <div class="collapse" id="collapseCaraPeta">
                            <div class="sipdira-how-to-body">
                                <ul class="sipdira-guide-steps">
                                    <li><strong>1. Cari Daerah Irigasi:</strong> Gunakan kotak pencarian pada sidebar kiri atau pilih wilayah kabupaten untuk memusatkan peta ke lokasi yang diinginkan.</li>
                                    <li><strong>2. Pilih Kategori Layer:</strong> Centang atau nonaktifkan layer data spasial seperti Lahan Baku, Lahan Fungsional, Lahan Potensial, Jaringan Saluran, dan Bangunan Air.</li>
                                    <li><strong>3. Interaksi dengan Peta:</strong> Klik ikon lokasi pada peta untuk melihat detail informasi teknis, luasan hektare, dan data hidrologi.</li>
                                    <li><strong>4. Mode Citra Satelit:</strong> Beralih ke citra satelit resolusi tinggi dengan mengklik tombol "Satelit" pada panel kontrol peta.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- 3.1 METRIK DATA IRIGASI KALBAR (KHAS BWS KALIMANTAN I)      -->
        <!-- ============================================================ -->
        <div class="sipdira-metrics-block">
            <div class="sipdira-metrics-header d-flex flex-column flex-md-row justify-content-between align-items-md-end">
                <div>
                    <span class="sipdira-metrics-tag" style="color: #1a1c54;"><i class="fas fa-chart-pie mr-1 text-warning"></i> Data Spasial Terpadu</span>
                    <h3 class="sipdira-metrics-title" style="color: #1a1c54;">Statistik Daerah Irigasi Kalimantan Barat</h3>
                    <p class="sipdira-metrics-subtitle">Rekapitulasi luasan lahan, jaringan saluran irigasi, dan bangunan air kewenangan BWS Kalimantan I.</p>
                </div>
                <div class="mt-2 mt-md-0 text-md-right">
                    <span class="badge badge-light px-3 py-2 border font-weight-bold shadow-xs" style="font-size: 11.5px; color: #1a1c54; background: rgba(255, 255, 255, 0.95); border: 1.5px solid rgba(250, 214, 5, 0.6) !important; border-radius: 10px;">
                        <i class="fas fa-satellite mr-1 text-warning"></i> Terintegrasi WebGIS Spasial
                    </span>
                </div>
            </div>

            <div class="row">
                <!-- Kartu 1: Lahan Baku Irigasi -->
                <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-lg-0">
                    <div class="sipdira-metric-card theme-blue">
                        <div class="sipdira-metric-top">
                            <div class="sipdira-metric-icon-wrap">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <span class="sipdira-metric-indicator">Total Luas</span>
                        </div>
                        <div class="sipdira-metric-val">
                            45.820 <span class="sipdira-metric-unit">Ha</span>
                        </div>
                        <div class="sipdira-metric-label">Luas Lahan Baku Irigasi</div>
                        <div class="sipdira-metric-note">Potensi total daerah irigasi terdata di Kalimantan Barat.</div>
                    </div>
                </div>

                <!-- Kartu 2: Lahan Fungsional -->
                <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-lg-0">
                    <div class="sipdira-metric-card theme-cyan">
                        <div class="sipdira-metric-top">
                            <div class="sipdira-metric-icon-wrap">
                                <i class="fas fa-tint"></i>
                            </div>
                            <span class="sipdira-metric-indicator">Aktif Mengalir</span>
                        </div>
                        <div class="sipdira-metric-val">
                            32.450 <span class="sipdira-metric-unit">Ha</span>
                        </div>
                        <div class="sipdira-metric-label">Lahan Fungsional</div>
                        <div class="sipdira-metric-note">Lahan pertanian pangan yang aktif terairi secara kontinyu.</div>
                    </div>
                </div>

                <!-- Kartu 3: Jaringan Saluran -->
                <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-lg-0">
                    <div class="sipdira-metric-card theme-green">
                        <div class="sipdira-metric-top">
                            <div class="sipdira-metric-icon-wrap">
                                <i class="fas fa-route"></i>
                            </div>
                            <span class="sipdira-metric-indicator">Primer &amp; Sekunder</span>
                        </div>
                        <div class="sipdira-metric-val">
                            184,5 <span class="sipdira-metric-unit">Km</span>
                        </div>
                        <div class="sipdira-metric-label">Panjang Jaringan Saluran</div>
                        <div class="sipdira-metric-note">Jaringan saluran pembawa dan pembuang irigasi teknis.</div>
                    </div>
                </div>

                <!-- Kartu 4: Bangunan Air -->
                <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-lg-0">
                    <div class="sipdira-metric-card theme-amber">
                        <div class="sipdira-metric-top">
                            <div class="sipdira-metric-icon-wrap">
                                <i class="fas fa-archway"></i>
                            </div>
                            <span class="sipdira-metric-indicator">Bangunan SDA</span>
                        </div>
                        <div class="sipdira-metric-val">
                            128 <span class="sipdira-metric-unit">Unit</span>
                        </div>
                        <div class="sipdira-metric-label">Bangunan &amp; Pintu Air</div>
                        <div class="sipdira-metric-note">Bendung, intake, pintu air, dan gorong-gorong pengendali.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- 3.2 FOOTER RESMI KEDINASAN BWS KALIMANTAN I                 -->
        <!-- ============================================================ -->
        <footer id="kontak-layanan" class="bws-official-footer">
            <div class="row">
                <!-- Kolom 1: Profil Instansi Resmi -->
                <div class="col-lg-5 col-md-12 mb-4 mb-lg-0">
                    <div class="bws-footer-brand-wrap">
                        <div class="bws-footer-logo-pu">
                            <img src="{{ asset('img/logo-pu.svg') }}" alt="Logo PU" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                        <div class="bws-footer-logo-sipdira">
                            <img src="{{ asset('img/logo-sipdira.svg') }}" alt="Logo SIPDIRA" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                        <div>
                            <h4 class="bws-footer-title">SIPDIRA SPASIAL</h4>
                            <div class="bws-footer-subtitle">Sistem Informasi Peta Daerah Irigasi &bull; BWS Kalimantan I</div>
                        </div>
                    </div>
                    <p class="bws-footer-address">
                        <strong>Balai Wilayah Sungai Kalimantan I Pontianak</strong><br>
                        Direktorat Jenderal Sumber Daya Air &bull; Kementerian Pekerjaan Umum<br>
                        <i class="fas fa-map-marker-alt text-warning mr-1"></i> Jl. Paris II No. 1, Kota Pontianak, Kalimantan Barat 78124
                    </p>
                    <div class="bws-footer-social-wrap">
                        <a href="https://instagram.com/bwskalimantan1" target="_blank" rel="noopener noreferrer" class="bws-footer-social-pill">
                            <i class="fab fa-instagram text-danger"></i> @bwskalimantan1
                        </a>
                        <a href="https://wa.me/6285389999100" target="_blank" rel="noopener noreferrer" class="bws-footer-social-pill">
                            <i class="fab fa-whatsapp text-success"></i> +62 853 8999 9100
                        </a>
                    </div>
                </div>

                <!-- Kolom 2: Layanan & Kontak Resmi -->
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <h5 class="bws-footer-col-title">Layanan &amp; Pengaduan</h5>
                    <ul class="bws-footer-list">
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <span>Telepon: (0561) 742182 / 742183</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>Email: <a href="mailto:bwskalimantan1@pu.go.id">bwskalimantan1@pu.go.id</a></span>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>Jam Layanan: Senin - Jumat (08.00 - 16.00 WIB)</span>
                        </li>
                        <li>
                            <i class="fas fa-shield-alt"></i>
                            <span>Pengaduan Publik: LAPOR! / Kring SDA Kalimantan I</span>
                        </li>
                    </ul>
                </div>

                <!-- Kolom 3: Tautan Terkait Instansi -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="bws-footer-col-title">Tautan Terkait</h5>
                    <ul class="bws-footer-list">
                        <li>
                            <i class="fas fa-external-link-alt"></i>
                            <a href="https://pu.go.id" target="_blank" rel="noopener noreferrer">Kementerian Pekerjaan Umum</a>
                        </li>
                        <li>
                            <i class="fas fa-external-link-alt"></i>
                            <a href="https://sda.pu.go.id" target="_blank" rel="noopener noreferrer">Ditjen Sumber Daya Air</a>
                        </li>
                        <li>
                            <i class="fas fa-external-link-alt"></i>
                            <a href="https://sigi.pu.go.id" target="_blank" rel="noopener noreferrer">SIGI Geospasial PU</a>
                        </li>
                        <li>
                            <i class="fas fa-external-link-alt"></i>
                            <a href="https://tanahair.indonesia.go.id" target="_blank" rel="noopener noreferrer">Ina-Geoportal BIG</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bar Bawah Hak Cipta & Integritas Geospasial -->
            <div class="bws-footer-bottom">
                <div>
                    &copy; {{ date('Y') }} <strong>SIPDIRA SPASIAL</strong> &bull; Balai Wilayah Sungai Kalimantan I Pontianak. Seluruh Hak Cipta Dilindungi.
                </div>
                <div>
                    <span class="mr-2"><i class="fas fa-check-circle text-success mr-1"></i> Standar Data Geospasial Nasional</span>
                </div>
            </div>
        </footer>

    </section>

</div>

<!-- MODAL PETUNJUK PENGGUNAAN WEBGIS (Resmi & Profesional) -->
<div class="modal fade" id="modalPanduanAplikasi" tabindex="-1" role="dialog" aria-labelledby="modalPanduanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
            
            <!-- Modal Header (Gaya Resmi Kedinasan PU) -->
            <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #093c52 0%, #0d5c70 50%, #117887 100%);">
                <div class="d-flex align-items-center">
                    <div class="mr-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px; background: #ffffff; border-radius: 12px; color: #1d4ed8; font-size: 20px; border: 2px solid rgba(250, 214, 5, 0.9);">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold text-white mb-0" id="modalPanduanLabel" style="font-size: 1.15rem; letter-spacing: 0.3px;">
                            Petunjuk Penggunaan WebGIS
                        </h5>
                        <small style="color: rgba(255, 255, 255, 0.88); font-size: 0.78rem;">
                            Balai Wilayah Sungai Kalimantan I &bull; Direktorat Jenderal Sumber Daya Air
                        </small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85; text-shadow: none;">
                    <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                </button>
            </div>
            
            <div class="modal-body p-4">
                <!-- Navigasi Tab Modern -->
                <ul class="nav nav-pills nav-fill mb-3" id="pills-tab-panduan" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold py-2" id="tab-peta-link" data-toggle="pill" href="#tab-peta" role="tab" style="font-size: 0.85rem; border-radius: 10px;">
                            <i class="fas fa-layer-group mr-1"></i> 1. Lapisan &amp; Simbol Peta
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-2" id="tab-filter-link" data-toggle="pill" href="#tab-filter" role="tab" style="font-size: 0.85rem; border-radius: 10px;">
                            <i class="fas fa-search-location mr-1"></i> 2. Pencarian &amp; Filter
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-2" id="tab-popup-link" data-toggle="pill" href="#tab-popup" role="tab" style="font-size: 0.85rem; border-radius: 10px;">
                            <i class="fas fa-info-circle mr-1"></i> 3. Interaksi &amp; Detail
                        </a>
                    </li>
                </ul>

                <!-- Konten Tab Panduan -->
                <div class="tab-content border rounded p-3 bg-white shadow-sm" id="pills-tabContent" style="min-height: 230px; font-size: 13px; line-height: 1.6; border-color: #e2e8f0 !important;">
                    
                    <!-- TAB 1: LAPISAN & SIMBOL PETA -->
                    <div class="tab-pane fade show active" id="tab-peta" role="tabpanel">
                        <div class="font-weight-bold text-primary mb-2" style="font-size: 14px;">
                            <i class="fas fa-palette mr-1"></i> Klasifikasi Data Spasial Irigasi
                        </div>
                        <p class="text-secondary mb-3">Setiap warna dan bentuk geometri pada peta merepresentasikan objek sumber daya air resmi BWS Kalimantan I:</p>
                        
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="p-2 border rounded bg-light d-flex align-items-center">
                                    <span class="badge badge-success px-2 py-1 mr-2" style="min-width: 70px;">Hijau</span>
                                    <div><strong>Lahan Baku:</strong> Lahan siap tanam dalam kewenangan Daerah Irigasi Rawa (DIR).</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="p-2 border rounded bg-light d-flex align-items-center">
                                    <span class="badge badge-primary px-2 py-1 mr-2" style="min-width: 70px;">Biru</span>
                                    <div><strong>Lahan Fungsional:</strong> Lahan aktif beririgasi dan beroperasi O&amp;P rutin.</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="p-2 border rounded bg-light d-flex align-items-center">
                                    <span class="badge badge-warning text-dark px-2 py-1 mr-2" style="min-width: 70px;">Kuning</span>
                                    <div><strong>Lahan Potensial:</strong> Wilayah perencanaan pengembangan potensi irigasi.</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="p-2 border rounded bg-light d-flex align-items-center">
                                    <span class="badge badge-info px-2 py-1 mr-2" style="background:#0284c7; color:#fff; min-width: 70px;">Cyan</span>
                                    <div><strong>Jaringan Saluran:</strong> Trase garis saluran pembawa primer, sekunder, &amp; tersier.</div>
                                </div>
                            </div>
                            <div class="col-12 mb-1">
                                <div class="p-2 border rounded bg-light d-flex align-items-center">
                                    <span class="badge px-2 py-1 mr-2" style="background:#d97706; color:#fff; min-width: 70px;">Oranye</span>
                                    <div><strong>Bangunan Air:</strong> Titik pintu air, bendung, sadap, gorong-gorong, dan pos hidrologi.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: PENCARIAN & FILTER -->
                    <div class="tab-pane fade" id="tab-filter" role="tabpanel">
                        <div class="font-weight-bold text-primary mb-2" style="font-size: 14px;">
                            <i class="fas fa-search mr-1"></i> Cara Cepat Menemukan Objek Irigasi
                        </div>
                        <ul class="pl-3 text-secondary mb-2">
                            <li class="mb-2">
                                <strong>Pencarian Pintar (*Sidebar* Kiri):</strong> Ketikkan nama pos atau daerah irigasi (misal: <em>"Pemangkat"</em>, <em>"Sungai Daun"</em>, atau <em>"Kubu"</em>) pada kotak pencarian, lalu klik hasil yang muncul untuk langsung menuju lokasi.
                            </li>
                            <li class="mb-2">
                                <strong>Filter Kabupaten:</strong> Gunakan menu pilihan wilayah untuk membatasi fokus peta ke Kabupaten tertentu (Kabupaten Sambas, Kubu Raya, atau Ketapang).
                            </li>
                            <li class="mb-1">
                                <strong>Tombol Kategori Layer:</strong> Klik baris kategori (*Lahan Baku*, *Fungsional*, *Saluran*, *Bangunan Air*) di panel kiri untuk menampilkan atau menyembunyikan lapisan tertentu dari peta.
                            </li>
                        </ul>
                    </div>

                    <!-- TAB 3: INTERAKSI & DETAIL -->
                    <div class="tab-pane fade" id="tab-popup" role="tabpanel">
                        <div class="font-weight-bold text-primary mb-2" style="font-size: 14px;">
                            <i class="fas fa-mouse-pointer mr-1"></i> Membaca Informasi &amp; Koordinat Objek
                        </div>
                        <p class="text-secondary mb-2">Peta ini bersifat interaktif dan akurat secara geospasial:</p>
                        <ul class="pl-3 text-secondary mb-2">
                            <li class="mb-2">
                                <strong>Klik Objek pada Peta:</strong> Setiap kali Anda mengklik poligon lahan, garis saluran, atau ikon bangunan air, peta akan melakukan <strong>pusat kamera otomatis (*smooth flyTo*)</strong> langsung ke titik koordinat presisi.
                            </li>
                            <li class="mb-2">
                                <strong>Kartu Informasi Melayang:</strong> Di atas objek yang diklik akan muncul kartu pop-up modern berisi Nama Objek, Kategori, Kabupaten, serta data teknis resmi dari Buku DIR BWS Kalimantan I.
                            </li>
                            <li class="mb-1">
                                <strong>Navigasi Peta:</strong> Anda dapat memperbesar (*zoom-in*), memperkecil (*zoom-out*), atau menggeser peta secara bebas menggunakan mouse komputer atau sentuhan layar sentuh pada HP.
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Tips Navigasi Praktis -->
                <div class="p-2 px-3 rounded mt-3 d-flex align-items-center" style="background: rgba(29, 78, 216, 0.06); border: 1px dashed rgba(29, 78, 216, 0.25); font-size: 12.5px; color: #1e40af;">
                    <i class="fas fa-lightbulb text-warning mr-2 fa-lg"></i>
                    <div>
                        <strong>Tips Praktis:</strong> Gunakan tombol <strong>Layar Penuh</strong> (<i class="fas fa-expand mx-1"></i>) pada pojok kanan atas navbar untuk melihat sebaran jaringan irigasi secara maksimal dan leluasa.
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-light py-2 px-4 justify-content-between">
                <small class="text-muted" style="font-size: 11.5px;">
                    <i class="fas fa-shield-alt text-success mr-1"></i> Data Spasial Resmi SISDA BWS Kalimantan I
                </small>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold px-4 shadow-sm" data-dismiss="modal" style="border-radius: 8px;">
                    Mengerti &amp; Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.9.0/proj4.js"></script>
<script>
    window.availableTypes = {!! json_encode($availableTypes ?? ['baku'=>true,'fungsional'=>true,'potensial'=>true,'saluran'=>true,'bangunan'=>true,'custom'=>false]) !!};
    window.geojsonFiles   = {!! json_encode($geojsonFiles ?? []) !!};
    window.lokasiData     = {!! json_encode($lokasi ?? []) !!};
    window.canManageGis   = {{ Auth::check() ? 'true' : 'false' }};

    function toggleQropCheckbox(chkId) {
        var chk = document.getElementById(chkId);
        if (chk) {
            chk.checked = !chk.checked;
            var cat = chkId.replace('chkLayer', '').toLowerCase();
            if (typeof toggleCategoryLayer === 'function') {
                toggleCategoryLayer(cat, chk.checked);
            }
        }
    }
</script>
<script src="{{ asset('js/peta-map.js') }}?v={{ file_exists(public_path('js/peta-map.js')) ? filemtime(public_path('js/peta-map.js')) : time() }}"></script>
@endpush

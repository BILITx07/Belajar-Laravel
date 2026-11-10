@extends('layout.master')

@section('title', 'SIPDIRA - Sistem Informasi Peta Daerah Irigasi BWS Kalimantan I')
@section('hide_footer', true)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/peta.css') }}?v={{ file_exists(public_path('css/peta.css')) ? filemtime(public_path('css/peta.css')) : time() }}">
<style>
    /* Tab Navigasi Bersih & Ringkas untuk Modal Panduan */
    .bws-clean-tabs {
        display: flex;
        gap: 20px;
        margin-bottom: -1px;
    }
    .bws-clean-tabs .nav-link {
        padding: 8px 0 10px 0 !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        color: #64748b !important;
        border: none !important;
        border-bottom: 2px solid transparent !important;
        border-radius: 0 !important;
        background: transparent !important;
        transition: color 0.15s ease, border-color 0.15s ease;
    }
    .bws-clean-tabs .nav-link:hover {
        color: #0f172a !important;
    }
    .bws-clean-tabs .nav-link.active {
        color: #10143a !important;
        border-bottom-color: #10143a !important;
        font-weight: 700 !important;
    }
</style>
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

            <!-- Strip Info Bawah Peta (Persis Gambar Referensi QROP) -->
            <div class="bws-map-footer-strip">
                <span class="bws-map-footer-left">
                    <span class="bws-pulse-dot mr-1"></span> Lokasi daerah irigasi ditandai pada peta.
                </span>
                <span class="bws-map-footer-right">
                    Geser untuk menjelajah &bull; Klik ikon untuk detail
                </span>
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
        <!-- 4. FOOTER RESMI KEMENTERIAN PU (BERSIH & ELEGAN)             -->
        <!-- ============================================================ -->
        <footer class="sipdira-clean-footer" id="kontak-layanan">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                <div class="sipdira-footer-left-info d-flex align-items-center mb-2 mb-md-0">
                    <img src="{{ asset('img/logo-pu.svg') }}" alt="Logo PU" class="sipdira-footer-mini-pu mr-2.5">
                    <div class="sipdira-footer-instansi-text">
                        <span>&copy; {{ date('Y') }} <strong>SIPDIRA SPASIAL</strong> &bull; Balai Wilayah Sungai Kalimantan I Pontianak</span>
                        <div class="text-muted small">Direktorat Jenderal Sumber Daya Air &bull; Kementerian Pekerjaan Umum</div>
                    </div>
                </div>
            </div>
        </footer>

    </section>

</div>

<!-- MODAL KONTAK RESMI BWS KALIMANTAN I (BERSIH & PROFESIONAL) -->
<div class="modal fade" id="modalKontakResmi" tabindex="-1" role="dialog" aria-labelledby="modalKontakLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
            <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #10143a 0%, #1a1c54 100%); border-bottom: 3.5px solid #f59e0b;">
                <div class="d-flex align-items-center">
                    <div class="mr-3 d-flex align-items-center justify-content-center bg-white shadow-xs" style="width: 42px; height: 42px; border-radius: 10px; border: 1.5px solid #fad605; padding: 4px;">
                        <img src="{{ asset('img/logo-pu.svg') }}" alt="Logo PU" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <div>
                        <h6 class="modal-title font-weight-bold text-white mb-0" id="modalKontakLabel" style="font-size: 1.05rem; letter-spacing: 0.3px;">
                            Kontak &amp; Layanan Resmi
                        </h6>
                        <small style="color: rgba(255, 255, 255, 0.88); font-size: 0.76rem;">
                            Balai Wilayah Sungai Kalimantan I Pontianak
                        </small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85; text-shadow: none;">
                    <span aria-hidden="true" style="font-size: 1.4rem;">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3 d-flex align-items-start">
                    <div class="mr-3 text-primary mt-1" style="font-size: 16px; width: 22px; text-align: center;"><i class="fas fa-map-marker-alt text-danger"></i></div>
                    <div>
                        <strong class="d-block text-dark font-weight-bold" style="font-size: 13.5px;">Alamat Kantor</strong>
                        <div class="text-muted small" style="line-height: 1.45;">Jl. Paris II No. 1, Kota Pontianak, Kalimantan Barat 78124</div>
                    </div>
                </div>
                <div class="mb-3 d-flex align-items-start">
                    <div class="mr-3 text-primary mt-1" style="font-size: 16px; width: 22px; text-align: center;"><i class="fas fa-phone-alt text-primary"></i></div>
                    <div>
                        <strong class="d-block text-dark font-weight-bold" style="font-size: 13.5px;">Telepon Kantor</strong>
                        <div class="text-muted small">(0561) 742182 / 742183</div>
                    </div>
                </div>
                <div class="mb-3 d-flex align-items-start">
                    <div class="mr-3 text-primary mt-1" style="font-size: 16px; width: 22px; text-align: center;"><i class="fas fa-envelope text-info"></i></div>
                    <div>
                        <strong class="d-block text-dark font-weight-bold" style="font-size: 13.5px;">Email Kedinasan</strong>
                        <div class="text-muted small"><a href="mailto:bwskalimantan1@pu.go.id" class="text-primary font-weight-bold">bwskalimantan1@pu.go.id</a></div>
                    </div>
                </div>
                <div class="mb-4 d-flex align-items-start">
                    <div class="mr-3 text-primary mt-1" style="font-size: 16px; width: 22px; text-align: center;"><i class="far fa-clock text-warning"></i></div>
                    <div>
                        <strong class="d-block text-dark font-weight-bold" style="font-size: 13.5px;">Jam Layanan Publik</strong>
                        <div class="text-muted small">Senin - Jumat: 08.00 - 16.00 WIB</div>
                    </div>
                </div>
                <div class="pt-3 border-top d-flex justify-content-between" style="gap: 10px;">
                    <a href="https://wa.me/6285389999100" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-success font-weight-bold flex-fill py-2 d-flex align-items-center justify-content-center shadow-xs" style="border-radius: 10px; gap: 6px;">
                        <i class="fab fa-whatsapp font-weight-bold" style="font-size: 15px;"></i> WhatsApp BWS
                    </a>
                    <a href="https://instagram.com/bwskalimantan1" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger font-weight-bold flex-fill py-2 d-flex align-items-center justify-content-center" style="border-radius: 10px; gap: 6px;">
                        <i class="fab fa-instagram" style="font-size: 15px;"></i> Instagram Resmi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PETUNJUK PENGGUNAAN WEBGIS (Bersih, Rapi & Ringkas) -->
<div class="modal fade" id="modalPanduanAplikasi" tabindex="-1" role="dialog" aria-labelledby="modalPanduanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 520px;">
        <div class="modal-content border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            
            <!-- Header Bersih & Human-Crafted (Tanpa Gradasi Berat / Border Emas Tebal) -->
            <div class="modal-header bg-white px-4 pt-3.5 pb-2 border-0 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="modal-title font-weight-bold mb-0" id="modalPanduanLabel" style="color: #0f172a; font-size: 15px; letter-spacing: -0.2px;">
                        Petunjuk Penggunaan WebGIS
                    </h6>
                    <span class="text-muted" style="font-size: 11px;">BWS Kalimantan I &bull; Ditjen SDA Kementerian PU</span>
                </div>
                <button type="button" class="close text-secondary" data-dismiss="modal" aria-label="Close" style="font-size: 1.35rem; font-weight: 300; opacity: 0.6; outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <!-- Tab Navigasi Garis Minimalis -->
            <div class="px-4" style="border-bottom: 1px solid #f1f5f9;">
                <ul class="nav bws-clean-tabs" id="pills-tab-panduan" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-peta-link" data-toggle="pill" href="#tab-peta" role="tab">
                            Simbol Peta
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-filter-link" data-toggle="pill" href="#tab-filter" role="tab">
                            Pencarian &amp; Filter
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-popup-link" data-toggle="pill" href="#tab-popup" role="tab">
                            Interaksi Peta
                        </a>
                    </li>
                </ul>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="tab-content" id="pills-tabContent">
                    
                    <!-- TAB 1: SIMBOL PETA (Legenda Rapi & Ringkas) -->
                    <div class="tab-pane fade show active" id="tab-peta" role="tabpanel">
                        <div class="text-muted mb-2.5" style="font-size: 11.5px;">
                            Arti kode warna dan simbol lapisan data irigasi pada peta:
                        </div>
                        <div class="row no-gutters" style="row-gap: 10px;">
                            <!-- 1. Lahan Baku -->
                            <div class="col-6 pr-2">
                                <div class="d-flex align-items-start">
                                    <span class="mr-2 mt-0.5" style="width: 14px; height: 14px; border-radius: 3px; background: rgba(16, 185, 129, 0.25); border: 2px solid #10b981; display: inline-block; flex-shrink: 0;"></span>
                                    <div>
                                        <div class="font-weight-bold" style="font-size: 12px; color: #0f172a;">Lahan Baku</div>
                                        <div class="text-muted" style="font-size: 11px; line-height: 1.3;">Siap tanam kewenangan DIR</div>
                                    </div>
                                </div>
                            </div>
                            <!-- 2. Lahan Fungsional -->
                            <div class="col-6 pl-2">
                                <div class="d-flex align-items-start">
                                    <span class="mr-2 mt-0.5" style="width: 14px; height: 14px; border-radius: 3px; background: rgba(37, 99, 235, 0.25); border: 2px solid #2563eb; display: inline-block; flex-shrink: 0;"></span>
                                    <div>
                                        <div class="font-weight-bold" style="font-size: 12px; color: #0f172a;">Lahan Fungsional</div>
                                        <div class="text-muted" style="font-size: 11px; line-height: 1.3;">Aktif beririgasi rutin O&amp;P</div>
                                    </div>
                                </div>
                            </div>
                            <!-- 3. Lahan Potensial -->
                            <div class="col-6 pr-2">
                                <div class="d-flex align-items-start">
                                    <span class="mr-2 mt-0.5" style="width: 14px; height: 14px; border-radius: 3px; background: rgba(245, 158, 11, 0.25); border: 2px solid #f59e0b; display: inline-block; flex-shrink: 0;"></span>
                                    <div>
                                        <div class="font-weight-bold" style="font-size: 12px; color: #0f172a;">Lahan Potensial</div>
                                        <div class="text-muted" style="font-size: 11px; line-height: 1.3;">Rencana pengembangan baru</div>
                                    </div>
                                </div>
                            </div>
                            <!-- 4. Jaringan Saluran -->
                            <div class="col-6 pl-2">
                                <div class="d-flex align-items-start">
                                    <span class="mr-2 mt-1.5" style="width: 14px; height: 3px; background: #06b6d4; display: inline-block; flex-shrink: 0; border-radius: 2px;"></span>
                                    <div>
                                        <div class="font-weight-bold" style="font-size: 12px; color: #0f172a;">Jaringan Saluran</div>
                                        <div class="text-muted" style="font-size: 11px; line-height: 1.3;">Trase primer, sekunder, tersier</div>
                                    </div>
                                </div>
                            </div>
                            <!-- 5. Bangunan Air -->
                            <div class="col-6 pr-2">
                                <div class="d-flex align-items-start">
                                    <span class="mr-2 mt-0.5" style="width: 12px; height: 12px; border-radius: 50%; background: #ea580c; border: 2px solid #fff; box-shadow: 0 0 0 1px #ea580c; display: inline-block; flex-shrink: 0;"></span>
                                    <div>
                                        <div class="font-weight-bold" style="font-size: 12px; color: #0f172a;">Bangunan Air</div>
                                        <div class="text-muted" style="font-size: 11px; line-height: 1.3;">Pintu air, bendung, &amp; pos BWS</div>
                                    </div>
                                </div>
                            </div>
                            <!-- 6. Batas Wilayah DIR -->
                            <div class="col-6 pl-2">
                                <div class="d-flex align-items-start">
                                    <span class="mr-2 mt-0.5" style="width: 14px; height: 14px; border-radius: 3px; background: rgba(99, 102, 241, 0.2); border: 2px dashed #6366f1; display: inline-block; flex-shrink: 0;"></span>
                                    <div>
                                        <div class="font-weight-bold" style="font-size: 12px; color: #0f172a;">Batas Wilayah DIR</div>
                                        <div class="text-muted" style="font-size: 11px; line-height: 1.3;">Delineasi area kewenangan</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: PENCARIAN & FILTER (Alur Alami Tanpa Badge Tebal) -->
                    <div class="tab-pane fade" id="tab-filter" role="tabpanel">
                        <div class="text-muted mb-2.5" style="font-size: 11.5px;">
                            3 langkah cepat menemukan data irigasi:
                        </div>
                        <div class="d-flex flex-column" style="gap: 10px;">
                            <div class="d-flex align-items-start">
                                <div class="mr-2.5 d-flex align-items-center justify-content-center font-weight-bold" style="width: 20px; height: 20px; border-radius: 50%; background: #f1f5f9; color: #10143a; font-size: 11px; flex-shrink: 0;">1</div>
                                <div style="font-size: 12px; line-height: 1.45;">
                                    <strong style="color: #0f172a;">Cari Nama DIR:</strong> Masukkan nama daerah irigasi (misal: <em>Pemangkat</em>, <em>Sungai Daun</em>) pada kolom pencarian kiri untuk terbang langsung ke lokasi.
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <div class="mr-2.5 d-flex align-items-center justify-content-center font-weight-bold" style="width: 20px; height: 20px; border-radius: 50%; background: #f1f5f9; color: #10143a; font-size: 11px; flex-shrink: 0;">2</div>
                                <div style="font-size: 12px; line-height: 1.45;">
                                    <strong style="color: #0f172a;">Filter Kabupaten:</strong> Gunakan menu wilayah untuk mempersempit tampilan peta ke kabupaten tertentu.
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <div class="mr-2.5 d-flex align-items-center justify-content-center font-weight-bold" style="width: 20px; height: 20px; border-radius: 50%; background: #f1f5f9; color: #10143a; font-size: 11px; flex-shrink: 0;">3</div>
                                <div style="font-size: 12px; line-height: 1.45;">
                                    <strong style="color: #0f172a;">Atur Layer:</strong> Centang atau hapus centang pada panel kiri untuk menentukan data yang ingin ditampilkan.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: INTERAKSI PETA -->
                    <div class="tab-pane fade" id="tab-popup" role="tabpanel">
                        <div class="text-muted mb-2.5" style="font-size: 11.5px;">
                            Fitur navigasi dan informasi interaktif peta:
                        </div>
                        <div class="d-flex flex-column" style="gap: 10px;">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-crosshairs text-muted mr-2.5 mt-1" style="font-size: 12px; width: 16px; text-align: center;"></i>
                                <div style="font-size: 12px; line-height: 1.45;">
                                    <strong style="color: #0f172a;">Fokus Otomatis:</strong> Klik objek di peta atau daftar hasil cari untuk memusatkan kamera ke lokasi sasaran.
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <i class="fas fa-info-circle text-muted mr-2.5 mt-1" style="font-size: 12px; width: 16px; text-align: center;"></i>
                                <div style="font-size: 12px; line-height: 1.45;">
                                    <strong style="color: #0f172a;">Informasi Teknis:</strong> Klik poligon atau saluran untuk membuka info luas (Ha), status O&amp;P, dan kewenangan.
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <i class="fas fa-layer-group text-muted mr-2.5 mt-1" style="font-size: 12px; width: 16px; text-align: center;"></i>
                                <div style="font-size: 12px; line-height: 1.45;">
                                    <strong style="color: #0f172a;">Mode Satelit:</strong> Klik tombol <em>Satelit</em> di kanan atas peta untuk melihat citra permukaan bumi riil.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Petunjuk Ringkas Bersahaja -->
                <div class="text-muted text-center pt-2.5 mt-3 border-top" style="font-size: 11px;">
                    <i class="fas fa-expand-alt mr-1"></i> Gunakan tombol <strong>Layar Penuh</strong> di navbar kanan atas untuk ruang pandang peta yang lebih leluasa.
                </div>
            </div>

            <!-- Footer Bersih & Sederhana -->
            <div class="modal-footer bg-light px-4 py-2 border-0 d-flex align-items-center justify-content-between">
                <button type="button" class="btn btn-sm btn-link text-muted p-0 font-weight-bold d-none" id="btnPanduanPrev" style="font-size: 12px; text-decoration: none;">
                    &larr; Sebelumnya
                </button>
                <div class="ml-auto d-flex align-items-center" style="gap: 8px;">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-dismiss="modal" style="font-size: 12px; border-radius: 6px;">
                        Tutup
                    </button>
                    <button type="button" class="btn btn-sm text-white px-3 font-weight-bold" id="btnPanduanNext" style="background: #10143a; font-size: 12px; border-radius: 6px;">
                        <span id="btnPanduanNextText">Lanjut</span> &rarr;
                    </button>
                </div>
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
<script>
    // ============================================================
    // WIZARD NAVIGASI PANDUAN PENGGUNAAN (STEP-BY-STEP MODAL)
    // ============================================================
    $(function() {
        var panduanTabs = ['#tab-peta-link', '#tab-filter-link', '#tab-popup-link'];
        var currentStep = 0;

        function updateWizardUI(step) {
            currentStep = step;
            if (step === 0) {
                $('#btnPanduanPrev').addClass('d-none');
                $('#btnPanduanNext').html('<span id="btnPanduanNextText">Lanjut</span> &rarr;');
                $('#btnPanduanNext').data('action', 'next');
            } else if (step === 1) {
                $('#btnPanduanPrev').removeClass('d-none');
                $('#btnPanduanNext').html('<span id="btnPanduanNextText">Lanjut</span> &rarr;');
                $('#btnPanduanNext').data('action', 'next');
            } else if (step === 2) {
                $('#btnPanduanPrev').removeClass('d-none');
                $('#btnPanduanNext').html('<span id="btnPanduanNextText">Selesai</span> &check;');
                $('#btnPanduanNext').data('action', 'finish');
            }
        }

        $('#pills-tab-panduan a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
            var targetId = $(e.target).attr('id');
            var idx = panduanTabs.indexOf('#' + targetId);
            if (idx !== -1) {
                updateWizardUI(idx);
            }
        });

        $('#btnPanduanNext').on('click', function() {
            if ($(this).data('action') === 'finish') {
                $('#modalPanduanAplikasi').modal('hide');
            } else {
                var next = currentStep + 1;
                if (next < panduanTabs.length) {
                    $(panduanTabs[next]).tab('show');
                }
            }
        });

        $('#btnPanduanPrev').on('click', function() {
            var prev = currentStep - 1;
            if (prev >= 0) {
                $(panduanTabs[prev]).tab('show');
            }
        });

        $('#modalPanduanAplikasi').on('show.bs.modal', function() {
            $(panduanTabs[0]).tab('show');
            updateWizardUI(0);
        });
    });
</script>
@endpush

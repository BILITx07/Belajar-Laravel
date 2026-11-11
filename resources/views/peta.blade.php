@extends('layout.master')

@section('title', 'SIPDIRA - Sistem Informasi Peta Daerah Irigasi BWS Kalimantan I')
@section('hide_footer', true)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/peta.css') }}?v={{ file_exists(public_path('css/peta.css')) ? filemtime(public_path('css/peta.css')) : time() }}">
<style>
    /* Segmented Tab Nav for Petunjuk Penggunaan Modal */
    .bws-modal-nav-capsule {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 4px;
        gap: 4px;
        display: flex;
    }
    .bws-modal-nav-capsule .nav-item {
        flex: 1;
        margin: 0;
    }
    .bws-modal-nav-capsule .nav-link {
        border-radius: 9px !important;
        font-size: 0.82rem !important;
        font-weight: 700 !important;
        color: #475569 !important;
        border: none !important;
        padding: 8px 10px !important;
        text-align: center;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        background: transparent;
        white-space: nowrap;
    }
    .bws-modal-nav-capsule .nav-link:hover {
        background: rgba(255, 255, 255, 0.85) !important;
        color: #10143a !important;
    }
    .bws-modal-nav-capsule .nav-link.active {
        background: #10143a !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(16, 20, 58, 0.22) !important;
    }
    .sipdira-legend-card {
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }
    .sipdira-legend-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border-color: #cbd5e1 !important;
        background: #ffffff !important;
    }
    .sipdira-footer-right-links {
        margin-right: 0 !important;
    }
    .sipdira-footer-policy-link {
        color: #64748b !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        background: transparent !important;
        border: none !important;
        padding: 4px 0 !important;
        box-shadow: none !important;
        transition: color 0.18s ease;
        display: inline-block;
    }
    .sipdira-footer-policy-link:hover {
        color: #10143a !important;
        text-decoration: underline !important;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        transform: none !important;
    }
    @media (max-width: 767.98px) {
        .sipdira-footer-right-links {
            margin-right: 0 !important;
            margin-top: 10px;
        }
    }

    /* Hero Card - Seamless, Clean & Borderless ala QROP (PU Navy & Gold Edition) */
    .bws-hero-card {
        background: linear-gradient(115deg, #10143a 0%, #161b4d 50%, #1c2364 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 15px !important;
        box-shadow: 0 6px 20px rgba(16, 20, 58, 0.15) !important;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
        padding: 27px 30px !important;
        margin-bottom: 25px !important;
    }
    .bws-dot-hero {
        width: 6px !important;
        height: 6px !important;
        background: #fad605 !important;
        box-shadow: none !important;
    }
    .bws-hero-title-accent {
        color: #fad605 !important;
    }
    .bws-hero-location {
        background: transparent !important;
        border: none !important;
        border-radius: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        backdrop-filter: none !important;
    }
    .bws-location-icon-box {
        width: 44px !important;
        height: 44px !important;
        background: rgba(255, 255, 255, 0.12) !important;
        color: #ffffff !important;
        border-radius: 50% !important;
    }
    .bws-location-main-text {
        font-weight: 700;
        font-size: 14.5px;
        color: #ffffff;
        line-height: 1.3;
        letter-spacing: -0.2px;
    }
    .bws-location-sub-text {
        color: rgba(255, 255, 255, 0.72);
        font-size: 12px;
        line-height: 1.3;
    }

    /* Sembunyikan Bendera Attribution Leaflet */
    .leaflet-attribution-flag,
    .leaflet-control-attribution svg {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
        visibility: hidden !important;
    }

    /* ══ FLOATING MAP TOOLS (PERSIS REFERENSI: GPS, HOME, FULLSCREEN) ══ */
    .bws-map-floating-tools {
        position: absolute;
        top: 96px; /* Spasi presisi 8px di bawah kontrol zoom Leaflet */
        left: 10px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 8px;
        pointer-events: auto;
    }
    .bws-map-tool-btn {
        width: 38px;
        height: 38px;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #166534; /* Hijau tua elegan khas cartographic GIS seperti referensi */
        font-size: 15px;
        cursor: pointer;
        outline: none !important;
        transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        padding: 0;
    }
    .bws-map-tool-btn:hover {
        background: #f8fafc;
        color: #10143a;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.14);
    }
    .bws-map-tool-btn:active {
        transform: scale(0.96);
    }

    /* Modern Squircle Leaflet Zoom Control */
    .leaflet-control-zoom {
        margin: 10px 0 0 10px !important;
    }
    .leaflet-bar {
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08) !important;
        border-radius: 12px !important;
        overflow: hidden !important;
    }
    .leaflet-bar a {
        width: 38px !important;
        height: 38px !important;
        line-height: 38px !important;
        color: #166534 !important;
        font-weight: 700 !important;
        background: #ffffff !important;
        border-bottom: 1px solid #f1f5f9 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }
    .leaflet-bar a:last-child {
        border-bottom: none !important;
    }
    .leaflet-bar a.leaflet-control-zoom-in {
        font-size: 19px !important;
    }
    .leaflet-bar a.leaflet-control-zoom-out {
        font-size: 21px !important;
    }
    .leaflet-bar a:hover {
        background: #f8fafc !important;
        color: #10143a !important;
    }

    /* User GPS Location Marker Pulse Animation */
    .bws-user-pulse-marker {
        position: relative;
    }
    .bws-gps-dot {
        width: 14px;
        height: 14px;
        background: #2563eb;
        border: 2.5px solid #ffffff;
        border-radius: 50%;
        position: absolute;
        top: 5px;
        left: 5px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        z-index: 2;
    }
    .bws-gps-pulse {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgba(37, 99, 235, 0.35);
        animation: bwsGpsPulseAnim 1.8s infinite ease-out;
    }
    @keyframes bwsGpsPulseAnim {
        0% { transform: scale(0.5); opacity: 1; }
        100% { transform: scale(2.2); opacity: 0; }
    }

    /* ══ MODE PERLUAS PETA PERSIS QROP (FIT 100% VIEWPORT, TANPA BISA DISCROLL) ══ */
    html.bws-no-scroll,
    body.bws-no-scroll {
        overflow: hidden !important;
        height: 100vh !important;
        max-height: 100vh !important;
    }

    .bws-qrop-page.bws-map-expanded {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        max-height: 100vh !important;
        overflow: hidden !important;
        padding: 10px 14px 10px 14px !important;
        background: #f1f5f9 !important;
        z-index: 9999 !important;
        display: flex !important;
        flex-direction: column !important;
        box-sizing: border-box !important;
    }

    .bws-qrop-page.bws-map-expanded .bws-hero-card,
    .bws-qrop-page.bws-map-expanded .bws-sidebar-card,
    .bws-qrop-page.bws-map-expanded #tentang-sipdira,
    .bws-qrop-page.bws-map-expanded .sipdira-about-section,
    .bws-qrop-page.bws-map-expanded .sipdira-clean-footer,
    .bws-qrop-page.bws-map-expanded #section-table,
    .bws-qrop-page.bws-map-expanded .sipdira-btn-back-top,
    .bws-qrop-page.bws-map-expanded .bws-scroll-top-btn {
        display: none !important;
    }

    .bws-qrop-page.bws-map-expanded .bws-qrop-workspace {
        flex: 1 1 100% !important;
        height: 100% !important;
        max-height: 100% !important;
        display: flex !important;
        flex-direction: column !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden !important;
    }

    .bws-qrop-page.bws-map-expanded .bws-map-container-column {
        flex: 1 1 100% !important;
        height: 100% !important;
        max-height: 100% !important;
        display: flex !important;
        flex-direction: column !important;
        overflow: hidden !important;
        background: #ffffff !important;
        border-radius: 14px !important;
        border: 2.5px solid #166534 !important; /* Border hijau cartographic persis gambar 1 QROP */
        box-shadow: 0 10px 30px rgba(16, 20, 58, 0.12) !important;
    }

    .bws-qrop-page.bws-map-expanded .bws-map-topbar {
        margin: 0 !important;
        padding: 9px 16px !important;
        background: #ffffff !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-radius: 12px 12px 0 0 !important;
        flex-shrink: 0 !important;
    }

    .bws-qrop-page.bws-map-expanded .bws-leaflet-card {
        flex: 1 1 auto !important;
        height: auto !important;
        min-height: 0 !important;
        max-height: none !important;
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        overflow: hidden !important;
        position: relative !important;
    }

    .bws-qrop-page.bws-map-expanded #map {
        width: 100% !important;
        height: 100% !important;
        min-height: 0 !important;
    }

    .bws-qrop-page.bws-map-expanded .bws-map-footer-strip {
        margin: 0 !important;
        padding: 6px 16px !important;
        background: #f8fafc !important;
        border-top: 1px solid #e2e8f0 !important;
        border-radius: 0 0 12px 12px !important;
        flex-shrink: 0 !important;
    }

    /* ══ FLOATING LAYER CONTROL PANEL (PERSIS GAMBAR 2 REFERENSI QROP) ══ */
    .bws-floating-layer-panel {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 1000;
        width: 228px;
        max-height: calc(100% - 24px);
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12), 0 2px 6px rgba(0, 0, 0, 0.04);
        display: none; /* Default tertutup sesuai permintaan */
        flex-direction: column;
        overflow: hidden;
        pointer-events: auto;
        font-family: inherit;
        animation: bwsFadeSlideDown 0.2s ease-out;
    }
    @keyframes bwsFadeSlideDown {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .bws-floating-layer-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 11px 14px 9px 14px;
        border-bottom: 1px solid #f1f5f9;
        background: #ffffff;
    }
    .bws-floating-layer-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
        letter-spacing: -0.2px;
    }
    .bws-floating-layer-hide-btn {
        background: #ecfdf5;
        color: #15803d;
        border: 1px solid #d1fae5;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        cursor: pointer;
        outline: none !important;
        transition: all 0.15s ease;
        line-height: 1.3;
    }
    .bws-floating-layer-hide-btn:hover {
        background: #dcfce7;
        color: #166534;
        border-color: #86efac;
    }
    .bws-floating-layer-body {
        padding: 10px 14px 14px 14px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        max-height: 390px;
    }
    /* Custom Scrollbar Persis Gambar 2 */
    .bws-floating-layer-body::-webkit-scrollbar {
        width: 6px;
    }
    .bws-floating-layer-body::-webkit-scrollbar-track {
        background: transparent;
    }
    .bws-floating-layer-body::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border-radius: 6px;
    }
    .bws-floating-layer-body::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }
    .bws-layer-sec-item {
        display: flex;
        flex-direction: column;
    }
    .bws-layer-sec-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        padding: 4px 0 6px 0;
        user-select: none;
    }
    .bws-layer-sec-header span {
        font-size: 12.5px;
        font-weight: 700;
        color: #15803d; /* Hijau cartographic khas QROP */
        letter-spacing: -0.1px;
    }
    .bws-chevron-indicator {
        font-size: 11px;
        color: #64748b;
        transition: transform 0.2s ease;
    }
    .bws-layer-sec-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding-top: 2px;
    }
    .bws-layer-item-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        cursor: pointer;
        user-select: none;
    }
    .bws-layer-item-label {
        font-size: 12.5px;
        color: #334155;
        font-weight: 500;
        line-height: 1.3;
        transition: color 0.15s ease;
    }
    .bws-layer-item-row:hover .bws-layer-item-label {
        color: #0f172a;
    }
    .bws-floating-layer-divider {
        border-top: 1px solid #e2e8f0;
        margin: 10px 0;
    }

    /* Custom Radio Persis Gambar 2 (Lingkaran Hijau Elegan) */
    .bws-custom-radio {
        appearance: none;
        -webkit-appearance: none;
        width: 15px;
        height: 15px;
        border: 1.8px solid #94a3b8;
        border-radius: 50%;
        outline: none;
        margin: 0;
        cursor: pointer;
        display: grid;
        place-content: center;
        transition: border-color 0.15s ease;
        background: #ffffff;
        flex-shrink: 0;
    }
    .bws-custom-radio::before {
        content: "";
        width: 7px;
        height: 7px;
        border-radius: 50%;
        transform: scale(0);
        transition: 0.12s transform ease-in-out;
        background-color: #15803d;
    }
    .bws-custom-radio:checked {
        border-color: #15803d;
    }
    .bws-custom-radio:checked::before {
        transform: scale(1);
    }

    /* Custom Checkbox Persis Gambar 2 (Kotak Hijau Berisi Centang Putih) */
    .bws-custom-check {
        appearance: none;
        -webkit-appearance: none;
        width: 15px;
        height: 15px;
        border: 1.8px solid #94a3b8;
        border-radius: 4px;
        outline: none;
        margin: 0;
        cursor: pointer;
        display: grid;
        place-content: center;
        transition: all 0.15s ease;
        background: #ffffff;
        flex-shrink: 0;
    }
    .bws-custom-check:checked {
        background-color: #15803d;
        border-color: #15803d;
    }
    .bws-custom-check:checked::after {
        content: "✓";
        font-size: 10px;
        font-weight: 800;
        color: #ffffff;
        line-height: 1;
    }

    /* Tombol Toggle Melayang saat Panel Disembunyikan / Default Tertutup */
    .bws-layer-toggle-btn {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 999;
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.10);
        padding: 7px 14px;
        font-size: 13px;
        font-weight: 700;
        color: #166534; /* Hijau cartographic persis gambar 2 */
        display: inline-flex; /* Default terbuka sebagai tombol pill tertutup */
        align-items: center;
        gap: 8px;
        cursor: pointer;
        outline: none !important;
        transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: auto;
    }
    .bws-layer-toggle-btn i {
        font-size: 14px;
        color: #166534;
    }
    .bws-layer-toggle-btn:hover {
        background: #f8fafc;
        color: #10143a;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }
    .bws-layer-toggle-btn:active {
        transform: scale(0.97);
    }

    /* Status Pill di Header Topbar */
    .bws-topbar-status-badge {
        font-size: 11.5px;
        font-weight: 600;
        color: #475569;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 4px 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .bws-pulse-status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: #16a34a;
        box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.2);
    }
</style>
@endpush

@section('konten')
<div class="bws-qrop-page">

    <!-- ============================================================ -->
    <!-- 1. KARTU BANNER HERO MEWAH (BERSIH & ELEGAN PERSIS QROP)     -->
    <!-- ============================================================ -->
    <div class="bws-hero-card">
        <div class="bws-hero-content">
            <div class="bws-hero-pretitle">
                <span class="bws-dot-hero"></span> DATA SPASIAL SIPDIRA &bull; BWS KALIMANTAN I
            </div>
            <h1 class="bws-hero-title">
                Peta Daerah Irigasi <span class="bws-hero-title-accent">&amp; Jaringan SDA.</span>
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
                <div class="bws-location-main-text">Kalimantan Barat</div>
                <div class="bws-location-sub-text">BWS Kalimantan I Pontianak</div>
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
                    <div class="bws-category-row inactive" onclick="toggleQropCheckbox('chkLayerSaluran')">
                        <div class="bws-icon-pastel box-saluran">
                            <i class="fas fa-route"></i>
                        </div>
                        <div class="bws-category-label-wrap">
                            <div class="bws-category-text">Jaringan Saluran</div>
                            <small class="text-muted">Primer, Sekunder &amp; Tersier</small>
                        </div>
                        <div class="bws-check-wrap">
                            <input type="checkbox" id="chkLayerSaluran" onchange="toggleCategoryLayer('saluran', this.checked); event.stopPropagation();">
                            <span class="bws-green-badge"><i class="fas fa-check"></i></span>
                        </div>
                    </div>

                    <!-- 5. Bangunan Air -->
                    <div class="bws-category-row inactive" onclick="toggleQropCheckbox('chkLayerBangunan')">
                        <div class="bws-icon-pastel box-bangunan">
                            <i class="fas fa-archway"></i>
                        </div>
                        <div class="bws-category-label-wrap">
                            <div class="bws-category-text">Bangunan Air</div>
                            <small class="text-muted">Bendung &amp; Pintu Air</small>
                        </div>
                        <div class="bws-check-wrap">
                            <input type="checkbox" id="chkLayerBangunan" onchange="toggleCategoryLayer('bangunan', this.checked); event.stopPropagation();">
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

            <!-- Header Peta Ramping (Breadcrumb Kiri) -->
            <div class="bws-map-topbar">
                <div class="d-flex align-items-center">
                    <span class="bws-dot-green-qrop"></span>
                    <span class="font-weight-bold text-dark ml-2" style="font-size: 13.5px;">Peta Daerah Irigasi</span>
                    <span class="text-muted mx-2">/</span>
                    <span class="text-muted small font-weight-bold" id="currentBreadcrumbLabel">Seluruh Kalimantan Barat</span>
                </div>
            </div>

            <!-- Card Peta Leaflet -->
            <div class="bws-leaflet-card">
                <div class="bws-map-loading" id="map-loading">
                    <i class="fas fa-spinner fa-spin text-primary"></i>
                    <span class="ml-2 font-weight-bold">Memuat koordinat spasial BWS Kalimantan I...</span>
                </div>
                <div id="map"></div>

                <!-- ══ FLOATING MAP TOOLS (PERSIS REFERENSI: GPS, HOME, FULLSCREEN) ══ -->
                <div class="bws-map-floating-tools">
                    <button type="button" class="bws-map-tool-btn" id="btnMapLocate" onclick="locateUserPosition()" title="Lokasi Saya (Deteksi GPS)">
                        <i class="fas fa-crosshairs" id="iconMapLocate"></i>
                    </button>
                    <button type="button" class="bws-map-tool-btn" id="btnMapHome" onclick="resetToKalbar()" title="Atur Ulang / Pusatkan ke Kalimantan Barat">
                        <i class="fas fa-home"></i>
                    </button>
                    <button type="button" class="bws-map-tool-btn" id="btnMapFullscreen" onclick="toggleMapFullscreen()" title="Layar Penuh Peta">
                        <i class="fas fa-expand" id="iconMapFullscreen"></i>
                    </button>
                </div>

                <!-- ══ TOMBOL TOGGLE PILIHAN LAYER (DEFAULT TERBUKA SEBAGAI TOMBOL TERTUTUP, PERSIS GAMBAR 2) ══ -->
                <button type="button" class="bws-layer-toggle-btn shadow-sm" id="btnOpenLayerPanel" onclick="toggleFloatingLayerPanel(true)" title="Buka Pilihan Layer">
                    <i class="fas fa-layer-group"></i>
                    <span>Pilihan layer</span>
                </button>

                <!-- ══ KARTU MELAYANG PILIHAN LAYER (DEFAULT TERTUTUP/TERSEMBUNYI) ══ -->
                <div class="bws-floating-layer-panel" id="floatingLayerPanel" style="display: none;">
                    <!-- Header Panel: Judul & Tombol Sembunyikan -->
                    <div class="bws-floating-layer-header">
                        <span class="bws-floating-layer-title">Pilihan layer</span>
                        <button type="button" class="bws-floating-layer-hide-btn" onclick="toggleFloatingLayerPanel(false)" title="Sembunyikan panel layer">
                            Sembunyikan
                        </button>
                    </div>

                    <!-- Isi Panel Layer Berkelompok -->
                    <div class="bws-floating-layer-body">
                        <!-- 1. Peta dasar -->
                        <div class="bws-layer-sec-item">
                            <div class="bws-layer-sec-header" onclick="toggleLayerSection('secListBasemap', this)">
                                <span>Peta dasar</span>
                                <i class="fas fa-caret-up bws-chevron-indicator"></i>
                            </div>
                            <div class="bws-layer-sec-list" id="secListBasemap">
                                <label class="bws-layer-item-row">
                                    <input type="radio" name="floating_basemap" value="sat" class="bws-custom-radio" onchange="switchBasemap('sat')">
                                    <span class="bws-layer-item-label">Google Satellite</span>
                                </label>
                                <label class="bws-layer-item-row">
                                    <input type="radio" name="floating_basemap" value="streets" class="bws-custom-radio" onchange="switchBasemap('streets')">
                                    <span class="bws-layer-item-label">Google Streets</span>
                                </label>
                                <label class="bws-layer-item-row">
                                    <input type="radio" name="floating_basemap" value="osm" class="bws-custom-radio" checked onchange="switchBasemap('osm')">
                                    <span class="bws-layer-item-label">OpenStreetMap</span>
                                </label>
                            </div>
                        </div>

                        <div class="bws-floating-layer-divider"></div>

                        <!-- 2. Kategori Layer Irigasi -->
                        <div class="bws-layer-sec-item">
                            <div class="bws-layer-sec-header" onclick="toggleLayerSection('secListIrigasi', this)">
                                <span>Data Spasial Irigasi</span>
                                <i class="fas fa-caret-up bws-chevron-indicator"></i>
                            </div>
                            <div class="bws-layer-sec-list" id="secListIrigasi">
                                <label class="bws-layer-item-row">
                                    <input type="checkbox" id="chkFloatingBaku" class="bws-custom-check" checked onchange="toggleCategoryLayer('baku', this.checked)">
                                    <span class="bws-layer-item-label">Lahan Baku</span>
                                </label>
                                <label class="bws-layer-item-row">
                                    <input type="checkbox" id="chkFloatingFungsional" class="bws-custom-check" checked onchange="toggleCategoryLayer('fungsional', this.checked)">
                                    <span class="bws-layer-item-label">Lahan Fungsional</span>
                                </label>
                                <label class="bws-layer-item-row">
                                    <input type="checkbox" id="chkFloatingPotensial" class="bws-custom-check" checked onchange="toggleCategoryLayer('potensial', this.checked)">
                                    <span class="bws-layer-item-label">Lahan Potensial</span>
                                </label>
                                <label class="bws-layer-item-row">
                                    <input type="checkbox" id="chkFloatingSaluran" class="bws-custom-check" onchange="toggleCategoryLayer('saluran', this.checked)">
                                    <span class="bws-layer-item-label">Jaringan Saluran</span>
                                </label>
                                <label class="bws-layer-item-row">
                                    <input type="checkbox" id="chkFloatingBangunan" class="bws-custom-check" onchange="toggleCategoryLayer('bangunan', this.checked)">
                                    <span class="bws-layer-item-label">Bangunan Air</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
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
        
        <div class="sipdira-about-grid">
            <!-- Kolom Kiri: Kartu Brand Kotak Modern (Persis Card Kiri QROP) -->
            <div class="sipdira-about-brand-col">
                <div class="sipdira-brand-about-card">
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
            <div class="sipdira-about-text-col">
                <div class="sipdira-about-content">
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
        <!-- 4. FOOTER RESMI KEDINASAN KEMENTERIAN PU (MODERN & TERPADU)  -->
        <!-- ============================================================ -->
        <footer class="bws-official-footer mt-5" id="kontak-layanan">
            <style>
                .bws-official-footer {
                    background: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 16px;
                    padding: 36px 36px 20px;
                    box-shadow: 0 2px 14px rgba(15, 23, 42, 0.03);
                    border-top: 3.5px solid #10143a;
                }
                .bws-footer-main-grid {
                    display: grid;
                    grid-template-columns: 1.8fr 1fr 1.3fr;
                    gap: 36px;
                    padding-bottom: 28px;
                    border-bottom: 1px solid #f1f5f9;
                }
                @media (max-width: 992px) {
                    .bws-footer-main-grid {
                        grid-template-columns: 1fr;
                        gap: 24px;
                    }
                    .bws-official-footer {
                        padding: 24px 20px 16px;
                    }
                }
                .bws-footer-heading {
                    font-size: 13.5px;
                    font-weight: 800;
                    color: #0f172a;
                    letter-spacing: -0.2px;
                    margin-bottom: 14px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
                .bws-footer-links-list {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                    display: flex;
                    flex-direction: column;
                    gap: 9px;
                }
                .bws-footer-links-list a {
                    color: #64748b;
                    font-size: 12.5px;
                    text-decoration: none;
                    transition: all 0.15s ease;
                    display: inline-flex;
                    align-items: center;
                    gap: 7px;
                }
                .bws-footer-links-list a:hover {
                    color: #10143a;
                    transform: translateX(2px);
                }
                .bws-footer-contact-item {
                    display: flex;
                    align-items: flex-start;
                    gap: 12px;
                    margin-bottom: 12px;
                    font-size: 12px;
                    color: #475569;
                    line-height: 1.5;
                }
                .bws-footer-contact-icon {
                    width: 30px;
                    height: 30px;
                    border-radius: 8px;
                    background: #f8fafc;
                    border: 1px solid #e2e8f0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                    font-size: 12px;
                }
            </style>

            <div class="bws-footer-main-grid">
                <!-- Kolom 1: Identitas Kelembagaan Resmi BWSK I & Kementerian PU -->
                <div>
                    <div class="d-flex align-items-center mb-3" style="gap: 14px;">
                        <img src="{{ asset('img/logo-pu.svg') }}" alt="Logo PU" style="width: 44px; height: 44px; object-fit: contain;">
                        <div>
                            <div style="font-size: 16px; font-weight: 800; color: #10143a; line-height: 1.2;">
                                SIPDIRA <span style="color: #f59e0b;">SPASIAL</span>
                            </div>
                            <div style="font-size: 11px; font-weight: 700; color: #475569; letter-spacing: 0.3px;">
                                Balai Wilayah Sungai Kalimantan I Pontianak
                            </div>
                        </div>
                    </div>
                    <p style="font-size: 12px; color: #64748b; line-height: 1.6; margin-bottom: 14px;">
                        Sistem Informasi Peta Daerah Irigasi Berbasis Data Spasial di bawah Direktorat Jenderal Sumber Daya Air, Kementerian Pekerjaan Umum. Menyajikan inventarisasi geospasial jaringan irigasi, lahan baku, lahan fungsional, dan infrastruktur air penunjang ketahanan pangan di Provinsi Kalimantan Barat.
                    </p>
                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <span class="badge" style="background: #10143a; color: #fad605; font-size: 10px; font-weight: 700; padding: 4px 9px; border-radius: 6px;">KEMENTERIAN PU</span>
                        <span class="badge" style="background: #f1f5f9; color: #334155; font-size: 10px; font-weight: 700; padding: 4px 9px; border-radius: 6px; border: 1px solid #e2e8f0;">DITJEN SDA</span>
                    </div>
                </div>

                <!-- Kolom 2: Tautan Terkait Kedinasan & Portal Resmi PU -->
                <div>
                    <h5 class="bws-footer-heading">
                        <i class="fas fa-external-link-alt text-primary" style="font-size: 12px;"></i> Tautan Terkait
                    </h5>
                    <ul class="bws-footer-links-list">
                        <li>
                            <a href="https://pu.go.id" target="_blank" rel="noopener noreferrer" title="Portal Resmi Kementerian Pekerjaan Umum">
                                <i class="fas fa-chevron-right text-muted" style="font-size: 9px;"></i> Kementerian Pekerjaan Umum
                                <i class="fas fa-external-link-alt ml-auto text-muted" style="font-size: 8px; opacity: 0.6;"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://sda.pu.go.id" target="_blank" rel="noopener noreferrer" title="Direktorat Jenderal Sumber Daya Air">
                                <i class="fas fa-chevron-right text-muted" style="font-size: 9px;"></i> Ditjen Sumber Daya Air
                                <i class="fas fa-external-link-alt ml-auto text-muted" style="font-size: 8px; opacity: 0.6;"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://data.go.id" target="_blank" rel="noopener noreferrer" title="Satu Data Indonesia">
                                <i class="fas fa-chevron-right text-muted" style="font-size: 9px;"></i> Portal Satu Data Indonesia
                                <i class="fas fa-external-link-alt ml-auto text-muted" style="font-size: 8px; opacity: 0.6;"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.lapor.go.id" target="_blank" rel="noopener noreferrer" title="Layanan Aspirasi dan Pengaduan Online Rakyat">
                                <i class="fas fa-chevron-right text-muted" style="font-size: 9px;"></i> Layanan SP4N LAPOR!
                                <i class="fas fa-external-link-alt ml-auto text-muted" style="font-size: 8px; opacity: 0.6;"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('kebijakan-privasi') }}" title="Kebijakan Privasi dan Tata Kelola Data Spasial">
                                <i class="fas fa-shield-alt text-muted" style="font-size: 10px;"></i> Kebijakan Privasi Data Spasial
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Kolom 3: Kantor Balai & Komunikasi Resmi -->
                <div>
                    <h5 class="bws-footer-heading">
                        <i class="fas fa-building text-warning"></i> Kontak &amp; Kedinasan
                    </h5>
                    <div class="bws-footer-contact-item">
                        <div class="bws-footer-contact-icon text-danger">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <strong class="d-block text-dark">Alamat Kantor</strong>
                            <span>Jl. Achmad Sood No. 6, Kota Pontianak, Kalimantan Barat 78121</span>
                        </div>
                    </div>
                    <div class="bws-footer-contact-item">
                        <div class="bws-footer-contact-icon text-primary">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <strong class="d-block text-dark">Telepon Layanan</strong>
                            <a href="tel:+6281349497711" class="text-secondary text-decoration-none">+62 813-4949-7711</a>
                        </div>
                    </div>
                    <div class="bws-footer-contact-item">
                        <div class="bws-footer-contact-icon text-info">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <strong class="d-block text-dark">Email Kedinasan</strong>
                            <a href="mailto:bwsk1pontianak@pu.go.id" class="text-primary text-decoration-none font-weight-bold">
                                bwsk1pontianak@pu.go.id
                            </a>
                        </div>
                    </div>
                    <div class="mt-3 pt-2">
                        <a href="https://www.instagram.com/pu_sda_kalimantan1/" target="_blank" rel="noopener noreferrer" class="btn btn-sm d-inline-flex align-items-center shadow-xs" style="background: #ffffff; color: #be185d; border: 1.5px solid #f9a8d4; border-radius: 8px; font-size: 11.5px; font-weight: 700; padding: 6px 12px; gap: 7px; text-decoration: none;">
                            <i class="fab fa-instagram"></i>
                            <span>@pu_sda_kalimantan1</span>
                            <i class="fas fa-external-link-alt ml-1" style="font-size: 9px; opacity: 0.7;"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Baris Sub-Footer Hak Cipta -->
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between pt-3" style="font-size: 11.5px; color: #94a3b8; gap: 10px;">
                <div>
                    &copy; {{ date('Y') }} <strong>SIPDIRA SPASIAL</strong> &bull; Balai Wilayah Sungai Kalimantan I Pontianak. Hak Cipta Dilindungi.
                </div>
                <div class="d-flex align-items-center" style="gap: 16px;">
                    <a href="{{ route('kebijakan-privasi') }}" class="text-secondary text-decoration-none" style="font-size: 11.5px;">
                        Kebijakan Privasi
                    </a>
                    <span>&bull;</span>
                    <a href="#map" class="text-secondary text-decoration-none" style="font-size: 11.5px;">
                        Kembali ke Peta &uarr;
                    </a>
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
                        <div class="text-muted small" style="line-height: 1.45;">Jl. Achmad Sood No. 6, Kota Pontianak, Kalimantan Barat 78121</div>
                    </div>
                </div>
                <div class="mb-3 d-flex align-items-start">
                    <div class="mr-3 text-primary mt-1" style="font-size: 16px; width: 22px; text-align: center;"><i class="fas fa-phone-alt text-primary"></i></div>
                    <div>
                        <strong class="d-block text-dark font-weight-bold" style="font-size: 13.5px;">Telepon / WhatsApp Layanan</strong>
                        <div class="text-muted small">+62 813-4949-7711</div>
                    </div>
                </div>
                <div class="mb-3 d-flex align-items-start">
                    <div class="mr-3 text-primary mt-1" style="font-size: 16px; width: 22px; text-align: center;"><i class="fas fa-envelope text-info"></i></div>
                    <div>
                        <strong class="d-block text-dark font-weight-bold" style="font-size: 13.5px;">Email Kedinasan</strong>
                        <div class="text-muted small"><a href="mailto:bwsk1pontianak@pu.go.id" class="text-primary font-weight-bold">bwsk1pontianak@pu.go.id</a></div>
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
                    <a href="https://wa.me/6281349497711" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-success font-weight-bold flex-fill py-2 d-flex align-items-center justify-content-center shadow-xs" style="border-radius: 10px; gap: 6px;">
                        <i class="fab fa-whatsapp font-weight-bold" style="font-size: 15px;"></i> WhatsApp BWS
                    </a>
                    <a href="https://www.instagram.com/pu_sda_kalimantan1/" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger font-weight-bold flex-fill py-2 d-flex align-items-center justify-content-center" style="border-radius: 10px; gap: 6px;">
                        <i class="fab fa-instagram" style="font-size: 15px;"></i> @pu_sda_kalimantan1
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PETUNJUK PENGGUNAAN WEBGIS (Resmi & Profesional) -->
<div class="modal fade" id="modalPanduanAplikasi" tabindex="-1" role="dialog" aria-labelledby="modalPanduanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
            
            <!-- Modal Header (Gaya Resmi Kedinasan PU Navy & Emas) -->
            <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #10143a 0%, #1a1c54 100%); border-bottom: 3.5px solid #f59e0b;">
                <div class="d-flex align-items-center">
                    <div class="mr-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px; background: #ffffff; border-radius: 12px; color: #1a1c54; font-size: 20px; border: 2px solid #fad605;">
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
                <!-- Navigasi Tab Segmented Modern (Executive BWS) -->
                <ul class="nav nav-pills bws-modal-nav-capsule mb-3" id="pills-tab-panduan" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-peta-link" data-toggle="pill" href="#tab-peta" role="tab">
                            <i class="fas fa-layer-group mr-1.5 text-warning"></i> 1. Lapisan &amp; Simbol Peta
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-filter-link" data-toggle="pill" href="#tab-filter" role="tab">
                            <i class="fas fa-search-location mr-1.5 text-primary"></i> 2. Pencarian &amp; Filter
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-popup-link" data-toggle="pill" href="#tab-popup" role="tab">
                            <i class="fas fa-info-circle mr-1.5 text-info"></i> 3. Interaksi &amp; Detail
                        </a>
                    </li>
                </ul>

                <!-- Konten Tab Panduan -->
                <div class="tab-content border rounded p-3 bg-white shadow-sm" id="pills-tabContent" style="min-height: 250px; font-size: 13px; line-height: 1.6; border-color: #e2e8f0 !important; border-radius: 14px !important;">
                    
                    <!-- TAB 1: LAPISAN & SIMBOL PETA (Grid Simetris 3x2) -->
                    <div class="tab-pane fade show active" id="tab-peta" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <div>
                                <div class="font-weight-bold" style="color: #10143a; font-size: 14px;">
                                    <i class="fas fa-layer-group text-warning mr-1"></i> Klasifikasi Data Spasial Irigasi Resmi
                                </div>
                                <small class="text-muted">Simbol geometri dan kode warna standar kartografi BWS Kalimantan I:</small>
                            </div>
                            <span class="badge badge-light px-2 py-1 text-muted border" style="font-size: 11px;">
                                <i class="fas fa-check-circle text-success mr-1"></i> 6 Lapisan Kartografi
                            </span>
                        </div>
                        
                        <div class="row">
                            <!-- 1. Lahan Baku -->
                            <div class="col-md-6 mb-2">
                                <div class="sipdira-legend-card p-2 rounded border bg-light d-flex align-items-center h-100">
                                    <div class="sipdira-swatch-box mr-2 d-flex align-items-center justify-content-center shadow-xs" style="width: 38px; height: 38px; border-radius: 8px; background: rgba(16, 185, 129, 0.15); border: 2px solid #10b981; color: #047857; font-size: 15px; flex-shrink: 0;">
                                        <i class="fas fa-vector-square"></i>
                                    </div>
                                    <div style="flex: 1; line-height: 1.35;">
                                        <div class="d-flex align-items-center justify-content-between mb-0.5">
                                            <strong style="color: #065f46; font-size: 12.5px;">Lahan Baku</strong>
                                            <span class="badge px-1.5 py-0.5 text-white" style="background: #10b981; font-size: 9.5px; border-radius: 4px;">Poligon Hijau</span>
                                        </div>
                                        <span class="text-muted" style="font-size: 11.5px;">Lahan siap tanam dalam kewenangan Daerah Irigasi Rawa (DIR).</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Lahan Fungsional -->
                            <div class="col-md-6 mb-2">
                                <div class="sipdira-legend-card p-2 rounded border bg-light d-flex align-items-center h-100">
                                    <div class="sipdira-swatch-box mr-2 d-flex align-items-center justify-content-center shadow-xs" style="width: 38px; height: 38px; border-radius: 8px; background: rgba(37, 99, 235, 0.15); border: 2px solid #2563eb; color: #1d4ed8; font-size: 15px; flex-shrink: 0;">
                                        <i class="fas fa-water"></i>
                                    </div>
                                    <div style="flex: 1; line-height: 1.35;">
                                        <div class="d-flex align-items-center justify-content-between mb-0.5">
                                            <strong style="color: #1e40af; font-size: 12.5px;">Lahan Fungsional</strong>
                                            <span class="badge px-1.5 py-0.5 text-white" style="background: #2563eb; font-size: 9.5px; border-radius: 4px;">Poligon Biru</span>
                                        </div>
                                        <span class="text-muted" style="font-size: 11.5px;">Lahan aktif beririgasi dan beroperasi rutin O&amp;P BWS.</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Lahan Potensial -->
                            <div class="col-md-6 mb-2">
                                <div class="sipdira-legend-card p-2 rounded border bg-light d-flex align-items-center h-100">
                                    <div class="sipdira-swatch-box mr-2 d-flex align-items-center justify-content-center shadow-xs" style="width: 38px; height: 38px; border-radius: 8px; background: rgba(245, 158, 11, 0.15); border: 2px solid #f59e0b; color: #b45309; font-size: 15px; flex-shrink: 0;">
                                        <i class="fas fa-draw-polygon"></i>
                                    </div>
                                    <div style="flex: 1; line-height: 1.35;">
                                        <div class="d-flex align-items-center justify-content-between mb-0.5">
                                            <strong style="color: #92400e; font-size: 12.5px;">Lahan Potensial</strong>
                                            <span class="badge px-1.5 py-0.5 text-dark font-weight-bold" style="background: #fad605; font-size: 9.5px; border-radius: 4px;">Poligon Kuning</span>
                                        </div>
                                        <span class="text-muted" style="font-size: 11.5px;">Wilayah perencanaan pengembangan potensi irigasi baru.</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Jaringan Saluran -->
                            <div class="col-md-6 mb-2">
                                <div class="sipdira-legend-card p-2 rounded border bg-light d-flex align-items-center h-100">
                                    <div class="sipdira-swatch-box mr-2 d-flex align-items-center justify-content-center shadow-xs" style="width: 38px; height: 38px; border-radius: 8px; background: rgba(6, 182, 212, 0.15); border: 2px solid #06b6d4; color: #0891b2; font-size: 15px; flex-shrink: 0;">
                                        <i class="fas fa-route"></i>
                                    </div>
                                    <div style="flex: 1; line-height: 1.35;">
                                        <div class="d-flex align-items-center justify-content-between mb-0.5">
                                            <strong style="color: #0e7490; font-size: 12.5px;">Jaringan Saluran</strong>
                                            <span class="badge px-1.5 py-0.5 text-white" style="background: #0891b2; font-size: 9.5px; border-radius: 4px;">Garis Cyan</span>
                                        </div>
                                        <span class="text-muted" style="font-size: 11.5px;">Trase garis saluran pembawa primer, sekunder, &amp; tersier.</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 5. Bangunan Air -->
                            <div class="col-md-6 mb-2">
                                <div class="sipdira-legend-card p-2 rounded border bg-light d-flex align-items-center h-100">
                                    <div class="sipdira-swatch-box mr-2 d-flex align-items-center justify-content-center shadow-xs" style="width: 38px; height: 38px; border-radius: 8px; background: rgba(234, 88, 12, 0.15); border: 2px solid #ea580c; color: #c2410c; font-size: 15px; flex-shrink: 0;">
                                        <i class="fas fa-landmark"></i>
                                    </div>
                                    <div style="flex: 1; line-height: 1.35;">
                                        <div class="d-flex align-items-center justify-content-between mb-0.5">
                                            <strong style="color: #9a3412; font-size: 12.5px;">Bangunan Air (SDA)</strong>
                                            <span class="badge px-1.5 py-0.5 text-white" style="background: #ea580c; font-size: 9.5px; border-radius: 4px;">Titik Oranye</span>
                                        </div>
                                        <span class="text-muted" style="font-size: 11.5px;">Titik pintu air, bendung, gorong-gorong, serta pos hidrologi.</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 6. Batas Wilayah Kerja DIR (Penyempurna Grid 3x2) -->
                            <div class="col-md-6 mb-2">
                                <div class="sipdira-legend-card p-2 rounded border bg-light d-flex align-items-center h-100">
                                    <div class="sipdira-swatch-box mr-2 d-flex align-items-center justify-content-center shadow-xs" style="width: 38px; height: 38px; border-radius: 8px; background: rgba(99, 102, 241, 0.15); border: 2px solid #6366f1; color: #4f46e5; font-size: 15px; flex-shrink: 0;">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </div>
                                    <div style="flex: 1; line-height: 1.35;">
                                        <div class="d-flex align-items-center justify-content-between mb-0.5">
                                            <strong style="color: #4338ca; font-size: 12.5px;">Batas Wilayah DIR</strong>
                                            <span class="badge px-1.5 py-0.5 text-white" style="background: #6366f1; font-size: 9.5px; border-radius: 4px;">Garis Ungu</span>
                                        </div>
                                        <span class="text-muted" style="font-size: 11.5px;">Delineasi batas administrasi kewenangan daerah irigasi BWS.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: PENCARIAN & FILTER -->
                    <div class="tab-pane fade" id="tab-filter" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <div>
                                <div class="font-weight-bold" style="color: #10143a; font-size: 14px;">
                                    <i class="fas fa-search-location text-warning mr-1"></i> Alur Kerja Pencarian &amp; Penyaringan Data
                                </div>
                                <small class="text-muted">3 langkah mudah menemukan lokasi dan data teknis irigasi:</small>
                            </div>
                            <span class="badge badge-light px-2 py-1 text-muted border" style="font-size: 11px;">
                                <i class="fas fa-bolt text-warning mr-1"></i> Cepat &amp; Presisi
                            </span>
                        </div>

                        <div class="row">
                            <!-- Langkah 1 -->
                            <div class="col-12 mb-2">
                                <div class="sipdira-step-card p-2.5 rounded border bg-light d-flex align-items-start" style="padding: 10px 14px; border-left: 4px solid #1a1c54 !important;">
                                    <span class="badge mr-3 px-2 py-1 font-weight-bold" style="background: #1a1c54; color: #fad605; font-size: 10.5px; border-radius: 6px; letter-spacing: 0.5px;">LANGKAH 01</span>
                                    <div style="flex: 1;">
                                        <div class="font-weight-bold text-dark" style="font-size: 13px;">
                                             Pencarian Cepat Nama DIR
                                        </div>
                                        <div class="text-secondary" style="font-size: 12px; margin-top: 2px;">
                                            Ketikkan nama daerah irigasi pada kotak <em>"Cari Daerah Irigasi (DIR)"</em> di sidebar kiri (misalnya: <strong>"Pemangkat"</strong>, <strong>"Sungai Daun"</strong>, atau <strong>"Kubu"</strong>), lalu klik hasil yang muncul untuk langsung menuju lokasi sasaran secara otomatis.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Langkah 2 -->
                            <div class="col-12 mb-2">
                                <div class="sipdira-step-card p-2.5 rounded border bg-light d-flex align-items-start" style="padding: 10px 14px; border-left: 4px solid #2563eb !important;">
                                    <span class="badge mr-3 px-2 py-1 font-weight-bold" style="background: #2563eb; color: #ffffff; font-size: 10.5px; border-radius: 6px; letter-spacing: 0.5px;">LANGKAH 02</span>
                                    <div style="flex: 1;">
                                        <div class="font-weight-bold text-dark" style="font-size: 13px;">
                                            Filter Wilayah Administratif
                                        </div>
                                        <div class="text-secondary" style="font-size: 12px; margin-top: 2px;">
                                            Pilih menu dropdown <em>"Wilayah kabupaten"</em> untuk membatasi fokus peta ke wilayah kerja tertentu, seperti <strong>Kabupaten Sambas</strong>, <strong>Kabupaten Kubu Raya</strong>, atau <strong>Kabupaten Ketapang</strong>.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Langkah 3 -->
                            <div class="col-12 mb-1">
                                <div class="sipdira-step-card p-2.5 rounded border bg-light d-flex align-items-start" style="padding: 10px 14px; border-left: 4px solid #10b981 !important;">
                                    <span class="badge mr-3 px-2 py-1 font-weight-bold" style="background: #10b981; color: #ffffff; font-size: 10.5px; border-radius: 6px; letter-spacing: 0.5px;">LANGKAH 03</span>
                                    <div style="flex: 1;">
                                        <div class="font-weight-bold text-dark" style="font-size: 13px;">
                                            Kontrol Visibilitas Layer
                                        </div>
                                        <div class="text-secondary" style="font-size: 12px; margin-top: 2px;">
                                            Gunakan tombol centang hijau pada daftar <em>"Tampilkan kategori"</em> untuk menampilkan atau menyembunyikan lapisan spesifik (Lahan Baku, Lahan Fungsional, Jaringan Saluran, Bangunan Air) sesuai kebutuhan analisis spasial Anda.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: INTERAKSI & DETAIL (Terminologi Resmi & Jelas) -->
                    <div class="tab-pane fade" id="tab-popup" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <div>
                                <div class="font-weight-bold" style="color: #10143a; font-size: 14px;">
                                    <i class="fas fa-mouse-pointer text-warning mr-1"></i> Interaksi Peta &amp; Informasi Geospasial
                                </div>
                                <small class="text-muted">Fitur interaktif navigasi dan pembacaan atribut spasial:</small>
                            </div>
                            <span class="badge badge-light px-2 py-1 text-muted border" style="font-size: 11px;">
                                <i class="fas fa-satellite text-primary mr-1"></i> Interaktif
                            </span>
                        </div>

                        <div class="row">
                            <!-- Fitur 1 -->
                            <div class="col-md-4 mb-2">
                                <div class="sipdira-feature-card p-3 rounded border bg-light h-100 d-flex flex-column text-center">
                                    <div class="mb-2 mx-auto d-flex align-items-center justify-content-center shadow-xs" style="width: 44px; height: 44px; border-radius: 12px; background: rgba(26, 28, 84, 0.1); color: #1a1c54; font-size: 19px;">
                                        <i class="fas fa-crosshairs"></i>
                                    </div>
                                    <strong style="color: #10143a; font-size: 13px; margin-bottom: 4px;">Navigasi &amp; Zoom Fokus</strong>
                                    <p class="text-muted mb-0" style="font-size: 11.5px; line-height: 1.45;">
                                        Klik objek mana pun di peta untuk animasi kamera meluncur otomatis presisi ke titik koordinat sasaran.
                                    </p>
                                </div>
                            </div>

                            <!-- Fitur 2 -->
                            <div class="col-md-4 mb-2">
                                <div class="sipdira-feature-card p-3 rounded border bg-light h-100 d-flex flex-column text-center">
                                    <div class="mb-2 mx-auto d-flex align-items-center justify-content-center shadow-xs" style="width: 44px; height: 44px; border-radius: 12px; background: rgba(37, 99, 235, 0.1); color: #2563eb; font-size: 19px;">
                                        <i class="fas fa-window-restore"></i>
                                    </div>
                                    <strong style="color: #1e40af; font-size: 13px; margin-bottom: 4px;">Informasi Atribut Spasial</strong>
                                    <p class="text-muted mb-0" style="font-size: 11.5px; line-height: 1.45;">
                                        Kartu pop-up informatif berisi Nama DIR, luasan hektare (Ha), nomenklatur saluran, dan data teknis resmi Buku DIR BWS.
                                    </p>
                                </div>
                            </div>

                            <!-- Fitur 3 -->
                            <div class="col-md-4 mb-2">
                                <div class="sipdira-feature-card p-3 rounded border bg-light h-100 d-flex flex-column text-center">
                                    <div class="mb-2 mx-auto d-flex align-items-center justify-content-center shadow-xs" style="width: 44px; height: 44px; border-radius: 12px; background: rgba(245, 158, 11, 0.15); color: #b45309; font-size: 19px;">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <strong style="color: #92400e; font-size: 13px; margin-bottom: 4px;">Peta Dasar &amp; Citra Satelit</strong>
                                    <p class="text-muted mb-0" style="font-size: 11.5px; line-height: 1.45;">
                                        Gunakan kontrol zoom (+/-) atau beralih ke layer <strong>Satelit</strong> di pojok kanan atas untuk analisis tutupan lahan nyata.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tips Navigasi Praktis (Executive Clean) -->
                <div class="p-2.5 px-3 rounded mt-3 d-flex align-items-center" style="background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #f59e0b; font-size: 12.5px; color: #1e293b;">
                    <div class="mr-2.5 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; border-radius: 6px; background: #fef3c7; color: #d97706; flex-shrink: 0;">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div style="line-height: 1.4;">
                        <strong>Tips Praktis:</strong> Gunakan tombol <strong>Layar Penuh</strong> (<i class="fas fa-expand mx-0.5 text-muted"></i>) pada pojok kanan atas navbar untuk melihat sebaran jaringan irigasi secara maksimal dan leluasa.
                    </div>
                </div>
            </div>

            <!-- Modal Footer dengan Navigasi Wizard Dinamis -->
            <div class="modal-footer bg-light py-2.5 px-4 d-flex align-items-center justify-content-between">
                <small class="text-muted" style="font-size: 11.5px;">
                    <i class="fas fa-shield-alt text-success mr-1"></i> Data Spasial Resmi SISDA BWS Kalimantan I
                </small>
                <div class="d-flex align-items-center" style="gap: 8px;">
                    <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold px-3 d-none" id="btnPanduanPrev" style="border-radius: 8px; font-size: 12px; border-color: #cbd5e1; color: #475569;">
                        <i class="fas fa-arrow-left mr-1"></i> Sebelumnya
                    </button>
                    <button type="button" class="btn text-white btn-sm font-weight-bold px-3 shadow-sm" id="btnPanduanNext" style="background: linear-gradient(135deg, #10143a 0%, #1a1c54 100%); border: 1.5px solid #fad605; border-radius: 8px; font-size: 12px;">
                        <span id="btnPanduanNextText">Lanjut: Pencarian</span> <i class="fas fa-arrow-right ml-1 text-warning" id="btnPanduanNextIcon"></i>
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
                $('#btnPanduanNextText').text('Lanjut: Pencarian');
                $('#btnPanduanNextIcon').attr('class', 'fas fa-arrow-right ml-1 text-warning');
                $('#btnPanduanNext').data('action', 'next');
            } else if (step === 1) {
                $('#btnPanduanPrev').removeClass('d-none');
                $('#btnPanduanNextText').text('Lanjut: Interaksi');
                $('#btnPanduanNextIcon').attr('class', 'fas fa-arrow-right ml-1 text-warning');
                $('#btnPanduanNext').data('action', 'next');
            } else if (step === 2) {
                $('#btnPanduanPrev').removeClass('d-none');
                $('#btnPanduanNextText').text('Selesai & Jelajahi Peta');
                $('#btnPanduanNextIcon').attr('class', 'fas fa-check ml-1 text-warning');
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

@extends('admin.layout')

@section('title', 'Uji Coba & Pratinjau Peta Spasial Admin — BWS Kalimantan I')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Admin</a>
    <span class="mx-2 text-muted">/</span>
    <a href="{{ route('admin.kelola-gis') }}" class="text-decoration-none">Master Data</a>
    <span class="mx-2 text-muted">/</span>
    <span class="text-dark font-weight-bold">Uji Coba Peta Spasial</span>
@endsection

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .studio-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .studio-sidebar {
        height: 720px;
        display: flex;
        flex-direction: column;
        border-right: 1px solid #e2e8f0;
    }

    .studio-sidebar-header {
        padding: 16px 18px 12px;
        border-bottom: 1px solid #f1f5f9;
        background: #ffffff;
    }

    .studio-sidebar-body {
        flex: 1;
        overflow-y: auto;
        padding: 12px 14px;
        background: #f8fafc;
    }

    .studio-sidebar-body::-webkit-scrollbar {
        width: 5px;
    }
    .studio-sidebar-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 8px;
    }

    .studio-file-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 12px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
        position: relative;
    }

    .studio-file-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.08);
        transform: translateY(-1px);
    }

    .studio-file-card.inactive {
        opacity: 0.55;
        background: #f1f5f9;
    }

    .category-pill {
        font-size: 0.68rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 10px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .cat-baku       { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .cat-fungsional { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .cat-potensial  { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .cat-saluran    { background: #f0f9ff; color: #0284c7; border: 1px solid #bae6fd; }
    .cat-bangunan   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .cat-ws_kapuas  { background: #f5f3ff; color: #7c3aed; border: 1px solid #ddd6fe; }
    .cat-custom     { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

    .studio-map-area {
        height: 720px;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .studio-map-topbar {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
        z-index: 10;
    }

    #adminPreviewMap {
        flex: 1;
        width: 100%;
        height: 100%;
        background: #e2e8f0;
    }

    .nimbul-badge {
        font-size: 0.7rem;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .nimbul-success {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
    }
    .nimbul-muted {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #cbd5e1;
    }

    .focus-btn {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 8px;
        transition: all 0.15s ease;
    }
    .focus-btn:hover {
        background: #1d4ed8;
        color: #ffffff;
        border-color: #1d4ed8;
    }

    .layer-filter-btn {
        font-size: 0.74rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 16px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .layer-filter-btn.active {
        background: #1d4ed8;
        color: #ffffff;
        border-color: #1d4ed8;
    }

    /* Popup Tabel Properti Leaflet */
    .leaflet-popup-content-wrapper {
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.18);
        border: 1px solid #cbd5e1;
        padding: 0;
        overflow: hidden;
    }
    .leaflet-popup-content {
        margin: 0;
        line-height: 1.4;
    }
    .popup-header {
        background: #1d4ed8;
        color: #ffffff;
        padding: 10px 14px;
        font-weight: 700;
        font-size: 13px;
    }
    .popup-body {
        padding: 10px 14px;
        max-height: 240px;
        overflow-y: auto;
        font-size: 11.5px;
    }
    .popup-table td {
        padding: 4px 6px;
        border-bottom: 1px solid #f1f5f9;
    }
</style>
@endpush

@section('content')
<div class="anim-fade-in">

    <!-- Header & Judul Halaman -->
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-3" style="gap: 12px;">
        <div>
            <div class="d-flex align-items-center mb-1">
                <span class="badge badge-warning text-dark font-weight-bold px-2.5 py-1 mr-2" style="font-size: 0.75rem; border-radius: 6px;">
                    <i class="fas fa-vial mr-1"></i> Laboratorium Spasial Admin
                </span>
                <h4 class="font-weight-bold text-dark mb-0" style="letter-spacing: -0.3px;">
                    Uji Coba &amp; Pratinjau Peta Spasial
                </h4>
            </div>
            <p class="text-muted small mb-0">
                Verifikasi langsung data geospasial yang telah diunggah: periksa kemunculan poligon lahan, jaringan saluran, dan bangunan air secara akurat pada koordinat Kalimantan Barat.
            </p>
        </div>

        <div class="d-flex align-items-center" style="gap: 8px;">
            <a href="{{ route('admin.kelola-gis') }}" class="btn btn-outline-secondary btn-sm font-weight-bold px-3 shadow-xs" style="border-radius: 20px; height: 36px; display: inline-flex; align-items: center;">
                <i class="fas fa-layer-group mr-1.5"></i> Kelola Data GIS
            </a>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold px-3 shadow-sm text-white" style="border-radius: 20px; height: 36px; background: #1d4ed8; border: none; display: inline-flex; align-items: center;" data-toggle="modal" data-target="#modalUploadStudio">
                <i class="fas fa-cloud-upload-alt mr-1.5"></i> Upload File Baru
            </button>
            <a href="{{ route('peta') }}" target="_blank" class="btn btn-light border btn-sm font-weight-bold px-3 shadow-xs" style="border-radius: 20px; height: 36px; display: inline-flex; align-items: center;" title="Buka Halaman Publik WebGIS untuk Melihat Versi Pengunjung">
                <i class="fas fa-external-link-alt mr-1.5 text-muted"></i> Web Publik
            </a>
        </div>
    </div>

    <!-- KPI Ringkasan Status Pengujian -->
    <div class="row mb-3" style="margin-left: -6px; margin-right: -6px;">
        <div class="col-6 col-md-3 px-1.5 mb-2 mb-md-0">
            <div class="p-2.5 rounded border bg-white shadow-xs d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-2.5" style="width: 38px; height: 38px; background: #eff6ff; color: #1d4ed8; font-size: 1rem;">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase;">Total File Spasial</div>
                    <div class="font-weight-bold text-dark" style="font-size: 1.15rem; line-height: 1.2;">{{ count($allFiles) }} <span class="text-muted" style="font-size: 0.75rem;">Berkas</span></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 px-1.5 mb-2 mb-md-0">
            <div class="p-2.5 rounded border bg-white shadow-xs d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-2.5" style="width: 38px; height: 38px; background: #ecfdf5; color: #059669; font-size: 1rem;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase;">Layer Nimbul di Peta</div>
                    <div class="font-weight-bold text-success" id="counterLayerNimbul" style="font-size: 1.15rem; line-height: 1.2;">0 <span class="text-muted" style="font-size: 0.75rem;">Aktif</span></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 px-1.5 mb-2 mb-md-0">
            <div class="p-2.5 rounded border bg-white shadow-xs d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-2.5" style="width: 38px; height: 38px; background: #fffbeb; color: #d97706; font-size: 1rem;">
                    <i class="fas fa-database"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase;">Kapasitas Geospasial</div>
                    <div class="font-weight-bold text-dark" style="font-size: 1.15rem; line-height: 1.2;">{{ $stats['total_size'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 px-1.5">
            <div class="p-2.5 rounded border bg-white shadow-xs d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-2.5" style="width: 38px; height: 38px; background: #fdf2f8; color: #db2777; font-size: 1rem;">
                    <i class="fas fa-map-pin"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase;">Titik Pos DIR BWS</div>
                    <div class="font-weight-bold text-dark" style="font-size: 1.15rem; line-height: 1.2;">{{ count($lokasi) }} <span class="text-muted" style="font-size: 0.75rem;">Lokasi</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wadah Studio Uji Coba: 2 Kolom (Sidebar Kontrol Layer + Peta) -->
    <div class="studio-card">
        <div class="row no-gutters">

            <!-- ── PANEL KIRI: DAFTAR FILE & CONTROLLER LAYER (380px) ── -->
            <div class="col-lg-4 col-xl-4 studio-sidebar">
                
                <!-- Header Panel -->
                <div class="studio-sidebar-header">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                            <i class="fas fa-layer-group text-primary mr-1"></i> Pengujian Layer Data
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary btn-xs py-0.5 px-2" onclick="toggleAllLayers(true)" title="Tampilkan Semua File">
                                Semua
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-xs py-0.5 px-2" onclick="toggleAllLayers(false)" title="Sembunyikan Semua File">
                                Bersihkan
                            </button>
                        </div>
                    </div>

                    <!-- Search Input File -->
                    <div class="position-relative mb-2">
                        <input type="text" 
                               id="inputSearchStudio" 
                               class="form-control form-control-sm" 
                               placeholder="Cari nama file GeoJSON..." 
                               onkeyup="filterStudioFiles(this.value)"
                               style="border-radius: 8px; padding-left: 30px; font-size: 0.8rem;">
                        <i class="fas fa-search position-absolute text-muted" style="left: 10px; top: 9px; font-size: 0.75rem;"></i>
                    </div>

                    <!-- Filter Kategori Tabs -->
                    <div class="d-flex align-items-center" style="gap: 5px; overflow-x: auto; padding-bottom: 2px;">
                        <button type="button" class="layer-filter-btn active" onclick="filterByCategory('all', this)">Semua</button>
                        <button type="button" class="layer-filter-btn" onclick="filterByCategory('baku', this)">Baku</button>
                        <button type="button" class="layer-filter-btn" onclick="filterByCategory('fungsional', this)">Fungsional</button>
                        <button type="button" class="layer-filter-btn" onclick="filterByCategory('potensial', this)">Potensial</button>
                        <button type="button" class="layer-filter-btn" onclick="filterByCategory('saluran', this)">Saluran</button>
                        <button type="button" class="layer-filter-btn" onclick="filterByCategory('bangunan', this)">Bangunan</button>
                        <button type="button" class="layer-filter-btn" onclick="filterByCategory('custom', this)">Kustom</button>
                    </div>
                </div>

                <!-- Body Panel: List Card File -->
                <div class="studio-sidebar-body" id="studioFileListContainer">
                    @forelse($allFiles as $file)
                    <div class="studio-file-card" data-filename="{{ $file['name'] }}" data-category="{{ $file['category'] }}">
                        <div class="d-flex align-items-start justify-content-between mb-1">
                            <div class="d-flex align-items-center" style="min-width: 0; flex: 1;">
                                <input type="checkbox" 
                                       class="chk-layer mr-2" 
                                       id="chk_{{ md5($file['name']) }}" 
                                       checked 
                                       onchange="toggleLayer('{{ $file['name'] }}', this.checked)"
                                       style="width: 16px; height: 16px; accent-color: #1d4ed8; cursor: pointer; flex-shrink: 0;">
                                <div class="text-truncate mr-2">
                                    <div class="font-weight-bold text-dark text-truncate" style="font-size: 0.82rem;" title="{{ $file['name'] }}">
                                        {{ $file['name'] }}
                                    </div>
                                    <small class="text-muted">{{ $file['size'] }} &bull; {{ $file['date'] }}</small>
                                </div>
                            </div>
                            <span class="category-pill cat-{{ $file['category'] }} flex-shrink-0">
                                {{ strtoupper($file['category']) }}
                            </span>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-2 pt-1.5 border-top">
                            <span class="nimbul-badge nimbul-success" id="status_{{ md5($file['name']) }}">
                                <i class="fas fa-spinner fa-spin"></i> Memuat...
                            </span>
                            <button type="button" class="focus-btn" onclick="focusLayer('{{ $file['name'] }}')" title="Arahkan peta langsung ke koordinat file ini">
                                <i class="fas fa-crosshairs mr-1"></i> Fokuskan
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-folder-open fa-2x mb-2 text-secondary opacity-50"></i>
                        <p class="mb-0 small">Belum ada file GeoJSON di server. Silakan upload file baru.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- ── PANEL KANAN: STUDIO PETA LEAFLET INTERAKTIF ── -->
            <div class="col-lg-8 col-xl-8 studio-map-area">

                <!-- Map Studio Topbar -->
                <div class="studio-map-topbar">
                    <div class="d-flex align-items-center">
                        <span class="badge badge-success px-2 py-1 mr-2" style="border-radius: 12px; font-size: 0.72rem;">
                            <span class="spinner-grow spinner-grow-sm mr-1" role="status" aria-hidden="true" style="width: 7px; height: 7px;"></span> LIVE MAP
                        </span>
                        <span class="font-weight-bold text-dark small d-none d-sm-inline">
                            Koordinat Kursor:
                        </span>
                        <code class="ml-1 px-1.5 py-0.5 rounded bg-light border text-primary small font-weight-bold" id="cursorCoordDisplay">
                            -0.15000, 109.85000
                        </code>
                    </div>

                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <!-- Basemap Switcher -->
                        <div class="btn-group btn-group-sm shadow-xs" role="group">
                            <button type="button" class="btn btn-primary btn-sm active" id="btnAdminOsm" onclick="switchAdminBasemap('osm')">
                                <i class="fas fa-map mr-1"></i> Peta
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btnAdminSat" onclick="switchAdminBasemap('sat')">
                                <i class="fas fa-satellite mr-1"></i> Satelit
                            </button>
                        </div>

                        <!-- Reset Zoom -->
                        <button type="button" class="btn btn-outline-secondary btn-sm shadow-xs" onclick="resetAdminKalbar()" title="Kembalikan zoom ke seluruh Kalimantan Barat">
                            <i class="fas fa-home"></i>
                        </button>
                    </div>
                </div>

                <!-- Leaflet Map Container -->
                <div id="adminPreviewMap"></div>

                <!-- Notification Toast Inside Map -->
                <div id="mapStudioToast" class="position-absolute shadow-lg border" style="bottom: 20px; right: 20px; z-index: 999; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); border-radius: 10px; padding: 8px 14px; font-size: 0.78rem; display: none;">
                    <i class="fas fa-info-circle text-primary mr-1.5"></i>
                    <span id="mapStudioToastMsg" class="font-weight-bold text-dark"></span>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- MODAL UPLOAD CEPAT DI DALAM STUDIO -->
<div class="modal fade" id="modalUploadStudio" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header py-3 px-4 text-white" style="background: #1d4ed8;">
                <h5 class="modal-title font-weight-bold" style="font-size: 1.05rem;">
                    <i class="fas fa-cloud-upload-alt mr-2"></i> Upload File Geospasial Baru
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 small border-0 mb-3" style="background: #eff6ff; color: #1e40af; border-radius: 8px;">
                        <i class="fas fa-info-circle mr-1"></i> File yang diupload akan langsung tersimpan di server dan siap diverifikasi di peta ini.
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small mb-1">Pilih Kategori Spasial:</label>
                        <select name="kategori" class="form-control form-control-sm" style="border-radius: 8px;">
                            <option value="auto">Otomatis (Sesuai nama file)</option>
                            <option value="baku">Lahan Baku</option>
                            <option value="fungsional">Lahan Fungsional</option>
                            <option value="potensial">Lahan Potensial</option>
                            <option value="saluran">Jaringan Saluran</option>
                            <option value="bangunan">Bangunan Air</option>
                            <option value="custom">Layer Kustom</option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label class="font-weight-bold text-dark small mb-1">Pilih Berkas (.zip Shapefile atau .geojson):</label>
                        <input type="file" name="file_geojson[]" class="form-control-file border p-2 rounded" multiple required accept=".zip,.geojson,.json" style="font-size: 0.82rem;">
                        <small class="text-muted">Mendukung Shapefile (.zip) atau GeoJSON (.geojson/.json). Maksimal 100MB per file.</small>
                    </div>
                </div>
                <div class="modal-footer py-2.5 px-4 bg-light border-top">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm font-weight-bold px-4" style="border-radius: 8px; background: #1d4ed8; border: none;">
                        <i class="fas fa-cloud-upload-alt mr-1"></i> Unggah Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet & Proj4js JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.9.0/proj4.js"></script>

<script>
    // Inisialisasi Peta Leaflet Studio Admin
    var defaultKalbar = [-0.15, 109.85];
    var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap | BWS Kalimantan I'
    });

    var satLayer = L.tileLayer('https://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '&copy; Google Maps Satelit | BWS Kalimantan I'
    });

    var adminMap = L.map('adminPreviewMap', {
        center: defaultKalbar,
        zoom: 8,
        layers: [osmLayer],
        zoomControl: true
    });

    // Skala Peta
    L.control.scale({ imperial: false, metric: true, position: 'bottomleft' }).addTo(adminMap);

    // Kursor Koordinat
    adminMap.on('mousemove', function(e) {
        var coordEl = document.getElementById('cursorCoordDisplay');
        if (coordEl) {
            coordEl.innerText = e.latlng.lat.toFixed(5) + ', ' + e.latlng.lng.toFixed(5);
        }
    });

    function switchAdminBasemap(type) {
        var btnOsm = document.getElementById('btnAdminOsm');
        var btnSat = document.getElementById('btnAdminSat');
        if (type === 'sat') {
            adminMap.removeLayer(osmLayer);
            adminMap.addLayer(satLayer);
            btnSat.className = 'btn btn-primary btn-sm active';
            btnOsm.className = 'btn btn-outline-primary btn-sm';
        } else {
            adminMap.removeLayer(satLayer);
            adminMap.addLayer(osmLayer);
            btnOsm.className = 'btn btn-primary btn-sm active';
            btnSat.className = 'btn btn-outline-primary btn-sm';
        }
    }

    function resetAdminKalbar() {
        adminMap.flyTo(defaultKalbar, 8, { duration: 1 });
        showStudioToast('Tampilan kamera diatur ulang ke seluruh Kalimantan Barat');
    }

    // Koleksi Layer Leaflet per File
    var layerCollection = {};
    var fileDataList = {!! json_encode($allFiles) !!};
    var nimbulCount = 0;

    // Palet Warna Kategori
    function getCategoryStyle(category) {
        switch(category) {
            case 'baku':
                return { color: '#059669', fillColor: '#10b981', fillOpacity: 0.45, weight: 2 };
            case 'fungsional':
                return { color: '#1d4ed8', fillColor: '#3b82f6', fillOpacity: 0.45, weight: 2 };
            case 'potensial':
                return { color: '#d97706', fillColor: '#f59e0b', fillOpacity: 0.45, weight: 2 };
            case 'saluran':
                return { color: '#0284c7', weight: 3, opacity: 0.85 };
            case 'bangunan':
                return { color: '#dc2626', fillColor: '#f87171', fillOpacity: 0.9, radius: 6, weight: 2 };
            case 'ws_kapuas':
                return { color: '#7c3aed', fillColor: '#8b5cf6', fillOpacity: 0.2, weight: 1.5 };
            default:
                return { color: '#475569', fillColor: '#94a3b8', fillOpacity: 0.4, weight: 2 };
        }
    }

    // Memuat File GeoJSON ke Peta secara Asinkron
    function loadGeojsonFile(fileObj) {
        var fileName = fileObj.name;
        var category = fileObj.category;
        var badgeId = 'status_' + md5(fileName);
        var badgeEl = document.getElementById(badgeId);

        fetch('/geojson/' + encodeURIComponent(fileName))
            .then(function(res) {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(function(data) {
                if (!data || !data.features || data.features.length === 0) {
                    if (badgeEl) {
                        badgeEl.className = 'nimbul-badge nimbul-muted';
                        badgeEl.innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i> Kosong (0 Fitur)';
                    }
                    return;
                }

                var style = getCategoryStyle(category);
                var geoLayer = L.geoJSON(data, {
                    style: function() { return style; },
                    pointToLayer: function(feature, latlng) {
                        return L.circleMarker(latlng, style);
                    },
                    onEachFeature: function(feature, layer) {
                        var props = feature.properties || {};
                        var popupHtml = '<div class="popup-header"><i class="fas fa-layer-group mr-1.5"></i> ' + (props.NAMA_DIR || props.Nama_DIR || props.nama || fileName) + '</div>';
                        popupHtml += '<div class="popup-body"><table class="table table-sm popup-table mb-0">';
                        for (var key in props) {
                            if (props.hasOwnProperty(key)) {
                                popupHtml += '<tr><td class="font-weight-bold text-muted" style="width:40%;">' + key + '</td><td>' + props[key] + '</td></tr>';
                            }
                        }
                        popupHtml += '</table></div>';
                        layer.bindPopup(popupHtml, { maxWidth: 360 });

                        // Hover effect
                        layer.on('mouseover', function() {
                            if (typeof layer.setStyle === 'function') {
                                layer.setStyle({ weight: (style.weight || 2) + 2, fillOpacity: 0.7 });
                            }
                        });
                        layer.on('mouseout', function() {
                            if (typeof layer.setStyle === 'function') {
                                layer.setStyle(style);
                            }
                        });
                    }
                });

                adminMap.addLayer(geoLayer);
                layerCollection[fileName] = {
                    layer: geoLayer,
                    bounds: geoLayer.getBounds(),
                    featureCount: data.features.length
                };

                nimbulCount++;
                updateNimbulCounter();

                if (badgeEl) {
                    badgeEl.className = 'nimbul-badge nimbul-success';
                    badgeEl.innerHTML = '<i class="fas fa-check-circle"></i> Nimbul (' + data.features.length + ' fitur)';
                }
            })
            .catch(function(err) {
                console.warn('Gagal memuat GeoJSON ' + fileName, err);
                if (badgeEl) {
                    badgeEl.className = 'nimbul-badge text-danger border border-danger';
                    badgeEl.innerHTML = '<i class="fas fa-times-circle"></i> Gagal Muat';
                }
            });
    }

    function updateNimbulCounter() {
        var el = document.getElementById('counterLayerNimbul');
        if (el) {
            el.innerHTML = nimbulCount + ' <span class="text-muted" style="font-size:0.75rem;">Aktif</span>';
        }
    }

    // Toggle Muncul / Sembunyikan Layer
    function toggleLayer(fileName, checked) {
        var card = document.querySelector('[data-filename="' + fileName + '"]');
        if (card) {
            if (checked) card.classList.remove('inactive');
            else card.classList.add('inactive');
        }

        var entry = layerCollection[fileName];
        if (!entry) return;

        if (checked) {
            adminMap.addLayer(entry.layer);
            nimbulCount++;
        } else {
            adminMap.removeLayer(entry.layer);
            nimbulCount = Math.max(0, nimbulCount - 1);
        }
        updateNimbulCounter();
    }

    // Fokuskan / Zoom Langsung ke Layer Tertentu
    function focusLayer(fileName) {
        var entry = layerCollection[fileName];
        if (entry && entry.bounds && entry.bounds.isValid()) {
            adminMap.fitBounds(entry.bounds, { padding: [40, 40], maxZoom: 15, duration: 1.2 });
            showStudioToast('Peta difokuskan ke koordinat: ' + fileName + ' (' + entry.featureCount + ' fitur)');
        } else {
            showStudioToast('Layer ' + fileName + ' belum siap atau tidak memiliki koordinat valid.');
        }
    }

    // Toggle Semua Layer Sekaligus
    function toggleAllLayers(show) {
        var checkboxes = document.querySelectorAll('.chk-layer');
        checkboxes.forEach(function(chk) {
            chk.checked = show;
        });

        for (var fName in layerCollection) {
            if (layerCollection.hasOwnProperty(fName)) {
                var entry = layerCollection[fName];
                if (show) {
                    adminMap.addLayer(entry.layer);
                } else {
                    adminMap.removeLayer(entry.layer);
                }
            }
        }

        nimbulCount = show ? Object.keys(layerCollection).length : 0;
        updateNimbulCounter();

        var cards = document.querySelectorAll('.studio-file-card');
        cards.forEach(function(c) {
            if (show) c.classList.remove('inactive');
            else c.classList.add('inactive');
        });

        showStudioToast(show ? 'Semua layer diaktifkan di peta' : 'Semua layer dinonaktifkan');
    }

    // Filter Pencarian Teks
    function filterStudioFiles(q) {
        var keyword = q.toLowerCase().trim();
        var cards = document.querySelectorAll('.studio-file-card');
        cards.forEach(function(card) {
            var fName = (card.getAttribute('data-filename') || '').toLowerCase();
            if (!keyword || fName.includes(keyword)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Filter Kategori Tab
    function filterByCategory(cat, btnEl) {
        var buttons = document.querySelectorAll('.layer-filter-btn');
        buttons.forEach(function(b) { b.classList.remove('active'); });
        btnEl.classList.add('active');

        var cards = document.querySelectorAll('.studio-file-card');
        cards.forEach(function(card) {
            var cCat = card.getAttribute('data-category') || '';
            if (cat === 'all' || cCat === cat) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function showStudioToast(msg) {
        var toast = document.getElementById('mapStudioToast');
        var toastMsg = document.getElementById('mapStudioToastMsg');
        if (toast && toastMsg) {
            toastMsg.innerText = msg;
            toast.style.display = 'block';
            clearTimeout(window._toastTimer);
            window._toastTimer = setTimeout(function() {
                toast.style.display = 'none';
            }, 3500);
        }
    }

    // Helper MD5 Sederhana untuk ID Elemen
    function md5(string) {
        var hash = 0;
        for (var i = 0; i < string.length; i++) {
            var char = string.charCodeAt(i);
            hash = ((hash << 5) - hash) + char;
            hash = hash & hash;
        }
        return 'h' + Math.abs(hash);
    }

    // Memuat seluruh file saat halaman siap
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            adminMap.invalidateSize();
            fileDataList.forEach(function(f) {
                loadGeojsonFile(f);
            });
        }, 300);
    });
</script>
@endpush


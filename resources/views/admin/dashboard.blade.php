@extends('layout.master')

@section('title', 'Dashboard Admin > Peta Daerah Irigasi (DIR) — BWS Kalimantan I')
@section('hide_footer', true)

@push('styles')
<style>
    /* ── TATA LETAK UTAMA WEBGIS ADMIN SESUAI GAMBAR ── */
    .webgis-container {
        display: flex;
        flex-direction: row;
        width: 100%;
        height: calc(100vh - 100px);
        min-height: 600px;
        gap: 20px;
    }

    /* ── SIDEBAR KIRI ── */
    .gis-sidebar {
        width: 270px;
        min-width: 270px;
        max-width: 270px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .gis-form-group {
        display: flex;
        flex-direction: column;
    }

    .gis-form-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }

    .gis-select, .gis-input {
        background-color: #ffffff;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 0.88rem;
        color: #1e293b;
        font-weight: 500;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .gis-select:focus, .gis-input:focus {
        border-color: #2563eb;
        outline: none;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    /* Tombol Kelola File GIS */
    .btn-kelola-gis {
        background: #ffffff;
        border: 1px solid #3b82f6;
        color: #2563eb;
        font-weight: 600;
        font-size: 0.88rem;
        padding: 10px 14px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(37, 99, 235, 0.08);
    }

    .btn-kelola-gis:hover {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #2563eb;
        text-decoration: none;
    }

    .btn-kelola-gis .badge-count {
        background-color: #2563eb;
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 12px;
    }

    /* Tombol Upload GeoJSON */
    .btn-upload-geojson {
        background-color: #2563eb;
        border: 1px solid #2563eb;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.92rem;
        padding: 11px 16px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
    }

    .btn-upload-geojson:hover {
        background-color: #1d4ed8;
        border-color: #1d4ed8;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
    }

    /* ── KONTEN AREA KANAN (MAP) ── */
    .gis-content-area {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    /* Breadcrumb Header */
    .gis-breadcrumb-title {
        font-size: 1.25rem;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        line-height: 1.3;
    }

    .gis-breadcrumb-title .crumb-parent {
        color: #64748b;
        font-weight: 600;
    }

    .gis-breadcrumb-title .crumb-sep {
        color: #94a3b8;
        margin: 0 8px;
        font-size: 0.95rem;
    }

    .gis-breadcrumb-title .crumb-active {
        color: #0f172a;
        font-weight: 800;
    }

    /* Map Card Wrapper */
    .gis-map-card {
        flex: 1;
        width: 100%;
        background: #e2e8f0;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        position: relative;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }

    #map {
        width: 100%;
        height: 100%;
        min-height: 520px;
        z-index: 1;
    }

    /* ── FLOATING OVERLAYS PADA PETA ── */

    /* 1. Floating Layer Control (Kanan Atas) dengan Fitur Minimize */
    .gis-layer-control-card {
        position: absolute;
        top: 14px;
        right: 14px;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 12px;
        padding: 12px 14px;
        z-index: 1000;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        width: 250px;
        font-size: 0.82rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .gis-layer-control-card.is-minimized {
        width: auto !important;
        min-width: 135px;
        padding: 8px 12px !important;
        border-radius: 20px;
        cursor: pointer;
    }

    .gis-layer-control-card.is-minimized #layerControlBody {
        display: none !important;
    }

    .gis-layer-control-card.is-minimized .layer-control-header {
        margin-bottom: 0 !important;
    }

    .layer-control-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
        cursor: pointer;
        user-select: none;
    }

    .btn-minimize-icon {
        background: #f1f5f9;
        border: none;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        font-size: 10px;
        transition: all 0.2s ease;
        padding: 0;
    }

    .btn-minimize-icon:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .layer-radio-item, .layer-checkbox-item {
        display: flex;
        align-items: center;
        margin-bottom: 6px;
        cursor: pointer;
        font-weight: 500;
        color: #334155;
        user-select: none;
    }

    .layer-radio-item input[type="radio"],
    .layer-checkbox-item input[type="checkbox"] {
        margin-right: 8px;
        width: 16px;
        height: 16px;
        accent-color: #2563eb;
        cursor: pointer;
    }

    .layer-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 8px 0;
    }

    /* 2. Floating Legenda Peta (Kiri Bawah) dengan Fitur Minimize */
    .gis-legend-card {
        position: absolute;
        bottom: 24px;
        left: 20px;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 12px;
        padding: 12px 16px;
        z-index: 1000;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        min-width: 190px;
        font-size: 0.8rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .gis-legend-card.is-minimized {
        min-width: unset !important;
        width: auto !important;
        padding: 8px 14px !important;
        border-radius: 20px;
        cursor: pointer;
    }

    .gis-legend-card.is-minimized #legendCardBody {
        display: none !important;
    }

    .gis-legend-card.is-minimized .gis-legend-header {
        margin-bottom: 0 !important;
    }

    .gis-legend-header {
        font-weight: 700;
        font-size: 0.84rem;
        color: #0f172a;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
    }

    .legend-row {
        display: flex;
        align-items: center;
        margin-bottom: 6px;
        font-weight: 500;
        color: #334155;
    }

    .legend-swatch-box {
        width: 14px;
        height: 10px;
        border-radius: 3px;
        margin-right: 8px;
        flex-shrink: 0;
    }

    .legend-swatch-line {
        width: 14px;
        height: 3px;
        background-color: #2563eb;
        margin-right: 8px;
        flex-shrink: 0;
    }

    .legend-swatch-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #ef4444;
        border: 2px solid #ffffff;
        box-shadow: 0 0 0 1px #dc2626;
        margin-right: 10px;
        margin-left: 2px;
        flex-shrink: 0;
    }

    /* Custom Leaflet Zoom Button Style */
    .leaflet-bar {
        border: 1px solid #d1d5db !important;
        border-radius: 8px !important;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12) !important;
    }

    .leaflet-bar a {
        background-color: #ffffff !important;
        color: #1e293b !important;
        width: 32px !important;
        height: 32px !important;
        line-height: 32px !important;
        font-size: 15px !important;
        font-weight: 700 !important;
    }

    /* Red Pin Marker */
    .gis-red-marker {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #ef4444;
        border: 3px solid #ffffff;
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.6);
        cursor: pointer;
        animation: marker-pulse 2s infinite;
    }

    @keyframes marker-pulse {
        0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
        100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }

    @media (max-width: 991px) {
        .webgis-container {
            flex-direction: column;
            height: auto;
        }
        .gis-sidebar {
            width: 100%;
            max-width: 100%;
        }
        .gis-map-card {
            height: 520px;
        }
    }
</style>
@endpush

@section('konten')
<!-- Flash Message Notifikasi -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm mb-3" role="alert" style="border-radius:10px;border-left:4px solid #10b981;">
    <div class="d-flex align-items-center">
        <i class="fas fa-check-circle mr-2 text-success fa-lg"></i>
        <div><strong>Berhasil!</strong> {{ session('success') }}</div>
    </div>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3" role="alert" style="border-radius:10px;border-left:4px solid #ef4444;">
    <div class="d-flex align-items-center">
        <i class="fas fa-exclamation-triangle mr-2 text-danger fa-lg"></i>
        <div><strong>Gagal!</strong> {{ session('error') }}</div>
    </div>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="webgis-container">

    <!-- ── SIDEBAR KIRI (FILTER & ACTION BUTTONS) ── -->
    <aside class="gis-sidebar">
        
        <!-- 1. Dashboard Type -->
        <div class="gis-form-group">
            <label class="gis-form-label">Dashboard Type</label>
            <select class="gis-select" id="selectDashboardType">
                <option value="data" selected>Dashboard Data</option>
                <option value="analisis">Dashboard Analisis</option>
            </select>
        </div>

        <!-- 2. View Mode -->
        <div class="gis-form-group">
            <label class="gis-form-label">View</label>
            <select class="gis-select" id="selectViewMode" onchange="toggleViewMode(this.value)">
                <option value="table_maps" selected>Table + Maps</option>
                <option value="maps_only">Maps Only</option>
            </select>
        </div>

        <!-- 3. Kelola File GIS Button -->
        <a href="{{ route('admin.kelola-gis') }}" class="btn-kelola-gis" title="Buka Manajemen Master Data GIS">
            <span class="d-flex align-items-center">
                <i class="fas fa-folder mr-2" style="color: #3b82f6; font-size: 15px;"></i>
                Kelola File GIS
            </span>
            <span class="badge-count">{{ count($geojsonFiles ?? []) > 0 ? count($geojsonFiles ?? []) : 54 }}</span>
        </a>

        <!-- 4. Upload Spasial ZIP / GeoJSON Button (Petugas / Admin) -->
        <button type="button" class="btn-upload-geojson" data-toggle="modal" data-target="#uploadGeojsonModal" title="Upload File ZIP (Shapefile ArcGIS) atau GeoJSON">
            <i class="fas fa-file-archive fa-lg"></i>
            <span>Upload ZIP / GeoJSON</span>
        </button>

        <!-- 5. Ringkasan Luas & DIR (Sesuai Desain Rapi) -->
        <div class="card border-0 bg-white shadow-sm mt-auto" style="border-radius: 12px; border: 1px solid #e2e8f0; padding: 14px 16px;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span style="color: #64748b; font-size: 0.84rem; font-weight: 500;">Total DIR</span>
                <span class="font-weight-bold" style="color: #3b82f6; font-size: 0.95rem;">{{ $statistik['total_dir'] ?? count($lokasi) }} DIR</span>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span style="color: #64748b; font-size: 0.84rem; font-weight: 500;">Luas Fungsional</span>
                <span class="font-weight-bold" style="color: #10b981; font-size: 0.95rem;">{{ $statistik['total_luas_fung'] ?? '-' }}</span>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <span style="color: #64748b; font-size: 0.84rem; font-weight: 500;">Luas Baku</span>
                <span class="font-weight-bold" style="color: #f59e0b; font-size: 0.95rem;">{{ $statistik['total_luas_baku'] ?? '-' }}</span>
            </div>
        </div>

    </aside>

    <!-- ── KONTEN AREA KANAN (MAP & BREADCRUMB) ── -->
    <main class="gis-content-area">
        
        <!-- Breadcrumb Header: Dashboard Data > Peta Daerah Irigasi (DIR) -->
        <div class="gis-breadcrumb-title">
            <span class="crumb-parent">Dashboard Data</span>
            <span class="crumb-sep">&gt;</span>
            <span class="crumb-active">Peta Daerah Irigasi (DIR)</span>
        </div>

        <!-- Map Card Container -->
        <div class="gis-map-card">
            
            <!-- Elemen Peta Leaflet -->
            <div id="map"></div>

            <!-- Floating Layer Control (Kanan Atas) Sesuai Gambar dengan Fitur Minimize -->
            <div class="gis-layer-control-card" id="cardLayerControl">
                <div class="layer-control-header" onclick="toggleLayerCard()">
                    <span class="font-weight-bold" style="color: #0f172a; font-size: 0.84rem;">
                        <i class="fas fa-layer-group text-primary mr-1"></i> Layer Peta
                    </span>
                    <button type="button" class="btn-minimize-icon" title="Minimize / Buka Layer Peta">
                        <i class="fas fa-minus" id="iconToggleLayer"></i>
                    </button>
                </div>

                <div id="layerControlBody">
                    <!-- Radio Pilihan Basemap -->
                    <label class="layer-radio-item">
                        <input type="radio" name="basemap_choice" value="osm" onchange="switchBaseMap('osm')">
                        <span>Peta (OpenStreetMap)</span>
                    </label>
                    <label class="layer-radio-item">
                        <input type="radio" name="basemap_choice" value="satellite" checked onchange="switchBaseMap('satellite')">
                        <span>Satelit (Google Hybrid)</span>
                    </label>

                    <div class="layer-divider"></div>

                    <!-- Checkbox Layer Spasial -->
                    <label class="layer-checkbox-item">
                        <input type="checkbox" id="chk-baku" checked onchange="toggleLayer('baku', this.checked)">
                        <span>Daerah Irigasi Baku (Hijau)</span>
                    </label>
                    <label class="layer-checkbox-item">
                        <input type="checkbox" id="chk-fungsional" checked onchange="toggleLayer('fungsional', this.checked)">
                        <span>Daerah Irigasi Fungsional (Biru)</span>
                    </label>
                    <label class="layer-checkbox-item">
                        <input type="checkbox" id="chk-potensial" checked onchange="toggleLayer('potensial', this.checked)">
                        <span>Daerah Irigasi Potensial (Oranye)</span>
                    </label>
                    <label class="layer-checkbox-item">
                        <input type="checkbox" id="chk-saluran" checked onchange="toggleLayer('saluran', this.checked)">
                        <span>Jaringan Saluran Irigasi</span>
                    </label>
                    <label class="layer-checkbox-item">
                        <input type="checkbox" id="chk-bangunan" checked onchange="toggleLayer('bangunan', this.checked)">
                        <span>Bangunan Air / Pelengkap</span>
                    </label>
                </div>
            </div>

            <!-- Floating Legenda Peta (Kiri Bawah) Sesuai Gambar dengan Fitur Minimize -->
            <div class="gis-legend-card" id="cardLegend">
                <div class="gis-legend-header" onclick="toggleLegendCard()">
                    <span>🎨 Legenda Peta</span>
                    <button type="button" class="btn-minimize-icon ml-2" title="Minimize / Buka Legenda">
                        <i class="fas fa-minus" id="iconToggleLegend"></i>
                    </button>
                </div>

                <div id="legendCardBody">
                    <div class="legend-row">
                        <span class="legend-swatch-box" style="background-color: #22c55e;"></span>
                        <span>DIR Baku (Hijau)</span>
                    </div>
                    <div class="legend-row">
                        <span class="legend-swatch-box" style="background-color: #0284c7;"></span>
                        <span>DIR Fungsional (Biru)</span>
                    </div>
                    <div class="legend-row">
                        <span class="legend-swatch-box" style="background-color: #f97316;"></span>
                        <span>DIR Potensial (Oranye)</span>
                    </div>
                    <div class="legend-row">
                        <span class="legend-swatch-line" style="background-color: #0284c7;"></span>
                        <span>Jaringan Saluran</span>
                    </div>
                    <div class="legend-row">
                        <span class="legend-swatch-dot"></span>
                        <span>Bangunan Air</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Tabel Data DIR (Muncul jika view Table + Maps aktif) -->
        <div id="sectionTableDIR" class="card border-0 shadow-sm mt-3" style="border-radius: 12px; display: none;">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-0">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-table mr-2"></i>Daftar Lokasi Daerah Irigasi (DIR) Terdaftar
                </h6>
                <div class="d-flex align-items-center" style="gap:10px;">
                    <a href="{{ route('admin.kelola-gis') }}" class="btn btn-sm btn-outline-primary font-weight-bold px-3" style="border-radius:20px;">
                        <i class="fas fa-database mr-1"></i> Buka Manajemen File GeoJSON
                    </a>
                    <span class="badge badge-primary px-3 py-1 font-weight-bold">{{ count($lokasi) }} Lokasi</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 240px; overflow-y: auto;">
                    <table class="table table-hover table-striped mb-0 text-dark small">
                        <thead class="thead-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Daerah Irigasi</th>
                                <th>Kabupaten</th>
                                <th>Luas Baku (Ha)</th>
                                <th>Luas Fungsional (Ha)</th>
                                <th>Koordinat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lokasi as $loc)
                            <tr>
                                <td><span class="badge badge-light border font-weight-bold">{{ $loc['kode'] }}</span></td>
                                <td class="font-weight-bold text-primary">{{ $loc['nama'] }}</td>
                                <td>{{ $loc['kabupaten'] }}</td>
                                <td>{{ number_format($loc['luas_baku'], 0, ',', '.') }} Ha</td>
                                <td>{{ number_format($loc['luas_fung'], 0, ',', '.') }} Ha</td>
                                <td><code>{{ $loc['lat'] }}, {{ $loc['lng'] }}</code></td>
                                <td class="text-center">
                                    <button class="btn btn-xs btn-outline-primary py-1 px-2" onclick="focusToLocation({{ $loc['lat'] }}, {{ $loc['lng'] }}, '{{ $loc['nama'] }}')">
                                        <i class="fas fa-search-location mr-1"></i> Lihat Peta
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

</div>

<!-- Modal Upload File Spasial (ZIP / GeoJSON) Khusus Admin -->
<div class="modal fade" id="uploadGeojsonModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header text-white" style="background: #1d64d8;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-archive mr-2 fa-lg"></i>
                    <h6 class="modal-title font-weight-bold mb-0" id="uploadModalLabel">Upload File Spasial (ZIP / GeoJSON)</h6>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form id="gisUploadForm" action="{{ route('admin.upload') }}" method="POST" enctype="multipart/form-data" onsubmit="handleGisUpload(this, event)">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 small mb-3 border-0" style="background: #e0f2fe; color: #0369a1; border-radius: 8px;">
                        <i class="fas fa-check-circle mr-1"></i> <strong>Mendukung File ZIP Shapefile ArcGIS / QGIS!</strong><br>
                        Anda dapat langsung mengunggah file <code>.zip</code> (berisi <code>.shp</code>, <code>.dbf</code>, <code>.shx</code>, <code>.prj</code>) tanpa perlu konversi manual. Sistem akan otomatis mengekstrak & memproses geometrinya.
                    </div>
                    <div class="form-group mb-2">
                        <label class="font-weight-bold small text-dark">Pilih File ZIP atau GeoJSON</label>
                        <input type="file" id="inputGisFiles" name="file_geojson[]" class="form-control-file border p-2 rounded w-100" accept=".zip,.json,.geojson" multiple required>
                        <small class="text-muted d-block mt-1">Dapat mengunggah beberapa file sekaligus (maksimal 256 MB per file).</small>
                    </div>
                    <div id="uploadStatusText" class="small font-weight-bold text-primary mt-2" style="display: none;"></div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSubmitUpload" class="btn btn-primary btn-sm px-4 font-weight-bold" style="background: #2563eb;">
                        <i class="fas fa-cloud-upload-alt mr-1"></i> Mulai Unggah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
var map;
var osmLayer, satelliteLayer;
var layerBaku, layerFungsional, layerPotensial, layerSaluran, layerBangunan;
var dirMarkers = [];
var locations = {!! json_encode($lokasi) !!};

document.addEventListener('DOMContentLoaded', function () {

    // 1. Inisialisasi Basemaps
    osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    });

    // Google Satellite Hybrid (sesuai referensi gambar)
    satelliteLayer = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        attribution: '&copy; Google Satellite'
    });

    // 2. Inisialisasi Map Leaflet dengan default Satelit Hybrid
    map = L.map('map', {
        center: [-0.3, 110.5],
        zoom: 7,
        layers: [satelliteLayer],
        zoomControl: true
    });

    // Atur posisi tombol Zoom Leaflet di pojok kiri atas
    map.zoomControl.setPosition('topleft');

    // 3. Layer Groups untuk Geometri Spasial
    layerBaku       = L.layerGroup().addTo(map);
    layerFungsional = L.layerGroup().addTo(map);
    layerPotensial  = L.layerGroup().addTo(map);
    layerSaluran    = L.layerGroup().addTo(map);
    layerBangunan   = L.layerGroup().addTo(map);

    // 4. Render Marker Pin Titik Merah (Sesuai Titik Merah Pada Gambar Referensi)
    locations.forEach(function (loc) {
        var markerIcon = L.divIcon({
            className: 'custom-red-pin-container',
            html: '<div class="gis-red-marker" title="' + loc.nama + '"></div>',
            iconSize: [18, 18],
            iconAnchor: [9, 9]
        });

        var m = L.marker([loc.lat, loc.lng], { icon: markerIcon }).addTo(map);
        m.bindPopup(
            '<div style="min-width: 220px; font-family: sans-serif;">' +
            '<div class="d-flex align-items-center mb-2">' +
            '<span class="badge badge-primary mr-2" style="background:#2563eb;">' + loc.kode + '</span>' +
            '<strong class="text-primary" style="font-size: 13px;">' + loc.nama + '</strong>' +
            '</div>' +
            '<div class="text-muted small mb-2"><i class="fas fa-map-marker-alt mr-1 text-danger"></i>' + loc.status + '</div>' +
            '<table class="table table-sm table-striped mb-1" style="font-size: 11px;">' +
            '<tr><td>Luas Baku</td><td><b>' + (loc.luas_baku ? loc.luas_baku + ' Ha' : '-') + '</b></td></tr>' +
            '<tr><td>Luas Fungsional</td><td><b>' + (loc.luas_fung ? loc.luas_fung + ' Ha' : '-') + '</b></td></tr>' +
            '<tr><td>Koordinat</td><td><code>' + loc.lat + ', ' + loc.lng + '</code></td></tr>' +
            '</table>' +
            '<small class="text-secondary">' + (loc.keterangan || '-') + '</small>' +
            '</div>'
        );
        dirMarkers.push({ name: loc.nama.toLowerCase(), marker: m });
    });

    // 5. Muat GeoJSON Secara Asinkron
    var geojsonFiles = {!! json_encode($geojsonFiles) !!};
    geojsonFiles.forEach(function (fileName) {
        var fileLower = fileName.toLowerCase();
        var targetLayer = null;
        var strokeColor = '#0284c7', fillColor = '#38bdf8', fillOpacity = 0.5, weight = 2;

        if (fileLower.startsWith('baku')) {
            targetLayer = layerBaku;
            strokeColor = '#16a34a'; fillColor = '#22c55e'; fillOpacity = 0.6;
        } else if (fileLower.startsWith('fungsional')) {
            targetLayer = layerFungsional;
            strokeColor = '#0284c7'; fillColor = '#38bdf8'; fillOpacity = 0.6;
        } else if (fileLower.startsWith('potensial')) {
            targetLayer = layerPotensial;
            strokeColor = '#ea580c'; fillColor = '#f97316'; fillOpacity = 0.6;
        } else if (fileLower.startsWith('saluran')) {
            targetLayer = layerSaluran;
            strokeColor = '#0284c7'; fillColor = '#0284c7'; fillOpacity = 0; weight = 2.5;
        } else if (fileLower.startsWith('bangunan')) {
            targetLayer = layerBangunan;
            strokeColor = '#dc2626'; fillColor = '#ef4444'; fillOpacity = 0.9;
        }

        if (!targetLayer) return;

        fetch('{{ asset("geojson") }}/' + encodeURIComponent(fileName))
            .then(function(r) { return r.json(); })
            .then(function (gj) {
                if (!gj || !gj.features) return;
                var layer = L.geoJSON(gj, {
                    style: function() {
                        return { color: strokeColor, fillColor: fillColor, fillOpacity: fillOpacity, weight: weight };
                    },
                    pointToLayer: function(f, latlng) {
                        return L.circleMarker(latlng, { radius: 5, fillColor: strokeColor, color: '#fff', weight: 1.5, fillOpacity: 0.9 });
                    },
                    onEachFeature: function(f, l) {
                        if (f.properties) {
                            var p = f.properties;
                            var title = p.NAMOBJ || p.Name || p.D_I_R || fileName;
                            var html = '<div style="max-width:280px; max-height:260px; overflow-y:auto; font-size:11px;">' +
                                '<h6 class="font-weight-bold text-primary mb-1 border-bottom pb-1">' + title + '</h6>' +
                                '<table class="table table-sm table-striped mb-0">';
                            for (var k in p) {
                                if (p[k] !== null && p[k] !== undefined && String(p[k]).trim() !== '') {
                                    html += '<tr><th class="text-muted">' + k + '</th><td><b>' + p[k] + '</b></td></tr>';
                                }
                            }
                            html += '</table></div>';
                            l.bindPopup(html);
                        }
                    }
                });
                targetLayer.addLayer(layer);
            })
            .catch(function(err) {
                console.warn('Gagal memuat GeoJSON ' + fileName + ':', err);
            });
    });

    // Panggil toggleViewMode sesuai default
    toggleViewMode(document.getElementById('selectViewMode').value);
});

// Ganti Basemap (Peta OSM vs Satelit Hybrid)
function switchBaseMap(type) {
    if (type === 'osm') {
        if (map.hasLayer(satelliteLayer)) map.removeLayer(satelliteLayer);
        if (!map.hasLayer(osmLayer)) map.addLayer(osmLayer);
    } else {
        if (map.hasLayer(osmLayer)) map.removeLayer(osmLayer);
        if (!map.hasLayer(satelliteLayer)) map.addLayer(satelliteLayer);
    }
}

// Toggle Layer Spasial (Checkbox Kanan Atas)
function toggleLayer(layerKey, isChecked) {
    var layerMap = {
        'baku': layerBaku,
        'fungsional': layerFungsional,
        'potensial': layerPotensial,
        'saluran': layerSaluran,
        'bangunan': layerBangunan
    };

    var target = layerMap[layerKey];
    if (!target) return;

    if (isChecked) {
        if (!map.hasLayer(target)) map.addLayer(target);
    } else {
        if (map.hasLayer(target)) map.removeLayer(target);
    }
}

// Toggle Tampilan Table + Maps vs Maps Only
function toggleViewMode(val) {
    var tableEl = document.getElementById('sectionTableDIR');
    if (val === 'table_maps') {
        tableEl.style.display = 'block';
    } else {
        tableEl.style.display = 'none';
    }
    setTimeout(function() {
        if (map) map.invalidateSize();
    }, 200);
}

// Toggle Minimize Layer Control Card (Kanan Atas)
function toggleLayerCard(e) {
    if (e) e.stopPropagation();
    var card = document.getElementById('cardLayerControl');
    var icon = document.getElementById('iconToggleLayer');
    card.classList.toggle('is-minimized');
    if (card.classList.contains('is-minimized')) {
        icon.className = 'fas fa-chevron-down';
    } else {
        icon.className = 'fas fa-minus';
    }
}

// Toggle Minimize Legenda Card (Kiri Bawah)
function toggleLegendCard(e) {
    if (e) e.stopPropagation();
    var card = document.getElementById('cardLegend');
    var icon = document.getElementById('iconToggleLegend');
    card.classList.toggle('is-minimized');
    if (card.classList.contains('is-minimized')) {
        icon.className = 'fas fa-chevron-up';
    } else {
        icon.className = 'fas fa-minus';
    }
}

// Handle Upload Form dengan Konversi Otomatis Shapefile ZIP via shpjs
async function handleGisUpload(form, event) {
    event.preventDefault();
    var fileInput = document.getElementById('inputGisFiles');
    var files = fileInput.files;
    if (!files || files.length === 0) {
        alert('Silakan pilih file terlebih dahulu.');
        return false;
    }

    var submitBtn = document.getElementById('btnSubmitUpload');
    var statusText = document.getElementById('uploadStatusText');
    submitBtn.disabled = true;
    statusText.style.display = 'block';

    var formData = new FormData();
    formData.append('_token', form.querySelector('input[name="_token"]').value);

    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var ext = file.name.split('.').pop().toLowerCase();

        if (ext === 'zip') {
            statusText.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengekstrak paket Shapefile ZIP: ' + file.name + '...';
            try {
                if (typeof shp !== 'undefined') {
                    var buffer = await file.arrayBuffer();
                    var geojson = await shp(buffer);

                    if (Array.isArray(geojson)) {
                        geojson.forEach(function(gj, idx) {
                            var rawName = gj.fileName || (file.name.replace(/\.zip$/i, '') + (idx > 0 ? '_' + idx : ''));
                            var cleanName = rawName.replace(/[^a-zA-Z0-9_\-]/g, '_') + '.geojson';
                            var blob = new Blob([JSON.stringify(gj)], { type: 'application/geo+json' });
                            formData.append('file_geojson[]', blob, cleanName);
                        });
                    } else if (geojson && geojson.type) {
                        var rawName = geojson.fileName || file.name.replace(/\.zip$/i, '');
                        var cleanName = rawName.replace(/[^a-zA-Z0-9_\-]/g, '_') + '.geojson';
                        var blob = new Blob([JSON.stringify(geojson)], { type: 'application/geo+json' });
                        formData.append('file_geojson[]', blob, cleanName);
                    } else {
                        formData.append('file_geojson[]', file, file.name);
                    }
                } else {
                    formData.append('file_geojson[]', file, file.name);
                }
            } catch (err) {
                console.warn('shpjs conversion fallback:', err);
                formData.append('file_geojson[]', file, file.name);
            }
        } else {
            formData.append('file_geojson[]', file, file.name);
        }
    }

    statusText.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim data ke server WebGIS...';

    try {
        var response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.redirected) {
            window.location.href = response.url;
            return;
        }

        var resData = await response.json().catch(function() { return null; });
        if (resData && resData.success) {
            window.location.reload();
        } else if (resData && !resData.success) {
            alert(resData.message || 'Gagal mengunggah file.');
            submitBtn.disabled = false;
            statusText.style.display = 'none';
        } else {
            window.location.reload();
        }
    } catch (e) {
        // Fallback standard submit
        form.submit();
    }
}
</script>
@endpush

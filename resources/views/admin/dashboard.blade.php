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

    /* 1. Floating Layer Control (Kanan Atas) */
    .gis-layer-control-card {
        position: absolute;
        top: 14px;
        right: 14px;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 10px;
        padding: 12px 14px;
        z-index: 1000;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        width: 250px;
        font-size: 0.82rem;
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

    /* 2. Floating Legenda Peta (Kiri Bawah) */
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
    }

    .gis-legend-header {
        font-weight: 700;
        font-size: 0.84rem;
        color: #0f172a;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
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

        <!-- 2. Filter Tanggal -->
        <div class="gis-form-group">
            <label class="gis-form-label">Filter Tanggal</label>
            <div class="position-relative">
                <input type="text" class="gis-input w-100 pr-4" id="filterTanggal" value="{{ date('d F Y') }}" readonly>
                <i class="far fa-calendar-alt position-absolute text-muted" style="right: 12px; top: 12px; font-size: 15px;"></i>
            </div>
        </div>

        <!-- 3. View -->
        <div class="gis-form-group">
            <label class="gis-form-label">View</label>
            <select class="gis-select" id="selectViewMode" onchange="toggleViewMode(this.value)">
                <option value="table_maps" selected>Table + Maps</option>
                <option value="maps_only">Maps Only</option>
            </select>
        </div>

        <!-- 4. Kelola File GIS Button -->
        <a href="{{ route('admin.kelola-gis') }}" class="btn-kelola-gis" title="Buka Manajemen Master Data GIS">
            <span class="d-flex align-items-center">
                <i class="fas fa-folder-open mr-2 text-primary" style="font-size: 14px;"></i>
                Kelola File GIS
            </span>
            <span class="badge-count">{{ count($geojsonFiles ?? []) > 0 ? count($geojsonFiles ?? []) : 54 }}</span>
        </a>

        <!-- 5. Upload GeoJSON Button (Petugas / Admin) -->
        <button type="button" class="btn-upload-geojson" data-toggle="modal" data-target="#uploadGeojsonModal">
            <i class="fas fa-cloud-upload-alt fa-lg"></i>
            <span>Upload GeoJSON</span>
        </button>

        <!-- Informasi Tambahan Ringkas -->
        <div class="card border-0 bg-white shadow-sm mt-auto p-3" style="border-radius: 10px; border: 1px solid #e2e8f0;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small font-weight-bold text-muted">Total DIR</span>
                <span class="font-weight-bold text-primary">{{ $statistik['total_dir'] ?? count($lokasi) }} DIR</span>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small font-weight-bold text-muted">Luas Fungsional</span>
                <span class="font-weight-bold text-success">{{ $statistik['total_luas_fung'] ?? '-' }}</span>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <span class="small font-weight-bold text-muted">Luas Baku</span>
                <span class="font-weight-bold text-warning">{{ $statistik['total_luas_baku'] ?? '-' }}</span>
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

            <!-- Floating Layer Control (Kanan Atas) Sesuai Gambar -->
            <div class="gis-layer-control-card">
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

            <!-- Floating Legenda Peta (Kiri Bawah) Sesuai Gambar -->
            <div class="gis-legend-card">
                <div class="gis-legend-header">
                    <span>🎨 Legenda Peta</span>
                </div>
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

<!-- Modal Upload GeoJSON Khusus Admin -->
<div class="modal fade" id="uploadGeojsonModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header text-white" style="background: #1d64d8;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-cloud-upload-alt mr-2 fa-lg"></i>
                    <h6 class="modal-title font-weight-bold mb-0" id="uploadModalLabel">Upload Data GeoJSON Baru</h6>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="{{ route('admin.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <p class="small text-muted mb-3">
                        Unggah file spasial <code>.geojson</code> atau <code>.json</code> untuk dipetakan secara real-time pada peta WebGIS.
                    </p>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-dark">Pilih File GeoJSON</label>
                        <input type="file" name="file_geojson[]" class="form-control-file border p-2 rounded w-100" accept=".json,.geojson" multiple required>
                        <small class="text-muted">Dapat mengunggah beberapa file sekaligus (maksimal 256 MB per file).</small>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 font-weight-bold" style="background: #2563eb;">
                        <i class="fas fa-upload mr-1"></i> Mulai Unggah
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

// Fokuskan Peta ke Koordinat Tertentu
function focusToLocation(lat, lng, nama) {
    map.setView([lat, lng], 13);
    var item = dirMarkers.find(function(d) { return d.name === nama.toLowerCase(); });
    if (item && item.marker) {
        item.marker.openPopup();
    }
}
</script>
@endpush

@extends('layout.master')

@php
    $hasFiles = $hasFiles ?? (isset($fileList) && count($fileList) > 0);
    $availableTypes = $availableTypes ?? [
        'ws_kapuas'  => false,
        'baku'       => false,
        'fungsional' => false,
        'potensial'  => false,
        'saluran'    => false,
        'bangunan'   => false,
        'custom'     => false,
    ];
@endphp

@section('title', 'Peta Interaktif WebGIS Daerah Irigasi & WS Kapuas — BWS Kalimantan I')

@push('styles')
<style>
    /* ── TATA LETAK WEBGIS DENGAN SIDEBAR KIRI FUNGSIONAL ── */
    .gis-wrapper-container {
        display: flex;
        flex-direction: row;
        width: 100%;
        min-height: calc(100vh - 80px);
        background: #f8fafc;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
    }

    /* ── SIDEBAR KIRI FILTER & LAYER CONTROL ── */
    .gis-sidebar-left {
        width: 340px;
        min-width: 320px;
        max-width: 360px;
        background: #ffffff;
        border-right: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        z-index: 10;
        max-height: calc(100vh - 80px);
        overflow-y: auto;
    }

    .gis-sidebar-header {
        background: linear-gradient(135deg, #0d3875 0%, #174ea6 100%);
        padding: 16px 20px;
        color: #fff;
    }

    .gis-sidebar-body {
        padding: 18px 20px;
        flex: 1;
        overflow-y: auto;
    }

    /* ── AREA PETA KANAN ── */
    .gis-map-content {
        flex: 1;
        position: relative;
        min-height: 650px;
        display: flex;
        flex-direction: column;
    }

    #map {
        width: 100%;
        height: 100%;
        min-height: 650px;
        flex: 1;
        z-index: 1;
    }

    /* ── ELEMEN FILTER SIDEBAR ── */
    .sidebar-section-title {
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #174ea6;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }

    .sidebar-filter-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 16px;
    }

    /* Custom Checkbox Layer */
    .custom-layer-checkbox {
        display: flex;
        align-items: center;
        padding: 8px 10px;
        border-radius: 8px;
        margin-bottom: 6px;
        cursor: pointer;
        transition: background 0.15s ease;
        background: #ffffff;
        border: 1px solid #e2e8f0;
    }

    .custom-layer-checkbox:hover {
        background: #f1f5f9;
    }

    .custom-layer-checkbox input[type="checkbox"] {
        width: 17px;
        height: 17px;
        cursor: pointer;
        margin-right: 10px;
        accent-color: #174ea6;
    }

    .custom-layer-label {
        font-size: 0.84rem;
        font-weight: 600;
        color: #334155;
        display: flex;
        align-items: center;
        flex: 1;
        margin-bottom: 0;
        cursor: pointer;
    }

    .color-swatch-box {
        width: 15px;
        height: 15px;
        border-radius: 3px;
        display: inline-block;
        margin-right: 8px;
        flex-shrink: 0;
    }

    /* Widget Statistik */
    .stat-mini-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 12px;
        text-align: center;
    }

    .stat-mini-val {
        font-size: 1.1rem;
        font-weight: 800;
        color: #174ea6;
        line-height: 1.2;
    }

    .stat-mini-lbl {
        font-size: 0.7rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        margin-top: 2px;
    }

    /* Legenda Sidebar */
    .sidebar-legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 7px;
        font-size: 0.8rem;
        color: #475569;
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .gis-wrapper-container {
            flex-direction: column;
        }
        .gis-sidebar-left {
            width: 100%;
            max-width: 100%;
            max-height: unset;
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
        }
        #map {
            min-height: 500px;
        }
    }
</style>
@endpush

@section('konten')
<!-- Breadcrumb & Status Bar -->
<div class="d-sm-flex align-items-center justify-content-between mb-3">
    <div>
        <h1 class="h4 mb-0 text-gray-800 font-weight-bold">
            <i class="fas fa-map-marked-alt text-primary mr-2"></i>WebGIS Daerah Irigasi &amp; WS Kapuas
        </h1>
        <p class="text-muted mb-0 small">Balai Wilayah Sungai Kalimantan I Pontianak &bull; Kementerian Pekerjaan Umum</p>
    </div>
    
    @auth
    <div class="mt-2 mt-sm-0">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-primary shadow-sm font-weight-bold px-3" style="border-radius:20px; background:#2563eb;">
            <i class="fas fa-th-large mr-1"></i> Dashboard Admin
        </a>
    </div>
    @endauth
</div>

<!-- ═══ WADAH UTAMA WEBGIS DENGAN SIDEBAR KIRI ═══ -->
<div class="gis-wrapper-container">

    <!-- ── SIDEBAR KIRI FUNGSIONAL ── -->
    <aside class="gis-sidebar-left">
        <!-- 1. Header Panel -->
        <div class="gis-sidebar-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="font-weight-bold mb-0 text-white" style="letter-spacing:0.2px;">
                        <i class="fas fa-sliders-h mr-2 text-warning"></i>Filter Data GIS BWSK I
                    </h6>
                    <small class="text-white-50" style="font-size:0.75rem;">Panel Navigasi &amp; Layer Kontrol</small>
                </div>
            </div>
        </div>

        <div class="gis-sidebar-body">

            <!-- 2. Dropdown Filter Kabupaten / WS Kapuas -->
            <div class="sidebar-filter-card">
                <label class="sidebar-section-title">
                    <i class="fas fa-map-marker-alt mr-1"></i> Filter Kabupaten / Wilayah
                </label>
                <select id="selectFilterWilayah" class="form-control form-control-sm" style="border-radius:6px; font-weight:600;">
                    <option value="semua">Semua Wilayah Sungai</option>
                    @foreach($kabupatenList as $kab)
                        <option value="{{ $kab }}">{{ $kab }}</option>
                    @endforeach
                </select>
                <small class="text-muted mt-1 d-block" style="font-size:0.72rem;">Peta akan otomatis bergeser ke koordinat kabupaten terpilih.</small>
            </div>

            <!-- 3. Layer Control (Checkbox Spasial Sesuai Spesifikasi) -->
            <div class="sidebar-filter-card">
                <label class="sidebar-section-title">
                    <i class="fas fa-layer-group mr-1"></i> Layer Control (Spasial)
                </label>

                <!-- Checkbox WS & DAS Kapuas -->
                <label class="custom-layer-checkbox">
                    <input type="checkbox" id="chk-ws-kapuas" checked onchange="toggleSidebarLayer('ws_kapuas', this.checked)">
                    <span class="custom-layer-label">
                        <span class="color-swatch-box" style="background:#4f46e5; border:1px solid #3730a3;"></span>
                        Wilayah Sungai &amp; DAS Kapuas
                    </span>
                </label>

                <!-- Checkbox DIR Baku -->
                <label class="custom-layer-checkbox">
                    <input type="checkbox" id="chk-baku" checked onchange="toggleSidebarLayer('baku', this.checked)">
                    <span class="custom-layer-label">
                        <span class="color-swatch-box" style="background:#27ae60; border:1px solid #1b7a43;"></span>
                        Daerah Irigasi Baku (Hijau)
                    </span>
                </label>

                <!-- Checkbox DIR Fungsional -->
                <label class="custom-layer-checkbox">
                    <input type="checkbox" id="chk-fungsional" checked onchange="toggleSidebarLayer('fungsional', this.checked)">
                    <span class="custom-layer-label">
                        <span class="color-swatch-box" style="background:#2980b9; border:1px solid #1a5276;"></span>
                        Daerah Irigasi Fungsional (Biru)
                    </span>
                </label>

                <!-- Checkbox DIR Potensial -->
                <label class="custom-layer-checkbox">
                    <input type="checkbox" id="chk-potensial" checked onchange="toggleSidebarLayer('potensial', this.checked)">
                    <span class="custom-layer-label">
                        <span class="color-swatch-box" style="background:#e67e22; border:1px solid #b95c00;"></span>
                        Daerah Irigasi Potensial (Oranye)
                    </span>
                </label>

                <!-- Checkbox Jaringan Saluran Irigasi -->
                <label class="custom-layer-checkbox">
                    <input type="checkbox" id="chk-saluran" checked onchange="toggleSidebarLayer('saluran', this.checked)">
                    <span class="custom-layer-label">
                        <span class="color-swatch-box" style="background:#e74c3c; height:4px; margin-top:5px;"></span>
                        Jaringan Saluran Irigasi
                    </span>
                </label>

                <!-- Checkbox Bangunan Air / Pelengkap -->
                <label class="custom-layer-checkbox">
                    <input type="checkbox" id="chk-bangunan" checked onchange="toggleSidebarLayer('bangunan', this.checked)">
                    <span class="custom-layer-label">
                        <span class="color-swatch-box" style="background:#d63031; border-radius:50%; width:12px; height:12px;"></span>
                        Bangunan Air / Pelengkap
                    </span>
                </label>
            </div>

            <!-- 4. Kotak Pencarian Nama Daerah Irigasi -->
            <div class="sidebar-filter-card">
                <label class="sidebar-section-title">
                    <i class="fas fa-search mr-1"></i> Cari Daerah Irigasi (DIR)
                </label>
                <div class="input-group input-group-sm">
                    <input type="text" id="inputCariDir" class="form-control" placeholder="Ketik nama DIR..." onkeyup="handleCariDir(this.value)" style="border-radius:6px 0 0 6px;">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" style="border-radius:0 6px 6px 0; background:#174ea6;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div id="dirSearchResults" class="mt-2" style="max-height:160px; overflow-y:auto; display:none;"></div>
            </div>

            <!-- 5. Widget Ringkasan Statistik Singkat -->
            <div class="sidebar-filter-card">
                <label class="sidebar-section-title">
                    <i class="fas fa-chart-pie mr-1"></i> Ringkasan Statistik Spasial
                </label>
                <div class="row no-gutters" style="gap:6px;">
                    <div class="col stat-mini-box">
                        <div class="stat-mini-val">{{ $statistik['total_dir'] }}</div>
                        <div class="stat-mini-lbl">Total DIR</div>
                    </div>
                    <div class="col stat-mini-box">
                        <div class="stat-mini-val" style="color:#27ae60;">{{ $statistik['total_luas_fung'] }}</div>
                        <div class="stat-mini-lbl">Fungsional</div>
                    </div>
                    <div class="col stat-mini-box">
                        <div class="stat-mini-val" style="color:#e67e22;">{{ $statistik['total_luas_baku'] }}</div>
                        <div class="stat-mini-lbl">Baku</div>
                    </div>
                </div>
            </div>

            <!-- 6. Legenda Peta -->
            <div class="sidebar-filter-card mb-0">
                <label class="sidebar-section-title">
                    <i class="fas fa-palette mr-1"></i> Legenda Simbol Peta
                </label>
                <div class="sidebar-legend-item">
                    <span class="color-swatch-box" style="background:#4f46e5;"></span>
                    <span>Wilayah Sungai Kapuas (Batas WS/DAS)</span>
                </div>
                <div class="sidebar-legend-item">
                    <span class="color-swatch-box" style="background:#27ae60;"></span>
                    <span>Lahan Irigasi Baku</span>
                </div>
                <div class="sidebar-legend-item">
                    <span class="color-swatch-box" style="background:#2980b9;"></span>
                    <span>Lahan Irigasi Fungsional</span>
                </div>
                <div class="sidebar-legend-item">
                    <span class="color-swatch-box" style="background:#e67e22;"></span>
                    <span>Lahan Irigasi Potensial</span>
                </div>
                <div class="sidebar-legend-item">
                    <span class="color-swatch-box" style="background:#e74c3c; height:3px; margin-top:6px;"></span>
                    <span>Saluran Primer / Sekunder</span>
                </div>
                <div class="sidebar-legend-item">
                    <span class="color-swatch-box" style="background:#d63031; border-radius:50%; width:10px; height:10px;"></span>
                    <span>Bangunan Bagi / Sadap / Bendung</span>
                </div>
            </div>

        </div>
    </aside>

    <!-- ── AREA PETA INTERAKTIF KANAN ── -->
    <div class="gis-map-content">
        <div id="map"></div>
    </div>

</div>
@endsection

@push('scripts')
<!-- Proj4 JS untuk Reproyeksi Koordinat -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.9.0/proj4.js"></script>

<script>
var map;
var layerWsKapuas, layerBaku, layerFungsional, layerPotensial, layerSaluran, layerBangunan;
var dirMarkers = {};
var locations = {!! json_encode($lokasi) !!};

document.addEventListener('DOMContentLoaded', function () {

    // 1. Inisialisasi Peta Leaflet
    var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    });
    var googleSat = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        attribution: '&copy; Google Satellite'
    });

    map = L.map('map', {
        center: [0.15, 110.15],
        zoom: 8,
        layers: [osm],
        zoomControl: true
    });

    // Kontrol Ganti Basemap di pojok kanan atas
    var baseLayers = {
        "Peta Jalan (OpenStreetMap)": osm,
        "Citra Satelit (Google Hybrid)": googleSat
    };
    L.control.layers(baseLayers, null, { position: 'topright' }).addTo(map);

    // 2. Layer Groups
    layerWsKapuas   = L.layerGroup().addTo(map);
    layerBaku       = L.layerGroup().addTo(map);
    layerFungsional = L.layerGroup().addTo(map);
    layerPotensial  = L.layerGroup().addTo(map);
    layerSaluran    = L.layerGroup().addTo(map);
    layerBangunan   = L.layerGroup().addTo(map);

    // 3. Render Marker Pin Daerah Irigasi
    locations.forEach(function (loc) {
        var markerBg = '#2980b9';
        if (loc.kabupaten === 'Kubu Raya') markerBg = '#e74c3c';
        if (loc.kabupaten === 'Ketapang')  markerBg = '#27ae60';

        var customIcon = L.divIcon({
            className: 'custom-dir-pin-wrapper',
            html: '<div style="background:' + markerBg + '; width:26px; height:26px; border-radius:50%; border:2px solid #fff; box-shadow:0 2px 5px rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center; color:#fff; font-size:11px;">' +
                  '<i class="fas fa-' + (loc.ikon || 'tint') + '"></i></div>',
            iconSize: [26, 26],
            iconAnchor: [13, 13]
        });

        var marker = L.marker([loc.lat, loc.lng], { icon: customIcon }).addTo(map);
        marker.bindPopup(
            '<div style="min-width:220px; font-family:sans-serif;">' +
            '<div class="d-flex align-items-center mb-2">' +
            '<span class="badge badge-primary mr-2">' + loc.kode + '</span>' +
            '<h6 class="font-weight-bold mb-0 text-primary" style="font-size:13px;">' + loc.nama + '</h6></div>' +
            '<p class="small text-muted mb-1"><i class="fas fa-map-pin mr-1"></i>' + loc.status + '</p>' +
            '<table class="table table-sm table-striped mb-1" style="font-size:11px;">' +
            '<tr><td>Luas Baku</td><td><b>' + (loc.luas_baku ? loc.luas_baku + ' Ha' : '-') + '</b></td></tr>' +
            '<tr><td>Luas Fungsional</td><td><b>' + (loc.luas_fung ? loc.luas_fung + ' Ha' : '-') + '</b></td></tr>' +
            '<tr><td>Koordinat</td><td><code>' + loc.lat + ', ' + loc.lng + '</code></td></tr>' +
            '</table>' +
            '<small class="text-secondary">' + (loc.keterangan || '-') + '</small></div>'
        );

        dirMarkers[loc.nama.toLowerCase()] = marker;
    });

    // 4. Fetch File GeoJSON
    var geojsonFiles = {!! json_encode($geojsonFiles) !!};
    geojsonFiles.forEach(function (fileName) {
        var fileLower = fileName.toLowerCase();
        var targetLayer = null;
        var strokeColor = '#3b82f6', fillColor = '#60a5fa', fillOpacity = 0.45, weight = 2;

        if (fileLower.indexOf('kapuas') !== -1 || fileLower.startsWith('ws') || fileLower.indexOf('das') !== -1) {
            targetLayer = layerWsKapuas;
            strokeColor = '#4f46e5'; fillColor = '#818cf8'; fillOpacity = 0.25;
        } else if (fileLower.startsWith('baku')) {
            targetLayer = layerBaku;
            strokeColor = '#27ae60'; fillColor = '#2ecc71'; fillOpacity = 0.55;
        } else if (fileLower.startsWith('fungsional')) {
            targetLayer = layerFungsional;
            strokeColor = '#2980b9'; fillColor = '#3498db'; fillOpacity = 0.55;
        } else if (fileLower.startsWith('potensial')) {
            targetLayer = layerPotensial;
            strokeColor = '#e67e22'; fillColor = '#f39c12'; fillOpacity = 0.55;
        } else if (fileLower.startsWith('saluran')) {
            targetLayer = layerSaluran;
            strokeColor = '#e74c3c'; fillColor = '#e74c3c'; fillOpacity = 0; weight = 2.5;
        } else if (fileLower.startsWith('bangunan')) {
            targetLayer = layerBangunan;
            strokeColor = '#d63031'; fillColor = '#ff7675'; fillOpacity = 0.9;
        }

        if (!targetLayer) return;

        fetch('{{ asset("geojson") }}/' + encodeURIComponent(fileName))
            .then(r => r.json())
            .then(function (gj) {
                if (!gj || !gj.features) return;
                var layer = L.geoJSON(gj, {
                    style: function(f) {
                        return { color: strokeColor, fillColor: fillColor, fillOpacity: fillOpacity, weight: weight };
                    },
                    pointToLayer: function(f, latlng) {
                        return L.circleMarker(latlng, { radius: 4, fillColor: strokeColor, color: '#fff', weight: 1.5, fillOpacity: 0.9 });
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
            .catch(err => console.error(err));
    });

    // 5. Event Listener Filter Wilayah
    document.getElementById('selectFilterWilayah').addEventListener('change', function () {
        var kab = this.value;
        if (kab === 'semua') {
            map.setView([0.15, 110.15], 8);
            return;
        }

        var matched = locations.filter(l => l.kabupaten === kab);
        if (matched.length > 0) {
            var bounds = L.latLngBounds();
            matched.forEach(l => bounds.extend([l.lat, l.lng]));
            map.fitBounds(bounds, { padding: [50, 50], maxZoom: 12 });
        }
    });

});

// Fungsi Toggle Layer dari Sidebar Checkbox
function toggleSidebarLayer(layerKey, isChecked) {
    var layerMap = {
        'ws_kapuas': layerWsKapuas,
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

// Fungsi Pencarian DIR dari Sidebar
function handleCariDir(val) {
    var q = (val || '').toLowerCase().trim();
    var resBox = document.getElementById('dirSearchResults');
    if (!q) {
        resBox.style.display = 'none';
        resBox.innerHTML = '';
        return;
    }

    var matched = locations.filter(l => l.nama.toLowerCase().indexOf(q) !== -1 || l.kabupaten.toLowerCase().indexOf(q) !== -1);
    if (matched.length === 0) {
        resBox.style.display = 'block';
        resBox.innerHTML = '<div class="p-2 text-muted small bg-light rounded text-center">Tidak ditemukan DIR yang cocok.</div>';
        return;
    }

    var html = '<div class="list-group list-group-flush border rounded shadow-sm">';
    matched.forEach(function (loc) {
        html += '<a href="javascript:void(0)" class="list-group-item list-group-item-action p-2 small" onclick="zoomToDir(\'' + loc.nama.toLowerCase() + '\', ' + loc.lat + ', ' + loc.lng + ')">' +
            '<div class="font-weight-bold text-primary">' + loc.nama + '</div>' +
            '<div class="text-muted" style="font-size:10px;"><i class="fas fa-map-marker-alt mr-1"></i>' + loc.status + '</div>' +
            '</a>';
    });
    html += '</div>';

    resBox.innerHTML = html;
    resBox.style.display = 'block';
}

function zoomToDir(namaKey, lat, lng) {
    map.setView([lat, lng], 14);
    if (dirMarkers[namaKey]) {
        dirMarkers[namaKey].openPopup();
    }
}
</script>
@endpush

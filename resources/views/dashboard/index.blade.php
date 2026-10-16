@extends('layout.master')

@section('title', 'Realtime Telemetri & Monitoring - BWS Kalimantan I')

@push('styles')
<style>
    /* Styling Persis Referensi BWS Kalimantan I (bwskal1.higertech.com & fews.bwskal1.or.id) */
    body {
        background-color: #f1f5f9;
    }

    /* 1. Bar Tab & Metrik Atas */
    .telemetri-top-bar {
        background: #ffffff;
        border-radius: 8px;
        padding: 10px 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        margin-bottom: 12px;
    }
    .metric-pill-btn {
        border-radius: 6px;
        padding: 6px 14px;
        font-weight: 700;
        font-size: 0.78rem;
        display: inline-flex;
        align-items: center;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none !important;
    }
    .metric-pill-btn:hover {
        background: #f8fafc;
        color: #1e40af;
    }
    .metric-pill-btn.active {
        background-color: #1b5fb5 !important;
        border-color: #1b5fb5 !important;
        color: #ffffff !important;
    }
    .metric-pill-btn .badge-pill-count {
        border-radius: 4px;
        padding: 2px 6px;
        margin-left: 8px;
        font-size: 0.75rem;
    }

    .kpi-stat-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 6px 14px;
        display: inline-flex;
        align-items: center;
    }
    .kpi-stat-item .stat-icon {
        font-size: 1.25rem;
        margin-right: 10px;
    }

    /* 2. Kolom Kiri: Card List Pos */
    .telemetri-left-col {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        padding: 12px;
        height: 640px;
        display: flex;
        flex-direction: column;
    }
    .filter-pills-row {
        display: flex;
        gap: 4px;
        margin-bottom: 10px;
    }
    .filter-pill {
        border-radius: 20px;
        padding: 3px 12px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
        cursor: pointer;
    }
    .filter-pill.active {
        background: #1b5fb5;
        border-color: #1b5fb5;
        color: #ffffff;
    }

    .telemetri-scroll-list {
        overflow-y: auto;
        flex-grow: 1;
        padding-right: 4px;
    }
    .telemetri-scroll-list::-webkit-scrollbar {
        width: 4px;
    }
    .telemetri-scroll-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .device-card {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px 12px;
        margin-bottom: 8px;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .device-card:hover {
        border-color: #1b5fb5;
        box-shadow: 0 2px 6px rgba(27, 95, 181, 0.12);
    }
    .device-card.selected-device {
        border: 1.5px solid #1b5fb5 !important;
        background: #f8fbff;
    }

    .device-dot-status {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }
    .device-tag {
        font-size: 0.68rem;
        padding: 2px 6px;
        border-radius: 4px;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        display: inline-block;
    }

    /* 3. Kolom Tengah: Peta */
    .telemetri-map-card {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        height: 640px;
        position: relative;
        overflow: hidden;
    }
    #mapHigertech {
        height: 100%;
        width: 100%;
    }

    /* Floating Controls on Map */
    .map-top-left-controls {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 1000;
        display: flex;
        gap: 4px;
    }
    .map-switch-btn {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 4px;
        cursor: pointer;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .map-switch-btn.active {
        background: #1b5fb5;
        border-color: #1b5fb5;
        color: #ffffff;
    }

    .map-floating-legend {
        position: absolute;
        bottom: 20px;
        left: 14px;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 0.75rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        max-width: 190px;
    }

    /* 4. Kolom Kanan: Grafik & CCTV */
    .telemetri-right-col {
        height: 640px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .chart-panel-card {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        padding: 12px 14px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .cctv-panel-card {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        padding: 12px 14px;
        height: 220px;
        display: flex;
        flex-direction: column;
    }

    /* 5. Bottom Marquee Ticker */
    .telemetri-bottom-marquee {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 0.8rem;
        margin-top: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
    }

    /* Telemetri Custom Marker "T" */
    .marker-t-pin {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 13px;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }
</style>
@endpush

@section('konten')

<!-- ================= 1. TOP STAT PILLS & METRIC BAR (PERSIS SEPERTI bwskal1.higertech.com) ================= -->
<div class="telemetri-top-bar">
    <div class="d-flex flex-wrap align-items-center justify-content-between">
        
        <!-- Filter Tab Kategori Pos -->
        <div class="d-flex flex-wrap gap-2 mb-2 mb-lg-0">
            <button type="button" class="metric-pill-btn active" id="tabPosDugaAir" onclick="filterByTopType('AWLR', this)">
                POS DUGA AIR <span class="badge-pill-count bg-white text-primary">{{ $statistik['total_awlr'] }}</span>
            </button>
            <button type="button" class="metric-pill-btn" id="tabCurahHujan" onclick="filterByTopType('ARR', this)">
                CURAH HUJAN <span class="badge-pill-count badge-success text-white">{{ $statistik['total_arr'] }}</span>
            </button>
            <button type="button" class="metric-pill-btn" id="tabPdaPch" onclick="filterByTopType('PDA_PCH', this)">
                PDA & PCH <span class="badge-pill-count badge-warning text-dark">{{ $statistik['total_pch'] }}</span>
            </button>
        </div>

        <!-- 3 Kotak KPI Stat -->
        <div class="d-flex flex-wrap gap-2">
            <!-- TOTAL POS -->
            <div class="kpi-stat-item">
                <i class="fas fa-desktop stat-icon text-primary"></i>
                <div>
                    <div style="font-size: 0.65rem; color: #64748b; font-weight: 700;">TOTAL POS</div>
                    <div class="font-weight-bold text-gray-900" style="font-size: 1.15rem; line-height: 1;">{{ $statistik['total_pos'] }}</div>
                </div>
            </div>

            <!-- POS ONLINE -->
            <div class="kpi-stat-item">
                <i class="fas fa-signal stat-icon text-success"></i>
                <div>
                    <div style="font-size: 0.65rem; color: #64748b; font-weight: 700;">POS ONLINE</div>
                    <div class="font-weight-bold text-success" style="font-size: 1.15rem; line-height: 1;">{{ $statistik['pos_online'] }}</div>
                </div>
            </div>

            <!-- POS OFFLINE -->
            <div class="kpi-stat-item">
                <i class="fas fa-exclamation-triangle stat-icon text-danger"></i>
                <div>
                    <div style="font-size: 0.65rem; color: #64748b; font-weight: 700;">POS OFFLINE</div>
                    <div class="font-weight-bold text-danger" style="font-size: 1.15rem; line-height: 1;">{{ $statistik['pos_offline'] }}</div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ================= 2. TIGA KOLOM TERPADU: LIST POS | PETA | GRAFIK & CCTV ================= -->
<div class="row no-gutters" style="margin-left: -5px; margin-right: -5px;">

    <!-- KOLOM 1 (KIRI): LIST PERANGKAT TELEMETRI (WIDTH ~300PX) -->
    <div class="col-xl-3 col-lg-4 px-1 mb-2 mb-lg-0">
        <div class="telemetri-left-col">

            <!-- Search Box -->
            <div class="input-group input-group-sm mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0 text-muted"><i class="fas fa-search"></i></span>
                </div>
                <input type="text" id="inputFilterDevice" class="form-control border-left-0" placeholder="Cari nama pos/device id ..." onkeyup="filterDeviceCards()">
            </div>

            <!-- Filter Status Pill (Semua Pos, Pos Online, Pos Offline) -->
            <div class="filter-pills-row">
                <button type="button" class="filter-pill active" onclick="filterDeviceStatus('semua', this)">Semua Pos</button>
                <button type="button" class="filter-pill" onclick="filterDeviceStatus('online', this)">Pos Online</button>
                <button type="button" class="filter-pill" onclick="filterDeviceStatus('offline', this)">Pos Offline</button>
            </div>

            <!-- Daftar Kartu Pos Telemetri (Scrollable) -->
            <div class="telemetri-scroll-list" id="deviceCardsContainer">
                @foreach($posList as $index => $pos)
                    <div class="device-card {{ $index === 0 ? 'selected-device' : '' }}" 
                         id="devCard_{{ $pos['id'] }}"
                         data-id="{{ $pos['id'] }}"
                         data-nama="{{ strtolower($pos['nama']) }}"
                         data-kode="{{ strtolower($pos['kode']) }}"
                         data-tipe="{{ $pos['tipe'] }}"
                         data-status="{{ $pos['status_alat'] === 'Online' ? 'online' : 'offline' }}"
                         onclick="selectTelemetryPos({{ $pos['id'] }})">

                        <!-- Baris 1: Nama Pos + Waktu -->
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="font-weight-bold text-gray-900 text-truncate pr-1" style="font-size: 0.82rem;">
                                <span class="device-dot-status {{ $pos['status_alat'] === 'Online' ? 'bg-success' : 'bg-danger' }}"></span>
                                {{ strtoupper($pos['nama']) }}
                            </div>
                            <div class="text-muted" style="font-size: 0.68rem; white-space: nowrap;">
                                Hari Ini, {{ $pos['jam_update'] }}
                            </div>
                        </div>

                        <!-- Baris 2: Sub-badge Device & Voltase -->
                        <div class="mb-2">
                            <span class="device-tag">Higertech</span>
                            <span class="device-tag font-monospace">{{ $pos['kode'] }}</span>
                            <span class="device-tag text-warning">
                                <i class="fas fa-bolt text-warning mr-1"></i>{{ $pos['voltase'] > 0 ? $pos['voltase'] . ' V' : 'Offline' }}
                            </span>
                        </div>

                        <!-- Baris 3: Tiga Kolom (TMA | Debit | Status Siaga) -->
                        <div class="row text-center no-gutters pt-1 border-top align-items-center">
                            <div class="col-4 border-right">
                                <div class="font-weight-bold text-gray-900" style="font-size: 0.95rem;">
                                    @if($pos['tipe'] === 'AWLR')
                                        {{ $pos['tma'] }} m
                                    @elseif($pos['tipe'] === 'ARR')
                                        {{ $pos['curah_hujan'] }} mm
                                    @else
                                        {{ $pos['tma'] }} m
                                    @endif
                                </div>
                                <div style="font-size: 0.62rem; color: #64748b;">
                                    {{ $pos['tipe'] === 'AWLR' ? 'Tinggi Muka Air' : ($pos['tipe'] === 'ARR' ? 'Curah Hujan' : 'TMA & Hujan') }}
                                </div>
                            </div>

                            <div class="col-4 border-right">
                                <div class="font-weight-bold text-gray-900" style="font-size: 0.95rem;">
                                    {{ $pos['debit'] ?? '-' }}
                                </div>
                                <div style="font-size: 0.62rem; color: #64748b;">Debit</div>
                            </div>

                            <div class="col-4">
                                @if($pos['status_siaga'] === 'Normal')
                                    <span class="badge badge-light border text-muted px-1" style="font-size: 0.65rem;">TANPA STATUS</span>
                                @elseif($pos['status_siaga'] === 'Waspada')
                                    <span class="badge badge-warning text-dark px-1" style="font-size: 0.65rem;">WASPADA</span>
                                @elseif($pos['status_siaga'] === 'Siaga' || $pos['status_siaga'] === 'Awas')
                                    <span class="badge badge-danger px-1" style="font-size: 0.65rem;">{{ strtoupper($pos['status_siaga']) }}</span>
                                @else
                                    <span class="badge badge-secondary px-1" style="font-size: 0.65rem;">OFFLINE</span>
                                @endif
                                <div style="font-size: 0.62rem; color: #64748b;">Status Siaga</div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    </div>

    <!-- KOLOM 2 (TENGAH): PETA INTERAKTIF -->
    <div class="col-xl-6 col-lg-5 px-1 mb-2 mb-lg-0">
        <div class="telemetri-map-card">

            <!-- Switcher Top-Left (Peta | Satelit | Layers) -->
            <div class="map-top-left-controls">
                <button type="button" class="map-switch-btn active" id="btnMapPeta" onclick="switchBaseMap('osm')">Peta</button>
                <button type="button" class="map-switch-btn" id="btnMapSat" onclick="switchBaseMap('sat')">Satelit</button>
                <div class="dropdown d-inline-block">
                    <button type="button" class="map-switch-btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-layer-group mr-1 text-primary"></i> Layers
                    </button>
                    <div class="dropdown-menu shadow border-0 p-3" style="font-size: 0.8rem; width: 220px;">
                        <h6 class="dropdown-header px-0 text-dark font-weight-bold">Layer Aktif:</h6>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="checkTipeAwlr" checked onchange="togglePosGroup('AWLR', this.checked)">
                            <label class="custom-control-label" for="checkTipeAwlr">Pos Duga Air (AWLR)</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="checkTipeArr" checked onchange="togglePosGroup('ARR', this.checked)">
                            <label class="custom-control-label" for="checkTipeArr">Pos Curah Hujan (ARR)</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="checkTipePch" checked onchange="togglePosGroup('PDA_PCH', this.checked)">
                            <label class="custom-control-label" for="checkTipePch">PDA & PCH Terpadu</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kontainer Peta Leaflet -->
            <div id="mapHigertech"></div>

            <!-- Floating Legend Bottom-Left (Persis Screenshot 5) -->
            <div class="map-floating-legend">
                <div class="font-weight-bold text-gray-900 mb-1" style="font-size: 0.78rem;">Tipe Pos</div>
                <div class="d-flex align-items-center mb-1">
                    <span style="background: #1b5fb5; width: 16px; height: 16px; border-radius: 50%; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 9px; font-weight: bold; margin-right: 6px;">T</span>
                    <span>Pos Duga Air</span>
                </div>
                <div class="d-flex align-items-center mb-1">
                    <span style="background: #10b981; width: 16px; height: 16px; border-radius: 50%; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 9px; font-weight: bold; margin-right: 6px;">T</span>
                    <span>Pos Curah Hujan</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span style="background: #f59e0b; width: 16px; height: 16px; border-radius: 50%; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 9px; font-weight: bold; margin-right: 6px;">T</span>
                    <span>PDA & PCH</span>
                </div>
                <div class="text-muted border-top pt-1" style="font-size: 0.65rem;">
                    T = Telemetri
                </div>
            </div>

        </div>
    </div>

    <!-- KOLOM 3 (KANAN): GRAFIK & MONITORING CCTV (WIDTH ~300PX - 320PX) -->
    <div class="col-xl-3 col-lg-3 px-1">
        <div class="telemetri-right-col">

            <!-- Card Atas: Grafik Fluktuasi -->
            <div class="chart-panel-card">
                <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                    <i class="fas fa-chart-line text-primary mr-2"></i>
                    <div>
                        <div class="text-muted" style="font-size: 0.65rem; text-transform: uppercase;">GRAFIK</div>
                        <div class="font-weight-bold text-gray-900" style="font-size: 0.88rem;" id="chartStationTitle">
                            AWLR AMBAWANG
                        </div>
                    </div>
                </div>

                <div class="flex-grow-1 position-relative" style="min-height: 220px;">
                    <canvas id="canvasChartHigertech"></canvas>
                </div>

                <div class="text-center text-muted border-top pt-1 mt-1" style="font-size: 0.68rem;">
                    Pembacaan periode 12-24 jam terakhir
                </div>
            </div>

            <!-- Card Bawah: Monitoring CCTV (Persis Screenshot 5) -->
            <div class="cctv-panel-card">
                <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                    <i class="fas fa-video text-primary mr-2"></i>
                    <div>
                        <div class="text-muted" style="font-size: 0.65rem; text-transform: uppercase;">MONITORING</div>
                        <div class="font-weight-bold text-gray-900" style="font-size: 0.88rem;">CCTV</div>
                    </div>
                </div>

                <div class="flex-grow-1 d-flex flex-column align-items-center justify-content-center text-center p-2" style="background: #f8fafc; border-radius: 6px; border: 1px dashed #cbd5e1;">
                    <i class="fas fa-video-slash fa-2x text-muted mb-2"></i>
                    <div class="font-weight-bold text-gray-800" style="font-size: 0.82rem;">CCTV Tidak Tersedia</div>
                    <div class="text-muted" style="font-size: 0.68rem; line-height: 1.3;">
                        Pos ini belum memiliki kamera CCTV yang terdaftar atau feed belum aktif.
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- ================= 3. BOTTOM MARQUEE TICKER (PERSIS SEPERTI bwskal1.higertech.com) ================= -->
<div class="telemetri-bottom-marquee">
    <marquee behavior="scroll" direction="left" scrollamount="5" onmouseover="this.stop();" onmouseout="this.start();">
        <span class="mr-4">
            <i class="fas fa-circle text-danger mr-1" style="font-size: 8px;"></i>
            <strong>AWLR KEMBAYAN</strong> (Offline) <span class="badge badge-danger">AWAS (28,52 m)</span>
        </span>
        <span class="mr-4">
            <i class="fas fa-circle text-warning mr-1" style="font-size: 8px;"></i>
            <strong>AWLR NANGA PINOH</strong> <span class="badge badge-warning text-dark">SIAGA (6,20 m)</span>
        </span>
        <span class="mr-4">
            <i class="fas fa-circle text-warning mr-1" style="font-size: 8px;"></i>
            <strong>ARR AMBAWANG</strong> <span class="badge badge-warning text-dark">WASPADA (38,5 mm)</span>
        </span>
        <span class="mr-4">
            <i class="fas fa-circle text-success mr-1" style="font-size: 8px;"></i>
            <strong>AWLR RASAU JAYA</strong> (Normal, 2,20 m)
        </span>
        <span class="mr-4">
            <i class="fas fa-circle text-success mr-1" style="font-size: 8px;"></i>
            <strong>AWLR PEDALAMAN</strong> (Normal, 0,41 m)
        </span>
        <span class="mr-4">
            <i class="fas fa-circle text-success mr-1" style="font-size: 8px;"></i>
            <strong>AWLR MELAWI HILIR</strong> (Normal, 4,85 m)
        </span>
    </marquee>
</div>

@endsection

@push('scripts')
<script>
    const posDataset = @json($posList);
    let mapLeaflet;
    let markersLayerGroup = {};
    let lineChartHigertech;
    let currentSelectedStation = null;

    // Basemaps
    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap | BWS Kalimantan I'
    });
    const satLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 18,
        attribution: 'Tiles &copy; Esri'
    });

    document.addEventListener("DOMContentLoaded", function () {
        initClock();
        initMap();
        initChart();

        if (posDataset.length > 0) {
            selectTelemetryPos(posDataset[0].id, false);
        }
    });

    // Realtime Clock on Navbar
    function initClock() {
        setInterval(() => {
            const now = new Date();
            const dateStr = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
            const el = document.getElementById('topLiveClock');
            if (el) el.innerText = `${dateStr} ${timeStr}`;
        }, 1000);
    }

    // Leaflet Map with "T" Markers
    function initMap() {
        mapLeaflet = L.map('mapHigertech', {
            center: [-0.05, 110.80],
            zoom: 7,
            layers: [osmLayer],
            zoomControl: false
        });

        // Reposition zoom control to bottom right
        L.control.zoom({ position: 'bottomright' }).addTo(mapLeaflet);

        posDataset.forEach(pos => {
            let bgColor = '#1b5fb5'; // Pos Duga Air
            if (pos.tipe === 'ARR') bgColor = '#10b981'; // Curah Hujan
            else if (pos.tipe === 'PDA_PCH') bgColor = '#f59e0b'; // PDA & PCH

            const iconT = L.divIcon({
                className: 'custom-t-div-icon',
                html: `<div class="marker-t-pin" style="background: ${bgColor};">T</div>`,
                iconSize: [26, 26],
                iconAnchor: [13, 13]
            });

            const marker = L.marker([pos.lat, pos.lng], { icon: iconT });

            const popupContent = `
                <div style="font-family: sans-serif; font-size: 12px; min-width: 190px;">
                    <div style="font-weight: 700; color: #1b5fb5; margin-bottom: 2px;">${pos.nama}</div>
                    <div style="font-size: 11px; color: #64748b; margin-bottom: 6px;">${pos.tipe_nama} &bull; ${pos.kabupaten}</div>
                    <div style="background: #f1f5f9; padding: 5px 8px; border-radius: 4px; font-size: 11px; margin-bottom: 6px;">
                        <strong>${pos.tipe === 'AWLR' ? 'TMA' : 'Curah Hujan'}:</strong> 
                        ${pos.tipe === 'AWLR' ? pos.tma + ' m' : pos.curah_hujan + ' mm'}
                        <br><strong>Status:</strong> ${pos.status_siaga}
                        <br><strong>Baterai:</strong> ${pos.voltase > 0 ? pos.voltase + ' V' : 'Offline'}
                    </div>
                    <button class="btn btn-xs btn-primary btn-block text-white" style="font-size: 11px;" onclick="selectTelemetryPos(${pos.id})">
                        Pilih Pos
                    </button>
                </div>
            `;

            marker.bindPopup(popupContent);
            marker.on('click', () => selectTelemetryPos(pos.id));
            marker.addTo(mapLeaflet);

            if (!markersLayerGroup[pos.tipe]) {
                markersLayerGroup[pos.tipe] = [];
            }
            markersLayerGroup[pos.tipe].push(marker);
        });
    }

    function switchBaseMap(type) {
        if (type === 'sat') {
            mapLeaflet.removeLayer(osmLayer);
            mapLeaflet.addLayer(satLayer);
            document.getElementById('btnMapSat').classList.add('active');
            document.getElementById('btnMapPeta').classList.remove('active');
        } else {
            mapLeaflet.removeLayer(satLayer);
            mapLeaflet.addLayer(osmLayer);
            document.getElementById('btnMapPeta').classList.add('active');
            document.getElementById('btnMapSat').classList.remove('active');
        }
    }

    function togglePosGroup(type, isChecked) {
        if (markersLayerGroup[type]) {
            markersLayerGroup[type].forEach(m => {
                if (isChecked) mapLeaflet.addLayer(m);
                else mapLeaflet.removeLayer(m);
            });
        }
    }

    // Chart.js (Grafik Fluktuasi)
    function initChart() {
        const ctx = document.getElementById('canvasChartHigertech').getContext('2d');
        const defaultData = posDataset[0] || {
            history_jam: ['01:00', '03:00', '05:00', '07:00', '09:00'],
            history_tma: [26.1, 26.8, 27.6, 28.1, 28.52]
        };

        lineChartHigertech = new Chart(ctx, {
            type: 'line',
            data: {
                labels: defaultData.history_jam || [],
                datasets: [{
                    label: 'Tinggi Muka Air',
                    data: defaultData.history_tma || [],
                    borderColor: '#1b5fb5',
                    backgroundColor: 'rgba(27, 95, 181, 0.08)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3.5,
                    pointBackgroundColor: '#1b5fb5'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                    y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } } }
                }
            }
        });
    }

    function updateTelemetryChart(pos) {
        if (!lineChartHigertech) return;
        const isAwlr = (pos.tipe === 'AWLR');
        const color = isAwlr ? '#1b5fb5' : '#10b981';
        const bg = isAwlr ? 'rgba(27, 95, 181, 0.08)' : 'rgba(16, 185, 129, 0.08)';

        lineChartHigertech.data.labels = pos.history_jam || ['01:00', '03:00', '05:00', '07:00', '09:00'];
        lineChartHigertech.data.datasets[0].label = isAwlr ? 'Tinggi Muka Air (m)' : 'Curah Hujan (mm)';
        lineChartHigertech.data.datasets[0].data = pos.history_tma || [0, 0, 0, 0, 0];
        lineChartHigertech.data.datasets[0].borderColor = color;
        lineChartHigertech.data.datasets[0].backgroundColor = bg;
        lineChartHigertech.data.datasets[0].pointBackgroundColor = color;
        lineChartHigertech.update();
    }

    // Select Pos from Card or Map
    function selectTelemetryPos(posId, shouldFly = true) {
        const pos = posDataset.find(p => p.id == posId);
        if (!pos) return;

        currentSelectedStation = pos;

        // Card selected highlight
        document.querySelectorAll('.device-card').forEach(el => {
            if (el.getAttribute('data-id') == posId) {
                el.classList.add('selected-device');
                el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                el.classList.remove('selected-device');
            }
        });

        // Map pan
        if (mapLeaflet && shouldFly) {
            mapLeaflet.flyTo([pos.lat, pos.lng], 9, { duration: 1.0 });
        }

        // Title update
        document.getElementById('chartStationTitle').innerText = pos.nama.toUpperCase();

        // Chart update
        updateTelemetryChart(pos);
    }

    // Filter Search Box
    function filterDeviceCards() {
        const query = document.getElementById('inputFilterDevice').value.toLowerCase().trim();
        const cards = document.querySelectorAll('.device-card');

        cards.forEach(card => {
            const nama = card.getAttribute('data-nama');
            const kode = card.getAttribute('data-kode');
            if (nama.includes(query) || kode.includes(query)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Filter Status Pills (Semua Pos, Pos Online, Pos Offline)
    function filterDeviceStatus(status, btn) {
        document.querySelectorAll('.filter-pill').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const cards = document.querySelectorAll('.device-card');
        cards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            if (status === 'semua' || cardStatus === status) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Filter by Top Pills (AWLR, ARR, PDA_PCH)
    function filterByTopType(tipe, btn) {
        document.querySelectorAll('.metric-pill-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const cards = document.querySelectorAll('.device-card');
        cards.forEach(card => {
            const cardTipe = card.getAttribute('data-tipe');
            if (cardTipe === tipe) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
@endpush

@extends('layout.master')

@section('title', 'Dashboard Data - POS Duga Air - FEWS BWS Kalimantan I')

@push('styles')
<style>
    /* Styling Persis Referensi FEWS BWS Kalimantan I (fews.bwskal1.or.id/dashboard-data/pos-duga-air) */
    .fews-filter-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .fews-filter-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 4px;
    }
    .fews-select {
        height: 38px;
        font-size: 0.85rem;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
    }

    #mapFewsPos {
        height: 380px;
        width: 100%;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }

    /* Tabel Header Gelap Khas FEWS BWSK */
    .table-fews thead th {
        background-color: #1a2e4c;
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        border: none;
        padding: 10px 12px;
        white-space: nowrap;
    }
    .table-fews tbody td {
        font-size: 0.8rem;
        padding: 9px 12px;
        vertical-align: middle;
        white-space: nowrap;
        border-color: #f1f5f9;
    }
</style>
@endpush

@section('konten')

<!-- Layout Dua Kolom: Kiri Panel Filter FEWS | Kanan Map & Table -->
<div class="row">

    <!-- PANEL FILTER KIRI (Persis Screenshot 2: fews.bwskal1.or.id) -->
    <div class="col-xl-3 col-lg-4 mb-4">
        <div class="fews-filter-panel">

            <form action="{{ route('hidrologi.data') }}" method="GET">

                <!-- 1. Dashboard Type -->
                <div class="form-group mb-3">
                    <label class="fews-filter-label">Dashboard Type</label>
                    <select class="form-control fews-select font-weight-bold text-dark" name="type">
                        <option value="data" selected>Dashboard Data</option>
                    </select>
                </div>

                <!-- 2. Filter Sebaran -->
                <div class="form-group mb-3">
                    <label class="fews-filter-label">Filter Sebaran</label>
                    <select class="form-control fews-select" name="tipe" onchange="this.form.submit()">
                        <option value="semua" {{ ($tipeFilter ?? 'semua') === 'semua' ? 'selected' : '' }}>SEMUA TIPE POS</option>
                        <option value="AWLR" {{ ($tipeFilter ?? '') === 'AWLR' ? 'selected' : '' }}>POS Duga Air</option>
                        <option value="ARR" {{ ($tipeFilter ?? '') === 'ARR' ? 'selected' : '' }}>POS Curah Hujan</option>
                        <option value="PDA_PCH" {{ ($tipeFilter ?? '') === 'PDA_PCH' ? 'selected' : '' }}>PDA & PCH</option>
                    </select>
                </div>

                <!-- 3. Filter POS -->
                <div class="form-group mb-3">
                    <label class="fews-filter-label">Filter POS</label>
                    <select class="form-control fews-select" name="kabupaten" onchange="this.form.submit()">
                        <option value="semua" {{ ($kabupatenFilter ?? 'semua') === 'semua' ? 'selected' : '' }}>SEMUA POS</option>
                        @foreach($kabupatenList as $kab)
                            <option value="{{ $kab }}" {{ ($kabupatenFilter ?? '') === $kab ? 'selected' : '' }}>Wilayah {{ $kab }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 4. Filter Tanggal -->
                <div class="form-group mb-3">
                    <label class="fews-filter-label">Filter Tanggal</label>
                    <div class="input-group">
                        <input type="text" class="form-control fews-select" value="{{ date('d F Y') }}" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text bg-white"><i class="far fa-calendar-alt text-muted"></i></span>
                        </div>
                    </div>
                </div>

                <!-- 5. View -->
                <div class="form-group mb-3">
                    <label class="fews-filter-label">View</label>
                    <select class="form-control fews-select" name="view">
                        <option value="both" selected>Table + Maps</option>
                        <option value="map">Maps Only</option>
                        <option value="table">Table Only</option>
                    </select>
                </div>

                <div class="pt-2">
                    <a href="{{ route('hidrologi.data') }}" class="btn btn-sm btn-light border btn-block text-muted">
                        Reset Filter
                    </a>
                </div>

            </form>

        </div>
    </div>

    <!-- AREA KANAN: BREADCRUMB, MAP, DAN DATA TABLE -->
    <div class="col-xl-9 col-lg-8 mb-4">

        <!-- Breadcrumb Header FEWS -->
        <div class="mb-3">
            <h5 class="font-weight-bold text-gray-900 mb-0">
                Dashboard Data <i class="fas fa-chevron-right text-muted mx-2" style="font-size: 11px;"></i> <span class="text-primary">{{ ($tipeFilter ?? '') === 'ARR' ? 'POS Curah Hujan' : 'POS Duga Air' }}</span>
            </h5>
        </div>

        <!-- Peta Leaflet (Persis Posisi Screenshot 2) -->
        <div class="card shadow-sm border-0 mb-4 overflow-hidden position-relative">
            <div id="mapFewsPos"></div>
            <!-- Switcher Peta & Satelit -->
            <div class="position-absolute" style="top: 10px; left: 10px; z-index: 1000;">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-light border font-weight-bold active" id="btnOsmFews" onclick="switchFewsLayer('osm')">Peta</button>
                    <button type="button" class="btn btn-light border font-weight-bold" id="btnSatFews" onclick="switchFewsLayer('sat')">Satelit</button>
                </div>
            </div>
        </div>

        <!-- Tabel Data (Persis Tampilan Screenshot 3) -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-fews mb-0">
                        <thead>
                            <tr>
                                <th style="width: 40px;">No</th>
                                <th>NAMA POS</th>
                                <th>TIPE</th>
                                <th>DAS</th>
                                <th>Tanggal</th>
                                <th>Jam (WIB)</th>
                                <th>TMA (m)</th>
                                <th>Kenaikan Air (cm/jam)</th>
                                <th>Debit (m³/s)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posList as $index => $pos)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="font-weight-bold text-primary">{{ $pos['nama'] }}</td>
                                    <td>
                                        <span class="badge {{ $pos['tipe'] === 'AWLR' ? 'badge-primary' : 'badge-success' }}">
                                            {{ $pos['tipe'] }}
                                        </span>
                                    </td>
                                    <td>{{ $pos['das'] }}</td>
                                    <td>{{ date('d M Y') }}</td>
                                    <td>{{ $pos['jam_update'] }}</td>
                                    <td class="font-weight-bold text-dark">
                                        {{ $pos['tipe'] === 'AWLR' ? number_format($pos['tma'], 2) : number_format($pos['curah_hujan'], 1) . ' mm' }}
                                    </td>
                                    <td>
                                        @if($pos['status_siaga'] === 'Waspada' || $pos['status_siaga'] === 'Siaga')
                                            <span class="text-danger font-weight-bold">+2.4 cm</span>
                                        @else
                                            <span class="text-muted">0.0 cm</span>
                                        @endif
                                    </td>
                                    <td>{{ $pos['debit'] ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        Tidak ada data yang sesuai filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
    const fewsData = @json($posList);
    let mapFews;
    let fewsOsm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap | BWS Kalimantan I'
    });
    let fewsSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 18,
        attribution: 'Tiles &copy; Esri'
    });

    document.addEventListener("DOMContentLoaded", function () {
        mapFews = L.map('mapFewsPos', {
            center: [-0.05, 110.80],
            zoom: 7,
            layers: [fewsOsm]
        });

        fewsData.forEach(pos => {
            let color = '#1b5fb5';
            if (pos.tipe === 'ARR') color = '#10b981';

            L.circleMarker([pos.lat, pos.lng], {
                radius: 7,
                fillColor: color,
                color: '#ffffff',
                weight: 1.5,
                fillOpacity: 0.9
            }).bindPopup(`
                <div style="font-size: 11px;">
                    <strong>${pos.nama}</strong><br>
                    TMA: ${pos.tma || pos.curah_hujan} &bull; ${pos.kabupaten}
                </div>
            `).addTo(mapFews);
        });
    });

    function switchFewsLayer(type) {
        if (type === 'sat') {
            mapFews.removeLayer(fewsOsm);
            mapFews.addLayer(fewsSat);
            document.getElementById('btnSatFews').classList.add('active');
            document.getElementById('btnOsmFews').classList.remove('active');
        } else {
            mapFews.removeLayer(fewsSat);
            mapFews.addLayer(fewsOsm);
            document.getElementById('btnOsmFews').classList.add('active');
            document.getElementById('btnSatFews').classList.remove('active');
        }
    }
</script>
@endpush

@extends('layout.master')

@section('title', 'BWS KALIMANTAN I - SIG')
@section('hide_footer', true)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/peta.css') }}">
@endpush

@section('konten')
<div class="bws-page-container">

    <!-- ── SIDEBAR KIRI ── -->
    <aside class="bws-sidebar">

        <!-- 1. Dashboard Type -->
        <div class="bws-filter-group">
            <label class="bws-filter-label">Dashboard Type</label>
            <select class="form-control bws-select-control" id="filterDashboardType">
                <option value="data" selected>Dashboard Data</option>
            </select>
        </div>

        <!-- 2. View Mode -->
        <div class="bws-filter-group">
            <label class="bws-filter-label">View</label>
            <select class="form-control bws-select-control"
                    id="filterViewMode"
                    onchange="switchViewMode(this.value)">
                <option value="both" selected>Table + Maps</option>
                <option value="maps">Maps Only</option>
                <option value="table">Table Only</option>
            </select>
        </div>

        @auth
        <!-- Tombol Akses Kelola / Upload File GIS -->
        <div class="mt-3 pt-2">
            <a href="{{ route('admin.kelola-gis') }}" 
               class="btn btn-primary btn-block font-weight-bold shadow-sm py-2 d-flex align-items-center justify-content-center" 
               style="border-radius: 8px; background: #2563eb; border-color: #2563eb; font-size: 13px;">
                <i class="fas fa-cloud-upload-alt mr-2"></i> Upload File GIS
            </a>
        </div>
        @else
        <!-- Mode Publik Info Card untuk Pengunjung -->
        <div class="card border-0 bg-light shadow-sm text-center p-3 mt-2" style="border-radius: 8px;">
            <div class="mb-1 text-primary">
                <i class="fas fa-globe-asia fa-lg"></i>
            </div>
            <div class="font-weight-bold text-dark" style="font-size: 12px;">Mode Publik / Viewer</div>
            <div class="text-muted mt-1" style="font-size: 10.5px; line-height: 1.4;">
                Anda dapat melihat peta spasial lengkap dan mengunduh tabel data CSV.
            </div>
            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary mt-2 font-weight-bold" style="font-size: 11px;">
                <i class="fas fa-sign-in-alt mr-1"></i> Login Staf
            </a>
        </div>
        @endauth

    </aside>

    <!-- ── KONTEN AREA KANAN (MAP & TABEL) ── -->
    <main class="bws-content">

        <!-- Breadcrumb & Quick Focus Toolbar -->
        <div class="bws-breadcrumb d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
            <div class="d-flex align-items-center">
                <span class="bws-breadcrumb-parent">Dashboard Data</span>
                <i class="fas fa-chevron-right bws-breadcrumb-arrow"></i>
                <span class="bws-breadcrumb-current"></span>
            </div>
            <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                <span class="small font-weight-bold text-muted mr-1" style="font-size:11.5px;"><i class="fas fa-crosshairs text-primary mr-1"></i>Fokus Wilayah:</span>
                <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold py-1 px-2 shadow-sm" style="font-size: 11px; border-radius: 6px;" onclick="focusRegion('sambas')" title="Fokus ke Daerah Irigasi Sambas / Pemangkat">
                    📍 Sambas / Pemangkat
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold py-1 px-2 shadow-sm" style="font-size: 11px; border-radius: 6px;" onclick="focusRegion('kubu_raya')" title="Fokus ke Daerah Irigasi Kubu Raya">
                    📍 Kubu Raya
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold py-1 px-2 shadow-sm" style="font-size: 11px; border-radius: 6px;" onclick="focusRegion('ketapang')" title="Fokus ke Daerah Irigasi Ketapang">
                    📍 Ketapang
                </button>
                <button type="button" class="btn btn-sm btn-outline-dark font-weight-bold py-1 px-2 shadow-sm" style="font-size: 11px; border-radius: 6px;" onclick="focusRegion('all')" title="Tampilkan Semua Wilayah Kalimantan Barat">
                    🗺️ Semua
                </button>
            </div>
        </div>

        <!-- Map Card Container -->
        <div class="bws-map-card" id="section-map">
            <div class="bws-map-loading" id="map-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <span>Memuat koordinat GIS ArcMap...</span>
            </div>
            <div id="map"></div>
        </div>

        <!-- Table Card Container -->
        <div class="bws-table-card" id="section-table">
            <div class="bws-table-toolbar">
                <div class="bws-table-title">
                    <span>Daftar Pos Daerah Irigasi &amp; Data Spasial</span>
                    <span class="badge badge-light border text-muted px-2 py-1" style="font-size:12px;" id="badge-total-file-tabel">
                        {{ count($geojsonFiles ?? []) }} File
                    </span>
                </div>

                <div class="bws-table-controls">
                    <div class="bws-search-input-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text"
                               id="cari-tabel-koordinat"
                               placeholder="Cari nama pos/objek/file..."
                               onkeyup="filterTabelKoordinat(this.value)">
                    </div>
                </div>
            </div>

            <div class="bws-table-responsive">
                <table class="bws-custom-table" id="tabel-lokasi">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No.</th>
                            <th width="28%">Nama Pos / Objek</th>
                            <th width="18%">Wilayah / Kategori</th>
                            <th width="12%" class="text-center">Latitude (Y)</th>
                            <th width="12%" class="text-center">Longitude (X)</th>
                            <th width="17%">Sumber File GIS</th>
                            <th width="8%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-lokasi">
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-spinner fa-spin text-primary mr-2"></i>
                                Membaca data koordinat GIS ArcMap...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="bws-table-footer">
                <span id="tabel-footer-info">Memuat data koordinat...</span>
                <span>
                    <i class="fas fa-sync-alt text-success mr-1"></i>
                    Otomatis sinkron dengan file yang diunggah
                </span>
            </div>
        </div>

    </main>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.9.0/proj4.js"></script>
<script>
    window.availableTypes = {!! json_encode($availableTypes ?? ['baku'=>true,'fungsional'=>true,'potensial'=>true,'saluran'=>true,'bangunan'=>true,'custom'=>false]) !!};
    window.geojsonFiles   = {!! json_encode($geojsonFiles ?? []) !!};
    window.canManageGis   = {{ Auth::check() ? 'true' : 'false' }};
</script>
<script src="{{ asset('js/peta-map.js') }}"></script>
@endpush

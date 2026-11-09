@extends('admin.layout')

@section('title', 'Dashboard Eksekutif WebGIS — BWS Kalimantan I')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* ── STIL KARTU STATISTIK VIBRANT ALA SIPERISDA BWS KALIMANTAN I ── */
    .siperisda-card {
        border-radius: 12px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 124px;
        border: none;
    }

    .siperisda-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.14);
    }

    .siperisda-body {
        padding: 16px 18px 12px;
        position: relative;
        z-index: 2;
    }

    .siperisda-value {
        font-size: 1.85rem;
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.5px;
        margin-bottom: 2px;
    }

    .siperisda-unit {
        font-size: 0.95rem;
        font-weight: 600;
        opacity: 0.9;
        margin-left: 3px;
    }

    .siperisda-label {
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 0.2px;
        opacity: 0.95;
    }

    .siperisda-icon {
        position: absolute;
        right: 14px;
        top: 14px;
        font-size: 2.5rem;
        opacity: 0.22;
        z-index: 1;
        transition: transform 0.3s ease;
    }

    .siperisda-card:hover .siperisda-icon {
        transform: scale(1.12);
        opacity: 0.35;
    }

    .siperisda-footer {
        background: rgba(0, 0, 0, 0.16);
        padding: 6px 18px;
        font-size: 0.74rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 2;
    }

    /* Warna Khas SIPERISDA (Sesuai Screenshot Referensi User) */
    .bg-sip-cyan   { background: linear-gradient(135deg, #00b4d8 0%, #0096c7 100%); }
    .bg-sip-green  { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .bg-sip-coral  { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }
    .bg-sip-mint   { background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); }
    .bg-sip-rose   { background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%); }
    .bg-sip-ocean  { background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); }

    /* ── STIL KARTU GRAFIK & KONTEN ── */
    .dashboard-panel-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .panel-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
    }

    .panel-card-title {
        font-size: 0.98rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .panel-card-body {
        padding: 20px;
    }

    /* Map Dashboard Preview */
    #map-preview {
        height: 380px;
        width: 100%;
        border-radius: 8px;
        z-index: 10;
    }

    /* Region focus button */
    .btn-focus-pill {
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 4px 12px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
        transition: all 0.2s;
    }

    .btn-focus-pill:hover,
    .btn-focus-pill.active {
        background: #2563eb;
        border-color: #2563eb;
        color: #ffffff;
    }
</style>
@endpush

@section('content')
<div class="anim-fade-in">

    <!-- ═══ HEADER BREADCRUMB & AKSI CEPAT (PERSIS REFERENSI FEWS BWS) ═══ -->
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4 pb-1" style="gap: 14px;">
        <div>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <h4 class="font-weight-bold mb-0" style="letter-spacing: 0.4px; font-size: 1.15rem; color: #1e293b;">
                    DASHBOARD UTAMA
                </h4>
                <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 0.72rem; border-radius: 6px; background: #174ea6;">
                    BWS KALIMANTAN I
                </span>
            </div>
            <p class="text-muted small mb-0 mt-1" style="font-size: 0.82rem;">
                Monitoring Spasial Daerah Irigasi Kewenangan Balai Wilayah Sungai Kalimantan I Pontianak
            </p>
        </div>

        <div class="d-flex align-items-center">
            <span class="badge badge-light border text-secondary px-3 py-2 font-weight-bold shadow-sm" style="border-radius: 20px; font-size: 0.8rem; background: #ffffff; border-color: #e2e8f0;">
                <i class="fas fa-calendar-alt text-primary mr-2"></i> {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </span>
        </div>
    </div>

    <!-- Flash Alerts -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 10px; border-left: 4px solid #10b981;">
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
    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 10px; border-left: 4px solid #ef4444;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle mr-2 text-danger fa-lg"></i>
            <div><strong>Gagal!</strong> {{ session('error') }}</div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- ═══ 1. ENAM KARTU METRIK VIBRANT (PERSIS GAMBAR REFERENSI 1: SIPERISDA BWS KALIMANTAN I) ═══ -->
    <div class="row mb-4" style="row-gap: 16px;">
        <!-- Card 1: Total Layer Spasial (Cyan) -->
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <a href="{{ route('admin.kelola-gis') }}" class="text-decoration-none text-reset d-block" title="Klik untuk Kelola & Upload File Spasial">
                <div class="siperisda-card bg-sip-cyan">
                    <div class="siperisda-body">
                        <i class="fas fa-layer-group siperisda-icon"></i>
                        <div class="siperisda-value">{{ $stats['total_files'] ?? count($geojsonFiles) }}</div>
                        <div class="siperisda-label">Total File Spasial</div>
                    </div>
                    <div class="siperisda-footer">
                        <span>File GeoJSON &amp; SHP</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card 2: Lahan Baku (Green) -->
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="siperisda-card bg-sip-green">
                <div class="siperisda-body">
                    <i class="fas fa-leaf siperisda-icon"></i>
                    <div class="siperisda-value">{{ str_replace(' Ha', '', $statistik['total_luas_baku'] ?? '24.500') }}<span class="siperisda-unit">Ha</span></div>
                    <div class="siperisda-label">Lahan Baku Siap Tanam</div>
                </div>
                <div class="siperisda-footer">
                    <span>Daerah Irigasi Baku</span>
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Lahan Fungsional (Coral/Orange) -->
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="siperisda-card bg-sip-coral">
                <div class="siperisda-body">
                    <i class="fas fa-tint siperisda-icon"></i>
                    <div class="siperisda-value">{{ str_replace(' Ha', '', $statistik['total_luas_fung'] ?? '18.200') }}<span class="siperisda-unit">Ha</span></div>
                    <div class="siperisda-label">Lahan Fungsional</div>
                </div>
                <div class="siperisda-footer">
                    <span>Teraliri Air Irigasi</span>
                    <i class="fas fa-water"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Total Daerah Irigasi (Mint) -->
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="siperisda-card bg-sip-mint">
                <div class="siperisda-body">
                    <i class="fas fa-seedling siperisda-icon"></i>
                    <div class="siperisda-value">{{ $statistik['total_dir'] ?? 12 }}<span class="siperisda-unit">DIR</span></div>
                    <div class="siperisda-label">Daerah Irigasi</div>
                </div>
                <div class="siperisda-footer">
                    <span>Kewenangan BWSK I</span>
                    <i class="fas fa-map-marker-alt"></i>
                </div>
            </div>
        </div>

        <!-- Card 5: Jaringan & Bangunan Air (Rose/Pink) -->
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="siperisda-card bg-sip-rose">
                <div class="siperisda-body">
                    <i class="fas fa-stream siperisda-icon"></i>
                    <div class="siperisda-value">{{ ($stats['categories']['saluran'] ?? 0) + ($stats['categories']['bangunan'] ?? 0) }}<span class="siperisda-unit">Objek</span></div>
                    <div class="siperisda-label">Saluran &amp; Bangunan</div>
                </div>
                <div class="siperisda-footer">
                    <span>Infrastruktur Air</span>
                    <i class="fas fa-landmark"></i>
                </div>
            </div>
        </div>

        <!-- Card 6: Petugas Terautentikasi (Ocean Teal) -->
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="siperisda-card bg-sip-ocean">
                <div class="siperisda-body">
                    <i class="fas fa-users-cog siperisda-icon"></i>
                    <div class="siperisda-value">{{ $totalUsers ?? 2 }}<span class="siperisda-unit">Akun</span></div>
                    <div class="siperisda-label">Petugas Sistem</div>
                </div>
                <div class="siperisda-footer">
                    <span>Super Admin &amp; Admin</span>
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ 2. GRAFIK ANALITIK SPASIAL (PERSIS REFERENSI 1: SIPERISDA DENGAN CHART.JS) ═══ -->
    <div class="row">
        <!-- Grafik 1: Perbandingan Luas Lahan Baku vs Fungsional per Kabupaten -->
        <div class="col-xl-8 col-lg-7">
            <div class="dashboard-panel-card">
                <div class="panel-card-header">
                    <h5 class="panel-card-title">
                        <i class="fas fa-chart-bar text-primary"></i>
                        <span>Sebaran Luas Lahan Baku vs Fungsional per Kabupaten</span>
                    </h5>
                    <span class="badge badge-light border text-muted px-2 py-1 small">Satuan: Hektare (Ha)</span>
                </div>
                <div class="panel-card-body">
                    <div style="position: relative; height: 320px; width: 100%;">
                        <canvas id="chartLuasPerKabupaten"></canvas>
                    </div>
                    <div class="d-flex align-items-center justify-content-center mt-3 pt-2 border-top" style="gap: 20px; font-size: 0.8rem;">
                        <span class="d-flex align-items-center"><span style="width:12px; height:12px; background:#10b981; border-radius:3px; display:inline-block; margin-right:6px;"></span> Lahan Baku Siap Tanam</span>
                        <span class="d-flex align-items-center"><span style="width:12px; height:12px; background:#2563eb; border-radius:3px; display:inline-block; margin-right:6px;"></span> Lahan Fungsional Teraliri</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik 2: Komposisi Layer Spasial GIS (Doughnut Chart) -->
        <div class="col-xl-4 col-lg-5">
            <div class="dashboard-panel-card">
                <div class="panel-card-header">
                    <h5 class="panel-card-title">
                        <i class="fas fa-chart-pie text-warning"></i>
                        <span>Komposisi Layer Spasial</span>
                    </h5>
                    <span class="badge badge-light border text-muted px-2 py-1 small">{{ $stats['total_files'] ?? 57 }} File</span>
                </div>
                <div class="panel-card-body">
                    <div style="position: relative; height: 260px; width: 100%;">
                        <canvas id="chartKategoriGIS"></canvas>
                    </div>
                    <!-- Badges Legend -->
                    <div class="mt-3 pt-2 border-top" style="font-size: 0.78rem;">
                        <div class="row no-gutters text-muted">
                            <div class="col-6 mb-1"><span class="badge badge-dot mr-1" style="background:#10b981;width:8px;height:8px;display:inline-block;border-radius:50%;"></span> Baku: <strong>{{ $stats['categories']['baku'] ?? 0 }}</strong></div>
                            <div class="col-6 mb-1"><span class="badge badge-dot mr-1" style="background:#2563eb;width:8px;height:8px;display:inline-block;border-radius:50%;"></span> Fungsional: <strong>{{ $stats['categories']['fungsional'] ?? 0 }}</strong></div>
                            <div class="col-6 mb-1"><span class="badge badge-dot mr-1" style="background:#f59e0b;width:8px;height:8px;display:inline-block;border-radius:50%;"></span> Potensial: <strong>{{ $stats['categories']['potensial'] ?? 0 }}</strong></div>
                            <div class="col-6 mb-1"><span class="badge badge-dot mr-1" style="background:#ec4899;width:8px;height:8px;display:inline-block;border-radius:50%;"></span> Saluran: <strong>{{ $stats['categories']['saluran'] ?? 0 }}</strong></div>
                            <div class="col-6 mb-1"><span class="badge badge-dot mr-1" style="background:#8b5cf6;width:8px;height:8px;display:inline-block;border-radius:50%;"></span> Bangunan: <strong>{{ $stats['categories']['bangunan'] ?? 0 }}</strong></div>
                            <div class="col-6 mb-1"><span class="badge badge-dot mr-1" style="background:#06b6d4;width:8px;height:8px;display:inline-block;border-radius:50%;"></span> WS Kapuas: <strong>{{ $stats['categories']['ws_kapuas'] ?? 0 }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ 3. PREVIEW PETA GIS & LOG AKTIVITAS AUDIT (DOKUMENTASI LAPORAN MAGANG) ═══ -->
    <div class="row">
        <!-- Kolom Kiri: Peta Interaktif Ringkas & Quick Focus -->
        <div class="col-xl-7 col-lg-6">
            <div class="dashboard-panel-card">
                <div class="panel-card-header flex-wrap" style="gap: 10px;">
                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <h5 class="panel-card-title">
                            <i class="fas fa-map text-success"></i>
                            <span>Preview Sebaran Spasial DIR Kalbar</span>
                        </h5>
                    </div>
                    <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                        <button type="button" class="btn-focus-pill" onclick="panToRegion(-1.8, 110.0, 8, this)">Ketapang</button>
                        <button type="button" class="btn-focus-pill" onclick="panToRegion(-0.4, 109.5, 9, this)">Kubu Raya</button>
                        <button type="button" class="btn-focus-pill" onclick="panToRegion(1.3, 109.3, 9, this)">Sambas</button>
                        <button type="button" class="btn-focus-pill active" onclick="panToRegion(0.0, 109.8, 7, this)">Semua Kalbar</button>
                    </div>
                </div>
                <div class="panel-card-body p-2">
                    <div id="map-preview"></div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Log Aktivitas Sistem (Pusat Notifikasi Riil) -->
        <div class="col-xl-5 col-lg-6">
            <div class="dashboard-panel-card">
                <div class="panel-card-header">
                    <h5 class="panel-card-title">
                        <i class="fas fa-history text-info"></i>
                        <span>Riwayat Aktivitas Petugas</span>
                    </h5>
                    <a href="{{ route('admin.kelola-gis') }}" class="small font-weight-bold text-primary">
                        Kelola Master Data &raquo;
                    </a>
                </div>
                <div class="panel-card-body p-0">
                    <div class="list-group list-group-flush" style="font-size: 0.84rem;">
                        @forelse($recentActivities as $act)
                            @php
                                $badgeStyle = match($act['action'] ?? 'update') {
                                    'upload' => ['bg' => '#ecfdf5', 'color' => '#059669', 'icon' => 'fa-cloud-upload-alt', 'label' => 'Upload'],
                                    'delete' => ['bg' => '#fef2f2', 'color' => '#dc2626', 'icon' => 'fa-trash-alt', 'label' => 'Hapus'],
                                    'rename' => ['bg' => '#fffbeb', 'color' => '#d97706', 'icon' => 'fa-pen', 'label' => 'Ubah'],
                                    default  => ['bg' => '#eff6ff', 'color' => '#2563eb', 'icon' => 'fa-database', 'label' => 'Update']
                                };
                            @endphp
                            <div class="list-group-item d-flex align-items-start px-3 py-3 border-bottom">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0 mt-1" 
                                     style="width: 32px; height: 32px; background: {{ $badgeStyle['bg'] }}; color: {{ $badgeStyle['color'] }}; font-size: 13px;">
                                    <i class="fas {{ $badgeStyle['icon'] }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="font-weight-bold text-dark" style="font-size: 0.85rem;">{{ $act['user'] ?? 'Petugas' }}</span>
                                        <span class="badge font-weight-bold px-2 py-1" style="background: {{ $badgeStyle['bg'] }}; color: {{ $badgeStyle['color'] }}; font-size: 0.68rem; border-radius: 6px;">
                                            {{ $badgeStyle['label'] }}
                                        </span>
                                    </div>
                                    <div class="text-secondary small mb-1" style="line-height: 1.35; font-size: 0.8rem;">
                                        {{ $act['description'] ?? '-' }}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.72rem;">
                                        <i class="far fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($act['time'])->locale('id')->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted small">
                                <i class="fas fa-check-circle text-success mr-1"></i> Belum ada aktivitas tercatat.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ 4. TABEL FILE GEOSPASIAL TERKINI (REFERENSI FEWS BWS) ═══ -->
    <div class="dashboard-panel-card">
        <div class="panel-card-header">
            <h5 class="panel-card-title">
                <i class="fas fa-folder-open text-warning"></i>
                <span>Data Spasial Terkini Terdaftar</span>
            </h5>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <a href="{{ route('admin.kelola-gis') }}" class="btn btn-sm btn-outline-secondary font-weight-bold px-3" style="border-radius: 20px; font-size: 0.78rem;">
                    Buka Seluruh {{ count($geojsonFiles ?? []) }} File Master Data &raquo;
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size: 0.85rem;">
                <thead style="background: #f8fafc; color: #475569; font-weight: 600;">
                    <tr>
                        <th width="5%" class="text-center">No.</th>
                        <th width="35%">Nama File Spasial</th>
                        <th width="20%">Kategori Master Data</th>
                        <th width="15%">Ukuran File</th>
                        <th width="15%">Tanggal Modifikasi</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentFiles as $idx => $rf)
                    <tr>
                        <td class="text-center text-muted font-weight-bold">{{ $idx + 1 }}</td>
                        <td>
                            <div class="font-weight-bold text-dark">{{ $rf['name'] }}</div>
                            <span class="text-muted small" style="font-size: 0.72rem;">Format: {{ strtoupper($rf['extension']) }}</span>
                        </td>
                        <td>
                            @php
                                $catBadge = match($rf['category']) {
                                    'baku'       => ['bg' => '#ecfdf5', 'color' => '#059669', 'label' => 'DIR Baku'],
                                    'fungsional' => ['bg' => '#eff6ff', 'color' => '#2563eb', 'label' => 'DIR Fungsional'],
                                    'potensial'  => ['bg' => '#fffbeb', 'color' => '#d97706', 'label' => 'DIR Potensial'],
                                    'saluran'    => ['bg' => '#fdf2f8', 'color' => '#db2777', 'label' => 'Jaringan Saluran'],
                                    'bangunan'   => ['bg' => '#f5f3ff', 'color' => '#7c3aed', 'label' => 'Bangunan Air'],
                                    'ws_kapuas'  => ['bg' => '#ecfeff', 'color' => '#0891b2', 'label' => 'WS & DAS Kapuas'],
                                    default      => ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => 'Layer Kustom']
                                };
                            @endphp
                            <span class="badge font-weight-bold px-2 py-1" style="background: {{ $catBadge['bg'] }}; color: {{ $catBadge['color'] }}; font-size: 0.74rem; border-radius: 6px;">
                                {{ $catBadge['label'] }}
                            </span>
                        </td>
                        <td class="text-muted font-weight-bold">{{ $rf['size'] }}</td>
                        <td class="text-muted">{{ $rf['date'] }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.kelola-gis', ['search' => $rf['name']]) }}" class="btn btn-sm btn-light border px-2 py-1 text-primary font-weight-bold" style="border-radius: 6px; font-size: 0.75rem;" title="Kelola File Ini">
                                <i class="fas fa-cog mr-1"></i> Kelola
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada file GeoJSON terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<!-- Leaflet JS & Chart.js -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>

    // ═══ INISIALISASI CHART 1: SEBARAN LUAS PER KABUPATEN (BAR CHART) ═══
    @php
        $kabLabels = array_keys($chartKabupaten ?? ['Sambas' => [], 'Kubu Raya' => [], 'Ketapang' => []]);
        $dataBaku = [];
        $dataFung = [];
        foreach ($kabLabels as $k) {
            $dataBaku[] = $chartKabupaten[$k]['baku'] ?? 0;
            $dataFung[] = $chartKabupaten[$k]['fungsional'] ?? 0;
        }
    @endphp

    var ctxBar = document.getElementById('chartLuasPerKabupaten').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: {!! json_encode($kabLabels) !!},
            datasets: [
                {
                    label: 'Luas Baku (Ha)',
                    data: {!! json_encode($dataBaku) !!},
                    backgroundColor: '#10b981',
                    borderRadius: 6,
                    borderSkipped: false,
                    barPercentage: 0.6,
                    categoryPercentage: 0.7
                },
                {
                    label: 'Luas Fungsional (Ha)',
                    data: {!! json_encode($dataFung) !!},
                    backgroundColor: '#2563eb',
                    borderRadius: 6,
                    borderSkipped: false,
                    barPercentage: 0.6,
                    categoryPercentage: 0.7
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toLocaleString('id-ID') + ' Ha';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: "'Plus Jakarta Sans', sans-serif", weight: 'bold' } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        callback: function(value) { return value.toLocaleString('id-ID') + ' Ha'; }
                    }
                }
            }
        }
    });

    // ═══ INISIALISASI CHART 2: KOMPOSISI LAYER SPASIAL (DOUGHNUT CHART) ═══
    var ctxDonut = document.getElementById('chartKategoriGIS').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['DIR Baku', 'DIR Fungsional', 'DIR Potensial', 'Jaringan Saluran', 'Bangunan Air', 'WS Kapuas'],
            datasets: [{
                data: [
                    {{ $stats['categories']['baku'] ?? 8 }},
                    {{ $stats['categories']['fungsional'] ?? 14 }},
                    {{ $stats['categories']['potensial'] ?? 5 }},
                    {{ $stats['categories']['saluran'] ?? 16 }},
                    {{ $stats['categories']['bangunan'] ?? 12 }},
                    {{ $stats['categories']['ws_kapuas'] ?? 2 }}
                ],
                backgroundColor: [
                    '#10b981',
                    '#2563eb',
                    '#f59e0b',
                    '#ec4899',
                    '#8b5cf6',
                    '#06b6d4'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + ' File Spasial';
                        }
                    }
                }
            }
        }
    });

    // ═══ INISIALISASI PREVIEW PETA RINGKAS LEAFLET ═══
    var previewMap = L.map('map-preview', {
        zoomControl: true,
        attributionControl: false
    }).setView([0.0, 109.8], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18
    }).addTo(previewMap);

    // Titik Pos Irigasi dari Data Aktual BWS
    var lokasiPoints = {!! json_encode($lokasi) !!};
    var markersLayer = L.layerGroup().addTo(previewMap);

    lokasiPoints.forEach(function(item) {
        if (item.lat && item.lng) {
            var marker = L.circleMarker([item.lat, item.lng], {
                radius: 7,
                fillColor: item.warna || '#2563eb',
                color: '#ffffff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.9
            });

            marker.bindPopup(
                '<div style="font-family:\'Plus Jakarta Sans\',sans-serif; min-width:180px;">' +
                    '<div style="font-weight:700; color:#1e293b; font-size:13px; margin-bottom:4px;">' + item.nama + '</div>' +
                    '<div style="font-size:11px; color:#64748b;">Wilayah: ' + item.kabupaten + '</div>' +
                    '<div style="font-size:11px; color:#059669; font-weight:600; margin-top:2px;">Luas Baku: ' + (item.luas_baku || 0).toLocaleString('id-ID') + ' Ha</div>' +
                    '<div style="font-size:11px; color:#2563eb; font-weight:600;">Luas Fungsional: ' + (item.luas_fung || 0).toLocaleString('id-ID') + ' Ha</div>' +
                '</div>'
            );
            markersLayer.addLayer(marker);
        }
    });

    function panToRegion(lat, lng, zoom, btn) {
        previewMap.flyTo([lat, lng], zoom, { duration: 1.2 });
        document.querySelectorAll('.btn-focus-pill').forEach(function(el) {
            el.classList.remove('active');
        });
        if (btn) btn.classList.add('active');
    }
</script>
@endpush

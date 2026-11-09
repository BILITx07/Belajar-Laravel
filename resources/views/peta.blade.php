@extends('layout.master')

@section('title', 'BWS KALIMANTAN I - SIG Daerah Irigasi')
@section('hide_footer', true)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/peta.css') }}?v={{ file_exists(public_path('css/peta.css')) ? filemtime(public_path('css/peta.css')) : time() }}">
@endpush

@section('konten')
<div class="bws-qrop-page">

    <!-- ============================================================ -->
    <!-- 1. KARTU BANNER HERO MEWAH (PERSIS SEPERTI GAMBAR 1 QROP)    -->
    <!-- ============================================================ -->
    <div class="bws-hero-card">
        <div class="bws-hero-content">
            <div class="bws-hero-pretitle">
                <span class="bws-dot-hero"></span> DATA SPASIAL BWS KALIMANTAN I
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
                    <h5 class="bws-sidebar-main-title">Jelajahi objek irigasi</h5>
                    <div class="bws-sidebar-main-sub">Mulai dari nama atau file data spasial.</div>
                </div>
            </div>

            <!-- Field 1: Cari Usaha atau Alamat / Pos Irigasi -->
            <div class="bws-field-block">
                <label class="bws-field-label">Cari pos atau daerah irigasi</label>
                <div class="bws-input-container">
                    <input type="text"
                           class="form-control bws-text-input"
                           id="sidebarSearchInput"
                           placeholder="Nama pos, DIR, atau file..."
                           autocomplete="off"
                           onkeyup="handleSidebarSearch(this.value)">
                    <button type="button"
                            class="bws-clear-search-btn"
                            id="btnSidebarSearchClear"
                            onclick="clearSidebarSearch()"
                            title="Hapus pencarian">&times;</button>
                </div>
                <small class="bws-field-hint">Pilih hasil pencarian untuk melihat detail. Maksimal 150 karakter.</small>
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
                @auth
                <a href="{{ route('admin.kelola-gis') }}" class="btn btn-primary btn-block font-weight-bold shadow-sm py-2 mb-2" style="font-size: 12px; border-radius: 8px;">
                    <i class="fas fa-cloud-upload-alt mr-1"></i> Upload File GIS
                </a>
                @endauth
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
                    <span class="text-muted small font-weight-bold" id="currentBreadcrumbLabel">Kalimantan Barat</span>
                </div>

                <div class="d-flex align-items-center" style="gap: 8px;">
                    <!-- Switcher Peta Jalan vs Satelit Rapi -->
                    <div class="btn-group btn-group-sm bws-basemap-group shadow-sm" role="group">
                        <button type="button" class="btn btn-sm bws-basemap-btn active" id="btnBasemapOsm" onclick="switchBasemap('osm')" title="Ganti ke Peta Jalan OpenStreetMap">
                            <i class="fas fa-map mr-1"></i> Jalan
                        </button>
                        <button type="button" class="btn btn-sm bws-basemap-btn" id="btnBasemapSat" onclick="switchBasemap('sat')" title="Ganti ke Citra Satelit Google">
                            <i class="fas fa-satellite mr-1"></i> Satelit
                        </button>
                    </div>

                    <button type="button" class="btn btn-sm btn-white bws-action-pill shadow-sm" onclick="resetToKalbar()" title="Reset tampilan Kalimantan Barat">
                        <i class="fas fa-home mr-1 text-primary"></i> Reset
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

        </main>

    </div>

</div>

<!-- MODAL PETUNJUK PENGGUNAAN WEBGIS (Resmi & Profesional) -->
<div class="modal fade" id="modalPanduanAplikasi" tabindex="-1" role="dialog" aria-labelledby="modalPanduanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
            
            <!-- Modal Header (Gaya Resmi Kedinasan PU) -->
            <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #093c52 0%, #0d5c70 50%, #117887 100%);">
                <div class="d-flex align-items-center">
                    <div class="mr-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px; background: #ffffff; border-radius: 12px; color: #1d4ed8; font-size: 20px; border: 2px solid rgba(250, 214, 5, 0.9);">
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
                <!-- Navigasi Tab Modern -->
                <ul class="nav nav-pills nav-fill mb-3" id="pills-tab-panduan" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold py-2" id="tab-peta-link" data-toggle="pill" href="#tab-peta" role="tab" style="font-size: 0.85rem; border-radius: 10px;">
                            <i class="fas fa-layer-group mr-1"></i> 1. Lapisan &amp; Simbol Peta
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-2" id="tab-filter-link" data-toggle="pill" href="#tab-filter" role="tab" style="font-size: 0.85rem; border-radius: 10px;">
                            <i class="fas fa-search-location mr-1"></i> 2. Pencarian &amp; Filter
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-2" id="tab-popup-link" data-toggle="pill" href="#tab-popup" role="tab" style="font-size: 0.85rem; border-radius: 10px;">
                            <i class="fas fa-info-circle mr-1"></i> 3. Interaksi &amp; Detail
                        </a>
                    </li>
                </ul>

                <!-- Konten Tab Panduan -->
                <div class="tab-content border rounded p-3 bg-white shadow-sm" id="pills-tabContent" style="min-height: 230px; font-size: 13px; line-height: 1.6; border-color: #e2e8f0 !important;">
                    
                    <!-- TAB 1: LAPISAN & SIMBOL PETA -->
                    <div class="tab-pane fade show active" id="tab-peta" role="tabpanel">
                        <div class="font-weight-bold text-primary mb-2" style="font-size: 14px;">
                            <i class="fas fa-palette mr-1"></i> Klasifikasi Data Spasial Irigasi
                        </div>
                        <p class="text-secondary mb-3">Setiap warna dan bentuk geometri pada peta merepresentasikan objek sumber daya air resmi BWS Kalimantan I:</p>
                        
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="p-2 border rounded bg-light d-flex align-items-center">
                                    <span class="badge badge-success px-2 py-1 mr-2" style="min-width: 70px;">Hijau</span>
                                    <div><strong>Lahan Baku:</strong> Lahan siap tanam dalam kewenangan Daerah Irigasi Rawa (DIR).</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="p-2 border rounded bg-light d-flex align-items-center">
                                    <span class="badge badge-primary px-2 py-1 mr-2" style="min-width: 70px;">Biru</span>
                                    <div><strong>Lahan Fungsional:</strong> Lahan aktif beririgasi dan beroperasi O&amp;P rutin.</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="p-2 border rounded bg-light d-flex align-items-center">
                                    <span class="badge badge-warning text-dark px-2 py-1 mr-2" style="min-width: 70px;">Kuning</span>
                                    <div><strong>Lahan Potensial:</strong> Wilayah perencanaan pengembangan potensi irigasi.</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="p-2 border rounded bg-light d-flex align-items-center">
                                    <span class="badge badge-info px-2 py-1 mr-2" style="background:#0284c7; color:#fff; min-width: 70px;">Cyan</span>
                                    <div><strong>Jaringan Saluran:</strong> Trase garis saluran pembawa primer, sekunder, &amp; tersier.</div>
                                </div>
                            </div>
                            <div class="col-12 mb-1">
                                <div class="p-2 border rounded bg-light d-flex align-items-center">
                                    <span class="badge px-2 py-1 mr-2" style="background:#d97706; color:#fff; min-width: 70px;">Oranye</span>
                                    <div><strong>Bangunan Air:</strong> Titik pintu air, bendung, sadap, gorong-gorong, dan pos hidrologi.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: PENCARIAN & FILTER -->
                    <div class="tab-pane fade" id="tab-filter" role="tabpanel">
                        <div class="font-weight-bold text-primary mb-2" style="font-size: 14px;">
                            <i class="fas fa-search mr-1"></i> Cara Cepat Menemukan Objek Irigasi
                        </div>
                        <ul class="pl-3 text-secondary mb-2">
                            <li class="mb-2">
                                <strong>Pencarian Pintar (*Sidebar* Kiri):</strong> Ketikkan nama pos atau daerah irigasi (misal: <em>"Pemangkat"</em>, <em>"Sungai Daun"</em>, atau <em>"Kubu"</em>) pada kotak pencarian, lalu klik hasil yang muncul untuk langsung menuju lokasi.
                            </li>
                            <li class="mb-2">
                                <strong>Filter Kabupaten:</strong> Gunakan menu pilihan wilayah untuk membatasi fokus peta ke Kabupaten tertentu (Kabupaten Sambas, Kubu Raya, atau Ketapang).
                            </li>
                            <li class="mb-1">
                                <strong>Tombol Kategori Layer:</strong> Klik baris kategori (*Lahan Baku*, *Fungsional*, *Saluran*, *Bangunan Air*) di panel kiri untuk menampilkan atau menyembunyikan lapisan tertentu dari peta.
                            </li>
                        </ul>
                    </div>

                    <!-- TAB 3: INTERAKSI & DETAIL -->
                    <div class="tab-pane fade" id="tab-popup" role="tabpanel">
                        <div class="font-weight-bold text-primary mb-2" style="font-size: 14px;">
                            <i class="fas fa-mouse-pointer mr-1"></i> Membaca Informasi &amp; Koordinat Objek
                        </div>
                        <p class="text-secondary mb-2">Peta ini bersifat interaktif dan akurat secara geospasial:</p>
                        <ul class="pl-3 text-secondary mb-2">
                            <li class="mb-2">
                                <strong>Klik Objek pada Peta:</strong> Setiap kali Anda mengklik poligon lahan, garis saluran, atau ikon bangunan air, peta akan melakukan <strong>pusat kamera otomatis (*smooth flyTo*)</strong> langsung ke titik koordinat presisi.
                            </li>
                            <li class="mb-2">
                                <strong>Kartu Informasi Melayang:</strong> Di atas objek yang diklik akan muncul kartu pop-up modern berisi Nama Objek, Kategori, Kabupaten, serta data teknis resmi dari Buku DIR BWS Kalimantan I.
                            </li>
                            <li class="mb-1">
                                <strong>Navigasi Peta:</strong> Anda dapat memperbesar (*zoom-in*), memperkecil (*zoom-out*), atau menggeser peta secara bebas menggunakan mouse komputer atau sentuhan layar sentuh pada HP.
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Tips Navigasi Praktis -->
                <div class="p-2 px-3 rounded mt-3 d-flex align-items-center" style="background: rgba(29, 78, 216, 0.06); border: 1px dashed rgba(29, 78, 216, 0.25); font-size: 12.5px; color: #1e40af;">
                    <i class="fas fa-lightbulb text-warning mr-2 fa-lg"></i>
                    <div>
                        <strong>Tips Praktis:</strong> Gunakan tombol <strong>Layar Penuh</strong> (<i class="fas fa-expand mx-1"></i>) pada pojok kanan atas navbar untuk melihat sebaran jaringan irigasi secara maksimal dan leluasa.
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-light py-2 px-4 justify-content-between">
                <small class="text-muted" style="font-size: 11.5px;">
                    <i class="fas fa-shield-alt text-success mr-1"></i> Data Spasial Resmi SISDA BWS Kalimantan I
                </small>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold px-4 shadow-sm" data-dismiss="modal" style="border-radius: 8px;">
                    Mengerti &amp; Tutup
                </button>
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
@endpush

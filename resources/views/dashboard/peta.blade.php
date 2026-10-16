@extends('layout.master')

@php
    $hasFiles = $hasFiles ?? (isset($fileList) && count($fileList) > 0);
    $availableTypes = $availableTypes ?? [
        'baku'       => false,
        'fungsional' => false,
        'potensial'  => false,
        'saluran'    => false,
        'bangunan'   => false,
        'custom'     => false,
    ];
@endphp

@section('title', 'Peta Interaktif Daerah Irigasi (DIR) - BWS Kalimantan I')

@section('konten')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold">
            <i class="fas fa-map-marked-alt text-primary mr-2"></i>Peta Interaktif Daerah Irigasi (DIR)
        </h1>
        <p class="text-muted mb-0 small">Sistem Informasi Geografis (SIG) Terintegrasi Data ArcMap - Balai Wilayah Sungai Kalimantan I Pontianak</p>
    </div>
    <div class="mt-3 mt-sm-0 d-flex flex-wrap align-items-center">
        <button type="button" class="btn btn-sm btn-outline-secondary shadow-sm mr-2 font-weight-bold" data-toggle="modal" data-target="#modalKelolaGeojson">
            <i class="fas fa-folder-open mr-1"></i> Kelola File GIS <span class="badge badge-primary ml-1">{{ count($fileList ?? []) }}</span>
        </button>
        <button type="button" class="btn btn-sm btn-info shadow-sm font-weight-bold" data-toggle="modal" data-target="#modalUploadGeojson">
            <i class="fas fa-cloud-upload-alt mr-1"></i> Upload GeoJSON
        </button>
    </div>
</div>

{{-- Flash Message Notifikasi --}}
<div id="js-flash-alert-container"></div>
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
    <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
    <div class="font-weight-bold mb-1"><i class="fas fa-exclamation-circle mr-1"></i>Terjadi Kesalahan:</div>
    <ul class="mb-0 pl-3">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif  

<!-- ================================================================
     Modal Upload GIS - Mendukung GeoJSON & File Mentah (Auto Konversi)
     Format: .geojson, .json, .zip (Shapefile), .kml, .kmz, .csv, .gpx
     ================================================================ -->
<!-- ================================================================
     Modal Upload GIS - Mendukung GeoJSON & File Mentah (Auto Konversi)
     Format: .geojson, .json, .zip (Shapefile), .kml, .kmz, .csv, .gpx
     ================================================================ -->
<div class="modal fade" id="modalUploadGeojson" tabindex="-1" role="dialog" aria-labelledby="modalUploadGeojsonLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 760px; margin: 1.5rem auto;">
        <div class="modal-content border-0 shadow-lg rounded-lg overflow-hidden">
            <!-- Header Modal -->
            <div class="modal-header text-white px-4 py-3 border-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #2563eb 100%);">
                <div class="d-flex align-items-center">
                    <div class="mr-3 rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width:40px;height:40px;background:rgba(255,255,255,0.18);">
                        <i class="fas fa-cloud-upload-alt text-white fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-white" id="modalUploadGeojsonLabel" style="font-size:1.15rem;letter-spacing:-0.2px;">
                            Upload &amp; Konversi Data GIS
                        </h5>
                        <small class="text-white-50" style="font-size:0.8rem;">Sistem otomatis mengkonversi Shapefile, KML, CSV &amp; GPX ke peta</small>
                    </div>
                </div>
                <button type="button" class="close text-white opacity-75" data-dismiss="modal" aria-label="Close" id="btnCloseUploadModal" style="outline:none;text-shadow:none;font-size:1.5rem;padding:0.75rem 1rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Tab Navigation (Pills) -->
            <div class="px-4 pt-3 pb-2 bg-light border-bottom">
                <ul class="nav nav-pills" id="uploadTabNav" role="tablist">
                    <li class="nav-item mr-2">
                        <a class="nav-link active font-weight-bold px-3 py-2" id="tab-geojson-lnk" data-toggle="pill" href="#tab-geojson" role="tab" style="font-size:13px;border-radius:20px;">
                            <i class="fas fa-file-code mr-1"></i>File Siap (.geojson / .json)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold px-3 py-2" id="tab-konversi-lnk" data-toggle="pill" href="#tab-konversi" role="tab" style="font-size:13px;border-radius:20px;">
                            <i class="fas fa-magic text-warning mr-1"></i>✨ Konversi Otomatis
                            <span class="badge badge-warning text-dark ml-1 font-weight-bold">SHP · KML · CSV</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="modal-body p-4" style="background:#fafbfc;">
                <div class="tab-content">

                    <!-- ===== TAB 1: Upload GeoJSON / JSON biasa ===== -->
                    <div class="tab-pane fade show active" id="tab-geojson" role="tabpanel">
                        <form id="formUploadGeojson" action="{{ route('peta.upload-geojson') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="bg-white p-3 rounded border mb-3">
                                <label class="font-weight-bold text-gray-800 mb-2 d-flex align-items-center">
                                    <i class="fas fa-file-code text-primary mr-2"></i>Pilih File GeoJSON / JSON
                                </label>
                                <div class="custom-file">
                                    <input type="file" name="file_geojson[]" class="custom-file-input" id="inputGeojsonFile" multiple accept=".geojson,.json" required onchange="updateFileInputLabel(this)">
                                    <label class="custom-file-label" for="inputGeojsonFile" id="labelGeojsonFile">Pilih satu atau beberapa file...</label>
                                </div>
                                <small class="form-text text-muted mt-2">
                                    <i class="fas fa-info-circle mr-1 text-primary"></i>Format: <code>.geojson</code> atau <code>.json</code>. Anda dapat memilih banyak file sekaligus.
                                </small>
                            </div>

                            {{-- Progress Bar Tab 1 --}}
                            <div id="uploadProgressSection" class="bg-white p-3 rounded border mb-3" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="font-weight-bold small text-primary" id="uploadStatusText">
                                        <i class="fas fa-spinner fa-spin mr-1"></i>Mengunggah file...
                                    </span>
                                    <span class="font-weight-bold small text-muted" id="uploadPercentText">0%</span>
                                </div>
                                <div class="progress" style="height: 14px; border-radius: 7px;">
                                    <div id="uploadProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success font-weight-bold" role="progressbar" style="width: 0%; font-size: 10px;">0%</div>
                                </div>
                                <div class="small text-muted mt-2 d-flex justify-content-between" id="uploadDetailText">
                                    <span>Memproses file...</span>
                                    <span id="uploadBatchCountText">Batch 0/0</span>
                                </div>
                            </div>

                            <div id="uploadSuccessSection" class="alert alert-success py-2 px-3 small mb-3 shadow-sm" style="display: none;">
                                <i class="fas fa-check-circle mr-1"></i><span id="uploadSuccessMessage">Semua file berhasil diunggah!</span>
                            </div>
                            <div id="uploadErrorSection" class="alert alert-danger py-2 px-3 small mb-3 shadow-sm" style="display: none;">
                                <i class="fas fa-exclamation-triangle mr-1"></i><span id="uploadErrorMessage"></span>
                            </div>

                            <div class="alert alert-info py-2 px-3 small mb-3 border-0" style="background:#e8f0fe;color:#1e3a8a;">
                                <i class="fas fa-folder-open mr-1"></i>File akan disimpan ke folder <code>public/geojson/</code> dan otomatis langsung tampil pada peta interaktif.
                            </div>

                            <div class="d-flex justify-content-end" id="uploadModalFooter">
                                <button type="button" class="btn btn-light border mr-2 font-weight-bold" data-dismiss="modal" id="btnBatalUpload">
                                    <i class="fas fa-times mr-1"></i>Batal
                                </button>
                                <button type="submit" class="btn btn-primary font-weight-bold px-4 shadow-sm" id="btnSubmitUpload">
                                    <i class="fas fa-upload mr-1"></i>Upload Sekarang
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- ===== TAB 2: Konversi Otomatis File Mentah ===== -->
                    <div class="tab-pane fade" id="tab-konversi" role="tabpanel">

                        <!-- Format badges & Intro -->
                        <div class="bg-white p-3 rounded border mb-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap mb-2">
                                <div class="font-weight-bold text-dark small">
                                    <i class="fas fa-bolt text-warning mr-1"></i>Format yang Didukung Sistem:
                                </div>
                                <div class="small text-muted">Auto-convert di browser</div>
                            </div>
                            <div class="d-flex flex-wrap" style="gap:6px;">
                                <span class="badge badge-pill px-3 py-1 font-weight-normal" style="background:#e8f0fe;color:#1a73e8;font-size:12px;">
                                    <i class="fas fa-file-archive mr-1"></i><strong>.zip</strong> (Shapefile SHP+DBF+PRJ)
                                </span>
                                <span class="badge badge-pill px-3 py-1 font-weight-normal" style="background:#fce8e6;color:#d32f2f;font-size:12px;">
                                    <i class="fas fa-map mr-1"></i><strong>.kml</strong> / <strong>.kmz</strong> (Google Earth)
                                </span>
                                <span class="badge badge-pill px-3 py-1 font-weight-normal" style="background:#e6f4ea;color:#1e8e3e;font-size:12px;">
                                    <i class="fas fa-table mr-1"></i><strong>.csv</strong> (Lat &amp; Lng)
                                </span>
                                <span class="badge badge-pill px-3 py-1 font-weight-normal" style="background:#fef7e0;color:#b06000;font-size:12px;">
                                    <i class="fas fa-route mr-1"></i><strong>.gpx</strong> (GPS)
                                </span>
                            </div>
                        </div>

                        <!-- Drop Zone -->
                        <div id="gisDropZone" onclick="document.getElementById('inputRawGisFile').click()"
                             style="border: 2px dashed #3b82f6; border-radius: 12px; background: #f0f7ff; padding: 22px 18px; text-align:center; cursor:pointer; transition:all 0.2s;"
                             class="mb-3 hover-shadow">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="rounded-circle bg-white d-flex align-items-center justify-content-center shadow-sm" style="width:50px;height:50px;">
                                    <i class="fas fa-cloud-upload-alt fa-lg text-primary"></i>
                                </div>
                            </div>
                            <div class="font-weight-bold text-primary mb-1" style="font-size:14px;">Klik atau Tarik File Mentah ke Sini</div>
                            <div class="text-muted small">Pilih Shapefile (.zip), KML, KMZ, CSV, atau GPX dari komputer Anda</div>
                            <input type="file" id="inputRawGisFile" multiple
                                   accept=".zip,.kml,.kmz,.csv,.gpx"
                                   style="display:none;"
                                   onchange="handleRawGisFiles(this.files)">
                        </div>

                        <!-- Daftar file yang dipilih & status konversi -->
                        <div id="konversiFileList" class="bg-white p-3 rounded border mb-3" style="display:none;">
                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                <span class="font-weight-bold small text-dark">
                                    <i class="fas fa-layer-group text-primary mr-1"></i>File Terpilih:
                                </span>
                                <button type="button" class="btn btn-xs btn-outline-secondary btn-sm py-1 px-2" onclick="resetKonversiForm()">
                                    <i class="fas fa-redo mr-1"></i>Reset / Ganti File
                                </button>
                            </div>
                            <div id="konversiFileItems" style="max-height:180px; overflow-y:auto;"></div>
                        </div>

                        <!-- Konversi Progress Multi-Tahap -->
                        <div id="konversiProgressSection" class="bg-white p-3 rounded border mb-3" style="display:none;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small font-weight-bold text-primary" id="konversiStatusText">
                                    <i class="fas fa-spinner fa-spin mr-1"></i>Mempersiapkan konversi...
                                </span>
                                <span class="small font-weight-bold text-dark" id="konversiPersenText">0%</span>
                            </div>
                            <div class="progress" style="height:14px; border-radius:7px; background:#e2e8f0;">
                                <div id="konversiProgressBar" class="progress-bar progress-bar-striped progress-bar-animated font-weight-bold" 
                                     style="width:0%; font-size:10px; background: linear-gradient(90deg, #3b82f6, #10b981);">0%</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2 small text-muted" id="konversiSubStatus">
                                <span id="konversiStepDetail"><i class="fas fa-info-circle mr-1"></i>Memproses data spasial...</span>
                                <span id="konversiTimerText" class="badge badge-light border">Waktu berjalan</span>
                            </div>
                        </div>

                        <!-- Hasil konversi notifikasi -->
                        <div id="konversiResultSection" class="mb-3" style="display:none;">
                            <div class="alert py-2 px-3 small mb-0 rounded shadow-sm" id="konversiResultAlert">
                                <span id="konversiResultText"></span>
                            </div>
                        </div>

                        <!-- Note format accordion / compact info -->
                        <div class="alert py-2 px-3 small mb-3 border-0 rounded" style="background:#fffbeb;border-left:4px solid #f59e0b !important;color:#92400e;">
                            <div class="font-weight-bold mb-1">
                                <i class="fas fa-lightbulb text-warning mr-1"></i>Tips Format File:
                            </div>
                            <ul class="mb-0 pl-3" style="font-size:11.5px;line-height:1.5;">
                                <li><strong>Shapefile (.zip):</strong> Satukan file <code>.shp</code>, <code>.dbf</code>, dan <code>.prj</code> dalam satu arsip <code>.zip</code>. File besar (>20MB) membutuhkan waktu ekstra untuk diurai di browser.</li>
                                <li><strong>CSV Koordinat:</strong> Pastikan memiliki nama kolom <code>latitude</code>/<code>lat</code> dan <code>longitude</code>/<code>lng</code>.</li>
                            </ul>
                        </div>

                        <!-- Footer Tombol -->
                        <div class="d-flex justify-content-end align-items-center pt-2 border-top">
                            <button type="button" class="btn btn-light border mr-2 font-weight-bold px-3" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i>Batal
                            </button>
                            <button type="button" class="btn font-weight-bold text-white px-4 shadow-sm" id="btnKonversiUpload"
                                    onclick="mulaiKonversiDanUpload()"
                                    style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);" disabled>
                                <i class="fas fa-magic mr-1"></i>Konversi &amp; Upload
                            </button>
                        </div>
                    </div>

                </div><!-- /tab-content -->
            </div><!-- /modal-body -->
        </div><!-- /modal-content -->
    </div>
</div>

<!-- ================================================================
     SCRIPT: Konversi File Mentah GIS → GeoJSON
     Menggunakan library lokal di public/vendor/gis-convert/
     Fallback otomatis ke CDN jika file lokal tidak ditemukan
     ================================================================ -->
<script src="{{ asset('vendor/gis-convert/jszip.min.js') }}"></script>
<script src="{{ asset('vendor/gis-convert/shp.js') }}"></script>
<script src="{{ asset('vendor/gis-convert/togeojson.js') }}"></script>
<script>
// ──────────────────────────────────────────────────────────────────
// Fallback CDN jika library lokal belum termuat
// ──────────────────────────────────────────────────────────────────
if (typeof JSZip === 'undefined') {
    var sZip = document.createElement('script');
    sZip.src = 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js';
    document.head.appendChild(sZip);
}
if (typeof shp === 'undefined') {
    var sShp = document.createElement('script');
    sShp.src = 'https://cdn.jsdelivr.net/npm/shpjs@latest/dist/shp.js';
    document.head.appendChild(sShp);
}
if (typeof toGeoJSON === 'undefined') {
    var sGeo = document.createElement('script');
    sGeo.src = 'https://cdn.jsdelivr.net/npm/@mapbox/togeojson@0.16.0/togeojson.js';
    document.head.appendChild(sGeo);
}

// Tunggu library tertentu siap (polling)
function waitForLib(checkFn, libName, maxSeconds) {
    maxSeconds = maxSeconds || 20;
    return new Promise(function(resolve, reject) {
        if (checkFn()) { resolve(); return; }
        var tries = 0;
        var maxTries = maxSeconds * 10;
        var iv = setInterval(function() {
            tries++;
            if (checkFn()) {
                clearInterval(iv);
                resolve();
            } else if (tries > maxTries) {
                clearInterval(iv);
                reject(new Error('Library ' + libName + ' belum selesai dimuat. Periksa koneksi internet Anda lalu muat ulang halaman.'));
            }
        }, 100);
    });
}

// Helper delay non-blocking untuk repaint browser UI
function uiYield(ms) {
    return new Promise(function(resolve) { setTimeout(resolve, ms || 30); });
}

// ──────────────────────────────────────────────────────────────────
// Drop zone drag & drop
// ──────────────────────────────────────────────────────────────────
var dropZone = document.getElementById('gisDropZone');
if (dropZone) {
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.style.background = '#dbeafe';
        dropZone.style.borderColor = '#1d4ed8';
    });
    dropZone.addEventListener('dragleave', function() {
        dropZone.style.background = '#f0f7ff';
        dropZone.style.borderColor = '#3b82f6';
    });
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.style.background = '#f0f7ff';
        dropZone.style.borderColor = '#3b82f6';
        handleRawGisFiles(e.dataTransfer.files);
    });
}

// ──────────────────────────────────────────────────────────────────
// State untuk konversi
// ──────────────────────────────────────────────────────────────────
var rawGisFiles = [];

function resetKonversiForm() {
    rawGisFiles = [];
    document.getElementById('konversiFileList').style.display = 'none';
    document.getElementById('konversiFileItems').innerHTML = '';
    document.getElementById('konversiProgressSection').style.display = 'none';
    document.getElementById('konversiResultSection').style.display = 'none';
    document.getElementById('btnKonversiUpload').disabled = true;
    document.getElementById('inputRawGisFile').value = '';
}

function handleRawGisFiles(files) {
    if (!files || files.length === 0) return;
    var allowed = ['.zip', '.kml', '.kmz', '.csv', '.gpx'];

    for (var i = 0; i < files.length; i++) {
        var f = files[i];
        var ext = '.' + f.name.split('.').pop().toLowerCase();
        if (!allowed.includes(ext)) {
            alert('⚠️ Format file "' + f.name + '" belum didukung.\nFormat yang diterima: ' + allowed.join(', '));
            continue;
        }
        var dup = rawGisFiles.find(function(r) { return r.file.name === f.name; });
        if (!dup) {
            rawGisFiles.push({ file: f, status: 'pending', geojson: null, outputName: null });
        }
    }

    renderKonversiFileList();
    document.getElementById('btnKonversiUpload').disabled = (rawGisFiles.length === 0);
}

function renderKonversiFileList() {
    if (rawGisFiles.length === 0) {
        document.getElementById('konversiFileList').style.display = 'none';
        return;
    }
    document.getElementById('konversiFileList').style.display = 'block';
    var html = '';
    var iconMap = {
        zip: 'fa-file-archive text-primary',
        kml: 'fa-map text-danger',
        kmz: 'fa-map text-danger',
        csv: 'fa-table text-success',
        gpx: 'fa-route text-warning'
    };

    rawGisFiles.forEach(function(item, idx) {
        var ext = item.file.name.split('.').pop().toLowerCase();
        var icon = iconMap[ext] || 'fa-file text-muted';
        var sizeMb = (item.file.size / 1048576).toFixed(2);
        var statusBadge = '';
        var errorRow = '';
        var isLarge = item.file.size > 15 * 1024 * 1024; // > 15MB

        if (item.status === 'pending') {
            statusBadge = '<span class="badge badge-secondary px-2 py-1">Siap</span>';
        } else if (item.status === 'converting') {
            statusBadge = '<span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-spinner fa-spin mr-1"></i>Memproses...</span>';
        } else if (item.status === 'done') {
            statusBadge = '<span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i>Selesai</span>';
        } else if (item.status === 'error') {
            statusBadge = '<span class="badge badge-danger px-2 py-1"><i class="fas fa-times mr-1"></i>Gagal</span>';
            if (item.errorMsg) {
                errorRow = '<div class="px-3 pb-2 small text-danger"><i class="fas fa-exclamation-circle mr-1"></i>' + item.errorMsg + '</div>';
            }
        }

        html += '<div class="mb-2 rounded border bg-white shadow-sm">' +
            '<div class="d-flex align-items-center justify-content-between py-2 px-3">' +
            '  <div class="d-flex align-items-center text-truncate mr-2">' +
            '    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 bg-light" style="width:36px;height:36px;min-width:36px;">' +
            '      <i class="fas ' + icon + '" style="font-size:16px;"></i>' +
            '    </div>' +
            '    <div class="text-truncate">' +
            '      <div class="font-weight-bold text-dark text-truncate" style="font-size:13px;" title="' + item.file.name + '">' + item.file.name + '</div>' +
            '      <div class="text-muted" style="font-size:11px;">' +
                     sizeMb + ' MB · .' + ext.toUpperCase() +
                     (isLarge ? ' · <span class="text-warning font-weight-bold"><i class="fas fa-tachometer-alt"></i> File besar</span>' : '') +
            '      </div>' +
            '    </div>' +
            '  </div>' +
            '  <div class="d-flex align-items-center">' + statusBadge +
            (item.status === 'pending' ? ' <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 ml-2" title="Hapus" onclick="hapusFileKonversi(' + idx + ')"><i class="fas fa-trash"></i></button>' : '') +
            '  </div>' +
            '</div>' +
            errorRow +
            '</div>';
    });
    document.getElementById('konversiFileItems').innerHTML = html;
}

function hapusFileKonversi(idx) {
    rawGisFiles.splice(idx, 1);
    renderKonversiFileList();
    document.getElementById('btnKonversiUpload').disabled = (rawGisFiles.length === 0);
}

// ──────────────────────────────────────────────────────────────────
// ──────────────────────────────────────────────────────────────────
// Helper: Ekstrak nama layer yang bersih & representatif dari path shapefile
// ──────────────────────────────────────────────────────────────────
function extractLayerName(rawFileName, zipBaseName) {
    if (!rawFileName) return zipBaseName || 'Layer';
    var parts = String(rawFileName).replace(/\\/g, '/').split('/').filter(Boolean);
    // Jika bagian pertama adalah nama file ZIP induk, abaikan root zip tersebut jika ada subfolder
    if (parts.length > 1 && zipBaseName && parts[0].toLowerCase() === zipBaseName.toLowerCase()) {
        parts.shift();
    }
    if (parts.length === 1) {
        return parts[0].replace(/_/g, ' ').trim();
    }

    var folder = parts[parts.length - 2].replace(/_/g, ' ').trim();
    var file = parts[parts.length - 1].replace(/_/g, ' ').trim();

    var cf = folder.toLowerCase().replace(/[\s_-]/g, '');
    var cfi = file.toLowerCase().replace(/[\s_-]/g, '');

    // Jika nama folder dan file sama persis
    if (cf === cfi) return folder;

    // Cek apakah semua kata kunci penting dari file sudah ada di folder (contoh: "JS_Kapuas" di "WS KAPUAS JS")
    var fileWords = file.toLowerCase().split(/[\s_-]+/).filter(Boolean);
    var allWordsInFolder = fileWords.length > 0 && fileWords.every(function(w) { 
        return folder.toLowerCase().indexOf(w) !== -1; 
    });
    if (allWordsInFolder) return folder;

    if (cf.indexOf(cfi) !== -1) return folder;
    if (cfi.indexOf(cf) !== -1) return file;

    // Jika berbeda dan saling melengkapi, gabungkan (contoh: "Kabupaten Sambas - Jaringan Saluran")
    return folder + ' - ' + file;
}

// ──────────────────────────────────────────────────────────────────
// KONVERSI SETIAP FILE KE GEOJSON (MENDUKUNG MULTI-LAYER / MULTI-SHAPEFILE)
// ──────────────────────────────────────────────────────────────────
async function convertFileToGeoJson(item, onProgress) {
    var ext = item.file.name.split('.').pop().toLowerCase();
    var baseName = item.file.name.replace(/\.[^.]+$/, '');
    var sizeMb = (item.file.size / 1048576).toFixed(1);

    if (ext === 'zip') {
        onProgress('Memastikan library Shapefile siap...', 15);
        await waitForLib(function() { return typeof shp !== 'undefined'; }, 'shpjs (Shapefile)', 15);
        await uiYield(40);

        onProgress('Membaca berkas arsip ZIP (' + sizeMb + ' MB)...', 25);
        var arrayBuffer = await item.file.arrayBuffer();
        await uiYield(40);

        onProgress('Mengekstrak Shapefile & Mengurai Geometri Spasial (' + sizeMb + ' MB)... Mohon tunggu...', 45);
        await uiYield(60);

        var gj = await shp(arrayBuffer);
        await uiYield(40);

        onProgress('Menyusun dan memisahkan setiap layer spasial...', 80);
        var rawLayers = Array.isArray(gj) ? gj : [gj];
        var resultLayers = [];
        var usedNames = {};

        for (var k = 0; k < rawLayers.length; k++) {
            var fc = rawLayers[k];
            if (!fc || !fc.features || !Array.isArray(fc.features) || fc.features.length === 0) continue;

            var rawLayerName = fc.fileName || (baseName + (rawLayers.length > 1 ? ' Layer ' + (k + 1) : ''));
            var cleanLayerName = extractLayerName(rawLayerName, baseName);

            // Hindari tabrakan nama jika ada shapefile dengan nama mirip
            if (usedNames[cleanLayerName]) {
                usedNames[cleanLayerName]++;
                cleanLayerName += ' (' + usedNames[cleanLayerName] + ')';
            } else {
                usedNames[cleanLayerName] = 1;
            }

            var safeFileName = cleanLayerName.replace(/[/\\?%*:|"<>]/g, '_').trim() + '.geojson';
            resultLayers.push({
                outputName: safeFileName,
                displayName: cleanLayerName,
                geojson: optimizeGeoJsonCoordinates(fc)
            });
        }

        if (resultLayers.length === 0) {
            throw new Error('Tidak ditemukan layer spasial (.shp) yang valid di dalam file ZIP.');
        }

        onProgress('Mengoptimalkan koordinat ' + resultLayers.length + ' layer...', 88);
        return resultLayers;

    } else if (ext === 'kml') {
        onProgress('Mempersiapkan parser KML...', 20);
        await waitForLib(function() { return typeof toGeoJSON !== 'undefined'; }, 'toGeoJSON (KML)', 15);
        onProgress('Membaca file KML...', 40);
        var text = await item.file.text();
        onProgress('Mengubah KML ke GeoJSON...', 75);
        var parser = new DOMParser();
        var kmlDoc = parser.parseFromString(text, 'text/xml');
        var kmlGj = optimizeGeoJsonCoordinates(toGeoJSON.kml(kmlDoc));
        return [{ outputName: baseName + '.geojson', displayName: baseName, geojson: kmlGj }];

    } else if (ext === 'kmz') {
        onProgress('Mempersiapkan parser KMZ & JSZip...', 20);
        await waitForLib(function() { return typeof JSZip !== 'undefined'; }, 'JSZip (KMZ)', 15);
        await waitForLib(function() { return typeof toGeoJSON !== 'undefined'; }, 'toGeoJSON (KMZ)', 15);
        onProgress('Mengekstrak arsip KMZ...', 40);
        var ab = await item.file.arrayBuffer();
        var zip = await JSZip.loadAsync(ab);
        var kmlFile = null;
        zip.forEach(function(relPath, zf) {
            if (!kmlFile && relPath.toLowerCase().endsWith('.kml')) kmlFile = zf;
        });
        if (!kmlFile) throw new Error('Tidak ditemukan file .kml di dalam file .kmz ini.');
        onProgress('Mengubah KML ke GeoJSON...', 75);
        var kmlText = await kmlFile.async('string');
        var parser2 = new DOMParser();
        var kmlDoc2 = parser2.parseFromString(kmlText, 'text/xml');
        var kmzGj = optimizeGeoJsonCoordinates(toGeoJSON.kml(kmlDoc2));
        return [{ outputName: baseName + '.geojson', displayName: baseName, geojson: kmzGj }];

    } else if (ext === 'gpx') {
        onProgress('Mempersiapkan parser GPX...', 20);
        await waitForLib(function() { return typeof toGeoJSON !== 'undefined'; }, 'toGeoJSON (GPX)', 15);
        onProgress('Membaca file GPX...', 50);
        var gpxText = await item.file.text();
        onProgress('Mengubah rute/titik GPX ke GeoJSON...', 80);
        var parser3 = new DOMParser();
        var gpxDoc = parser3.parseFromString(gpxText, 'text/xml');
        var gpxGj = optimizeGeoJsonCoordinates(toGeoJSON.gpx(gpxDoc));
        return [{ outputName: baseName + '.geojson', displayName: baseName, geojson: gpxGj }];

    } else if (ext === 'csv') {
        onProgress('Membaca file tabel CSV...', 30);
        var csvTxt = await item.file.text();
        onProgress('Memetakan koordinat baris CSV...', 70);
        var csvGj = optimizeGeoJsonCoordinates(convertCsvToGeoJson(csvTxt));
        return [{ outputName: baseName + '.geojson', displayName: baseName, geojson: csvGj }];

    } else {
        throw new Error('Format .' + ext + ' tidak didukung.');
    }
}

function convertCsvToGeoJson(csvText) {
    var lines = csvText.split(/\r?\n/).filter(function(l) { return l.trim() !== ''; });
    if (lines.length < 2) throw new Error('File CSV kosong atau hanya memiliki baris header.');

    var sep = lines[0].includes(';') ? ';' : ',';
    var headers = lines[0].split(sep).map(function(h) { return h.trim().replace(/^["']|["']$/g, ''); });

    var latCol = -1, lngCol = -1;
    var latNames = ['lat','latitude','lintang','y','lat_dd'];
    var lngNames = ['lon','lng','longitude','bujur','x','long','lon_dd'];
    headers.forEach(function(h, i) {
        var hl = h.toLowerCase();
        if (latCol === -1 && latNames.some(function(n) { return hl === n; })) latCol = i;
        if (lngCol === -1 && lngNames.some(function(n) { return hl === n; })) lngCol = i;
    });
    if (latCol === -1 || lngCol === -1) {
        throw new Error('Kolom koordinat tidak ditemukan di CSV.\nPastikan kolom bernama: lat/latitude dan lon/lng/longitude.');
    }

    var features = [];
    for (var i = 1; i < lines.length; i++) {
        var cells = lines[i].split(sep).map(function(c) { return c.trim().replace(/^["']|["']$/g, ''); });
        var lat = parseFloat(cells[latCol]);
        var lng = parseFloat(cells[lngCol]);
        if (isNaN(lat) || isNaN(lng)) continue;

        var props = {};
        headers.forEach(function(h, hi) { if (hi !== latCol && hi !== lngCol) props[h] = cells[hi] || ''; });

        features.push({
            type: 'Feature',
            geometry: { type: 'Point', coordinates: [lng, lat] },
            properties: props
        });
    }
    if (features.length === 0) throw new Error('Tidak ada baris data koordinat yang valid di CSV.');
    return { type: 'FeatureCollection', features: features };
}

// ──────────────────────────────────────────────────────────────────
// Optimasi Koordinat: Pangkas desimal menjadi 6 angka secara in-place (hemat memori)
// ──────────────────────────────────────────────────────────────────
function optimizeGeoJsonCoordinates(gj) {
    if (!gj) return gj;
    function roundInPlace(c) {
        if (!Array.isArray(c) || c.length === 0) return;
        if (typeof c[0] === 'number') {
            c[0] = Math.round(c[0] * 1000000) / 1000000;
            c[1] = Math.round(c[1] * 1000000) / 1000000;
            return;
        }
        for (var i = 0; i < c.length; i++) {
            roundInPlace(c[i]);
        }
    }
    var features = gj.features || (gj.type === 'Feature' ? [gj] : []);
    for (var i = 0; i < features.length; i++) {
        var f = features[i];
        if (f && f.geometry && f.geometry.coordinates) {
            roundInPlace(f.geometry.coordinates);
        }
    }
    return gj;
}

// ──────────────────────────────────────────────────────────────────
// Helper: Upload file Blob dengan progress byte real-time & timeout aman
// ──────────────────────────────────────────────────────────────────
function uploadGeojsonBlobXhr(blob, outputName, csrfToken, onProgress) {
    return new Promise(function(resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route("peta.upload-geojson") }}', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        if (xhr.upload) {
            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable && onProgress) {
                    onProgress(e.loaded, e.total);
                }
            };
        }

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    var json = JSON.parse(xhr.responseText);
                    resolve(json);
                } catch(e) {
                    resolve({ success: true });
                }
            } else {
                var errMsg = 'Server error HTTP ' + xhr.status;
                try {
                    var errObj = JSON.parse(xhr.responseText);
                    if (errObj && errObj.message) errMsg = errObj.message;
                } catch(e) {}
                reject(new Error(errMsg));
            }
        };

        xhr.onerror = function() {
            reject(new Error('Koneksi terputus saat mengunggah layer. Silakan coba kembali.'));
        };

        xhr.ontimeout = function() {
            reject(new Error('Waktu upload habis (timeout). Periksa kestabilan server lokal.'));
        };
        xhr.timeout = 300000; // 5 menit

        var formData = new FormData();
        formData.append('file_geojson', blob, outputName);
        formData.append('_token', csrfToken);
        xhr.send(formData);
    });
}

// ──────────────────────────────────────────────────────────────────
// MAIN: Konversi semua file, pisahkan setiap layer, lalu upload ke server
// ──────────────────────────────────────────────────────────────────
async function mulaiKonversiDanUpload() {
    if (rawGisFiles.length === 0) return;

    var btn = document.getElementById('btnKonversiUpload');
    btn.disabled = true;

    var progressSection = document.getElementById('konversiProgressSection');
    var progressBar = document.getElementById('konversiProgressBar');
    var persenText = document.getElementById('konversiPersenText');
    var statusText = document.getElementById('konversiStatusText');
    var stepDetail = document.getElementById('konversiStepDetail');
    var timerText = document.getElementById('konversiTimerText');

    progressSection.style.display = 'block';
    document.getElementById('konversiResultSection').style.display = 'none';

    var startTime = Date.now();
    var timerIv = setInterval(function() {
        var sec = Math.floor((Date.now() - startTime) / 1000);
        timerText.textContent = sec + ' detik';
    }, 500);

    function updateProgress(stepMsg, percent) {
        progressBar.style.width = percent + '%';
        progressBar.textContent = percent + '%';
        persenText.textContent = percent + '%';
        stepDetail.innerHTML = '<i class="fas fa-cog fa-spin mr-1"></i>' + stepMsg;
    }

    var totalFiles = rawGisFiles.length;
    var totalLayersUploaded = 0;
    var successCount = 0, failCount = 0;
    var uploadedLayerNames = [];
    var csrfToken = document.querySelector('meta[name="csrf-token"]') ?
        document.querySelector('meta[name="csrf-token"]').content :
        '{{ csrf_token() }}';

    for (var i = 0; i < rawGisFiles.length; i++) {
        var item = rawGisFiles[i];
        item.status = 'converting';
        renderKonversiFileList();

        statusText.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Memproses file ' + (i + 1) + ' dari ' + totalFiles + ': <strong>' + item.file.name + '</strong>';
        updateProgress('Memulai konversi...', 10);

        try {
            var layers = await convertFileToGeoJson(item, function(stepMsg, subPct) {
                var overallPct = Math.round(((i + (subPct / 100)) / totalFiles) * 80);
                updateProgress(stepMsg, Math.max(10, overallPct));
            });

            if (!layers || !Array.isArray(layers) || layers.length === 0) {
                throw new Error('Hasil konversi tidak menghasilkan layer spasial yang valid.');
            }

            // Upload setiap layer secara terpisah ke server
            for (var l = 0; l < layers.length; l++) {
                var layer = layers[l];
                var layerNum = l + 1;
                var totalL = layers.length;
                updateProgress('Mempersiapkan data layer (' + layerNum + '/' + totalL + '): ' + layer.displayName + '...', 80);
                await uiYield(30);

                var geojsonStr = JSON.stringify(layer.geojson);
                var blob = new Blob([geojsonStr], { type: 'application/json' });
                var blobMb = (blob.size / 1048576).toFixed(1);

                await uploadGeojsonBlobXhr(blob, layer.outputName, csrfToken, function(loaded, total) {
                    var pct = Math.round((loaded / total) * 100);
                    var loadedMb = (loaded / 1048576).toFixed(1);
                    var overallPct = 80 + Math.round(((l + (loaded / total)) / totalL) * 18);
                    updateProgress('Mengunggah layer (' + layerNum + '/' + totalL + '): ' + layer.displayName + ' (' + loadedMb + ' / ' + blobMb + ' MB - ' + pct + '%)...', Math.min(99, overallPct));
                });

                totalLayersUploaded++;
                uploadedLayerNames.push(layer.displayName);
            }

            item.status = 'done';
            successCount++;

        } catch (err) {
            item.status = 'error';
            item.errorMsg = err.message || 'Terjadi kesalahan tidak diketahui saat konversi.';
            failCount++;
            console.error('Gagal konversi/upload file:', item.file.name, err);
        }

        renderKonversiFileList();
    }

    clearInterval(timerIv);
    updateProgress('Selesai diproses!', 100);
    progressBar.className = 'progress-bar bg-success font-weight-bold';

    statusText.innerHTML = '<i class="fas fa-check-circle text-success mr-1"></i>Proses Konversi Selesai';

    var resultSection = document.getElementById('konversiResultSection');
    var resultAlert = document.getElementById('konversiResultAlert');
    var resultText = document.getElementById('konversiResultText');
    resultSection.style.display = 'block';

    if (failCount === 0) {
        resultAlert.className = 'alert alert-success py-2 px-3 small mb-0 rounded shadow-sm';
        resultText.innerHTML = '<i class="fas fa-check-circle mr-1"></i><strong>' + totalLayersUploaded + ' layer GIS</strong> (' + uploadedLayerNames.join(', ') + ') berhasil diekstrak dan disimpan ke peta! Halaman akan dimuat ulang...';
        setTimeout(function() { location.reload(); }, 2000);
    } else if (totalLayersUploaded > 0) {
        resultAlert.className = 'alert alert-warning py-2 px-3 small mb-0 rounded shadow-sm';
        resultText.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i><strong>' + totalLayersUploaded + ' layer GIS</strong> berhasil, namun ada file yang gagal. Memuat ulang data yang berhasil...';
        setTimeout(function() { location.reload(); }, 2500);
    } else {
        resultAlert.className = 'alert alert-danger py-2 px-3 small mb-0 rounded shadow-sm';
        resultText.innerHTML = '<i class="fas fa-times-circle mr-1"></i>Konversi gagal. Pastikan file Shapefile (.zip) berisi .shp, .dbf, dan .prj yang lengkap.';
        btn.disabled = false;
    }
}
</script>

<!-- Modal Kelola & Hapus File GeoJSON -->
<div class="modal fade" id="modalKelolaGeojson" tabindex="-1" role="dialog" aria-labelledby="modalKelolaGeojsonLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title font-weight-bold" id="modalKelolaGeojsonLabel">
                    <i class="fas fa-folder-open text-warning mr-2"></i>Kelola File GIS (public/geojson)
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <div class="small text-muted mb-2 mb-sm-0 d-flex align-items-center flex-wrap gap-2">
                        <span>Total tersimpan: <span class="badge badge-primary px-2 py-1">{{ count($fileList ?? []) }} File GIS</span></span>
                        <span id="bulk-selected-info" class="ml-2" style="display:none;">
                            <span class="badge badge-warning text-dark px-2 py-1">
                                <i class="fas fa-check-square mr-1"></i><span id="bulk-selected-count">0</span> Dipilih
                            </span>
                        </span>
                    </div>
                    <div class="w-50">
                        <input type="text" id="cari-file-geojson" class="form-control form-control-sm" placeholder="🔍 Cari nama file..." onkeyup="filterTabelFileGeojson(this.value)">
                    </div>
                </div>

                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm table-hover table-bordered mb-0" id="tabel-kelola-geojson">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%" class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="chk-select-all-geojson">
                                        <label class="custom-control-label" for="chk-select-all-geojson"></label>
                                    </div>
                                </th>
                                <th width="4%" class="text-center">#</th>
                                <th>Nama File</th>
                                <th width="13%" class="text-center">Ukuran</th>
                                <th width="20%" class="text-center">Waktu Simpan</th>
                                <th width="13%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fileList ?? [] as $index => $f)
                            <tr class="item-file-row">
                                <td class="text-center align-middle">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chk-file-geojson" id="chk-file-{{ $index }}" value="{{ $f['name'] }}">
                                        <label class="custom-control-label" for="chk-file-{{ $index }}"></label>
                                    </div>
                                </td>
                                <td class="text-center font-weight-bold text-muted align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle">
                                    <i class="fas fa-file-code text-info mr-1"></i>
                                    <span class="nama-file-text font-weight-bold">{{ $f['name'] }}</span>
                                    @if(!$f['is_default'])
                                        <span class="badge badge-warning text-dark ml-1 small"><i class="fas fa-cloud-upload-alt mr-1"></i>Upload</span>
                                    @else
                                        <span class="badge badge-light border text-secondary ml-1 small">Sistem</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle"><span class="badge badge-light border">{{ $f['size'] }}</span></td>
                                <td class="text-center small text-muted align-middle">{{ $f['date'] }}</td>
                                <td class="text-center align-middle">
                                    <form action="{{ route('peta.delete-geojson') }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus file \'{{ $f['name'] }}\'? File ini akan dihapus secara permanen dari server dan tidak akan dimuat lagi di peta.')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="file_name" value="{{ $f['name'] }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2 font-weight-bold" title="Hapus File Ini">
                                            <i class="fas fa-trash-alt mr-1"></i>Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Tidak ada file GIS di folder public/geojson</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 d-flex justify-content-between align-items-center">
                {{-- Tombol Hapus Terpilih (Bulk Delete) --}}
                <div>
                    <button type="button" id="btn-hapus-terpilih" class="btn btn-danger btn-sm font-weight-bold" disabled
                        onclick="konfirmasiHapusTerpilih()">
                        <i class="fas fa-trash-alt mr-1"></i>Hapus Terpilih (<span id="btn-bulk-count">0</span>)
                    </button>
                </div>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Form tersembunyi untuk submit bulk delete --}}
<form id="form-bulk-delete" action="{{ route('peta.delete-geojson-bulk') }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
    <div id="bulk-delete-inputs"></div>
</form>

{{-- Filter Bar: muncul kondisional berdasarkan ketersediaan file GIS --}}
@if($hasFiles)
<div class="card shadow-sm mb-4 border-left-primary">
    <div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between">
        <div class="d-flex align-items-center my-1">
            <span class="font-weight-bold text-gray-700 mr-2 small"><i class="fas fa-filter text-primary mr-1"></i>Filter:</span>
            <div class="btn-group btn-group-sm" role="group" id="btn-filter-wilayah">
                <button type="button" class="btn btn-outline-primary active" data-filter="all">
                    <i class="fas fa-layer-group mr-1"></i>Semua Data GIS
                    <span class="badge badge-primary ml-1">{{ count($fileList) }} File</span>
                </button>
            </div>
        </div>
        <div class="small text-muted my-1" id="info-fitur-termuat">
            <i class="fas fa-spinner fa-spin text-info mr-1"></i> Memuat layer GIS ArcMap...
        </div>
    </div>
</div>
@else
<div class="card shadow-sm mb-4 border-left-warning">
    <div class="card-body py-2 px-3 d-flex align-items-center">
        <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
        <span class="small text-muted">
            Belum ada file GIS yang diunggah. Gunakan tombol
            <button type="button" class="btn btn-xs btn-info btn-sm py-0 px-2 mx-1" data-toggle="modal" data-target="#modalUploadGeojson">
                <i class="fas fa-cloud-upload-alt mr-1"></i>Upload GeoJSON
            </button>
            untuk menambahkan data ke peta.
        </span>
    </div>
</div>
@endif


<!-- Peta Interaktif Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-primary text-white">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-globe-asia mr-1"></i> Visualisasi Spasial (WebGIS BWSK I)
        </h6>
        <div id="map-loading" class="small text-white font-weight-bold">
            <i class="fas fa-spinner fa-spin mr-1"></i> Membaca data koordinat ArcMap...
        </div>
    </div>
    <div class="card-body p-0 position-relative">
        <div id="map" style="height: 600px; width: 100%; z-index: 1;"></div>
    </div>
    <div class="card-footer bg-light text-muted small d-flex flex-wrap justify-content-between align-items-center py-2">
        <div>
            <i class="fas fa-mouse-pointer text-primary mr-1"></i>
            Klik fitur pada peta (Saluran, Bangunan, atau Area DIR) untuk menampilkan tabel atribut lengkap dari ArcMap.
        </div>
        <div class="font-weight-bold text-primary">
            Data IGT DIR Kewenangan BWSK I Pontianak
        </div>
    </div>
</div>

<!-- Tabel Daftar Koordinat & Data Spasial -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center">
        <div class="d-flex align-items-center flex-wrap my-1">
            <h6 class="m-0 font-weight-bold text-primary mr-2">
                <i class="fas fa-list-alt mr-2"></i>Daftar Koordinat & Data Spasial GIS
            </h6>
            @if($hasFiles)
                <span class="badge badge-info small mr-2" id="badge-total-file-tabel">{{ count($fileList) }} File Aktif</span>
            @endif
        </div>
        @if($hasFiles)
        <div class="d-flex align-items-center flex-wrap my-1">
            {{-- Toggle Mode: Per File GIS vs Rincian Objek --}}
            <div class="btn-group btn-group-sm mr-2" role="group" id="tabel-mode-switch">
                <button type="button" class="btn btn-primary active font-weight-bold" id="btn-mode-file" onclick="setTabelMode('file')">
                    <i class="fas fa-folder mr-1"></i>Ringkasan File (<span id="count-mode-file">{{ count($fileList) }}</span>)
                </button>
                <button type="button" class="btn btn-outline-primary font-weight-bold" id="btn-mode-objek" onclick="setTabelMode('objek')">
                    <i class="fas fa-map-marker-alt mr-1"></i>Rincian Objek (<span id="count-mode-objek">0</span>)
                </button>
            </div>
            {{-- Input Pencarian Cepat --}}
            <div style="min-width: 220px;">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" id="cari-tabel-koordinat" class="form-control border-left-0" placeholder="Cari nama objek/file..." onkeyup="filterTabelKoordinat(this.value)">
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="card-body {{ $hasFiles ? '' : 'p-0' }}">
        @if($hasFiles)
        <div class="table-responsive" style="max-height: 550px; overflow-y: auto;">
            <table class="table table-bordered table-hover table-sm mb-0" id="tabel-lokasi">
                <thead class="thead-dark sticky-top">
                    <tr>
                        <th width="4%" class="text-center align-middle">#</th>
                        <th class="align-middle">Nama Objek / Fitur</th>
                        <th width="18%" class="align-middle">Wilayah / Kategori</th>
                        <th width="13%" class="text-center align-middle">Latitude (Y)</th>
                        <th width="13%" class="text-center align-middle">Longitude (X)</th>
                        <th width="22%" class="align-middle">Sumber File</th>
                        <th width="10%" class="text-center align-middle">Navigasi Peta</th>
                    </tr>
                </thead>
                <tbody id="tbody-lokasi">
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-spinner fa-spin text-primary mr-2"></i>Membaca koordinat dari {{ count($fileList) }} file GIS...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light py-2 px-3 d-flex justify-content-between align-items-center small text-muted">
            <span id="tabel-footer-info">Memuat data koordinat...</span>
            <span><i class="fas fa-sync-alt text-success mr-1"></i>Otomatis sinkron dengan file yang diunggah</span>
        </div>
        @else
        <div class="text-center py-5 px-3">
            <div class="mb-3">
                <i class="fas fa-map-marked-alt fa-4x text-gray-300"></i>
            </div>
            <h5 class="font-weight-bold text-gray-600 mb-2">Peta & Data Masih Kosong</h5>
            <p class="text-muted small mb-4">Belum ada file GeoJSON yang diunggah ke sistem. Upload file untuk menampilkan layer peta, legenda, dan koordinat secara otomatis.</p>
            <button type="button" class="btn btn-info font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalUploadGeojson">
                <i class="fas fa-cloud-upload-alt mr-2"></i>Upload File GeoJSON Sekarang
            </button>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<!-- Proj4 JS untuk reproyeksi koordinat -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.9.0/proj4.js"></script>

<style>
    .map-legend {
        background: rgba(255,255,255,0.97);
        padding: 10px 14px;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.22);
        font-size: 12px;
        line-height: 19px;
        max-width: 290px;
        border: 1px solid #ddd;
    }
    .legend-content {
        max-height: 380px;
        overflow-y: auto;
        padding-right: 4px;
    }
    .map-legend h6 {
        font-size: 13px;
        font-weight: bold;
        margin-bottom: 8px;
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 5px;
        color: #333;
    }
    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
        gap: 8px;
    }
    .legend-color-box {
        width: 20px;
        height: 13px;
        border-radius: 3px;
        border: 1px solid rgba(0,0,0,0.25);
        flex-shrink: 0;
    }
    .legend-line {
        width: 22px;
        height: 4px;
        border-radius: 2px;
        flex-shrink: 0;
    }
    .legend-circle {
        width: 11px;
        height: 11px;
        border-radius: 50%;
        border: 1.5px solid #fff;
        box-shadow: 0 0 0 1px #999;
        flex-shrink: 0;
    }
    .custom-dir-pin {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #fff;
        font-weight: bold;
        box-shadow: 0 2px 6px rgba(0,0,0,0.4);
        border: 2.5px solid #ffffff;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 8px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.22);
        padding: 2px;
    }
    .leaflet-popup-content {
        margin: 10px 14px;
        line-height: 1.45;
    }
    .gis-popup-table { font-size: 11px; margin-bottom: 0; }
    .gis-popup-table td, .gis-popup-table th { padding: 3px 6px; }
    .leaflet-control-layers {
        border-radius: 8px !important;
        box-shadow: 0 2px 12px rgba(0,0,0,0.18) !important;
        border: 1px solid #ccc !important;
        font-size: 13px !important;
        transition: all 0.2s ease;
    }
    .leaflet-control-layers-expanded {
        padding: 8px 12px !important;
        min-width: 210px !important;
    }
    .leaflet-control-layers label { margin-bottom: 4px !important; cursor: pointer !important; }
    
    /* Header & Tombol Minimize untuk Layer Control & Legenda */
    .custom-control-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 700;
        font-size: 13px;
        color: #2d3748;
        padding-bottom: 6px;
        margin-bottom: 8px;
        border-bottom: 1px solid #e2e8f0;
        cursor: pointer;
        user-select: none;
    }
    .custom-control-header:hover {
        color: #1a56db;
    }
    .custom-control-header .control-header-title {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .btn-toggle-box {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        width: 22px;
        height: 22px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 11px;
        color: #475569;
        transition: all 0.2s ease;
        padding: 0;
        line-height: 1;
    }
    .btn-toggle-box:hover {
        background: #e2e8f0;
        color: #0f172a;
        border-color: #94a3b8;
    }
    .btn-toggle-box:focus {
        outline: none;
    }

    /* State Minimized untuk Layer Control */
    .leaflet-control-layers.is-minimized .leaflet-control-layers-list {
        display: none !important;
    }
    .leaflet-control-layers.is-minimized .custom-control-header {
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
        border-bottom: none !important;
    }
    .leaflet-control-layers.is-minimized {
        padding: 6px 10px !important;
        min-width: unset !important;
    }

    /* State Minimized untuk Legenda */
    .map-legend.is-minimized .legend-content {
        display: none !important;
    }
    .map-legend.is-minimized .custom-control-header {
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
        border-bottom: none !important;
    }
    .map-legend.is-minimized {
        padding: 6px 10px !important;
        max-width: unset !important;
    }

</style>

<script>
function filterTabelFileGeojson(keyword) {
    keyword = (keyword || '').toLowerCase();
    document.querySelectorAll('#tabel-kelola-geojson tbody tr.item-file-row').forEach(function(row) {
        var el = row.querySelector('.nama-file-text');
        if (!el) return;
        var text = el.innerText.toLowerCase();
        row.style.display = text.indexOf(keyword) !== -1 ? '' : 'none';
    });
}

function updateBulkDeleteUI() {
    var checked = document.querySelectorAll('.chk-file-geojson:checked');
    var total = checked.length;
    var countEl = document.getElementById('bulk-selected-count');
    var btnCountEl = document.getElementById('btn-bulk-count');
    var infoEl = document.getElementById('bulk-selected-info');
    var btn = document.getElementById('btn-hapus-terpilih');

    if (countEl) countEl.textContent = total;
    if (btnCountEl) btnCountEl.textContent = total;
    if (infoEl) infoEl.style.display = total > 0 ? '' : 'none';
    if (btn) btn.disabled = total === 0;

    // Update state checkbox select-all
    var all = document.querySelectorAll('.chk-file-geojson');
    var chkAll = document.getElementById('chk-select-all-geojson');
    if (chkAll) {
        if (total === 0) {
            chkAll.indeterminate = false;
            chkAll.checked = false;
        } else if (total === all.length) {
            chkAll.indeterminate = false;
            chkAll.checked = true;
        } else {
            chkAll.indeterminate = true;
            chkAll.checked = false;
        }
    }
}

function konfirmasiHapusTerpilih() {
    var checked = document.querySelectorAll('.chk-file-geojson:checked');
    if (checked.length === 0) return;

    var names = [];
    checked.forEach(function(chk) { names.push(chk.value); });

    var msg = 'Anda akan menghapus ' + checked.length + ' file GIS secara permanen:\n\n';
    names.forEach(function(n, i) { msg += (i + 1) + '. ' + n + '\n'; });
    msg += '\nFile yang dihapus tidak dapat dikembalikan. Lanjutkan?';

    if (!confirm(msg)) return;

    // Isi hidden form dengan input array
    var container = document.getElementById('bulk-delete-inputs');
    container.innerHTML = '';
    names.forEach(function(name) {
        var inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'file_names[]';
        inp.value = name;
        container.appendChild(inp);
    });

    document.getElementById('form-bulk-delete').submit();
}

// Inisialisasi event checkbox setelah DOM siap
document.addEventListener('DOMContentLoaded', function() {
    // Checkbox "Pilih Semua"
    var chkAll = document.getElementById('chk-select-all-geojson');
    if (chkAll) {
        chkAll.addEventListener('change', function() {
            document.querySelectorAll('.chk-file-geojson').forEach(function(chk) {
                chk.checked = chkAll.checked;
            });
            updateBulkDeleteUI();
        });
    }

    // Checkbox per baris
    document.querySelectorAll('.chk-file-geojson').forEach(function(chk) {
        chk.addEventListener('change', function() {
            updateBulkDeleteUI();
        });
    });

    // Reset checkbox saat modal ditutup
    var modalKelola = document.getElementById('modalKelolaGeojson');
    if (modalKelola) {
        modalKelola.addEventListener('hidden.bs.modal', function() {
            document.querySelectorAll('.chk-file-geojson').forEach(function(chk) { chk.checked = false; });
            var chkAllEl = document.getElementById('chk-select-all-geojson');
            if (chkAllEl) { chkAllEl.checked = false; chkAllEl.indeterminate = false; }
            updateBulkDeleteUI();
        });
        // jQuery fallback (Bootstrap 3/4 pakai jQuery)
        $(modalKelola).on('hidden.bs.modal', function() {
            document.querySelectorAll('.chk-file-geojson').forEach(function(chk) { chk.checked = false; });
            var chkAllEl = document.getElementById('chk-select-all-geojson');
            if (chkAllEl) { chkAllEl.checked = false; chkAllEl.indeterminate = false; }
            updateBulkDeleteUI();
        });
    }
});


function updateFileInputLabel(input) {
    var label = document.getElementById('labelGeojsonFile');
    if (!label) return;
    if (input.files && input.files.length > 1) {
        label.innerText = input.files.length + ' file dipilih';
    } else if (input.files && input.files.length === 1) {
        label.innerText = input.files[0].name;
    } else {
        label.innerText = 'Pilih satu atau beberapa file...';
    }
}

// Handler Flash Message dari Batch Upload via sessionStorage
(function() {
    var flashMsg = sessionStorage.getItem('upload_flash_success');
    if (flashMsg) {
        sessionStorage.removeItem('upload_flash_success');
        var alertContainer = document.getElementById('js-flash-alert-container');
        if (alertContainer) {
            alertContainer.innerHTML = '<div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">' +
                '<i class="fas fa-check-circle mr-2"></i>' + flashMsg +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>';
        }
    }
})();

// Inisialisasi Batch Upload GeoJSON (Bypass limit max_file_uploads = 20 PHP)
document.addEventListener('DOMContentLoaded', function () {
    var formUpload = document.getElementById('formUploadGeojson');
    if (!formUpload) return;

    formUpload.addEventListener('submit', async function(e) {
        e.preventDefault();

        var input = document.getElementById('inputGeojsonFile');
        if (!input || !input.files || input.files.length === 0) {
            alert('Silakan pilih setidaknya satu file GeoJSON/JSON terlebih dahulu.');
            return;
        }

        var allSelectedFiles = Array.from(input.files);
        var files = allSelectedFiles.filter(function(f) {
            var ext = f.name.split('.').pop().toLowerCase();
            return ext === 'geojson' || ext === 'json';
        });

        if (files.length === 0) {
            alert('Tidak ada file berekstensi .geojson atau .json yang valid dari file yang Anda pilih.');
            return;
        }

        var totalFiles = files.length;
        // Batch size 10: sangat aman di bawah limit bawaan PHP max_file_uploads = 20
        var BATCH_SIZE = 10;
        var totalBatches = Math.ceil(totalFiles / BATCH_SIZE);

        var progressSection = document.getElementById('uploadProgressSection');
        var progressBar     = document.getElementById('uploadProgressBar');
        var percentText     = document.getElementById('uploadPercentText');
        var statusText      = document.getElementById('uploadStatusText');
        var detailText      = document.getElementById('uploadDetailText');
        var batchCountText  = document.getElementById('uploadBatchCountText');
        var successSection  = document.getElementById('uploadSuccessSection');
        var errorSection    = document.getElementById('uploadErrorSection');
        var btnSubmit       = document.getElementById('btnSubmitUpload');
        var btnBatal        = document.getElementById('btnBatalUpload');
        var btnClose        = document.getElementById('btnCloseUploadModal');

        // Reset state
        if (errorSection) { errorSection.style.display = 'none'; errorSection.innerText = ''; }
        if (successSection) { successSection.style.display = 'none'; }
        if (progressSection) progressSection.style.display = 'block';
        if (btnSubmit) {
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Mengunggah...';
        }
        if (btnBatal) btnBatal.disabled = true;
        if (btnClose) btnClose.style.display = 'none';

        var totalUploaded = 0;
        var allUploadedNames = [];
        var allFailed = [];
        var csrfToken = formUpload.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

        try {
            for (var b = 0; b < totalBatches; b++) {
                var start = b * BATCH_SIZE;
                var end = Math.min(start + BATCH_SIZE, totalFiles);
                var batchFiles = files.slice(start, end);
                var currentBatchNum = b + 1;

                if (statusText) {
                    statusText.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Mengunggah batch ' + currentBatchNum + ' dari ' + totalBatches + '...';
                }
                if (batchCountText) {
                    batchCountText.textContent = 'Batch ' + currentBatchNum + '/' + totalBatches + ' (' + batchFiles.length + ' file)';
                }

                var formData = new FormData();
                formData.append('_token', csrfToken);
                for (var f = 0; f < batchFiles.length; f++) {
                    formData.append('file_geojson[]', batchFiles[f]);
                }

                var response = await fetch("{{ route('peta.upload-geojson') }}", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (!response.ok) {
                    var errJson = await response.json().catch(function() { return {}; });
                    throw new Error(errJson.message || ('Gagal pada pengunggahan batch ke-' + currentBatchNum + ' (HTTP ' + response.status + ')'));
                }

                var result = await response.json();
                if (result.uploaded && result.uploaded.length > 0) {
                    allUploadedNames = allUploadedNames.concat(result.uploaded);
                    totalUploaded += result.uploaded.length;
                }
                if (result.failed && result.failed.length > 0) {
                    allFailed = allFailed.concat(result.failed);
                }

                var percent = Math.min(100, Math.round((totalUploaded / totalFiles) * 100));
                if (progressBar) {
                    progressBar.style.width = percent + '%';
                    progressBar.textContent = percent + '%';
                }
                if (percentText) percentText.textContent = percent + '%';
                if (detailText) {
                    detailText.innerHTML = '<span>' + totalUploaded + ' dari ' + totalFiles + ' file berhasil diproses...</span><span id="uploadBatchCountText">Batch ' + currentBatchNum + '/' + totalBatches + '</span>';
                }
            }

            if (progressBar) {
                progressBar.style.width = '100%';
                progressBar.textContent = '100%';
                progressBar.classList.remove('progress-bar-animated', 'progress-bar-striped');
                progressBar.classList.add('bg-success');
            }
            if (statusText) {
                statusText.innerHTML = '<i class="fas fa-check-circle text-success mr-1"></i>Pengunggahan Selesai!';
            }
            if (successSection) {
                var succMsg = 'Berhasil mengunggah total ' + totalUploaded + ' file GeoJSON!';
                if (allFailed.length > 0) {
                    succMsg += ' (' + allFailed.length + ' file dilewati karena bukan .json/.geojson)';
                }
                successSection.innerHTML = '<i class="fas fa-check-circle mr-1"></i>' + succMsg + '<br><small class="text-muted">Menyegarkan peta dalam sekejap...</small>';
                successSection.style.display = 'block';
            }

            var finalMsg = totalUploaded + ' file GeoJSON berhasil diunggah ke public/geojson (' + (allUploadedNames.slice(0, 5).join(', ') + (allUploadedNames.length > 5 ? '... dan ' + (allUploadedNames.length - 5) + ' file lainnya' : '')) + ').';
            sessionStorage.setItem('upload_flash_success', finalMsg);

            setTimeout(function() {
                window.location.reload();
            }, 1200);

        } catch (err) {
            console.error('Error saat upload batch:', err);
            if (progressBar) {
                progressBar.classList.remove('bg-success');
                progressBar.classList.add('bg-danger');
            }
            if (errorSection) {
                errorSection.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i>' + (err.message || 'Terjadi kesalahan saat mengunggah file.');
                errorSection.style.display = 'block';
            }
            if (statusText) {
                statusText.innerHTML = '<i class="fas fa-times-circle text-danger mr-1"></i>Gagal Mengunggah';
            }
            if (btnSubmit) {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fas fa-upload mr-1"></i>Coba Upload Lagi';
            }
            if (btnBatal) btnBatal.disabled = false;
            if (btnClose) btnClose.style.display = '';
        }
    });

    // Reset form dan state progress saat modal ditutup
    $('#modalUploadGeojson').on('hidden.bs.modal', function () {
        var progressSection = document.getElementById('uploadProgressSection');
        var errorSection    = document.getElementById('uploadErrorSection');
        var successSection  = document.getElementById('uploadSuccessSection');
        var btnSubmit       = document.getElementById('btnSubmitUpload');
        var btnBatal        = document.getElementById('btnBatalUpload');
        var btnClose        = document.getElementById('btnCloseUploadModal');
        var progressBar     = document.getElementById('uploadProgressBar');
        var input           = document.getElementById('inputGeojsonFile');
        var label           = document.getElementById('labelGeojsonFile');

        if (progressSection) progressSection.style.display = 'none';
        if (errorSection) errorSection.style.display = 'none';
        if (successSection) successSection.style.display = 'none';
        if (btnSubmit) { btnSubmit.disabled = false; btnSubmit.innerHTML = '<i class="fas fa-upload mr-1"></i>Upload Sekarang'; }
        if (btnBatal) btnBatal.disabled = false;
        if (btnClose) btnClose.style.display = '';
        if (progressBar) {
            progressBar.style.width = '0%';
            progressBar.textContent = '0%';
            progressBar.className = 'progress-bar progress-bar-striped progress-bar-animated bg-success font-weight-bold';
        }
        if (input) input.value = '';
        if (label) label.innerText = 'Pilih satu atau beberapa file...';
    });
});

document.addEventListener('DOMContentLoaded', function () {

    // 1. PROJ4 DEFINITIONS
    proj4.defs("EPSG:4326",  "+proj=longlat +datum=WGS84 +no_defs");
    proj4.defs("EPSG:32649", "+proj=utm +zone=49 +datum=WGS84 +units=m +no_defs");
    proj4.defs("EPSG:32749", "+proj=utm +zone=49 +south +datum=WGS84 +units=m +no_defs");
    proj4.defs("EPSG:54034", "+proj=merc +lon_0=0 +k=1 +x_0=0 +y_0=0 +datum=WGS84 +units=m +no_defs");
    proj4.defs("EPSG:3857",  "+proj=merc +a=6378137 +b=6378137 +lat_ts=0 +lon_0=0 +x_0=0 +y_0=0 +k=1 +units=m +nadgrids=@null +wktext +no_defs");

    // 2. BASEMAPS
    var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });
    var googleSat = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        attribution: '&copy; Google Satellite'
    });

    var map = L.map('map', {
        center: [0.55, 109.15],
        zoom: 8,
        layers: [osm],
        zoomControl: true
    });

    // 3. OVERLAY LAYER GROUPS (dinamis — hanya buat layer sesuai tipe file yang tersedia)
    var availableTypes = {!! json_encode($availableTypes) !!};
    var hasFiles = {{ $hasFiles ? 'true' : 'false' }};

    // Selalu buat semua layer group (agar referensi JS tidak error), tapi hanya tampilkan di peta jika ada file-nya
    var layerBaku       = L.layerGroup(); if (availableTypes.baku)       layerBaku.addTo(map);
    var layerFungsional = L.layerGroup(); if (availableTypes.fungsional) layerFungsional.addTo(map);
    var layerPotensial  = L.layerGroup(); if (availableTypes.potensial)  layerPotensial.addTo(map);
    var layerSaluran    = L.layerGroup(); if (availableTypes.saluran)    layerSaluran.addTo(map);
    var layerBangunan   = L.layerGroup(); if (availableTypes.bangunan)   layerBangunan.addTo(map);

    var baseMaps = {
        "OpenStreetMap": osm,
        "Satelit (Google Hybrid)": googleSat
    };

    // Overlay hanya ditambahkan jika ada file untuk tipe tersebut
    var overlayMaps = {};
    if (availableTypes.baku)       overlayMaps["Daerah Irigasi Baku (Hijau)"]       = layerBaku;
    if (availableTypes.fungsional) overlayMaps["Daerah Irigasi Fungsional (Biru)"]  = layerFungsional;
    if (availableTypes.potensial)  overlayMaps["Daerah Irigasi Potensial (Oranye)"] = layerPotensial;
    if (availableTypes.saluran)    overlayMaps["Jaringan Saluran Irigasi"]           = layerSaluran;
    if (availableTypes.bangunan)   overlayMaps["Bangunan Air / Pelengkap"]           = layerBangunan;

    // LAYER CONTROL DENGAN HEADER & TOMBOL MINIMIZE
    var layerControl = L.control.layers(baseMaps, overlayMaps, { collapsed: false, position: 'topright' }).addTo(map);
    var layerControlContainer = layerControl.getContainer();

    var layerHeader = L.DomUtil.create('div', 'custom-control-header');
    layerHeader.innerHTML = 
        '<span class="control-header-title"><i class="fas fa-layer-group text-primary mr-1"></i> Layer Peta</span>' +
        '<button type="button" class="btn-toggle-box" id="btn-toggle-layers" title="Minimize / Perluas Layer">' +
            '<i class="fas fa-chevron-up"></i>' +
        '</button>';
    layerControlContainer.insertBefore(layerHeader, layerControlContainer.firstChild);

    var btnToggleLayers = layerHeader.querySelector('#btn-toggle-layers');
    var iconToggleLayers = btnToggleLayers.querySelector('i');

    function toggleLayerBox(e) {
        if (e) {
            L.DomEvent.stopPropagation(e);
            L.DomEvent.preventDefault(e);
        }
        var isMin = layerControlContainer.classList.toggle('is-minimized');
        if (isMin) {
            iconToggleLayers.className = 'fas fa-chevron-down';
            btnToggleLayers.title = 'Buka / Perluas Layer';
        } else {
            iconToggleLayers.className = 'fas fa-chevron-up';
            btnToggleLayers.title = 'Minimize / Perkecil Layer';
        }
    }

    L.DomEvent.on(btnToggleLayers, 'click', toggleLayerBox);
    L.DomEvent.on(layerHeader, 'click', function(e) {
        if (e.target !== btnToggleLayers && !btnToggleLayers.contains(e.target)) {
            toggleLayerBox(e);
        }
    });
    L.DomEvent.disableClickPropagation(layerHeader);

    // 4. MAP BOUNDS
    var mapBounds = L.latLngBounds();


    // 5. LEGENDA DINAMIS (hanya tampil jika ada file; item hanya untuk tipe layer yang ada)
    var legend = L.control({ position: 'bottomleft' });
    legend.onAdd = function () {
        var div = L.DomUtil.create('div', 'map-legend');

        // Bangun item legenda dinamis berdasarkan tipe file yang tersedia
        var legendItems = '';
        if (availableTypes.baku)       legendItems += '<div class="legend-item"><span class="legend-color-box" style="background:#27ae60;border-color:#1b7a43;"></span> <b>DIR Baku</b> (Hijau)</div>';
        if (availableTypes.fungsional) legendItems += '<div class="legend-item"><span class="legend-color-box" style="background:#2980b9;border-color:#1a5276;"></span> <b>DIR Fungsional</b> (Biru)</div>';
        if (availableTypes.potensial)  legendItems += '<div class="legend-item"><span class="legend-color-box" style="background:#e67e22;border-color:#b95c00;"></span> <b>DIR Potensial</b> (Oranye)</div>';
        if (availableTypes.saluran) {
            legendItems += '<div class="legend-item"><span class="legend-line" style="background:#e74c3c;"></span> <b>Saluran Primer</b> (Merah)</div>';
            legendItems += '<div class="legend-item"><span class="legend-line" style="background:#0984e3;"></span> <b>Saluran Sekunder</b> (Biru)</div>';
            legendItems += '<div class="legend-item"><span class="legend-line" style="background:#00b894;"></span> <b>Saluran Sub Sek.</b> (Teal)</div>';
        }
        if (availableTypes.bangunan)   legendItems += '<div class="legend-item"><span class="legend-circle" style="background:#d63031;"></span> <b>Bangunan Air</b> (Titik)</div>';
        if (availableTypes.custom) {
            legendItems += '<div id="legend-custom-section" style="display:none;">' +
                '<div class="legend-separator my-2" style="border-top:1px dashed #cbd5e1;"></div>' +
                '<div class="small font-weight-bold text-gray-700 mb-1 d-flex align-items-center" style="font-size:11px;letter-spacing:0.2px;">' +
                    '<i class="fas fa-layer-group text-primary mr-1"></i> Layer Data Upload:' +
                '</div>' +
                '<div id="legend-custom-items"></div>' +
            '</div>';
        }

        // Jika tidak ada item legenda, sembunyikan kontrol ini
        if (!legendItems) {
            div.style.display = 'none';
            return div;
        }

        div.innerHTML =
            '<div class="custom-control-header" id="legend-header">' +
                '<span class="control-header-title"><i class="fas fa-palette text-primary mr-1"></i> Legenda Peta</span>' +
                '<button type="button" class="btn-toggle-box" id="btn-toggle-legend" title="Minimize / Perluas Legenda">' +
                    '<i class="fas fa-chevron-up"></i>' +
                '</button>' +
            '</div>' +
            '<div class="legend-content" id="legend-content-body">' +
                legendItems +
            '</div>';

        var btnToggleLegend = div.querySelector('#btn-toggle-legend');
        var iconToggleLegend = btnToggleLegend.querySelector('i');
        var legendHeader = div.querySelector('#legend-header');

        function toggleLegendBox(e) {
            if (e) {
                L.DomEvent.stopPropagation(e);
                L.DomEvent.preventDefault(e);
            }
            var isMin = div.classList.toggle('is-minimized');
            if (isMin) {
                iconToggleLegend.className = 'fas fa-chevron-down';
                btnToggleLegend.title = 'Buka / Perluas Legenda';
            } else {
                iconToggleLegend.className = 'fas fa-chevron-up';
                btnToggleLegend.title = 'Minimize / Perkecil Legenda';
            }
        }

        L.DomEvent.on(btnToggleLegend, 'click', toggleLegendBox);
        L.DomEvent.on(legendHeader, 'click', function(e) {
            if (e.target !== btnToggleLegend && !btnToggleLegend.contains(e.target)) {
                toggleLegendBox(e);
            }
        });
        L.DomEvent.disableClickPropagation(div);

        return div;
    };
    // Legenda hanya ditambahkan ke peta jika ada file GIS yang tersedia
    if (hasFiles) legend.addTo(map);

    // 6. MARKER SENTROID DIR (hanya dari file $lokasi — kini selalu kosong, data dari GeoJSON)
    const locations = {!! json_encode($lokasi) !!};
    var dirMarkers = {};

    locations.forEach(function (loc) {
        var markerBg = '#2980b9';
        if (loc.status.toLowerCase().indexOf('kubu raya') !== -1) markerBg = '#e74c3c';
        if (loc.status.toLowerCase().indexOf('ketapang') !== -1)  markerBg = '#27ae60';

        var customIcon = L.divIcon({
            className: 'custom-dir-pin-wrapper',
            html: '<div class="custom-dir-pin" style="background:' + markerBg + ';width:28px;height:28px;font-size:12px;">' +
                  '<i class="fas fa-' + (loc.ikon || 'tint') + '"></i></div>',
            iconSize: [28, 28],
            iconAnchor: [14, 14]
        });

        var marker = L.marker([loc.lat, loc.lng], { icon: customIcon }).addTo(map);
        marker.bindPopup(
            '<div style="min-width:230px;">' +
            '<div class="d-flex align-items-center mb-2">' +
            '<span class="badge badge-' + (loc.badge || 'primary') + ' mr-2">' + (loc.kode || 'DIR') + '</span>' +
            '<h6 class="font-weight-bold mb-0 text-primary">' + loc.nama + '</h6></div>' +
            '<p class="small text-muted mb-1"><i class="fas fa-map-pin mr-1"></i>' + loc.status + '</p>' +
            '<div class="small bg-light p-2 rounded mb-2 border"><b>Koordinat GIS:</b><br>' +
            'Latitude: <code>' + loc.lat + '</code><br>Longitude: <code>' + loc.lng + '</code></div>' +
            '<small class="text-secondary">' + (loc.keterangan || '-') + '</small></div>'
        );

        mapBounds.extend([loc.lat, loc.lng]);
        if (loc.kode) dirMarkers[loc.kode] = marker;
        dirMarkers[loc.nama] = marker;
    });

    // 7. REPROYEKSI KOORDINAT
    function projectCoordinate(x, y, wkid) {
        if (typeof x !== 'number' || typeof y !== 'number' || isNaN(x) || isNaN(y)) return [x, y];
        if (wkid === 4326 || (Math.abs(x) <= 180 && Math.abs(y) <= 90 && (!wkid || wkid === 'EPSG:4326'))) return [x, y];
        try {
            var wkidStr = String(wkid || '');
            if (wkidStr.indexOf('3857') !== -1 || wkid === 3857 || wkid === 102100 || wkid === 900913) {
                return proj4('EPSG:3857', 'EPSG:4326', [x, y]);
            }
            if (wkidStr.indexOf('54034') !== -1 || wkid === 54034) return proj4('EPSG:54034', 'EPSG:4326', [x, y]);
            if (wkidStr.indexOf('32749') !== -1 || wkid === 32749) return proj4('EPSG:32749', 'EPSG:4326', [x, y]);
            if (wkidStr.indexOf('32649') !== -1 || wkid === 32649) return proj4('EPSG:32649', 'EPSG:4326', [x, y]);
            if (wkid && proj4.defs['EPSG:' + wkid])                  return proj4('EPSG:' + wkid, 'EPSG:4326', [x, y]);
            if (wkid && proj4.defs[wkid])                            return proj4(wkid, 'EPSG:4326', [x, y]);

            // Auto deteksi koordinat mercator / utm
            if (Math.abs(x) > 2000000 || Math.abs(y) > 2000000) {
                return proj4('EPSG:3857', 'EPSG:4326', [x, y]);
            }
            if (y > 2000000) return proj4('EPSG:32749', 'EPSG:4326', [x, y]);
            if (x > 100000)  return proj4('EPSG:32649', 'EPSG:4326', [x, y]);
            return [x, y];
        } catch(e) { console.warn('Reproj err', x, y, e); return [x, y]; }
    }

    function transformGeoJsonCoords(coords, geomType, wkid) {
        if (!coords) return coords;
        if (geomType === 'Point') {
            return projectCoordinate(coords[0], coords[1], wkid);
        } else if (geomType === 'LineString' || geomType === 'MultiPoint') {
            return coords.map(function(c) { return projectCoordinate(c[0], c[1], wkid); });
        } else if (geomType === 'Polygon' || geomType === 'MultiLineString') {
            return coords.map(function(ring) {
                return ring.map(function(c) { return projectCoordinate(c[0], c[1], wkid); });
            });
        } else if (geomType === 'MultiPolygon') {
            return coords.map(function(poly) {
                return poly.map(function(ring) {
                    return ring.map(function(c) { return projectCoordinate(c[0], c[1], wkid); });
                });
            });
        }
        return coords;
    }

    // 8. KONVERSI ESRI & NORMALISASI GEOJSON KE WGS84
    function convertEsriToGeoJson(data) {
        if (!data) return null;

        var wkid = null;
        if (data.crs && data.crs.properties && data.crs.properties.name) {
            wkid = String(data.crs.properties.name);
        } else if (data.spatialReference) {
            wkid = data.spatialReference.wkid || data.spatialReference.latestWkid;
        }

        // Jika data adalah GeoJSON standar (FeatureCollection / Feature), transform koordinatnya ke WGS84
        if (data.type === 'FeatureCollection' && Array.isArray(data.features)) {
            data.features.forEach(function(f) {
                if (f.geometry && f.geometry.coordinates) {
                    f.geometry.coordinates = transformGeoJsonCoords(f.geometry.coordinates, f.geometry.type, wkid);
                }
            });
            return data;
        }
        if (data.type === 'Feature' && data.geometry && data.geometry.coordinates) {
            data.geometry.coordinates = transformGeoJsonCoords(data.geometry.coordinates, data.geometry.type, wkid);
            return data;
        }

        // Jika data adalah Esri JSON
        if (!data.features || !Array.isArray(data.features)) return null;

        var geomType = data.geometryType;
        var gj = { type: 'FeatureCollection', features: [] };

        data.features.forEach(function(f, idx) {
            if (!f.geometry) return;
            var geom = null;
            if (geomType === 'esriGeometryPoint' || (f.geometry.x !== undefined && f.geometry.y !== undefined)) {
                var pt = projectCoordinate(f.geometry.x, f.geometry.y, wkid);
                geom = { type: 'Point', coordinates: [pt[0], pt[1]] };
            } else if (geomType === 'esriGeometryPolyline' || f.geometry.paths) {
                var paths = f.geometry.paths || [];
                if (paths.length === 1) {
                    geom = { type: 'LineString', coordinates: paths[0].map(function(c){ return projectCoordinate(c[0],c[1],wkid); }) };
                } else if (paths.length > 1) {
                    geom = { type: 'MultiLineString', coordinates: paths.map(function(p){ return p.map(function(c){ return projectCoordinate(c[0],c[1],wkid); }); }) };
                }
            } else if (geomType === 'esriGeometryPolygon' || f.geometry.rings) {
                var rings = f.geometry.rings || [];
                if (rings.length > 0) {
                    geom = { type: 'Polygon', coordinates: rings.map(function(r){ return r.map(function(c){ return projectCoordinate(c[0],c[1],wkid); }); }) };
                }
            }
            if (geom) {
                gj.features.push({
                    type: 'Feature',
                    id: f.attributes ? (f.attributes.FID !== undefined ? f.attributes.FID : (f.attributes.OID_ !== undefined ? f.attributes.OID_ : idx)) : idx,
                    properties: f.attributes || {},
                    geometry: geom
                });
            }
        });
        return gj;
    }

    // 9. FETCH & RENDER GEOJSON FILES
    var geojsonFiles = {!! json_encode($geojsonFiles ?? []) !!};
    var loadingEl   = document.getElementById('map-loading');
    var infoFiturEl = document.getElementById('info-fitur-termuat');
    var customUploadLayers = {}; // Koleksi layer grup khusus untuk file yang di-upload
    var loadedCustomLayers = []; // Metadata layer upload untuk sinkronisasi ke legenda dinamis
    var customColorIndex = 0;

    var CUSTOM_COLOR_PALETTES = [
        { stroke: '#0284c7', fill: '#38bdf8', fillOpacity: 0.22, name: 'Biru Cerah' },     // Blue
        { stroke: '#059669', fill: '#34d399', fillOpacity: 0.22, name: 'Hijau Zamrud' },  // Emerald
        { stroke: '#7c3aed', fill: '#a78bfa', fillOpacity: 0.20, name: 'Ungu Violet' },   // Purple
        { stroke: '#d97706', fill: '#fbbf24', fillOpacity: 0.22, name: 'Kuning Amber' },  // Amber
        { stroke: '#e11d48', fill: '#fb7185', fillOpacity: 0.22, name: 'Merah Rose' },    // Rose
        { stroke: '#0d9488', fill: '#2dd4bf', fillOpacity: 0.22, name: 'Teal' },          // Teal
        { stroke: '#ea580c', fill: '#fb923c', fillOpacity: 0.22, name: 'Oranye Terang' }, // Orange
        { stroke: '#4f46e5', fill: '#818cf8', fillOpacity: 0.18, name: 'Biru Indigo' }    // Indigo
    ];

    // Data penampung baris tabel koordinat
    var fileTableRows = [];      // 1 baris per file GIS yang diunggah
    var objectTableRows = [];    // 1 baris per objek/fitur individu di dalam file
    var currentTabelMode = 'file'; // default: 'file' (Ringkasan per file)
    var currentSearchQuery = '';

    if (geojsonFiles.length > 0) {
        var totalFiles      = geojsonFiles.length;
        var loadedCount     = 0;
        var totalFiturCount = 0;

        geojsonFiles.forEach(function(fileName) {
            fetch('/geojson/' + fileName)
                .then(function(r) { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
                .then(function(rawData) {
                    var gj = convertEsriToGeoJson(rawData);
                    if (!gj || !gj.features || gj.features.length === 0) return;

                    var featCount = gj.features.length;
                    totalFiturCount += featCount;

                    var nameUpper   = fileName.toUpperCase();
                    var cleanName   = fileName.replace(/\.(json|geojson)$/i, '').replace(/_/g, ' ');
                    var targetLayer = null;
                    var isCustomUpload = false;
                    var strokeColor = '#27ae60';
                    var fillColor   = '#2ecc71';
                    var fillOpacity = 0.45;
                    var weight      = 1.8;
                    var catBadge    = '<span class="badge badge-secondary"><i class="fas fa-file-code mr-1"></i>GIS</span>';
                    var catName     = 'Data GIS';
                    var rowClass    = 'row-custom';

                    if (nameUpper.indexOf('BAKU') === 0) {
                        targetLayer = layerBaku; strokeColor = '#1e824c'; fillColor = '#27ae60'; fillOpacity = 0.45;
                        catBadge = '<span class="badge badge-success"><i class="fas fa-seedling mr-1"></i>DIR Baku</span>';
                        catName  = 'Daerah Irigasi Baku';
                        rowClass = 'row-baku';
                    } else if (nameUpper.indexOf('FUNGSIONAL') === 0) {
                        targetLayer = layerFungsional; strokeColor = '#1f618d'; fillColor = '#2980b9'; fillOpacity = 0.45;
                        catBadge = '<span class="badge badge-primary"><i class="fas fa-water mr-1"></i>DIR Fungsional</span>';
                        catName  = 'DIR Fungsional';
                        rowClass = 'row-fungsional';
                    } else if (nameUpper.indexOf('POTENSIAL') === 0) {
                        targetLayer = layerPotensial; strokeColor = '#b95c00'; fillColor = '#e67e22'; fillOpacity = 0.40;
                        catBadge = '<span class="badge badge-warning text-dark"><i class="fas fa-sun mr-1"></i>DIR Potensial</span>';
                        catName  = 'DIR Potensial';
                        rowClass = 'row-potensial';
                    } else if (nameUpper.indexOf('SALURAN') === 0) {
                        targetLayer = layerSaluran; strokeColor = '#0984e3'; weight = 2.2;
                        catBadge = '<span class="badge badge-info"><i class="fas fa-project-diagram mr-1"></i>Saluran</span>';
                        catName  = 'Jaringan Saluran';
                        rowClass = 'row-saluran';
                    } else if (nameUpper.indexOf('BANGUNAN') === 0) {
                        targetLayer = layerBangunan; strokeColor = '#c0392b';
                        catBadge = '<span class="badge badge-danger"><i class="fas fa-map-pin mr-1"></i>Bangunan</span>';
                        catName  = 'Bangunan Air';
                        rowClass = 'row-bangunan';
                    } else {
                        // File Kustom / Upload baru
                        isCustomUpload = true;

                        // Deteksi tipe geometri dominan di file ini (Poligon / Garis / Titik)
                        var polyCount = 0, lineCount = 0, pointCount = 0;
                        for (var fi = 0; fi < gj.features.length; fi++) {
                            var gt = (gj.features[fi].geometry ? gj.features[fi].geometry.type : '') || '';
                            if (gt === 'Polygon' || gt === 'MultiPolygon') polyCount++;
                            else if (gt === 'LineString' || gt === 'MultiLineString') lineCount++;
                            else if (gt === 'Point' || gt === 'MultiPoint') pointCount++;
                        }

                        var geomType = 'polygon';
                        var geomLabel = 'Area Poligon';
                        if (lineCount > polyCount && lineCount >= pointCount) {
                            geomType = 'line';
                            geomLabel = 'Jaringan Garis';
                        } else if (pointCount > polyCount && pointCount > lineCount) {
                            geomType = 'point';
                            geomLabel = 'Titik Lokasi';
                        }

                        var nameCleanUpper = fileName.replace(/\.(json|geojson)$/i, '').toUpperCase();
                        var isDasLayer = (/\bDAS\b/).test(nameCleanUpper) || nameCleanUpper.indexOf('DAS') !== -1;
                        var isJsLayer  = (/\bJS\b/).test(nameCleanUpper) || nameCleanUpper.indexOf('_JS') !== -1 || nameCleanUpper.indexOf('JS_') !== -1 || nameCleanUpper.indexOf('SUNGAI') !== -1 || nameCleanUpper.indexOf('RIVER') !== -1 || (geomType === 'line' && nameCleanUpper.indexOf('KAPUAS') !== -1);
                        var isWsLayer  = (/\bWS\b/).test(nameCleanUpper) || nameCleanUpper.indexOf('WILAYAH') !== -1;

                        // Pewarnaan tematik & semantik sesuai jenis data SDA / GIS
                        if (isDasLayer) {
                            strokeColor = '#059669'; fillColor = '#10b981'; weight = 2.0; fillOpacity = 0.22;
                            catName = 'Daerah Aliran Sungai (DAS)';
                            geomLabel = 'Batas DAS (Poligon)';
                            catBadge = '<span class="badge badge-success"><i class="fas fa-mountain mr-1"></i>DAS</span>';
                        } else if (isJsLayer) {
                            strokeColor = '#0284c7'; fillColor = '#0284c7'; weight = 2.4; fillOpacity = 0;
                            catName = 'Jaringan Sungai';
                            geomLabel = 'Jaringan Sungai (Garis)';
                            catBadge = '<span class="badge badge-info"><i class="fas fa-water mr-1"></i>Sungai</span>';
                        } else if (isWsLayer) {
                            strokeColor = '#4f46e5'; fillColor = '#818cf8'; weight = 2.2; fillOpacity = 0.16;
                            catName = 'Wilayah Sungai (WS)';
                            geomLabel = 'Wilayah Sungai (Poligon)';
                            catBadge = '<span class="badge badge-primary"><i class="fas fa-draw-polygon mr-1"></i>WS</span>';
                        } else {
                            var pal = CUSTOM_COLOR_PALETTES[customColorIndex % CUSTOM_COLOR_PALETTES.length];
                            customColorIndex++;
                            strokeColor = pal.stroke; fillColor = pal.fill;
                            fillOpacity = (geomType === 'polygon') ? pal.fillOpacity : 0;
                            weight = (geomType === 'line') ? 2.5 : 2.0;
                            catName = geomLabel;
                            catBadge = '<span class="badge badge-secondary"><i class="fas fa-layer-group mr-1"></i>Upload</span>';
                        }

                        rowClass = 'row-uploaded';

                        if (!customUploadLayers[fileName]) {
                            customUploadLayers[fileName] = L.layerGroup().addTo(map);

                            var swatchIcon = '';
                            if (geomType === 'polygon') {
                                swatchIcon = '<span style="display:inline-block;width:12px;height:10px;background:' + fillColor + ';border:1.5px solid ' + strokeColor + ';border-radius:2px;margin-right:6px;vertical-align:middle;opacity:0.85;"></span>';
                            } else if (geomType === 'line') {
                                swatchIcon = '<span style="display:inline-block;width:14px;height:3px;background:' + strokeColor + ';border-radius:2px;margin-right:6px;vertical-align:middle;"></span>';
                            } else {
                                swatchIcon = '<span style="display:inline-block;width:9px;height:9px;background:' + strokeColor + ';border:1.5px solid #fff;box-shadow:0 0 0 1px #888;border-radius:50%;margin-right:6px;vertical-align:middle;"></span>';
                            }
                            layerControl.addOverlay(customUploadLayers[fileName], swatchIcon + '<strong>' + cleanName + '</strong>');
                        }
                        targetLayer = customUploadLayers[fileName];

                        loadedCustomLayers.push({
                            fileName: fileName,
                            cleanName: cleanName,
                            geomType: geomType,
                            geomLabel: geomLabel,
                            strokeColor: strokeColor,
                            fillColor: fillColor,
                            featCount: featCount,
                            layerGroup: customUploadLayers[fileName]
                        });
                    }

                    var group = L.geoJSON(gj, {
                        style: function(feature) {
                            var lc = strokeColor, lw = weight, fo = fillOpacity;
                            if (feature.properties && (feature.properties.Jenis || feature.properties.JENIS)) {
                                var j = String(feature.properties.Jenis || feature.properties.JENIS).toLowerCase();
                                if      (j.indexOf('primer')   !== -1) { lc = '#e74c3c'; lw = 2.8; }
                                else if (j.indexOf('sekunder') !== -1) { lc = '#0984e3'; lw = 2.2; }
                                else if (j.indexOf('sub')      !== -1) { lc = '#00b894'; lw = 1.8; }
                            }
                            return { color: lc, weight: lw, opacity: 0.9, fillColor: fillColor, fillOpacity: fo };
                        },
                        pointToLayer: function(feature, latlng) {
                            if (isCustomUpload) {
                                return L.circleMarker(latlng, { radius: 6, fillColor: strokeColor, color: '#ffffff', weight: 2, opacity: 1, fillOpacity: 0.95 });
                            }
                            return L.circleMarker(latlng, { radius: 3.5, fillColor: '#e74c3c', color: '#ffffff', weight: 1, opacity: 1, fillOpacity: 0.95 });
                        },
                        onEachFeature: function(feature, layer) {
                            if (feature.properties) {
                                var props = feature.properties;
                                var title = props.NAMOBJ || props.Name || props.Nomenklatu || props.NomenKlatu || props.D_I_R || props.D_I || cleanName;

                                // Tooltip titik
                                if (feature.geometry && feature.geometry.type === 'Point') {
                                    layer.bindTooltip('<b>' + title + '</b>' + (props.REMARK ? ' <span class="badge badge-light border">' + props.REMARK + '</span>' : ''), {
                                        direction: 'top',
                                        offset: [0, -7]
                                    });
                                }

                                var objLat = null;
                                var objLng = null;
                                if (layer.getLatLng) {
                                    var ll = layer.getLatLng();
                                    objLat = ll.lat; objLng = ll.lng;
                                } else if (feature.geometry && feature.geometry.type === 'Point' && feature.geometry.coordinates) {
                                    objLng = feature.geometry.coordinates[0]; objLat = feature.geometry.coordinates[1];
                                } else if (layer.getBounds && layer.getBounds().isValid()) {
                                    var c = layer.getBounds().getCenter();
                                    objLat = c.lat; objLng = c.lng;
                                }

                                var latText = (objLat !== null) ? objLat.toFixed(6) : null;
                                var lngText = (objLng !== null) ? objLng.toFixed(6) : null;

                                var content = '<div style="max-width:300px;max-height:280px;overflow-y:auto;">' +
                                    '<h6 class="font-weight-bold text-primary mb-1 border-bottom pb-1">' +
                                    '<i class="fas fa-map-marker-alt text-danger mr-1"></i> ' + title + '</h6>';

                                if (latText && lngText) {
                                    content += '<div class="small bg-light p-1 px-2 rounded mb-2 border text-muted">' +
                                        '<b>Titik Koordinat:</b> Lat <code>' + latText + '</code>, Lng <code>' + lngText + '</code></div>';
                                }

                                content += '<table class="table table-sm table-striped gis-popup-table mb-1"><tbody>';
                                for (var key in props) {
                                    if (props[key] !== null && props[key] !== undefined && String(props[key]).trim() !== '' && key !== 'FID' && key !== 'OID_') {
                                        content += '<tr><th class="text-muted" style="width:42%;font-size:11px;">' + key + '</th><td style="font-size:11px;"><b>' + props[key] + '</b></td></tr>';
                                    }
                                }
                                content += '</tbody></table>' +
                                    '<div class="text-right"><small class="text-secondary" style="font-size:10px;"><i class="fas fa-file-code mr-1"></i>Sumber: ' + fileName + '</small></div>' +
                                    '</div>';

                                layer.bindPopup(content);

                                // Simpan ke objectTableRows jika koordinat valid
                                if (objLat !== null && objLng !== null) {
                                    objectTableRows.push({
                                        nama: title,
                                        kategoriBadge: catBadge,
                                        wilayah: (props.KABUPATEN || props.KECAMATAN || props.Kabupaten || props.Kecamatan || catName),
                                        lat: latText,
                                        lng: lngText,
                                        latNum: objLat,
                                        lngNum: objLng,
                                        sumber: fileName,
                                        layer: layer
                                    });
                                }
                            }
                        }
                    });

                    if (targetLayer) {
                        group.addTo(targetLayer);
                    } else {
                        group.addTo(map);
                    }

                    if (group.getBounds().isValid()) {
                        mapBounds.extend(group.getBounds());
                    }

                    var centerCoords = null;
                    if (group.getBounds().isValid()) {
                        var ctr = group.getBounds().getCenter();
                        centerCoords = {
                            lat: ctr.lat.toFixed(6),
                            lng: ctr.lng.toFixed(6),
                            latNum: ctr.lat,
                            lngNum: ctr.lng
                        };
                    }

                    fileTableRows.push({
                        nama: cleanName,
                        kategoriBadge: catBadge,
                        wilayah: catName,
                        lat: centerCoords ? centerCoords.lat : '-',
                        lng: centerCoords ? centerCoords.lng : '-',
                        sumber: fileName,
                        fiturCount: featCount,
                        bounds: group.getBounds().isValid() ? group.getBounds() : null
                    });

                    loadedCount++;
                    if (loadedCount === totalFiles) {
                        onAllFilesLoaded();
                    }
                })
                .catch(function(err) {
                    console.error('Gagal memuat file GIS ' + fileName + ':', err);
                    loadedCount++;
                    if (loadedCount === totalFiles) {
                        onAllFilesLoaded();
                    }
                });
        });

        // Update Legenda Peta secara dinamis untuk file yang di-upload
        function updateCustomLayersLegend() {
            var container = document.getElementById('legend-custom-section');
            var itemsBox = document.getElementById('legend-custom-items');
            if (!container || !itemsBox) return;

            if (loadedCustomLayers.length === 0) {
                container.style.display = 'none';
                return;
            }

            container.style.display = 'block';
            var html = '';
            loadedCustomLayers.forEach(function(item, idx) {
                var swatch = '';
                if (item.geomType === 'polygon') {
                    swatch = '<span class="legend-color-box" style="background:' + item.fillColor + ';border-color:' + item.strokeColor + ';opacity:0.85;"></span>';
                } else if (item.geomType === 'line') {
                    swatch = '<span class="legend-line" style="background:' + item.strokeColor + ';height:3px;"></span>';
                } else {
                    swatch = '<span class="legend-circle" style="background:' + item.strokeColor + ';border-color:#fff;"></span>';
                }

                var safeId = 'legend-item-custom-' + idx;
                html += '<div class="legend-item" id="' + safeId + '" style="margin-bottom:6px;align-items:flex-start;transition:opacity 0.2s;">' +
                    swatch +
                    '<div style="line-height:1.25;margin-left:2px;">' +
                        '<div class="font-weight-bold text-dark" style="font-size:11.5px;">' + item.cleanName + '</div>' +
                        '<div class="text-muted" style="font-size:10px;">' + item.geomLabel + ' (' + item.featCount + ' fitur)</div>' +
                    '</div>' +
                '</div>';
            });
            itemsBox.innerHTML = html;
        }

        // Sinkronisasi status layer checkbox di control layers ke legenda peta
        map.on('overlayadd', function(e) {
            for (var i = 0; i < loadedCustomLayers.length; i++) {
                if (loadedCustomLayers[i].layerGroup === e.layer) {
                    var el = document.getElementById('legend-item-custom-' + i);
                    if (el) el.style.opacity = '1';
                    break;
                }
            }
        });
        map.on('overlayremove', function(e) {
            for (var i = 0; i < loadedCustomLayers.length; i++) {
                if (loadedCustomLayers[i].layerGroup === e.layer) {
                    var el = document.getElementById('legend-item-custom-' + i);
                    if (el) el.style.opacity = '0.35';
                    break;
                }
            }
        });

        function onAllFilesLoaded() {
            if (loadingEl) loadingEl.style.display = 'none';
            if (infoFiturEl) {
                infoFiturEl.innerHTML = '<i class="fas fa-check-circle text-success mr-1"></i> ' +
                    totalFiturCount + ' fitur dimuat (' + totalFiles + ' file)';
            }
            if (mapBounds.isValid()) {
                map.fitBounds(mapBounds, { padding: [30, 30] });
            }
            var countObjekEl = document.getElementById('count-mode-objek');
            if (countObjekEl) countObjekEl.textContent = objectTableRows.length;
            updateCustomLayersLegend();
            renderTabel();
        }
    } else {
        if (loadingEl) loadingEl.style.display = 'none';
        if (infoFiturEl) {
            infoFiturEl.innerHTML = '<i class="fas fa-info-circle text-muted mr-1"></i> Belum ada file GeoJSON';
        }
        if (mapBounds.isValid()) {
            map.fitBounds(mapBounds, { padding: [30, 30] });
        }
    }

    // FUNGSI NAVIGASI & TABEL KOORDINAT
    window.setTabelMode = function(mode) {
        currentTabelMode = mode;
        var btnFile = document.getElementById('btn-mode-file');
        var btnObjek = document.getElementById('btn-mode-objek');
        if (btnFile && btnObjek) {
            if (mode === 'file') {
                btnFile.classList.add('btn-primary', 'active');
                btnFile.classList.remove('btn-outline-primary');
                btnObjek.classList.remove('btn-primary', 'active');
                btnObjek.classList.add('btn-outline-primary');
            } else {
                btnObjek.classList.add('btn-primary', 'active');
                btnObjek.classList.remove('btn-outline-primary');
                btnFile.classList.remove('btn-primary', 'active');
                btnFile.classList.add('btn-outline-primary');
            }
        }
        renderTabel();
    };

    window.filterTabelKoordinat = function(val) {
        currentSearchQuery = (val || '').toLowerCase().trim();
        renderTabel();
    };

    window.zoomToFeature = function(lat, lng) {
        map.setView([lat, lng], 16);
        for (var i = 0; i < objectTableRows.length; i++) {
            if (Math.abs(objectTableRows[i].latNum - lat) < 0.00001 && Math.abs(objectTableRows[i].lngNum - lng) < 0.00001) {
                if (objectTableRows[i].layer && objectTableRows[i].layer.openPopup) {
                    objectTableRows[i].layer.openPopup();
                }
                break;
            }
        }
        var mapEl = document.getElementById('map');
        if (mapEl) {
            mapEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    };

    window.zoomToGroup = function(idx) {
        var row = fileTableRows[idx];
        if (row && row.bounds) {
            map.fitBounds(row.bounds, { padding: [40, 40] });
            var mapEl = document.getElementById('map');
            if (mapEl) {
                mapEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    };

    function renderTabel() {
        var tbody = document.getElementById('tbody-lokasi');
        var footerInfo = document.getElementById('tabel-footer-info');
        if (!tbody) return;

        var items = (currentTabelMode === 'file') ? fileTableRows : objectTableRows;
        var filtered = items.filter(function(item) {
            if (!currentSearchQuery) return true;
            var text = (item.nama + ' ' + (item.wilayah || '') + ' ' + (item.sumber || '')).toLowerCase();
            return text.indexOf(currentSearchQuery) !== -1;
        });

        if (filtered.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-muted">' +
                '<i class="fas fa-search mr-2"></i>Tidak ada data yang cocok dengan filter.</td></tr>';
            if (footerInfo) footerInfo.textContent = 'Menampilkan 0 dari ' + items.length + ' data';
            return;
        }

        var html = '';
        filtered.forEach(function(item, idx) {
            if (currentTabelMode === 'file') {
                html += '<tr>' +
                    '<td class="text-center align-middle small font-weight-bold">' + (idx + 1) + '</td>' +
                    '<td class="align-middle font-weight-bold text-dark">' +
                        '<i class="fas fa-file-alt text-primary mr-2"></i>' + item.nama +
                        ' <span class="badge badge-light border text-muted ml-1">' + item.fiturCount + ' fitur</span>' +
                    '</td>' +
                    '<td class="align-middle">' + item.kategoriBadge + '</td>' +
                    '<td class="text-center align-middle font-monospace small"><code>' + item.lat + '</code></td>' +
                    '<td class="text-center align-middle font-monospace small"><code>' + item.lng + '</code></td>' +
                    '<td class="align-middle small text-muted"><i class="fas fa-paperclip mr-1"></i>' + item.sumber + '</td>' +
                    '<td class="text-center align-middle">' +
                        (item.bounds ? '<button type="button" class="btn btn-xs btn-outline-primary btn-sm py-1 px-2" onclick="zoomToGroup(' + fileTableRows.indexOf(item) + ')" title="Fokuskan layer file ini di peta"><i class="fas fa-search-plus mr-1"></i>Lihat</button>' : '-') +
                    '</td>' +
                '</tr>';
            } else {
                html += '<tr>' +
                    '<td class="text-center align-middle small font-weight-bold">' + (idx + 1) + '</td>' +
                    '<td class="align-middle font-weight-bold text-gray-800">' +
                        '<i class="fas fa-map-pin text-danger mr-2"></i>' + item.nama +
                    '</td>' +
                    '<td class="align-middle">' + item.kategoriBadge + ' <span class="small text-muted ml-1">' + (item.wilayah || '') + '</span></td>' +
                    '<td class="text-center align-middle font-monospace small"><code>' + item.lat + '</code></td>' +
                    '<td class="text-center align-middle font-monospace small"><code>' + item.lng + '</code></td>' +
                    '<td class="align-middle small text-muted"><i class="fas fa-file-code mr-1"></i>' + item.sumber + '</td>' +
                    '<td class="text-center align-middle">' +
                        '<button type="button" class="btn btn-xs btn-outline-info btn-sm py-1 px-2" onclick="zoomToFeature(' + item.latNum + ', ' + item.lngNum + ')" title="Perbesar ke titik ini"><i class="fas fa-crosshairs mr-1"></i>Zoom</button>' +
                    '</td>' +
                '</tr>';
            }
        });

        tbody.innerHTML = html;
        if (footerInfo) {
            footerInfo.textContent = 'Menampilkan ' + filtered.length + ' dari ' + items.length +
                (currentTabelMode === 'file' ? ' file GIS' : ' objek fitur');
        }
    }
});
</script>
@endpush
@extends('admin.layout')

@section('title', 'Manajemen Data GIS — WebGIS BWS Kalimantan I')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Admin</a>
    <span class="mx-2 text-muted">/</span>
    <span class="text-dark font-weight-bold">Manajemen Data GIS</span>
@endsection

@section('content')
<div class="anim-fade-in">

    <!-- Flash Alerts -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius:10px;border-left:4px solid #10b981;">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle mr-2 text-success fa-lg"></i>
            <div>
                <strong>Berhasil!</strong> {{ session('success') }}
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius:10px;border-left:4px solid #ef4444;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle mr-2 text-danger fa-lg"></i>
            <div>
                <strong>Gagal!</strong> {{ session('error') }}
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius:10px;">
        <div class="font-weight-bold mb-1"><i class="fas fa-exclamation-circle mr-1"></i>Terdapat kesalahan input:</div>
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

    <!-- Header Title & Breadcrumb Persis Referensi FEWS BWS Kalimantan I -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="font-weight-bold mb-0" style="letter-spacing: 0.5px; font-size: 1.05rem; color: #495057;">
            MASTER DATA GIS
        </h4>
        <span class="text-muted small" style="font-weight: 500;">Menu Utama</span>
    </div>

    @php
        $catLabels = [
            'baku'       => ['DIR Baku', '#10b981', 'fa-leaf'],
            'fungsional' => ['DIR Fungsional', '#2563eb', 'fa-water'],
            'potensial'  => ['DIR Potensial', '#f59e0b', 'fa-seedling'],
            'saluran'    => ['Jaringan Saluran', '#ec4899', 'fa-stream'],
            'bangunan'   => ['Bangunan Air', '#8b5cf6', 'fa-landmark'],
            'ws_kapuas'  => ['WS & DAS Kapuas', '#06b6d4', 'fa-globe-asia'],
            'custom'     => ['Layer Kustom', '#64748b', 'fa-file-alt'],
        ];
    @endphp

    <!-- Card Tabel Data Bersih ala Referensi FEWS BWS -->
    <div class="card border-0 shadow-sm" style="border-radius:12px;overflow:hidden;background:#fff;">
        <div class="card-body p-4">

            <!-- Card Header: Judul & Tombol Aksi -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 pb-2 border-bottom" style="gap: 12px;">
                <h5 class="font-weight-bold text-dark mb-0" style="font-size: 1.15rem;">
                    Daftar File Geospasial
                </h5>
                <div class="d-flex align-items-center" style="gap:10px;">
                    <a href="{{ route('peta') }}" target="_blank" class="btn btn-outline-primary font-weight-bold px-3 py-2 shadow-sm d-inline-flex align-items-center" style="border-radius:25px;font-size:0.85rem;height:38px;">
                        <i class="fas fa-map-marked-alt mr-2"></i>Buka Peta Publik
                    </a>
                    <button type="button" class="btn text-white font-weight-bold px-4 py-2 shadow-sm d-inline-flex align-items-center justify-content-center" style="background:#2563eb;border-radius:25px;font-size:0.88rem;height:38px;transition:all 0.2s ease;" data-toggle="modal" data-target="#modalUpload">
                        <i class="fas fa-cloud-upload-alt mr-2"></i>Upload File Baru
                    </button>
                </div>
            </div>

            <!-- Filter Bar: Entri & Pencarian -->
            <form method="GET" action="{{ route('admin.kelola-gis') }}" id="filterForm">
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-3" style="gap:14px;">
                    
                    <!-- Left: Tampilkan X entri & Bulk Delete -->
                    <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                        <div class="d-flex align-items-center text-muted small">
                            <span class="mr-2">Tampilkan</span>
                            <select name="per_page" class="form-control form-control-sm" style="width:75px;border-radius:6px;" onchange="document.getElementById('filterForm').submit()">
                                <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <span class="ml-2">entri</span>
                        </div>

                        <!-- Dropdown Filter Kategori -->
                        <select name="type" class="form-control form-control-sm" style="width:160px;border-radius:6px;" onchange="document.getElementById('filterForm').submit()">
                            <option value="semua" {{ ($filterType ?? 'semua') === 'semua' ? 'selected' : '' }}>Semua Kategori</option>
                            <option value="baku" {{ ($filterType ?? '') === 'baku' ? 'selected' : '' }}>DIR Baku</option>
                            <option value="fungsional" {{ ($filterType ?? '') === 'fungsional' ? 'selected' : '' }}>DIR Fungsional</option>
                            <option value="potensial" {{ ($filterType ?? '') === 'potensial' ? 'selected' : '' }}>DIR Potensial</option>
                            <option value="saluran" {{ ($filterType ?? '') === 'saluran' ? 'selected' : '' }}>Jaringan Saluran</option>
                            <option value="bangunan" {{ ($filterType ?? '') === 'bangunan' ? 'selected' : '' }}>Bangunan Air</option>
                        </select>

                        <!-- Tombol Hapus Masal -->
                        <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold" id="btnBulkDelete" disabled onclick="submitBulkDelete()" style="border-radius:6px;display:none;">
                            <i class="fas fa-trash-alt mr-1"></i>Hapus Terpilih (<span id="bulkCount">0</span>)
                        </button>
                    </div>

                    <!-- Right: Search Input -->
                    <div class="d-flex align-items-center" style="gap:8px;">
                        <span class="text-muted small">Cari:</span>
                        <div class="input-group input-group-sm" style="width:230px;">
                            <input type="text" name="search" class="form-control" placeholder="Ketik nama file..." value="{{ $search ?? '' }}" style="border-radius:6px 0 0 6px;">
                            <div class="input-group-append">
                                <button class="btn btn-primary px-3" type="submit" style="border-radius:0 6px 6px 0;background:#2563eb;">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        @if($search || ($filterType && $filterType !== 'semua'))
                            <a href="{{ route('admin.kelola-gis') }}" class="btn btn-sm btn-light border text-muted" title="Reset filter" style="border-radius:6px;">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>

                </div>
            </form>

            <!-- Tabel Data -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:0.87rem;" id="tableGisData">
                    <thead style="background:#f8fafc;border-top:1px solid #e2e8f0;border-bottom:2px solid #e2e8f0;">
                        <tr class="text-muted font-weight-bold" style="font-size:0.8rem;letter-spacing:0.3px;">
                            <th width="40" class="text-center">
                                <input type="checkbox" id="checkAll" onchange="toggleAllChecks(this)">
                            </th>
                            <th width="50" class="text-center">No.</th>
                            <th>Nama File</th>
                            <th width="190">Kategori</th>
                            <th width="120">Ukuran</th>
                            <th width="170">Login/Upload Terakhir</th>
                            <th width="120" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fileList as $index => $file)
                            @php
                                $catInfo = $catLabels[$file['category']] ?? ['Lainnya', '#64748b', 'fa-file'];
                            @endphp
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td class="text-center align-middle">
                                    <input type="checkbox" class="file-check" value="{{ $file['name'] }}" onchange="updateBulkCount()">
                                </td>
                                <td class="text-center text-muted align-middle font-weight-bold">
                                    {{ ($offset ?? 0) + $index + 1 }}
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3 rounded d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#eff6ff;color:#2563eb;">
                                            <i class="fas fa-file-code"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark" style="max-width:320px;word-break:break-all;">
                                                {{ $file['name'] }}
                                            </div>
                                            <small class="text-muted">Format: .{{ $file['extension'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <span class="badge px-2 py-1 font-weight-bold" style="background:{{ $catInfo[1] }}18;color:{{ $catInfo[1] }};border-radius:6px;font-size:0.75rem;">
                                        <i class="fas {{ $catInfo[2] }} mr-1"></i>{{ $catInfo[0] }}
                                    </span>
                                </td>
                                <td class="align-middle font-weight-bold text-secondary">
                                    {{ $file['size'] }}
                                </td>
                                <td class="align-middle text-muted" style="font-size:0.8rem;">
                                    {{ $file['date'] }}
                                </td>
                                <td class="text-center align-middle">
                                    <div class="d-flex align-items-center justify-content-center" style="gap:6px;">
                                        <!-- Tombol Edit (Warna Hijau Sesuai Referensi) -->
                                        <button type="button" class="btn btn-sm btn-success px-2 py-1 shadow-sm" style="background:#10b981;border-color:#10b981;border-radius:6px;" title="Edit / Rename File" onclick="openRenameModal('{{ $file['name'] }}')">
                                            <i class="fas fa-pencil-alt fa-xs"></i>
                                        </button>
                                        <!-- Tombol Hapus (Warna Merah Sesuai Referensi) -->
                                        <button type="button" class="btn btn-sm btn-danger px-2 py-1 shadow-sm" style="background:#ef4444;border-color:#ef4444;border-radius:6px;" title="Hapus File" onclick="confirmDelete('{{ $file['name'] }}')">
                                            <i class="fas fa-trash-alt fa-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-folder-open fa-3x text-muted mb-3 d-block" style="opacity:0.4;"></i>
                                        <h6 class="font-weight-bold text-muted mb-1">Belum ada file GeoJSON yang cocok</h6>
                                        <p class="text-muted small mb-3">Klik tombol "Upload File GeoJSON" di atas untuk menambahkan data spasial baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Bagian Bawah: Info & Navigasi Halaman ala Referensi -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-4 pt-3 border-top" style="gap:12px;">
                <div class="text-muted small">
                    Menampilkan <strong>{{ count($fileList) }}</strong> sampai <strong>{{ min(($offset ?? 0) + ($perPage ?? 10), $totalItems ?? 0) }}</strong> dari <strong>{{ $totalItems ?? 0 }}</strong> entri
                </div>

                @if(($totalPages ?? 1) > 1)
                <div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous --}}
                            <li class="page-item {{ ($currentPage ?? 1) <= 1 ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => max(1, ($currentPage ?? 1) - 1)]) }}" aria-label="Previous">
                                    <span aria-hidden="true">&lsaquo;</span>
                                </a>
                            </li>

                            {{-- Numbers --}}
                            @for($p = 1; $p <= $totalPages; $p++)
                                <li class="page-item {{ ($currentPage ?? 1) == $p ? 'active' : '' }}">
                                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $p]) }}" style="{{ ($currentPage ?? 1) == $p ? 'background:#2563eb;border-color:#2563eb;' : '' }}">{{ $p }}</a>
                                </li>
                            @endfor

                            {{-- Next --}}
                            <li class="page-item {{ ($currentPage ?? 1) >= $totalPages ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => min($totalPages, ($currentPage ?? 1) + 1)]) }}" aria-label="Next">
                                    <span aria-hidden="true">&rsaquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                @endif
            </div>

        </div>
    </div>

</div>

<!-- ==========================================
     MODAL UPLOAD FILE GEOJSON
     ========================================== -->
<div class="modal fade" id="modalUpload" tabindex="-1" role="dialog" aria-labelledby="modalUploadLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:520px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
            <div class="modal-header text-white py-3 px-4" style="background: linear-gradient(135deg, #1d64d8 0%, #1e40af 100%);">
                <div class="d-flex align-items-center">
                    <div class="mr-3 d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:rgba(255,255,255,0.2);border-radius:8px;">
                        <i class="fas fa-file-archive fa-lg text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold text-white mb-0" id="modalUploadLabel" style="font-size:1.05rem;">
                            Upload File Spasial (ZIP / GeoJSON)
                        </h5>
                        <small class="text-white-50">Mendukung file Shapefile ZIP dari ArcGIS & GeoJSON</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="outline:none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form id="formUploadKelolaGis" action="{{ route('admin.upload') }}" method="POST" enctype="multipart/form-data" onsubmit="handleKelolaGisUpload(this, event)">
                @csrf
                <div class="modal-body p-4 bg-light">
                    <div class="alert alert-info py-2 px-3 small mb-3 border-0 shadow-sm" style="background:#eff6ff;border-left:4px solid #3b82f6 !important;color:#1e40af;border-radius:8px;">
                        <i class="fas fa-check-circle mr-1 text-primary"></i> <strong>Langsung Unggah File ZIP Shapefile!</strong><br>
                        Anda dapat langsung mengunggah file <code>.zip</code> paket Shapefile ArcGIS (berisi <code>.shp</code>, <code>.dbf</code>, <code>.shx</code>, <code>.prj</code>) tanpa perlu konversi manual. Sistem akan otomatis mengekstrak & memprosesnya.
                    </div>

                    <div class="bg-white p-3 rounded border mb-3" style="border-radius:10px !important;">
                        <label class="font-weight-bold mb-2 d-block text-dark small">
                            <i class="fas fa-folder-open text-primary mr-1"></i>Pilih File ZIP atau GeoJSON
                        </label>
                        <div class="custom-file">
                            <input type="file" name="file_geojson[]" class="custom-file-input" id="inputUploadGeojson" multiple accept=".zip,.geojson,.json" required onchange="handleFileChange(this)">
                            <label class="custom-file-label text-truncate" for="inputUploadGeojson" id="labelUploadGeojson">Pilih satu atau beberapa file...</label>
                        </div>
                        <small class="form-text text-muted mt-2">
                            <i class="fas fa-info-circle text-primary mr-1"></i>Dapat mengunggah banyak file sekaligus (maksimal 256 MB per file).
                        </small>
                        <div id="kelolaGisUploadStatus" class="small font-weight-bold text-primary mt-2" style="display:none;"></div>
                    </div>

                    <div class="alert alert-warning py-2 px-3 small mb-0 rounded" style="background:#fffbeb;border:1px solid #fde68a;color:#92400e;">
                        <i class="fas fa-lightbulb mr-1 text-warning"></i>
                        <strong>Tips Penamaan:</strong> Beri awalan nama seperti <code>baku_</code>, <code>fungsional_</code>, <code>potensial_</code>, <code>saluran_</code>, atau <code>bangunan_</code> agar otomatis terkelompok pada layer peta.
                    </div>
                </div>

                <div class="modal-footer bg-white border-top py-3 px-4">
                    <button type="button" class="btn btn-light border font-weight-bold px-3 btn-sm" data-dismiss="modal" style="border-radius:6px;">Batal</button>
                    <button type="submit" id="btnKelolaGisSubmit" class="btn btn-primary font-weight-bold px-4 btn-sm shadow-sm" style="background:#2563eb;border-color:#2563eb;border-radius:6px;">
                        <i class="fas fa-cloud-upload-alt mr-1"></i>Mulai Unggah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==========================================
     MODAL EDIT / RENAME FILE
     ========================================== -->
<div class="modal fade" id="modalRename" tabindex="-1" role="dialog" aria-labelledby="modalRenameLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:480px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
            <div class="modal-header border-0 py-3 px-4" style="background:#f0fdf4;">
                <h5 class="modal-title font-weight-bold" id="modalRenameLabel" style="font-size:1rem;color:#166534;">
                    <i class="fas fa-pencil-alt mr-2 text-success"></i>Edit Nama File Spasial
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.rename') }}" method="POST">
                @csrf
                <input type="hidden" name="old_name" id="renameOldName">
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label class="font-weight-bold small text-muted mb-1">Nama File Saat Ini:</label>
                        <div class="p-2 bg-light border rounded small font-weight-bold text-dark" id="renameOldNameDisplay" style="word-break:break-all;"></div>
                    </div>
                    <div class="mb-2">
                        <label class="font-weight-bold small text-dark mb-1">Nama File Baru:</label>
                        <input type="text" name="new_name" id="renameNewName" class="form-control form-control-sm" required placeholder="Masukkan nama file baru...">
                        <small class="text-muted mt-1 d-block" style="font-size:0.75rem;">
                            Ekstensi <code>.geojson</code> akan otomatis disertakan jika tidak ditulis.
                        </small>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top py-2 px-4">
                    <button type="button" class="btn btn-light border font-weight-bold btn-sm px-3" data-dismiss="modal" style="border-radius:6px;">Batal</button>
                    <button type="submit" class="btn btn-success font-weight-bold btn-sm px-4 shadow-sm" style="background:#10b981;border-color:#10b981;border-radius:6px;">
                        <i class="fas fa-check mr-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==========================================
     MODAL KONFIRMASI HAPUS
     ========================================== -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
            <div class="modal-body p-4 text-center">
                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-3" style="width:54px;height:54px;background:#fee2e2;color:#ef4444;">
                    <i class="fas fa-trash-alt fa-lg"></i>
                </div>
                <h6 class="font-weight-bold text-dark mb-1">Hapus File Geospasial?</h6>
                <p class="text-muted small mb-0" id="deleteFileName" style="word-break:break-all;"></p>
                <small class="text-danger d-block mt-2 font-weight-bold">Tindakan ini tidak dapat dibatalkan!</small>
            </div>
            <div class="modal-footer border-0 bg-light justify-content-center py-3" style="gap:8px;">
                <button type="button" class="btn btn-light border font-weight-bold btn-sm px-3" data-dismiss="modal" style="border-radius:6px;">Batal</button>
                <form id="formDelete" action="{{ route('admin.delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="file_name" id="deleteFileInput">
                    <button type="submit" class="btn btn-danger font-weight-bold btn-sm px-4" style="background:#ef4444;border-color:#ef4444;border-radius:6px;">
                        <i class="fas fa-trash-alt mr-1"></i>Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Form Tersembunyi untuk Bulk Delete -->
<form id="formBulkDelete" action="{{ route('admin.delete-bulk') }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
    <div id="bulkDeleteInputs"></div>
</form>

@endsection

@push('scripts')
<script>
    function handleFileChange(input) {
        var label = document.getElementById('labelUploadGeojson');
        if (input.files.length === 1) {
            label.textContent = input.files[0].name;
        } else if (input.files.length > 1) {
            label.textContent = input.files.length + ' file dipilih';
        } else {
            label.textContent = 'Pilih satu atau beberapa file...';
        }
    }

    function openRenameModal(fileName) {
        document.getElementById('renameOldName').value = fileName;
        document.getElementById('renameOldNameDisplay').textContent = fileName;
        document.getElementById('renameNewName').value = fileName;
        $('#modalRename').modal('show');
    }

    function confirmDelete(fileName) {
        document.getElementById('deleteFileName').textContent = fileName;
        document.getElementById('deleteFileInput').value = fileName;
        $('#modalDelete').modal('show');
    }

    function toggleAllChecks(master) {
        document.querySelectorAll('.file-check').forEach(function(cb) {
            cb.checked = master.checked;
        });
        updateBulkCount();
    }

    function updateBulkCount() {
        var checked = document.querySelectorAll('.file-check:checked');
        var count = checked.length;
        var btn = document.getElementById('btnBulkDelete');
        var countSpan = document.getElementById('bulkCount');
        
        countSpan.textContent = count;
        if (count > 0) {
            btn.style.display = 'inline-flex';
            btn.disabled = false;
        } else {
            btn.style.display = 'none';
            btn.disabled = true;
        }
    }

    function submitBulkDelete() {
        var checked = document.querySelectorAll('.file-check:checked');
        if (checked.length === 0) return;
        if (!confirm('Yakin ingin menghapus ' + checked.length + ' file geospasial terpilih?')) return;

        var container = document.getElementById('bulkDeleteInputs');
        container.innerHTML = '';
        checked.forEach(function(cb) {
            var inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'file_names[]';
            inp.value = cb.value;
            container.appendChild(inp);
        });
        document.getElementById('formBulkDelete').submit();
    }

    // Handle Upload ZIP Shapefile & GeoJSON via shpjs
    async function handleKelolaGisUpload(form, event) {
        event.preventDefault();
        var fileInput = document.getElementById('inputUploadGeojson');
        var files = fileInput.files;
        if (!files || files.length === 0) {
            alert('Silakan pilih file terlebih dahulu.');
            return false;
        }

        var submitBtn = document.getElementById('btnKelolaGisSubmit');
        var statusText = document.getElementById('kelolaGisUploadStatus');
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

        statusText.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan ke server...';

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
            if (response.ok && resData && resData.success) {
                alert('✅ Berhasil!\n\n' + resData.message);
                window.location.reload();
            } else if (resData && !resData.success) {
                alert('❌ Gagal Mengunggah:\n\n' + (resData.message || 'File tidak dapat diproses oleh server.'));
                submitBtn.disabled = false;
                statusText.style.display = 'none';
            } else {
                var errDetail = response.status === 413 ? 'Ukuran file terlalu besar (melebihi batas server).' : 'Format file di dalam ZIP tidak sesuai atau struktur tidak valid.';
                alert('❌ Gagal Mengunggah (HTTP ' + response.status + '):\n\n' + errDetail + '\n\nCatatan: Arsip ZIP harus berisi file berekstensi .geojson atau .json.');
                submitBtn.disabled = false;
                statusText.style.display = 'none';
            }
        } catch (e) {
            alert('❌ Terjadi kesalahan saat proses upload:\n\n' + (e.message || 'Koneksi terputus atau proses konversi memori melebihi batas.'));
            submitBtn.disabled = false;
            statusText.style.display = 'none';
        }
    }
</script>
@endpush

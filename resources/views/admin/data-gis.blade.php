@extends('admin.layout')

@section('title', 'Manajemen Data GIS — WebGIS BWS Kalimantan I')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Admin</a> / <span>Data GIS</span>
@endsection

@section('content')
<div class="anim-fade-in">

    <!-- Flash Alerts -->
    @if(session('success'))
    <div class="alert admin-alert alert-success shadow-sm mb-4" role="alert">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert admin-alert alert-danger shadow-sm mb-4" role="alert">
        <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    @endif
    @if(isset($errors) && $errors->any())
    <div class="alert admin-alert alert-danger shadow-sm mb-4" role="alert">
        <strong><i class="fas fa-exclamation-circle mr-1"></i>Kesalahan:</strong>
        <ul class="mb-0 mt-1 pl-3">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
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
            'baku'       => ['DIR Baku', '#22c55e', 'fa-leaf'],
            'fungsional' => ['DIR Fungsional', '#3b82f6', 'fa-water'],
            'potensial'  => ['DIR Potensial', '#f97316', 'fa-seedling'],
            'saluran'    => ['Jaringan Saluran', '#ec4899', 'fa-stream'],
            'bangunan'   => ['Bangunan Air', '#8b5cf6', 'fa-landmark'],
        ];
    @endphp

    <!-- Data Table Card -->
    <div class="data-card">
        <div class="data-card-header">
            <h5><i class="fas fa-table mr-2 text-primary"></i>Daftar File Spasial</h5>

            <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                <!-- Search -->
                <form method="GET" action="{{ route('admin.data-gis') }}" class="d-flex align-items-center" style="gap:10px;">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" class="search-input" placeholder="Cari nama file..."
                               value="{{ $search ?? '' }}">
                    </div>
                    <select name="type" class="filter-select" onchange="this.form.submit()">
                        <option value="semua" {{ ($filterType ?? 'semua') === 'semua' ? 'selected' : '' }}>Semua Kategori</option>
                        <optgroup label="🌾 Irigasi">
                            <option value="irigasi" {{ ($filterType ?? '') === 'irigasi' ? 'selected' : '' }}>Semua Irigasi</option>
                            <option value="baku" {{ ($filterType ?? '') === 'baku' ? 'selected' : '' }}>DIR Baku</option>
                            <option value="fungsional" {{ ($filterType ?? '') === 'fungsional' ? 'selected' : '' }}>DIR Fungsional</option>
                            <option value="potensial" {{ ($filterType ?? '') === 'potensial' ? 'selected' : '' }}>DIR Potensial</option>
                        </optgroup>
                        <optgroup label="🏗️ Infrastruktur">
                            <option value="infrastruktur" {{ ($filterType ?? '') === 'infrastruktur' ? 'selected' : '' }}>Semua Infrastruktur</option>
                            <option value="saluran" {{ ($filterType ?? '') === 'saluran' ? 'selected' : '' }}>Jaringan Saluran</option>
                            <option value="bangunan" {{ ($filterType ?? '') === 'bangunan' ? 'selected' : '' }}>Bangunan Air</option>
                        </optgroup>
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
                        <i class="fas fa-filter"></i>
                    </button>
                    @if($search || ($filterType && $filterType !== 'semua'))
                        <a href="{{ route('admin.data-gis') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;" title="Reset filter">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>

                <!-- Bulk Delete -->
                <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold" style="border-radius:8px;" id="btnBulkDelete" disabled onclick="submitBulkDelete()">
                    <i class="fas fa-trash-alt mr-1"></i>Hapus Terpilih (<span id="bulkCount">0</span>)
                </button>
            </div>
        </div>

        <!-- Table -->
        <div style="overflow-x:auto;">
            <table class="admin-table" id="gisTable">
                <thead>
                    <tr>
                        <th style="width:40px;text-align:center;">
                            <input type="checkbox" id="checkAll" onchange="toggleAllChecks(this)">
                        </th>
                        <th style="width:40px;">No.</th>
                        <th>Nama File</th>
                        <th>Kategori</th>
                        <th>Ukuran</th>
                        <th>Tanggal Modifikasi</th>
                        <th style="width:140px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fileList as $index => $file)
                    <tr>
                        <td style="text-align:center;">
                            <input type="checkbox" class="file-check" value="{{ $file['name'] }}" onchange="updateBulkCount()">
                        </td>
                        <td class="text-muted" style="font-weight:600;">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-3 d-flex align-items-center justify-content-center" style="width:36px;height:36px;border-radius:8px;background:#f1f5f9;">
                                    <i class="fas fa-file-code" style="color:#3b82f6;font-size:0.9rem;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold text-dark" style="font-size:0.82rem;max-width:300px;" title="{{ $file['name'] }}">
                                        {{ Str::limit($file['name'], 45) }}
                                    </div>
                                    <div class="text-muted" style="font-size:0.7rem;">.{{ $file['extension'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $allCatLabels = [
                                    'baku'       => ['DIR Baku', '#22c55e', 'fa-leaf'],
                                    'fungsional' => ['DIR Fungsional', '#3b82f6', 'fa-water'],
                                    'potensial'  => ['DIR Potensial', '#f97316', 'fa-seedling'],
                                    'saluran'    => ['Jaringan Saluran', '#ec4899', 'fa-stream'],
                                    'bangunan'   => ['Bangunan Air', '#8b5cf6', 'fa-landmark'],
                                    'ws_kapuas'  => ['WS & DAS Kapuas', '#14b8a6', 'fa-globe-asia'],
                                    'custom'     => ['Layer Kustom', '#64748b', 'fa-file-alt'],
                                ];
                                $catInfo = $allCatLabels[$file['category']] ?? ['Lainnya', '#64748b', 'fa-file'];
                            @endphp
                            <span class="cat-badge cat-{{ $file['category'] }}">
                                <i class="fas {{ $catInfo[2] }}"></i>{{ $catInfo[0] }}
                            </span>
                        </td>
                        <td style="font-weight:500;">{{ $file['size'] }}</td>
                        <td class="text-muted">{{ $file['date'] }}</td>
                        <td style="text-align:center;">
                            <div class="d-flex align-items-center justify-content-center" style="gap:6px;">
                                <!-- Edit/Rename -->
                                <button type="button" class="btn-action btn-action-info" title="Ubah Nama"
                                        onclick="openRenameModal('{{ $file['name'] }}')">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <!-- Delete -->
                                <button type="button" class="btn-action btn-action-danger" title="Hapus File"
                                        onclick="confirmDelete('{{ $file['name'] }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div style="opacity:0.5;">
                                <i class="fas fa-database fa-3x mb-3 d-block text-muted"></i>
                                <p class="mb-1 font-weight-bold text-muted">Tidak ada file ditemukan</p>
                                <p class="mb-0 small text-muted">
                                    @if($search || ($filterType && $filterType !== 'semua'))
                                        Coba ubah filter pencarian Anda.
                                    @else
                                        Klik tombol "Upload File Baru" untuk menambahkan data GIS.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(count($fileList) > 0)
        <div class="px-4 py-3 border-top d-flex align-items-center justify-content-between" style="background:#fafbfc;">
            <span class="text-muted" style="font-size:0.78rem;">
                Menampilkan <strong>{{ count($fileList) }}</strong> dari <strong>{{ $stats['total_files'] }}</strong> file
            </span>
        </div>
        @endif
    </div>

</div>

<!-- ═══ MODAL UPLOAD ═══ -->
<div class="modal fade" id="modalUpload" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width:650px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
            <div class="modal-header text-white border-0 py-3 px-4" style="background:linear-gradient(135deg,#0f172a 0%,#1e3a8a 100%);">
                <div class="d-flex align-items-center">
                    <div class="mr-3 rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;background:rgba(255,255,255,0.15);">
                        <i class="fas fa-cloud-upload-alt fa-lg text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-white" style="font-size:1.05rem;">Upload Data GIS</h5>
                        <small class="text-white-50" style="font-size:0.75rem;">Format: .geojson atau .json</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4" style="background:#fafbfc;">
                <form id="formUpload" action="{{ route('admin.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white p-3 rounded border mb-3" style="border-radius:10px !important;">
                        <label class="font-weight-bold mb-2 d-block" style="font-size:0.85rem;">
                            <i class="fas fa-file-code text-primary mr-1"></i>Pilih File GeoJSON / JSON
                        </label>
                        <div class="custom-file">
                            <input type="file" name="file_geojson[]" class="custom-file-input" id="inputUploadFile"
                                   multiple accept=".geojson,.json" required onchange="updateUploadLabel(this)">
                            <label class="custom-file-label" for="inputUploadFile" id="labelUploadFile">
                                Pilih satu atau beberapa file...
                            </label>
                        </div>
                        <small class="form-text text-muted mt-2">
                            <i class="fas fa-info-circle text-primary mr-1"></i>Anda dapat memilih banyak file sekaligus. Max 256 MB per file.
                        </small>
                    </div>

                    <div class="d-flex justify-content-end" style="gap:8px;">
                        <button type="button" class="btn btn-light border font-weight-bold px-3" data-dismiss="modal" style="border-radius:8px;">
                            <i class="fas fa-times mr-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary font-weight-bold px-4 shadow-sm" style="border-radius:8px;">
                            <i class="fas fa-upload mr-1"></i>Upload Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ═══ MODAL RENAME ═══ -->
<div class="modal fade" id="modalRename" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:500px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
            <div class="modal-header border-0 py-3 px-4" style="background:#eff6ff;">
                <h5 class="modal-title font-weight-bold" style="font-size:0.95rem;color:#1e40af;">
                    <i class="fas fa-pen-alt mr-2"></i>Ubah Nama File
                </h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <form id="formRename" action="{{ route('admin.rename') }}" method="POST">
                    @csrf
                    <input type="hidden" name="old_name" id="renameOldName">
                    <div class="mb-3">
                        <label class="font-weight-bold mb-2 d-block" style="font-size:0.82rem;">Nama File Lama</label>
                        <div class="text-muted px-3 py-2 bg-light border rounded" id="renameOldNameDisplay" style="font-size:0.82rem;border-radius:8px !important;word-break:break-all;"></div>
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-bold mb-2 d-block" style="font-size:0.82rem;">Nama File Baru</label>
                        <input type="text" name="new_name" id="renameNewName" class="form-control" required
                               placeholder="Ketik nama file baru..." style="border-radius:8px;font-size:0.85rem;">
                        <small class="text-muted mt-1 d-block">Gunakan huruf, angka, underscore, atau strip. Ekstensi .geojson akan otomatis ditambahkan.</small>
                    </div>
                    <div class="d-flex justify-content-end" style="gap:8px;">
                        <button type="button" class="btn btn-light border font-weight-bold px-3" data-dismiss="modal" style="border-radius:8px;">Batal</button>
                        <button type="submit" class="btn btn-primary font-weight-bold px-4" style="border-radius:8px;">
                            <i class="fas fa-save mr-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ═══ MODAL CONFIRM DELETE ═══ -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
            <div class="modal-header border-0 py-3 px-4" style="background:#fef2f2;">
                <h5 class="modal-title font-weight-bold" style="font-size:0.95rem;color:#dc2626;">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Hapus File
                </h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="fas fa-trash-alt fa-2x text-danger mb-3"></i>
                <p class="mb-1 font-weight-bold" style="font-size:0.9rem;">Yakin hapus file ini?</p>
                <p class="text-muted small mb-0" id="deleteFileName" style="word-break:break-all;"></p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4" style="gap:8px;">
                <button type="button" class="btn btn-light border font-weight-bold px-4" data-dismiss="modal" style="border-radius:8px;">Batal</button>
                <form id="formDelete" action="{{ route('admin.delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="file_name" id="deleteFileInput">
                    <button type="submit" class="btn btn-danger font-weight-bold px-4" style="border-radius:8px;">
                        <i class="fas fa-trash-alt mr-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ═══ FORM BULK DELETE (HIDDEN) ═══ -->
<form id="formBulkDelete" action="{{ route('admin.delete-bulk') }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
    <div id="bulkDeleteInputs"></div>
</form>

@endsection

@push('scripts')
<script>
    // Upload label update
    function updateUploadLabel(input) {
        var label = document.getElementById('labelUploadFile');
        if (input.files.length === 1) {
            label.textContent = input.files[0].name;
        } else if (input.files.length > 1) {
            label.textContent = input.files.length + ' file dipilih';
        } else {
            label.textContent = 'Pilih satu atau beberapa file...';
        }
    }

    // Rename modal
    function openRenameModal(fileName) {
        document.getElementById('renameOldName').value = fileName;
        document.getElementById('renameOldNameDisplay').textContent = fileName;
        document.getElementById('renameNewName').value = fileName;
        $('#modalRename').modal('show');
    }

    // Delete confirm
    function confirmDelete(fileName) {
        document.getElementById('deleteFileName').textContent = fileName;
        document.getElementById('deleteFileInput').value = fileName;
        $('#modalDelete').modal('show');
    }

    // Bulk select
    function toggleAllChecks(master) {
        document.querySelectorAll('.file-check').forEach(function(cb) {
            cb.checked = master.checked;
        });
        updateBulkCount();
    }

    function updateBulkCount() {
        var checked = document.querySelectorAll('.file-check:checked');
        document.getElementById('bulkCount').textContent = checked.length;
        document.getElementById('btnBulkDelete').disabled = (checked.length === 0);
    }

    function submitBulkDelete() {
        var checked = document.querySelectorAll('.file-check:checked');
        if (checked.length === 0) return;
        if (!confirm('Yakin hapus ' + checked.length + ' file terpilih? Tindakan ini tidak bisa dibatalkan.')) return;

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
</script>
@endpush

@extends('admin.layout')

@section('title', 'Master Data GIS — FEWS BWS Kalimantan I')

@section('content')
<div class="anim-fade-in">

    <!-- Flash Alert Sukses / Error -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius:10px;border-left:4px solid #10b981;">
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
    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius:10px;border-left:4px solid #ef4444;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle mr-2 text-danger fa-lg"></i>
            <div><strong>Gagal!</strong> {{ session('error') }}</div>
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

    <!-- ═══ HEADER HALAMAN (PERSIS REFERENSI VISUAL BWS) ═══ -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div>
            <div class="text-uppercase text-muted font-weight-bold" style="font-size:0.75rem; letter-spacing:1px;">
                Manajemen Data GIS
            </div>
            <h4 class="font-weight-bold text-gray-800 mb-0">
                Daftar File GeoJSON / Spasial
            </h4>
        </div>
        <div class="mt-3 mt-md-0 d-flex align-items-center" style="gap:10px;">
            <button type="button" class="btn btn-primary btn-sm font-weight-bold px-4 shadow-sm" data-toggle="modal" data-target="#modalUploadGeojson" style="background:#2563eb;border-color:#2563eb;border-radius:20px;height:38px;display:inline-flex;align-items:center;">
                <i class="fas fa-plus mr-2"></i>Upload File GeoJSON Baru
            </button>
        </div>
    </div>

    <!-- ═══ KARTU TABEL DATA UTAMA (PERSIS REFERENSI VISUAL BWS) ═══ -->
    <div class="card border-0 shadow-sm" style="border-radius:12px;overflow:hidden;background:#fff;">
        <div class="card-body p-4">

            <!-- Filter Bar: Entri & Pencarian -->
            <form method="GET" action="{{ route('admin.dashboard') }}" id="filterForm">
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-3" style="gap:14px;">
                    
                    <!-- Left: Tampilkan X entri & Filter Kategori -->
                    <div class="d-flex align-items-center flex-wrap" style="gap:12px;">
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

                        <!-- Dropdown Kategori Master Data GIS -->
                        <select name="type" class="form-control form-control-sm" style="width:180px;border-radius:6px;" onchange="document.getElementById('filterForm').submit()">
                            <option value="semua" {{ ($filterType ?? 'semua') === 'semua' ? 'selected' : '' }}>Semua Master Data</option>
                            <option value="irigasi" {{ ($filterType ?? '') === 'irigasi' ? 'selected' : '' }}>Daerah Irigasi (DIR)</option>
                            <option value="saluran" {{ ($filterType ?? '') === 'saluran' ? 'selected' : '' }}>Jaringan Saluran</option>
                            <option value="bangunan" {{ ($filterType ?? '') === 'bangunan' ? 'selected' : '' }}>Bangunan Air</option>
                            <option value="ws_kapuas" {{ ($filterType ?? '') === 'ws_kapuas' ? 'selected' : '' }}>WS &amp; DAS Kapuas</option>
                        </select>

                        <!-- Tombol Hapus Masal -->
                        <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold" id="btnBulkDelete" disabled onclick="submitBulkDelete()" style="border-radius:6px;display:none;">
                            <i class="fas fa-trash-alt mr-1"></i>Hapus Terpilih (<span id="bulkCount">0</span>)
                        </button>
                    </div>

                    <!-- Right: Search Input (Cari:) Sesuai Gambar Referensi -->
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
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-light border text-muted" title="Reset filter" style="border-radius:6px;">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>

                </div>
            </form>

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

            <!-- Tabel Data Spasial -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:0.87rem;" id="tableGisData">
                    <thead style="background:#f8fafc;border-top:1px solid #e2e8f0;border-bottom:2px solid #e2e8f0;">
                        <tr class="text-muted font-weight-bold" style="font-size:0.8rem;letter-spacing:0.3px;">
                            <th width="40" class="text-center">
                                <input type="checkbox" id="checkAll" onchange="toggleAllChecks(this)">
                            </th>
                            <th width="50" class="text-center">No.</th>
                            <th>Nama File</th>
                            <th width="190">Kategori Spasial</th>
                            <th width="120">Ukuran</th>
                            <th width="180">Login / Upload Terakhir</th>
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
                                        <!-- Tombol Edit (Warna Hijau Sesuai Referensi Gambar BWS) -->
                                        <button type="button" class="btn btn-sm btn-success px-2 py-1 shadow-sm" style="background:#10b981;border-color:#10b981;border-radius:6px;" title="Edit / Ubah Nama File" onclick="openRenameModal('{{ $file['name'] }}')">
                                            <i class="fas fa-pencil-alt fa-xs"></i>
                                        </button>
                                        <!-- Tombol Hapus (Warna Merah Sesuai Referensi Gambar BWS) -->
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
                                        <p class="text-muted small mb-3">Klik tombol "Upload File GeoJSON Baru" di atas untuk menambahkan data spasial.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ═══ PAGINATION & INFORMASI ENTRI (PERSIS REFERENSI BWS) ═══ -->
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

<!-- ═══ MODAL UPLOAD FILE GEOJSON ═══ -->
<div class="modal fade" id="modalUploadGeojson" tabindex="-1" role="dialog" aria-labelledby="modalUploadGeojsonLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:580px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
            <div class="modal-header text-white px-4 py-3 border-0" style="background:linear-gradient(135deg, #0d3875 0%, #174ea6 100%);">
                <div class="d-flex align-items-center">
                    <div class="mr-3 rounded-circle d-flex align-items-center justify-content-center" style="width:38px;height:38px;background:rgba(255,255,255,0.18);">
                        <i class="fas fa-cloud-upload-alt text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold text-white mb-0" id="modalUploadGeojsonLabel" style="font-size:1.05rem;">
                            Upload File GeoJSON Baru
                        </h5>
                        <small class="text-white-50">Tambahkan data spasial Daerah Irigasi &amp; WS Kapuas ke peta</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="outline:none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="{{ route('admin.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 bg-light">
                    <div class="bg-white p-3 rounded border mb-3" style="border-radius:10px !important;">
                        <label class="font-weight-bold mb-2 d-block text-dark small">
                            <i class="fas fa-file-code text-primary mr-1"></i>Pilih File GeoJSON (.geojson / .json)
                        </label>
                        <div class="custom-file">
                            <input type="file" name="file_geojson[]" class="custom-file-input" id="inputUploadGeojson" multiple accept=".geojson,.json" required onchange="handleFileChange(this)">
                            <label class="custom-file-label" for="inputUploadGeojson" id="labelUploadGeojson">Pilih satu atau beberapa file...</label>
                        </div>
                        <small class="form-text text-muted mt-2">
                            <i class="fas fa-info-circle text-primary mr-1"></i>Mendukung upload banyak file sekaligus. Maksimum 256 MB per file.
                        </small>
                    </div>

                    <div class="alert alert-info py-2 px-3 small mb-0 rounded" style="background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;">
                        <i class="fas fa-lightbulb mr-1 text-warning"></i>
                        <strong>Tips Penamaan:</strong> Beri awalan seperti <code>baku_</code>, <code>fungsional_</code>, <code>potensial_</code>, <code>saluran_</code>, atau <code>bangunan_</code> agar terkelompok otomatis di layer peta.
                    </div>
                </div>

                <div class="modal-footer bg-white border-top py-3 px-4">
                    <button type="button" class="btn btn-light border font-weight-bold px-3 btn-sm" data-dismiss="modal" style="border-radius:6px;">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4 btn-sm shadow-sm" style="background:#2563eb;border-color:#2563eb;border-radius:6px;">
                        <i class="fas fa-cloud-upload-alt mr-1"></i>Upload Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══ MODAL EDIT / RENAME FILE ═══ -->
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
                            Ekstensi <code>.geojson</code> akan otomatis ditambahkan jika tidak ditulis.
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

<!-- ═══ MODAL KONFIRMASI HAPUS ═══ -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
            <div class="modal-body p-4 text-center">
                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-3" style="width:54px;height:54px;background:#fee2e2;color:#ef4444;">
                    <i class="fas fa-trash-alt fa-lg"></i>
                </div>
                <h6 class="font-weight-bold text-dark mb-1">Hapus File Spasial?</h6>
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
</script>
@endpush

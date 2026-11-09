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
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 pb-2 border-bottom" style="gap: 12px;">
                <div>
                    <h5 class="font-weight-bold text-dark mb-1" style="font-size: 1.15rem;">
                        Daftar File Geospasial
                    </h5>
                    <p class="text-muted small mb-0">
                        {{ ($currentTab ?? 'active') === 'trash' ? 'File geospasial yang sedang berada di kotak sampah sementara (dapat dipulihkan kapan saja).' : 'Kelola file spasial aktif yang saat ini tampil di peta interaktif WebGIS BWS Kalimantan I.' }}
                    </p>
                </div>

                <div class="d-flex align-items-center" style="gap:10px;">
                    @if(($currentTab ?? 'active') === 'trash')
                        @if(($totalTrash ?? 0) > 0 && Auth::check() && Auth::user()->isSuperAdmin())
                        <form action="{{ route('admin.gis.empty-trash') }}" method="POST" onsubmit="return confirm('Peringatan: Kosongkan seluruh kotak sampah? Semua file di dalam kotak sampah akan dihapus permanen selamanya.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger font-weight-bold px-3 py-2 shadow-sm d-inline-flex align-items-center" style="border-radius:25px;font-size:0.84rem;height:38px;">
                                <i class="fas fa-trash mr-2"></i>Kosongkan Kotak Sampah
                            </button>
                        </form>
                        @endif
                    @else
                        <button type="button" class="btn text-white font-weight-bold px-4 py-2 shadow-sm d-inline-flex align-items-center justify-content-center" style="background:#2563eb;border-radius:25px;font-size:0.88rem;height:38px;transition:all 0.2s ease;" data-toggle="modal" data-target="#modalUpload">
                            <i class="fas fa-cloud-upload-alt mr-2"></i>Upload File Baru
                        </button>
                    @endif
                </div>
            </div>

            <!-- Tab Navigasi: File Aktif vs Kotak Sampah -->
            <div class="d-flex align-items-center mb-3 pb-2 border-bottom" style="gap: 8px;">
                <a href="{{ route('admin.kelola-gis', ['tab' => 'active']) }}" 
                   class="btn btn-sm {{ ($currentTab ?? 'active') === 'active' ? 'btn-primary text-white font-weight-bold shadow-sm' : 'btn-light text-muted' }}" 
                   style="border-radius: 20px; font-size: 0.84rem; padding: 5px 16px;">
                    <i class="fas fa-folder-open mr-1"></i> File Aktif ({{ $totalActive ?? 0 }})
                </a>
                <a href="{{ route('admin.kelola-gis', ['tab' => 'trash']) }}" 
                   class="btn btn-sm {{ ($currentTab ?? '') === 'trash' ? 'btn-danger text-white font-weight-bold shadow-sm' : 'btn-light text-muted' }}" 
                   style="border-radius: 20px; font-size: 0.84rem; padding: 5px 16px;">
                    <i class="fas fa-trash-alt mr-1"></i> Kotak Sampah ({{ $totalTrash ?? 0 }})
                </a>
            </div>

            <!-- Filter Bar: Entri & Pencarian -->
            <form method="GET" action="{{ route('admin.kelola-gis') }}" id="filterForm">
                <input type="hidden" name="tab" value="{{ $currentTab ?? 'active' }}">
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

                        @if(($currentTab ?? 'active') === 'active')
                        <!-- Dropdown Filter Kategori -->
                        <select name="type" class="form-control form-control-sm" style="width:175px;border-radius:6px;" onchange="document.getElementById('filterForm').submit()">
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

                        <!-- Tombol Hapus Masal (Khusus Super Admin) -->
                        @if(Auth::check() && Auth::user()->isSuperAdmin())
                        <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold" id="btnBulkDelete" disabled onclick="submitBulkDelete()" style="border-radius:6px;display:none;">
                            <i class="fas fa-trash-alt mr-1"></i>Hapus Terpilih (<span id="bulkCount">0</span>)
                        </button>
                        @endif
                        @endif
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
                            <a href="{{ route('admin.kelola-gis', ['tab' => $currentTab ?? 'active']) }}" class="btn btn-sm btn-light border text-muted" title="Reset filter" style="border-radius:6px;">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>

                </div>
            </form>

            @if(($currentTab ?? 'active') === 'trash')
            <!-- ═══ TABEL KOTAK SAMPAH (TRASH) ═══ -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:0.87rem;">
                    <thead style="background:#fef2f2;border-top:1px solid #fecaca;border-bottom:2px solid #fca5a5;">
                        <tr class="text-danger font-weight-bold" style="font-size:0.8rem;letter-spacing:0.3px;">
                            <th width="50" class="text-center">No.</th>
                            <th>Nama File Terhapus</th>
                            <th width="120">Ukuran</th>
                            <th width="170">Waktu Dihapus</th>
                            <th width="150">Dihapus Oleh</th>
                            <th width="150" class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fileList as $index => $file)
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td class="text-center text-muted align-middle font-weight-bold">
                                    {{ ($offset ?? 0) + $index + 1 }}
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3 rounded d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#fee2e2;color:#dc2626;">
                                            <i class="fas fa-file-archive"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark" style="max-width:340px;word-break:break-all;">
                                                {{ $file['name'] }}
                                            </div>
                                            <small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>Status: Di Kotak Sampah</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle font-weight-bold text-secondary">
                                    {{ $file['size'] }}
                                </td>
                                <td class="align-middle text-muted" style="font-size:0.82rem;">
                                    <i class="far fa-clock mr-1 text-danger"></i> {{ $file['deleted_at'] }}
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-light border text-muted font-weight-bold px-2 py-1" style="font-size:0.75rem;">
                                        {{ $file['deleted_by'] }}
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="d-flex align-items-center justify-content-center" style="gap:6px;">
                                        <!-- Tombol Pulihkan (Restore) -->
                                        <form action="{{ route('admin.gis.restore') }}" method="POST" class="d-inline" onsubmit="return confirm('Pulihkan file \'{{ $file['name'] }}\' kembali aktif ke peta WebGIS?');">
                                            @csrf
                                            <input type="hidden" name="file_name" value="{{ $file['name'] }}">
                                            <button type="submit" class="btn btn-sm shadow-sm d-inline-flex align-items-center" style="border-radius:6px; background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; font-size:0.78rem; font-weight:700; padding:4px 10px;" title="Pulihkan File Kembali Aktif">
                                                <i class="fas fa-undo mr-1"></i> Pulihkan
                                            </button>
                                        </form>

                                        <!-- Tombol Hapus Permanen (Khusus Super Admin) -->
                                        @if(Auth::check() && Auth::user()->isSuperAdmin())
                                        <form action="{{ route('admin.gis.force-delete') }}" method="POST" class="d-inline" onsubmit="return confirm('Peringatan Keras: Hapus file \'{{ $file['name'] }}\' secara permanen selamanya dari server? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="file_name" value="{{ $file['name'] }}">
                                            <button type="submit" class="btn btn-sm shadow-sm d-inline-flex align-items-center" style="border-radius:6px; background:#fef2f2; color:#dc2626; border:1px solid #fecaca; font-size:0.78rem; font-weight:700; padding:4px 8px;" title="Hapus Permanen Selamanya">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-trash-restore fa-3x text-muted mb-3 d-block" style="opacity:0.35;"></i>
                                        <h6 class="font-weight-bold text-muted mb-1">Kotak Sampah Kosong</h6>
                                        <p class="text-muted small mb-0">Tidak ada file geospasial yang sedang berada di kotak sampah.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @else
            <!-- ═══ TABEL FILE SPASIAL AKTIF ═══ -->
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
                            <th width="170">Waktu Terdaftar</th>
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
                                    <div class="d-flex align-items-center justify-content-center" style="gap:8px;">
                                        <!-- Tombol Edit Ramping -->
                                        <button type="button" class="btn btn-sm shadow-sm d-inline-flex align-items-center justify-content-center" style="width:32px; height:32px; border-radius:8px; background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; transition:all 0.2s ease;" title="Edit / Rename File" onmouseover="this.style.background='#10b981'; this.style.color='#fff';" onmouseout="this.style.background='#ecfdf5'; this.style.color='#059669';" onclick="openRenameModal('{{ $file['name'] }}')">
                                            <i class="fas fa-pen fa-xs"></i>
                                        </button>
                                        <!-- Tombol Hapus Sementara ke Sampah -->
                                        <button type="button" class="btn btn-sm shadow-sm d-inline-flex align-items-center justify-content-center" style="width:32px; height:32px; border-radius:8px; background:#fef2f2; color:#dc2626; border:1px solid #fecaca; transition:all 0.2s ease;" title="Pindahkan ke Kotak Sampah" onmouseover="this.style.background='#ef4444'; this.style.color='#fff';" onmouseout="this.style.background='#fef2f2'; this.style.color='#dc2626';" onclick="confirmDelete('{{ $file['name'] }}')">
                                            <i class="fas fa-trash fa-xs"></i>
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
                                        <p class="text-muted small mb-3">Klik tombol "Upload File Baru" di atas untuk menambahkan data spasial baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif
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
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:880px; width:95%;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px; overflow:hidden;">
            <!-- Header Modal Resmi BWS Kalimantan I -->
            <div class="modal-header text-white py-3 px-4" style="background: linear-gradient(135deg, #093c52 0%, #0d5c70 45%, #1d4ed8 100%);">
                <div class="d-flex align-items-center">
                    <div class="mr-3 d-flex align-items-center justify-content-center shadow-sm" style="width:42px;height:42px;background:rgba(255,255,255,0.18);border-radius:12px;border:1.5px solid rgba(255,255,255,0.35);">
                        <i class="fas fa-cloud-upload-alt fa-lg text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold text-white mb-0" id="modalUploadLabel" style="font-size:1.15rem;letter-spacing:0.3px;">
                            Unggah Data Geospasial Irigasi
                        </h5>
                        <small style="color:rgba(255,255,255,0.88);font-size:0.78rem;">
                            Balai Wilayah Sungai Kalimantan I &bull; Direktorat Jenderal Sumber Daya Air
                        </small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="outline:none;opacity:0.85;">
                    <span aria-hidden="true" style="font-size:1.6rem;">&times;</span>
                </button>
            </div>
            
            <form id="formUploadKelolaGis" action="{{ route('admin.upload') }}" method="POST" enctype="multipart/form-data" onsubmit="handleKelolaGisUpload(this, event)">
                @csrf
                <div class="modal-body p-4 bg-light">
                    <div class="row" style="row-gap: 16px;">
                        
                        <!-- ═══ KOLOM KIRI (LEBAR 42%): PEMILIHAN FILE & KATEGORI ═══ -->
                        <div class="col-lg-5 col-md-5 d-flex flex-column justify-content-between">
                            
                            <!-- 1. Dropdown Kategori Lapisan Peta -->
                            <div class="form-group mb-3 bg-white p-3 rounded border shadow-sm" style="border-radius:12px !important;">
                                <label class="font-weight-bold text-dark mb-1 d-block" style="font-size:0.82rem;">
                                    <i class="fas fa-layer-group text-primary mr-1"></i> Kategori Lapisan Peta:
                                </label>
                                <select name="kategori" id="selectKategoriUpload" class="form-control" style="border-radius:8px;font-size:0.82rem;font-weight:600;border:1.5px solid #cbd5e1;height:40px;">
                                    <option value="auto" selected>🔄 Otomatis (Sesuai nama file)</option>
                                    <option value="baku">🌱 Lahan Baku (DIR Baku)</option>
                                    <option value="fungsional">💧 Lahan Fungsional (Aktif Teraliri)</option>
                                    <option value="potensial">📐 Lahan Potensial (Pengembangan)</option>
                                    <option value="saluran">🛤️ Jaringan Saluran Air</option>
                                    <option value="bangunan">🏛️ Bangunan Air &amp; Bendung</option>
                                    <option value="ws_kapuas">🌊 Wilayah Sungai Kapuas</option>
                                </select>
                                <small class="text-muted d-block mt-1" style="font-size:0.72rem;line-height:1.35;">
                                    Pilih kategori agar data otomatis masuk ke layer peta yang tepat.
                                </small>
                            </div>

                            <!-- 2. Area Unggah Drag & Drop -->
                            <div class="dropzone-box text-center p-3 rounded bg-white border shadow-sm d-flex flex-column justify-content-center align-items-center" id="dropzoneBox" onclick="document.getElementById('inputUploadGeojson').click()" style="border: 2px dashed #93c5fd !important; border-radius: 12px; cursor: pointer; transition: all 0.2s; min-height: 205px;">
                                <input type="file" name="file_geojson[]" id="inputUploadGeojson" multiple accept=".geojson,.json,.zip" required onchange="handleFileChange(this)" style="display:none;">
                                
                                <div id="dropzoneEmptyState">
                                    <div class="mb-2">
                                        <i class="fas fa-cloud-upload-alt text-primary" style="font-size: 2.4rem; opacity: 0.9;"></i>
                                    </div>
                                    <div class="font-weight-bold text-dark" style="font-size: 0.9rem;">
                                        Pilih File dari Komputer
                                    </div>
                                    <div class="text-muted small mt-1" style="font-size: 0.74rem;">
                                        atau seret &amp; lepas file ke kotak ini
                                    </div>
                                    <div class="mt-2 d-flex justify-content-center flex-wrap" style="gap:4px;">
                                        <span class="badge badge-light border text-primary font-weight-bold px-2 py-1" style="font-size:0.68rem;border-radius:6px;">.GEOJSON</span>
                                        <span class="badge badge-light border text-info font-weight-bold px-2 py-1" style="font-size:0.68rem;border-radius:6px;">.JSON</span>
                                        <span class="badge badge-light border text-secondary font-weight-bold px-2 py-1" style="font-size:0.68rem;border-radius:6px;">.ZIP</span>
                                    </div>
                                </div>

                                <!-- Pratinjau File Terpilih -->
                                <div id="dropzoneSelectedState" style="display:none; width: 100%;">
                                    <div class="mb-2">
                                        <i class="fas fa-check-circle text-success" style="font-size: 2rem;"></i>
                                    </div>
                                    <div class="font-weight-bold text-dark text-truncate px-2" id="selectedFileSummary" style="font-size: 0.88rem;">
                                        File Terpilih
                                    </div>
                                    <div id="selectedFileList" class="text-muted small mt-2 text-left bg-light p-2 rounded border" style="max-height:85px;overflow-y:auto;font-size:0.75rem;"></div>
                                    <button type="button" class="btn btn-sm btn-link text-danger mt-2 font-weight-bold" onclick="clearSelectedFiles(event)" style="font-size:0.75rem;">
                                        <i class="fas fa-times-circle mr-1"></i> Ganti File Lain
                                    </button>
                                </div>
                            </div>

                        </div>

                        <!-- ═══ KOLOM KANAN (LEBAR 58%): PEMILAH ISI ZIP ATAU INFORMASI PANDUAN ═══ -->
                        <div class="col-lg-7 col-md-7">
                            
                            <!-- Keadaan 1: Belum Ada ZIP yang Dipilih (Panduan Bersih) -->
                            <div id="zipPickerEmptyNotice" class="card border-0 shadow-sm h-100" style="border-radius:12px;background:#ffffff; min-height: 310px;">
                                <div class="card-body p-4 d-flex flex-column justify-content-center text-center text-muted">
                                    <div class="mb-3">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width:54px;height:54px;background:#eff6ff;color:#2563eb;font-size:24px;">
                                            <i class="fas fa-file-invoice"></i>
                                        </div>
                                    </div>
                                    <h6 class="font-weight-bold text-dark mb-1" style="font-size:0.95rem;">
                                        Dukungan Format Spasial Fleksibel
                                    </h6>
                                    <p class="small text-muted mb-3" style="font-size:0.78rem;line-height:1.5;">
                                        Anda dapat mengunggah <strong>1 file satuan (.geojson / .json)</strong> langsung tanpa kompresi, atau memilih <strong>file arsip paket (.zip)</strong>.
                                    </p>
                                    <div class="text-left bg-light p-3 rounded border" style="font-size:0.75rem;line-height:1.5;">
                                        <div class="d-flex align-items-start mb-2">
                                            <i class="fas fa-check text-success mr-2 mt-1"></i>
                                            <div><strong>Unggah 1 File:</strong> Cukup pilih file .geojson Anda, sistem langsung menyimpannya ke peta.</div>
                                        </div>
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-check text-success mr-2 mt-1"></i>
                                            <div><strong>Unggah Arsip ZIP:</strong> Sistem otomatis mendeteksi isi file di dalamnya sehingga Anda bisa memilih file tertentu saja.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Keadaan 2: File ZIP Dipilih (Panel Pemilah Checkbox Canggih) -->
                            <div id="zipPickerSection" class="card border-0 shadow-sm h-100 flex-column" style="display:none; border-radius:12px; overflow:hidden; min-height:310px;">
                                <div class="card-header bg-white py-2 px-3 border-bottom d-flex align-items-center justify-content-between flex-wrap" style="gap:6px;">
                                    <div>
                                        <strong class="text-primary" style="font-size:0.84rem;">
                                            <i class="fas fa-file-archive mr-1"></i> Pemilah File dalam ZIP
                                        </strong>
                                        <div class="text-muted small" id="zipPickerSummary" style="font-size:0.74rem;">
                                            Membaca isi berkas...
                                        </div>
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary btn-sm py-1 px-2 font-weight-bold" style="font-size:0.72rem;border-radius:6px 0 0 6px;" onclick="toggleAllZipEntries(true)">
                                            Pilih Semua
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm py-1 px-2 font-weight-bold" style="font-size:0.72rem;border-radius:0 6px 6px 0;" onclick="toggleAllZipEntries(false)">
                                            Kosongkan
                                        </button>
                                    </div>
                                </div>

                                <!-- Filter Pencarian Cepat -->
                                <div class="p-2 bg-white border-bottom">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0" style="font-size:0.75rem;"><i class="fas fa-search text-muted"></i></span>
                                        </div>
                                        <input type="text" id="searchZipEntry" class="form-control border-left-0" style="font-size:0.78rem;" placeholder="Cari nama file (contoh: Pemangkat, Kubu, Saluran)..." onkeyup="filterZipEntries(this.value)">
                                    </div>
                                </div>

                                <!-- List Box Checkbox dengan Layout Rapi & Checkbox Utuh (Tidak Terpotong) -->
                                <div class="p-2 bg-white" id="zipEntriesContainer" style="height: 195px; max-height: 195px; overflow-y: auto; font-size: 0.8rem;">
                                    <!-- Diisi secara dinamis oleh JavaScript -->
                                </div>

                                <div class="card-footer bg-light py-2 px-3 d-flex align-items-center justify-content-between border-top">
                                    <span class="small font-weight-bold text-dark" id="zipSelectedCounter" style="font-size:0.78rem;">
                                        0 file dipilih
                                    </span>
                                    <small class="text-muted" style="font-size:0.72rem;">
                                        Centang file yang ingin Anda masukkan ke peta
                                    </small>
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- Indikator Status Proses -->
                    <div id="kelolaGisUploadStatus" class="alert alert-info py-2 px-3 small font-weight-bold text-primary mt-3 mb-0" style="display:none;border-radius:8px;background:#eff6ff;border:1px solid #bfdbfe;">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Memproses unggahan ke server BWS Kalimantan I...
                    </div>

                </div>

                <div class="modal-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center">
                    <small class="text-muted" style="font-size:0.75rem;">
                        <i class="fas fa-shield-alt text-success mr-1"></i> Data Spasial Resmi BWS Kalimantan I &bull; Maks. 256 MB
                    </small>
                    <div style="gap:8px;" class="d-flex">
                        <button type="button" class="btn btn-light border font-weight-bold px-3 btn-sm" data-dismiss="modal" style="border-radius:8px;">Batal</button>
                        <button type="submit" id="btnKelolaGisSubmit" class="btn btn-primary font-weight-bold px-4 btn-sm shadow-sm" style="background:#1d4ed8;border-color:#1d4ed8;border-radius:8px;">
                            <i class="fas fa-cloud-upload-alt mr-1"></i> Mulai Unggah
                        </button>
                    </div>
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
     MODAL KONFIRMASI SOFT DELETE (KE KOTAK SAMPAH)
     ========================================== -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
            <div class="modal-body p-4 text-center">
                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-3" style="width:54px;height:54px;background:#fff7ed;color:#ea580c;">
                    <i class="fas fa-trash-alt fa-lg"></i>
                </div>
                <h6 class="font-weight-bold text-dark mb-1">Pindahkan ke Sampah?</h6>
                <p class="text-muted small mb-0 font-weight-bold" id="deleteFileName" style="word-break:break-all;"></p>
                <small class="text-muted d-block mt-2" style="font-size:0.75rem;">
                    File akan disembunyikan dari peta publik, namun masih tersimpan aman di <strong>Kotak Sampah</strong> dan bisa dipulihkan kembali kapan saja.
                </small>
            </div>
            <div class="modal-footer border-0 bg-light justify-content-center py-3" style="gap:8px;">
                <button type="button" class="btn btn-light border font-weight-bold btn-sm px-3" data-dismiss="modal" style="border-radius:6px;">Batal</button>
                <form id="formDelete" action="{{ route('admin.delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="file_name" id="deleteFileInput">
                    <button type="submit" class="btn btn-warning text-white font-weight-bold btn-sm px-3" style="background:#ea580c;border-color:#ea580c;border-radius:6px;">
                        <i class="fas fa-trash-alt mr-1"></i>Pindahkan ke Sampah
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

@push('styles')
<style>
    .zip-entry-row {
        transition: all 0.15s ease-in-out;
        border-color: #e2e8f0 !important;
        cursor: pointer;
    }
    .zip-entry-row:hover {
        background-color: #eff6ff !important;
        border-color: #93c5fd !important;
        box-shadow: 0 2px 4px rgba(37,99,235,0.06);
    }
    .zip-entry-row input[type="checkbox"] {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script>
    var activeZipInstance = null;
    var activeZipEntries = [];

    async function handleFileChange(input) {
        var files = input.files;
        var emptyState = document.getElementById('dropzoneEmptyState');
        var selectedState = document.getElementById('dropzoneSelectedState');
        var summary = document.getElementById('selectedFileSummary');
        var list = document.getElementById('selectedFileList');
        var emptyNotice = document.getElementById('zipPickerEmptyNotice');
        var zipSection = document.getElementById('zipPickerSection');

        activeZipInstance = null;
        activeZipEntries = [];
        if (zipSection) zipSection.style.display = 'none';
        if (emptyNotice) emptyNotice.style.display = 'block';

        if (!files || files.length === 0) {
            if (emptyState) emptyState.style.display = 'block';
            if (selectedState) selectedState.style.display = 'none';
            updateSubmitButtonText(0);
            return;
        }

        if (emptyState) emptyState.style.display = 'none';
        if (selectedState) selectedState.style.display = 'block';

        if (files.length === 1 && files[0].name.toLowerCase().endsWith('.zip')) {
            var f = files[0];
            var sizeStr = f.size > 1024 * 1024 ? (f.size / (1024 * 1024)).toFixed(2) + ' MB' : (f.size / 1024).toFixed(1) + ' KB';
            summary.innerHTML = '<span class="text-primary font-weight-bold">' + f.name + '</span> (' + sizeStr + ')';
            list.innerHTML = '<div class="d-flex align-items-center justify-content-between p-1"><span><i class="fas fa-file-archive text-warning mr-2"></i>' + f.name + '</span><span class="badge badge-light border text-muted">' + sizeStr + '</span></div>';
            
            // Periksa & bongkar isi file ZIP agar bisa dipilih 1-per-1
            await inspectZipFile(f);
        } else if (files.length === 1) {
            var f = files[0];
            var sizeStr = f.size > 1024 * 1024 ? (f.size / (1024 * 1024)).toFixed(2) + ' MB' : (f.size / 1024).toFixed(1) + ' KB';
            summary.innerHTML = '<span class="text-primary font-weight-bold">' + f.name + '</span> (' + sizeStr + ')';
            list.innerHTML = '<div class="d-flex align-items-center justify-content-between p-1"><span><i class="fas fa-file-code text-primary mr-2"></i>' + f.name + '</span><span class="badge badge-light border text-muted">' + sizeStr + '</span></div>';
            updateSubmitButtonText(1);
        } else {
            summary.innerHTML = '<span class="text-primary font-weight-bold">' + files.length + ' file dipilih</span>';
            var html = '';
            for (var i = 0; i < files.length; i++) {
                var f = files[i];
                var sizeStr = f.size > 1024 * 1024 ? (f.size / (1024 * 1024)).toFixed(2) + ' MB' : (f.size / 1024).toFixed(1) + ' KB';
                html += '<div class="d-flex align-items-center justify-content-between py-1 border-bottom"><span><i class="fas fa-file text-primary mr-2"></i>' + f.name + '</span><span class="badge badge-light border text-muted">' + sizeStr + '</span></div>';
            }
            list.innerHTML = html;
            updateSubmitButtonText(files.length);
        }
    }

    // Fungsi membaca & membedah isi file ZIP untuk dipilih satuan
    async function inspectZipFile(file) {
        var emptyNotice = document.getElementById('zipPickerEmptyNotice');
        var zipSection = document.getElementById('zipPickerSection');
        var zipSummary = document.getElementById('zipPickerSummary');
        var container = document.getElementById('zipEntriesContainer');

        if (typeof JSZip === 'undefined') {
            updateSubmitButtonText(1);
            return;
        }

        if (emptyNotice) emptyNotice.style.display = 'none';
        if (zipSection) zipSection.style.display = 'flex';
        zipSummary.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Membaca daftar file di dalam ' + file.name + '...';
        container.innerHTML = '<div class="text-center p-3 text-muted"><i class="fas fa-spinner fa-spin mr-1"></i> Membuka arsip ZIP...</div>';

        try {
            var zip = await JSZip.loadAsync(file);
            activeZipInstance = zip;
            activeZipEntries = [];

            zip.forEach(function(relativePath, zipEntry) {
                if (zipEntry.dir) return;
                var baseName = relativePath.split('/').pop();
                if (baseName.startsWith('.') || relativePath.includes('__MACOSX')) return;
                var ext = baseName.split('.').pop().toLowerCase();
                if (['json', 'geojson'].includes(ext)) {
                    activeZipEntries.push({
                        path: relativePath,
                        name: baseName,
                        entry: zipEntry
                    });
                }
            });

            if (activeZipEntries.length === 0) {
                zipSummary.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i> Tidak ditemukan file .geojson/.json di dalam ZIP</span>';
                container.innerHTML = '<div class="text-center p-3 text-muted">Tidak ada file spasial yang dapat diimpor di dalam file ZIP ini.</div>';
                updateSubmitButtonText(0);
                return;
            }

            zipSummary.innerHTML = 'Ditemukan <strong class="text-primary">' + activeZipEntries.length + ' file</strong> di dalam ZIP. Centang file yang ingin Anda unggah:';
            renderZipEntriesList(activeZipEntries);
        } catch (err) {
            console.error('JSZip error:', err);
            zipSummary.innerHTML = '<span class="text-danger">Gagal membaca isi ZIP: ' + err.message + '</span>';
            updateSubmitButtonText(1);
        }
    }

    function renderZipEntriesList(entries) {
        var container = document.getElementById('zipEntriesContainer');
        var html = '';
        entries.forEach(function(item, idx) {
            var sizeStr = item.entry._data ? (item.entry._data.uncompressedSize > 1024 ? (item.entry._data.uncompressedSize / 1024).toFixed(0) + ' KB' : item.entry._data.uncompressedSize + ' B') : 'File';
            html += '<label class="d-flex align-items-center justify-content-between p-2 rounded mb-1 border zip-entry-row" style="background:#ffffff; margin-bottom:5px;" data-name="' + item.name.toLowerCase() + '">' +
                        '<div class="d-flex align-items-center mr-2" style="min-width:0; overflow:hidden;">' +
                            '<input type="checkbox" class="zip-entry-checkbox" id="chkZip_' + idx + '" value="' + item.path + '" checked onchange="updateZipSelectedCounter()" style="width:16px; height:16px; min-width:16px; accent-color:#1d4ed8; cursor:pointer; margin:0 10px 0 2px; flex-shrink:0;">' +
                            '<i class="fas fa-file-code text-primary mr-2" style="font-size:0.85rem; flex-shrink:0;"></i>' +
                            '<span class="text-truncate font-weight-bold text-dark" style="font-size:0.8rem;" title="' + item.name + '">' +
                                item.name +
                            '</span>' +
                        '</div>' +
                        '<span class="badge badge-light border text-muted flex-shrink-0" style="font-size:0.68rem; padding:3px 6px;">' + sizeStr + '</span>' +
                    '</label>';
        });
        container.innerHTML = html;
        updateZipSelectedCounter();
    }

    function toggleAllZipEntries(checked) {
        var checkboxes = document.querySelectorAll('.zip-entry-checkbox');
        checkboxes.forEach(function(cb) {
            var row = cb.closest('.zip-entry-row');
            if (row && row.style.display !== 'none') {
                cb.checked = checked;
            }
        });
        updateZipSelectedCounter();
    }

    function filterZipEntries(keyword) {
        var q = keyword.toLowerCase().trim();
        var rows = document.querySelectorAll('.zip-entry-row');
        rows.forEach(function(row) {
            var name = row.getAttribute('data-name') || '';
            if (!q || name.includes(q)) {
                row.style.setProperty('display', 'flex', 'important');
            } else {
                row.style.setProperty('display', 'none', 'important');
            }
        });
    }

    function updateZipSelectedCounter() {
        var checked = document.querySelectorAll('.zip-entry-checkbox:checked');
        var total = document.querySelectorAll('.zip-entry-checkbox');
        var counter = document.getElementById('zipSelectedCounter');
        if (counter) {
            counter.innerHTML = '<span class="text-primary font-weight-bold">' + checked.length + ' dari ' + total.length + ' file</span> dicentang';
        }
        updateSubmitButtonText(checked.length);
    }

    function updateSubmitButtonText(count) {
        var submitBtn = document.getElementById('btnKelolaGisSubmit');
        if (!submitBtn) return;
        if (count === 0) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-cloud-upload-alt mr-1"></i> Pilih Minimal 1 File';
        } else if (count === 1) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-cloud-upload-alt mr-1"></i> Mulai Unggah (1 File Terpilih)';
        } else {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-cloud-upload-alt mr-1"></i> Mulai Unggah (' + count + ' File Terpilih)';
        }
    }

    function clearSelectedFiles(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        var input = document.getElementById('inputUploadGeojson');
        if (input) input.value = '';
        activeZipInstance = null;
        activeZipEntries = [];
        var emptyNotice = document.getElementById('zipPickerEmptyNotice');
        var zipSection = document.getElementById('zipPickerSection');
        if (zipSection) zipSection.style.display = 'none';
        if (emptyNotice) emptyNotice.style.display = 'block';
        var emptyState = document.getElementById('dropzoneEmptyState');
        var selectedState = document.getElementById('dropzoneSelectedState');
        if (emptyState) emptyState.style.display = 'block';
        if (selectedState) selectedState.style.display = 'none';
        updateSubmitButtonText(0);
    }

    // Drag and drop event listeners untuk Dropzone
    document.addEventListener('DOMContentLoaded', function() {
        var dropzone = document.getElementById('dropzoneBox');
        if (!dropzone) return;

        ['dragenter', 'dragover'].forEach(function(eventName) {
            dropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.style.borderColor = '#1d4ed8';
                dropzone.style.background = '#eff6ff';
            }, false);
        });

        ['dragleave', 'drop'].forEach(function(eventName) {
            dropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.style.borderColor = '#93c5fd';
                dropzone.style.background = '#f8fafc';
            }, false);
        });

        dropzone.addEventListener('drop', function(e) {
            var dt = e.dataTransfer;
            var files = dt.files;
            var input = document.getElementById('inputUploadGeojson');
            if (input && files && files.length > 0) {
                input.files = files;
                handleFileChange(input);
            }
        }, false);
    });

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

    // Handle Upload ZIP Shapefile & GeoJSON via shpjs / JSZip
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
        var kategoriSelect = document.getElementById('selectKategoriUpload');
        var kategori = kategoriSelect ? kategoriSelect.value : 'auto';
        formData.append('kategori', kategori);

        // KASUS 1: File ZIP dengan Pemilah Checkbox Aktif
        if (activeZipInstance && activeZipEntries.length > 0) {
            var checkedBoxes = document.querySelectorAll('.zip-entry-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Silakan centang minimal satu file dari dalam ZIP yang ingin diunggah.');
                submitBtn.disabled = false;
                statusText.style.display = 'none';
                return false;
            }

            statusText.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengekstrak ' + checkedBoxes.length + ' file terpilih dari dalam ZIP...';

            for (var k = 0; k < checkedBoxes.length; k++) {
                var zipPath = checkedBoxes[k].value;
                var zipEntry = activeZipInstance.file(zipPath);
                if (zipEntry) {
                    var baseName = zipPath.split('/').pop();
                    var content = await zipEntry.async('string');
                    var cleanName = baseName.replace(/[^a-zA-Z0-9_\-.]/g, '_');
                    if (kategori && kategori !== 'auto' && !cleanName.toLowerCase().startsWith(kategori + '_')) {
                        cleanName = kategori + '_' + cleanName;
                    }
                    var blob = new Blob([content], { type: 'application/geo+json' });
                    formData.append('file_geojson[]', blob, cleanName);
                }
            }
        } else {
            // KASUS 2: File Biasa (.geojson / .json tunggal atau multi-file)
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
                                    if (kategori && kategori !== 'auto' && !cleanName.toLowerCase().startsWith(kategori + '_')) {
                                        cleanName = kategori + '_' + cleanName;
                                    }
                                    var blob = new Blob([JSON.stringify(gj)], { type: 'application/geo+json' });
                                    formData.append('file_geojson[]', blob, cleanName);
                                });
                            } else if (geojson && geojson.type) {
                                var rawName = geojson.fileName || file.name.replace(/\.zip$/i, '');
                                var cleanName = rawName.replace(/[^a-zA-Z0-9_\-]/g, '_') + '.geojson';
                                if (kategori && kategori !== 'auto' && !cleanName.toLowerCase().startsWith(kategori + '_')) {
                                    cleanName = kategori + '_' + cleanName;
                                }
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
        }

        statusText.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan ke server BWS Kalimantan I...';

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

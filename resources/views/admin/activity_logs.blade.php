@extends('admin.layout')

@section('title', 'Log Aktivitas & Audit Trail — BWS Kalimantan I')

@section('content')
<div class="anim-fade-in">

    <!-- Header Judul & Tombol Aksi -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 pb-1" style="gap: 12px;">
        <div>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <h4 class="font-weight-bold mb-0" style="letter-spacing: 0.4px; font-size: 1.15rem; color: #1e293b;">
                    LOG AKTIVITAS &amp; AUDIT TRAIL
                </h4>
                <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 0.72rem; border-radius: 6px; background: #174ea6;">
                    BWS KALIMANTAN I
                </span>
            </div>
            <p class="text-muted small mb-0 mt-1" style="font-size: 0.82rem;">
                Rekam Jejak Operasional &amp; Keamanan Data Geospasial Balai Wilayah Sungai Kalimantan I Pontianak
            </p>
        </div>

        @if(Auth::check() && Auth::user()->role === 'superadmin')
        <div>
            <form action="{{ route('admin.activity-logs.clear') }}" method="POST" id="formClearLogs" onsubmit="return confirm('Peringatan: Apakah Anda yakin ingin membersihkan seluruh riwayat log aktivitas? Tindakan ini tidak dapat dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm font-weight-bold px-3 py-2 shadow-sm" style="border-radius: 20px; font-size: 0.82rem;">
                    <i class="fas fa-trash-alt mr-1"></i> Bersihkan Log
                </button>
            </form>
        </div>
        @endif
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

    <!-- ═══ 4 KARTU STATISTIK AKTIVITAS AUDIT ═══ -->
    <div class="row mb-4">
        <div class="col-6 col-lg-3 mb-3 mb-lg-0">
            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; background: #ffffff;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Log</div>
                        <h4 class="font-weight-bold text-dark mb-0 mt-1" style="font-size: 1.4rem;">{{ $statsAksi['total'] ?? 0 }}</h4>
                    </div>
                    <div class="p-2 rounded-circle" style="background: #eff6ff; color: #2563eb; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-history"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 mb-3 mb-lg-0">
            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; background: #ffffff;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Unggah File</div>
                        <h4 class="font-weight-bold text-dark mb-0 mt-1" style="font-size: 1.4rem;">{{ $statsAksi['upload'] ?? 0 }}</h4>
                    </div>
                    <div class="p-2 rounded-circle" style="background: #ecfdf5; color: #059669; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 mb-3 mb-lg-0">
            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; background: #ffffff;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Dihapus (Sampah)</div>
                        <h4 class="font-weight-bold text-dark mb-0 mt-1" style="font-size: 1.4rem;">{{ $statsAksi['delete'] ?? 0 }}</h4>
                    </div>
                    <div class="p-2 rounded-circle" style="background: #fef2f2; color: #dc2626; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 mb-3 mb-lg-0">
            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; background: #ffffff;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Dipulihkan</div>
                        <h4 class="font-weight-bold text-dark mb-0 mt-1" style="font-size: 1.4rem;">{{ $statsAksi['restore'] ?? 0 }}</h4>
                    </div>
                    <div class="p-2 rounded-circle" style="background: #eff6ff; color: #1d4ed8; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-undo"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ CARD UTAMA TABEL LOG AKTIVITAS ═══ -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; background: #ffffff;">
        <div class="card-body p-4">

            <!-- Filter Kategori Aksi & Pencarian -->
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4 pb-2 border-bottom" style="gap: 14px;">
                <!-- Tab Kategori Aksi -->
                <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                    <a href="{{ route('admin.activity-logs', ['action' => 'all', 'search' => $search]) }}" 
                       class="btn btn-sm {{ ($actionFilter === 'all' || empty($actionFilter)) ? 'btn-primary text-white font-weight-bold' : 'btn-light text-muted' }}" 
                       style="border-radius: 20px; font-size: 0.8rem; padding: 4px 14px;">
                        Semua Aktivitas
                    </a>
                    <a href="{{ route('admin.activity-logs', ['action' => 'upload', 'search' => $search]) }}" 
                       class="btn btn-sm {{ $actionFilter === 'upload' ? 'btn-success text-white font-weight-bold' : 'btn-light text-muted' }}" 
                       style="border-radius: 20px; font-size: 0.8rem; padding: 4px 14px;">
                        <i class="fas fa-cloud-upload-alt mr-1"></i> Unggah
                    </a>
                    <a href="{{ route('admin.activity-logs', ['action' => 'delete', 'search' => $search]) }}" 
                       class="btn btn-sm {{ $actionFilter === 'delete' ? 'btn-danger text-white font-weight-bold' : 'btn-light text-muted' }}" 
                       style="border-radius: 20px; font-size: 0.8rem; padding: 4px 14px;">
                        <i class="fas fa-trash-alt mr-1"></i> Kotak Sampah
                    </a>
                    <a href="{{ route('admin.activity-logs', ['action' => 'restore', 'search' => $search]) }}" 
                       class="btn btn-sm {{ $actionFilter === 'restore' ? 'btn-info text-white font-weight-bold' : 'btn-light text-muted' }}" 
                       style="border-radius: 20px; font-size: 0.8rem; padding: 4px 14px;">
                        <i class="fas fa-undo mr-1"></i> Dipulihkan
                    </a>
                </div>

                <!-- Form Pencarian Teks -->
                <form method="GET" action="{{ route('admin.activity-logs') }}" class="m-0 d-flex align-items-center" style="gap: 8px;">
                    <input type="hidden" name="action" value="{{ $actionFilter }}">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0" style="border-radius: 6px 0 0 6px;"><i class="fas fa-search text-muted fa-xs"></i></span>
                        </div>
                        <input type="text" name="search" value="{{ $search }}" class="form-control border-left-0" style="border-radius: 0 6px 6px 0;" placeholder="Cari nama/petugas/file...">
                    </div>
                </form>
            </div>

            <!-- Tabel Data Log Audit -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.86rem;">
                    <thead>
                        <tr class="text-dark font-weight-bold" style="border-top: 1px solid #f1f5f9; border-bottom: 2px solid #e2e8f0; color: #1e293b;">
                            <th width="5%" class="text-center font-weight-bold">No.</th>
                            <th width="16%" class="font-weight-bold">Waktu &amp; Tanggal</th>
                            <th width="18%" class="font-weight-bold">Petugas / Aktor</th>
                            <th width="14%" class="font-weight-bold">Jenis Aksi</th>
                            <th width="35%" class="font-weight-bold">Rincian Catatan Audit</th>
                            <th width="12%" class="text-center font-weight-bold">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $idx => $log)
                            @php
                                $act = strtolower($log['action'] ?? 'info');
                                $badgeStyle = match($act) {
                                    'upload'       => 'background:#ecfdf5; color:#059669; border-color:#a7f3d0;',
                                    'delete'       => 'background:#fef2f2; color:#dc2626; border-color:#fecaca;',
                                    'restore'      => 'background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe;',
                                    'force_delete' => 'background:#450a0a; color:#fecaca; border-color:#7f1d1d;',
                                    'rename'       => 'background:#fffbeb; color:#d97706; border-color:#fde68a;',
                                    'clear_logs'   => 'background:#f1f5f9; color:#475569; border-color:#cbd5e1;',
                                    default        => 'background:#f8fafc; color:#64748b; border-color:#e2e8f0;',
                                };
                                $actLabel = match($act) {
                                    'upload'       => 'Unggah File',
                                    'delete'       => 'Pindah Sampah',
                                    'restore'      => 'Dipulihkan',
                                    'force_delete' => 'Hapus Permanen',
                                    'rename'       => 'Ganti Nama',
                                    'clear_logs'   => 'Bersihkan Log',
                                    default        => ucfirst($act),
                                };
                                $actIcon = match($act) {
                                    'upload'       => 'fa-cloud-upload-alt',
                                    'delete'       => 'fa-trash-alt',
                                    'restore'      => 'fa-undo',
                                    'force_delete' => 'fa-skull-crossbones',
                                    'rename'       => 'fa-edit',
                                    'clear_logs'   => 'fa-broom',
                                    default        => 'fa-info-circle',
                                };
                            @endphp
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td class="text-center text-muted align-middle">
                                    {{ $offset + $idx + 1 }}
                                </td>
                                <td class="align-middle text-muted" style="font-size: 0.82rem;">
                                    <i class="far fa-clock mr-1 text-primary"></i> {{ $log['time'] ?? '-' }}
                                </td>
                                <td class="align-middle">
                                    <div class="font-weight-bold text-dark">{{ $log['user'] ?? 'Petugas' }}</div>
                                    <small class="badge badge-light border text-muted" style="font-size: 0.7rem; border-radius: 4px;">
                                        {{ ucfirst($log['role'] ?? 'Petugas BWS') }}
                                    </small>
                                </td>
                                <td class="align-middle">
                                    <span class="badge border font-weight-bold px-2 py-1" style="font-size: 0.74rem; border-radius: 6px; {{ $badgeStyle }}">
                                        <i class="fas {{ $actIcon }} mr-1"></i> {{ $actLabel }}
                                    </span>
                                </td>
                                <td class="align-middle text-dark font-weight-500">
                                    {{ $log['description'] ?? '-' }}
                                </td>
                                <td class="text-center align-middle text-muted small">
                                    <code>{{ $log['ip'] ?? '127.0.0.1' }}</code>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3 d-block" style="opacity: 0.35;"></i>
                                    <div class="font-weight-bold mb-1">Belum ada riwayat aktivitas yang tercatat</div>
                                    <div class="small text-muted">Aktivitas unggah, penghapusan ke kotak sampah, dan pemulihan data akan muncul di sini secara otomatis.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Pagination & Keterangan -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-4 pt-2 border-top" style="gap: 12px;">
                <div class="text-muted small">
                    Menampilkan <strong>{{ count($logs) }}</strong> dari <strong>{{ $totalLogs }}</strong> total aktivitas
                </div>

                @if($totalPages > 1)
                <ul class="pagination pagination-sm m-0">
                    <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}">‹</a>
                    </li>
                    @for($i = 1; $i <= $totalPages; $i++)
                        @if($i == 1 || $i == $totalPages || abs($i - $currentPage) <= 2)
                            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                            </li>
                        @elseif($i == 2 || $i == $totalPages - 1)
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        @endif
                    @endfor
                    <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}">›</a>
                    </li>
                </ul>
                @endif
            </div>

        </div>
    </div>

</div>
@endsection


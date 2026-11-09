@extends('admin.layout')

@section('title', 'Log Aktivitas — WebGIS BWS Kalimantan I')

@section('content')
<div class="anim-fade-in">

    <!-- Header Judul, Keterangan & Tombol Bersihkan Log -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 pb-1" style="gap: 12px;">
        <div>
            <h4 class="font-weight-bold mb-0 text-dark" style="letter-spacing: -0.3px; font-size: 1.25rem;">
                Log Aktivitas &amp; Jejak Audit
            </h4>
            <p class="text-muted small mb-0 mt-1" style="font-size: 0.84rem;">
                Rekam jejak operasional dan pengawasan keamanan data geospasial BWS Kalimantan I Pontianak.
            </p>
        </div>

        @if(Auth::check() && Auth::user()->role === 'superadmin')
        <div>
            <button type="button" 
                    class="btn btn-outline-danger btn-sm font-weight-bold px-3 py-2 shadow-sm d-inline-flex align-items-center" 
                    style="border-radius: 20px; font-size: 0.82rem; height: 38px; transition: all 0.2s ease;" 
                    data-toggle="modal" 
                    data-target="#modalClearLogs">
                <i class="fas fa-trash-alt mr-2"></i> Bersihkan Log
            </button>
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

    <!-- ═══ 4 KARTU STATISTIK METRIK AKTIVITAS (INTERAKTIF BISA DIKLIK) ═══ -->
    <div class="row mb-4">
        <!-- 1. Total Log -->
        <div class="col-6 col-lg-3 mb-3 mb-lg-0">
            <a href="{{ route('admin.activity-logs', ['action' => 'all', 'search' => $search, 'per_page' => $perPage]) }}" 
               class="card border-0 shadow-sm p-3 h-100 text-decoration-none kpi-card" 
               style="border-radius: 12px; background: #ffffff; transition: all 0.2s ease; {{ ($actionFilter === 'all' || empty($actionFilter)) ? 'border: 2px solid #1d4ed8 !important; background: #f0f7ff !important;' : '' }}">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Log</div>
                        <h4 class="font-weight-bold text-dark mb-0 mt-1" style="font-size: 1.45rem;">{{ $statsAksi['total'] ?? 0 }}</h4>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="background: #eff6ff; color: #1d4ed8; width: 44px; height: 44px; font-size: 1.1rem;">
                        <i class="fas fa-history"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- 2. Unggah File -->
        <div class="col-6 col-lg-3 mb-3 mb-lg-0">
            <a href="{{ route('admin.activity-logs', ['action' => 'upload', 'search' => $search, 'per_page' => $perPage]) }}" 
               class="card border-0 shadow-sm p-3 h-100 text-decoration-none kpi-card" 
               style="border-radius: 12px; background: #ffffff; transition: all 0.2s ease; {{ $actionFilter === 'upload' ? 'border: 2px solid #10b981 !important; background: #f0fdf4 !important;' : '' }}">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Unggah File</div>
                        <h4 class="font-weight-bold text-dark mb-0 mt-1" style="font-size: 1.45rem;">{{ $statsAksi['upload'] ?? 0 }}</h4>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="background: #ecfdf5; color: #059669; width: 44px; height: 44px; font-size: 1.1rem;">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- 3. Dihapus (Kotak Sampah) -->
        <div class="col-6 col-lg-3 mb-3 mb-lg-0">
            <a href="{{ route('admin.activity-logs', ['action' => 'delete', 'search' => $search, 'per_page' => $perPage]) }}" 
               class="card border-0 shadow-sm p-3 h-100 text-decoration-none kpi-card" 
               style="border-radius: 12px; background: #ffffff; transition: all 0.2s ease; {{ $actionFilter === 'delete' ? 'border: 2px solid #ef4444 !important; background: #fef2f2 !important;' : '' }}">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Dihapus (Sampah)</div>
                        <h4 class="font-weight-bold text-dark mb-0 mt-1" style="font-size: 1.45rem;">{{ $statsAksi['delete'] ?? 0 }}</h4>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="background: #fef2f2; color: #dc2626; width: 44px; height: 44px; font-size: 1.1rem;">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- 4. Dipulihkan -->
        <div class="col-6 col-lg-3 mb-3 mb-lg-0">
            <a href="{{ route('admin.activity-logs', ['action' => 'restore', 'search' => $search, 'per_page' => $perPage]) }}" 
               class="card border-0 shadow-sm p-3 h-100 text-decoration-none kpi-card" 
               style="border-radius: 12px; background: #ffffff; transition: all 0.2s ease; {{ $actionFilter === 'restore' ? 'border: 2px solid #3b82f6 !important; background: #eff6ff !important;' : '' }}">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Dipulihkan</div>
                        <h4 class="font-weight-bold text-dark mb-0 mt-1" style="font-size: 1.45rem;">{{ $statsAksi['restore'] ?? 0 }}</h4>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="background: #eff6ff; color: #1d4ed8; width: 44px; height: 44px; font-size: 1.1rem;">
                        <i class="fas fa-undo"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- ═══ CARD UTAMA TABEL LOG AKTIVITAS ═══ -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; background: #ffffff;">
        <div class="card-body p-4">

            <!-- Toolbar: Tampilkan Entri, Filter Kategori Kapsul & Pencarian Terpadu -->
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4 pb-2 border-bottom" style="gap: 12px;">
                
                <!-- Left: Tampilkan X entri & Tab Kategori Aksi -->
                <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                    <!-- Pengatur Entri -->
                    <div class="d-flex align-items-center text-muted small bg-light px-2 py-1 rounded border shadow-sm" style="height: 38px; border-color:#cbd5e1;">
                        <span class="mr-2 text-dark font-weight-bold" style="font-size: 0.78rem;">Tampilkan</span>
                        <select class="custom-select custom-select-sm border-0 bg-transparent font-weight-bold text-primary" 
                                style="width: 65px; height: 30px; font-size: 0.82rem; cursor: pointer;" 
                                onchange="window.location.href='{{ request()->fullUrlWithQuery(['per_page' => 'PLACEHOLDER']) }}'.replace('PLACEHOLDER', this.value)">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="ml-1 text-muted" style="font-size: 0.78rem;">entri</span>
                    </div>

                    <!-- Kapsul Filter Kategori Aksi -->
                    <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                        <a href="{{ route('admin.activity-logs', ['action' => 'all', 'search' => $search, 'per_page' => $perPage]) }}" 
                           class="btn btn-sm {{ ($actionFilter === 'all' || empty($actionFilter)) ? 'btn-primary text-white font-weight-bold shadow-sm' : 'btn-light text-muted' }}" 
                           style="border-radius: 20px; font-size: 0.82rem; padding: 6px 14px; {{ ($actionFilter === 'all' || empty($actionFilter)) ? 'background:#1d4ed8;border-color:#1d4ed8;' : '' }}">
                            Semua ({{ $statsAksi['total'] ?? 0 }})
                        </a>
                        <a href="{{ route('admin.activity-logs', ['action' => 'upload', 'search' => $search, 'per_page' => $perPage]) }}" 
                           class="btn btn-sm {{ $actionFilter === 'upload' ? 'btn-success text-white font-weight-bold shadow-sm' : 'btn-light text-muted' }}" 
                           style="border-radius: 20px; font-size: 0.82rem; padding: 6px 14px; {{ $actionFilter === 'upload' ? 'background:#059669;border-color:#059669;' : '' }}">
                            <i class="fas fa-cloud-upload-alt mr-1"></i> Unggah ({{ $statsAksi['upload'] ?? 0 }})
                        </a>
                        <a href="{{ route('admin.activity-logs', ['action' => 'delete', 'search' => $search, 'per_page' => $perPage]) }}" 
                           class="btn btn-sm {{ $actionFilter === 'delete' ? 'btn-danger text-white font-weight-bold shadow-sm' : 'btn-light text-muted' }}" 
                           style="border-radius: 20px; font-size: 0.82rem; padding: 6px 14px; {{ $actionFilter === 'delete' ? 'background:#dc2626;border-color:#dc2626;' : '' }}">
                            <i class="fas fa-trash-alt mr-1"></i> Sampah ({{ $statsAksi['delete'] ?? 0 }})
                        </a>
                        <a href="{{ route('admin.activity-logs', ['action' => 'restore', 'search' => $search, 'per_page' => $perPage]) }}" 
                           class="btn btn-sm {{ $actionFilter === 'restore' ? 'btn-info text-white font-weight-bold shadow-sm' : 'btn-light text-muted' }}" 
                           style="border-radius: 20px; font-size: 0.82rem; padding: 6px 14px; {{ $actionFilter === 'restore' ? 'background:#2563eb;border-color:#2563eb;' : '' }}">
                            <i class="fas fa-undo mr-1"></i> Dipulihkan ({{ $statsAksi['restore'] ?? 0 }})
                        </a>
                    </div>
                </div>

                <!-- Right: Form Pencarian Terpadu -->
                <form method="GET" action="{{ route('admin.activity-logs') }}" class="m-0 d-flex align-items-center" style="gap: 8px;">
                    <input type="hidden" name="action" value="{{ $actionFilter }}">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <div class="input-group input-group-sm shadow-sm" style="width: 260px;">
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}" 
                               class="form-control font-weight-normal border" 
                               style="border-radius: 8px 0 0 8px; font-size: 0.83rem; height: 38px; border-color: #cbd5e1;" 
                               placeholder="Cari aktor, aksi, file...">
                        <div class="input-group-append">
                            <button class="btn btn-primary px-3" type="submit" style="background:#1d4ed8; border-color:#1d4ed8; border-radius: 0 8px 8px 0; height: 38px;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    @if(!empty($search))
                    <a href="{{ route('admin.activity-logs', ['action' => $actionFilter, 'per_page' => $perPage]) }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center shadow-sm" style="height: 38px; border-radius: 8px; font-size: 0.8rem;" title="Reset Pencarian">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                    @endif
                </form>
            </div>

            <!-- Tabel Data Log Audit ala Enterprise Modern -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                    <thead>
                        <tr class="text-secondary font-weight-bold text-uppercase" style="background: #f8fafc; border-top: 1px solid #f1f5f9; border-bottom: 2px solid #e2e8f0; font-size: 0.76rem; letter-spacing: 0.5px;">
                            <th width="5%" class="text-center font-weight-bold py-3">No.</th>
                            <th width="16%" class="font-weight-bold py-3">Waktu &amp; Tanggal</th>
                            <th width="20%" class="font-weight-bold py-3">Petugas / Aktor</th>
                            <th width="14%" class="font-weight-bold py-3">Jenis Aksi</th>
                            <th width="33%" class="font-weight-bold py-3">Rincian Catatan Audit</th>
                            <th width="12%" class="text-center font-weight-bold py-3">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $idx => $log)
                            @php
                                $act = strtolower($log['action'] ?? 'info');
                                $badgeStyle = match($act) {
                                    'upload'       => 'background:#ecfdf5; color:#059669; border: 1px solid #a7f3d0;',
                                    'delete'       => 'background:#fef2f2; color:#dc2626; border: 1px solid #fecaca;',
                                    'restore'      => 'background:#eff6ff; color:#1d4ed8; border: 1px solid #bfdbfe;',
                                    'force_delete' => 'background:#450a0a; color:#fecaca; border: 1px solid #7f1d1d;',
                                    'rename'       => 'background:#fffbeb; color:#d97706; border: 1px solid #fde68a;',
                                    'clear_logs'   => 'background:#f1f5f9; color:#475569; border: 1px solid #cbd5e1;',
                                    default        => 'background:#f8fafc; color:#64748b; border: 1px solid #e2e8f0;',
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

                                // Petugas Avatar & Inisial
                                $actorName = $log['user'] ?? 'Petugas';
                                $words = explode(' ', trim($actorName));
                                $initials = '';
                                foreach (array_slice($words, 0, 2) as $w) {
                                    $initials .= strtoupper(mb_substr($w, 0, 1));
                                }
                                $palettes = [
                                    ['bg' => '#eff6ff', 'text' => '#1d4ed8', 'border' => '#bfdbfe'],
                                    ['bg' => '#f0fdf4', 'text' => '#15803d', 'border' => '#bbf7d0'],
                                    ['bg' => '#fef3c7', 'text' => '#b45309', 'border' => '#fde68a'],
                                    ['bg' => '#f3e8ff', 'text' => '#7e22ce', 'border' => '#e9d5ff'],
                                    ['bg' => '#fce7f3', 'text' => '#be185d', 'border' => '#fbcfe8'],
                                ];
                                $theme = $palettes[abs(crc32($actorName)) % count($palettes)];

                                // Format Tanggal Indonesia
                                $rawTime = $log['time'] ?? '-';
                                $formattedDate = $rawTime;
                                $formattedHour = '';
                                try {
                                    $carbonDate = \Carbon\Carbon::parse($rawTime);
                                    $formattedDate = $carbonDate->format('d/m/Y');
                                    $formattedHour = $carbonDate->format('H:i:s');
                                } catch (\Throwable $e) {}

                                // Highlight Nama File Spasial
                                $desc = e($log['description'] ?? '-');
                                $descFormatted = preg_replace("/'([^']+)'/", '<span class="badge badge-light border text-primary font-weight-bold px-2 py-05" style="font-size:0.82rem; background:#f0f7ff; border-color:#bfdbfe; font-family:monospace;">$1</span>', $desc);
                            @endphp
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td class="text-center text-muted align-middle font-weight-bold" style="font-size: 0.82rem;">
                                    {{ $offset + $idx + 1 }}
                                </td>
                                <td class="align-middle">
                                    <div class="text-dark font-weight-bold" style="font-size: 0.84rem;">
                                        <i class="far fa-calendar-alt text-primary mr-1 fa-xs"></i>{{ $formattedDate }}
                                    </div>
                                    @if($formattedHour)
                                    <small class="text-muted d-block" style="font-size: 0.74rem;">
                                        <i class="far fa-clock mr-1"></i>{{ $formattedHour }} WIB
                                    </small>
                                    @endif
                                </td>
                                <td class="align-middle py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center font-weight-bold mr-2 shadow-xs" 
                                             style="width: 34px; height: 34px; min-width: 34px; background: {{ $theme['bg'] }}; color: {{ $theme['text'] }}; border: 1px solid {{ $theme['border'] }}; font-size: 0.78rem;">
                                            {{ $initials ?: 'P' }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $actorName }}</div>
                                            <span class="badge badge-light border text-secondary font-weight-normal" style="font-size: 0.7rem; border-radius: 4px; background: #f8fafc;">
                                                {{ ucfirst($log['role'] ?? 'Petugas BWS') }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <span class="badge font-weight-bold px-2 py-1" style="font-size: 0.76rem; border-radius: 6px; {{ $badgeStyle }}">
                                        <i class="fas {{ $actIcon }} mr-1"></i> {{ $actLabel }}
                                    </span>
                                </td>
                                <td class="align-middle text-dark" style="font-size: 0.84rem; line-height: 1.5;">
                                    {!! $descFormatted !!}
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-light border font-weight-normal text-secondary px-2 py-1" style="font-family: monospace; font-size: 0.79rem; background: #f8fafc; border-color: #e2e8f0;">
                                        <i class="fas fa-network-wired text-muted mr-1" style="font-size: 0.72rem;"></i>{{ $log['ip'] ?? '127.0.0.1' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3 d-block" style="opacity: 0.35;"></i>
                                    <div class="font-weight-bold mb-1">Belum ada riwayat aktivitas yang tercatat</div>
                                    <div class="small text-muted">Aktivitas unggah, penghapusan ke kotak sampah, dan pemulihan data akan tercatat di sini secara otomatis.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Pagination & Keterangan -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-4 pt-3 border-top" style="gap: 12px;">
                <div class="text-muted small">
                    Menampilkan <strong>{{ count($logs) }}</strong> dari <strong>{{ $totalLogs }}</strong> total aktivitas audit
                </div>

                @if($totalPages > 1)
                <ul class="pagination pagination-sm m-0">
                    <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}" style="border-radius: 6px 0 0 6px;">&lsaquo;</a>
                    </li>
                    @for($i = 1; $i <= $totalPages; $i++)
                        @if($i == 1 || $i == $totalPages || abs($i - $currentPage) <= 2)
                            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" style="{{ $i == $currentPage ? 'background: #1d4ed8; border-color: #1d4ed8;' : '' }}">{{ $i }}</a>
                            </li>
                        @elseif($i == 2 || $i == $totalPages - 1)
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        @endif
                    @endfor
                    <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}" style="border-radius: 0 6px 6px 0;">&rsaquo;</a>
                    </li>
                </ul>
                @else
                <div>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled">
                            <span class="page-link" style="border-radius: 6px 0 0 6px;">&lsaquo;</span>
                        </li>
                        <li class="page-item active">
                            <span class="page-link" style="background: #1d4ed8; border-color: #1d4ed8;">1</span>
                        </li>
                        <li class="page-item disabled">
                            <span class="page-link" style="border-radius: 0 6px 6px 0;">&rsaquo;</span>
                        </li>
                    </ul>
                </div>
                @endif
            </div>

        </div>
    </div>

</div>

<!-- ==========================================
     MODAL KONFIRMASI BERSIHKAN LOG
     ========================================== -->
<div class="modal fade" id="modalClearLogs" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-body p-4 text-center">
                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-3 shadow-xs" style="width: 56px; height: 56px; background: #fee2e2; color: #ef4444;">
                    <i class="fas fa-trash-alt fa-lg"></i>
                </div>
                <h6 class="font-weight-bold text-dark mb-1">Bersihkan Riwayat Log?</h6>
                <p class="text-muted small mb-0">
                    Seluruh catatan rekam jejak audit dan aktivitas operasional akan dihapus permanen.
                </p>
                <small class="text-danger d-block mt-2 font-weight-bold">Tindakan ini tidak dapat dibatalkan!</small>
            </div>
            <div class="modal-footer border-0 bg-light justify-content-center py-3" style="gap: 8px;">
                <button type="button" class="btn btn-light border font-weight-bold btn-sm px-3" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
                <form action="{{ route('admin.activity-logs.clear') }}" method="POST" class="m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger font-weight-bold btn-sm px-4 shadow-sm" style="background: #ef4444; border-color: #ef4444; border-radius: 6px;">
                        <i class="fas fa-trash-alt mr-1"></i> Ya, Bersihkan Log
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.06) !important;
    }
</style>
@endsection

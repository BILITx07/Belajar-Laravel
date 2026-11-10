@extends('admin.layout')

@section('title', 'Manajemen User — WebGIS BWS Kalimantan I')

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

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius:10px;border-left:4px solid #ef4444;">
        <div class="d-flex align-items-center mb-1">
            <i class="fas fa-exclamation-circle mr-2 text-danger"></i>
            <strong>Terjadi kesalahan input:</strong>
        </div>
        <ul class="mb-0 pl-4 small">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Card Utama Manajemen User -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; background: #ffffff;">
        <div class="card-body p-4">

            <!-- Card Header: Judul, Subtitle & Tombol Tambah User -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 pb-2 border-bottom" style="gap: 12px;">
                <div>
                    <h5 class="font-weight-bold text-dark mb-1" style="font-size: 1.18rem; letter-spacing: -0.3px;">
                        Daftar User Aplikasi
                    </h5>
                    <p class="text-muted small mb-0">
                        Kelola akun hak akses administrator dan staf operator WebGIS BWS Kalimantan I.
                    </p>
                </div>
                <button type="button" 
                        class="btn text-white font-weight-bold px-4 py-2 shadow-sm d-inline-flex align-items-center justify-content-center"
                        style="background: #10143a; border: 1px solid #10143a; border-radius: 8px; font-size: 0.86rem; height: 38px; transition: all 0.18s ease;"
                        onmouseover="this.style.background='#1c2262';this.style.borderColor='#1c2262';"
                        onmouseout="this.style.background='#10143a';this.style.borderColor='#10143a';"
                        data-toggle="modal" 
                        data-target="#modalTambahUser">
                    <i class="fas fa-user-plus mr-2 text-warning"></i> Tambah User Baru
                </button>
            </div>

            <!-- Toolbar: Tampilkan entri & Cari Terpadu -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3" style="gap: 12px;">
                <!-- Left: Tampilkan X entri -->
                <div class="d-flex align-items-center text-muted small bg-light px-2 py-1 rounded border shadow-sm" style="height: 38px; border-color:#cbd5e1;">
                    <span class="mr-2 text-dark font-weight-bold" style="font-size: 0.78rem;">Tampilkan</span>
                    <select class="custom-select custom-select-sm border-0 bg-transparent font-weight-bold text-primary" 
                            style="width: 65px; height: 30px; font-size: 0.82rem; cursor: pointer;" 
                            onchange="window.location.href='{{ request()->fullUrlWithQuery(['per_page' => 'PLACEHOLDER']) }}'.replace('PLACEHOLDER', this.value)">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="ml-1 text-muted" style="font-size: 0.78rem;">entri</span>
                </div>

                <!-- Right: Cari Terpadu -->
                <form method="GET" action="{{ route('users.index') }}" class="m-0 d-flex align-items-center" style="gap: 8px;">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <div class="input-group input-group-sm shadow-sm" style="width: 260px;">
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}" 
                               class="form-control font-weight-normal border" 
                               style="border-radius: 8px 0 0 8px; font-size: 0.83rem; height: 38px; border-color: #cbd5e1;" 
                               placeholder="Ketik nama, email, username...">
                        <div class="input-group-append">
                            <button class="btn px-3 text-white" type="submit" style="background:#10143a; border-color:#10143a; border-radius: 0 8px 8px 0; height: 38px;" onmouseover="this.style.background='#1c2262';" onmouseout="this.style.background='#10143a';">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    @if(!empty($search))
                    <a href="{{ route('users.index', ['per_page' => $perPage]) }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center shadow-sm" style="height: 38px; border-radius: 8px; font-size: 0.8rem;" title="Reset Pencarian">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                    @endif
                </form>
            </div>

            <!-- Tabel Pengguna ala Enterprise Modern -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                    <thead>
                        <tr class="text-secondary font-weight-bold text-uppercase" style="background: #f8fafc; border-top: 1px solid #f1f5f9; border-bottom: 2px solid #e2e8f0; font-size: 0.76rem; letter-spacing: 0.5px;">
                            <th width="5%" class="text-center font-weight-bold py-3">No.</th>
                            <th width="28%" class="font-weight-bold py-3">Nama Pengguna</th>
                            <th width="15%" class="font-weight-bold py-3">Username</th>
                            <th width="14%" class="font-weight-bold py-3">No. HP</th>
                            <th width="20%" class="font-weight-bold py-3">Email</th>
                            <th width="10%" class="font-weight-bold py-3">Role</th>
                            <th width="8%" class="text-center font-weight-bold py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            @php
                                $words = explode(' ', trim($user->name));
                                $initials = '';
                                foreach (array_slice($words, 0, 2) as $w) {
                                    $initials .= strtoupper(mb_substr($w, 0, 1));
                                }
                                $palette = [
                                    ['bg' => '#f1f5f9', 'text' => '#334155', 'border' => '#cbd5e1'],
                                    ['bg' => '#eff6ff', 'text' => '#1d4ed8', 'border' => '#bfdbfe'],
                                    ['bg' => '#ecfdf5', 'text' => '#059669', 'border' => '#a7f3d0'],
                                    ['bg' => '#fffbeb', 'text' => '#d97706', 'border' => '#fde68a'],
                                    ['bg' => '#f8fafc', 'text' => '#475569', 'border' => '#e2e8f0'],
                                ];
                                $colorIdx = abs(crc32($user->email ?? $user->name)) % count($palette);
                                $theme = $palette[$colorIdx];
                            @endphp
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td class="text-center text-muted align-middle font-weight-bold" style="font-size: 0.82rem;">
                                    {{ $users->firstItem() + $index }}
                                </td>
                                <td class="align-middle py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center font-weight-bold mr-3 shadow-xs" 
                                             style="width: 38px; height: 38px; min-width: 38px; background: {{ $theme['bg'] }}; color: {{ $theme['text'] }}; border: 1px solid {{ $theme['border'] }}; font-size: 0.84rem;">
                                            {{ $initials ?: 'U' }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark" style="font-size: 0.9rem;">
                                                {{ $user->name }}
                                            </div>
                                            <small class="text-muted d-block" style="font-size: 0.74rem;">
                                                <i class="far fa-clock mr-1"></i>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-light border text-secondary px-2 py-1 font-weight-bold" style="font-size: 0.8rem; background: #f8fafc; border-color: #e2e8f0;">
                                        <i class="fas fa-at text-muted" style="font-size:0.75rem;margin-right:2px;"></i>{{ $user->display_username }}
                                    </span>
                                </td>
                                <td class="align-middle" style="font-size: 0.84rem;">
                                    @if($user->phone)
                                        <span class="text-dark"><i class="fas fa-phone-alt text-muted mr-1 fa-xs"></i>{{ $user->phone }}</span>
                                    @else
                                        <span class="text-muted font-italic">-</span>
                                    @endif
                                </td>
                                <td class="align-middle" style="font-size: 0.84rem;">
                                    <span class="text-dark">
                                        <i class="far fa-envelope text-muted mr-1"></i>{{ $user->email }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    @if(strtolower($user->role) === 'superadmin')
                                        <span class="badge font-weight-bold px-2 py-1" style="font-size:0.76rem; border-radius:6px; background:rgba(16,20,58,0.06); color:#10143a; border:1px solid rgba(16,20,58,0.18);">
                                            <i class="fas fa-shield-alt mr-1 text-warning"></i> Super Admin
                                        </span>
                                    @else
                                        <span class="badge font-weight-bold px-2 py-1" style="font-size:0.76rem; border-radius:6px; background:#f1f5f9; color:#475569; border:1px solid #e2e8f0;">
                                            <i class="fas fa-user mr-1 text-muted"></i> Admin
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center align-middle py-3">
                                    <div class="d-inline-flex align-items-center justify-content-center" style="gap: 8px;">
                                        <!-- Tombol Edit User (Clean Neutral State) -->
                                        <button type="button" 
                                                class="btn btn-sm shadow-xs d-inline-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px; border-radius: 8px; background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; transition: all 0.18s ease;"
                                                title="Edit Data User"
                                                onmouseover="this.style.background='#10143a'; this.style.color='#ffffff'; this.style.borderColor='#10143a';"
                                                onmouseout="this.style.background='#f8fafc'; this.style.color='#475569'; this.style.borderColor='#e2e8f0';"
                                                onclick="openEditUserModal({{ json_encode([
                                                    'id'       => $user->id,
                                                    'name'     => $user->name,
                                                    'username' => $user->display_username,
                                                    'email'    => $user->email,
                                                    'phone'    => $user->phone,
                                                    'role'     => $user->role,
                                                ]) }})">
                                            <i class="fas fa-pen fa-xs"></i>
                                        </button>

                                        <!-- Tombol Hapus User (Clean Neutral State) -->
                                        @if(Auth::id() !== $user->id)
                                        <button type="button" 
                                                class="btn btn-sm shadow-xs d-inline-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px; border-radius: 8px; background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; transition: all 0.18s ease;"
                                                title="Hapus Akun User"
                                                onmouseover="this.style.background='#dc2626'; this.style.color='#ffffff'; this.style.borderColor='#dc2626';"
                                                onmouseout="this.style.background='#f8fafc'; this.style.color='#64748b'; this.style.borderColor='#e2e8f0';"
                                                onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                            <i class="fas fa-trash fa-xs"></i>
                                        </button>
                                        @else
                                        <!-- Placeholder Terkunci agar Kolom Edit Selalu Sejajar Sempurna -->
                                        <button type="button" 
                                                class="btn btn-sm d-inline-flex align-items-center justify-content-center disabled"
                                                style="width: 32px; height: 32px; border-radius: 8px; background: #f8fafc; color: #cbd5e1; border: 1px solid #f1f5f9; cursor: not-allowed; opacity: 0.6;"
                                                title="Akun Anda sendiri tidak dapat dihapus"
                                                disabled>
                                            <i class="fas fa-trash fa-xs"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-user-slash fa-3x text-muted mb-3 d-block" style="opacity: 0.4;"></i>
                                    <div class="font-weight-bold mb-1">Tidak ada data user ditemukan</div>
                                    <p class="small mb-0">Klik tombol "+ Tambah User Baru" untuk mendaftarkan akun baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer: Menampilkan entri & Pagination -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-4 pt-3 border-top" style="gap: 12px;">
                <div class="text-muted small">
                    @if($users->total() > 0)
                        Menampilkan <strong>{{ $users->firstItem() }}</strong> – <strong>{{ $users->lastItem() }}</strong> dari <strong>{{ $users->total() }}</strong> pengguna terdaftar
                    @else
                        Menampilkan <strong>0</strong> pengguna
                    @endif
                </div>

                @if($users->hasPages())
                <div>
                    {{ $users->links() }}
                </div>
                @else
                <div>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled">
                            <span class="page-link" style="border-radius: 6px 0 0 6px;">&lsaquo;</span>
                        </li>
                        <li class="page-item active">
                            <span class="page-link" style="background: #10143a; border-color: #10143a;">1</span>
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
     MODAL TAMBAH USER
     ========================================== -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" role="dialog" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 520px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header text-white py-3 px-4" style="background: linear-gradient(135deg, #0d1033 0%, #161b46 100%); border-bottom: 2px solid #fad605;">
                <div class="d-flex align-items-center">
                    <div class="mr-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(255,255,255,0.2); border-radius: 10px;">
                        <i class="fas fa-user-plus fa-lg text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold text-white mb-0" id="modalTambahUserLabel" style="font-size: 1.05rem;">
                            Tambah User Baru
                        </h5>
                        <small class="text-white-50">Daftarkan akun administrator atau staf operator WebGIS</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-dark mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Budi Santoso, S.T.">
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Contoh: budisantoso">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold small text-dark mb-1">No. HP / WhatsApp</label>
                            <input type="text" name="phone" class="form-control" placeholder="Contoh: 08123456789">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-dark mb-1">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required placeholder="budi@pu.go.id">
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Hak Akses / Role <span class="text-danger">*</span></label>
                            <select name="role" class="custom-select" required>
                                <option value="admin" selected>Admin (Operator)</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required minlength="6" placeholder="Minimal 6 karakter">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top py-3 px-4">
                    <button type="button" class="btn btn-light border font-weight-bold btn-sm px-3" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
                    <button type="submit" class="btn text-white font-weight-bold btn-sm px-4 shadow-sm" style="background: #10143a; border-color: #10143a; border-radius: 6px;" onmouseover="this.style.background='#1c2262';" onmouseout="this.style.background='#10143a';">
                        <i class="fas fa-check mr-1 text-warning"></i> Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==========================================
     MODAL EDIT USER
     ========================================== -->
<div class="modal fade" id="modalEditUser" tabindex="-1" role="dialog" aria-labelledby="modalEditUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 520px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header text-white py-3 px-4" style="background: linear-gradient(135deg, #0d1033 0%, #161b46 100%); border-bottom: 2px solid #fad605;">
                <div class="d-flex align-items-center">
                    <div class="mr-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(255,255,255,0.2); border-radius: 10px;">
                        <i class="fas fa-user-edit fa-lg text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold text-white mb-0" id="modalEditUserLabel" style="font-size: 1.05rem;">
                            Edit Data User
                        </h5>
                        <small class="text-white-50">Perbarui profil, hak akses, atau kata sandi akun</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formEditUser" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 bg-white">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-dark mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editUserName" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Username</label>
                            <input type="text" name="username" id="editUserUsername" class="form-control">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold small text-dark mb-1">No. HP / WhatsApp</label>
                            <input type="text" name="phone" id="editUserPhone" class="form-control">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-dark mb-1">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="editUserEmail" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Hak Akses / Role <span class="text-danger">*</span></label>
                            <select name="role" id="editUserRole" class="custom-select" required>
                                <option value="admin">Admin (Operator)</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Ganti Password (Opsional)</label>
                            <input type="password" name="password" class="form-control" minlength="6" placeholder="Kosongkan jika tak diubah">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top py-3 px-4">
                    <button type="button" class="btn btn-light border font-weight-bold btn-sm px-3" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
                    <button type="submit" class="btn text-white font-weight-bold btn-sm px-4 shadow-sm" style="background: #10143a; border-color: #10143a; border-radius: 6px;" onmouseover="this.style.background='#1c2262';" onmouseout="this.style.background='#10143a';">
                        <i class="fas fa-check mr-1 text-warning"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==========================================
     MODAL KONFIRMASI HAPUS USER
     ========================================== -->
<div class="modal fade" id="modalDeleteUser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-body p-4 text-center">
                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-3 shadow-xs" style="width: 56px; height: 56px; background: #fee2e2; color: #ef4444;">
                    <i class="fas fa-trash-alt fa-lg"></i>
                </div>
                <h6 class="font-weight-bold text-dark mb-1">Hapus Pengguna?</h6>
                <p class="text-muted small mb-0" id="deleteUserNameText"></p>
                <small class="text-danger d-block mt-2 font-weight-bold">Tindakan ini tidak dapat dibatalkan!</small>
            </div>
            <div class="modal-footer border-0 bg-light justify-content-center py-3" style="gap: 8px;">
                <button type="button" class="btn btn-light border font-weight-bold btn-sm px-3" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
                <form id="formDeleteUser" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger font-weight-bold btn-sm px-4 shadow-sm" style="background: #ef4444; border-color: #ef4444; border-radius: 6px;">
                        <i class="fas fa-trash-alt mr-1"></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openEditUserModal(user) {
        document.getElementById('editUserName').value = user.name || '';
        document.getElementById('editUserUsername').value = user.username || '';
        document.getElementById('editUserEmail').value = user.email || '';
        document.getElementById('editUserPhone').value = user.phone || '';
        document.getElementById('editUserRole').value = user.role || 'admin';
        
        var form = document.getElementById('formEditUser');
        form.action = '/users/' + user.id;

        $('#modalEditUser').modal('show');
    }

    function confirmDeleteUser(userId, userName) {
        document.getElementById('deleteUserNameText').textContent = 'Anda akan menghapus akun "' + userName + '".';
        var form = document.getElementById('formDeleteUser');
        form.action = '/users/' + userId;
        $('#modalDeleteUser').modal('show');
    }
</script>
@endpush

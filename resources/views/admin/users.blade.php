@extends('admin.layout')

@section('title', 'Manajemen User — FEWS BWS Kalimantan I')

@section('content')
<div class="anim-fade-in">

    <!-- Header Title & Breadcrumb Persis Referensi FEWS BWS Kalimantan I -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="font-weight-bold mb-0" style="letter-spacing: 0.5px; font-size: 1.05rem; color: #495057;">
            MANAJEMEN USER
        </h4>
        <span class="text-muted small" style="font-weight: 500;">Menu Utama</span>
    </div>

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
    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius:10px;">
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

    <!-- Card Utama Sesuai Referensi FEWS BWS Kalimantan I -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; background: #ffffff;">
        <div class="card-body p-4">

            <!-- Card Header: Judul & Tombol Tambah User -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 pb-2 border-bottom" style="gap: 12px;">
                <h5 class="font-weight-bold text-dark mb-0" style="font-size: 1.15rem;">
                    Daftar User Aplikasi
                </h5>
                <button type="button" 
                        class="btn text-white font-weight-bold px-4 py-2 shadow-sm d-inline-flex align-items-center justify-content-center"
                        style="background: #2563eb; border-radius: 25px; font-size: 0.88rem; transition: all 0.2s ease;"
                        data-toggle="modal" 
                        data-target="#modalTambahUser">
                    <i class="fas fa-plus mr-2"></i> Tambah User
                </button>
            </div>

            <!-- Toolbar: Tampilkan entri & Cari -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3" style="gap: 12px;">
                <div class="d-flex align-items-center text-muted" style="font-size: 0.88rem;">
                    <span class="mr-2">Tampilkan</span>
                    <select class="form-control form-control-sm d-inline-block px-2 text-center" 
                            style="width: 70px; border-radius: 6px;" 
                            onchange="window.location.href='{{ request()->fullUrlWithQuery(['per_page' => 'PLACEHOLDER']) }}'.replace('PLACEHOLDER', this.value)">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="ml-2">entri</span>
                </div>

                <div class="d-flex align-items-center text-muted" style="font-size: 0.88rem;">
                    <span class="mr-2">Cari:</span>
                    <form method="GET" action="{{ route('users.index') }}" class="m-0">
                        <input type="hidden" name="per_page" value="{{ $perPage }}">
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}" 
                               class="form-control form-control-sm" 
                               style="width: 220px; border-radius: 6px;" 
                               placeholder=""
                               onkeypress="if(event.key === 'Enter') this.form.submit()">
                    </form>
                </div>
            </div>

            <!-- Tabel Pengguna ala FEWS BWS -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                    <thead>
                        <tr class="text-dark font-weight-bold" style="border-top: 1px solid #f1f5f9; border-bottom: 2px solid #e2e8f0; color: #1e293b;">
                            <th width="5%" class="text-center font-weight-bold">No.</th>
                            <th width="18%" class="font-weight-bold">
                                Nama <i class="fas fa-sort text-muted ml-1 fa-xs" style="opacity: 0.5;"></i>
                            </th>
                            <th width="15%" class="font-weight-bold">
                                Username <i class="fas fa-sort text-muted ml-1 fa-xs" style="opacity: 0.5;"></i>
                            </th>
                            <th width="13%" class="font-weight-bold">
                                No. HP <i class="fas fa-sort text-muted ml-1 fa-xs" style="opacity: 0.5;"></i>
                            </th>
                            <th width="20%" class="font-weight-bold">
                                Email <i class="fas fa-sort text-muted ml-1 fa-xs" style="opacity: 0.5;"></i>
                            </th>
                            <th width="11%" class="font-weight-bold">
                                Role <i class="fas fa-sort text-muted ml-1 fa-xs" style="opacity: 0.5;"></i>
                            </th>
                            <th width="12%" class="font-weight-bold">
                                Login Terakhir <i class="fas fa-sort text-muted ml-1 fa-xs" style="opacity: 0.5;"></i>
                            </th>
                            <th width="6%" class="text-center font-weight-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td class="text-center text-muted align-middle">
                                    {{ $users->firstItem() + $index }}
                                </td>
                                <td class="align-middle font-weight-bold text-dark">
                                    {{ $user->name }}
                                </td>
                                <td class="align-middle text-muted">
                                    {{ $user->display_username }}
                                </td>
                                <td class="align-middle text-muted">
                                    {{ $user->phone ?: '-' }}
                                </td>
                                <td class="align-middle text-dark">
                                    {{ $user->email }}
                                </td>
                                <td class="align-middle">
                                    @if(in_array(strtolower($user->role), ['superadmin', 'admin']))
                                        <span class="text-primary font-weight-bold">Administrator</span>
                                    @elseif(strtolower($user->role) === 'operator')
                                        <span class="text-dark font-weight-bold">Operator</span>
                                    @else
                                        <span class="text-secondary font-weight-bold">User</span>
                                    @endif
                                </td>
                                <td class="align-middle text-muted" style="font-size: 0.82rem;">
                                    {{ $user->last_login_at ? $user->last_login_at->format('d-M-Y H:i') : '-' }}
                                </td>
                                <td class="text-center align-middle">
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                        <!-- Tombol Edit Hijau -->
                                        <button type="button" 
                                                class="btn btn-sm btn-success px-2 py-1 shadow-sm"
                                                style="background: #10b981; border-color: #10b981; border-radius: 6px;"
                                                title="Edit User"
                                                onclick="openEditUserModal({{ json_encode([
                                                    'id'       => $user->id,
                                                    'name'     => $user->name,
                                                    'username' => $user->display_username,
                                                    'email'    => $user->email,
                                                    'phone'    => $user->phone,
                                                    'role'     => $user->role,
                                                ]) }})">
                                            <i class="fas fa-pencil-alt fa-xs"></i>
                                        </button>

                                        <!-- Tombol Hapus Merah -->
                                        @if(Auth::id() !== $user->id)
                                        <button type="button" 
                                                class="btn btn-sm btn-danger px-2 py-1 shadow-sm"
                                                style="background: #ef4444; border-color: #ef4444; border-radius: 6px;"
                                                title="Hapus User"
                                                onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                            <i class="fas fa-trash-alt fa-xs"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-user-slash fa-3x text-muted mb-3 d-block" style="opacity: 0.4;"></i>
                                    <div class="font-weight-bold mb-1">Belum ada data user</div>
                                    <p class="small mb-0">Klik tombol "+ Tambah User" untuk menambahkan pengguna baru.</p>
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
                        Menampilkan <strong>{{ $users->firstItem() }}</strong> sampai <strong>{{ $users->lastItem() }}</strong> dari <strong>{{ $users->total() }}</strong> entri
                    @else
                        Menampilkan <strong>0</strong> dari <strong>0</strong> entri
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
                            <span class="page-link">&lsaquo;</span>
                        </li>
                        <li class="page-item active">
                            <span class="page-link" style="background: #2563eb; border-color: #2563eb;">1</span>
                        </li>
                        <li class="page-item disabled">
                            <span class="page-link">&rsaquo;</span>
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
            <div class="modal-header text-white py-3 px-4" style="background: linear-gradient(135deg, #1d64d8 0%, #1e40af 100%);">
                <div class="d-flex align-items-center">
                    <div class="mr-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(255,255,255,0.2); border-radius: 8px;">
                        <i class="fas fa-user-plus fa-lg text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold text-white mb-0" id="modalTambahUserLabel" style="font-size: 1.05rem;">
                            Tambah User Baru
                        </h5>
                        <small class="text-white-50">Menambahkan akun petugas atau operator aplikasi</small>
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
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Operator FEWS">
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Contoh: operator">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold small text-dark mb-1">No. HP / Telepon</label>
                            <input type="text" name="phone" class="form-control" placeholder="Contoh: 08123456789">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-dark mb-1">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required placeholder="operator@gmail.com">
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Role / Peran <span class="text-danger">*</span></label>
                            <select name="role" class="form-control" required>
                                <option value="operator" selected>Operator</option>
                                <option value="admin">Administrator</option>
                                <option value="superadmin">Super Admin</option>
                                <option value="user">User Biasa</option>
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
                    <button type="submit" class="btn btn-primary font-weight-bold btn-sm px-4 shadow-sm" style="background: #2563eb; border-color: #2563eb; border-radius: 6px;">
                        <i class="fas fa-check mr-1"></i> Simpan User
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
            <div class="modal-header border-0 py-3 px-4" style="background: #f0fdf4;">
                <h5 class="modal-title font-weight-bold" id="modalEditUserLabel" style="font-size: 1.05rem; color: #166534;">
                    <i class="fas fa-user-edit mr-2 text-success"></i> Edit Data User
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                            <label class="font-weight-bold small text-dark mb-1">No. HP / Telepon</label>
                            <input type="text" name="phone" id="editUserPhone" class="form-control">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-dark mb-1">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="editUserEmail" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Role / Peran <span class="text-danger">*</span></label>
                            <select name="role" id="editUserRole" class="form-control" required>
                                <option value="operator">Operator</option>
                                <option value="admin">Administrator</option>
                                <option value="superadmin">Super Admin</option>
                                <option value="user">User Biasa</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Ganti Password (Opsional)</label>
                            <input type="password" name="password" class="form-control" minlength="6" placeholder="Biarkan kosong jika tidak diubah">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top py-2 px-4">
                    <button type="button" class="btn btn-light border font-weight-bold btn-sm px-3" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
                    <button type="submit" class="btn btn-success font-weight-bold btn-sm px-4 shadow-sm" style="background: #10b981; border-color: #10b981; border-radius: 6px;">
                        <i class="fas fa-check mr-1"></i> Simpan Perubahan
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
                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px; background: #fee2e2; color: #ef4444;">
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
                    <button type="submit" class="btn btn-danger font-weight-bold btn-sm px-4" style="background: #ef4444; border-color: #ef4444; border-radius: 6px;">
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
        document.getElementById('editUserRole').value = user.role || 'user';
        
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

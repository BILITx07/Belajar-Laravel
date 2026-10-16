@extends('layout.master')

@section('title', 'Profil Akun - SISDA BWS Kalimantan I')

@push('styles')
<style>
    .profile-avatar-circle {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1b5fb5 0%, #0f3b75 100%);
        color: #ffffff;
        font-size: 2.2rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px auto;
        box-shadow: 0 4px 14px rgba(27, 95, 181, 0.3);
        letter-spacing: 1px;
    }
    .profile-card-header {
        background: linear-gradient(135deg, #1b5fb5 0%, #134280 100%);
        color: #ffffff;
        padding: 14px 20px;
        border-radius: 8px 8px 0 0;
    }
    .form-control:focus {
        border-color: #1b5fb5;
        box-shadow: 0 0 0 0.18rem rgba(27, 95, 181, 0.18);
    }
    .btn-save-profile {
        background: linear-gradient(135deg, #1b5fb5, #134280);
        border: none;
        color: #fff;
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .btn-save-profile:hover {
        background: linear-gradient(135deg, #134280, #0f3060);
        color: #fff;
    }
</style>
@endpush

@section('konten')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-1 text-gray-800 font-weight-bold">
                <i class="fas fa-user-circle text-primary mr-2"></i>Profil Akun
            </h1>
            <p class="text-muted small mb-0">Kelola informasi dan keamanan akun Anda</p>
        </div>
    </div>

    @if (session('success_profile'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success_profile') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('success_password'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success_password') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Informasi Akun -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-id-card mr-1"></i> Informasi Akun
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img class="img-profile rounded-circle shadow-sm mb-3"
                             src="{{ asset('sbadmin/img/undraw_profile.svg') }}"
                             style="width: 100px; height: 100px;">
                        <h5 class="font-weight-bold text-gray-800 mb-1">{{ $user->name }}</h5>
                        <p class="text-muted small mb-2">{{ $user->email }}</p>
                        @if($user->role === 'superadmin')
                            <span class="badge badge-danger px-3 py-1 font-weight-bold">Super Admin</span>
                        @elseif($user->role === 'admin')
                            <span class="badge badge-primary px-3 py-1 font-weight-bold">Admin</span>
                        @else
                            <span class="badge badge-secondary px-3 py-1 font-weight-bold">User</span>
                        @endif
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="small font-weight-bold text-gray-700">Nama Lengkap</label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold text-gray-700">Alamat Email</label>
                            <input type="email"
                                   class="form-control bg-light"
                                   value="{{ $user->email }}"
                                   readonly>
                            <small class="text-muted">Email tidak dapat diubah secara langsung.</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan Profil
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ubah Password Langsung -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-key mr-1"></i> Ubah Password
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Gunakan form di bawah ini untuk memperbarui password Anda langsung tanpa perlu meminta link reset email.
                    </p>

                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="small font-weight-bold text-gray-700">Password Saat Ini</label>
                            <input type="password"
                                   name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   placeholder="Masukkan password saat ini"
                                   required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold text-gray-700">Password Baru</label>
                            <input type="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Minimal 8 karakter"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold text-gray-700">Konfirmasi Password Baru</label>
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   placeholder="Ketik ulang password baru"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-success btn-block shadow-sm font-weight-bold">
                            <i class="fas fa-check-circle mr-1"></i> Perbarui Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

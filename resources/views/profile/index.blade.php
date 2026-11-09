@extends('admin.layout')

@section('title', 'Profil Akun — WebGIS BWS Kalimantan I')

@section('content')
<div class="anim-fade-in">

    <!-- Header Judul Halaman -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="font-weight-bold mb-1 text-dark" style="letter-spacing: -0.3px; font-size: 1.25rem;">
                Profil &amp; Keamanan Akun
            </h4>
            <p class="text-muted small mb-0" style="font-size: 0.84rem;">
                Kelola foto identitas, nama pengguna, dan kata sandi akun administrator WebGIS BWS Kalimantan I.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm font-weight-bold px-3 py-2 shadow-sm d-inline-flex align-items-center" style="border-radius: 20px; font-size: 0.82rem; height: 38px;">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Flash Alerts -->
    @if (session('success_profile'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 10px; border-left: 4px solid #10b981;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle mr-2 text-success fa-lg"></i>
                <div><strong>Berhasil!</strong> {{ session('success_profile') }}</div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('success_password'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 10px; border-left: 4px solid #10b981;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle mr-2 text-success fa-lg"></i>
                <div><strong>Berhasil!</strong> {{ session('success_password') }}</div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 10px; border-left: 4px solid #ef4444;">
            <div class="d-flex align-items-center mb-1">
                <i class="fas fa-exclamation-triangle mr-2 text-danger"></i>
                <strong>Perhatian: Terjadi kesalahan input</strong>
            </div>
            <ul class="mb-0 pl-4 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- ═══ KARTU 1: FOTO IDENTITAS & DATA PENGGUNA ═══ -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px; overflow: hidden; background: #ffffff;">
                <div class="card-header py-3 px-4 bg-white border-bottom d-flex align-items-center justify-content-between">
                    <span class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                        <i class="fas fa-id-card text-primary mr-2"></i> Informasi Akun
                    </span>
                    <span class="badge badge-light border text-muted px-2 py-1 small" style="font-size: 0.72rem;">Profil Pribadi</span>
                </div>
                <div class="card-body p-4">
                    @php
                        $words = explode(' ', trim($user->name));
                        $initials = '';
                        foreach (array_slice($words, 0, 2) as $w) {
                            $initials .= strtoupper(mb_substr($w, 0, 1));
                        }
                    @endphp

                    <!-- Form Update Profil & Upload Foto -->
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Avatar Interaktif dengan Tombol Ganti Foto -->
                        <div class="text-center mb-4 pb-2 border-bottom">
                            <div class="position-relative d-inline-block mx-auto mb-3">
                                <!-- Lingkaran Foto / Monogram -->
                                <div id="avatarContainer" 
                                     class="rounded-circle d-flex align-items-center justify-content-center text-white font-weight-bold shadow-sm overflow-hidden" 
                                     style="width: 96px; height: 96px; background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%); font-size: 2.3rem; border: 3px solid rgba(250, 214, 5, 0.95); letter-spacing: 1px;">
                                    @if($user->avatar_url)
                                        <img src="{{ $user->avatar_url }}" alt="Foto {{ $user->name }}" id="avatarImg" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <span id="avatarInitials">{{ $initials ?: 'U' }}</span>
                                        <img src="" alt="Preview" id="avatarImg" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                    @endif
                                </div>

                                <!-- Tombol Kamera Upload Foto -->
                                <label for="avatarInput" 
                                       class="btn btn-sm btn-primary rounded-circle position-absolute shadow-sm d-flex align-items-center justify-content-center" 
                                       style="width: 32px; height: 32px; bottom: 0; right: 0; padding: 0; border: 2px solid #ffffff; background: #1d4ed8; cursor: pointer; transition: all 0.2s ease;"
                                       title="Unggah Foto Profil Baru">
                                    <i class="fas fa-camera fa-xs"></i>
                                </label>
                                <input type="file" name="avatar" id="avatarInput" accept="image/png, image/jpeg, image/jpg, image/webp" style="display: none;" onchange="previewProfilePhoto(this)">
                            </div>

                            <h5 class="font-weight-bold text-dark mb-1" style="font-size: 1.15rem;">{{ $user->name }}</h5>
                            <p class="text-muted small mb-2">{{ $user->email }}</p>

                            <div class="d-flex align-items-center justify-content-center flex-wrap" style="gap: 6px;">
                                @if(strtolower($user->role) === 'superadmin')
                                    <span class="badge border font-weight-bold px-3 py-1" style="font-size: 0.78rem; border-radius: 20px; background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe;">
                                        <i class="fas fa-shield-alt mr-1"></i> Super Admin
                                    </span>
                                @else
                                    <span class="badge border font-weight-bold px-3 py-1" style="font-size: 0.78rem; border-radius: 20px; background: #f0fdf4; color: #15803d; border-color: #bbf7d0;">
                                        <i class="fas fa-user-tie mr-1"></i> Admin Operasional
                                    </span>
                                @endif
                                <span class="badge badge-light border text-muted px-2 py-1" style="font-size: 0.76rem; border-radius: 20px;">
                                    <i class="fas fa-check-circle text-success mr-1"></i> Akun Aktif
                                </span>
                            </div>

                            <small id="photoHint" class="text-info d-none mt-2 font-weight-bold" style="font-size: 0.76rem;">
                                <i class="fas fa-info-circle mr-1"></i> Foto baru dipilih. Klik tombol Simpan di bawah untuk menerapkan.
                            </small>
                        </div>

                        <!-- Input Nama Lengkap -->
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-dark mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px; border-color: #cbd5e1;">
                                        <i class="fas fa-user text-muted fa-xs"></i>
                                    </span>
                                </div>
                                <input type="text"
                                       name="name"
                                       class="form-control font-weight-bold text-dark border-left-0 @error('name') is-invalid @enderror"
                                       style="border-radius: 0 8px 8px 0; border-color: #cbd5e1; height: 42px; font-size: 0.88rem;"
                                       value="{{ old('name', $user->name) }}"
                                       required>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Email Terkunci -->
                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-dark mb-1">Alamat Email Terdaftar</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px; border-color: #cbd5e1;">
                                        <i class="fas fa-envelope text-muted fa-xs"></i>
                                    </span>
                                </div>
                                <input type="email"
                                       class="form-control bg-light text-muted border-left-0"
                                       style="border-radius: 0 8px 8px 0; border-color: #cbd5e1; height: 42px; font-size: 0.88rem; cursor: not-allowed;"
                                       value="{{ $user->email }}"
                                       readonly>
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 0.74rem;">
                                <i class="fas fa-lock mr-1"></i> Email akun dikunci demi integritas keamanan &amp; rekam jejak audit.
                            </small>
                        </div>

                        <button type="submit" 
                                class="btn text-white font-weight-bold btn-block shadow-sm d-flex align-items-center justify-content-center"
                                style="background: #1d4ed8; border-color: #1d4ed8; border-radius: 8px; height: 42px; font-size: 0.88rem; transition: all 0.2s ease;">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan Profil
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ═══ KARTU 2: KEAMANAN & UBAH KATA SANDI ═══ -->
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px; overflow: hidden; background: #ffffff;">
                <div class="card-header py-3 px-4 bg-white border-bottom d-flex align-items-center justify-content-between">
                    <span class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                        <i class="fas fa-key text-primary mr-2"></i> Keamanan &amp; Kata Sandi
                    </span>
                    <span class="badge badge-light border text-muted px-2 py-1 small" style="font-size: 0.72rem;">Proteksi Akun</span>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-4 pb-2 border-bottom" style="line-height: 1.5; font-size: 0.84rem;">
                        Perbarui kata sandi secara berkala untuk menjaga akun dari akses pihak yang tidak berwenang. Password baru wajib memiliki panjang minimal 8 karakter.
                    </p>

                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Password Saat Ini -->
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-dark mb-1">Password Saat Ini <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px; border-color: #cbd5e1;">
                                        <i class="fas fa-lock text-muted fa-xs"></i>
                                    </span>
                                </div>
                                <input type="password"
                                       name="current_password"
                                       id="pwdCurrent"
                                       class="form-control border-left-0 border-right-0 @error('current_password') is-invalid @enderror"
                                       style="border-color: #cbd5e1; height: 42px; font-size: 0.88rem;"
                                       placeholder="Masukkan kata sandi saat ini"
                                       required>
                                <div class="input-group-append">
                                    <button class="btn btn-light border border-left-0 text-muted" type="button" style="border-radius: 0 8px 8px 0; border-color: #cbd5e1;" onclick="togglePasswordVisibility('pwdCurrent', this)">
                                        <i class="fas fa-eye fa-xs"></i>
                                    </button>
                                </div>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Baru -->
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-dark mb-1">Password Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px; border-color: #cbd5e1;">
                                        <i class="fas fa-shield-alt text-muted fa-xs"></i>
                                    </span>
                                </div>
                                <input type="password"
                                       name="password"
                                       id="pwdNew"
                                       class="form-control border-left-0 border-right-0 @error('password') is-invalid @enderror"
                                       style="border-color: #cbd5e1; height: 42px; font-size: 0.88rem;"
                                       placeholder="Minimal 8 karakter"
                                       required>
                                <div class="input-group-append">
                                    <button class="btn btn-light border border-left-0 text-muted" type="button" style="border-radius: 0 8px 8px 0; border-color: #cbd5e1;" onclick="togglePasswordVisibility('pwdNew', this)">
                                        <i class="fas fa-eye fa-xs"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1" style="font-size: 0.74rem;">
                                Gunakan kombinasi huruf besar, huruf kecil, angka, atau simbol.
                            </small>
                        </div>

                        <!-- Konfirmasi Password Baru -->
                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-dark mb-1">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px; border-color: #cbd5e1;">
                                        <i class="fas fa-check-double text-muted fa-xs"></i>
                                    </span>
                                </div>
                                <input type="password"
                                       name="password_confirmation"
                                       id="pwdConfirm"
                                       class="form-control border-left-0 border-right-0"
                                       style="border-color: #cbd5e1; height: 42px; font-size: 0.88rem;"
                                       placeholder="Ketik ulang password baru Anda"
                                       required>
                                <div class="input-group-append">
                                    <button class="btn btn-light border border-left-0 text-muted" type="button" style="border-radius: 0 8px 8px 0; border-color: #cbd5e1;" onclick="togglePasswordVisibility('pwdConfirm', this)">
                                        <i class="fas fa-eye fa-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" 
                                class="btn text-white font-weight-bold btn-block shadow-sm d-flex align-items-center justify-content-center"
                                style="background: #1d4ed8; border-color: #1d4ed8; border-radius: 8px; height: 42px; font-size: 0.88rem; transition: all 0.2s ease;">
                            <i class="fas fa-key mr-2"></i> Perbarui Kata Sandi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function previewProfilePhoto(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById('avatarImg');
                var initials = document.getElementById('avatarInitials');
                if (img) {
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                if (initials) {
                    initials.style.display = 'none';
                }
                var hint = document.getElementById('photoHint');
                if (hint) {
                    hint.classList.remove('d-none');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function togglePasswordVisibility(inputId, btn) {
        var input = document.getElementById(inputId);
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
            btn.classList.add('text-primary');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
            btn.classList.remove('text-primary');
        }
    }
</script>
@endpush
@endsection

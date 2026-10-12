@extends('layout.master')

@section('title', 'Profil - Lupa Password')

@section('konten')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-xl-6 col-lg-7 col-md-9">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header py-3 bg-white border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-lock mr-2 text-primary"></i> Profil Akun - Lupa / Reset Password
                    </h6>
                    @if(Auth::check())
                        <span class="badge badge-light border text-muted px-2 py-1"><i class="fas fa-user-circle mr-1"></i>{{ Auth::user()->name }}</span>
                    @endif
                </div>

                <div class="card-body p-4">

                    @if(Auth::check())
                        <div class="alert alert-info py-2 px-3 small mb-3 border-left-info shadow-sm d-flex align-items-center">
                            <i class="fas fa-info-circle fa-lg mr-2 text-info"></i>
                            <div>
                                Anda sedang masuk sebagai <b>{{ Auth::user()->name }}</b> (<code>{{ Auth::user()->email }}</code>). Masukkan email Anda untuk meminta tautan reset password baru.
                            </div>
                        </div>
                    @else
                        <p class="text-muted small mb-3">
                            Masukkan email yang terdaftar pada akun Anda. Kami akan mengirimkan tautan untuk mengatur ulang password baru Anda.
                        </p>
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>{{ session('status') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i>{{ $errors->first() }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="email" class="small font-weight-bold text-gray-700">
                                <i class="fas fa-envelope mr-1 text-primary"></i> Alamat Email Akun
                            </label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control"
                                value="{{ old('email', Auth::user()->email ?? '') }}"
                                placeholder="Masukkan email Anda"
                                required
                                autofocus
                            >
                        </div>

                        <button type="submit" class="btn btn-primary btn-block font-weight-bold shadow-sm py-2">
                            <i class="fas fa-paper-plane mr-1"></i> Kirim Link Reset Password
                        </button>
                    </form>

                    <div class="mt-4 pt-3 border-top text-center">
                        @if(Auth::check())
                            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
                            </a>
                        @endif
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
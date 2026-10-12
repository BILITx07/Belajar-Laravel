@extends('layout.master')

@section('title', 'Reset Password')

@section('konten')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow mb-4">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Reset Password
                    </h6>
                </div>

                <div class="card-body">

                    <p class="text-muted">
                        Silakan masukkan password baru untuk akun Anda.
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">

                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group">
                            <label for="email">Email</label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control"
                                value="{{ $email ?? old('email') }}"
                                required
                                readonly
                            >
                        </div>

                        <div class="form-group">
                            <label for="password">Password Baru</label>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                placeholder="Masukkan password baru"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">
                                Konfirmasi Password Baru
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                placeholder="Masukkan kembali password baru"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key mr-1"></i>
                            Reset Password
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
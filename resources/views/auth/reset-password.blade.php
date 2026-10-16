<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Atur Ulang Password - Bili SHOP</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('sbadmin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('sbadmin/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-card {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.175) !important;
        }
        .icon-circle-header {
            width: 70px;
            height: 70px;
            background: rgba(40, 167, 69, 0.12);
            color: #28a745;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .form-control-custom {
            height: 48px;
            border-radius: 8px;
            border: 1px solid #d1d3e2;
            padding: 10px 16px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .form-control-custom:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.18);
        }
        .btn-custom {
            height: 48px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .btn-custom:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.35);
        }
    </style>
</head>

<body class="bg-gradient-primary py-4">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8">

                <div class="card o-hidden border-0 shadow-lg my-5 auth-card">
                    <div class="card-body p-4 p-md-5">

                        <div class="text-center mb-4">
                            <div class="icon-circle-header mb-3 shadow-sm">
                                <i class="fas fa-lock-open fa-2x"></i>
                            </div>
                            <h1 class="h4 text-gray-900 font-weight-bold">Atur Ulang Password</h1>
                            <p class="text-muted small">
                                Silakan buat kata sandi baru untuk akun Anda (minimal 8 karakter).
                            </p>
                        </div>

                        {{-- Pesan Error --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                <i class="fas fa-exclamation-triangle mr-2"></i>{{ $errors->first() }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group mb-3">
                                <label for="email" class="small font-weight-bold text-gray-700 mb-1">
                                    <i class="fas fa-envelope text-primary mr-1"></i> Email Akun
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="form-control form-control-custom bg-light"
                                    value="{{ $email ?? old('email') }}"
                                    required
                                    readonly
                                >
                            </div>

                            <div class="form-group mb-3">
                                <label for="password" class="small font-weight-bold text-gray-700 mb-1">
                                    <i class="fas fa-key text-primary mr-1"></i> Password Baru
                                </label>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control form-control-custom @error('password') is-invalid @enderror"
                                    placeholder="Minimal 8 karakter"
                                    required
                                    autofocus
                                >
                            </div>

                            <div class="form-group mb-4">
                                <label for="password_confirmation" class="small font-weight-bold text-gray-700 mb-1">
                                    <i class="fas fa-check-circle text-primary mr-1"></i> Ulangi Password Baru
                                </label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control form-control-custom"
                                    placeholder="Ketik ulang password baru Anda"
                                    required
                                >
                            </div>

                            <button type="submit" class="btn btn-success btn-block btn-custom shadow">
                                <i class="fas fa-save mr-2"></i> Simpan Password Baru
                            </button>
                        </form>

                        <div class="text-center mt-4 pt-3 border-top">
                            <a class="small font-weight-bold text-primary" href="{{ route('login') }}">
                                <i class="fas fa-arrow-left mr-1"></i> Batalkan dan Kembali ke Login
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('sbadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title', 'SISDA & FEWS - BWS Kalimantan I')</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('sbadmin')}}/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('sbadmin')}}/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Leaflet CSS (untuk peta interaktif) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <style>
        :root {
            --pu-navy: #0f3b75;
            --pu-blue: #1e56a0;
            --pu-yellow: #f6b93b;
            --pu-gold: #e58e26;
        }
        .bg-pu-navy {
            background-color: #0d2c54 !important;
        }
        .bg-gradient-pu {
            background: linear-gradient(180deg, #0d2c54 0%, #174d8c 100%) !important;
        }
        .text-pu-yellow {
            color: #f6b93b !important;
        }
        .badge-pulse-warning {
            animation: pulse-warn 1.8s infinite;
        }
        .badge-pulse-danger {
            animation: pulse-danger 1.5s infinite;
        }
        @keyframes pulse-warn {
            0% { box-shadow: 0 0 0 0 rgba(246, 185, 59, 0.7); }
            70% { box-shadow: 0 0 0 8px rgba(246, 185, 59, 0); }
            100% { box-shadow: 0 0 0 0 rgba(246, 185, 59, 0); }
        }
        @keyframes pulse-danger {
            0% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.7); }
            70% { box-shadow: 0 0 0 9px rgba(231, 76, 60, 0); }
            100% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0); }
        }
        .custom-card-hover {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .custom-card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }
        .telemetry-live-dot {
            width: 10px;
            height: 10px;
            background-color: #2ecc71;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 rgba(46, 204, 113, 0.4);
            animation: pulse-green 1.5s infinite;
        }
        @keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7); }
            70% { box-shadow: 0 0 0 7px rgba(46, 204, 113, 0); }
            100% { box-shadow: 0 0 0 0 rgba(46, 204, 113, 0); }
        }
    </style>
    @stack('styles')
</head>



<body id="page-top">

    <!-- Page Wrapper (Full Width Single Navigation) -->
    <div id="wrapper" style="min-height: 100vh;">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column w-100">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('layout.navbar')

                <!-- Begin Page Content -->
                <div class="container-fluid px-3 py-2">
                    @yield('konten')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            @include('layout.footer')

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yakin ingin keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Apakah anda yakin ingin Logout?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('sbadmin')}}/vendor/jquery/jquery.min.js"></script>
    <script src="{{asset('sbadmin')}}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('sbadmin')}}/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('sbadmin')}}/js/sb-admin-2.min.js"></script>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Leaflet JS (untuk peta interaktif) - harus sebelum @stack('scripts') -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    @stack('scripts')

</body>

</html>
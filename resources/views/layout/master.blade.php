<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title', 'WebGIS BWS Kalimantan I — Kementerian Pekerjaan Umum')</title>

    <!-- Favicon Logo PU -->
    <link rel="icon" href="{{ asset('img/logo-pu.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('img/logo-pu.png') }}" type="image/png">

    <!-- Custom fonts for this template-->
    <link href="{{asset('sbadmin')}}/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('sbadmin')}}/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Leaflet CSS (untuk peta interaktif) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <style>
        body, #content-wrapper, input, select, button {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        }
        #content-wrapper {
            background-color: #f8fafc;
        }
        
        :root {
            --pu-navy: #0f3b75;
            --pu-blue: #1e56a0;
            --pu-yellow: #f6b93b;
            --pu-gold: #e58e26;
        }

        /* Scroll to Top - Executive PU Navy & Gold */
        .scroll-to-top {
            background: #10143a !important;
            border-radius: 12px !important;
            border: 1.5px solid rgba(250, 214, 5, 0.45) !important;
            box-shadow: 0 4px 14px rgba(16, 20, 58, 0.25) !important;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1) !important;
            z-index: 1045 !important;
            width: 42px !important;
            height: 42px !important;
            line-height: 38px !important;
            right: 1.25rem !important;
            bottom: 1.25rem !important;
        }
        .scroll-to-top i {
            color: #fad605 !important;
            font-size: 15px !important;
            font-weight: 800 !important;
            transition: transform 0.2s ease !important;
        }
        .scroll-to-top:focus,
        .scroll-to-top:hover {
            background: #1c2262 !important;
            border-color: #fad605 !important;
            box-shadow: 0 6px 20px rgba(16, 20, 58, 0.35) !important;
            transform: translateY(-3px) !important;
            text-decoration: none !important;
        }
        .scroll-to-top:hover i {
            color: #ffffff !important;
            transform: translateY(-2px) !important;
        }

        /* Hilangkan Bendera Attribution Leaflet */
        .leaflet-attribution-flag,
        .leaflet-control-attribution svg {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
            visibility: hidden !important;
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
            <div id="content" class="d-flex flex-column" style="flex: 1;">

                <!-- Topbar -->
                @include('layout.navbar')

                <!-- Begin Page Content -->
                <div class="container-fluid p-0" style="flex: 1; display: flex; flex-direction: column;">
                    @yield('konten')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            @if(!View::hasSection('hide_footer'))
                <!-- Footer -->
                @include('layout.footer')
            @endif

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

    <!-- shpjs: Otomatis membaca & mengonversi file .zip Shapefile (ArcGIS/QGIS) ke GeoJSON -->
    <script src="https://cdn.jsdelivr.net/npm/shpjs@6.2.0/dist/shp.js"></script>

    @stack('scripts')
</body>

</html>
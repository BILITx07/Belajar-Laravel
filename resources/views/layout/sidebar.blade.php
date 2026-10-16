<!-- Sidebar Resmi FEWS & SISDA BWS Kalimantan I -->
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #174e96; z-index: 1010;">

    <!-- Sidebar Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-3" href="{{ route('dashboard') }}" style="height: 60px; background: #134280;">
        <div class="sidebar-brand-icon">
            <i class="fas fa-water text-warning"></i>
        </div>
        <div class="sidebar-brand-text mx-2 text-left">
            <div class="font-weight-bold" style="font-size: 0.88rem; letter-spacing: 0.5px;">FEWS BWSK I</div>
            <div style="font-size: 0.65rem; color: #dbeafe;">Sumber Daya Air</div>
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0" style="border-color: rgba(255,255,255,0.15);">

    <!-- Section Heading -->
    <div class="sidebar-heading text-uppercase text-white-50 mt-2" style="font-size: 0.65rem; letter-spacing: 0.5px;">
        Monitoring & Data
    </div>

    <!-- 1. Realtime Telemetri -->
    <li class="nav-item {{ request()->is('home') || request()->is('/') || request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link py-2" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-broadcast-tower text-warning"></i>
            <span>Realtime Telemetri</span>
        </a>
    </li>

    <!-- 2. Dashboard Data POS Duga Air (FEWS) -->
    <li class="nav-item {{ request()->is('hidrologi-data*') ? 'active' : '' }}">
        <a class="nav-link py-2" href="{{ route('hidrologi.data') }}">
            <i class="fas fa-fw fa-chart-bar text-info"></i>
            <span>Data POS Duga Air</span>
        </a>
    </li>

    <!-- 3. WebGIS Sebaran Aset SDA -->
    <li class="nav-item {{ request()->is('peta*') ? 'active' : '' }}">
        <a class="nav-link py-2" href="{{ route('peta') }}">
            <i class="fas fa-fw fa-map-marked-alt text-success"></i>
            <span>WebGIS Sebaran SDA</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-2" style="border-color: rgba(255,255,255,0.15);">

    @if(Auth::check() && in_array(Auth::user()->role, ['superadmin', 'admin']))
    <div class="sidebar-heading text-uppercase text-white-50" style="font-size: 0.65rem; letter-spacing: 0.5px;">
        Sistem
    </div>

    <li class="nav-item {{ request()->is('users*') ? 'active' : '' }}">
        <a class="nav-link py-2" href="{{ route('users.index') }}">
            <i class="fas fa-fw fa-users-cog text-white-50"></i>
            <span>Manajemen Pengguna</span>
        </a>
    </li>

    <hr class="sidebar-divider my-2" style="border-color: rgba(255,255,255,0.15);">
    @endif

    <!-- Sidebar Toggler Button -->
    <div class="text-center d-none d-md-inline my-2">
        <button class="rounded-circle border-0" id="sidebarToggle" style="background: rgba(255,255,255,0.2);"></button>
    </div>

</ul>
<!-- End of Sidebar -->
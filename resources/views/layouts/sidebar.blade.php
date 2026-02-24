<aside class="app-sidebar bg-dark shadow position-relative" data-bs-theme="dark">

    <div class="sidebar-brand">
        <a href="{{ url('dashboard') }}" class="brand-link text-center">
            <span class="brand-text fw-light">eKos System</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column">

                {{-- DASHBOARD --}}
                <li class="nav-item">
                    <a href="{{ url('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- KAMAR --}}
                <li class="nav-item">
                    <a href="{{ url('/kamar') }}"
                       class="nav-link {{ request()->is('kamar*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-door-open"></i>
                        <p>Manajemen Kamar</p>
                    </a>
                </li>

                {{-- PENGHUNI --}}
                <li class="nav-item">
                    <a href="{{ url('/penghuni') }}"
                       class="nav-link {{ request()->is('penghuni*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Manajemen Penghuni</p>
                    </a>
                </li>

                {{-- PEMBAYARAN --}}
                <li class="nav-item">
                    <a href="{{ url('/pembayaran') }}"
                       class="nav-link {{ request()->is('pembayaran*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-cash-coin"></i>
                        <p>Manajemen Pembayaran</p>
                    </a>
                </li>

                {{-- LAPORAN --}}
                <li class="nav-item">
                    <a href="{{ route('laporan.index') }}"
                       class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-bar-chart-line"></i>
                        <p>Rekapitulasi Laporan</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>

    {{-- LOGOUT --}}
    <div class="position-absolute bottom-0 w-100 p-3 border-top">
        <a href="{{ url('/logout') }}" class="nav-link text-danger">
            <i class="nav-icon bi bi-box-arrow-right"></i>
            <p class="d-inline ms-2"><strong>Logout</strong></p>
        </a>
    </div>

</aside>

<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#">
                    <i class="bi bi-list"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ asset('assets/assets/img/avatar.png') }}"
                         class="user-image rounded-circle shadow"
                         alt="User">
                    <span class="d-none d-md-inline">
                        {{ session('admin_nama') ?? 'Admin' }}
                    </span>
                </a>

                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="user-footer text-center p-3">
                        <a href="{{ url('/logout') }}" class="btn btn-danger btn-sm">
                            Logout
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

    </div>
</nav>

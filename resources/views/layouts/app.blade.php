<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'eKos Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.css') }}">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        /* Active menu styling */
        .nav-link.active {
            background-color: rgba(13,110,253,0.15) !important;
            border-left: 4px solid #0d6efd;
            font-weight: 600;
        }

        .nav-link.active .nav-icon {
            color: #0d6efd !important;
        }

        .nav-link {
            border-left: 4px solid transparent;
            transition: all 0.2s ease;
        }
        </style>

</head>

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">

<div class="app-wrapper">

    {{-- ================= HEADER ================= --}}
    @include('layouts.header')
    {{-- ========================================== --}}


    {{-- ================= SIDEBAR ================= --}}
    @include('layouts.sidebar')
    {{-- =========================================== --}}


    {{-- ================= MAIN CONTENT ================= --}}
    <main class="app-main">

        <!-- Content Header -->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">@yield('page-title')</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Body -->
        <div class="app-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

    </main>
    {{-- =============================================== --}}


    {{-- ================= FOOTER ================= --}}
    @include('layouts.footer')
    {{-- ========================================== --}}

</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}
<!-- jQuery (WAJIB untuk DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ asset('assets/js/adminlte.js') }}"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonColor: '#0d6efd'
            });
        @endif

        @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#dc3545'
        });
        @endif

    });
</script>

@stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Aplikasi Gudang & Inventory">
    <meta name="author" content="Kelompok Kami">

    <title>Sistem Gudang - @yield('title', 'Admin')</title>

    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Sedikit perbaikan agar tabel lebih rapi */
        .sidebar-item.active .sidebar-link:hover { background: rgba(255, 255, 255, 0.087); }
    </style>
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="{{ route('dashboard') }}">
                    <span class="align-middle">Sistem Gudang</span>
                </a>

                <ul class="sidebar-nav">
                    <li class="sidebar-header">Utama</li>

                    <li class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('dashboard') }}">
                            <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
                        </a>
                    </li>

                    @if(auth()->user()->role === 'admin')
                    <li class="sidebar-header">Admin Area</li>

                    <li class="sidebar-item {{ request()->is('users*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('users.index') }}">
                            <i class="align-middle" data-feather="users"></i> <span class="align-middle">Kelola Akun</span>
                        </a>
                    </li>
                    @endif

                    <li class="sidebar-header">Master Data</li>

                    <li class="sidebar-item {{ request()->is('items*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="#">
                            <i class="align-middle" data-feather="package"></i> <span class="align-middle">Data Barang</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="#">
                            <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Kategori</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="#">
                            <i class="align-middle" data-feather="truck"></i> <span class="align-middle">Supplier</span>
                        </a>
                    </li>

                    <li class="sidebar-header">Transaksi</li>

                    <li class="sidebar-item">
                        <a class="sidebar-link" href="#">
                            <i class="align-middle" data-feather="arrow-down-circle"></i> <span class="align-middle">Barang Masuk</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="#">
                            <i class="align-middle" data-feather="arrow-up-circle"></i> <span class="align-middle">Barang Keluar</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                                <i class="align-middle" data-feather="settings"></i>
                            </a>

                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                                <span class="text-dark">Halo, {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Log out</button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content">
                <div class="container-fluid p-0">
                    @yield('content')
                </div>
            </main>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <strong>Sistem Gudang</strong> &copy; 2025
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    @yield('scripts')
</body>

</html>

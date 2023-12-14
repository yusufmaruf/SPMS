<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/branding/logo.png') }}" alt="" width="32px" height="22px"
                    srcset="">
            </span>
            <span class="app-brand-text demo menu-text fw-bold">SPMS</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item ">
            <a href="/" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="dashboard">Dashboard</div>
            </a>
        </li>

        @if (Auth::user()->role == 'admin')
            <li class="menu-item open">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-api-app"></i>
                    <div data-i18n="Sistem Cerdas"> Sistem Cerdas</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item ">
                        <a href="" class="menu-link">
                            <div data-i18n="ManajemenStok">Manajemen Stok</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="dashboards-crm.html" class="menu-link">
                            <div data-i18n="Forecasting">Forecasting</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif



        <!-- Apps & Pages -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Master Data</span>
        </li>
        <li class="menu-item {{ request()->is('product*') ? 'active' : '' }}">
            <a href="{{ route('product.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-paper-bag"></i>
                <div data-i18n="Produk">Produk</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('stok.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-fridge"></i>
                <div data-i18n="Stok Bahan Baku">Stok Bahan Baku</div>
            </a>
        </li>

        @if (Auth::user()->role == 'admin')
            <li class="menu-item {{ request()->is('bahanbaku*') ? 'active' : '' }}">
                <a href="{{ route('bahanbaku.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-cookie"></i>
                    <div data-i18n="Bahan Baku">Bahan Baku</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('resep*') ? 'active' : '' }}">
                <a href="{{ route('resep.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-flask"></i>
                    <div data-i18n="Resep">Resep</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('cabang*') ? 'active' : '' }}">
                <a href="{{ route('cabang.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-map-pin"></i>
                    <div data-i18n="Cabang">Cabang</div>
                </a>
            </li>
        @endif



        <!-- Components -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Transaksi</span>
        </li>
        <li class="menu-item">
            <a href="{{ route('penjualan.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
                <div data-i18n="Penjualan">Penjualan Cabang</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('penjualan.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-download"></i>
                <div data-i18n="Pembelian">Pembelian</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="app-kanban.html" class="menu-link">
                <i class="menu-icon tf-icons ti ti-truck"></i>
                <div data-i18n="Pasok Bahan Baku">Pasok Bahan Baku</div>
            </a>
        </li>


        <!-- Forms & Tables -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Laporan Penjualan</span>
        </li>
        <li class="menu-item">
            <a href="tables-basic.html" class="menu-link">
                <i class="menu-icon tf-icons ti ti-report-money"></i>
                <div data-i18n="Laporan Pengeluran">Laporan Pengeluran</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="tables-basic.html" class="menu-link">
                <i class="menu-icon tf-icons ti ti-report-analytics"></i>
                <div data-i18n="Laporan Pemansukan">Laporan Pemasukan</div>
            </a>
        </li>
    </ul>
</aside>

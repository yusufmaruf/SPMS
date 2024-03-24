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
        <li class="menu-item  {{ request()->is('dashboard*') ? 'active' : '' }}">
            <a href="/dashboard" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Dashboard"></div>
            </a>
        </li>
        @if (Auth::user()->role == 'admin')
            <li
                class="menu-item {{ request()->is('admin/manajemenstok*') || request()->is('admin/forecast*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-api-app"></i>
                    <div data-i18n="Sistem Cerdas"> Sistem Cerdas</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('admin/manajemenstok*') ? 'active' : '' }} ">
                        <a href="{{ route('manajemenstok.index') }}" class="menu-link">
                            <div data-i18n="ManajemenStok">Manajemen Stok</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('admin/forecast*') ? 'active' : '' }}">
                        <a href="{{ route('forecast.index') }}" class="menu-link">
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

        <li class="menu-item  {{ request()->is('stok*') ? 'active' : '' }}">
            <a href="{{ route('stok.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-fridge"></i>
                <div data-i18n="Stok Bahan Baku">Stok Bahan Baku</div>
            </a>
        </li>

        @if (Auth::user()->role == 'admin')
            <li class="menu-item {{ request()->is('admin/bahanbaku*') ? 'active' : '' }}">
                <a href="{{ route('bahanbaku.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-cookie"></i>
                    <div data-i18n="Bahan Baku">Bahan Baku</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('admin/cabang*') ? 'active' : '' }}">
                <a href="{{ route('cabang.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-map-pin"></i>
                    <div data-i18n="Cabang">Cabang</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('pengguna*') ? 'active' : '' }}">
                <a href="{{ route('pengguna.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div data-i18n="Karyawan">Karyawan</div>
                </a>
            </li>
        @endif
        <li class="menu-item {{ request()->is('resep*') ? 'active' : '' }}">
            <a href="{{ route('resep.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-flask"></i>
                <div data-i18n="Resep">Resep</div>
            </a>
        </li>



        <!-- Components -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Transaksi</span>
        </li>
        @if (Auth::user()->role == 'user')
            <li class="menu-item {{ request()->is('penjualan*') ? 'active' : '' }}">
                <a href="{{ route('penjualan.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
                    <div data-i18n="Penjualan">Penjualan Cabang</div>
                </a>
            </li>
        @endif
        <li class="menu-item {{ request()->is('pembelian*') ? 'active' : '' }}">
            <a href="{{ route('pembelian.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
                <div data-i18n="Purchase">Purchase Cabang</div>
            </a>
        </li>
        {{-- <li class="menu-item">
            <a href="{{ route('penjualan.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-download"></i>
                <div data-i18n="Pembelian">Pembelian</div>
            </a>
        </li> --}}
        {{-- <li class="menu-item">
            <a href="app-kanban.html" class="menu-link">
                <i class="menu-icon tf-icons ti ti-truck"></i>
                <div data-i18n="Pasok Bahan Baku">Pasok Bahan Baku</div>
            </a>
        </li> --}}


        <!-- Forms & Tables -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Laporan Penjualan</span>
        </li>
        @if (Auth::user()->role == 'user')
            <li class="menu-item  {{ request()->is('reportpurchase*') ? 'active' : '' }}">
                <a href="{{ route('reportpurchase.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-report-money"></i>
                    <div data-i18n="Laporan Pengeluran">Laporan Pengeluran</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('laporanpenjualan*') ? 'active' : '' }}">
                <a href="{{ route('laporanpenjualan.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-report-analytics"></i>
                    <div data-i18n="Laporan Pemasukan">Laporan Pemasukan</div>
                </a>
            </li>
        @elseif (Auth::user()->role == 'admin' || Auth::user()->role == 'manager')
            <li class="menu-item  {{ request()->is('adminReportPurchase*') ? 'active' : '' }}">
                <a href="{{ route('adminReportPurchase.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-report-money"></i>
                    <div data-i18n="Laporan Pengeluran">Laporan Pengeluran</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('adminReportSales*') ? 'active' : '' }}">
                <a href="{{ route('adminReportSales.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-report-analytics"></i>
                    <div data-i18n="Laporan Pemasukan">Laporan Pemasukan</div>
                </a>
            </li>
        @endif
    </ul>
</aside>

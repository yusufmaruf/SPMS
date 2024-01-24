@extends('layouts.master')

<!-- Vendors CSS -->
@push('style')
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <!-- Row Group CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Sistem Cerdas /</span> Metode Min - Max</h4>
        <!-- DataTable with Buttons -->
        <div class="card mb-5">
            <div class="card-datatable table-responsive pt-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Metode Min-Max Produk</h5>
                    <p>Tanggal awal : {{ $tanggalAwal }}</p>
                    <p>Tanggal akhir : {{ $tanggalAkhir }}</p>
                </div>
                <table class="table MinMaxProduk table-dt">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>nama</th>
                            <th>Total Permintaan</th>
                            <th>Rata-Rata</th>
                            <th>Safety</th>
                            <th>Minimum</th>
                            <th>Maximum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp

                        @foreach ($processedData as $item)
                            <tr>
                                <td>
                                    {{ $no++ }}
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->max_quantity }}</td>
                                <td>{{ $item->AVG_quantity }}</td>
                                <td>{{ round($item->safetystock) }}</td>
                                <td>{{ round($item->minimumStock) }}</td>
                                <td>{{ round($item->maximumStock) }}</td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Metode Min-Max Bahan Baku</h5>
                    <p>Tanggal awal : {{ $tanggalAwal }}</p>
                    <p>Tanggal akhir : {{ $tanggalAkhir }}</p>
                </div>
                <table class="table MinMaxProduk table-dt">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>nama</th>
                            <th>cabang</th>
                            <th>Total Permintaan</th>
                            <th>Rata-Rata</th>
                            <th>Safety</th>
                            <th>Minimum</th>
                            <th>Maximum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp

                        @foreach ($processedData2 as $item)
                            <tr>
                                <td>
                                    {{ $no++ }}
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->cabang }}</td>
                                <td>{{ $item->total_quantity }}</td>
                                <td>{{ $item->AVG_quantity }}</td>
                                <td>{{ round($item->safetystock) }}</td>
                                <td>{{ round($item->minimumStock) }}</td>
                                <td>{{ round($item->maximumStock) }}</td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('script')
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.js"></script>


    <script>
        // Declare table as a global variable

        $(document).ready(function() {
            $('.MinMaxProduk').DataTable({

            });
        });
    </script>
@endpush

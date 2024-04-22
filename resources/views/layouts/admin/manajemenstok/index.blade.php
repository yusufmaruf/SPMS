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
        <div class="card mb-4 ">
            <div class="accordion-item ">
                <div class="accordion-header px-4 mt-4 d-flex justify-content-between" id="accordionIconOne">
                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#accordionIcon-minmax" aria-controls="accordionIcon-minmax" aria-expanded="false">
                        <h4> Metode Min Max</h4>
                    </button>
                </div>
                <div id="accordionIcon-minmax" class="accordion-collapse collapse " data-bs-parent="#accordionIcon"
                    style="">
                    <p class="accordion-body px-4 mb-4">
                        Metode Min Min-Max merupakan sebuah metode untuk mengontrol suatu persediaan dengan menentukan
                        safety stock untuk mencegah kekurangan persediaan suatu barang.
                    </p>
                </div>
            </div>
        </div>

        <div class="card mb-4 ">
            <div class="accordion-item ">
                <div class="accordion-header px-4 mt-4 d-flex justify-content-between" id="accordionIconOne">
                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#accordionIcon-safetystock" aria-controls="accordionIcon-safetystock"
                        aria-expanded="false">
                        <h4>Safety Stock </h4>
                    </button>
                </div>
                <div id="accordionIcon-safetystock" class="accordion-collapse collapse " data-bs-parent="#accordionIcon"
                    style="">
                    <p class="accordion-body px-4 mb-2">
                        Safety Stock yaitu sejumlah stok tambahan yang disimpan di luar stok minimum untuk mengantisipasi
                        ketidakpastian dalam permintaan atau pasokan. Rumus menghitung safety Stock sebagai berikut :
                        <br>
                    </p>
                    <p class="accordion-body px-4 mb-4"> <strong>SS= (Permintaan maksimum-T)×LT <br></strong>
                        Keterangan : <br>
                        T = rata-rata penjualan <br>
                        LT= lead time (hari) <br>
                        SS = safety stock
                    </p>
                </div>
            </div>
        </div>

        <div class="card mb-4 ">
            <div class="accordion-item ">
                <div class="accordion-header px-4 mt-4 d-flex justify-content-between" id="accordionIconOne">
                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#accordionIcon-maximum" aria-controls="accordionIcon-maximum" aria-expanded="false">
                        <h4>Maximum Stock </h4>
                    </button>
                </div>
                <div id="accordionIcon-maximum" class="accordion-collapse collapse " data-bs-parent="#accordionIcon"
                    style="">
                    <p class="accordion-body px-4 mb-2">
                        Maximum Stock merupakan jumlah maksimum produk yang diperbolehkan untuk disimpan sebagai
                        persediaan . Rumus untuk menghitung Maximum Stock adalah sebagai berikut :
                        <br>
                    </p>
                    <p class="accordion-body px-4 mb-4"> <strong>Maximum Stok = 2 (T x LT) <br></strong>
                        Keterangan : <br>
                        T = rata-rata penjualan <br>
                        LT= lead time (hari) <br>
                        SS = safety stock
                    </p>
                </div>
            </div>
        </div>

        <div class="card mb-4 ">
            <div class="accordion-item ">
                <div class="accordion-header px-4 mt-4 d-flex justify-content-between" id="accordionIconOne">
                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#accordionIcon-Minimum" aria-controls="accordionIcon-Minimum" aria-expanded="false">
                        <h4>Minimum Stock </h4>
                    </button>
                </div>
                <div id="accordionIcon-Minimum" class="accordion-collapse collapse " data-bs-parent="#accordionIcon"
                    style="">
                    <p class="accordion-body px-4 mb-2">
                        Minimum Stock merupakan jumlah miniman produk yang harus ada di penyimpanan. Rumus untuk
                        menghitung Minimum Stock adalah sebagai berikut :
                        <br>
                    </p>
                    <p class="accordion-body px-4 mb-4"> <strong>Minimum Stok = (T x LT ) + SS <br></strong>
                        Keterangan : <br>
                        T = rata-rata penjualan <br>
                        LT= lead time (hari) <br>
                        SS = safety stock
                    </p>
                </div>
            </div>
        </div>

        <div class="card mb-4 ">
            <div class="accordion-item ">
                <div class="accordion-header px-4 mt-4 d-flex justify-content-between" id="accordionIconOne">
                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#accordionIcon-manajemen" aria-controls="accordionIcon-manajemen"
                        aria-expanded="false">
                        <h4>Informasi Detail Manajemen Stok </h4>
                    </button>
                </div>
                <div id="accordionIcon-manajemen" class="accordion-collapse collapse " data-bs-parent="#accordionIcon"
                    style="">
                    <form id="basic-form" method="get"
                        action="{{ route('manajemenstok.show', ['manajemenstok' => 'idCabang']) }}">
                        <div class="mb-3 px-4">
                            <label for="nameCabang" class="form-label">Pilih Cabang</label>
                            <select name="idCabang" id="select2basicCabang"
                                class="select2 form-select form-select form-control mb-2">
                                @foreach ($cabang as $item)
                                    <option value="{{ $item->idCabang }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary w-100 mb-3 mt-4">Submit</button>

                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-datatable table-responsive pt-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Metode Min-Max Produk </h5>
                    <p>Untuk Tanggal : {{ $tanggalAwal->format('d/m/Y') }} - {{ $tanggalAkhir->format('d/m/Y') }}</p>
                </div>
                <table class="table MinMaxProduk table-dt">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>nama</th>
                            <th>Total Permintaan Sebelumnya</th>
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
                                <td>{{ $item->totalPermintaanSebelumnya }}</td>
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

        <div class="card mb-5">
            <div class="card-datatable table-responsive pt-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Metode Min-Max Bahan Baku</h5>
                    <p>Untuk Tanggal : {{ $tanggalAwal->format('d/m/Y') }} - {{ $tanggalAkhir->format('d/m/Y') }}</p>
                </div>
                <table class="table MinMaxProduk table-dt">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>nama</th>
                            <th>Total Permintaan</th>
                            <th>Permintaan Maximal</th>
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
                                <td>{{ $item->totalPermintaanSebelumnya }}</td>
                                <td>{{ $item->maximumpermintaan }}</td>
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

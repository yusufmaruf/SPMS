@extends('layouts.master')

<!-- Vendors CSS -->
@push('style')
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">

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
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Prediksi /</span> Metode Least Square</h4>
        <div class="card mb-4 ">
            <div class="accordion-item ">
                <div class="accordion-header px-4 mt-4 d-flex justify-content-between" id="accordionIconOne">
                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#accordionIcon-least" aria-controls="accordionIcon-least" aria-expanded="false">
                        <h4> Least Square</h4>
                    </button>
                </div>
                <div id="accordionIcon-least" class="accordion-collapse collapse " data-bs-parent="#accordionIcon"
                    style="">
                    <p class="accordion-body px-4 mb-4">
                        Least Square adalah metode peramalan yang digunakan untuk mengamati tren dalam data deret waktu atau
                        berkala. Metode ini membutuhkan data penjualan masa lalu untuk melakukan peramalan penjualan di masa
                        mendatang untuk menentukan hasilnya. Formulanya sebagai berikut :
                    </p>
                    <div class="text-center"> <!-- Added class "text-center" here -->
                        <img src="{{ asset('assets/img/leastsquare.png') }}" class="" alt="">
                    </div>
                    <p class="accordion-body px-4 mb-4">
                        Dalam Penentuan nilai x seringkali menggunakan teknik alternatif dengan memberikan skor. Dalam hal
                        ini dilakukan pembagian data menjadi dua kelompok yaitu:
                    <ul>
                        <li>Jika banyak data genap maka berlaku penambahan dua : ..,-3,-1,1,3,5,…</li>
                        <li>Jika banyak data ganjil : ..,-3,-2,-1,0,1,2,3,…</li>
                    </ul>
                    <p class="accordion-body px-4">
                        Keterangan :
                        <br>
                        Y = Jumlah Penjualan
                        <br>
                        a = Nilai trend penjualan.
                        <br>
                        b = Rata–rata pertumbuhan nilai trend
                        <br>
                        x = variable waktu
                    </p>

                </div>
            </div>
        </div>
        <div class="card mb-4 ">
            <div class="accordion-item ">
                <div class="accordion-header px-4 mt-4 d-flex justify-content-between" id="accordionIconOne">
                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#accordionIcon-1" aria-controls="accordionIcon-1" aria-expanded="false">
                        <h4> MAPE (Mean Absolute Percentage Error)</h4>
                    </button>
                </div>
                <div id="accordionIcon-1" class="accordion-collapse collapse " data-bs-parent="#accordionIcon"
                    style="">
                    <p class="accordion-body px-4 mb-4">
                        Metrik yang digunakan untuk mengevaluasi kinerja model peramalan atau prediksi dengan
                        membandingkan
                        nilai aktual dengan nilai yang diprediksi dan menghitung rata-rata persentase kesalahan
                        absolut dari
                        nilai-nilai tersebut. Formulanya sebagai berikut :
                    </p>
                    <div class="text-center"> <!-- Added class "text-center" here -->
                        <img src="{{ asset('assets/img/mape.jpg') }}" class="" alt="">
                    </div>
                    <p class="accordion-body px-4 mb-4">
                        Setelah itu dilakukan analisis rata-rata kesalahan absolut dari kesalahan prediksi sesuai
                        dengan
                        tabel berikut : </p>
                    <table class="table table-responsive table-bordered p-4 mb-4">
                        <thead class="table-success">
                            <tr>
                                <th>Range Mape</th>
                                <th>Arti Nilai</th>
                            <tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> 0 - 10%</td>
                                <td>Kemampuan Peramalan Sangat Baik</td>
                            </tr>
                            <tr>
                                <td> 10 - 20%</td>
                                <td>Kemampuan Peramalan Cukup Baik</td>
                            </tr>
                            <tr>
                                <td> 20 - 50%</td>
                                <td>Kemampuan Peramalan Layak</td>
                            </tr>
                            <tr>
                                <td> > 50%</td>
                                <td>Kemampuan Peramalan Buruk</td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="accordion-body px-4">
                        Dari tabel tersebut kita bisa memahami rentang nilai yang menunjukkan arti nilai persentase
                        error
                        pada MAPE, dimana nilai MAPE masih bisa digunakan apabila tidak melebihi 50%, jika nilai
                        MAPE sudah
                        di atas 50% maka model peramalan tersebut tidak bisa digunakan

                    </p>

                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="accordion-item ">
                <div class="accordion-header px-4 mt-4 d-flex justify-content-between" id="accordionIconOne">
                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#accordionIcon-Data" aria-controls="accordionIcon-Data " aria-expanded="false">
                        <h4> Data Prediksi Sebelumnya</h4>
                    </button>
                </div>
                <div id="accordionIcon-Data" class="accordion-collapse collapse " data-bs-parent="#accordionIcon"
                    style="">
                    <div class="card-datatable table-responsive pt-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <p class="card-title mb-0">Berikut merupakan analisis data hasil peramalan sebelumnya</p>

                        </div>

                        @if (is_array($result) || is_object($result))
                            <table class="table MinMaxProduk table-dt">
                                <thead>
                                    <tr>
                                        <th>Product ID</th>
                                        <th>Week</th>
                                        <th>Predicted Quantity</th>
                                        <th>Actual Quantity</th>
                                        <th>MAPE (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($result as $productId => $productResult)
                                        @if (is_array($productResult) || is_object($productResult))
                                            @foreach ($productResult as $prediction)
                                                <tr>
                                                    <td>{{ $prediction['nameProduk'] }}</td>
                                                    <td>{{ $prediction['minggu_ke'] }}</td>
                                                    <td>{{ $prediction['predicted'] }}</td>
                                                    <td>{{ $prediction['actual'] }}</td>
                                                    <td>{{ $prediction['mape'] }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <h2 class="text-center">Average MAPE: {{ $result['average_mape'] }} %</h2>
                        @else
                            <p>No prediction results available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 ">
            <div class="accordion-item ">
                <div class="accordion-header px-4 mt-4 d-flex justify-content-between" id="accordionIconOne">
                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#accordionIcon-meramal" aria-controls="accordionIcon-meramal" aria-expanded="false">
                        <h4> Prediksi Detail Penjualan</h4>
                    </button>
                </div>
                <div id="accordionIcon-meramal" class="accordion-collapse collapse " data-bs-parent="#accordionIcon"
                    style="">
                    <p class="accordion-body px-4">Lihat Detail dan Hasil peramalan</p>
                    <form id="basic-form" method="get"
                        action="{{ route('forecast.show', ['forecast' => 'idProduct', 'idCabang' => 'idCabang']) }}">
                        @csrf <!-- Include CSRF token for Laravel form submission -->
                        <div class="mb-3 px-4">
                            <label for="name" class="form-label">Name Product</label>
                            <select name="idProduct" id="select2basicproduct"
                                class="select2 form-select form-select form-control mb-3">
                                @foreach ($products as $item)
                                    <option value="{{ $item->idProduct }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <label for="nameCabang" class="form-label">Pilih Cabang</label>
                            <select name="idCabang" id="select2basicCabang"
                                class="select2 form-select form-select form-control mb-3">
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






    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.js"></script>


    <script>
        // Declare table as a global variable

        $(document).ready(function() {
            $('.MinMaxProduk').DataTable({});
            $('#select2basicproduct').select2();
            $('#select2basicCabang').select2();

        });
        // document.getElementById('basic-form').addEventListener('submit', function(event) {
        //     // Prevent the default form submission behavior
        //     event.preventDefault();

        //     // Get the selected product ID
        //     var selectedProductId = document.getElementById('select2basicproduct').value;

        //     // Construct the URL based on the selected product ID
        //     var url = "{{ route('forecast.show', ['forecast' => ':id']) }}";
        //     url = url.replace(':id', selectedProductId);

        //     // Redirect the user to the constructed URL
        //     window.location.href = url;
        // });
    </script>
@endpush

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

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Prediksi /</span> Metode Least Square</h4>
        <div class="card mb-4 ">
            <div class="px-4 py-4">
                <h3>Grafik peramalan dan data aktual</h3>
                <div id="chartprediksi">

                </div>
                <h4>Peramalan Produk Masa Lalu</h4>
                @if ($data['average_mape'] > 0)
                    @if (is_array($data) || is_object($data))
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
                                @foreach ($data as $productId => $productResult)
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
                        @if ($data['average_mape'] > 0)
                            <h2 class="text-center">Average MAPE: {{ $data['average_mape'] }} %</h2>
                        @else
                            <h2 class="text-center">No prediction results available.</h2>
                        @endif
                    @else
                        <p>No prediction results available.</p>
                    @endif
                @else
                    <p class="text-center">No prediction results available.</p>
                @endif
                <h4>Peramalan 3 Minggu Selanjutnya</h4>
                @if ($data['average_mape'] > 0)
                    {{-- @dd($prediksi); --}}
                    @if (is_array($prediksi) || is_object($prediksi))
                        <table class="table prediction table-dt">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Tanggal Prediksi</th>
                                    <th>Prediksi Penjualan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prediksi as $productId => $productResult)
                                    <tr>
                                        <td>{{ $productResult['nameProduk'] }}</td>
                                        <td>{{ $productResult['awalminggu1'] }}</td>
                                        <td>{{ $productResult['minggu1'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $productResult['nameProduk'] }}</td>
                                        <td>{{ $productResult['awalminggu2'] }}</td>
                                        <td>{{ $productResult['minggu2'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $productResult['nameProduk'] }}</td>
                                        <td>{{ $productResult['awalminggu3'] }}</td>
                                        <td>{{ $productResult['minggu3'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No prediction results available.</p>
                    @endif
                @else
                    <p class="text-center">No prediction results available.</p>
                @endif
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
            $('.prediction').DataTable({});
            $('#select2basicproduct').select2();

            var url = window.location.pathname;
            var urlParts = url.split('/');
            var id = urlParts[urlParts.length - 1];
            var url = '/data-chart/' + id;

            console.log(url);
        });

        document.getElementById('basic-form').addEventListener('submit', function(event) {
            // Prevent the default form submission behavior
            event.preventDefault();

            // Get the selected product ID
            var selectedProductId = document.getElementById('select2basicproduct').value;

            // Construct the URL based on the selected product ID
            var url = "{{ route('forecast.show', ['forecast' => ':id']) }}";
            url = url.replace(':id', selectedProductId);

            // Redirect the user to the constructed URL
            window.location.href = url;
        });
    </script>
    <script>
        $(document).ready(function() {
            var url = window.location.pathname;
            var urlParts = url.split('/');
            var id = urlParts[urlParts.length - 1];
            var url = '/data-chart/' + id;
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Panggil fungsi untuk membuat chart di sini dengan data yang diterima
                    createChart(response.weeks, response.predictions, response.actuals);
                    console.log(response.categories);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });

        })

        function createChart(categories, predictedData, actualData) {
            Highcharts.chart('chartprediksi', {
                title: {
                    text: 'Prediksi dan Aktual'
                },
                xAxis: {
                    categories: categories
                },
                yAxis: {
                    title: {
                        text: 'Nilai'
                    }
                },
                series: [{
                    name: 'Prediksi',
                    data: predictedData
                }, {
                    name: 'Aktual',
                    data: actualData
                }]
            });
        }
    </script>
@endpush

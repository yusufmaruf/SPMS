@extends('layouts.master')
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
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Data Purchase /</span> Data Purchase</h4>
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Data Purchase
                    </h5>
                </div>
                <form id="form-filter" class="m-3" method="get">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" id="dari" name="dari" class="form-control flatpickr"
                                value="" />
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" id="sampai" name="sampai" class="form-control flatpickr"
                                value="" />
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 mt-4">Filter</button>
                        </div>
                    </div>
                </form>
                <a id="download-link" class="btn btn-vimeo d-flex m-4" href="#">Download Laporan</a>
                <table class="datatables-basic table">
                    <thead>
                        <tr>
                            <th width="10%">No</th>
                            <th>Tanggal</th>
                            <th>transaksi</th>
                            <th>cabang</th>
                            <th>User</th>
                            <th>total</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @includeIf('layouts.admin.Purchase.modal')
@endsection

@push('script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script>
        $(document).ready(function() {
            let table = $('.datatables-basic').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('reportpurchase.data') }}',
                    data: function(d) {
                        d.dari = $('#dari').val(); // Ambil nilai tanggal dari input "dari"
                        d.sampai = $('#sampai').val(); // Ambil nilai tanggal dari input "sampai"
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                    }, {
                        data: 'tanggal',
                    }, {
                        data: 'name',
                    },
                    {
                        data: 'cabang',
                    },
                    {
                        data: 'user',
                    },
                    {
                        data: 'total',
                    }
                ],
            });

            $('#form-filter').on('submit', function(event) {
                event.preventDefault();
                table.ajax.reload();

                // Setelah menekan tombol "Filter", buatlah tautan unduh laporan yang sesuai dengan tanggal yang telah dipilih
                let dari = $('#dari').val();
                let sampai = $('#sampai').val();
                let downloadLink = '{{ route('reportpurchase.print') }}?dari=' + dari + '&sampai=' +
                sampai;
                $('#download-link').attr('href',
                downloadLink); // Atur href tautan unduhan dengan URL yang sesuai
            });
        });
    </script>
@endpush

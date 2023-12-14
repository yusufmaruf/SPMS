@extends('layouts.master')
@push('style')
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/summernote/summernote-bs4.min.css') }}">
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
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Sale /</span> Buat Penjualan</h4>
        <!-- DataTable with Buttons -->
        <div class="card mb-3">
            <div class="card-body">

                <div class="mb-3">
                    <label for="name" class="form-label"> Name Product </label>
                    <select name="idProduct" id="select2basicproduct" class="select2 form-select form-select form-control">
                        @foreach ($product as $item)
                            <option value="{{ $item->idProduct }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="quantity" id="quantity" class="form-control">
                </div>
                <div class="mb-3">
                    <button type="submit" onclick="addProduct('{{ route('cart.store') }}')"
                        class="btn btn-primary  w-100 mb-3">Tambahkan</button>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-datatable table-responsive pt-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Cart</h5>
                </div>
                <table class="table table-dt">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th width="10%">aksi</th>
                        </tr>
                    </thead>
                    <tbody>


                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header text-center">
                <h4 class="card-title mb-0">Total Bayar</h4>
                <h2 class="JumlahBayar text-danger"></h2>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('penjualan.store') }}" id="basic-form" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label mb-3">Pilih Metode Pembayaran</label>

                        <div class="row gy-3 mb-4">
                            <div class="col-md">
                                <div class="form-check custom-option custom-option-icon">
                                    <label class="form-check-label custom-option-content" for="customRadioIcon1">
                                        <span class="custom-option-body">
                                            <i class="ti ti-cash"></i>
                                            <span class="custom-option-title"> Pembayaran Cash </span>
                                            <small> Pembayaran Tunai </small>
                                        </span>
                                        <input name="payment" class="form-check-input" type="radio" id="customRadioIcon1"
                                            value="cash" />
                                    </label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-check custom-option custom-option-icon">
                                    <label class="form-check-label custom-option-content" for="customRadioIcon2">
                                        <span class="custom-option-body">
                                            <i class="ti ti-credit-card"></i>
                                            <span class="custom-option-title"> Qris </span>
                                            <small>Pembayaran Via Qris</small>
                                        </span>
                                        <input name="payment" class="form-check-input" type="radio" value="qris"
                                            id="customRadioIcon2" />
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary  w-100 mb-3">Selesaikan Pembayaran</button>

                    </div>
                </form>

            </div>

        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/vendor/libs/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.js"></script>
@endpush

@push('script')
    <script>
        let table; // Declare table as a global variable
        $(function() {
            getTotal();
            $('#select2basicproduct').select2();
            table = $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('cart.data') }}',
                },
                columns: [{
                    data: 'DT_RowIndex',
                }, {
                    data: 'product_name',
                }, {
                    data: 'quantity',
                }, {
                    data: 'total',
                }, {
                    data: 'aksi',
                }],
            });
        });
    </script>

    <script>
        function updateData(cartId, newQuantity, updateUrl) {
            const csrfToken = $('[name=csrf-token]').attr('content');

            // Menggunakan jQuery untuk melakukan AJAX request
            $.ajax({
                type: 'PUT',
                url: updateUrl,
                data: {
                    _token: csrfToken,
                    quantity: newQuantity
                },
                success: function(response) {
                    // Menampilkan pesan sukses pada span dengan ID yang sesuai
                    table.ajax.reload();
                    getTotal();
                },
                error: function(error) {
                    // Menampilkan pesan error pada span dengan ID yang sesuai
                    $('#updateMessage_' + cartId).text('Error updating data');
                }
            });
        }


        function addProduct(url) {
            const csrfToken = $('[name=csrf-token]').attr('content');
            const idProduct = $('#select2basicproduct').val();
            const quantity = $('#quantity').val();
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _token: csrfToken,
                    idProduct: idProduct,
                    quantity: quantity
                },
                success: function(data) {
                    table.ajax.reload();
                    getTotal();
                }
            });

        }

        function getTotal() {
            let total = 0;
            $.ajax({
                type: 'GET',
                url: '{{ route('cart.total') }}',
                success: function(data) {
                    $('.JumlahBayar').text(data.content);
                },
                error: function(error) {
                    $('.JumlahBayar').text(total);
                }

            })
        }

        function deleteData(url) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                // Get the CSRF token from the meta tag.
                const csrfToken = $('[name=csrf-token]').attr('content');

                $.ajax({
                    type: 'DELETE',
                    url: url,
                    data: {
                        '_token': csrfToken,
                        '_method': 'delete'
                    },
                    success: function(response) {
                        table.ajax.reload();
                        getTotal();
                    },
                    error: function(xhr, status, error) {
                        alert('Tidak dapat menghapus data');
                    }
                });
            }
        }
    </script>
@endpush

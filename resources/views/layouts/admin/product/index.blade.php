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
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Product /</span> Data Product</h4>
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Data Product</h5>
                    <!-- Move the button to the right using ml-auto -->
                    @if (auth()->user()->role == 'admin')
                        <a href="{{ route('product.create') }}" class="btn btn-primary ml-auto"><span
                                class="ti ti-plus me-1">
                            </span> Tambah Data</a>
                    @endif
                </div>
                <table class="table table-dt">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Price</th>
                            @if (auth()->user()->role == 'admin')
                                <th width="10%">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.js"></script>


    <script>
        let table; // Declare table as a global variable

        $(document).ready(function() {
            table = $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('product.data') }}',
                },
                columns: [{
                    data: 'DT_RowIndex',
                }, {
                    data: 'name',
                    name: 'name',
                }, {
                    data: 'desc',
                    name: 'desc',
                }, {
                    data: 'image'
                }, {
                    data: 'price',
                }, {
                    data: 'aksi',
                }],
            });

            if ("{{ auth()->user()->role }}" === 'admin') {
                table.column(5).visible(true); // Kolom 'aksi' memiliki indeks 5 (mulai dari 0)
            } else {
                table.column(5).visible(false);
            }
        });

        function deleteData(url) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                // Get the CSRF token from the meta tag.
                const csrfToken = $('[name=csrf-token]').attr('content');

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        '_token': csrfToken,
                        '_method': 'delete'
                    },
                    success: function(response) {
                        table.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        alert('Tidak dapat menghapus data');
                    }
                });
            }
        }
    </script>
@endpush

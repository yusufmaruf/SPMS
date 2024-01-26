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
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">resep /</span> Data Resep</h4>
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Data Resep</h5>
                    <!-- Move the button to the right using ml-auto -->
                    @if (Auth::user()->role == 'admin')
                        <a href="{{ route('plantReceipt.create') }}" class="btn btn-primary ml-auto"><span
                                class="ti ti-plus me-1">
                            </span> Tambah Receipt</a>
                    @endif
                </div>
                <table class="table table-dt">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th width="10%">aksi</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($receipt as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->product->name }}</td>
                                @if (Auth::user()->role == 'admin')
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('resep.show', $item->idProduct) }}"
                                                class="btn btn-success">Lihat</a>
                                            <a href="{{ route('resep.edit', ['resep' => $item->idProduct]) }}"
                                                class="btn btn-warning">Edit</a>
                                            <a href="#"
                                                onclick="deleteData('{{ route('resep.destroy', $item->idProduct) }}')"
                                                class="btn btn-danger">Hapus</a>
                                        </div>
                                    </td>
                                @else
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('resep.show', $item->idProduct) }}"
                                                class="btn btn-success">Lihat</a>
                                        </div>
                                    </td>
                                @endif

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
        let table; // Declare table as a global variable

        $(document).ready(function() {
            table = $('.table').DataTable();
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

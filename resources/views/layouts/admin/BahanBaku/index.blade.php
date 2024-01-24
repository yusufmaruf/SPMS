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
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Bahan Baku /</span> Data Bahan Baku</h4>
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Data Bahan Baku</h5>
                    <!-- Move the button to the right using ml-auto -->
                    <button onclick="tambahData()" type="button" class="btn btn-primary ml-auto"><span
                            class="ti ti-plus me-1">
                        </span> Tambah Data</button>
                </div>
                <table class="datatables-basic table">
                    <thead>
                        <tr>
                            <th width="10%">No</th>
                            <th>Nama</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @includeIf('layouts.admin.BahanBaku.modal')
@endsection

@push('script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('body').on('click', '.tombol-edit', function(e) {
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route('bahanbaku.show', ['bahanbaku' => ':id']) }}'.replace(':id',
                        id),
                    type: 'GET',
                    success: function(response) {
                        $('#editdata').modal('show');
                        $('#nameEdit').val(response.result.name);
                        console.log(response.result.idBahan);
                        $('#bahanbakuupdate').attr('action',
                            '{{ route('bahanbaku.update', ['bahanbaku' => ':id']) }}'
                            .replace(':id',
                                id));

                    }
                });
            });


            $('body').on('click', '.tombol-del', function(e) {
                var id = $(this).data('id');

                $.ajax({
                    url: '{{ route('bahanbaku.show', ['bahanbaku' => ':id']) }}'.replace(':id',
                        id),
                    type: 'GET',
                    success: function(response) {
                        $('#deletedata').modal('show');
                        $('#idBahan').val(response.result.idBahan);
                        var nameParagraph = $('<p>').text('Nama: ' + response.result.name);
                        $('.content-data').append(nameParagraph);

                        // Update the form action attribute
                        $('#bahanbakudelete').attr('action',
                            '/admin/bahanbaku/' + id);
                    }
                });

            });


        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }

        });
    </script>
    <script>
        function tambahData() {
            $('#tambahdata').modal('show');
        }
    </script>
    <script>
        let table; // Declare table as a global variable
        $(document).ready(function() {
            table = $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('bahan.data') }}',
                },
                columns: [{
                    data: 'DT_RowIndex',
                }, {
                    data: 'name',
                }, {
                    data: 'aksi',
                }],
            });
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

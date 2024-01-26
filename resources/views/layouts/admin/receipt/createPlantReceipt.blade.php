@extends('layouts.master')
@push('style')
    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">

    {{-- datatable --}}
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
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Tambah Resep</h4>
        <!-- DataTable with Buttons -->

        <form action="{{ route('resep.store') }}" id="basic-form" method="post" novalidate enctype="multipart/form-data">
            @csrf
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tambah Data Resep</h5>
                    <!-- Move the button to the right using ml-auto -->
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <label for="">Produk</label>
                        <select name="idProduct" id="select2basicproduct"
                            class="select2 form-select form-select form-control">
                            @foreach ($produk as $item)
                                <option value="{{ $item->idProduct }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="dinamic-form">
                        <div class="mb-3">
                            <label for="name" class="form-label">Bahan Baku</label>
                            <select name="idBahan[]" id="select2basic" class="select2 form-select form-select form-control">
                                @foreach ($bahan as $item)
                                    <option value="{{ $item->idBahan }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" name="quantity[]">
                        </div>
                        <button type="button" class="btn btn-success w-100 mb-3" id="tambahBahanBtn">Tambah
                            Bahan</button>

                    </div>



                </div>

            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <table class="table table-dt">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Nama Bahan Baku</th>
                                <th>Jumlah</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary  w-100">Submit</button>
                </div>
            </div>
        </form>
    </div>
    @php
        $bahanOptions = json_encode($bahan->pluck('name', 'idBahan')->toArray());
    @endphp
@endsection

@push('script')
    <script src="{{ asset('assets/vendor/libs/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script>
        $('#select2basic').select2({
            placeholder: 'Pilih Bahan Baku',
        });
    </script>
@endpush

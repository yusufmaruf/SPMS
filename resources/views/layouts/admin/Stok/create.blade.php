@extends('layouts.master')
@push('style')
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Tambah Stok</h4>
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tambah Data Stok</h5>
                    <!-- Move the button to the right using ml-auto -->
                </div>
                <div class="card-body">
                    <form action="{{ route('stok.store') }}" id="basic-form" method="post" novalidate
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="">Bahan Baku</label>
                            <select name="idBahan" id="select2basicproduct"
                                class="select2 form-select form-select form-control">
                                @foreach ($bahan as $item)
                                    <option value="{{ $item->idBahan }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="">Cabang</label>
                            <select name="idCabang" id="select2basic" class="select2 form-select form-select form-control">
                                @foreach ($cabang as $item)
                                    <option value="{{ $item->idCabang }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="">Jumlah</label>
                            <input type="number" class="form-control" name="jumlah">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary  w-100 mb-3">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/vendor/libs/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script>
        $(function() {
            $('#summernote').summernote();
            $('#select2basic').select2();
            $('#select2basicproduct').select2();
        })
    </script>
@endpush

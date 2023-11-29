@extends('layouts.master')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Tambah Bahan Baku</h4>
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tambah Data Bahan Baku</h5>
                    <!-- Move the button to the right using ml-auto -->
                    <a href="" class="btn btn-primary ml-auto"><span class="ti ti-plus me-1"> </span> Tambah Data</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('bahanbaku.store') }}" id="basic-form" method="post" novalidate
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="">Bahan Baku</label>
                            <input type="text" name="name" id="" class="form-control">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="assets/vendor/libs/select2/select2.js"></script>
@endpush

@extends('layouts.master')
@push('style')
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/summernote/summernote-bs4.min.css') }}">
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Tambah Product</h4>
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Data Product</h5>
                    <!-- Move the button to the right using ml-auto -->
                </div>
                <div class="card-body">
                    <form action="{{ route('product.update', ['product' => $item->idProduct]) }}" id="basic-form"
                        method="post" novalidate enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="">Produk</label>
                            <input type="text" name="name" id="" value="{{ $item->name }}"
                                class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Desc</label>
                            <textarea id="summernote" name="description" class="form-control">{{ $item->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="">Image</label> <br>
                            <img src="{{ Storage::url($item->image) }}" class="mb-3 mt-2" alt="">
                            <input type="file" name="image" id="" value="" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="">Price</label>
                            <input type="number" name="price" id="" value="{{ $item->price }}"
                                class="form-control">
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
    <script src="{{ asset('assets/vendor/libs/summernote/summernote-bs4.js') }}"></script>
    <script>
        $(function() {
            $('#summernote').summernote()
        })
    </script>
@endpush

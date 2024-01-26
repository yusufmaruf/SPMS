@extends('layouts.master')
@push('style')
    @section('content')
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Tambah Product</h4>
            <!-- DataTable with Buttons -->
            <div class="card">
                <div class="card-datatable table-responsive pt-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Tambah Data Product</h5>
                        <!-- Move the button to the right using ml-auto -->
                    </div>
                    <div class="card-body">
                        <form action="{{ route('cabang.store') }}" id="basic-form" method="post" novalidate
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name">Nama Cabang</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="image">Image</label>
                                <input type="file" name="image" id="image" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="location">Location</label>
                                <input type="text" name="location" id="location" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="open">Jam Buka</label>
                                <input type="text" name="open" id="open" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="close">Jam Tutup</label>
                                <input type="text" name="close" id="close" class="form-control" required>
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

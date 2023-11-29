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
                        <a href="" class="btn btn-primary ml-auto"><span class="ti ti-plus me-1"> </span> Tambah Data</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('cabang.update', ['cabang' => $item->idCabang]) }}" id="basic-form"
                            method="post" novalidate enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="">Nama Cabang</label>
                                <input type="text" name="name" id="" class="form-control"
                                    value="{{ $item->name }}">
                            </div>
                            <div class="mb-3">
                                <label for="">Image</label> <br>
                                <img src="{{ Storage::url($item->image) }}" class="mb-3 mt-2" alt="">
                                <input type="file" name="image" id="" value="" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="">Location</label>
                                <input type="text" name="location" id="" class="form-control"
                                    value="{{ $item->location }}">
                            </div>
                            <div class="mb-3">
                                <label for="">Phone</label>
                                <input type="text" name="phone" id="" class="form-control"
                                    value="{{ $item->phone }}">
                            </div>
                            <div class="mb-3">
                                <label for="">Jam Buka</label>
                                <input type="text" name="open" id="" class="form-control"
                                    value="{{ $item->open }}">
                            </div>
                            <div class="mb-3">
                                <label for="">Jam Tutup</label>
                                <input type="text" name="close" id="" class="form-control"
                                    value="{{ $item->close }}">
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

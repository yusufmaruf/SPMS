@extends('layouts.master')
@push('style')
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Tambah Karyawan</h4>
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tambah Data Karyawan</h5>
                    <!-- Move the button to the right using ml-auto -->
                </div>
                <div class="card-body">
                    <form action="{{ route('pengguna.update', ['pengguna' => $data->idUser]) }}" id="basic-form"
                        method="POSt" novalidate enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="mb-3">
                            <label for="name">Nama</label>
                            <input required type="string" class="form-control" name="name" value="{{ $data->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input required type="string" class="form-control" name="email" value="{{ $data->email }}">
                        </div>
                        <div class="mb-3">
                            <label for="password">Password </label>
                            <span class="text-danger font-weight-light text-sm  font-italic">(Masukkan password untuk
                                merubahnya.)</span>
                            <input required type="password" class="form-control" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="idCabang">Cabang</label>
                            <select required name="idCabang" id="select2basic"
                                class="select2 form-select form-select form-control">

                                @foreach ($cabang as $item)
                                    <option value="{{ $item->idCabang }}" @if ($item->idCabang === $data->idCabang) selected @endif>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="role">Role</label>
                            <select required name="role" id="select2basicRole"
                                class="select2 form-select form-select form-control">
                                <option value="user" @if ($data->role === 'user') selected @endif>Karyawan</option>
                                <option value="admin" @if ($data->role === 'admin') selected @endif>Admin</option>
                                <option value="manager" @if ($data->role === 'manager') selected @endif>Manager</option>
                            </select>
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
            $('#select2basicRole').select2();
        })
    </script>
@endpush

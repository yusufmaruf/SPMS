@extends('layouts.master')
@push('style')
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Tambah Resep</h4>
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tambah Data Resep</h5>
                    <!-- Move the button to the right using ml-auto -->
                </div>
                <div class="card-body">
                    <form action="{{ route('resep.store') }}" id="basic-form" method="post" novalidate
                        enctype="multipart/form-data">
                        @csrf
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
                                <select name="idBahan[]" id="select2basic"
                                    class="select2 form-select form-select form-control">
                                    @foreach ($bahan as $item)
                                        <option value="{{ $item->idBahan }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Jumlah</label>
                                <input type="number" class="form-control" name="quantity[]">
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-success w-100 mb-3" id="tambahBahanBtn">Tambah
                                Bahan</button>
                            <button type="submit" class="btn btn-primary  w-100 mb-3">Submit</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @php
        $bahanOptions = json_encode($bahan->pluck('name', 'idBahan')->toArray());
    @endphp
@endsection

@push('script')
    <script src="{{ asset('assets/vendor/libs/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script>
        $(function() {
            $('#summernote').summernote()
            $('#select2basic').select2({
                placeholder: 'Pilih Bahan Baku',
            })

            // Data bahan dari PHP
            var bahanOptions = {!! $bahanOptions !!};

            // Tanggapan terhadap klik tombol "Tambah Bahan"
            $('#tambahBahanBtn').click(function() {
                var newForm =
                    '<div class="dinamic-form">' +
                    '<div class="mb-3">' +
                    '<label for="name" class="form-label">Bahan Baku</label>' +
                    '<select name="idBahan[]" class="select2 form-select form-select form-control">';

                // Tambahkan opsi untuk setiap bahan
                for (var id in bahanOptions) {
                    newForm += '<option value="' + id + '">' + bahanOptions[id] + '</option>';
                }

                newForm +=
                    '</select>' +
                    '</div>' +
                    '<div class="mb-3">' +
                    '<label for="name" class="form-label">Jumlah</label>' +
                    '<input type="number" class="form-control" name="quantity[]">' +
                    '</div>' +
                    '</div>';

                $('.dinamic-form:last').after(newForm);

                // Reinitialize select2 on the new element
                $('.select2').select2({
                    placeholder: 'Pilih Bahan Baku',
                });
            });
        })
    </script>
@endpush

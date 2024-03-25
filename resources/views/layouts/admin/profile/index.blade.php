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
    {{-- pie chart  --}}
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Account</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Profile Details</h5>
                    <!-- Account -->
                    <div class="card-body">
                        @foreach ($pengguna as $item)
                            <form id="formAccountSettings" method="POST"
                                action="{{ route('profille.update', ['profille' => $item->idUser]) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="mb-3 col-md-12">
                                        <label for="name" class="form-label"> Name</label>
                                        <input class="form-control" type="text" id="name" name="name"
                                            value={{ $item->name }} autofocus />
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input class="form-control" type="text" id="email" name="email"
                                            value="{{ $item->email }}" placeholder="john.doe@example.com" />
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <label for="password">Password </label>
                                        <span class="text-danger font-weight-light text-sm font-italic">(Masukkan password
                                            Jika Ingin merubahnya.)</span>
                                        <div class="input-group">
                                            <input required type="password" value="" class="form-control"
                                                name="password" id="password">
                                            <span class="input-group-text cursor-pointer" id="togglePassword">
                                                <i class="ti ti-eye-off"></i>
                                            </span>
                                        </div>
                                    </div>
                        @endforeach

                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                    </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
    </div>
@endsection

@push('script')
    <script>
        document.getElementById("togglePassword").addEventListener("click", function() {
            const passwordInput = document.getElementById("password");
            const icon = this.querySelector("i");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("ti-eye-off");
                icon.classList.add("ti-eye");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("ti-eye");
                icon.classList.add("ti-eye-off");
            }
        });
    </script>
@endpush

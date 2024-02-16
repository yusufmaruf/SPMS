<!-- Add New Address Modal -->
<div class="modal fade" id="tambahdata" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="address-title mb-2">Tambah Purchase</h3>
                    <p class="text-muted address-subtitle">Silahkan masukan data Purchase</p>
                </div>
                <form id="addNewAddressForm" class="row g-3" action="{{ route('bahanbaku.store') }}" method="post"
                    novalidate enctype="multipart/form-data">
                    @csrf
                    <div class="col-12">
                        <label class="form-label" for="name">Nama Transaksi</label>
                        <input type="text" id="name" name="name" class="form-control" />
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="name">Jumlah Transaksi</label>
                        <input type="number" id="total" name="total" class="form-control" />
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Add New Address Modal -->

{{-- edit data modal --}}
<div class="modal fade" id="editdata" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="address-title mb-2">Edit Data Purchase</h3>
                    <p class="text-muted address-subtitle">Silahkan Perbarui Data Purchase Terbaru</p>
                </div>
                <form id="bahanbakuupdate" class="row g-3" method="POST" novalidate enctype="multipart/form-data">
                    @method('PUT')
                    @csrf

                    <div class="col-12">
                        <label class="form-label" for="modalAddressFirstName">Nama Purchase</label>
                        <input type="text" id="nameEdit" name="name" class="form-control" />
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="name">Jumlah Transaksi</label>
                        <input type="number" id="total" name="total" class="form-control" />
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- end data model  --}}
<div class="modal fade" id="deletedata" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center content-data mb-4">
                    <h3 class="address-title mb-2">Delete Bahan Baku</h3>
                    <p class="text-muted address-subtitle">apakah anda yakin akan menghapus data</p>
                </div>
                <form id="bahanbakudelete" class="row g-3" method="POST" novalidate enctype="multipart/form-data">
                    @method('DELETE')
                    @csrf
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deletedata" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center content-data mb-4">
                    <h3 class="address-title mb-2">Delete Cabang</h3>
                    <p class="text-muted address-subtitle">apakah anda yakin akan menghapus data</p>
                    <p class="namaUser"></p>
                </div>
                <form id="cabangdelete" class="row g-3" method="POST" novalidate enctype="multipart/form-data">
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

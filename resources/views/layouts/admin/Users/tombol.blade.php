<div class="btn-group">
    <a class="btn  btn-warning btn-flat" href="{{ route('pengguna.edit', ['pengguna' => $data->idUser]) }}">
        Update
    </a>
    <a href='#' data-id="{{ $data->idUser }}" class="btn btn-danger btn-flat tombol-del">Delete</a>
</div>

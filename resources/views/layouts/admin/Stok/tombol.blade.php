<div class="btn-group">
    <a class="btn  btn-warning btn-flat" href="{{ route('stok.edit', ['stok' => $data->idStok]) }}">
        Sunting
    </a>
    <a href='#' data-id="{{ $data->idStok }}" class="btn btn-danger btn-flat tombol-del">Del</a>
</div>

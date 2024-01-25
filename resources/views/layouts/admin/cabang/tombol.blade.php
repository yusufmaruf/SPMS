<div class="btn-group">
    <a class="btn  btn-warning btn-flat" href="{{ route('cabang.edit', ['cabang' => $data->idCabang]) }}">
        Sunting
    </a>
    <a href='#' data-id="{{ $data->idCabang }}" class="btn btn-danger btn-flat tombol-del">Del</a>
</div>

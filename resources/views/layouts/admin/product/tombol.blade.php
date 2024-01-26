<div class="btn-group">
    <a class="btn  btn-warning btn-flat" href="{{ route('product.edit', ['product' => $data->idProduct]) }}">
        Sunting
    </a>
    <a href='#' data-id="{{ $data->idProduct }}" class="btn btn-danger btn-flat tombol-del">Del</a>
</div>

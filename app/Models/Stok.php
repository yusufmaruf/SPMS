<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;
    protected $primaryKey = 'idStok';
    protected $fillable = [
        'idBahan', 'idCabang', 'jumlah'
    ];
    public function bahan()
    {
        return $this->belongsTo(BahanBaku::class, 'idBahan', 'idBahan');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'idCabang', 'idCabang');
    }
}

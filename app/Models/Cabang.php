<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;
    protected $primaryKey = 'idCabang';
    protected $fillable = [
        'name', 'slug', 'image', 'location', 'phone', 'open', 'close'
    ];

    public function stok()
    {
        return $this->hasMany(Stok::class, 'idCabang', 'idCabang');
    }
    public function penjualan()
    {
        return $this->hasMany(Sale::class, 'idCabang', 'idCabang');
    }
}

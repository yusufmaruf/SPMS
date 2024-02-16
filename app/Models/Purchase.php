<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $primaryKey = 'idPurchase';
    protected $fillable = [
        'idPurchase', 'idUser', 'idCabang', 'name', 'total', 'idTransaction'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'idCabang', 'idCabang');
    }
}

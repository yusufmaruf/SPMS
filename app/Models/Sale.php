<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $primaryKey = 'idSale';
    protected $fillable = [
        'idUser',    'idCabang',  'subtotal', 'payment'
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

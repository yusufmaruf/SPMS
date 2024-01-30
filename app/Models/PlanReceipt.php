<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanReceipt extends Model
{
    use HasFactory;
    protected $primaryKey = 'idPlanReceipt';
    protected $fillable = [
        'idProduct', 'idBahan', 'quantity', 'idUser'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'idProduct', 'idProduct');
    }

    public function bahanbaku()
    {
        return $this->belongsTo(BahanBaku::class, 'idBahan', 'idBahan');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }
}

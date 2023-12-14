<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $primaryKey = 'idCart';
    protected $fillable = [
        'idProduct', 'quantity', 'total', "idUser"
    ];
    // Cart model
    public function products()
    {
        return $this->hasMany(Product::class, 'idProduct', 'idProduct');
    }
}

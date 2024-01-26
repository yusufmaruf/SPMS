<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $primaryKey = 'idProduct';
    protected $fillable = [
        'name', 'slug', 'image', 'price', 'description'
    ];


    // Product model
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'idProduct', 'idProduct');
    }
    public function receipt()
    {
        return $this->hasMany(Receipt::class, 'idProduct', 'idProduct');
    }
}

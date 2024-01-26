<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;
    protected $primaryKey = 'idBahan';
    protected $fillable = [
        'name', 'slug'
    ];

    public function receipt()
    {
        return $this->hasMany(Receipt::class, 'idBahan', 'idBahan');
    }
}

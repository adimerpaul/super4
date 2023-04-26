<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use HasFactory;
    protected $fillable = [
        'quantity',
        'product',
        'notes',
        'price',
        'subtotal',
        'user_id',
        'producto_id',
        'sale_id',
    ];
}

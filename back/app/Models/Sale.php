<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'time',
        'type',
        'status',
        'total',
        'distance',
        'deliveryAmount',
        'user_id',
        'address_id',
        'invoice_id',
    ];
}

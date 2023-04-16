<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'star',
        'orden',
        'mostrar',
        'tipo',
        'imagen',
        'rubro_id',
        'rubro_id',
        'agencia_id',
    ];
    public function rubro(){ return $this->belongsTo(Rubro::class); }
}

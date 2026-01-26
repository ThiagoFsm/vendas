<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    use HasFactory;

    protected $table = 'entregas';

    protected $fillable = [
        'rua',
        'numero',
        'bairro',
        'data',
        'hora',
        'complemento'
    ];

    public function pedido()
    {
        // Indica que esta entrega aponta para apenas UM pedido
        return $this->morphOne(Pedido::class, 'entrega_retirada');
    }
}

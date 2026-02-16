<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    use HasFactory;

    protected $table = 'entregas';

    protected $fillable = [
        'bairro',
        'rua',
        'numero',
        'data',
        'periodo',
        'valor_uber',
        'entregador_id'
    ];

    public function pedido()
    {
        // Indica que esta entrega aponta para apenas UM pedido
        return $this->morphOne(Pedido::class, 'entrega_retirada');
    }
}

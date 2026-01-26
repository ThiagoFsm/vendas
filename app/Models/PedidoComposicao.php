<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoComposicao extends Model
{
    use HasFactory;

    protected $table = 'pedido_composicao';

    protected $fillable = [
        'pedido_id',
        'produto_id',
        'quantidade',
    ];
}

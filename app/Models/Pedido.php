<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'cliente_id',
        'quantidade_itens',
        'valor_total',
        'valor_antecipado',
        'valor_restante',
        'pago',
        'entrega_retirada',
        'concluido'
    ];

    public function entrega_retirada()
    {
        // Define que este campo pode pertencer a múltiplos modelos
        return $this->morphTo();
    }
}

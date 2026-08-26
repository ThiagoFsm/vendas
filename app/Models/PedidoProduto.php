<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $pedido_id
 * @property int $produto_id
 * @property int $quantidade
 * @property int $produzido
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PedidoProduto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PedidoProduto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PedidoProduto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PedidoProduto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PedidoProduto wherePedidoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PedidoProduto whereProdutoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PedidoProduto whereProduzido($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PedidoProduto whereQuantidade($value)
 * @mixin \Eloquent
 */
class PedidoProduto extends Model
{
    use HasFactory;

    protected $table = 'pedido_produto';

    protected $fillable = [
        'pedido_id',
        'produto_id',
        'quantidade',
        'produzido'
    ];
}

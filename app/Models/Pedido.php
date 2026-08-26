<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $cliente_id
 * @property int $quantidade_itens
 * @property numeric $valor_total
 * @property numeric|null $valor_antecipado
 * @property numeric|null $valor_restante
 * @property int $pago
 * @property string $entrega_retirada_type
 * @property int $entrega_retirada_id
 * @property int $ativo
 * @property int $produzido
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cliente $cliente
 * @property-read Model|\Eloquent $entrega_retirada
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Produto> $produtos
 * @property-read int|null $produtos_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pedido newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pedido newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pedido query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pedido whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pedido whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pedido whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pedido whereEntregaRetiradaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pedido whereEntregaRetiradaType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pedido whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pedido wherePago($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pedido whereProduzido($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pedido whereQuantidadeItens($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pedido whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pedido whereValorAntecipado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pedido whereValorRestante($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pedido whereValorTotal($value)
 * @mixin \Eloquent
 */
class Pedido extends Model
{

    /**
     * @var float|mixed
     */
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

    public function entrega_retirada(): BelongsTo
    {
        // Define que este campo pode pertencer a múltiplos modelos
        return $this->morphTo();
    }

    public function produtos(): BelongsToMany
    {
        return $this->belongsToMany(Produto::class, 'pedido_produto', 'pedido_id', 'produto_id')
                ->withPivot('quantidade', 'produzido');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $tipo_produto_id
 * @property int $sabor_id
 * @property int $tamanho_id
 * @property numeric|null $valor_produto
 * @property int $ativo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pedido> $pedidos
 * @property-read int|null $pedidos_count
 * @property-read \App\Models\Sabor $sabor
 * @property-read \App\Models\Tamanho $tamanho
 * @property-read \App\Models\TipoProduto $tipoProduto
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produto whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produto whereSaborId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produto whereTamanhoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produto whereTipoProdutoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produto whereValorProduto($value)
 * @mixin \Eloquent
 */
class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $fillable = [
        'tipo_produto_id',
        'sabor_id',
        'tamanho_id',
        'valor_produto'
    ];

    public function tipoProduto() {
        return $this->belongsTo(TipoProduto::class, 'tipo_produto_id', 'id');
    }

    public function sabor() {
        return $this->belongsTo(Sabor::class, 'sabor_id', 'id');
    }

    public function tamanho() {
        return $this->belongsTo(Tamanho::class, 'tamanho_id', 'id');
    }

    public function pedidos() {
        return $this->belongsToMany(Pedido::class, 'pedido_produto', 'produto_id', 'pedido_id')
            ->withPivot('quantidade');
    }
}

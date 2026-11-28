<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $descricao
 * @property bool $ativo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Produto> $produtos
 * @property-read int|null $produtos_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoProduto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoProduto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoProduto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoProduto whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoProduto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoProduto whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoProduto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoProduto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoProduto extends Model
{
    use HasFactory;

    protected $table = 'tipo_produtos';

    protected $fillable = [
        'descricao',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class, 'tipo_produto_id');
    }
}

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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tamanho newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tamanho newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tamanho query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tamanho whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tamanho whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tamanho whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tamanho whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tamanho whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tamanho extends Model
{
    use HasFactory;

    protected $table = 'tamanhos';

    protected $fillable = [
        'descricao',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class, 'tamanho_id');
    }
}

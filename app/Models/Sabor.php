<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $descricao
 * @property int $ativo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Produto> $produtos
 * @property-read int|null $produtos_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sabor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sabor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sabor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sabor whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sabor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sabor whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sabor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sabor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Sabor extends Model
{
    use HasFactory;

    protected $table = 'sabores';

    protected $fillable = [
        'descricao'
    ];

    public function produtos() {
        return $this->hasMany(Produto::class, 'sabor_id');
    }
}

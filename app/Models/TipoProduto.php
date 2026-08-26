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

    protected $table = "tipo_produtos";

    protected $fillable = [
        'descricao'
    ];
}

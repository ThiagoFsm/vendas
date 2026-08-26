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

    protected $table = "tamanhos";

    protected $fillable = [
        'descricao'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property int $id
 * @property string $bairro
 * @property string $data
 * @property string|null $periodo
 * @property string|null $horas
 * @property bool $realizada
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pedido|null $pedido
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retirada newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retirada newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retirada query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retirada whereBairro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retirada whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retirada whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retirada whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retirada wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retirada whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Retirada extends Model
{
    use HasFactory;

    protected $table = 'retiradas';

    protected $fillable = [
        'bairro',
        'data',
        'periodo',
        'horas',
        'realizada',
    ];

    protected $casts = [
        'realizada' => 'boolean',
    ];

    public function pedido(): MorphOne
    {
        // Indica que esta entrega aponta para apenas UM pedido
        return $this->morphOne(Pedido::class, 'entrega_retirada');
    }
}

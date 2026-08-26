<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $bairro_id
 * @property string|null $rua
 * @property string|null $numero
 * @property string|null $data
 * @property string|null $periodo
 * @property numeric|null $valor_uber
 * @property int|null $entregador_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Bairro|null $bairro
 * @property-read \App\Models\Vendedor|null $entregador
 * @property-read \App\Models\Pedido|null $pedido
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entrega newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entrega newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entrega query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entrega whereBairroId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entrega whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entrega whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entrega whereEntregadorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entrega whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entrega whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entrega wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entrega whereRua($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entrega whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entrega whereValorUber($value)
 * @mixin \Eloquent
 */
class Entrega extends Model
{
    use HasFactory;

    protected $table = 'entregas';

    protected $fillable = [
        'bairro_id',
        'rua',
        'numero',
        'data',
        'periodo',
        'valor_uber',
        'entregador_id'
    ];

    public function pedido()
    {
        // Indica que esta entrega aponta para apenas UM pedido
        return $this->morphOne(Pedido::class, 'entrega_retirada');
    }
    public function bairro() {
        return $this->belongsTo(Bairro::class, 'bairro_id', 'id');
    }

    public function entregador() {
        return $this->belongsTo(Vendedor::class, 'entregador_id', 'id');
    }
}

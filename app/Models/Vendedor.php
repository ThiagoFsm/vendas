<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nome
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cliente> $cliente
 * @property-read int|null $cliente_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendedor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendedor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendedor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendedor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendedor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendedor whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendedor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Vendedor extends Model
{
    use HasFactory;

    protected $table = 'vendedores';

    protected $fillable = [
        'nome'
    ];

    public function cliente()
    {
        return $this->hasMany(Cliente::class);
    }
}

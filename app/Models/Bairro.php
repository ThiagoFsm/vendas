<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $descricao
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Entrega> $entregas
 * @property-read int|null $entregas_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bairro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bairro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bairro query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bairro whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bairro whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bairro whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bairro whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Bairro extends Model
{
    use HasFactory;

    protected $table = 'bairros';
    protected $fillable = ['descricao'];

    public function entregas() {
        return $this->hasMany(Entrega::class, 'bairro_id', 'id');
    }
}

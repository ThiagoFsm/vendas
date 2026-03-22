<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bairro extends Model
{
    use HasFactory;

    protected $table = 'bairros';
    protected $fillable = ['descricao'];

    public function entregas() {
        return $this->hasMany(Entrega::class, 'bairro_id', 'id');
    }
}

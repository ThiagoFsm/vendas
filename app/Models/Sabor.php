<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

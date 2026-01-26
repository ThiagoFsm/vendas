<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retirada extends Model
{
    use HasFactory;

    protected $table = 'retiradas';

    protected $fillable = [
        'bairro',
        'data',
        'hora'
    ];

    public function pedido()
    {
        // Indica que esta entrega aponta para apenas UM pedido
        return $this->morphOne(Pedido::class, 'entrega_retirada');
    }
}

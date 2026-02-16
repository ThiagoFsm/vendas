<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = "clientes";

    protected $fillable = [
        'nome',
        'vendedor_id'
    ];

    public function pedido()
    {
        return $this->hasMany(Pedido::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }
}

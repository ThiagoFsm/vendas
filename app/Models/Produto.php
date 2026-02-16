<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $fillable = [
        'tipo_produto_id',
        'sabor_id',
        'tamanho_id',
        'valor_produto'
    ];

    public function tipoProduto() {
        return $this->belongsTo(TipoProduto::class);
    }

    public function sabor() {
        return $this->belongsTo(Sabor::class);
    }

    public function tamanho() {
        return $this->belongsTo(Tamanho::class);
    }

    public function pedidos() {
        return $this->hasMany(Pedido::class);
    }
}

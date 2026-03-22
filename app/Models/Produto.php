<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        return $this->belongsTo(TipoProduto::class, 'tipo_produto_id', 'id');
    }

    public function sabor() {
        return $this->belongsTo(Sabor::class, 'sabor_id', 'id');
    }

    public function tamanho() {
        return $this->belongsTo(Tamanho::class, 'tamanho_id', 'id');
    }

    public function pedidos() {
        return $this->belongsToMany(Pedido::class, 'pedido_produto', 'produto_id', 'pedido_id')
            ->withPivot('quantidade');
    }
}

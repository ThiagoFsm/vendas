<?php

namespace App\Services;

use App\Models\Bairro;
use App\Models\Cliente;
use App\Models\Entrega;
use App\Models\Pedido;
use App\Models\Retirada;
use App\Models\Produto;
use App\Models\Sabor;
use App\Models\Tamanho;
use App\Models\TipoProduto;
use App\Models\Vendedor;
use Exception;
use Illuminate\Support\Facades\DB;

class ProducaoService
{
    public function filtarPedidos($sabor_id, $tamanho_id) {
        return Pedido::with([
            'cliente',
            // 1. FILTRA OS FILHOS: Para que no seu dd() só apareçam os produtos que batem com o filtro
            'produtos' => function ($query) use ($sabor_id, $tamanho_id) {
                $query->where('sabor_id', $sabor_id)
                    ->where('tamanho_id', $tamanho_id);
            },
            'produtos.tipoProduto',
            'produtos.sabor',
            'produtos.tamanho',
        ])
// 2. FILTRA O PAI: Para que o Pedido só seja retornado se possuir ao menos um produto desses
            ->whereHas('produtos', function ($query) use ($sabor_id, $tamanho_id) {
                $query->where('sabor_id', $sabor_id)
                    ->where('tamanho_id', $tamanho_id);
            })->get();
    }
    public function marcarProdutoComoProduzido($pedido_id, $produto_id) {
        $pedido = Pedido::find($pedido_id);
        // atualizar na tabela pedido_produto onde pedido_id e produto_id, o campo produzido
        //
        $produto = $pedido->produtos->where('id', $produto_id)->first();
        $produto->produzido = true;
    }
}

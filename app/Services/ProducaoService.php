<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Support\Collection;

class ProducaoService
{
    /**
     * @param $sabor_id
     * @param $tamanho_id
     * @return Collection
     */
    public function filtarPedidos($sabor_id, $tamanho_id): Collection
    {
        return Pedido::with([
            'cliente',
            // 1. FILTRA OS FILHOS: Para que só apareçam os produtos que batem com o filtro
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

    /**
     * @param Pedido $pedido
     * @param Produto $produto
     * @return Produto
     */
    public function marcarProdutoComoProduzido(Pedido $pedido, Produto $produto): Produto
    {
        $pedido->produtos()->updateExistingPivot($produto, [
            'produzido' => true
        ]);

        $pedido->load('produtos');
        $this->marcarPedidoComoProduzido($pedido);

        return $produto;
    }

    /**
     * @param Pedido $pedido
     * @return bool
     */
    public function marcarPedidoComoProduzido(Pedido $pedido): bool
    {
        $produtos = $pedido->produtos ?? null;

        if ($produtos && $produtos->isNotEmpty()) {
            $todosProduzidos = $produtos->every(function ($produto) {
                return (bool) ($produto->pivot->produzido ?? false);
            });

            if ($todosProduzidos) {
                $pedido->produzido = true;
                return $pedido->save();
            }
        }

        return false;
    }

}

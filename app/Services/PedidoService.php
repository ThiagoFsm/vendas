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

class PedidoService
{
    public function gerenciarDadosListagem()
    {
        return Pedido::with([
            'cliente',
            'entrega_retirada',
        ])->get();
    }

    public function gerenciarModalListagem($pedido_id)
    {
        if (is_null($pedido_id)) return null;

        return Pedido::with([
            'cliente',
            'entrega_retirada' => function ($morphTo) {
                $morphTo->morphWith([
                    Entrega::class => ['entregador', 'bairro'],
                ]);
            },
            'produtos' => function ($query) {
                $query->with(['tipoProduto', 'sabor', 'tamanho']);
            }
        ])->find($pedido_id);
    }

    public function gerenciarDependencias($idCliente): array
    {
        if (is_null($idCliente)) return [];

        $cliente = Cliente::find($idCliente);
        $dependencias['cliente'] = $cliente;

        $dependencias['produtos'] = Produto::with(['tipoProduto', 'sabor', 'tamanho'])->where('ativo', true)->get();
        $dependencias['tipoProdutos'] = TipoProduto::where('ativo', true)->get();
        $dependencias['sabores'] = Sabor::where('ativo', true)->get();
        $dependencias['tamanhos'] = Tamanho::where('ativo', true)->get();
        $dependencias['vendedores'] = Vendedor::all();
        $dependencias['bairros'] = Bairro::all();

        return $dependencias;
    }

    public function gerenciarEntregaRetirada($dados)
    {
        if (is_null($dados)) return null;

        //retirada
        if(isset($dados['rua']) || isset($dados['entregador_id'])) {
            $entrega_retirada = Retirada::create($dados);
        }

        //uber ou entrega
        elseif(isset($dados['valor_uber'])) {
            $dados['valor_uber'] = (float) str_replace(',', '.', $dados['valor_uber']);
            $entrega_retirada = Entrega::create($dados);
        }

        else {
            $entrega_retirada = Entrega::create($dados);
        }

        return $entrega_retirada;
    }

    public function prepararPedidoSalvar($dados) {

        if (is_null($dados)) return null;

        $pedido['cliente_id'] = $dados['cliente']['id'];
        $pedido['quantidade_itens'] = count($dados['pedido']);
        $pedido['valor_total'] = $dados['valor_total'];
        $pedido['valor_antecipado'] = (float) $dados['valor_antecipado'];
        $pedido['valor_restante'] = $pedido['valor_total'] - $pedido['valor_antecipado'];

        if($pedido['valor_restante'] === 0.00) {
            $pedido['pago'] = true;
        }
        else {
            $pedido['pago'] = false;
        }

        return $pedido;
    }

    public function salvarPedido($pedidoPreparado, $entrega_retirada, $produtos): string
    {
        try {
            return DB::transaction(function () use ($pedidoPreparado, $entrega_retirada, $produtos) {
                $pedido = new Pedido($pedidoPreparado);
                $pedido->entrega_retirada()->associate($entrega_retirada);
                $pedido->save();
                $pedido->produtos()->sync($produtos);
                return $pedido;
            });
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function prepararProdutosSalvar($dadosPedido): array
    {
        if (is_null($dadosPedido)) return [];

        return collect($dadosPedido)->mapWithKeys(function($produto) {
            return [
                $produto['produto_id'] => [
                    'quantidade' => (int) $produto['quantidade']
                ]
            ];
        })->toArray();
    }

    public function atualizarPagamentoPedido($pedido_id)
    {
        if (is_null($pedido_id)) return null;

        $pedido = Pedido::find($pedido_id);
        $pedido->valor_restante = 0.0;
        $pedido->pago = true;
        $pedido->save();

        return $pedido;
    }
}

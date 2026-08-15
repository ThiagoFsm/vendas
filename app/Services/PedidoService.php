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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use LaravelIdea\Helper\App\Models\_IH_Pedido_C;
use Throwable;

class PedidoService
{
    /**
     * @return Pedido[]|Collection|_IH_Pedido_C
     */
    public function gerenciarDadosListagem(): Collection|_IH_Pedido_C|array
    {
        return Pedido::with([
            'cliente',
            'entrega_retirada',
        ])->get();
    }

    /**
     * @param $pedidoId
     * @return Pedido|Pedido[]|Collection|Model|_IH_Pedido_C|null
     */
    public function gerenciarModalListagem($pedidoId): Model|Collection|_IH_Pedido_C|Pedido|array|null
    {
        if (is_null($pedidoId)) return null;

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
        ])->find($pedidoId);
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

    /**
     * @param $dados
     * @return Entrega|Retirada|null
     */
    public function gerenciarEntregaRetirada($dados): Entrega|Retirada|null
    {
        if (is_null($dados)) return null;

        //entrega
        if(isset($dados['entregador_id'])) {
            $entrega_retirada = Entrega::create($dados);
        }

        //uber
        elseif(isset($dados['valor_uber'])) {
            $dados['valor_uber'] = (float) str_replace(',', '.', $dados['valor_uber']);
            $entrega_retirada = Entrega::create($dados);
        }

        //retirada
        else {
            $entrega_retirada = Retirada::create($dados);
        }

        return $entrega_retirada;
    }

    /**
     * @param $dados
     * @return array|null
     */
    public function prepararPedidoSalvar($dados) {

        if (is_null($dados)) return null;

        $pedido['cliente_id'] = $dados['cliente']['id'];
        $pedido['quantidade_itens'] = array_sum(array_column($dados['pedido'], 'quantidade'));
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

    /**
     * @param $pedidoPreparado
     * @param $entrega_retirada
     * @param $produtos
     * @return string
     * @throws Throwable
     */
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

    /**
     * @param $dadosPedido
     * @return array
     */
    public function prepararProdutosSalvar($dadosPedido): array
    {
        if (is_null($dadosPedido)) return [];

        return collect($dadosPedido)->mapWithKeys(function($produto) {
            return [
                $produto['produto_id'] => [
                    'quantidade' => (int) $produto['quantidade'],
                    'produzido' => false
                ]
            ];
        })->toArray();
    }

    /**
     * @param $pedidoId
     * @return Pedido|Pedido[]|_IH_Pedido_C|null
     */
    public function atualizarPagamentoPedido($pedidoId): _IH_Pedido_C|Pedido|array|null
    {
        if (is_null($pedidoId)) return null;

        $pedido = Pedido::find($pedidoId);
        $pedido->valor_restante = 0.0;
        $pedido->pago = true;
        $pedido->save();

        return $pedido;
    }

    public function atualizarPedido($pedidoId, $pedidoPreparado, $entrega_retirada, $produtos): Pedido
    {

//        return $pedido;
    }
}

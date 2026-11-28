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
use Throwable;

class PedidoService
{
    /**
     * @return Collection<int, Pedido>
     */
    public function gerenciarDadosListagem(): Collection
    {
        $pedidos = Pedido::with([
            'cliente.vendedor',
            'entrega_retirada',
            'produtos',
        ])->get();

        foreach ($pedidos as $pedido) {
            if (!$pedido->produzido && $pedido->produtos->isNotEmpty()) {
                $todosProduzidos = $pedido->produtos->every(function ($produto) {
                    return (bool) ($produto->pivot->produzido ?? false);
                });

                if ($todosProduzidos) {
                    $pedido->produzido = true;
                    $pedido->save();
                }
            }
        }

        return $pedidos;
    }

    /**
     * @param Pedido $pedido
     * @return Pedido
     */
    public function gerenciarModalListagem(Pedido $pedido): Pedido
    {
        $pedido->load([
            'cliente',
            'entrega_retirada' => function ($morphTo) {
                $morphTo->morphWith([
                    Entrega::class => ['entregador', 'bairro'],
                ]);
            },
            'produtos.tipoProduto',
            'produtos.sabor',
            'produtos.tamanho',
        ]);

        if (!$pedido->produzido && $pedido->produtos->isNotEmpty()) {
            $todosProduzidos = $pedido->produtos->every(function ($produto) {
                return (bool) ($produto->pivot->produzido ?? false);
            });

            if ($todosProduzidos) {
                $pedido->produzido = true;
                $pedido->save();
            }
        }

        return $pedido;
    }

    public function gerenciarDependencias($idCliente = null): array
    {
        $dependencias = [];
        $dependencias['cliente'] = !is_null($idCliente)
            ? Cliente::with('vendedor')->find($idCliente)
            : session('cliente_pendente_pedido');
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
    {// refatorar
        if (is_null($dados) || empty($dados)) return null;

        $dados['realizada'] = false;
        if (array_key_exists('horas', $dados) && empty($dados['horas'])) {
            $dados['horas'] = null;
        }

        // Uber: possui valor_uber ou bairro_id com rua/numero
        if (!empty($dados['valor_uber']) || (!empty($dados['bairro_id']) && !empty($dados['rua']))) {
            if (isset($dados['valor_uber']) && is_string($dados['valor_uber'])) {
                $dados['valor_uber'] = (float) str_replace(',', '.', str_replace('.', '', $dados['valor_uber']));
            }
            return Entrega::create($dados);
        }

        // Entrega: possui entregador_id
        if (!empty($dados['entregador_id'])) {
            return Entrega::create($dados);
        }

        // Retirada: possui bairro ou data de retirada
        if (!empty($dados['bairro']) || !empty($dados['data'])) {
            return Retirada::create($dados);
        }

        return null;
    }

    /**
     * @param $dados
     * @return array|null
     */
    public function prepararPedidoSalvar($dados): ?array
    {
        if (is_null($dados)) return null;

        $pedido = [];
        $pedido['cliente_id'] = $dados['cliente']['id'] ?? $dados['cliente_id'] ?? null;
        $pedido['quantidade_itens'] = array_sum(array_column($dados['pedido'] ?? [], 'quantidade'));
        $pedido['valor_total'] = $dados['valor_total'] ?? 0;
        $valorAntecipado = $dados['valor_antecipado'] ?? 0;
        if (is_string($valorAntecipado)) {
            $valorAntecipado = (float) str_replace(',', '.', str_replace('.', '', $valorAntecipado));
        }
        $pedido['valor_antecipado'] = (float) $valorAntecipado;
        $pedido['valor_restante'] = (float) $pedido['valor_total'] - (float) $pedido['valor_antecipado'];

        if ($pedido['valor_restante'] <= 0.00) {
            $pedido['pago'] = true;
            $pedido['valor_restante'] = 0.00;
        } else {
            $pedido['pago'] = false;
        }

        return $pedido;
    }

    /**
     * @param $pedidoPreparado
     * @param $entrega_retirada
     * @param $produtos
     * @param array<string, mixed>|null $dadosCliente
     * @return Pedido
     * @throws Throwable
     */
    public function salvarPedido($pedidoPreparado, $entrega_retirada, $produtos, ?array $dadosCliente = null): Pedido
    {
        return DB::transaction(function () use ($pedidoPreparado, $entrega_retirada, $produtos, $dadosCliente) {
            if (empty($pedidoPreparado['cliente_id']) && !empty($dadosCliente)) {
                $cliente = Cliente::create([
                    'nome' => trim((string) ($dadosCliente['nome'] ?? '')),
                    'vendedor_id' => (int) ($dadosCliente['vendedor_id'] ?? 0),
                ]);
                $pedidoPreparado['cliente_id'] = $cliente->id;
            }

            $pedido = new Pedido($pedidoPreparado);
            if ($entrega_retirada) {
                $pedido->entrega_retirada()->associate($entrega_retirada);
            }
            $pedido->save();
            if (!empty($produtos)) {
                $pedido->produtos()->sync($produtos);
                $todosProduzidos = collect($produtos)->every(function ($p) {
                    return !empty($p['produzido']);
                });
                if ($todosProduzidos) {
                    $pedido->produzido = true;
                    $pedido->save();
                }
            }
            return $pedido;
        });
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
                    'produzido' => !empty($produto['produzido'])
                ]
            ];
        })->toArray();
    }

    /**
     * @param $pedidoId
     * @return Pedido|null
     */
    public function atualizarPagamentoPedido($pedidoId): Pedido|null
    {
        if (is_null($pedidoId)) return null;

        $pedido = Pedido::find($pedidoId);
        $pedido->valor_restante = 0.0;
        $pedido->pago = true;
        $pedido->save();

        return $pedido;
    }

    /**
     * @param $pedidoId
     * @param $pedidoPreparado
     * @param $dadosEntregaRetirada
     * @param $produtos
     * @return Pedido
     * @throws Throwable
     */
    public function atualizarPedido($pedidoId, $pedidoPreparado, $dadosEntregaRetirada, $produtos): Pedido
    {
        return DB::transaction(function () use ($pedidoId, $pedidoPreparado, $dadosEntregaRetirada, $produtos) {
            $pedido = Pedido::with('entrega_retirada')->findOrFail($pedidoId);
            $pedido->update($pedidoPreparado);
            $pedido->produtos()->sync($produtos);

            if (!empty($produtos)) {
                $todosProduzidos = collect($produtos)->every(function ($p) {
                    return !empty($p['produzido']);
                });
                $pedido->produzido = $todosProduzidos;
                $pedido->save();
            }

            if ($dadosEntregaRetirada) {
                $pedido->entrega_retirada?->delete();
                $novaEntregaRetirada = $this->gerenciarEntregaRetirada($dadosEntregaRetirada);
                if ($novaEntregaRetirada) {
                    $pedido->entrega_retirada()->associate($novaEntregaRetirada);
                    $pedido->save();
                }
            }

            return $pedido;
        });
    }

    public function excluirPedido(Pedido $pedido): bool
    {
        return DB::transaction(function () use ($pedido) {
            $pedido->entrega_retirada?->delete();
            $pedido->produtos()->detach();
            return (bool) $pedido->delete();
        });
    }

    public function marcarPedidoComoPago(Pedido $pedidoAtualizar): bool
    {
        if ($pedidoAtualizar->pago) return false;

        $pedidoAtualizar->valor_antecipado += $pedidoAtualizar->valor_restante;
        $pedidoAtualizar->valor_restante = 0.0;
        $pedidoAtualizar->pago = true;
        return $pedidoAtualizar->save();
    }

    public function marcarPedidoComoProduzido(Pedido $pedido): bool
    {
        if ($pedido->produzido) {
            return false;
        }

        $pedido->produzido = true;
        $salvo = $pedido->save();

        $pedido->load('produtos');
        if ($pedido->produtos->isNotEmpty()) {
            $pedido->produtos()->updateExistingPivot($pedido->produtos->pluck('id'), [
                'produzido' => true,
            ]);
        }

        return $salvo;
    }

    public function marcarEntregaRetiradaComoRealizada(Pedido $pedido): bool
    {
        $entregaRetirada = $pedido->entrega_retirada;

        if (!$entregaRetirada || $entregaRetirada->realizada) {
            return false;
        }

        $entregaRetirada->realizada = true;

        return $entregaRetirada->save();
    }
}

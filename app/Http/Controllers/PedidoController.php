<?php

namespace App\Http\Controllers;

use App\Http\Requests\PedidosRequest;
use App\Models\Pedido;
use App\Models\Retirada;
use App\Services\PedidoService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class PedidoController extends Controller
{
    protected PedidoService $pedidoService;
    public function __construct(PedidoService $pedidoService)
    {
        $this->pedidoService = $pedidoService;
    }

    /**
     * @param Request $request
     * @return Factory|\Illuminate\Contracts\View\View|View|JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $pedidos = $this->pedidoService->gerenciarDadosListagem();

            return response()->json($pedidos);
        }

        return view('pedidos.index');
    }

    /**
     * @param Pedido $pedido
     * @return JsonResponse
     */
    public function show(Pedido $pedido): JsonResponse
    {
        $pedidoFormatado = $this->pedidoService->gerenciarModalListagem($pedido);

        return response()->json($pedidoFormatado);
    }

    /**
     * @param $cliente_id
     * @return JsonResponse
     */
    public function dependencias($cliente_id = null): JsonResponse
    {
        $dependencias = $this->pedidoService->gerenciarDependencias($cliente_id);

        return response()->json($dependencias);
    }

    /**
     * @param $cliente_id
     * @return Factory|\Illuminate\Contracts\View\View|View
     */
    public function create($cliente_id = null)
    {
        return view('pedidos.create', [
            'clienteId' => $cliente_id,
            'pedidoId' => null,
        ]);
    }

    /**
     * @param $pedidoId
     * @return Factory|\Illuminate\Contracts\View\View|View
     */
    public function edit($pedidoId)
    {
        $pedido = Pedido::findOrFail($pedidoId);

        return view('pedidos.create', [
            'pedidoId' => $pedido->id,
            'clienteId' => $pedido->cliente_id,
        ]);
    }

    /**
     * @param PedidosRequest $request
     * @param $pedidoId
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(PedidosRequest $request, $pedidoId = null): JsonResponse
    {
        $dados = $request->validated();
        $pedidoId = $pedidoId ?? ($dados['pedidoId'] ?? null);
        $isUpdate = (bool) $pedidoId;

        $produtos = $this->pedidoService->prepararProdutosSalvar($dados['pedido'] ?? []);
        $pedidoPreparado = $this->pedidoService->prepararPedidoSalvar($dados);

        if ($isUpdate) {
            $pedido = $this->pedidoService->atualizarPedido($pedidoId, $pedidoPreparado, $dados['entrega_retirada'] ?? null, $produtos);
        } else {
            $entrega_retirada = $this->pedidoService->gerenciarEntregaRetirada($dados['entrega_retirada'] ?? null);
            $pedido = $this->pedidoService->salvarPedido($pedidoPreparado, $entrega_retirada, $produtos);
        }

        $mensagem = $isUpdate
            ? 'Pedido atualizado com sucesso!'
            : 'Pedido criado com sucesso!';

        session()->flash('success', $mensagem);

        return response()->json([
            'message' => $mensagem,
            'pedido' => $pedido,
            'redirect' => route('vendas.pedidos.index'),
        ]);
    }

    /**
     * @param PedidosRequest $request
     * @param Pedido|null $pedido
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(PedidosRequest $request, ?Pedido $pedido = null): JsonResponse
    {
        $id = $pedido ? $pedido->id : $request->input('pedidoId');
        return $this->store($request, $id);
    }

    /**
     * @param Request $request
     * @param Pedido|null $pedido
     * @return JsonResponse
     */
    public function destroy(Request $request, ?Pedido $pedido = null): JsonResponse
    {
        $pedidoModel = ($pedido && $pedido->exists)
            ? $pedido
            : Pedido::findOrFail($request->input('pedidoId') ?? $request->route('pedido') ?? $request->route('pedido_id'));

        $this->pedidoService->excluirPedido($pedidoModel);

        return response()->json([
            'message' => 'Pedido excluído com sucesso!'
        ]);
    }

    public function pagar(Pedido $pedido): JsonResponse
    {
        $pedidoPago = $this->pedidoService->marcarPedidoComoPago($pedido);

        return response()->json([
            'message' => $pedidoPago ? 'Pedido pago com sucesso!' : 'O pedido já está pago.'
        ]);
    }

    public function produzir(Pedido $pedido): JsonResponse
    {
        $pedidoProduzido = $this->pedidoService->marcarPedidoComoProduzido($pedido);

        return response()->json([
            'message' => $pedidoProduzido ? 'Pedido marcado como feito com sucesso!' : 'O pedido já está concluído.'
        ]);
    }

    public function entregarRetirar(Pedido $pedido): JsonResponse
    {
        $pedido->load('entrega_retirada');

        if (!$pedido->entrega_retirada) {
            return response()->json([
                'message' => 'Este pedido não possui entrega ou retirada vinculada.'
            ], 422);
        }

        $isRetirada = $pedido->entrega_retirada instanceof Retirada;
        $realizado = $this->pedidoService->marcarEntregaRetiradaComoRealizada($pedido);

        $mensagem = $realizado
            ? ($isRetirada ? 'Pedido marcado como retirado com sucesso!' : 'Pedido marcado como entregue com sucesso!')
            : ($isRetirada ? 'O pedido já foi retirado.' : 'O pedido já foi entregue.');

        return response()->json([
            'message' => $mensagem
        ]);
    }
}

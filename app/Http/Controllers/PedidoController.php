<?php

namespace App\Http\Controllers;

use App\Http\Requests\PedidosRequest;
use App\Models\Pedido;
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
        return view('pedidos.create', [
            'pedidoId' => $pedidoId,
            'clienteId' => null,
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

        $produtos = $this->pedidoService->prepararProdutosSalvar($dados['pedido'] ?? []);
        $pedidoPreparado = $this->pedidoService->prepararPedidoSalvar($dados);

        if ($pedidoId) {
            $pedido = $this->pedidoService->atualizarPedido($pedidoId, $pedidoPreparado, $dados['entrega_retirada'] ?? null, $produtos);
        } else {
            $entrega_retirada = $this->pedidoService->gerenciarEntregaRetirada($dados['entrega_retirada'] ?? null);
            $pedido = $this->pedidoService->salvarPedido($pedidoPreparado, $entrega_retirada, $produtos);
        }

        return response()->json($pedido);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
//        dd($request->all());
//        $pedidoId = $request['pedidoId'];
//        $pedido_atualizado = $this->pedidoService->atualizarPedido($pedidoId);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $pedidoId = $request['pedidoId'];
        $pedido = Pedido::findOrFail($pedidoId);
        $pedido->entrega_retirada->delete();
        $pedido->delete();

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
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\PedidosRequest;
use App\Models\Cliente;
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
     * @param $pedidoId
     * @return Factory|\Illuminate\Contracts\View\View|JsonResponse|View
     */
    public function index($pedidoId = null)
    {
        $pedidos = $this->pedidoService->gerenciarDadosListagem();

        if (request()->ajax()) {
            $pedido = $this->pedidoService->gerenciarModalListagem($pedidoId);

            return response()->json($pedido);
        }

        return view('pedidos.index', compact('pedidos'));
    }

    /**
     * @param $cliente_id
     * @return Factory|\Illuminate\Contracts\View\View|View
     */
    public function create($cliente_id = null)
    {
        $dependencias = $this->pedidoService->gerenciarDependencias($cliente_id);

        return view('pedidos.create', compact('dependencias'));
    }

    /**
     * @param PedidosRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(PedidosRequest $request)
    {
        $dados = $request->validated();
        $pedidoId = $dados['pedidoId'] ?? null;

        $produtos = $this->pedidoService->prepararProdutosSalvar($dados['pedido']);
        $pedidoPreparado = $this->pedidoService->prepararPedidoSalvar($dados);

        if ($pedidoId) {
            $pedido = $this->pedidoService->atualizarPedido($pedidoId, $pedidoPreparado, $dados['entrega_retirada'], $produtos);
        }
        else {
            $entrega_retirada = $this->pedidoService->gerenciarEntregaRetirada($dados['entrega_retirada']);
            $pedido = $this->pedidoService->salvarPedido($pedidoPreparado, $entrega_retirada, $produtos);
        }

        return response()->json($pedido);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function pagar(Request $request)
    {
        $pedidoId = $request['pedidoId'];
        $pedido_pago = $this->pedidoService->atualizarPagamentoPedido($pedidoId);

        return response()->json($pedido_pago);
    }

    /**
     * @param $pedidoId
     * @return void
     */
    public function edit($pedidoId = null)
    {
//        $pedido = Pedido::with([
//            'cliente.vendedor',
//            'entrega_retirada',
//            'produtos.tipoProduto',
//            'produtos.sabor',
//            'produtos.tamanho',
//        ])->find($pedidoId);
//
//        return view('pedidos.create', compact('pedido'));
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
    public function destroy(Request $request)
    {
        $pedidoId = $request['pedidoId'];
        $pedido = Pedido::findOrFail($pedidoId);
        if($pedido->entrega_retirada){
            $pedido->entrega_retirada->delete();
        }
        $pedido->delete();

        return response()->json(['message' => 'Pedido excluído com sucesso!']);
    }
}

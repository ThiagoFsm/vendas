<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Services\PedidoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PedidoController extends Controller
{
    protected $pedidoService;
    public function __construct(PedidoService $pedidoService)
    {
        $this->pedidoService = $pedidoService;
    }

    /**
     * @param $pedido_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|JsonResponse|View
     */
    public function index($pedido_id = null)
    {
        $pedidos = $this->pedidoService->gerenciarDadosListagem();

        if (request()->ajax()) {
            $pedido = $this->pedidoService->gerenciarModalListagem($pedido_id);

            return response()->json($pedido);
        }

        return view('pedidos.index', compact('pedidos'));
    }

    /**
     * @param Cliente $cliente
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|View
     */
    public function create($cliente_id = null)
    {
        $dependencias = $this->pedidoService->gerenciarDependencias($cliente_id);

        return view('pedidos.create', compact('dependencias'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $dados = $request->all();
        $produtos = $this->pedidoService->prepararProdutosSalvar($dados['pedido']);
        $entrega_retirada = $this->pedidoService->gerenciarEntregaRetirada($dados['entrega_retirada']);
        $pedidoPreparado = $this->pedidoService->prepararPedidoSalvar($dados);
        $pedidoCriado = $this->pedidoService->salvarPedido($pedidoPreparado, $entrega_retirada, $produtos);

        return response()->json($pedidoCriado);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function pagar(Request $request)
    {
        $pedido_id = $request['pedido_id'];
        $pedido_pago = $this->pedidoService->atualizarPagamentoPedido($pedido_id);

        return response()->json($pedido_pago);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|View
     */
    public function edit($pedido_id = null)
    {
//        $pedido = Pedido::with([
//            'cliente.vendedor',
//            'entrega_retirada',
//            'produtos.tipoProduto',
//            'produtos.sabor',
//            'produtos.tamanho',
//        ])->find($pedido_id);
//
//        return view('pedidos.create', compact('pedido'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pedido  $pedidos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
//        dd($request->all());
//        $pedido_id = $request['pedido_id'];
//        $pedido_atualizado = $this->pedidoService->atualizarPedido($pedido_id);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $pedido_id = $request['pedido_id'];
        $pedido = Pedido::findOrFail($pedido_id);
        if($pedido->entrega_retirada){
            $pedido->entrega_retirada->delete();
        }
        $pedido->delete();

        return response()->json(['message' => 'Pedido excluído com sucesso!']);
    }
}

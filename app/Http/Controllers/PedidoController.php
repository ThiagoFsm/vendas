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
     * Display the specified resource.
     *
     * @param  \App\Models\Pedido  $pedidos
     * @return \Illuminate\Http\Response
     */
    public function show(Pedido $pedido_id)
    {
       //
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function edit(Request $request)
    {
        $pedido_id = $request['pedido_id'];
        $pedido_pago = $this->pedidoService->atualizarPagamentoPedido($pedido_id);

        return response()->json($pedido_pago);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pedido  $pedidos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pedido $pedidos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pedido  $pedidos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pedido $pedidos)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Services\PedidoService;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index() : view
    {

        return view('pedidos.index');
    }

    /**
     * @param Cliente $cliente
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|View
     */
    public function create()
    {
        $idCliente = request()->all();
        $dependencias = $this->pedidoService->gerenciarDependencias($idCliente);

        return view('pedidos.create', compact('dependencias'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $dados = $request->all();
        $produtos = $this->pedidoService->prepararProdutosSalvar($dados['pedido']);
        $entrega_retirada = $this->pedidoService->gerenciarEntregaRetirada($dados['entrega_retirada']);
        $pedidoPreparado = $this->pedidoService->prepararPedidoSalvar($dados);
        $pedidoCriado = $this->pedidoService->salvarPedido($pedidoPreparado, $entrega_retirada, $produtos);

        return response()->json(
            'Pedido criado com sucesso!'
        );
//        return redirect()->route('vendas.pedidos.index')->with('success', 'Pedido cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pedido  $pedidos
     * @return \Illuminate\Http\Response
     */
    public function show(Pedido $pedidos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pedido  $pedidos
     * @return \Illuminate\Http\Response
     */
    public function edit(Pedido $pedidos)
    {
        //
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

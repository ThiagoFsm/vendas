<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Produto;
use App\Models\Sabor;
use App\Services\ProducaoService;
use Illuminate\Http\Request;

class SaborController extends Controller
{
    protected $producaoService;

    public function __construct(ProducaoService $producaoService) {
        $this->producaoService = $producaoService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        $sabores = Sabor::where('ativo', 1)->orderBy('id')->get();
        $pedidos = Pedido::with(
        'produtos',
                'produtos.tipoProduto',
                'produtos.sabor',
                'produtos.tamanho',
        )->get();

        if (request()->ajax()) {
            $sabor_id = request('sabor_id');
            $tamanho_id = request('tamanho_id');
            $pedidosFiltrados = $this->producaoService->filtarPedidos($sabor_id, $tamanho_id);

            return response()->json($pedidosFiltrados);
        }

        return view('sabores.index', compact('sabores', 'pedidos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $produtoProduzido = $this->producaoService->marcarProdutoComoProduzido($request['pedido_id'], $request['produto_id']);
        return response()->json($produtoProduzido);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sabor  $sabor
     * @return \Illuminate\Http\Response
     */
    public function show(Sabor $sabor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sabor  $sabor
     * @return \Illuminate\Http\Response
     */
    public function edit(Sabor $sabor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sabor  $sabor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sabor $sabor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sabor  $sabor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sabor $sabor)
    {
        //
    }
}

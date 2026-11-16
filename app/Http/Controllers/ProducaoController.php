<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Produto;
use App\Models\Sabor;
use App\Services\ProducaoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProducaoController extends Controller
{
    protected ProducaoService $producaoService;

    public function __construct(ProducaoService $producaoService)
    {
        $this->producaoService = $producaoService;
    }

    /**
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $sabor_id = $request->input('sabor_id');
            $tamanho_id = $request->input('tamanho_id');
            $pedidosFiltrados = $this->producaoService->filtarPedidos($sabor_id, $tamanho_id);

            return response()->json($pedidosFiltrados);
        }

        $sabores = Sabor::where('ativo', true)->orderBy('id')->get();
        $pedidos = Pedido::with([
            'produtos',
            'produtos.tipoProduto',
            'produtos.sabor',
            'produtos.tamanho',
        ])->get();

        return view('producao.index', compact('sabores', 'pedidos'));
    }

    /**
     * @param Produto $produto
     * @param Pedido $pedido
     * @return JsonResponse
     */
    public function store(Produto $produto, Pedido $pedido): JsonResponse
    {
        $produtoProduzido = $this->producaoService->marcarProdutoComoProduzido($pedido, $produto);

        return response()->json($produtoProduzido);
    }
}

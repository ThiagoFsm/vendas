<?php

namespace App\Http\Controllers;

use App\Models\Bairro;
use App\Models\Retirada;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RetiradaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $query = Retirada::with([
                'pedido.cliente.vendedor',
                'pedido.produtos.tipoProduto',
                'pedido.produtos.sabor',
                'pedido.produtos.tamanho',
            ]);

            if ($request->filled('data')) {
                $query->whereDate('data', $request->input('data'));
            }

            if ($request->filled('periodo')) {
                $query->where('periodo', $request->input('periodo'));
            }

            if ($request->filled('bairro')) {
                $query->where('bairro', 'like', '%' . $request->input('bairro') . '%');
            }

            $retiradas = $query->orderBy('data', 'desc')
                ->orderBy('id', 'desc')
                ->get();

            return response()->json($retiradas);
        }

        return view('retiradas.index');
    }

    /**
     * Retorna dependências para filtros e formulários
     *
     * @return JsonResponse
     */
    public function dependencias(): JsonResponse
    {
        return response()->json([
            'bairros' => Bairro::orderBy('descricao')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|JsonResponse
     */
    public function create(): View|JsonResponse
    {
        return response()->json([
            'bairros' => Bairro::orderBy('descricao')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'bairro' => 'required|string|max:255',
            'data' => 'required|date',
            'periodo' => 'nullable|string|max:50',
        ]);

        $retirada = Retirada::create($dados);

        return response()->json([
            'message' => 'Retirada cadastrada com sucesso!',
            'retirada' => $retirada,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Retirada $retirada
     * @return JsonResponse
     */
    public function show(Retirada $retirada): JsonResponse
    {
        $retirada->load([
            'pedido.cliente.vendedor',
            'pedido.produtos.tipoProduto',
            'pedido.produtos.sabor',
            'pedido.produtos.tamanho',
        ]);

        return response()->json($retirada);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Retirada $retirada
     * @return JsonResponse
     */
    public function edit(Retirada $retirada): JsonResponse
    {
        return response()->json($retirada->load([
            'pedido.cliente',
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Retirada $retirada
     * @return JsonResponse
     */
    public function update(Request $request, Retirada $retirada): JsonResponse
    {
        $dados = $request->validate([
            'bairro' => 'required|string|max:255',
            'data' => 'required|date',
            'periodo' => 'nullable|string|max:50',
        ]);

        $retirada->update($dados);
        $retirada->load([
            'pedido.cliente.vendedor',
            'pedido.produtos.tipoProduto',
            'pedido.produtos.sabor',
            'pedido.produtos.tamanho',
        ]);

        return response()->json([
            'message' => 'Retirada atualizada com sucesso!',
            'retirada' => $retirada,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Retirada $retirada
     * @return JsonResponse
     */
    public function destroy(Retirada $retirada): JsonResponse
    {
        $retirada->delete();

        return response()->json([
            'message' => 'Retirada excluída com sucesso!',
        ]);
    }
}

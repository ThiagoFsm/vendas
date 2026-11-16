<?php

namespace App\Http\Controllers;

use App\Models\Bairro;
use App\Models\Entrega;
use App\Models\Vendedor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EntregaController extends Controller
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
            $query = Entrega::with([
                'bairro',
                'entregador',
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

            if ($request->filled('entregador_id')) {
                $query->where('entregador_id', $request->input('entregador_id'));
            }

            if ($request->filled('tipo')) {
                $tipo = $request->input('tipo');
                if ($tipo === 'uber') {
                    $query->where(function ($q) {
                        $q->whereNotNull('valor_uber')
                            ->orWhere(function ($subQ) {
                                $subQ->whereNull('entregador_id')
                                    ->whereNotNull('rua');
                            });
                    });
                } elseif ($tipo === 'entregador') {
                    $query->whereNotNull('entregador_id');
                }
            }

            $entregas = $query->orderBy('data', 'desc')
                ->orderBy('id', 'desc')
                ->get();

            return response()->json($entregas);
        }

        return view('entregas.index');
    }

    /**
     * Retorna dependências para filtros e formulários
     *
     * @return JsonResponse
     */
    public function dependencias(): JsonResponse
    {
        return response()->json([
            'entregadores' => Vendedor::orderBy('nome')->get(),
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
            'entregadores' => Vendedor::orderBy('nome')->get(),
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
            'bairro_id' => 'nullable|exists:bairros,id',
            'rua' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:50',
            'data' => 'nullable|date',
            'periodo' => 'nullable|string|max:50',
            'valor_uber' => 'nullable',
            'entregador_id' => 'nullable|exists:vendedores,id',
        ]);

        if (array_key_exists('bairro_id', $dados) && empty($dados['bairro_id'])) {
            $dados['bairro_id'] = null;
        }

        if (array_key_exists('entregador_id', $dados) && empty($dados['entregador_id'])) {
            $dados['entregador_id'] = null;
        }

        if (array_key_exists('valor_uber', $dados)) {
            if ($dados['valor_uber'] === '' || $dados['valor_uber'] === null) {
                $dados['valor_uber'] = null;
            } elseif (is_string($dados['valor_uber'])) {
                $dados['valor_uber'] = (float) str_replace(',', '.', str_replace('.', '', $dados['valor_uber']));
            }
        }

        $entrega = Entrega::create($dados);
        $entrega->load(['bairro', 'entregador']);

        return response()->json([
            'message' => 'Entrega cadastrada com sucesso!',
            'entrega' => $entrega,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Entrega $entrega
     * @return JsonResponse
     */
    public function show(Entrega $entrega): JsonResponse
    {
        $entrega->load([
            'bairro',
            'entregador',
            'pedido.cliente.vendedor',
            'pedido.produtos.tipoProduto',
            'pedido.produtos.sabor',
            'pedido.produtos.tamanho',
        ]);

        return response()->json($entrega);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Entrega $entrega
     * @return JsonResponse
     */
    public function edit(Entrega $entrega): JsonResponse
    {
        return response()->json($entrega->load([
            'bairro',
            'entregador',
            'pedido.cliente',
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Entrega $entrega
     * @return JsonResponse
     */
    public function update(Request $request, Entrega $entrega): JsonResponse
    {
        $dados = $request->validate([
            'bairro_id' => 'nullable|exists:bairros,id',
            'rua' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:50',
            'data' => 'nullable|date',
            'periodo' => 'nullable|string|max:50',
            'valor_uber' => 'nullable',
            'entregador_id' => 'nullable|exists:vendedores,id',
        ]);

        if (array_key_exists('bairro_id', $dados) && empty($dados['bairro_id'])) {
            $dados['bairro_id'] = null;
        }

        if (array_key_exists('entregador_id', $dados) && empty($dados['entregador_id'])) {
            $dados['entregador_id'] = null;
        }

        if (array_key_exists('valor_uber', $dados)) {
            if ($dados['valor_uber'] === '' || $dados['valor_uber'] === null) {
                $dados['valor_uber'] = null;
            } elseif (is_string($dados['valor_uber'])) {
                $dados['valor_uber'] = (float) str_replace(',', '.', str_replace('.', '', $dados['valor_uber']));
            }
        }

        $entrega->update($dados);
        $entrega->load([
            'bairro',
            'entregador',
            'pedido.cliente.vendedor',
            'pedido.produtos.tipoProduto',
            'pedido.produtos.sabor',
            'pedido.produtos.tamanho',
        ]);

        return response()->json([
            'message' => 'Entrega atualizada com sucesso!',
            'entrega' => $entrega,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Entrega $entrega
     * @return JsonResponse
     */
    public function destroy(Entrega $entrega): JsonResponse
    {
        $entrega->delete();

        return response()->json([
            'message' => 'Entrega excluída com sucesso!',
        ]);
    }
}

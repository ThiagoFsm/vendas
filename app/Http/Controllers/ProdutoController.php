<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdutoRequest;
use App\Models\Produto;
use App\Models\Sabor;
use App\Models\Tamanho;
use App\Models\TipoProduto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    /**
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $produtos = Produto::with(['tipoProduto', 'sabor', 'tamanho'])
                ->orderBy('id', 'desc')
                ->get();

            return response()->json($produtos);
        }

        return view('produtos.index');
    }

    /**
     * Retorna dependências para o formulário de cadastro/edição (tipos, sabores, tamanhos)
     *
     * @return JsonResponse
     */
    public function dependencias(): JsonResponse
    {
        return response()->json([
            'tipoProdutos' => TipoProduto::where('ativo', true)->orderBy('descricao')->get(),
            'sabores'      => Sabor::where('ativo', true)->orderBy('descricao')->get(),
            'tamanhos'     => Tamanho::where('ativo', true)->orderBy('id')->get(),
        ]);
    }

    /**
     * @param ProdutoRequest $request
     * @return JsonResponse
     */
    public function store(ProdutoRequest $request): JsonResponse
    {
        $dados = $request->validated();
        if (!isset($dados['ativo'])) {
            $dados['ativo'] = true;
        }

        $produto = Produto::create($dados);
        $produto->load(['tipoProduto', 'sabor', 'tamanho']);

        return response()->json([
            'message' => 'Produto cadastrado com sucesso!',
            'produto' => $produto,
        ], 201);
    }

    /**
     * @param Produto $produto
     * @return JsonResponse
     */
    public function show(Produto $produto): JsonResponse
    {
        return response()->json($produto->load(['tipoProduto', 'sabor', 'tamanho']));
    }

    /**
     * @param ProdutoRequest $request
     * @param Produto $produto
     * @return JsonResponse
     */
    public function update(ProdutoRequest $request, Produto $produto): JsonResponse
    {
        $dados = $request->validated();
        $produto->update($dados);
        $produto->load(['tipoProduto', 'sabor', 'tamanho']);

        return response()->json([
            'message' => 'Produto atualizado com sucesso!',
            'produto' => $produto,
        ]);
    }

    /**
     * Alterna o status ativo/inativo do produto
     *
     * @param Produto $produto
     * @return JsonResponse
     */
    public function toggleAtivo(Produto $produto): JsonResponse
    {
        $produto->ativo = !$produto->ativo;
        $produto->save();

        $status = $produto->ativo ? 'ativado' : 'desativado';

        return response()->json([
            'message' => "Produto {$status} com sucesso!",
            'ativo'   => $produto->ativo,
        ]);
    }

    /**
     * Exclui o produto ou o inativa caso já esteja vinculado a pedidos
     *
     * @param Produto $produto
     * @return JsonResponse
     */
    public function destroy(Produto $produto): JsonResponse
    {
        // Se possui pedidos vinculados, desativa para não quebrar integridade referencial
        if ($produto->pedidos()->exists()) {
            $produto->ativo = false;
            $produto->save();

            return response()->json([
                'message' => 'O produto possui pedidos vinculados e foi desativado em vez de excluído.',
                'action'  => 'deactivated',
            ]);
        }

        $produto->delete();

        return response()->json([
            'message' => 'Produto excluído com sucesso!',
            'action'  => 'deleted',
        ]);
    }
}

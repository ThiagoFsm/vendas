<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Sabor;
use App\Models\Tamanho;
use App\Models\TipoProduto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ConfiguracaoController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($this->carregarDadosCatalogo());
        }

        return view('configuracoes.index');
    }

    /**
     * Salva alterações em lote de status (ativo/inativo) e valores do catálogo.
     *
     * @throws Throwable
     */
    public function salvar(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'tipoProdutos' => 'sometimes|array',
            'tipoProdutos.*.id' => 'required|integer|exists:tipo_produtos,id',
            'tipoProdutos.*.ativo' => 'required|boolean',

            'sabores' => 'sometimes|array',
            'sabores.*.id' => 'required|integer|exists:sabores,id',
            'sabores.*.ativo' => 'required|boolean',

            'tamanhos' => 'sometimes|array',
            'tamanhos.*.id' => 'required|integer|exists:tamanhos,id',
            'tamanhos.*.ativo' => 'required|boolean',

            'produtos' => 'sometimes|array',
            'produtos.*.id' => 'required|integer|exists:produtos,id',
            'produtos.*.ativo' => 'sometimes|boolean',
            'produtos.*.valor_produto' => 'sometimes|nullable',
        ]);

        DB::transaction(function () use ($dados): void {
            foreach ($dados['tipoProdutos'] ?? [] as $item) {
                TipoProduto::query()
                    ->where('id', $item['id'])
                    ->update(['ativo' => (bool) $item['ativo']]);
            }

            foreach ($dados['sabores'] ?? [] as $item) {
                Sabor::query()
                    ->where('id', $item['id'])
                    ->update(['ativo' => (bool) $item['ativo']]);
            }

            foreach ($dados['tamanhos'] ?? [] as $item) {
                Tamanho::query()
                    ->where('id', $item['id'])
                    ->update(['ativo' => (bool) $item['ativo']]);
            }

            foreach ($dados['produtos'] ?? [] as $item) {
                $atualizacao = [];

                if (array_key_exists('ativo', $item)) {
                    $atualizacao['ativo'] = (bool) $item['ativo'];
                }

                if (array_key_exists('valor_produto', $item) && $item['valor_produto'] !== null && $item['valor_produto'] !== '') {
                    $valor = $this->normalizarValorMonetario($item['valor_produto']);
                    if ($valor !== null && $valor >= 0) {
                        $atualizacao['valor_produto'] = $valor;
                    }
                }

                if ($atualizacao !== []) {
                    Produto::query()
                        ->where('id', $item['id'])
                        ->update($atualizacao);
                }
            }
        });

        return response()->json([
            'message' => 'Alterações salvas com sucesso!',
            'dados' => $this->carregarDadosCatalogo(),
        ]);
    }

    /**
     * Cria um novo item no catálogo (Tipo de Produto, Produto, Sabor ou Tamanho).
     */
    public function storeItem(Request $request): JsonResponse
    {
        $tipo = (string) $request->input('tipo_item');

        if (!in_array($tipo, ['tipo_produto', 'produto', 'sabor', 'tamanho'], true)) {
            return response()->json([
                'message' => 'Tipo de item inválido.',
            ], 422);
        }

        if ($tipo === 'produto') {
            $valorNormalizado = $this->normalizarValorMonetario($request->input('valor_produto'));
            if ($valorNormalizado !== null) {
                $request->merge(['valor_produto' => $valorNormalizado]);
            }

            $validado = $request->validate([
                'tipo_produto_id' => 'required|integer|exists:tipo_produtos,id',
                'sabor_id' => 'required|integer|exists:sabores,id',
                'tamanho_id' => 'required|integer|exists:tamanhos,id',
                'valor_produto' => 'required|numeric|min:0',
                'ativo' => 'sometimes|boolean',
            ], [
                'tipo_produto_id.required' => 'Selecione o tipo de produto.',
                'sabor_id.required' => 'Selecione o sabor.',
                'tamanho_id.required' => 'Selecione o tamanho.',
                'valor_produto.required' => 'Informe o valor do produto.',
                'valor_produto.numeric' => 'Informe um valor numérico válido.',
                'valor_produto.min' => 'O valor não pode ser negativo.',
            ]);

            $validado['ativo'] = $validado['ativo'] ?? true;

            $produto = Produto::create($validado);
            $produto->load(['tipoProduto', 'sabor', 'tamanho']);

            return response()->json([
                'message' => 'Produto cadastrado com sucesso!',
                'item' => $produto,
                'dados' => $this->carregarDadosCatalogo(),
            ], 201);
        }

        if (is_string($request->input('descricao'))) {
            $request->merge(['descricao' => trim($request->input('descricao'))]);
        }

        $validado = $request->validate([
            'descricao' => 'required|string|max:120',
            'ativo' => 'sometimes|boolean',
        ], [
            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.max' => 'A descrição não pode ultrapassar 120 caracteres.',
        ]);

        $validado['ativo'] = $validado['ativo'] ?? true;

        $item = match ($tipo) {
            'tipo_produto' => TipoProduto::create($validado),
            'sabor' => Sabor::create($validado),
            'tamanho' => Tamanho::create($validado),
        };

        $rotulo = match ($tipo) {
            'tipo_produto' => 'Tipo de produto',
            'sabor' => 'Sabor',
            'tamanho' => 'Tamanho',
        };

        return response()->json([
            'message' => "{$rotulo} cadastrado com sucesso!",
            'item' => $item,
            'dados' => $this->carregarDadosCatalogo(),
        ], 201);
    }

    /**
     * @return array<string, mixed>
     */
    private function carregarDadosCatalogo(): array
    {
        return [
            'tipoProdutos' => TipoProduto::withCount('produtos')
                ->orderBy('id')
                ->get(),
            'produtos' => Produto::with(['tipoProduto', 'sabor', 'tamanho'])
                ->orderBy('tipo_produto_id')
                ->orderBy('sabor_id')
                ->orderBy('tamanho_id')
                ->get(),
            'sabores' => Sabor::withCount('produtos')
                ->orderBy('id')
                ->get(),
            'tamanhos' => Tamanho::withCount('produtos')
                ->orderBy('id')
                ->get(),
        ];
    }

    private function normalizarValorMonetario(mixed $valor): ?float
    {
        if (is_int($valor) || is_float($valor)) {
            return (float) $valor;
        }

        if (!is_string($valor)) {
            return null;
        }

        $limpo = trim(str_replace(['R$', ' '], '', $valor));
        if ($limpo === '') {
            return null;
        }

        if (str_contains($limpo, ',')) {
            $limpo = str_replace('.', '', $limpo);
            $limpo = str_replace(',', '.', $limpo);
        }

        return is_numeric($limpo) ? (float) $limpo : null;
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaborRequest;
use App\Models\Sabor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaborController extends Controller
{
    /**
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $sabores = Sabor::withCount('produtos')
                ->orderBy('id', 'desc')
                ->get();

            return response()->json($sabores);
        }

        return view('sabores.index');
    }

    /**
     * @param SaborRequest $request
     * @return JsonResponse
     */
    public function store(SaborRequest $request): JsonResponse
    {
        $dados = $request->validated();
        if (!isset($dados['ativo'])) {
            $dados['ativo'] = true;
        }

        $sabor = Sabor::create($dados);
        $sabor->loadCount('produtos');

        return response()->json([
            'message' => 'Sabor cadastrado com sucesso!',
            'sabor'   => $sabor,
        ], 201);
    }

    /**
     * @param Sabor $sabor
     * @return JsonResponse
     */
    public function show(Sabor $sabor): JsonResponse
    {
        return response()->json($sabor->loadCount('produtos'));
    }

    /**
     * @param SaborRequest $request
     * @param Sabor $sabor
     * @return JsonResponse
     */
    public function update(SaborRequest $request, Sabor $sabor): JsonResponse
    {
        $dados = $request->validated();
        $sabor->update($dados);
        $sabor->loadCount('produtos');

        return response()->json([
            'message' => 'Sabor atualizado com sucesso!',
            'sabor'   => $sabor,
        ]);
    }

    /**
     * Alterna o status ativo/inativo do sabor
     *
     * @param Sabor $sabor
     * @return JsonResponse
     */
    public function toggleAtivo(Sabor $sabor): JsonResponse
    {
        $sabor->ativo = !$sabor->ativo;
        $sabor->save();

        $status = $sabor->ativo ? 'ativado' : 'desativado';

        return response()->json([
            'message' => "Sabor {$status} com sucesso!",
            'ativo'   => $sabor->ativo,
        ]);
    }

    /**
     * Exclui o sabor ou o inativa caso já esteja vinculado a produtos
     *
     * @param Sabor $sabor
     * @return JsonResponse
     */
    public function destroy(Sabor $sabor): JsonResponse
    {
        if ($sabor->produtos()->exists()) {
            $sabor->ativo = false;
            $sabor->save();

            return response()->json([
                'message' => 'O sabor possui produtos vinculados e foi desativado em vez de excluído.',
                'action'  => 'deactivated',
            ]);
        }

        $sabor->delete();

        return response()->json([
            'message' => 'Sabor excluído com sucesso!',
            'action'  => 'deleted',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Vendedor;
use App\Services\ClienteService;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * ${COMMENT}
     *
     * @var ClienteService
     */
    protected $clienteService;

    /**
     * @param ClienteService $clienteService
     */
    public function __construct(ClienteService $clienteService) {
        $this->clienteService = $clienteService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
     */
    public function index()
    {
        return view('clientes.index');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
     */
    public function create()
    {
        session()->forget('cliente_pendente_pedido');
        $dependencias = $this->clienteService->gerenciarDependencias();

        return view('clientes.create', compact('dependencias'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'vendedor_id' => 'required|integer|exists:vendedores,id',
        ]);

        $vendedor = Vendedor::findOrFail($dados['vendedor_id']);

        session()->put('cliente_pendente_pedido', [
            'id' => null,
            'nome' => trim($dados['nome']),
            'vendedor_id' => (int) $vendedor->id,
            'vendedor' => [
                'id' => (int) $vendedor->id,
                'nome' => $vendedor->nome,
            ],
        ]);

        return redirect()->route('vendas.pedidos.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        //
    }
}

<?php

namespace App\Services;

use App\Models\Bairro;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\Sabor;
use App\Models\Tamanho;
use App\Models\TipoProduto;
use App\Models\Vendedor;
use Exception;
use Illuminate\Support\Facades\DB;

class ClienteService
{
    public function gerenciarDependencias(): array
    {
        $dependencias['clientes'] = Cliente::all();
        $dependencias['vendedores'] = Vendedor::all();

        return $dependencias;
    }

    public function salvarCliente($dados)
    {
        try {
            return DB::transaction(function () use ($dados) {
                $cliente = new Cliente();
                $cliente->nome = $dados['nome'];
                $cliente->vendedor_id = $dados['vendedor_id'];
                $cliente->save();

                return $cliente;
            });
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function gerenciarDependenciasPedido(): array
    {
        $dependencias['produtos'] = Produto::with('tipoProduto', 'sabor', 'tamanho')->where('ativo', true)->get();
        $dependencias['tipoProdutos'] = TipoProduto::where('ativo', true)->get();
        $dependencias['sabores'] = Sabor::where('ativo', true)->get();
        $dependencias['tamanhos'] = Tamanho::where('ativo', true)->get();
        $dependencias['vendedores'] = Vendedor::all();
        $dependencias['bairros'] = Bairro::all();

//        dd($dependencias);

        return $dependencias;
    }
}

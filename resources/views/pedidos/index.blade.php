@extends('layouts.main')

@section('title', 'Vendas')

@section('content')
    <pedidos inline-template
        rota_pedidos="{{ route('vendas.pedidos.index') }}"
        rota_criar_cliente="{{ route('vendas.clientes.create') }}"
        rota_store="{{ route('vendas.pedidos.store') }}">
        @include('pedidos.listagem')
    </pedidos>
@endsection

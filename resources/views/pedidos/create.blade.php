@extends('layouts.main')

@section('title', isset($pedidoId) && $pedidoId ? 'Editar Pedido' : 'Novo Pedido')

@section('content')
    <criar-pedido inline-template
        :pedido-id="{{ isset($pedidoId) && $pedidoId ? json_encode($pedidoId) : 'null' }}"
        :cliente-id="{{ isset($clienteId) && $clienteId ? json_encode($clienteId) : 'null' }}">
        @include('pedidos.partials.criar_pedido')
    </criar-pedido>
@endsection

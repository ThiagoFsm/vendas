@extends('layouts.main')

@section('title', 'Novo pedido')

@section('content')
    <criar-pedido inline-template
        :produtos="{{ json_encode($dependencias['produtos'] ?? []) }}"
        :cliente="{{ json_encode($dependencias['cliente'] ?? []) }}"
        :bairros="{{ json_encode($dependencias['bairros'] ?? []) }}"
        :vendedores="{{ json_encode($dependencias['vendedores'] ?? []) }}">
        @include('pedidos.partials.criar_pedido')
    </criar-pedido>
@endsection

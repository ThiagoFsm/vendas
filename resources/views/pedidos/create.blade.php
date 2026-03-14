@extends('layouts.main')

@section('title', 'Novo pedido')

@section('content')
    <criar-pedido inline-template
        :produtos="{{ json_encode($dependencias['produtos'] ?? []) }}"
        :cliente="{{ json_encode($dependencias['cliente'] ?? []) }}">
        @include('pedidos.partials.criar_pedido')
    </criar-pedido>
@endsection

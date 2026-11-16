@extends('layouts.main')

@section('title', 'Entregas')

@section('content')
    <entregas inline-template
        rota_entregas="{{ route('vendas.entregas.index') }}"
        rota_pedidos="{{ route('vendas.pedidos.index') }}"
        rota_dependencias="{{ route('vendas.entregas.dependencias') }}">
        @include('entregas.listagem')
    </entregas>
@endsection

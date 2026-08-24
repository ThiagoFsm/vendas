@extends('layouts.main')

@section('title', 'Vendas')

@section('content')
    <pedidos inline-template
        rota_criar="{{ route('vendas.pedidos.create') }}"
        rota_store="{{ route('vendas.pedidos.store') }}">
        @include('pedidos.listagem')
    </pedidos>
@endsection

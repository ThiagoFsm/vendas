@extends('layouts.main')

@section('title', 'Vendas')

@section('content')
    <pedidos inline-template
    rota_create="{{ route('vendas.pedidos.create') }}">
        @include('pedidos.listagem')
    </pedidos>
@endsection

@extends('layouts.main')

@section('title', 'Produtos')

@section('content')
    <produtos inline-template
        rota_produtos="{{ route('vendas.produtos.index') }}"
        rota_dependencias="{{ route('vendas.produtos.dependencias') }}"
        rota_store="{{ route('vendas.produtos.store') }}">
        @include('produtos.listagem')
    </produtos>
@endsection

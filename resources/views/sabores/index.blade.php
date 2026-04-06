@extends('layouts.main')

@section('title', 'Vendas')

@section('content')
    <sabores inline-template
        :sabores="{{ json_encode($sabores ?? []) }}"
        :pedidos="{{ json_encode($pedidos ?? []) }}">
        @include('sabores.listagem')
    </sabores>
@endsection

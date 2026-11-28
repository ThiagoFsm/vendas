@extends('layouts.main')

@section('title', 'Produção')

@section('content')
    <producao inline-template
        :sabores="{{ json_encode($sabores ?? []) }}"
        :tamanhos="{{ json_encode($tamanhos ?? []) }}"
        :pedidos="{{ json_encode($pedidos ?? []) }}">
        @include('producao.listagem')
    </producao>
@endsection

@extends('layouts.main')

@section('title', 'Produção')

@section('content')
    <producao inline-template
        :sabores="{{ json_encode($sabores ?? []) }}"
        :pedidos="{{ json_encode($pedidos ?? []) }}">
        @include('producao.listagem')
    </producao>
@endsection

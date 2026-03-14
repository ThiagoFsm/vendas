@extends('layouts.main')

@section('title', 'Clientes')

@section('content')
    <pedidos inline-template
        {{--rota_create="{{ route('pedidos.create') }}"--}}>
        @include('clientes.partials.novo_cliente')
    </pedidos>
@endsection



@extends('layouts.main')

@section('title', 'Retiradas')

@section('content')
    <retiradas inline-template
        rota_retiradas="{{ route('vendas.retiradas.index') }}"
        rota_pedidos="{{ route('vendas.pedidos.index') }}"
        rota_dependencias="{{ route('vendas.retiradas.dependencias') }}">
        @include('retiradas.listagem')
    </retiradas>
@endsection

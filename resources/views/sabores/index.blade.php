@extends('layouts.main')

@section('title', 'Sabores')

@section('content')
    <sabores inline-template
        rota_sabores="{{ route('vendas.sabores.index') }}"
        rota_store="{{ route('vendas.sabores.store') }}">
        @include('sabores.listagem')
    </sabores>
@endsection

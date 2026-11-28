@extends('layouts.main')

@section('title', 'Configurações do Catálogo')

@section('content')
    <configuracoes inline-template
        rota_configuracoes="{{ route('vendas.configuracoes.index') }}"
        rota_salvar="{{ route('vendas.configuracoes.salvar') }}"
        rota_novo_item="{{ route('vendas.configuracoes.novoItem') }}">
        @include('configuracoes.conteudo')
    </configuracoes>
@endsection

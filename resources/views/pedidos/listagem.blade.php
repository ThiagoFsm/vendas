<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Pedidos</h3>
            <h5 class="text-muted">Gerencie as vendas e status de pagamento</h5>
        </div>
        <a href="/vendas/clientes/create" class="btn btn-confirmar" style="border-radius:8px;">
            Novo Pedido
        </a>
    </div>
    <div class="card card-custom">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered-custom mb-0">
                    <thead>
                    <tr class="text-center">
                        <th class="w-1/12">Nº Pedido</th>
                        <th class="w-1/12">Cliente</th>
                        <th class="w-1/12">Vendedor</th>
                        <th class="w-1/12">Qtd</th>
                        <th class="w-1/12">Valor Total</th>
                        <th class="w-1/12">Pagamento</th>
                        <th class="w-1/12">Produção</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!$pedidos->isEmpty())
                        @foreach($pedidos as $pedido)
                            <tr class="text-center">
                                <td class="fw-bold text-dark">{{ $pedido->id }}</td>
                                <td>{{ $pedido->cliente->nome }}</td>
                                <td class="text-muted">{{ $pedido->cliente->vendedor->nome }}</td>
                                <td>{{ $pedido->quantidade_itens }}</td>
                                <td>{{ $pedido->valor_total }}</td>
                                <td>
                                        <span
                                            class="badge-status {{ $pedido->pago ? 'bg-success text-white' : 'bg-warning text-white' }}"
                                            style="border: 2px solid {{ $pedido->pago ? '#c3e6cb' : '#ffeeba' }}; border-radius: 30px">
                                            {{ $pedido->pago ? 'Pago' : 'Pendente' }}
                                        </span>
                                </td>
                                @if($pedido->produzido)
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="d-flex align-items-center justify-content-center gap-1">
{{--                                                <h6 class="m-0">Feito</h6>--}}
                                                <a class="btn-marcar-feito">
                                                    <i class="bi bi-check-all" style="font-size: 25px"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <h6 class="m-0">À fazer</h6>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                                <td class="d-flex justify-content-center gap-1">
                                    <button class="btn-action btn-view"
                                            style="border-color: #2d3238; --btn-hover-bg: #E4E4E4FF;"
                                            @click.prevent="detalhesPedido({{ $pedido->id }})">Detalhes
                                    </button>
{{--                                    @if(!$pedido->produzido)--}}
{{--                                        <button class="btn-action btn-view"--}}
{{--                                                style="color: green; border-color: green; --btn-hover-bg: #C4FDC4FF"--}}
{{--                                                @click.prevent="marcarPedidoComoFeito({{ $pedido->id }})">--}}
{{--                                            Feito--}}
{{--                                        </button>--}}
{{--                                    @endif--}}
                                    <button class="btn-action btn-view"
                                            style="color: deepskyblue; border-color: deepskyblue; --btn-hover-bg: #C2EFFFFF"
                                            @click.prevent="editarPedido({{ $pedido->id }})">Editar
                                    </button>
                                    <button class="btn-action btn-view text-danger"
                                            style="border-color: #fdcece; --btn-hover-bg: #FDCECEFF"
                                            @click.prevent="excluirPedido({{ $pedido->id }})">Excluir
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>
                                <span>Nenhum pedido encontrado.</span>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('pedidos.partials.modalDetalhesPedido')
</div>

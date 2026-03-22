<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Pedidos</h3>
            <h5 class="text-muted">Gerencie as vendas e status de pagamento</h5>
        </div>
        <a href="/vendas/clientes/create" class="btn btn-outline-secondary px-4 text-black" style="border-radius: 8px;">
            Novo pedido
        </a>
    </div>
{{--    {{ dd($pedidos[6]) }}--}}
    <div class="card card-custom">
        <div class="card-body p-0"> <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered-custom mb-0">
                    <thead>
                        <tr class="text-center">
                            <th class="w-1/8">Nº Pedido</th>
                            <th class="w-1/8">Cliente</th>
                            <th class="w-1/8">Vendedor</th>
                            <th class="w-1/8">Qtd</th>
                            <th class="w-1/8">Valor Total</th>
                            <th class="w-1/8">Status</th>
                            <th class="w-1/8">Entrega/Retirada</th>
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
                                            class="badge-status {{ $pedido->pago ? 'bg-success text-white' : 'bg-light text-warning' }}"
                                            style="border: 1px solid {{ $pedido->pago ? '#c3e6cb' : '#ffeeba' }};">
                                            {{ $pedido->pago ? 'Pago' : 'Pendente' }}
                                        </span>
                                    </td>
                                    <td>{{ isset($pedido->entrega_retirada->rua) ? 'Entrega' : 'Retirada' }}</td>
                                    <td class="d-flex gap-1">
                                        <button class="btn-action btn-view" @click.prevent="detalhesPedido({{ $pedido->id }})">Detalhes</button>
                                        <button class="btn-action btn-view text-info">Editar</button>
                                        <button class="btn-action btn-view text-danger" @click.prevent="marcarPedidoComoPago({{ $pedido->id }})">Excluir</button>
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

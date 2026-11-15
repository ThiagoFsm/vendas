<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Pedidos</h3>
            <h5 class="text-muted">Gerencie as vendas e status de pagamento</h5>
        </div>
        <a :href="rota_criar" class="btn btn-confirmar" style="border-radius:8px;" @click.prevent="criarPedido">
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
                    <tr v-if="carregando">
                        <td colspan="8" class="text-center py-4 text-muted">
                            <span>Carregando pedidos...</span>
                        </td>
                    </tr>
                    <tr v-else-if="!pedidos || pedidos.length === 0">
                        <td colspan="8" class="text-center py-4 text-muted">
                            <span>Nenhum pedido encontrado.</span>
                        </td>
                    </tr>
                    <template v-else>
                        <tr v-for="pedido in pedidos" :key="pedido.id" class="text-center">
                            <td class="fw-bold text-dark">@{{ pedido.id }}</td>
                            <td>@{{ pedido.cliente ? pedido.cliente.nome : '-' }}</td>
                            <td class="text-muted">@{{ pedido.cliente && pedido.cliente.vendedor ? pedido.cliente.vendedor.nome : '-' }}</td>
                            <td>@{{ pedido.quantidade_itens }}</td>
                            <td>@{{ pedido.valor_total }}</td>
                            <td>
                                <span
                                    class="badge-status"
                                    :class="pedido.pago ? 'bg-success text-white' : 'bg-warning text-white'"
                                    :style="{
                                        border: '2px solid ' + (pedido.pago ? '#c3e6cb' : '#ffeeba'),
                                        borderRadius: '30px'
                                    }">
                                    @{{ pedido.pago ? 'Pago' : 'Pendente' }}
                                </span>
                            </td>
                            <td v-if="pedido.produzido">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <a class="btn-marcar-feito">
                                            <i class="bi bi-check-all" style="font-size: 25px"></i>
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td v-else>
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <h6 class="m-0">À fazer</h6>
                                    </div>
                                </div>
                            </td>
                            <td class="d-flex justify-content-center gap-1">
                                <button class="btn-action btn-view"
                                        style="border-color: #2d3238; --btn-hover-bg: #E4E4E4FF;"
                                        @click.prevent="detalhesPedido(pedido.id)">Detalhes
                                </button>
                                <button class="btn-action btn-view"
                                        style="color: deepskyblue; border-color: deepskyblue; --btn-hover-bg: #C2EFFFFF"
                                        @click.prevent="editarPedido(pedido.id)">Editar
                                </button>
                                <button class="btn-action btn-view text-danger"
                                        style="border-color: #fdcece; --btn-hover-bg: #FDCECEFF"
                                        @click.prevent="excluirPedido(pedido.id)">Excluir
                                </button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('pedidos.partials.modalDetalhesPedido')
</div>

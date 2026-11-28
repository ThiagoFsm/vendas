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
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <!-- Barra de Pesquisa de Cliente -->
                <div class="position-relative" style="width: 360px; max-width: 100%; flex-shrink: 0;">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input
                        type="text"
                        class="form-control ps-5 pe-5"
                        placeholder="Buscar pelo nome do cliente..."
                        v-model="termoPesquisa"
                        style="border-radius: 8px; border: 1.5px solid #d1d5db; min-height: 48px;"
                    >
                    <button
                        v-if="termoPesquisa"
                        type="button"
                        class="btn btn-sm btn-link position-absolute top-50 end-0 translate-middle-y text-muted text-decoration-none me-2"
                        @click="termoPesquisa = ''"
                        title="Limpar busca"
                    >
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <!-- Filtro Vendedor -->
                <div style="width: 260px; max-width: 100%; flex-shrink: 0;">
                    <select
                        class="form-select"
                        v-model="filtroVendedor"
                        style="border-radius: 8px; border: 1.5px solid #d1d5db; min-height: 48px;"
                    >
                        <option value="">Todos os Vendedores</option>
                        <option v-for="vendedor in vendedores" :key="vendedor.id" :value="vendedor.id">
                            @{{ vendedor.nome }}
                        </option>
                    </select>
                </div>

                <!-- Filtro Produção -->
                <div style="width: 220px; max-width: 100%; flex-shrink: 0;">
                    <select
                        class="form-select"
                        v-model="filtroProducao"
                        style="border-radius: 8px; border: 1.5px solid #d1d5db; min-height: 48px;"
                    >
                        <option value="">Toda Produção</option>
                        <option value="a_fazer">À fazer</option>
                        <option value="feito">Feito</option>
                    </select>
                </div>

                <!-- Botão Limpar Filtros -->
                <button
                    v-if="hasFiltrosAtivos"
                    type="button"
                    class="btn btn-outline-secondary d-flex align-items-center gap-1 text-nowrap"
                    style="border-radius: 8px; border: 1.5px solid #d1d5db; min-height: 48px; flex-shrink: 0;"
                    @click="limparFiltros"
                    title="Limpar todos os filtros"
                >
                    <i class="bi bi-x-circle"></i>
                    <span>Limpar</span>
                </button>
            </div>

            <div class="text-muted small">
                <span>Total: <strong>@{{ pedidosFiltrados.length }}</strong> de <strong>@{{ pedidos.length }}</strong> pedido(s)</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered-custom mb-0">
                    <thead>
                    <tr class="text-center">
                        <th style="width: 120px;">Nº Pedido</th>
                        <th class="text-start">Cliente</th>
                        <th>Vendedor</th>
                        <th style="width: 90px;">Qtd</th>
                        <th>Valor Total</th>
                        <th>Pagamento</th>
                        <th>Produção</th>
                        <th>Entrega/Retirada</th>
                        <th style="width: 290px;">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="carregando">
                        <td colspan="9" class="text-center py-4 text-muted">
                            <span>Carregando pedidos...</span>
                        </td>
                    </tr>
                    <tr v-else-if="!pedidos || pedidos.length === 0">
                        <td colspan="9" class="text-center py-4 text-muted">
                            <span>Nenhum pedido encontrado.</span>
                        </td>
                    </tr>
                    <tr v-else-if="pedidosFiltrados.length === 0">
                        <td colspan="9" class="text-center py-4 text-muted">
                            <span v-if="termoPesquisa">Nenhum pedido encontrado para o cliente "<strong>@{{ termoPesquisa }}</strong>".</span>
                            <span v-else>Nenhum pedido encontrado com os filtros selecionados.</span>
                        </td>
                    </tr>
                    <template v-else>
                        <tr v-for="pedido in pedidosFiltrados" :key="pedido.id" class="text-center">
                            <td class="fw-bold text-dark">@{{ pedido.id }}</td>
                            <td class="text-start">@{{ pedido.cliente ? pedido.cliente.nome : '-' }}</td>
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
                                            <i class="bi bi-check-all" style="font-size: 30px"></i>
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
                            <td>
                                <div v-if="pedido.entrega_retirada && pedido.entrega_retirada.realizada" class="d-flex align-items-center justify-content-center">
                                    <a class="btn-marcar-feito"
                                       :title="pedido.entrega_retirada_type && pedido.entrega_retirada_type.includes('Retirada') ? 'Retirado' : 'Entregue'">
                                        <i class="bi bi-check-all" style="font-size: 30px"></i>
                                    </a>
                                </div>
                                <div v-else-if="pedido.entrega_retirada" class="d-flex align-items-center justify-content-center">
                                    <h6 class="m-0">
                                        @{{ pedido.entrega_retirada_type && pedido.entrega_retirada_type.includes('Retirada') ? 'À retirar' : 'À entregar' }}
                                    </h6>
                                </div>
                                <span v-else class="text-muted">-</span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
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
                                </div>
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

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Entregas</h3>
            <h5 class="text-muted">Acompanhe as entregas e despachos de pedidos</h5>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-white text-dark border px-3 py-2 d-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-truck text-primary"></i>
                Total: <strong>@{{ entregas.length }}</strong>
            </span>
        </div>
    </div>

    <div class="card card-custom">
        <!-- Barra de Filtros e Busca -->
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <!-- Barra de Pesquisa -->
                <div class="position-relative" style="width: 280px; flex-shrink: 0;">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input
                        type="text"
                        class="form-control ps-5"
                        placeholder="Buscar por cliente, endereço ou entregador..."
                        v-model="termoPesquisa"
                        style="border-radius: 8px; border: 1.5px solid #d1d5db;"
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

                <!-- Filtro Tipo de Entrega -->
                <div style="width: 160px; flex-shrink: 0;">
                    <select
                        class="form-select"
                        v-model="filtroTipo"
                        style="border-radius: 8px; border: 1.5px solid #d1d5db;"
                    >
                        <option value="">Todos os Tipos</option>
                        <option value="uber">Uber</option>
                        <option value="entregador">Entregador</option>
                    </select>
                </div>

                <!-- Filtro Período -->
                <div style="width: 150px; flex-shrink: 0;">
                    <select
                        class="form-select"
                        v-model="filtroPeriodo"
                        style="border-radius: 8px; border: 1.5px solid #d1d5db;"
                    >
                        <option value="">Todos Períodos</option>
                        <option value="Manhã">Manhã</option>
                        <option value="Tarde">Tarde</option>
                        <option value="Noite">Noite</option>
                    </select>
                </div>

                <!-- Filtro Data -->
                <div style="width: 165px; flex-shrink: 0;">
                    <input
                        type="date"
                        class="form-control"
                        v-model="filtroData"
                        title="Filtrar por data"
                        style="border-radius: 8px; border: 1.5px solid #d1d5db;"
                    >
                </div>

                <!-- Filtro Status Pedido -->
                <div style="width: 160px; flex-shrink: 0;">
                    <select
                        class="form-select"
                        v-model="filtroStatusPedido"
                        style="border-radius: 8px; border: 1.5px solid #d1d5db;"
                    >
                        <option value="">Status Pedido</option>
                        <option value="pago">Pago</option>
                        <option value="pendente">Pagamento Pendente</option>
                        <option value="produzido">Produzido</option>
                        <option value="a_fazer">À Fazer</option>
                    </select>
                </div>

                <!-- Botão Limpar Filtros -->
                <button
                    v-if="hasFiltrosAtivos"
                    type="button"
                    class="btn btn-outline-secondary d-flex align-items-center gap-1 text-nowrap"
                    style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 38px; flex-shrink: 0;"
                    @click="limparFiltros"
                    title="Limpar todos os filtros"
                >
                    <i class="bi bi-x-circle"></i>
                    <span>Limpar</span>
                </button>
            </div>

            <div class="text-muted small">
                <span>Total: <strong>@{{ entregasFiltradas.length }}</strong> de <strong>@{{ entregas.length }}</strong> entrega(s)</span>
            </div>
        </div>

        <!-- Tabela de Entregas -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered-custom mb-0">
                    <thead>
                    <tr class="text-center">
                        <th style="width: 70px;">ID</th>
                        <th class="text-start">Cliente</th>
                        <th>Entregador (a)</th>
                        <th class="text-start">Endereço</th>
                        <th>Data / Período / Hora</th>
                        <th>Valor Pedido</th>
                        <th>Status Pedido</th>
                        <th style="width: 110px;">Realizada</th>
                        <th style="width: 190px;">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="carregando">
                        <td colspan="9" class="text-center py-5 text-muted">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            <span>Carregando entregas...</span>
                        </td>
                    </tr>
                    <tr v-else-if="!entregas || entregas.length === 0">
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                            <span>Nenhuma entrega registrada.</span>
                        </td>
                    </tr>
                    <tr v-else-if="entregasFiltradas.length === 0">
                        <td colspan="9" class="text-center py-5 text-muted">
                            <span v-if="termoPesquisa">Nenhuma entrega encontrada para "<strong>@{{ termoPesquisa }}</strong>".</span>
                            <span v-else>Nenhuma entrega encontrada com os filtros selecionados.</span>
                        </td>
                    </tr>
                    <template v-else>
                        <tr v-for="entrega in entregasFiltradas" :key="entrega.id" class="text-center">
                            <td>@{{ entrega.id }}</td>
                            <td class="text-start">
                                <span class="text-dark d-block">
                                    @{{ entrega.pedido && entrega.pedido.cliente ? entrega.pedido.cliente.nome : '-' }}
                                </span>
                                <span class="text-muted small" v-if="entrega.pedido && entrega.pedido.cliente && entrega.pedido.cliente.telefone">
                                    <i class="bi bi-telephone me-1"></i>@{{ entrega.pedido.cliente.telefone }}
                                </span>
                            </td>
                            <td>
                                <span v-if="tipoEntrega(entrega) === 'uber'" class="badge bg-dark text-white px-2 py-1">
                                    <i class="bi bi-car-front-fill me-1"></i> Uber
                                </span>
                                <span v-else class="badge bg-primary text-white px-2 py-1">
                                    <i class="bi bi-bicycle me-1"></i> @{{ entrega.entregador ? entrega.entregador.nome : 'Entregador' }}
                                </span>
                            </td>
                            <td class="text-start">
                                <span class="text-dark">@{{ enderecoCompleto(entrega) }}</span>
                            </td>
                            <td>
                                <span class="d-block">@{{ formatarData(entrega.data) }}</span>
                                <span class="badge bg-light text-dark border" style="font-size: 0.75rem;">
                                    @{{ entrega.periodo || '-' }}
                                </span>
                                <small v-if="entrega.horas" class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                    <i class="bi bi-clock me-1"></i>@{{ formatarHora(entrega.horas) }}
                                </small>
                            </td>
                            <td>
                                <span class="text-dark">
                                    @{{ formatarValor(entrega.pedido ? entrega.pedido.valor_total : 0) }}
                                </span>
                                <span v-if="entrega.valor_uber" class="small text-muted d-block">
                                    Uber: @{{ formatarValor(entrega.valor_uber) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1 align-items-center" v-if="entrega.pedido">
                                    <span
                                        class="badge-status"
                                        :class="entrega.pedido.pago ? 'bg-success text-white' : 'bg-warning text-white'"
                                        :style="{
                                            border: '2px solid ' + (entrega.pedido.pago ? '#c3e6cb' : '#ffeeba'),
                                            borderRadius: '30px',
                                            padding: '0.25rem 0.6rem'
                                        }">
                                        @{{ entrega.pedido.pago ? 'Pago' : 'Pendente' }}
                                    </span>
                                    <span
                                        class="badge"
                                        :class="entrega.pedido.produzido ? 'bg-success text-white' : 'bg-secondary text-white'"
                                        style="font-size: 0.7rem; border-radius: 30px;">
                                        @{{ entrega.pedido.produzido ? 'Produzido' : 'À fazer' }}
                                    </span>
                                </div>
                                <span v-else class="text-muted">-</span>
                            </td>
                            <td>
                                <span
                                    class="badge"
                                    :class="entrega.realizada ? 'bg-success text-white' : 'bg-secondary text-white'"
                                    style="border-radius: 30px; font-size: 0.75rem; padding: 0.35rem 0.6rem;">
                                    <i class="bi" :class="entrega.realizada ? 'bi-check2-circle me-1' : 'bi-clock me-1'"></i>
                                    @{{ entrega.realizada ? 'Realizada' : 'Pendente' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <button
                                        class="btn-action btn-view"
                                        style="border-color: #2d3238; --btn-hover-bg: #E4E4E4FF;"
                                        @click.prevent="abrirModalDetalhes(entrega)"
                                        title="Ver detalhes da entrega"
                                    >
                                        <i class="bi bi-eye me-1"></i> Detalhes
                                    </button>
                                    <button
                                        class="btn-action btn-view"
                                        style="color: #0d6efd; border-color: #0d6efd; --btn-hover-bg: #E7F1FFFF;"
                                        @click.prevent="abrirModalEditar(entrega)"
                                        title="Editar agendamento e dados da entrega"
                                    >
                                        <i class="bi bi-pencil-square me-1"></i> Editar
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

    @include('entregas.partials.modalDetalhesEntrega')
    @include('entregas.partials.modalEditarEntrega')
</div>

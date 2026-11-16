<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Retiradas</h3>
            <h5 class="text-muted">Acompanhe e gerencie as retiradas de pedidos no local</h5>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-white text-dark border px-3 py-2 d-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-shop text-info"></i>
                Total: <strong>@{{ retiradas.length }}</strong>
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
                        placeholder="Buscar por cliente, local ou pedido..."
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

                <!-- Filtro Bairro / Local -->
                <div style="width: 170px; flex-shrink: 0;">
                    <select
                        class="form-select"
                        v-model="filtroBairro"
                        style="border-radius: 8px; border: 1.5px solid #d1d5db;"
                    >
                        <option value="">Todos os Locais</option>
                        <option v-for="bairro in bairrosUnicos" :key="bairro" :value="bairro">
                            @{{ bairro }}
                        </option>
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
                        <option value="a_fazer">Produção À Fazer</option>
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
                <span>Total: <strong>@{{ retiradasFiltradas.length }}</strong> de <strong>@{{ retiradas.length }}</strong> retirada(s)</span>
            </div>
        </div>

        <!-- Tabela de Retiradas -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered-custom mb-0">
                    <thead>
                    <tr class="text-center">
                        <th style="width: 70px;">ID</th>
                        <th class="text-start">Cliente</th>
                        <th class="text-start">Local / Bairro</th>
                        <th>Data Prevista</th>
                        <th>Período</th>
                        <th>Valor Pedido</th>
                        <th>Status Pedido</th>
                        <th style="width: 190px;">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="carregando">
                        <td colspan="8" class="text-center py-5 text-muted">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            <span>Carregando retiradas...</span>
                        </td>
                    </tr>
                    <tr v-else-if="!retiradas || retiradas.length === 0">
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                            <span>Nenhuma retirada registrada.</span>
                        </td>
                    </tr>
                    <tr v-else-if="retiradasFiltradas.length === 0">
                        <td colspan="8" class="text-center py-5 text-muted">
                            <span v-if="termoPesquisa">Nenhuma retirada encontrada para "<strong>@{{ termoPesquisa }}</strong>".</span>
                            <span v-else>Nenhuma retirada encontrada com os filtros selecionados.</span>
                        </td>
                    </tr>
                    <template v-else>
                        <tr v-for="retirada in retiradasFiltradas" :key="retirada.id" class="text-center">
                            <td>@{{ retirada.id }}</td>
                            <td class="text-start">
                                <span class="text-dark d-block">
                                    @{{ retirada.pedido && retirada.pedido.cliente ? retirada.pedido.cliente.nome : '-' }}
                                </span>
                                <span class="text-muted small" v-if="retirada.pedido && retirada.pedido.cliente && retirada.pedido.cliente.telefone">
                                    <i class="bi bi-telephone me-1"></i>@{{ retirada.pedido.cliente.telefone }}
                                </span>
                            </td>
                            <td class="text-start">
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    <i class="bi bi-geo-alt text-danger me-1"></i> @{{ retirada.bairro || 'Local não informado' }}
                                </span>
                            </td>
                            <td>
                                <span>@{{ formatarData(retirada.data) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border" style="font-size: 0.75rem;">
                                    @{{ retirada.periodo || '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-dark">
                                    @{{ formatarValor(retirada.pedido ? retirada.pedido.valor_total : 0) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1 align-items-center" v-if="retirada.pedido">
                                    <span
                                        class="badge-status"
                                        :class="retirada.pedido.pago ? 'bg-success text-white' : 'bg-warning text-white'"
                                        :style="{
                                            border: '2px solid ' + (retirada.pedido.pago ? '#c3e6cb' : '#ffeeba'),
                                            borderRadius: '30px',
                                            padding: '0.25rem 0.6rem'
                                        }">
                                        @{{ retirada.pedido.pago ? 'Pago' : 'Pendente' }}
                                    </span>
                                    <span
                                        class="badge"
                                        :class="retirada.pedido.produzido ? 'bg-success text-white' : 'bg-secondary text-white'"
                                        style="font-size: 0.7rem; border-radius: 30px;">
                                        @{{ retirada.pedido.produzido ? 'Produzido' : 'À fazer' }}
                                    </span>
                                </div>
                                <span v-else class="text-muted">-</span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <button
                                        class="btn-action btn-view"
                                        style="border-color: #2d3238; --btn-hover-bg: #E4E4E4FF;"
                                        @click.prevent="abrirModalDetalhes(retirada)"
                                        title="Ver detalhes da retirada"
                                    >
                                        <i class="bi bi-eye me-1"></i> Detalhes
                                    </button>
                                    <button
                                        class="btn-action btn-view"
                                        style="color: #0d6efd; border-color: #0d6efd; --btn-hover-bg: #E7F1FFFF;"
                                        @click.prevent="abrirModalEditar(retirada)"
                                        title="Editar agendamento e local da retirada"
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

    @include('retiradas.partials.modalDetalhesRetirada')
    @include('retiradas.partials.modalEditarRetirada')
</div>

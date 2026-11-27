<div v-if="modal" v-cloak class="modal-overlay">
    <div class="modal-content-custom" style="width: 95%; max-width: 1100px; height: 92vh; max-height: 940px; display: flex; flex-direction: column;">
        <div class="modal-header-custom">
            <h3 class="m-0">Detalhes de Produção</h3>
            <span @click.prevent="fecharModal" style="cursor:pointer; font-size: 1.5625rem;" title="Fechar">
                <i class="bi bi-x-lg"></i>
            </span>
        </div>

        <!-- Barra de Pesquisa e Filtro de Produção -->
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <!-- Pesquisa por Cliente -->
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

                <!-- Botão Limpar -->
                <button
                    v-if="hasFiltrosAtivos"
                    type="button"
                    class="btn btn-outline-secondary d-flex align-items-center gap-1 text-nowrap"
                    style="border-radius: 8px; border: 1.5px solid #d1d5db; min-height: 48px; flex-shrink: 0;"
                    @click="limparFiltros"
                    title="Limpar filtros"
                >
                    <i class="bi bi-x-circle"></i>
                    <span>Limpar</span>
                </button>
            </div>

            <div class="text-muted small" v-if="dadosModal && dadosModal.length">
                <span>Exibindo: <strong>@{{ dadosModalFiltrados.length }}</strong> de <strong>@{{ dadosModal.length }}</strong> item(ns)</span>
            </div>
        </div>

        <div class="modal-body-scrollable flex-grow-1" style="min-height: 0; max-height: none !important; overflow-y: auto; overflow-x: auto; padding: 0;">
            <div v-if="!dadosModal || dadosModal.length === 0" class="d-flex align-items-center justify-content-center h-100 py-5 text-muted">
                <span>Nenhum item encontrado.</span>
            </div>
            <div v-else-if="dadosModalFiltrados.length === 0" class="d-flex align-items-center justify-content-center h-100 py-5 text-muted">
                <span v-if="termoPesquisa">Nenhum item encontrado para o cliente "<strong>@{{ termoPesquisa }}</strong>".</span>
                <span v-else>Nenhum item encontrado com os filtros selecionados.</span>
            </div>
            <table v-else class="table align-middle mb-0 table-sticky" style="border-collapse: separate; border-spacing: 0; width: 100%;">
                <thead>
                <tr class="text-center">
                    <th class="p-3 text-uppercase">Quantidade</th>
                    <th class="p-3 text-uppercase">Tamanho</th>
                    <th class="p-3 text-uppercase">Cliente</th>
                    <th class="p-3 text-uppercase">Produção</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(pedido, idx) in dadosModalFiltrados" :key="idx" class="text-center">
                    <td class="p-2">@{{ pedido.produtos[0].pivot.quantidade }}</td>
                    <td class="p-2">@{{ pedido.produtos[0].tamanho.descricao }}</td>
                    <td class="p-2">@{{ pedido.cliente.nome }}</td>
                    <td>
                        <div class="d-flex gap-1 justify-content-center">
                            <button v-if="!pedido.produtos[0].pivot.produzido" class="btn-action btn-view"
                                    style="color: green; --btn-hover-bg: #C4FDC4FF; border-color: green"
                                    @click.prevent="marcarProdutoComoFeito(pedido.id, pedido.produtos[0].id)">
                                Marcar como feito
                            </button>
                            <a v-else class="btn-marcar-feito">
                                <i class="bi bi-check-all" style="font-size: 30px"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer da Modal com Totais e Botão Fechar integrados -->
        <div class="modal-footer-custom d-flex justify-content-between align-items-center flex-wrap gap-2 px-3 py-2" style="flex-shrink: 0; background-color: #212529; color: #ffffff; border-top: 1px solid #373b3e;">
            <div class="d-flex align-items-center flex-wrap gap-3 fw-bold">
                <div>
                    <span class="text-muted fw-normal">Total de itens: </span>
                    <span class="badge bg-secondary fs-6">@{{ totalItens }}</span>
                </div>
                <div>
                    <span class="text-muted fw-normal">Total de itens feitos: </span>
                    <span class="badge bg-success fs-6">@{{ totalItensFeitos }}</span>
                </div>
                <div>
                    <span class="text-muted fw-normal">Total de itens à fazer: </span>
                    <span class="badge bg-warning text-dark fs-6">@{{ totalItensAFazer }}</span>
                </div>
            </div>
            <div>
                <button class="btn btn-fechar m-0" @click="fecharModal">Fechar</button>
            </div>
        </div>
    </div>
</div>

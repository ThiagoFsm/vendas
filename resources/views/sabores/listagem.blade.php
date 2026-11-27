<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Sabores</h3>
            <h5 class="text-muted">Gerencie o catálogo de sabores disponíveis</h5>
        </div>
        <button class="btn btn-confirmar" style="border-radius: 8px;" @click.prevent="abrirModalCriar">
            Novo Sabor
        </button>
    </div>

    <div class="card card-custom">
        <!-- Barra de Filtros e Busca -->
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <!-- Pesquisa de Texto -->
                <div class="position-relative" style="width: 360px; max-width: 100%; flex-shrink: 0;">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input
                        type="text"
                        class="form-control ps-5 pe-5"
                        placeholder="Buscar pelo nome do sabor..."
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

                <!-- Filtro Status -->
                <div style="width: 200px; max-width: 100%; flex-shrink: 0;">
                    <select
                        class="form-select"
                        v-model="filtroStatus"
                        style="border-radius: 8px; border: 1.5px solid #d1d5db; min-height: 48px;"
                    >
                        <option value="">Todos Status</option>
                        <option value="ativo">Ativos</option>
                        <option value="inativo">Inativos</option>
                    </select>
                </div>

                <!-- Botão Limpar Filtros -->
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

            <div class="text-muted small">
                <span>Total: <strong>@{{ saboresFiltrados.length }}</strong> de <strong>@{{ sabores.length }}</strong> sabor(es)</span>
            </div>
        </div>

        <!-- Tabela de Sabores -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered-custom mb-0">
                    <thead>
                    <tr class="text-center">
                        <th style="width: 80px;">ID</th>
                        <th class="text-start">Descrição do Sabor</th>
                        <th>Produtos Vinculados</th>
                        <th>Status</th>
                        <th style="width: 230px;">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="carregando">
                        <td colspan="5" class="text-center py-5 text-muted">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            <span>Carregando sabores...</span>
                        </td>
                    </tr>
                    <tr v-else-if="!sabores || sabores.length === 0">
                        <td colspan="5" class="text-center py-5 text-muted">
                            <span>Nenhum sabor cadastrado.</span>
                        </td>
                    </tr>
                    <tr v-else-if="saboresFiltrados.length === 0">
                        <td colspan="5" class="text-center py-5 text-muted">
                            <span v-if="termoPesquisa">Nenhum sabor encontrado para "<strong>@{{ termoPesquisa }}</strong>".</span>
                            <span v-else>Nenhum sabor encontrado com os filtros selecionados.</span>
                        </td>
                    </tr>
                    <template v-else>
                        <tr v-for="sabor in saboresFiltrados" :key="sabor.id" class="text-center">
                            <td>@{{ sabor.id }}</td>
                            <td class="text-start">
                                <span class="d-inline-block me-2" :style="{ width: '12px', height: '12px', backgroundColor: GerarCorSabor(sabor.id) }"></span>
                                @{{ sabor.descricao }}
                            </td>
                            <td>
                                <span>
                                    @{{ sabor.produtos_count || 0 }} produto(s)
                                </span>
                            </td>
                            <td>
                                <span
                                    class="badge-status"
                                    :class="sabor.ativo ? 'bg-success text-white' : 'bg-secondary text-white'"
                                    :style="{
                                        border: '2px solid ' + (sabor.ativo ? '#c3e6cb' : '#d6d8db'),
                                        borderRadius: '30px',
                                        cursor: 'pointer'
                                    }"
                                    @click="toggleAtivo(sabor)"
                                    :title="sabor.ativo ? 'Clique para desativar' : 'Clique para ativar'"
                                >
                                    @{{ sabor.ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <button
                                        class="btn-action btn-view"
                                        style="color: deepskyblue; border-color: deepskyblue; --btn-hover-bg: #C2EFFFFF;"
                                        @click="abrirModalEditar(sabor)"
                                        title="Editar sabor"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        class="btn-action btn-view text-danger"
                                        style="border-color: #fdcece; --btn-hover-bg: #FDCECEFF;"
                                        @click="excluirSabor(sabor)"
                                        title="Excluir sabor"
                                    >
                                        Excluir
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

    <!-- Modal de Cadastro / Edição -->
    @include('sabores.partials.modalSabor')
</div>

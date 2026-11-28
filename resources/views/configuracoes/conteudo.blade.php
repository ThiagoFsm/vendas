<div class="container-fluid py-2">
    <div class="config-header">
        <div>
            <h2 class="config-title">CONFIGURAÇÕES DO CATÁLOGO</h2>
            <p class="config-subtitle">Gerencie as categorias, produtos, sabores e tamanhos</p>
        </div>

        <button type="button" class="btn-novo-item" @click.prevent="abrirModalNovoItem">
            + Novo Item
        </button>
    </div>

    <div class="config-shell">
        <!-- Menu Lateral de Abas -->
        <div class="config-tabs" role="tablist" aria-label="Abas de configurações do catálogo">
            <button
                v-for="aba in abas"
                :key="aba.id"
                type="button"
                role="tab"
                class="config-tab"
                :class="{ active: abaAtiva === aba.id }"
                :aria-selected="abaAtiva === aba.id ? 'true' : 'false'"
                @click="selecionarAba(aba.id)"
            >
                @{{ aba.titulo }}
            </button>
        </div>

        <!-- Painel de Conteúdo da Aba -->
        <div class="config-panel">
            <div class="config-panel-header">
                <h4>@{{ abaAtual.titulo }}</h4>
                <p>@{{ abaAtual.descricao }}</p>
            </div>

            <!-- Estado de Carregamento -->
            <div v-if="carregando" class="text-center py-5 text-muted flex-grow-1 d-flex flex-column justify-content-center align-items-center">
                <span class="spinner-border spinner-border-sm mb-2" role="status"></span>
                <span>Carregando configurações do catálogo...</span>
            </div>

            <template v-else>
                <!-- Barra de Busca/Filtro para Abas com Listas Maiores -->
                <div v-if="abaAtiva === 'produtos' || abaAtiva === 'valores' || abaAtiva === 'sabores'" class="config-filter-bar">
                    <div class="position-relative" style="width: 320px; max-width: 100%;">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input
                            type="text"
                            class="form-control ps-5 pe-5"
                            v-model="termoBusca"
                            :placeholder="abaAtiva === 'sabores' ? 'Buscar sabor...' : 'Buscar por tipo, sabor ou tamanho...'"
                            style="border-radius: 8px; border: 1.5px solid #d1d5db; min-height: 44px;"
                        >
                        <button
                            v-if="termoBusca"
                            type="button"
                            class="btn btn-sm btn-link position-absolute top-50 end-0 translate-middle-y text-muted text-decoration-none me-2"
                            @click="termoBusca = ''"
                            title="Limpar busca"
                        >
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <div v-if="abaAtiva === 'produtos' || abaAtiva === 'valores'" style="width: 240px; max-width: 100%;">
                        <select
                            class="form-select"
                            v-model="filtroTipoProduto"
                            style="border-radius: 8px; border: 1.5px solid #d1d5db; min-height: 44px;"
                        >
                            <option value="">Todos os Tipos</option>
                            <option v-for="tipo in tipoProdutos" :key="tipo.id" :value="tipo.id">
                                @{{ tipo.descricao }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- ABA 1: Tipos de Produtos -->
                <div v-if="abaAtiva === 'tipos'" class="config-items-list">
                    <div v-if="tipoProdutos.length === 0" class="text-center py-5 text-muted">
                        Nenhum tipo de produto cadastrado.
                    </div>

                    <div
                        v-for="tipo in tipoProdutos"
                        :key="tipo.id"
                        class="config-item-card"
                        :class="{ 'is-inativo': !tipo.ativo }"
                    >
                        <div class="config-item-left">
                            <span class="config-item-grip" aria-hidden="true">
                                <svg width="14" height="22" viewBox="0 0 14 22" fill="currentColor">
                                    <circle cx="3.5" cy="4" r="1.8" />
                                    <circle cx="10.5" cy="4" r="1.8" />
                                    <circle cx="3.5" cy="11" r="1.8" />
                                    <circle cx="10.5" cy="11" r="1.8" />
                                    <circle cx="3.5" cy="18" r="1.8" />
                                    <circle cx="10.5" cy="18" r="1.8" />
                                </svg>
                            </span>
                            <div class="config-item-info">
                                <div class="config-item-name">@{{ tipo.descricao }}</div>
                            </div>
                        </div>

                        <div class="config-item-right">
                            <label class="config-switch-wrapper" :title="tipo.ativo ? 'Clique para inativar' : 'Clique para ativar'">
                                <input
                                    type="checkbox"
                                    class="config-switch-input"
                                    v-model="tipo.ativo"
                                >
                                <span class="config-switch-track"></span>
                                <span class="config-status-label" :class="tipo.ativo ? 'is-ativo' : 'is-inativo'">
                                    @{{ tipo.ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- ABA 2: Produtos -->
                <div v-if="abaAtiva === 'produtos'" class="config-items-list">
                    <div v-if="produtosFiltrados.length === 0" class="text-center py-5 text-muted">
                        Nenhum produto encontrado.
                    </div>

                    <div
                        v-for="produto in produtosFiltrados"
                        :key="produto.id"
                        class="config-item-card"
                        :class="{ 'is-inativo': !produto.ativo }"
                    >
                        <div class="config-item-left">
                            <span class="config-item-grip" aria-hidden="true">
                                <svg width="14" height="22" viewBox="0 0 14 22" fill="currentColor">
                                    <circle cx="3.5" cy="4" r="1.8" />
                                    <circle cx="10.5" cy="4" r="1.8" />
                                    <circle cx="3.5" cy="11" r="1.8" />
                                    <circle cx="10.5" cy="11" r="1.8" />
                                    <circle cx="3.5" cy="18" r="1.8" />
                                    <circle cx="10.5" cy="18" r="1.8" />
                                </svg>
                            </span>
                            <div class="config-item-info">
                                <div class="config-item-name">
                                    @{{ produto.sabor ? produto.sabor.descricao : 'Sem sabor' }}
                                    <span v-if="produto.tamanho">(@{{ produto.tamanho.descricao }})</span>
                                </div>
                                <div class="config-item-meta">
                                    @{{ produto.tipo_produto ? produto.tipo_produto.descricao : '-' }}
                                    &bull;
                                    R$ @{{ formatarValor(produto.valor_produto) }}
                                </div>
                            </div>
                        </div>

                        <div class="config-item-right">
                            <label class="config-switch-wrapper" :title="produto.ativo ? 'Clique para inativar' : 'Clique para ativar'">
                                <input
                                    type="checkbox"
                                    class="config-switch-input"
                                    v-model="produto.ativo"
                                >
                                <span class="config-switch-track"></span>
                                <span class="config-status-label" :class="produto.ativo ? 'is-ativo' : 'is-inativo'">
                                    @{{ produto.ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- ABA 3: Sabores -->
                <div v-if="abaAtiva === 'sabores'" class="config-items-list">
                    <div v-if="saboresFiltrados.length === 0" class="text-center py-5 text-muted">
                        Nenhum sabor encontrado.
                    </div>

                    <div
                        v-for="sabor in saboresFiltrados"
                        :key="sabor.id"
                        class="config-item-card"
                        :class="{ 'is-inativo': !sabor.ativo }"
                    >
                        <div class="config-item-left">
                            <span class="config-item-grip" aria-hidden="true">
                                <svg width="14" height="22" viewBox="0 0 14 22" fill="currentColor">
                                    <circle cx="3.5" cy="4" r="1.8" />
                                    <circle cx="10.5" cy="4" r="1.8" />
                                    <circle cx="3.5" cy="11" r="1.8" />
                                    <circle cx="10.5" cy="11" r="1.8" />
                                    <circle cx="3.5" cy="18" r="1.8" />
                                    <circle cx="10.5" cy="18" r="1.8" />
                                </svg>
                            </span>
                            <div class="config-item-info">
                                <div class="config-item-name">@{{ sabor.descricao }}</div>
                            </div>
                        </div>

                        <div class="config-item-right">
                            <label class="config-switch-wrapper" :title="sabor.ativo ? 'Clique para inativar' : 'Clique para ativar'">
                                <input
                                    type="checkbox"
                                    class="config-switch-input"
                                    v-model="sabor.ativo"
                                >
                                <span class="config-switch-track"></span>
                                <span class="config-status-label" :class="sabor.ativo ? 'is-ativo' : 'is-inativo'">
                                    @{{ sabor.ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- ABA 4: Tamanhos -->
                <div v-if="abaAtiva === 'tamanhos'" class="config-items-list">
                    <div v-if="tamanhos.length === 0" class="text-center py-5 text-muted">
                        Nenhum tamanho cadastrado.
                    </div>

                    <div
                        v-for="tamanho in tamanhos"
                        :key="tamanho.id"
                        class="config-item-card"
                        :class="{ 'is-inativo': !tamanho.ativo }"
                    >
                        <div class="config-item-left">
                            <span class="config-item-grip" aria-hidden="true">
                                <svg width="14" height="22" viewBox="0 0 14 22" fill="currentColor">
                                    <circle cx="3.5" cy="4" r="1.8" />
                                    <circle cx="10.5" cy="4" r="1.8" />
                                    <circle cx="3.5" cy="11" r="1.8" />
                                    <circle cx="10.5" cy="11" r="1.8" />
                                    <circle cx="3.5" cy="18" r="1.8" />
                                    <circle cx="10.5" cy="18" r="1.8" />
                                </svg>
                            </span>
                            <div class="config-item-info">
                                <div class="config-item-name">@{{ tamanho.descricao }}</div>
                            </div>
                        </div>

                        <div class="config-item-right">
                            <label class="config-switch-wrapper" :title="tamanho.ativo ? 'Clique para inativar' : 'Clique para ativar'">
                                <input
                                    type="checkbox"
                                    class="config-switch-input"
                                    v-model="tamanho.ativo"
                                >
                                <span class="config-switch-track"></span>
                                <span class="config-status-label" :class="tamanho.ativo ? 'is-ativo' : 'is-inativo'">
                                    @{{ tamanho.ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- ABA 5: Valores -->
                <div v-if="abaAtiva === 'valores'" class="config-items-list">
                    <div v-if="produtosFiltrados.length === 0" class="text-center py-5 text-muted">
                        Nenhum produto encontrado.
                    </div>

                    <div
                        v-for="produto in produtosFiltrados"
                        :key="produto.id"
                        class="config-item-card"
                        :class="{ 'is-inativo': !produto.ativo }"
                    >
                        <div class="config-item-left">
                            <span class="config-item-grip" aria-hidden="true">
                                <svg width="14" height="22" viewBox="0 0 14 22" fill="currentColor">
                                    <circle cx="3.5" cy="4" r="1.8" />
                                    <circle cx="10.5" cy="4" r="1.8" />
                                    <circle cx="3.5" cy="11" r="1.8" />
                                    <circle cx="10.5" cy="11" r="1.8" />
                                    <circle cx="3.5" cy="18" r="1.8" />
                                    <circle cx="10.5" cy="18" r="1.8" />
                                </svg>
                            </span>
                            <div class="config-item-info">
                                <div class="config-item-name">
                                    @{{ produto.sabor ? produto.sabor.descricao : 'Sem sabor' }}
                                    <span v-if="produto.tamanho">(@{{ produto.tamanho.descricao }})</span>
                                </div>
                                <div class="config-item-meta">
                                    @{{ produto.tipo_produto ? produto.tipo_produto.descricao : '-' }}
                                    &bull;
                                    <span :style="{ color: produto.ativo ? '#2e8b57' : '#9ca3af', fontWeight: 600 }">
                                        @{{ produto.ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="config-item-right">
                            <div class="input-group config-valor-input-group">
                                <span class="input-group-text">R$</span>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="produto.valor_editavel"
                                    placeholder="0,00"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Rodapé com Botão Salvar Alterações -->
            <div class="config-panel-footer">
                <span v-if="totalAlteracoesPendentes > 0" class="text-muted small">
                    @{{ totalAlteracoesPendentes }} alteração(ões) pendente(s)
                </span>
                <button
                    type="button"
                    class="btn-salvar-config"
                    :class="{ 'has-changes': totalAlteracoesPendentes > 0 }"
                    :disabled="salvando || carregando"
                    @click.prevent="salvarAlteracoes"
                >
                    <span v-if="salvando" class="spinner-border spinner-border-sm me-1" role="status"></span>
                    <span>@{{ salvando ? 'Salvando...' : 'Salvar Alterações' }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Novo Item -->
    @include('configuracoes.partials.modalNovoItem')
</div>

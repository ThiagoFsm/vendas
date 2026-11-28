<div v-if="modalNovoItem" v-cloak class="modal-overlay">
    <div class="modal-content-custom" style="width: 95%; max-width: 660px; max-height: 92vh; display: flex; flex-direction: column; border-radius: 12px; overflow: hidden; animation: modalIn 0.3s ease-out;">
        <div class="modal-header-custom d-flex justify-content-between align-items-center">
            <h3 class="m-0 fs-5">Novo Item no Catálogo</h3>
            <span @click.prevent="fecharModalNovoItem" style="cursor: pointer; font-size: 1.5625rem;" title="Fechar">
                <i class="bi bi-x-lg"></i>
            </span>
        </div>

        <form @submit.prevent="salvarNovoItem" class="d-flex flex-column flex-grow-1" style="min-height: 0;">
            <div class="modal-body-custom modal-body-scrollable flex-grow-1 p-4">
                <!-- Tipo de Item -->
                <div class="mb-3">
                    <label for="novo_tipo_item" class="form-label fw-bold small text-muted text-uppercase">
                        O que deseja cadastrar? <span class="text-danger">*</span>
                    </label>
                    <select
                        id="novo_tipo_item"
                        class="form-select"
                        v-model="novoItem.tipo_item"
                        style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 48px;"
                    >
                        <option value="tipo_produto">Tipo de Produto (Categoria)</option>
                        <option value="produto">Produto</option>
                        <option value="sabor">Sabor</option>
                        <option value="tamanho">Tamanho</option>
                    </select>
                </div>

                <!-- Campos para Tipo de Produto / Sabor / Tamanho -->
                <div v-if="novoItem.tipo_item !== 'produto'" class="mb-3">
                    <label for="novo_descricao" class="form-label fw-bold small text-muted text-uppercase">
                        Descrição <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        id="novo_descricao"
                        class="form-control"
                        v-model="novoItem.descricao"
                        :placeholder="placeholderNovoItem"
                        maxlength="120"
                        required
                        style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 48px;"
                    >
                </div>

                <!-- Campos para Produto -->
                <template v-else>
                    <div class="mb-3">
                        <label for="novo_produto_tipo_id" class="form-label fw-bold small text-muted text-uppercase">
                            Tipo de Produto <span class="text-danger">*</span>
                        </label>
                        <select
                            id="novo_produto_tipo_id"
                            class="form-select"
                            v-model="novoItem.tipo_produto_id"
                            required
                            style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 48px;"
                        >
                            <option value="">Selecione o tipo de produto...</option>
                            <option v-for="tipo in tipoProdutos" :key="tipo.id" :value="tipo.id">
                                @{{ tipo.descricao }}
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="novo_produto_sabor_id" class="form-label fw-bold small text-muted text-uppercase">
                            Sabor <span class="text-danger">*</span>
                        </label>
                        <select
                            id="novo_produto_sabor_id"
                            class="form-select"
                            v-model="novoItem.sabor_id"
                            required
                            style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 48px;"
                        >
                            <option value="">Selecione o sabor...</option>
                            <option v-for="sabor in sabores" :key="sabor.id" :value="sabor.id">
                                @{{ sabor.descricao }}
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="novo_produto_tamanho_id" class="form-label fw-bold small text-muted text-uppercase">
                            Tamanho <span class="text-danger">*</span>
                        </label>
                        <select
                            id="novo_produto_tamanho_id"
                            class="form-select"
                            v-model="novoItem.tamanho_id"
                            required
                            style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 48px;"
                        >
                            <option value="">Selecione o tamanho...</option>
                            <option v-for="tamanho in tamanhos" :key="tamanho.id" :value="tamanho.id">
                                @{{ tamanho.descricao }}
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="novo_produto_valor" class="form-label fw-bold small text-muted text-uppercase">
                            Valor (R$) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius: 8px 0 0 8px; border: 1.5px solid #d1d5db; border-right: none;">R$</span>
                            <input
                                type="text"
                                id="novo_produto_valor"
                                class="form-control"
                                v-model="novoItem.valor_produto"
                                placeholder="0,00"
                                required
                                style="border-radius: 0 8px 8px 0; border: 1.5px solid #d1d5db; height: 48px;"
                            >
                        </div>
                    </div>
                </template>

                <!-- Switch Ativo -->
                <div class="mt-3">
                    <label class="config-switch-wrapper">
                        <input
                            type="checkbox"
                            class="config-switch-input"
                            v-model="novoItem.ativo"
                        >
                        <span class="config-switch-track"></span>
                        <span class="config-status-label" :class="novoItem.ativo ? 'is-ativo' : 'is-inativo'">
                            @{{ novoItem.ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </label>
                    <div class="form-text text-muted mt-1">
                        Itens inativos ficam ocultos para novos pedidos.
                    </div>
                </div>
            </div>

            <div class="modal-footer-custom px-4 py-3 d-flex justify-content-end gap-2" style="background-color: #f8f9fa; border-top: 1px solid #e9ecef;">
                <button type="button" class="btn btn-fechar" @click="fecharModalNovoItem" :disabled="criandoItem">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-confirmar" :disabled="criandoItem">
                    <span v-if="criandoItem" class="spinner-border spinner-border-sm me-1" role="status"></span>
                    <span>@{{ criandoItem ? 'Salvando...' : 'Cadastrar Item' }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

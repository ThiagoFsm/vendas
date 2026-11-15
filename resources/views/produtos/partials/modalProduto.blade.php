<div v-if="modal" class="modal-overlay">
    <div class="modal-content-custom" style="width: 90%; max-width: 600px; border-radius: 12px; overflow: hidden; animation: modalIn 0.3s ease-out;">
        <div class="modal-header-custom d-flex justify-content-between align-items-center">
            <h3 class="m-0 fs-5">@{{ editando ? 'Editar Produto' : 'Novo Produto' }}</h3>
            <span @click.prevent="fecharModal" style="cursor: pointer; font-size: 1.25rem;" title="Fechar">
                <i class="bi bi-x-lg"></i>
            </span>
        </div>

        <form @submit.prevent="salvarProduto">
            <div class="modal-body-custom p-4">
                <!-- Tipo de Produto -->
                <div class="mb-3">
                    <label for="tipo_produto_id" class="form-label fw-bold small text-muted text-uppercase">
                        Tipo de Produto <span class="text-danger">*</span>
                    </label>
                    <select
                        id="tipo_produto_id"
                        class="form-select"
                        v-model="form.tipo_produto_id"
                        required
                        style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 42px;"
                    >
                        <option value="">Selecione o tipo de produto...</option>
                        <option v-for="tipo in dependencias.tipoProdutos" :key="tipo.id" :value="tipo.id">
                            @{{ tipo.descricao }}
                        </option>
                    </select>
                </div>

                <!-- Sabor -->
                <div class="mb-3">
                    <label for="sabor_id" class="form-label fw-bold small text-muted text-uppercase">
                        Sabor <span class="text-danger">*</span>
                    </label>
                    <select
                        id="sabor_id"
                        class="form-select"
                        v-model="form.sabor_id"
                        required
                        style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 42px;"
                    >
                        <option value="">Selecione o sabor...</option>
                        <option v-for="sabor in dependencias.sabores" :key="sabor.id" :value="sabor.id">
                            @{{ sabor.descricao }}
                        </option>
                    </select>
                </div>

                <!-- Tamanho -->
                <div class="mb-3">
                    <label for="tamanho_id" class="form-label fw-bold small text-muted text-uppercase">
                        Tamanho <span class="text-danger">*</span>
                    </label>
                    <select
                        id="tamanho_id"
                        class="form-select"
                        v-model="form.tamanho_id"
                        required
                        style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 42px;"
                    >
                        <option value="">Selecione o tamanho...</option>
                        <option v-for="tam in dependencias.tamanhos" :key="tam.id" :value="tam.id">
                            @{{ tam.descricao }}
                        </option>
                    </select>
                </div>

                <!-- Valor do Produto -->
                <div class="mb-3">
                    <label for="valor_produto" class="form-label fw-bold small text-muted text-uppercase">
                        Valor (R$) <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-radius: 8px 0 0 8px; border: 1.5px solid #d1d5db; border-right: none;">R$</span>
                        <input
                            type="text"
                            id="valor_produto"
                            class="form-control"
                            v-model="form.valor_produto"
                            placeholder="0,00"
                            required
                            style="border-radius: 0 8px 8px 0; border: 1.5px solid #d1d5db; height: 42px;"
                        >
                    </div>
                </div>

                <!-- Status Ativo -->
                <div class="form-check form-switch mt-3">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        role="switch"
                        id="ativoSwitch"
                        v-model="form.ativo"
                        style="cursor: pointer;"
                    >
                    <label class="form-check-label ms-2 fw-semibold" for="ativoSwitch" style="cursor: pointer;">
                        Produto Ativo
                    </label>
                    <div class="form-text text-muted">
                        Produtos inativos não ficam disponíveis para novos pedidos.
                    </div>
                </div>
            </div>

            <div class="modal-footer-custom px-4 py-3 d-flex justify-content-end gap-2" style="background-color: #f8f9fa; border-top: 1px solid #e9ecef;">
                <button type="button" class="btn btn-fechar" @click="fecharModal" :disabled="salvando">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-confirmar" :disabled="salvando">
                    <span v-if="salvando" class="spinner-border spinner-border-sm me-1" role="status"></span>
                    <span>@{{ salvando ? 'Salvando...' : (editando ? 'Atualizar Produto' : 'Cadastrar Produto') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

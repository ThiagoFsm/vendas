<div v-if="modal" v-cloak class="modal-overlay">
    <div class="modal-content-custom" style="width: 90%; max-width: 550px; border-radius: 12px; overflow: hidden; animation: modalIn 0.3s ease-out;">
        <div class="modal-header-custom d-flex justify-content-between align-items-center">
            <h3 class="m-0 fs-5">@{{ editando ? 'Editar Sabor' : 'Novo Sabor' }}</h3>
            <span @click.prevent="fecharModal" style="cursor: pointer; font-size: 1.25rem;" title="Fechar">
                <i class="bi bi-x-lg"></i>
            </span>
        </div>

        <form @submit.prevent="salvarSabor">
            <div class="modal-body-custom p-4">
                <!-- Descrição do Sabor -->
                <div class="mb-3">
                    <label for="sabor_descricao" class="form-label fw-bold small text-muted text-uppercase">
                        Descrição do Sabor <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        id="sabor_descricao"
                        class="form-control"
                        placeholder="Ex: Nutella, Ninho com Nutella, Paçoca..."
                        v-model="form.descricao"
                        required
                        style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 42px;"
                    >
                </div>

                <!-- Status Ativo -->
                <div class="form-check form-switch mt-3">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        role="switch"
                        id="saborAtivoSwitch"
                        v-model="form.ativo"
                        style="cursor: pointer;"
                    >
                    <label class="form-check-label ms-2 fw-semibold" for="saborAtivoSwitch" style="cursor: pointer;">
                        Sabor Ativo
                    </label>
                    <div class="form-text text-muted">
                        Sabores inativos não ficam disponíveis para novos produtos e pedidos.
                    </div>
                </div>
            </div>

            <div class="modal-footer-custom px-4 py-3 d-flex justify-content-end gap-2" style="background-color: #f8f9fa; border-top: 1px solid #e9ecef;">
                <button type="button" class="btn btn-fechar" @click="fecharModal" :disabled="salvando">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-confirmar" :disabled="salvando">
                    <span v-if="salvando" class="spinner-border spinner-border-sm me-1" role="status"></span>
                    <span>@{{ salvando ? 'Salvando...' : (editando ? 'Atualizar Sabor' : 'Cadastrar Sabor') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

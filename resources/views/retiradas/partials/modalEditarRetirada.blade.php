<div v-if="modalEditar" v-cloak class="modal-overlay">
    <div class="modal-content-custom" style="width: 95%; max-width: 780px; max-height: 92vh; display: flex; flex-direction: column;">
        <div class="modal-header-custom d-flex justify-content-between align-items-center">
            <h4 class="m-0 fw-bold">
                <i class="bi bi-pencil-square me-2 text-primary"></i>
                Editar Retirada #@{{ formEditar.id }}
                <span v-if="retiradaEditando && retiradaEditando.pedido" class="badge bg-secondary ms-2" style="font-size: 1.0625rem;">
                    Pedido #@{{ retiradaEditando.pedido.id }}
                </span>
            </h4>
            <span @click.prevent="fecharModalEditar" style="cursor: pointer; font-size: 1.5625rem;" title="Fechar">
                <i class="bi bi-x-lg"></i>
            </span>
        </div>

        <form @submit.prevent="salvarEdicaoRetirada" class="d-flex flex-column flex-grow-1" style="min-height: 0;">
            <div class="modal-body-scrollable flex-grow-1 p-4" style="overflow-y: auto;">
                <!-- Card de Identificação -->
                <div class="alert alert-light border d-flex align-items-center justify-content-between mb-4 py-2 px-3" style="border-radius: 8px;">
                    <div>
                        <span class="text-muted small d-block">Cliente:</span>
                        <strong class="text-dark">
                            @{{ (retiradaEditando && retiradaEditando.pedido && retiradaEditando.pedido.cliente) ? retiradaEditando.pedido.cliente.nome : 'Não informado' }}
                        </strong>
                    </div>
                    <div class="text-end" v-if="retiradaEditando && retiradaEditando.pedido">
                        <span class="text-muted small d-block">Valor do Pedido:</span>
                        <strong class="text-success">@{{ formatarValor(retiradaEditando.pedido.valor_total) }}</strong>
                    </div>
                </div>

                <!-- Seção 1: Agendamento -->
                <div class="card mb-4" style="border: 1.5px solid #d1d5db; border-radius: 10px;">
                    <div class="card-header bg-white border-bottom fw-bold text-dark py-2 px-3">
                        <i class="bi bi-calendar-event text-primary me-1"></i> Agendamento da Retirada
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="edit_retirada_data" class="form-label fw-bold small text-muted text-uppercase">
                                    Data da Retirada <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="edit_retirada_data"
                                    class="form-control"
                                    v-model="formEditar.data"
                                    required
                                    style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 48px;"
                                >
                            </div>
                            <div class="col-md-4">
                                <label for="edit_retirada_periodo" class="form-label fw-bold small text-muted text-uppercase">
                                    Período <span class="text-danger">*</span>
                                </label>
                                <select
                                    id="edit_retirada_periodo"
                                    class="form-select"
                                    v-model="formEditar.periodo"
                                    required
                                    style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 48px;"
                                >
                                    <option value="">Selecione o período...</option>
                                    <option value="Manhã">Manhã</option>
                                    <option value="Tarde">Tarde</option>
                                    <option value="Noite">Noite</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_retirada_horas" class="form-label fw-bold small text-muted text-uppercase">
                                    Horas
                                </label>
                                <input
                                    type="time"
                                    id="edit_retirada_horas"
                                    class="form-control"
                                    v-model="formEditar.horas"
                                    style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 48px;"
                                >
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="edit_retirada_realizada" v-model="formEditar.realizada" style="cursor: pointer;">
                                    <label class="form-check-label fw-bold small text-muted" for="edit_retirada_realizada" style="cursor: pointer;">
                                        Retirada já realizada
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seção 2: Local de Retirada -->
                <div class="card" style="border: 1.5px solid #d1d5db; border-radius: 10px;">
                    <div class="card-header bg-white border-bottom fw-bold text-dark py-2 px-3">
                        <i class="bi bi-shop text-info me-1"></i> Ponto de Retirada
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-2">
                            <label for="edit_retirada_bairro" class="form-label fw-bold small text-muted text-uppercase">
                                Bairro / Local <span class="text-danger">*</span>
                            </label>
                            <input
                                list="listaBairrosRetirada"
                                id="edit_retirada_bairro"
                                class="form-control"
                                v-model="formEditar.bairro"
                                placeholder="Selecione ou digite o local de retirada..."
                                required
                                style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 48px;"
                            >
                            <datalist id="listaBairrosRetirada">
                                <option value="Jacy">Jacy</option>
                                <option value="Tiradentes">Tiradentes</option>
                                <option v-for="bairro in dependencias.bairros" :key="bairro.id" :value="bairro.descricao"></option>
                            </datalist>
                            <span class="text-muted small mt-1 d-block">
                                Você pode selecionar uma sugestão da lista ou digitar o nome do local.
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer-custom d-flex justify-content-end align-items-center gap-2 p-3 border-top">
                <button type="button" class="btn btn-fechar" @click="fecharModalEditar">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-confirmar d-flex align-items-center gap-1" :disabled="salvando">
                    <span v-if="salvando" class="spinner-border spinner-border-sm me-1" role="status"></span>
                    <i v-else class="bi bi-check-lg me-1"></i>
                    <span>@{{ salvando ? 'Salvando...' : 'Salvar Alterações' }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

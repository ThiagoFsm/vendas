<div v-if="modalEditar" class="modal-overlay">
    <div class="modal-content-custom" style="width: 90%; max-width: 650px; max-height: 90vh; display: flex; flex-direction: column;">
        <div class="modal-header-custom d-flex justify-content-between align-items-center">
            <h4 class="m-0 fw-bold">
                <i class="bi bi-pencil-square me-2 text-primary"></i>
                Editar Entrega @{{ formEditar.id }}
                <span v-if="entregaEditando && entregaEditando.pedido" class="badge bg-secondary ms-2" style="font-size: 0.75rem;">
                    Pedido @{{ entregaEditando.pedido.id }}
                </span>
            </h4>
            <span @click.prevent="fecharModalEditar" style="cursor: pointer; font-size: 1.25rem;" title="Fechar">
                <i class="bi bi-x-lg"></i>
            </span>
        </div>

        <form @submit.prevent="salvarEdicaoEntrega" class="d-flex flex-column flex-grow-1" style="min-height: 0;">
            <div class="modal-body-scrollable flex-grow-1 p-4" style="overflow-y: auto;">
                <!-- Card de Identificação -->
                <div class="alert alert-light border d-flex align-items-center justify-content-between mb-4 py-2 px-3" style="border-radius: 8px;">
                    <div>
                        <span class="text-muted small d-block">Cliente:</span>
                        <strong class="text-dark">
                            @{{ (entregaEditando && entregaEditando.pedido && entregaEditando.pedido.cliente) ? entregaEditando.pedido.cliente.nome : 'Não informado' }}
                        </strong>
                    </div>
                    <div class="text-end" v-if="entregaEditando && entregaEditando.pedido">
                        <span class="text-muted small d-block">Valor do Pedido:</span>
                        <strong class="text-success">@{{ formatarValor(entregaEditando.pedido.valor_total) }}</strong>
                    </div>
                </div>

                <!-- Seção 1: Agendamento -->
                <div class="card mb-4" style="border: 1.5px solid #d1d5db; border-radius: 10px;">
                    <div class="card-header bg-white border-bottom fw-bold text-dark py-2 px-3">
                        <i class="bi bi-calendar-event text-primary me-1"></i> Agendamento da Entrega
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="edit_data" class="form-label fw-bold small text-muted text-uppercase">
                                    Data da Entrega <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="edit_data"
                                    class="form-control"
                                    v-model="formEditar.data"
                                    required
                                    style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 42px;"
                                >
                            </div>
                            <div class="col-md-4">
                                <label for="edit_periodo" class="form-label fw-bold small text-muted text-uppercase">
                                    Período <span class="text-danger">*</span>
                                </label>
                                <select
                                    id="edit_periodo"
                                    class="form-select"
                                    v-model="formEditar.periodo"
                                    required
                                    style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 42px;"
                                >
                                    <option value="">Selecione o período...</option>
                                    <option value="Manhã">Manhã</option>
                                    <option value="Tarde">Tarde</option>
                                    <option value="Noite">Noite</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_horas" class="form-label fw-bold small text-muted text-uppercase">
                                    Horas
                                </label>
                                <input
                                    type="time"
                                    id="edit_horas"
                                    class="form-control"
                                    v-model="formEditar.horas"
                                    style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 42px;"
                                >
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="edit_realizada" v-model="formEditar.realizada" style="cursor: pointer;">
                                    <label class="form-check-label fw-bold small text-muted" for="edit_realizada" style="cursor: pointer;">
                                        Entrega já realizada
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seção 2: Modalidade da Entrega -->
                <div class="card mb-4" style="border: 1.5px solid #d1d5db; border-radius: 10px;">
                    <div class="card-header bg-white border-bottom fw-bold text-dark py-2 px-3">
                        <i class="bi bi-truck text-primary me-1"></i> Modalidade da Entrega
                    </div>
                    <div class="card-body p-3">
                        <div class="btn-group w-100" role="group">
                            <input
                                type="radio"
                                class="btn-check"
                                name="edit_modalidade"
                                id="modalidade_entrega"
                                value="entrega"
                                v-model="formEditar.modalidade"
                                autocomplete="off"
                            >
                            <label class="btn btn-outline-primary fw-bold py-2 d-flex align-items-center justify-content-center gap-2" for="modalidade_entrega">
                                <i class="bi bi-bicycle fs-5"></i>
                                <span>Entrega (Entregador)</span>
                            </label>

                            <input
                                type="radio"
                                class="btn-check"
                                name="edit_modalidade"
                                id="modalidade_uber"
                                value="uber"
                                v-model="formEditar.modalidade"
                                autocomplete="off"
                            >
                            <label class="btn btn-outline-dark fw-bold py-2 d-flex align-items-center justify-content-center gap-2" for="modalidade_uber">
                                <i class="bi bi-car-front-fill fs-5"></i>
                                <span>Uber</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Seção 3A: Responsável (quando modalidade for Entrega) -->
                <div v-if="formEditar.modalidade === 'entrega'" class="card mb-4" style="border: 1.5px solid #d1d5db; border-radius: 10px;">
                    <div class="card-header bg-white border-bottom fw-bold text-dark py-2 px-3">
                        <i class="bi bi-person-badge text-primary me-1"></i> Responsável
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="edit_entregador_id" class="form-label fw-bold small text-muted text-uppercase">
                                    Entregador Responsável <span class="text-danger">*</span>
                                </label>
                                <select
                                    id="edit_entregador_id"
                                    class="form-select"
                                    v-model="formEditar.entregador_id"
                                    :required="formEditar.modalidade === 'entrega'"
                                    style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 42px;"
                                >
                                    <option value="">Selecione o entregador responsável...</option>
                                    <option v-for="entregador in dependencias.entregadores" :key="entregador.id" :value="entregador.id">
                                        @{{ entregador.nome }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seção 3B: Endereço e Valor do Uber (quando modalidade for Uber) -->
                <div v-if="formEditar.modalidade === 'uber'" class="card mb-4" style="border: 1.5px solid #d1d5db; border-radius: 10px;">
                    <div class="card-header bg-white border-bottom fw-bold text-dark py-2 px-3">
                        <i class="bi bi-geo-alt text-danger me-1"></i> Endereço e Valor do Uber
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="edit_valor_uber" class="form-label fw-bold small text-muted text-uppercase">
                                    Valor Uber (R$) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text" style="border-radius: 8px 0 0 8px; border: 1.5px solid #d1d5db; border-right: none;">R$</span>
                                    <input
                                        type="text"
                                        id="edit_valor_uber"
                                        class="form-control"
                                        placeholder="0,00"
                                        v-model="formEditar.valor_uber"
                                        v-money="$dinheiro"
                                        :required="formEditar.modalidade === 'uber'"
                                        style="border-radius: 0 8px 8px 0; border: 1.5px solid #d1d5db; height: 42px;"
                                    >
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label for="edit_bairro_id" class="form-label fw-bold small text-muted text-uppercase">
                                    Bairro <span class="text-danger">*</span>
                                </label>
                                <select
                                    id="edit_bairro_id"
                                    class="form-select"
                                    v-model="formEditar.bairro_id"
                                    :required="formEditar.modalidade === 'uber'"
                                    style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 42px;"
                                >
                                    <option value="">Selecione o bairro...</option>
                                    <option v-for="bairro in dependencias.bairros" :key="bairro.id" :value="bairro.id">
                                        @{{ bairro.descricao }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="edit_rua" class="form-label fw-bold small text-muted text-uppercase">
                                    Rua / Logradouro <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="edit_rua"
                                    class="form-control"
                                    placeholder="Ex: Rua das Flores"
                                    v-model="formEditar.rua"
                                    :required="formEditar.modalidade === 'uber'"
                                    style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 42px;"
                                >
                            </div>
                            <div class="col-md-2">
                                <label for="edit_numero" class="form-label fw-bold small text-muted text-uppercase">
                                    Número <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="edit_numero"
                                    class="form-control text-center"
                                    placeholder="Nº"
                                    v-model="formEditar.numero"
                                    :required="formEditar.modalidade === 'uber'"
                                    style="border-radius: 8px; border: 1.5px solid #d1d5db; height: 42px;"
                                >
                            </div>
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

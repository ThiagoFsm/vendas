<div v-if="modal" v-cloak class="modal-overlay">
    <div class="modal-content-custom" style="width: 90%; max-width: 850px; max-height: 90vh; display: flex; flex-direction: column;">
        <div class="modal-header-custom">
            <h4 class="m-0 fw-bold">
                <i class="bi bi-truck me-2"></i>
                Detalhes da Entrega #@{{ dadosModal.id }}
                <span v-if="dadosModal.pedido" class="badge bg-secondary ms-2" style="font-size: 0.75rem;">
                    Pedido #@{{ dadosModal.pedido.id }}
                </span>
            </h4>
            <span @click.prevent="fecharModal" style="cursor: pointer; font-size: 1.25rem;" title="Fechar">
                <i class="bi bi-x-lg"></i>
            </span>
        </div>

        <div class="modal-body-scrollable flex-grow-1 p-4" style="overflow-y: auto;">
            <!-- Seção 1: Dados da Entrega -->
            <div class="card mb-4" style="border: 1.5px solid #d1d5db; border-radius: 10px; background-color: #f8fafc;">
                <div class="card-header bg-white border-bottom fw-bold text-dark py-2 px-3 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-geo-alt-fill text-danger me-1"></i> Informações do Envio</span>
                    <span v-if="tipoEntrega(dadosModal) === 'uber'" class="badge bg-dark text-white px-2 py-1">
                        <i class="bi bi-car-front-fill me-1"></i> Uber
                    </span>
                    <span v-else class="badge bg-primary text-white px-2 py-1">
                        <i class="bi bi-bicycle me-1"></i> Entregador
                    </span>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-md-6" v-if="tipoEntrega(dadosModal) === 'entregador'">
                            <span class="text-muted small d-block">Entregador Responsável:</span>
                            <span class="fw-bold text-dark">@{{ dadosModal.entregador ? dadosModal.entregador.nome : 'Não informado' }}</span>
                        </div>
                        <div class="col-md-6" v-if="tipoEntrega(dadosModal) === 'uber' && dadosModal.valor_uber">
                            <span class="text-muted small d-block">Valor do Uber:</span>
                            <span class="fw-bold text-success">@{{ formatarValor(dadosModal.valor_uber) }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block">Data da Entrega:</span>
                            <span class="fw-bold text-dark">@{{ formatarData(dadosModal.data) }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block">Período / Hora:</span>
                            <span class="badge bg-light text-dark border px-2 py-1">
                                @{{ dadosModal.periodo || 'Não informado' }}
                                <span v-if="dadosModal.horas">(@{{ formatarHora(dadosModal.horas) }})</span>
                            </span>
                        </div>
                        <div class="col-12" v-if="tipoEntrega(dadosModal) === 'uber' || dadosModal.rua || dadosModal.bairro_id">
                            <span class="text-muted small d-block">Endereço de Destino:</span>
                            <span class="fw-bold text-dark">@{{ enderecoCompleto(dadosModal) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção 2: Dados do Cliente e Pedido -->
            <div class="card mb-4" style="border: 1.5px solid #d1d5db; border-radius: 10px;">
                <div class="card-header bg-white border-bottom fw-bold text-dark py-2 px-3">
                    <i class="bi bi-person-circle text-primary me-1"></i> Dados do Cliente e Pedido
                </div>
                <div class="card-body p-3">
                    <div class="row g-3" v-if="dadosModal.pedido">
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Cliente:</span>
                            <span class="fw-bold text-dark">@{{ dadosModal.pedido.cliente ? dadosModal.pedido.cliente.nome : '-' }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Contato / Telefone:</span>
                            <span class="text-dark">@{{ dadosModal.pedido.cliente?.telefone || '-' }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Vendedor(a):</span>
                            <span class="text-dark">@{{ dadosModal.pedido.cliente?.vendedor?.nome || '-' }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Valor Total:</span>
                            <span class="fw-bold text-dark">@{{ formatarValor(dadosModal.pedido.valor_total) }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Valor Antecipado:</span>
                            <span class="text-dark">@{{ formatarValor(dadosModal.pedido.valor_antecipado) }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Valor Restante:</span>
                            <span class="fw-bold" :class="dadosModal.pedido.pago ? 'text-success' : 'text-danger'">
                                @{{ formatarValor(dadosModal.pedido.valor_restante) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block">Status de Pagamento:</span>
                            <span class="badge" :class="dadosModal.pedido.pago ? 'bg-success text-white' : 'bg-warning text-white'" style="border-radius: 30px;">
                                @{{ dadosModal.pedido.pago ? 'Pago' : 'Pendente' }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block">Status de Produção:</span>
                            <span class="badge" :class="dadosModal.pedido.produzido ? 'bg-success text-white' : 'bg-secondary text-white'" style="border-radius: 30px;">
                                @{{ dadosModal.pedido.produzido ? 'Produzido' : 'À fazer' }}
                            </span>
                        </div>
                    </div>
                    <div v-else class="text-center py-2 text-muted">
                        <span>Nenhum pedido associado a esta entrega.</span>
                    </div>
                </div>
            </div>

            <!-- Seção 3: Itens do Pedido -->
            <div class="card" style="border: 1.5px solid #d1d5db; border-radius: 10px;">
                <div class="card-header bg-white border-bottom fw-bold text-dark py-2 px-3 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-box-seam text-secondary me-1"></i> Itens do Pedido</span>
                    <span class="badge bg-secondary" v-if="dadosModal.pedido && dadosModal.pedido.produtos">
                        @{{ dadosModal.pedido.produtos.length }} produto(s)
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 text-center">
                            <thead class="bg-light">
                            <tr>
                                <th style="width: 80px;">Qtd</th>
                                <th class="text-start">Produto</th>
                                <th>Sabor</th>
                                <th>Tamanho</th>
                                <th style="width: 120px;">Produção</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="!dadosModal.pedido || !dadosModal.pedido.produtos || dadosModal.pedido.produtos.length === 0">
                                <td colspan="5" class="py-3 text-muted">Nenhum produto encontrado neste pedido.</td>
                            </tr>
                            <tr v-else v-for="produto in dadosModal.pedido.produtos" :key="produto.id">
                                <td class="fw-bold">@{{ produto.pivot?.quantidade || 1 }}</td>
                                <td class="text-start">@{{ produto.tipo_produto ? produto.tipo_produto.descricao : '-' }}</td>
                                <td>@{{ produto.sabor ? produto.sabor.descricao : '-' }}</td>
                                <td>@{{ produto.tamanho ? produto.tamanho.descricao : '-' }}</td>
                                <td>
                                    <span v-if="produto.pivot && produto.pivot.produzido" class="text-success fw-bold">
                                        <i class="bi bi-check-all" style="font-size: 1.4rem;"></i>
                                    </span>
                                    <span v-else class="badge bg-secondary" style="font-size: 0.75rem;">
                                        À fazer
                                    </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer-custom d-flex justify-content-between align-items-center p-3 border-top">
            <div class="d-flex gap-2" v-if="dadosModal.pedido">
                <button v-if="!dadosModal.pedido.pago" class="btn btn-sm btn-success"
                        @click.prevent="marcarPedidoComoPago(dadosModal.pedido.id)">
                    <i class="bi bi-cash-coin me-1"></i> Marcar como Pago
                </button>
                <button v-if="!dadosModal.pedido.produzido" class="btn btn-sm btn-outline-success"
                        @click.prevent="marcarPedidoComoFeito(dadosModal.pedido.id)">
                    <i class="bi bi-check2-circle me-1"></i> Marcar como Feito
                </button>
            </div>
            <div v-else></div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-primary" @click.prevent="fecharModal(); abrirModalEditar(dadosModal);">
                    <i class="bi bi-pencil-square me-1"></i> Editar Entrega
                </button>
                <button type="button" class="btn btn-fechar" @click="fecharModal">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

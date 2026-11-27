<div v-if="modal" v-cloak class="modal-overlay">
    <div class="modal-content-custom" style="width: 90%; max-width: 1050px; max-height: 90vh; display: flex; flex-direction: column;">
        <div class="modal-header-custom">
            <h4 class="m-0 fw-bold">
                <i class="bi bi-cart3 me-2"></i>
                Detalhes do Pedido
                <span v-if="dadosModal.dados && dadosModal.dados.pedido_id" class="badge bg-secondary ms-2" style="font-size: 0.75rem;">
                    #@{{ dadosModal.dados.pedido_id }}
                </span>
                <span v-if="dadosModal.nome_cliente" class="text-muted fw-normal ms-2" style="font-size: 1rem;">
                    - @{{ dadosModal.nome_cliente }}
                </span>
            </h4>
            <span @click.prevent="fecharModal" style="cursor: pointer; font-size: 1.25rem;" title="Fechar">
                <i class="bi bi-x-lg"></i>
            </span>
        </div>

        <div class="modal-body-scrollable flex-grow-1 p-4" style="overflow-y: auto;">
            <!-- Seção 1: Informações do Pedido e Pagamento -->
            <div class="card mb-4" style="border: 1.5px solid #d1d5db; border-radius: 10px;">
                <div class="card-header bg-white border-bottom fw-bold text-dark py-2 px-3 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-receipt text-primary me-1"></i> Informações do Pedido</span>
                    <div class="d-flex gap-2">
                        <span class="badge" :class="dadosModal.pago ? 'bg-success text-white' : 'bg-warning text-white'" style="border-radius: 30px;">
                            @{{ dadosModal.pago ? 'Pago' : 'Pendente' }}
                        </span>
                        <span class="badge" :class="dadosModal.produzido ? 'bg-success text-white' : 'bg-secondary text-white'" style="border-radius: 30px;">
                            @{{ dadosModal.produzido ? 'Produzido' : 'À fazer' }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-3" v-if="dadosModal.dados">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <span class="text-muted small d-block">Cliente:</span>
                            <span class="fw-bold text-dark">@{{ dadosModal.nome_cliente || '-' }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small d-block">Valor Total:</span>
                            <span class="fw-bold text-dark">@{{ dadosModal.dados.valor_total }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small d-block">Valor Antecipado:</span>
                            <span class="text-dark">@{{ dadosModal.dados.valor_antecipado }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small d-block">Valor Restante:</span>
                            <span class="fw-bold" :class="dadosModal.pago ? 'text-success' : 'text-danger'">
                                @{{ dadosModal.dados.valor_restante }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção 2: Informações de Entrega ou Retirada -->
            <div class="card mb-4" style="border: 1.5px solid #d1d5db; border-radius: 10px; background-color: #f8fafc;" v-if="dadosModal.entrega_retirada">
                <div class="card-header bg-white border-bottom fw-bold text-dark py-2 px-3 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-geo-alt-fill text-danger me-1"></i> Informações do Envio / Retirada</span>
                    <!-- Badge do Tipo -->
                    <span v-if="dadosModal.entrega_retirada.rua" class="badge bg-dark text-white px-2 py-1">
                        <i class="bi bi-car-front-fill me-1"></i> Uber
                    </span>
                    <span v-else-if="dadosModal.entrega_retirada.entregador_id" class="badge bg-primary text-white px-2 py-1">
                        <i class="bi bi-bicycle me-1"></i> Entrega
                    </span>
                    <span v-else class="badge bg-info text-dark px-2 py-1">
                        <i class="bi bi-shop me-1"></i> Retirada
                    </span>
                </div>
                <div class="card-body p-3">
                    <!-- Detalhes se for Uber -->
                    <div class="row g-3" v-if="dadosModal.entrega_retirada.rua">
                        <div class="col-12">
                            <span class="text-muted small d-block">Endereço de Destino:</span>
                            <span class="fw-bold text-dark">
                                @{{ dadosModal.entrega_retirada.rua + ', ' + dadosModal.entrega_retirada.numero + (dadosModal.entrega_retirada.bairro ? ' - ' + dadosModal.entrega_retirada.bairro.descricao : '') }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Data:</span>
                            <span class="fw-bold text-dark">@{{ dadosModal.entrega_retirada.data }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Período / Hora:</span>
                            <span class="fw-bold text-dark">
                                @{{ dadosModal.entrega_retirada.periodo || '-' }}
                                <span v-if="dadosModal.entrega_retirada.horas">(@{{ dadosModal.entrega_retirada.horas.substring(0, 5) }})</span>
                            </span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Valor do Uber:</span>
                            <span class="fw-bold text-success">@{{ dadosModal.entrega_retirada.valor_uber }}</span>
                        </div>
                    </div>

                    <!-- Detalhes se for Entrega com Entregador -->
                    <div class="row g-3" v-else-if="dadosModal.entrega_retirada.entregador_id">
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Entregador(a):</span>
                            <span class="fw-bold text-dark">
                                @{{ dadosModal.entrega_retirada.entregador ? dadosModal.entrega_retirada.entregador.nome : 'Não informado' }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Data:</span>
                            <span class="fw-bold text-dark">@{{ dadosModal.entrega_retirada.data }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Período / Hora:</span>
                            <span class="fw-bold text-dark">
                                @{{ dadosModal.entrega_retirada.periodo || '-' }}
                                <span v-if="dadosModal.entrega_retirada.horas">(@{{ dadosModal.entrega_retirada.horas.substring(0, 5) }})</span>
                            </span>
                        </div>
                    </div>

                    <!-- Detalhes se for Retirada -->
                    <div class="row g-3" v-else>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Bairro / Local de Retirada:</span>
                            <span class="fw-bold text-dark">@{{ dadosModal.entrega_retirada.bairro }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Data:</span>
                            <span class="fw-bold text-dark">@{{ dadosModal.entrega_retirada.data }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Período / Hora:</span>
                            <span class="fw-bold text-dark">
                                @{{ dadosModal.entrega_retirada.periodo || '-' }}
                                <span v-if="dadosModal.entrega_retirada.horas">(@{{ dadosModal.entrega_retirada.horas.substring(0, 5) }})</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção 3: Itens do Pedido -->
            <div class="card" style="border: 1.5px solid #d1d5db; border-radius: 10px;">
                <div class="card-header bg-white border-bottom fw-bold text-dark py-2 px-3 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-box-seam text-secondary me-1"></i> Itens do Pedido</span>
                    <span class="badge bg-secondary" v-if="dadosModal.produtos">
                        @{{ dadosModal.produtos.length }} produto(s)
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
                                <th style="width: 200px;">Produção</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="!dadosModal.produtos || dadosModal.produtos.length === 0">
                                <td colspan="5" class="py-3 text-muted">Nenhum item encontrado.</td>
                            </tr>
                            <tr v-else v-for="produto in dadosModal.produtos" :key="produto.id">
                                <td class="fw-bold">@{{ produto.pivot.quantidade }}</td>
                                <td class="text-start">@{{ produto.tipo_produto ? produto.tipo_produto.descricao : '-' }}</td>
                                <td>@{{ produto.sabor ? produto.sabor.descricao : '-' }}</td>
                                <td>@{{ produto.tamanho ? produto.tamanho.descricao : '-' }}</td>
                                <td>
                                    <div v-if="produto.pivot.produzido" class="d-flex align-items-center justify-content-center">
                                        <span class="text-success fw-bold" title="Produto feito">
                                            <i class="bi bi-check-all" style="font-size: 1.4rem;"></i>
                                        </span>
                                    </div>
                                    <div v-else class="d-flex align-items-center justify-content-center">
                                        <button class="btn btn-sm btn-outline-success"
                                                @click.prevent="marcarProdutoComoFeito(dadosModal.dados.pedido_id, produto.id)">
                                            <i class="bi bi-check2 me-1"></i> Produto feito
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer-custom d-flex justify-content-between align-items-center p-3 border-top">
            <div class="d-flex gap-2">
                <button v-if="!dadosModal.produzido" class="btn btn-primary"
                        @click.prevent="marcarPedidoComoFeito(dadosModal.dados.pedido_id)">
                    <i class="bi bi-check2-circle me-1"></i> Marcar como feito
                </button>
                <button v-if="!dadosModal.pago" class="btn btn-success"
                        @click.prevent="marcarPedidoComoPago(dadosModal.dados.pedido_id)">
                    <i class="bi bi-cash-coin me-1"></i> Marcar como pago
                </button>
            </div>
            <div>
                <button type="button" class="btn btn-fechar" @click="fecharModal">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

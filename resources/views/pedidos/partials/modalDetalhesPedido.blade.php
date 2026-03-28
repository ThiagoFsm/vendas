<div v-if="modal" class="modal-overlay">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <h3>Detalhes do Pedido de @{{ dadosModal.nome_cliente }}</h3>
            <span @click.prevent="fecharModal" style="cursor:pointer">&times;</span>
        </div>

        <div class="table-responsive p-3">
            <div v-if="dadosModal.produtos?.length === 0">
                <tr class="w-full">
                    <td>
                        <span>Nenhum item encontrado.</span>
                    </td>
                </tr>
            </div>
            <div v-else>
                <table class="table align-middle mb-2 border" style="border-color: #212529;">
                    <thead>
                    <tr class="text-center">
                        <th class="p-3">Quantidade</th>
                        <th class="p-3">Produto</th>
                        <th class="p-3">Sabor</th>
                        <th class="p-3">Tamanho</th>
                    </tr>
                    </thead>
                    <tbody v-for="produto in dadosModal.produtos" :v-key="produto.id">
                    <tr class="text-center">
                        <td class="p-2">@{{ produto.pivot.quantidade }}</td>
                        <td class="p-2">@{{ produto.tipo_produto.descricao }}</td>
                        <td class="p-2">@{{ produto.sabor.descricao }}</td>
                        <td class="p-2">@{{ produto.tamanho.descricao }}</td>
                    </tr>
                    </tbody>
                </table>
                <div class="w-full text-center">
                    <h4 class="mt-3 text-center">Informações do Pedido</h4>
                    <div class="d-flex flex-row justify-content-center">
                        <div class="w-25">
                            Valor Total:
                            <span>@{{ dadosModal.dados.valor_total }}</span>
                        </div>
                        <div class="w-25">
                            Valor Antecipado:
                            <span>@{{ dadosModal.dados.valor_antecipado }}</span>
                        </div>
                        <div class="w-25">
                            Valor Restante:
                            <span>@{{ dadosModal.dados.valor_restante }}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="w-full text-center">
                        <div v-if="dadosModal.entrega_retirada.rua">
                            <h4 class="mt-3 text-center">Uber</h4>
                            <div class="d-flex flex-row">
                                <div class="w-full">
                                    Rua:
                                    <span>@{{ dadosModal.entrega_retirada.rua + ', ' + dadosModal.entrega_retirada.numero + ' - ' + dadosModal.entrega_retirada.bairro.descricao }}</span>
                                </div>
                            </div>
                            <div class="d-flex flex-row">
                                <div class="w-25">
                                    Data:
                                    <span>@{{ dadosModal.entrega_retirada.data }}</span>
                                </div>
                                <div class="w-25">
                                    Período:
                                    <span>@{{ dadosModal.entrega_retirada.periodo }}</span>
                                </div>
                                <div class="w-25">
                                    Valor:
                                    <span>@{{ dadosModal.entrega_retirada.valor_uber }}</span>
                                </div>
                            </div>
                        </div>
                        <div v-else-if="dadosModal.entrega_retirada.entregador_id">
                            <h4 class="mt-3 text-center">Entrega</h4>
                            <div class="d-flex flex-row justify-content-center">
                                <div class="w-25">
                                    Entregador(a):
                                    <span>@{{ dadosModal.entrega_retirada.entregador.nome }}</span>
                                </div>
                                <div class="w-25">
                                    Data:
                                    <span>@{{ dadosModal.entrega_retirada.data }}</span>
                                </div>
                                <div class="w-25">
                                    Período:
                                    <span>@{{ dadosModal.entrega_retirada.periodo }}</span>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <h4 class="mt-3 text-center">Retirada</h4>
                            <div class="d-flex flex-row justify-content-center">
                                <div class="w-25">
                                    Bairro:
                                    <span>@{{ dadosModal.entrega_retirada.bairro }}</span>
                                </div>
                                <div class="w-25">
                                    Data:
                                    <span>@{{ dadosModal.entrega_retirada.data }}</span>
                                </div>
                                <div class="w-25">
                                    Período:
                                    <span>@{{ dadosModal.entrega_retirada.periodo }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer-custom">
            <button class="btn btn-confirmar" @click.prevent="marcarPedidoComoPago({{ $pedido->id }})">Marcar como pago</button>
            <button class="btn btn-fechar" @click="fecharModal">Fechar</button>
        </div>
    </div>
</div>

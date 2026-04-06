<div v-if="modal" class="modal-overlay">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <h3>Detalhes de Produção</h3>
            <span @click.prevent="fecharModal" style="cursor:pointer">&times;</span>
        </div>
        <div class="table-responsive p-3">
            <div v-if="dadosModal?.length === 0">
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
                        <th class="p-3">Tamanho</th>
                        <th class="p-3">Cliente</th>
                        <th class="p-3">Ações</th>
                    </tr>
                    </thead>
                    <tbody v-for="(pedido, idx) in dadosModal" :v-key="idx">
                    <tr class="text-center">
                        <td class="p-2">@{{ pedido.produtos[0].pivot.quantidade }}</td>
                        <td class="p-2">@{{ pedido.produtos[0].tamanho.descricao }}</td>
                        <td class="p-2">@{{ pedido.cliente.nome }}</td>
                        <td class="d-flex gap-1 justify-content-center">
                            <button class="btn-action btn-view" style="background: lightgreen" @click.prevent="marcarComoFeito(pedido.id, pedido.produtos[0].id)">Marcar como feito</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
{{--                <div class="w-full text-center">--}}
{{--                    <h4 class="mt-3 text-center">Informações do Pedido</h4>--}}
{{--                    <div class="d-flex flex-row justify-content-center">--}}
{{--                        <div class="w-25">--}}
{{--                            Valor Total:--}}
{{--                            <span></span>--}}
{{--                        </div>--}}
{{--                        <div class="w-25">--}}
{{--                            Valor Antecipado:--}}
{{--                            <span></span>--}}
{{--                        </div>--}}
{{--                        <div class="w-25">--}}
{{--                            Valor Restante:--}}
{{--                            <span></span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div>--}}
{{--                    <div class="w-full text-center">--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>

        <div class="modal-footer-custom">
            <button class="btn btn-fechar" @click="fecharModal">Fechar</button>
        </div>
    </div>
</div>

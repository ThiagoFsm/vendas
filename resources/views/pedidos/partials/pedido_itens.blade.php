<div class="col-md-12 d-flex justify-content-center mb-5">
    <table class="table-striped" style="width: 90%">
        <thead class="border-bottom">
            <th class="text-center" width="5%">Quantidade</th>
            <th class="text-center" width="20%">Produto</th>
            <th class="text-center" width="35%">Sabor</th>
            <th class="text-center" width="15%">Tamanho</th>
            <th class="text-center" width="15%">Valor</th>
            <th class="text-center" width="10%">Excluir</th>
        </thead>

        <tbody v-for="item in itens_pedido" class="p-2">
            <td class="text-center">
                @{{ item.quantidade }}
            </td>
            <td class="text-center">
                @{{ item.produto }}
            </td>
            <td class="text-center">
                @{{ item.sabor }}
            </td>
            <td class="text-center">
                @{{ item.tamanho }}
            </td>
            <td class="text-center">
                {{"R$"}}@{{ item.valor }}{{",00"}}
            </td>
            <td class="text-center">
                <button @click.prevent="excluirItem(item.produto_id)" style="border: none; background: none">
                    <i class="bi bi-x-lg" style="color: red; font-weight: bold"></i>
                </button>
            </td>
        </tbody>
    </table>
</div>

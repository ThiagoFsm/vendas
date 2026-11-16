<div class="card shadow-none" style="border: 1.5px solid #d1d5db; border-radius: 10px; overflow: hidden;">
    <div class="card-header bg-white border-bottom fw-bold text-dark py-2 px-3 d-flex justify-content-between align-items-center">
        <span>
            <i class="bi bi-box-seam text-secondary me-1"></i> Itens Selecionados
        </span>
        <span class="badge bg-secondary" v-if="itens_pedido.length > 0">
            @{{ itens_pedido.length }} @{{ itens_pedido.length === 1 ? 'item' : 'itens' }}
        </span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-light sticky-top" style="top: 0; z-index: 2;">
                <tr>
                    <th style="width: 80px;">Qtd</th>
                    <th class="text-start">Produto</th>
                    <th>Sabor</th>
                    <th style="width: 120px;">Tamanho</th>
                    <th style="width: 140px;">Valor</th>
                    <th style="width: 80px;">Ação</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="itens_pedido.length === 0">
                    <td colspan="6" class="py-4 text-center text-muted">
                        <i class="bi bi-cart-x fs-2 d-block text-secondary opacity-50 mb-2"></i>
                        <span class="fw-semibold d-block">Nenhum item adicionado ao pedido</span>
                        <small class="text-muted">Selecione o produto, sabor, tamanho e quantidade acima e clique no botão <strong>+</strong> para adicionar.</small>
                    </td>
                </tr>
                <tr v-else v-for="(item, index) in itens_pedido" :key="index">
                    <td>
                        <span class="badge bg-light text-dark border px-2 py-1 fw-bold fs-6">
                            @{{ item.quantidade }}x
                        </span>
                    </td>
                    <td class="text-start">
                        <span class="fw-bold text-dark">@{{ item.produto }}</span>
                    </td>
                    <td>
                        <span class="text-secondary">@{{ item.sabor || '-' }}</span>
                    </td>
                    <td>
                        <span class="badge bg-secondary text-white px-2 py-1" style="font-size: 0.75rem;">
                            @{{ item.tamanho || '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="fw-bold text-success fs-6">
                            R$ @{{ Number(item.valor).toFixed(2).replace('.', ',') }}
                        </span>
                        <div v-if="item.quantidade > 1" class="text-muted small" style="font-size: 0.75rem;">
                            (R$ @{{ Number(item.valor / item.quantidade).toFixed(2).replace('.', ',') }} un)
                        </div>
                    </td>
                    <td>
                        <button type="button"
                                @click.prevent="excluirItem(index)"
                                class="btn btn-sm btn-outline-danger border-0 p-1 px-2"
                                title="Remover item"
                                style="border-radius: 6px;">
                            <i class="bi bi-trash3 fs-6"></i>
                        </button>
                    </td>
                </tr>
                </tbody>
                <tfoot v-if="itens_pedido.length > 0" class="table-light border-top sticky-bottom" style="bottom: 0; z-index: 2;">
                <tr>
                    <td colspan="4" class="text-end fw-bold py-2 px-3 text-muted">
                        Subtotal dos Itens:
                    </td>
                    <td class="text-center fw-bold text-success fs-6 py-2">
                        R$ @{{ Number(valor_total_itens).toFixed(2).replace('.', ',') }}
                    </td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

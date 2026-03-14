<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Pedidos</h3>
            <p class="text-muted small">Gerencie as vendas e status de pagamento</p>
        </div>
        <a href="/vendas/clientes/create" class="btn btn-outline-secondary px-4 text-black" style="border-radius: 8px;">
            Novo pedido
        </a>
    </div>

    <div class="card card-custom">
        <div class="card-body p-0"> <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered-custom mb-0">
                    <thead>
                    <tr>
                        <th>Nº Pedido</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Qtd</th>
                        <th>Valor Total</th>
                        <th>Status</th>
                        <th class="text-end">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="fw-bold text-dark">#1024</td>
                        <td>João Silva</td>
                        <td class="text-muted">Thiago</td>
                        <td>10 unidades</td>
                        <td>R$ 250,00</td>
                        <td>
                            <span class="badge-status bg-light text-warning" style="border: 1px solid #ffeeba;">Pendente</span>
                        </td>
                        <td class="text-end">
                            <button class="btn-action btn-view">Detalhes</button>
                            <button class="btn-action text-danger border-0 bg-transparent ms-2">Excluir</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">#1025</td>
                        <td>Maria Souza</td>
                        <td class="text-muted">Jaqueline</td>
                        <td>05 unidades</td>
                        <td>R$ 1.100,00</td>
                        <td>
                            <span class="badge-status bg-light text-success" style="border: 1px solid #d4edda;">Concluído</span>
                        </td>
                        <td class="text-end">
                            <button class="btn-action btn-view">Detalhes</button>
                            <button class="btn-action text-danger border-0 bg-transparent ms-2">Excluir</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

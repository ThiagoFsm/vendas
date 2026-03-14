<div class="container-fluid py-4" style="font-size: 13px">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Cadastre o cliente</h3>
            <p class="text-muted small">Preencha os dados do cliente</p>
        </div>
        <a href="/vendas/pedidos" class="btn btn-outline-secondary px-4 text-black" style="border-radius: 8px;">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="card border-10 shadow-sm" style="border-radius: 8px;">
        <div class="card-body p-4">
            <form action="{{ route('vendas.clientes.store') }}" method="POST">
                @CSRF
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="nome" class="form-label fw-bold text-dark">Nome do cliente</label>
                        <input type="text" id="nome" name="nome" class="form-control border-2 bg-light" style="height: 40px; border-radius: 8px;" required>
                    </div>

                    <div class="col-md-6">
                        <label for="vendedor_id" class="form-label fw-bold text-dark">Vendedor(a)</label>
                        <select class="form-select border-2" id="vendedor_id" name="vendedor_id" style="height: 40px;" required>
                            <option value="">Selecione</option>
                            @if(isset($dependencias['vendedores']))
                                @foreach($dependencias['vendedores'] as $vendedor)
                                    <option value="{{ $vendedor['id'] }}">
                                        {{ $vendedor['nome'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="reset" class="btn btn-outline-secondary px-4 text-black" style="border-radius: 8px;">Limpar</button>
                        <button type="submit" class="btn btn-dark px-3 py-2 fw-bold" style="border-radius: 8px; letter-spacing: 0.5px;">
                            <i class="bi bi-check-circle"></i> SALVAR CLIENTE
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

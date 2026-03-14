<div class="container-fluid py-4" style="font-size: 13px">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Novo Pedido</h3>
            <p class="text-muted small">Preencha os dados para registrar uma nova venda</p>
        </div>
        <a href="/pedidos" class="btn btn-outline-secondary px-4 text-black" style="border-radius: 8px;">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
    {{--    {{ dd($dependencias['cliente']) }}--}}
    <div class="card border-10 shadow-sm" style="border-radius: 8px;">
        <div class="card-body p-4">
            <form action="#" method="POST">
                <div class="row g-4">
                    <div class="col-md-6 text-center">
                        <span class="form-label fw-bold text-dark">Cliente</span>
                        <input type="text" id="cliente_id" name="cliente_id" class="form-control border-2 bg-light mt-1"
                               value="{{ $dependencias['cliente']->nome ?? '' }}"
                               style="height: 40px; border-radius: 8px;" readonly>
                    </div>

                    <div class="col-md-6 text-center">
                        <span class="form-label fw-bold text-dark">Vendedor(a)</span>
                        <input id="vendedor" name="vendedor" class="form-control border-2 bg-light mt-1"
                               value="{{ $dependencias['cliente']->vendedor->nome ?? '' }}"
                               style="height: 40px; border-radius: 8px;" readonly>
                    </div>

                    <div class="col-12">
                        <hr class="text-muted opacity-25">
                    </div>

                    <div class="col-12">
                        <h5 class="fw-bold mb-3" style="color: #495057;">Itens do Pedido</h5>
                        <div class="row g-3 align-items-end p-3 mb-3"
                             style="background-color: #f8f9fa; border-radius: 8px;">
                            <div class="col-md-3  text-center">
                                <label for="item_produto" class="form-label small fw-bold text-muted">Produto</label>
                                <select id="item_produto" name="item_produto" class="form-select select-input border-2"
                                        v-model="item.tipo_produto_id" style="height: 40px;" v-select>
                                    <option value="">Selecione</option>
                                    @if(isset($dependencias['tipoProdutos']))
                                        @foreach($dependencias['tipoProdutos'] as $tipoProduto)
                                            <option value="{{ $tipoProduto['id'] }}">
                                                {{ $tipoProduto['descricao'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4 text-center">
                                <label for="item_sabor" class="form-label small fw-bold text-muted">Sabor</label>
                                <select id="item_sabor" name="item_sabor" class="form-select select-input border-2"
                                        v-model="item.sabor_id" style="height: 40px;" v-select>
                                    <option value="">Selecione</option>
                                    @if(isset($dependencias['sabores']))
                                        @foreach($dependencias['sabores'] as $sabor)
                                            <option value="{{ $sabor['id'] }}">
                                                {{ $sabor['descricao'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2 text-center">
                                <label for="item_tamanho" class="form-label small fw-bold text-muted">Tamanho</label>
                                <select id="item_tamanho" name="item_tamanho" class="form-select select-input border-2"
                                        v-model="item.tamanho_id" style="height: 40px; border-radius: 8px;" v-select>
                                    <option value="">Selecione</option>
                                    @if(isset($dependencias['tamanhos']))
                                        @foreach($dependencias['tamanhos'] as $tamanho)
                                            <option value="{{ $tamanho['id'] }}">
                                                {{ $tamanho['descricao'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2 text-center">
                                <label for="item_quantidade"
                                       class="form-label small fw-bold text-muted">Quantidade</label>
                                <input id="item_quantidade" name="item_quantidade" type="number"
                                       class="form-control border-2" v-model="item.quantidade" min="1" placeholder="Qtd"
                                       style="height: 40px; border-radius: 8px;">
                            </div>
                            <div class="col-md-1 my-1 p-0">
                                <button @click.prevent="adicionarItem" class="mx-2 mb-2 px-2 py-1"
                                        style="border-radius: 8px; background-color: black; border: black">
                                    <h5 style="font-size: 14px; margin: 0; color: white; font-weight: bold">+</h5>
                                </button>
                            </div>
                        </div>
                        <div>
                            <div v-if="listar" style="height: 150px; overflow-y: auto">
                                @include('pedidos.partials.pedido_itens')
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <hr class="text-muted opacity-25">
                    </div>

                    <div class="col-md-6 text-md-center">
                        <span class="form-label d-block fw-bold text-dark">Entrega / Retirada</span>
                        <div class="d-flex gap-3 mt-2 justify-content-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_entrega" id="uber"
                                       v-model="uber_selecionado" value="1">
                                <label class="form-check-label" for="uber">Uber</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_entrega" id="retirada"
                                       v-model="retirada_selecionada" value="1">
                                <label class="form-check-label" for="retirada">Retirada</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_entrega" id="entrega"
                                       v-model="entrega_selecionada" value="1">
                                <label class="form-check-label" for="entrega">Entrega</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 text-md-center mb-3">
                        <span class="form-label d-block fw-bold text-dark">Valor Pago na Hora</span>
                        <div class="d-flex align-items-center justify-content-center gap-2 mt-1" role="group"
                             style="border-radius: 8px; overflow: hidden;">
                            <label class="form-label small fw-bold text-muted mb-0" for="valor_antecipado">R$</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control border-2 text-center" name="valor_antecipado"
                                       id="valor_antecipado" v-model.lazy="valor_antecipado" v-money="$dinheiro"
                                       style="height: 40px; font-size: 13px; border-radius: 8px;">
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="uber_selecionado" class="col-md-12">
                    <div class="col-12">
                        <hr class="text-muted opacity-25">
                    </div>
                    @include('pedidos.partials.uber')
                </div>

                <div v-if="retirada_selecionada" class="col-md-12">
                    <div class="col-12">
                        <hr class="text-muted opacity-25">
                    </div>
                    @include('pedidos.partials.retirada')
                </div>

                <div v-if="entrega_selecionada" class="col-md-12">
                    <div class="col-12">
                        <hr class="text-muted opacity-25">
                    </div>
                    @include('pedidos.partials.entrega')
                </div>

                <div
                    class="mt-5 pt-4 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="p-3 bg-light" style="border-radius: 8px; border-left: 5px solid #212529;">
                        <span class="text-muted small d-block">Total do Pedido</span>
                        <strong class="fs-4 text-dark">
                            {{"R$"}}@{{ valor_total_itens }}{{",00"}} + {{"R$"}}@{{ uber.valor_uber }}{{" (Uber)"}} =
                        </strong>
                        <strong class="fs-4 text-dark">
                            {{"R$"}}@{{ valor_total_pedido }}
                        </strong>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="reset" class="btn btn-outline-secondary px-4 text-black"
                                style="border-radius: 8px;">Limpar
                        </button>
                        <button @click.prevent="salvarPedido" type="submit" class="btn btn-dark px-3 py-2 fw-bold"
                                style="border-radius: 8px; letter-spacing: 0.5px;">
                            <i class="bi bi-check-circle"></i> SALVAR PEDIDO
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


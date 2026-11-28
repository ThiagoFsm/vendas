<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Produção</h3>
            <h5 class="text-muted">Gerencie a produção dos produtos</h5>
        </div>
    </div>
    <div v-if="sabores">
        <div v-for="sabor in sabores" :key="sabor.id">
            <div class="card card-custom mb-2">
                <div class="card-body p-3">
                    <div class="text-center">
                        <div>
                            <h5 style="color: black">@{{ sabor.descricao }}</h5>
                        </div>
                        <div class="d-flex flex-row flex-wrap gap-2 justify-content-center" v-if="tamanhos && tamanhos.length > 0">
                            <div v-for="tamanho in tamanhos" :key="tamanho.id">
                                <button class="btn-sabor" :style="{ background: GerarCorSabor(sabor.id), color: 'white' }"
                                        @click.prevent="filtrarItens(sabor.id, sabor.descricao, tamanho.id)">
                                    @{{ tamanho.descricao }}
                                </button>
                            </div>
                        </div>
                        <div v-else class="text-muted small">
                            Nenhum tamanho ativo encontrado.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('producao.partials.modalDetalhesProducao')
</div>

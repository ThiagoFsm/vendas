<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Produção</h3>
            <h5 class="text-muted">Gerencie a produção dos produtos</h5>
        </div>
    </div>
    <div v-if="sabores">
        <div v-for="sabor in sabores" :v-key="sabor.id">
            <div class="card card-custom mb-2">
                <div class="card-body p-3">
                    <div class="text-center">
                        <div>
                            <h5 style="color: black">@{{ sabor.descricao }}</h5>
                        </div>
                        <div class="d-flex flex-row gap-2 justify-content-center">
                            <div class="btn-success">
                                <button class="btn-sabor" :style="{ background: GerarCorSabor(sabor.id), color: 'white' }"
                                @click.prevent="filtrarItens(sabor.id, sabor.descricao, 1)">150g</button>
                            </div>
                            <div>
                                <button class="btn-sabor" :style="{ background: GerarCorSabor(sabor.id), color: 'white' }"
                                @click.prevent="filtrarItens(sabor.id, sabor.descricao, 2)">350g</button>
                            </div>
                            <div>
                                <button class="btn-sabor" :style="{ background: GerarCorSabor(sabor.id), color: 'white' }"
                                @click.prevent="filtrarItens(sabor.id, sabor.descricao, 3)">500g</button>
                            </div>
                            <div>
                                <button class="btn-sabor" :style="{ background: GerarCorSabor(sabor.id), color: 'white' }"
                                @click.prevent="filtrarItens(sabor.id, sabor.descricao, 4)">coração</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('sabores.partials.modalDetalhesProducao')
</div>

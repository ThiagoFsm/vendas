<span class="form-label d-block fw-bold text-dark mt-4">Entrega</span>
<div class="d-flex gap-3 justify-content-center">
    <div class="col-md-3 text-center">
        <label for="entregador_id" class="form-label small fw-bold text-muted">Quem?</label>
        <select class="form-select select-input border-2" id="entregador_id" name="entregador_id"
                v-model="entrega.entregador_id" style="height: 40px;" v-select required>
            <option value="">Selecione</option>
            <option v-for="vendedor in vendedores" :v-key="vendedor.id" :value="vendedor.id">
                @{{ vendedor.nome }}
            </option>
        </select>
    </div>
    <div class="col-md-3 text-center">
        <label for="data" class="form-label small fw-bold text-muted">Data</label>
        <input type="date" id="data" name="data" class="form-control border-2"
               v-model="entrega.data" style="height: 40px; border-radius: 8px;">
    </div>
    <div class="col-md-3 text-center">
        <label for="periodo" class="form-label small fw-bold text-muted">Período</label>
        <select class="form-select select-input border-2" name="periodo" id="periodo"
                v-model="entrega.periodo" style="height: 40px; border-radius: 8px" v-select>
            <option value="">Selecione</option>
            <option value="Manhã">Manhã</option>
            <option value="Tarde">Tarde</option>
        </select>
    </div>
</div>

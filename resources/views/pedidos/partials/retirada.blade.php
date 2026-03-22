<span class="form-label d-block fw-bold text-dark mt-4">Retirada</span>
<div class="d-flex gap-3 justify-content-center">
    <div class="col-md-3 text-center">
        <label for="bairro" class="form-label small fw-bold text-muted">Bairro</label>
        <select name="bairro" id="bairro" class="form-select select-input border-2" v-model="retirada.bairro"
                style="height: 40px; border-radius: 8px;" v-select required>
            <option value="">Selecione</option>
            <option value="Jacy">Jacy</option>
            <option value="Tiradentes">Tiradentes</option>
        </select>
    </div>
    <div class="col-md-3 text-center">
        <label for="data" class="form-label small fw-bold text-muted">Data</label>
        <input type="date" id="data" name="data" class="form-control border-2"
               v-model="retirada.data" style="height: 40px; border-radius: 8px;" required>
    </div>
    <div class="col-md-3 text-center">
        <label for="periodo" class="form-label small fw-bold text-muted">Período</label>
        <select class="form-select select-input border-2" name="periodo" id="periodo"
                v-model="retirada.periodo" style="height: 40px; border-radius: 8px" v-select>
            <option value="">Selecione</option>
            <option value="Manhã">Manhã</option>
            <option value="Tarde">Tarde</option>
        </select>
    </div>
</div>

<h5 class="fw-bold mt-4 mb-3" style="color: #495057;">Retirada</h5>
<div class="row g-3 justify-content-center">
    <div class="col-lg-3 col-md-6 text-center">
        <label for="bairro" class="form-label small fw-bold text-muted">Bairro</label>
        <select name="bairro" id="bairro" class="form-select select-input border-2" v-model="retirada.bairro"
                style="height: 48px; border-radius: 8px;" v-select required>
            <option value="">Selecione</option>
            <option value="Jacy">Jacy</option>
            <option value="Tiradentes">Tiradentes</option>
        </select>
    </div>
    <div class="col-lg-3 col-md-6 text-center">
        <label for="data" class="form-label small fw-bold text-muted">Data</label>
        <input type="date" id="data" name="data" class="form-control border-2"
               v-model="retirada.data" style="height: 48px; border-radius: 8px;" required>
    </div>
    <div class="col-lg-3 col-md-6 text-center">
        <label for="periodo" class="form-label small fw-bold text-muted">Período</label>
        <select class="form-select select-input border-2" name="periodo" id="periodo"
                v-model="retirada.periodo" style="height: 48px; border-radius: 8px" v-select>
            <option value="">Selecione</option>
            <option value="Manhã">Manhã</option>
            <option value="Tarde">Tarde</option>
            <option value="Noite">Noite</option>
        </select>
    </div>
    <div class="col-lg-3 col-md-6 text-center">
        <label for="horas_retirada" class="form-label small fw-bold text-muted">Horas</label>
        <input type="time" id="horas_retirada" name="horas" class="form-control border-2 text-center"
               v-model="retirada.horas" style="height: 48px; border-radius: 8px;">
    </div>
</div>

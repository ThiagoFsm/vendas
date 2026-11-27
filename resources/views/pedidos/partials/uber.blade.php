<h5 class="fw-bold mt-4 mb-3" style="color: #495057;">Uber</h5>
<div class="row g-3 justify-content-center">
    <div class="col-lg-4 col-md-6 text-center">
        <label for="bairro" class="form-label small fw-bold text-muted">Bairro</label>
        <select id="bairro" name="bairro" class="form-select select-input border-2"
                v-model="uber.bairro_id" style="height: 48px; border-radius: 8px" v-select required>
            <option value="">Selecione</option>
            <option v-for="bairro in bairros" :key="bairro.id" :value="bairro.id">
                @{{ bairro.descricao }}
            </option>
        </select>
    </div>
    <div class="col-lg-4 col-md-6 text-center">
        <label for="rua" class="form-label small fw-bold text-muted">Rua</label>
        <input type="text" id="rua" name="rua" class="form-control border-2"
               v-model="uber.rua" style="height: 48px; border-radius: 8px;" placeholder="Rua" autocomplete="on" required>
    </div>
    <div class="col-lg-2 col-md-6 text-center">
        <label for="numero" class="form-label small fw-bold text-muted">N°</label>
        <input type="text" id="numero" name="numero" class="form-control border-2 text-center"
               v-model="uber.numero" style="height: 48px; border-radius: 8px;" placeholder="Número" autocomplete="on" required>
    </div>
    <div class="col-lg-2 col-md-6 text-center">
        <label for="valor_uber" class="form-label small fw-bold text-muted">Valor R$</label>
        <input type="text" id="valor_uber" name="valor_uber" class="form-control border-2 text-center"
               v-model="uber.valor_uber" style="height: 48px; border-radius: 8px; font-size: 18px" v-money="$dinheiro" required>
    </div>
    <div class="col-lg-4 col-md-4 text-center">
        <label for="data" class="form-label small fw-bold text-muted">Data</label>
        <input type="date" id="data" name="data" class="form-control border-2"
               v-model="uber.data" style="height: 48px; border-radius: 8px;" autocomplete="on" required>
    </div>
    <div class="col-lg-4 col-md-4 text-center">
        <label for="periodo" class="form-label small fw-bold text-muted">Período</label>
        <select class="form-select select-input border-2" name="periodo" id="periodo"
                v-model="uber.periodo" style="height: 48px; border-radius: 8px" v-select required>
            <option value="">Selecione</option>
            <option value="Manhã">Manhã</option>
            <option value="Tarde">Tarde</option>
            <option value="Noite">Noite</option>
        </select>
    </div>
    <div class="col-lg-4 col-md-4 text-center">
        <label for="horas_uber" class="form-label small fw-bold text-muted">Horas</label>
        <input type="time" id="horas_uber" name="horas" class="form-control border-2 text-center"
               v-model="uber.horas" style="height: 48px; border-radius: 8px;">
    </div>
</div>

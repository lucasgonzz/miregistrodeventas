
<!-- Modal -->
<div class="modal fade" id="ventas-desde-una-fecha" tabindex="-1" role="dialog" aria-labelledby="ventas-resumens" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Mostrar ventas desde una fecha</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="">
          <div class="form-group">
            <label for="desde">Buscar desde</label>
            <input type="date" id="desde" v-model="desde" class="form-control">
          </div>
          <div class="form-group">
            <label for="hasta">Hasta</label>
            <input type="date" id="hasta" v-model="hasta" class="form-control">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" @click="fromDate">Buscar</button>
      </div>
    </div>
  </div>
</div>
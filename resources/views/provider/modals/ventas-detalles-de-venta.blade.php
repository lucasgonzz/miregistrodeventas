
<!-- Modal -->
<div class="modal fade" id="ventas-detalles-de-venta" tabindex="-1" role="dialog" aria-labelledby="ventas-resumens" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalles de la venta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead class="thead-dark">
            <th scope="col">Codigo</th>    
            <th scope="col">Nombre</th>    
            <th v-show="mostrarCostos" scope="col">Costo</th>      
            <th scope="col">Precio</th>      
          </thead>
          <tbody>
            <tr>
              <td>1231231</td>
              <td>Camion</td>
              <td v-show="mostrarCostos">$450</td>
              <td>$700</td>
            </tr>
            <tr>
              <td>1231231</td>
              <td>Camion</td>
              <td v-show="mostrarCostos">$450</td>
              <td>$700</td>
            </tr>
            <tr>
              <td>1231231</td>
              <td>Camion</td>
              <td v-show="mostrarCostos">$450</td>
              <td>$700</td>
            </tr>
            <tr>
              <td>1231231</td>
              <td>Camion</td>
              <td v-show="mostrarCostos">$450</td>
              <td>$700</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">
          <i class="icon-print"></i>
          Imprimir con costos
        </button>
        <button type="button" class="btn btn-primary">
          <i class="icon-print"></i>
          Imprimir sin costos
        </button>
      </div>
    </div>
  </div>
</div>
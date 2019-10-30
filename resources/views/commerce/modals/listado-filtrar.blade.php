
<!-- Modal -->
<div class="modal fade" id="listado-filtrar" tabindex="-1" role="dialog" aria-labelledby="ventas-resumens" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Filtrar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="">
          <div class="form-group">
            <label for="articulos-a-mostrar">Mostrar</label>
            <select name="" id="articulos-a-mostrar" class="form-control">
              <option value="">Todos mis artículos</option>
              <option value="">Los desactualizados</option>
              <option value="">Los que nunca se hayan vendido</option>
              <option value="">Los que no tengan stock</option>
            </select>
          </div>
          <div class="form-group">
            <label for="ordenar">Ordenar los artículos</label>
            <select name="ordenar" id="ordenar" class="form-control">
              <option value="">De mas viejos a mas nuevos</option>
              <option value="">De mas nuevos a mas viejos</option>
              <option value="">De mayor a menor precio</option>
              <option value="">De menor a mayor precio</option>
            </select>
          </div>
          <div class="form-group">
            <label for="ordenar">Los artículos que pertenezcan a el/los mayoristas</label>
            <select name="ordenar" id="ordenar" class="form-control" multiple>
              <option v-for="provider in providers" value="provider.id">
                @{{ provider.name }}
              </option> 
            </select>
          </div>
          <div class="form-group">
            <label for="">Los artículos que tengan un precio entre</label>
            <div class="row">
              <div class="col">
                <input type="text" placeholder="Precio minimo" class="form-control">
              </div>
              <i class="icon-minus p-t-5"></i>
              <div class="col">
                <input type="text" placeholder="Precio maximo" class="form-control">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" @click="">Filtrar</button>
      </div>
    </div>
  </div>
</div>
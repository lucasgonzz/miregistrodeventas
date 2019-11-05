<template>

<!-- Modal -->
<div class="modal fade" id="listado-descargar-pdf" tabindex="-1" role="dialog" aria-labelledby="ventas-resumens" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Descargar mis artículos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <h5>¿Que artículos quiere descargar?</h5>
            <div class="custom-control custom-radio">
              <input type="radio" id="todos" name="customRadio" class="custom-control-input">
              <label class="custom-control-label" for="todos">Todos</label>
            </div>
            <div class="custom-control custom-radio">
              <input type="radio" id="esta-pagina" name="customRadio" class="custom-control-input">
              <label class="custom-control-label" for="esta-pagina">Solo los de esta página</label>
            </div>
          </div>
        </div>
        <div class="row m-t-10">
          <div class="col">
            <h5>¿Que columnas quiere que se muestren?</h5>
            <!-- <div class="form-check">
              <input class="form-check-input" v-model="columnas_para_imprimir" type="checkbox" value="bar_code" id="codigo">
              <label class="form-check-label" for="codigo">
                Codigo de barras
              </label>
            </div> -->
            <div class="custom-control custom-checkbox my-1 mr-sm-2">
              <input type="checkbox" v-model="columnasParaImprimir" class="custom-control-input" value="bar_code" id="bar_code">
              <label class="custom-control-label" for="bar_code">Codigo de barras</label>
            </div>
            <div class="custom-control custom-checkbox my-1 mr-sm-2">
              <input class="custom-control-input" v-model="columnasParaImprimir" type="checkbox" value="name" id="nombre">
              <label class="custom-control-label" for="nombre">
                Nombre
              </label>
            </div>
            <div class="custom-control custom-checkbox my-1 mr-sm-2">
              <input class="custom-control-input" v-model="columnasParaImprimir" type="checkbox" value="cost" id="costo">
              <label class="custom-control-label" for="costo">
                Costo
              </label>
            </div>
            <div class="custom-control custom-checkbox my-1 mr-sm-2">
              <input class="custom-control-input" v-model="columnasParaImprimir" type="checkbox" value="price" id="precio">
              <label class="custom-control-label" for="precio">
                Precio
              </label>
            </div>
            <div class="custom-control custom-checkbox my-1 mr-sm-2">
              <input class="custom-control-input" v-model="columnasParaImprimir" type="checkbox" value="created_at" id="created_at">
              <label class="custom-control-label" for="created_at">
                Fecha en que se agrego
              </label>
            </div>
            <div class="custom-control custom-checkbox my-1 mr-sm-2">
              <input class="custom-control-input" v-model="columnasParaImprimir" type="checkbox" value="updated_at" id="updated_at">
              <label class="custom-control-label" for="updated_at">
                Ultima fecha en que se actualizo
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" @click="">Generar Pdf</button>
      </div>
    </div>
  </div>
</div>
</template>
<script>
export default {
    props: ['columnasParaImprimir']
}
</script>
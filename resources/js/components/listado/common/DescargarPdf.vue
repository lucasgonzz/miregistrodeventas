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
             <input type="radio" v-model="articulosADescargar" value="todos" id="todos" name="customRadio" class="custom-control-input">
              <label class="custom-control-label" for="todos">Todos</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" v-model="articulosADescargar" value="esta-pagina" id="esta-pagina" name="customRadio" class="custom-control-input">
                <label class="custom-control-label" for="esta-pagina">Solo los de esta página</label>
            </div>
          </div>
        </div>
        <div class="row m-t-10">
          <div class="col">
            <h5>¿Que columnas quiere que se muestren?</h5>
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
              <input class="custom-control-input" v-model="columnasParaImprimir" type="checkbox" value="previus_price" id="precio">
              <label class="custom-control-label" for="precio">
                Precio anterior
              </label>
            </div>
            <div class="custom-control custom-checkbox my-1 mr-sm-2">
              <input class="custom-control-input" v-model="columnasParaImprimir" type="checkbox" value="stock" id="precio">
              <label class="custom-control-label" for="precio">
                Stock
              </label>
            </div>
            <div v-show="rol == 'commerce'" class="custom-control custom-checkbox my-1 mr-sm-2">
              <input class="custom-control-input" v-model="columnasParaImprimir" type="checkbox" value="providers" id="precio">
              <label class="custom-control-label" for="precio">
                Proveedores
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
        <div class="col">
          <small class="form-text text-muted">
            Puede imprimir un maximo de 7 columnas  
          </small>
        </div>
        {{ columnasParaImprimir }}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <a :href="getLink()" target="_blank" class="btn btn-primary" >Generar Pdf</a>
      </div>
    </div>
  </div>
</div>
</template>
<script>
export default {
    props: ['rol', 'idsArticles'],
    data() {
      return {
        articulosADescargar: 'esta-pagina',
        columnasParaImprimir: ['name', 'cost', 'price', 'created_at'],
      }
    },
    methods: {
        pdf() {
            this.$emit('generatePdf', this.columnasParaImprimir)
        },
        getColumns() {
            return this.columnasParaImprimir.join('-')
        },
        getLink() {
            var link = 'pdf/' + this.columnasParaImprimir.join('-') + '/'
            if (this.articulosADescargar == 'esta-pagina') {
                this.idsArticles.forEach( id => {
                    link += id + '-'
                })
                link = link.substring(0, link.length-1)
            } else {
                link += 'todos'
            }
            return link
        }
    }
}
</script>
<template>

<!-- Modal -->
<div class="modal fade" id="listado-descargar-pdf" tabindex="-1" role="dialog" aria-labelledby="ventas-resumens" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Descargar mis artículos</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <div class="card">
              <div class="card-header">
                <h6 class="m-0">¿Que artículos quiere descargar?</h6>
              </div>
              <div class="card-body">
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
          </div>
        </div>
        <div class="row m-t-10">
          <div class="col">
            <div class="card">
              <div class="card-header">
                <h6 class="m-0">¿Que columnas quiere que se muestren?</h6>
              </div>
              <div class="card-body">
                <div class="custom-control custom-checkbox  custom-control-inline-50 my-1 mr-sm-2">
                    <input type="checkbox" v-model="columnasParaImprimir" class="custom-control-input" value="bar_code" id="bar_code">
                    <label class="custom-control-label" for="bar_code">Codigo de barras</label>
                </div>
                <div class="custom-control custom-checkbox  custom-control-inline-50 my-1 mr-sm-2">
                  <input class="custom-control-input" v-model="columnasParaImprimir" type="checkbox" value="name" id="name">
                  <label class="custom-control-label" for="name">
                    Nombre
                  </label>
                </div>
                <div class="custom-control custom-checkbox  custom-control-inline-50 my-1 mr-sm-2">
                  <input class="custom-control-input" v-model="columnasParaImprimir" type="checkbox" value="cost" id="cost">
                  <label class="custom-control-label" for="cost">
                    Costo
                  </label>
                </div>
                <div class="custom-control custom-checkbox  custom-control-inline-50 my-1 mr-sm-2">
                  <input class="custom-control-input" v-model="columnasParaImprimir" type="checkbox" value="price" id="price">
                  <label class="custom-control-label" for="price">
                    Precio
                  </label>
                </div>
                <div class="custom-control custom-checkbox  custom-control-inline-50 my-1 mr-sm-2">
                  <input class="custom-control-input" v-model="columnasParaImprimir" type="checkbox" value="previus_price" id="previus_price">
                  <label class="custom-control-label" for="previus_price">
                    Precio anterior
                  </label>
                </div>
                <div class="custom-control custom-checkbox  custom-control-inline-50 my-1 mr-sm-2">
                  <input class="custom-control-input" v-model="columnasParaImprimir" type="checkbox" value="stock" id="stock">
                  <label class="custom-control-label" for="stock">
                    Stock
                  </label>
                </div>
                <!-- <div v-show="rol == 'commerce'" class="custom-control custom-checkbox  custom-control-inline-50 my-1 mr-sm-2">
                  <input class="custom-control-input" v-model="columnasParaImprimir" type="checkbox" value="providers" id="precio">
                  <label class="custom-control-label" for="precio">
                    Proveedores
                  </label>
                </div> -->
                <div class="custom-control custom-checkbox  custom-control-inline-50 my-1 mr-sm-2">
                  <input class="custom-control-input" v-model="columnasParaImprimir" type="checkbox" value="created_at" id="created_at">
                  <label class="custom-control-label" for="created_at">
                    Fecha en que se agrego
                  </label>
                </div>
                <div class="custom-control custom-checkbox  custom-control-inline-50 my-1 mr-sm-2">
                  <input class="custom-control-input" v-model="columnasParaImprimir" type="checkbox" value="updated_at" id="updated_at">
                  <label class="custom-control-label" for="updated_at">
                    Ultima fecha en que se actualizo
                  </label>
                </div>
              </div>
              <!-- <div class="card-footer">
                <small class="form-text text-muted">
                  Si selecciona mas de 6 columnas la orientation cambiara de normal a apaisado
                </small>
              </div> -->
            </div>
          </div>
        </div>
        <div class="row m-t-10">
          <div class="col">
            <div class="card">
              <div class="card-header">
                <h6 class="m-0">Horientación</h6>
              </div>
              <div class="card-body">
                <div class="custom-control custom-radio">
                 <input type="radio" v-model="orientation" value="normal" id="normal" name="normal" class="custom-control-input">
                  <label class="custom-control-label" for="normal">Normal</label>
                </div>
                <div class="custom-control custom-radio">
                 <input type="radio" v-model="orientation" value="apaisado" id="apaisado" name="apaisado" class="custom-control-input">
                  <label class="custom-control-label" for="apaisado">Apaisado</label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row m-t-10">
          <div class="col">
            <div class="card">
              <div class="card-header">
                <h6 class="m-0">Encabezado</h6>
              </div>
              <div class="card-body">
                <div class="custom-control custom-checkbox my-1 mr-sm-2">
                  <input class="custom-control-input" v-model="header" type="checkbox" value="date" id="date">
                  <label class="custom-control-label" for="date">
                    Fecha
                  </label>
                </div>
                <div class="custom-control custom-checkbox my-1 mr-sm-2">
                  <input class="custom-control-input" v-model="header" type="checkbox" value="company_name" id="company_name">
                  <label class="custom-control-label" for="company_name">
                    Nombre del negocio
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
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
    props: ['rol', 'idsArticles', 'filtro'],
    data() {
      return {
        articulosADescargar: 'esta-pagina',
        columnasParaImprimir: ['name', 'cost', 'price', 'created_at'],
        orientation: 'normal',
        // design: 'blanco-negro',
        header: ['date', 'company_name'],
      }
    },
    methods: {
        getLink() {
            var link = 'pdf/' + this.getColumnasParaImpirimir().join('-') + '/'
            if (this.articulosADescargar == 'esta-pagina') {
                this.idsArticles.forEach( id => {
                    link += id + '-'
                })
                link = link.substring(0, link.length-1)
            } else {
                link += 'todos'
            }
            link += '/' + this.orientation + '/'
            if (this.header.length) {
              link += this.header.join('-')
            }
            return link
        },
        getColumnasParaImpirimir() {
          var columns = []
          if (this.columnasParaImprimir.includes('bar_code')) {
            columns.push('bar_code')
          }
          if (this.columnasParaImprimir.includes('name')) {
            columns.push('name')
          }
          if (this.columnasParaImprimir.includes('cost')) {
            columns.push('cost')
          }
          if (this.columnasParaImprimir.includes('price')) {
            columns.push('price')
          }
          if (this.columnasParaImprimir.includes('previus_price')) {
            columns.push('previus_price')
          }
          if (this.columnasParaImprimir.includes('stock')) {
            columns.push('stock')
          }
          if (this.columnasParaImprimir.includes('created_at')) {
            columns.push('created_at')
          }
          if (this.columnasParaImprimir.includes('updated_at')) {
            columns.push('updated_at')
          }
          return columns
        }
    }
}
</script>
<template>

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
            <select name="" v-model="filtro.mostrar" id="articulos-a-mostrar" class="form-control">
              <option value="todos">Todos mis artículos</option>
              <option value="desactualizados">Los desactualizados</option>
              <option value="no-vendidos">Los que nunca se hayan vendido</option>
              <option value="no-stock">Los que no tengan stock</option>
            </select>
          </div>
          <div class="form-group">
            <label for="ordenar">Ordenar los artículos</label>
            <select name="ordenar" v-model="filtro.ordenar" id="ordenar" class="form-control">
              <option value="nuevos-viejos">De mas nuevos a mas viejos</option>
              <option value="viejos-nuevos">De mas viejos a mas nuevos</option>
              <option value="caros-baratos">De mayor a menor precio</option>
              <option value="baratos-caros">De menor a mayor precio</option>
            </select>
          </div>
          <div class="form-group" v-show="rol == 'commerce'">
            
            <label class="label-block">Los que pertenezcan a los siguientes proveedores</label>
            <div v-for="provider in providers" 
              class="custom-control custom-checkbox custom-control-inline">
              <input v-model="filtro.providers" type="checkbox" class="custom-control-input" :id="'f'+provider.id" :value="provider.name">
              <label class="custom-control-label" :for="'f'+provider.id">
                {{ provider.name }}
              </label>
            </div>
            <br>
            <button @click.prevent="uncheckProviders" class="btn btn-secondary btn-sm m-t-5">Desmarcar todos</button>
          </div>
          <div class="form-group">
            <label for="">Los artículos que tengan un precio entre</label>
            <div class="row">
              <div class="col">
                <div class="input-group mb-2 mr-sm-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text">$</div>
                  </div>
                  <input type="text" v-model="filtro.precio_entre.min" placeholder="Precio minimo" class="form-control">
                </div>
              </div>
              <i class="icon-minus p-t-5"></i>
              <div class="col">
                <div class="input-group mb-2 mr-sm-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text">$</div>
                  </div>
                  <input type="text" v-model="filtro.precio_entre.max" placeholder="Precio maximo" class="form-control">
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" @click="filter">Filtrar</button>
      </div>
    </div>
  </div>
</div>
</template>
<script>
export default {
    props: ['filtro', 'rol', 'providers'],
    methods: {
        filter() {
            this.$emit('filter', this.filtro)
        },
        uncheckProviders() {
          this.$emit('uncheckProviders')
        }
    }
}
</script>
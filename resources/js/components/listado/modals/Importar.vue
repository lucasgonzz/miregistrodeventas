<template>

<!-- Modal -->
<div class="modal fade" id="listado-importar" tabindex="-1" role="dialog" aria-labelledby="ventas-resumens" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Importar artículos</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="articles/import/exel" method="POST" enctype="multipart/form-data">
	      <div class="modal-body">
	        <div class="row">
	          <div class="col">
	          		<div class="card">
	          			<div class="card-header">
	          				¿Que columnas tenes en tu archivo exel?
	          			</div>
	          			<div class="card-body">
	          				<ul class="list-group">
	          					<li class="list-group-item p-5" v-for="column in columns">
	          						{{ column.name }}
	          						<span class="float-right btn-importar" @click="up(column)">
	          							<i class="icon-up text-primary"></i>
	          						</span>
	          						<span class="float-right btn-importar" @click="down(column)">
	          							<i class="icon-down text-primary"></i>
	          						</span>
	          					</li>
	          				</ul>
	          			</div>
	          		</div>
	            	<div class="form-group">
	            		<input type="hidden" name="_token" :value="csrf">
	            		csrf:
	            		{{csrf}}
	            		<div class="custom-file">
						  <input type="file" class="custom-file-input" id="exel" name="exel">
						  <label class="custom-file-label" for="exel" data-browse="Elegir">Selecciona tu archivo exel</label>
						</div>
	            	</div>
	          </div>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
	        <button type="submit" class="btn btn-primary" >Importar</button>
	      </div>
      </form>
    </div>
  </div>
</div>
</template>
<script>
export default {
    props: [],
    data() {
      return {
      	csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      	columns: [
      		{value: 'bar_code', name: 'Codigo de barras'},
      		{value: 'name', name: 'Nombre'},
      		{value: 'cost', name: 'Costo'},
      		{value: 'price', name: 'Precio'},
      		{value: 'previus_price', name: 'Precio Anterior'},
      		{value: 'stock', name: 'Stock'},
      		{value: 'created_at', name: 'Ingresado'},
      		{value: 'updated_at', name: 'Actualizado'},
      	],
      }
    },
    methods: {
    	up(column) {
    		// column = 
    		console.log(column)
    		var i = this.columns.indexOf(column)
    		var anterior = this.columns[i-1]
    		var actual = column
    		console.log('Entrooo')
    		console.log('anterior')
    		console.log(anterior)
    		console.log('actual')
    		console.log(column)
    		this.columns[i].value = anterior.value
    		this.columns[i].name = anterior.name
    		this.columns[i-1].value = actual.value
    		this.columns[i-1].name = actual.name
    	},
    	down(column) {
    		var i = this.columns.indexOf(column)
    		var posterior = this.columns[i+1]
    		this.columns[i] = posterior
    		this.columns[i+1] = column
    	}
    }
}
</script>
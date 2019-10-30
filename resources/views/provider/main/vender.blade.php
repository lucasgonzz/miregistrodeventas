@extends('layouts.app')
@section('content')
<div id="vender">
	<div class="row justify-content-center">	
		<div class="col-lg-9">	
			<div class="card">
				<div class="card-header">
					<form>
						<div class="form-row">	
							<div class="col-8">	
								<div class="input-group mb-2 mr-sm-2">
									<div class="input-group-prepend">
									  	<div class="input-group-text">Codigo de barras</div>
									</div>
									<input type="text" class="form-control" placeholder="Ingrese el codigo de barras">
								</div>
							</div>
							<div class="col-4">	
								<input type="text" class="form-control" placeholder="Cantidad">
							</div>
						</div>
					</form>	
				</div>
				<div class="card-body">
					<div v-show="!ventaRealizada">
						<table class="table text-center table-striped">
							<thead>
								<tr>
									<th scope="col">Nombre</th>
									<th scope="col">Precio Unitario</th>
									<th scope="col">Cantidad</th>
									<th scope="col">Subtotal</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="article in articles">
									<td>@{{ article.name }}</td>
									<th scope="row">$@{{ price(article.price) }}</th>
									<td>@{{ article.cantidad }}</td>
									<td>$@{{ price(article.cantidad * article.price) }}</td>
								</tr>
							</tbody>
						</table>
						<h5 class="card-title">Total: $@{{ total() }}</h5>
						<p class="card-text">
							@{{ articles.length }} artículos, @{{ cantidad_articulos() }} unidades
						</p>
					</div>
					<!-- @{{ ventaRealizada }} -->
					<div v-show="ventaRealizada">
						<div class="venta-ok-container">
							<i class="icon-check icon-venta-realizada text-success"></i>
							<p class="text-success font-weight-bold">
								Venta realizada con exito
							</p>
							<a :href="'pdf/client/'"
								class="btn btn-primary btn-block m-t-10" target="_blank">
								<i class="icon-print m-r-5"></i>
								Imprimir remito al cliente
							</a>
							<a :href="'pdf/negocio/'"
								class="btn btn-primary btn-block m-t-10" target="_blank">
								<i class="icon-print m-r-5"></i>
								Imprimir remito para el negocio
							</a>
							<a href="#" class="btn btn-primary btn-block">
								<i class="fas fa-sync-alt m-r-5"></i>
								Nueva venta
							</a>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col">
							<form class="form-inline">
							  <label class="my-1 mr-2" for="client">Cliente</label>
							  <select class="custom-select my-1 mr-sm-2" v-model="selected_client" id="client">
							  	<option value="0" selected>Elija un cliente</option>
							    <option v-for="client in clients" :value="client.id">@{{ client.name }}</option>
							  </select>

							  <button  class="btn btn-secondary my-1">Agregar un cliente</button>
							</form>
						</div>
						<div class="col">
							<button @click="vender" :class="selected_client == 0 ? 'disabled' : ''" class="btn btn-success btn-block btn-lg">
								Vender
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
new Vue({
	el: '#vender',
	data: {
		clients: [
			{'id': 1, 'name': 'Juan'},
			{'id': 2, 'name': 'Marcos'},
			{'id': 3, 'name': 'Seba'},
			{'id': 4, 'name': 'Tobias'},
		],
		articles: [
			{'id': 1, 'name': 'Camion', 'price': 600, 'cantidad': 12},
			{'id': 2, 'name': 'Lampara', 'price': 200, 'cantidad': 10},
			{'id': 3, 'name': 'Celular', 'price': 7000, 'cantidad': 7},
			{'id': 4, 'name': 'Compu', 'price': 9000, 'cantidad': 5},
			{'id': 5, 'name': 'Billetera', 'price': 300, 'cantidad': 12},
			{'id': 6, 'name': 'Cama', 'price': 6000, 'cantidad': 5},
			{'id': 7, 'name': 'Mesa', 'price': 3000, 'cantidad': 2},
		],
		ventaRealizada: false,
		selected_client: 0
	},
	methods: {
		total() {
			var total = 0
			this.articles.forEach( article => {
				total += article.price * article.cantidad
			})
			return this.price(total)
		},
		cantidad_articulos() {
			var cantidad_articulos = 0
			this.articles.forEach( article => {
				cantidad_articulos += article.cantidad
			})
			return cantidad_articulos
		},
		price(price) {
			let formated_price = numeral(price).format('0,0')
			array = formated_price.split(',')
			formated_price = array.join('.')
			return formated_price
		},
		vender() {
			this.articles = []
			this.ventaRealizada = true
		}
	},
})
</script>
@endsection


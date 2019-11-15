<template>
<div id="vender">
	<successful-sale :sale="sale"></successful-sale>
	<clients @getClients="getClients" 
				@setClient="setClient"
				:clients="clients"></clients>
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
									<input type="text" 
											v-model="bar_code"
											id="bar-code"
											class="form-control" 
											placeholder="Ingrese el codigo de barras"
											@keyup.enter="changeToCantidad">
								</div>
							</div>
							<div class="col-4">	
								<div class="input-group mb-2 mr-sm-2">
									<div class="input-group-prepend">
									  	<div class="input-group-text">Cantidad</div>
									</div>
									<input type="number" 
											v-model="amount"
											@keyup.enter="addArticle"
											id="cantidad"
											class="form-control" 
											placeholder="Cantidad">
								</div>
							</div>
						</div>
					</form>	
				</div>
				<div class="card-body">
					<div class="vender-table">
						<h5 class="card-title" v-if="articles.length">Total: ${{ total() }}</h5>
						<p class="card-text" v-if="articles.length">
							{{ articles.length }} artículos, {{ cantidad_articulos() }} unidades
						</p>
						<table class="table text-center table-striped">
							<thead>
								<tr>
									<th scope="col">Nombre</th>
									<th scope="col">Precio Unitario</th>
									<th scope="col">Cantidad</th>
									<th scope="col">Subtotal</th>
								</tr>
							</thead>
							<tbody v-if="articles.length">
								<tr v-for="article in articles">
									<td>{{ article.name }}</td>
									<th scope="row">${{ price(article.price) }}</th>
									<td>{{ article.amount }}</td>
									<td>${{ price(parseFloat(article.price) * article.amount) }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col">
							<form class="form-inline">
								<label class="my-1 mr-2" for="client">Cliente</label>
								<select class="custom-select my-1 mr-sm-2" 
											v-model="selected_client" 
											id="client">
									<option value="0">Elija un cliente</option>
									<option v-for="client in clients" 
											:value="client.id">
											{{ client.name }}
									</option>
								</select>
								<button class="btn btn-secondary btn-sm"
										@click.prevent="showClients">
									Agregar un cliente
								</button>
							</form>
						</div>
						<div class="col">
							<button @click="vender" 
									v-show="!ventaRealizada"
									:class="selected_client == 0 ? 'disabled' : ''" class="btn btn-success btn-block btn-lg">
								Vender
							</button>
							<button @click="nuevaVenta" 
									v-show="ventaRealizada"
									:class="selected_client == 0 ? 'disabled' : ''" class="btn btn-success btn-block btn-lg">
								Nueva venta
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</template>
<script>
import Clients from './modals/Clients.vue'
import SuccessfulSale from './modals/SuccessfulSale.vue'
export default {
	components: {
		Clients,
		SuccessfulSale,
	},
	data() {
		return {
			bar_code: '',
			amount: '',
			clients: [],
			articles: [],
			ventaRealizada: false,
			selected_client: 0,
			bar_codes: [],
			sale: {},
		}
	},
	methods: {
		total() {
			var total = 0
			this.articles.forEach( article => {
				total += article.price * article.amount
			})
			return this.price(total)
		},
		cantidad_articulos() {
			var cantidad_articulos = 0
			this.articles.forEach( article => {
				cantidad_articulos += article.amount
			})
			return cantidad_articulos
		},
		isFloat(n){
			return Number(n) === n && n % 1 !== 0;
		},
		price(p, punto=true) {
			if (typeof(p) === 'number') {
				console.log(p)
				p = p.toString()+'.00'
			}
			var centavos = p.split('.')[1]
			var price = p.split('.')[0]
			var formated_price
			if (punto) {
				formated_price = numeral(price).format('0,0').split(',').join('.')
				if (centavos != '00') {
					formated_price = formated_price + ',' + centavos
				}
			} else {
				formated_price = price
				if (centavos != '00') {
					formated_price = formated_price + '.' + centavos
				}
			}
			return formated_price
		},
		changeToCantidad() {
			if (this.bar_codes.includes(this.bar_code)) {
				$('#cantidad').focus()
			} else {
				toastr.error('Este codigo no pertenece a ningun artículo registrado')
				this.bar_code = ''
			}
		},
		resetInputs() {
			this.bar_code = ''
			this.amount = ''
			$('#bar-code').focus()
		},
		addArticle() {
			var repetido = false
			this.articles.forEach(article => {
				if (article.bar_code == this.bar_code) {
					article.amount += parseInt(this.amount)
					this.resetInputs()
					repetido = true
				}
			})
			if (!repetido) {
				axios.get('articles/get-by-bar-code/'+this.bar_code)
				.then(res => {
					var article = res.data
					article.amount = parseInt(this.amount)
					this.articles.push(article)
					this.resetInputs()
				})
				.catch(err => {
					console.log(err)
				})
			}
		},
		vender() {
			if(this.selected_client!=0) {
				axios.post('sales', {
					client_id: this.selected_client,
					articles: this.articles,
				})
				.then(res => {
					this.sale = res.data
					console.log(res.data)
					this.articles = []	
					this.ventaRealizada = true
					$('#successful-sale').modal('show')
				})
				.catch(err => {
					console.log(err)
				})
			} else {
				toastr.error('Debe seleccionar uno de sus clientes')
				$('#client').focus()
			}
		},
		nuevaVenta() {
			this.ventaRealizada = false
		},

		// Clientes
		getClients() {
			axios.get('clients')
			.then(res => {
				this.clients = res.data
				$('#bar-code').focus()
			})
			.catch(err => {
				console.log(err)
			})
		},
		setClient(client) {
			this.selected_client = client.id
		},
		showClients() {
			$('#clients').modal('show')
		},

		getBarCodes() {
			axios.get('articles/bar-codes')
			.then(res => {
				this.bar_codes = res.data
			})
			.catch(err => {
				console.log(err)
			})
		},
	},
	created() {
		this.getClients()
		this.getBarCodes()
	}
}
</script>
<style>
.vender-table {
	min-height: 300px;
	max-height: 300px;
	overflow-y: scroll;
}
</style>
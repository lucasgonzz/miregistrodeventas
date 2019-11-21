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
					<h5 class="card-title">Total: ${{ getTotal() }}</h5>
					<p class="card-text">
						{{ articles.length }} artículos, {{ cantidadArticulos() }} unidades
					</p>
					<table class="table text-center table-striped">
						<thead>
							<tr>
								<th scope="col">Nombre</th>
								<th scope="col">Precio Unitario</th>
								<th scope="col">Cantidad</th>
								<th scope="col">Subtotal</th>
								<th scope="col">Eliminar</th>
							</tr>
						</thead>
						<tbody class="vender-table">
							<tr v-for="article in articles">
								<td>{{ article.name }}</td>
								<th scope="row">${{ price(article.price) }}</th>
								<td>
									<input type="text"
											class="form-control input-amount"
											v-model="article.amount">
									<button @click="up(article)"
											class="btn btn-primary btn-sm">
										<i class="icon-up"></i>
									</button>
									<button @click="down(article)"
											class="btn btn-primary btn-sm">
										<i class="icon-down"></i>
									</button>
								</td>
								<td>
									${{ price(parseFloat(article.price) * article.amount) }}
								</td>
								<td>
									<button @click="removeArticle(article)"
											class="btn btn-danger">
										<i class="icon-cancel"></i>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
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
			total: 0,
			cantidad_articulos: 0,
			cantidad_unidades: 0,
		}
	},
	methods: {
		addTotal(article, repeated = false) {
			this.total += Number(article.price) * article.amount
			this.cantidad_unidades += article.amount
			if (!repeated) {
				this.cantidad_articulos++
			}
		},
		getTotal() {
			var total = 0
			this.articles.forEach( article => {
				total += article.price * article.amount
			})
			// return price(total)
			return total
		},
		cantidadArticulos() {
			var cantidad_articulos = 0
			this.articles.forEach( article => {
				cantidad_articulos += article.amount
			})
			return cantidad_articulos
		},
		// total() {
		// 	var total = 0
		// 	this.articles.forEach( article => {
		// 		total += article.price * article.amount
		// 	})
		// 	return this.price(total)
		// },
		// cantidad_articulos() {
		// 	var cantidad_articulos = 0
		// 	this.articles.forEach( article => {
		// 		cantidad_articulos += article.amount
		// 	})
		// 	return cantidad_articulos
		// },
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
					// this.addTotal(article, true)
					this.resetInputs()
					repetido = true
				}
			})
			if (!repetido) {
				axios.get('articles/get-by-bar-code/'+this.bar_code)
				.then(res => {
					var article = res.data
					article.amount = parseInt(this.amount)
					// this.addTotal(article)
					this.articles.push(article)
					this.resetInputs()
				})
				.catch(err => {
					console.log(err)
				})
			}
		},
		removeArticle(article) {
			// this.total -= article.price * article.amount
			// this.cantidad_articulos--
			// this.cantidad_unidades -= article.amount
			var i = this.articles.indexOf(article)
			this.articles.splice(i, 1)
		},
		up(article) {
			article.amount++
			// this.addTotal(article, true)
		},
		down(article) {
			if (article.amount > 1) {
				article.amount--
				// this.total -= Number(article.price)
				// this.cantidad_unidades--

			} else {
				toastr.error('No se pueden restar mas unidades')
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
<style scoped>
.vender-table {
	display: block;
	width: 100%;
	min-height: 250px;
	max-height: 250px;
	overflow-y: scroll;
}

thead, tbody tr {
    display:table;
    width:100%;
    table-layout:fixed;/* even columns width , fix width of table too*/
}

.input-amount{
	display: inline-block;
	width: 50px;
	margin: 0px;
}
</style>
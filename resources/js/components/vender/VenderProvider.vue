<template>
<div id="vender">
	<successful-sale :sale="sale"></successful-sale>
	<clients @getClients="getClients" 
				@setClient="setClient"
				:clients="clients"></clients>
	<article-not-register @updateArticles="updateArticlesList"></article-not-register>
	<div class="row justify-content-center">	
		<div class="col-lg-9">	
			<div class="card">
				<div class="card-header">
					<div class="form-row">	
						<div class="col-5">	
							<div class="input-group mb-2 mr-sm-2">
								<div class="input-group-prepend">
								  	<div class="input-group-text">Codigo de barras</div>
								</div>
								<input type="text" 
										v-model="article.bar_code"
										id="bar-code"
										class="form-control" 
										placeholder="Escaneé el codigo de barras"
										@keyup.enter="changeToCantidadFromBarCode">
							</div>
						</div>	
						<div class="col-5">	
							<div class="input-group mb-2 mr-sm-2">
								<div class="input-group-prepend">
								  	<div class="input-group-text">Nombre</div>
								</div>
								<input type="text" 
										v-model="article.name"
										id="name"
										class="form-control" 
										placeholder="Ingrese el nombre del artículo"
										autocomplete="off" 
										@keyup="setPossibleArticles"
										@keyup.enter="changeToCantidadFromName">
							</div>
							<ul class="list-group list" v-show="possible_articles.length">
								<li class="list-group-item active p-t-5 p-b-5">
									Selecciona el artículo que queres vender 
								</li>
								<li v-for="name in possible_articles"
									@click="selectPossibleArticle(name)"
									class="list-group-item c-p p-t-5 p-b-5">
									{{ name }}
								</li>
							</ul>
						</div>
						<div class="col-2">	
							<div class="input-group mb-2 mr-sm-2">
								<div class="input-group-prepend">
								  	<div class="input-group-text">Cantidad</div>
								</div>
								<input type="number" 
										v-model="article.amount"
										@keyup.enter="addArticle"
										id="cantidad"
										min="1"
										class="form-control" >
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row m-b-10" v-show="markers.length">
						<div class="col">
							<div class="card">
								<div class="card-header">
									<button class="btn btn-success" @click="showMarkers">
										<i class="icon-star-1" v-show="!show_markers"></i>
										<i class="icon-cancel" v-show="show_markers"></i>
										Marcadores
									</button>
								</div>
								<div class="card-body" v-show="show_markers">
									<button v-for="marker in markers"
											@click="addMarker(marker)"
											class="btn btn-primary m-5">
										{{ marker.name }}
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<h5 class="card-title">Total: {{ getTotal() }}</h5>
							<p class="card-text">
								{{ articles.length }} artículos, {{ cantidadArticulos() }} unidades
							</p>
						</div>
					</div>
					<div class="row">
						<div class="col">
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
										<th scope="row">{{ price(article.price) }}</th>
										<td>
											<input type="text"
													class="form-control input-amount"
													v-model="article.amount">
											<button @click="up(article)"
													class="btn btn-primary btn-sm">
												<i class="icon-plus"></i>
											</button>
											<button @click="down(article)"
													class="btn btn-primary btn-sm">
												<i class="icon-minus"></i>
											</button>
										</td>
										<td>
											{{ price(parseFloat(article.price) * article.amount) }}
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
									Agregar, eliminar clientes
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
import ArticleNotRegister from './modals/ArticleNotRegister.vue'
export default {
	components: {
		Clients,
		ArticleNotRegister,
		SuccessfulSale,
	},
	data() {
		return {
			article: {
				bar_code: '',
				name: '',
				amount: '',
			},
			possible_articles: [],
			names: [],
			markers: [],

			clients: [],
			articles: [],
			ventaRealizada: false,
			selected_client: 0,
			bar_codes: [],
			sale: {},
			total: 0,
			cantidad_articulos: 0,
			cantidad_unidades: 0,
			show_markers: true,
		}
	},
	methods: {
		price(p) {
			return numeral(p).format('$0,0.00')
		},
		showMarkers() {
			if (this.show_markers) {
				this.show_markers = false
			} else {
				this.show_markers = true
			}
		},
		/* 
		________________________________________________________________
		|																|
		|	* Cambia al campo de cantidad verificando que los datos		|
		|	* sean correctos											|	
		|_______________________________________________________________|

		*/
		changeToCantidadFromName() {
			if (this.names.includes(this.article.name.replace(/\b\w/g, l => l.toUpperCase()))) {
				$('#cantidad').focus()
			} else {
				$('#article-not-register').modal('show')
				this.article.name = ''
			}		
		},
		changeToCantidadFromBarCode() {
			if (this.bar_codes.includes(this.article.bar_code.toUpperCase())) {
				$('#cantidad').focus()
			} else if (this.bar_codes.includes(this.article.bar_code.toLowerCase())) {
				$('#cantidad').focus()
			} else {
				$('#article-not-register').modal('show')
				this.article.bar_code = ''
			}
		},

		// Metodos para la sugerencia de articulos
		setPossibleArticles() {
			this.possible_articles = []
			if (this.article.name.length >= 2) {
				this.names.forEach(name => {
					if(name.toLowerCase().includes(this.article.name)) {
						this.possible_articles.push(name)
					}
				})
			}
		},
		selectPossibleArticle(name) {
			this.article.name = name
			this.possible_articles = []
			this.changeToCantidadFromName()
		},


		// Articulos
		addMarker(article) {
			this.article.name = article.name
			$('#cantidad').focus()
		},
		addArticle() {
			var repetido = false

			// Revisa que no este repetido
			if (this.article.name != '' && this.article.bar_code == '') {
				this.articles.forEach(article => {
					if (article.name == this.article.name) {
						toastr.warning(article.name+' ya esta ingresado en esta venta, se le sumo una unidad')
						article.amount += Number(this.article.amount)
						this.addTotal(article, true)
						this.resetInputs()
						repetido = true
					}
				})
			} else if (this.article.bar_code != '' && this.article.name == '') {
				this.articles.forEach(article => {
					if (article.bar_code == this.article.bar_code) {
						toastr.warning(article.name+' ya esta ingresado en esta venta, se le sumo una unidad')
						article.amount += Number(this.article.amount)
						this.addTotal(article, true)
						this.resetInputs()
						repetido = true
					}
				})
			} else {
				toastr.error('Agrege su artículo mediante el codigo o mediante el nombre, no los dos a la vez')
			}


			if (!repetido) {
				if (this.article.name != '' && this.article.bar_code == '') {
					axios.get('articles/get-by-name/'+this.article.name)
					.then(res => {
						var article = res.data
						article.amount = Number(this.article.amount)
						console.log(article)
						this.articles.push(article)
						this.resetInputs()
					})
					.catch(err => {
						console.log(err)
					})
				} else if (this.article.bar_code != '' && this.article.name == '') {
					axios.get('articles/get-by-bar-code/'+this.article.bar_code)
					.then(res => {
						var article = res.data
						article.amount = Number(this.article.amount)
						console.log(article)
						this.articles.push(article)
						this.resetInputs()
					})
					.catch(err => {
						console.log(err)
					})
				} else {
					toastr.error('Agrege su artículo mediante el codigo o mediante el nombre, no los dos a la vez')
				}
			}
		},
		removeArticle(article) {
			var i = this.articles.indexOf(article)
			this.articles.splice(i, 1)
		},

		// Venta
		vender() {
			if(this.selected_client!=0) {
				axios.post('sales', {
					client_id: this.selected_client,
					articles: this.articles,
				})
				.then(res => {
					this.sale = res.data
					// console.log(res.data)
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


		// Total y cantidad de articulos
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
			return this.price(total)
		},
		cantidadArticulos() {
			var cantidad_articulos = 0
			this.articles.forEach( article => {
				cantidad_articulos += article.amount
			})
			return cantidad_articulos
		},

		// Varios
		resetInputs() {
			this.article.bar_code = ''
			this.article.name = ''
			this.article.amount = ''
			$('#bar-code').focus()
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

		/*
			* Manejo de los clientes
		*/
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

		/*

		* Obtiene lo datos para comenzar
			* Codigos de barra
			* Nombres disponibles

		*/
		getBarCodes() {
			axios.get('articles/bar-codes')
			.then(res => {
				this.bar_codes = res.data
			})
			.catch(err => {
				console.log(err)
			})
		},
		getNames() {
			axios.get('articles/names')
			.then(res => {
				console.log(res.data)
				this.names = res.data
			})
			.catch(err => {
				console.log(err)
			})
		},
		getMarkers() {
			axios.get('articles/get-markers')
			.then(res => {
				this.markers = res.data
			})
			.catch(err => {
				console.log(err)
			})
		},
		updateArticlesList() {
			this.getBarCodes()
			this.getNames()
			$('#article-not-register').modal('hide')
		},
	},
	created() {
		this.getClients()
		this.getBarCodes()
		this.getNames()
		this.getMarkers()
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

.list {
	position: absolute;
	top: 100%;
	width: 98%;
	z-index: 500;
}
</style>
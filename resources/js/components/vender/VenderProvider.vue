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
					<div class="row m-b-10" v-show="markers.length || marker_groups.length">
					<div class="col">
		                <div class="custom-control custom-checkbox  custom-control-inline-50 my-1 mr-sm-2 c-p">
		                  	<input class="custom-control-input c-p" 
		                  			v-model="show_markers" 
		                  			type="checkbox" 
		                  			id="show-markers">
		                  	<label class="custom-control-label c-p" 
		                  			for="show-markers">
		                  			<strong>Mostrar marcadores</strong>
		                  	</label>
		                </div>
						<div class="card" v-show="show_markers">
							<div class="card-header">
								<i class="icon-star-1"></i>
								Marcadores
							</div>
							<div class="card-body" v-show="show_markers">
				                <div class="custom-control custom-checkbox  custom-control-inline-50 my-1 mr-sm-2 c-p">
				                  	<input class="custom-control-input c-p" 
				                  			v-model="show_markers_prices" 
				                  			type="checkbox" 
				                  			id="show_markers_prices">
				                  	<label class="custom-control-label c-p" 
				                  			for="show_markers_prices">
				                  			<strong>Mostrar precios</strong>
				                  	</label>
				                </div>
				                <br>
								<div class="dropdown m-r-5" 
										v-for="marker_group in marker_groups">
									<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										{{ marker_group.name }}
									</button>
									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<template v-if="marker_group.markers.length">
											<a v-for="marker in marker_group.markers"
												@click.prevent="addMarker(marker)"
												class="dropdown-item" 
												href="#">
												{{ marker.article.name }}
												<span v-show="show_markers_prices">
													(${{ marker.article.price }})
												</span>
											</a>
										</template>
										<template v-else>
											<a href="#" class="dropdown-item">
												Sin marcadores
											</a>
										</template>
									</div>
								</div>
								<button v-for="marker in markers"
										@click="addMarker(marker)"
										class="btn btn-primary m-l-5 m-r-5">
									{{ marker.article.name }}
									<span v-show="show_markers_prices">
										(${{ marker.article.price }})
									</span>
								</button>
							</div>
						</div>
					</div>
				</div>
					<div class="row">
						<div class="col">
							<h5 class="card-title">Total: {{ price(total) }}</h5>
							<p class="card-text">
								{{ cantidad_articulos }} artículos, {{ cantidad_unidades }} unidades
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

										<!-- td del precio -->
										<td v-if="article.uncontable == 1"
											class="td-price">
											<template v-if="article.offer_price"
													class="text-danger">
												<i class="icon-sale-ticket ticket-price"></i>
												{{ price(article.offer_price) }} el {{ article.measurement }}
											</template>
											<template v-else>
													{{ price(article.price) }} el {{ article.measurement_original }}
											</template>
										</td>
										<td v-else
											class="td-price">
											<template v-if="article.offer_price"
													class="text-danger">
												<i class="icon-sale-ticket ticket-price"></i>
												{{ price(article.offer_price) }}
											</template>
											<template v-else>
												{{ price(article.price) }}
											</template>
										</td>
										<!-- Termina td de precio -->

										<!-- td cantidad -->
										<td v-if="article.uncontable == 0">
										<input type="number" 
												min="1"
												class="form-control input-amount"
												v-model="article.amount">
										</td>
										<td v-else>
											<input type="number" 
													:id="'amount-measurement-'+article.id"
													min="1"
													class="form-control input-amount-measurement"
													@keyup.enter="calculateTotal"
													v-model="article.amount">
											<select id="select-measurement" 
													v-model="article.measurement"
													class="form-control select-measurement">
												<option value="gramo">Gramo(s)</option>		
												<option value="kilo">Kilo(s)</option>	
											</select>
											<button @click="calculateTotal"
													class="btn btn-primary btn-sm">
												<i class="icon-check"></i>
											</button>
										</td>
										<!-- Termina td de cantidad -->

										<td>
											{{ getSubTotal(article) }}
										</td>
										<td>
											<button @click="up(article)"
													class="btn btn-primary btn-sm">
												<i class="icon-plus"></i>
											</button>
											<button @click="down(article)"
													class="btn btn-primary btn-sm">
												<i class="icon-minus"></i>
											</button>
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
									id="vender"
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

			clients: [],
			articles: [],
			ventaRealizada: false,
			selected_client: 0,
			
			// Articulos disponibles
			names: [],
			bar_codes: [],
			available_articles: [],

			sale: {},
			total: 0,
			cantidad_articulos: 0,
			cantidad_unidades: 0,

			// Marcadores
			markers: [],
			marker_groups: [],
			show_markers: true,
			show_markers_prices: true,
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
		getSubTotal(article) {
			var price = 0
			if (article.offer_price) {
				price = parseFloat(article.offer_price)
			} else {
				price = parseFloat(article.price)
			}
			var amount = parseFloat(article.amount)

			if (article.uncontable == 0) {
				return this.price(price * amount)
			} else {
				if (article.measurement == article.measurement_original) {
					return this.price(price * amount)
				} else {
					return this.price(price * amount / 1000)
				}
			}
		},

		/*
		|----------------------------------------------------------------
		| Cambiar a cantidad
		|----------------------------------------------------------------
		|
		|	* Cambia al campo a cantidad verificando que los datos	
		|
		*/
		changeToCantidadFromName() {
			var disponible = false
			this.available_articles.forEach(article => {
				if (article.name == this.article.name) {
					if (article.uncontable == 0) {
						$('#cantidad').focus()
						disponible = true
					} else {
						this.addArticle()
						disponible = true
					}
				}
			})
			if (!disponible) {
				$('#article-not-register').modal('show')
				this.article.name = ''
			}
			// if (this.names.includes(this.article.name.replace(/\b\w/g, l => l.toUpperCase()))) {
			// 	$('#cantidad').focus()
			// } else {
			// 	$('#article-not-register').modal('show')
			// 	this.article.name = ''
			// }		
		},
		changeToCantidadFromBarCode() {
			var disponible = false
			this.available_articles.forEach(article => {
				if (article.bar_code == this.article.bar_code) {
					if (article.uncontable == 0) {
						$('#cantidad').focus()
						disponible = true
					} else {
						this.addArticle()
						disponible = true
					}
				}
			})
			if (!disponible) {
				$('#article-not-register').modal('show')
				this.article.bar_code = ''
			}
			// if (this.bar_codes.includes(this.article.bar_code.toUpperCase())) {
			// 	$('#cantidad').focus()
			// } else if (this.bar_codes.includes(this.article.bar_code.toLowerCase())) {
			// 	$('#cantidad').focus()
			// } else {
			// 	$('#article-not-register').modal('show')
			// 	this.article.bar_code = ''
			// }
		},

		/*
		|----------------------------------------------------------------
		| Sugerencia de articulos
		|----------------------------------------------------------------
		|
		|	* setPossibleArticles agrega articulos a la lista de sugerencias
		|	si el nombre coincide con los de la lista
		|	* selectPossibleArticle selecciona el articulo de la lista de sugerencias
		|
		*/
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
		isRepeated() {
			var repetido = false
			this.articles.forEach(article => {
				if (article.bar_code == this.article.bar_code || article.name == this.article.name) {
					if (article.uncontable == 0) {
						article.amount += Number(this.article.amount)
						toastr.warning('El artículo ya esta en la venta, se aumento '+this.article.amount+' unidad(es)')
						this.resetInputs()
						this.calculateTotal()
						repetido = true
					}
				}
			})
			return repetido
		},
		addArticle() {
			var disponible = false
			var repetido = this.isRepeated()
			if (!repetido) {
				this.available_articles.forEach(article => {
					if (article.bar_code == this.article.bar_code || article.name == this.article.name) {
						disponible = true
						if (article.bar_code === null) {
							axios.get('articles/get-by-name/'+article.name)
							.then(res => {
								var article = res.data
								article.amount = 1
								this.possible_articles = []
								this.articles.push(article)
								if (article.uncontable == 1) {
									article.measurement_original = article.measurement
									setTimeout(() => {
										$(`#amount-measurement-${article.id}`).focus()
									}, 500)
								} else {
									this.calculateTotal()
								}
							})
							.catch(err => {
								console.log(err)
							})
						} else {
							axios.get('articles/get-by-bar-code/'+article.bar_code)
							.then(res => {
								var article = res.data
								article.amount = 1
								this.possible_articles = []
								this.articles.push(article)
								if (article.uncontable == 1) {
									article.measurement_original = article.measurement
									setTimeout(() => {
										$(`#amount-measurement-${article.id}`).focus()
									}, 500)
								} else {
									this.calculateTotal()
								}
							})
							.catch(err => {
								console.log(err)
							})
						}
					} 
				})
			}
			// if (!disponible) {
			// 	toastr.error('No registrado')
			// }
		},
		addMarker(marker) {
			this.article.name = marker.article.name
			if (marker.article.uncontable == 0) {
				$('#cantidad').focus()
			} else {
				this.addArticle()
			}
		},
		calculateTotal() {
			this.total = 0
			this.cantidad_articulos = 0
			this.cantidad_unidades = 0
			this.articles.forEach(article => {
				this.cantidad_articulos++
				var price = 0
				if (article.offer_price) {
					price = parseFloat(article.offer_price)
				} else {
					price = parseFloat(article.price)
				}

				if (article.uncontable == 0) {
					// article.stock -= article.amount
					this.total += price * article.amount
					this.cantidad_unidades += article.amount
				} else {
					this.cantidad_unidades++
					if (article.measurement == article.measurement_original) {
						// article.stock -= article.amount
						this.total += price * parseFloat(article.amount)
					} else {
						// article.stock -= article.amount / 1000
						this.total += price * parseFloat(article.amount) / 1000
					}
				}
			})
			this.article.amount = ''
			if (this.article.name != '') {
				this.article.name = ''
				$('#name').focus()
			} else {
				this.article.bar_code = ''
				$('#bar-code').focus()
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
					console.log(res.data)
					this.sale = res.data
					this.articles = []	
					this.total = 0
					this.cantidad_articulos = 0
					this.cantidad_unidades = 0
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

		// addTotal(article, repeated = false) {
		// 	this.total += Number(article.price) * article.amount
		// 	this.cantidad_unidades += article.amount
		// 	if (!repeated) {
		// 		this.cantidad_articulos++
		// 	}
		// },
		// getTotal() {
		// 	var total = 0
		// 	this.articles.forEach( article => {
		// 		total += article.price * article.amount
		// 	})
		// 	// return price(total)
		// 	return this.price(total)
		// },
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

		/*
		|----------------------------------------------------------------
		| Aumentar y decrementar unidades de los articulos
		|----------------------------------------------------------------
		|
		*/
		up(article) {
			article.amount++
			this.calculateTotal()
			// this.addTotal(article, true)
		},
		down(article) {
			if (article.amount > 1) {
				article.amount--
				this.calculateTotal()
			} else {
				toastr.error('No se pueden restar mas unidades')
			}
		},

		/*
		|----------------------------------------------------------------
		| Manejo de los clientes
		|----------------------------------------------------------------
		|
		|	* Obtine los clientes
		|	* Muestra los clientes
		|	* El setClient selecciona el cliente que recien se creo
		|
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
			$('#vender').focus()
		},
		showClients() {
			$('#clients').modal('show')
		},

	    /*
		|----------------------------------------------------------------
		| Obtiene lo datos para comenzar
		|----------------------------------------------------------------
		|	* Codigos de barra
		|	* Nombres disponibles
		|	* Articulos disponibles
		|	* Marcadores
		|	* Grupo de marcadores
		|
		*/
		getBarCodes() {
			axios.get('articles/bar-codes')
			.then(res => {
				this.bar_codes = res.data
			})
			.catch(err => {
				console.log(err)
				location.reload()
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
				location.reload()
			})
		},
		getAvailableArticles() {
			axios.get('articles/availables')
			.then(res => {
				this.available_articles = res.data
			})
			.catch(err => {
				console.log(err)
				location.reload()
			})
		},
		getMarkers() {
			axios.get('articles/markers')
			.then(res => {
				console.log('marcadores: ')
				console.log(res.data)
				this.markers = res.data
			})
			.catch(err => {
				console.log(err)
				location.reload()
			})
		},
		getMarkerGroups() {
			axios.get('articles/marker-groups')
			.then(res => {
				console.log('grupo de marcadores: ')
				console.log(res.data)
				this.marker_groups = res.data
			})
			.catch(err => {
				console.log(err)
				location.reload()
			})
		},
		updateArticlesList() {
			this.getBarCodes()
			this.getNames()
			this.getAvailableArticles()
			$('#article-not-register').modal('hide')
		},
	},
	created() {
		this.getClients()
		this.getBarCodes()
		this.getNames()
		this.getMarkers()
		this.getMarkerGroups()
		this.getAvailableArticles()
		$('#bar-code').focus()
	}
}
</script>
<style scoped>

.td-price {
	position: relative;
	font-weight: bold;
}

.ticket-price {
	position: absolute;
	font-size: 30px;
	color: #E23535;
	top: -5px;
	left: 0px;
}

.input-amount{
	display: inline-block;
	width: 70px;
	margin: 0px;
}

.list {
	position: absolute;
	top: 100%;
	width: 98%;
	z-index: 500;
}

.measurement {
	display: inline-block;
}
.select-measurement {
	display: inline-block;
	width: 100px;
}
.input-amount-measurement {
	width: 70px;
	display: inline-block;
}
.dropdown {
	display: inline-block;
}
</style>
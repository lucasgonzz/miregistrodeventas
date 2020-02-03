<template>
<div class="row justify-content-center">	
	<div class="col-lg-7">	
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
									autofocus 
									@keyup.enter="addArticle"
									class="form-control" 
									placeholder="Ingrese el codigo de barras">
						</div>
					</div>
					<div class="col-5">
						<div class="input-group mb-2 mr-sm-2">
							<div class="input-group-prepend">
							  	<div class="input-group-text">Nombre</div>
							</div>
							<input type="text" 
									v-model="article.name"
									class="form-control"
									id="name"
									@keyup="setPossibleArticles"
									@keyup.enter="addArticle"
									placeholder="Ingrese el nombre del artículo"
									autocomplete="off">
						</div>
						<ul class="list-group list" v-show="possible_articles.length">
							<li class="list-group-item active p-t-5 p-b-5">
								Que artículo queres vender
							</li>
							<li class="list-group-item c-p p-t-5 p-b-5" 
								v-for="article_name in possible_articles"
								@click="selectPossibleArticle(article_name)">
								{{ article_name }}
							</li>
						</ul>	
					</div>
					<div class="col-2">	
						<button type="submit"
								@click="vender"
								:class="articles.length ? '' : 'disabled'" 
								class="btn btn-primary btn-block mb-2">Vender</button>
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
										v-for="marker_group in marker_groups"
										v-show="marker_group.markers.length">
									<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										{{ marker_group.name }}
									</button>
									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a v-for="marker in marker_group.markers"
											@click.prevent="addMarker(marker)"
											class="dropdown-item" 
											href="#">
											{{ marker.article.name }}
											<span v-show="show_markers_prices">
												(${{ marker.article.price }})
											</span>
										</a>
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
									<th scope="col">Precio</th>
									<th scope="col">Nombre</th>
									<th scope="col">Quedarian</th>
									<th scope="col">Cantidad</th>
									<th scope="col">Opciones</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="article in articles">
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
									<td>{{ article.name }}</td>
									<td v-if="article.stock">
										<template v-if="article.stock">
											Sin datos											
										</template>
										<template v-else>
											<span v-if="article.uncontable == 1">
												{{ article.stock }} {{ article.measurement_original }}s
											</span>
											<span v-else>
												{{ article.stock }} 
											</span>
										</template>
									</td>
									<td v-else>
										sin datos
									</td>
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
									<td>
										<button @click="up(article)"
												v-show="article.uncontable == 0"
												class="btn btn-primary btn-sm">
											<i class="icon-plus"></i>
										</button>
										<button @click="down(article)"
												v-show="article.uncontable == 0"
												class="btn btn-primary btn-sm">
											<i class="icon-minus"></i>
										</button>
										<button @click="removeArticle(article)"
												class="btn btn-danger btn-sm">
											<i class="icon-cancel"></i>
										</button>

									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</template>
<script>
export default {
	data() {
		return {
			article: {
				bar_code: '',
				name: '',
				enteredByName: false,
			},
			articles: [],

			total: 0,
			cantidad_articulos: 0,
			cantidad_unidades: 0,
			possible_articles: [],
			available_articles: [],
			names: [],
			bar_codes: [],

			// Marcadores
			markers: [],
			marker_groups: [],
			show_markers: true,
			show_markers_prices: true,
		}
	},
	created() {
		this.getNames()
		this.getBarCodes()
		this.getMarkers()
		this.getMarkerGroups()
		this.getAvailableArticles()
		$('#bar-code').focus()
	},
	methods: {
		price(p) {
			return numeral(p).format('$0,0.00')
		},
		isRepeated() {
			var repetido = false
			this.articles.forEach(article => {
				if (article.bar_code == this.article.bar_code || article.name == this.article.name) {
					if (article.uncontable == 0) {
						article.amount++
						toastr.warning('El artículo ya esta en la venta, se aumento una unidad')
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
			console.log('Repetido: '+repetido)
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
		},
		addMarker(marker) {
			this.article.name = marker.article.name
			this.addArticle()
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
					article.stock -= article.amount
					this.total += price * article.amount
					this.cantidad_unidades += article.amount
				} else {
					this.cantidad_unidades++
					if (article.measurement == article.measurement_original) {
						article.stock -= article.amount
						this.total += price * parseFloat(article.amount)
					} else {
						article.stock -= article.amount / 1000
						this.total += price * parseFloat(article.amount) / 1000
					}
				}
			})

			this.resetInputs()
		},
		resetInputs() {
			if (this.article.name != '') {
				this.article.name = ''
				$('#name').focus()
			} else {
				this.article.bar_code = ''
				$('#bar-code').focus()
			}
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
		getNames() {
			axios.get('articles/names')
			.then(res => {
				// console.log(res.data)
				this.names = res.data
			})
			.catch(err => {
				console.log(err)
			})
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
		getAvailableArticles() {
			axios.get('articles/availables')
			.then(res => {
				this.available_articles = res.data
			})
			.catch(err => {
				console.log(err)
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
			})
		},
		setPossibleArticles() {
			// console.log('entro')
			if (this.article.bar_code.length == 0 && this.article.name.length >= 1) {
				this.possible_articles = []
				this.names.forEach(name => {
					if (name.toLowerCase().includes(this.article.name)) {
						this.possible_articles.push(name)
					}
				})				
			} else {
				this.possible_articles = []
			}
		},
		selectPossibleArticle(article_name) {
			this.article.name = article_name
			this.addArticle()
		},
		isRegister() {
			if (this.bar_codes.includes(this.article.bar_code.toUpperCase())) {
				return true
			} else if (this.bar_codes.includes(this.article.bar_code.toLowerCase())) {
				return true
			} else {
				toastr.error('No hay ningun artículo registrado con ese codigo')
				this.article.bar_code = ''
				return false
			}
		},

		removeArticle(article) {
			this.total -= Number(article.price) * article.amount
			this.cantidad_articulos--
			this.cantidad_unidades -= article.amount
			var i = this.articles.indexOf(article)
			this.articles.splice(i, 1)
		},
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

		vender() {
			if (this.articles.length > 0) {
				axios.post('sales', {
					articles: this.articles,
				})
				.then(res => {
					// console.log('aca esta: ')
					// console.log(res.data)
					this.articles = []	
					this.total = 0
					this.cantidad_articulos = 0
					this.cantidad_unidades = 0
					toastr.success('Venta realizada correctamente')
					$('#bar-code').focus()
				})
				.catch(err => {
					console.log(err)
				})
			} else {
				toastr.error('Debe ingresar al menos un artículo para realizar una venta')
				this.bar_code = ''
				$('#bar_code').focus()
			}
		},
	}	
}
</script>
<style scoped>
.input-amount{
	display: inline-block;
	width: 70px;
	margin: 0px;
}

.list {
	width: 98%;
	z-index: 500;
}

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
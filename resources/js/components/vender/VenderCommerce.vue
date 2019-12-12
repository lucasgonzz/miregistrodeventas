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
										<span v-if="article.uncontable == 1">
											{{ article.stock }} {{ article.measurement_original }}s
										</span>
										<span v-else>
											{{ article.stock }} 
										</span>
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
												@keyup.enter="addTotal(article)"
												v-model="article.amount">
										<select id="select-measurement" 
												v-model="article.measurement"
												class="form-control select-measurement">
											<option value="kilo">Kilo(s)</option>	
											<option value="gramo">Gramo(s)</option>		
										</select>
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
			markers: [],
			show_markers: true,
		}
	},
	created() {
		this.getNames()
		this.getBarCodes()
		this.getMarkers()
		this.getAvailableArticles()
		$('#bar-code').focus()
	},
	methods: {
		price(p) {
			return numeral(p).format('$0,0.00')
		},
		addArticle() {

			var disponible = false
			this.available_articles.forEach(article => {
				if (article.bar_code == this.article.bar_code || article.name == this.article.name) {
					disponible = true
					axios.get('articles/get-by-name/'+article.name)
					.then(res => {
						var article = res.data
						article.amount = 1
						this.possible_articles = []
						this.articles.push(article)
						if (article.uncontable == 1) {
							// console.log(article)
							article.measurement_original = article.measurement
							setTimeout(() => {
								$(`#amount-measurement-${article.id}`).focus()
							}, 500)
						} else {
							this.addTotal(article)
						}
					})
					.catch(err => {
						console.log(err)
					})
				} 
			})
			if (!disponible) {
				toastr.error('No registrado')
			}
		},
		addTotal(article, repeated = false) {	
			// console.log(article)
			if (article.uncontable == 0) {
				if (article.offer_price) {
					this.total += parseFloat(article.offer_price)
				} else {
					this.total += parseFloat(article.price)
				}
			} else {
				var total_a_pagar_del_incotable = 0

				// Revisa se se estan vendiendo kilos de un articulo que tiene el
				// precio en kilos o lo mismo pero con gramos
				if (article.measurement_original == article.measurement) {
					console.log('unidades iguales')
					if (article.offer_price) {
						total_a_pagar_del_incotable = parseFloat(article.amount) * parseFloat(article.offer_price)
					} else {
						total_a_pagar_del_incotable = parseFloat(article.amount) * parseFloat(article.price)
					}
					if (article.stock) {
						article.stock -= article.amount
					}
				} else {
					// Si son diferentes revisa si el peso original era en kilogramos
					// Si es asi es porque se eligio venderlo en gramos
					console.log('unidades diferentes')
					if (article.measurement_original == 'kilo') {
						total_a_pagar_del_incotable = parseFloat(article.amount) * parseFloat(article.price) / 1000
						if (article.stock) {
							article.stock -= article.amount / 1000
						}
						if (article.offer_price) {
							total_a_pagar_del_incotable = parseFloat(article.amount) * parseFloat(article.offer_price) / 1000
						} else {
							total_a_pagar_del_incotable = parseFloat(article.amount) * parseFloat(article.price) / 1000
						}
					} else {
						// Si entra aca es porque el peso del articulo esta en gramos
						// y se kieren vender kilos
						// Esto es muy raro que pase asi que no lo voy a programar
					}
				}
				console.log('total: '+total_a_pagar_del_incotable)
				this.total += total_a_pagar_del_incotable
			} 
			// this.total = this.price(this.total)

			this.cantidad_unidades++
			if (article.stock && article.uncontable == 0) {
				article.stock--
			}
			if (!repeated) {
				this.cantidad_articulos++
			}
			if (this.article.bar_code != '') {
				this.article.bar_code = ''
				$('#bar_code').focus()
			} else {
				this.article.name = ''
				$('#name').focus()
			}
		},
		showMarkers() {
			if (this.show_markers) {
				this.show_markers = false
			} else {
				this.show_markers = true
			}
		},
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
			axios.get('articles/get-markers')
			.then(res => {
				this.markers = res.data
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

		/*
			* Agregar artículos a la lista de articulos por ser vendidos
		*/
		addMarker(marker) {
			var repetido = false
			this.articles.forEach(article => {
				if (article.name == marker.name) {
					repetido = true
					toastr.warning(article.name+' ya esta ingresado en esta venta, se le sumo una unidad')
					article.amount++
					this.addTotal(article, true)
					this.article.bar_code = ''
					$('#bar-code').focus()
				}
			})

			if (!repetido) {
				marker.amount = 1
				this.articles.push(marker)
				this.addTotal(marker)
				$('#bar-code').focus()
			}
		},
		getByBarCode(bar_code) {
			axios.get('articles/get-by-bar-code/'+bar_code)
			.then(res => {
				var article = res.data
				article.amount = 1
				this.articles.push(article)
				this.addTotal(article)
				this.article.bar_code = ''
				$('#bar-code').focus()
			})
			.catch(err => {
				console.log(err)
			})
		},
		isRepeated() {
			if (this.possible_articles.length) {
				this.articles.forEach(article => {
					if (article.name == this.article.name) {
						// repetido = true
						toastr.warning(article.name+' ya esta ingresado en esta venta, se le sumo una unidad')
						article.amount++
						this.addTotal(article, true)
						this.possible_articles = []
						this.article.name = ''
						$('#name').focus()
						return true
					}
				})
			} else {
				// Si se ingresa por codigo revisa que no este repetido
				this.articles.forEach(article => {
					if (article.bar_code == this.article.bar_code) {
						// repetido = true
						toastr.warning(article.name+' ya esta ingresado en esta venta, se le sumo una unidad')
						article.amount++
						this.addTotal(article, true)
						this.article.bar_code = ''
						$('#bar-code').focus()
						return true
					}
				})
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
			this.addTotal(article, true)
		},
		down(article) {
			if (article.amount > 1) {
				article.amount--
				if (article.stock != 'No tiene datos') {
					article.stock++
				}
				this.total -= Number(article.price)
				this.cantidad_unidades--

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
					console.log(res.data)
					this.articles = []	
					this.total = 0
					this.cantidad_articulos = 0
					this.cantidad_unidades = 0
					toastr.success('Venta realizada correctamente')
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
	width: 50px;
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
</style>
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
				<h5 class="card-title">Total: ${{ total }}</h5>
				<p class="card-text">
					{{ cantidad_articulos }} artículos, {{ cantidad_unidades }} unidades
				</p>
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
							<td v-if="article.offer_price"
								class="td-price text-danger">
								<i class="icon-sale-ticket ticket-price"></i>
								${{ article.offer_price }}
							</td>
							<td class="td-price" v-else>
								${{ article.price }}
							</td>
							<td>{{ article.name }}</td>
							<td>{{ article.stock }}</td>
							<td>
								<input type="text" 
										min="1"
										class="form-control input-amount"
										v-model="article.amount">
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
			// possible_articles: [
			// 	{name: 'Camion'},
			// 	{name: 'Casita'},
			// 	{name: 'Anteojos'},
			// 	{name: 'Reja'},
			// ],
			names: [],
		}
	},
	created() {
		$('#bar-code').focus()
		this.getNames()
	},
	methods: {
		addTotal(article, repeated = false) {	
			if (article.offer_price) {
				this.total += parseFloat(article.offer_price)
			} else {
				this.total += parseFloat(article.price)
			}
			this.cantidad_unidades++
			if (article.stock != 'No tiene datos' && article.stock) {
				article.stock--
			} else {
				article.stock = 'No tiene datos'
			}
			if (!repeated) {
				this.cantidad_articulos++
			}
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
		setPossibleArticles() {
			// console.log('entro')
			if (this.article.bar_code.length == 0 && this.article.name.length >= 2) {
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

		/*
			* Agregar artículos a la lista de articulos por ser vendidos
		*/
		addArticle() {
			var repetido = false
			// Si se ingresa por nombre revisa que no este repetido
			if (this.possible_articles.length) {
				this.articles.forEach(article => {
					if (article.name == this.article.name) {
						repetido = true
						toastr.warning(article.name+' ya esta ingresado en esta venta, se le sumo una unidad')
						article.amount++
						this.addTotal(article, true)
						this.possible_articles = []
						this.article.name = ''
						$('#name').focus()
					}
				})
			} else {
				// Si se ingresa por codigo revisa que no este repetido
				this.articles.forEach(article => {
					if (article.bar_code == this.article.bar_code) {
						repetido = true
						toastr.warning(article.name+' ya esta ingresado en esta venta, se le sumo una unidad')
						article.amount++
						this.addTotal(article, true)
						this.article.bar_code = ''
						$('#bar-code').focus()
					}
				})
			}

			// Si no esta repetido se piden los datos
			if (!repetido) {
				if (this.possible_articles.length) {
					axios.get('articles/get-by-name/'+this.article.name)
					.then(res => {
						// console.log(res.data)
						var article = res.data
						article.amount = 1
						this.articles.push(article)
						this.addTotal(article)
						this.possible_articles = []
						this.article.name = ''
						$('#name').focus()
					})
					.catch(err => {
						console.log(err)
					})
				} else {
					axios.get('articles/get-by-bar-code/'+this.article.bar_code)
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
				}
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
<style>
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
</style>
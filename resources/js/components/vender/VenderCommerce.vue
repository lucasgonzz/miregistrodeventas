<template>
<div class="row justify-content-center">	
	<div class="col-lg-7">	
		<div class="card">
			<div class="card-header">
				<div class="form-row">	
					<div class="col-10">	
						<div class="input-group mb-2 mr-sm-2">
							<div class="input-group-prepend">
							  <div class="input-group-text">Codigo de barras</div>
							</div>
							<input type="text" 
									v-model="bar_code"
									id="bar_code"
									@keyup.enter="addArticle"
									class="form-control" 
									placeholder="Ingrese el codigo de barras">
						</div>
					</div>
					<div class="col-2">	
						<button type="submit"
								@click="vender"
								:class="articles.length ? '' : 'disabled'" 
								class="btn btn-primary mb-2">Vender</button>
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
							<th scope="row">${{ article.price }}</th>
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
									<i class="icon-up"></i>
								</button>
								<button @click="down(article)"
										class="btn btn-primary btn-sm">
									<i class="icon-down"></i>
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
			bar_code: '',
			articles: [],

			total: 0,
			cantidad_articulos: 0,
			cantidad_unidades: 0,
		}
	},
	created() {
		$('#bar_code').focus()
	},
	methods: {
		addTotal(article, repeated = false) {		
			console.log(article.price)
			this.total += Number(article.price)
			this.cantidad_unidades++
			article.stock--
			if (!repeated) {
				this.cantidad_articulos++
			}
		},
		addArticle() {
			var repetido = false
			this.articles.forEach(article => {
				if (article.bar_code == this.bar_code) {
					repetido = true
					article.amount++
					this.addTotal(article, true)
					this.bar_code = ''
				}
			})
			if (!repetido) {
				axios.get('articles/get-by-bar-code/'+this.bar_code)
				.then(res => {
					var article = res.data
					article.amount = 1
					this.articles.push(article)
					this.addTotal(article)
					this.bar_code = ''
				})
				.catch(err => {
					console.log(err)
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
				article.stock++
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
</style>
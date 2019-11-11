<template>
<div id="listado">	
	<editar-articulo :rol="rol" 
						:article="article" 
						:providers="providers" 
						@updateArticle="updateArticle"></editar-articulo>
	<confirmar-eliminacion :article="article" 
							@destroyArticle="destroyArticle"></confirmar-eliminacion>
	<descargar-pdf :rol="rol" 
					:ids-articles="idsArticles"
					:filtro="filtro"></descargar-pdf>
	<importar></importar>
	<filtrar :filtro="filtro" 
				:rol="rol" 
				:providers="providers"
				@uncheckProviders="uncheckProviders"
				@filter="filter"></filtrar>
	<div class="row justify-content-center">
		<div class="col-lg-12">	
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-10 col-lg-5 p-r-0">
							<div class="buscador">
								<input type="text" class="form-control input-search" 
										placeholder="Buscar por codigo o nombre"
										v-model="search_query" @keyup="preSearch" @keyup.enter="search">
								<div class="resultados">
									<ul>
										<li @click="selectPreSearch(article.name)" v-for="article in pre_search">
											{{ article.name }}
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-1 p-l-0">
						  	<button class="btn btn-primary btn-search" @click="search">
								<i class="icon-search"></i>
						  	</button>						
						</div>
						<div class="col-12 col-lg-6 m-t-10 m-lg-t-0">
							<button class="btn btn-secondary m-l-5 float-right" @click="showFiltrar">
								<i class="icon-filter"></i>
								Filtrar
							</button>
							<button class="btn btn-danger m-l-5 float-right" @click="showDescargarPdf">
								<i class="icon-download"></i>
								Pdf
							</button>
							<a href="articles/exel" class="btn btn-success m-l-5 float-right">
								<i class="icon-download"></i>
								Exel
							</a>
							<button class="btn btn-warning m-l-5 float-right" @click="showImportar">
								<i class="icon-upload"></i>
								Importar Exel
							</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<info-filtrados :filtro="filtro" :filtrado="filtrado" 
									:providers="providers"
									:articles-length="articles.length"></info-filtrados>
					<div class="table-responsive">						
						<table class="table">
							<thead class="thead-dark">
								<tr v-if="rol == 'commerce'">
									<th scope="col">Codigo</th>
									<th scope="col">Nombre</th>
									<th scope="col">Costo</th>
									<th scope="col">Precio</th>
									<th scope="col">Pre anterior</th>
									<th scope="col">Stock</th>
									<th scope="col">Proveedor/es</th>
									<th scope="col">Agregado</th>
									<th scope="col">Actualizado</th>
									<th scope="col">Opciones</th>
								</tr>
								<tr v-else>
									<th scope="col">Codigo</th>
									<th scope="col">Nombre</th>
									<th scope="col">Costo</th>
									<th scope="col">Precio</th>
									<th scope="col">Pre anterior</th>
									<th scope="col">Stock</th>
									<th scope="col">Agregado</th>
									<th scope="col">Actualizado</th>
									<th scope="col">Opciones</th>
								</tr>
							</thead>
							<tbody>
								<template v-if="rol == 'commerce'">
									<tr v-for="article in articles">
										<td>{{ article.bar_code }}</td>
										<td>{{ article.name }}</td>
										<td>${{ price(article.cost) }}</td>
										<td class="td-price">${{ price(article.price) }}</td>
										<td v-if="article.previus_price != '0.00'">${{ price(article.previus_price) }}</td>
										<td v-else>Sin datos</td>
										<td>{{ article.stock }}</td>
										<td>
											<div class="dropdown">
												<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													Proveedores
												</button>
												<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
													<span v-for="provider in article.providers"
														class="dropdown-item c-n">
														{{ provider.name }}						
													</span>
												</div>
											</div>
										</td>
										<td>{{ date(article.created_at) }}</td>
										<td>{{ date(article.updated_at) }}</td>
										<td class="td-options">
											<button @click="editArticle(article)" class="btn btn-listado btn-listado-edit">
												<i class="icon-edit"></i>
											</button>
											<button @click="deleteArticle(article)" class="btn btn-listado btn-listado-delete">
												<i class="icon-trash-2"></i>
											</button>
										</td>
									</tr>
								</template>
								<template v-else>								
									<tr v-for="article in articles">
										<td>{{ article.bar_code }}</td>
										<td>{{ article.name }}</td>
										<td>${{ price(article.cost) }}</td>
										<td class="td-price">${{ price(article.price) }}</td>
										<td v-if="article.previus_price != '0.00'">${{ price(article.previus_price) }}</td>
										<td v-else>Sin datos</td>
										<td>{{ article.stock }}</td>
										<td>{{ date(article.created_at) }}</td>
										<td>{{ date(article.updated_at) }}</td>
										<td class="td-options">
											<button @click="editArticle(article)" class="btn btn-listado btn-listado-edit">
												<i class="icon-edit"></i>
											</button>
											<button @click="deleteArticle(article)" class="btn btn-listado btn-listado-delete">
												<i class="icon-trash-2"></i>
											</button>
										</td>
									</tr>								
								</template>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card-footer p-0">
					<pagination @changePage="changePage" 
								:filtrado="filtrado"
								:pagination="pagination"
								:pages-number="pagesNumber"></pagination>
					<div class="row p-10">
						<div class="col">
							<button v-show="filtrado" 
									class="btn btn-primary float-right"
									@click="volverAListar">
								<i class="icon-undo"></i>
								Volver a listar todos mis artículos
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
import DescargarPdf from './modals/DescargarPdf.vue'
import Importar from './modals/Importar.vue'
import Filtrar from './modals/Filtrar.vue'
import EditarArticulo from './modals/EditarArticulo.vue'
import ConfirmarEliminacion from './modals/ConfirmarEliminacion.vue'
import InfoFiltrados from './modals/InfoFiltrados.vue'
import Pagination from './Pagination.vue'
export default {
	components: {
		DescargarPdf,
		Importar,
		Filtrar,
		EditarArticulo,
		InfoFiltrados,
		Pagination,
		ConfirmarEliminacion
	},
	props: ['rol'],
	data() {
		return {

			articles: [],
			article: {'id': 0, 'bar_code': '','name': '', 'cost': 0, 'price': 0, 'stock': 0, 'providers': [], 'created_at': '', 'updated_at': '', 'creado': '', 'actualizado': '', 'act_fecha': true},
			search_query: '',
			pre_search: [],
			providers: [],
			idsArticles: [],

			// Filtros
			filtro: {
				'mostrar': 'todos',
				'ordenar': 'nuevos-viejos',
				'precio_entre': {
					'min': '',
					'max': ''
				},
				'providers': []
			},
			filtrado: false,

			// Pagination
			current_page: 0,
			pagination: {
	            'total' : 0,
	            'current_page' : 0,
	            'per_page' : 0,
	            'last_page' : 0,
	            'from' : 0,
	            'to' : 0,
			},
			offset: 2,
			
			columnas_para_imprimir: ['bar_code', 'name', 'cost', 'price', 'created_at', 'updated_at'],
		}
	},
	methods: {
		date(date) {
			return moment(date).format('DD/MM/YY')
		},
		price(p, punto=true) {
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
		since(date) {
			return moment(date).fromNow()
		},
		setIdsArticles() {
			this.idsArticles = []
			this.articles.forEach( article => {
				this.idsArticles.push(article.id)
			})
		},
		filter(filtro) {
			axios.post('articles/filter', {
				mostrar: filtro.mostrar,
				ordenar: filtro.ordenar,
				precio_entre: filtro.precio_entre,
				providers: filtro.providers
			})
			.then( res => {
				this.filtrado = true
				this.articles = res.data
				this.setIdsArticles()
				$('#listado-filtrar').modal('hide')
				// console.log(res.data)
			})
			.catch( err => {
				console.log(err)
			})
		},
		preSearch() {
			if (this.search_query.length > 2) {
				axios.get('articles/pre-search/'+this.search_query)
				.then( res => {
					this.pre_search = res.data
					$('.resultados').show()
					$('.input-search').addClass('input-search-resultados')
				})
				.catch( err => {
					console.log(err)
				})
			} else {
				$('.input-search').removeClass('input-search-resultados')
				$('.resultados').hide()
			}
		},
		selectPreSearch(query) {
			this.search_query = query
			this.search()
			$('.resultados').hide()
			$('.input-search').removeClass('input-search-resultados')
		},
		search() {
			axios.get('articles/search/'+this.search_query)
			.then( res => {
				console.log(res.data)
				var articles = res.data
				if (articles.length > 0) {
					this.articles = res.data
				} else {
					toastr.error('No se encontraron artículos con ese criterio')
				}
			})
			.catch( err => {
				console.log(err)
			})
		},


		// Articulos
		getArticles(page) {
			axios.get('articles?page=' + page)
			.then( res => {
				console.log(res.data)
				this.articles = res.data.articles.data;
				this.pagination = res.data.pagination;
				const self = this
				this.setIdsArticles()
			})
			.catch( err => {
				console.log(err)
				// location.reload()
			})
		},
		editArticle(article) {
			this.article.id = article.id
			this.article.bar_code = article.bar_code
			this.article.name = article.name
			this.article.cost = this.price(article.cost, false)
			this.article.price = this.price(article.price, false)
			this.article.stock = article.stock
			if (this.rol == 'commerce') {
				this.article.providers = []
				const self = this
				article.providers.forEach( p => {
					self.article.providers.push(p.id)
				})
			}
			this.article.created_at = article.created_at
			this.article.updated_at = article.updated_at
			this.article.creado = this.date(article.created_at)+' '+this.since(article.created_at)
			this.article.actualizado = this.date(article.updated_at)+' '+this.since(article.updated_at)
			$('#listado-edit-article').modal('show')
		},
		updateArticle(article) {
			axios.put('articles/'+this.article.id, {
				article: article
			})
			.then( res => {
				this.getArticles(1)
				$('#listado-edit-article').modal('hide')
				console.log(res.data)
				toastr.success(`${article.name} se actualizo con exito`)
			})
			.catch( err => {
				console.log(err)
				// location.reload()
			})
		},
		deleteArticle(article) {
			console.log(article.name)	
			this.article.id = article.id
			this.article.name = article.name
			$('#listado-delete-article').modal('show')
		},
		destroyArticle(article_id) {
			axios.delete('articles/'+article_id)
			.then( res => {
				$('#listado-delete-article').modal('hide')
				this.getArticles(1)
				toastr.success('Artículo eliminado con exito')
			})
			.catch( err => {
				console.log(err)
			})
		},


		volverAListar() {
			this.filtrado = false
			this.getArticles(1)
		},

		// Pagination
		changePage: function(page){
			this.pagination.current_page = page;
			this.getArticles(page);
		},

		// Modals
		showDescargarPdf() {
			$('#listado-descargar-pdf').modal('show')
		},
		showImportar() {
			$('#listado-importar').modal('show')
		},
		showFiltrar() {
			$('#listado-filtrar').modal('show')
		},
		uncheckProviders() {
			this.filtro.providers = []
		},
		getProviders() {
			axios.get('providers')
			.then( res => {
				this.providers = res.data
				this.providers.forEach( p => {
					this.filtro.providers.push(p.name)
				})
			})
			.catch( err => {
				console.log(err)
			})
		}
	},
	created() {
		this.getArticles(1)
		this.getProviders()
	},
	computed: {
		isActived: function(){
			return this.pagination.current_page;
		},
		pagesNumber: function(){

			if(!this.pagination.to){
				return [];
			}

			var from = this.pagination.current_page - this.offset;
			
			if(from < 1){
				from = 1;
			}

			var to = from + (2 * this.offset);
			if(to >= this.pagination.last_page){
				to = this.pagination.last_page;
			}

			var pagesArray = [];
			while(from <= to){
				pagesArray.push(from);
				from++;
			}
			return pagesArray;
		}		
	}
}
</script>
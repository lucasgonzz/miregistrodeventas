@extends('layouts.app')

@section('content')
<div id="listado">	
	@include('provider.modals.listado-descargar-pdf')
	@include('provider.modals.listado-filtrar')
	@include('provider.modals.listado-edit-article')
	<div class="row justify-content-center">
		<div class="col-lg-10">	
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-5 p-r-0">
							<div class="buscador">
								<input type="text" class="form-control input-search" 
										placeholder="Buscar por codigo o nombre"
										v-model="search_query" @keyup="preSearch" @keyup.enter="search">
								<div class="resultados">
									<ul>
										<li @click="selectPreSearch(article.name)" v-for="article in pre_search">
											@{{ article.name }}
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
						<div class="col-6">
							<button class="btn btn-secondary m-l-5 float-right" @click="showFiltrar">
								<i class="icon-filter"></i>
								Filtrar
							</button>
							<button class="btn btn-danger m-l- float-right" @click="showDescargarPdf">
								<i class="icon-file"></i>
								Descargar Pdf
							</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					@include('common.includes.listado-info-filtrado')
					<table class="table">
						<thead class="thead-dark">
							<tr>
								<th scope="col">Codigo</th>
								<th scope="col">Nombre</th>
								<th scope="col">Costo</th>
								<th scope="col">Precio</th>
								<th scope="col">Pre anterior</th>
								<th scope="col">Agregado</th>
								<th scope="col">Actualizado</th>
								<th scope="col">Opciones</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="article in articles">
								<td>@{{ article.bar_code }}</td>
								<td>@{{ article.name }}</td>
								<td>$@{{ price(article.cost) }}</td>
								<th scope="row">$@{{ price(article.price) }}</th>
								<td v-if="article.previus_price">$@{{ price(article.previus_price) }}</td>
								<td v-else>Sin datos</td>
								<td>@{{ date(article.created_at) }}</td>
								<td>@{{ date(article.updated_at) }}</td>
								<td class="td-options">
									<button @click="editArticle(article)" class="btn btn-listado btn-listado-edit">
										<i class="icon-edit"></i>
									</button>
									<button @click="deleteArticle(article.id)" class="btn btn-listado btn-listado-delete">
										<i class="icon-trash-2"></i>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="card-footer p-0">
					@include('common.includes.pagination')	
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
@endsection

@section('scripts')
<script>
new Vue({
	el: '#listado',
	data: {

		articles: [],
		article: {'id': 0, 'bar_code': '','name': '', 'cost': 0, 'price': 0, 'stock': 0, 'created_at': '', 'updated_at': '', 'creado': '', 'actualizado': '', 'act_fecha': true},
		search_query: '',
		pre_search: [],

		// Filtros
		filtro: {
			'mostrar': 'todos',
			'ordenar': 'nuevos-viejos',
			'precio_entre': {
				'min': '',
				'max': ''
			}
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
	},
	created() {
		this.getArticles(1)
	},
	methods: {
		date(date) {
			return moment(date).format('DD/MM/YY')
		},
		price(p) {
			var centavos = p.split('.')[1]
			var price = p.split('.')[0]
			let formated_price = numeral(price).format('0,0').split(',').join('.')
			if (centavos != '00') {
				formated_price = formated_price + ',' + centavos
			}
			return formated_price
		},
		since(date) {
			return moment(date).fromNow()
		},

		filter() {
			axios.post('articles/filter', {
				mostrar: this.filtro.mostrar,
				ordenar: this.filtro.ordenar,
				precio_entre: this.filtro.precio_entre
			})
			.then( res => {
				this.filtrado = true
				this.articles = res.data
				$('#listado-filtrar').modal('hide')
				console.log(res.data)
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
				this.articles = res.data
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
			})
			.catch( err => {
				console.log(err)
				location.reload()
			})
		},
		editArticle(article) {
			this.article.id = article.id
			this.article.bar_code = article.bar_code
			this.article.name = article.name
			this.article.cost = article.cost
			this.article.price = article.price
			this.article.stock = article.stock
			this.article.created_at = article.created_at
			this.article.updated_at = article.updated_at
			this.article.creado = this.date(article.created_at)+' '+this.since(article.created_at)
			this.article.actualizado = this.date(article.updated_at)+' '+this.since(article.updated_at)
			$('#listado-edit-article').modal('show')
		},
		updateArticle() {
			axios.put('articles/'+this.article.id, {
				article: this.article
			})
			.then( res => {
				this.getArticles(1)
				$('#listado-edit-article').modal('hide')
				console.log(res.data)
			})
			.catch( err => {
				console.log(err)
				// location.reload()
			})
		},
		deleteArticle(article_id) {
			axios.delete('articles/'+article_id)
			.then( res => {
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
		showFiltrar() {
			$('#listado-filtrar').modal('show')
		},
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
	},
})
</script>
@endsection
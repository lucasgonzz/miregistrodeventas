@extends('layouts.app')

@section('content')
<div id="listado">	
	@include('commerce.modals.listado-descargar-pdf')
	@include('commerce.modals.listado-filtrar')
	<div class="row justify-content-center">
		<div class="col-lg-10">	
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-5 p-r-0">
							<input type="text" class="form-control input-search" placeholder="Buscar por codigo o nombre">
						</div>
						<div class="col-1 p-l-0">
						  	<button class="btn btn-primary btn-search">
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
					<!-- <h5 class="card-title">
						57 artículos registrados
					</h5> -->
					<table class="table">
						<thead class="thead-dark">
							<tr>
								<th scope="col">Codigo</th>
								<th scope="col">Nombre</th>
								<th scope="col">Costo</th>
								<th scope="col">Precio</th>
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
								<td>@{{ date(article.created_at) }}</td>
								<td>@{{ date(article.updated_at) }}</td>
								<td class="td-options">
									<button class="btn btn-listado btn-listado-edit">
										<i class="icon-edit"></i>
									</button>
									<button class="btn btn-listado btn-listado-delete">
										<i class="icon-trash-2"></i>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="card-footer p-0">
					@include('common.includes.pagination')
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
		providers: [
			{'id': 1, 'name': 'Victoria'},
			{'id': 2, 'name': 'Rosario'},
			{'id': 3, 'name': 'Buenos Aires'},
			{'id': 4, 'name': 'Mendoza'},
			{'id': 5, 'name': 'San Luiz'},
			{'id': 6, 'name': 'San Juan'},
		],
		articles: [],

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
		showDescargarPdf() {
			$('#listado-descargar-pdf').modal('show')
		},
		showFiltrar() {
			$('#listado-filtrar').modal('show')
		},
		getArticles(page) {
			axios.get('articles?page=' + page)
			.then( res => {
				this.articles = res.data.articles.data;
				this.pagination = res.data.pagination;
			})
			.catch( err => {
				console.log(err)
			})
		},
		changePage: function(page){
			this.pagination.current_page = page;
			this.getArticles(page);
		},
	},
})
</script>
@endsection
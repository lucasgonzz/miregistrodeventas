<template>
<div class="container-fluid">	
	<div id="listado">	
		<editar-articulo :rol="rol" 
						:article="article" 
						:providers="providers" 
						@updateArticle="updateArticle"
						@clearArticle="clearArticle"></editar-articulo>
		<add-marker @deleteMarkerGroup="deleteMarkerGroup"
					@addMarkerToGroup="addMarkerToGroup"
					:marker_groups="marker_groups"
					:article="article"></add-marker>
		<is-marker :article="article"
					@deleteMarker="deleteMarker"></is-marker>
		<update-by-porcentage @updateByPorcentage="updateByPorcentage"
						:selected_articles="selected_articles"></update-by-porcentage>
		<confirmar-eliminacion :article="article" 
								@destroyArticle="destroyArticle"></confirmar-eliminacion>
		<descargar-pdf :rol="rol" 
						:selected_articles="selected_articles.selected_articles"
						:articles_id="selected_articles.articles_id"
						:filtro="filtro"></descargar-pdf>
		<print-tickets :selected_articles="selected_articles"></print-tickets>
		<delete-articles :selected_articles="selected_articles"
						@deleteArticles="deleteArticles"></delete-articles>
		<importar></importar>
		<create-offer :selected_articles="selected_articles"
						@createOffer="createOffer"></create-offer>
		<providers-history :article="article"></providers-history>
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
							<div class="col-10 col-lg-3 p-r-0">
								<div class="buscador">
									<input type="search" class="form-control input-search" 
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
							<div class="col-12 col-lg-8 m-t-10 m-lg-t-0 col-btn">
								<div class="dropdown" 
									v-show="selected_articles.selected_articles.length">
									<button class="btn btn-secondary dropdown-toggle" 
											type="button" 
											id="selected_articles_buttons" 
											data-toggle="dropdown" 
											aria-haspopup="true" 
											aria-expanded="false">
										{{ selected_articles.selected_articles.length }} arículos seleccionados
									</button>
									<div class="dropdown-menu" aria-labelledby="selected_articles_buttons">
										<a href="#" @click.prevent="showCreateOffer"
											class="dropdown-item c-p">
											<i class="icon-sale-ticket"></i>
											Oferta
										</a>
										<a href="#" @click.prevent="showUpdateByPorcentage"
											class="dropdown-item c-p">
											<i class="icon-plus"></i>
											%
										</a>
										<a href="#" @click.prevent="showPrintTickets"
											class="dropdown-item c-p">
											<span class="icon-tag"></span>
											Tickets	
										</a>
										<a href="#" @click.prevent="showDeleteArticles"
											class="dropdown-item c-p">
											<i class="icon-trash-3"></i>
											Eliminar
										</a>
									</div>
								</div>
								<!-- <button class="btn btn-warning m-l-5" @click="showImportar">
									<i class="icon-upload"></i>
									Importar Exel
								</button> -->
								<a href="articles/exel" class="btn btn-success m-l-5">
									<i class="icon-download"></i>
									Exel
								</a>
								<button class="btn btn-danger m-l-5" @click="showDescargarPdf">
									<i class="icon-download"></i>
									Pdf
								</button>
								<button class="btn btn-secondary m-l-5" @click="showFiltrar">
									<i class="icon-filter"></i>
									Filtrar
								</button>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="row m-b-10" v-show="!is_filter && !searching">
							<div class="col">
								<pagination @changePage="changePage" 
											:is_filter="is_filter"
											:pagination="pagination"
											:pages-number="pagesNumber"></pagination>
							</div>
						</div>
						<div class="row m-b-10" v-show="is_filter || searching">
							<div class="col">
								<button class="btn btn-primary float-right"
										@click="volverAListar">
									<i class="icon-undo"></i>
									Volver a listar todos mis artículos
								</button>
							</div>
						</div>
						<div class="row" v-show="isLoading">
							<div class="col">
								<div class="spinner-listado">
									<div class="spinner">
										<div class="spinner-border text-primary" role="status">
											<span class="sr-only">Loading...</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row" v-show="!isLoading">
							<div class="col">
								<info-filtrados :filtro="filtro" :is_filter="is_filter" 
												:providers="providers"
												:articles-length="articles.length">
								</info-filtrados>
							</div>
						</div>
						<div class="row" v-show="!isLoading">
							<div class="col">
								<div class="table-responsive">						
									<table class="table">
										<thead class="thead-dark">
											<tr v-if="rol == 'commerce'">
												<th scope="col" class="td-checkbox">
									                <div class="custom-control custom-checkbox  custom-control-inline-50 my-1 mr-sm-2">
									                  	<input class="custom-control-input" 
									                  			v-model="selected_articles.is_all_selected" 
									                  			@change="selectAllArticles"
									                  			type="checkbox" 
									                  			id="is-all-selected">
									                  	<label class="custom-control-label" 
									                  			for="is-all-selected">
									                  </label>
									                </div>
												</th>
												<th scope="col">Codigo</th>
												<th scope="col">Nombre</th>
												<th scope="col">Costo</th>
												<th scope="col">Precio</th>
												<th scope="col" class="td-stock">Stock</th>
												<th scope="col">Proveedor/es</th>
												<th scope="col">Ult modificacion</th>
												<th scope="col">Opciones</th>
											</tr>
											<tr v-else>
												<th scope="col" class="td-checkbox">
									                <div class="custom-control custom-checkbox  custom-control-inline-50 my-1 mr-sm-2">
									                  	<input class="custom-control-input" 
									                  			v-model="selected_articles.is_all_selected" 
									                  			@change="selectAllArticles"
									                  			type="checkbox" 
									                  			id="is-all-selected">
									                  	<label class="custom-control-label" 
									                  			for="is-all-selected">
									                  </label>
									                </div>
												</th>
												<th scope="col">Codigo</th>
												<th scope="col">Nombre</th>
												<th scope="col">Costo</th>
												<th scope="col">Precio</th>
												<th scope="col" class="td-stock">Stock</th>
												<th scope="col">Ult modificacion</th>
												<th scope="col">Opciones</th>
											</tr>
										</thead>
										<tbody class="vender-table">
											<template v-if="rol == 'commerce'">
												<tr v-for="article in articles"
													:class="selected_articles.selected_articles.includes(article.id) ? 'bg-warning' : ''">
													<td class="td-checkbox">
										                <div class="custom-control custom-checkbox  custom-control-inline-50 my-1 mr-sm-2">
										                  	<input class="custom-control-input c-p" 
										                  			v-model="selected_articles.selected_articles" 
										                  			:value="article.id"
										                  			type="checkbox" 
										                  			:id="article.id">
										                  	<label class="custom-control-label" 
										                  			:for="article.id">
										                  </label>
										                </div>
													</td>
													<td v-if="article.bar_code">
														{{ article.bar_code }}
													</td>
													<td v-else>
														<strong>No tiene</strong>
													</td>
													<td>{{ article.name }}</td>
													<td>{{ price(article.cost) }}</td>

													<td v-if="article.offer_price"
														class="td-price">
														<i class="icon-sale-ticket ticket-price"></i>
														<span v-show="article.uncontable == 0">
															{{ price(article.offer_price) }}
														</span>
														<span v-show="article.uncontable == 1">
															{{ price(article.offer_price) }} el  {{ article.measurement }}
														</span>
													</td>
													<td v-else
														class="td-price">
														<span v-show="article.uncontable == 0">
															{{ price(article.price) }}
														</span>
														<span v-show="article.uncontable == 1">
															{{ price(article.price) }} el  {{ article.measurement }}
														</span>
													</td>
													<td v-if="article.stock" class="td-stock">
														<span v-if="article.uncontable == 1">
															{{ stock(article.stock) }} {{ article.measurement }}(s)
														</span>
														<span v-else>
															{{ stock(article.stock) }}
														</span>
													</td>
													<td v-else>
														Sin uso
													</td>
													<td>
														<div class="dropdown">
															<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																Proveedores
															</button>
															<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
																<span v-for="provider in article.providers_not_repeated"
																	class="dropdown-item c-n">
																	{{ provider.name }}
																</span>
																<span @click="providersHistory(article)"
																		class="dropdown-item c-p">
																	<i class="icon-calendar"></i>
																	Historial
																</span>
															</div>
														</div>
													</td>
													<td>{{ date(article.updated_at) }}</td>
													<td class="td-options">
														<button @click="editArticle(article)" class="btn btn-listado btn-listado-edit">
															<i class="icon-edit"></i>
														</button>
														<button @click="deleteArticle(article)" class="btn btn-listado btn-listado-delete">
															<i class="icon-trash-3"></i>
														</button>									
														<button class="btn"
																:class="article.marker ? 'btn-warning' : 'btn-primary'"
																@click="showAddMarker(article)">
															<i class="icon-star-1"></i>
														</button>
 														<button @click="deleteOffer(article)"
																v-show="article.offer_price"
																class="btn btn-danger btn-sm">
															<i class="icon-sale-ticket"></i>
															<i class="icon-cancel"></i>
														</button>
													</td>
												</tr>
											</template>
											<template v-else>								
												<tr v-for="article in articles"	
													:class="selected_articles.selected_articles.includes(article.id) ? 'bg-warning' : ''">
													<td class="td-checkbox">
										                <div class="custom-control custom-checkbox  custom-control-inline-50 my-1 mr-sm-2">
										                  	<input class="custom-control-input c-p" 
										                  			v-model="selected_articles.selected_articles"
										                  			:value="article.id" 
										                  			type="checkbox" 
										                  			:id="article.id">
										                  	<label class="custom-control-label" 
										                  			:for="article.id">
										                  </label>
										                </div>
													</td>
													<td v-if="article.bar_code">
														{{ article.bar_code }}
													</td>
													<td v-else>
														<strong>No tiene</strong>
													</td>
													<td>{{ article.name }}</td>
													<td>{{ price(article.cost) }}</td>

													<td v-if="article.offer_price"
														class="td-price">
														<i class="icon-sale-ticket ticket-price"></i>
														<span v-show="article.uncontable == 0">
															{{ price(article.offer_price) }}
														</span>
														<span v-show="article.uncontable == 1">
															{{ price(article.offer_price) }} el  {{ article.measurement }}
														</span>
													</td>
													<td v-else
														class="td-price">
														<span v-show="article.uncontable == 0">
															{{ price(article.price) }}
														</span>
														<span v-show="article.uncontable == 1">
															{{ price(article.price) }} el  {{ article.measurement }}
														</span>
													</td>
													<td v-if="article.stock" class="td-stock">
														<span v-if="article.uncontable == 1">
															{{ stock(article.stock) }} {{ article.measurement }}(s)
														</span>
														<span v-else>
															{{ stock(article.stock) }}
														</span>
													</td>
													<td v-else>
														Sin uso
													</td>
													<td>{{ date(article.updated_at) }}</td>
													<td class="td-options">
														<button @click="editArticle(article)" class="btn btn-listado btn-listado-edit">
															<i class="icon-edit"></i>
														</button>
														<button @click="deleteArticle(article)" class="btn btn-listado btn-listado-delete">
															<i class="icon-trash-3"></i>
														</button>								
														<button class="btn"
																:class="article.marker ? 'btn-warning' : 'btn-primary'"
																@click="showAddMarker(article)">
															<i class="icon-star-1"></i>
														</button>
														<!-- <button @click="createMarker(article)" class="btn btn-listado btn-listado-edit">
															<i v-show="article.marker == 1"
																:id="'marker-1-'+article.id"
																class="icon-star-1"></i>
															<i  v-show="article.marker == 0"
																:id="'marker-2-'+article.id"
																class="icon-star-2"></i>
														</button> -->
														<button @click="deleteOffer(article)"
																v-show="article.offer_price"
																class="btn btn-danger btn-sm">
															<i class="icon-sale-ticket"></i>
															<i class="icon-cancel"></i>
															<!-- Terminar Oferta -->
														</button>
													</td>
												</tr>								
											</template>
										</tbody>
									</table>
								</div>
							</div>
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
import UpdateByPorcentage from './modals/UpdateByPorcentage.vue'
import CreateOffer from './modals/CreateOffer.vue'
import AddMarker from './modals/AddMarker.vue'
import IsMarker from './modals/IsMarker.vue'
import PrintTickets from './modals/PrintTickets.vue'
import DeleteArticles from './modals/DeleteArticles.vue'
import ProvidersHistory from './modals/ProvidersHistory.vue'
import ConfirmarEliminacion from './modals/ConfirmarEliminacion.vue'
import InfoFiltrados from './modals/InfoFiltrados.vue'
import Pagination from './Pagination.vue'
export default {
	components: {
		DescargarPdf,
		Importar,
		Filtrar,
		EditarArticulo,
		IsMarker,
		AddMarker,
		CreateOffer,
		UpdateByPorcentage,
		DeleteArticles,
		PrintTickets,
		ProvidersHistory,
		InfoFiltrados,
		Pagination,
		ConfirmarEliminacion
	},
	props: ['rol'],
	data() {
		return {

			articles: [],
			article: {'id': 0, 'bar_code': '','name': '', 'cost': 0, 'price': 0, 'stock': 0, 'new_stock': 0, 'providers': [], 'created_at': '', 'updated_at': '', 'creado': '', 'actualizado': '', 'act_fecha': true},
			isLoading: false,
			all_selected_articles: false,

			// Marcadores
			marker_groups: [],

			// Buscar
			search_query: '',
			pre_search: [],
			searching: false,

			// Provedores
			providers: [],
			proiders_history: [],

			// Objeto para controlar el seleccionados de articulos para 
			// ser importados en pdf, exel y para imprimir los tickets
			selected_articles: {
				is_all_selected: false,
				selected_articles: [],
				articles: [],
				articles_id: [],
				selected_pages: [],
			},
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
			is_filter: false,

			// Pagination
			// current_page: 0,
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
		price(p) {
			return numeral(p).format('$0,0.00')
		},
		since(date) {
			return moment(date).fromNow()
		},
		stock(s) {
			return s.replace('.00', '')
		},

		showAddMarker(article) {
			this.setArticle(article)
			if (article.marker) {
				$('#is-marker').modal('show')
			} else {
				$('#add-marker').modal('show')
			}
		},


		/* --------------------------------------------------------------------------
			* Se fija si la pagina actual aparece en las paginas seleccionadas
			* Si aparece marca la casilla de todos los articulos seleccionados
			* Sino la desmarca
		-------------------------------------------------------------------------- */
		setIsAllSelected() {
			if (this.selected_articles.selected_pages.includes(this.pagination.current_page)) {
				this.selected_articles.is_all_selected = true
			} else {
				this.selected_articles.is_all_selected = false
			}
		},
		/* --------------------------------------------------------------------------
			* Es llamado por la casilla de marcar todos los articulos
			* Si esta activada agregar todos los id de los articulos de la pagina actual
			a los id de los articulos seleccionados
		-------------------------------------------------------------------------- */
		selectAllArticles() {
			if (this.selected_articles.is_all_selected) {
				this.selected_articles.articles_id.forEach(article_id => {
					if (!this.selected_articles.selected_articles.includes(article_id)) {
						this.selected_articles.selected_articles.push(article_id)
					}
				})
				this.selected_articles.selected_pages.push(this.pagination.current_page)
			} else {
				this.selected_articles.articles_id.forEach(article_id => {
					var index = this.selected_articles.selected_articles.indexOf(article_id)
					this.selected_articles.selected_articles.splice(index, 1)
				})
				var index = this.selected_articles.selected_pages.indexOf(this.pagination.current_page)
				this.selected_articles.selected_pages.splice(index, 1)
			}
		},
		/* --------------------------------------------------------------------------
			* Recorre todos los articulos y agrega los id a la propiedad
			de los articulos seleccionados que almacena los id de los artículos
			que hay en la pagina
			* Se la usa en filtrar y en getArticles
		-------------------------------------------------------------------------- */
		setArticlesId() {
			this.selected_articles.articles_id = []
			this.articles.forEach( article => {
				this.selected_articles.articles_id.push(article.id)
			})
		},
		
		/* -----------------------------------------------------------------------------------
			* Ofrese los posibles resultados para la busqueda de artículos
		----------------------------------------------------------------------------------- */
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
		
		/* -----------------------------------------------------------------------------------
			* Cuando hacemos click en la lista de los posibles articulos a mostrar
			setea el search_cuery y llama al metodo search
		----------------------------------------------------------------------------------- */
		selectPreSearch(query) {
			this.search_query = query
			this.search()
		},
		/* -----------------------------------------------------------------------------------
			* Hace una llamada al metodo search del controlador de articulos
		----------------------------------------------------------------------------------- */
		search() {
			this.searching = true
			this.isLoading = true
			axios.get('articles/search/'+this.search_query)
			.then( res => {
				this.isLoading = false
				var articles = res.data
				if (articles.length > 0) {
					this.articles = res.data
					this.filterProviders()
					this.setArticlesId()
				} else {
					toastr.error('No se encontraron artículos con ese criterio')
				}
				$('.resultados').hide()
				$('.input-search').removeClass('input-search-resultados')
			})
			.catch( err => {
				console.log(err)
			})
		},


		/*
			ARTICULOS
		-----------------------------------------------------------------------------------
			* Obtiene los articulos
			* Setea los id de los articulos que ha obtenido
			* Setea si la casilla de todos los articulos seleccionados esta o no activada
			* Filtra los proveedores para que no aparescan mas de una vez en la lista
		-----------------------------------------------------------------------------------
		*/
		getArticles(page) {
			this.isLoading = true
			axios.get('articles?page=' + page)
			.then( res => {
				this.isLoading = false
				this.articles = res.data.articles.data;
				console.log(res.data.articles.data)
				this.pagination = res.data.pagination;
				this.setArticlesId()
				this.setIsAllSelected()
				this.filterProviders()
				// Se pasa el nombre de la medida a español
			})
			.catch( err => {
				console.log(err)
			})
		},
		
		/* -----------------------------------------------------------------------------------
			* Setea las propiedades del articulos con las del articulo pasado por parametro
		----------------------------------------------------------------------------------- */
		editArticle(article) {
			console.log(article)
			this.setArticle(article)
			this.article.creado = this.date(article.created_at)+' '+this.since(article.created_at)
			this.article.actualizado = this.date(article.updated_at)+' '+this.since(article.updated_at)
			$('#listado-edit-article').modal('show')
		},
		
		/* -----------------------------------------------------------------------------------
			* Envia el objeto articulo al metodo update del controlador de articulos
			* Esconde el modal de editar articulo
			* Envia notificacion de ariculo actualizado con exito
			* Reinicia las propiedades del articulo
		----------------------------------------------------------------------------------- */
		updateArticle(article) {
			axios.put('articles/'+this.article.id, {
				article: article
			})
			.then( res => {
				this.updateArticlesList()
				$('#listado-edit-article').modal('hide')
				toastr.success(`${article.name} se actualizo con exito`)
				this.clearArticle()
			})
			.catch( err => {
				console.log(err)
			})
		},
		updateArticlesList(after_delete_articles = false) {
			if (after_delete_articles) {
				this.getArticles(this.pagination.current_page)
			} else {
				if (this.searching) {
					this.search()
				} else if (this.is_filter) {
					this.filter(this.filtro)
				} else {
					this.getArticles(this.pagination.current_page)
				}
			}
			this.selected_articles.is_all_selected = false
		},
		/* -----------------------------------------------------------------------------------
			* Setea el objeto de articulo
			* Muestra modal de eliminar articulo
		----------------------------------------------------------------------------------- */
		deleteArticle(article) {
			this.setArticle(article)
			$('#listado-delete-article').modal('show')
		},


		// Marcadores
		// createMarker(article) {
		// 	if (article.marker) {
		// 		axios.get(`articles/delete-marker/${article.id}`)
		// 		.then(res => {
		// 			$(`#marker-1-${article.id}`).removeClass('icon-star-1')
		// 			$(`#marker-1-${article.id}`).addClass('icon-star-2')
		// 			toastr.success('Marcador eliminado correctamente')
		// 		})
		// 		.catch(err => {
		// 			console.log(err)
		// 		})
		// 	} else {
		// 		axios.get(`articles/create-marker/${article.id}`)
		// 		.then(res => {
		// 			$(`#marker-2-${article.id}`).removeClass('icon-star-2')
		// 			$(`#marker-2-${article.id}`).addClass('icon-star-1')
		// 			toastr.success('Marcador añadido correctamente')
		// 		})
		// 		.catch(err => {
		// 			console.log(err)
		// 		})
		// 	}
		// },
		deleteMarker(marker_id) {
			axios.delete('markers/'+marker_id)
			.then(res => {
				this.updateArticlesList()
				$('#is-marker').modal('hide')
				toastr.success('Marcador eliminado correctamente')
			})
			.catch(err => {
				console.log(err)
			})
		},
		addMarkerToGroup(marker_group_id, article_id) {
			axios.get('marker-groups/add-marker-to-group/'+marker_group_id+'/'+article_id)
			.then(res => {
				this.updateArticlesList()
				$('#add-marker').modal('hide')
				toastr.success('Marcador agregado correctamente')
				this.getMarkerGroups()
			})
		},

		getMarkerGroups() {
			axios.get('marker-groups')
			.then(res => {
				this.marker_groups = res.data
			})
			.catch(err => {
				console.log(err)
			})
		},

		deleteMarkerGroup(marker_group_id) {
			axios.delete('marker-groups/'+marker_group_id)
			.then(res => {
				this.getMarkerGroups()
				this.updateArticlesList()
				toastr.success('Grupo de marcadores eliminado correctamente')
			})
		},
		deleteOffer(article) {
			axios.delete('articles/delete-offer/'+article.id)
			.then(res => {
				this.updateArticlesList()
				toastr.success('Oferta eliminada correctamente')
			})
			.catch(err => {
				console.log(err)
			})
		},
		setArticle(article) {
			// this.article = article
			this.article.id = article.id
			this.article.uncontable = article.uncontable
			this.article.measurement = article.measurement
			this.article.bar_code = article.bar_code
			this.article.name = article.name
			this.article.cost = article.cost
			this.article.price = article.price
			if (article.offer_price != null) {
				this.article.offer_price = article.offer_price
			}
			this.article.marker = article.marker
			this.article.previus_price = article.previus_price
			this.article.stock = article.stock
			if (this.rol == 'commerce') {
				this.article.provider = article.providers[0].id
				this.article.providers = article.providers
			}
			this.article.created_at = article.created_at
			this.article.updated_at = article.updated_at
		},
		
		/* -----------------------------------------------------------------------------------
			* Envia id del articulo a aliminar al metodo delete del controlador de
			articulos
			* Obtiene los datos de los articulos actualizados
			* Esconde modal de eliminacionde articulo
			* Envia notificacion de eliminacion exitosa
		----------------------------------------------------------------------------------- */
		destroyArticle(article_id) {
			axios.delete('articles/'+article_id)
			.then( res => {
				this.selected_articles.selected_articles = []
				this.updateArticlesList()
				$('#listado-delete-article').modal('hide')
				toastr.success('Artículo eliminado correctamente')
			})
			.catch( err => {
				console.log(err)
			})
		},
		
		/* -----------------------------------------------------------------------------------
			* Resetea las propiedades del objeto articulo
		----------------------------------------------------------------------------------- */
		clearArticle() {
			this.article.id = ''
			this.article.bar_code = ''
			this.article.name = ''
			this.article.cost = ''
			this.article.price = ''
			this.article.stock = 0
			this.article.new_stock = 0
			if (this.rol == 'commerce') {
				this.article.provider = this.article.providers[0].id
			}
			this.article.created_at = ''
			this.previus_next = 0
			console.log(this.article)
		},

		/* -----------------------------------------------------------------------------------
			* False la propiedad que indica si esta filtrado o no
			* False la propiedad que indica si esta buscando o no
			* Deja el campo del earch_query vacio
			* Actualiza los articulos 
		----------------------------------------------------------------------------------- */
		volverAListar() {
			this.is_filter = false
			this.searching = false
			this.search_query = ''
			this.selected_articles.selected_articles = []
			this.getArticles(this.pagination.current_page)
		},

		// Pagination
		changePage: function(page){
			this.pagination.current_page = page;
			this.getArticles(page);
		},

		// Modals
		// Metodos del header

		getArticlesBySelectedArticles() {
			axios.get('articles/get-by-ids/'+this.selected_articles.selected_articles.join('-'))
			.then(res => {
				this.selected_articles.articles = res.data
			})
			.catch(err => {
				console.log(err)
			})
		},
		showCreateOffer() {
			this.getArticlesBySelectedArticles()
			$('#create-offer').modal('show')
		},
		createOffer() {
			axios.post('articles/create-offer', {
				articles: this.selected_articles.articles,
				// porcentage: porcentage
			})
			.then(res => {
				this.updateArticlesList()
				$('#create-offer').modal('hide')
				toastr.success('Ofertas creadas correctamente')
			})
			.catch(err => {
				console.log(err)
			})
		},
		showUpdateByPorcentage() {
			$('#update-by-porcentage').modal('show')
		},
		updateByPorcentage(cost, price, decimals) {
			axios.post('articles/update-by-porcentage', {
				cost,
				price,
				decimals,
				articles_ids: this.selected_articles.selected_articles
			})
			.then(res => {
				// console.log(res.data)
				this.updateArticlesList()
				$('#update-by-porcentage').modal('hide')
			})
			.catch(err => {
				console.log(err)
			})
		},
		showPrintTickets() {
			$('#print-tickets').modal('show')
		},
		showDeleteArticles() {
			$('#delete-articles').modal('show')
		},
		deleteArticles() {
			axios.delete('articles/delete-articles/'+this.selected_articles.selected_articles.join('-'))
			.then(res => {
				this.selected_articles.selected_articles = []
				this.updateArticlesList(true)
				toastr.success('Artículos eliminados correctamente')
				$('#delete-articles').modal('hide')
			})
			.catch(err => {
				console.log(err)
			})
		},
		showImportar() {
			$('#listado-importar').modal('show')
		},
		showDescargarPdf() {
			$('#listado-descargar-pdf').modal('show')
		},
		showFiltrar() {
			$('#listado-filtrar').modal('show')
		},
		uncheckProviders() {
			this.filtro.providers = []
		},
		/* --------------------------------------------------------------------------
			* Toma los valores de filtrado y los manda al metodo de filtrado 
			de los artículos
			* Setea la propiedad de filtrado como verdadera
			* Agrega los articulos obtenidos a los articulos
			* Setea los id de los artiuclos
			* Filtra los proveedores para que aparescan solo una vez en la lista
		-------------------------------------------------------------------------- */
		filter(filtro) {
			this.isLoading = true
			$('#listado-filtrar').modal('hide')
			axios.post('articles/filter', {
				mostrar: filtro.mostrar,
				ordenar: filtro.ordenar,
				precio_entre: filtro.precio_entre,
				providers: filtro.providers
			})
			.then( res => {
				this.isLoading = false
				this.is_filter = true
				console.log(res.data)
				this.articles = res.data
				this.setArticlesId()
				this.filterProviders()
			})
			.catch( err => {
				console.log(err)
			})
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
		},
		filterProviders() {
			if (this.rol == 'commerce') {
				// Se crea una propiedad en los articulos donde estan solo 
				// los proveedores que no se repiten
				this.articles.forEach( article => {
					var providers_ids = []
					var providers = []
					article.providers.forEach( provider => {
						if (!providers_ids.includes(provider.id)) {
							providers_ids.push(provider.id)
							providers.push(provider)
						}
					})
					article.providers_not_repeated = providers
				})
			}
		},
		providersHistory(article) {
			this.article = article
			$('#providers-history').modal('show')
		},
	},
	created() {
		this.getArticles(1)
		if (this.rol == 'commerce') {
			this.getProviders()
		}
		this.getMarkerGroups()
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
<style scoped>
td {
	vertical-align: middle;	
}
.col-btn {
	display: flex;
	flex-direction: row;
	justify-content: flex-end;
}

.dropdown-item.c-p:hover {
	background: #3490dc;
	color: #FFF;
}

.spinner-listado {
	position: absolute;
	width: 100vw;
	height: 100vh;
	background: #FFF;
	z-index: 500;
}

.td-price {
	position: relative;
	font-weight: bold
}

.ticket-price {
	position: absolute;
	font-size: 30px;
	color: #E23535;
	top: -5px;
	left: -20px;
}
</style>
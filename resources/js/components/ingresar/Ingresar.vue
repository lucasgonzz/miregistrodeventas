<template>
<div class="container">	
	<div id="ingresar">
		<providers :providers="providers"
					@deleteProvider="deleteProvider" 
					@saveProvider="saveProvider"></providers>
		<edit-article
					:providers="providers"
					:rol="rol"
					:article="article"
					:previusNext="previus_next"
					@previus="previus"
					@next="next"
					@clearArticle="clearArticle"
					@updateArticle="updateArticle"></edit-article>
		<porcentage-for-price @setPorcentageForPrice="setPorcentageForPrice"></porcentage-for-price>
		<print-tickets :articles="articles"
						:articles_id="articles_id_to_print"></print-tickets>
		<div class="row justify-content-center">
			<div class="col-lg-7">	
				<div class="card">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col col-lg-5">
								<h5 class="m-b-0">
									<strong>Ingresar un nuevo artículo</strong>
								</h5>
							</div>
							<div class="col">
								<!-- <button class="btn btn-success">
									Porcentaje Fijo
								</button> -->
								<button @click="previus" 
										class="btn btn-primary float-right">
									<i class="icon-undo"></i>
									Anterior
								</button>
							</div>
						</div>
					</div>
					<div class="card-body">
						<h6 class="h6">
							<strong>Complete con los datos del artículo que quiera ingresar</strong>
						</h6>
						
						
						<div class="custom-control custom-checkbox my-1 mr-sm-2 m-b-10">
							<input v-model="article.is_uncontable" 
									type="checkbox" 
									class="custom-control-input" 
									id="uncontable">
							<label class="custom-control-label c-p" 
									for="uncontable">
								Incontable
							</label>
							<small class="form-text text-muted">
								¿Este artículo no puede ser contado o enumerado? Ej: Queso, luquido, etc.
							</small>
						</div>

						<div class="form-group">
							<label for="bar_code">Codigo de Barras</label>
							<input 
								type="text" id="bar_code" name="bar_code" 
								class="form-control focus-red" autofocus="true" 
								@keyup.enter="isRegister" 
								v-model="article.bar_code"
								placeholder="Codigo de barras">

							<a href="codigos-de-barra" class="btn btn-link" v-show="article.bar_code == ''">
								¿No tiene codigo de barras? Crea uno aca
							</a>
						</div>
						<div class="form-group">
							<div class="buscador">
								<label for="name">Nombre</label>
								<input type="text" 
										@keyup="setPossibleNames"
										@keyup.enter="changeToCost"
										required 
										autocomplete="off" 
										class="form-control focus-red input-search" 
										placeholder="Ingrese el nombre"
										id="name"
										v-model="article.name">
								<div class="results m-t-10" v-show="possibles_names.length">
									<ul class="list-group">
										<li class="list-group-item active p-t-5 p-b-5">
											Selecciona tu artículo si aparece en esta lista
										</li>
										<li class="list-group-item p-t-5 p-b-5 c-p" @click="selectPossibleName(name)"
											v-for="name in possibles_names">
											{{ name }}
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="row m-b-5">
							<div :class="article.is_uncontable ? 'col-5' : 'col'">
								<label for="cost">Costo</label>
								<input type="number" 
										class="form-control focus-red" 
										placeholder="Ingrese el costo"
										required 
										id="cost"
										@keyup.enter="changeToPrice"
										@keyup="calculatePorcentageForPrice"
										v-model="article.cost">
								<small class="form-text text-muted">
									Coloque un . (punto) para agregar centavos
								</small>
							</div>
							<div :class="article.is_uncontable ? 'col-7' : 'col'">
								<label for="price">Precio</label>
								<br>
								<input type="number" 
										class="form-control focus-red" 
										:class="article.is_uncontable ? 'input-uncontable-price' : ''"
										placeholder="Ingrese el precio"
										required 
										id="price"
										@keyup.enter="changeToDate"
										v-model="article.price">
								<span v-show="article.is_uncontable" class="p-l-5 p-r-5">el</span>
								<select v-show="article.is_uncontable"
										id="measurement" 
										v-model="article.measurement"
										class="form-control select-measurement">
									<option value="kilo" selected>Kilo</option>
									<option value="gramo">Gramo</option>
								</select>
								<small class="form-text text-muted">
									Coloque un . (punto) para agregar centavos
								</small>

								<a @click.prevent="showPorcentageForPrice"
									href="#" 
									class="btn btn-link" v-show="porcentage_for_price == 0">
									Usar un porcentaje fijo
								</a>
								<a @click.prevent="stopPorcentageForPrice"
									href="#" 
									class="btn btn-link" v-show="porcentage_for_price != 0">
									Dejar de usar porcentaje fijo (%{{ porcentage_for_price }})
								</a>
							</div>
						</div>
						<div class="form-group">
							<label for="created_at">Fecha</label>
							<input type="date" 
									@change="remember_date_"
									id="created_at"
									class="form-control focus-red"
									v-model="article.created_at">
						</div>
						<div v-show="remember_date"
								class="custom-control custom-checkbox my-1 mr-sm-2 m-b-10">
							<input v-model="remember_date" 
									type="checkbox" 
									class="custom-control-input" 
									id="remember_date">
							<label class="custom-control-label c-p" 
									for="remember_date">
								Recordar fecha
							</label>
							<small class="form-text text-muted">
								Sí el proximo artículo también es del {{ article.created_at }} se recordara
							</small>
						</div>
						<div class="row m-b-15" v-if="rol == 'commerce'">
							<div class="col">
								<div class="form-group">
									<label class="label-block" for="providers">Proveedor</label>
									<select v-model="article.provider" 
											@keyup.enter="changeToStock"
											name="providers" 
											required 
											id="providers" 
											class="form-control">
										<option v-for="provider in providers" 
												:value="provider.id">
											{{ provider.name }}
										</option>
									</select>
								</div>
								<div class="custom-control custom-checkbox my-1 mr-sm-2 m-b-10">
									<input v-model="remember_provider" 
											type="checkbox" 
											class="custom-control-input" 
											id="remember_provider">
									<label class="custom-control-label c-p" 
											for="remember_provider">
										Recordar proveedor
									</label>
									<small class="form-text text-muted">
										Sí el proximo artículo es de/los mismos proveedores se recordara
									</small>
								</div>
								<a href="#" @click.prevent="showProviders">
									Proveedores (agregar, eliminar)...
								</a>
							</div>
							<div class="col">
								<label for="stock">Cantidad</label>
								<br>
								<input type="number" 
										class="form-control focus-red" 
										:class="article.is_uncontable ? 'input-uncontable-stock' : ''"
										id="stock"
										@keyup.enter="saveArticle"
										placeholder="Ingrese la cantidad"
										v-model="article.stock">
								<span v-show="article.is_uncontable">{{ article.measurement }}(s)
								</span>
								<small class="form-text text-muted">
									Si deja este campo vacio no se tendra en cuenta el stock al 
									momento de hacer una venta
								</small>
							</div>
						</div>
						<div class="form-group" v-else>
							<label for="stock">Cantidad</label>
							<br>
							<input type="number" 
									class="form-control focus-red"
										:class="article.is_uncontable ? 'input-uncontable-stock' : ''"
									@keyup.enter="saveArticle" 
									id="stock"
									placeholder="Ingrese la cantidad"
									v-model="article.stock">
							<span v-show="article.is_uncontable">{{ article.measurement }}(s)
							</span>
							<small class="form-text text-muted">
								Si deja este campo vacio no se tendra en cuenta el stock al 
								momento de hacer una venta
							</small>
						</div>
					</div>
					<div class="card-footer p-0">
						<div class="row m-0">
							<div class="col-4 p-0">
								<button @click.prevent="showPrintTickets" 
										class="btn btn-block btn-left btn-primary m-0">
									<i class="icon-tag"></i>
									Tickets ({{ articles_id_to_print.length }})
								</button>
							</div>
							<div class="col-8 p-0">
								<button @click.prevent="saveArticle"
										class="btn btn-block btn-right btn-success m-0">
									<i class="icon-check"></i>
									Guardar
								</button>
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
import Providers from './modals/Providers.vue'
import PorcentageForPrice from './modals/PorcentageForPrice.vue'
import EditArticle from './modals/EditArticle.vue'
import PrintTickets from './modals/PrintTickets.vue'
export default {
	components: {
		PorcentageForPrice,
		Providers,
		PrintTickets,
		EditArticle
	},
	props: ['rol'],
	data() {
		return {
			article: {
				is_uncontable: false,
				bar_code: '',
				name: '',
				cost: '',
				price: '',
				measurement: 'kilo',
				new_stock: 0,
				stock: '',
				provider: 0,
				act_fecha: true,
				created_at: new Date().toISOString().slice(0,10),
			},
			articles: [],
			porcentage_for_price: 0,
			articles_id_to_print: [],
			// pre_search: [],
			providers: [],
			remember_provider: true,
			remember_date: false,
			bar_codes: [],
			names: [],
			possibles_names: [],
			generated_bar_codes: [],
			previus_next: 0,
		}
	},
	created() {
		if (this.rol == 'commerce') {
			this.getProviders()
		}
		this.getBarCodes()
		this.getNames()
		this.getGeneratedBarCodes()
	},
	methods: {
		// article.measurement {
		// 	if (this.article.measurement == 'kilograms') {
		// 		return 'kilos'
		// 	} else if (this.article.measurement == 'grams') {
		// 		return 'gramos'
		// 	}
		// },
		// Cambiar
		changeToCost() {
			$('#cost').focus()
		},
		changeToPrice() {
			if (this.porcentage_for_price == 0) {
				$('#price').focus()
			} else {
				$('#created_at').focus()
			}
		},
		changeToName() {
			$('#name').focus()
		},
		changeToDate() {
			$('#created_at').focus()
		},
		changeFromDate() {
			if (this.rol == 'commerce') {
				$('#providers').focus()
			} else {
				$('#stock').focus()
			}
		},
		changeToStock() {
			$('#stock').focus()
		},
		date(date) {
			return moment(date).format('DD/MM/YY')
		},
		since(date) {
			return moment(date).fromNow()
		},

		getNames() {
			axios.get('articles/names')
			.then(res => {
				this.names = res.data
			})
			.catch(err => {
				console.log(err)
			})
		},

		showPorcentageForPrice() {
			$('#porcentage-for-price').modal('show')
		},
		stopPorcentageForPrice() {
			this.porcentage_for_price = 0
		},
		setPorcentageForPrice(porcentage) {
			this.porcentage_for_price = porcentage
			$('#porcentage-for-price').modal('hide')
		},
		calculatePorcentageForPrice() {
			if (this.porcentage_for_price != 0) {
				this.article.price = parseFloat(this.article.cost) + 
									(parseFloat(this.porcentage_for_price) / 100) * 
									parseFloat(this.article.cost)
			}
		},

		// Codigos de barra
		getBarCodes() {
			axios.get('articles/bar-codes')
			.then( res => {
				this.bar_codes = res.data
			})
			.catch( err => {
				console.log(err)
			})
		},
		getGeneratedBarCodes() {
			axios.get('bar-codes/generated')
			.then( res => {
				this.generated_bar_codes = res.data
			})
			.catch( err => {
				console.log(err)
			})
		},
		isRegister() {
			// Controla que el codigo no este registrado con otro articulo
			if (this.bar_codes.includes(this.article.bar_code)) {
				axios.get('articles/get-by-bar-code/'+this.article.bar_code)
				.then( res => {
					this.setArticle(res.data)
					$('#edit-article').modal('show')
				})
				.catch( err => {
					console.log(err)
				})
			} else {
				// Controla que el codigo haya sido creado por nosotros
				var codigo_ya_creado = false
				this.generated_bar_codes.forEach(bar_code => {
					if (bar_code.name == this.article.bar_code) {
						codigo_ya_creado = true
						toastr.success('Codigo generado por usted, se completo la cantidad')
						this.article.stock = bar_code.amount
						$('#name').focus()
					}
				})

				// Si no esta registrado y no es un codigo creado por nosotros
				// se pasa al campo del nombre
				if (!codigo_ya_creado) {
					$('#name').focus()
				}
			}
		},
		setPossibleNames() {
			if (this.article.bar_code.length == 0 && this.article.name.length >= 2) {
				this.possibles_names = []
				this.names.forEach(name => {
					var n = this.article.name.charAt(0).toUpperCase() + this.article.name.slice(1)

					if (name.toLowerCase().includes(this.article.name)) {
						this.possibles_names.push(name)
					}
				})				
			} else {
				this.possibles_names = []
			}
		},
		selectPossibleName(article_name) {
			axios.get('articles/search/'+article_name)
			.then( res => {
				console.log(res.data)
				this.setArticle(res.data[0])
				this.possibles_names = []
				$('#edit-article').modal('show')
			})
			.catch( err => {
				console.log(err)
			})
		},

		remember_date_() {
			this.remember_date = true
		},
		validate() {
			var ok = true
			console.log('validadndo')
			if (this.article.name == '') {
				ok = false
				toastr.error('El campo nombre es obligatorio')
				$('#name').focus()
			}
			if (this.article.cost == '') {
				ok = false
				toastr.error('El campo costo es obligatorio')
				$('#cost').focus()
			}
			if (this.article.price == '') {
				ok = false
				toastr.error('El campo precio es obligatorio')
				$('#price').focus()
			}
			// if (this.article.bar_code == '') {
			// 	console.log('entro')
			// 	this.names.forEach(name => {
			// 		if (name.toLowerCase() == this.article.name) {
			// 			console.log('entro tmb')
			// 			ok = false
			// 			toastr.error('Ya hay un artículo con este nombre')
			// 			$('#name').focus()
			// 		}
			// 	})
				// if (this.names.includes(this.article.name)) {
				// } else {
				// 	console.log('No entro')
				// }
			// }
			return ok
		},

		// Articles
		saveArticle() {
			var ok = this.validate()
			if ( ok ) {
				axios.post('articles', {
					article: this.article
				})
				.then( res => {
					var article = res.data
					if (this.article.bar_code != '') {
						this.bar_codes.push(this.article.bar_code)
					}
					this.names.push(this.article.name)
					this.articles.push(article)
					this.articles_id_to_print.push(article.id)
					this.clearArticle()
					toastr.success('Artículo guardado correctamente')
					$('#bar_code').focus()
				})
				.catch( err => {
					toastr.error('Error al guardar el artículo, revise sus datos e intentelo nuevamente por favor')
					console.log(err)
				})
			}
		},
		updateArticle() {
			axios.put('articles/'+this.article.id, {
				article: this.article
			})
			.then( res => {
				var article = res.data
				this.articles.push(article)
				this.articles_id_to_print.push(article.id)
				this.clearArticle()
				toastr.success('Artículo actualizado correctamente')
				$('#edit-article').modal('hide')
			})
			.catch( err => {
				toastr.error('Error al actualizar el artículo, revise sus datos e intentelo nuevamente por favor')
				console.log(err)
			})
		},
		showPrintTickets() {
			$('#print-tickets').modal('show')
		},
		toPrintTickets() {
			var link = 'imprimir-precios/'+this.articles_id_to_print.join('-')
			window.open(link)
		},
		setArticle(article) {
			this.article.creado = this.date(article.created_at) + ' ' 
									+ this.since(article.created_at)
			this.article.actualizado = this.date(article.updated_at) + ' ' 
										+ this.since(article.updated_at)
			this.article.id = article.id
			this.article.bar_code = article.bar_code
			this.article.name = article.name
			this.article.cost = article.cost
			this.article.price = article.price
			this.article.offer_price = article.offer_price
			if (this.rol == 'commerce') {
				this.article.provider = article.providers[0].id
				this.article.providers = article.providers
			}
			this.article.stock = article.stock
		},
		previus() {
			this.previus_next++
			axios.get('articles/previus-next/'+this.previus_next)
			.then( res => {
				this.setArticle(res.data)
				$('#edit-article').modal('show')
			})
			.catch( err => {
				console.log(err)
			})
		},
		next() {
			this.previus_next--
			axios.get('articles/previus-next/'+this.previus_next)
			.then( res => {
				this.setArticle(res.data)
				$('#edit-article').modal('show')
			})
			.catch( err => {
				console.log(err)
			})
		},
		clearArticle() {
			this.article.bar_code = ''
			this.article.name = ''
			this.article.cost = ''
			this.article.price = ''
			this.article.stock = ''
			this.article.new_stock = ''
			if (!this.remember_provider) {
				this.article.provider = this.providers[0].id
			}
			if (!this.remember_date) {
				this.article.created_at = new Date().toISOString().slice(0,10)
			}
			this.previus_next = 0
		},

		// Providers
		getProviders() {
			axios.get('providers')
			.then( res => {
				this.providers = res.data
				this.article.provider = this.providers[0].id
			})
			.catch( err => {
				console.log(err)
			})
		},
		showProviders() {
			$('#providers').modal('show')
		},
		deleteProvider(provider) {
			axios.delete('providers/'+provider.id)
			.then( res => {
				this.getProviders()
				// $('#providers').modal('hide')
				toastr.success('El proveedor ' + provider.name + ' se elimino correctamente')
			})
			.catch( err => {
				console.log(err)
			})
		},
		saveProvider(provider) {
			console.log('provider: ')
			console.log(provider)
			axios.get('providers/'+provider.name)
			.then( res => {
				this.getProviders()
			    console.log('provider terminado: ')
				console.log(res.data)
				setTimeout(() => {
					this.article.provider = res.data.id
				}, 1000)
				$('#providers').modal('hide')
				toastr.success('El proveedor ' + provider.name + ' se guardo correctamente')
			})
			.catch( err => {
				console.log(err)
			})
		}
	}
}
</script>
<style scoped>
.input-uncontable-price {
	width: 50%;
	display: inline-block;
}
.select-measurement {
	width: 30%;
	display: inline-block;
}
.input-uncontable-stock {
	width: 60%;
	display: inline-block;
}
</style>
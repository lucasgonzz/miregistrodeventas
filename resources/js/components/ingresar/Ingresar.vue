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
								<button @click="next" 
										v-show="previus_next > 1" 
										class="btn btn-primary m-l-5 float-right">
									<i class="icon-redo"></i>
									Siguiente
								</button>
								<button @click="previus" 
										class="btn btn-primary m-l-5 float-right">
									<i class="icon-undo"></i>
									Anterior
								</button>
								<button @click="clearArticle" 
										v-show="previus_next != 0"
										class="btn btn-warning float-right">
									<i class="icon-refresh"></i>
									Limpiar
								</button>
							</div>
						</div>
					</div>
					<!-- <form> -->
						<div class="card-body">
							<h6 class="h6">
								<strong>Complete con los datos del artículo que quiera ingresar</strong>
							</h6>
							<div class="form-group">
								<label for="barCode">Codigo de Barras</label>
								<input 
									type="text" id="barCode" name="barCode" 
									class="form-control focus-red" autofocus="true" 
									@keyup.enter="isRegister" 
									v-model="article.bar_code"
									placeholder="Codigo de barras">
							</div>
							<div class="row m-b-5">
								<div class="col">
									<label for="cost">Costo</label>
									<input type="text" 
											class="form-control focus-red" 
											placeholder="Ingrese el costo"
											id="cost"
											v-model="article.cost">
									<small class="form-text text-muted">
										Coloque un . (punto) para agregar centavos
									</small>
								</div>
								<div class="col">
									<label for="price">Precio</label>
									<input type="text" 
											class="form-control focus-red" 
											placeholder="Ingrese el precio"
											id="price"
											v-model="article.price">
									<small class="form-text text-muted">
										Coloque un . (punto) para agregar centavos
									</small>
								</div>
							</div>
							<div class="form-group">
								<label for="name">Nombre</label>
								<input type="text" 
										class="form-control focus-red" 
										placeholder="Ingrese el nombre"
										id="name"
										v-model="article.name">
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
												name="providers" id="providers" 
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
									<input type="number" 
											class="form-control focus-red" 
											id="stock"
											placeholder="Ingrese la cantidad"
											v-model="article.stock">
									<small class="form-text text-muted">
										Si deja este campo vacio no se tendra en cuenta el stock al 
										momento de hacer una venta
									</small>
								</div>
							</div>
							<div class="form-group" v-else>
								<label for="stock">Cantidad</label>
								<input type="number" 
										class="form-control focus-red" 
										id="stock"
										placeholder="Ingrese la cantidad"
										v-model="article.stock">
								<small class="form-text text-muted">
									Si deja este campo vacio no se tendra en cuenta el stock al 
									momento de hacer una venta
								</small>
							</div>
						</div>
						<div class="card-footer p-0">
							<div class="row m-0">
								<div class="col-3 p-0">
									<button class="btn btn-block btn-left btn-primary m-0">
										<i class="icon-undo"></i>
										Atras
									</button>
								</div>
								<div class="col-3 p-0">
									<button @click="clearArticle" 
											class="btn btn-block btn-center btn-danger m-0">
										<i class="icon-refresh"></i>
										Limpiar
									</button>
								</div>
								<div v-if="previus_next == 0" class="col-6 p-0">
									<button @click.prevent="saveArticle"
											class="btn btn-block btn-right btn-success m-0">
										<i class="icon-check"></i>
										Guardar
									</button>
								</div>
								<div v-else class="col-6 p-0">
									<button @click.prevent="saveArticle"
											class="btn btn-block btn-right btn-success m-0">
										<i class="icon-check"></i>
										Actualizar
									</button>
								</div>
							</div>
						</div>
					<!-- </form>	 -->
				</div>
			</div>
		</div>
	</div>
</div>
</template>
<script>
import Providers from './modals/Providers.vue'
import EditArticle from './modals/EditArticle.vue'
export default {
	components: {
		Providers,
		EditArticle
	},
	props: ['rol'],
	data() {
		return {
			article: {
				bar_code: '',
				name: '',
				cost: '',
				price: '',
				new_stock: 0,
				stock: '',
				provider: 0,
				act_fecha: true,
				created_at: new Date().toISOString().slice(0,10),
			},
			providers: [],
			remember_provider: true,
			remember_date: false,
			bar_codes: [],
			generated_bar_codes: [],
			previus_next: 0,
		}
	},
	created() {
		if (this.rol == 'commerce') {
			this.getProviders()
		}
		this.getBarCodes()
		this.getGeneratedBarCodes()
	},
	methods: {
		date(date) {
			return moment(date).format('DD/MM/YY')
		},
		since(date) {
			return moment(date).fromNow()
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
				this.generated_bar_codes.forEach(bar_code => {
					if (bar_code.name == this.article.bar_code) {
						toastr.success('Codigo generado por usted, se completo la cantidad')
						this.article.stock = bar_code.amount
						$('#cost').focus()
					}
				})
			}
		},

		remember_date_() {
			this.remember_date = true
		},

		// Articles
		saveArticle() {
			axios.post('articles', {
				article: this.article
			})
			.then( res => {
				console.log(res.data)
				this.clearArticle()
				toastr.success('Artículo guardado correctamente')
			})
			.catch( err => {
				console.log('mal')
				console.log(err)
			})
		},
		updateArticle() {
			axios.put('articles/'+this.article.id, {
				article: this.article
			})
			.then( res => {
				this.clearArticle()
				toastr.success('Artículo actualizado correctamente')
				$('#edit-article').modal('hide')
			})
			.catch( err => {
				console.log(err)
			})
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
			axios.post('providers', {
				provider
			})
			.then( res => {
				this.getProviders()
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
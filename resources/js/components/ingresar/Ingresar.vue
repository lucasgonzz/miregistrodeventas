<template>
	<div id="ingresar">
		<providers :providers="providers"
					@deleteProvider="deleteProvider" 
					@saveProvider="saveProvider"></providers>
		<edit-article
					:providers="providers"
					:rol="rol"
					:article="article"
					@clearArticle="clearArticle"
					@updateArticle="updateArticle"></edit-article>
		<div class="row justify-content-center">
			<div class="col-lg-7">	
				<div class="card">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col">
								<h5 class="m-b-0">
									<strong>Ingresar un nuevo artículo</strong>
								</h5>
							</div>
							<div class="col">
								<button class="btn btn-primary float-right">
									<i class="icon-undo"></i>
									Ultimo Ingresado
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
										v-model="article.name">
							</div>
							<div class="form-group">
								<label for="created_at">Fecha</label>
								<input type="date" 
										@change="remember_date_"
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
									<div class="form-group" v-show="rol == 'commerce'">
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
											placeholder="Ingrese la cantidad"
											v-model="article.stock">
								</div>
							</div>
							<div class="form-group" v-else>
								<label for="stock">Cantidad</label>
								<input type="number" 
										class="form-control focus-red" 
										placeholder="Ingrese la cantidad"
										v-model="article.stock">
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
								<div class="col-6 p-0">
									<button @click.prevent="saveArticle"
											class="btn btn-block btn-right btn-success m-0">
										<i class="icon-check"></i>
										Guardar
									</button>
								</div>
							</div>
						</div>
					<!-- </form>	 -->
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
		}
	},
	created() {
		if (this.rol == 'commerce') {
			this.getProviders()
		}
		this.getBarCodes()
	},
	methods: {
		date(date) {
			return moment(date).format('DD/MM/YY')
		},
		since(date) {
			return moment(date).fromNow()
		},



		getBarCodes() {
			axios.get('articles/bar-codes')
			.then( res => {
				console.log(res.data)
				this.bar_codes = res.data
			})
			.catch( err => {
				console.log(err)
			})
		},
		isRegister() {
			if (this.bar_codes.includes(this.article.bar_code)) {
				axios.get('articles/get-by-bar-code/'+this.article.bar_code)
				.then( res => {
					console.log(res.data)
					var article = res.data
					this.article.creado = this.date(article.created_at) + ' ' 
											+ this.since(article.created_at)
					this.article.actualizado = this.date(article.updated_at) + ' ' 
												+ this.since(article.updated_at)
					this.article.id = article.id
					this.article.bar_code = article.bar_code
					this.article.name = article.name
					this.article.cost = article.cost
					this.article.price = article.price
					this.article.providers = article.providers
					this.article.stock = article.stock
					console.log(article.providers)
					$('#edit-article').modal('show')
				})
				.catch( err => {
					console.log(err)
				})
			}
		},

		remember_date_() {
			this.remember_date = true
		},

		// Articles
		saveArticle() {
			// if (this.article.bar_code && this.article.name 
				// && this.article.cost && this.article.price && this.article.stock) {
				axios.post('articles', {
					article: this.article
				})
				.then( res => {
					this.article.bar_code = ''
					this.article.name = ''
					this.article.cost = ''
					this.article.price = ''
					this.article.stock = ''
					if (!this.remember_provider) {
						this.article.provider = []
					}
					if (!this.remember_date) {
						this.article.created_at = new Date().toISOString().slice(0,10)
					}
					toastr.success('Artículo guardado correctamente')
				})
				.catch( err => {
					console.log(err)
				})
			// }
		},
		updateArticle() {
			axios.put('articles/'+this.article.id, {
				article: this.article
			})
			.then( res => {
				this.article.bar_code = ''
				this.article.name = ''
				this.article.cost = ''
				this.article.price = ''
				this.article.stock = ''
				this.article.new_stock = 0
				this.article.provider = this.providers[0].id
				this.article.providers = []
				this.article.created_at = new Date().toISOString().slice(0,10)
				toastr.success('Artículo guardado correctamente')
				$('#edit-article').modal('hide')
				console.log(res.data)
			})
			.catch( err => {
				console.log(err)
			})
		},
		clearArticle() {
			this.article = {
								bar_code: '',
								name: '',
								cost: '',
								price: '',
								new_stock: 0,
								stock: '',
								provider: 0,
								act_fecha: true,
								created_at: new Date().toISOString().slice(0,10),
							}
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
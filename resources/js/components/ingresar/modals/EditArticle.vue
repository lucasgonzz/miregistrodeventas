<template>
<div class="modal fade" id="edit-article" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<strong v-show="previusNext == 0">
					Ya hay un <strong>{{ article.name }}</strong> ingresado
				</strong>
				<strong v-show="previusNext != 0">
					<strong>{{ article.name }}</strong>
				</strong>
				<button @click="clearArticle" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group m-b-10">
					<button v-show="previusNext > 0"
							@click="previus"
							class="btn btn-primary m-r-5">
						<i class="icon-undo"></i>
						Anterior
					</button>
					<button v-show="previusNext > 1"
							@click="next"
							class="btn btn-primary m-r-5">
						<i class="icon-redo"></i>
						Siguiente
					</button>
				</div>
				<div class="form-group">
					<div class="input-group mb-2 mr-sm-2">
						<div class="input-group-prepend">
						  	<div class="input-group-text"><i class="icon-barcode"></i></div>
						</div>
						<input v-if="article.bar_code"
								type="text" 
								name="bar-code" 
								v-model="article.bar_code" 
								class="form-control" disabled>
						<input v-else
								type="text" 
								v-model="article.bar_code" 
								name="bar-code" 
								:placeholder="'Ingrese un codigo de barras para '+article.name" 
								class="form-control">
					</div>
					<a v-show="!article.bar_code"
						href="#" 
						@click.prevent="createBarCode"
						class="btn btn-link">
						¿No tiene codigo de barras? Crea uno aca
					</a>
				</div>
				<div class="form-group">
					<label for="cost">Agregado</label>
					<input type="text" name="cost" v-model="article.creado" class="form-control" disabled>
				</div>
				<div class="form-group">
					<label for="cost">Actualizado</label>
					<input type="text" name="cost" v-model="article.actualizado" class="form-control" disabled>
				</div>
				<div class="form-group">
					<label for="cost">Costo</label>
					<input type="number" name="cost" v-model="article.cost" id="costo" class="form-control focus-red">
					<small class="form-text text-muted">
						Para agregar decimales (centavos) coloque un punto para separar las unidades	
					</small>
				</div>

				<div class="form-group">
					<label for="price">Precio</label>
					<input type="number" name="price" v-model="article.price" class="form-control focus-red">
					<small class="form-text text-muted">
						Para agregar decimales (centavos) coloque un punto para separar las unidades	
					</small>
				</div>
				<div class="form-group" v-show="article.offer_price">
					<label for="price">Precio de oferta</label>
					<input type="number" name="price" v-model="article.offer_price" class="form-control focus-red">
					<small class="form-text text-muted">
						Para agregar decimales (centavos) coloque una coma para separar las unidades	
					</small>
				</div>
				<div class="form-group" v-show="article.stock">
					<label for="stock">Cantidad para agregar</label>
					<input type="number" 
							min="0"
							v-model="article.new_stock" 
							class="form-control focus-red">
					<small>
						Actualmente hay {{ article.stock }}
					</small>
				</div>
				<div class="form-group" v-show="rol == 'commerce' && article.new_stock > 0">
					<label class="label-block" for="provider">
						De que proveedor son estas {{ article.new_stock }} unidades
					</label>
					<select v-model="article.provider"
							id="provider" 
							class="form-control">
						<option v-for="provider in providers" :value="provider.id">
							{{ provider.name }}
						</option>
					</select>
					<ul class="list-group m-t-5">
						<li class="list-group-item active p-t-5 p-b-5">Otros provedores</li>
						<li v-for="provider in article.providers"
							class="list-group-item p-t-5 p-b-5">
							<p class="m-t-0 m-b-0">
								<strong>
									{{ provider.name }}
								</strong> se le compraron {{ provider.pivot.amount }}
								<span class="float-right">
									costo: ${{ provider.pivot.cost }}
								</span>
							</p>
							<p class="m-t-0 m-b-0">
								 el {{ date(provider.pivot.created_at) }} hace {{ since(provider.pivot.created_at) }}
								<span class="float-right">
								 	precio: ${{ provider.pivot.price }}
								</span>
							</p>
						</li>
					</ul>
				</div>
				<div class="form-group">
					<label for="name">Nombre</label>
					<input type="text" name="name" v-model="article.name" class="form-control focus-red">
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox my-1 mr-sm-2 m-b-10">
						<input v-model="article.act_fecha" 
								type="checkbox" 
								class="custom-control-input" 
								id="act_fecha">
						<label class="custom-control-label c-p" 
								for="act_fecha">
							Actualizar fecha
						</label>
						<small id="nameHelp" 
								class="form-text text-muted">
							Si desmarca esta casilla no se guardara la fecha de actualización	
						</small>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button @click="clearArticle" type="button" class="btn btn-secondary focus-red" data-dismiss="modal">Cancelar</button>
				<button type="button" 
						class="btn btn-primary focus-red" 
						@click="updateArticle">
					Actualizar
				</button>
			</div>
		</div>
	</div>
</div>

</template>
<script>
export default {
	props: ['article', 'rol', 'providers', 'previusNext'],	
	methods: {
		date(date) {
			return moment(date).format('DD/MM/YY')
		},
		since(date) {
			return moment(date).fromNow()
		},
		clearArticle() {
			this.$emit('clearArticle')
		},
		previus() {
			this.$emit('previus')
		},
		next() {
			this.$emit('next')
		},
		updateArticle() {
			this.$emit('updateArticle')
		},
		isChecked(provider_id) {
			this.article.providers.forEach( p => {
				if (provider_id == p.id) {
					console.log(`${p.name} si`)
					return true
				} else {
					console.log('no')
					return false
				}
			})
		},
		createBarCode() {
			window.open('codigos-de-barra')
		},
	}
}
</script>
<template>
<div class="modal fade" id="edit-article" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					Ya hay un <strong>{{ article.name }}</strong> ingresado
				</h5>
				<button @click="clearArticle" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
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
					<input type="text" name="cost" v-model="article.cost" id="costo" class="form-control focus-red">
					<small class="form-text text-muted">
						Para agregar decimales (centavos) coloque un punto para separar las unidades	
					</small>
				</div>

				<div class="form-group">
					<label for="price">Precio</label>
					<input type="text" name="price" v-model="article.price" class="form-control focus-red">
					<small class="form-text text-muted">
						Para agregar decimales (centavos) coloque un punto para separar las unidades	
					</small>
				</div>
				<div class="form-group" v-show="rol == 'commerce'">
					<label class="label-block" for="provider">Proveedores</label>
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
					<label for="stock">Cantidad para agregar</label>
					<input type="number" 
							v-model="article.new_stock" 
							class="form-control focus-red">
					<small>
						Actualmente hay {{ article.stock }}
					</small>
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
				<!-- {{article}} -->
			</div>
			<div class="modal-footer">
				<button @click="clearArticle" type="button" class="btn btn-secondary focus-red" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary focus-red" v-on:click="updateArticle">Actualizar</button>
			</div>
		</div>
	</div>
</div>

</template>
<script>
export default {
	props: ['article', 'rol', 'providers'],	
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
		}
	}
}
</script>
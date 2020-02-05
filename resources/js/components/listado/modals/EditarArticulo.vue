<template>
<div class="modal fade" id="listado-edit-article" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Editar <strong>{{ article.name }}</strong> | <i class="icon-barcode"></i> {{ article.bar_code }}</h5>
				<button @click="clearArticle"
						type="button" 
						class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
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
								v-model="article.new_bar_code" 
								name="bar-code"
								autocomplete="off" 
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
				<div class="form-group" v-show="can_see_cost">
					<label for="cost">Costo</label>
					<input type="number" 
							name="cost" 
							v-model="article.cost" 
							id="costo" 
							autocomplete="off" 
							class="form-control focus-red">
					<small class="form-text text-muted">
						Para agregar decimales (centavos) coloque una coma para separar las unidades	
					</small>
				</div>
				<div class="form-group">
					<label for="price">Precio</label>
					<input type="number" 
							name="price" 
							v-model="article.price" 
							autocomplete="off" 
							class="form-control focus-red">
					<small class="form-text text-muted">
						Para agregar decimales (centavos) coloque una coma para separar las unidades	
					</small>
				</div>
				<div class="form-group" v-show="article.offer_price">
					<label for="price">Precio de oferta</label>
					<input type="number" 
							name="price" 
							v-model="article.offer_price" 
							autocomplete="off" 
							class="form-control focus-red">
					<small class="form-text text-muted">
						Para agregar decimales (centavos) coloque una coma para separar las unidades
					</small>
				</div>
				<div class="form-group" v-show="article.previus_price">
					<label for="cost">Precio Anterior</label>
					<input type="number" name="cost" v-model="article.previus_price" class="form-control" disabled>
				</div>
				<div class="form-group">
					<label for="stock">
						Cantidad para agregar
						<span v-show="article.uncontable == 1"> 
							en {{ article.measurement }}(s)
						</span>
					</label>
					<input type="number" 
							min="0"
							autocomplete="off" 
							v-model="article.new_stock" 
							class="form-control focus-red">
					<small>
						Actualmente hay {{ article.stock }} 
						<span v-show="article.uncontable == 1"> 
							{{ article.measurement }}(s)
						</span>
					</small>
				</div>
				<div class="form-group" v-show="rol == 'commerce' && article.new_stock > 0">
					<label class="label-block" for="provider_modal">
						<span v-show="article.new_stock == 1">
							De que proveedor es esta unidad
						</span>
						<span v-show="article.new_stock > 1">
							De que proveedor son estas {{ article.new_stock }} unidades
						</span>
					</label>
					<select v-model="article.provider"
							id="provider_modal" 
							class="form-control">
						<option v-for="provider in providers" 
								:id="'provider_'+provider.id"
								:value="provider.id">
							{{ provider.name }}
						</option>
					</select>
					<ul class="list-group m-t-5">
						<li class="list-group-item active p-t-5 p-b-5">Otros provedores</li>
						<div class="list-listado">
							<li v-for="provider in article.providers"
								class="list-group-item p-t-5 p-b-5">
								<p class="m-t-0 m-b-0">
									<strong>
										{{ provider.name }}
									</strong> 
									<span v-show="provider.pivot.amount">
										se le compraron {{ provider.pivot.amount }}
									</span>
									<span class="float-right" v-show="provider.pivot.cost">
										costo: ${{ provider.pivot.cost }}
									</span>
								</p>
								<p class="m-t-0 m-b-0">
									<span v-show="provider.pivot.created_at">
										el {{ date(provider.pivot.created_at) }} hace {{ since(provider.pivot.created_at) }}
									</span>
									<span class="float-right" v-show="provider.pivot.price">
									 	precio: ${{ provider.pivot.price }}
									</span>
								</p>
							</li>
						</div>
					</ul>
				</div>
				<div class="form-group">
					<label for="name">Nombre</label>
					<input type="text" 
							name="name" 
							v-model="article.name"
							autocomplete="off" 
							class="form-control focus-red">
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
				<button @click="clearArticle"
						type="button" 
						class="btn btn-secondary focus-red" 
						data-dismiss="modal">
					Cancelar
				</button>
				<button type="button" class="btn btn-primary focus-red" v-on:click="updateArticle">Actualizar</button>
			</div>
		</div>
	</div>
</div>
</template>
<script>
export default {
	props: ['article', 'rol', 'providers', 'can_see_cost'],	
	methods: {
		date(date) {
			return moment(date).format('DD/MM/YY')
		},
		since(date) {
			return moment(date).fromNow()
		},
		updateArticle() {
			this.$emit('updateArticle', this.article)
		},
		clearArticle() {
			this.$emit('clearArticle')
		}
	}
}
</script>
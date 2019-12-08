<template>
<div>
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
		<div class="col">
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
		<div class="col">
			<label for="price">Precio</label>
			<input type="number" 
					class="form-control focus-red" 
					placeholder="Ingrese el precio"
					required 
					id="price"
					@keyup.enter="changeToDate"
					v-model="article.price">
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
			<input type="number" 
					class="form-control focus-red" 
					id="stock"
					@keyup.enter="saveArticle"
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
				@keyup.enter="saveArticle" 
				id="stock"
				placeholder="Ingrese la cantidad"
				v-model="article.stock">
		<small class="form-text text-muted">
			Si deja este campo vacio no se tendra en cuenta el stock al 
			momento de hacer una venta
		</small>
	</div>
</div>
</template>
<script>
export default {
	props: ['article'],
	data() {
		return {

		}
	},
	methods: {
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
		
	},
}
</script>
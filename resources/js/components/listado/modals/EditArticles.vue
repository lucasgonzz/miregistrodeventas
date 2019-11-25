<template>
<div class="modal fade" id="edit-articles" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					Editar los {{ selected_articles.length }} seleccionados
				</h5>
				<button class="close" data-dismiss="modal" aria-label="Close">
					<i class="icon-cancel"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<label for="porcentage-cost" class="col-4 col-form-label text-right">Costo</label>
					<div class="col-8">
						<div class="input-group mb-2 mr-sm-2">
							<div class="input-group-prepend">
							  	<div class="input-group-text">%</div>
							</div>
							<input type="number" id="porcentage-cost" 
								v-model="porcentage_cost"
								class="form-control"
								min="0"
								placeholder="Porcentaje para aumentar los costos">
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label for="porcentage-price" class="col-4 col-form-label text-right">Precio</label>
					<div class="col-8">
						<div class="input-group mb-2 mr-sm-2">
							<div class="input-group-prepend">
							  	<div class="input-group-text">%</div>
							</div>
							<input type="number" id="porcentage-price" 
								v-model="porcentage_price"
								min="0"
								placeholder="Porcentaje para aumentar los precios" 
								class="form-control">
						</div>
					</div>
				</div>
				<!-- <div class="form-group row">
					<label for="" class="col-4 col-form-label text-right">Redondear para</label>
					<div class="col-8">
		                <div class="custom-control custom-radio">
		                    <input type="radio" v-model="round" 
		                            value="up" name="round" 
		                            id="up" class="custom-control-input">
		                    <label class="custom-control-label" for="up">
		                      	Arriba
		                    </label>
		                </div>
		                <div class="custom-control custom-radio">
		                    <input type="radio" v-model="round" 
		                            value="down" name="round" 
		                            id="down" class="custom-control-input">
		                    <label class="custom-control-label" for="down">
		                      	Abajo
		                    </label>
		                </div>
		                <div class="custom-control custom-radio">
		                    <input type="radio" v-model="round" 
		                            value="none" name="round" 
		                            id="none" class="custom-control-input">
		                    <label class="custom-control-label" for="none">
		                      	No redondear
		                    </label>
		                </div>
					</div>
				</div> -->
				<div class="form-group row">
					<label for="decimals" class="col-4 col-form-label text-right"></label>
					<div class="col-8">
		                <div class="custom-control custom-checkbox">
		                    <input type="checkbox" v-model="decimals" class="custom-control-input" id="decimals">
		                    <label class="custom-control-label" for="decimals">Dejar decimales</label>
		                </div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal">
					Cerrar
				</button>
				<button @click="update"
						class="btn btn-primary">
					Actualizar
				</button>
			</div>
		</div>
	</div>
</div>
</template>
<script>
export default {
	props: ['selected_articles'],
	data() {
		return {
			porcentage_cost: '',
			porcentage_price: '',
			// round: 'up',
			decimals: false,
		}
	},
	methods: {
		update() {
			this.$emit('updateByPorcentage', 
						Number(this.porcentage_cost), 
						Number(this.porcentage_price),
						this.round,
						this.decimals,
						)
			this.porcentage_cost = ''
			this.porcentage_price = ''
		}
	}
}
</script>
<style scoped>
.form-inline {
	/*justify-content: center;*/
}
.inline {
	display: inline-block;
	width: 30%;
}
</style>
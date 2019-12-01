<template>
<div class="modal fade" id="update-by-porcentage" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					Aumentar los {{ selected_articles.selected_articles.length }} artículos seleccionados
				</h5>
				<button class="close" data-dismiss="modal" aria-label="Close">
					<i class="icon-cancel"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="porcentage-cost" class="col-4">Costo</label>
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
						<div class="form-group">
							<label for="porcentage-price" class="col-4">Precio</label>
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
						<div class="form-group">
							<label for="decimals" class="col-4"></label>
			                <div class="custom-control custom-checkbox">
			                    <input type="checkbox" 
			                    		v-model="decimals" 
			                    		true-value="1"
			                    		false-value="0"
			                    		class="custom-control-input" 
			                    		id="decimals">
			                    <label class="custom-control-label" for="decimals">Dejar decimales</label>
			                </div>
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
			decimals: 0,
		}
	},
	methods: {
		update() {
			this.$emit('updateByPorcentage', 
						Number(this.porcentage_cost), 
						Number(this.porcentage_price),
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
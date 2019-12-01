<template>
<div class="modal fade" id="porcentage-for-price" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					Usar un porcentaje fijo para los precios
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-inline">
					<div class="input-group mb-2 mr-sm-2">
						<div class="input-group-prepend">
						  	<div class="input-group-text">Porcentaje para los precios</div>
						</div>
						<input type="number" 
								@keyup.enter="run"
								name="porcentage_for_price" 
								placeholder="Ej: un 30%" 
								min="0"
								v-model="porcentage_for_price" 
								class="form-control">
					</div>
					<button @click="run"
							class="btn btn-primary mb-2">
						<i class="icon-check"></i>
						Listo
					</button>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary focus-red" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

</template>
<script>
export default {
	props: ['providers'],
	data() {
		return {
			porcentage_for_price: '',
		}
	},
	methods: {
		run() {
			this.$emit('setPorcentageForPrice', parseFloat(this.porcentage_for_price))
		},
	}
}
</script>
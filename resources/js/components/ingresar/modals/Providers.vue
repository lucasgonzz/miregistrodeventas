<template>
<div class="modal fade" id="providers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Proveedores</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<div class="card">
							<div class="card-header">
								Agregar un nuevo proveedor
							</div>
							<div class="card-body">
								<div class="form-group">
									<label for="cost">Nombre del nuevo proveedor</label>
									<input class="form-control"
											type="text" name="cost" 
											@keyup.enter="saveProvider"
											placeholder="Nombre del nuevo proveedor" 
											v-model="provider.name">
								</div>
							</div>
							<div class="card-footer">
								<button type="button" 
										class="btn btn-primary focus-red" 
										@click="saveProvider">
									Guardar proveedor
								</button>
							</div>
						</div>
					</div>
				</div>
				<div class="row m-t-10">
					<div class="col">
						<div class="card">
							<div class="card-header">
								Mis proveedores
							</div>
							<div class="card-body">
								<ul class="list-group">
									<li v-for="provider in providers" class="list-group-item">
										{{ provider.name }}
										<button @click="deleteProvider(provider)"
												class="btn btn-danger btn-sm float-right">
											Eliminar
										</button>
									</li>
								</ul>
							</div>
						</div>
					</div>
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
			provider: {name: ''},
		}
	},
	methods: {
		deleteProvider(provider) {
			this.$emit('deleteProvider', provider)
		},
		saveProvider() {
			if (this.provider.name != '') {
				this.$emit('saveProvider', this.provider)
			} else {
				toastr.error('Ingrese un nombre para el proveedor')
			}
		}
	}
}
</script>
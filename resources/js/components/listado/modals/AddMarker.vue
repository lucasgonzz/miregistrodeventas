<template>
<div class="modal fade" id="add-marker" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					Crear un nuevo marcardor de {{ article.name }}
				</h5>
				<button class="close" data-dismiss="modal" aria-label="Close">
					<i class="icon-cancel"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<div class="card">
							<div class="card-header">
								Grupos de marcadores
							</div>
							<div class="card-body">
								<ul class="list-group" v-if="marker_groups.length">
									<li class="list-group-item" 
										v-for="marker_group in marker_groups">
										{{ marker_group.name }}
										({{ marker_group.markers.length }} artículos)
										<span class="float-right">
											<button @click="deleteMarkerGroup(marker_group)"
													class="btn btn-danger btn-sm">
												Eliminar grupo de marcadores
											</button>
											<button @click="addMarkerToGroup(marker_group)"
													class="btn btn-primary btn-sm">
												Agregar a este grupo
											</button>
										</span>
									</li>
								</ul>
								<span v-else>
									Todavía no hay ningún grupo de marcadores 
								</span>
							</div>
							<div class="card-footer">
								<div class="form-inline">
									<input type="text" 
											placeholder="Nuevo grupo de marcadores" 
											v-model="marker_group.name"
											@keyup.enter="saveMarkerGroup"
											class="form-control mb-2 mr-sm-2 input-inline">
									<button class="btn btn-success mb-2"
											@click="saveMarkerGroup">
										Crear grupo de marcadores
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal">
					Cerrar
				</button>
				<button @click="addMarker"
						class="btn btn-primary">
					Agregar a marcadores
				</button>
			</div>
		</div>
	</div>
</div>
</template>
<script>
export default {
	props: ['article', 'marker_groups'],
	data() {
		return {
			marker_group: {
				name: '',
			},
		}
	},
	methods: {
		saveMarkerGroup() {
			axios.post('marker-groups', {
				marker_group: this.marker_group
			})
			.then(res => {
				this.getMarkerGroups()
				toastr.success('Grupo de marcadores creado correctamente')
				this.marker_group.name = ''
			})
			.catch(err => {
				console.log(err)
			})
		},
		deleteMarkerGroup(marker_group) {
			this.$emit('deleteMarkerGroup', marker_group.id)
		},
		addMarkerToGroup(marker_group) {
			this.$emit('addMarkerToGroup', marker_group.id, this.article.id)
		},
		addMarker() {
			axios.post('markers', {
				article_id: this.article.id
			})
			.then(res => {
				$('#add-marker').modal('hide')
				this.$emit('updateArticlesList')
				toastr.success('Marcador creado correctamente')
			})
			.catch(err => {
				console.log(err)
			})
		},
		getMarkerGroups() {
			axios.get('marker-groups')
			.then(res => {
				this.marker_groups = res.data
			})
			.catch(err => {
				console.log(err)
			})
		},
	},
}
</script>
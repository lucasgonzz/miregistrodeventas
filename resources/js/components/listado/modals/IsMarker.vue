<template>
<div class="modal fade" id="is-marker" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					{{ article.name }} ya es un marcador
				</h5>
				<button class="close" data-dismiss="modal" aria-label="Close">
					<i class="icon-cancel"></i>
				</button>
			</div>
			<div class="modal-body">
				{{ article.name }} ya esta en la lista de marcadores, quiere eliminarlo?
				<!-- <template v-if="article.marker">
					<template v-if="article.marker.marker_group_id">
						Es un marcador del grupode {{ article.marker }}
					</template>
					<template v-else>
						Es solo un marcador
					</template>
				</template> -->
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal">
					Cerrar
				</button>
				<button @click="deleteMarker"
						class="btn btn-danger">
					Eliminar de marcadores
				</button>
			</div>
		</div>
	</div>
</div>
</template>
<script>
export default {
	props: ['article'],
	methods: {
		deleteMarker() {
			this.$emit('deleteMarker', this.article.marker.id)
		},
	},
}
</script>
<template>
<div class="modal fade" id="delete-articles" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Eliminar {{ selected_articles.selected_articles.length }} artículos</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<h5>
					¿Seguro que quiere eliminar estos {{ selected_articles.selected_articles.length }} artículos?
				</h5>
				Se eliminarán y no podrá volver a restaurarlos 
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary focus-red" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-danger focus-red" @click="deleteArticles">Eliminar</button>
			</div>
		</div>
	</div>
</div>

</template>
<script>
export default {
	props: ['selected_articles'],	
	methods: {
		deleteArticles() {
			this.$emit('deleteArticles')
		}
	}
}
</script>	
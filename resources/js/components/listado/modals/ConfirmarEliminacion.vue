<template>
<div class="modal fade" id="listado-delete-article" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">¿Seguro que quiere eliminar {{ article.name }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				Se eliminará y no podrá volver a restaurarlo 
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary focus-red" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary focus-red" v-on:click="destroyArticle">Eliminar</button>
			</div>
		</div>
	</div>
</div>

</template>
<script>
export default {
	props: ['article'],	
	methods: {
		destroyArticle() {
			this.$emit('destroyArticle', this.article.id)
		}
	}
}
</script>
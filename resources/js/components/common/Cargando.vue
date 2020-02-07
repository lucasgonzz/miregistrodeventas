<template>
	<div class="row" v-show="is_loading">
		<div class="col">
			<div class="spinner">
				<div class="spinner-border text-primary" role="status">
					<span class="sr-only">Cargando...</span>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	props: ['is_loading'],
}
</script>
<style scoped>
.col {
	padding: 100px;
}	
</style>

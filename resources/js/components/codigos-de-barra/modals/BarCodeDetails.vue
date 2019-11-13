<template>
<div class="modal fade" id="bar-code-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<span v-show="!repeatedBarCode">
						{{ barCode.name }}
					</span>
					<span v-show="repeatedBarCode">
						El codigo {{ barCode.name }} ya fue generado
					</span>
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<ul class="list-group">
					<li class="list-group-item">
						Generado el {{ date(barCode.created_at) }}
						<span class="float-right">
							{{ since(barCode.created_at) }}
						</span>
					</li>
					<li v-if="barCode.article" class="list-group-item">
						Usado con el artículo {{ barCode.article.name }}
					</li>
					<li class="list-group-item">
						Impreso {{ barCode.amount }} veces
					</li>
				</ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary focus-red" data-dismiss="modal">
					Cancelar
				</button>
				<button @click="deleteBarCode"
						type="button" 
						class="btn btn-danger">
					Eliminar
				</button>
			</div>
		</div>
	</div>
</div>
</template>
<script>
export default {
	props: ['barCode', 'repeatedBarCode'],
	methods: {
		date(date) {
			return moment(date).format('DD/MM/YY')
		},
		since(date) {
			return moment(date).fromNow()
		},
		deleteBarCode() {
			this.$emit('deleteBarCode')
		}
	},
}
</script>
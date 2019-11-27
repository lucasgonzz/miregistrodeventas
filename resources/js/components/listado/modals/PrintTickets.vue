<template>
<div class="modal fade" id="print-tickets" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					Imprimir tickets
				</h5>
			</div>
			<div class="modal-body">
				<h5>
					Se van a imprimir {{ selected_articles.selected_articles.length }} tickets
				</h5>
                <div class="custom-control custom-checkbox my-1 mr-sm-2">
                  	<input class="custom-control-input" 
                  			v-model="show_company_name" 
                            true-value="1"
                            false-value="0"
                  			type="checkbox" 
                  			id="show_company_name">
                  	<label class="custom-control-label" 
                  			for="show_company_name">
                  			Mostrar nombre del negocio
                  	</label>
                </div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary Close" data-dismiss="modal">Cerrar</button>
				<button class="btn btn-primary" @click="printTickets">Imprimir tickets</button>
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
			show_company_name: 1,
		}
	},
	methods: {
		printTickets() {
			var link = 'imprimir-precios/'
						+this.selected_articles.selected_articles.join('-')+'/'
						+this.show_company_name
			window.open(link)

		}
	}
}
</script>
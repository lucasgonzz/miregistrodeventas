<template>
<div class="modal fade" id="print-tickets" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					Imprimir tickets de los siguientes artículos
				</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<table class="table">
							<thead class="thead-dark">
								<tr>
									<th scope="col">Articulo</th>
									<th scope="col">Eliminar</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="article in articles">
									<td>{{ article.name }}</td>
									<td>
										<button @click="deleteArticle(article)"
												class="btn btn-danger">
											<i class="icon-trash-3"></i>
										</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col">
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
	props: ['articles', 'articles_id'],
	data() {
		return {
			show_company_name: 1,
		}
	},
	methods: {
		printTickets() {
			var link = 'imprimir-precios/'
						+this.articles_id.join('-')+'/'
						+this.show_company_name
			window.open(link)
		},
		deleteArticle(article) {
			var i = this.articles.indexOf(article)
			this.articles.splice(i, 1)
			this.articles_id.splice(i, 1)
		}
	}
}
</script>
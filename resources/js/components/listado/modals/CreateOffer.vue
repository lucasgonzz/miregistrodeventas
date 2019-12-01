<template>
<div class="modal fade" id="create-offer" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					Crear una oferta para los {{ selected_articles.selected_articles.length }} artículos seleccionados
				</h5>
				<button class="close" data-dismiss="modal" aria-label="Close">
					<i class="icon-cancel"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<div class="form-group">
							<div class="input-group mb-2 mr-sm-2">
								<div class="input-group-prepend">
									<div class="input-group-text">Porcentaje para la oferta</div>
								</div>
								<input type="number" 
										v-model="procentage_offer"
										min="0"
										max="99"
										class="form-control" 
										id="porcentage_price_offer" 
										placeholder="Porcentaje de la oferta">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="table-responsive">
							<table class="table table-striped">
								<thead class="thead-dark">
									<tr>
										<th scope="col">Artículo</th>
										<th scope="col">Precio</th>
										<th scope="col">Precio de oferta</th>
										<th scope="col">Eliminar</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="article in selected_articles.articles">
										<td>{{ article.name }}</td>
										<td>{{ article.price }}</td>
										<td>
											<input type="number" 
													v-model="article.offer_price"
													class="form-control input-offer-price">
										</td>
										<td>
											<i class="icon-trash-2 text-danger icon-delete c-p"
												@click="deleteArticle(article)"></i>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal">
					Cerrar
				</button>
				<button @click="createOffer"
						class="btn btn-primary">
					Crear Oferta
				</button>
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
			procentage_offer: 1,
			articles: [],
		}
	},
	watch: {
		procentage_offer() {
			this.selected_articles.articles.forEach(article => {
				article.offer_price = parseFloat(article.price) - (this.procentage_offer / 100) * parseFloat(article.price)
			})
		}
	},
	methods: {
		createOffer() {
			this.$emit('createOffer')
		},
		deleteArticle(article) {
			var index_article = this.selected_articles.articles.indexOf(article)
			this.selected_articles.articles.splice(index_article, 1)
			var index_article_id = this.selected_articles.selected_articles.indexOf(article.id)
			this.selected_articles.selected_articles.splice(index_article_id, 1)
		}
	}
}
</script>
<style scoped>
.modal-lg {
	width: 50%;
}
.form-group {
	width: 40%;
}
.inline {
	display: inline-block;
	width: 30%;
}
.input-offer-price {
	width: 100px;
}
.icon-delete {
	font-size: 1.4rem;
}
</style>
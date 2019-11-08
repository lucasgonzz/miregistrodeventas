<div class="modal fade" id="listado-edit-article" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Editar <strong>@{{ article.name }}</strong> | <i class="icon-barcode"></i> @{{ article.bar_code }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<!-- as@{{article.creado}} -->
					<label for="cost">Agregado</label>
					<input type="text" name="cost" v-model="article.creado" class="form-control" disabled>
				</div>
				<div class="form-group">
					<label for="cost">Actualizado</label>
					<input type="text" name="cost" v-model="article.actualizado" class="form-control" disabled>
				</div>
				<div class="form-group">
					<label for="cost">Costo</label>
					<input type="text" name="cost" v-model="article.cost" id="costo" class="form-control focus-red">
				</div>
				<div class="form-group">
					<label for="price">Precio</label>
					<input type="text" name="price" v-model="article.price" class="form-control focus-red">
				</div>
				<div class="form-group">
					<label for="name">Nombre</label>
					<input type="text" name="name" v-model="article.name" class="form-control focus-red">
				</div>
				<div class="form-group">
					<label for="stock">Cantidad</label>
					<input type="number" v-model="article.stock" name="stock" class="form-control focus-red">
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox my-1 mr-sm-2 m-b-10">
						<input v-model="article.act_fecha" 
								type="checkbox" 
								class="custom-control-input" 
								id="act_fecha">
						<label class="custom-control-label c-p" 
								for="act_fecha">
							Actualizar fecha
						</label>
						<small id="nameHelp" 
								class="form-text text-muted">
							Si desmarca esta casilla no se guardara la fecha de actualización	
						</small>
					</div>
				</div>
				<!-- @{{article}} -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary focus-red" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary focus-red" v-on:click="updateArticle">Actualizar</button>
			</div>
		</div>
	</div>
</div>

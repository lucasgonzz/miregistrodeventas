@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
	<div class="col-lg-7">	
		<div class="card">
			<div class="card-header">
				<strong>Ingresar un nuevo artículo</strong>
			</div>
			<form>
				<div class="card-body">
					<h5 class="card-title">Complete con los datos del artículo que quiera ingresar</h5>
					{{ csrf_field() }}
					<div class="form-group">
						<label for="barCode">Codigo de Barras</label>
						<input type="text" id="barCode" name="barCode" class="form-control focus-red" autofocus="true" @keyup.enter="isRegister" placeholder="Codigo de barras">
					</div>
					<div class="row m-b-5">
						<div class="col">
							<label for="cost">Costo</label>
							<input type="text" class="form-control focus-red" placeholder="Ingrese el costo">
						</div>
						<div class="col">
							<label for="price">Precio</label>
							<input type="text" class="form-control focus-red" placeholder="Ingrese el precio">
						</div>
					</div>
					<div class="form-group">
						<label for="name">Nombre</label>
						<input type="text" class="form-control focus-red" placeholder="Ingrese el nombre">
					</div>
					<div class="form-group">
						<label for="created_at">Fecha</label>
						<input type="date" class="form-control focus-red">
					</div>
					<div class="row m-b-15">
						<div class="col">
							<label for="mayorista">Proveedores</label>
							<select name="" id="" class="form-control" multiple>
								<option value="">1</option>
								<option value="">1</option>
								<option value="">1</option>
								<option value="">1</option>
							</select>
							<!-- <select v-model="providers_selected" class="form-control" focus-red" multiple>
								<option v-for="provider in providers" :value="provider.id">@{{ provider.name }}</option>
							</select> -->
							<a href="#" @click.prevent="addProvider">Añadir proovedor</a>
						</div>
						<div class="col">
							<label for="stock">Cantidad</label>
							<input type="number" class="form-control focus-red" placeholder="Ingrese la cantidad">
						</div>
					</div>
				</div>
				<div class="card-footer text-muted">
					&nbsp;
					<button class="btn btn-primary m-r-5">
						<i class="icon-check"></i>
						Guardar
					</button>
					<button class="btn btn-danger">
						<i class="icon-cancel"></i>
						Limpiar
					</button>
				</div>
			</form>	
		</div>
	</div>
</div>
@endsection
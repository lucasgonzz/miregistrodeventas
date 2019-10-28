@extends('layouts.app')
@section('content')
<div class="row justify-content-center">	
	<div class="col-lg-7">	
		<div class="card">
			<div class="card-header">
				<form>
					<div class="form-row">	
						<div class="col-10">	
							<div class="input-group mb-2 mr-sm-2">
								<div class="input-group-prepend">
								  <div class="input-group-text">Codigo de barras</div>
								</div>
								<input type="text" class="form-control" placeholder="Ingrese el codigo de barras">
							</div>
						</div>
						<div class="col-2">	
							<button type="submit" class="btn btn-primary mb-2">Vender</button>
						</div>
					</div>
				</form>	
			</div>
			<div class="card-body">
				<h5 class="card-title">Total: $400</h5>
				<p class="card-text">
					5 artículos, 10 unidades
				</p>
				<table class="table text-center table-striped">
					<thead>
						<tr>
							<th scope="col">Precio</th>
							<th scope="col">Nombre</th>
							<th scope="col">Quedan</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row">$600</th>
							<td>Camion</td>
							<td>7</td>
						</tr>
						<tr>
							<th scope="row">$600</th>
							<td>Camion</td>
							<td>7</td>
						</tr>
						<tr>
							<th scope="row">$600</th>
							<td>Camion</td>
							<td>7</td>
						</tr>
						<tr>
							<th scope="row">$600</th>
							<td>Camion</td>
							<td>7</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection
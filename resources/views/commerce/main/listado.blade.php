@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
	<div class="col-lg-9">	
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-5 p-r-0">
						<input type="text" class="form-control input-search" placeholder="Buscar por codigo o nombre">
					</div>
					<div class="col-1 p-l-0">
					  	<button class="btn btn-primary btn-search">
							<i class="icon-search"></i>
					  	</button>						
					</div>
					<div class="col-6">
						<button class="btn btn-secondary float-right">
							<i class="icon-filter"></i>
							Filtrar
						</button>
					</div>
				</div>
			</div>
			<div class="card-body">
				<h5 class="card-title">
					57 artículos registrados
				</h5>
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<th scope="col">Codigo</th>
							<th scope="col">Nombre</th>
							<th scope="col">Costo</th>
							<th scope="col">Precio</th>
							<th scope="col">Agregado</th>
							<th scope="col">Actualizado</th>
							<th scope="col">Opciones</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>123123</td>
							<td>Camion</td>
							<td>$500</td>
							<th scope="row">$700</th>
							<td>17/11/2019</td>
							<td>17/11/2019</td>
							<td class="td-options">
								<button class="btn btn-listado btn-listado-edit">
									<i class="icon-edit"></i>
								</button>
								<button class="btn btn-listado btn-listado-delete">
									<i class="icon-trash-2"></i>
								</button>
							</td>
						</tr>
						<tr>
							<td>123123</td>
							<td>Camion</td>
							<td>$500</td>
							<th scope="row">$700</th>
							<td>17/11/2019</td>
							<td>17/11/2019</td>
							<td class="td-options">
								<button class="btn btn-listado btn-listado-edit">
									<i class="icon-edit"></i>
								</button>
								<button class="btn btn-listado btn-listado-delete">
									<i class="icon-trash-2"></i>
								</button>
							</td>
						</tr>
						<tr>
							<td>123123</td>
							<td>Camion</td>
							<td>$500</td>
							<th scope="row">$700</th>
							<td>17/11/2019</td>
							<td>17/11/2019</td>
							<td class="td-options">
								<button class="btn btn-listado btn-listado-edit">
									<i class="icon-edit"></i>
								</button>
								<button class="btn btn-listado btn-listado-delete">
									<i class="icon-trash-2"></i>
								</button>
							</td>
						</tr>
						<tr>
							<td>123123</td>
							<td>Camion</td>
							<td>$500</td>
							<th scope="row">$700</th>
							<td>17/11/2019</td>
							<td>17/11/2019</td>
							<td class="td-options">
								<button class="btn btn-listado btn-listado-edit">
									<i class="icon-edit"></i>
								</button>
								<button class="btn btn-listado btn-listado-delete">
									<i class="icon-trash-2"></i>
								</button>
							</td>
						</tr>
						<tr>
							<td>123123</td>
							<td>Camion</td>
							<td>$500</td>
							<th scope="row">$700</th>
							<td>17/11/2019</td>
							<td>17/11/2019</td>
							<td class="td-options">
								<button class="btn btn-listado btn-listado-edit">
									<i class="icon-edit"></i>
								</button>
								<button class="btn btn-listado btn-listado-delete">
									<i class="icon-trash-2"></i>
								</button>
							</td>
						</tr>
						<tr>
							<td>123123</td>
							<td>Camion</td>
							<td>$500</td>
							<th scope="row">$700</th>
							<td>17/11/2019</td>
							<td>17/11/2019</td>
							<td class="td-options">
								<button class="btn btn-listado btn-listado-edit">
									<i class="icon-edit"></i>
								</button>
								<button class="btn btn-listado btn-listado-delete">
									<i class="icon-trash-2"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="card-footer p-0">
				<ul class="pagination justify-content-center m-t-10 m-b-10">
					<li class="page-item">
						<a class="page-link" href="#" aria-label="Previous">
							<span aria-hidden="true">&laquo;</span>
						</a>
					</li>
					<li class="page-item"><a class="page-link" href="#">1</a></li>
					<li class="page-item"><a class="page-link" href="#">2</a></li>
					<li class="page-item"><a class="page-link" href="#">3</a></li>
					<li class="page-item"><a class="page-link" href="#">3</a></li>
					<li class="page-item"><a class="page-link" href="#">3</a></li>
					<li class="page-item">
						<a class="page-link" href="#" aria-label="Next">
							<span aria-hidden="true">&raquo;</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div id="codigos-de-barra">
	<div class="row justify-content-center">
		<div class="col-lg-9">	
			<div class="card">
				<div class="card-header">
					Empleados
				</div>
				<form>
					<div class="card-body">
						<!-- <h5 class="card-title">Genere un pdf con los codigos de barra y la cantidad que necesite</h5> -->
						<div class="row">
							<div class="col-12 col-lg-6">
								<div class="card">
									<div class="card-header">
										Registrar un nuevo empleado
									</div>
									<div class="card-body">
										<p class="card-title">
											Registre un nuevo empleado y asignele permisos para controlar que puede y que no hacer dentro del sistema
										</p>
										<form action="">
											<div class="form-group">
												<label for="name">Nombre del empleado</label>
												<div class="input-group mb-2 mr-sm-2">
													<div class="input-group-prepend">
														<div class="input-group-text">
															{{ Auth()->user()->company_name }} - 
														</div>
													</div>
													<input v-model="name_new_employee"
															type="text" 
															id="name" 
															class="form-control" 
															placeholder="Ingrese el nombre del empleado" 
															aria-describedby="nameHelp">
												</div>
												<small id="nameHelp" class="form-text text-muted">
													El empleado se registrara empezando con el nombre de su negocio seguido del nombre de su empleado
												</small>
											</div>
											<div class="form-group">
												<label for="password">Contraseña</label>
												<input v-model="password_new_employee"
														type="text" 
														id="password" 
														placeholder="Contraseña para el empleado" class="form-control">
											</div>
											<div class="form-group" 
												v-show="name_new_employee!='' && password_new_employee!=''">
												<label for="">Seleccione los permisos que tendra @{{ capitalize(name_new_employee) }}</label>
												<div v-for="permission in permissions" class="custom-control custom-checkbox my-1 mr-sm-2 m-b-10">
													<input v-model="permissions_new_employee" 
															type="checkbox" 
															class="custom-control-input" 
															:id="permission.id" 
															:value="permission.id">
													<label class="custom-control-label c-p" 
															:for="permission.id">
														@{{ permission.name }}
													</label>
													<small id="nameHelp" 
															class="form-text text-muted">
														@{{ permission.description }}	
													</small>
												</div>
												<!-- @{{ selected_permissions }} -->
											</div>
										</form>
									</div>
									<div class="card-footer">
										<button class="btn btn-primary">
											<i class="icon-user-plus"></i>
											Registrar empleado
										</button>
									</div>
								</div>
							</div>
							<div class="col-12 col-lg-6">
								<ul class="list-group">
									<li class="list-group-item active">Mis empleados</li>
									<li v-for="employee in employees" class="list-group-item">
										<i class="icon-user"></i>
										@{{ employee.name }}
										<span class="float-right">
											Alta: @{{ employee.created_at }} hace @{{ since(employee.created_at) }}
										</span>
									</li>
								</ul>
								<div class="card m-t-20">
									<div class="card-header">
										¿Por que deberia asignarle permisos a mis empleados?
									</div>
									<div class="card-body">
										Si un empleado tiene permisos para vender, por ejemplo, podría registrar nuevas ventas y quedarse con el artículo  ya que el stock de ese artículo coincidiría con los que tiene en su negocio. 
									</div>
								</div>
							</div>
						</div>					
					</div>
					<div class="card-footer p-0">
						
					</div>
				</form>	
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
new Vue({
	el: '#codigos-de-barra',
	data: {
		employees: [
			{'id': 1, 'name': 'Marcos', 'created_at': '12/10/2019'},
			{'id': 2, 'name': 'Belen', 'created_at': '12/15/2019'},
		],
		permissions: [],
		name_new_employee: '',
		password_new_employee: '',
		permissions_new_employee: [],
	},
	created() {
		this.getPermissions()
	},
	methods: {
		capitalize(str) {
			return str.charAt(0).toUpperCase() + str.slice(1)
		},
		since(date) {
			return moment(date).fromNow()
		},
		getPermissions() {
			axios.get('permissions')
			.then( res => {
				this.permissions = res.data
			})
			.catch(err => {
				console.log(err)
			})
		}
	}
})
</script>
@endsection
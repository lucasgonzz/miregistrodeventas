<template>

<div id="empleados">
	<div class="row justify-content-center">
		<div class="col-lg-9">	
			<div class="card">
				<div class="card-header">
					Empleados
				</div>
				<form>
					<div class="card-body">
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
															{{ user.company_name }} - 
														</div>
													</div>
													<input v-model="employee.name"
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
												<input v-model="employee.password"
														type="text" 
														id="password" 
														placeholder="Contraseña para el empleado" class="form-control">
											</div>
											<div class="form-group" 
												v-show="employee.name != '' 
														&& employee.password != ''">
												<label for="">Seleccione los permisos que tendra @{{ capitalize(employee.name) }}</label>
												<div v-for="permission in permissions" class="custom-control custom-checkbox my-1 mr-sm-2 m-b-10">
													<input v-model="employee.permissions" 
															type="checkbox" 
															class="custom-control-input" 
															:id="permission.id" 
															:value="permission.id">
													<label class="custom-control-label c-p" 
															:for="permission.id">
														{{ permission.name }}
													</label>
													<small id="nameHelp" 
															class="form-text text-muted">
														{{ permission.description }}	
													</small>
												</div>
												<!-- @{{ selected_permissions }} -->
											</div>
										</form>
									</div>
									<div class="card-footer">
										<button class="btn btn-primary"
												@click="saveEmployee">
											<i class="icon-user-plus"></i>
											Registrar empleado
										</button>
									</div>
								</div>
							</div>
							<div class="col-12 col-lg-6">
								<ul class="list-group">
									<li class="list-group-item active">Mis empleados</li>
									<li v-for="employee_ in employees" class="list-group-item">
										<p>
											<i class="icon-user"></i>
											{{ employee_.name }}
											<button class="btn btn danger btn-sm float-right">
												Eliminar
											</button>
										</p>
										<p>
											Alta: {{ date(employee_.created_at) }} {{ since(employee_.created_at) }}
										</p>
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
</template>
<script>
export default {
	props: ['rol'],
	data() {
		return {
			user: JSON.parse(document.querySelector("meta[name='user']").getAttribute('content')),
			employees: [
				{'id': 1, 'name': 'Marcos', 'created_at': '12/10/2019'},
				{'id': 2, 'name': 'Belen', 'created_at': '12/15/2019'},
			],
			permissions: [],
			employee: {
				name: '',
				password: '',
				permissions: [],
			}
		}
	},
	methods: {
		capitalize(str) {
			return str.charAt(0).toUpperCase() + str.slice(1)
		},
		since(date) {
			return moment(date).fromNow()
		},
		date(date) {
			return moment(date).format('DD/MM/YY')
		},
		getPermissions() {
			axios.get('permissions')
			.then( res => {
				this.permissions = res.data
			})
			.catch(err => {
				console.log(err)
			})
		},
		getEmployees() {
			axios.get('employees')
			then(res => {
				this.employees = res.data
			})
			.catch(err => {
				console.log(err)
			})
		},
		saveEmployee() {
			// if ( this.validate() ) {
				axios.post('employees')
				.then(res => {
					// setTimeout(() => {
					// 	this.getEmployees()
					// }, 1000)
					toastr.success('Empleado registrado correctamente')
				})
				.catch(err => {
					console.log(err)
				})
			// }
		},
		validate() {
			var ok = true
			if (this.employee.name == '') {
				ok = false
				toastr.error('El empleado debe tener un nombre')
			}
			if (this.employee.password == '') {
				ok = false
				toastr.error('El empleado debe tener una contraseña')
			}
			if (this.employee.permissions.length == 0) {
				ok = false
				toastr.error('El empleado debe tener al menos un permiso')
			}
			return ok
		}
	},
	created() {
		this.getEmployees()
		this.getPermissions()
	}
}
</script>
<template>
	<div id="codigos-de-barra">
		<div class="row justify-content-center">
			<div class="col-lg-9">	
				<div class="card">
					<div class="card-header">
						<h5 class="m-0">
							<strong>Empleados</strong>
						</h5>
					</div>
					<!-- <form> -->
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
															autocomplete="off" 
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
														type="password" 
														id="password" 
														placeholder="Contraseña para el empleado" 
														class="form-control">
											</div>
											<div class="form-group" 
												v-show="employee.name != '' && employee.password != ''">
												<label for="">Seleccione los permisos que tendra {{ capitalize(employee.name) }}</label>
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
											</div>
										</div>
										<div class="card-footer">
											<button @click.prevent="saveEmployee"
													class="btn btn-primary">
												<i class="icon-user-plus"></i>
												Registrar empleado
											</button>
										</div>
									</div>
								</div>
								<div class="col-12 col-lg-6">
									<ul class="list-group">
										<li v-show="employees.length" 
											class="list-group-item active">
											Mis empleados
										</li>
										<li v-show="employees.length == 0" 
											class="list-group-item active">
											Todavia no hay ningun empleado regtistrado
										</li>
										<li v-for="employee in employees" class="list-group-item">
											<i class="icon-user"></i>
											<strong>{{ employee.name }}</strong>
											<span class="float-right">
												<button @click.prevent="deleteEmployee(employee)"
														class="btn btn-danger btn-sm">
													Eliminar
												</button>
											</span>
											<p class="m-0">
												Alta: {{ date(employee.created_at) }} {{ since(employee.created_at) }}
											</p>
											<!-- <button @click="employeeDetails"
													class="btn btn-primary float-right">
												Ver
											</button> -->
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
					<!-- </form>	 -->
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
			user: JSON.parse(document.head.querySelector('meta[name="user"]').content),
			employees: [],
			employee: {
				name: '',
				password: '',
				permissions: [],
			},
			permissions: [],	
		}
	}, 
	methods: {
		capitalize(str) {
			return str.charAt(0).toUpperCase() + str.slice(1)
		},
		date(date) {
			return moment(date).format('DD/MM/YY')
		},
		since(date) {
			return moment(date).fromNow()
		},
		deleteEmployee(employee) {
			axios.delete(`employees/${employee.id}`)
			.then(res => {
				toastr.success(`Empleado ${employee.name} eliminado correctamente`)
				this.getEmployees()
			})
			.catch(err => {
				console.log(err)
			})
		},
		saveEmployee() {
			axios.post('employees', {
				employee: this.employee
			})
			.then(res => {
				// console.log(res.data)
				toastr.success(`Empleado ${this.employee.name} creado correctamente`)
				this.employee.name = ''
				this.employee.password = ''
				this.employee.permissions = []
				this.getEmployees()
			})
			.catch(err => {
				console.log(err)
			})
		},
		getEmployees() {
			axios.get('employees')
			.then(res => {
				this.employees = res.data
			})
			.catch(err => {
				console.log(err)
			})
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
	},
	created() {
		this.getEmployees()
		this.getPermissions()
	},
}
</script>
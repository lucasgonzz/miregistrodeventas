<template>
	<div id="codigos-de-barra">
		<employees-list :employees="employees"
						@deleteEmployee="deleteEmployee"></employees-list>
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
							<div class="row d-md-none m-b-10">
								<div class="col">
									<button @click="showEmployees"
											class="btn btn-success">
										Ver mis empleados
									</button>							
								</div>
							</div>
							<div class="row">
								<div class="col-12 col-lg-6">
									<div class="card">
										<div class="card-header">
											<strong>
												Registrar un nuevo empleado
											</strong>
										</div>
										<div class="card-body">
											<p class="card-title">
												Registre un nuevo empleado y asignele permisos para controlar que puede y que no hacer dentro del sistema
											</p>
											<div class="form-group">
												<label for="name">Nombre del empleado</label>
												<input v-model="employee.name"
														type="text" 
														id="name" 
														autocomplete="off" 
														class="form-control" 
														placeholder="Ingrese el nombre del empleado" 
														aria-describedby="nameHelp">
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
										</div>
									</div>
								</div>
								<div class="col-12 col-lg-6">
									<ul class="list-group d-none d-md-block">
										<li	class="list-group-item active">
											<strong>
												<span v-if="employees.length">
													Mis empleados
												</span>
												<span v-else>
													Todavia no hay ningun empleado regtistrado
												</span>
											</strong>
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
										</li>
									</ul>
									<div class="card m-t-20" 
											v-show="employee.name != '' && employee.password != ''">
										<div class="card-header">
											<strong>
												Seleccione los permisos para {{ employee.name }}
											</strong>
										</div>
										<div class="card-body">
											<div class="form-group">
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
											<div class="card-footer">
												<button @click.prevent="saveEmployee"
														class="btn btn-primary">
													<i class="icon-user-plus"></i>
													Registrar empleado
												</button>
											</div>
										</div>
									</div>
									<!-- <div class="card m-t-20">
										<div class="card-header">
											<strong>
												¿Por que deberia asignarle permisos a mis empleados?
											</strong>
										</div>
										<div class="card-body">
											Si un empleado tiene permisos para vender, por ejemplo, podría registrar nuevas ventas y quedarse con el artículo  ya que el stock de ese artículo coincidiría con los que tiene en su negocio. 
										</div>
									</div> -->
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
import EmployeesList from './modals/EmployeesList.vue'
export default {
	components: {
		EmployeesList
	},
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
		},
		showEmployees() {
			$('#employees-list').modal('show')
		}
	},
	created() {
		this.getEmployees()
		this.getPermissions()
	},
}
</script>
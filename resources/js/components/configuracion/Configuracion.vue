<template>
<div class="container">
	<div class="row justify-content-center">
		<div class="col-8">
			<div class="card">
				<div class="card-header">
					<h5 class="m-0">
						<strong>
							Configuración
						</strong>
					</h5>
				</div>
				<div class="card-body">
					<div class="card m-b-20">
						<div class="card-header">
							<strong>
								Nombre del comercio
							</strong>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label for="">Nombre actual</label>
								<input type="text"
									v-model="company_name"
									class="form-control">
								<small class="form-text text-muted">
									Este es el nombre que aparece en las facturas
								</small>
							</div>
							<div class="form-group">
								<button @click="saveCompanyName"
										class="btn btn-primary float-right">
                            		<i class="icon-refresh"></i>
									Actualizar nombre
								</button>
							</div>
						</div>
					</div>
					<div class="card m-b-20">
						<div class="card-header">
							<strong>
								Porcentaje para las tarjetas
							</strong>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label for="">Porcentaje actual</label>
								<input type="number"
										min="0"
										max="100"
										v-model="percentage_card"
										class="form-control">
								<small class="form-text text-muted">
									Porcentaje de recargo para las ventas con tarjeta
								</small>
							</div>
							<div class="form-group">
								<button @click="savePercentageCard"
										class="btn btn-primary float-right">
                            		<i class="icon-refresh"></i>
									Actualizar porcentaje
								</button>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-header">
							<strong>
								Cambiar contraseña
							</strong>
						</div>
						<div class="card-body">
                            <div class="form-group">
                                <label for="name">Contraseña actual</label>
                                <input type="password" 
                                		id="password" 
                                		v-model="password"
                                		placeholder="Contraseña actual" 
                                		class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password">Nueva contraseña</label>
                                <input type="password"  
                                		v-model="new_password"
                                		id="new_password" 
                                		placeholder="Nueva contraseña" 
                                		class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password">Confirme la nueva contraseña</label>
                                <input type="password" 
                                		name="password_confirmation" 
                                		id="confirmed_password" 
                                		v-model="confirmed_password"
                                		placeholder="Nueva contraseña" 
                                		class="form-control">
                            </div>
                            <button @click="updatePassword"
                            		class="btn btn-primary float-right">
                            	<i class="icon-refresh"></i>
                                Cambiar contraseña
                            </button>
						</div>
					</div>
				</div>
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
			company_name: '',
			percentage_card: 0,
			password: '',
			new_password: '',
			confirmed_password: '',
		}
	},
	methods: {
		getCompanyName() {
			axios.get('get-company-name')
			.then(res => {
				this.company_name = res.data
			})
			.catch(err => {
				console.log(err)
			})
		},
		saveCompanyName() {
			axios.get('set-company-name/'+this.company_name)
			.then(res => {
				toastr.success('Nombre del comercio actualizado correctamente')
				// this.getCompanyName()
			})
		},
		getPercentageCard() {
			axios.get('get-percentage-card')
			.then(res => {
				this.percentage_card = res.data
			})
			.catch(err => {
				console.log(err)
			})
		},
		savePercentageCard() {
			axios.get('set-percentage-card/'+this.percentage_card)
			.then(res => {
				toastr.success('Porcentaje para las tarjetas actualizado correctamente')
			})
		},
		updatePassword() {
			if (this.new_password === this.confirmed_password) {
				axios.post('user/password', {
					password: this.password,
					new_password: this.new_password
				})
				.then(res => {
					let rta = res.data
					if (rta == 'ok') {
						toastr.success('Contraseña actualizada correctamente')
						this.password = ''
						this.new_password = ''
						this.confirmed_password = ''
					} else {
						toastr.error('La contraseña actual no es correcta')
						this.password = ''
						$('#password').focus()
					}
				})
				.catch(err => {
					console.log(err)
				})
			} else {
				toastr.error('Las contraseñas no coinciden')
				$('#new_password').focus()
			}
		},
	},
	created() {
		this.getCompanyName()
		this.getPercentageCard()
	}
}
</script>
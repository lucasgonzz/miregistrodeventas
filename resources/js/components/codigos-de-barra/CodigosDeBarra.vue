<template>
<div class="container">	
	<div id="codigos-de-barra">
		<bar-code-details @deleteBarCode="deleteBarCode"
							:repeatedBarCode="repeated_bar_code"
							:barCode="bar_code_details"></bar-code-details>
		<div class="row justify-content-center">
			<div class="col-lg-9">	
				<div class="card">
					<div class="card-header">
						<h5 class="m-0">
							<strong>
								Codigos de barra
							</strong>
						</h5>
					</div>
					<form>
						<div class="card-body">
							<div class="row">
								<div class="col-12 col-lg-6">
									<div class="card">
										<div class="card-header">
											Genere un nuevo codigo de barras
										</div>
										<div class="card-body">
											<div class="form-group">
												<label for="bar-code">Nuevo codigo de barras</label>
												<input v-model="bar_code.name"
														type="text" 
														placeholder="Ingrese su nuevo codigo de barras" 
														id="bar-code"
														class="form-control">
												<button @click.prevent="generateCode"
														class="btn btn-success btn-sm m-t-5">
													Generar codigo
												</button>
											</div>
											<div class="form-group">
												<label for="bar-code-amount">
													Cuantas veces necesita imprimirlo
												</label>
												<input v-model="bar_code.amount"
														id="bar-code-amount"
														type="number" 
														min="0" 
														placeholder="Cantidad de veces que se imprimira" 
														class="form-control">
											</div>
											<div class="form-group">
												<label>Tamaño</label>
								                <div class="custom-control custom-radio">
													<input type="radio" 
															v-model="bar_code.size" 
															value="lg" id="lg" 
															class="custom-control-input">
													<label class="custom-control-label" for="lg">Grande</label>
								                </div>
								                <div class="custom-control custom-radio">
													<input type="radio" 
															v-model="bar_code.size" 
															value="md" id="md" 
															class="custom-control-input">
													<label class="custom-control-label" for="md">Normal</label>
								                </div>
								                <div class="custom-control custom-radio">
													<input type="radio" 
															v-model="bar_code.size" 
															value="sm" id="sm" 
															class="custom-control-input">
													<label class="custom-control-label" for="sm">Chico</label>
								                </div>
											</div>
											<div class="form-group">
								                <div class="custom-control custom-checkbox my-1 mr-sm-2">
								                    <input type="checkbox" 
								                    		v-model="bar_code.text" 
								                    		class="custom-control-input"  
								                    		id="bar-code-text">
								                    <label class="custom-control-label" for="bar-code-text">
								                    	Colocar numero debajo del código
								                    </label>
								                </div>
											</div>
										</div>
										<div class="card-footer">
											<button class="btn btn-primary"
												@click.prevent="saveBarCode"
												:class="bar_code.name && bar_code.amount > 0 ? '' : 'disabled'"
												target="_blank">
												<i class="icon-check"></i>
												Generar codigo
											</button>
										</div>
									</div>
								</div>
								<div class="col-12 col-lg-6" v-show="bar_codes.length">
									<ul class="list-group">
										<li class="list-group-item active">Codigos que he generado</li>
										<div class="list-bar-codes">
											<li v-for="bar_code_ in bar_codes" 
												class="list-group-item c-p">
												<input type="text" 
														:id="'bar-code-text-'+bar_code_.id"
														:value="bar_code_.name"
														class="form-control input-inline disabled">
												<div class="float-right">
													<button @click.prevent="copyText(bar_code_)"
															class="btn btn-success btn-sm">
														Copiar
													</button>
													<button @click.prevent="showBarCodeDetails(bar_code_)"
															class="btn btn-primary btn-sm">
														<i class="icon-eye"></i>
														Ver
													</button>
												</div>
											</li>
										</div>
									</ul>
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
</div>
</template>
<script>
import BarCodeDetails from './modals/BarCodeDetails.vue'
export default {
	components: {
		BarCodeDetails
	},
	data() {
		return {
			bar_codes: [],
			bar_code: {
				name: '',
				amount: 0,
				size: 'md',
				text: false,
			},
			bar_code_details: {
				article: {},
			},
			repeated_bar_code: false
		}
	},
	methods: {
		date(date) {
			return moment(date).format('DD/MM/YY')
		},
		since(date) {
			return moment(date).fromNow()
		},
		showBarCodeDetails(bar_code) {
			this.bar_code_details = bar_code
			console.log(this.bar_code_details)
			$('#bar-code-details').modal('show')
		},
		copyText(bar_code) {
			// Obtiene el id del input
			var bar_code_text_id = '#bar-code-text-'+bar_code.id

			// Guarda el input en una varialbe con el nombre del input
			var input = $(bar_code_text_id)

			// Selecciona el contenido del campo
			input.select();

			// Copia el texto seleccionado
			document.execCommand("copy");

			toastr.success('Codigo de barras copiado')
		},
		generateCode() {
			var bar_code = 1
			var bar_codes = []
			this.bar_codes.forEach(barCode => {
				bar_codes.push(Number(barCode.name))
			})
			while (bar_codes.includes(bar_code)) {
				bar_code++
			}
			bar_code = String(bar_code)
			var largo = bar_code.length
			var bc = '0000000000000'
			bc = bc.slice(0, 12-largo)
			bar_code = bc + bar_code
			this.bar_code.name = bar_code
			$('#bar-code-amount').focus()
		},
		saveBarCode() {
			var codigo_repetido = false
			this.bar_codes.forEach(bar_code => {
				if (bar_code.name == this.bar_code.name) {
					// Si ya fue generado se pone en true repeated_bar_code para
					// cuando se abra el modal y muestre porque se esta abriendo
					codigo_repetido = true
					this.repeated_bar_code = true
					this.bar_code_details = bar_code
					this.bar_code.name = ''
					$('#bar-code-details').modal('show')
				}
			})
			if (!codigo_repetido) {
				window.open(this.getLink())
				setTimeout(() => {
					this.getBarCodes()
				}, 1000)
				this.bar_code.name = ''
				this.bar_code.amount = 0
			}
		},
		deleteBarCode() {
			axios.delete('bar-codes/'+this.bar_code_details.id)
			.then(res => {
				this.getBarCodes()
				$('#bar-code-details').modal('hide')
				toastr.success('Codigo de barras eliminado correctamente')
			})
			.catch( err => {
				console.log(err)
			})
		},
		getBarCodes() {
			axios.get('bar-codes')
			.then( res => {
				console.log(res.data)
				this.bar_codes = res.data
			})
			.catch( err => {
				console.log(err)
			})
		},
		getLink() {
			return 'bar-codes/'
					+this.bar_code.name+'/'
					+this.bar_code.amount+'/'
					+this.bar_code.size+'/'
					+this.bar_code.text
		},
	},
	created() {
		this.getBarCodes()
	}
}
</script>
<style>
.input-inline {
	display: inline-block;
}
</style>
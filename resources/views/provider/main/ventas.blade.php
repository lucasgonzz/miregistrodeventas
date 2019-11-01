@extends('layouts.app')

@section('content')
<div id="ventas">	
	@include('provider.modals.ventas-resumen')
	@include('provider.modals.ventas-desde-una-fecha')
	@include('provider.modals.ventas-detalles-de-venta')
	<div class="row justify-content-center">
		<div class="col-lg-9">	
			<div class="card">
				<div class="card-header">
					<div class="row justify-content-between align-items-center">
						<div class="col-3">
							<strong v-show="!desdeUnaFecha">Ventas de hoy</strong>
							<strong v-show="desdeUnaFecha">
								Ventas desde @{{ desde }} hasta @{{ hasta }}
							</strong>
						</div>
						<div class="col-9">
							<button v-show="!desdeUnaFecha" class="btn m-l-5 float-right btn-primary" @click="showFromDate">
								<i class="icon-calendar"></i>
								Desde una fecha
							</button>
							<button v-show="desdeUnaFecha" class="btn m-l-5 float-right btn-primary" @click="today">
								<i class="icon-calendar"></i>
								Solo las de hoy
							</button>
							<button v-show="previus_day!=1" class="btn m-l-5 float-right btn-primary" @click="diaSiguiente">
								<i class="icon-redo"></i>
								Dia siguiente
							</button>
							<button v-show="!desdeUnaFecha" class="btn m-l-5 float-right btn-primary" @click="diaAnterior">
								<i class="icon-undo"></i>
								Dia anterior
							</button>
							<button class="btn float-right btn-success" @click="showResumen">
								<i class="icon-file"></i>
								Resumen
							</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col">
							<div class="form-check mb-2 mr-sm-2">
					          <input v-model="mostrarCostos" class="form-check-input" type="checkbox" id="costo">
					          <label class="form-check-label" for="costo">
					            Mostrar costos
					          </label>
					        </div>
						</div>
					</div>
			        <!-- @{{ selected_sales }} -->
			        <div class="row m-b-10">
			        	<div class="col">
			        		<img src="{{ asset('imgs/arrow.png') }}" class="img-arrow m-l-10" alt="">
			        		<button class="btn btn-success" :class="selected_sales.length ? '' : 'disabled'">
			        			<i class="icon-print"></i>
			        			Imprimir
			        		</button>
			        		<button class="btn btn-danger" :class="selected_sales.length ? '' : 'disabled'">
			        			<i class="icon-trash-2"></i>
			        			Eliminar
			        		</button>
			        	</div>
			        </div>
					<div class="row">
						<div class="col">
							<table class="table">
								<thead class="thead-dark">
									<tr>
										<th>
											<div class="form-check">
												<input class="form-check-input position-static" type="checkbox" value="0" @change="selectAll" v-model="selectAllProperty">
											</div>
										</th>
										<th scope="col">Ver</th>
										<th v-show="desdeUnaFecha || previus_day!=1" scope="col">Fecha</th>
										<th scope="col">Hora</th>
										<th v-show="mostrarCostos" scope="col">Costo</th>
										<th scope="col">Total</th>
										<th scope="col">Cant. Artículos</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="sale in sales">
										<td>
											<div class="form-check">
												<input class="form-check-input position-static" type="checkbox" :value="sale.id" v-model="selected_sales">
											</div>
										</td>
										<td class="td-options" @click="showDetallesVenta">
											<button class="btn btn-listado btn-listado-edit">
												<i class="icon-eye-1"></i>
											</button>
										</td>
										<td v-show="desdeUnaFecha || previus_day!=1">
											<i class="icon-calendar"></i>
											12/02/2019
										</td>
										<td>
											<i class="icon-clock-1"></i>
											@{{ sale.hora }}
										</td>
										<th v-show="mostrarCostos" scope="row">$500</th>
										<th scope="row">$700</th>
										<td>@{{ sale.cant_articulos }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="card-footer p-0">

				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
new Vue({
	el: '#ventas',
	data: {
		desdeUnaFecha: false,
		desde: '',
		hasta: '',
		mostrarCostos: true,
		selected_sales: [],
		sales: [
			{'id': 1, 'hora': '12:50', 'costo': 500, 'total': 700, 'cant_articulos': 6},
			{'id': 2, 'hora': '12:50', 'costo': 500, 'total': 700, 'cant_articulos': 6},
			{'id': 3, 'hora': '12:50', 'costo': 500, 'total': 700, 'cant_articulos': 6},
			{'id': 4, 'hora': '12:50', 'costo': 500, 'total': 700, 'cant_articulos': 6},
			{'id': 5, 'hora': '12:50', 'costo': 500, 'total': 700, 'cant_articulos': 6},
			{'id': 6, 'hora': '12:50', 'costo': 500, 'total': 700, 'cant_articulos': 6},
		],
		selectAllProperty: false,
		previus_day: 1,
	},
	methods: {

		// Metodos del header
		diaAnterior() {
			this.previus_day++
			// axios.get(`sales/previus-day/${this.previus_day}`)
			// .then( res => {
			// 	this.sales = res.data
			// 	this.previus_day++
			// })
			// .catch( err => {
			// 	console.log(err)
			// })
		},
		diaSiguiente() {
			this.previus_day--
		},

		// Resumen de ventas
		showResumen: () => {
			console.log('antes')
			$('#ventas-resumen').modal('show')
			console.log('despues')
		},

		// Ventas de hoy
		today() {
			this.desdeUnaFecha = false
		},

		// Desde una fecha
		showFromDate() {
			$('#ventas-desde-una-fecha').modal('show')
		},
		fromDate() {
			this.desdeUnaFecha = true
			$('#ventas-desde-una-fecha').modal('hide')
		},

		// Detalles de una venta
		showDetallesVenta() {
			$('#ventas-detalles-de-venta').modal('show')
		},

		selectAll() {
			if (this.selectAllProperty) {
				this.selected_sales = []
				this.sales.forEach( sale => {
					this.selected_sales.push(sale.id)
				})
			} else {
				this.selected_sales = []
			}
		}
	},
})
</script>
@endsection
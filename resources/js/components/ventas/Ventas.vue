<html>
	<head>
		<title>Hola</title>
	</head>
	<body>
		<nav></nav>
	</body>
</html>


<template>
<div id="ventas">	
	<summary :sales="sales"></summary>
	<from-date @fromDate="fromDate"
				@onlyOneDate="onlyOneDate"></from-date>
	<sale-details :sale="sale"></sale-details>
	<generate-pdf :sales_id="selected_sales.selected_sales"></generate-pdf>
	<confirm-delete-sales @deleteSales="deleteSales"
						:selected_sales="selected_sales.selected_sales"></confirm-delete-sales>
	<confirm-delete-sale @deleteSale="deleteSale"></confirm-delete-sale>
	<div class="row justify-content-center">
		<div class="col-lg-9">	
			<div class="card">
				<div class="card-header">
					<div class="row justify-content-between align-items-center">
						<div class="col-lg-5">
							<h5 class="m-0">
								<strong v-show="!is_from_date && !is_from_only_one_date && previus_next == 0">
									Ventas de hoy 
								</strong>
								<strong v-show="is_from_date">
									Ventas desde {{ date(from) }} hasta {{ date(to) }} 
									<span v-show="last_day_inclusive">(inclusive)</span>
								</strong>
								<strong v-show="is_from_only_one_date">
									Ventas del {{ date(only_one_date) }}
								</strong>
								<strong v-show="previus_next != 0 && !is_from_only_one_date && !is_from_date"
										class="p-l-5">
									<span v-show="previus_next == 1 && !is_from_only_one_date">
										Hace 1 día
									</span>
									<span v-show="previus_next > 1 && !is_from_only_one_date">
										Hace {{ previus_next }} días
									</span>
								</strong>
								<strong v-show="previus_next != 0 && is_from_only_one_date">
									<span v-show="previus_next == 1 && is_from_only_one_date">
										1 día atras
									</span>
									<span v-show="previus_next > 1 && is_from_only_one_date">
										{{ previus_next }} días atras
									</span>
									<span v-show="previus_next < 0 && is_from_only_one_date">
										{{ Math.abs(previus_next) }} días después
									</span>
								</strong>
							</h5>
						</div>
						<div class="col-lg-7 col-right">
							<button v-show="is_from_only_one_date || (previus_next != 0 && !is_from_date)" 
									class="btn m-l-5 btn-primary" 
									@click="nextDay">
								<i class="icon-redo"></i>
							</button>
							<button v-show="!is_from_date" 
									class="btn m-l-5 btn-primary" 
									@click="previusDay">
								<i class="icon-undo"></i>
							</button>
							<button class="btn m-l-5 btn-primary" 
									@click="showFromDate">
								<i class="icon-calendar"></i>
								Por fecha
							</button>
							<button v-show="is_from_date" 
									class="btn m-l-5 btn-primary"
									@click="today">
								<i class="icon-calendar"></i>
								Solo las de hoy
							</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row m-b-10">
						<div class="col">
							<div class="form-inline">
								<div class="form-group">
				                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
				                        <input class="custom-control-input c-p" 
				                            v-model="show_costs" 
				                            type="checkbox" 
				                            id="show_costs_principal">
				                        <label class="custom-control-label c-p" 
				                            for="show_costs_principal">
				                            Mostrar costos
				                        </label>
				                    </div>
								</div>
								<div class="form-group">
									<div class="btn-group" role="group" aria-label="Basic example">
										<button type="button" class="btn btn-secondary">
											{{ sales.length }} Ventas
										</button>
										<button type="button" class="btn btn-secondary">
											{{ total_articles }} artículos
										</button>
										<button type="button" class="btn btn-secondary">
											Total: {{ price(total) }}
										</button>
										<button @click="sales_with_card"
												type="button" 
												class="btn btn-primary">
											<span v-show="showing_only_with_card_sales">
												<i class="icon-undo"></i>
												Todas las ventas
											</span>
											<span v-show="!showing_only_with_card_sales">
												<i class="icon-credit-card p-r-5"></i>
												<span v-show="cant_card_sales() > 0">
													{{ cant_card_sales() }} Tarjetas
												</span>
												<span v-show="cant_card_sales() == 0">
													Sin tarjetas
												</span>
											</span>
										</button>
									</div>
								</div>
								<!-- <div class="form-group">
									<label for="orden-ventas">Ordenar</label>
									<select v-model="order_sales" 
											class="form-control"
											@change="orderSales"
											id="orden-ventas">
											<option value="latest">Mas recientes</option>
											<option value="first">Primeras</option>
											<option value="caras">Mas caras</option>
											<option value="baratas">Mas baratas</option>
									</select>
								</div> -->
							</div>
						</div>
					</div>
			        <div class="row m-b-10">
			        	<div class="col">
			        		<img :src="asset+'imgs/arrow.png'" class="img-arrow m-l-10" alt="">
			        		<a @click="generatePdf"
			        			href="#"
			        				class="btn btn-success" 
			        				:class="selected_sales.selected_sales.length ? '' : 'disabled'">
			        			<i class="icon-print"></i>
			        			Pdf/Imprimir
			        		</a>
			        		<a class="btn btn-danger" 
			        			href="#"
			        			@click.prevent="confirmDeleteSales"
			        			:class="selected_sales.selected_sales.length ? '' : 'disabled'">
			        			<i class="icon-trash-2"></i>
			        			Eliminar
			        		</a>
			        		<strong v-show="selected_sales.selected_sales.length" class="p-l-5">
			        			{{ selected_sales.selected_sales.length }} ventas seleccionadas
			        		</strong>
			        	</div>
			        </div>
					<!-- <div class="row" v-show="is_loading">
						<div class="col">
							<div class="spinner-listado">
								<div class="spinner">
									<div class="spinner-border text-primary" role="status">
										<span class="sr-only">Loading...</span>
									</div>
								</div>
							</div>
						</div>
					</div> -->
					<cargando :is_loading="is_loading"></cargando>
					<div class="row" v-show="!is_loading">
						<div class="col">
							<div class="table-responsive">
								<table class="table table-striped">
									<thead class="thead-dark">
										<tr>
											<th>
							                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
							                        <input class="custom-control-input" 
							                            v-model="selected_sales.is_all_selected" 
							                            type="checkbox" 
							                            @change="selectAllSales"
							                            id="is_all_selected">
							                        <label class="custom-control-label" 
							                            for="is_all_selected">
							                        </label>
							                    </div>
											</th>
											<th scope="col">Ver</th>
											<th v-show="is_from_date || previus_next!=0 || is_from_only_one_date" scope="col">
												Fecha
											</th>
											<th scope="col">Hora</th>
											<th scope="col" v-show="rol == 'provider'">Cliente</th>
											<th scope="col">Cant. Artículos</th>
											<th scope="col">Cant. Unidades</th>
											<th v-show="show_costs" scope="col">Costo</th>
											<th scope="col">Total</th>
											<th>Eliminar</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="sale in sales"
											:class="selected_sales.selected_sales.includes(sale.id) ? 'bg-warning' : ''">
											<td>
							                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
							                        <input class="custom-control-input" 
							                            v-model="selected_sales.selected_sales" 
							                            type="checkbox" 
							                            :value="sale.id"
							                            :id="sale.id">
							                        <label class="custom-control-label" 
							                            :for="sale.id">
							                        </label>
							                    </div>
											</td>
											<td class="td-options" @click="showSaleDetails(sale)">
												<button class="btn btn-listado btn-listado-edit">
													<i class="icon-eye"></i>
												</button>
											</td>
											<td v-show="is_from_date || previus_next!=0 || is_from_only_one_date">
												<i class="icon-calendar"></i>
												{{ date(sale.created_at) }}
											</td>
											<td>
												<i class="icon-clock-1"></i>
												{{ hour(sale.created_at) }}
											</td>
											<td v-if="rol == 'provider'">
												<span v-if="sale.client">
													{{ sale.client.name }}
												</span>
												<span v-else>
													Sin datos
												</span>
											</td>
											<td>{{ cantidad_articulos(sale) }}</td>
											<td>{{ cantidad_unidades(sale) }}</td>
											<th v-show="show_costs" scope="row">
												{{ getCost(sale) }}
											</th>
											<th scope="row">
												{{ getPrice(sale) }}
											</th>
											<td>
												<button @click="confirmDeleteSale(sale)"
														class="btn btn-danger btn-sm">
													<i class="icon-trash-3"></i>
												</button>
												<i v-show="sale.percentage_card != null"
													class="icon-credit-card text-primary card-icon"></i>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</template>
<script>
import FromDate from './modals/FromDate.vue'
import SaleDetails from './modals/SaleDetails.vue'
import ConfirmDeleteSales from './modals/ConfirmDeleteSales.vue'
import ConfirmDeleteSale from './modals/ConfirmDeleteSale.vue'
import Summary from './modals/Summary.vue'
import GeneratePdf from './modals/GeneratePdf.vue'
export default {
	components: {
		FromDate,
		SaleDetails,
		ConfirmDeleteSale,
		ConfirmDeleteSales,
		GeneratePdf,
		Summary,
	},
	props: ['rol'],
	data() {
		return {
			is_loading: false,
			is_from_date: false,
			is_from_only_one_date: false,
			only_one_date: '',
			from: '',
			to: '',
			show_costs: true,
			actual_prices: false,
			selected_sales: [],
			order_sales: 'latest',
			sales: [],
			previus_sales: [],
			showing_only_with_card_sales: false,
			sale: {},
			total: 0,
			total_articles: 0,
			last_day_inclusive: false,
			selected_sales: {
				is_all_selected: false,
				selected_sales: [],
				selected_pages: [],
			},
			sale: {},
			selectAllProperty: false,

			previus_next: 0,
			asset: document.getElementsByName('asset')[0].getAttribute('content')
		}
	},
	watch: {
		sales() {
			this.total = 0
			this.total_articles = 0
			this.sales.forEach(sale => {
				sale.articles.forEach(article => {
					if (article.uncontable == 1) {
						if (article.pivot.measurement == article.measurement) {
							this.total += parseFloat(article.pivot.price) * article.pivot.amount
						} else {
							this.total += parseFloat(article.pivot.price) * article.pivot.amount / 1000
						}
					} else {
						this.total += parseFloat(article.pivot.price) * article.pivot.amount
					}
					// if (this.actual_prices) {
					// } else {
					// 	this.total += parseFloat(article.pivot.price) * article.pivot.amount
					// }
					this.total_articles++
				})
			})
		}
	},
	methods: {
		generatePdf() {
			$('#generate-pdf').modal('show')
		},
		date(d) {
			return moment(d).format('DD/MM/YY')
		},
		hour(d) {
			return moment(d).format('HH:mm')
		},
		since(date) {
			return moment(date).fromNow()
		},
		price(p) {
			return numeral(p).format('$0,0.00')
		},
		cantidad_articulos(sale) {
			return sale.articles.length
		},
		cantidad_unidades(sale) {
			var cantidad_unidades = 0
			sale.articles.forEach(article => {
				if (article.uncontable == 0) {
					cantidad_unidades += article.pivot.amount
				} else {
					cantidad_unidades ++
				}
			})
			return cantidad_unidades
		},
		cant_card_sales() {
			var cantidad_sales = 0
			this.sales.forEach(sale => {
				if (sale.percentage_card === null) {

				} else {
					cantidad_sales++
				}
			})
			return cantidad_sales
		},
		getCost(sale) {
			var cost = 0
			sale.articles.forEach(article => {
				if (article.uncontable == 0) {
					cost += parseFloat(article.cost) * article.pivot.amount
				} else {
					if (article.pivot.measurement == article.measurement) {
						cost += parseFloat(article.cost) * article.pivot.amount
					} else {
						cost += parseFloat(article.cost) * article.pivot.amount / 1000
					}				
				}
			})
			return numeral(cost).format('$0,0.00')
		},
		getPrice(sale) {
			var price = 0
			sale.articles.forEach(article => {
				if (article.uncontable == 0) {
					price += parseFloat(article.price) * article.pivot.amount
				} else {
					if (article.pivot.measurement == article.measurement) {
						price += parseFloat(article.price) * article.pivot.amount
					} else {
						price += parseFloat(article.price) * article.pivot.amount / 1000
					}				
				}
				// if (this.actual_prices) {
				// 	price += parseFloat(article.price) * article.pivot.amount
				// } else {
				// 	price += parseFloat(article.pivot.price) * article.pivot.amount
				// }
			})
			return numeral(price).format('$0,0.00')
		},
		orderSales() {
			if (this.order_sales == 'latest') {
				this.sales.reverse()
			} else if (this.order_sales == 'first') {
				this.sales.reverse()
			} else if (this.order_sales == 'caros') {
				var sales = []
				this.sales.forEach(sale => {
					sales.push(this.getPrice(sale))
				})
				
			}
		},	

		// Metodos del header
		previusDay() {
			this.previus_next++
			this.previusNext('previus')
		},
		nextDay() {
			if (this.previus_next == 1) {
				this.previus_next--
				this.getSales()
			} else {
				this.previus_next--
				this.previusNext('next')
			}
		},
		previusNext(direction) {
			if (this.only_one_date == '') {
				this.is_loading = true
				axios.get('sales/previus-next/'+this.previus_next+'/'+direction)
				.then( res => {
					var sales = res.data.sales
					if (sales.length) {
						this.previus_next = res.data.index
						this.sales = sales
					} else {
						toastr.error("No hay ventas mas atras")
						this.previus_next--
						if (this.previus_next != 0) {
							this.previusNext()
						}
					}
					if (this.selected_sales.selected_pages.includes(this.previus_next)) {
						this.selected_sales.is_all_selected = true
					} else {
						this.selected_sales.is_all_selected = false
					}
					this.is_loading = false
				})
				.catch( err => {
					console.log(err)
				})
			} else {
				axios.get('sales/previus-next/'+this.previus_next+'/'+direction+'/'+this.only_one_date)
				.then( res => {
					// console.log(res.data)
					this.sales = res.data
					if (this.selected_sales.selected_pages.includes(this.previus_next)) {
						this.selected_sales.is_all_selected = true
					} else {
						this.selected_sales.is_all_selected = false
					}
				})
				.catch(err => {
					console.log(err)
				})
			}
		},

		// Ventas seleccionadas
		selectAllSales() {
			if (this.selected_sales.is_all_selected) {
				this.sales.forEach(sale => {
					if (!this.selected_sales.selected_sales.includes(sale.id)) {
						this.selected_sales.selected_sales.push(sale.id)
					}
				})
				this.selected_sales.selected_pages.push(this.previus_next)
			} else {
				this.sales.forEach(sale => {
					var i = this.selected_sales.selected_sales.indexOf(sale.id)
					this.selected_sales.selected_sales.splice(i, 1)
				})
				var index = this.selected_sales.selected_pages.indexOf(this.previus_next)
				this.selected_sales.selected_pages.splice(index, 1)
			}
		},
		confirmDeleteSales() {
			$('#delete-sales').modal('show')
		},
		deleteSales() {
			axios.delete('sales/delete-sales/'+this.selected_sales.selected_sales.join('-'))
			.then(res => {
				this.selected_sales.selected_sales = []
				if (this.is_from_date) {
					this.fromDate()
				} else {
					if (this.previus_next != 0) {
						this.previusNext()
					} else {
						this.getSales()
					}
				}
				$('#delete-sales').modal('hide')
				toastr.success('Se eliminaron las '+this.selected_sales.selected_sales.length+' correctamente')
			})
			.catch(err => {
				console.log(err)
			})
		},
		confirmDeleteSale(sale) {
			this.sale = sale
			$('#delete-sale').modal('show')
		},
		deleteSale() {
			axios.delete('sales/'+this.sale.id)
			.then(res => {
				this.sale = {}
				if (this.is_from_date) {
					this.fromDate()
				} else {
					if (this.previus_next != 0) {
						this.previusNext()
					} else {
						this.getSales()
					}
				}
				$('#delete-sale').modal('hide')			
				toastr.success('Venta eliminada correctamente')
			})
			.catch(err => {
				console.log(err)
			})
		},

		// Metodos del titulo

		sales_with_card() {
			if (!this.showing_only_with_card_sales) {
				var sales = []
				this.sales.forEach(sale => {
					console.log('entroasdsadsd')
					if (sale.percentage_card === null) {

					} else {
						console.log('entro')
						sales.push(sale)
					}
				})
				this.showing_only_with_card_sales = true
				this.previus_sales = this.sales
				this.sales = sales
			} else {
				this.showing_only_with_card_sales = false
				this.sales = this.previus_sales
			}
		},

		// Resumen de ventas
		showResumen() {
			$('#sales-summary').modal('show')
		},

		// Ventas de hoy
		today() {
			this.is_from_date = false
		},

		// Desde una fecha
		showFromDate() {
			$('#from-date').modal('show')
		},
		fromDate(from = this.from, to = this.to, last_day_inclusive = this.last_day_inclusive) {
			this.is_loading = true
			$('#from-date').modal('hide')
			this.from = from
			this.to = to
			if (last_day_inclusive == '0') {
				this.last_day_inclusive = false
			} else {
				this.last_day_inclusive = true
			}
			axios.get('sales/from-date/'+this.from+'/'+this.to+'/'+last_day_inclusive)
			.then(res => {
				this.is_loading = false
				if (res.data == false) {
					toastr.error('No hay ventas entre estas fechas')
				} else {
					this.sales = res.data
					this.is_from_date = true
				}
			})
			.catch(err => {
				console.log(err)
			})
		},
		onlyOneDate(date) {
			// Ver tema de previus_next porque capas ya se estuvieron usando
			this.is_loading = true
			$('#from-date').modal('hide')
			axios.get('sales/only-one-date/'+date)
			.then(res => {
				this.is_loading = false
				if (res.data == false) {
					toastr.error('No hay ventas en esta fecha')
				} else {
					this.only_one_date = date
					this.is_from_only_one_date = true
					this.sales = res.data
				}
			})
			.catch(err => {
				console.log(err)
			})
		},

		// Detalles de una venta
		showSaleDetails(sale) {
			this.sale = sale
			$('#sale-details').modal('show')
		},

		getSales() {
			this.is_loading = true
			axios.get('sales')
			.then(res => {
				this.is_loading = false
				this.sales = res.data
			})
			.catch(err => {
				console.log(err)
			})
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
	created() {
		this.getSales()
	}
}
</script>
<style scoped>
.col-right {
	display: flex;
	flex-direction: row;
	justify-content: flex-end;
}

.card-icon {
	font-size: 1.5rem
}
</style>
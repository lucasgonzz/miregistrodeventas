<template>
<div id="ventas">	
	<summary :sales="sales"></summary>
	<from-date @fromDate="fromDate"></from-date>
	<sale-details :sale="sale"></sale-details>
	<generate-pdf :sales_id="selected_sales.selected_sales"></generate-pdf>
	<div class="row justify-content-center">
		<div class="col-lg-9">	
			<div class="card">
				<div class="card-header">
					<div class="row justify-content-between align-items-center">
						<div class="col-5">
							<strong v-show="!is_from_date && previus_next == 0">
								Ventas de hoy
							</strong>
							<strong v-show="is_from_date">
								Ventas desde {{ from }} hasta {{ to }} 
								<span v-show="last_day_inclusive">(inclusive)</span>
							</strong>
							<strong v-show="previus_next != 0">
								<span v-show="previus_next == 1">
									Hace 1 día
								</span>
								<span v-show="previus_next > 1">
									Hace {{ previus_next }} días
								</span>
							</strong>
						</div>
						<div class="col-7 col-rigth">
							<button v-show="is_from_date"
									class="btn m-l-5 btn-success" 
									@click="showResumen">
								<i class="icon-file"></i>
								Resumen
							</button>
							<button v-show="previus_next != 0" 
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
								Desde y hasta una fecha
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
				                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
				                        <input class="custom-control-input c-p" 
				                            v-model="actual_prices" 
				                            type="checkbox" 
				                            id="actual_prices_principal">
				                        <label class="custom-control-label c-p" 
				                            for="actual_prices_principal">
				                            Mostrar precios actuales
				                        </label>
				                    </div>
								</div>
								<div class="form-group">
									<div class="btn-group" role="group" aria-label="Basic example">
										<button type="button" class="btn btn-warning">
											{{ sales.length }} Ventas
										</button>
										<button type="button" class="btn btn-warning">
											{{ total_articles }} artículos
										</button>
										<button type="button" class="btn btn-warning">Total: ${{ total }}</button>
									</div>
								</div>
							</div>
						</div>
					</div>
			        <div class="row m-b-10">
			        	<div class="col">
			        		<img :src="asset+'imgs/arrow.png'" class="img-arrow m-l-10" alt="">
			        		<button @click="generatePdf"
			        				class="btn btn-success" 
			        				:class="selected_sales.selected_sales.length ? '' : 'disabled'">
			        			<i class="icon-print"></i>
			        			Pdf/Imprimir
			        		</button>
			        		<button class="btn btn-danger" :class="selected_sales.length ? '' : 'disabled'">
			        			<i class="icon-trash-2"></i>
			        			Eliminar
			        		</button>
			        		<span v-show="selected_sales.selected_sales.length" class="p-l-5">
			        			{{ selected_sales.selected_sales.length }} ventas seleccionadas
			        		</span>
			        	</div>
			        </div>
					<div class="row">
						<div class="col">
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
										<th v-show="is_from_date || previus_next!=0" scope="col">Fecha</th>
										<th scope="col">Hora</th>
										<th v-show="show_costs" scope="col">Costo</th>
										<th scope="col">Total</th>
										<th scope="col">Cant. Artículos</th>
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
										<td v-show="is_from_date || previus_next!=0">
											<i class="icon-calendar"></i>
											{{ date(sale.created_at) }}
										</td>
										<td>
											<i class="icon-clock-1"></i>
											{{ hour(sale.created_at) }}
										</td>
										<th v-show="show_costs" scope="row">
											${{ getCost(sale) }}
										</th>
										<th scope="row">
											${{ getPrice(sale) }}
										</th>
										<td>{{ cantidad_articulos(sale) }}</td>
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
</template>
<script>
import FromDate from './modals/FromDate.vue'
import SaleDetails from './modals/SaleDetails.vue'
import Summary from './modals/Summary.vue'
import GeneratePdf from './modals/GeneratePdf.vue'
export default {
	components: {
		FromDate,
		SaleDetails,
		GeneratePdf,
		Summary,
	},
	data() {
		return {
			is_from_date: false,
			from: '',
			to: '',
			show_costs: true,
			actual_prices: false,
			selected_sales: [],
			sales: [],
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
					if (this.actual_prices) {
						this.total += parseFloat(article.price)
					} else {
						this.total += parseFloat(article.pivot.price)
					}
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
		cantidad_articulos(sale) {
			return sale.articles.length
		},
		getCost(sale) {
			var cost = 0
			sale.articles.forEach(article => {
				if (this.actual_prices) {
					cost += parseFloat(article.cost)
				} else {
					cost += parseFloat(article.pivot.cost)
				}
			})
			return cost
		},
		getPrice(sale) {
			var price = 0
			sale.articles.forEach(article => {
				if (this.actual_prices) {
					price += parseFloat(article.price)
				} else {
					price += parseFloat(article.pivot.price)
				}
			})
			return price
		},
		// Metodos del header
		previusDay() {
			this.previus_next++
			this.previusNext()
		},
		nextDay() {
			this.previus_next--
			this.previusNext()
		},
		previusNext() {
			axios.get('sales/previus-next/'+this.previus_next)
			.then( res => {
				// console.log(res.data)
				this.sales = res.data
				if (this.selected_sales.selected_pages.includes(this.previus_next)) {
					this.selected_sales.is_all_selected = true
				} else {
					this.selected_sales.is_all_selected = false
				}
			})
			.catch( err => {
				console.log(err)
			})
		},

		// Ventas seleccionadas
		selectAllSales() {
			if (this.selected_sales.is_all_selected) {
				this.sales.forEach(sale => {
					this.selected_sales.selected_sales.push(sale.id)
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
		fromDate(from, to, last_day_inclusive) {
			this.from = from
			this.to = to
			if (last_day_inclusive == '0') {
				this.last_day_inclusive = false
			} else {
				this.last_day_inclusive = true
			}
			axios.get('sales/from-date/'+this.from+'/'+this.to+'/'+last_day_inclusive)
			.then(res => {
				console.log(res.data)
				this.sales = res.data
				this.is_from_date = true
				$('#from-date').modal('hide')
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
			axios.get('sales')
			.then(res => {
				console.log(res.data)
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
<style>
.col-rigth {
	display: flex;
	flex-direction: row;
	justify-content: flex-end;
}
</style>
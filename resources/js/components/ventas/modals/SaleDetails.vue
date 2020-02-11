<template>
<div class="modal fade" id="sale-details" tabindex="-1" role="dialog" aria-labelledby="ventas-resumens" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Detalles de la venta
                    <span v-show="sale.percentage_card != null">
                        (Con un {{ sale.percentage_card }}% de interes por tarjeta)
                    </span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
                        <input class="custom-control-input" 
                            v-model="show_costs" 
                            type="checkbox" 
                            id="show_costs">
                        <label class="custom-control-label" 
                            for="show_costs">
                            Mostrar costos
                        </label>
                    </div>
                </div>
                <div class="table-responsive">                      
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <th scope="col">Nombre</th>    
                            <th scope="col">Cantidad</th>      
                            <th v-show="show_costs" scope="col">Costo</th>      
                            <th scope="col" class="td-lg">Precio</th>      
                            <th v-show="show_costs" scope="col">Sub Total Costos</th>      
                            <th scope="col">Sub Total</th>      
                        </thead>
                        <tbody>
                            <tr v-for="article in sale.articles">
                                <td>{{ article.name }}</td>
                                <td>
                                    <span v-if="article.uncontable == 1">                                    
                                        {{ article.pivot.amount }} {{ article.pivot.measurement }}(s)
                                    </span>
                                    <span v-else>
                                        {{ article.pivot.amount }}
                                    </span>
                                </td>
                                <td v-show="show_costs">
                                    {{ price(article.pivot.cost) }}
                                </td>
                                <td class="td-lg">
                                    <span v-if="article.uncontable == 1">
                                        {{ price(article.pivot.price) }} el {{ article.measurement }}
                                    </span>
                                    <span v-else>
                                        {{ price(article.pivot.price) }}
                                        <span v-show="sale.percentage_card != null">
                                            (
                                                <i class="icon-credit-card text-primary"></i>
                                                {{ price_with_card(article) }}
                                            )
                                        </span>
                                    </span>
                                </td>
                                <td v-show="show_costs">
                                    {{ getSubTotalCost(article) }}
                                </td>
                                <td>
                                    {{ getSubTotal(article) }}
                                    <span v-show="sale.percentage_card != null">
                                        (
                                            <i class="icon-credit-card text-primary"></i>
                                            {{ total_with_card(article) }}
                                        )
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
</template>
<script>
export default {
    props: ['sale'],
    data() {
        return {
            show_costs: true,
            actual_prices: false,
        }
    },
    methods: {
        price(p) {
            return numeral(p).format('$0,0.00')
        },
        price_with_card(article) {
            console.log('aca: '+article.price)
            console.log('aca2: '+parseFloat('1.' + this.sale.percentage_card))
            return this.price(parseFloat(article.price) * parseFloat('1.' + this.sale.percentage_card))
        },
        total_with_card(article) {
            return this.price(parseFloat(article.price) * parseFloat('1.' + this.sale.percentage_card) * article.pivot.amount)
        },
        getSubTotal(article) {
            if (article.uncontable == 1) {
                if (article.pivot.measurement != article.measurement) {
                    // console.log('diferente: '+article.name)
                    var sub_total_price = parseFloat(article.pivot.price) * article.pivot.amount / 1000
                } else {
                    var sub_total_price = parseFloat(article.pivot.price) * article.pivot.amount
                }
            } else {
                var sub_total_price = parseFloat(article.pivot.price) * article.pivot.amount
            }
            // var sub_total = parseFloat(article.pivot.price) * article.pivot.amount
            return this.price(sub_total_price)
        },
        getSubTotalCost(article) {
            if (article.uncontable == 1) {
                if (article.pivot.measurement != article.measurement) {
                    var sub_total_cost = parseFloat(article.pivot.cost) * article.pivot.amount / 1000
                } else {
                    var sub_total_cost = parseFloat(article.pivot.cost) * article.pivot.amount
                }
            } else {
                var sub_total_cost = parseFloat(article.pivot.cost) * article.pivot.amount
            }
            return this.price(sub_total_cost)
        },
        generatePdf() {
            // var link = 'sales/pdf/'+this.sale.id+
            //             '/1/'+
            //             '/'+ this.show_costs ? '1' : '0' +
            //             '/'+ this.show_costs ? '1' : '0' +
            //             '/1/'+
            //             '/'+ this.show_costs ? '1' : '0' +
            //             '/0'
            var link = 'sales/pdf/'+this.sale.id+
                        '/1'+
                        '/1'+
                        '/1'+
                        '/1'+
                        '/1'+
                        '/0'
            window.open(link)
        },
    }
}
</script>

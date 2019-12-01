<template>
<div class="modal fade" id="sale-details" tabindex="-1" role="dialog" aria-labelledby="ventas-resumens" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la venta</h5>
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
                <div class="form-group">
                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
                        <input class="custom-control-input" 
                            v-model="actual_prices" 
                            type="checkbox" 
                            id="actual_prices">
                        <label class="custom-control-label" 
                            for="actual_prices">
                            Mostrar precios actuales
                        </label>
                    </div>
                </div>
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <th scope="col">Codigo</th>    
                        <th scope="col">Nombre</th>    
                        <th v-show="show_costs" scope="col">Costo</th>      
                        <th scope="col">Precio</th>      
                    </thead>
                    <tbody>
                        <tr v-for="article in sale.articles">
                            <td v-if="article.bar_code">{{ article.bar_code }}</td>
                            <td v-else>No tiene</td>
                            <td>{{ article.name }}</td>
                            <td v-show="show_costs">
                                <span v-show="actual_prices">
                                    ${{ article.cost }}
                                </span>
                                <span v-show="!actual_prices">
                                    ${{ article.pivot.cost }}
                                </span>
                            </td>
                            <td>
                                <span v-show="actual_prices">
                                    ${{ article.price }}
                                </span>
                                <span v-show="!actual_prices">
                                    ${{ article.pivot.price }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
    }
}
</script>

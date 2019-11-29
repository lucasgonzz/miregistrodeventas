<template>

<div class="modal fade" id="generate-pdf" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="icon-check text-success"></i>
                    Generar Pdf - Imprimir remitos de {{ sales_id.length }} ventas
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h5>En el remito se mostrara</h5>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox my-1 mr-sm-2">
                                <input type="checkbox" 
                                        v-model="company_name" 
                                        true-value="1"
                                        false-value="0"
                                        class="custom-control-input" id="company_name">
                                <label class="custom-control-label" for="company_name">El nombre del negocio</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox my-1 mr-sm-2">
                                <input type="checkbox" 
                                        v-model="articles_cost" 
                                        true-value="1"
                                        false-value="0"
                                        class="custom-control-input" id="articles_cost">
                                <label class="custom-control-label" for="articles_cost">
                                    Los costos de los artículos
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox my-1 mr-sm-2">
                                <input type="checkbox" 
                                        v-model="articles_subtotal_cost" 
                                        true-value="1"
                                        false-value="0" 
                                        class="custom-control-input" id="articles_subtotal_cost">
                                <label class="custom-control-label" for="articles_subtotal_cost">
                                    El subtotal del costo
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox my-1 mr-sm-2">
                                <input type="checkbox" 
                                        v-model="articles_total_price" 
                                        true-value="1"
                                        false-value="0"
                                        class="custom-control-input" id="articles_total_price">
                                <label class="custom-control-label" for="articles_total_price">
                                	La suma de los precios de los artículos por cada página
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox my-1 mr-sm-2">
                                <input type="checkbox" 
                                        v-model="articles_total_cost" 
                                        true-value="1"
                                        false-value="0"
                                        class="custom-control-input" id="articles_total_cost">
                                <label class="custom-control-label" for="articles_total_cost">
                                	La suma de los costos de los artículos por cada página
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox my-1 mr-sm-2">
                                <input type="checkbox" 
                                        v-model="borders" 
                                        true-value="1"
                                        false-value="0"
                                        class="custom-control-input" id="borders">
                                <label class="custom-control-label" for="borders">Bordes</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label for="cantidad-registros">Maximo de artículos por página</label>
                                <input type="number" 
                                        v-model="articles_per_page"
                                        min="1"
                                        max="25"
                                        class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button @click="generatePdf"
                		class="btn btn-danger">
                	Generar Pdf
                </button>
            </div>
        </div>
    </div>
</div>
</template>
<script>
export default {
    props: ['sales_id'],
    data() {
        return {
            company_name: 1,
            articles_cost: 0,
            articles_subtotal_cost: 0,
            articles_total_price: 1,
            articles_total_cost: 0,
            borders: 0,
            articles_per_page: 25,
        }
    },
    methods: {
    	generatePdf() {
    		var sales_id_ = []
    		this.sales_id.forEach(sale_id => {
                // console.log(sale.id)
    			sales_id_.push(sale_id)
    		})
            var link = 'sales/pdf/'+sales_id_.join('-')+
            			'/'+this.company_name+
                        '/'+this.articles_cost+
                        '/'+this.articles_subtotal_cost+
                        '/'+this.articles_total_price+
                        '/'+this.articles_total_cost+
                        '/'+this.borders+
                        '/'+this.articles_per_page
            window.open(link)
    	},
    }
}
</script>
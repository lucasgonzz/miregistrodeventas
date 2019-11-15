<template>

<div class="modal fade" id="successful-sale" tabindex="-1" role="dialog" aria-labelledby="ventas-resumens" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    <i class="icon-check text-success"></i>
                    Venta realizada correctamente
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h5>En la factura se mostrara</h5>
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
                                        v-model="borders" 
                                        true-value="1"
                                        false-value="0"
                                        class="custom-control-input" id="borders">
                                <label class="custom-control-label" for="borders">Bordes</label>
                            </div>
                        </div>
                        <div class="form-group" v-show="articles_per_page != 0">
                            <div class="form-group">
                                <label for="cantidad-registros">Artículos por página</label>
                                <input type="number" 
                                        v-model="articles_per_page"
                                        :min="min"
                                        :max="max"
                                        class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-t-5">
                    <div class="col">
                        <button @click="pdfClient"
                                class="btn btn-primary btn-block">
                            <i class="icon-print"></i>
                            Factura para el Cliente
                        </button>
                    </div>
                    <div class="col">
                        <button @click="pdfCommerce"
                                class="btn btn-primary btn-block">
                            <i class="icon-print"></i>
                            Factura para el negocio
                        </button>
                    </div>
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
            company_name: 0,
            borders: 0,
            articles_per_page: 0,
            max: 0,
            min: 0,
        }
    },
    created() {
        if (this.sale.articles.length > 10) {
            this.articles_per_page = this.sale.articles.length / 2
            this.min = 5
            this.max = this.sale.articles.length
        }
    },
    methods: {
        pdfClient() {
            var link = 'sales/cliente/'+this.company_name+
                        '/'+this.borders+
                        '/'+this.articles_per_page+
                        '/'+this.sale.id
            window.open(link)
        },
        pdfCommerce() {
            var link = 'sales/comercio/'+this.company_name+
                        '/'+this.borders+
                        '/'+this.articles_per_page+
                        '/'+this.sale.id
            window.open(link)
        },
    }
}
</script>
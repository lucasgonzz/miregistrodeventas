<template>

<!-- Modal -->
<div class="modal fade" id="article-not-register" tabindex="-1" role="dialog" aria-labelledby="ventas-resumens" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Artículo no registrado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        Regitra <button class="btn btn-link" @click.prevent="openRegisterArticle">aca</button> tu artículo para poder ingresarlo en esta venta
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button @click="run" class="btn btn-primary">Listo, ya lo registre</button>
            </div>
        </div>
    </div>
</div>
</template>
<script>
export default {
    methods: {
        openRegisterArticle() {
            window.open('ingresar')
        },
        run() {
            this.$emit('updateArticles')
        }
    }
}
</script>
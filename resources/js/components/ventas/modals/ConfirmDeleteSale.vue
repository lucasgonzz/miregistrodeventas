<template>

<!-- Modal -->
<div class="modal fade" id="delete-sale" tabindex="-1" role="dialog" aria-labelledby="ventas-resumens" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Eliminar Venta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5>¿Seguro que quiere eliminar esta venta?</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-danger" @click="deleteSale">Eliminar venta</button>
      </div>
    </div>
  </div>
</div>
</template>
<script>
export default {
	methods: {
    deleteSale() {
      this.$emit('deleteSale')
    }
	}
}
</script>
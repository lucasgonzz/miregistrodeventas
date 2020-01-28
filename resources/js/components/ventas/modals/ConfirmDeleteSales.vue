<template>

<!-- Modal -->
<div class="modal fade" id="delete-sales" tabindex="-1" role="dialog" aria-labelledby="ventas-resumens" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Eliminar Ventas seleccionadas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5>¿Seguro que quiere eliminar estas {{ selected_sales.length }} ventas?</h5>
        <p>Se repondran las unidades de sus artículos</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-danger" @click="deleteSales">Eliminar ventas</button>
      </div>
    </div>
  </div>
</div>
</template>
<script>
export default {
  props: ['selected_sales'],
	methods: {
    deleteSales() {
      this.$emit('deleteSales')
    }
	}
}
</script>
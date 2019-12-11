<template>
<div class="modal fade" id="sale-details" tabindex="-1" role="dialog" aria-labelledby="ventas-resumens" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del empleado {{ employee.name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item active">
                        {{ employee.name }}
                    </li>
                    <li class="list-group-item">
                        Alta: {{ date(employee.created_at) }}
                        <span class="float-right">
                            {{ since(employee.created_at) }}
                        </span>
                    </li>
                    
                </ul>
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
    props: ['employee'],
    data() {
        return {
            show_costs: true,
            actual_prices: false,
        }
    }
}
</script>

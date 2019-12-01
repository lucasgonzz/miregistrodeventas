<template>

<!-- Modal -->
<div class="modal fade" id="from-date" tabindex="-1" role="dialog" aria-labelledby="ventas-resumens" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mostrar ventas por fecha</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card m-b-10" v-show="only_one_date == ''">
                    <div class="card-header">
                        Desde y hasta una fecha
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="from">Buscar desde</label>
                                <input type="date" id="from" v-model="from" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="to">Hasta</label>
                            <input type="date" id="to" v-model="to" class="form-control">
                        </div>
                        <div class="form-group" v-show="to != ''">
                            <div class="custom-control custom-checkbox my-1 mr-sm-2">
                                <input class="custom-control-input c-p" 
                                v-model="last_day_inclusive" 
                                true-value="1"
                                false-value="0"
                                type="checkbox" 
                                id="last_day_inclusive">
                                <label class="custom-control-label c-p" 
                                for="last_day_inclusive">
                                    El {{ getDayTo() }} inclusive
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" v-show="from == '' && to == ''">
                    <div class="card-header">
                        Solo las de una fecha
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="only_one_date">Fecha</label>
                            <input type="date" id="only_one_date" v-model="only_one_date" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" @click="fromDate">Buscar</button>
            </div>
        </div>
    </div>
</div>
</template>
<script>
export default {
	data() {
		return {
			from: '',
			to: '',
            only_one_date: '',
            last_day_inclusive: 1,
		}
	},
	methods: {
		fromDate() {
            if (this.only_one_date != '') {
                this.$emit('onlyOneDate', this.only_one_date)
                this.resetInputs()
            } else {
                this.$emit('fromDate', this.from, this.to, this.last_day_inclusive)
                this.resetInputs()
            }
		},
        getDayTo() {
            return this.to.split('-')[2]
        },
        resetInputs() {
            this.from = ''
            this.to = ''
            this.only_one_date = ''
        }
	}
}
</script>
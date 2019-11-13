<template>

<!-- Modal -->
<div class="modal fade" id="clients" tabindex="-1" role="dialog" aria-labelledby="ventas-resumens" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Mis Clientes</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Agregar un nuevo Cliente
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="client-name">Nombre del cliente</label>
                            <input type="text" 
                                    class="form-control"
                                    placeholder="Nombre del nuevo cliente" 
                                    v-model="client.name">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button @click="saveClient"
                                class="btn btn-primary float-right">
                            <i class="icon-check"></i>
                            Guardar cliente
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-t-10" v-show="clients.length">
            <div class="col">
                <ul class="list-group">
                    <li class="list-group-item active p-t-5 p-b-5">Mis clientes</li>
                    <li class="list-group-item p-t-5 p-b-5" v-for="client_ in clients">
                        {{ client_.name }} - {{ since(client_.created_at) }}
                        <button @click="deleteClient(client_)"
                                class="btn btn-danger btn-sm float-right">
                            <i class="icon-trash"></i>
                            Eliminar
                        </button>
                    </li>
                </ul>
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
    props: ['clients'],
    data() {
        return {
            client: {name: ''},
        }
    },
    methods: {
        since(d) {
            return moment(d).fromNow()
        },
        saveClient() {
            axios.post('clients', {
                client: this.client
            })
            .then(res => {
                this.client.name = ''
                this.$emit('getClients')
                this.$emit('setClient', res.data)
                $('#clients').modal('hide')
                toastr.success('Cliente guardado correctamente')
            })
            .catch(err => {
                console.log(err)
            })
        },
        deleteClient(client) {
            axios.delete('clients/'+client.id)
            .then(res => {
                this.$emit('getClients')
                toastr.success('Cliente eliminado correctamente')
            })
            .catch(err => {
                console.log(err)
            })
        }
    }
}
</script>
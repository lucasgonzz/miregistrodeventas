<template>
<div class="modal fade" id="providers-history" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Historial de proveedores de {{ article.name}}</h5>
				<button class="close" data-dismiss="modal" arial-label="Close">
					<i class="icon-cancel"></i>
				</button>
			</div>
			<div class="modal-body">
				<ul class="list-group">
					<li class="list-group-item" v-for="provider in article.providers">
						<p class="m-t-0 m-b-0">
							<strong>
								{{ provider.name }}
							</strong> 
							<span v-if="provider.pivot.amount">
								se le compraron {{ provider.pivot.amount }}
							</span>
							<span class="float-right" v-if="provider.pivot.cost">
								costo: ${{ provider.pivot.cost }}
							</span>
						</p>
						<p class="m-t-0 m-b-0" v-if="provider.pivot.created_at">
							 el {{ date(provider.pivot.created_at) }} hace {{ since(provider.pivot.created_at) }}
							<span class="float-right" v-if="provider.pivot.price">
							 	precio: ${{ provider.pivot.price }}
							</span>
						</p>
					</li>
				</ul>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
</template>
<script>
export default {
	props: ['article'],
	methods: {
		date(d) {
			return moment(d).format('DD/MM/YY')
		},
		since(d) {
			return moment(d).fromNow()
		}
	}
}
</script>
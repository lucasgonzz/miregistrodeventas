<template>
<div class="card m-b-20" v-show="is_filter">
	<div class="card-header">
		<h4 class="h4 m-b-0">Filtros</h4>
	</div>
	<div class="card-body">
		<div class="row m-b-10" v-show="is_filter">
			<div class="col-12 col-lg-3" v-show="filtro.mostrar != 'todos'">
				<ul class="list-group">
					<li class="list-group-item active">Mostrando solo artículos que</li>
					<li v-show="filtro.mostrar == 'oferta'" class="list-group-item">
						Esten en oferta
					</li>
					<li v-show="filtro.mostrar == 'marker'" class="list-group-item">
						Esten en marcadores
					</li>
					<li v-show="filtro.mostrar == 'desactualizados'" class="list-group-item">
						Esten desactualizados
					</li>
					<li v-show="filtro.mostrar == 'no-vendidos'" class="list-group-item">
						No se han vendido
					</li>
					<li v-show="filtro.mostrar == 'no-stock'" class="list-group-item">
						No tengan stock
					</li>
				</ul>
			</div>
			<div class="col-12 col-lg-3">
				<ul class="list-group">
					<li class="list-group-item active">Ordenados de</li>
					<li v-show="filtro.ordenar == 'nuevos-viejos'" class="list-group-item">
						Mas nuevos a mas viejos
					</li>
					<li v-show="filtro.ordenar == 'viejos-nuevos'" class="list-group-item">
						Mas viejos a mas nuevos
					</li>
					<li v-show="filtro.ordenar == 'caros-baratos'" class="list-group-item">
						Mas caros a mas baratos
					</li>
					<li v-show="filtro.ordenar == 'baratos-caros'" class="list-group-item">
						Mas baratos a mas caros
					</li>
				</ul>
			</div>
			<div class="col-12 col-lg-3" v-show="filtro.providers.length != providers.length">
				<ul class="list-group">
					<li class="list-group-item active">De los proveedores</li>
					<li v-for="provider in filtro.providers" class="list-group-item">
						{{ provider }}
					</li>
				</ul>
			</div>
			<div class="col-12 col-lg-3" v-show="filtro.precio_entre.min && filtro.precio_entre.max">
				<ul class="list-group">
					<li class="list-group-item active">Con un precio entre</li>
					<li class="list-group-item">
						${{ filtro.precio_entre.min }} y ${{ filtro.precio_entre.max }} 
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="card-footer">
		Artículos filtrados: {{ articlesLength }}
	</div>
</div>
</template>
<script>
export default {
	props: ['filtro', 'is_filter', 'providers', 'articlesLength']
}
</script>
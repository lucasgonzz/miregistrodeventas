<div class="row m-b-10" v-show="filtrado">
	<div class="col-12 col-lg-4" v-show="filtro.mostrar != 'todos'">
		<ul class="list-group">
			<li class="list-group-item active">Mostrando solo artículos que</li>
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
	<div class="col-12 col-lg-4">
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
	<div class="col-12 col-lg-4" v-show="filtro.precio_entre.min && filtro.precio_entre.max">
		<ul class="list-group">
			<li class="list-group-item active">Con un precio entre</li>
			<li class="list-group-item">
				$@{{ filtro.precio_entre.min }} y $@{{ filtro.precio_entre.max }} 
			</li>
		</ul>
	</div>
</div>
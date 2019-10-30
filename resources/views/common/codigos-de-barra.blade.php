@extends('layouts.app')

@section('content')
<div id="codigos-de-barra">
	<div class="row justify-content-center">
		<div class="col-lg-8">	
			<div class="card">
				<div class="card-header">
					Codigos de barra
				</div>
				<form>
					<div class="card-body">
						<!-- <h5 class="card-title">Genere un pdf con los codigos de barra y la cantidad que necesite</h5> -->
						<div class="row">
							<div class="col-12 col-lg-6">
								<div class="card">
									<div class="card-header">
										Genere un nuevo codigo de barras
									</div>
									<div class="card-body">
										<form action="">
											<div class="form-group">
												<label for="bar-code">Nuevo codigo de barras</label>
												<input type="text" placeholder="Ingrese su nuevo codigo de barras" class="form-control">
											</div>
											<div class="form-group">
												<label for="bar-code">Cuantas veces necesita imprimirlo</label>
												<input type="number" min="0" placeholder="Cantidad de veces que se imprimira" class="form-control">
											</div>
										</form>
									</div>
									<div class="card-footer">
										<button class="btn btn-primary">Generar codigo</button>
									</div>
								</div>
							</div>
							<div class="col-12 col-lg-6">
								<ul class="list-group list-bar-codes">
									<li class="list-group-item active">Codigos que he generado</li>
									<li v-for="bar_code in bar_codes" class="list-group-item">
										<p>
											<i class="icon-barcode"></i>
											@{{ bar_code.name }}
											<span class="float-right">
												impreso @{{ bar_code.amount }} veces
											</span>
										</p>
										<span class="float-right">
											@{{ bar_code.created_at }} 
											hace @{{ since(bar_code.created_at) }}
										</span>
									</li>
								</ul>
							</div>
						</div>					
					</div>
					<div class="card-footer p-0">

					</div>
				</form>	
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
new Vue({
	el: '#codigos-de-barra',
	data: {
		bar_codes: [
			{'id': 1, 'name': '3213213', 'created_at': '12/02/2019', 'amount': 7},
			{'id': 2, 'name': '123123', 'created_at': '12/02/2019', 'amount': 2},
			{'id': 3, 'name': '3243244', 'created_at': '12/02/2019', 'amount': 11},
			{'id': 4, 'name': '343443', 'created_at': '12/02/2019', 'amount': 12},
			{'id': 5, 'name': '4354654', 'created_at': '12/02/2019', 'amount': 23},
			{'id': 6, 'name': '4553545', 'created_at': '12/02/2019', 'amount': 12},
			{'id': 7, 'name': '6777644', 'created_at': '12/02/2019', 'amount': 12},
			{'id': 8, 'name': '264356574', 'created_at': '12/02/2019', 'amount': 5},
		],
	},
	methods: {
		since(date) {
			return moment(date).fromNow()
		}
	}
})
</script>
@endsection
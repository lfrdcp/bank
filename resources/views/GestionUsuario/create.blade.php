@extends('layouts.app')
@section('title','crear')
@section('content')
<div class="container">
	<div class="card">
		<div class="card-body">
			<form method="POST" action="/cuenta" enctype="multipart/form-data">
				@csrf

				<div class="form-group has-success" style="padding: 30px;">
					<label class="control-label ">Monto</label>
					<input name="monto" type="text" class="form-control valid" >
					{!!$errors->first('monto','<div class="alert alert-warning" role="alert"  >:message</div>')!!}
				</div>

				<div class="form-group has-success" style="padding: 30px;">
					<label class="control-label ">Fecha de pago</label>
					<input name="fechaPago" type="text" class="form-control valid" >
					{!!$errors->first('fechaPago','<div class="alert alert-warning" role="alert"  >:message</div>')!!}
				</div>

				<div style="margin: 10px;">
					<button id="payment-button" type="submit" class="btn btn-success btn-block"  >						
						Listo
					</button>
				</div>

			</form>
		</div>
	</div>
</div>
@endsection

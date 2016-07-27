@extends('accounting.asiento.main')

@section('breadcrumb')	
	<li><a href="{{ route('asientos.index') }}">Asientos contables</a></li>
	<li class="active">{{ $asiento->asiento1_ano }}-{{ $asiento->asiento1_mes }} {{ $asiento->asiento1_numero }}</li>
@stop


@section('module')
	<div class="box box-success" id="asientos-show">
		<div class="box-header with-border">
        	<div class="row">
				<div class="col-md-2 col-sm-6 col-xs-6 text-left">
					<a href="{{ route('asientos.index') }}" class="btn btn-default btn-sm btn-block">{{ trans('app.comeback') }}</a>
				</div>
				@if($asiento->asiento1_preguardado)
					<div class="col-md-2 col-md-offset-8 col-sm-6 col-xs-6 text-right">
						<a href="{{ route('asientos.edit', ['asientos' => $asiento->id]) }}" class="btn btn-primary btn-sm btn-block">{{ trans('app.continue') }}</a>
					</div>
				@endif
			</div>
		</div>

		<div class="box-body">
			<div class="row">
				<div class="form-group col-md-1">
					<label for="asiento1_ano" class="control-label">Año</label>	
					<div>{{ $asiento->asiento1_ano }}</div>
				</div>
				<div class="form-group col-md-1">
					<label for="asiento1_mes" class="control-label">Mes</label>	
					<div>{{ $asiento->asiento1_mes ? config('koi.meses')[$asiento->asiento1_mes] : ''  }}</div>
				</div>
				<div class="form-group col-md-1">
					<label for="asiento1_dia" class="control-label">Día</label>	
					<div>{{ $asiento->asiento1_dia }}</div>
				</div>
				@if($asiento->asiento1_preguardado)
					<div class="form-group col-md-offset-7 col-md-2 text-right">
						<span class="label label-warning">PRE-GUARDADO</span>					
					</div>
				@endif
			</div>

			<div class="row">
				<div class="form-group col-md-3 col-xs-10">
					<label for="asiento1_folder" class="control-label">Folder</label>
					<div>{{ $asiento->folder_nombre }}</div>
				</div>

				<div class="form-group col-md-3">
					<label for="asiento1_documento" class="control-label">Documento</label>
					<div>{{ $asiento->documento_nombre }}</div>
				</div>

				<div class="form-group col-md-2">
					<label for="asiento1_numero" class="control-label">Número</label>
					<div>{{ $asiento->asiento1_numero }}</div>	
				</div>
			</div>

			<div class="row">
				<div class="form-group col-md-9">
					<label for="asiento1_beneficiario" class="control-label">Beneficiario</label>
					<div>
						<a href="{{ route('terceros.show', ['terceros' =>  $asiento->asiento1_beneficiario ]) }}">
							{{ $asiento->tercero_nit }}
						</a>- {{ $asiento->tercero_nombre }}
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-md-9">
					<label for="asiento1_detalle" class="control-label">Detalle</label>
					<div>{{ $asiento->asiento1_detalle }}</div>
				</div>
			</div>

			<div class="box-body table-responsive">
				<table id="browse-detalle-asiento-list" class="table table-hover table-bordered" cellspacing="0" width="100%">
		            <tr>
		                <th>Cuenta</th>
		                <th>Nombre</th>
		                <th>Beneficiario</th>
		                <th>Centro Costo</th>
		                <th>Base</th>
		                <th>Debito</th>
		                <th>Credito</th>
		                <th>Detalle</th>
		            </tr>
			    </table>
			</div>
		</div>
	</div>
@stop
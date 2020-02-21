@extends('layout.layout')

@section('title') Reporte mayor y balance @stop

@section('content')
    <section class="content-header">
		<h1>
			Reporte Mayor y Balance
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('app.home') }}</a></li>
			<li class="active">Reporte Mayor y Balance</li>
		</ol>
    </section>

   	<section class="content">
	    <div class="box box-success">
	    	<form action="{{ route('rmayorbalance.index') }}" method="GET" data-toggle="validator">
			 	<input class="hidden" id="type-report-koi-component" name="type"></input>
				<div class="box-body">
					<div class="row">
						<label for="cuenta_inicio" class="col-sm-2 col-md-offset-2 control-label text-right">Cuenta Inicio</label>
						<div class="form-group col-sm-3 col-md-2">
				      		<div class="input-group input-group-sm">
								<span class="input-group-btn">
									<button type="button" class="btn btn-default btn-flat btn-koi-search-plancuenta-component" data-field="cuenta_inicio">
										<i class="fa fa-tasks"></i>
									</button>
								</span>
								<input id="cuenta_inicio" placeholder="Cuenta" class="form-control plancuenta-koi-component" name="cuenta_inicio" type="text" maxlength="15" data-name="cuenta_inicio_nombre">
							</div>
						</div>
						<div class="col-sm-6 col-md-4">
							<input id="cuenta_inicio_nombre" name="cuenta_inicio_nombre" placeholder="Nombre cuenta" class="form-control input-sm" type="text" maxlength="15" disabled>
						</div>
					</div>
					<div class="row">
						<label for="cuenta_fin" class="col-sm-2 col-md-offset-2 control-label text-right">Cuenta Final</label>
						<div class="form-group col-sm-3 col-md-2">
				      		<div class="input-group input-group-sm">
								<span class="input-group-btn">
									<button type="button" class="btn btn-default btn-flat btn-koi-search-plancuenta-component" data-field="cuenta_fin">
										<i class="fa fa-tasks"></i>
									</button>
								</span>
								<input id="cuenta_fin" placeholder="Cuenta" class="form-control plancuenta-koi-component" name="cuenta_fin" type="text" maxlength="15" data-name="cuenta_fin_nombre">
							</div>
						</div>
						<div class="col-sm-6 col-md-4">
							<input id="cuenta_fin_nombre" name="cuenta_fin_nombre" placeholder="Nombre cuenta" class="form-control input-sm" type="text" maxlength="15" disabled>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-offset-4 col-sm-offset-4 col-xs-6 col-sm-3 col-md-2">
							<label for="mes" class="control-label">Mes</label>
							<select name="mes" id="mes" class="form-control" required>
								@foreach (config('koi.meses') as $key => $value)
									<option value="{{ $key }}" {{ $key == date('m') ? 'selected' : '' }}>{{ $value }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group col-xs-6 col-sm-3 col-md-2">
							<label for="ano" class="control-label">Año</label>
							<select name="ano" id="ano" class="form-control" required>
								@for ($i = config('koi.app.ano'); $i <= date('Y'); $i++)
									<option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
								@endfor
							</select>
						</div>
					</div>
				</div>
                @include('partials.buttons', ['type' => 'exportar', 'module' => 'rmayorbalance'])
			</form>
		</div>
	</section>
@stop

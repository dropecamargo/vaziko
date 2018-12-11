@extends('production.ordenes.productos.main')

@section('module')
	<section class="content-header">
		<h1>
			Ordenes de producción <small>Administración de ordenes de producción</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('ordenes.index') }}">Ordenes</a></li>
			<li><a href="{{ route('ordenes.edit', ['ordenes' => $orden->id]) }}">{{ $orden->orden_codigo }}</a></li>
			<li class="active">Producto</li>
		</ol>
	</section>

	<section class="content">
		<div class="box box-success" id="ordenes-productos-show">
			<div class="box-header with-border">
	            <div class="row">
	                <div class="col-md-2 col-sm-6 col-xs-6 text-left">
						<a href="{{ route('ordenes.show', ['ordenes' => $orden->id]) }}" class="btn btn-default btn-sm btn-block">{{ trans('app.comeback') }}</a>
	                </div>
	            </div>
	        </div>
			<div class="box-body">
				<div class="alert alert-info">
					<h4><b>Información general</b></h4>
					<div class="row">
						<label class="col-md-2 control-label">Referencia</label>
						<div class="form-group col-md-10">
							{{ $orden->orden_referencia }}
						</div>
					</div>

					<div class="row">
						<label class="col-md-2 control-label">Cliente</label>
						<div class="form-group col-md-10">
							{{ $orden->tercero_nit }} - {{ $orden->tercero_nombre }}
						</div>
					</div>

					<div class="row">
						<label class="col-md-2 control-label">Orden</label>
						<div class="form-group col-md-10">
							{{ $orden->orden_codigo }}
						</div>
					</div>

					<div class="row">
						<label class="col-md-2 control-label">Código producto</label>
						<div class="form-group col-md-10">
							{{ $producto->id }}
						</div>
					</div>

					<div class="row">
						<label class="col-md-2 control-label">Producto</label>
						<div class="form-group col-md-10">
							{{ $ordenp2->productop_nombre }}
						</div>
					</div>
				</div>

				<div class="row">
					<label class="control-label col-md-1">Referencia</label>
					<div class="form-group col-md-8">
						<div>{{ $ordenp2->orden2_referencia }}</div>
					</div>
					<label class="control-label col-md-1">Cantidad</label>
					<div class="form-group col-md-1">
						<div>{{ $ordenp2->orden2_cantidad }}</div>
					</div>
				</div>

				<div class="row">
					<label class="col-sm-1 control-label">Observaciones</label>
					<div class="form-group col-md-11">
						<textarea placeholder="Observaciones" class="form-control" rows="2" disabled>{{ $ordenp2->orden2_observaciones }}</textarea>
					</div>
				</div><br>

				@if($producto->productop_abierto || $producto->productop_cerrado)
					<div class="box box-primary">
						<div class="box-body">
							@if($producto->productop_abierto)
								<div class="row">
									<label class="col-sm-offset-1 col-sm-1 control-label">Abierto</label>
									<label for="orden2_ancho" class="col-sm-1 control-label text-right">Ancho</label>
									<div class="form-group col-md-3">
										<div class="col-md-9">
											{{ $ordenp2->orden2_ancho }}
										</div>
										<div class="col-md-3 text-left">{{ $producto->m1_sigla }}</div>
									</div>

									<label for="orden2_alto" class="col-sm-1 control-label text-right">Alto</label>
									<div class="form-group col-md-3">
										<div class="col-md-9">
											{{ $ordenp2->orden2_alto }}
										</div>
										<div class="col-md-3 text-left">{{ $producto->m2_sigla }}</div>
									</div>
								</div>
							@endif

							@if($producto->productop_cerrado)
								<div class="row">
									<label class="col-sm-offset-1 col-sm-1 control-label">Cerrado</label>
									<label for="orden2_c_ancho" class="col-sm-1 control-label text-right">Ancho</label>
									<div class="form-group col-md-3">
										<div class="col-md-9">
											{{ $ordenp2->orden2_c_ancho }}
										</div>
										<div class="col-md-3 text-left">{{ $producto->m3_sigla }}</div>
									</div>

									<label for="orden2_c_alto" class="col-sm-1 control-label text-right">Alto</label>
									<div class="form-group col-md-3">
										<div class="col-md-9">
											{{ $ordenp2->orden2_c_alto }}
										</div>
										<div class="col-md-3 text-left">{{ $producto->m4_sigla }}</div>
									</div>
								</div>
							@endif
						</div>
					</div>
				@endif

				@if($producto->productop_3d)
					<div class="box box-primary">
						<div class="box-body">
							<div class="row">
								<label class="col-sm-offset-1 col-sm-1 control-label">3D</label>
								<label for="orden2_3d_ancho" class="col-sm-1 control-label text-right">Ancho</label>
								<div class="form-group col-md-2">
									<div class="col-md-9">
										{{ $ordenp2->orden2_3d_ancho }}
									</div>
									<div class="col-md-3 text-left">{{ $producto->m5_sigla }}</div>
								</div>

								<label for="orden2_3d_alto" class="col-sm-1 control-label text-right">Alto</label>
								<div class="form-group col-md-2">
									<div class="col-md-9">
										{{ $ordenp2->orden2_3d_alto }}
									</div>
									<div class="col-md-3 text-left">{{ $producto->m6_sigla }}</div>
								</div>

								<label for="orden2_3d_profundidad" class="col-sm-1 control-label text-right">Profundidad</label>
								<div class="form-group col-md-2">
									<div class="col-md-9">
										{{ $ordenp2->orden2_3d_profundidad }}
									</div>
									<div class="col-md-3 text-left">{{ $producto->m7_sigla }}</div>
								</div>
							</div>
						</div>
					</div>
				@endif

				@if($producto->productop_tiro || $producto->productop_retiro)
					<div class="box box-primary">
						<div class="box-body">
							<div class="row">
								<label class="col-sm-offset-2 col-sm-1 col-xs-offset-2 col-xs-1 control-label"></label>
								<label class="col-sm-1 col-xs-1 control-label">C</label>
								<label class="col-sm-1 col-xs-1 control-label">M</label>
								<label class="col-sm-1 col-xs-1 control-label">Y</label>
								<label class="col-sm-1 col-xs-1 control-label">K</label>
								<label class="col-sm-1 col-xs-1 control-label">P1</label>
								<label class="col-sm-1 col-xs-1 control-label">P2</label>
							</div>

							@if($producto->productop_tiro)
								<div class="row">
									<div class="col-sm-offset-2 col-md-1 col-xs-3">
										<label for="orden2_tiro" class="control-label">T</label>
										<input type="checkbox" disabled id="orden2_tiro" name="orden2_tiro" value="orden2_tiro" {{ $ordenp2->orden2_tiro ? 'checked': '' }}>
									</div>
									<div class="col-md-1 col-xs-1">
										<input type="checkbox" disabled id="orden2_yellow" name="orden2_yellow" value="orden2_yellow" {{ $ordenp2->orden2_yellow ? 'checked': '' }}>
									</div>
									<div class="col-md-1 col-xs-1">
										<input type="checkbox" disabled id="orden2_magenta" name="orden2_magenta" value="orden2_magenta" {{ $ordenp2->orden2_magenta ? 'checked': '' }}>
									</div>
									<div class="col-md-1 col-xs-1">
										<input type="checkbox" disabled id="orden2_cyan" name="orden2_cyan" value="orden2_cyan" {{ $ordenp2->orden2_cyan ? 'checked': '' }}>
									</div>
									<div class="col-md-1 col-xs-1">
										<input type="checkbox" disabled id="orden2_key" name="orden2_key" value="orden2_key" {{ $ordenp2->orden2_key ? 'checked': '' }}>
									</div>
									<div class="col-md-1 col-xs-1">
										<input type="checkbox" disabled id="orden2_color1" name="orden2_color1" value="orden2_color1" {{ $ordenp2->orden2_color1 ? 'checked': '' }}>
									</div>
									<div class="col-md-1 col-xs-1">
										<input type="checkbox" disabled id="orden2_color2" name="orden2_color2" value="orden2_color2" {{ $ordenp2->orden2_color2 ? 'checked': '' }}>
									</div>
								</div>
							@endif

							@if($producto->productop_retiro)
								<div class="row">
									<div class="col-sm-offset-2 col-md-1 col-xs-3">
										<label for="orden2_retiro" class="control-label">R</label>
										<input type="checkbox" disabled id="orden2_retiro" name="orden2_retiro" value="orden2_retiro" {{ $ordenp2->orden2_retiro ? 'checked': '' }}>
									</div>
									<div class="col-md-1 col-xs-1">
										<input type="checkbox" disabled id="orden2_yellow2" name="orden2_yellow2" value="orden2_yellow2" {{ $ordenp2->orden2_yellow2 ? 'checked': '' }}>
									</div>
									<div class="col-md-1 col-xs-1">
										<input type="checkbox" disabled id="orden2_magenta2" name="orden2_magenta2" value="orden2_magenta2" {{ $ordenp2->orden2_magenta2 ? 'checked': '' }}>
									</div>
									<div class="col-md-1 col-xs-1">
										<input type="checkbox" disabled id="orden2_cyan2" name="orden2_cyan2" value="orden2_cyan2" {{ $ordenp2->orden2_cyan2 ? 'checked': '' }}>
									</div>
									<div class="col-md-1 col-xs-1">
										<input type="checkbox" disabled id="orden2_key2" name="orden2_key2" value="orden2_key2" {{ $ordenp2->orden2_key2 ? 'checked': '' }}>
									</div>
									<div class="col-md-1 col-xs-1">
										<input type="checkbox" disabled id="orden2_color12" name="orden2_color12" value="orden2_color12" {{ $ordenp2->orden2_color12 ? 'checked': '' }}>
									</div>
									<div class="col-md-1 col-xs-1">
										<input type="checkbox" disabled id="orden2_color22" name="orden2_color22" value="orden2_color22" {{ $ordenp2->orden2_color22 ? 'checked': '' }}>
									</div>
								</div>
							@endif

							<div class="row">
								@if($producto->productop_tiro)
									<div class="form-group @if($producto->productop_tiro && $producto->productop_retiro) col-sm-6 @else col-sm-12 @endif">
										<label for="orden2_nota_tiro" class="control-label">Nota tiro</label>
										<div>{{ $ordenp2->orden2_nota_tiro }}</div>
									</div>
								@endif

								@if($producto->productop_retiro)
									<div class="form-group @if($producto->productop_tiro && $producto->productop_retiro) col-sm-6 @else col-sm-12 @endif">
										<label for="orden2_nota_retiro" class="control-label">Nota retiro</label>
										<div>{{ $ordenp2->orden2_nota_retiro }}</div>
									</div>
								@endif
							</div>
						</div>
					</div>
				@endif

				<div class="row">
					{{-- Content maquinas --}}
					<div class="col-sm-6">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Máquinas de producción</h3>
							</div>
							<div class="box-body">
								@foreach( App\Models\Production\Ordenp3::getOrdenesp3($producto->id, $ordenp2->id) as $maquina)
									<div class="row">
										<div class="form-group col-md-12">
											<label class="checkbox-inline without-padding white-space-normal" for="orden3_maquinap_{{ $maquina->id }}">
												<input type="checkbox" id="orden3_maquinap_{{ $maquina->id }}" name="orden3_maquinap_{{ $maquina->id }}" value="orden3_maquinap_{{ $maquina->id }}" {{ $maquina->activo ? 'checked': '' }} disabled> {{ $maquina->maquinap_nombre }}
											</label>
										</div>
									</div>
								@endforeach
							</div>
						</div>
					</div>

					{{-- Content acabados --}}
					<div class="col-sm-6">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Acabados de producción</h3>
							</div>
							<div class="box-body">
								@foreach( App\Models\Production\Ordenp5::getOrdenesp5($producto->id, $ordenp2->id) as $acabado)
									<div class="row">
										<div class="form-group col-md-12">
											<label class="checkbox-inline without-padding white-space-normal" for="orden5_acabadop_{{ $acabado->id }}">
												<input type="checkbox" id="orden5_acabadop_{{ $acabado->id }}" name="orden5_acabadop_{{ $acabado->id }}" value="orden5_acabadop_{{ $acabado->id }}" {{ $acabado->activo ? 'checked': '' }} disabled> {{ $acabado->acabadop_nombre }}
											</label>
										</div>
									</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>

				@if( Auth::user()->ability('admin', 'opcional2', ['module' => 'ordenes']) )
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Fórmulas</h3>
						</div>
						<div class="box-body">
							<div class="row">
								<label class="control-label col-md-1">Fórmula</label>
								<div class="form-group col-md-6">
									<div>{{ $ordenp2->orden2_precio_formula }}</div>
								</div>
								<label class="control-label col-md-1">Precio</label>
								<div class="form-group col-md-4">
									<div>{{ number_format($ordenp2->orden2_precio_venta, 2, ',', '.') }}</div>
								</div>
							</div>
							<div class="row">
								<label class="control-label col-md-1">Fórmula</label>
								<div class="form-group col-md-6">
									<div>{{ $ordenp2->orden2_transporte_formula }}</div>
								</div>
								<label class="control-label col-md-1">Transporte</label>
								<div class="form-group col-md-4">
									<div>{{ number_format($ordenp2->orden2_transporte, 2, ',', '.') }}</div>
								</div>
							</div>
							<div class="row">
								<label class="control-label col-md-1">Fórmula</label>
								<div class="form-group col-md-6">
									<div>{{ $ordenp2->orden2_viaticos_formula }}</div>
								</div>
								<label class="control-label col-md-1">Viaticos</label>
								<div class="form-group col-md-4">
									<div>{{ number_format($ordenp2->orden2_viaticos, 2, ',', '.') }}</div>
								</div>
							</div>
						</div>
					</div>
				@endif

				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Imágenes</h3>
					</div>
					<div class="box-body table-responsive no-padding">
						<div class="fine-uploader"></div>
					</div>
				</div>

				@if( $orden->cotizacion1_precotizacion )
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Impresiones</h3>
						</div>
						<div class="box-body table-responsive no-padding">
							<table id="browse-orden-producto-impresiones-list" class="table table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th width="70%">Detalle</th>
										<th width="15%">Ancho</th>
										<th width="15%">Alto</th>
									</tr>
								</thead>
								<tbody>
									@foreach( App\Models\Production\Ordenp7::getOrdenesp7( $ordenp2->id ) as $impresion )
										<tr>
											<td class="text-left">{{ $impresion->orden7_texto }}</td>
											<td class="text-left">{{ $impresion->orden7_ancho }}</td>
											<td class="text-left">{{ $impresion->orden7_alto }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				@endif

				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Materiales de producción</h3>
					</div>
					<div class="box-body table-responsive no-padding">
						<table id="browse-orden-producto-materiales-list" class="table table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th width="20%">Material</th>
									<th width="20%">Insumo</th>
									<th width="10%">Dimensiones</th>
									<th width="5%">Cantidad</th>
									<th width="15%">Valor unidad</th>
									<th width="15%">Valor</th>
								</tr>
							</thead>
							<tbody>
								{{--*/ $totalmaterialesp = 0; /*--}}
								@foreach( App\Models\Production\Ordenp4::getOrdenesp4( $ordenp2->id ) as $materialp )
									<tr>
										<td>{{ $materialp->materialp_nombre }}</td>
										<td>{!! isset($materialp->producto_nombre) ? $materialp->producto_nombre : "-" !!}</td>
										<td>{{ $materialp->orden4_medidas }}</td>
										<td class="text-center">{{ $materialp->orden4_cantidad }}</td>
										<td class="text-right">{{ number_format($materialp->orden4_valor_unitario, 2, ',', '.') }}</td>
										<td class="text-right">{{ number_format($materialp->orden4_valor_total, 2, ',', '.') }}</td>
									</tr>
									{{--*/ $totalmaterialesp += $materialp->orden4_valor_total; /*--}}
								@endforeach
							</tbody>
							<tfoot>
								<tr>
									<td colspan="4"></td>
									<th class="text-right">Total</th>
									<th class="text-right" id="total">{{ number_format($totalmaterialesp, 2, ',', '.') }}</th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>

				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Áreas de producción</h3>
					</div>
					<div class="box-body">
						<div class="box-body table-responsive no-padding">
							<table id="browse-orden-producto-areas-list" class="table table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Área</th>
										<th>Nombre</th>
										<th>Horas</th>
										@if( Auth::user()->ability('admin', 'opcional2', ['module' => 'ordenes']) )
											<th>Valor</th>
											<th>Total</th>
										@endif
									</tr>
								</thead>
								<tbody>
									{{-- variables para calcular las areas --}}
									{{--*/ $area = $sumareap = $totalareap = 0; /*--}}
									@foreach( App\Models\Production\Ordenp6::getOrdenesp6($ordenp2->id) as $areap)
										{{--*/
											$tiempo = explode(':', $areap->orden6_tiempo);
											$area = round( ($tiempo[0] + ($tiempo[1] / 60)) * $areap->orden6_valor );
											$sumareap += $area;
											$totalareap = round( $sumareap / $ordenp2->orden2_cantidad );
										/*--}}

										<tr>
											<td>{{ $areap->areap_nombre == '' ? '-': $areap->areap_nombre }}</td>
											<td>{{ $areap->orden6_nombre == '' ? '-': $areap->orden6_nombre }}</td>
											<td class="text-center">{{  $areap->orden6_tiempo }}</td>
											@if( Auth::user()->ability('admin', 'opcional2', ['module' => 'ordenes']) )
												<td class="text-right">{{ number_format($areap->orden6_valor, 2, ',', '.') }}</td>
												<td class="text-right">{{ number_format($area, 2, ',', '.') }}</td>
											@endif
										</tr>
									@endforeach
								</tbody>
								@if( Auth::user()->ability('admin', 'opcional2', ['module' => 'ordenes']) )
									<tfoot>
										<tr>
											<td colspan="3"></td>
											<th class="text-right">Total</th>
											<th class="text-right">{{ number_format($sumareap, 2, ',', '.') }}</th>
										</tr>
									</tfoot>
								@endif
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		@if( Auth::user()->ability('admin', 'opcional2', ['module' => 'ordenes']) )
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="box box-primary">
						{{-- Content informacion --}}
						{{--*/ $subtotal = $total = $transporte = $viaticos = 0; /*--}}

						{{--*/
							$transporte = round( $ordenp2->orden2_transporte / $ordenp2->orden2_cantidad );
							$viaticos = round( $ordenp2->orden2_viaticos / $ordenp2->orden2_cantidad );
							$totalmaterialesp = round( $totalmaterialesp / $ordenp2->orden2_cantidad );
							$totalmaterialesp += $totalmaterialesp*$ordenp2->orden2_margen_materialp / 100;
							$exp = pow(10, $ordenp2->orden2_round_materialp);
							$totalmaterialesp = round($totalmaterialesp*$exp)/$exp;
							$subtotal = $ordenp2->orden2_precio_venta + $transporte + $viaticos + $totalareap;
						/*--}}

						<div class="box-body">
							<div class="list-group">
								<div class="list-group-item list-group-item-info">
									<div class="row">
										<div class="col-md-2"><b>Precio</b></div>
										<div class="col-md-10 text-right"><b>{{ number_format($ordenp2->orden2_precio_venta, 2, ',', '.')}}</b></div>
									</div>
								</div>
								<div class="list-group-item list-group-item-info">
									<div class="row">
										<div class="col-md-2"><b>Transporte</b></div>
										<div class="col-md-10 text-right"><b>{{ number_format($transporte, 2, ',', '.') }}</b></div>
									</div>
								</div>
								<div class="list-group-item list-group-item-info">
									<div class="row">
										<div class="col-md-2"><b>Viáticos</b></div>
										<div class="col-md-10 text-right"><b>{{ number_format($viaticos, 2, ',', '.')}}</b></div>
									</div>
								</div>
								<div class="list-group-item list-group-item-info">
									<div class="row">
										<div class="col-md-2"><b>Materiales</b></div>
										<div class="col-md-2">{{ $ordenp2->orden2_margen_materialp }}</div>
										<div class="col-md-1">%</div>
										<div class="col-md-2"><b>Redondear</b></div>
										<div class="col-md-2">{{ $ordenp2->orden2_round_materialp }}</div>
										<div class="col-md-3 text-right"><b><span>{{ number_format($totalmaterialesp, 2, ',', '.') }}</span></b></div>
									</div>
								</div>
								<div class="list-group-item list-group-item-info">
									<div class="row">
										<div class="col-md-2"><b>Áreas</b></div>
										<div class="col-md-10 text-right"><b><span>{{ number_format($totalareap, 2, ',', '.') }}</span></b></div>
									</div>
								</div>
								<div class="list-group-item list-group-item-success">
									<div class="row">
										<div class="col-md-8"><b>Subtotal</b></div>
										<div class="col-md-4 text-right">
											<span class="pull-right badge bg-red">$ {{ number_format($subtotal, 2, ',', '.') }}</span>
										</div>
									</div>
								</div>
								<div class="list-group-item list-group-item-success">
									<div class="row">
										<div class="col-md-2"><b>Volumen</b></div>
										<div class="col-md-2">{{ $ordenp2->orden2_volumen }}</div>
										<div class="col-md-2"><b>Redondear</b></div>
										<div class="col-md-2">{{ $ordenp2->orden2_round }}</div>
										<div class="col-md-4 text-right">
											<span class="pull-right badge bg-red">$ {{ number_format($ordenp2->orden2_vtotal, 2, ',', '.') }}</span>
										</div>
									</div>
								</div>
								<div class="list-group-item list-group-item-success">
									<div class="row">
										<div class="col-md-8"><b>Total</b></div>
										<div class="col-md-4 text-right">
											<span class="pull-right badge bg-red">$ {{ number_format($ordenp2->orden2_total_valor_unitario, 2, ',', '.') }}</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<p><b>Los campos de transporte, viáticos, materiales y áreas se dividirán por la cantidad ingresada.</b></p>
						</div>
					</div>
				</div>
			</div>
		@endif
	</section>
@stop

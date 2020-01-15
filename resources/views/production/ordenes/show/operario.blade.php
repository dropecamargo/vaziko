<section class="content" id="ordenes-show">
    <div class="box box-primary">
        <div class="box-body bg-primary">
            <div class="row">
                <div class="form-group col-md-2">
                    <label class="control-label">Código</label>
                    <div>
                        {{ $orden->orden_codigo }}
                        @if($orden->orden_anulada)
                            <span class="label label-danger">ANULADA</span>
                        @elseif($orden->orden_abierta)
                            <span class="label label-success">ABIERTA</span>
                        @elseif($orden->orden_culminada)
                            <span class="label bg-blue">CULMINADO</span>
                        @else
                            <span class="label label-warning">CERRADA</span>
                        @endif
                    </div>
                </div>
                @if ($orden->precotizacion_codigo)
                    <div class="form-group col-md-2">
                        <label class="control-label">Pre-cotización</label>
                        <div>
                            <a href="{{ route('precotizaciones.show', ['precotizaciones' => $orden->cotizacion1_precotizacion]) }}" title="Ir a precotización">{{ $orden->precotizacion_codigo }}</a>
                        </div>
                    </div>
                @endif
                @if ($orden->cotizacion_codigo)
                    <div class="form-group col-md-2">
                        <label class="control-label">Cotización</label>
                        <div>
                            <a href="{{ route('cotizaciones.show', ['cotizaciones' => $orden->orden_cotizacion]) }}" title="Ir a cotización">{{ $orden->cotizacion_codigo }}</a>
                        </div>
                    </div>
                @endif
                <div class="form-group col-md-5">
                    <label class="control-label">Referencia</label>
                    <div>{{ $orden->orden_referencia }}</div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-3">
                    <label class="control-label">F. Inicio</label>
                    <div>{{ $orden->orden_fecha_inicio }}</div>
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">F. Entrega</label>
                    <div>{{ $orden->orden_fecha_entrega }}</div>
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">H. Entrega</label>
                    <div>{{ $orden->orden_hora_entrega }}</div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-9">
                    <label class="control-label">Cliente</label>
                    <div>
                        <a href="{{ route('terceros.show', ['terceros' =>  $orden->orden_cliente ]) }}">
                            {{ $orden->tercero_nit }}
                        </a>- {{ $orden->tercero_nombre }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label">Contacto</label>
                    <div>{{ $orden->tcontacto_nombre }}</div>
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Teléfono</label>
                    <div>{{ $orden->tcontacto_telefono }}</div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label">Suministran</label>
                    <div>{{ $orden->orden_suministran }}</div>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">Forma pago</label>
                    <div>{{ $orden->orden_formapago }}</div>
                </div>
            </div>
            @if ($orden->orden_observaciones)
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="control-label">Detalle</label>
                        <div>{{ $orden->orden_observaciones }}</div>
                    </div>
                </div>
            @endif
            @if ($orden->orden_terminado)
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="control-label">Terminado</label>
                        <div>{{ $orden->orden_terminado }}</div>
                    </div>
                </div>
            @endif
            <div class="row">
                @if ($orden->orden_fecha_recogida1)
                    <div class="form-group col-md-3">
                        <label class="control-label">F. Recogida #1</label>
                        <div>{{ $orden->orden_fecha_recogida1 }}</div>
                    </div>
                @endif
                @if ($orden->orden_hora_recogida1)
                    <div class="form-group col-md-3">
                        <label class="control-label">H. Recogida #1</label>
                        <div>{{ $orden->orden_hora_recogida1 }}</div>
                    </div>
                @endif
                @if ($orden->orden_fecha_recogida2)
                    <div class="form-group col-md-3">
                        <label class="control-label">F. Recogida #2</label>
                        <div>{{ $orden->orden_fecha_recogida2 }}</div>
                    </div>
                @endif
                @if ($orden->orden_hora_recogida2)
                    <div class="form-group col-md-3">
                        <label class="control-label">H. Recogida #2</label>
                        <div>{{ $orden->orden_hora_recogida2 }}</div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="form-group col-md-2">
                    <label class="control-label">Usuario elaboro</label>
                    <div>
                        <a href="{{ route('terceros.show', ['terceros' =>  $orden->orden_usuario_elaboro ]) }}" title="Ver tercero">
                            {{ $orden->username_elaboro }}</a>
                    </div>
                </div>
                <div class="form-group col-md-2">
                    <label class="control-label">Fecha elaboro</label>
                    <div>{{ $orden->orden_fecha_elaboro }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-primary spinner-main">
        <div class="box-header with-border">
            <h3 class="box-title title-producto-show"></b></h3>
            <div class="box-tools pull-right">
                <a href="#" class="btn btn-md btn-primary producto-pagination" data-action="P" disabled>
                    <i class="fa fa-chevron-circle-left"></i>
                </a>
                <a href="#" class="btn btn-md btn-primary producto-pagination" data-action="N">
                    <i class="fa fa-chevron-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="box-body" id="wrapper-show-producto">
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Quienes trabajaron en esta orden</h3>
        </div>
        <div class="box-body">
            <table id="browse-tiemposp-global-list" class="table table-bordered" cellspacing="0">
                <thead>
                    <tr>
                        <th width="20%">Tercero</th>
                        <th width="6%">Fecha</th>
                        <th width="5%">H. inicio</th>
                        <th width="5%">H. fin</th>
                        <th width="15%">Actividad</th>
                        <th width="20%">Subactividad</th>
                        <th width="25%">Área</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th colspan="7" class="text-center">Ningún dato disponible en esta tabla.</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box-footer with-border">
        <div class="row">
            <div class="col-md-2 col-md-offset-5 col-sm-6 col-xs-6">
                <a href="{{ route('ordenes.index') }}" class="btn btn-default btn-sm btn-block">{{ trans('app.comeback') }}</a>
            </div>
        </div>
    </div>
</section>

<script type="text/template" id="ordenp-tiempop-item-list-tpl">
    <td><%- tercero_nombre %></td>
    <td><%- tiempop_fecha %></td>
    <td><%- moment(tiempop_hora_inicio, 'HH:mm').format('HH:mm') %></td>
    <td><%- moment(tiempop_hora_fin, 'H:mm').format('H:mm') %></td>
    <td><%- actividadp_nombre %></td>
    <td><%- subactividadp_nombre || '-' %></td>
    <td><%- areap_nombre %></td>
</script>
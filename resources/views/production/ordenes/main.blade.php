@extends('layout.layout')

@section('title') Ordenes de producción @stop

@section('content')
    @yield ('module')

    <script type="text/template" id="add-ordenp-tpl">
        <section class="content-header">
            <h1>
                Ordenes de producción <small>Administración de ordenes de producción</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{trans('app.home')}}</a></li>
                <li><a href="{{ route('ordenes.index') }}">Orden</a></li>
                <% if( !_.isUndefined(edit) && !_.isNull(edit) && edit) { %>
                    <li><a href="<%- window.Misc.urlFull( Route.route('ordenes.show', { ordenes: id}) ) %>"><%- orden_codigo %></a></li>
                    <li class="active">Editar</li>
                <% }else{ %>
                    <li class="active">Nuevo</li>
                <% } %>
            </ol>
        </section>

        <section class="content">
            <div class="box box-solid" id="spinner-main">
                <div class="nav-tabs-custom tab-success tab-whithout-box-shadow">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_orden" data-toggle="tab">Orden</a></li>
                        <% if( !_.isUndefined(edit) && !_.isNull(edit) && edit) { %>
                            <li><a href="#tab_despachos" data-toggle="tab">Distribución por clientes</a></li>
                            <li><a href="#tab_contabilidad" data-toggle="tab">Contabilidad</a></li>
                            @if( Auth::user()->ability('admin', 'opcional2', ['module' => 'ordenes']) )
                                <li><a href="#tab_tiemposp" data-toggle="tab">Tiempos de producción</a></li>
                                <li><a href="#tab_tiempospcharts" data-toggle="tab">Gráficas de producción</a></li>
                                <li><a href="#tab_imagenes" data-toggle="tab">Imágenes de producción</a></li>
                            @endif
                            <li class="pull-right">
                                <button type="button" class="btn btn-block btn-danger btn-sm export-ordenp">
                                    <i class="fa fa-file-pdf-o"></i>
                                </button>
                            </li>
                            <li class="dropdown pull-right">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    Opciones <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li role="presentation">
                                        <a role="menuitem" tabindex="-1" href="#" class="close-ordenp">
                                            <i class="fa fa-lock"></i>Cerrar orden
                                        </a>
                                        @if( Auth::user()->ability('admin', 'crear', ['module' => 'ordenes']) )
                                            <a role="menuitem" tabindex="-1" href="#" class="complete-ordenp">
                                                <i class="fa fa-handshake-o"></i>Culminar orden
                                            </a>
                                            <a role="menuitem" tabindex="-1" href="#" class="clone-ordenp">
                                                <i class="fa fa-clone"></i>Clonar orden
                                            </a>
                                        @endif
                                        <a role="menuitem" tabindex="-1" href="#" class="export-ordenp">
                                            <i class="fa fa-file-pdf-o"></i>Exportar
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <% } %>
                    </ul>
                    <div class="tab-content">
                        {{-- Content orden --}}
                        <div class="tab-pane active" id="tab_orden">
                            <div class="box box-whithout-border">
                                <div class="box-body">
                                    <form method="POST" accept-charset="UTF-8" id="form-ordenes" data-toggle="validator">
                                        <div class="row">
                                            <% if( typeof(orden_codigo) !== 'undefined' && !_.isUndefined(orden_codigo) && !_.isNull(orden_codigo) && orden_codigo != '') { %>
                                                <label class="col-sm-1 control-label">Código</label>
                                                <div class="form-group col-md-1">
                                                    <%- orden_codigo %>
                                                </div>
                                            <% } %>
                                            <% if( typeof(precotizacion_codigo) !== 'undefined' && !_.isUndefined(precotizacion_codigo) && !_.isNull(precotizacion_codigo) && precotizacion_codigo != '') { %>
                                                <label class="col-sm-1 control-label">Pre-cotización</label>
                                                <div class="form-group col-md-1">
                                                    <%- precotizacion_codigo %>
                                                </div>
                                            <% } %>
                                            <% if( typeof(cotizacion_codigo) !== 'undefined' && !_.isUndefined(cotizacion_codigo) && !_.isNull(cotizacion_codigo) && cotizacion_codigo != '') { %>
                                                <label class="col-sm-1 control-label">Cotización</label>
                                                <div class="form-group col-md-1">
                                                    <%- cotizacion_codigo %>
                                                </div>
                                            <% } %>

                                            <label for="orden_referencia" class="col-sm-1 control-label">Referencia</label>
                                            <div class="form-group col-md-5">
                                                <input id="orden_referencia" value="<%- orden_referencia %>" placeholder="Referencia" class="form-control input-sm input-toupper" name="orden_referencia" type="text" maxlength="200" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="orden_fecha_inicio" class="col-sm-1 control-label">F. Inicio</label>
                                            <div class="form-group col-md-2">
                                                <input type="text" id="orden_fecha_inicio" name="orden_fecha_inicio" placeholder="Fecha inicio" class="form-control input-sm datepicker" value="<%- orden_fecha_inicio %>" required>
                                            </div>

                                            <label for="orden_fecha_entrega" class="col-sm-1 control-label">F. Entrega</label>
                                            <div class="form-group col-md-2">
                                                <input type="text" id="orden_fecha_entrega" name="orden_fecha_entrega" placeholder="Fecha entrega" class="form-control input-sm datepicker" value="<%- orden_fecha_entrega %>" required>
                                            </div>

                                            <label for="orden_hora_entrega" class="col-sm-1 control-label">H. Entrega</label>
                                            <div class="form-group col-md-2">
                                                <div class="bootstrap-timepicker">
                                                    <div class="input-group">
                                                        <input type="text" id="orden_hora_entrega" name="orden_hora_entrega" placeholder="Fecha entrega" class="form-control input-sm timepicker" value="<%- orden_hora_entrega %>" required>
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="orden_cliente" class="col-sm-1 control-label">Cliente</label>
                                            <div class="form-group col-sm-3">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-default btn-flat btn-koi-search-tercero-component-table" data-field="orden_cliente">
                                                            <i class="fa fa-user"></i>
                                                        </button>
                                                    </span>
                                                    <input id="orden_cliente" placeholder="Cliente" class="form-control tercero-koi-component" name="orden_cliente" type="text" maxlength="15" data-wrapper="spinner-main" data-name="orden_cliente_nombre" data-contacto="btn-add-contact" data-formapago="orden_formapago" value="<%- tercero_nit %>" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-5 col-xs-10">
                                                <input id="orden_cliente_nombre" name="orden_cliente_nombre" placeholder="Nombre cliente" class="form-control input-sm" type="text" maxlength="15" value="<%- tercero_nombre %>" readonly required>
                                            </div>
                                            <div class="col-sm-1 col-xs-2">
                                                <button type="button" class="btn btn-default btn-flat btn-sm btn-add-resource-koi-component" data-resource="tercero" data-field="orden_cliente">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="tcontacto_nombre" class="col-sm-1 control-label">Contacto</label>
                                            <div class="form-group col-sm-5 col-xs-10">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-default btn-flat btn-koi-search-contacto-component-table" data-field="orden_contacto" data-name="tcontacto_nombre" data-phone="tcontacto_telefono" data-tercero="btn-add-contact">
                                                            <i class="fa fa-address-book"></i>
                                                        </button>
                                                    </span>
                                                    <input id="orden_contacto" name="orden_contacto" type="hidden" value="<%- orden_contacto %>">
                                                    <input id="tcontacto_nombre" placeholder="Contacto" class="form-control" name="tcontacto_nombre" type="text" value="<%- tcontacto_nombre %>" readonly required>
                                                </div>
                                            </div>
                                            <div class="col-sm-1 col-xs-2">
                                                <button type="button" id="btn-add-contact" class="btn btn-default btn-flat btn-sm btn-add-resource-koi-component" data-resource="contacto" data-field="orden_contacto" data-name="tcontacto_nombre" data-tercero="<%- orden_cliente %>" data-phone="tcontacto_telefono" data-address-default="<%- tercero_direccion %>" data-address-nomenclatura-default="<%- tercero_dir_nomenclatura %>" data-municipio-default="<%- tercero_municipio %>">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>

                                            <label for="tcontacto_telefono" class="col-sm-1 control-label">Teléfono</label>
                                            <div class="form-group col-md-3">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-phone"></i>
                                                    </div>
                                                    <input id="tcontacto_telefono" class="form-control input-sm" name="tcontacto_telefono" type="text" data-inputmask="'mask': '(999) 999-99-99'" data-mask value="<%- tcontacto_telefono %>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="orden_suministran" class="col-sm-1 control-label">Suministran</label>
                                            <div class="form-group col-sm-6">
                                                <input id="orden_suministran" placeholder="Suministran" class="form-control" name="orden_suministran" type="text" value="<%- orden_suministran %>" required maxlength="200">
                                            </div>
                                            <label for="orden_formapago" class="col-sm-1 control-label">Forma pago</label>
                                            <div class="form-group col-md-2">
                                                <input type="text" id="orden_formapago" name="orden_formapago" placeholder="Forma de pago" class="form-control input-sm" value="<%- orden_formapago %>" maxlength="30" required readonly>
                                            </div>
                                            <% if( typeof(id) !== 'undefined' && !_.isUndefined(id) && !_.isNull(id) && id != '') { %>
                                                <label for="orden_iva" class="col-sm-1 control-label">Iva</label>
                                                <div class="form-group col-sm-1">
                                                    <select name="orden_iva" id="orden_iva" class="form-control" required>
                                                        @foreach( config('koi.contabilidad.iva') as $key => $value)
                                                        <option value="{{ $key }}" <%- orden_iva == '{{ $key }}' ? 'selected': ''%> >{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            <% } %>
                                        </div>

                                        <div class="row">
                                            <label for="orden_observaciones" class="col-sm-1 control-label">Detalle</label>
                                            <div class="form-group col-sm-11">
                                                <textarea id="orden_observaciones" name="orden_observaciones" class="form-control" rows="2" placeholder="Detalle"><%- orden_observaciones %></textarea>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="orden_terminado" class="col-sm-1 control-label">Terminado</label>
                                            <div class="form-group col-sm-11">
                                                <textarea id="orden_terminado" name="orden_terminado" class="form-control" rows="2" placeholder="Terminado"><%- orden_terminado %></textarea>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="row">
                                        <div class="col-md-2 col-md-offset-4 col-sm-6 col-xs-6">
                                            <a href="{{ route('ordenes.index') }}" class="btn btn-default btn-sm btn-block">{{ trans('app.cancel') }}</a>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-6">
                                            <button type="button" class="btn btn-primary btn-sm btn-block submit-ordenp">{{ trans('app.save') }}</button>
                                        </div>
                                    </div>
                                    <br>

                                    <% if( !_.isUndefined(edit) && !_.isNull(edit) && edit) { %>
                                        <div class="box box-primary">
                                            <div class="box-body">
                                                <form method="GET" accept-charset="UTF-8" id="form-productosp3" data-toggle="validator" action="<%- window.Misc.urlFull( Route.route('ordenes.productos.create') ) %>">
                                                    <div class="row">
                                                        <label for="typeproductop" class="control-label col-sm-1 col-md-offset-2">Tipo </label>
                                                        <div class="form-group col-sm-3 col-xs-11">
                                                            <select name="typeproductop" id="typeproductop" class="form-control select2-default-clear">
                                                                @foreach( App\Models\Production\TipoProductop::getTypeProductsp() as $key => $value)
                                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <label for="subtypeproductop" class="control-label col-sm-1">Subtipo </label>
                                                        <div class="form-group col-sm-3 col-xs-11">
                                                            <select name="subtypeproductop" id="subtypeproductop" class="form-control select2-default" disabled>
                                                                <option value=""></option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <label for="productop" class="control-label col-sm-1 col-md-offset-2">Producto</label>
                                                        <div class="form-group col-sm-6 col-xs-11">
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-btn">
                                                                    <button type="button" class="btn btn-default btn-flat btn-koi-search-productop-component-table" data-field="productop">
                                                                        <i class="fa fa-search"></i>
                                                                    </button>
                                                                </span>
                                                                <input type="hidden" id="ordenp" name="ordenp" value="<%- id %>" required>
                                                                <select name="productop" id="productop" class="form-control select2-default" data-productop="true" required></select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-sm-1">
                                                            <button type="submit" class="btn btn-success btn-sm btn-block">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <!-- table table-bordered table-striped -->
                                                <div class="box-body table-responsive no-padding">
                                                    <table id="browse-orden-productop-list" class="table table-hover table-bordered" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%"></th>
                                                                <th width="5%"></th>
                                                                <th width="5%">Código</th>
                                                                <th width="55%">Nombre</th>
                                                                <th width="10%">Cantidad</th>
                                                                <th width="10%">Facturado</th>
                                                                @if( Auth::user()->ability('admin', 'opcional2', ['module' => 'ordenes']) )
                                                                <th width="10%">Precio</th>
                                                                <th width="10%">Total</th>
                                                                @endif
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {{-- Render content productos --}}
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="3"></td>
                                                                <th class="text-right">Subtotal</th>
                                                                <td class="text-center" id="subtotal-cantidad">0</td>
                                                                <td class="text-center" id="subtotal-facturado">0</td>
                                                                @if( Auth::user()->ability('admin', 'opcional2', ['module' => 'ordenes']) )
                                                                <td></td>
                                                                <td class="text-right" id="subtotal-total">0</td>
                                                                @endif
                                                            </tr>
                                                            @if( Auth::user()->ability('admin', 'opcional2', ['module' => 'ordenes']) )
                                                            <tr>
                                                                <td colspan="3"></td>
                                                                <th class="text-right">Iva (<%- orden_iva %>%)</th>
                                                                <td colspan="4" class="text-right" id="iva-total">0</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3"></td>
                                                                <th class="text-right">Total</th>
                                                                <td colspan="4" class="text-right" id="total-total">0</td>
                                                            </tr>
                                                            @endif
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    <% } %>
                                </div>
                            </div>
                        </div>

                        {{-- Content despachos --}}
                        <% if( !_.isUndefined(edit) && !_.isNull(edit) && edit) { %>
                            <div class="tab-pane" id="tab_despachos">
                                <div class="box box-whithout-border">
                                    <div class="box-body">
                                        <form method="POST" accept-charset="UTF-8" id="form-despachosp" data-toggle="validator">
                                            <div class="row">
                                                <div class="form-group col-sm-8 col-xs-12">
                                                    <label for="despachop1_nombre" class="control-label">Enviar a</label>
                                                    <div class="row">
                                                        <div class="col-sm-11 col-xs-10">
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-btn">
                                                                    <button type="button" class="btn btn-default btn-flat btn-koi-search-contacto-component-table" data-field="despachop1_contacto" data-name="despachop1_nombre" data-phone="despachop1_telefono" data-address="despachop1_direccion" data-nomenclatura="despachop1_direccion_nomenclatura" data-name-nomenclatura="despachop1_nomenclatura" data-city="despachop1_municipio" data-email="despachop1_email" data-tercero="btn-add-contact">
                                                                        <i class="fa fa-address-book"></i>
                                                                    </button>
                                                                </span>
                                                                <input id="despachop1_contacto" name="despachop1_contacto" type="hidden">
                                                                <input id="despachop1_nombre" placeholder="Contacto" class="form-control" name="despachop1_nombre" type="text" readonly required>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-1 col-xs-2">
                                                            <button type="button" id="btn-add-contact" class="btn btn-default btn-flat btn-sm btn-add-resource-koi-component" data-resource="contacto" data-field="despachop1_contacto" data-name="despachop1_nombre" data-tercero="<%- orden_cliente %>" data-phone="despachop1_telefono" data-address="despachop1_direccion" data-name-nomenclatura="despachop1_nomenclatura" data-city="despachop1_municipio" data-email="despachop1_email" data-address-default="<%- tercero_direccion %>" data-address-nomenclatura-default="<%- tercero_dir_nomenclatura %>" data-municipio-default="<%- tercero_municipio %>">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="despachop1_telefono" class="control-label">Teléfono</label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-phone"></i>
                                                        </div>
                                                        <input id="despachop1_telefono" class="form-control input-sm" name="despachop1_telefono" type="text" data-inputmask="'mask': '(999) 999-99-99'" data-mask required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label for="despachop1_direccion" class="control-label">Dirección</label>
                                                    <small id="despachop1_nomenclatura"></small>
                                                    <div class="input-group input-group-sm">
                                                        <input type="hidden" id="despachop1_direccion_nomenclatura" name="despachop1_direccion_nomenclatura">
                                                        <input id="despachop1_direccion" placeholder="Dirección" class="form-control address-koi-component" name="despachop1_direccion" data-nm-name="despachop1_nomenclatura" data-nm-value="despachop1_direccion_nomenclatura" type="text" maxlength="200" required>
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-default btn-flat btn-address-koi-component" data-field="despachop1_direccion">
                                                                <i class="fa fa-map-signs"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="despachop1_municipio" class="control-label">Municipio</label>
                                                    <select name="despachop1_municipio" id="despachop1_municipio" class="form-control select2-default" required>
                                                        @foreach( App\Models\Base\Municipio::getMunicipios() as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="despachop1_email" class="control-label">Email</label>
                                                    <input id="despachop1_email" placeholder="Email" class="form-control input-sm" name="despachop1_email" type="email" maxlength="200">
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>

                                            <!-- table table-bordered table-striped -->
                                            <div class="box-body table-responsive no-padding">
                                                <table id="browse-orden-despachosp-pendientes-list" class="table table-hover table-bordered" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">Código</th>
                                                            <th width="65%">Producto</th>
                                                            <th width="10%">Cantidad</th>
                                                            <th width="10%">Saldo</th>
                                                            <th width="10%">Entregado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {{-- Render content productos --}}
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="despachop1_observacion" class="control-label">Detalle</label>
                                                    <textarea id="despachop1_observacion" name="despachop1_observacion" class="form-control" rows="2" placeholder="Detalle"></textarea>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="despachop1_transporte" class="control-label">Transporte</label>
                                                    <input id="despachop1_transporte" placeholder="Transporte" class="form-control input-sm input-toupper" name="despachop1_transporte" type="text" maxlength="200">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-2 col-md-offset-5 col-sm-6 col-xs-6">
                                                    <button type="submit" class="btn btn-primary btn-sm btn-block">{{ trans('app.save') }}</button>
                                                </div>
                                            </div>
                                        </form><br/>

                                        <div class="box box-primary">
                                            <div class="box-body">
                                                <!-- table table-bordered table-striped -->
                                                <div class="box-body table-responsive no-padding">
                                                    <table id="browse-orden-despachosp-list" class="table table-hover table-bordered" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                @if( Auth::user()->ability('admin', 'opcional3', ['module' => 'ordenes']) )
                                                                <th width="5%"></th>
                                                                @endif
                                                                <th width="5%">Código</th>
                                                                <th width="70%">Contacto</th>
                                                                <th width="15%">Fecha</th>
                                                                <th width="5%"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {{-- Render content productos --}}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_contabilidad">
                                <div class="box box-whithout-border">
                                    <div class="box-body table-responsive no-padding">
                                        <table id="browse-detalle-asiento-list" class="table table-bordered" cellspacing="0" width="100%">
                                            <thead>
                					            <tr>
                					                <th>Cuenta</th>
                					                <th>Nombre</th>
                					                <th>Beneficiario</th>
                					                <th>Centro Costo</th>
                					                <th>Base</th>
                					                <th>Debito</th>
                					                <th>Credito</th>
                					                <th></th>
                					            </tr>
                                            </thead>
                                            <tbody>
                                                {{-- render content --}}
                                            </tbody>
            						    </table>
                                    </div>
                                </div>
                            </div>

                            @if( Auth::user()->ability('admin', 'opcional2', ['module' => 'ordenes']) )
                                <div class="tab-pane" id="tab_tiemposp">
                                    <div class="box box-whithout-border">
                                        <div class="box-body table-responsive no-padding">
                                            <table id="browse-tiemposp-global-list" class="table table-bordered" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th width="2%">#</th>
                                                        <th width="20%">Tercero</th>
                                                        <th width="20%">Actividad</th>
                                                        <th width="20%">Subactividad</th>
                                                        <th width="20%">Área</th>
                                                        <th width="8%">Fecha</th>
                                                        <th width="5%">H. inicio</th>
                                                        <th width="5%">H. fin</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- Render content productos --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="tab_tiempospcharts">
                                    @include('production.ordenes.charts.charts')
                                </div>

                                <div class="tab-pane" id="tab_imagenes">
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <textarea id="orden_observaciones_imagen" name="orden_observaciones_imagen" class="form-control" placeholder="Observaciones"><%- orden_observaciones_imagen %></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <div class="fine-uploader"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        <% } %>
                    </div>
                </div>
            </div>
        </section>
    </script>

    <script type="text/template" id="ordenp-producto-item-list-tpl">
        <% if(edit) { %>
            <td class="text-center">
                @if( Auth::user()->ability('admin', 'eliminar', ['module' => 'ordenes']) )
                    <a class="btn btn-default btn-xs item-orden-producto-remove" data-resource="<%- id %>" title="Eliminar producto">
                        <span><i class="fa fa-times"></i></span>
                    </a>
                @endif
            </td>

            <td class="text-center">
                @if( Auth::user()->ability('admin', 'crear', ['module' => 'ordenes']) )
                    <a class="btn btn-default btn-xs item-orden-producto-clone" data-resource="<%- id %>" title="Clonar producto">
                        <span><i class="fa fa-clone"></i></span>
                    </a>
                @endif
            </td>
        <% } %>
        <td>
            <a href="<%- window.Misc.urlFull( Route.route('ordenes.productos.show', {productos: id}) ) %>" title="Ver producto"><%- id %></a>
        </td>
        <td><%- productop_nombre %></td>
        <td class="text-center"><%- orden2_cantidad %></td>
        <td class="text-center"><%- orden2_facturado %></td>
        @if( Auth::user()->ability('admin', 'opcional2', ['module' => 'ordenes']) )
            <td class="text-right"><%- window.Misc.currency( orden2_total_valor_unitario ) %></td>
            <td class="text-right"><%- window.Misc.currency( orden2_precio_total ) %></td>
        @endif
    </script>

    <script type="text/template" id="ordenp-despacho-item-list-tpl">
        <% if(edit) { %>
            @if( Auth::user()->ability('admin', 'opcional3', ['module' => 'ordenes']) )
                <td class="text-center">
                    <a class="btn btn-default btn-xs item-orden-despacho-remove" data-resource="<%- id %>">
                        <span><i class="fa fa-times"></i></span>
                    </a>
                </td>
            @endif
        <% } %>
        <td class="text-center"><%- id %></td>
        <td><%- tcontacto_nombre %></td>
        <td><%- despachop1_fecha %></td>
        <td>
            <a href="<%- window.Misc.urlFull( Route.route('ordenes.despachos.exportar', {despachos: id}) ) %>"  target="_blank" class="btn btn-danger btn-xs" data-resource="<%- id %>">
                <span><i class="fa fa-file-pdf-o"></i></span>
            </a>
        </td>
    </script>

    <script type="text/template" id="ordenp-tiempop-item-list-tpl">
        <td><%- id %></td>
        <td><%- tercero_nombre %></td>
        <td><%- actividadp_nombre %></td>
        <td><%- !_.isNull(subactividadp_nombre) ? subactividadp_nombre : ' - ' %></td>
        <td><%- areap_nombre %></td>
        <td><%- tiempop_fecha %></td>
        <td><%- moment(tiempop_hora_inicio, 'HH:mm').format('HH:mm') %></td>
        <td><%- moment(tiempop_hora_fin, 'H:mm').format('H:mm') %></td>
        @if( Auth::user()->ability('admin', 'opcional2', ['module' => 'ordenes']) )
            <td class="text-center">
                <a class="btn btn-default btn-xs edit-tiempop" data-tiempop-resource="<%- id %>">
                    <span><i class="fa fa-pencil-square-o"></i></span>
                </a>
            </td>
        @endif
    </script>

    <script type="text/template" id="ordenp-despacho-pendiente-item-list-tpl">
        <td><%- id %></td>
        <td><%- productop_nombre %></td>
        <td>
            <input id="despachop2_cantidad_<%- id %>" name="despachop2_cantidad_<%- id %>" class="form-control input-sm" type="number" min="0" max="<%- orden2_saldo %>" value="0" required>
        </td>
        <td><%- orden2_saldo %></td>
        <td><%- orden2_entregado %></td>
    </script>

    <script type="text/template" id="ordenp-close-confirm-tpl">
        <p>¿Está seguro que desea cerrar la orden de producción <b><%- orden_codigo %></b>?</p>
    </script>

    <script type="text/template" id="ordenp-complete-confirm-tpl">
        <p>¿Está a punto de culminar la orden de producción <b><%- orden_codigo %></b>?</p>
    </script>

    <script type="text/template" id="ordenp-clone-confirm-tpl">
        <p>¿Está seguro que desea clonar la orden de producción <b><%- orden_codigo %></b>?</p>
    </script>

    <script type="text/template" id="ordenp-productop-clone-confirm-tpl">
        <p>¿Está seguro que desea clonar el producto <b><%- orden2_codigo %> - <%- productop_nombre %></b>?</p>
    </script>

    <script type="text/template" id="ordenp-productop-delete-confirm-tpl">
        <p>¿Está seguro que desea eliminar el producto <b><%- producto_id %> - <%- producto_nombre %></b>?</p>
    </script>

    <script type="text/template" id="ordenp-despacho-delete-confirm-tpl">
        <p>¿Está seguro que desea eliminar el despacho para <b><%- tcontacto_nombre %></b> con fecha <b><%- despachop1_fecha %></b>?</p>
    </script>

    <script type="text/template" id="edit-tiempop-ordenp-tpl">
        <div class="row">
            <label for="tiempop_fecha" class="col-md-1 control-label">Fecha</label>
            <div class="form-group col-md-2">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" id="tiempop_fecha" name="tiempop_fecha" placeholder="Fecha inicio" value="<%- tiempop_fecha %>" class="form-control input-sm datepicker" required>
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div>

        <div class="row">
            <label for="tiempop_actividadp" class="control-label col-md-1">Actividad</label>
            <div class="form-group col-md-4">
                <select name="tiempop_actividadp" id="tiempop_actividadp" class="form-control select2-default-clear change-actividadp" required>
                    @foreach( App\Models\Production\Actividadp::getActividadesp() as $key => $value)
                        <option value="{{ $key }}" <%- tiempop_actividadp == '{{ $key }}' ? 'selected': ''%> >{{ $value }}</option>
                    @endforeach
                </select>
                <div class="help-block with-errors"></div>
            </div>

            <label for="tiempop_subactividadp" class="control-label col-md-1">Subactividad</label>
            <div class="form-group col-md-4">
                <select name="tiempop_subactividadp" id="tiempop_subactividadp" class="form-control select2-default-clear">
                    <option value="<%- tiempop_subactividadp %>" <%- tiempop_subactividadp == 'tiempop_subactividadp' ? 'selected': ''%>><%- subactividadp_nombre %></option>
                </select>
            </div>
        </div>

        <div class="row">
            <label for="tiempop_areap" class="control-label col-md-1">Área</label>
            <div class="form-group col-md-4">
                <select name="tiempop_areap" id="tiempop_areap" class="form-control select2-default-clear" required>
                    @foreach( App\Models\Production\Areap::getAreas() as $key => $value)
                        <option value="{{ $key }}" <%- tiempop_areap == '{{ $key }}' ? 'selected': ''%> >{{ $value }}</option>
                    @endforeach
                </select>
                <div class="help-block with-errors"></div>
            </div>
            <label for="tiempop_hora_inicio" class="col-md-1 control-label">H. inicio</label>
            <div class="form-group col-md-2">
                <div class="bootstrap-timepicker">
                    <div class="input-group">
                        <input type="text" id="tiempop_hora_inicio" name="tiempop_hora_inicio" placeholder="Inicio" class="form-control input-sm timepicker" value="<%- tiempop_hora_inicio %>" required>
                        <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                    </div>
                </div>
                <div class="help-block with-errors"></div>
            </div>
            <label for="tiempop_hora_fin" class="col-md-1 control-label">H. fin</label>
            <div class="form-group col-md-2">
                <div class="bootstrap-timepicker">
                    <div class="input-group">
                        <input type="text" id="tiempop_hora_fin" name="tiempop_hora_fin" placeholder="Fin" class="form-control input-sm timepicker" value="<%- tiempop_hora_fin %>" required>
                        <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                    </div>
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div>
    </script>

    <script type="text/template" id="chart-empleado-ordenp">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tiempos por empleado</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart-container">
                            <canvas id="chart_empleado" width="500" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/template" id="chart-areasp-ordenp">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tiempos por área de producción</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="chart-container">
                                <canvas id="chart_areas" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/template" id="chart-productop-ordenp">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tiempos de producción</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart-container">
                            <canvas id="chart_comparativa" width="500" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>
@stop

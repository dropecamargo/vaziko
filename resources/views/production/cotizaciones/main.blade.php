@extends('layout.layout')

@section('title') Cotizaciones @stop

@section('content')
    @yield ('module')

    <script type="text/template" id="add-cotizacion-tpl">
        <section class="content-header">
            <h1>
                Cotizaciones <small>Administración de cotizaciones</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{trans('app.home')}}</a></li>
                <li><a href="{{ route('cotizaciones.index') }}">Cotización</a></li>
                <% if( !_.isUndefined(edit) && !_.isNull(edit) && edit) { %>
                    <li><a href="<%- window.Misc.urlFull( Route.route('cotizaciones.show', { cotizaciones: id}) ) %>"><%- cotizacion_codigo %></a></li>
                    <li class="active">Editar</li>
                <% }else{ %>
                    <li class="active">Nueva</li>
                <% } %>
            </ol>
        </section>

        <section class="content">
            <div class="box box-solid" id="spinner-main">
                <div class="nav-tabs-custom tab-solid tab-whithout-box-shadow">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_cotizacion" data-toggle="tab">Cotización</a></li>
                        <% if( !_.isUndefined(edit) && !_.isNull(edit) && edit) { %>
                            <li class="dropdown pull-right">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    Opciones <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li role="presentation">
                                        <a role="menuitem" tabindex="-1" href="#" class="close-cotizacion">
                                            <i class="fa fa-lock"></i>Cerrar cotización
                                        </a>
                                        @if( Auth::user()->ability('admin', 'crear', ['module' => 'cotizaciones']) )
                                            <a role="menuitem" tabindex="-1" href="#" class="clone-cotizacion">
                                                <i class="fa fa-clone"></i>Clonar cotización
                                            </a>
                                            <a role="menuitem" tabindex="-1" href="#" class="generate-cotizacion">
                                                <i class="fa fa-sticky-note"></i>Generar orden
                                            </a>
                                        @endif
                                        <a role="menuitem" tabindex="-1" href="#" class="export-cotizacion">
                                            <i class="fa fa-file-pdf-o"></i>Exportar
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <% } %>
                    </ul>
                    <div class="tab-content">
                        {{-- Content cotizacion --}}
                        <div class="tab-pane active" id="tab_cotizacion">
                            <div class="box box-whithout-border">
                                <div class="box-body">
                                    <form method="POST" accept-charset="UTF-8" id="form-cotizaciones" data-toggle="validator">
                                        <div class="row">
                                            <% if( typeof(cotizacion_codigo) !== 'undefined' && !_.isUndefined(cotizacion_codigo) && !_.isNull(cotizacion_codigo) && cotizacion_codigo != '') { %>
                                                <label class="col-sm-1 control-label">Código</label>
                                                <div class="form-group col-md-1">
                                                    <%- cotizacion_codigo %>
                                                </div>
                                            <% } %>
                                            <% if( typeof(precotizacion_codigo) !== 'undefined' && !_.isUndefined(precotizacion_codigo) && !_.isNull(precotizacion_codigo) && precotizacion_codigo != '') { %>
                                                <label class="col-sm-1 control-label">Pre-cotización</label>
                                                <div class="form-group col-md-1">
                                                    <%- precotizacion_codigo %>
                                                </div>
                                            <% } %>

                                            <label for="cotizacion1_fecha_inicio" class="col-md-1 control-label">F. Inicio</label>
                                            <div class="form-group col-md-2">
                                                <input type="text" id="cotizacion1_fecha_inicio" name="cotizacion1_fecha_inicio" placeholder="Fecha inicio" class="form-control input-sm datepicker" value="<%- cotizacion1_fecha_inicio %>" required>
                                            </div>

                                            <label for="cotizacion1_formapago" class="col-md-1 control-label">Forma de pago</label>
                                            <div class="form-group col-md-4">
                                                <input type="text" id="cotizacion1_formapago" name="cotizacion1_formapago" placeholder="Forma de pago" class="form-control input-sm" value="<%- cotizacion1_formapago %>" maxlength="30" required readonly>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="cotizacion1_referencia" class="col-sm-1 control-label">Referencia</label>
                                            <div class="form-group col-md-10">
                                                <input id="cotizacion1_referencia" value="<%- cotizacion1_referencia %>" placeholder="Referencia" class="form-control input-sm input-toupper" name="cotizacion1_referencia" type="text" maxlength="200" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="cotizacion1_cliente" class="col-sm-1 control-label">Cliente</label>
                                            <div class="form-group col-sm-3">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-default btn-flat btn-koi-search-tercero-component-table" data-field="cotizacion1_cliente">
                                                            <i class="fa fa-user"></i>
                                                        </button>
                                                    </span>
                                                    <input id="cotizacion1_cliente" placeholder="Cliente" class="form-control tercero-koi-component" name="cotizacion1_cliente" type="text" maxlength="15" data-wrapper="spinner-main" data-name="cotizacion1_cliente_nombre" data-contacto="btn-add-contact" data-formapago="cotizacion1_formapago" value="<%- tercero_nit %>" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-7 col-xs-10">
                                                <input id="cotizacion1_cliente_nombre" name="cotizacion1_cliente_nombre" placeholder="Nombre cliente" class="form-control input-sm" type="text" maxlength="15" value="<%- tercero_nombre %>" readonly required>
                                            </div>
                                            <div class="col-sm-1 col-xs-2">
                                                <button type="button" class="btn btn-default btn-flat btn-sm btn-add-resource-koi-component" data-resource="tercero" data-field="cotizacion1_cliente">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="tcontacto_nombre" class="col-sm-1 control-label">Contacto</label>
                                            <div class="form-group col-sm-5 col-xs-10">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-default btn-flat btn-koi-search-contacto-component-table" data-field="cotizacion1_contacto" data-name="tcontacto_nombre" data-phone="tcontacto_telefono" data-tercero="btn-add-contact">
                                                            <i class="fa fa-address-book"></i>
                                                        </button>
                                                    </span>
                                                    <input id="cotizacion1_contacto" name="cotizacion1_contacto" type="hidden" value="<%- cotizacion1_contacto %>">
                                                    <input id="tcontacto_nombre" placeholder="Contacto" class="form-control" name="tcontacto_nombre" type="text" value="<%- tcontacto_nombre %>" readonly required>
                                                </div>
                                            </div>
                                            <div class="col-sm-1 col-xs-2">
                                                <button type="button" id="btn-add-contact" class="btn btn-default btn-flat btn-sm btn-add-resource-koi-component" data-resource="contacto" data-field="cotizacion1_contacto" data-name="tcontacto_nombre" data-tercero="<%- cotizacion1_cliente %>" data-phone="tcontacto_telefono" data-address-default="<%- tercero_direccion %>" data-address-nomenclatura-default="<%- tercero_dir_nomenclatura %>" data-municipio-default="<%- tercero_municipio %>">
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
                                            <label for="cotizacion1_suministran" class="col-sm-1 control-label">Suministran</label>
                                            <div class="form-group col-sm-11">
                                                <input id="cotizacion1_suministran" placeholder="Suministran" class="form-control" name="cotizacion1_suministran" type="text" value="<%- cotizacion1_suministran %>" required maxlength="200">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="cotizacion1_observaciones" class="col-sm-1 control-label">Detalle</label>
                                            <div class="form-group col-sm-11">
                                                <textarea id="cotizacion1_observaciones" name="cotizacion1_observaciones" class="form-control" rows="2" placeholder="Detalle"><%- cotizacion1_observaciones %></textarea>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="cotizacion1_terminado" class="col-sm-1 control-label">Terminado</label>
                                            <div class="form-group col-sm-11">
                                                <textarea id="cotizacion1_terminado" name="cotizacion1_terminado" class="form-control" rows="2" placeholder="Terminado"><%- cotizacion1_terminado %></textarea>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="row">
                                        <div class="col-md-2 col-md-offset-4 col-sm-6 col-xs-6">
                                            <a href="{{ route('cotizaciones.index') }}" class="btn btn-default btn-sm btn-block">{{ trans('app.cancel') }}</a>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-6">
                                            <button type="button" class="btn btn-primary btn-sm btn-block submit-cotizacion">{{ trans('app.save') }}</button>
                                        </div>
                                    </div>
                                    <br>

                                    <% if( !_.isUndefined(edit) && !_.isNull(edit) && edit) { %>
                                        <div class="box box-danger">
                                            <div class="box-body">
                                                <form method="GET" accept-charset="UTF-8" id="form-productosp3" data-toggle="validator" action="<%- window.Misc.urlFull( Route.route('cotizaciones.productos.create') ) %>">
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
                                                                <input type="hidden" id="cotizacion" name="cotizacion" value="<%- id %>" required>
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
                                                    <table id="browse-cotizacion-productop-list" class="table table-hover table-bordered" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th width="2%"></th>
                                                                <th width="2%"></th>
                                                                <th width="5%">Código</th>
                                                                <th width="60%">Nombre</th>
                                                                <th width="5%">Cantidad</th>
                                                                <th width="6%">Facturado</th>
                                                                @if( Auth::user()->ability('admin', 'opcional2', ['module' => 'cotizaciones']) )
                                                                    <th width="10%" class="text-right">Precio</th>
                                                                    <th width="10%" class="text-right">Total</th>
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
                                                                <th class="text-center" id="subtotal-cantidad">0</th>
                                                                <th class="text-center" id="subtotal-facturado">0</th>
                                                                @if( Auth::user()->ability('admin', 'opcional2', ['module' => 'cotizaciones']) )
                                                                    <th></th>
                                                                    <th class="text-right" id="subtotal-total">0</th>
                                                                @endif
                                                            </tr>
                                                            @if( Auth::user()->ability('admin', 'opcional2', ['module' => 'cotizaciones']) )
                                                                <tr>
                                                                    <th colspan="3"></th>
                                                                    <th class="text-right">Iva (<%- cotizacion1_iva %>%)</th>
                                                                    <th colspan="5" class="text-right" id="iva-total">0</th>
                                                                </tr>
                                                                <tr>
                                                                    <th colspan="3"></th>
                                                                    <th class="text-right">Total</th>
                                                                    <th colspan="5" class="text-right" id="total-total">0</th>
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
                    </div>
                </div>
            </div>
        </section>
    </script>

    <script type="text/template" id="cotizacion-producto-item-list-tpl">
        <% if(edit) { %>
            <td class="text-center">
                @if( Auth::user()->ability('admin', 'eliminar', ['module' => 'cotizaciones']) )
                    <a class="btn btn-default btn-xs item-cotizacion-producto-remove" data-resource="<%- id %>" title="Eliminar producto">
                        <span><i class="fa fa-times"></i></span>
                    </a>
                @endif
            </td>

            <td class="text-center">
                @if( Auth::user()->ability('admin', 'crear', ['module' => 'cotizaciones']) )
                    <a class="btn btn-default btn-xs item-cotizacion-producto-clone" data-resource="<%- id %>" title="Clonar producto">
                        <span><i class="fa fa-clone"></i></span>
                    </a>
                @endif
            </td>
        <% } %>
        <td>
            <a href="<%- window.Misc.urlFull( Route.route('cotizaciones.productos.show', {productos: id}) ) %>" title="Ver producto"><%- id %></a>
        </td>
        <td><%- productop_nombre %></td>
        <td class="text-center"><%- cotizacion2_cantidad %></td>
        <td class="text-center"><%- cotizacion2_facturado %></td>
        @if( Auth::user()->ability('admin', 'opcional2', ['module' => 'cotizaciones']) )
            <td class="text-right"><%- window.Misc.currency( cotizacion2_total_valor_unitario ) %></td>
            <td class="text-right"><%- window.Misc.currency( cotizacion2_precio_total ) %></td>
        @endif
    </script>

    <script type="text/template" id="cotizacion-close-confirm-tpl">
        <p>¿Está seguro que desea cerrar la cotización <b><%- cotizacion_codigo %></b>?</p>
    </script>

    <script type="text/template" id="cotizacion-clone-confirm-tpl">
        <p>¿Está seguro que desea clonar la cotización <b><%- cotizacion_codigo %></b>?</p>
    </script>

    <script type="text/template" id="cotizacion-generate-confirm-tpl">
        <p>¿Está seguro que desea generar una orden de producción del producto <b><%- cotizacion_codigo %> - <%- cotizacion_referencia %></b>?</p>
    </script>

    <script type="text/template" id="cotizacion-productop-clone-confirm-tpl">
        <p>¿Está seguro que desea clonar el producto <b><%- cotizacion2_codigo %> - <%- productop_nombre %></b>?</p>
    </script>

    <script type="text/template" id="cotizacion-productop-delete-confirm-tpl">
        <p>¿Está seguro que desea eliminar el producto <b><%- producto_id %> - <%- producto_nombre %></b>?</p>
    </script>
@stop
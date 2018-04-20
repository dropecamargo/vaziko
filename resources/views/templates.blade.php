{{-- Base templates --}}
<script type="text/template" id="add-sucursal-tpl">
    <div class="row">
		<div class="form-group col-md-8">
			<label for="sucursal_nombre" class="control-label">Nombre</label>
			<input type="text" id="sucursal_nombre" name="sucursal_nombre" value="<%- sucursal_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="100" required>
		</div>
    </div>
</script>

<script type="text/template" id="add-contacto-tpl">
    <div class="row">
		<div class="form-group col-md-4">
			<label for="tcontacto_nombres" class="control-label">Nombres</label>
			<input type="text" id="tcontacto_nombres" name="tcontacto_nombres" value="<%- tcontacto_nombres %>" placeholder="Nombres" class="form-control input-sm input-toupper" maxlength="200" required>
		</div>

		<div class="form-group col-md-4">
			<label for="tcontacto_apellidos" class="control-label">Apellidos</label>
			<input type="text" id="tcontacto_apellidos" name="tcontacto_apellidos" value="<%- tcontacto_apellidos %>" placeholder="Apellidos" class="form-control input-sm input-toupper" maxlength="200" required>
		</div>
    </div>

    <div class="row">
    	<div class="form-group col-md-4">
			<label for="tcontacto_direccion" class="control-label">Dirección</label>
			<small id="tcontacto_dir_nomenclatura"><%- tcontacto_direccion_nomenclatura %></small>
      		<div class="input-group input-group-sm">
  		 		<input type="hidden" id="tcontacto_direccion_nomenclatura" name="tcontacto_direccion_nomenclatura" value="<%- tcontacto_direccion_nomenclatura %>">
				<input id="tcontacto_direccion" value="<%- tcontacto_direccion %>" placeholder="Dirección" class="form-control address-koi-component" name="tcontacto_direccion" type="text" maxlength="200" required data-nm-name="tcontacto_dir_nomenclatura" data-nm-value="tcontacto_direccion_nomenclatura">
				<span class="input-group-btn">
					<button type="button" class="btn btn-default btn-flat btn-address-koi-component" data-field="tcontacto_direccion">
						<i class="fa fa-map-signs"></i>
					</button>
				</span>
			</div>
		</div>

    	<div class="form-group col-md-4">
			<label for="tcontacto_municipio" class="control-label">Municipio</label>
			<select name="tcontacto_municipio" id="tcontacto_municipio" class="form-control choice-select-autocomplete" data-ajax-url="<%- window.Misc.urlFull(Route.route('municipios.index'))%>" data-placeholder="Seleccione" placeholder="Seleccione" data-initial-value="<%- tcontacto_municipio %>">
			</select>
		</div>

		<div class="form-group col-md-4">
			<label for="tcontacto_nombres" class="control-label">Email</label>
			<input id="tcontacto_email" value="<%- tcontacto_email %>" placeholder="Email" class="form-control input-sm" name="tcontacto_email" type="email" maxlength="200">
		    <div class="help-block with-errors"></div>
		</div>
    </div>

    <div class="row">
		<div class="form-group col-md-4">
			<label for="tcontacto_cargo" class="control-label">Cargo</label>
			<input type="text" id="tcontacto_cargo" name="tcontacto_cargo" value="<%- tcontacto_cargo %>" placeholder="Cargos" class="form-control input-sm input-toupper" maxlength="200">
		</div>

		<div class="form-group col-md-4">
			<label for="tcontacto_telefono" class="control-label">Teléfono</label>
			<div class="input-group">
				<div class="input-group-addon">
					<i class="fa fa-phone"></i>
				</div>
				<input id="tcontacto_telefono" value="<%- tcontacto_telefono %>" class="form-control input-sm" name="tcontacto_telefono" type="text" data-inputmask="'mask': '(999) 999-99-99 EXT 99999'" data-mask required>
			</div>
		</div>

		<div class="form-group col-md-4">
			<label for="tcontacto_celular" class="control-label">Celular</label>
			<div class="input-group">
				<div class="input-group-addon">
					<i class="fa fa-mobile"></i>
				</div>
				<input id="tcontacto_celular" value="<%- tcontacto_celular %>" class="form-control input-sm" name="tcontacto_celular" type="text" data-inputmask="'mask': '(999) 999-99-99'" data-mask>
			</div>
		</div>
	</div>
</script>

<script type="text/template" id="add-tercero-tpl">
	<form method="POST" accept-charset="UTF-8" id="form-tercero" data-toggle="validator">
		<div class="row">
			<div class="form-group col-md-3">
				<label for="tercero_nit" class="control-label">Documento</label>
				<div class="row">
					<div class="col-md-9">
						<input id="tercero_nit" value="<%- tercero_nit %>" placeholder="Nit" class="form-control input-sm change-nit-koi-component" name="tercero_nit" type="text" required data-field="tercero_digito">
					</div>
					<div class="col-md-3">
						<input id="tercero_digito" value="<%- tercero_digito %>" class="form-control input-sm" name="tercero_digito" type="text" readonly required>
					</div>
				</div>
			</div>

			<div class="form-group col-md-3">
				<label for="tercero_tipo" class="control-label">Tipo</label>
				<select name="tercero_tipo" id="tercero_tipo" class="form-control" required>
					<option value="" selected>Seleccione</option>
					@foreach( config('koi.terceros.tipo') as $key => $value)
						<option value="{{ $key }}" <%- tercero_tipo == '{{ $key }}' ? 'selected': ''%> >{{ $value }}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group col-md-3">
				<label for="tercero_persona" class="control-label">Persona</label>
				<select name="tercero_persona" id="tercero_persona" class="form-control" required>
					<option value="" selected>Seleccione</option>
					@foreach( config('koi.terceros.persona') as $key => $value)
						<option value="{{ $key }}" <%- tercero_persona == '{{ $key }}' ? 'selected': ''%> >{{ $value }}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group col-md-3">
				<label for="tercero_regimen" class="control-label">Regimen</label>
				<select name="tercero_regimen" id="tercero_regimen" class="form-control" required>
					<option value="" selected>Seleccione</option>
					@foreach( config('koi.terceros.regimen') as $key => $value)
						<option value="{{ $key }}" <%- tercero_regimen == '{{ $key }}' ? 'selected': ''%> >{{ $value }}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="row">
			<div class="form-group col-md-3">
				<label for="tercero_nombre1" class="control-label">1er. Nombre</label>
				<input id="tercero_nombre1" value="<%- tercero_nombre1 %>" placeholder="1er. Nombre" class="form-control input-sm input-toupper" name="tercero_nombre1" type="text">
			</div>

			<div class="form-group col-md-3">
				<label for="tercero_nombre2" class="control-label">2do. Nombre</label>
				<input id="tercero_nombre2" value="<%- tercero_nombre2 %>" placeholder="2do. Nombre" class="form-control input-sm input-toupper" name="tercero_nombre2" type="text">
			</div>

			<div class="form-group col-md-3">
				<label for="tercero_apellido1" class="control-label">1er. Apellido</label>
				<input id="tercero_apellido1" value="<%- tercero_apellido1 %>" placeholder="1er. Apellido" class="form-control input-sm input-toupper" name="tercero_apellido1" type="text">
			</div>

			<div class="form-group col-md-3">
				<label for="tercero_apellido2" class="control-label">2do. Apellido</label>
				<input id="tercero_apellido2" value="<%- tercero_apellido2 %>" placeholder="2do. Apellido" class="form-control input-sm input-toupper" name="tercero_apellido2" type="text">
			</div>
		</div>

		<div class="row">
			<div class="form-group col-md-12">
				<label for="tercero_razonsocial" class="control-label">Razón Social, Comercial o Establecimiento</label>
				<input id="tercero_razonsocial" value="<%- tercero_razonsocial %>" placeholder="Razón Social, Comercial o Establecimiento" class="form-control input-sm input-toupper" name="tercero_razonsocial" type="text">
			</div>
		</div>

		<div class="row">
			<div class="form-group col-md-3">
				<label for="tercero_direccion" class="control-label">Dirección</label> <small id="tercero_nomenclatura"><%- tercero_dir_nomenclatura %></small>
	      		<div class="input-group input-group-sm">
      		 		<input type="hidden" id="tercero_dir_nomenclatura" name="tercero_dir_nomenclatura" value="<%- tercero_dir_nomenclatura %>">
					<input id="tercero_direccion" value="<%- tercero_direccion %>" placeholder="Dirección" class="form-control address-koi-component" name="tercero_direccion" type="text" data-nm-name="tercero_nomenclatura" data-nm-value="tercero_dir_nomenclatura" required>
					<span class="input-group-btn">
						<button type="button" class="btn btn-default btn-flat btn-address-koi-component" data-field="tercero_direccion">
							<i class="fa fa-map-signs"></i>
						</button>
					</span>
				</div>
			</div>

			<div class="form-group col-md-3">
				<label for="tercero_municipio" class="control-label">Municipio</label>
				<select name="tercero_municipio" id="tercero_municipio" class="form-control choice-select-autocomplete" data-ajax-url="<%- window.Misc.urlFull(Route.route('municipios.index'))%>" data-placeholder="Seleccione" placeholder="Seleccione" data-initial-value="<%- tercero_municipio %>">
				</select>
			</div>

			<div class="form-group col-md-3">
				<label for="tercero_email" class="control-label">Email</label>
				<input id="tercero_email" value="<%- tercero_email %>" placeholder="Email" class="form-control input-sm" name="tercero_email" type="email">
			    <div class="help-block with-errors"></div>
			</div>
	    </div>

	    <div class="row">
	    	<div class="form-group col-md-3">
				<label for="tercero_telefono1" class="control-label">Teléfono</label>
				<div class="input-group">
					<div class="input-group-addon">
						<i class="fa fa-phone"></i>
					</div>
					<input id="tercero_telefono1" value="<%- tercero_telefono1 %>" class="form-control input-sm" name="tercero_telefono1" type="text" data-inputmask="'mask': '(999) 999-99-99 EXT 99999'" data-mask>
				</div>
			</div>

			<div class="form-group col-md-3">
				<label for="tercero_telefono2" class="control-label">2do. Teléfono</label>
				<div class="input-group">
					<div class="input-group-addon">
						<i class="fa fa-phone"></i>
					</div>
					<input id="tercero_telefono2" value="<%- tercero_telefono2 %>" class="form-control input-sm" name="tercero_telefono2" type="text" data-inputmask="'mask': '(999) 999-99-99 EXT 99999'" data-mask>
				</div>
			</div>

			<div class="form-group col-md-3">
				<label for="tercero_fax" class="control-label">Fax</label>
				<div class="input-group">
					<div class="input-group-addon">
						<i class="fa fa-fax"></i>
					</div>
					<input id="tercero_fax" value="<%- tercero_fax %>" class="form-control input-sm" name="tercero_fax" type="text" data-inputmask="'mask': '(999) 999-99-99 EXT 99999'" data-mask>
				</div>
			</div>

			<div class="form-group col-md-3">
				<label for="tercero_celular" class="control-label">Celular</label>
				<div class="input-group">
					<div class="input-group-addon">
						<i class="fa fa-mobile"></i>
					</div>
					<input id="tercero_celular" value="<%- tercero_celular %>" class="form-control input-sm" name="tercero_celular" type="text" data-inputmask="'mask': '(999) 999-99-99'" data-mask>
				</div>
			</div>
		</div>

	    <div class="row">
			<div class="form-group col-md-6">
				<label for="tercero_representante" class="control-label">Representante Legal</label>
				<input id="tercero_representante" value="<%- tercero_representante %>" placeholder="Representante Legal" class="form-control input-sm input-toupper" name="tercero_representante" type="text" maxlength="200">
			</div>
			<div class="form-group col-md-3">
	    		<label for="tercero_cc_representante" class="control-label">Cédula</label>
	    		<input id="tercero_cc_representante" value="<%- tercero_cc_representante %>" placeholder="Cédula" class="form-control input-sm" name="tercero_cc_representante" type="text" maxlength="15">
	    	</div>
			<div class="form-group col-md-3">
	    		<label for="tercero_formapago" class="control-label">Forma de pago</label>
	    		<input id="tercero_formapago" value="<%- tercero_formapago %>" placeholder="Forma de pago" class="form-control input-sm input-toupper" name="tercero_formapago" type="text" maxlength="30" required>
	    	</div>
		</div>

	    <div class="row">
            <div class="col-md-offset-4 col-md-2 col-sm-6 col-xs-6">
                <a href="<%- window.Misc.urlFull( (typeof(id) !== 'undefined' && !_.isUndefined(id) && !_.isNull(id) && id != '') ? Route.route('terceros.show', { terceros: id}) : Route.route('terceros.index') ) %>" class="btn btn-default btn-sm btn-block">{{ trans('app.comeback') }}</a>
            </div>
            <div class="col-md-2 col-sm-6 col-xs-6">
                <button type="submit" class="btn btn-primary btn-sm btn-block">{{ trans('app.save') }}</button>
            </div>
        </div>
	</form>

	<br/>
	<div class="row">
    	<div class="form-group col-md-12">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab_contabilidad" data-toggle="tab">Contabilidad</a></li>
					<% if( !_.isUndefined(tercero_nit) && !_.isNull(tercero_nit) && tercero_nit != ''){ %>
						<li><a id="wrapper-empleados" href="#tab_empleados" data-toggle="tab" class="<%- parseInt(tercero_empleado) || parseInt(tercero_interno) ? '' : 'hide' %>">Empleado</a></li>
						<li><a href="#tab_contactos" data-toggle="tab">Contactos</a></li>
					<% } %>
				</ul>

				<div class="tab-content">
					{{-- Tab contabilidad --}}
					<div class="tab-pane active" id="tab_contabilidad">
						<form method="POST" accept-charset="UTF-8" id="form-accounting" data-toggle="validator">
				    	    <div class="row">
						    	<div class="form-group col-md-10">
						    		<label for="tercero_actividad" class="control-label">Actividad Económica</label>
									<select name="tercero_actividad" id="tercero_actividad" class="form-control choice-select-autocomplete" data-ajax-url="<%- window.Misc.urlFull(Route.route('actividades.index'))%>" data-placeholder="Seleccione" placeholder="Seleccione" data-initial-value="<%- tercero_actividad %>">
									</select>
						    	</div>
						    	<div class="form-group col-md-2">
						    		<label for="tercero_retecree" class="control-label">% Cree</label>
						    		<div id="tercero_retecree"><%- actividad_tarifa %></div>
						    	</div>
						    </div>

						    <div class="row">
						    	<div class="form-group col-md-2">
									<label class="checkbox-inline" for="tercero_cliente">
										<input type="checkbox" id="tercero_cliente" name="tercero_cliente" value="tercero_cliente" <%- parseInt(tercero_cliente) ? 'checked': ''%>> Cliente
									</label>
								</div>
								<div class="form-group col-md-2">
									<label class="checkbox-inline" for="tercero_acreedor">
										<input type="checkbox" id="tercero_acreedor" name="tercero_acreedor" value="tercero_acreedor" <%- parseInt(tercero_acreedor) ? 'checked': ''%>> Acreedor
									</label>
								</div>
								<div class="form-group col-md-2">
									<label class="checkbox-inline" for="tercero_proveedor">
										<input type="checkbox" id="tercero_proveedor" name="tercero_proveedor" value="tercero_proveedor" <%- parseInt(tercero_proveedor) ? 'checked': ''%>> Proveedor
									</label>
								</div>
								<div class="form-group col-md-2">
									<label class="checkbox-inline" for="tercero_autoretenedor_ica">
										<input type="checkbox" id="tercero_autoretenedor_ica" name="tercero_autoretenedor_ica" value="tercero_autoretenedor_ica" <%- parseInt(tercero_autoretenedor_ica) ? 'checked': ''%>> Autorretenedor ICA
									</label>
								</div>
								<div class="form-group col-md-3">
									<label class="checkbox-inline" for="tercero_responsable_iva">
										<input type="checkbox" id="tercero_responsable_iva" name="tercero_responsable_iva" value="tercero_responsable_iva" <%- parseInt(tercero_responsable_iva) ? 'checked': ''%>> Responsable de IVA
									</label>
								</div>
						    </div>

						    <div class="row">
						    	<div class="form-group col-md-2">
							    	<label class="checkbox-inline" for="tercero_empleado">
										<input type="checkbox" id="tercero_empleado" name="tercero_empleado" value="tercero_empleado" <%- parseInt(tercero_empleado) ? 'checked': ''%> class="change_employee"> Empleado
									</label>
								</div>
								<div class="form-group col-md-2">
									<label class="checkbox-inline" for="tercero_interno">
										<input type="checkbox" id="tercero_interno" name="tercero_interno" value="tercero_interno" <%- parseInt(tercero_interno) ? 'checked': ''%> class="change_employee"> Interno
									</label>
								</div>
								<div class="form-group col-md-2">
									<label class="checkbox-inline" for="tercero_extranjero">
										<input type="checkbox" id="tercero_extranjero" name="tercero_extranjero" value="tercero_extranjero" <%- parseInt(tercero_extranjero) ? 'checked': ''%>> Extranjero
									</label>
								</div>
								<div class="form-group col-md-2">
									<label class="checkbox-inline" for="tercero_afiliado">
										<input type="checkbox" id="tercero_afiliado" name="tercero_afiliado" value="tercero_afiliado" <%- parseInt(tercero_afiliado) ? 'checked': ''%>> Afiliado
									</label>
								</div>
								<div class="form-group col-md-3">
									<label class="checkbox-inline" for="tercero_autoretenedor_cree">
										<input type="checkbox" id="tercero_autoretenedor_cree" name="tercero_autoretenedor_cree" value="tercero_autoretenedor_cree" <%- parseInt(tercero_autoretenedor_cree) ? 'checked': ''%>> Autorretenedor CREE
									</label>
								</div>
						    </div>

							<div class="row">
								<div class="form-group col-md-2">
									<label class="checkbox-inline" for="tercero_socio">
										<input type="checkbox" id="tercero_socio" name="tercero_socio" value="tercero_socio" <%- parseInt(tercero_socio) ? 'checked': ''%>> Socio
									</label>
								</div>
								<div class="form-group col-md-2">
									<label class="checkbox-inline" for="tercero_mandatario">
										<input type="checkbox" id="tercero_mandatario" name="tercero_mandatario" value="tercero_mandatario" <%- parseInt(tercero_mandatario) ? 'checked': ''%>> Mandatario
									</label>
								</div>
								<div class="form-group col-md-2">
									<label class="checkbox-inline" for="tercero_gran_contribuyente">
										<input type="checkbox" id="tercero_gran_contribuyente" name="tercero_gran_contribuyente" value="tercero_gran_contribuyente" <%- parseInt(tercero_gran_contribuyente) ? 'checked': ''%>> Gran contribuyente
									</label>
								</div>
								<div class="form-group col-md-2">
									<label class="checkbox-inline" for="tercero_autoretenedor_renta">
										<input type="checkbox" id="tercero_autoretenedor_renta" name="tercero_autoretenedor_renta" value="tercero_autoretenedor_renta" <%- parseInt(tercero_autoretenedor_renta) ? 'checked': ''%>> Autorretenedor renta
									</label>
								</div>
								<div class="form-group col-md-2">
									<label class="checkbox-inline" for="tercero_otro">
										<input type="checkbox" id="tercero_otro" name="tercero_otro" value="tercero_otro" <%- parseInt(tercero_otro) ? 'checked': ''%>> Otro
									</label>
								</div>

								<div class="form-group col-md-2">
									<input id="tercero_cual" value="<%- tercero_cual %>" placeholder="¿Cual?" class="form-control input-sm" name="tercero_cual" type="text" maxlength="15">
								</div>
						    </div>
						</form>
					</div>

					<% if( !_.isUndefined(tercero_nit) && !_.isNull(tercero_nit) && tercero_nit != ''){ %>
						{{-- Tab empleados --}}
						<div class="tab-pane" id="tab_empleados">
							<form method="POST" accept-charset="UTF-8" id="form-employee" data-toggle="validator">
								<div class="row">
							    	<div class="form-group col-md-2">
								    	<label class="checkbox-inline" for="tercero_activo">
											<input type="checkbox" id="tercero_activo" name="tercero_activo" value="tercero_activo" <%- parseInt(tercero_activo) ? 'checked': ''%>> Activo
										</label>
									</div>
							    	<div class="form-group col-md-2">
								    	<label class="checkbox-inline" for="tercero_tecnico">
											<input type="checkbox" id="tercero_tecnico" name="tercero_tecnico" value="tercero_tecnico" <%- parseInt(tercero_tecnico) ? 'checked': ''%>> Técnico
										</label>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-2">
								    	<label class="checkbox-inline" for="tercero_coordinador">
											<input type="checkbox" id="tercero_coordinador" name="tercero_coordinador" value="tercero_coordinador" <%- parseInt(tercero_coordinador) ? 'checked': ''%>> Coordinador
										</label>
									</div>

	                               	<div id="wrapper-coordinador" class="form-group col-md-6 <%- parseInt(tercero_tecnico) ? '' : 'hide' %>">
										<label for="tercero_coordinador_por" class="control-label">Coordinado por</label>
										<select name="tercero_coordinador_por" id="tercero_coordinador_por" class="form-control select2-default">
		                                    @foreach( App\Models\Base\Tercero::getTechnicalAdministrators() as $key => $value)
		                                        <option value="{{ $key }}" <%- tercero_coordinador_por == '{{ $key }}' ? 'selected': ''%>>{{ $value }}</option>
		                                    @endforeach
		                                </select>
			                        </div>
								</div>
							</form>

							<br />
							<div class="row">
								<div class="form-group col-md-6">
					            	<div class="box box-success" id="wrapper-password">
										<div class="box-header with-border">
											<h3 class="box-title">Datos de acceso</h3>
										</div>
										<div class="box-body">
											<form method="POST" accept-charset="UTF-8" id="form-changed-password" data-toggle="validator">
												<div class="row">
													<div class="form-group col-md-12">
														<label for="username" class="control-label">Cuenta de usuario</label>
														<input type="text" name="username" id="username" class="form-control input-lower" value="<%- username %>" minlength="4" maxlength="20" required>
													</div>
												</div>

												<div class="row">
													<div class="form-group col-md-6">
													<label for="password" class="control-label">Contraseña</label>
														<input type="password" name="password" id="password" class="form-control" minlength="6" maxlength="15">
														<div class="help-block">Minimo de 6 caracteres</div>
													</div>

													<div class="form-group col-md-6">
													<label for="password_confirmation" class="control-label">Verificar contraseña</label>
														<input type="password" name="password_confirmation" id="password_confirmation" class="form-control" data-match="#password" data-match-error="Oops, no coinciden la contraseña" minlength="6" maxlength="15">
														<div class="help-block with-errors"></div>
													</div>
												</div>

												<div class="row">
													<div class="col-md-12 text-center">
														<button type="submit" class="btn btn-success change-pass">Cambiar</button>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
						    	<div class="form-group col-md-6">
									<div class="box box-success" id="wrapper-roles">
										<div class="box-header with-border">
											<h3 class="box-title">Roles de usuario</h3>
										</div>
							            <div class="box-body">
						                    <form method="POST" accept-charset="UTF-8" id="form-item-roles" data-toggle="validator">
						                        <div class="row">
						                        	<label for="role_id" class="control-label col-sm-1 col-md-offset-1 hidden-xs">Rol</label>
						                            <div class="form-group col-md-7 col-xs-9">
						                                <select name="role_id" id="role_id" class="form-control select2-default" required>
						                                    @foreach( App\Models\Base\Rol::getRoles() as $key => $value)
						                                        <option value="{{ $key }}">{{ $value }}</option>
						                                    @endforeach
						                                </select>
						                            </div>
						                            <div class="form-group col-md-2 col-xs-3">
						                                <button type="submit" class="btn btn-success btn-sm btn-block">
						                                    <i class="fa fa-plus"></i>
						                                </button>
						                            </div>
						                        </div>
						                    </form>
						                    <!-- table table-bordered table-striped -->
						                    <div class="table-responsive no-padding">
						                        <table id="browse-roles-list" class="table table-bordered" cellspacing="0">
						                            <thead>
						                                <tr>
						                                    <th width="5%"></th>
						                                    <th width="95%">Nombre</th>
						                                </tr>
						                            </thead>
						                            <tbody>
						                                {{-- Render content roles --}}
						                            </tbody>
						                        </table>
						                    </div>
						                </div>
						            </div>
					            </div>
							</div>
						</div>

						{{-- Tab contactos --}}
						<div class="tab-pane" id="tab_contactos">
						    <div class="row">
								<div class="col-md-offset-5 col-md-2 col-sm-offset-2 col-sm-8 col-xs-12">
									<button type="button" class="btn btn-primary btn-block btn-sm btn-add-tcontacto">
										<i class="fa fa-user-plus"></i>  Nuevo contacto
									</button>
								</div>
							</div>
							<br />

							<div class="box box-success">
								<div class="box-body table-responsive no-padding">
									<table id="browse-contact-list" class="table table-bordered" cellspacing="0" width="100%">
							            <thead>
								            <tr>
								                <th>Nombre</th>
								                <th>Dirección</th>
								                <th>Teléfono</th>
								                <th>Celular</th>
								            </tr>
							           </thead>
							           <tbody>
											{{-- Render contact list --}}
							           </tbody>
									</table>
								</div>
							</div>
						</div>
					<% } %>
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/template" id="contact-item-list-tpl">
	<td><%- tcontacto_nombres %> <%- tcontacto_apellidos %></td>
	<td><%- tcontacto_direccion %></td>
	<td><%- tcontacto_telefono %></td>
	<td><%- tcontacto_celular %></td>
    <% if(edit) { %>
    	<td class="text-center">
    		<a class="btn btn-default btn-xs btn-edit-tcontacto" data-resource="<%- id %>">
    			<span><i class="fa fa-pencil-square-o"></i></span>
    		</a>
    	</td>
    <% } %>
</script>

<script type="text/template" id="roles-item-list-tpl">
	<% if(edit) { %>
        <td class="text-center">
            <a class="btn btn-default btn-xs item-roles-remove" data-resource="<%- id %>">
                <span><i class="fa fa-times"></i></span>
            </a>
    	</td>
    <% } %>
	<td><%- display_name %></td>
</script>

<script type="text/template" id="add-puntoventa-tpl">
    <div class="row">
		<div class="form-group col-md-6">
			<label for="puntoventa_nombre" class="control-label">Nombre</label>
			<input type="text" id="puntoventa_nombre" name="puntoventa_nombre" value="<%- puntoventa_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="200" required>
		</div>
    </div>

    <div class="row">
		<div class="form-group col-md-2">
			<label for="puntoventa_prefijo" class="control-label">Prefijo</label>
			<input type="text" id="puntoventa_prefijo" name="puntoventa_prefijo" value="<%- puntoventa_prefijo %>" placeholder="Prefijo" class="form-control input-sm input-toupper" maxlength="4">
		</div>

		<div class="form-group col-md-4">
			<label for="puntoventa_resolucion_dian" class="control-label">Resolución de facturación DIAN</label>
			<input type="text" id="puntoventa_resolucion_dian" name="puntoventa_resolucion_dian" value="<%- puntoventa_resolucion_dian %>" placeholder="Resolución de facturación DIAN" class="form-control input-sm input-toupper" maxlength="200">
		</div>
    </div>
</script>

{{-- Accounting templates --}}
<script type="text/template" id="facturapt-item-list-tpl">
	<td><%- facturap1_factura %></td>
    <td><%- facturap2_cuota %></td>
    <td><%- facturap1_fecha %></td>
    <td><%- facturap2_vencimiento %></td>
    <td class="text-right"><%- window.Misc.currency(facturap2_saldo) %></td>
    <td class="text-right"><%- dias %></td>
</script>

<script type="text/template" id="add-centrocosto-tpl">
    <div class="row">
		<div class="form-group col-md-2">
			<label for="centrocosto_codigo" class="control-label">Código</label>
			<input type="text" id="centrocosto_codigo" name="centrocosto_codigo" value="<%- centrocosto_codigo %>" placeholder="Código" class="form-control input-sm input-toupper" maxlength="4" required>
		</div>
    	<div class="form-group col-md-3">
			<label for="centrocosto_centro" class="control-label">Centro</label>
			<input type="text" id="centrocosto_centro" name="centrocosto_centro" value="<%- centrocosto_centro %>" placeholder="Centro" class="form-control input-sm input-toupper" maxlength="20" required>
		</div>
		<div class="form-group col-md-7">
			<label for="centrocosto_nombre" class="control-label">Nombre</label>
			<input type="text" id="centrocosto_nombre" name="centrocosto_nombre" value="<%- centrocosto_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="200" required>
		</div>
    </div>

	<div class="row">
		<div class="form-group col-md-12">
			<label for="centrocosto_descripcion1" class="control-label">Descripción 1</label>
			<input type="text" id="centrocosto_descripcion1" name="centrocosto_descripcion1" value="<%- centrocosto_descripcion1 %>" placeholder="Descripción 1" class="form-control input-sm input-toupper" maxlength="200">
		</div>
    </div>

	<div class="row">
		<div class="form-group col-md-12">
			<label for="centrocosto_descripcion2" class="control-label">Descripción 2</label>
			<input type="text" id="centrocosto_descripcion2" name="centrocosto_descripcion2" value="<%- centrocosto_descripcion2 %>" placeholder="Descripción 2" class="form-control input-sm input-toupper" maxlength="200">
		</div>
    </div>

    <div class="row">
		<div class="form-group col-md-12 col-sm-12 col-xs-12">
			<label class="control-label">Tipo</label>
			<div class="row">
				@foreach(config('koi.contabilidad.centrocosto.tipo') as $key => $value)
					<label class="radio-inline" for="centrocosto_tipo_{{ $key }}">
						<input type="radio" id="centrocosto_tipo_{{ $key }}" name="centrocosto_tipo" value="{{ $key }}" <%- centrocosto_tipo == '{{ $key }}' ? 'checked': '' %> >{{ $value }}
					</label>
				@endforeach
			</div>
		</div>
    </div>

	<div class="row">
		<div class="form-group col-md-2 col-xs-4 col-sm-4">
			<label for="centrocosto_estructura" class="control-label">Título</label>
			<select name="centrocosto_estructura" id="centrocosto_estructura" class="form-control" required>
				<option value="N" <%- centrocosto_estructura == 'N' ? 'selected': ''%>>No</option>
				<option value="S" <%- centrocosto_estructura == 'S' ? 'selected': ''%>>Si</option>
			</select>
		</div>
		<div class="form-group col-md-2 col-xs-8 col-sm-3">
			<br><label class="checkbox-inline" for="centrocosto_activo">
				<input type="checkbox" id="centrocosto_activo" name="centrocosto_activo" value="centrocosto_activo" <%- parseInt(centrocosto_activo) ? 'checked': ''%>> Activo
			</label>
		</div>
    </div>
</script>

<script type="text/template" id="add-plancuentas-tpl">
    <div class="row">
		<div class="form-group col-md-3">
			<label for="plancuentas_cuenta" class="control-label">Cuenta</label>
			<div class="row">
				<div class="col-md-9">
					<input type="text" id="plancuentas_cuenta" name="plancuentas_cuenta" value="<%- plancuentas_cuenta %>" placeholder="Cuenta" class="form-control input-sm" maxlength="15" required>
				</div>
				<div class="col-md-3">
					<input type="text" id="plancuentas_nivel" name="plancuentas_nivel" value="<%- plancuentas_nivel %>" class="form-control input-sm" maxlength="1" required readonly>
				</div>
			</div>
		</div>

		<div class="form-group col-md-7">
			<label for="plancuentas_nombre" class="control-label">Nombre</label>
			<input type="text" id="plancuentas_nombre" name="plancuentas_nombre" value="<%- plancuentas_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="200" required>
		</div>
	</div>

	<div class="row">
		<div class="form-group col-md-6 col-xs-10">
			<label for="plancuentas_centro" class="control-label">Centro de costo</label>
			<select name="plancuentas_centro" id="plancuentas_centro" class="form-control select2-default-clear">
				@foreach( App\Models\Accounting\CentroCosto::getCentrosCosto('S') as $key => $value)
					<option value="{{ $key }}" <%- plancuentas_centro == '{{ $key }}' ? 'selected': ''%> >{{ $value }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group col-md-1 col-xs-2 text-right">
			<div>&nbsp;</div>
			<button type="button" class="btn btn-default btn-flat btn-sm btn-add-resource-koi-component" data-resource="centrocosto" data-field="plancuentas_centro">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>

	<div class="row">
		<div class="form-group col-md-6 col-sm-12 col-xs-12">
			<label class="control-label">Naturaleza</label>
			<div class="row">
				<label class="radio-inline" for="plancuentas_naturaleza_debito">
					<input type="radio" id="plancuentas_naturaleza_debito" name="plancuentas_naturaleza" value="D" <%- plancuentas_naturaleza == 'D' ? 'checked': ''%>> Débito
				</label>

				<label class="radio-inline" for="plancuentas_naturaleza_credito">
					<input type="radio" id="plancuentas_naturaleza_credito" name="plancuentas_naturaleza" value="C" <%- plancuentas_naturaleza == 'C' ? 'checked': ''%>> Crédito
				</label>
			</div>
		</div>
	</div>

    <div class="row">
		<div class="form-group col-md-3 col-sm-12 col-xs-12">
			<label for="plancuentas_tercero">¿Requiere tercero?</label>
			<div class="row">
				<label class="radio-inline" for="plancuentas_tercero_no">
					<input type="radio" id="plancuentas_tercero_no" name="plancuentas_tercero" value="plancuentas_tercero_no" <%- !plancuentas_tercero ? 'checked': ''%>> No
				</label>
				<label class="radio-inline" for="plancuentas_tercero_si">
					<input type="radio" id="plancuentas_tercero_si" name="plancuentas_tercero" value="plancuentas_tercero" <%- plancuentas_tercero ? 'checked': ''%>> Si
				</label>
			</div>
		</div>
    </div>

    <div class="row">
		<div class="form-group col-md-12 col-sm-12 col-xs-12">
			<label class="control-label">Tipo</label>
			<div class="row">
				@foreach(config('koi.contabilidad.plancuentas.tipo') as $key => $value)
					<label class="radio-inline" for="plancuentas_naturaleza_{{ $key }}">
						<input type="radio" id="plancuentas_naturaleza_{{ $key }}" name="plancuentas_tipo" value="{{ $key }}" <%- plancuentas_tipo == '{{ $key }}' ? 'checked': ''%>> {{ $value }}
					</label>
				@endforeach
			</div>
		</div>
    </div>

    <div class="row">
		<div class="form-group col-md-2">
			<label for="plancuentas_tasa" class="control-label">Tasa</label>
			<input type="text" id="plancuentas_tasa" name="plancuentas_tasa" value="<%- plancuentas_tasa ? plancuentas_tasa : '0' %>" placeholder="Tasa" class="form-control input-sm" required>
		</div>

		<div class="form-group col-md-4">
			<label for="plancuentas_equivalente" class="control-label">Equivalencia en NIF</label>
			<select name="plancuentas_equivalente" id="plancuentas_equivalente" class="form-control select2-default-clear">
				@foreach( App\Models\Accounting\PlanCuentaNif::getPlanCuentas() as $key => $value)
					<option value="{{ $key }}" <%- plancuentas_equivalente == '{{ $key }}' ? 'selected': ''%> >{{ $value }}</option>
				@endforeach
			</select>
		</div>
    </div>
</script>

<script type="text/template" id="add-plancuentasnif-tpl">
    <div class="row">
		<div class="form-group col-md-3">
			<label for="plancuentasn_cuenta" class="control-label">Cuenta</label>
			<div class="row">
				<div class="col-md-9">
					<input type="text" id="plancuentasn_cuenta" name="plancuentasn_cuenta" value="<%- plancuentasn_cuenta %>" placeholder="Cuenta" class="form-control input-sm" maxlength="15" required>
				</div>
				<div class="col-md-3">
					<input type="text" id="plancuentasn_nivel" name="plancuentasn_nivel" value="<%- plancuentasn_nivel %>" class="form-control input-sm" maxlength="1" required readonly>
				</div>
			</div>
		</div>

		<div class="form-group col-md-7">
			<label for="plancuentasn_nombre" class="control-label">Nombre</label>
			<input type="text" id="plancuentasn_nombre" name="plancuentasn_nombre" value="<%- plancuentasn_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="200" required>
		</div>
	</div>

	<div class="row">
		<div class="form-group col-md-6 col-xs-10">
			<label for="plancuentasn_centro" class="control-label">Centro de costo</label>
			<select name="plancuentasn_centro" id="plancuentasn_centro" class="form-control select2-default-clear">
				@foreach( App\Models\Accounting\CentroCosto::getCentrosCosto('S') as $key => $value)
					<option value="{{ $key }}" <%- plancuentasn_centro == '{{ $key }}' ? 'selected': ''%> >{{ $value }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group col-md-1 col-xs-2 text-right">
			<div>&nbsp;</div>
			<button type="button" class="btn btn-default btn-flat btn-sm btn-add-resource-koi-component" data-resource="centrocosto" data-field="plancuentasn_centro">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>

	<div class="row">
		<div class="form-group col-md-6 col-sm-12 col-xs-12">
			<label class="control-label">Naturaleza</label>
			<div class="row">
				<label class="radio-inline" for="plancuentasn_naturaleza_debito">
					<input type="radio" id="plancuentasn_naturaleza_debito" name="plancuentasn_naturaleza" value="D" <%- plancuentasn_naturaleza == 'D' ? 'checked': ''%>> Débito
				</label>

				<label class="radio-inline" for="plancuentasn_naturaleza_credito">
					<input type="radio" id="plancuentasn_naturaleza_credito" name="plancuentasn_naturaleza" value="C" <%- plancuentasn_naturaleza == 'C' ? 'checked': ''%>> Crédito
				</label>
			</div>
		</div>
	</div>

    <div class="row">
		<div class="form-group col-md-3 col-sm-12 col-xs-12">
			<label for="plancuentasn_tercero">¿Requiere tercero?</label>
			<div class="row">
				<label class="radio-inline" for="plancuentasn_tercero_no">
					<input type="radio" id="plancuentasn_tercero_no" name="plancuentasn_tercero" value="plancuentasn_tercero_no" <%- !plancuentasn_tercero ? 'checked': ''%>> No
				</label>
				<label class="radio-inline" for="plancuentasn_tercero_si">
					<input type="radio" id="plancuentasn_tercero_si" name="plancuentasn_tercero" value="plancuentasn_tercero" <%- plancuentasn_tercero ? 'checked': ''%>> Si
				</label>
			</div>
		</div>
    </div>

    <div class="row">
		<div class="form-group col-md-12 col-sm-12 col-xs-12">
			<label class="control-label">Tipo</label>
			<div class="row">
				@foreach(config('koi.contabilidad.plancuentas.tipo') as $key => $value)
					<label class="radio-inline" for="plancuentasn_naturaleza_{{ $key }}">
						<input type="radio" id="plancuentasn_naturaleza_{{ $key }}" name="plancuentasn_tipo" value="{{ $key }}" <%- plancuentasn_tipo == '{{ $key }}' ? 'checked': ''%>> {{ $value }}
					</label>
				@endforeach
			</div>
		</div>
    </div>

    <div class="row">
		<div class="form-group col-md-2">
			<label for="plancuentasn_tasa" class="control-label">Tasa</label>
			<input type="text" id="plancuentasn_tasa" name="plancuentasn_tasa" value="<%- plancuentasn_tasa ? plancuentasn_tasa : '0' %>" placeholder="Tasa" class="form-control input-sm" required>
		</div>
    </div>
</script>
<script type="text/template" id="add-folder-tpl">
    <div class="row">
		<div class="form-group col-md-2">
			<label for="folder_codigo" class="control-label">Código</label>
			<input type="text" id="folder_codigo" name="folder_codigo" value="<%- folder_codigo %>" placeholder="Código" class="form-control input-sm input-toupper" maxlength="4" required>
		</div>
    </div>
    <div class="row">
		<div class="form-group col-md-8">
			<label for="folder_nombre" class="control-label">Nombre</label>
			<input type="text" id="folder_nombre" name="folder_nombre" value="<%- folder_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="50" required>
		</div>
    </div>
</script>

<script type="text/template" id="add-documento-tpl">
    <div class="row">
		<div class="form-group col-md-2">
			<label for="documento_codigo" class="control-label">Código</label>
			<input type="text" id="documento_codigo" name="documento_codigo" value="<%- documento_codigo %>" placeholder="Código" class="form-control input-sm input-toupper" maxlength="20" required>
		</div>
		<div class="form-group col-md-8">
			<label for="documento_nombre" class="control-label">Nombre</label>
			<input type="text" id="documento_nombre" name="documento_nombre" value="<%- documento_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="200" required>
		</div>
    </div>
	<div class="row">
		<div class="form-group col-md-6 col-xs-10">
			<label for="documento_folder" class="control-label">Folder</label>
			<select name="documento_folder" id="documento_folder" class="form-control select2-default" required>
				@foreach( App\Models\Accounting\Folder::getFolders() as $key => $value)
					<option value="{{ $key }}" <%- documento_folder == '{{ $key }}' ? 'selected': ''%> >{{ $value }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group col-md-1 col-xs-2 text-right">
			<div>&nbsp;</div>
			<button type="button" class="btn btn-default btn-flat btn-sm btn-add-resource-koi-component" data-resource="folder" data-field="documento_folder">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
    <div class="row">
		<div class="form-group col-md-12 col-sm-12 col-xs-12">
			<label class="control-label">Consecutivo</label>
			<div class="row">
				@foreach(config('koi.contabilidad.documento.consecutivo') as $key => $value)
					<label class="radio-inline" for="documento_tipo_consecutivo_{{ $key }}">
						<input type="radio" id="documento_tipo_consecutivo_{{ $key }}" name="documento_tipo_consecutivo" value="{{ $key }}" <%- documento_tipo_consecutivo == '{{ $key }}' ? 'checked': ''%>> {{ $value }}
					</label>
				@endforeach
			</div>
		</div>
		<div class="form-group col-md-4">
			<label class="control-label">Tipo contabilidad </label>
			<div class="row">
				<label class="checkbox-inline" for="documento_actual">
					<input type="checkbox" id="documento_actual" name="documento_actual" value="documento_actual" <%- parseInt(documento_actual) ? 'checked': ''%>> Normal
				</label>
				<label class="checkbox-inline" for="documento_nif">
					<input type="checkbox" id="documento_nif" name="documento_nif" value="documento_nif" <%- parseInt(documento_nif) ? 'checked': ''%>> Nif
				</label>
			</div>
		</div>
    </div>
</script>

<script type="text/template" id="add-actividad-tpl">
    <div class="row">
		<div class="form-group col-md-2">
			<label for="actividad_codigo" class="control-label">Código</label>
			<input type="text" id="actividad_codigo" name="actividad_codigo" value="<%- actividad_codigo %>" placeholder="Código" class="form-control input-sm input-toupper" maxlength="11" required>
		</div>
		<div class="form-group col-md-8">
			<label for="actividad_nombre" class="control-label">Nombre</label>
			<input type="text" id="actividad_nombre" name="actividad_nombre" value="<%- actividad_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" required>
		</div>
    </div>
    <div class="row">
		<div class="form-group col-md-2">
			<label for="actividad_tarifa" class="control-label">% Cree</label><br>
			<input type="text" id="actividad_tarifa" name="actividad_tarifa" value="<%- actividad_tarifa %>" placeholder="% Cree" class="form-control input-sm spinner-percentage" maxlength="4" required>
		</div>
    	<div class="form-group col-md-2">
			<label for="actividad_categoria" class="control-label">Categoria</label>
			<input type="text" id="actividad_categoria" name="actividad_categoria" value="<%- actividad_categoria %>" placeholder="Categoria" class="form-control input-sm input-toupper" maxlength="3">
		</div>
    </div>
</script>

<script type="text/template" id="add-itemrollo-tpl">
    <td class="text-center"><%- id %></td>
    <td>
		<input id="itemrollo_metros_<%- id %>" name="itemrollo_metros_<%- id %>" class="form-control input-sm text-right" type="number" value="0" min="0.1" step="0.1" required>
    </td>
</script>

<script type="text/template" id="choose-itemrollo-tpl">
    <td class="text-center"><%- prodboderollo_item %></td>
    <td class="text-right"><%- prodboderollo_metros %></td>
    <td class="text-right"><%- prodboderollo_saldo %></td>
    <td>
        <div class="form-group">
            <input id="itemrollo_metros_<%- id %>" name="itemrollo_metros_<%- id %>" class="form-control input-sm text-right" type="number" value="0" min="0" max="<%- prodboderollo_saldo %>" step="0.1">
        </div>
    </td>
</script>
<script type="text/template" id="itemrollo-tpl">
    <td class="text-center"><%- prodboderollo_item %></td>
    <td class="text-right"><%- prodboderollo_metros %></td>
    <td class="text-right"><%- prodboderollo_saldo %></td>
</script>

<script type="text/template" id="add-serie-tpl">
    <td class="text-center"><%- id %></td>
    <td>
    	<input type="text" id="producto_serie_<%- id %>" name="producto_serie_<%- id %>" class="form-control input-sm input-toupper" maxlength="15" required>
    </td>
</script>

{{-- Inventory templates --}}
<script type="text/template" id="add-subgrupo-tpl">
    <div class="row">
		<div class="form-group col-md-2">
			<label for="subgrupo_codigo" class="control-label">Código</label>
			<input type="text" id="subgrupo_codigo" name="subgrupo_codigo" value="<%- subgrupo_codigo %>" placeholder="Código" class="form-control input-sm input-toupper" maxlength="4" required>
		</div>
    </div>
    <div class="row">
		<div class="form-group col-md-8">
			<label for="subgrupo_nombre" class="control-label">Nombre</label>
			<input type="text" id="subgrupo_nombre" name="subgrupo_nombre" value="<%- subgrupo_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="50" required>
		</div>
    </div>
</script>

<script type="text/template" id="add-grupo-tpl">
    <div class="row">
		<div class="form-group col-md-2">
			<label for="grupo_codigo" class="control-label">Código</label>
			<input type="text" id="grupo_codigo" name="grupo_codigo" value="<%- grupo_codigo %>" placeholder="Código" class="form-control input-sm input-toupper" maxlength="4" required>
		</div>
    </div>
    <div class="row">
		<div class="form-group col-md-8">
			<label for="grupo_nombre" class="control-label">Nombre</label>
			<input type="text" id="grupo_nombre" name="grupo_nombre" value="<%- grupo_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="50" required>
		</div>
    </div>
</script>

<script type="text/template" id="add-unidad-tpl">
    <div class="row">
		<div class="form-group col-md-2">
			<label for="unidadmedida_sigla " class="control-label">Sigla</label>
			<input type="text" id="unidadmedida_sigla" name="unidadmedida_sigla" value="<%- unidadmedida_sigla %>" placeholder="Sigla" class="form-control input-sm input-toupper" maxlength="15" required>
		</div>
    </div>
    <div class="row">
		<div class="form-group col-md-8">
			<label for="unidadmedida_nombre" class="control-label">Nombre</label>
			<input type="text" id="unidadmedida_nombre" name="unidadmedida_nombre" value="<%- unidadmedida_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="100" required>
		</div>
    </div>
</script>

<script type="text/template" id="add-producto-tpl">
	<div class="row">
		<div class="form-group col-md-3">
			<label for="producto_codigo" class="control-label">Código</label>
			<input type="text" id="producto_codigo" name="producto_codigo" value="<%- producto_codigo %>" placeholder="Código" class="form-control input-sm input-toupper" maxlength="15" required>
            <div class="help-block with-errors"></div>
		</div>

		<div class="form-group col-md-3">
			<label for="producto_codigoori" class="control-label">Código proveedor</label>
			<input type="text" id="producto_codigoori" name="producto_codigoori" value="<%- producto_codigoori %>" placeholder="Código" class="form-control input-sm input-toupper" maxlength="15" required>
            <div class="help-block with-errors"></div>
		</div>

		<div class="form-group col-md-6">
			<label for="producto_nombre" class="control-label">Nombre</label>
			<input type="text" id="producto_nombre" name="producto_nombre" value="<%- producto_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="200" required>
            <div class="help-block with-errors"></div>
		</div>
	</div>

	<div class="row">
		<div class="form-group col-md-3 col-xs-10">
			<label for="producto_grupo" class="control-label">Grupo</label>
			<select name="producto_grupo" id="producto_grupo" class="form-control select2-default" required>
				@foreach( App\Models\Inventory\Grupo::getGrupos() as $key => $value)
					<option value="{{ $key }}" <%- producto_grupo == '{{ $key }}' ? 'selected': ''%> >{{ $value }}</option>
				@endforeach
			</select>
            <div class="help-block with-errors"></div>
		</div>
		<div class="form-group col-md-1 col-xs-2 text-right">
			<div>&nbsp;</div>
			<button type="button" class="btn btn-default btn-flat btn-sm btn-add-resource-koi-component" data-resource="grupo" data-field="producto_grupo">
				<i class="fa fa-plus"></i>
			</button>
		</div>

		<div class="form-group col-md-3 col-xs-10">
			<label for="producto_subgrupo" class="control-label">Subgrupo</label>
			<select name="producto_subgrupo" id="producto_subgrupo" class="form-control select2-default" required>
				@foreach( App\Models\Inventory\SubGrupo::getSubGrupos() as $key => $value)
					<option value="{{ $key }}" <%- producto_subgrupo == '{{ $key }}' ? 'selected': ''%> >{{ $value }}</option>
				@endforeach
			</select>
            <div class="help-block with-errors"></div>
		</div>
		<div class="form-group col-md-1 col-xs-2 text-right">
			<div>&nbsp;</div>
			<button type="button" class="btn btn-default btn-flat btn-sm btn-add-resource-koi-component" data-resource="subgrupo" data-field="producto_subgrupo">
				<i class="fa fa-plus"></i>
			</button>
		</div>
        <div class="form-group col-md-4 col-xs-10">
            <label for="producto_materialp" class="control-label">Material de producción</label>
            <select name="producto_materialp" id="producto_materialp" class="form-control select2-default-clear">
                @foreach( App\Models\Production\Materialp::getMateriales() as $key => $value)
                    <option value="{{ $key }}" <%- producto_materialp == '{{ $key }}' ? 'selected': ''%> >{{ $value }}</option>
                @endforeach
            </select>
        </div>
	</div>

	<div class="row">
		<div class="form-group col-md-3 col-xs-10">
			<label for="producto_unidadmedida" class="control-label">Unidad de medida</label>
			<select name="producto_unidadmedida" id="producto_unidadmedida" class="form-control select2-default-clear">
				@foreach( App\Models\Inventory\Unidad::getUnidades() as $key => $value)
					<option value="{{ $key }}" <%- producto_unidadmedida == '{{ $key }}' ? 'selected': ''%> >{{ $value }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group col-md-1 col-xs-2 text-right">
			<div>&nbsp;</div>
			<button type="button" class="btn btn-default btn-flat btn-sm btn-add-resource-koi-component" data-resource="unidadmedida" data-field="producto_unidadmedida">
				<i class="fa fa-plus"></i>
			</button>
		</div>

		<div class="form-group col-md-2 col-xs-12">
			<label for="producto_vidautil" class="control-label">Vida útil</label>
			<input type="number" id="producto_vidautil" name="producto_vidautil" value="<%- producto_vidautil %>" placeholder="Vida útil" class="form-control input-sm">
		</div>
	</div>

	<div class="row">
		<div class="form-group col-md-2 col-xs-4">
			<label for="producto_unidades" class="control-label">¿Maneja unidades?</label>
			<div><input type="checkbox" id="producto_unidades" name="producto_unidades" value="producto_unidades" <%- parseInt(producto_unidades) ? 'checked': ''%>></div>
		</div>

		<div class="form-group col-md-2 col-xs-4">
			<label for="producto_serie" class="control-label">¿Maneja serie?</label>
			<div><input type="checkbox" id="producto_serie" name="producto_serie" value="producto_serie" <%- parseInt(producto_serie) ? 'checked': ''%>></div>
		</div>

		<div class="form-group col-md-2 col-xs-4">
			<label for="producto_metrado" class="control-label">¿Producto metrado?</label>
			<div><input type="checkbox" id="producto_metrado" name="producto_metrado" value="producto_metrado" <%- parseInt(producto_metrado) ? 'checked': ''%>></div>
		</div>

		<div class="form-group col-md-2 col-xs-4">
			<label for="producto_ancho" class="control-label">Ancho</label>
            <input type="number" id="producto_ancho" name="producto_ancho" value="<%- producto_ancho %>" placeholder="Ancho" class="form-control input-sm" disabled required>
            <div class="help-block with-errors"></div>
		</div>
		<div class="form-group col-md-2 col-xs-4">
			<label for="producto_largo" class="control-label">Largo</label>
            <input type="number" id="producto_largo" name="producto_largo" value="<%- producto_largo %>" placeholder="Ancho" class="form-control input-sm" disabled required>
            <div class="help-block with-errors"></div>
		</div>
	</div>
</script>

{{-- Production templates --}}
<script type="text/template" id="add-areap-tpl">
	<div class="row">
		<div class="form-group col-md-8">
			<label for="areap_nombre" class="control-label">Nombre</label>
			<input type="text" id="areap_nombre" name="areap_nombre" value="<%- areap_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="200" required>
		</div>

		<div class="form-group col-md-3">
			<label for="areap_nombre" class="control-label">Valor</label>
			<input type="text" id="areap_valor" name="areap_valor" value="<%- areap_valor %>" placeholder="Nombre" class="form-control input-sm input-toupper" data-currency required>
		</div>
    </div>
</script>

<script type="text/template" id="add-acabadop-tpl">
	<div class="row">
		<div class="form-group col-md-8">
			<label for="acabadop_nombre" class="control-label">Nombre</label>
			<input type="text" id="acabadop_nombre" name="acabadop_nombre" value="<%- acabadop_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="200" required>
		</div>
    </div>

    <div class="row">
		<div class="form-group col-md-8">
			<label for="acabadop_descripcion" class="control-label">Descripción</label>
            <textarea id="acabadop_descripcion" name="acabadop_descripcion" class="form-control" rows="2" placeholder="Descripción"><%- acabadop_descripcion %></textarea>
		</div>
    </div>
</script>

<script type="text/template" id="add-maquinap-tpl">
	<div class="row">
		<div class="form-group col-md-8">
			<label for="maquinap_nombre" class="control-label">Nombre</label>
			<input type="text" id="maquinap_nombre" name="maquinap_nombre" value="<%- maquinap_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="200" required>
		</div>
    </div>
</script>

<script type="text/template" id="add-materialp-tpl">
	<div class="row">
		<div class="form-group col-md-8">
			<label for="materialp_nombre" class="control-label">Nombre</label>
			<input type="text" id="materialp_nombre" name="materialp_nombre" value="<%- materialp_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="200" required>
		</div>

		<div class="form-group col-md-4">
			<label for="materialp_tipomaterial" class="control-label">Tipo de material</label>
			<select name="materialp_tipomaterial" id="materialp_tipomaterial" class="form-control select2-default-clear" required>
				<option value="" selected>Seleccione</option>
				@foreach( App\Models\Production\TipoMaterialp::getTiposMaterialp() as $key => $value)
					<option value="{{ $key }}" <%- materialp_tipomaterial == '{{ $key }}' ? 'selected': ''%> >{{ $value }}</option>
				@endforeach
			</select>
		</div>
    </div>

    <div class="row">
		<div class="form-group col-md-8">
			<label for="materialp_descripcion" class="control-label">Descripción</label>
            <textarea id="materialp_descripcion" name="materialp_descripcion" class="form-control" rows="2" placeholder="Descripción"><%- materialp_descripcion %></textarea>
		</div>
    </div>
</script>

<script type="text/template" id="add-tipomaterialp-tpl">
	<div class="row">
		<div class="form-group col-md-6">
			<label for="tipomaterial_nombre" class="control-label">Nombre</label>
			<input type="text" id="tipomaterial_nombre" name="tipomaterial_nombre" value="<%- tipomaterial_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="50" required>
		</div>

		<div class="form-group col-md-2 col-xs-6">
			<label for="tipomaterial_activo" class="control-label">Activo</label>
			<div><input type="checkbox" id="tipomaterial_activo" name="tipomaterial_activo" value="tipomaterial_activo" <%- parseInt(tipomaterial_activo) ? 'checked': ''%>></div>
		</div>
    </div>
</script>

{{-- Template TipoProductop --}}
<script type="text/template" id="add-tipoproductop-tpl">
	<div class="row">
		<div class="form-group col-md-6">
			<label for="tipoproductop_nombre" class="control-label">Nombre</label>
			<input type="text" id="tipoproductop_nombre" name="tipoproductop_nombre" value="<%- tipoproductop_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="35" required>
		</div>

		<div class="form-group col-md-2 col-xs-6">
			<label for="tipoproductop_activo" class="control-label">Activo</label>
			<div><input type="checkbox" id="tipoproductop_activo" name="tipoproductop_activo" value="tipoproductop_activo" <%- parseInt(tipoproductop_activo) ? 'checked': ''%>></div>
		</div>
    </div>
</script>

{{-- Template SubtipoProductop --}}
<script type="text/template" id="add-subtipoproductop-tpl">
	<div class="row">
        <div class="form-group col-md-4">
			<label for="subtipoproductop_tipoproductop" class="control-label">Tipo de producto</label>
			<select name="subtipoproductop_tipoproductop" id="subtipoproductop_tipoproductop" class="form-control select2-default-clear" required>
				<option value="" selected>Seleccione</option>
				@foreach( App\Models\Production\TipoProductop::getTypeProductsp() as $key => $value)
					<option value="{{ $key }}" <%- subtipoproductop_tipoproductop == '{{ $key }}' ? 'selected': ''%> >{{ $value }}</option>
				@endforeach
			</select>
		</div>

		<div class="form-group col-md-5">
			<label for="subtipoproductop_nombre" class="control-label">Nombre</label>
			<input type="text" id="subtipoproductop_nombre" name="subtipoproductop_nombre" value="<%- subtipoproductop_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="25" required>
		</div>

		<div class="form-group col-md-2 col-xs-6">
			<label for="subtipoproductop_activo" class="control-label">Activo</label>
			<div><input type="checkbox" id="subtipoproductop_activo" name="subtipoproductop_activo" value="subtipoproductop_activo" <%- parseInt(subtipoproductop_activo) ? 'checked': ''%>></div>
		</div>
    </div>
</script>

{{-- Template Actividadp --}}
<script type="text/template" id="add-actividadp-tpl">
	<div class="row">
		<div class="form-group col-md-8">
			<label for="actividadp_nombre" class="control-label">Nombre</label>
			<input type="text" id="actividadp_nombre" name="actividadp_nombre" value="<%- actividadp_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="50" required>
		</div>

		<div class="form-group col-md-2 col-xs-6">
			<label for="actividadp_activo" class="control-label">Activo</label>
			<div><input type="checkbox" id="actividadp_activo" name="actividadp_activo" value="actividadp_activo" <%- parseInt(actividadp_activo) ? 'checked': ''%>></div>
		</div>
    </div>
</script>

{{-- Template SubActividadp --}}
<script type="text/template" id="add-subactividadp-tpl">
	<div class="row">
        <div class="form-group col-md-4">
			<label for="subactividadp_actividadp" class="control-label">Actividad</label>
			<select name="subactividadp_actividadp" id="subactividadp_actividadp" class="form-control select2-default-clear" required>
				<option value="" selected>Seleccione</option>
				@foreach( App\Models\Production\Actividadp::getActividadesp() as $key => $value)
					<option value="{{ $key }}" <%- subactividadp_actividadp == '{{ $key }}' ? 'selected': ''%> >{{ $value }}</option>
				@endforeach
			</select>
		</div>

		<div class="form-group col-md-5">
			<label for="subactividadp_nombre" class="control-label">Nombre</label>
			<input type="text" id="subactividadp_nombre" name="subactividadp_nombre" value="<%- subactividadp_nombre %>" placeholder="Nombre" class="form-control input-sm input-toupper" maxlength="50" required>
		</div>

		<div class="form-group col-md-2 col-xs-6">
			<label for="subactividadp_activo" class="control-label">Activo</label>
			<div><input type="checkbox" id="subactividadp_activo" name="subactividadp_activo" value="subactividadp_activo" <%- parseInt(subactividadp_activo) ? 'checked': ''%>></div>
		</div>
    </div>
</script>


{{-- Inicio template general asiento detalle --}}
<script type="text/template" id="add-asiento2-item-tpl">
	<% if(edit) { %>
	<td class="text-center">
		<a class="btn btn-default btn-xs item-asiento2-remove" data-resource="<%- id %>" data-resource-nif = "<%- asientoNif2_id %>">
			<span><i class="fa fa-times"></i></span>
		</a>
	</td>
	<% } %>
	<td><%- plancuentas_cuenta %></td>
    <td><%- plancuentas_nombre %></td>
    <td>
    	<a href="<%- window.Misc.urlFull( Route.route('terceros.show', {terceros: asiento2_beneficiario}) ) %>" title="<%- tercero_nombre %>" target="_blank">
    		<%- tercero_nit %>
    	</a>
    </td>
    <td>
    	<% if( !_.isUndefined(asiento2_centro) && !_.isNull(asiento2_centro) && asiento2_centro != '') { %>
	    	<a href="<%- window.Misc.urlFull( Route.route('centroscosto.show', {centroscosto: asiento2_centro}) ) %>" title="<%- centrocosto_nombre %>" target="_blank">
	    		<%- centrocosto_codigo %>
	    	</a>
    	<% } %>
    </td>
    <td class="text-right"><%- window.Misc.currency(asiento2_base ? asiento2_base : 0) %></td>
    <td class="text-right"><%- window.Misc.currency(asiento2_debito ? asiento2_debito : 0) %></td>
    <td class="text-right"><%- window.Misc.currency(asiento2_credito ? asiento2_credito: 0) %></td>
    <td class="text-center">
		<a class="btn btn-default btn-xs item-asiento2-show-info" data-resource="<%- id %>">
			<span><i class="fa fa-info-circle"></i></span>
		</a>
	</td>
</script>

<script type="text/template" id="show-info-asiento2-tpl">
    <div class="row">
        <div class="form-group col-md-6">
            <label class="control-label">Naturaleza</label>
            <div><%- asiento2_naturaleza == 'D' ? 'Débito' : 'Crédito' %></div>
        </div>
        <div class="form-group col-md-6">
            <label class="control-label">Asiento N°</label>
            <div><%- asiento1_numero %></div>
        </div>
    </div>

    <!-- Orden -->
    <% if( !_.isUndefined(asiento2_ordenp) && !_.isNull(asiento2_ordenp) && asiento2_ordenp != ''){ %>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label">Orden de producción</label>
                <div><%- ordenp_codigo %> - <%- ordenp_beneficiario %></div>
            </div>
        </div>
    <% } %>

    <!-- Beneficiario -->
    <div class="row">
        <div class="form-group col-md-12">
            <label class="control-label">Beneficiario</label>
            <div><%- tercero_nit %> - <%- tercero_nombre %></div>
        </div>
    </div>

    <!-- Centro costo -->
    <% if( !_.isUndefined(asiento2_centro) && !_.isNull(asiento2_centro) && asiento2_centro != '') { %>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label">Centro Costo</label>
                <div><%- centrocosto_codigo %> - <%- centrocosto_nombre %></div>
            </div>
        </div>
    <% } %>

    <!-- Detalle -->
    <% if( !_.isUndefined(asiento2_detalle) && !_.isNull(asiento2_detalle) && asiento2_detalle != '') { %>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label">Detalle</label>
                <div><%- asiento2_detalle %></div>
            </div>
        </div>
    <% } %>

    <!-- Detalle -->
    <% if( !_.isUndefined(asiento1_documentos) && !_.isNull(asiento1_documentos) && asiento1_documentos != '') { %>
        <% if( asiento1_documentos == 'FACT') { %>
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="control-label">Documento</label>
                    <div><b><%- documento_nombre %></b> <a href="<%- window.Misc.urlFull( Route.route('facturas.show', { facturas: asiento1_id_documentos}) ) %>">Ver factura</a> </div>
                </div>
            </div>
        <% } %>
    <% } %>

    <div class="row" id="render-info-modal"></div>
</script>

<!-- Facturas, Facturap, Inventario -> Padres -->
<script type="text/template" id="add-info-facturap-item">
    <div class="box box-success">
        <div class="box-body">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <b>Factura proveedor </b><small>(<%- naturaleza == 'D' ? 'Débito' : 'Crédito' %>)</small>
                </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="control-label">Factura proveedor</label>
                        <div><%- father[0].movimiento_facturap %></div>
                    </div>
                    <% if( father[0].movimiento_nuevo ) { %>
                        <div class="form-group col-md-6">
                            <label class="control-label">Valor</label>
                            <div><%- window.Misc.currency( father[0].movimiento_valor ) %></div>
                        </div>
                    <% } %>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="control-label">Tercero</label>
                        <div><%- tercero.tercero_nit %> - <%- tercero.tercero_nombre %></div>
                    </div>
                </div>
                <% if( father[0].movimiento_nuevo ) { %>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Vencimiento</label>
                            <div><%- father[0].movimiento_fecha %></div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Cuotas</label>
                            <div><%- father[0].movimiento_item %></div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Periodicidad</label>
                            <div><%- father[0].movimiento_periodicidad %></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label">Observaciones</label>
                            <div><%- father[0].movimiento_observaciones %></div>
                        </div>
                    </div>
                <% } %>
            </div>
        </div>
    </div>
    <% if ( !father[0].movimiento_nuevo ) { %>
        <div class="box box-success">
            <div class="box-body">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>Información adicional</b></h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table id="browse-showinfo-facturap-list" class="table no-margin" cellspacing="0">
                        <tr>
                            <th class="text-center">Cuota</th>
                            <th class="text-center">Pago</th>
                        </tr>
                        <% _.each( father, function(children){ %>
                            <tr>
                                <td class="text-center"><%- children.facturap2_cuota %></td>
                                <td class="text-center"><%- window.Misc.currency( children.movimiento_valor ) %></td>
                            </tr>
                        <% }); %>
                    </table>
                </div>
            </div>
        </div>
     <% } %>
</script>

<script type="text/template" id="add-info-factura-item">
    <div class="box box-success">
        <div class="box-body">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <b>Factura </b><small>(<%- naturaleza == 'D' ? 'Débito' : 'Crédito' %>)</small>
                </h3>
                <div class="pull-right">
                    <% if ( !_.isNull(father.movimiento_factura) ) { %>
                        <b>Número </b><small># <%- father.factura1_numero %></small>
                    <% } %>
                    <b>Prefijo </b><small><%- father.puntoventa_prefijo %></small>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="control-label">Fecha</label>
                        <div><%- father.factura1_fecha %></div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label">Vencimiento</label>
                        <div><%- father.factura1_fecha_vencimiento %></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label class="control-label">Punto de venta</label>
                        <div><%- father.puntoventa_nombre %></div>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Valor</label>
                        <div><%- window.Misc.currency( father.factura1_total ) %></div>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Cuotas</label>
                        <div><%- father.factura1_cuotas %></div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="control-label">Tercero</label>
                        <div><%- father.tercero_nit %> - <%- father.tercero_nombre %></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Información adicional</b></h3>
            </div>

            <div class="box-body table-responsive no-padding">
                <table id="browse-showinfo-factura-list" class="table no-margin" cellspacing="0">
                    <tr>
                        <th class="text-center">Cuota No.</th>
                        <th class="text-center">Pago</th>
                    </tr>
                    <% _.each( childrens, function( children ){ %>
                        <tr>
                            <td class="text-center"><%- children.factura4_cuota %></td>
                            <td class="text-center"><%- window.Misc.currency( children.movimiento_valor ) %></td>
                        </tr>
                    <% }); %>
                </table>
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="add-info-inventario-item">
    <div class="box box-success">
        <div class="box-body">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <b>Inventario </b><small>(<%- naturaleza == 'D' ? 'Débito' : 'Crédito' %>)</small>
                </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="control-label">Tercero</label>
                        <div><%- tercero.tercero_nit %> - <%- tercero.tercero_nombre %></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="control-label">Producto</label>
                        <div><%- father.producto_codigo %> - <%- father.producto_nombre %></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="control-label">Sucursal</label>
                        <div><%- father.sucursal_nombre %></div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label">Cantidad</label>
                        <div><%- father.movimiento_valor %></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="box-header with-border">
                  <h3 class="box-title"><b>Información adicional</b></h3>
            </div>

            <div class="box-body table-responsive no-padding">
                <table id="browse-showinfo-asiento-list" class="table table-bordered" cellspacing="0">
                    <tr>
                        <th class="text-center">Item</th>
                        <th class="text-center"><%- !_.isNull( childrens[0].movimiento_serie) ? 'Series' : 'Metros (m)' %></th>
                    </tr>
                    <% _.each(childrens, function( children ){ %>
                        <tr>
                            <td class="text-center"><%- children.movimiento_item %></td>
                            <td class="text-center"><%- !_.isNull(children.movimiento_serie) ? children.movimiento_serie : children.movimiento_valor %></td>
                        </tr>
                    <% }); %>
                </table>
            </div>
        </div>
    </div>
</script>
{{-- fin template general asiento detalle --}}

<script type="text/template" id="add-seriesprodbode-tpl">
    <td><%- sucursal_nombre %></td>
    <td><%- producto_codigo %></td>
    <td><%- producto_nombre %></td>
</script>

<script type="text/template" id="qq-template">
    <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="{{ trans('app.files.drop') }}">
        <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
            <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
        </div>
        <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
            <span class="qq-upload-drop-area-text-selector"></span>
        </div>
        <div class="buttons">
            <div class="qq-upload-button-selector qq-upload-button">
                <div><i class="fa fa-folder-open" aria-hidden="true"></i> {{ trans('app.files.choose-file') }}</div>
            </div>
        </div>
        <span class="qq-drop-processing-selector qq-drop-processing">
            <span>{{ trans('app.files.process') }}</span>
            <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
        </span>
        <ul class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
            <li>
                <div class="qq-progress-bar-container-selector">
                    <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                </div>
                <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                <a class="preview-link" target="_blank">
                    <img class="qq-thumbnail-selector" qq-max-size="100" qq-server-scale>
                </a>
                <span class="qq-upload-file-selector qq-upload-file"></span>
                <span class="qq-upload-size-selector qq-upload-size"></span>
                <button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel">{{ trans('app.cancel') }}</button>
                <button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry">{{ trans('app.files.retry') }}</button>
                <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">{{ trans('app.delete') }}</button>
                <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
            </li>
        </ul>

        <dialog class="qq-alert-dialog-selector">
            <div class="qq-dialog-message-selector"></div>
            <div class="qq-dialog-buttons">
                <button type="button" class="qq-cancel-button-selector">Cerrar</button>
            </div>
        </dialog>

        <dialog class="qq-confirm-dialog-selector">
            <div class="qq-dialog-message-selector"></div>
            <div class="qq-dialog-buttons">
                <button type="button" class="qq-cancel-button-selector">No</button>
                <button type="button" class="qq-ok-button-selector">Si</button>
            </div>
        </dialog>

        <dialog class="qq-prompt-dialog-selector">
            <div class="qq-dialog-message-selector"></div>
            <input type="text">
            <div class="qq-dialog-buttons">
                <button type="button" class="qq-cancel-button-selector">{{ trans('app.cancel') }}</button>
                <button type="button" class="qq-ok-button-selector">{{ trans('app.continue') }}</button>
            </div>
        </dialog>
    </div>
</script>

<?php

namespace App\Http\Controllers\Production;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Production\PreCotizacion1, App\Models\Production\PreCotizacion2, App\Models\Production\PreCotizacion3, App\Models\Production\PreCotizacion4, App\Models\Production\PreCotizacion5, App\Models\Production\PreCotizacion6, App\Models\Production\PreCotizacion7, App\Models\Production\PreCotizacion8, App\Models\Production\Cotizacion1, App\Models\Production\Cotizacion2, App\Models\Production\Cotizacion3, App\Models\Production\Cotizacion4, App\Models\Production\Cotizacion5, App\Models\Production\Cotizacion6, App\Models\Production\Cotizacion7, App\Models\Production\Cotizacion8, App\Models\Base\Empresa, App\Models\Base\Tercero, App\Models\Base\Contacto;
use App, Auth, DB, Log, Datatables, Storage;

class PreCotizacion1Controller extends Controller
{
    /**
     * Instantiate a new Controller instance.
     */
    public function __construct()
    {
        $this->middleware('ability:admin,crear', ['only' => ['abrir', 'cerrar']]);
        $this->middleware('ability:admin,opcional2', ['only' => ['terminar', 'generar', 'clonar']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()){
            $query = PreCotizacion1::query();
            $query->select('koi_precotizacion1.id', DB::raw("CONCAT(precotizacion1_numero,'-',SUBSTRING(precotizacion1_ano, -2)) as precotizacion_codigo"), 'precotizacion1_culminada', 'precotizacion1_numero', 'precotizacion1_ano', 'precotizacion1_fecha', 'precotizacion1_abierta',
                DB::raw("
                    CONCAT(
                        (CASE WHEN tercero_persona = 'N'
                            THEN CONCAT(tercero_nombre1,' ',tercero_nombre2,' ',tercero_apellido1,' ',tercero_apellido2,
                                (CASE WHEN (tercero_razonsocial IS NOT NULL AND tercero_razonsocial != '') THEN CONCAT(' - ', tercero_razonsocial) ELSE '' END)
                            )
                            ELSE tercero_razonsocial
                        END),
                    ' (', precotizacion1_referencia ,')'
                    ) AS tercero_nombre"
                )
            );
            $query->join('koi_tercero', 'precotizacion1_cliente', '=', 'koi_tercero.id');

            // Persistent data filter
            if($request->has('persistent') && $request->persistent) {
                session(['searchprecotizacion_numero' => $request->has('precotizacion_numero') ? $request->precotizacion_numero : '']);
                session(['searchprecotizacion_tercero' => $request->has('precotizacion_tercero_nit') ? $request->precotizacion_tercero_nit : '']);
                session(['searchprecotizacion_tercero_nombre' => $request->has('precotizacion_tercero_nombre') ? $request->precotizacion_tercero_nombre : '']);
                session(['searchprecotizacion_estado' => $request->has('precotizacion_estado') ? $request->precotizacion_estado : '']);
            }

            // Permisions mostrar botones crear [close, open]
            if( Auth::user()->ability('admin', 'crear', ['module' => 'precotizaciones']) ) {
                $query->addSelect(DB::raw('TRUE as precotizacion_create'));
            } else {
                $query->addSelect(DB::raw('FALSE as precotizacion_create'));
            }

            // Permisions mostrar botones opcional2 [complete, clone]
            if( Auth::user()->ability('admin', 'opcional2', ['module' => 'precotizaciones']) ) {
                $query->addSelect(DB::raw('TRUE as precotizacion_opcional'));
            } else {
                $query->addSelect(DB::raw('FALSE as precotizacion_opcional'));
            }

            return Datatables::of($query)
                ->filter(function($query) use($request) {
                    // Cotizacion codigo
                    if($request->has('precotizacion_numero')) {
                        $query->whereRaw("CONCAT(precotizacion1_numero,'-',SUBSTRING(precotizacion1_ano, -2)) LIKE '%{$request->precotizacion_numero}%'");
                    }

                    // Tercero nit
                    if($request->has('precotizacion_tercero_nit')) {
                        $query->where('tercero_nit', $request->precotizacion_tercero_nit);
                    }

                    // Tercero id
                    if($request->has('precotizacion_cliente')) {
                        $query->where('precotizacion1_cliente', $request->precotizacion_cliente);
                    }

                    // Estado
                    if($request->has('precotizacion_estado')) {
                        if($request->precotizacion_estado == 'A') {
                            $query->where('precotizacion1_abierta', true);
                        }

                        if($request->precotizacion_estado == 'C') {
                            $query->where('precotizacion1_abierta', false);
                        }

                        if($request->precotizacion_estado == 'T') {
                            $query->where('precotizacion1_abierta', false);
                            $query->where('precotizacion1_culminada', true);
                        }
                    }
                })->make(true);
        }
        return view('production.precotizaciones.index', ['empresa' => parent::getPaginacion()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('production.precotizaciones.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();

            $precotizacion = new PreCotizacion1;
            if ($precotizacion->isValid($data)) {
                DB::beginTransaction();
                try {
                    // Recuperar tercero
                    $tercero = Tercero::where('tercero_nit', $request->precotizacion1_cliente)->first();
                    if(!$tercero instanceof Tercero) {
                        DB::rollback();
                        return response()->json(['success' => false, 'errors' => 'No es posible recuperar cliente, por favor verifique la información o consulte al administrador.']);
                    }

                    // Validar contacto
                    $contacto = Contacto::find($request->precotizacion1_contacto);
                    if(!$contacto instanceof Contacto) {
                        DB::rollback();
                        return response()->json(['success' => false, 'errors' => 'No es posible recuperar contacto, por favor verifique la información o consulte al administrador.']);
                    }
                    // Validar tercero contacto
                    if($contacto->tcontacto_tercero != $tercero->id) {
                        DB::rollback();
                        return response()->json(['success' => false, 'errors' => 'El contacto seleccionado no corresponde al tercero, por favor seleccione de nuevo el contacto o consulte al administrador.']);
                    }

                    // Actualizar telefono del contacto
                    if($contacto->tcontacto_telefono != $request->tcontacto_telefono) {
                        $contacto->tcontacto_telefono = $request->tcontacto_telefono;
                        $contacto->save();
                    }

                    // Recuperar numero cotizacion
                    $numero = DB::table('koi_precotizacion1')->where('precotizacion1_ano', date('Y'))->max('precotizacion1_numero');
                    $numero = !is_integer(intval($numero)) ? 1 : ($numero + 1);

                    // cotizacion
                    $precotizacion->fill($data);
                    $precotizacion->precotizacion1_cliente = $tercero->id;
                    $precotizacion->precotizacion1_ano = date('Y');
                    $precotizacion->precotizacion1_numero = $numero;
                    $precotizacion->precotizacion1_contacto = $contacto->id;
                    $precotizacion->precotizacion1_abierta = true;
                    $precotizacion->precotizacion1_fh_elaboro = date('Y-m-d H:m:s');
                    $precotizacion->precotizacion1_usuario_elaboro = Auth::user()->id;
                    $precotizacion->save();

                    // Commit Transaction
                    DB::commit();
                    return response()->json(['success' => true, 'id' => $precotizacion->id]);
                }catch(\Exception $e){
                    DB::rollback();
                    Log::error($e->getMessage());
                    return response()->json(['success' => false, 'errors' => trans('app.exception')]);
                }
            }
            return response()->json(['success' => false, 'errors' => $precotizacion->errors]);
        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $precotizacion = PreCotizacion1::getPreCotizacion($id);
        if(!$precotizacion instanceof PreCotizacion1){
            abort(404);
        }

        if ($request->ajax()) {
            return response()->json($precotizacion);
        }

        if( $precotizacion->precotizacion1_abierta == true ) {
            return redirect()->route('precotizaciones.edit', ['precotizacion' => $precotizacion]);
        }

        return view('production.precotizaciones.show', ['precotizacion' => $precotizacion]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $precotizacion = PreCotizacion1::getPreCotizacion($id);
        if(!$precotizacion instanceof PreCotizacion1) {
            abort(404);
        }

        if($precotizacion->precotizacion1_abierta == false ) {
            return redirect()->route('precotizaciones.show', ['precotizacion' => $precotizacion]);
        }

        return view('production.precotizaciones.create', ['precotizacion' => $precotizacion]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = $request->all();

            $precotizacion = PreCotizacion1::findOrFail($id);
            if ($precotizacion->isValid($data)) {
                DB::beginTransaction();
                try {
                    // Recuperar tercero
                    $tercero = Tercero::where('tercero_nit', $request->precotizacion1_cliente)->first();
                    if(!$tercero instanceof Tercero) {
                        DB::rollback();
                        return response()->json(['success' => false, 'errors' => 'No es posible recuperar cliente, por favor verifique la información o consulte al administrador.']);
                    }

                    // Validar contacto
                    $contacto = Contacto::find($request->precotizacion1_contacto);
                    if(!$contacto instanceof Contacto) {
                        DB::rollback();
                        return response()->json(['success' => false, 'errors' => 'No es posible recuperar contacto, por favor verifique la información o consulte al administrador.']);
                    }
                    // Validar tercero contacto
                    if($contacto->tcontacto_tercero != $tercero->id) {
                        DB::rollback();
                        return response()->json(['success' => false, 'errors' => 'El contacto seleccionado no corresponde al tercero, por favor seleccione de nuevo el contacto o consulte al administrador.']);
                    }

                    // Actualizar telefono del contacto
                    if($contacto->tcontacto_telefono != $request->tcontacto_telefono) {
                        $contacto->tcontacto_telefono = $request->tcontacto_telefono;
                        $contacto->save();
                    }

                    // Cotizacion
                    $precotizacion->fill($data);
                    $precotizacion->precotizacion1_cliente = $tercero->id;
                    $precotizacion->precotizacion1_contacto = $contacto->id;
                    $precotizacion->save();

                    // Commit Transaction
                    DB::commit();
                    return response()->json(['success' => true, 'id' => $precotizacion->id]);
                }catch(\Exception $e){
                    DB::rollback();
                    Log::error($e->getMessage());
                    return response()->json(['success' => false, 'errors' => trans('app.exception')]);
                }
            }
            return response()->json(['success' => false, 'errors' => $precotizacion->errors]);
        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Abrir the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function abrir(Request $request, $id)
    {
        if ($request->ajax()) {
            $precotizacion = PreCotizacion1::findOrFail($id);
            if(!$precotizacion instanceof PreCotizacion1){
                return response()->json(['success' => false, 'errors' => 'No es posible recuperar la pre-cotización, por favor verifique la información o consulte al administrador.']);
            }

            DB::beginTransaction();
            try {
                // Orden
                $precotizacion->precotizacion1_abierta = true;
                $precotizacion->precotizacion1_culminada = false;
                $precotizacion->precotizacion1_fh_culminada = NULL;
                $precotizacion->precotizacion1_usuario_culminada = NULL;
                $precotizacion->save();

                // Commit Transaction
                DB::commit();
                return response()->json(['success' => true, 'msg' => 'Pre-cotización reabierta con exito.']);
            }catch(\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
                return response()->json(['success' => false, 'errors' => trans('app.exception')]);
            }
        }
        abort(403);
    }

    /**
     * Cerrar the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cerrar(Request $request, $id)
    {
        if ($request->ajax()) {
            $precotizacion = PreCotizacion1::findOrFail($id);
            if(!$precotizacion instanceof PreCotizacion1){
                return response()->json(['success' => false, 'errors' => 'No es posible recuperar la pre-cotización, por favor verifique la información o consulte al adminitrador.']);
            }

            DB::beginTransaction();
            try {
                // Orden
                $precotizacion->precotizacion1_abierta = false;
                $precotizacion->precotizacion1_culminada = false;
                $precotizacion->precotizacion1_fh_culminada = NULL;
                $precotizacion->precotizacion1_usuario_culminada = NULL;
                $precotizacion->save();

                // Commit Transaction
                DB::commit();
                return response()->json(['success' => true, 'msg' => 'Pre-cotización cerrada con exito.']);
            }catch(\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
                return response()->json(['success' => false, 'errors' => trans('app.exception')]);
            }
        }
        abort(403);
    }

    /**
     * Terminar the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function terminar(Request $request, $id)
    {
        if ($request->ajax()) {
            $precotizacion = PreCotizacion1::findOrFail($id);
            if(!$precotizacion instanceof PreCotizacion1){
                return response()->json(['success' => false, 'errors' => 'No es posible recuperar la pre-cotización, por favor verifique la información o consulte al adminitrador.']);
            }

            DB::beginTransaction();
            try {
                // Pre cotizacion
                $precotizacion->precotizacion1_abierta = false;
                $precotizacion->precotizacion1_culminada = true;
                $precotizacion->precotizacion1_fh_culminada = date('Y-m-d H:m:s');
                $precotizacion->precotizacion1_usuario_culminada = Auth::user()->id;
                $precotizacion->save();

                // Commit Transaction
                DB::commit();
                return response()->json(['success' => true, 'msg' => 'Se culmino con exito la pre-cotización.']);
            }catch(\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
                return response()->json(['success' => false, 'errors' => trans('app.exception')]);
            }
        }
        abort(403);
    }

    /**
     * Cerrar the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generar(Request $request, $id)
    {
        if ($request->ajax()) {
            $precotizacion = PreCotizacion1::getPreCotizacion($id);
            if(!$precotizacion instanceof PreCotizacion1){
                return response()->json(['success' => false, 'errors' => 'No es posible recuperar la pre-cotización, por favor verifique la información o consulte al adminitrador.']);
            }

            // Recuperar empresa
            $empresa = Empresa::getEmpresa();
            DB::beginTransaction();
            try{
                // Recuperar numero precotizacion
                $numero = Cotizacion1::where('cotizacion1_ano', date('Y'))->max('cotizacion1_numero');
                $numero = !is_integer(intval($numero)) ? 1 : ($numero + 1);

                // Cotizacion
                $cotizacion = new Cotizacion1;
                $cotizacion->cotizacion1_cliente = $precotizacion->precotizacion1_cliente;
                $cotizacion->cotizacion1_referencia = $precotizacion->precotizacion1_referencia;
                $cotizacion->cotizacion1_numero = $numero;
                $cotizacion->cotizacion1_ano = $precotizacion->precotizacion1_ano;
                $cotizacion->cotizacion1_fecha_inicio = date('Y-m-d');
                $cotizacion->cotizacion1_contacto = $precotizacion->precotizacion1_contacto;
                $cotizacion->cotizacion1_iva = $empresa->empresa_iva;
                $cotizacion->cotizacion1_formapago = $precotizacion->tercero_formapago;
                $cotizacion->cotizacion1_precotizacion = $precotizacion->id;
                $cotizacion->cotizacion1_anulada = false;
                $cotizacion->cotizacion1_abierta = true;
                $cotizacion->cotizacion1_suministran = $precotizacion->precotizacion1_suministran;
                $cotizacion->cotizacion1_observaciones = $precotizacion->precotizacion1_observaciones;
                $cotizacion->cotizacion1_usuario_elaboro = Auth::user()->id;
                $cotizacion->cotizacion1_fecha_elaboro = date('Y-m-d H:m:s');
                $cotizacion->save();

                // Recuperar Productop de cotizacion para generar precotizacion
                $productos = PreCotizacion2::where('precotizacion2_precotizacion1', $precotizacion->id)->orderBy('id', 'asc')->get();
                foreach ($productos as $precotizacion2) {
                    $cotizacion2 = new Cotizacion2;
                    $cotizacion2->cotizacion2_cotizacion = $cotizacion->id;
                    $cotizacion2->cotizacion2_productop = $precotizacion2->precotizacion2_productop;
                    $cotizacion2->cotizacion2_observaciones = $precotizacion2->precotizacion2_observaciones;
                    $cotizacion2->cotizacion2_cantidad = $precotizacion2->precotizacion2_cantidad;
                    $cotizacion2->cotizacion2_referencia = $precotizacion2->precotizacion2_referencia;
                    $cotizacion2->cotizacion2_tiro = $precotizacion2->precotizacion2_tiro;
                    $cotizacion2->cotizacion2_retiro = $precotizacion2->precotizacion2_retiro;
                    $cotizacion2->cotizacion2_yellow = $precotizacion2->precotizacion2_yellow;
                    $cotizacion2->cotizacion2_magenta = $precotizacion2->precotizacion2_magenta;
                    $cotizacion2->cotizacion2_cyan = $precotizacion2->precotizacion2_cyan;
                    $cotizacion2->cotizacion2_key = $precotizacion2->precotizacion2_key;
                    $cotizacion2->cotizacion2_color1 = $precotizacion2->precotizacion2_color1;
                    $cotizacion2->cotizacion2_color2 = $precotizacion2->precotizacion2_color2;
                    $cotizacion2->cotizacion2_nota_tiro = $precotizacion2->precotizacion2_nota_tiro;
                    $cotizacion2->cotizacion2_yellow2 = $precotizacion2->precotizacion2_yellow2;
                    $cotizacion2->cotizacion2_magenta2 = $precotizacion2->precotizacion2_magenta2;
                    $cotizacion2->cotizacion2_cyan2 = $precotizacion2->precotizacion2_cyan2;
                    $cotizacion2->cotizacion2_key2 = $precotizacion2->precotizacion2_key2;
                    $cotizacion2->cotizacion2_color12 = $precotizacion2->precotizacion2_color12;
                    $cotizacion2->cotizacion2_color22 = $precotizacion2->precotizacion2_color22;
                    $cotizacion2->cotizacion2_nota_retiro = $precotizacion2->precotizacion2_nota_retiro;
                    $cotizacion2->cotizacion2_ancho = $precotizacion2->precotizacion2_ancho;
                    $cotizacion2->cotizacion2_alto = $precotizacion2->precotizacion2_alto;
                    $cotizacion2->cotizacion2_c_ancho = $precotizacion2->precotizacion2_c_ancho;
                    $cotizacion2->cotizacion2_c_alto = $precotizacion2->precotizacion2_c_alto;
                    $cotizacion2->cotizacion2_3d_ancho = $precotizacion2->precotizacion2_3d_ancho;
                    $cotizacion2->cotizacion2_3d_alto = $precotizacion2->precotizacion2_3d_alto;
                    $cotizacion2->cotizacion2_3d_profundidad = $precotizacion2->precotizacion2_3d_profundidad;
                    $cotizacion2->cotizacion2_usuario_elaboro = $cotizacion->cotizacion1_usuario_elaboro;
                    $cotizacion2->cotizacion2_fecha_elaboro = $cotizacion->cotizacion1_fecha_elaboro;
                    $cotizacion2->save();

                    // Recuperar Materiales de pre-cotizacion para generar cotizacion
                    $materiales = PreCotizacion3::where('precotizacion3_precotizacion2', $precotizacion2->id)->get();
                    $totalmaterial = $totalareasp = 0;
                    foreach ($materiales as $precotizacion3) {
                         $cotizacion4 = new Cotizacion4;
                         $cotizacion4->cotizacion4_materialp = $precotizacion3->precotizacion3_materialp;
                         $cotizacion4->cotizacion4_cotizacion2 = $cotizacion2->id;
                         $cotizacion4->cotizacion4_producto = $precotizacion3->precotizacion3_producto;
                         $cotizacion4->cotizacion4_proveedor = $precotizacion3->precotizacion3_proveedor;
                         $cotizacion4->cotizacion4_cantidad = $precotizacion3->precotizacion3_cantidad;
                         $cotizacion4->cotizacion4_medidas = $precotizacion3->precotizacion3_medidas;
                         $cotizacion4->cotizacion4_valor_unitario = $precotizacion3->precotizacion3_valor_unitario;
                         $cotizacion4->cotizacion4_valor_total = $precotizacion3->precotizacion3_valor_total;
                         $cotizacion4->cotizacion4_fh_elaboro = date('Y-m-d H:m:s');
                         $cotizacion4->cotizacion4_usuario_elaboro = Auth::user()->id;
                         $cotizacion4->save();

                         $totalmaterial += $precotizacion3->precotizacion3_valor_total / $cotizacion2->cotizacion2_cantidad;
                    }

                    // Recuperar Areasp de cotizacion para generar precotizacion
                    $areasp = PreCotizacion6::select('koi_precotizacion6.*', DB::raw("((SUBSTRING_INDEX(precotizacion6_tiempo, ':', -1) / 60) + SUBSTRING_INDEX(precotizacion6_tiempo, ':', 1)) * precotizacion6_valor as total_areap"))->where('precotizacion6_precotizacion2', $precotizacion2->id)->get();
                    foreach ($areasp as $precotizacion6) {
                         $cotizacion6 = new Cotizacion6;
                         $cotizacion6->cotizacion6_cotizacion2 = $cotizacion2->id;
                         $cotizacion6->cotizacion6_areap = $precotizacion6->precotizacion6_areap;
                         $cotizacion6->cotizacion6_nombre = $precotizacion6->precotizacion6_nombre;
                         $cotizacion6->cotizacion6_tiempo = $precotizacion6->precotizacion6_tiempo;
                         $cotizacion6->cotizacion6_valor = $precotizacion6->precotizacion6_valor;
                         $cotizacion6->save();

                         // Convertir minutos a horas y sumar horas
                         $totalareasp += round($precotizacion6->total_areap) / $cotizacion2->cotizacion2_cantidad;
                    }

                    // Recuperar Materiales de pre-cotizacion para generar cotizacion
                    $impresiones = PreCotizacion5::where('precotizacion5_precotizacion2', $precotizacion2->id)->get();
                    foreach ($impresiones as $precotizacion5) {
                         $cotizacion7 = new Cotizacion7;
                         $cotizacion7->cotizacion7_cotizacion2 = $cotizacion2->id;
                         $cotizacion7->cotizacion7_texto = $precotizacion5->precotizacion5_texto;
                         $cotizacion7->cotizacion7_ancho = $precotizacion5->precotizacion5_ancho;
                         $cotizacion7->cotizacion7_alto = $precotizacion5->precotizacion5_alto;
                         $cotizacion7->save();
                    }

                    // Recuperar Imagenes de pre-cotizacion para generar cotizacion
                    $imagenes = PreCotizacion4::where('precotizacion4_precotizacion2', $precotizacion2->id)->get();
                    foreach ($imagenes as $precotizacion4) {
                         $cotizacion8 = new Cotizacion8;
                         $cotizacion8->cotizacion8_cotizacion2 = $cotizacion2->id;
                         $cotizacion8->cotizacion8_archivo = $precotizacion4->precotizacion4_archivo;
                         $cotizacion8->cotizacion8_fh_elaboro = date('Y-m-d H:m:s');
                         $cotizacion8->cotizacion8_usuario_elaboro = Auth::user()->id;
                         $cotizacion8->save();

                         // Recuperar imagen y copiar
                         if( Storage::has("pre-cotizaciones/precotizacion_{$precotizacion2->precotizacion2_precotizacion1}/producto_{$precotizacion4->precotizacion4_precotizacion2}/{$precotizacion4->precotizacion4_archivo}") ) {

                             $oldfile = "pre-cotizaciones/precotizacion_{$precotizacion2->precotizacion2_precotizacion1}/producto_{$precotizacion4->precotizacion4_precotizacion2}/{$precotizacion4->precotizacion4_archivo}";
                             $newfile = "cotizaciones/cotizacion_{$cotizacion2->cotizacion2_cotizacion}/producto_{$cotizacion8->cotizacion8_cotizacion2}/{$cotizacion8->cotizacion8_archivo}";

                             // Copy file storege laravel
                             Storage::copy($oldfile, $newfile);
                         }
                    }

                    // Recuperar Acabados de cotizacion para generar cotizacion
                    $acabados = PreCotizacion7::where('precotizacion7_precotizacion2', $precotizacion2->id)->get();
                    foreach ($acabados as $precotizacion7) {
                         $cotizacion5 = new Cotizacion5;
                         $cotizacion5->cotizacion5_acabadop = $precotizacion7->precotizacion7_acabadop;
                         $cotizacion5->cotizacion5_cotizacion2 = $cotizacion2->id;
                         $cotizacion5->save();
                    }

                    // Recuperar Maquinas de cotizacion para generar cotizacion
                    $maquinas = PreCotizacion8::where('precotizacion8_precotizacion2', $precotizacion2->id)->get();
                    foreach ($maquinas as $precotizacion8) {
                         $cotizacion3 = new Cotizacion3;
                         $cotizacion3->cotizacion3_maquinap = $precotizacion8->precotizacion8_maquinap;
                         $cotizacion3->cotizacion3_cotizacion2 = $cotizacion2->id;
                         $cotizacion3->save();
                    }

                    // Actualizar precio en cotizacion2;
                    $cotizacion2->cotizacion2_total_valor_unitario = round($totalmaterial) + round($totalareasp);
                    $cotizacion2->save();
                }

                $precotizacion->precotizacion1_abierta = false;
                $precotizacion->precotizacion1_culminada = false;
                $precotizacion->precotizacion1_fh_culminada = NULL;
                $precotizacion->precotizacion1_usuario_culminada = NULL;
                $precotizacion->save();

                // Commit Transaction
                DB::commit();
                return response()->json(['success' => true, 'msg' => 'Se genero con exito la cotizacion', 'cotizacion_id' => $cotizacion->id]);
            }catch(\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
                return response()->json(['success' => false, 'errors' => trans('app.exception')]);
            }
        }
        abort(403);
    }

    /**
     * Clonar the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function clonar(Request $request, $id)
    {
        if ($request->ajax()) {
            $precotizacion = PreCotizacion1::findOrFail($id);
            if(!$precotizacion instanceof PreCotizacion1){
                return response()->json(['success' => false, 'errors' => 'No es posible recuperar la pre-cotización, por favor verifique la información o consulte al adminitrador.']);
            }

            DB::beginTransaction();
            try {
                // Recuperar numero precotizacion
                $numero = DB::table('koi_precotizacion1')->where('precotizacion1_ano', date('Y'))->max('precotizacion1_numero');
                $numero = !is_integer(intval($numero)) ? 1 : ($numero + 1);

                // Precotizacion
                $newprecotizacion = $precotizacion->replicate();
                $newprecotizacion->precotizacion1_fecha = date('Y-m-d');
                $newprecotizacion->precotizacion1_abierta = true;
                $newprecotizacion->precotizacion1_culminada = false;
                $newprecotizacion->precotizacion1_fh_culminada = NULL;
                $newprecotizacion->precotizacion1_usuario_culminada = NULL;
                $newprecotizacion->precotizacion1_ano = date('Y');
                $newprecotizacion->precotizacion1_numero = $numero;
                $newprecotizacion->precotizacion1_usuario_elaboro = Auth::user()->id;
                $newprecotizacion->precotizacion1_fh_elaboro = date('Y-m-d H:m:s');
                $newprecotizacion->save();

                // PreCotizacion2
                $productos = PreCotizacion2::where('precotizacion2_precotizacion1', $precotizacion->id)->orderBy('id', 'asc')->get();
                foreach ($productos as $precotizacion2) {
                    $newprecotizacion2 = $precotizacion2->replicate();
                    $newprecotizacion2->precotizacion2_precotizacion1 = $newprecotizacion->id;
                    $newprecotizacion2->save();

                    // Proveedores
                    $proveedores = PreCotizacion3::where('precotizacion3_precotizacion2', $precotizacion2->id)->get();
                    foreach ($proveedores as $precotizacion3) {
                         $newprecotizacion3 = $precotizacion3->replicate();
                         $newprecotizacion3->precotizacion3_precotizacion2 = $newprecotizacion2->id;
                         $newprecotizacion3->precotizacion3_fh_elaboro = date('Y-m-d H:m:s');
                         $newprecotizacion3->precotizacion3_usuario_elaboro = Auth::user()->id;
                         $newprecotizacion3->save();
                    }

                    // Imagenes
                    $imagenes = PreCotizacion4::where('precotizacion4_precotizacion2', $precotizacion2->id)->get();
                    foreach ($imagenes as $precotizacion4) {
                        $newprecotizacion4 = $precotizacion4->replicate();
                        $newprecotizacion4->precotizacion4_precotizacion2 = $newprecotizacion2->id;
                        $newprecotizacion4->precotizacion4_usuario_elaboro = Auth::user()->id;
                        $newprecotizacion4->precotizacion4_fh_elaboro = date('Y-m-d H:m:s');
                        $newprecotizacion4->save();

                        // Recuperar imagen y copiar
                        if( Storage::has("pre-cotizaciones/precotizacion_{$precotizacion2->precotizacion2_precotizacion1}/producto_{$precotizacion4->precotizacion4_precotizacion2}/{$precotizacion4->precotizacion4_archivo}") ) {

                            $oldfile = "pre-cotizaciones/precotizacion_{$precotizacion2->precotizacion2_precotizacion1}/producto_{$precotizacion4->precotizacion4_precotizacion2}/{$precotizacion4->precotizacion4_archivo}";
                            $newfile = "pre-cotizaciones/precotizacion_{$newprecotizacion2->precotizacion2_precotizacion1}/producto_{$newprecotizacion4->precotizacion4_precotizacion2}/{$newprecotizacion4->precotizacion4_archivo}";

                            // Copy file storege laravel
                            Storage::copy($oldfile, $newfile);
                        }
                    }

                    // Impresiones
                    $impresiones = PreCotizacion5::where('precotizacion5_precotizacion2', $precotizacion2->id)->get();
                    foreach ($impresiones as $precotizacion5) {
                         $newprecotizacion5 = $precotizacion5->replicate();
                         $newprecotizacion5->precotizacion5_precotizacion2 = $newprecotizacion2->id;
                         $newprecotizacion5->save();
                    }

                    // Areasp
                    $areasp = PreCotizacion6::where('precotizacion6_precotizacion2', $precotizacion2->id)->get();
                    foreach ($areasp as $precotizacion6) {
                         $newprecotizacion6 = $precotizacion6->replicate();
                         $newprecotizacion6->precotizacion6_precotizacion2 = $newprecotizacion2->id;
                         $newprecotizacion6->save();
                    }

                    // Acabados
                    $acabados = PreCotizacion7::where('precotizacion7_precotizacion2', $precotizacion2->id)->get();
                    foreach ($acabados as $precotizacion7) {
                         $newprecotizacion7 = $precotizacion7->replicate();
                         $newprecotizacion7->precotizacion7_precotizacion2 = $newprecotizacion2->id;
                         $newprecotizacion7->save();
                    }

                    // Maquinas
                    $maquinas = PreCotizacion8::where('precotizacion8_precotizacion2', $precotizacion2->id)->get();
                    foreach ($maquinas as $precotizacion8) {
                         $newprecotizacion8 = $precotizacion8->replicate();
                         $newprecotizacion8->precotizacion8_precotizacion2 = $newprecotizacion2->id;
                         $newprecotizacion8->save();
                    }
                }
                // Commit Transaction
                DB::commit();
                return response()->json(['success' => true, 'id' => $newprecotizacion->id, 'msg' => 'Pre-cotización clonada con exito.']);
            }catch(\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
                return response()->json(['success' => false, 'errors' => trans('app.exception')]);
            }
        }
        abort(403);
    }
}

<?php

namespace App\Http\Controllers\Production;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Production\PreCotizacion1, App\Models\Production\PreCotizacion2, App\Models\Production\PreCotizacion3, App\Models\Production\PreCotizacion4, App\Models\Production\PreCotizacion6, App\Models\Production\PreCotizacion7, App\Models\Production\PreCotizacion8, App\Models\Production\PreCotizacion9, App\Models\Production\PreCotizacion10, App\Models\Production\Productop, App\Models\Production\Materialp, App\Models\Production\Areap;
use App\Models\Base\Tercero;
use App\Models\Inventory\Producto;
use DB, Log, Datatables, Storage;

class PreCotizacion2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = [];
            if ($request->has('precotizacion')) {
                $data = PreCotizacion2::getPreCotizaciones2($request->precotizacion);
            }
            return response()->json($data);
        }
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Recuperar precotizacion
        $precotizacion = $request->has('precotizacion') ? PreCotizacion1::getPreCotizacion($request->precotizacion) : null;
        if (!$precotizacion instanceof PreCotizacion1) {
            abort(404);
        }

        // Recuperar producto
        $producto = $request->has('productop') ? Productop::getProduct($request->productop) : null;
        if (!$producto instanceof Productop) {
            abort(404);
        }

        // Lazy Eager Loading
        $producto->load('tips');

        if (!$precotizacion->precotizacion1_abierta) {
            return redirect()->route('precotizaciones.show', compact('precotizacion'));
        }

        return view('production.precotizaciones.productos.create', compact('precotizacion', 'producto'));
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
            $data['materialesp'] = json_decode($data['materialesp']);
            $data['areasp'] = json_decode($data['areasp']);
            $data['empaques'] = json_decode($data['empaques']);
            $data['transportes'] = json_decode($data['transportes']);

            $precotizacion2 = new PreCotizacion2;
            if ($precotizacion2->isValid($data)) {
                DB::beginTransaction();
                try {
                    // Validar producto
                    $producto = Productop::find($request->precotizacion2_productop);
                    if (!$producto instanceof Productop) {
                        DB::rollback();
                        return response()->json(['success' => false, 'errors' => 'No es posible recuperar producto, por favor verifique la información o consulte al administrador.']);
                    }

                    // Validar cotizacion
                    $precotizacion = PreCotizacion1::find($request->precotizacion);
                    if (!$precotizacion instanceof PreCotizacion1) {
                        DB::rollback();
                        return response()->json(['success' => false, 'errors' => 'No es posible recuperar pre-cotizacion, por favor verifique la información o consulte al administrador.']);
                    }

                    // Cotizacion2
                    $precotizacion2->fill($data);
                    $precotizacion2->fillBoolean($data);
                    $precotizacion2->precotizacion2_precotizacion1 = $precotizacion->id;
                    $precotizacion2->precotizacion2_productop = $producto->id;
                    $precotizacion2->save();

                    // Acabados
                    $acabados = PreCotizacion7::getPreCotizaciones7($precotizacion2->precotizacion2_productop, $precotizacion2->id);
                    foreach ($acabados as $acabado) {
                        if ($request->has("precotizacion7_acabadop_$acabado->id")) {
                            $precotizacion7 = new PreCotizacion7;
                            $precotizacion7->precotizacion7_precotizacion2 = $precotizacion2->id;
                            $precotizacion7->precotizacion7_acabadop = $acabado->id;
                            $precotizacion7->save();
                        }
                    }

                    // Maquinas
                    $maquinas = PreCotizacion8::getPreCotizaciones8($precotizacion2->precotizacion2_productop, $precotizacion2->id);
                    foreach ($maquinas as $maquina) {
                        if($request->has("precotizacion8_maquinap_$maquina->id")) {
                            $precotizacion8 = new PreCotizacion8;
                            $precotizacion8->precotizacion8_precotizacion2 = $precotizacion2->id;
                            $precotizacion8->precotizacion8_maquinap = $maquina->id;
                            $precotizacion8->save();
                        }
                    }

                    // Reuperar imagenes y almacenar en storage/app/precotizacines
                    $files = [];
                    $images = isset($data['imagenes']) ? $data['imagenes'] : [];
                    foreach ($images as $image) {
                        // Recuperar nombre de archivo
                        $name = str_random(4)."_{$image->getClientOriginalName()}";

                        // Insertar imagen
                        $imagen = new PreCotizacion4;
                        $imagen->precotizacion4_archivo = $name;
                        $imagen->precotizacion4_precotizacion2 = $precotizacion2->id;
                        $imagen->precotizacion4_fh_elaboro = date('Y-m-d H:i:s');
                        $imagen->precotizacion4_usuario_elaboro = auth()->user()->id;
                        $imagen->save();

                        $object = new \stdClass();
                        $object->route = "pre-cotizaciones/precotizacion_$precotizacion2->precotizacion2_precotizacion1/producto_$precotizacion2->id/$name";
                        $object->file = file_get_contents($image->getRealPath());

                        $files[] = $object;
                    }

                    // Materialesp
                    $materiales = isset($data['materialesp']) ? $data['materialesp'] : null;
                    foreach ($materiales as $material) {
                        $materialp = Materialp::find($material->precotizacion3_materialp);
                        if (!$materialp instanceof Materialp) {
                            DB::rollback();
                            return response()->json(['success' => false, 'errors' => 'No es posible recuperar el material de producción, por favor verifique la información o consulte al administrador.']);
                        }

                        $insumo = Producto::find($material->precotizacion3_producto);
                        if (!$insumo instanceof Producto) {
                            DB::rollback();
                            return response()->json(['success' => false, 'errors' => 'No es posible recuperar el insumo del material, por favor verifique la información o consulte al administrador.']);
                        }

                        // Guardar individual porque sale error por ser objeto decodificado
                        $precotizacion3 = new PreCotizacion3;
                        $precotizacion3->precotizacion3_medidas = $material->precotizacion3_medidas;
                        $precotizacion3->precotizacion3_cantidad = $material->precotizacion3_cantidad;
                        $precotizacion3->precotizacion3_valor_unitario = $material->precotizacion3_valor_unitario;
                        $precotizacion3->precotizacion3_valor_total = $material->precotizacion3_valor_total;
                        $precotizacion3->precotizacion3_producto = $insumo->id;
                        $precotizacion3->precotizacion3_precotizacion2 = $precotizacion2->id;
                        $precotizacion3->precotizacion3_materialp = $materialp->id;
                        $precotizacion3->precotizacion3_fh_elaboro = date('Y-m-d H:i:s');
                        $precotizacion3->precotizacion3_usuario_elaboro = auth()->user()->id;
                        $precotizacion3->save();
                    }

                    // Areap
                    $areasp = isset($data['areasp']) ? $data['areasp'] : null;
                    foreach ($areasp as $areap) {
                        // Recuperar tiempo
                        $precotizacion6 = new PreCotizacion6;
                        $precotizacion6->precotizacion6_valor = $areap->precotizacion6_valor;
                        if (!empty($areap->precotizacion6_areap)) {
                            $precotizacion6->precotizacion6_areap = $areap->precotizacion6_areap;
                        } else {
                            $precotizacion6->precotizacion6_nombre = $areap->precotizacion6_nombre;
                        }
                        $precotizacion6->precotizacion6_tiempo = "{$areap->precotizacion6_horas}:{$areap->precotizacion6_minutos}";
                        $precotizacion6->precotizacion6_precotizacion2 = $precotizacion2->id;
                        $precotizacion6->save();
                    }

                    // Empaques
                    $empaques = isset($data['empaques']) ? $data['empaques'] : null;
                    foreach ($empaques as $empaque) {
                        $materialp = Materialp::where('materialp_empaque', true)->find($empaque->precotizacion9_materialp);
                        if (!$materialp instanceof Materialp) {
                            DB::rollback();
                            return response()->json(['success' => false, 'errors' => 'No es posible recuperar el empaque de producción, por favor verifique la información o consulte al administrador.']);
                        }


                        $producto = Producto::where('producto_empaque', true)->find($empaque->precotizacion9_producto);
                        if (!$producto instanceof Producto) {
                            DB::rollback();
                            return response()->json(['success' => false, 'errors' => 'No es posible recuperar el insumo del empaque, por favor verifique la información o consulte al administrador.']);
                        }

                        // Guardar individual porque sale error por ser objeto decodificado
                        $precotizacion9 = new PreCotizacion9;
                        $precotizacion9->precotizacion9_medidas = $empaque->precotizacion9_medidas;
                        $precotizacion9->precotizacion9_cantidad = $empaque->precotizacion9_cantidad;
                        $precotizacion9->precotizacion9_valor_unitario = $empaque->precotizacion9_valor_unitario;
                        $precotizacion9->precotizacion9_valor_total = $empaque->precotizacion9_valor_total;
                        $precotizacion9->precotizacion9_materialp = $materialp->id;
                        $precotizacion9->precotizacion9_producto = $producto->id;
                        $precotizacion9->precotizacion9_precotizacion2 = $precotizacion2->id;
                        $precotizacion9->precotizacion9_fh_elaboro = date('Y-m-d H:i:s');
                        $precotizacion9->precotizacion9_usuario_elaboro = auth()->user()->id;
                        $precotizacion9->save();
                    }

                    // Transportes
                    $transportes = isset($data['transportes']) ? $data['transportes'] : null;
                    foreach ($transportes as $transporte) {
                        $materialp = Materialp::where('materialp_transporte', true)->find($transporte->precotizacion10_materialp);
                        if (!$materialp instanceof Materialp) {
                            DB::rollback();
                            return response()->json(['success' => false, 'errors' => 'No es posible recuperar el transporte de producción, por favor verifique la información o consulte al administrador.']);
                        }


                        $producto = Producto::where('producto_transporte', true)->find($transporte->precotizacion10_producto);
                        if (!$producto instanceof Producto) {
                            DB::rollback();
                            return response()->json(['success' => false, 'errors' => 'No es posible recuperar el insumo del transporte, por favor verifique la información o consulte al administrador.']);
                        }

                        // Guardar individual porque sale error por ser objeto decodificado
                        $precotizacion10 = new PreCotizacion10;
                        $precotizacion10->precotizacion10_medidas = $transporte->precotizacion10_medidas;
                        $precotizacion10->precotizacion10_cantidad = $transporte->precotizacion10_cantidad;
                        $precotizacion10->precotizacion10_valor_unitario = $transporte->precotizacion10_valor_unitario;
                        $precotizacion10->precotizacion10_valor_total = $transporte->precotizacion10_valor_total;
                        $precotizacion10->precotizacion10_materialp = $materialp->id;
                        $precotizacion10->precotizacion10_producto = $producto->id;
                        $precotizacion10->precotizacion10_precotizacion2 = $precotizacion2->id;
                        $precotizacion10->precotizacion10_fh_elaboro = date('Y-m-d H:i:s');
                        $precotizacion10->precotizacion10_usuario_elaboro = auth()->user()->id;
                        $precotizacion10->save();
                    }

                    // Guardar imagenes si todo sale bien
                    if (count($files)) {
                        foreach ($files as $file) {
                            Storage::put($file->route, $file->file);
                        }
                    }

                    // Commit Transaction
                    DB::commit();
                    return response()->json(['success' => true, 'id' => $precotizacion2->id, 'id_precotizacion' => $precotizacion->id]);
                } catch(\Exception $e) {
                    DB::rollback();
                    Log::error($e->getMessage());
                    return response()->json(['success' => false, 'errors' => trans('app.exception')]);
                }
            }
            return response()->json(['success' => false, 'errors' => $precotizacion2->errors]);
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
        // Recuperar precotizacion2
        $precotizacion2 = PreCotizacion2::getPreCotizacion2($id);
        if (!$precotizacion2 instanceof PreCotizacion2) {
            abort(404);
        }

        if ($request->ajax()) {
            return response()->json($precotizacion2);
        }

        // Recuperar precotizacion
        $precotizacion = PreCotizacion1::getPreCotizacion($precotizacion2->precotizacion2_precotizacion1);
        if (!$precotizacion instanceof PreCotizacion1) {
            abort(404);
        }

        // Recuperar producto
        $producto = Productop::getProduct($precotizacion2->precotizacion2_productop);
        if (!$producto instanceof Productop) {
            abort(404);
        }

        // Lazy Eager Loading
        $producto->load('tips');

        // Validar precotizacion
        if ($precotizacion->precotizacion1_abierta && auth()->user()->ability('admin', 'editar', ['module' => 'precotizaciones'])) {
            return redirect()->route('precotizaciones.productos.edit', ['productos' => $precotizacion2->id]);
        }
        return view('production.precotizaciones.productos.show', compact('precotizacion', 'producto', 'precotizacion2'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        // Recuperar precotizacion2
        $precotizacion2 = PreCotizacion2::findOrFail($id);

        // Recuperar cotizacion
        $precotizacion = PreCotizacion1::getPreCotizacion($precotizacion2->precotizacion2_precotizacion1);
        if (!$precotizacion instanceof PreCotizacion1) {
            abort(404);
        }

        // Recuperar producto
        $producto = Productop::getProduct($precotizacion2->precotizacion2_productop);
        if (!$producto instanceof Productop) {
            abort(404);
        }

        // Lazy Eager Loading
        $producto->load('tips');

        // Validar cotizacion
        if (!$precotizacion->precotizacion1_abierta) {
            return redirect()->route('precotizaciones.productos.show', ['productos' => $precotizacion2->id]);
        }
        return view('production.precotizaciones.productos.create', compact('precotizacion', 'producto', 'precotizacion2'));
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

            // Recuperar precotizacion2
            $precotizacion2 = PreCotizacion2::findOrFail($id);

            // Recuperar cotizacion
            $precotizacion = PreCotizacion1::findOrFail($precotizacion2->precotizacion2_precotizacion1);
            if ($precotizacion->precotizacion1_abierta) {
                if ($precotizacion2->isValid($data)) {
                    DB::beginTransaction();
                    try {
                        // Cotizacion2
                        $precotizacion2->fill($data);
                        $precotizacion2->fillBoolean($data);
                        $precotizacion2->save();

                        // Acabados
                        $acabados = PreCotizacion7::getPreCotizaciones7($precotizacion2->precotizacion2_productop, $precotizacion2->id);
                        foreach ($acabados as $acabado) {
                            $precotizacion7 = PreCotizacion7::where('precotizacion7_precotizacion2', $precotizacion2->id)->where('precotizacion7_acabadop', $acabado->id)->first();
                            if ($request->has("precotizacion7_acabadop_$acabado->id")){
                                if (!$precotizacion7 instanceof PreCotizacion7) {
                                    $precotizacion7 = new PreCotizacion7;
                                    $precotizacion7->precotizacion7_precotizacion2 = $precotizacion2->id;
                                    $precotizacion7->precotizacion7_acabadop = $acabado->id;
                                    $precotizacion7->save();
                                }
                            } else {
                                if ($precotizacion7 instanceof PreCotizacion7) {
                                    $precotizacion7->delete();
                                }
                            }
                        }

                        // Maquinas
                        $maquinas = PreCotizacion8::getPreCotizaciones8($precotizacion2->precotizacion2_productop, $precotizacion2->id);
                        foreach ($maquinas as $maquina) {
                            $precotizacion8 = PreCotizacion8::where('precotizacion8_precotizacion2', $precotizacion2->id)->where('precotizacion8_maquinap', $maquina->id)->first();
                            if ($request->has("precotizacion8_maquinap_$maquina->id")) {
                                if (!$precotizacion8 instanceof PreCotizacion8) {
                                    $precotizacion8 = new PreCotizacion8;
                                    $precotizacion8->precotizacion8_precotizacion2 = $precotizacion2->id;
                                    $precotizacion8->precotizacion8_maquinap = $maquina->id;
                                    $precotizacion8->save();
                                }
                            } else {
                                if ($precotizacion8 instanceof PreCotizacion8) {
                                    $precotizacion8->delete();
                                }
                            }
                        }

                        // Materiales
                        $keys = [];
                        $materiales = isset($data['materialesp']) ? $data['materialesp'] : null;
                        foreach ($materiales as $material) {
                            $precotizacion3 = PreCotizacion3::find( is_numeric($material['id']) ? $material['id'] : null);
                            if (!$precotizacion3 instanceof PreCotizacion3) {
                                $materialp = Materialp::find($material['precotizacion3_materialp']);
                                if (!$materialp instanceof Materialp) {
                                    DB::rollback();
                                    return response()->json(['success' => false, 'errors' => 'No es posible recuperar el material de producción, por favor verifique la información o consulte al administrador.']);
                                }

                                $insumo = Producto::find($material['precotizacion3_producto']);
                                if (!$insumo instanceof Producto) {
                                    DB::rollback();
                                    return response()->json(['success' => false, 'errors' => 'No es posible recuperar el insumo del material, por favor verifique la información o consulte al administrador.']);
                                }

                                $precotizacion3 = new PreCotizacion3;
                                $precotizacion3->fill($material);
                                $precotizacion3->precotizacion3_producto = $insumo->id;
                                $precotizacion3->precotizacion3_precotizacion2 = $precotizacion2->id;
                                $precotizacion3->precotizacion3_materialp = $materialp->id;
                                $precotizacion3->precotizacion3_fh_elaboro = date('Y-m-d H:i:s');
                                $precotizacion3->precotizacion3_usuario_elaboro = auth()->user()->id;
                                $precotizacion3->save();

                            } else {
                                $precotizacion3->fill($material);
                                $precotizacion3->save();

                            }

                            // asociar id a un array para validar
                            $keys[] = $precotizacion3->id;
                        }

                        // Remover registros que no existan
                        $deletemateriales = PreCotizacion3::whereNotIn('id', $keys)->where('precotizacion3_precotizacion2', $precotizacion2->id)->delete();

                        // Areasp
                        $keys = [];
                        $areasp = isset($data['areasp']) ? $data['areasp'] : null;
                        foreach ($areasp as $areap) {
                            $precotizacion6 = PreCotizacion6::find( is_numeric($areap['id']) ? $areap['id'] : null);
                            if (!$precotizacion6 instanceof PreCotizacion6) {
                                $precotizacion6 = new PreCotizacion6;
                                $precotizacion6->fill($areap);
                                if (!empty($areap['precotizacion6_areap'])) {
                                    $precotizacion6->precotizacion6_areap = $areap['precotizacion6_areap'];
                                } else {
                                    $precotizacion6->precotizacion6_nombre = $areap['precotizacion6_nombre'];
                                }
                                $precotizacion6->precotizacion6_tiempo = "{$areap['precotizacion6_horas']}:{$areap['precotizacion6_minutos']}";
                                $precotizacion6->precotizacion6_precotizacion2 = $precotizacion2->id;
                                $precotizacion6->save();
                            } else {
                                $precotizacion6->precotizacion6_tiempo = "{$areap['precotizacion6_horas']}:{$areap['precotizacion6_minutos']}";
                                $precotizacion6->save();
                            }

                            $keys[] = $precotizacion6->id;
                        }

                        // Remover registros que no existan
                        $deleteareasp = PreCotizacion6::whereNotIn('id', $keys)->where('precotizacion6_precotizacion2', $precotizacion2->id)->delete();

                        // Empaques
                        $keys = [];
                        $empaques = isset($data['empaques']) ? $data['empaques'] : null;
                        foreach ($empaques as $empaque) {
                            $precotizacion9 = PreCotizacion9::find( is_numeric($empaque['id']) ? $empaque['id'] : null);
                            if (!$precotizacion9 instanceof PreCotizacion9) {
                                $materialp = Materialp::find($empaque['precotizacion9_materialp']);
                                if (!$materialp instanceof Materialp) {
                                    DB::rollback();
                                    return response()->json(['success' => false, 'errors' => 'No es posible recuperar el empaque de producción, por favor verifique la información o consulte al administrador.']);
                                }

                                $producto = Producto::find($empaque['precotizacion9_producto']);
                                if (!$producto instanceof Producto) {
                                    DB::rollback();
                                    return response()->json(['success' => false, 'errors' => 'No es posible recuperar el insumo del empaque, por favor verifique la información o consulte al administrador.']);
                                }

                                $precotizacion9 = new PreCotizacion9;
                                $precotizacion9->fill($empaque);
                                $precotizacion9->precotizacion9_producto = $producto->id;
                                $precotizacion9->precotizacion9_materialp = $materialp->id;
                                $precotizacion9->precotizacion9_precotizacion2 = $precotizacion2->id;
                                $precotizacion9->precotizacion9_fh_elaboro = date('Y-m-d H:i:s');
                                $precotizacion9->precotizacion9_usuario_elaboro = auth()->user()->id;
                                $precotizacion9->save();

                            } else {
                                $precotizacion9->fill($empaque);
                                $precotizacion9->save();

                            }

                            // asociar id a un array para validar
                            $keys[] = $precotizacion9->id;
                        }

                        // Remover registros que no existan
                        $deleteempaques = PreCotizacion9::whereNotIn('id', $keys)->where('precotizacion9_precotizacion2', $precotizacion2->id)->delete();

                        // Transportes
                        $keys = [];
                        $transportes = isset($data['transportes']) ? $data['transportes'] : null;
                        foreach ($transportes as $transporte) {
                            $precotizacion10 = PreCotizacion10::find( is_numeric($transporte['id']) ? $transporte['id'] : null);
                            if (!$precotizacion10 instanceof PreCotizacion10) {
                                $materialp = Materialp::find($transporte['precotizacion10_materialp']);
                                if (!$materialp instanceof Materialp) {
                                    DB::rollback();
                                    return response()->json(['success' => false, 'errors' => 'No es posible recuperar el empaque de producción, por favor verifique la información o consulte al administrador.']);
                                }

                                $producto = Producto::find($transporte['precotizacion10_producto']);
                                if (!$producto instanceof Producto) {
                                    DB::rollback();
                                    return response()->json(['success' => false, 'errors' => 'No es posible recuperar el insumo del transporte, por favor verifique la información o consulte al administrador.']);
                                }

                                $precotizacion10 = new PreCotizacion10;
                                $precotizacion10->fill($transporte);
                                $precotizacion10->precotizacion10_producto = $producto->id;
                                $precotizacion10->precotizacion10_materialp = $materialp->id;
                                $precotizacion10->precotizacion10_precotizacion2 = $precotizacion2->id;
                                $precotizacion10->precotizacion10_fh_elaboro = date('Y-m-d H:i:s');
                                $precotizacion10->precotizacion10_usuario_elaboro = auth()->user()->id;
                                $precotizacion10->save();

                            } else {
                                $precotizacion10->fill($transporte);
                                $precotizacion10->save();

                            }

                            // asociar id a un array para validar
                            $keys[] = $precotizacion10->id;
                        }

                        // Remover registros que no existan
                        $deletetransportes = PreCotizacion10::whereNotIn('id', $keys)->where('precotizacion10_precotizacion2', $precotizacion2->id)->delete();

                        // Commit Transaction
                        DB::commit();
                        return response()->json(['success' => true, 'id' => $precotizacion2->id, 'id_precotizacion' => $precotizacion->id]);
                    } catch(\Exception $e) {
                        DB::rollback();
                        Log::error($e->getMessage());
                        return response()->json(['success' => false, 'errors' => trans('app.exception')]);
                    }
                }
                return response()->json(['success' => false, 'errors' => $precotizacion2->errors]);
            }
            abort(403);
        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                $precotizacion2 = PreCotizacion2::find( $id );
                if(!$precotizacion2 instanceof PreCotizacion2) {
                    DB::rollback();
                    return response()->json(['success' => false, 'errors' => 'No es posible recuperar de la pre-cotización, por favor verifique la información del asiento o consulte al administrador.']);
                }

                // Materiales
                DB::table('koi_precotizacion3')->where('precotizacion3_precotizacion2', $precotizacion2->id)->delete();

                // Imagenes
                DB::table('koi_precotizacion4')->where('precotizacion4_precotizacion2', $precotizacion2->id)->delete();

                // Areasp
                DB::table('koi_precotizacion6')->where('precotizacion6_precotizacion2', $precotizacion2->id)->delete();

                // Acabados
                DB::table('koi_precotizacion7')->where('precotizacion7_precotizacion2', $precotizacion2->id)->delete();

                // Maquinas
                DB::table('koi_precotizacion8')->where('precotizacion8_precotizacion2', $precotizacion2->id)->delete();

                // Empaques
                DB::table('koi_precotizacion9')->where('precotizacion9_precotizacion2', $precotizacion2->id)->delete();

                // Transportes
                DB::table('koi_precotizacion10')->where('precotizacion10_precotizacion2', $precotizacion2->id)->delete();

                // Eliminar item precotizacion2
                $precotizacion2->delete();

                // Recuperar cartepta y eliminar
                if (Storage::has("pre-cotizaciones/precotizacion_$precotizacion2->precotizacion2_precotizacion1/producto_$precotizacion2->id")) {
                    Storage::deleteDirectory("pre-cotizaciones/precotizacion_$precotizacion2->precotizacion2_precotizacion1/producto_$precotizacion2->id");
                }

                DB::commit();
                return response()->json(['success' => true]);
            } catch(\Exception $e) {
                DB::rollback();
                Log::error(sprintf('%s -> %s: %s', 'Precotizacion2Controller', 'destroy', $e->getMessage()));
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
            $precotizacion2 = PreCotizacion2::findOrFail($id);
            DB::beginTransaction();
            try {
                $newprecotizacion2 = $precotizacion2->replicate();
                $newprecotizacion2->save();

                // Acabados
                $acabados = PreCotizacion7::where('precotizacion7_precotizacion2', $precotizacion2->id)->get();
                foreach ($acabados as $precotizacion7) {
                     $newprecotizacion7 = $precotizacion7->replicate();
                     $newprecotizacion7->precotizacion7_precotizacion2 = $newprecotizacion2->id;
                     $newprecotizacion7->save();
                }

                // Mquinas
                $maquinas = PreCotizacion8::where('precotizacion8_precotizacion2', $precotizacion2->id)->get();
                foreach ($maquinas as $precotizacion8) {
                     $newprecotizacion8 = $precotizacion8->replicate();
                     $newprecotizacion8->precotizacion8_precotizacion2 = $newprecotizacion2->id;
                     $newprecotizacion8->save();
                }

                // Imagenes
                $imagenes = PreCotizacion4::where('precotizacion4_precotizacion2', $precotizacion2->id)->get();
                foreach ($imagenes as $precotizacion4) {
                    $newprecotizacion4 = $precotizacion4->replicate();
                    $newprecotizacion4->precotizacion4_precotizacion2 = $newprecotizacion2->id;
                    $newprecotizacion4->precotizacion4_usuario_elaboro = auth()->user()->id;
                    $newprecotizacion4->precotizacion4_fh_elaboro = date('Y-m-d H:i:s');
                    $newprecotizacion4->save();

                    // Recuperar imagen y copy
                    if( Storage::has("pre-cotizaciones/precotizacion_$precotizacion2->precotizacion2_precotizacion1/producto_$precotizacion2->id/$precotizacion4->precotizacion4_archivo") ) {

                        $oldfile = "pre-cotizaciones/precotizacion_$precotizacion2->precotizacion2_precotizacion1/producto_$precotizacion2->id/$precotizacion4->precotizacion4_archivo";
                        $newfile = "pre-cotizaciones/precotizacion_$newprecotizacion2->precotizacion2_precotizacion1/producto_$newprecotizacion2->id/$newprecotizacion4->precotizacion4_archivo";

                        // Copy file storege laravel
                        Storage::copy($oldfile, $newfile);
                    }
                }

                // Materiales
                $materiales = PreCotizacion3::where('precotizacion3_precotizacion2', $precotizacion2->id)->get();
                foreach ($materiales as $precotizacion3) {
                     $newprecotizacion3 = $precotizacion3->replicate();
                     $newprecotizacion3->precotizacion3_precotizacion2 = $newprecotizacion2->id;
                     $newprecotizacion3->precotizacion3_usuario_elaboro = auth()->user()->id;
                     $newprecotizacion3->precotizacion3_fh_elaboro = date('Y-m-d H:i:s');
                     $newprecotizacion3->save();
                }

                // Areasp
                $areasp = PreCotizacion6::where('precotizacion6_precotizacion2', $precotizacion2->id)->get();
                foreach ($areasp as $precotizacion6) {
                     $newprecotizacion6 = $precotizacion6->replicate();
                     $newprecotizacion6->precotizacion6_precotizacion2 = $newprecotizacion2->id;
                     $newprecotizacion6->save();
                }

                // Transportes
                $transportes = PreCotizacion10::where('precotizacion10_precotizacion2', $precotizacion2->id)->get();
                foreach ($transportes as $precotizacion10) {
                     $newprecotizacion10 = $precotizacion10->replicate();
                     $newprecotizacion10->precotizacion10_precotizacion2 = $newprecotizacion2->id;
                     $newprecotizacion10->precotizacion10_usuario_elaboro = auth()->user()->id;
                     $newprecotizacion10->precotizacion10_fh_elaboro = date('Y-m-d H:i:s');
                     $newprecotizacion10->save();
                }

                // Commit Transaction
                DB::commit();
                return response()->json(['success' => true, 'id' => $newprecotizacion2->id, 'msg' => 'Producto de pre-cotización clonado con exito.']);
            } catch(\Exception $e) {
                DB::rollback();
                Log::error($e->getMessage());
                return response()->json(['success' => false, 'errors' => trans('app.exception')]);
            }
        }
        abort(403);
    }
}

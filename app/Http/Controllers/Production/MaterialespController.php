<?php

namespace App\Http\Controllers\Production;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB, Log, Datatables, Cache;

use App\Models\Production\Materialp, App\Models\Production\TipoMaterial;

class MaterialespController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Materialp::query();
            return Datatables::of($query)
            ->filter(function($query) use ($request){

                // Tipo Material
                if($request->has('tipo')){
                    $query->where('materialp_tipomaterial', $request->tipo );
                }
                
            })->make(true);
        }

        return view('production.materiales.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('production.materiales.create');
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

            $material = new Materialp;
            if ($material->isValid($data)) {
                DB::beginTransaction();
                try {
                    // Recuperar TipoMaterial
                    $tipomaterial = TipoMaterial::find($request->materialp_tipomaterial);
                    if(!$tipomaterial instanceof TipoMaterial){
                        DB::rollback();
                        return response()->json(['success'=>false, 'errors'=>'No es posible recuperar el tipo de material, por favor verifique la informacion o consulte al administrador.']);
                    }

                    // Material
                    $material->fill($data);
                    $material->materialp_tipomaterial = $tipomaterial->id;
                    $material->save();

                    // Commit Transaction
                    DB::commit();
                    // Forget cache
                    Cache::forget( Materialp::$key_cache );

                    return response()->json(['success' => true, 'id' => $material->id]);
                }catch(\Exception $e){
                    DB::rollback();
                    Log::error($e->getMessage());
                    return response()->json(['success' => false, 'errors' => trans('app.exception')]);
                }
            }
            return response()->json(['success' => false, 'errors' => $material->errors]);
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
        $material = Materialp::where('koi_materialp.id', $id)
                ->select('koi_materialp.*', 'tipomaterial_nombre')
                ->leftJoin('koi_tipomaterial','materialp_tipomaterial','=','koi_tipomaterial.id')
                ->first();
        if ($request->ajax()) {
            return response()->json($material);
        }
        return view('production.materiales.show', ['material' => $material]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $material = Materialp::findOrFail($id);
        return view('production.materiales.edit', ['material' => $material]);
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

            $material = Materialp::findOrFail($id);
            if ($material->isValid($data)) {
                DB::beginTransaction();
                try {
                    // Recuperar TipoMaterial
                    $tipomaterial = TipoMaterial::find($request->materialp_tipomaterial);
                    if(!$tipomaterial instanceof TipoMaterial){
                        DB::rollback();
                        return response()->json(['success'=>false, 'errors'=>'No es posible recuperar el tipo de material, por favor verifique la informacion o consulte al administrador.']);
                    }

                    // Material
                    $material->fill($data);
                    $material->materialp_tipomaterial = $tipomaterial->id;
                    $material->save();

                    // Commit Transaction
                    DB::commit();
                    // Forget cache
                    Cache::forget( Materialp::$key_cache );

                    return response()->json(['success' => true, 'id' => $material->id]);
                }catch(\Exception $e){
                    DB::rollback();
                    Log::error($e->getMessage());
                    return response()->json(['success' => false, 'errors' => trans('app.exception')]);
                }
            }
            return response()->json(['success' => false, 'errors' => $material->errors]);
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
}

<?php

namespace App\Models\Base;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;

use App\Models\BaseModel;

use Validator, DB, Cache;

class Tercero extends BaseModel implements AuthenticatableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;
    use EntrustUserTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'koi_tercero';

    /**
     * The key used by cache store.
     *
     * @var static string
     */
    public static $key_cache_tadministrators = '_technical_administrators';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tercero_nit', 'tercero_digito', 'tercero_tipo', 'tercero_regimen', 'tercero_persona', 'tercero_nombre1', 'tercero_nombre2', 'tercero_apellido1', 'tercero_apellido2', 'tercero_razonsocial', 'tercero_direccion','tercero_dir_nomenclatura', 'tercero_municipio', 'tercero_direccion', 'tercero_email', 'tercero_representante', 'tercero_cc_representante', 'tercero_telefono1', 'tercero_telefono2', 'tercero_fax', 'tercero_celular', 'tercero_actividad', 'tercero_cual', 'username', 'tercero_coordinador_por'];

    /**
     * The attributes that are mass boolean assignable.
     *
     * @var array
     */
    protected $boolean = ['tercero_activo', 'tercero_responsable_iva', 'tercero_autoretenedor_cree', 'tercero_gran_contribuyente', 'tercero_autoretenedor_renta', 'tercero_autoretenedor_ica', 'tercero_socio', 'tercero_cliente', 'tercero_acreedor', 'tercero_interno', 'tercero_mandatario', 'tercero_empleado', 'tercero_proveedor', 'tercero_extranjero', 'tercero_afiliado', 'tercero_tecnico', 'tercero_coordinador', 'tercero_otro'];

    /**
     * The attributes that are mass nullable fields to null.
     *
     * @var array
     */
    protected $nullable = ['tercero_coordinador_por'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function isValid($data)
    {
        $rules = [
            'tercero_nit' => 'required|max:15|min:1|unique:koi_tercero',
            'tercero_digito' => 'required',
            'tercero_tipo' => 'required',
            'tercero_regimen' => 'required',
            'tercero_persona' => 'required',
            'tercero_direccion' => 'required',
            'tercero_municipio' => 'required',
            'tercero_actividad' => 'required',
            'username' => 'unique:koi_tercero',
            'password' => 'min:6|confirmed',
        ];

        if ($this->exists){
            $rules['tercero_nit'] .= ',tercero_nit,' . $this->id;
            $rules['username'] .= ',username,' . $this->id;
        }else{
            $rules['tercero_nit'] .= '|required';
        }

        $validator = Validator::make($data, $rules);
        if ($validator->passes())
        {
            if($data['tercero_persona'] == 'N') {
                if(empty($data['tercero_nombre1'])) {
                    $this->errors = trans('validation.required', ['attribute' => '1er. Nombre']);
                    return false;
                }
                if(empty($data['tercero_apellido1'])) {
                    $this->errors = trans('validation.required', ['attribute' => '1er. Apellido']);
                    return false;
                }
            }else{
                if(empty($data['tercero_razonsocial'])) {
                    $this->errors = trans('validation.required', ['attribute' => 'Razón Social, Comercial o Establecimiento']);
                    return false;
                }
            }
            return true;
        }
        $this->errors = $validator->errors();
        return false;
    }

    public static function getTercero($id)
    {
        $query = Tercero::query();
        $query->select('koi_tercero.*', 'actividad_nombre', 'actividad_tarifa', DB::raw("CONCAT(municipio_nombre, ' - ', departamento_nombre) as municipio_nombre"), DB::raw("(CASE WHEN tc.tercero_persona = 'N'
                    THEN CONCAT(tc.tercero_nombre1,' ',tc.tercero_nombre2,' ',tc.tercero_apellido1,' ',tc.tercero_apellido2,
                            (CASE WHEN (tc.tercero_razonsocial IS NOT NULL AND tc.tercero_razonsocial != '') THEN CONCAT(' - ', tc.tercero_razonsocial) ELSE '' END)
                        )
                    ELSE tc.tercero_razonsocial END)
                AS nombre_coordinador"));
        $query->leftJoin('koi_actividad', 'tercero_actividad', '=', 'koi_actividad.id');
        $query->leftJoin('koi_municipio', 'tercero_municipio', '=', 'koi_municipio.id');
        $query->leftJoin('koi_departamento', 'koi_municipio.departamento_codigo', '=', 'koi_departamento.departamento_codigo');
        $query->leftJoin('koi_tercero as tc', 'koi_tercero.tercero_coordinador_por', '=', 'tc.id');
        $query->where('koi_tercero.id', $id);
        return $query->first();
    }

    public function getName()
    {
        return $this->attributes['tercero_razonsocial'] ? $this->attributes['tercero_razonsocial'] : sprintf('%s %s %s', $this->attributes['tercero_nombre1'], $this->attributes['tercero_apellido1'], $this->attributes['tercero_apellido2']);
    }

    public static function getTechnicalAdministrators()
    {
        if (Cache::has(self::$key_cache_tadministrators)) {
            return Cache::get(self::$key_cache_tadministrators);
        }

        return Cache::rememberForever(self::$key_cache_tadministrators, function() {
            $query = Tercero::query();
            $query->select('id',
                DB::raw("(CASE WHEN tercero_persona = 'N'
                    THEN CONCAT(tercero_nombre1,' ',tercero_nombre2,' ',tercero_apellido1,' ',tercero_apellido2,
                            (CASE WHEN (tercero_razonsocial IS NOT NULL AND tercero_razonsocial != '') THEN CONCAT(' - ', tercero_razonsocial) ELSE '' END)
                        )
                    ELSE tercero_razonsocial END)
                AS tercero_nombre")
            );
            $query->where('tercero_activo', true);
            $query->where('tercero_coordinador', true);
            $query->orderby('tercero_nombre', 'asc');
            $collection = $query->lists('tercero_nombre', 'id');

            $collection->prepend('', '');
            return $collection;
        });
    }

    public function setTerceroNombre1Attribute($name)
    {
        $this->attributes['tercero_nombre1'] = strtoupper($name);
    }

    public function setTerceroNombre2Attribute($name)
    {
        $this->attributes['tercero_nombre2'] = strtoupper($name);
    }

    public function setTerceroApellido1Attribute($lastname)
    {
        $this->attributes['tercero_apellido1'] = strtoupper($lastname);
    }

    public function setTerceroApellido2Attribute($lastname)
    {
        $this->attributes['tercero_apellido2'] = strtoupper($lastname);
    }

    public function setTerceroRazonsocialAttribute($name)
    {
        $this->attributes['tercero_razonsocial'] = strtoupper($name);
    }

    public function setTerceroDireccionAttribute($name)
    {
        $this->attributes['tercero_direccion'] = strtoupper($name);
    }
}
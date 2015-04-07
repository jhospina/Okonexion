<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait,
        RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');
    protected $fillable = array('email', 'password', 'nombres', 'apellidos', 'dni', "empresa", "pais", "region", "ciudad", "direccion", "telefono", "celular");

    //********************************************************
    //TIPOS DE USUARIOS********************************
    //********************************************************

    const USUARIO_REGULAR = "RE";
    const USUARIO_SOPORTE_GENERAL = "SG";

    public function registrar($data) {
        if (isset($data["nombre1"]))
            $data["nombres"] = $data["nombre1"] . " " . $data["nombre2"];

        $this->fill($data);
        // Guardamos el usuario
        return $this->save();
    }

    //Valida la información ingresada del usuario
    public function validar($data) {
        $errores = "";

        //Correo electronico
        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL))
            $errores.="<li>Debes escribir un correo electrónico válido</li>";

        //Nombre
        if (strlen($data["nombres"]) < 3)
            $errores.="<li>Debes escribir tu nombre</li>";

        if (strlen($data["apellidos"]) < 3)
            $errores.="<li>Debes escribir tus apellidos</li>";

        if (strlen($data["dni"]) > 0)
            if (!is_numeric($data["dni"]))
                $errores.="<li>El DNI/NIT solo debe contener números [0-9]</li>";

        if (strlen($data["telefono"]) > 0)
            if (!is_numeric($data["telefono"]))
                $errores.="<li>El telefono solo debe contener números [0-9]</li>";

        if (strlen($data["celular"]) > 0)
            if (!is_numeric($data["celular"]))
                $errores.="<li>El celular solo debe contener números [0-9]</li>";


        if (strlen($errores) > 0)
            return "<ul>" . $errores . "</ul>";
        else
            return $errores;
    }

    //Retorna un array con el formato de las variables para mostrarle un mensaje al usuario
    public static function mensaje($tipo, $params, $mensaje, $pos = 1) {
        return array("tipo_mensaje" => $tipo, "param_mensaje" => $params, "mensaje" => $mensaje, "pos_mensaje" => $pos);
    }

    //Permite cambiar la contraseña del usuario
    public static function cambiarContrasena($actual, $nueva) {

        $usuario = User::find(Auth::user()->id);

        //Obtiene la contraseña actual del usuario almacenada en la base de datos
        $actualDB = $usuario->password;

        //Verifica si la contraseña actual ingresada por el usuario es la misma que el la almacenada en la base de datos
        if (Hash::check($actual, $actualDB)) {
            $usuario->password = $nueva;
            return $usuario->update();
        } else {
            return false;
        }
    }

    //****************************************************
    //RELACIONES CON OTROS MODELOS***************************
    //****************************************************


    public function tickets() {
        if (Auth::user()->tipo == User::USUARIO_REGULAR)
            return $this->hasMany("Ticket", "usuario_cliente", "id");
        else
            return $this->hasMany("Ticket", "usuario_soporte", "id");
    }

    public function procesoapps() {
        return $this->hasMany("ProcesoApp", "id_soporte", "id");
    }

    public function aplicacion() {
        return $this->hasOne('Aplicacion', 'id_usuario');
    }

    //****************************************************
    //MODIFICACION DE ATRIBUTOS***************************
    //****************************************************

    public function setPasswordAttribute($value) {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    //****************************************************
    //UTILIDADES***************************
    //***************************************************

    public static function obtenerTiempoActual() {
        date_default_timezone_set('America/Bogota');
        return date('Y-m-d H:i:s');
    }

    public static function validarAcceso($tipo_usuario, $URL = "") {
        if (Auth::user()->tipo != $tipo_usuario)
            return Redirect::to($URL);
        else
            return null;
    }

    public static function invalidarAcceso($tipo_usuario, $URL = "") {
        if (Auth::user()->tipo == $tipo_usuario)
            return Redirect::to($URL);
        else
            return null;
    }
    
    
    
     /** Agrega un metadato a un usuario
     * 
     * @param String $clave La clave del metadato
     * @param String $valor El valor del metadato
     * @param Int $id_usuario (Opcional) Si no se especifica se toma el de la sesion actual
     */
    static function agregarMetaDato($clave, $valor,$id_usuario=null) {
        $meta = new UsuarioMetadato;
        $meta->id_contenido = $id_contenido;
        if (is_null($id_usuario))
            $meta->id_usuario = Auth::user()->id;
        else
            $meta->id_usuario = $id_usuario;
        $meta->clave = $clave;
        $meta->valor = $valor;
        $meta->save();
    }

    /** Obtiene el valor de un metadado dado por su valor clave
     * 
     * @param type $clave El valor clave que identifica el metadato
     * @param Int $id_usuario (Opcional) Si no se especifica se toma el de la sesion actual
     * @return type Retorna el valor del metadato en caso de exito, de lo contrario Null. 
     */
    static function obtenerMetadato($clave,$id_usuario=null) {
        if (is_null($id_usuario))
            $id_usuario = Auth::user()->id;
 
        $metas = UsuarioMetadato::where("id_usuario", $id_usuario)->where("clave", $clave)->get();
        foreach ($metas as $meta)
            return $meta;
        return null;
    }

    /** Actualiza el valor de un metadato de un usuario, y si no existe lo crea
     * 
     * @param String $clave La clave del metadato
     * @param String $valor El valor del metadato a actualizar
     * @param Int $id_usuario (Opcional) Si no se especifica se toma el de la sesion actual
     * @return boolean El resultado de la operacion
     */
    static function actualizarMetadato($clave, $valor,$id_usuario=null) {
        
        if (is_null($id_usuario))
            $id_usuario = Auth::user()->id;
        
        $meta = User::obtenerMetadato($clave,$id_usuario);
        //Si no existe lo agrega
        if(is_null($meta))
            return User::agregarMetaDato ($clave, $valor,$id_usuario);
        
        $meta->valor = $valor;
        return $meta->save();
    }

    /** Obtiene el valor de un metadato dado por su nombre clave
     * 
     * @param type $clave
     * @param Int $id_usuario (Opcional) Si no se especifica se toma el de la sesion actual
     * @return String Retorna el valro del metatado o null si no existe
     */
    static function obtenerValorMetadato($clave,$id_usuario=null) {
        
        if (is_null($id_usuario))
            $id_usuario = Auth::user()->id;
        
        $metas = UsuarioMetadato::where("id_usuario", $id_usuario)->where("clave", $clave)->get();
        foreach ($metas as $meta)
            return $meta->valor;
        return null;
    }

    /** Indica si el metadato de un usuario existe o esta definido
     * 
     * @param String $clave La clave del metadato
     * @param Int $id_usuario (Opcional) Si no se especifica se toma el de la sesion actual
     * @return boolean
     */
    static function existeMetadato($clave,$id_usuario=null) {
        
        if (is_null($id_usuario))
            $id_usuario = Auth::user()->id;
        
        $metas = UsuarioMetadato::where("id_usuario", $id_usuario)->where("clave", $clave)->get();

        if (count($metas) > 0)
            return true;
        else
            return false;
    }


}

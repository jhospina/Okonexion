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

}

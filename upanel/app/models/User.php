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
    protected $fillable = array('email', 'password', 'nombres', 'apellidos', 'dni', "empresa", "pais", "region", "ciudad", "direccion", "telefono", "celular", "instancia");

    //********************************************************
    //CONFIGURACION DE PLATAFORMA********************************
    //********************************************************

    const CONFIG_URL_LOGIN = "/login/";
    const PARAM_INSTANCIA_SUPER_ADMIN = 0;
    //********************************************************
    //TIPOS DE USUARIOS********************************
    //********************************************************

    const USUARIO_REGULAR = "RE";
    const USUARIO_SOPORTE_GENERAL = "SG";
    const USUARIO_ADMIN = "AD";
    //********************************************************
    //ESTADOS DE USUARIOS********************************
    //********************************************************
    const ESTADO_SIN_PAGAR = "SP";
    const ESTADO_PERIODO_PRUEBA = "PP";
    const ESTADO_PRUEBA_FINALIZADA = "PF";
    const ESTADO_SUSCRIPCION_VIGENTE = "SV";
    const ESTADO_SUSCRIPCION_CADUCADA = "SC";
    //********************************************************
    //METADATOS DE USUARIOS********************************
    //********************************************************

    const META_URL_ORIGEN = "url_origen";
    const META_URL_RECOVERY = "url_recovery";

    /** Registra los datos de un usuario
     *  
     * @param string $data
     * @param type $act Indica sie s una actualizacion de datos
     * @return boolean
     */
    public function registrar($data, $act = false) {
        if (isset($data["nombre1"]))
            $data["nombres"] = $data["nombre1"] . " " . $data["nombre2"];

        //Si es el registro de un usuario nuevo
        if (!$act) {
            //Verifica que no halla un email existente en la base de datos
            $consultar_email = User::where("email", $data["email"])->get();
            if (count($consultar_email) > 0)
                return false;
        }

        $this->fill($data);

        if (isset($data["tipo"]))
            $this->tipo = $data["tipo"];
        if (isset($data["email_confirmado"]))
            $this->email_confirmado = $data["email_confirmado"];

        if (isset($data["instancia"]))
            $instancia = intval($data["instancia"]);
        else
            $instancia = 0;

        //Verifica si en la instancia esta activado el periodo de prueba para asignarselo al usuario nuevo
        if (Util::convertirIntToBoolean(Instancia::obtenerValorMetadato(ConfigInstancia::periodoPrueba_activado, $instancia)) && !$act) {
            $fecha = new Fecha(Util::obtenerTiempoActual());

            //Agrega el estado de periodo de prueba al usuario y le asigna el numero de dias indicados por el administrador
            $this->estado = User::ESTADO_PERIODO_PRUEBA;
            $this->fin_suscripcion = $fecha->agregarDias(Instancia::obtenerValorMetadato(ConfigInstancia::periodoPrueba_numero_dias, $instancia));
        }


        // Guardamos el usuario
        $save = $this->save();

        if (isset($data["url"]))
            $this->agregarMetaDato(User::META_URL_ORIGEN, $data["url"], $this->id);

        return $save;
    }

    //Valida la información ingresada del usuario
    public function validar($data) {
        $errores = "";

        //Correo electronico
        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL))
            $errores.="<li>" . trans("menu_usuario.mi_perfil.info.email.error") . "</li>";

        //Nombre
        if (strlen($data["nombres"]) < 3)
            $errores.="<li>" . trans("menu_usuario.mi_perfil.info.nombre.error") . "</li>";

        if (strlen($data["apellidos"]) < 3)
            $errores.="<li>" . trans("menu_usuario.mi_perfil.info.apellidos.error") . "</li>";

        if (isset($data["dni"])) {
            if (strlen($data["dni"]) > 0)
                if (!is_numeric($data["dni"]))
                    $errores.="<li>" . trans("menu_usuario.mi_perfil.info.dni.error") . "</li>";
        }

        if (isset($data["telefono"])) {
            if (strlen($data["telefono"]) > 0)
                if (!is_numeric($data["telefono"]))
                    $errores.="<li>" . trans("menu_usuario.mi_perfil.info.telefono.error") . "</li>";
        }
        if (isset($data["celular"])) {
            if (strlen($data["celular"]) > 0)
                if (!is_numeric($data["celular"]))
                    $errores.="<li>" . trans("menu_usuario.mi_perfil.info.celular.error") . "</li>";
        }

        if (isset($data["password"])) {
            if (strlen($data["password"]) <= 5)
                $errores.="<li>" . trans("menu_usuario.cambiar_contrasena.post.error01") . "</li>";

            if ($data["password"] != $data["password2"])
                $errores.="<li>" . trans("menu_usuario.cambiar_contrasena.post.error02") . "</li>";
        }

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

    static function login() {
        return Redirect::to(User::CONFIG_URL_LOGIN);
    }

    //****************************************************
    //RELACIONES CON OTROS MODELOS***************************
    //****************************************************


    public function tickets() {
        if (Auth::user()->tipo == User::USUARIO_REGULAR)
            return $this->hasMany("Ticket", "usuario_cliente", "id");
        else {
            if (isset($_GET["ref"])) {
                if (!(User::esSuperAdmin()) && $_GET["ref"] == "assist") {
                    return $this->hasMany("Ticket", "usuario_cliente", "id");
                }
            } else {
                return $this->hasMany("Ticket", "usuario_soporte", "id");
            }
        }
    }

    public function procesoapps() {
        return $this->hasMany("ProcesoApp", "id_soporte", "id");
    }

    public function aplicacion() {
        return $this->hasOne('Aplicacion', 'id_usuario', "id");
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

    /** Valida el acceso a un usuario, retorna un redireccion si se invalida, de lo contrario Null
     * 
     * @param type $tipo_usuario El tipo de usuario a validar
     * @param type $URL LA url a donde redireccional
     * @return type
     */
    public static function validarAcceso($tipo_usuario, $URL = "") {
        if (Auth::user()->tipo != $tipo_usuario)
            return Redirect::to($URL);
        else
            return null;
    }

    /** Invalida el acceso a un usuario, retorna un redireccion si se invalida, de lo contrario Null
     * 
     * @param type $tipo_usuario El tipo de usuario a invalidar
     * @param type $URL LA url a donde redireccional
     * @return type
     */
    public static function invalidarAcceso($tipo_usuario, $URL = "") {
        if (Auth::user()->tipo == $tipo_usuario)
            return Redirect::to($URL);
        else
            return null;
    }

    /** Agrega un metadato a un usuario, si el metadato ya existe, lo actualiza
     * 
     * @param String $clave La clave del metadato
     * @param String $valor El valor del metadato
     * @param Int $id_usuario (Opcional) Si no se especifica se toma el de la sesion actual
     */
    static function agregarMetaDato($clave, $valor, $id_usuario = null) {
        $meta = new UsuarioMetadato;

        if (is_null($id_usuario))
            $id_usuario = Auth::user()->id;

        if (User::existeMetadato($clave, $id_usuario))
            return User::actualizarMetadato($clave, $valor, $id_usuario);

        $meta->id_usuario = $id_usuario;
        $meta->clave = $clave;
        $meta->valor = $valor;
        $meta->save();
    }

    /** Obtiene el objeto de un metadado dado por su valor clave
     * 
     * @param type $clave El valor clave que identifica el metadato
     * @param Int $id_usuario (Opcional) Si no se especifica se toma el de la sesion actual
     * @return type Retorna el valor del metadato en caso de exito, de lo contrario Null. 
     */
    static function obtenerMetadato($clave, $id_usuario = null) {

        if (is_null($id_usuario)) {
            if (isset(Auth::user()->id))
                $id_usuario = Auth::user()->id;
            else
                return null;
        }


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
    static function actualizarMetadato($clave, $valor, $id_usuario = null) {

        if (is_null($id_usuario)) {
            if (isset(Auth::user()->id))
                $id_usuario = Auth::user()->id;
            else
                return false;
        }

        $meta = User::obtenerMetadato($clave, $id_usuario);
        //Si no existe lo agrega
        if (is_null($meta))
            return User::agregarMetaDato($clave, $valor, $id_usuario);

        $meta->valor = $valor;

        return $meta->save();
    }

    /** Obtiene el valor de un metadato dado por su nombre clave
     * 
     * @param type $clave
     * @param Int $id_usuario (Opcional) Si no se especifica se toma el de la sesion actual
     * @return String Retorna el valro del metatado o null si no existe
     */
    static function obtenerValorMetadato($clave, $id_usuario = null) {

        if (is_null($id_usuario)) {
            if (isset(Auth::user()->id))
                $id_usuario = Auth::user()->id;
            else
                return null;
        }

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
    static function existeMetadato($clave, $id_usuario = null) {

        if (is_null($id_usuario)) {
            if (isset(Auth::user()->id))
                $id_usuario = Auth::user()->id;
            else
                return null;
        }


        $metas = UsuarioMetadato::where("id_usuario", $id_usuario)->where("clave", $clave)->get();

        if (count($metas) > 0)
            return true;
        else
            return false;
    }

    static function eliminarMetadato($clave, $id_usuario = null) {

        if (is_null($id_usuario)) {
            if (isset(Auth::user()->id))
                $id_usuario = Auth::user()->id;
            else
                return null;
        }

        $meta = User::obtenerMetadato($clave, $id_usuario);

        return $meta->delete();
    }

    /*     * Indica si el usuario es un superadminitrador 
     * 
     * @return boolean
     */

    static function esSuperAdmin() {
        return (Auth::user()->instancia == User::PARAM_INSTANCIA_SUPER_ADMIN && Auth::user()->tipo == User::USUARIO_ADMIN);
    }

    /** Indica si el usuario es super
     * 
     * @return boolean
     */
    static function esSuper() {
        return (User::esSuperAdmin() || Auth::user()->instancia == User::PARAM_INSTANCIA_SUPER_ADMIN);
    }

    /** Indica si el usuario se encuentra en periodo de prueba
     * 
     * @return type
     */
    static function enPrueba() {
        return (Auth::user()->tipo == User::USUARIO_REGULAR && Auth::user()->estado == User::ESTADO_PERIODO_PRUEBA && Util::convertirIntToBoolean(Instancia::obtenerValorMetadato(ConfigInstancia::periodoPrueba_activado)));
    }

    static function verificarPerfil() {
        $user = Auth::user();
        if ($user->dni == null || $user->pais == null || $user->region == null || $user->ciudad == null || $user->direccion == null)
            return User::mensaje("advertencia", "text-center", trans("msj.ad.completar.perfil", array("nombre" => $user->nombres, "link" => Route("usuario.edit", Auth::user()->id))));
        elseif ($user->telefono == null && $user->celular == null)
            return User::mensaje("advertencia", "text-center", trans("msj.ad.completar.perfil.telefono.celular", array("nombre" => $user->nombres, "link" => Route("usuario.edit", Auth::user()->id))));
        else
            return array();
    }

    /** Obtiene el id del producto de suscripcion vigente del usuario
     * 
     * @return type
     */
    public function obtenerProductoIdSuscripcionVigente() {
        $configs = ConfigInstancia::obtenerListadoConfig();

        $suc = User::obtenerValorMetadato(UsuarioMetadato::SUSCRIPCION_TIPO, $this->id) . User::obtenerValorMetadato(UsuarioMetadato::SUSCRIPCION_CICLO, $this->id);

        foreach ($configs as $index => $valor) {
            if (strpos($valor, $suc) !== false)
                return $valor;
        }

        return null;
    }

    //Obtiene la tipo de moneda del usuario
    public function getMoneda() {
        return User::obtenerValorMetadato(UsuarioMetadato::OP_MONEDA, $this->id);
    }

}

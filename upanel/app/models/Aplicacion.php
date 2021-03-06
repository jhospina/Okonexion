<?php

Class Aplicacion extends Eloquent {

    protected $table = 'aplicaciones';
    protected $fillable = array('id_usuario', 'nombre', 'key_app', 'url_logo', 'diseno', "estado", "configuracion");

    //********************************************************
    //ESTADOS DE LA APLICACION********************************
    //********************************************************

    const ESTADO_EN_DISENO = "DI";
    const ESTADO_ESTABLECIENTO_TEXTOS = "ET";
    const ESTADO_LISTA_PARA_ENVIAR = "LE";
    const ESTADO_EN_COLA_PARA_DESARROLLO = "CD";
    const ESTADO_EN_DESARROLLO = "DE";
    const ESTADO_TERMINADA = "TE";
    const ESTADO_EN_PROCESO_DE_ACTUALIZACION = "PA";
    const ESTADO_APLICACION_ACTIVA = "AA";
    const ESTADO_APLICACION_INACTIVA = "AI";
    //********************************************************
    //DATOS DE CONFIGURACION********************************
    //********************************************************
    const configLogoApp = "logoApp";
    const configNombreApp = "nombreApp";
    const configKeyApp = "keyApp";
    const configdisenoApp = "disenoApp";

    /** Obtiene el id de instancia de la aplicacion
     * 
     * @return type
     */
    public function getIdInstancia() {
        $usuario = User::find($this->id);
        return $usuario->instancia;
    }

    /** Retorna una array con las URL y el nombre de los diseños establecidos para una aplicacion
     * 
     * @return Array Retorna un array con las imagenes del los diseños de App
     */
    public static function mockups() {
        $mockups[App_Instytul_Metro::sigla] = App_Instytul_Metro::sigla;
        return $mockups;
    }

    /** Genera una clave de [50] caracteres
     * 
     * @param Int $longitud [50] La longitud de la clave
     * @return String La clave generada
     */
    public function generarKeyApp($longitud = 50) {
        $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"; //posibles caracteres a usar
        $codigo = ""; //variable para almacenar la cadena generada
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= substr($caracteres, rand(0, strlen($caracteres)), 1); /* Extraemos 1 caracter de los caracteres 
              entre el rango 0 a Numero de letras que tiene la cadena */
        }
        return $codigo;
    }

    /** Busca y obtiene la aplicacion del usuario, si es que existe
     * 
     * @return Aplicacion Retorna el objeto controlador de la aplicacion
     */
    public static function obtener() {
        $apps = Aplicacion::where("id_usuario", "=", Auth::user()->id)->get();
        if (count($apps) > 0) {
            foreach ($apps as $app)
                break;
            return $app;
        } else {
            return null;
        }
    }

    /** Indica si la aplicacion del usuario existe, es decir, ya fue creada. 
     * 
     * @return boolean True si la aplicacion del usuario existe, de lo contrario False
     */
    public static function existe() {
        $apps = Aplicacion::where("id_usuario", "=", Auth::user()->id)->get();
        if (count($apps) > 0) {
            foreach ($apps as $app)
                break;
            return true;
        } else {
            return false;
        }
    }

    public static function buscarPorUsuario($id_usuario) {
        $apps = Aplicacion::where("id_usuario", $id_usuario)->get();
        if (count($apps) > 0) {
            foreach ($apps as $app)
                break;
            return $app;
        } else {
            return null;
        }
    }

    /** Busca y obtiene el objeto Aplicacion dado por su id o key_app
     * 
     * @param (Int|String) $id_app Id de la aplicacion a buscar
     * @return type Retorna el objeto de Aplicacion o null en caso de no encontrarlo.
     */
    public static function buscar($id_app) {
        $apps = null;
        if (is_int($id_app))
            $apps = Aplicacion::where("id", "=", $id_app)->get();
        if (is_string($id_app))
            $apps = Aplicacion::where("key_app", $id_app)->get();

        if (count($apps) > 0) {
            foreach ($apps as $app)
                break;
            return $app;
        } else {
            return null;
        }
    }

    /** Retorna el ID de la aplicacion del usuario
     * 
     * @return Int Retorna el ID en caso de exito, de lo contrario Null
     */
    public static function ID() {
        if (!is_null($app = Aplicacion::obtener()))
            return $app->id;
        else
            return null;
    }

    /** Obtiene el nombre nominal del estado, dado por su sigla. 
     * 
     * @param String $sigla Sigla del estado de la aplicacion
     * @return string El nombre nominal
     */
    public static function obtenerNombreEstado($sigla) {
        $estados = array(Aplicacion::ESTADO_EN_DISENO => trans("atributos.estado.app.en_diseno"),
            Aplicacion::ESTADO_ESTABLECIENTO_TEXTOS => trans("atributos.estado.app.estableciendo_textos"),
            Aplicacion::ESTADO_LISTA_PARA_ENVIAR => trans("atributos.estado.app.listo_para_enviar"),
            Aplicacion::ESTADO_EN_COLA_PARA_DESARROLLO => trans("atributos.estado.app.en_cola"),
            Aplicacion::ESTADO_EN_DESARROLLO => trans("atributos.estado.app.en_desarrollo"),
            Aplicacion::ESTADO_EN_PROCESO_DE_ACTUALIZACION => trans("atributos.estado.app.en_proceso_act"),
            Aplicacion::ESTADO_TERMINADA => trans("atributos.estado.app.aplicacion_term"),
            Aplicacion::ESTADO_APLICACION_ACTIVA => trans("atributos.estado.app.activa"),
            Aplicacion::ESTADO_APLICACION_INACTIVA => trans("atributos.estado.app.inactiva"));
        return $estados[$sigla];
    }

    /** Indica si el estado de la aplicacion permite entrar a la seccion de desarrollo
     * 
     * @param String $estado La sigla del estado de la aplicacion 
     * @return boolean
     */
    static function validarEstadoParaEntrarSeccionDesarrollo($estado) {
        if ($estado == Aplicacion::ESTADO_LISTA_PARA_ENVIAR || $estado == Aplicacion::ESTADO_EN_COLA_PARA_DESARROLLO || $estado == Aplicacion::ESTADO_EN_DESARROLLO || $estado == Aplicacion::ESTADO_TERMINADA)
            return true;
        else
            return false;
    }

    /** Indica si la aplicacion ha sido terminada al menos una vez
     * 
     * @param type $estado La sigal del estado del a aplicacion
     * @return boolean
     */
    static function estaTerminada($estado) {
        if ($estado == Aplicacion::ESTADO_TERMINADA || $estado == Aplicacion::ESTADO_APLICACION_ACTIVA || $estado == Aplicacion::ESTADO_APLICACION_INACTIVA)
            return true;
        else
            return false;
    }

    /** Prepara los datos en un Json de la manera como le recibe la aplicacion movil
     * 
     * @param Array $datos Los datos a preparar
     * @return String Retorna una cadena JSON si el proceso se realizo con exito, de lo contrario Null 
     */
    static function prepararDatosParaApp($datos) {
        if (is_array($datos))
            return json_encode($datos);
        else
            return null;
    }

    //****************************************************
    //RELACIONES CON OTROS MODELOS***************************
    //****************************************************
    //Relacion de Uno a Uno con el modelo [User]
    function user() {
        return $this->hasOne('User', 'id', "id_usuario");
    }

    //****************************************************
    //CONTROLADOR META************************************
    //****************************************************

    /**
     * Indica si una opcion de configuracion existe, dada por su dato clave. 
     *
     * @param String clave
     * @return boolean
     */
    static function existeMetadato($clave, $id_app) {
        $configs = AppMeta::where("id_app", $id_app)->where("clave", "=", $clave)->get();
        return (count($configs) > 0);
    }

    /** Agrega una nuevo metadato a aplicacion, si el metadato ya existe, lo actualiza
     * 
     * @param String $clave La clave del metadato
     * @param String $valor El valor del metadato
     * @param Int $id_app (Opcional) Si no se especifica se toma el de la sesion actual
     */
    static function agregarMetadato($clave, $valor, $id_app, $id_usuario = null) {
        $meta = new AppMeta;

        if (Aplicacion::existeMetadato($clave, $id_app))
            return Aplicacion::actualizarMetadato($clave, $valor, $id_app);

        if (is_null($id_usuario))
            $id_usuario = Auth::user()->id;
        $meta->id_usuario = $id_usuario;
        $meta->id_app = $id_app;
        $meta->clave = $clave;
        $meta->valor = $valor;
        return $meta->save();
    }

    /** Actualiza el valor de un metadato de una factura, y si no existe lo crea
     * 
     * @param String $clave La clave del metadato
     * @param String $valor El valor del metadato a actualizar
     * @param Int $id_app (Opcional) Si no se especifica se toma el de la sesion actual
     * @return boolean El resultado de la operacion
     */
    static function actualizarMetadato($clave, $valor, $id_app) {
        $meta = Aplicacion::obtenerMetadato($clave, $id_app);

        //Si no existe lo agrega
        if (is_null($meta))
            return Aplicacion::agregarMetadato($clave, $valor, $id_app);

        $meta->valor = $valor;

        return $meta->save();
    }

    /** Obtiene el objeto de un metadado dado por su valor clave
     * 
     * @param type $clave El valor clave que identifica el metadato
     * @param Int $id_app (Opcional) Si no se especifica se toma el de la sesion actual
     * @return type Retorna el valor del metadato en caso de exito, de lo contrario Null. 
     */
    static function obtenerMetadato($clave, $id_app) {
        $metas = AppMeta::where("id_app", $id_app)->where("clave", $clave)->get();
        foreach ($metas as $meta)
            return $meta;
        return null;
    }

    static function eliminarMetadato($clave, $id_app) {
        $config = Aplicacion::obtenerMetadato($clave, $id_app);
        return $config->delete();
    }

    /** Obtiene el valor de un metadato dado por su nombre clave
     * 
     * @param type $clave
     * @param Int $id_app (Opcional) Si no se especifica se toma el de la sesion actual
     * @return String Retorna el valro del metatado o null si no existe
     */
    static function obtenerValorMetadato($clave, $id_app) {
        $metas = AppMeta::where("id_app", $id_app)->where("clave", $clave)->get();
        foreach ($metas as $meta)
            return $meta->valor;
        return null;
    }

}

<?php

Class Aplicacion extends Eloquent {

    protected $table = 'aplicaciones';
    protected $fillable = array('id_usuario', 'nombre', 'key_app', 'url_logo', 'diseno', "estado", "configuracion");
    public static $dim_logos = array(256, 128, 114, 32, 16); // Arreglo que contiene los tamaños del logo en pixeles de la aplicaion, cuando se va exportar.

    //********************************************************
    //ESTADOS DE LA APLICACION********************************
    //********************************************************

    const ESTADO_EN_DISENO = "DI";
    const ESTADO_LISTA_PARA_ENVIAR = "LE";
    const ESTADO_EN_COLA_PARA_DESARROLLO = "CD";
    const ESTADO_EN_DESARROLLO = "DE";
    const ESTADO_TERMINADA = "TE";
    const ESTADO_EN_PROCESO_DE_ACTUALIZACION = "PA";
    const ESTADO_APLICACION_ACTIVA = "AA";
    const ESTADO_APLICACION_INACTIVA = "AI";

    /** Retorna una array con las URL y el nombre de los diseños establecidos para una aplicacion
     * 
     * @return Array Retorna un array con las imagenes del los diseños de App
     */
    public static function mockups() {
        $mockups[App_Metro::sigla] = URL::to("assets/img/app/" . App_Metro::sigla . ".png");
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
        $app = new Aplicacion;
        if (count($apps) > 0) {
            foreach ($apps as $app)
                break;
            return $app;
        } else {
            return $app;
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
        $estados = array(Aplicacion::ESTADO_EN_DISENO => "En diseño",
            Aplicacion::ESTADO_LISTA_PARA_ENVIAR => "Listo para enviar",
            Aplicacion::ESTADO_EN_COLA_PARA_DESARROLLO => "En cola para desarrollo",
            Aplicacion::ESTADO_EN_DESARROLLO => "En desarrollo",
            Aplicacion::ESTADO_EN_PROCESO_DE_ACTUALIZACION => "En proceso de actualización",
            Aplicacion::ESTADO_TERMINADA => "Aplicación terminada",
            Aplicacion::ESTADO_APLICACION_ACTIVA => "Activa",
            Aplicacion::ESTADO_APLICACION_INACTIVA => "Inactiva");
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
        //return '{"c2dictionary":true,"data":' . json_encode($datos) . '}';
            return json_encode($datos);
        else
            return null;
    }

    static function aumentarNumeroBaseInfo() {
        $app = Aplicacion::obtener();
        $app->num_base_info = intval($app->num_base_info) + 1;
        $app->save();
    }

    //****************************************************
    //RELACIONES CON OTROS MODELOS***************************
    //****************************************************
    //Relacion de Uno a Uno con el modelo [User]
    function user() {
        return $this->belongsTo('User', 'id');
    }

}

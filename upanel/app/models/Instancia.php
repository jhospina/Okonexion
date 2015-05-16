<?php

Class Instancia extends Eloquent {

    protected $table = 'instancias';
    protected $fillable = array("empresa", "nit", "web", "email", "nombre_contacto", "pais", "region", "ciudad", "direccion", "telefono");

    const ESTADO_EN_ESPERA = "ES";
    const ESTADO_PRUEBA = "EP";
    const ESTADO_VIGENTE = "EV";
    const ESTADO_CADUCADO = "EC";

    public function validar($data) {

        $errores = "";

        foreach ($data as $index => $value) {
            if (in_array($index, $this->fillable)) {
                if (strlen($value) < 3) {
                    $errores.="<li>" . trans("instancias.info.error.llenar_campos") . "</li>";
                    break;
                }
            }
        }

        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL))
            $errores.="<li>" . trans("instancias.info.error.email") . "</li>";

        if (strlen($errores) > 0)
            return "<ul>" . $errores . "</ul>";
        else
            return $errores;
    }

    public function registrar($data, $id_admin = null) {

        $this->fill($data);

        if (isset($data["codigo_postal"]))
            $this->codigo_postal = $data["codigo_postal"];
        if (isset($data["celular"]))
            $this->celular = $data["celular"];

        $fecha = new Fecha(Util::obtenerTiempoActual());
        if (!is_null($id_admin)) {
            $this->estado = Instancia::ESTADO_PRUEBA;
            $this->fin_suscripcion = $fecha->agregarSemanas(1);
            $this->id_administrador = $id_admin;
        }

        return $this->save();
    }

    /** Obtiene el valor de una configuracion de instancia dado su nombre clave
     *  
     * @param type $config El nombre clave de la configuracion 
     * @param type $instancia [null] El id de la instancia
     * @return type Retorna el valro de la configaración, de lo contrario null
     */
    static function obtenerValorMetadato($config, $instancia = null) {

        if (is_null($instancia))
            $instancia = Auth::user()->instancia;

        $configs = ConfigInstancia::where("instancia", $instancia)->where("clave", $config)->take(1)->get();

        foreach ($configs as $config)
            return $config->valor;

        return null;
    }

    /**
     * Indica si una opcion de configuracion existe, dada por su dato clave. 
     *
     * @param String clave
     * @return boolean
     */
    static function existeMetadato($clave, $instancia = null) {

        if (is_null($instancia))
            $instancia = Auth::user()->instancia;

        $configs = ConfigInstancia::where("instancia", $instancia)->where("clave", "=", $clave)->get();
        return (count($configs) > 0);
    }

    /** Agrega una nuevo metadato a la instancia, si el metadato ya existe, lo actualiza
     * 
     * @param String $clave La clave del metadato
     * @param String $valor El valor del metadato
     * @param Int $instancia (Opcional) Si no se especifica se toma el de la sesion actual
     */
    static function agregarMetadato($clave, $valor, $instancia = null) {
        $meta = new UsuarioMetadato;

        if (is_null($instancia))
            $instancia = Auth::user()->instancia;

        if (Instancia::existeMetadato($clave, $instancia))
            return Instancia::actualizarMetadato($clave, $valor, $instancia);

        $meta->instancia = $instancia;
        $meta->clave = $clave;
        $meta->valor = $valor;
        $meta->save();
    }

    /** Actualiza el valor de un metadato de una instancia, y si no existe lo crea
     * 
     * @param String $clave La clave del metadato
     * @param String $valor El valor del metadato a actualizar
     * @param Int $instancia (Opcional) Si no se especifica se toma el de la sesion actual
     * @return boolean El resultado de la operacion
     */
    static function actualizarMetadato($clave, $valor, $instancia = null) {

        if (is_null($instancia)) {
            if (isset(Auth::user()->instancia))
                $instancia = Auth::user()->instancia;
            else
                return false;
        }

        $meta = Instancia::obtenerMetadato($clave, $instancia);
        //Si no existe lo agrega
        if (is_null($meta))
            return Instancia::agregarMetadato($clave, $valor, $instancia);

        $meta->valor = $valor;

        return $meta->save();
    }

    
     /** Obtiene el objeto de un metadado dado por su valor clave
     * 
     * @param type $clave El valor clave que identifica el metadato
     * @param Int $instancia (Opcional) Si no se especifica se toma el de la sesion actual
     * @return type Retorna el valor del metadato en caso de exito, de lo contrario Null. 
     */
    static function obtenerMetadato($clave, $instancia = null) {

        if (is_null($instancia)) {
            if (isset(Auth::user()->instancia))
                $instancia = Auth::user()->instancia;
            else
                return null;
        }

        $metas = ConfigInstancia::where("instancia", $instancia)->where("clave", $clave)->get();
        foreach ($metas as $meta)
            return $meta;
        return null;
    }

}

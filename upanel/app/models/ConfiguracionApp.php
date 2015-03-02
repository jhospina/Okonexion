<?php

Class ConfiguracionApp extends Eloquent {

    protected $fillable = array('id_aplicacion', 'clave', 'valor');
    public $timestamps = false;
    protected $table = "configuracionApp";

    /**
     * Obtiene el valor de una opcion dada por su dato clave.
     *
     *
     * @param String clave
     * @return String valor
     * @throws Exception Retorna Null si el valor de la clave no existe
     */
    static function obtenerValorConfig($clave) {
        $configs = ConfiguracionApp::where("id_aplicacion", "=", Aplicacion::ID())->where("clave", "=", $clave)->get();
        if (count($configs) > 0) {
            foreach ($configs as $config) {
                return $config->valor;
            }
        } else {
            return null;
        }
    }

    /**
     * Indica si una opcion de configuracion existe, dada por su dato clave. 
     *
     * @param String clave
     * @return boolean
     */
    static function existeConfig($clave) {
        $configs = ConfiguracionApp::where("id_aplicacion", "=", Aplicacion::ID())->where("clave", "=", $clave)->get();
        if (count($configs) > 0)
            return true;
        else
            return false;
    }

    static function esPredeterminado($clave) {
        $configs = ConfiguracionApp::where("id_aplicacion", "=", Aplicacion::ID())->where("clave", "=", $clave)->where("valor", "=", "Predeterminado")->get();
        if (count($configs) > 0)
            return true;
        else
            return false;
    }

    /**
     * Obtiene el numero ID de una opcion dada por su dato clave.  
     *
     * @param String clave
     * @return Int Retorna un numero entero que es el ID de la opcion, de lo contrario NULL
     */
    static function obtenerIdConfigDesdeClave($clave) {
        $configs = ConfiguracionApp::where("id_aplicacion", "=", Aplicacion::ID())->where("clave", "=", $clave)->get();
        if (count($configs) > 0) {
            foreach ($configs as $config) {
                return $config->id;
            }
        } else {
            return null;
        }
    }

    /**
     * Elimina la opciòn de una configuracion dada por su dato clve  
     *
     * @param String clave
     * @return boolean
     */
    static function eliminarConfigDesdeClave($clave) {
        $configs = ConfiguracionApp::where("id_aplicacion", "=", Aplicacion::ID())->where("clave", "=", $clave)->get();
        if (count($configs) > 0) {
            foreach ($configs as $config) {
                if ($config->delete())
                    return true;
                else
                    return false;
            }
        } else {
            return false;
        }
    }

    /**
     * obtiene un array con las claves de configuracion nullables y sus valores por defecto de un diseño de aplicacion.  
     *
     * @param String diseno
     * @return array
     */
    static function obtenerNullables($diseno) {
        switch ($diseno) {
            case App_Metro::sigla:
                return App_Metro::$configDefecto;
                break;
        }
    }

    /**
     * Establece todos los valores vacios de una configuracion a su valor predeterminado  
     *
     * @param Int id de la aplicacion
     * @return void
     */
    static function predeterminarValoresVacios($id_aplicacion) {
        $app = Aplicacion::find($id_aplicacion);

        //Obtiene los valores nullables del diseno de la aplicacion
        $nullables = ConfiguracionApp::obtenerNullables($app->diseno);

        foreach ($nullables as $clave => $valor) {

            if (!ConfiguracionApp::existeConfig($clave)) {
                $config = new ConfiguracionApp;
                $config->id_aplicacion = $id_aplicacion;
                $config->clave = $clave;
                $config->valor = $valor;
                $config->save();
            }
        }
    }

    /**
     * Obtiene una cadena JSON de la configuracion establecida de la aplicacion, dada por la id de la aplicacion  
     *
     * @param Int id de la aplicacion
     * @return JSON
     */
    static function obtenerJSON($id_aplicacion) {
        $app = Aplicacion::find($id_aplicacion);
        $configs = ConfiguracionApp::where("id_aplicacion", "=", $id_aplicacion)->get();

        $json = [];

        $json["nombreApp"] = $app->nombre;
        $json["logoApp"] = $app->url_logo;
        $json["keyApp"] = $app->key_app;
        $json["disenoApp"] = $app->diseno;

        foreach ($configs as $config) {
            $json[$config->clave] = $config->valor;
        }

        return json_encode($json);
    }

}

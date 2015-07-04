<?php

class AppDesing {
    
    const PLATAFORMA_ANDROID="android";
    const PLATAFORMA_IOS="ios";
    const PLATAFORMA_WINDOW="windows";

    /** Obtiene la disponibilidad de una plataforma dado por el ID del diseño
     * 
     * @param type $diseno La sigla del diseño
     * @return type
     */
    public static function obtenerDisponibilidadPlataformas($diseno) {
        switch ($diseno) {
            case App_Metro::sigla:
                return App_Metro::plataformas();
                break;
        }
    }

    public static function plantillaConfigAndroid($app, $config) {

        $diseno = (is_string($app)) ? $app : $app->diseno;

        switch ($diseno) {
            case App_Metro::sigla:
                return App_Metro::plantillaConfigAndroid($config);
                break;
        }
    }

    /** Prepara los archivos necesarios del diseño o los agrega a un archivo comprimidio previamente creado
     *  
     * @param type $app
     * @param type $config
     * @return type
     */
    public static function prepararArchivosParaAndroid($zip, $app, $config, $ruta) {
        $diseno = null;
        (is_string($app)) ? $diseno = $app : $diseno = $app->diseno;

        switch ($diseno) {
            case App_Metro::sigla:
                App_Metro::prepararArchivosParaAndroid($zip, $config, $ruta);
                break;
        }
    }

    /** Segun el diseño escogido guarda la configuracion de diseño establecida
     * 
     * @param array $data Los datos enviados por el usuario mediante formulario
     * @param Aplicacion $app Objeto de la aplicacion del usuario 
     * @return Request Dato por el tipo de diseño
     */
    public static function guardarConfigDiseno($data, $app) {
        switch ($app->diseno) {
            case App_Metro::sigla:
                return App_Metro::guardarConfigDiseno($data, $app);
                break;
        }
    }

    /** Guarda los datos de configuracion de los textos de la App, en la base de datos
     * 
     * @param array $data Los datos enviados por el usuario mediante formulario
     * @param Aplicacion $app Objeto de la aplicacion del usuario 
     * @return boolean|array Retorna true en caso de exito, de lo contraro un array con un mensaje de error
     */
    public static function guardarConfigTextos($data, $app) {
        $errores = "<ul>";

        $n = 0;
        //Verifica y valuda los textos
        foreach ($data as $clave => $valor) {
            $n++;
            if (!strlen($valor) > 0)
                $errores .= "<li>" . Util::eliminarPluralidad(trans("interfaz.menu.principal.mi_aplicacion.configuracion.textos")) . " $n</li>";
        }

        if (strlen($errores) > 6)
            return $errores . "</ul>";


        //Guarda la configuracion de los textos
        foreach ($data as $clave => $valor) {
            $n++;
            $config = new ConfiguracionApp;

            ConfiguracionApp::eliminarConfigDesdeClave($clave);

            $config->id_aplicacion = $app->id;
            $config->clave = $clave;
            $config->valor = $valor;
            $config->save();
        }



        return true;
    }

    /** Obtiene los colores de fondo determinados en la interfaz del diseño de una aplicacion 
     * 
     * @return Array Los colores en Hexadecimal
     */
    public static function coloresFondo() /* (OBSOLETO) */ {
        return array("#ff8080", "#FFFF80", "#80FF80", "#00FF80", "#80FFFF", "#0080FF", "#FF80C0", "#FF80FF", "#FF0000", "#FFFF00", "#80FF00", "#00FF40", "#00FFFF", "#0080C0", "#8080C0", "#FF00FF", "#804040", "#FF8040", "#00FF00", "#008080", "#004080", "#8080FF", "#800040", "#FF0080", "#800000", "#FF8000", "#008000", "#008040", "#0000FF", "#0000A0", "#800080", "#8000FF", "#400000", "#804000", "#004000", "#004040", "#000080", "#000040", "#400040", "#400080", "#000000", "#808000", "#808040", "#808080", "#408080", "#C0C0C0", "#400040", "#FFFFFF");
    }

    /** Prepara los requisitos de administracion de la aplicacion
     * 
     * @param Aplicacion $app Modelo de aplicacion del usuario
     */
    public static function prepararRequisitosDeAdministracion($app) {
        switch ($app->diseno) {
            case App_Metro::sigla:
                App_Metro::prepararRequisitosDeAdministracion($app);
                break;
        }
    }

    /** Obtiene la descripción de un diseño de aplicacion
     * 
     * @param type $app
     * @return type
     */
    public static function obtenerDescripcion($app) {

        $diseno = null;
        (is_string($app)) ? $diseno = $app : $diseno = $app->diseno;

        switch ($diseno) {
            case App_Metro::sigla:
                return App_Metro::descripcion();
                break;
        }
    }

    /** Obtiene el texto correspondiente del diseno con el nombre clave de texto dado
     * 
     * @param type $idNombre El nombre clave del texto
     * @return type
     */
    public static function obtenerTextoInfo($idNombre, $app = null) {

        if (is_null($app)) {
            if (Aplicacion::existe())
                $app = Aplicacion::obtener();
            else
                return null;
        }

        $diseno = null;
        (is_string($app)) ? $diseno = $app : $diseno = $app->diseno;

        return trans("diseno/" . $diseno . "." . $idNombre);
    }

    /** Obtiene un array con los nombres claves de los textos info del diseno App 
     * 
     * @return type
     */
    public static function obtenerNombresTextoInfo($app = null) {

        if (is_null($app)) {
            if (Aplicacion::existe())
                $app = Aplicacion::obtener();
            else
                return null;
        }

        $diseno = null;
        (is_string($app)) ? $diseno = $app : $diseno = $app->diseno;

        switch ($diseno) {
            case App_Metro::sigla:
                return App_Metro::obtenerNombresTextoInfo();
                break;
        }
    }

    /** Carga los datos JSON de los tipos de contenido de diseño de app
     * 
     * @param Aplicacion $app 
     * @return JSON
     */
    public static function cargarDatosJson($app) {
        switch ($app->diseno) {
            case App_Metro::sigla:
                return App_Metro::cargarDatosJson($app->id);
                break;
        }
    }

}

<?php

class AppDesing {

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

    /** Obtiene los colores de fondo determinados en la interfaz del diseño de una aplicacion 
     * 
     * @return Array Los colores en Hexadecimal
     */
    public static function coloresFondo() /*(OBSOLETO)*/ {
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

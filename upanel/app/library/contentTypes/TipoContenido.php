<?php

class TipoContenido {

    /** Obtiene un array indicando que contenidos tiene el diseño de aplicación escogido
     * 
     * @param String $diseno La sigla indicando el diseño de la aplicacion
     * @return Array Retorna un array con los tipos de contenidos del diseño
     */
    static function obtenerTiposContenidoDelDiseno($diseno) {
        switch ($diseno) {
            //Diseño de Metro
            case App_Metro::sigla:
                return App_Metro::$tipos_contenidos;
                break;
        }
    }

    /** Obtiene el nombre del tipo de contenido dado por el usuario en el diseño de la aplicacion
     * 
     * @param String $diseno La sigla indicando el diseño de la aplicacion
     * @param String $tipo El nombre del tipo de contenido
     * @return String Retorna el nombre del tipo de contenido dado por el usuario en caso de exito, de lo contrario retorna Null
     */
    static function obtenerNombre($diseno, $tipo) {
        switch ($diseno) {
            case App_Metro::sigla:
                return App_Metro::obtenerNombreTipoContenidoPorUsuario($tipo);
                break;
        }

        return null;
    }

    /** Obtiene el nombre de la clase Bootstrap icono representativo del tipo de contenido
     * 
     * @param String $tipo El nombre del tipo de contenido
     * @return String Retorna el nombre de la clase del icono que representar el tipo de contenido
     */
    static function obtenerIcono($tipo) {
        switch ($tipo) {
            case Contenido_Institucional::nombre:
                return Contenido_Institucional::icono;
                break;
            case Contenido_Noticias::nombre:
                return Contenido_Noticias::icono;
                break;
            case Contenido_Encuestas::nombre:
                return Contenido_Encuestas::icono;
                break;
            case Contenido_PQR::nombre:
                return Contenido_PQR::icono;
                break;
        }
    }

}

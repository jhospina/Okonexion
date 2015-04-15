<?php

class Contenido_PQR {

    const nombre = "pqr";
    const icono = "glyphicon-info-sign";
    
     /** Obtiene el nombre por defecto de este tipo de contenido
     * 
     * @return type
     */
    static function nombreDefecto(){
        return trans("app.tipo.contenido.pqr");
    }

}

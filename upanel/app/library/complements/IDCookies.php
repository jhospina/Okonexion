<?php

class IDCookies {

    //Verifica si el usuario ya vio el mensaje inicial al iniciar sesión del periodo de prueba. 
    const MSJ_INICIAL_PERIODO_PRUEBA = "msj_inicial_periodo_prueba";

    /** Retorna el tiempo indicado para una Cookie
     * 
     * @param type $id
     * @return type
     */
    static function duracion($id) {
        $duracion = array(
            IDCookies::MSJ_INICIAL_PERIODO_PRUEBA => (60 * 24 * 30)// 30 dias
        );
        return $duracion[$id];
    }

    /** Indica si una cookie existe
     * 
     * @param type $id
     * @return type
     */
    static function existe($id) {
        return (Cookie::get($id) !== false);
    }

}

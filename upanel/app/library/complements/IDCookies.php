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
            IDCookies::MSJ_INICIAL_PERIODO_PRUEBA => (60 * 24)// 1 dia
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

    /** Elimina una cookie dada por su ID
     * 
     * @param type $id
     * @return type
     */
    static function eliminar($id) {
        return Cookie::forget($id);
    }

}

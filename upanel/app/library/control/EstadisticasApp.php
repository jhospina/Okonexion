<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EstadisticasApp
 *
 * @author Jhon
 */
class EstadisticasApp {

    const reg_instalacion = "instalacion_";

    /** Obtiene el numero total de instalaciones de la aplicacion
     * 
     * @param Int $id_app El id de la aplicacion
     * @return Int
     */
    static function obtenerTotalInstalaciones($id_app) {
        return AppMeta::where("id_app", $id_app)->where("clave", "LIKE", EstadisticasApp::reg_instalacion . "%")->get()->count();
    }

    /** Obtiene el numero de instalaciones de un dia especifico
     * 
     * @param Int $id_app El id de la aplicacion
     * @param Array $fecha [null] array(ano,mes,dia). Si no se define se tomara el dia actual (Hoy) 
     * @return int
     */
    static function obtenerNumeroInstalacionesPorDia($id_app, $fecha = null) {
        if (is_null($fecha))
            return AppMeta::where("id_app", $id_app)->where("clave", "LIKE", EstadisticasApp::reg_instalacion . "%")->where("valor", "LIKE", date("Y-m-d") . "%")->get()->count();
        if (is_array($fecha))
            return AppMeta::where("id_app", $id_app)->where("clave", "LIKE", EstadisticasApp::reg_instalacion . "%")->where("valor", "LIKE", $fecha[0] . "-" . $fecha[1] . "-" . $fecha[2] . "%")->get()->count();

        return null;
    }

    /** Obtiene la cantidad de actividad de un dia especifico
     * 
     * @param Int $id_app El id de la aplicacion
     * @param Array $fecha [null] array(ano,mes,dia). Si no se define se tomara el dia actual (Hoy) 
     * @return int
     */
    static function obtenerActividadPorDia($id_app, $fecha = null) {
        if (is_null($fecha))
            $regActividad = AppMeta::where("id_app", $id_app)->where("clave", "LIKE", "%" . date("Ymd"))->orderBy("id_app", "DESC")->get();

        if (is_array($fecha))
            $regActividad = AppMeta::where("id_app", $id_app)->where("clave", "LIKE", "%" . $fecha[0] . $fecha[1] . $fecha[2])->orderBy("id_app", "DESC")->get();

        $cantActividad = 0;
        $aux = null;

        foreach ($regActividad as $actividad) {

            $parts = explode("_", $actividad->clave);

            if ($aux != $parts[0]) {
                $cantActividad++;
                $aux = $parts[0];
            }
        }

        return $cantActividad;
    }

}

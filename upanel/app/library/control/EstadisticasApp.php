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
    //Rangos
    const select_rang_tmp_inst = "tmp_inst"; //Instalaciones
    const select_tipoGrafica_inst = "tp_graph_inst";
    const select_interval_tmp_inst = "interval_inst"; // Intervalos de tiempo
    const select_rang_tmp_actividad = "tmp_act"; //Actividad
    const select_tipoGrafica_actividad = "tp_graph_act";
    const select_interval_tmp_actividad = "interval_ct"; // Intervalos de tiempo
    //Tipos de graficos
    const tgraf_lineal = "lineal";
    const tgraf_barras = "barras";
    const tgraf_radar = "radar";
    const tgraf_polar = "polar";
    const tgraf_pie = "pie"; //Torta
    const tgraf_dona = "dona";

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

    
    static function obtenerActividadEspecificaPorDia($id_app,$tipo_contenido,$fecha=null){
         if (is_null($fecha))
            $regActividad = AppMeta::where("id_app", $id_app)->where("clave", "LIKE", "%_".$tipo_contenido."_". date("Ymd"))->orderBy("id_app", "DESC")->get()->count();

        if (is_array($fecha))
            $regActividad = AppMeta::where("id_app", $id_app)->where("clave", "LIKE", "%_".$tipo_contenido."_". $fecha[0] . $fecha[1] . $fecha[2])->orderBy("id_app", "DESC")->get()->count();

     
        return $regActividad;
    }
    
    /** Obtiene un array indican el eje X y Y de una grafica de instalaciones por fecha. 
     * 
     * @param type $app Objeto de App
     * @param type $instalaciones_hoy [Null] Indica el numero de instalaciones de la fecha actual
     * @return type
     */
    static function obtenerValoresGrafica_Instalaciones($app, $instalaciones_hoy = null) {

        $fecha = new Fecha(Util::obtenerTiempoActual());

        //Rango de tiempo - Instalaciones
        $tmp = (isset($_GET[EstadisticasApp::select_rang_tmp_inst])) ? $_GET[EstadisticasApp::select_rang_tmp_inst] : 7;
        //Intervalos de tiempo - Instalaciones
        $interval_tmp = (isset($_GET[EstadisticasApp::select_interval_tmp_inst])) ? $_GET[EstadisticasApp::select_interval_tmp_inst] : 1;

        //Almacena las estadisticas de instalaciones
        $ejeY = array();
        $ejeY[] = (is_null($instalaciones_hoy)) ? EstadisticasApp::obtenerNumeroInstalacionesPorDia($app->id) : $instalaciones_hoy;

        $ejeX = array();

        if ($interval_tmp == 1)
            $ejeX[] = $fecha->dia . " " . Util::obtenerNombreMes(($fecha->mes < 10) ? "0" . $fecha->mes : $fecha->mes);


        //Carga el array con el rango de tiempo indicado para las instalaciones
        $stat = 0; //Acumula los datos de estadisticas por dia
        $contador_interval = 0; //Contador de control de intervalo

        for ($i = 1; $i < $tmp; $i++) {
            $fecha = new Fecha(Util::obtenerTiempoActual());
            $fecha->sustraerDias($i);
            $mes = Fecha::adecuarNumero($fecha->mes);
            $dia = Fecha::adecuarNumero($fecha->dia);

            $stat+=EstadisticasApp::obtenerNumeroInstalacionesPorDia($app->id, array($fecha->ano, $mes, $dia));
            $contador_interval++;
            //Agrega al arreglo del Ejex el valor correspodiente indicado
            if ($contador_interval >= $interval_tmp) {
                if ($interval_tmp == 1)
                    $ejeX[] = $fecha->dia . " " . Util::obtenerNombreMes($mes);
                else if ($interval_tmp == 7)
                    $ejeX[] = ceil($fecha->dia / 7) . "° " . trans("otros.time.semana") . " " . Util::obtenerNombreMes($mes);
                else if ($interval_tmp == 30)
                    $ejeX[] = Util::obtenerNombreMes($mes);
                $ejeY[] = $stat;
                $stat = 0;
                $contador_interval = 0;
            }
        }
        if (!count($ejeX) > 0) {
            $ejeX[] = Util::obtenerNombreMes($mes);
            $ejeY[] = $stat;
        }

        $ejeX = array_reverse($ejeX);
        $ejeY = array_reverse($ejeY);

        return array($ejeX, $ejeY);
    }

     /** Obtiene un array indican el eje X y Y de una grafica de actividad por fecha. 
     * 
     * @param type $app Objeto de App
     * @param type $actividad_hoy [Null] Indica el cantidad de la fecha actual
     * @return type
     */
    static function obtenerValoresGrafica_Actividad($app, $actividad_hoy=null) {

        $fecha = new Fecha(Util::obtenerTiempoActual());

        //Rango de tiempo - Instalaciones
        $tmp = (isset($_GET[EstadisticasApp::select_rang_tmp_actividad])) ? $_GET[EstadisticasApp::select_rang_tmp_actividad] : 7;
        //Intervalos de tiempo - Instalaciones
        $interval_tmp = (isset($_GET[EstadisticasApp::select_interval_tmp_actividad])) ? $_GET[EstadisticasApp::select_interval_tmp_actividad] : 1;

        //Almacena las estadisticas de instalaciones
        $ejeY = array();
        $ejeY[] = (is_null($actividad_hoy)) ? EstadisticasApp::obtenerActividadPorDia($app->id) : $actividad_hoy;


        $ejeX = array();

        if ($interval_tmp == 1)
            $ejeX[] = $fecha->dia . " " . Util::obtenerNombreMes(($fecha->mes < 10) ? "0" . $fecha->mes : $fecha->mes);


        //Carga el array con el rango de tiempo indicado para las instalaciones
        $stat = 0; //Acumula los datos de estadisticas por dia
        $contador_interval = 0; //Contador de control de intervalo

        for ($i = 1; $i < $tmp; $i++) {
            $fecha = new Fecha(Util::obtenerTiempoActual());
            $fecha->sustraerDias($i);
            $mes = Fecha::adecuarNumero($fecha->mes);
            $dia = Fecha::adecuarNumero($fecha->dia);

            $stat+=EstadisticasApp::obtenerActividadPorDia($app->id, array($fecha->ano, $mes, $dia));
            $contador_interval++;
            //Agrega al arreglo del Ejex el valor correspodiente indicado
            if ($contador_interval >= $interval_tmp) {
                if ($interval_tmp == 1)
                    $ejeX[] = $fecha->dia . " " . Util::obtenerNombreMes($mes);
                else if ($interval_tmp == 7)
                    $ejeX[] = ceil($fecha->dia / 7) . "° " . trans("otros.time.semana") . " " . Util::obtenerNombreMes($mes);
                else if ($interval_tmp == 30)
                    $ejeX[] = Util::obtenerNombreMes($mes);
                $ejeY[] = $stat;
                $stat = 0;
                $contador_interval = 0;
            }
        }

        if (!count($ejeX) > 0) {
            $ejeX[] = Util::obtenerNombreMes($mes);
            $ejeY[] = $stat;
        }

        $ejeX = array_reverse($ejeX);
        $ejeY = array_reverse($ejeY);

        return array($ejeX, $ejeY);
    }

    /** Retorna una lista de seleccion de rangos de tiempo con accion inmediata
     *  
     * @param type $id
     * @param type $class
     * @return string
     */
    static function html_select_tiempo($id, $class = null) {
        $html = "<a name='targ-$id'></a>";
        $html .= "<select class='form-control $class' id='$id'>";
        $html.="<option value='7'";
        $html.=(isset($_GET[$id]) && $_GET[$id] == 7) ? " selected" : "";
        $html.= ">" . trans("otros.fecha.rango.7.dias") . "</option>";
        $html.="<option value='30'";
        $html.=(isset($_GET[$id]) && $_GET[$id] == 30) ? " selected" : "";
        $html.= ">" . trans("otros.fecha.rango.30.dias") . "</option>";
        $html.="<option value='90'";
        $html.=(isset($_GET[$id]) && $_GET[$id] == 90) ? " selected" : "";
        $html.= ">" . trans("otros.fecha.rango.90.dias") . "</option>";
        $html.="<option value='180'";
        $html.=(isset($_GET[$id]) && $_GET[$id] == 180) ? " selected" : "";
        $html.= ">" . trans("otros.fecha.rango.180.dias") . "</option>";
        $html.="<option value='365'";
        $html.=(isset($_GET[$id]) && $_GET[$id] == 365) ? " selected" : "";
        $html.= ">" . trans("otros.fecha.rango.365.dias") . "</option>";

        $html.="</select>";

        $html.="<script>$('#$id').change(function(){" .
                "if($( '#$id option:selected').val()==7)"
                . "location.href='" . Util::Url_AgregarVariable(Util::obtenerUrlActual(), $id, 7) . "#$id" . "';"
                . "if($( '#$id option:selected').val()==30)"
                . "location.href='" . Util::Url_AgregarVariable(Util::obtenerUrlActual(), $id, 30) . "#$id" . "';"
                . "if($( '#$id option:selected').val()==90)"
                . "location.href='" . Util::Url_AgregarVariable(Util::obtenerUrlActual(), $id, 90) . "#$id" . "';"
                . "if($( '#$id option:selected').val()==180)"
                . "location.href='" . Util::Url_AgregarVariable(Util::obtenerUrlActual(), $id, 180) . "#$id" . "';"
                . "if($( '#$id option:selected').val()==365)"
                . "location.href='" . Util::Url_AgregarVariable(Util::obtenerUrlActual(), $id, 365) . "#$id" . "';"
                . "});</script>";
        return $html;
    }

    static function html_select_tipoGrafica($id, $class = null) {

        $tp_lineal = EstadisticasApp::tgraf_lineal;
        $tp_barras = EstadisticasApp::tgraf_barras;

        $html = "<a name='targ-$id'></a>";
        $html .= "<select class='form-control $class' id='$id'>";
        $html.="<option value='$tp_lineal'";
        $html.=(isset($_GET[$id]) && $_GET[$id] == $tp_lineal) ? " selected" : "";
        $html.= ">" . trans("app.estadisticas.tipo.grafica.lineal") . "</option>";
        $html.="<option value='$tp_barras'";
        $html.=(isset($_GET[$id]) && $_GET[$id] == $tp_barras) ? " selected" : "";
        $html.= ">" . trans("app.estadisticas.tipo.grafica.barras") . "</option>";
        $html.="</select>";

        $html.="<script>$('#$id').change(function(){" .
                "if($( '#$id option:selected').val()=='$tp_lineal')"
                . "location.href='" . Util::Url_AgregarVariable(Util::obtenerUrlActual(), $id, $tp_lineal) . "#$id" . "';"
                . "if($( '#$id option:selected').val()=='$tp_barras')"
                . "location.href='" . Util::Url_AgregarVariable(Util::obtenerUrlActual(), $id, $tp_barras) . "#$id" . "';"
                . "});</script>";

        return $html;
    }

    static function html_select_interval($id, $class = null) {
        $html = "<a name='targ-$id'></a>";
        $html .= "<select class='form-control $class' id='$id'>";
        $html.="<option value='1'";
        $html.=(isset($_GET[$id]) && $_GET[$id] == 1) ? " selected" : "";
        $html.= ">" . trans("otros.time.dias") . "</option>";
        $html.="<option value='7'";
        $html.=(isset($_GET[$id]) && $_GET[$id] == 7) ? " selected" : "";
        $html.= ">" . trans("otros.time.semanas") . "</option>";
        $html.="<option value='30'";
        $html.=(isset($_GET[$id]) && $_GET[$id] == 30) ? " selected" : "";
        $html.= ">" . trans("otros.time.meses") . "</option>";
        $html.="</select>";


        $html.="<script>$('#$id').change(function(){" .
                "if($( '#$id option:selected').val()=='1')"
                . "location.href='" . Util::Url_AgregarVariable(Util::obtenerUrlActual(), $id, 1) . "#$id" . "';"
                . "if($( '#$id option:selected').val()=='7')"
                . "location.href='" . Util::Url_AgregarVariable(Util::obtenerUrlActual(), $id, 7) . "#$id" . "';"
                . "if($( '#$id option:selected').val()=='30')"
                . "location.href='" . Util::Url_AgregarVariable(Util::obtenerUrlActual(), $id, 30) . "#$id" . "';"
                . "});</script>";

        return $html;
    }

}

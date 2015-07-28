<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UPanelControladorEstadisticas
 *
 * @author Jhon
 */
class UPanelControladorEstadisticas extends Controller {

    function ajax_appTotalInstalaciones($id_app) {
        return EstadisticasApp::obtenerTotalInstalaciones($id_app);
    }

    function ajax_appInstalacionesHoy($id_app) {
        return EstadisticasApp::obtenerNumeroInstalacionesPorDia($id_app);
    }

    function ajax_appActividadHoy($id_app) {
        return EstadisticasApp::obtenerActividadPorDia($id_app);
    }

}

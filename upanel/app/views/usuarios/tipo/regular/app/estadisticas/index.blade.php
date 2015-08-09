@extends('interfaz/plantilla')

@section("titulo"){{trans("interfaz.menu.principal.mi_aplicacion.estadisticas")}} {{$app->nombre}} @stop

@section("css")

{{ HTML::style('assets/css/upanel/estadisticas.css', array('media' => 'screen')) }}



@stop

@section("contenido")  



<h1><span class="glyphicon glyphicon-stats"></span> {{trans("interfaz.menu.principal.mi_aplicacion.estadisticas")}} {{$app->nombre}}</h1>
<hr>
@include("usuarios/tipo/regular/app/estadisticas/secciones/nav",array("app"=>$app))
<hr>
<div class="col-lg-12" style="margin-top:30px;">
    <div class="col-lg-6 estadistica">
        <span class="glyphicon glyphicon-phone"></span> {{trans("app.estadisticas.nombre.instalaciones.total")}}</div>
    <div class="col-lg-6 cant">{{$instalaciones}}</div>
    <div class="col-lg-6 estadistica">
        <span class="glyphicon glyphicon-phone"></span> {{trans("app.estadisticas.nombre.instalaciones.hoy")}}</div>
    <div class="col-lg-6 cant">{{$instalaciones_hoy}}</div>
    <div class="col-lg-6 estadistica">
        <span class="glyphicon glyphicon-magnet"></span> {{trans("app.estadisticas.nombre.actividad.hoy")}}</div>
    <div class="col-lg-6 cant">{{$actividad}}</div>
</div>

@include("usuarios/tipo/regular/app/estadisticas/avanzado",array("app"=>$app))

<div class="col-lg-12" style="margin-top: 40px;margin-bottom: 40px;">
    <div class="col-lg-12 content-stats">
        <div class="col-lg-12 text-uppercase text-center"><h3>{{trans("app.estadisticas.grafica.instalaciones")}}</h3></div>
        <div class="col-lg-4">{{EstadisticasApp::html_select_tiempo(EstadisticasApp::select_rang_tmp_inst)}}</div>
        <div class="col-lg-4">{{EstadisticasApp::html_select_interval(EstadisticasApp::select_interval_tmp_inst)}}</div>
        <div class="col-lg-4">{{EstadisticasApp::html_select_tipoGrafica(EstadisticasApp::select_tipoGrafica_inst)}}</div>
        <div class="col-lg-1"></div>
        <div class="col-lg-10"><canvas id="canvas-instalaciones" width="900px" height="350px"></canvas></div>
        <div class="col-lg-1"></div>
    </div>
    <div class="col-lg-12 content-stats">
        <div class="col-lg-12 text-uppercase text-center"><h3>{{trans("app.estadisticas.grafica.actividad")}}</h3></div>
        <div class="col-lg-4">{{EstadisticasApp::html_select_tiempo(EstadisticasApp::select_rang_tmp_actividad)}}</div>
        <div class="col-lg-4">{{EstadisticasApp::html_select_interval(EstadisticasApp::select_interval_tmp_actividad)}}</div>
        <div class="col-lg-4">{{EstadisticasApp::html_select_tipoGrafica(EstadisticasApp::select_tipoGrafica_actividad)}}</div>
        <div class="col-lg-1"></div>
        <div class="col-lg-10"><canvas id="canvas-actividad" width="900px" height="350px"></canvas></div>
        <div class="col-lg-1"></div>
    </div>

</div>

@stop


@section("script")

{{ HTML::script('assets/plugins/chart/Chart.js') }}


<script>
    //INSTALACIONES
    var dataEjeXInstal = [<?php echo Util::formatearResultadosArray($ejeX_instal, ",", "'", "'"); ?>];
    var dataRegsInstal = [<?php echo Util::formatearResultadosArray($stat_instalaciones, ","); ?>];
    var tgInstal = "<?php echo (isset($_GET[EstadisticasApp::select_tipoGrafica_inst])) ? $_GET[EstadisticasApp::select_tipoGrafica_inst] : EstadisticasApp::tgraf_lineal; ?>";
    //ACTIVIDAD
    var dataEjeXActividad = [<?php echo Util::formatearResultadosArray($ejeX_act, ",", "'", "'"); ?>];
    var dataRegsActividad = [<?php echo Util::formatearResultadosArray($stat_actividad, ","); ?>];
    var tgAct = "<?php echo (isset($_GET[EstadisticasApp::select_tipoGrafica_actividad])) ? $_GET[EstadisticasApp::select_tipoGrafica_actividad] : EstadisticasApp::tgraf_lineal; ?>";


</script>

{{ HTML::script('assets/jscode/stats-app.js') }}

@stop
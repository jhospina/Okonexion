@extends('interfaz/plantilla')

@section("titulo"){{trans("interfaz.menu.principal.mi_aplicacion.estadisticas")}} {{$app->nombre}} @stop

@section("css")

<style>

    .col-lg-6.estadistica,.col-lg-6.cant{
        padding: 20px;
        font-size: 20pt;
        border: 1px black solid;
        height: 80px;
    }

    .col-lg-6.estadistica{
        background-color: slategrey;color: white;
    }

    .col-lg-6.cant{
        font-family: fantasy;
        background-color: white;
        padding: 10px;
        font-size: 40px;
    }

</style>

@stop

@section("contenido")  

<h1><span class="glyphicon glyphicon-stats"></span> {{trans("interfaz.menu.principal.mi_aplicacion.estadisticas")}} {{$app->nombre}}</h1>
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

<div class="col-lg-12" style="margin-top: 40px;margin-bottom: 40px;">
    <div class="col-lg-6">
        <div class="col-lg-12 text-uppercase text-center"><h3>{{trans("app.estadisticas.grafica.instalaciones")}}</h4></div>
        <canvas id="canvas-instalaciones" style="height:250px;" class="col-lg-12"></canvas>
    </div>
    <div class="col-lg-6">
        <div class="col-lg-12 text-uppercase text-center"><h3>{{trans("app.estadisticas.grafica.actividad")}}</h4></div>
        <canvas id="canvas-actividad" style="height:250px;" class="col-lg-12"></canvas>
    </div>

</div>

@stop


@section("script")

{{ HTML::script('assets/plugins/chart/Chart.js') }}


<script>

    var dataInstalaciones = {
        labels: [<?php echo Util::formatearResultadosArray($dias, ",", "'", "'"); ?>],
        datasets: [
            {
                fillColor: "#A9A9A9",
                strokeColor: "black",
                highlightFill: "#808080",
                highlightStroke: "black",
                data: [<?php echo Util::formatearResultadosArray($stat_instalaciones, ","); ?>]
            }]
    }
    
    
    var dataActividad = {
        labels: [<?php echo Util::formatearResultadosArray($dias, ",", "'", "'"); ?>],
        datasets: [
            {
                fillColor: "#A9A9A9",
                strokeColor: "black",
                highlightFill: "#808080",
                highlightStroke: "black",
                data: [<?php echo Util::formatearResultadosArray($stat_actividad, ","); ?>]
            }]
    }
    
</script>


<script>
    
    window.onload = function () {
        var ctx = document.getElementById("canvas-actividad").getContext("2d");
        var ctx2 = document.getElementById("canvas-instalaciones").getContext("2d");

        window.myBar = new Chart(ctx).Bar(dataActividad, {
            responsive: true,
            barStrokeWidth: 2,
            scaleGridLineWidth: 1
        });

        window.myBar = new Chart(ctx2).Bar(dataInstalaciones, {
            responsive: true,
            barStrokeWidth: 2,
            scaleGridLineWidth: 1
        });
    }


</script>

@stop
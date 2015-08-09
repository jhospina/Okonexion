@extends('interfaz/plantilla')

@section("titulo"){{trans("app.estadisticas.titulo.seguimiento.especifico")}} {{$app->nombre}} @stop

@section("css")

{{ HTML::style('assets/css/upanel/estadisticas.css', array('media' => 'screen')) }}

@stop

@section("contenido")  

<h1><span class="glyphicon glyphicon-stats"></span> {{trans("app.estadisticas.titulo.seguimiento.especifico")}} {{$app->nombre}}</h1>
<hr>
@include("usuarios/tipo/regular/app/estadisticas/secciones/nav",array("app"=>$app))
<hr>

<div class="col-lg-12"><h2>{{trans("app.estadisticas.nombre.actividad.hoy")}}</h2></div>
<div class="col-lg-12">
    <?php for ($i = 0; $i < count($tipos_contenidos); $i++): if (!TipoContenido::estaActivo($tipos_contenidos[$i])) continue; ?>
        <div class="col-lg-6 estadistica esp">
            <span class="glyphicon {{TipoContenido::obtenerIcono($tipos_contenidos[$i])}}"></span> {{TipoContenido::obtenerNombre($app->diseno, $tipos_contenidos[$i])}}</div>
        <div class="col-lg-6 cant"><?php print(${"act_" . $tipos_contenidos[$i]}); ?></div>
    <?php endfor; ?>
</div>


<div class="col-lg-12" style="margin-top: 40px;margin-bottom: 40px;">

    <?php
    for ($i = 0; $i < count($tipos_contenidos); $i++):
        if (!TipoContenido::estaActivo($tipos_contenidos[$i]))
            continue;
        ${"ejes_" . $tipos_contenidos[$i]} = EstadisticasApp::histogramaDeActividadEspecifica($app, $tipos_contenidos[$i]);
        ?>

        <div class="col-lg-12 content-stats">
            <div class="col-lg-12 text-uppercase text-center"><h3><span class="glyphicon {{TipoContenido::obtenerIcono($tipos_contenidos[$i])}}"></span> {{TipoContenido::obtenerNombre($app->diseno, $tipos_contenidos[$i])}}</h3></div>
            <div class="col-lg-1"></div>  
            <div class="col-lg-10">
                <?php echo GraficoEstadistico::barras(${"ejes_" . $tipos_contenidos[$i]}[0], ${"ejes_" . $tipos_contenidos[$i]}[1], "act_" . $tipos_contenidos[$i], null, 300, null, true); ?>
            </div>
            <div class="col-lg-1"></div> 
        </div>

    <?php endfor; ?>

</div>


@stop


@section("script")

{{ HTML::script('assets/plugins/chart/Chart.js') }}

@stop
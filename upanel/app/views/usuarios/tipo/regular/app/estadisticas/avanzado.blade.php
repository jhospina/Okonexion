<?php
$act_noticias = EstadisticasApp::obtenerActividadEspecificaPorDia($app->id, Contenido_Noticias::nombre);
?>

<div class="col-lg-12"><h2>{{trans("app.estadisticas.titulo.actividad.especifica")}}</h2></div>
<div class="col-lg-12">
        <div class="col-lg-6 estadistica esp">
            <span class="glyphicon {{TipoContenido::obtenerIcono(Contenido_Institucional::nombre)}}"></span> {{TipoContenido::obtenerNombre($app->diseno, Contenido_institucional::nombre)}}</div>
        <div class="col-lg-6 cant">{{$act_noticias}}</div>
        <div class="col-lg-6 estadistica esp">
            <span class="glyphicon {{TipoContenido::obtenerIcono(Contenido_Noticias::nombre)}}"></span> {{TipoContenido::obtenerNombre($app->diseno, Contenido_Noticias::nombre)}}</div>
        <div class="col-lg-6 cant">{{$act_noticias}}</div>
        <div class="col-lg-6 estadistica esp">
            <span class="glyphicon {{TipoContenido::obtenerIcono(Contenido_Encuestas::nombre)}}"></span> {{TipoContenido::obtenerNombre($app->diseno, Contenido_Encuestas::nombre)}}</div>
        <div class="col-lg-6 cant">{{$act_noticias}}</div>
        <div class="col-lg-6 estadistica esp">
            <span class="glyphicon {{TipoContenido::obtenerIcono(Contenido_PQR::nombre)}}"></span> {{TipoContenido::obtenerNombre($app->diseno, Contenido_PQR::nombre)}}</div>
        <div class="col-lg-6 cant">{{$act_noticias}}</div>
</div>



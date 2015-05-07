<?php
$progreso = 5;
$texto = trans("atributos.estado.app.sin_iniciar");
$classColor = "progress-bar-danger";

if (Aplicacion::existe()) {
    switch ($app->estado) {
        //En Construcciòn
        case Aplicacion::ESTADO_EN_DISENO:
            $progreso = 15;
            $texto = Aplicacion::obtenerNombreEstado($app->estado);
            $classColor = "progress-bar-default";
            break;
        case Aplicacion::ESTADO_ESTABLECIENTO_TEXTOS:
            $progreso = 30;
            $texto = Aplicacion::obtenerNombreEstado($app->estado);
            $classColor = "progress-bar-default";
            break;
        case Aplicacion::ESTADO_LISTA_PARA_ENVIAR:
            $progreso = 45;
            $texto = Aplicacion::obtenerNombreEstado($app->estado);
            $classColor = "progress-bar-info-le";
            break;
        case Aplicacion::ESTADO_EN_COLA_PARA_DESARROLLO:
            $progreso = 60;
            $texto = Aplicacion::obtenerNombreEstado($app->estado);
            $classColor = "progress-bar-info";
            break;
        case Aplicacion::ESTADO_EN_DESARROLLO:
            $progreso = 80;
            $texto = Aplicacion::obtenerNombreEstado($app->estado);
            $classColor = "progress-bar-warning";
            break;
        case Aplicacion::ESTADO_TERMINADA:
            $progreso = 100;
            $texto = Aplicacion::obtenerNombreEstado($app->estado);
            $classColor = "progress-bar-success";
            break;
    }
}
?>

<div class="col-lg-12 block">
    <div class="progress" id="progress-app">
        <div id="progress-bar" class="progress-bar {{$classColor}}  active progress-bar-striped" role="progressbar" aria-valuenow="{{$progreso}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$progreso}}%">
            @if($progreso>5)     
            <span class="progress-text in" id="text-progress">&nbsp; {{$progreso}}% ({{$texto}})</span>
            @endif
        </div> 
        @if($progreso==5)     
        <span class="progress-text" id="text-progress">&nbsp; {{$progreso}}% ({{$texto}})</span>
        @endif
    </div>
</div>
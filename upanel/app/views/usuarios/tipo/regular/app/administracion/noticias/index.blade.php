<?php
$tipoContenido = "noticias";
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, $tipoContenido);
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | Administrar {{$nombreContenido}} @stop

@section("contenido") 

<h1>ADMINISTRAR {{strtoupper($nombreContenido)}}</h1>

<div class="well well-sm">
    <a href="{{URL::to("aplicacion/administrar/".$tipoContenido."/agregar")}}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>Agregar nuevo</a>
</div>

@stop

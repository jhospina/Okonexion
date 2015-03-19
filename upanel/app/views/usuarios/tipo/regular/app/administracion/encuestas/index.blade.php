<?php
$tipoContenido = Contenido_Encuestas::nombre;
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, $tipoContenido);
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | Administrar {{$nombreContenido}} @stop

@section("css")
{{ HTML::style('assets/css/upanel/insts.css', array('media' => 'screen')) }}
@stop

@section("contenido") 

<h2><span class="glyphicon {{Contenido_Encuestas::icono}}"></span> ADMINISTRAR {{strtoupper($nombreContenido)}}</h2>
<hr/>
@include("interfaz/mensaje/index",array("id_mensaje"=>2))

<div class="well well-sm" style="margin-top:10px;">
    <a href="{{URL::to("aplicacion/administrar/".$tipoContenido."/agregar")}}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Agregar nuevo</a>
</div>



@stop

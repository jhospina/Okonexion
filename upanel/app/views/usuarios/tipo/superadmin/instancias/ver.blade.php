<?php
$form_data = array('url' => array('instancias/crear'), 'method' => 'POST', 'rol' => 'form');
if (!isset($instancia))
    $instancia = null;
?>
@extends('interfaz/plantilla')

@section("titulo"){{trans("otros.info.instancia")}} - {{$instancia->empresa}} @stop



@section("contenido")  

<h1><span class="glyphicon glyphicon-new-window"></span> {{trans("otros.info.instancia")}} - {{$instancia->empresa}}</h1>

<hr/>

<div class="well well-sm">
    <a href="{{URL::to("instancias/")}}" class="btn btn-primary"><span class="glyphicon glyphicon-inbox"></span> {{trans("interfaz.menu.principal.instancias.indice")}}</a>
         <a href="{{URL::to("instancias/".$instancia->id)."/editar"}}" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span> {{trans("otros.info.editar")}}</a>
  
</div>


@include("interfaz/mensaje/index",array("id_mensaje"=>2))

<table class="table table-striped">
    <tr><th>ID</th><td>{{$instancia->id}}</td></tr>
    <tr><th>{{trans("instancias.dato.empresa")}} </th><td>{{$instancia->empresa}}</td></tr>
    <tr><th>{{trans("instancias.dato.nit")}} </th><td>{{$instancia->nit}}</td></tr>
    <tr><th>{{trans("instancias.dato.web")}}</th><td>{{$instancia->web}}</td></tr>
    <tr><th>{{trans("instancias.dato.nombre_contacto")}}</th><td>{{$instancia->nombre_contacto}}</td></tr>
    <tr><th>{{trans("instancias.dato.email")}}</th><td>{{$instancia->email}}</td></tr>
    <tr><th>{{trans("instancias.dato.pais")}}<td>{{$instancia->pais}}</td></tr>
    <tr><th>{{trans("instancias.dato.region")}}</th><td>{{$instancia->region}}</td></tr>
    <tr><th>{{trans("instancias.dato.ciudad")}} </th><td>{{$instancia->ciudad}}</td></tr>
    <tr><th>{{trans("instancias.dato.codigo_postal")}}</th><td>{{$instancia->codigo_postal}}</td></tr>
    <tr><th>{{trans("instancias.dato.direccion")}}</th><td>{{$instancia->direccion}}</td></tr>
    <tr><th>{{trans("instancias.dato.telefono")}}</th><td>{{$instancia->telefono}}</td></tr>
    <tr><th>{{trans("instancias.dato.celular")}}</th><td></td>{{$instancia->celular}}</tr>
    <tr><th></th><td></td></tr>
    <tr><th style="text-transform: uppercase;"><h3><b>{{trans("instancias.info.usuario.administrador")}}</b> <a title="{{trans("otros.info.ver")}} {{trans("otros.info.usuario")}}" class="tooltip-top" href="{{URL::to("usuario/".$admin->id)}}"><span class="glyphicon glyphicon-user"></span></a></h3></th><td></td></tr>
<tr><th></th><td></td></tr>
<tr><th>{{trans("menu_usuario.mi_perfil.info.nombre")}} </th><td>    {{$admin->nombres}}</td></tr>
<tr><th>{{trans("menu_usuario.mi_perfil.info.apellidos")}} </th><td> {{$admin->apellidos}}</td></tr>
<tr><th>{{trans("menu_usuario.mi_perfil.info.email")}}</th><td> {{$admin->email}}</td></tr>
</table>

<div class="well-lg text-center">

</div>

@stop

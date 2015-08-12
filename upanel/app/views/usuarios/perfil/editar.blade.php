<?php
$form_data = array('route' => array('usuario.update', $usuario->id), 'method' => 'PATCH', 'rol' => 'form');
$paises = Paises::listado();
array_unshift($paises, trans("otros.info.elegir"));
$tipos_documento=User::obtenerTiposDocumento();
?>

@extends('interfaz/plantilla')

@section("titulo"){{trans("menu_usuario.mi_perfil.editar.titulo")}} {{Auth::user()->nombres}}@stop

@section("contenido") 

<h1>{{trans("menu_usuario.mi_perfil.editar.titulo")}}</h1>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))

{{Form::model($usuario, $form_data, array('role' => 'form')) }}

<table class="table table-striped">
    <tr><th>{{trans("menu_usuario.mi_perfil.info.nombre")}} </th><td colspan="2">    {{ Form::text('nombres', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.nombre.placeholder"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.apellidos")}} </th><td colspan="2">{{ Form::text('apellidos', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.apellidos.placeholder"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.email")}}</th><td colspan="2">{{ Form::text('email', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.email.placeholder"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.empresa")}} </th><td colspan="2">{{ Form::text('empresa', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.empresa.placeholder"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.dni")}}</th><td>{{ Form::select('tipo_documento',$tipos_documento, null, array('placeholder' => trans("menu_usuario.mi_perfil.info.pais.placeholder"), 'class' => 'form-control')) }}</td><td>{{ Form::text('numero_identificacion', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.dni.placeholder"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.pais")}}</th><td colspan="2">{{ Form::select('pais',$paises, null, array('placeholder' => trans("menu_usuario.mi_perfil.info.pais.placeholder"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.region")}}</th><td colspan="2">{{ Form::text('region', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.region.placeholder"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.ciudad")}}</th><td colspan="2">{{ Form::text('ciudad', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.ciudad.placeholder"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.direccion")}}</th><td colspan="2">{{ Form::text('direccion', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.direccion.placeholder"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.telefono")}}</th><td colspan="2">{{ Form::text('telefono', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.telefono.placeholder"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.celular")}}</th><td colspan="2">{{ Form::text('celular', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.celular.placeholder"), 'class' => 'form-control')) }}</td></tr>
</table>

<div class="well-lg text-center">
    {{ Form::button(trans("menu_usuario.mi_perfil.editar.submit"), array('type' => 'submit', 'class' => 'btn btn-primary')) }}    
    {{ Form::close() }}
</div>

@stop
<?php
$form_data = array('url' => array('instancias/crear'), 'method' => 'POST', 'rol' => 'form');
if (!isset($instancia))
    $instancia = null;
?>
@extends('interfaz/plantilla')

@section("titulo"){{trans("interfaz.menu.principal.instancias.agregar")}} @stop



@section("contenido")  

<h1><span class="glyphicon glyphicon-new-window"></span> {{trans("interfaz.menu.principal.instancias.agregar")}}</h1>

<hr/>

<div class="well well-sm">
    <a href="{{URL::to("instancias/")}}" class="btn btn-primary"><span class="glyphicon glyphicon-inbox"></span> {{trans("interfaz.menu.principal.instancias.indice")}}</a>
</div>



@include("interfaz/mensaje/index",array("id_mensaje"=>2))

{{Form::model($instancia, $form_data, array('role' => 'form')) }}

<table class="table table-striped">
    <tr><th>{{trans("instancias.dato.empresa")}} </th><td>    {{ Form::text('empresa', null, array('placeholder' => trans("instancias.dato.empresa"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("instancias.dato.nit")}} </th><td>{{ Form::text('nit', null, array('placeholder' => trans("instancias.dato.nit"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("instancias.dato.web")}}</th><td>{{ Form::text('web', null, array('placeholder' => trans("instancias.dato.web"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("instancias.dato.nombre_contacto")}}</th><td>{{ Form::text('nombre_contacto', null, array('placeholder' => trans("instancias.dato.nombre_contacto"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("instancias.dato.email")}}</th><td>{{ Form::text('email', null, array('placeholder' => trans("instancias.dato.email"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("instancias.dato.pais")}}<td>{{ Form::text('pais', null, array('placeholder' => trans("instancias.dato.pais"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("instancias.dato.region")}}</th><td>{{ Form::text('region', null, array('placeholder' => trans("instancias.dato.region"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("instancias.dato.ciudad")}} </th><td>{{ Form::text('ciudad', null, array('placeholder' => trans("instancias.dato.ciudad"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("instancias.dato.codigo_postal")}}</th><td>{{ Form::text('codigo_postal', null, array('placeholder' => trans("instancias.dato.codigo_postal"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("instancias.dato.direccion")}}</th><td>{{ Form::text('direccion', null, array('placeholder' => trans("instancias.dato.direccion"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("instancias.dato.telefono")}}</th><td>{{ Form::text('telefono', null, array('placeholder' => trans("instancias.dato.telefono"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("instancias.dato.celular")}}</th><td>{{ Form::text('celular', null, array('placeholder' => trans("instancias.dato.celular"), 'class' => 'form-control')) }}</td></tr>
    <tr><th></th><td></td></tr>
    <tr><th style="text-transform: uppercase;"><h3><b>{{trans("instancias.info.usuario.administrador")}}</b></h3></th><td></td></tr>
    <tr><th></th><td></td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.nombre")}} </th><td>    {{ Form::text('nombres', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.nombre"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.apellidos")}} </th><td>{{ Form::text('apellidos', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.apellidos"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.email")}}</th><td>{{ Form::text('email_cuenta', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.email"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.escribir_contrasena")}}</th><td>{{ Form::password('password', array('placeholder' => trans("menu_usuario.mi_perfil.info.escribir_contrasena"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.repetir_contrasena")}}</th><td>{{ Form::password('password2', array('placeholder' => trans("menu_usuario.mi_perfil.info.repetir_contrasena"), 'class' => 'form-control')) }}</td></tr>
    
</table>

<div class="well-lg text-center">
    {{ Form::button("<span class=\"glyphicon glyphicon-new-window\"></span> ".trans("menu_usuario.crear.instancia.submit"), array('type' => 'submit', 'class' => 'btn btn-primary')) }}    
    {{ Form::close() }}
</div>

@stop

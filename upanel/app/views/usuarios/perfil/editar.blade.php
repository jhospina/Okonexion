<?php
$form_data = array('route' => array('usuario.update', $usuario->id), 'method' => 'PATCH', 'rol' => 'form');
?>

@extends('interfaz/plantilla')

@section("titulo") Perfil {{Auth::user()->nombres}}@stop

@section("contenido") 

<h1>Editar mi perfil</h1>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))

{{Form::model($usuario, $form_data, array('role' => 'form')) }}

<table class="table table-striped">
    <tr><th>Nombre: </th><td>    {{ Form::text('nombres', null, array('placeholder' => 'Introduce tu nombre', 'class' => 'form-control')) }}</td></tr>
    <tr><th>Apellidos: </th><td>{{ Form::text('apellidos', null, array('placeholder' => 'Introduce tus apellidos', 'class' => 'form-control')) }}</td></tr>
    <tr><th>Correo Electrónico</th><td>{{ Form::text('email', null, array('placeholder' => 'Escribe un correo electrónico', 'class' => 'form-control')) }}</td></tr>
    <tr><th>DNI/NIT</th><td>{{ Form::text('dni', null, array('placeholder' => 'Escribe el número de identificación de ti o de tu empresa', 'class' => 'form-control')) }}</td></tr>
    <tr><th>Empresa: </th><td>{{ Form::text('empresa', null, array('placeholder' => '¿Como se llama la empresa en donde trabajas?', 'class' => 'form-control')) }}</td></tr>
    <tr><th>Pais</th><td>{{ Form::text('pais', null, array('placeholder' => '¿En que pais vives?', 'class' => 'form-control')) }}</td></tr>
    <tr><th>Region/Estado</th><td>{{ Form::text('region', null, array('placeholder' => '¿En que Estado/Region/Departamento te encuestras?', 'class' => 'form-control')) }}</td></tr>
    <tr><th>Ciudad</th><td>{{ Form::text('ciudad', null, array('placeholder' => '¿En que ciudad vives?', 'class' => 'form-control')) }}</td></tr>
    <tr><th>Dirección</th><td>{{ Form::text('direccion', null, array('placeholder' => '¿Cual es tu dirección?', 'class' => 'form-control')) }}</td></tr>
    <tr><th>Teléfono</th><td>{{ Form::text('telefono', null, array('placeholder' => '¿Tienes un telefono fijo?', 'class' => 'form-control')) }}</td></tr>
    <tr><th>Celular</th><td>{{ Form::text('celular', null, array('placeholder' => '¿Cual es tu número de celular?', 'class' => 'form-control')) }}</td></tr>
</table>

<div class="well-lg text-center">
    {{ Form::button("Editar Perfil", array('type' => 'submit', 'class' => 'btn btn-primary')) }}    
    {{ Form::close() }}
</div>

@stop
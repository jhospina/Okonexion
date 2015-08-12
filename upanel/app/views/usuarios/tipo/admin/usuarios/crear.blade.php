<?php

$tipos_documento=User::obtenerTiposDocumento();

$form_data = array('url' => array('usuario/create'), 'method' => 'POST', 'rol' => 'form');
if (!isset($usuario))
    $usuario = null;

$tipos = array();

if (Auth::user()->tipo == User::USUARIO_ADMIN) {
    $tipos[User::USUARIO_REGULAR] = trans("usuario.tipo." . User::USUARIO_REGULAR);
    $tipos[User::USUARIO_SOPORTE_GENERAL] = trans("usuario.tipo." . User::USUARIO_SOPORTE_GENERAL);
    $tipos[User::USUARIO_ADMIN] = trans("usuario.tipo." . User::USUARIO_ADMIN);
} else {
    $tipos[User::USUARIO_REGULAR] = trans("usuario.tipo." . User::USUARIO_REGULAR);
}

?>
@extends('interfaz/plantilla')

@section("titulo"){{trans("interfaz.menu.principal.usuarios.agregar_usuario")}} @stop



@section("contenido")  

<h1><span class="glyphicon glyphicon-user"></span> {{trans("interfaz.menu.principal.usuarios.agregar_usuario")}}</h1>

<hr/>

<div class="well well-sm">
    <a href="{{URL::to("control/usuarios")}}" class="btn btn-primary"><span class="glyphicon glyphicon-th-list"></span> {{trans("interfaz.menu.principal.usuarios.indice")}}</a>
</div>



@include("interfaz/mensaje/index",array("id_mensaje"=>2))

{{Form::model($usuario, $form_data, array('role' => 'form')) }}

<table class="table table-striped">
    <tr><th>{{trans("menu_usuario.mi_perfil.info.nombre")}}* </th><td colspan="2">{{ Form::text('nombres', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.nombre"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.apellidos")}}* </th><td colspan="2">{{ Form::text('apellidos', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.apellidos"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.email")}}*</th><td colspan="2">{{ Form::text('email', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.email"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfi.info.confirmar_email")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans("menu_usuario.mi_perfi.info.confirmar_email.ayuda")))</th><td colspan="2">{{ Form::select('email_confirmado', array("1"=>"No","0"=>"Si"),null, array('class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.dni")}}</th><td>{{ Form::select('tipo_documento',$tipos_documento, null, array('placeholder' => trans("menu_usuario.mi_perfil.info.dni"), 'class' => 'form-control')) }}</td><td>{{ Form::text('numero_identificacion', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.dni"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.empresa")}} </th><td colspan="2">{{ Form::text('empresa', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.empresa"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.pais")}}</th><td colspan="2">{{ Form::text('pais', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.pais"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.region")}}</th><td colspan="2">{{ Form::text('region', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.region"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.ciudad")}}</th><td colspan="2">{{ Form::text('ciudad', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.ciudad"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.direccion")}}</th><td colspan="2">{{ Form::text('direccion', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.direccion"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.telefono")}}</th><td colspan="2">{{ Form::text('telefono', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.telefono"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.celular")}}</th><td colspan="2">{{ Form::text('celular', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.celular"), 'class' => 'form-control')) }}</td></tr>
    <tr><th></th><td></td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.tipo_usuario")}}</th><td>{{ Form::select('tipo', $tipos, null, array('placeholder' => trans("menu_usuario.mi_perfil.info.celular"), 'class' => 'form-control')) }}</td></tr>
    <tr><th></th><td></td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.escribir_contrasena")}}*</th><td>{{ Form::password('password', array('placeholder' => trans("menu_usuario.mi_perfil.info.escribir_contrasena"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.repetir_contrasena")}}*</th><td>{{ Form::password('password2', array('placeholder' => trans("menu_usuario.mi_perfil.info.repetir_contrasena"), 'class' => 'form-control')) }}</td></tr>

</table>

<div class="well-lg text-center">
    {{ Form::button("<span class=\"glyphicon glyphicon-user\"></span> ".trans("menu_usuario.crear.usuario.submit"), array('type' => 'submit', 'class' => 'btn btn-primary')) }}    
    {{ Form::close() }}
</div>

@stop
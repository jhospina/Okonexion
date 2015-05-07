@extends('interfaz/plantilla')

@section("titulo"){{trans("menu_usuario.mi_perfil.titulo")}} {{Auth::user()->nombres}}@stop

@section("contenido") 

<h1>{{trans("menu_usuario.mi_perfil.titulo")}}</h1>
<hr/>
{{-- MAPA DE NAVEGACION --}}
<ol class="breadcrumb">
    <li><a href="{{URL::to("/")}}">{{trans("interfaz.menu.principal.inicio")}}</a></li>
    <li class="active">{{trans("menu_usuario.mi_perfil.titulo")}}</li>
</ol>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))
<table class="table table-striped">
    <tr><th>{{trans("menu_usuario.mi_perfil.info.nombre")}}</th><td>{{Auth::user()->nombres}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.apellidos")}}</th><td>{{Auth::user()->apellidos}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.dni")}}</th><td>{{Auth::user()->dni}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.email")}}</th><td>{{Auth::user()->email}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.empresa")}}</th><td>{{Auth::user()->empresa}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.pais")}}</th><td>{{Auth::user()->pais}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.region")}}</th><td>{{Auth::user()->region}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.ciudad")}}</th><td>{{Auth::user()->ciudad}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.direccion")}}</th><td>{{Auth::user()->direccion}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.telefono")}}</th><td>{{Auth::user()->telefono}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.celular")}}</th><td>{{Auth::user()->celular}}</td></tr>
</table>

<div class="well well-sm">
    <a class="btn-info btn" href="{{URL::Route("usuario.edit",Auth::user()->id)}}">{{trans("menu_usuario.mi_perfil.editar.titulo")}}</a> <a class="btn-default btn" href="{{URL::to("cambiar-contrasena")}}">{{trans("interfaz.menu.usuario.cambiar_contrasena")}}</a>
</div>

@stop
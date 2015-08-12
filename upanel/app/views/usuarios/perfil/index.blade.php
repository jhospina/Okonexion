@extends('interfaz/plantilla')

@section("titulo")

@if($usuario->id==Auth::user()->id)
{{trans("menu_usuario.mi_perfil.titulo")}} {{$usuario->nombres}}
@else
{{$usuario->nombres}} {{$usuario->apellidos}}
@endif
@stop

@section("contenido") 
@if($usuario->id==Auth::user()->id)
<h1>{{trans("menu_usuario.mi_perfil.titulo")}}</h1>
@else
<h1>{{$usuario->nombres}} {{$usuario->apellidos}}</h1>
@endif
<hr/>
{{-- MAPA DE NAVEGACION --}}
<ol class="breadcrumb">
    <li><a href="{{URL::to("/")}}">{{trans("interfaz.menu.principal.inicio")}}</a></li>
    @if($usuario->id==Auth::user()->id)
    <li class="active">{{trans("menu_usuario.mi_perfil.titulo")}}</li>
    @else
    <li class="active">{{$usuario->nombres}} {{$usuario->apellidos}}</li>
    @endif
</ol>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))
<table class="table table-striped">
    <tr><th>{{trans("menu_usuario.mi_perfil.info.nombre")}}</th><td>{{$usuario->nombres}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.apellidos")}}</th><td>{{$usuario->apellidos}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.dni")}}</th><td>{{User::obtenerInfoTipoDocumento($usuario->tipo_documento,true)}} {{$usuario->numero_identificacion}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.email")}}</th><td>{{$usuario->email}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.empresa")}}</th><td>{{$usuario->empresa}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.pais")}}</th><td>{{Paises::obtenerNombre($usuario->pais)}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.region")}}</th><td>{{$usuario->region}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.ciudad")}}</th><td>{{$usuario->ciudad}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.direccion")}}</th><td>{{$usuario->direccion}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.telefono")}}</th><td>{{$usuario->telefono}}</td></tr>
    <tr><th>{{trans("menu_usuario.mi_perfil.info.celular")}}</th><td>{{$usuario->celular}}</td></tr>
</table>

@if($usuario->id==Auth::user()->id)
<div class="well well-sm">
    <a class="btn-info btn" href="{{URL::Route("usuario.edit",$usuario->id)}}">{{trans("menu_usuario.mi_perfil.editar.titulo")}}</a> <a class="btn-default btn" href="{{URL::to("cambiar-contrasena")}}">{{trans("interfaz.menu.usuario.cambiar_contrasena")}}</a>
</div>
@endif

@stop
@extends('interfaz/plantilla')

@section("titulo") Perfil {{Auth::user()->nombres}}@stop

@section("contenido") 

<h1>Mis datos</h1>
<hr/>
{{-- MAPA DE NAVEGACION --}}
<ol class="breadcrumb">
    <li><a href="{{URL::to("/")}}">Inicio</a></li>
    <li class="active">Perfil</li>
</ol>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))
<table class="table table-striped">
    <tr><th>Nombre</th><td>{{Auth::user()->nombres}}</td></tr>
    <tr><th>Apellidos</th><td>{{Auth::user()->apellidos}}</td></tr>
    <tr><th>DNI/NIT</th><td>{{Auth::user()->dni}}</td></tr>
    <tr><th>Correo Electrónico</th><td>{{Auth::user()->email}}</td></tr>
    <tr><th>Empresa</th><td>{{Auth::user()->empresa}}</td></tr>
    <tr><th>Pais</th><td>{{Auth::user()->pais}}</td></tr>
    <tr><th>Region/Estado</th><td>{{Auth::user()->region}}</td></tr>
    <tr><th>Ciudad</th><td>{{Auth::user()->ciudad}}</td></tr>
    <tr><th>Dirección</th><td>{{Auth::user()->direccion}}</td></tr>
    <tr><th>Teléfono</th><td>{{Auth::user()->telefono}}</td></tr>
    <tr><th>Celular</th><td>{{Auth::user()->celular}}</td></tr>
</table>

<div class="well well-sm">
    <a class="btn-info btn" href="{{URL::Route("usuario.edit",Auth::user()->id)}}">Editar mi perfil</a> <a class="btn-default btn" href="{{URL::to("cambiar-contrasena")}}">Cambiar contraseña</a>
</div>

@stop
<?php
$html_loading_ajax = '<div id="loading-ajax" class="text-center"><img src="' . URL::to("assets/img/loaders/gears.gif") . '"/></div>';
?>
@extends('interfaz/plantilla')

@section("titulo"){{trans("interfaz.menu.principal.usuarios.indice")}} @stop


@section("css")
{{ HTML::style('assets/plugins/fileinput/css/fileinput.css', array('media' => 'screen')) }}
{{ HTML::style('assets/plugins/switchery/switchery.css', array('media' => 'screen')) }}
@stop


@section("contenido")  

<h1><span class="glyphicon glyphicon-user"></span> {{trans("interfaz.menu.principal.usuarios.indice")}}</h1>

<hr/>

<div class="well well-sm">
    <a href="{{Route("usuario.create")}}" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> {{trans("interfaz.menu.principal.usuarios.agregar_usuario")}}</a>
</div>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))


<table class="table table-striped table-hover">
    <tr>
        <th>ID</th>
        <th>{{trans("otros.info.nombre")}}</th>
        <th>{{trans("otros.info.apellidos")}}</th>
        <th>{{trans("otros.info.email")}}</th>
        <th>{{trans("otros.info.tipo")}}</th>
        @if(User::esSuperAdmin())
        <th>{{trans("otros.info.instancia")}}</th>
        @endif
        <th>{{trans("app.info.aplicacion.vigente")}}</th>
        <th>{{trans("otros.info.fecha.registro")}}</th>
        <th></th>
    </tr>
    @foreach($usuarios as $usuario)
    <tr onclick="verUsuario($usuario - > id)">
        <td>{{$usuario->id}}</td>
        <td>{{$usuario->nombres}}</td>
        <td>{{$usuario->apellidos}}</td>
        <td>{{$usuario->email}}</td>
        <td>{{trans("usuario.tipo.".$usuario->tipo)}}</td>
        @if(User::esSuperAdmin())


        <td>
            @if($usuario->instancia==User::PARAM_INSTANCIA_SUPER_ADMIN)
            {{trans("instancias.default.super")}}
            @else
            <?php $instancia = Instancia::find($usuario->instancia); ?>
            {{$instancia->empresa}}  
            @endif
        </td>
        @endif

        <td> <?php $app = Aplicacion::buscarPorUsuario($usuario->id); ?>
            @if(is_null($app))
            {{trans("otros.info.ninguna")}}
            @else
            {{$app->nombre}}
            @endif
        </td>

        <td>{{$usuario->created_at}}</td> 
        <th> 
            {{--PARA MOSTRAR LA INFORMACIÓN DEL USUARIO--}}
            <a target="_blank" href="{{Route("usuario.show",$usuario->id)}}" class="tooltip-top" title="{{trans("usuario.info.perfil")}}"><span class="glyphicon glyphicon-user"></span></a>
            {{--PARA MOSTRAR LA INFORMACIÓN DEL ACERCA DE LA APLICACIÓN DEL USUARIO--}}
            @if($usuario->tipo==User::USUARIO_REGULAR)
            <a href="#" class="tooltip-top" title="{{trans("usuario.info.aplicacion")}}"><span class="glyphicon glyphicon-phone"></span></a>
            <a href="#" class="tooltip-top" title="{{trans("usuario.info.facturacion")}}"><span class="glyphicon glyphicon-list-alt"></span></a>
            @endif
        </th>
    </tr>
    @endforeach
</table>

{{$usuarios->links()}}

@stop


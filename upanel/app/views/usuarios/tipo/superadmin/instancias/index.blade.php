<?php
$html_loading_ajax = '<div id="loading-ajax" class="text-center"><img src="' . URL::to("assets/img/loaders/gears.gif") . '"/></div>';
?>
@extends('interfaz/plantilla')

@section("titulo"){{trans("interfaz.menu.principal.instancias.indice")}} @stop


@section("css")
{{ HTML::style('assets/plugins/fileinput/css/fileinput.css', array('media' => 'screen')) }}
{{ HTML::style('assets/plugins/switchery/switchery.css', array('media' => 'screen')) }}
@stop


@section("contenido")  

<h1><span class="glyphicon glyphicon-inbox"></span> {{trans("interfaz.menu.principal.instancias.indice")}}</h1>

<hr/>

<div class="well well-sm">
    <a href="{{URL::to("instancias/crear")}}" class="btn btn-primary"><span class="glyphicon glyphicon-new-window"></span> {{trans("interfaz.menu.principal.instancias.agregar")}}</a>
</div>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))



<table class="table table-striped table-hover">
    <tr>
        <th>ID</th>
        <th>{{trans("instancias.dato.empresa")}}</th>    
        <th>{{trans("instancias.dato.web")}}</th>
        <th>{{trans("instancias.dato.estado")}}</th>
        <th>{{trans("otros.info.tiempo_suscripcion")}}</th>
        <th>{{trans("otros.info.ver")}} / {{trans("otros.info.editar")}}</th>
    </tr>
    @if(count($instancias)>0)
    @foreach($instancias as $instancia)
    <td>{{$instancia->id}}</td>
    <td>{{$instancia->empresa}}</td>    
    <td><a target="_blank" href="{{$instancia->web}}">{{$instancia->web}}</a></td>
    <td>{{trans("instancias.estado.".$instancia->estado)}}</td>
    <td>{{Util::calcularDiferenciaFechas(Util::obtenerTiempoActual(),$instancia->fin_suscripcion)}}</td>
    <td>
        <a href="{{URL::to("instancias/".$instancia->id)}}" class="btn-sm btn-info"><span class="glyphicon glyphicon-eye-open"></span></a>
        <a href="{{URL::to("instancias/".$instancia->id)."/editar"}}" class="btn-sm btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
    </td>
    @endforeach

    @else
    <tr><td colspan="10" class="text-center"><h3>{{trans("otros.info.no_hay_datos")}}</h3></td></tr>
    @endif
</table>



@stop

@section("script")

{{ HTML::script('assets/js/bootstrap-tooltip.js') }}

<script>
    jQuery(document).ready(function () {
        jQuery(".tooltip-left").tooltip({placement: "left"});
        jQuery(".tooltip-top").tooltip({placement: "top"});
    });</script>

@stop
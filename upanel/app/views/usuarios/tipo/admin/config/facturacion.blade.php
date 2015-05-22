<?php
$moneda = Instancia::obtenerValorMetadato(ConfigInstancia::info_moneda);
list($decimales, $sep_millar, $sep_decimal) = Monedas::formato($moneda);
?>

@extends('interfaz/plantilla')

@section("titulo") {{trans("interfaz.menu.principal.config")}}/{{trans("interfaz.menu.principal.config.facturacion")}} @stop


@include("usuarios/tipo/admin/config/secciones/css")



@section("contenido") 

<h1><span class="glyphicon glyphicon-cog"></span> {{trans("interfaz.menu.principal.config")}}</h1>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))

@include("usuarios/tipo/admin/config/secciones/nav")

<div class="col-lg-9" id="content-config">

    <h2 class="text-right"><span class="glyphicon glyphicon-piggy-bank"></span> {{trans("interfaz.menu.principal.config.facturacion")}}</h2>

    <div class="panel panel-primary">
        <div class="panel-heading"></div>
        <div class="panel-body"></div>

    </div>

</div>




@stop

@include("usuarios/tipo/admin/config/secciones/script")

@section("script2")


@stop
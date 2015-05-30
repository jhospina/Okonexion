<?php
$moneda = Instancia::obtenerValorMetadato(ConfigInstancia::info_moneda);
list($decimales, $sep_millar, $sep_decimal) = Monedas::formato($moneda);
?>

@extends('interfaz/plantilla')

@section("titulo") {{trans("interfaz.menu.principal.config")}}/{{trans("interfaz.menu.principal.config.facturacion")}} @stop


@include("usuarios/tipo/admin/config/secciones/css")

@section("css2")

<style>
    .col-lg-6{
        margin-bottom: 10px;
    }
</style>

@stop

@section("contenido") 

<h1><span class="glyphicon glyphicon-cog"></span> {{trans("interfaz.menu.principal.config")}}</h1>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))

@include("usuarios/tipo/admin/config/secciones/nav")

<div class="col-lg-9" id="content-config">

    <h2 class="text-right"><span class="glyphicon glyphicon-piggy-bank"></span> {{trans("interfaz.menu.principal.config.facturacion")}}</h2>

    <form id="form-config" action="{{URL::to("config/post/guardar")}}" method="POST">

        <div class="panel panel-primary">
            <div class="panel-heading">{{trans("config.facturacion.seccion.impuestos.titulo")}}</div>
            <div class="panel-body">
                <div class="col-lg-6">
                    <label> {{trans("config.facturacion.seccion.impuestos.op.iva")}}</label>
                </div>
                <div class="col-lg-6">
                    <input type="text" class="form-control" placeholder="{{trans('config.facturacion.seccion.impuestos.op.iva.placeholder')}}" onkeypress="return soloNumeros(this, '');" name="{{ ConfigInstancia::fact_impuestos_iva; }}" value="{{ Instancia::obtenerValorMetadato(ConfigInstancia::fact_impuestos_iva); }}"/>
                </div>

            </div>
        </div>


        <div class="panel panel-primary">
            <div class="panel-heading">{{trans("config.facturacion.seccion.2co.titulo")}}</div>
            <div class="panel-body">
                <div class="col-lg-6">
                    <label>{{trans("config.facturacion.seccion.2co.idSeller")}}</label>
                </div>
                <div class="col-lg-6">
                    <input type="text" class="form-control" name="{{ ConfigInstancia::fact_2checkout_idSeller; }}" value="{{ Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_idSeller); }}"/>
                </div>
                <div class="col-lg-6">
                    <label>{{trans("config.facturacion.seccion.2co.publicKey")}}</label>
                </div>
                <div class="col-lg-6">
                    <input type="text" class="form-control" name="{{ ConfigInstancia::fact_2checkout_publicKey; }}" value="{{ Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_publicKey); }}"/>
                </div>
                <div class="col-lg-6">
                    <label>{{trans("config.facturacion.seccion.2co.privateKey")}}</label>
                </div>
                <div class="col-lg-6">
                    <input type="text" class="form-control" name="{{ ConfigInstancia::fact_2checkout_privateKey; }}" value="{{ Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_privateKey); }}"/>
                </div>
                <div class="col-lg-6">
                    <label>{{trans("config.facturacion.seccion.2co.sandbox")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans('config.facturacion.seccion.2co.sandbox.ayuda')))</label>
                </div>
                <div class="col-lg-6">
                    <input type="checkbox" class="js-switch" data-for="{{ConfigInstancia::fact_2checkout_sandbox}}" {{HtmlControl::setCheck(Util::convertirIntToBoolean(Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_sandbox)));}}>
                    <input type="hidden" id="{{ConfigInstancia::fact_2checkout_sandbox}}" name="{{ConfigInstancia::fact_2checkout_sandbox}}" value="{{ Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_sandbox); }}"/>
                </div>
            </div>
        </div>

        <div class="text-right">
            <button class="btn btn-info" type="button" id="btn-guardar" type="button"><span class="glyphicon glyphicon-save"></span> {{trans("otros.info.guardar")}}</button>
        </div>
    </form>

</div>




@stop

@include("usuarios/tipo/admin/config/secciones/script")

@section("script2")


@stop
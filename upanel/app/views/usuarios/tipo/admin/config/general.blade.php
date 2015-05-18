@extends('interfaz/plantilla')

@section("titulo") {{trans("interfaz.menu.principal.config")}}/{{trans("interfaz.menu.principal.config.general")}} @stop


@include("usuarios/tipo/admin/config/secciones/css")


@section("contenido") 

<h1><span class="glyphicon glyphicon-cog"></span> {{trans("interfaz.menu.principal.config")}}</h1>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))

@include("usuarios/tipo/admin/config/secciones/nav")

<div class="col-lg-9" id="content-config">

    <h2 class="text-right"><span class="glyphicon glyphicon-cog"></span> {{trans("interfaz.menu.principal.config.general")}}</h2>

    <form id="form-config" action="{{URL::to("config/post/guardar")}}" method="POST">
        <div class="panel panel-primary">
            <div class="panel-heading">{{trans("config.general.seccion.periodo_prueba")}}</div>
            <div class="panel-body">
                {{--ACTIVAR PERIODO DE PRUEBA--}}
                <div class="col-lg-6">
                    <label>{{trans("config.general.seccion.periodo_prueba.op.activar")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans('config.general.seccion.periodo_prueba.op.activar.ayuda')))</label>
                </div>
                <div class="col-lg-6">
                    <input type="checkbox" class="js-switch" data-for="{{ConfigInstancia::periodoPrueba_activado}}" <?php print HtmlControl::setCheck(Util::convertirIntToBoolean(Instancia::obtenerValorMetadato(ConfigInstancia::periodoPrueba_activado))); ?>>
                    <input type="hidden" id="{{ConfigInstancia::periodoPrueba_activado}}" name="{{ConfigInstancia::periodoPrueba_activado}}" value="<?php print Instancia::obtenerValorMetadato(ConfigInstancia::periodoPrueba_activado); ?>"/>
                </div>
                {{--NUMERO DE DIAS DEL PERIODO DE PRUEBA--}}
                <div class="col-lg-6">
                    <label> {{trans("config.general.seccion.periodo_prueba.op.cantidad")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans('config.general.seccion.periodo_prueba.op.cantidad.ayuda')))</label>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{trans("config.general.seccion.periodo_prueba.op.cantidad.addon.dias")}}</div>
                            <input type="text" class="form-control" name="<?php print ConfigInstancia::periodoPrueba_numero_dias; ?>" value="<?php print Instancia::obtenerValorMetadato(ConfigInstancia::periodoPrueba_numero_dias); ?>"/>
                        </div>
                    </div>
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
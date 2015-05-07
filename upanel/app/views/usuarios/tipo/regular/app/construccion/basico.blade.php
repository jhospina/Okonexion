<?php
$mockups = Aplicacion::mockups();
$form_data = array('action' => 'UPanelControladorAplicacion@guardarBasico', 'method' => 'post', 'enctype' => 'multipart/form-data', "id" => "form", "style" => "clear: both;margin-top: 15px;");

if (Aplicacion::existe()) {
    $estado = $app->estado;
} else {
    $estado = null;
}
?>

@extends('interfaz/plantilla')

@section("titulo"){{trans("app.hd.mi_aplicacion")}}@stop

@section("contenido")  

{{--CABECERA--}}
@include("usuarios/tipo/regular/app/construccion/secciones/cabecera")

{{--BARRA DE PROGRESO--}}
@include("usuarios/tipo/regular/app/construccion/secciones/barra-progreso") 

{{--MENU DE NAVEGACIÓN ENTRE SECCCION--}}
@include("usuarios/tipo/regular/app/construccion/secciones/nav")


<div class="col-lg-9" id="content-config">

    <h2 class="text-right">{{Util::convertirMayusculas(trans("interfaz.menu.principal.mi_aplicacion.configuracion.datos_basicos"))}}</h2>

    <hr/>


    @if(is_null($estado) || $app->estado==Aplicacion::ESTADO_LISTA_PARA_ENVIAR || $app->estado==Aplicacion::ESTADO_ESTABLECIENTO_TEXTOS || $app->estado==Aplicacion::ESTADO_EN_DISENO || $app->estado==Aplicacion::ESTADO_TERMINADA)

    @include("interfaz/mensaje/index",array("id_mensaje"=>3))

    {{Form::model($app, $form_data, array('role' => 'form')) }}
    <div class="col-lg-4 text-default input-lg">{{trans("app.config.info.nombre")}}</div> <div class="col-lg-8">{{ Form::text('nombre', null, array('placeholder' => trans("app.config.info.nombre.placeholder"), 'class' => 'form-control input-lg')) }}</div>
    <div class="col-lg-12" style="margin-top: 10px;">
        <div class="panel panel-primary" style="clear: both;margin-top: 10px;">
            <div class="panel-heading">
                <h3 class="panel-title">{{trans("app.config.info.diseno")}}</h3>
            </div>
            <div class="panel-body">
                <div class="well well-sm">{{trans("app.config.info.diseno.descripcion")}}</div>
                @foreach($mockups as $nombre => $url)

                <span class="tooltip-mockup" rel="tooltip" title="{{AppDesing::obtenerDescripcion($nombre)}}">
                    <img style="cursor: pointer;" id="mockup-{{$nombre}}" onClick="seleccionarMockup('{{$nombre}}','{{$url}}');" src="@if(!is_null($app))@if($app->diseno==$nombre){{URL::to("assets/img/app/".$nombre."_select.png")}}@endif @else{{$url}}@endif"/>
                </span>
                @endforeach

                <input type="hidden" name="mockup" id="mockup" value="@if(!is_null($app)){{$app->diseno}}@endif">
            </div>
        </div>
    </div>

    <div class="col-lg-12 text-right" style="margin-bottom:20px;"> {{ Form::button(trans("otros.btn.guardar"), array('type' => 'button', 'class' => 'btn btn-primary','id'=>"btn-guardar")) }}    </div>

    {{Form::close()}}

    @else
    {{--CUANDO LOS DATOS NO ESTAN DISPONIBLES PARA MODIFICARSE--}}
    @include("usuarios/tipo/regular/app/construccion/secciones/form-basico-lock") 
    @endif

</div>



@stop



@section("script")

{{ HTML::script('assets/js/bootstrap-filestyle.min.js') }}
{{ HTML::script('assets/js/bootstrap-tooltip.js') }}


<script>

            jQuery(document).ready(function () {

    jQuery(".tooltip-mockup").tooltip({placement: "left"});
            jQuery("#btn-guardar").click(function () {

    jQuery(this).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.procesando')}}...");
            jQuery(this).attr("disabled", "disabled");
            $("#progress-bar").animate({width:"15%"}, 2000, function(){
    $("#text-progress").html("15% ({{Aplicacion::obtenerNombreEstado(Aplicacion::ESTADO_EN_DISENO)}})");
            $(this).removeClass("progress-bar-danger");
            $(this).addClass("progress-bar-default");
    });
            setTimeout(function(){
            $("#form").submit();
            }, 2500);
    });
    });
            function seleccionarMockup(nombre, url) {
            jQuery("#mockup-" + nombre).attr("src", "" + url.replace(".png", "_select.png"));
                    jQuery("#mockup").val(nombre);
            }


</script>
@stop
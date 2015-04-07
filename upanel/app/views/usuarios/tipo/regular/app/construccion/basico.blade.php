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

@section("titulo")Mi aplicación @stop

@section("contenido")  

{{--CABECERA--}}
@include("usuarios/tipo/regular/app/construccion/secciones/cabecera")

{{--BARRA DE PROGRESO--}}
@include("usuarios/tipo/regular/app/construccion/secciones/barra-progreso") 

{{--MENU DE NAVEGACIÓN ENTRE SECCCION--}}
@include("usuarios/tipo/regular/app/construccion/secciones/nav")


<div class="col-lg-9" id="content-config">

    <h2 class="text-right">DATOS BASICOS</h2>

    <hr/>


    @if(is_null($estado) || $app->estado==Aplicacion::ESTADO_LISTA_PARA_ENVIAR || $app->estado==Aplicacion::ESTADO_EN_DISENO || $app->estado==Aplicacion::ESTADO_TERMINADA)

    @include("interfaz/mensaje/index",array("id_mensaje"=>3))

    {{Form::model($app, $form_data, array('role' => 'form')) }}
    <div class="col-lg-4 text-default input-lg">Nombre de la aplicación</div> <div class="col-lg-8">{{ Form::text('nombre', null, array('placeholder' => '¿Como se llamara la tu aplicación movil?', 'class' => 'form-control input-lg')) }}</div>
    <div class="col-lg-12" style="margin-top: 10px;">
        <div class="panel panel-primary" style="clear: both;margin-top: 10px;">
            <div class="panel-heading">
                <h3 class="panel-title">Diseño</h3>
            </div>
            <div class="panel-body">
                <div class="well well-sm">Escoge la plantilla de diseño que se utilizara para tu aplicación movil.</div>
                @foreach($mockups as $nombre => $url)

                <span class="tooltip-mockup" rel="tooltip" title="Diseño en cuadricula de 2x2 para navegar entre cada sección de la aplicación.">
                    <img style="cursor: pointer;" id="mockup-{{$nombre}}" onClick="seleccionarMockup('{{$nombre}}','{{$url}}');" src="@if(!is_null($app))@if($app->diseno==$nombre){{URL::to("assets/img/app/".$nombre."_select.png")}}@endif @else{{$url}}@endif"/>
                </span>
                @endforeach

                <input type="hidden" name="mockup" id="mockup" value="@if(!is_null($app)){{$app->diseno}}@endif">
            </div>
        </div>
    </div>

    <div class="col-lg-12 text-right" style="margin-bottom:20px;"> {{ Form::button("Guardar", array('type' => 'button', 'class' => 'btn btn-primary','id'=>"btn-guardar")) }}    </div>

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

    jQuery(this).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> Procesando...");
            jQuery(this).attr("disabled", "disabled");
            $("#progress-bar").animate({width:"20%"}, 2000, function(){
    $("#text-progress").html("30% (En diseño)");
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
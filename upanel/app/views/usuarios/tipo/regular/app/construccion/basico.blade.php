<?php
$mockups = Aplicacion::mockups();
$form_data = array('action' => 'UPanelControladorAplicacion@guardarBasico', 'method' => 'post', 'enctype' => 'multipart/form-data', "id" => "form", "style" => "clear: both;margin-top: 15px;");

if (Aplicacion::existe()) {
    $estado = $app->estado;
    if (!isset($app))
        $app = Aplicacion::obtener();
    if (!isset($version))
        $version = ProcesoApp::obtenerNumeroVersion($app->id);
} else {
    $estado = null;
    $version = 0;
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


    @if(is_null($estado) || $app->estado==Aplicacion::ESTADO_LISTA_PARA_ENVIAR || $app->estado==Aplicacion::ESTADO_ESTABLECIENTO_TEXTOS || $app->estado==Aplicacion::ESTADO_EN_DISENO || $app->estado==Aplicacion::ESTADO_EN_PROCESO_DE_ACTUALIZACION)

    @include("interfaz/mensaje/index",array("id_mensaje"=>3))

    {{Form::model($app, $form_data, array('role' => 'form')) }}
    <div class="col-lg-4 text-default input-lg">{{trans("app.config.info.nombre")}}</div> <div class="col-lg-8">{{ Form::text('nombre', null, array('placeholder' => trans("app.config.info.nombre.placeholder"), 'class' => 'form-control input-lg')) }}</div>
    <div class="col-lg-12" style="margin-top: 10px;">
        <div class="panel panel-primary" style="clear: both;margin-top: 10px;">
            <div class="panel-heading">
                <h3 class="panel-title">{{trans("app.config.info.diseno")}}</h3>
            </div>
            <div class="panel-body" id="seleccion-apps-basico">
                <div class="well well-sm">{{trans("app.config.info.diseno.descripcion")}}</div>
                @foreach($mockups as $index => $nombre)
                <?php
                $imagenesApp = array_reverse(ArchivosCTR::obtenerListadoArchivos("assets/img/app/" . $nombre . "/"));
                list($android, $ios, $windows) = AppDesing::obtenerDisponibilidadPlataformas($nombre);
                ?>
                <div class="content-app-info">
                    <div class="content-1">
                        <div class="slide-imagenes">
                            <?php for ($i = 0; $i < count($imagenesApp); $i++): ?>
                                <img class="img-rounded" style="{{($i==0)?"display:block":"display:none"}}" src="{{URL::to($imagenesApp[$i])}}">
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="content-2">
                        <div class="titulo-app">{{trans("diseno/".$nombre.".titulo")}}</div>
                        <div class="descripcion-app">{{trans("diseno/".$nombre.".descripcion")}}</div>
                        <div class="plataformas-app">
                            @if($android)
                            <img class="tooltip-top" title="Android" src="{{URL::to("assets/img/android.png")}}"/>
                            @endif
                            @if($ios)
                            <img class="tooltip-top" title="IOS" src="{{URL::to("assets/img/ios.png")}}"/>
                            @endif
                            @if($windows)
                            <img class="tooltip-top" title="Windows" src="{{URL::to("assets/img/windows.png")}}"/>
                            @endif
                        </div>
                        <div class="seleccion-app">
                            <button class="btn {{(!is_null($app) && $app->diseno==$nombre)?'btn-danger disabled':'btn-success'}}" type="button" onclick="seleccionarMockup('{{$nombre}}', this)">@if(!is_null($app) && $app->diseno==$nombre) <span class="glyphicon glyphicon-ok"></span> {{trans("otros.info.seleccionado")}} @else <span class="glyphicon glyphicon-phone"></span> {{trans("otros.info.seleccionar")}} @endif</button>
                        </div>
                    </div>
                </div>

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

@if(is_null($estado) || $app->estado==Aplicacion::ESTADO_LISTA_PARA_ENVIAR || $app->estado==Aplicacion::ESTADO_ESTABLECIENTO_TEXTOS || $app->estado==Aplicacion::ESTADO_EN_DISENO || $app->estado==Aplicacion::ESTADO_EN_PROCESO_DE_ACTUALIZACION)


@section("script")

{{ HTML::script('assets/js/bootstrap-filestyle.min.js') }}




<script>
            jQuery(document).ready(function () {

    jQuery(".tooltip-mockup").tooltip({placement: "left"});
            jQuery("#btn-guardar").click(function () {

    jQuery(this).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.procesando')}}...");
            jQuery(this).attr("disabled", "disabled");
            @if ($version == 0)
            $("#progress-bar").animate({width:"15%"}, 2000, function () {
    $("#text-progress").html("15% ({{Aplicacion::obtenerNombreEstado(Aplicacion::ESTADO_EN_DISENO)}})");
            $(this).removeClass("progress-bar-danger");
            $(this).addClass("progress-bar-default");
    });
            @endif
            setTimeout(function () {
            $("#form").submit();
            }, 2500);
    });
    });
            function seleccionarMockup(nombre, btn) {
            jQuery("#mockup").val(nombre);
                    $("#seleccion-apps-basico .seleccion-app button").removeClass("btn-danger disabled");
                    $("#seleccion-apps-basico .seleccion-app button").html("<span class='glyphicon glyphicon-phone'></span> {{trans('otros.info.seleccionar')}}")
                    $(btn).removeClass("btn-success");
                    $(btn).addClass("btn-danger disabled");
                    $(btn).html("<span class='glyphicon glyphicon-ok'></span> {{trans('otros.info.seleccionado')}}");
            }


</script>



@stop

@endif
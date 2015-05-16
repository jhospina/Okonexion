<?php
if (!isset($app))
    $app = Aplicacion::obtener();
if (!isset($version))
    $version = ProcesoApp::obtenerNumeroVersion($app->id);

$textos = AppDesing::obtenerNombresTextoInfo();

//Obtiene los textos configurados por el usuario, o si no, los establece por defectos
foreach ($textos as $indice => $valor) {
    if (ConfiguracionApp::existeConfig($valor))
        $textos[$indice] = ConfiguracionApp::obtenerValorConfig($valor);
    else
        $textos[$indice] = AppDesing::obtenerTextoInfo($valor);
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

    <h2 class="text-right">{{Util::convertirMayusculas(trans("interfaz.menu.principal.mi_aplicacion.configuracion.textos"))}}</h2>

    <hr/>


    @if($app->estado==Aplicacion::ESTADO_LISTA_PARA_ENVIAR || $app->estado==Aplicacion::ESTADO_ESTABLECIENTO_TEXTOS || $app->estado==Aplicacion::ESTADO_EN_DISENO || $app->estado==Aplicacion::ESTADO_EN_PROCESO_DE_ACTUALIZACION)

    @include("interfaz/mensaje/index",array("id_mensaje"=>3))


    <div class="col-lg-12 text-right" style="margin-bottom:20px;padding: 0px;"> {{ Form::button("<span class='glyphicon glyphicon-save'></span> ".trans("app.config.info.btn.establecer_textos"), array('type' => 'button', 'class' => 'btn btn-success btn-large btn-guardar',"style"=>"font-size:20px;")) }}    </div>


    <div class="panel panel-primary" style="clear: both;">
        <div class="panel-heading">
            <h3 class="panel-title">{{trans("app.config.txt.info.textos_aplicacion")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans("app.config.txt.info.textos_aplicacion.ayuda"))) </h3>
        </div>
        <div class="panel-body">

            <form id="form" action="" method="POST"> 
                <?php $n = 0; ?>
                @foreach($textos as $clave => $valor)
                <?php $n++; ?>
                <div class="col-lg-4 text-default input-lg">{{Util::eliminarPluralidad(trans("interfaz.menu.principal.mi_aplicacion.configuracion.textos"))}} {{$n}}</div> <div class="col-lg-8"><input type="text" name="{{$clave}}" id="{{$clave}}" class="form-control input-lg" value="{{$valor}}"/></div>
                @endforeach
            </form>

        </div>
    </div>

    <div class="col-lg-12 text-center" style="margin-bottom:20px;"> {{ Form::button("<span class='glyphicon glyphicon-save'></span> ".trans("app.config.info.btn.establecer_textos"), array('type' => 'button', 'class' => 'btn btn-success btn-large btn-guardar',"style"=>"font-size:20px;")) }}    </div>



    @else
    {{--CUANDO LOS DATOS NO ESTAN DISPONIBLES PARA MODIFICARSE--}}
    @include("usuarios/tipo/regular/app/construccion/secciones/textos-lock") 
    @endif

</div>



@stop



@section("script")

<script>

    jQuery(document).ready(function () {



        jQuery(".btn-guardar").click(function () {

            jQuery(this).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.procesando')}}...");
            jQuery(this).attr("disabled", "disabled");
                    @if ($version == 0)
                    $("#progress-bar").animate({width: "45%"}, 2000, function () {
                $("#text-progress").html("45% ({{Aplicacion::obtenerNombreEstado(Aplicacion::ESTADO_LISTA_PARA_ENVIAR)}})");
                $(this).removeClass("progress-bar-default");
                $(this).addClass("progress-bar-info-le");
            });
                    @endif
                    setTimeout(function () {
                        $("#form").submit();
                    }, 2500);
        });
    });



</script>
@stop
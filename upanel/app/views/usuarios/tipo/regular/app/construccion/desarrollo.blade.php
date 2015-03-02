<?php
if ($app->estado == Aplicacion::ESTADO_EN_COLA_PARA_DESARROLLO) {

    //select * from `procesosApp` where (`actividad` = CO or `actividad` = AC) and `fecha_finalizacion` is null order by `id` asc
    $consultaProcesos = DB::table("procesosApp")->where(function($query) {
                $query->where("actividad", "=", ProcesoApp::ACTIVIDAD_CONSTRUIR)->orWhere("actividad", "=", ProcesoApp::ACTIVIDAD_ACTUALIZAR);
            })->whereNull("fecha_finalizacion")->orderBy("id", "ASC")->get();

    $totalFila = count($consultaProcesos);
    $posCola = 0;
    foreach ($consultaProcesos as $proceso) {
        $posCola++;
        if ($proceso->id_aplicacion == $app->id) {
            break;
        }
    }
}


if ($app->estado == Aplicacion::ESTADO_EN_DESARROLLO) {
    //Obtiene el ultimo proceso solicitado por la aplicacion 
    $procesos = ProcesoApp::where("id_aplicacion", $app->id)->whereNull("fecha_finalizacion")->orderBy("id", "DESC")->take(1)->get();

    foreach ($procesos as $proceso)
        break;
}
?>

@extends('interfaz/plantilla')

@section("titulo")Mi aplicación @stop

@section("contenido")  

{{--CABECERA--}}
@include("usuarios/tipo/regular/app/construccion/secciones/cabecera")


{{--MENU DE NAVEGACIÓN ENTRE SECCCION--}}
@include("usuarios/tipo/regular/app/construccion/secciones/nav")


<div class="col-lg-9" id="content-config">

    <h2 class="text-right">DESARROLLO</h2>

    <hr/>
    @include("interfaz/mensaje/index",array("id_mensaje"=>3))
    <div class="block">
        <h2 class="text-center">Progreso de tu aplicación</h2>
        {{--BARRA DE PROGRESO--}}
        @include("usuarios/tipo/regular/app/construccion/secciones/barra-progreso") 
    </div>

    {{--LISTA PARA ENVIAR--}}
    @if($app->estado==Aplicacion::ESTADO_LISTA_PARA_ENVIAR)
    <div class="well well-lg" style="clear: both;">
        <h3 class="text-center">ATENCIÓN</h3>
        <p class="text-justify">Una vez que envíes tu aplicación a desarrollar, esta pasara a estar en estado de "En cola para desarrollo" y esto significa que estará en lista de espera para que nuestro equipo de desarrollo se encargue de construir tu aplicación. Mientras se encuentre en este estado, no podrás cambiar o modificar ninguno de los datos básicos de tu aplicación, tampoco podrás cambiar ninguno de los parámetros de apariencia. Una vez que se termine de construir la primera versión de tu aplicación, podrás realizar procesos de actualización, en donde nuevamente podrás cambiar el diseño y la apariencia de tu aplicación si así lo requieres.</p> 
        <p class="text-justify">Si quieres tener más información acerca de este proceso, no dudes en consultar nuestra <a>Base de conocimientos</a> o comunicarte directamente con nuestro equipo de soporte. Estamos para ayudarte.</p>
    </div>
    <form action="" method="POST" id="form">
        <div class="block text-center">
            <button type="button" class="btn btn-success btn-large" id="btn-enviar" style="font-size: 30px;"><span class="glyphicon glyphicon-upload"></span> Enviar a desarrollo</button>
        </div>
    </form>
    @endif

    {{--En cola para desarrollo o en desarrollo--}}
    @if($app->estado==Aplicacion::ESTADO_EN_COLA_PARA_DESARROLLO || $app->estado==Aplicacion::ESTADO_EN_DESARROLLO)

    <div class='well well-lg' style='clear: both;'>
        <h3>Información de desarrollo</h3>
        <table class='table table-striped'>
            <tr><th>Estado de la aplicación</th><td>{{Aplicacion::obtenerNombreEstado($app->estado)}}</td></tr>
            @if($app->estado==Aplicacion::ESTADO_EN_DESARROLLO)
            <tr><th>Fecha de inicio</th><td>{{$proceso->fecha_inicio}}</td></tr>
            @elseif($app->estado==Aplicacion::ESTADO_EN_COLA_PARA_DESARROLLO)
            <tr><th>Posición en la cola</th><td>{{$posCola}} de {{$totalFila}}</td></tr>
            @endif
        </table>
    </div>

    @endif


    {{--APLICACION TERMINADA--}}

    @if($app->estado==Aplicacion::ESTADO_TERMINADA)


    <h2>¡Tu aplicación esta disponible para descarga!</h2>
    {{--APLICACION EN ANDROID--}}
    <div class="well well-lg" style="padding: 10px;" id="content-upload-android">
        <table class="table" style="margin-bottom:0px;">
            <tr>
                <td style="vertical-align:middle;border:0px;">
                    <a href="#" class="btn btn-primary"><span class="glyphicon glyphicon-download"></span> Descargar {{$app->nombre}} para Android</a>
                </td>
                <td>
                    <a class="tooltip-right" rel="tooltip" title="Android"> 
                        <img src="{{URL::to("assets/img/android.png")}}"/>
                    </a>
                </td>
            </tr>
        </table>
    </div>
    {{--APLICACION EN WINDOWS--}}
    <div class="well well-lg" style="padding: 10px;" id="content-upload-windows">
        <table class="table" style="margin-bottom:0px;">
            <tr>
                <td style="vertical-align:middle;border:0px;">
                    <a href="#" class="btn btn-primary"><span class="glyphicon glyphicon-download"></span> Descargar {{$app->nombre}} Windows Phone</a>
                </td>
                <td>
                    <a class="tooltip-right" rel="tooltip" title="Windows Phone"> 
                        <img src="{{URL::to("assets/img/windows.png")}}"/>
                    </a>
                </td>
            </tr>
        </table>
    </div>
    {{--APLICACION EN IOS--}}
    <div class="well well-lg" style="padding: 10px;" id="content-upload-ios">
        <table class="table" style="margin-bottom:0px;">
            <tr>
                <td style="vertical-align:middle;border:0px;">
                    <a href="#" class="btn btn-primary"><span class="glyphicon glyphicon-download"></span> Descargar {{$app->nombre}} para Iphone</a>
                </td>
                <td>
                    <a class="tooltip-right" rel="tooltip" title="IOS (Iphone)"> 
                        <img  src="{{URL::to("assets/img/ios.png")}}"/>
                    </a>
                </td>
            </tr>
        </table>
    </div>

    @endif

</div> 



<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">¿Estas seguro de enviar tu aplicación a desarrollo?</h4>
            </div>
            <div class="modal-body">
                Recuerda que mientras se realice este proceso no podras modificar nada de la aplicación.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btn-confirmar">Enviar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>

    jQuery("#btn-enviar").click(function () {
        $('#myModal').modal('show');
    });

    jQuery("#btn-confirmar").click(function () {

        $('#myModal').modal('hide');

        jQuery("#btn-enviar").html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> Enviando...");
        jQuery("#btn-enviar").attr("disabled", "disabled");
        $("#progress-bar").animate({width: "55%"}, 2000, function () {
            $("#text-progress").html("55% (En cola para desarrollo)");
            $(this).removeClass("progress-bar-default");
            $(this).addClass("progress-bar-info");
        });
        setTimeout(function () {
            $("#form").submit();
        }, 2500);
    });
</script>


@stop


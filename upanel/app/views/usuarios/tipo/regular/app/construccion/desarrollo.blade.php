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

@section("titulo"){{trans("app.hd.mi_aplicacion")}} @stop

@section("contenido")  

{{--CABECERA--}}
@include("usuarios/tipo/regular/app/construccion/secciones/cabecera")


{{--MENU DE NAVEGACIÓN ENTRE SECCCION--}}
@include("usuarios/tipo/regular/app/construccion/secciones/nav")


<div class="col-lg-9" id="content-config">

    <h2 class="text-right">{{Util::convertirMayusculas(trans("interfaz.menu.principal.mi_aplicacion.desarrollo"))}}</h2>

    <hr/>
    @include("interfaz/mensaje/index",array("id_mensaje"=>3))
    <div class="block">
        <h2 class="text-center">{{trans("app.config.dep.info.progreso")}}</h2>
        {{--BARRA DE PROGRESO--}}
        @include("usuarios/tipo/regular/app/construccion/secciones/barra-progreso") 
    </div>

    {{--LISTA PARA ENVIAR--}}
    @if($app->estado==Aplicacion::ESTADO_LISTA_PARA_ENVIAR)
    <div class="well well-lg" style="clear: both;">
        {{trans("app.config.dep.info.enviar.descripcion")}}
    </div>
    <form action="" method="POST" id="form">
        <div class="block text-center">
            <button type="button" class="btn btn-success btn-large" id="btn-enviar" style="font-size: 30px;"><span class="glyphicon glyphicon-upload"></span> {{trans("app.config.dep.btn.enviar_desarrollo")}}</button>
        </div>
    </form>
    @endif

    {{--En cola para desarrollo o en desarrollo--}}
    @if($app->estado==Aplicacion::ESTADO_EN_COLA_PARA_DESARROLLO || $app->estado==Aplicacion::ESTADO_EN_DESARROLLO)

    <div class='well well-lg' style='clear: both;'>
        <h3>{{trans("app.config.dep.info.informacion_desarrollo")}}</h3>
        <table class='table table-striped'>
            <tr><th>{{trans("app.config.dep.info.estado")}}</th><td>{{Aplicacion::obtenerNombreEstado($app->estado)}}</td></tr>
            @if($app->estado==Aplicacion::ESTADO_EN_DESARROLLO)
            <tr><th>{{trans("otros.info.fecha_inicio")}}</th><td>{{$proceso->fecha_inicio}}</td></tr>
            @elseif($app->estado==Aplicacion::ESTADO_EN_COLA_PARA_DESARROLLO)
            <tr><th>{{trans("app.config.dep.info.posicion_cola")}}</th><td>{{$posCola}} de {{$totalFila}}</td></tr>
            @endif
        </table>
    </div>

    @endif


    {{--APLICACION TERMINADA--}}

    @if($app->estado==Aplicacion::ESTADO_TERMINADA)


    <h2>{{trans("app.config.dep.info.aplicacion_disponible")}}</h2>
    {{--APLICACION EN ANDROID--}}
    <div class="well well-lg" style="padding: 10px;" id="content-upload-android">
        <table class="table" style="margin-bottom:0px;">
            <tr>
                <td style="vertical-align:middle;border:0px;">
                    <a href="#" class="btn btn-primary"><span class="glyphicon glyphicon-download"></span> {{trans("app.config.dep.descargar.info.android",array("nombre_app"=>$app->nombre))}}</a>
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
                    <a href="#" class="btn btn-primary"><span class="glyphicon glyphicon-download"></span> {{trans("app.config.dep.descargar.info.windows",array("nombre_app"=>$app->nombre))}}</a>
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
                    <a href="#" class="btn btn-primary"><span class="glyphicon glyphicon-download"></span> {{trans("app.config.dep.descargar.info.iphone",array("nombre_app"=>$app->nombre))}}</a>
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
                <h4 class="modal-title">{{trans("app.config.dep.info.pregunta.enviar")}}</h4>
            </div>
            <div class="modal-body">
               {{trans("app.config.dep.info.pregunta.enviar.descripcion")}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{trans("app.info.cerrar")}}</button>
                <button type="button" class="btn btn-primary" id="btn-confirmar">{{trans("app.info.enviar")}}</button>
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

        jQuery("#btn-enviar").html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('app.info.enviando')}}...");
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


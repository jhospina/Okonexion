<?php
$tipoContenido = Contenido_PQR::nombre;
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, Contenido_PQR::nombre);
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | {{trans("otros.info.administrar")}} {{$nombreContenido}} @stop

@section("contenido") 

<h2><span class="glyphicon glyphicon-info-sign"></span> {{Util::convertirMayusculas(trans("otros.info.administrar")." ".$nombreContenido)}}</h2>
<hr/>
@include("interfaz/mensaje/index",array("id_mensaje"=>2))



{{--PETICIONES--}}
<div class="panel panel-primary">
    <div class="panel-heading">
        <h2 class="panel-title"><span class="glyphicon glyphicon-info-sign"></span> <b>{{Util::convertirMayusculas(trans("app.admin.pqr.info.peticiones"))}}</b></h2>
    </div>
    <div class="panel-body">

        @if(count($peticiones)>0)

        <table class="table table-striped table-hover">
            <tr>
                <th>{{trans("otros.info.asunto")}}</th>
                <th>{{trans("otros.info.nombre")}}</th>
                <th>{{trans("otros.info.email")}}</th>
                <th>{{trans("otros.info.ultima_respuesta")}}</th>
                <th>{{trans("otros.info.ultima_actualizacion")}}</th>
                <th></th>
            </tr>
            @foreach($peticiones as $peticion)
            <tr>
                <td>{{$peticion->titulo}}</td>
                <td>{{Contenido_PQR::cliente_obtenerNombre($peticion->id)}}</td>
                <td>{{Contenido_PQR::cliente_obtenerEmail($peticion->id)}}</td>
                <td>{{Contenido_PQR::obtenerUltimaRespuesta($peticion->id)}}</td>
                <td>{{trans("otros.info.hace")}} {{Contenido_PQR::obtenerUltimaActualizacion($peticion->id,$peticion->created_at)}}</td>
                <td>
                    <a title="{{trans("otros.info.revisar")}}" class="btn-sm btn-primary" href="{{URL::to("aplicacion/administrar/pqr/".$peticion->id."/revisar/")}}"><span class="glyphicon glyphicon-comment"></span></a>
                </td>
            </tr>
            @endforeach
        </table>

        {{$peticiones->links()}}

        @else

        <h3><span class="glyphicon glyphicon-warning-sign"></span> {{trans("otros.info.no_hay_datos")}}</h3>

        @endif


    </div>
</div>    

{{--QUEJAS--}}
<div class="panel panel-primary">
    <div class="panel-heading">
        <h2 class="panel-title"><span class="glyphicon glyphicon-info-sign"></span> <b>{{Util::convertirMayusculas(trans("app.admin.pqr.info.quejas"))}}</b></h2>
    </div>
    <div class="panel-body">

        @if(count($quejas)>0)

        <table class="table table-striped table-hover">
            <tr>
                <th>{{trans("otros.info.asunto")}}</th>
                <th>{{trans("otros.info.nombre")}}</th>
                <th>{{trans("otros.info.email")}}</th>
                <th>{{trans("otros.info.ultima_respuesta")}}</th>
                <th>{{trans("otros.info.ultima_actualizacion")}}</th>
                <th></th>
            </tr>
            @foreach($quejas as $queja)
            <tr>
                <td>{{$queja->titulo}}</td>
                <td>{{Contenido_PQR::cliente_obtenerNombre($queja->id)}}</td>
                <td>{{Contenido_PQR::cliente_obtenerEmail($queja->id)}}</td>
                <td>{{Contenido_PQR::obtenerUltimaRespuesta($queja->id)}}</td>
                <td>{{trans("otros.info.hace")}} {{Contenido_PQR::obtenerUltimaActualizacion($queja->id,$queja->created_at)}}</td>
                <td>
                    <a title="{{trans("otros.info.revisar")}}" class="btn-sm btn-primary" href="{{URL::to("aplicacion/administrar/pqr/".$queja->id."/revisar/")}}"><span class="glyphicon glyphicon-comment"></span></a>
                </td>
            </tr>
            @endforeach
        </table>

        {{$quejas->links()}}

        @else

        <h3><span class="glyphicon glyphicon-warning-sign"></span> {{trans("otros.info.no_hay_datos")}}</h3>

        @endif

    </div>
</div>    
{{--RECLAMOS--}}

<div class="panel panel-primary">
    <div class="panel-heading">
        <h2 class="panel-title"><span class="glyphicon glyphicon-info-sign"></span> <b>{{Util::convertirMayusculas(trans("app.admin.pqr.info.reclamos"))}}</b></h2>
    </div>
    <div class="panel-body">

        @if(count($reclamos)>0)

        <table class="table table-striped table-hover">
            <tr>
                <th>{{trans("otros.info.asunto")}}</th>
                <th>{{trans("otros.info.nombre")}}</th>
                <th>{{trans("otros.info.email")}}</th>
                <th>{{trans("otros.info.ultima_respuesta")}}</th>
                <th>{{trans("otros.info.ultima_actualizacion")}}</th>
                <th></th>
            </tr>
            @foreach($reclamos as $reclamo)
            <tr>
                <td>{{$reclamo->titulo}}</td>
                <td>{{Contenido_PQR::cliente_obtenerNombre($reclamo->id)}}</td>
                <td>{{Contenido_PQR::cliente_obtenerEmail($reclamo->id)}}</td>
                <td>{{Contenido_PQR::obtenerUltimaRespuesta($reclamo->id)}}</td>
                <td>{{trans("otros.info.hace")}} {{Contenido_PQR::obtenerUltimaActualizacion($reclamo->id,$reclamo->created_at)}}</td>
                <td>
                    <a title="{{trans("otros.info.revisar")}}" class="btn-sm btn-primary" href="{{URL::to("aplicacion/administrar/pqr/".$reclamo->id."/revisar/")}}"><span class="glyphicon glyphicon-comment"></span></a>
                </td>
            </tr>
            @endforeach
        </table>

        {{$reclamos->links()}}

        @else

        <h3><span class="glyphicon glyphicon-warning-sign"></span> {{trans("otros.info.no_hay_datos")}}</h3>

        @endif

    </div>
</div>    
{{--SUGERENCIAS--}}
<div class="panel panel-primary">
    <div class="panel-heading">
        <h2 class="panel-title"><span class="glyphicon glyphicon-info-sign"></span> <b>{{Util::convertirMayusculas(trans("app.admin.pqr.info.sugerencias"))}}</b></h2>
    </div>
    <div class="panel-body">

        @if(count($sugerencias)>0)

        <table class="table table-striped table-hover">
            <tr>
                <th>{{trans("otros.info.asunto")}}</th>
                <th>{{trans("otros.info.nombre")}}</th>
                <th>{{trans("otros.info.email")}}</th>
                <th>{{trans("otros.info.ultima_respuesta")}}</th>
                <th>{{trans("otros.info.ultima_actualizacion")}}</th>
                <th></th>
            </tr>
            @foreach($sugerencias as $sugerencia)
            <tr>
                <td>{{$sugerencia->titulo}}</td>
                <td>{{Contenido_PQR::cliente_obtenerNombre($sugerencia->id)}}</td>
                <td>{{Contenido_PQR::cliente_obtenerEmail($sugerencia->id)}}</td>
                <td>{{Contenido_PQR::obtenerUltimaRespuesta($sugerencia->id)}}</td>
                <td>{{trans("otros.info.hace")}} {{Contenido_PQR::obtenerUltimaActualizacion($sugerencia->id,$sugerencia->created_at)}}</td>
                <td>
                    <a title="{{trans("otros.info.revisar")}}" class="btn-sm btn-primary" href="{{URL::to("aplicacion/administrar/pqr/".$sugerencia->id."/revisar/")}}"><span class="glyphicon glyphicon-comment"></span></a>
                </td>
            </tr>
            @endforeach
        </table>

        {{$sugerencias->links()}}

        @else

        <h3><span class="glyphicon glyphicon-warning-sign"></span> {{trans("otros.info.no_hay_datos")}}</h3>

        @endif

    </div>
</div>    
@stop

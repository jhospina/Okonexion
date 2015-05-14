<?php
$html_loading_ajax = '<div id="loading-ajax" class="text-center"><img src="' . URL::to("assets/img/loaders/gears.gif") . '"/></div>';
?>
@extends('interfaz/plantilla')

@section("titulo"){{trans("app.coldep.titulo")}} @stop




@section("contenido")  

<h1>{{trans("app.historialDep.titulo")}}</h1>

<hr/>
@if(Auth::user()->instancia==User::PARAM_INSTANCIA_SUPER_ADMIN)
<div class="well well-sm">
    <a href="{{URL::to("aplicacion/desarrollo/cola")}}" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> {{trans("otros.info.volver")}}</a>
</div>
@endif

<table class="table table-striped">
    <tr>
        <th>N</th>
        <th>{{Util::convertirMayusculas(trans("otros.info.fecha_solicitud"))}}</th>
        <th>{{Util::convertirMayusculas(trans("otros.info.aplicacion"))}}</th>
        <th>{{Util::convertirMayusculas(trans("otros.info.atendido_por"))}}</th>
        <th>{{Util::convertirMayusculas(trans("otros.info.fecha_inicio"))}}</th>
        <th>{{Util::convertirMayusculas(trans("otros.info.fecha_finalizacion"))}}</th>
        <th>{{Util::convertirMayusculas(trans("otros.info.plataformas"))}}</th></tr>
    @foreach($historial as $proceso)
    <?php
    $app = Aplicacion::find($proceso->id_aplicacion);
    $atendido_por = $proceso->user;
    ?>
    
    <tr id="proceso-{{$proceso->id}}">
        <td>{{$proceso->id}}</td>
        <td>{{$proceso->fecha_creacion}}</td>
        <td id="nombre-app-{{$proceso->id}}">{{$app->nombre}}</td>
        <td id="atendido-{{$proceso->id}}">@if(is_null($atendido_por))Sin atender @else {{$atendido_por->nombres}} @endif</td>
        <td id="inicio-{{$proceso->id}}">{{$proceso->fecha_inicio}}</td>
        <td>
           {{$proceso->fecha_finalizacion}}
        </td>
        <td>
           
            @if(!is_null($proceso->url_android))
                <a title="{{trans("otros.info.descargar")}} Android" class="tooltip-top" href="{{$proceso->url_android}}"><img style="width: 25px;" src="{{URL::to("assets/img/android.png")}}"/></a>
            @endif
            
            @if(!is_null($proceso->url_windows))
                <a title="{{trans("otros.info.descargar")}} Windows" class="tooltip-top" href="{{$proceso->url_windows}}"><img style="width: 25px;" src="{{URL::to("assets/img/windows.png")}}"/></a>
            @endif
            
            @if(!is_null($proceso->url_iphone))
                <a title="{{trans("otros.info.descargar")}} Iphone" class="tooltip-top" href="{{$proceso->url_windows}}"><img style="width: 25px;border: 1px rgb(97, 97, 97) solid;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" src="{{URL::to("assets/img/ios.png")}}"/></a>
            @endif
            
        </td>
    </tr>
    @endforeach

    @if(count($historial)==0)

    <tr><td colspan="10" class="text-center"><h3>{{trans("otros.info.no_hay_datos")}}</h3></td></tr>

    @endif

</table>


{{$historial->links()}}

{{--*************************************************************************--}}
{{--*************************************************************************--}}
{{--*************************************************************************--}}
{{--*************************************************************************--}}
{{--*************************************************************************--}}


@stop


@section("script")


{{ HTML::script('assets/js/bootstrap-tooltip.js') }}


<script>
            jQuery(".tooltip-left").tooltip({placement: "left"});
            jQuery(".tooltip-top").tooltip({placement: "top"});
            jQuery(".tooltip-right").tooltip({placement: "right"});</script>


    
 @stop
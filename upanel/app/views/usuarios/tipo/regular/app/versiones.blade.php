<?php
$html_loading_ajax = '<div id="loading-ajax" class="text-center"><img src="' . URL::to("assets/img/loaders/gears.gif") . '"/></div>';
?>
@extends('interfaz/plantilla')

@section("titulo"){{trans("app.coldep.titulo")}} @stop


@section("contenido")  

<h1>{{trans("interfaz.menu.principal.mi_aplicacion.versiones")}} {{$app->nombre}}</h1>

<hr/>

<table class="table table-striped">
    <tr>
        <th>{{Util::convertirMayusculas(trans("otros.info.version"))}}</th>
        <th>{{Util::convertirMayusculas(trans("otros.info.fecha_solicitud"))}}</th>
        <th>{{Util::convertirMayusculas(trans("otros.info.fecha_inicio"))}}</th>
        <th>{{Util::convertirMayusculas(trans("otros.info.fecha_finalizacion"))}}</th>
        <th>{{Util::convertirMayusculas(trans("otros.info.plataformas"))}}</th></tr>
    
    <?php 
    $total_versiones=count($versiones);
    ?>
    
    @foreach($versiones as $version)
    <?php
    $app = Aplicacion::find($version->id_aplicacion);
    $atendido_por = $version->user;
    ?>
    
    <tr id="proceso-{{$version->id}}">
        <td>{{$total_versiones--}}</td>
        <td>{{$version->fecha_creacion}}</td>
        <td id="inicio-{{$version->id}}">{{$version->fecha_inicio}}</td>
        <td>
           {{$version->fecha_finalizacion}}
        </td>
        <td>  
            @if(!is_null($version->url_android))
                <a title="{{trans("otros.info.descargar")}} Android" class="tooltip-top" href="{{$version->url_android}}"><img style="width: 25px;" src="{{URL::to("assets/img/android.png")}}"/></a>
            @endif
            
            @if(!is_null($version->url_windows))
                <a title="{{trans("otros.info.descargar")}} Windows" class="tooltip-top" href="{{$version->url_windows}}"><img style="width: 25px;" src="{{URL::to("assets/img/windows.png")}}"/></a>
            @endif
            
            @if(!is_null($version->url_iphone))
                <a title="{{trans("otros.info.descargar")}} Iphone" class="tooltip-top" href="{{$version->url_windows}}"><img style="width: 25px;border: 1px rgb(97, 97, 97) solid;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" src="{{URL::to("assets/img/ios.png")}}"/></a>
            @endif
            
        </td>
    </tr>
    @endforeach

    @if(count($versiones)==0)

    <tr><td colspan="10" class="text-center"><h3>{{trans("otros.info.no_hay_datos")}}</h3></td></tr>

    @endif

</table>


{{$versiones->links()}}

{{--*************************************************************************--}}
{{--*************************************************************************--}}
{{--*************************************************************************--}}
{{--*************************************************************************--}}
{{--*************************************************************************--}}


@stop


@section("script")





    
 @stop
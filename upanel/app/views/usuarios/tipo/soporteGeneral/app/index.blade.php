@extends('interfaz/plantilla')

@section("titulo") {{trans("interfaz.menu.principal.aplicaciones")}} @stop

@section("contenido") 

<h1><span class="glyphicon glyphicon-phone"></span> {{trans("interfaz.menu.principal.aplicaciones")}}</h1>


<table class="table table-striped">

    <tr>
        <th>ID</th>
        <th>{{trans("app.config.info.nombre")}}</th>
        <th>{{trans("otros.info.estado")}}</th>
        <th>{{trans("otros.info.usuario")}}</th>
        <th></th>
    </tr>

    @foreach($apps as $app)
    <tr>
        <td>{{$app->id}}</td>
        <td>{{$app->nombre}}</td>
        <td>{{Aplicacion::obtenerNombreEstado($app->estado)}}</td>
        <td><a target="_blank" href="{{URL::to("usuario/".$app->id_usuario)}}">{{$app->nombres}} {{$app->apellidos}}</a></td>
        <td>
            <a target="_blank" title="{{trans("interfaz.menu.principal.mi_aplicacion.versiones")}}" class="tooltip-top" href="{{URL::to("aplicacion/".$app->id."/versiones")}}"><span class="glyphicon glyphicon-th"></span></a>
        </td>
    </tr>
    @endforeach

</table>

{{$apps->links()}}


@stop


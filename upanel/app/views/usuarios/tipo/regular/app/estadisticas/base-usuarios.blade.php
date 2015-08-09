@extends('interfaz/plantilla')

@section("titulo"){{trans("interfaz.menu.principal.mi_aplicacion.estadisticas")}} | {{trans("app.estadisticas.titulo.base.usuarios")}} {{$app->nombre}} @stop

@section("css")

{{ HTML::style('assets/css/upanel/estadisticas.css', array('media' => 'screen')) }}


@stop
@section("contenido")

<h1><span class="glyphicon glyphicon-user"></span> {{trans("interfaz.menu.principal.mi_aplicacion.estadisticas")}} | {{trans("app.estadisticas.titulo.base.usuarios")}}</h1>
<hr>
@include("usuarios/tipo/regular/app/estadisticas/secciones/nav",array("app"=>$app))
<hr>

<div class="col-lg-6"> 
    <div class="col-lg-12 text-center">
        <h3>{{trans("app.info.usuario.edades")}}</h3>
        {{GraficoEstadistico::pie($edades[0], $edades[1], "edades_pie")}}
        <table class="table table-bordered table-striped">
            <tr><th>{{trans("app.info.usuario.edad")}}</th><th>{{trans("app.estadisticas.info.cantidad")}}</th></tr>
            @for($i=0;$i<count($edades[0]);$i++)
            <tr><td>{{$edades[0][$i]}}</td><td>{{$edades[1][$i]}}</td></tr>
            @endfor
        </table>
    </div>
</div>
<div class="col-lg-6"> 

</div>

@stop

@section("script")
{{ HTML::script('assets/plugins/chart/Chart.js') }}
@stop

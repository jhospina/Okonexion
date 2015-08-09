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
        <h3>{{trans("app.info.usuario.generos")}}</h3>

        @if(count($generos[0])>1)

        {{GraficoEstadistico::pie($generos[0], $generos[1], "genero_pie")}}
        <table class="table table-bordered table-striped">
            <tr><th>{{trans("app.info.usuario.genero")}}</th><th>{{trans("app.estadisticas.info.cantidad")}}</th></tr>
            <?php for ($i = 0; $i < count($generos[0]); $i++): ?>
                <tr><td>{{$generos[0][$i]}}</td><td>{{$generos[1][$i]}}</td></tr>
            <?php endfor; ?>
        </table>

        @else 
        <div class="col-lg-12 sin-info">
            <span class="glyphicon glyphicon glyphicon-send"></span><br/>
            <div>{{trans("app.estadisticas.info.falta.informacion")}}</div>
        </div>
        @endif
    </div>
</div>
<div class="col-lg-6"> 
    <div class="col-lg-12 text-center">
        <h3>{{trans("app.info.usuario.edades")}}</h3>
        @if(count($edades[0])>1)
        {{GraficoEstadistico::pie($edades[0], $edades[1], "edades_pie")}}
        <table class="table table-bordered table-striped">
            <tr><th>{{trans("app.info.usuario.edad")}}</th><th>{{trans("app.estadisticas.info.cantidad")}}</th></tr>
            <?php for ($i = 0; $i < count($edades[0]); $i++): ?>
                <tr><td>{{$edades[0][$i]}}</td><td>{{$edades[1][$i]}}</td></tr>
            <?php endfor; ?>
        </table>
        @else 
        <div class="col-lg-12 sin-info">
            <span class="glyphicon glyphicon glyphicon-send"></span><br/>
            <div>{{trans("app.estadisticas.info.falta.informacion")}}</div>
        </div>
        @endif
    </div>
</div>


<div class="col-lg-12"> 
    <div class="col-lg-12 text-center">
        <h3>{{trans("app.info.usuarios.aficiones")}}</h3>


        @if(count($aficiones[0])>5)
        <div class="col-lg-8 text-center" style="padding:0px;">
            {{GraficoEstadistico::barras($aficiones[0], $aficiones[1], "aficiones_bar","700","470","border: 1px #B9B9B9 solid;padding: 10px")}}
        </div>
        <div class="col-lg-4">
            <table class="table table-bordered table-striped text-left">
                <tr><th>{{trans("otros.info.descripcion")}}</th><th>{{trans("app.estadisticas.info.cantidad")}}</th></tr>
                <?php for ($i = 0; $i < count($aficiones[0]); $i++): ?>
                    <tr><td>{{$aficiones[0][$i]}}</td><td>{{$aficiones[1][$i]}}</td></tr>
                <?php endfor; ?>
            </table>
        </div>

        @else 
        <div class="col-lg-12 sin-info">
            <span class="glyphicon glyphicon glyphicon-send"></span><br/>
            <div>{{trans("app.estadisticas.info.falta.informacion")}}</div>
        </div>
        @endif
    </div>
</div>

@stop

@section("script")
{{ HTML::script('assets/plugins/chart/Chart.js') }}
@stop

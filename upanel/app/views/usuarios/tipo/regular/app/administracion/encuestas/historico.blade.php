<?php
$tipoContenido = Contenido_Encuestas::nombre;
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, $tipoContenido);
$singNombre = Util::eliminarPluralidad(strtolower($nombreContenido));
//Obtiene las respuestas y los resultados de la encuesta vigente
$respuestas = Contenido_Encuestas::obtenerRespuestas($encuesta->id);
//Total de votos del a encuesta vigente
$total_votos = intval(ContenidoApp::obtenerValorMetadato($encuesta->id, "total"));
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | Administrar {{$nombreContenido}} @stop

@section("contenido") 

{{--NAVEGACION--}}
<div class="well well-sm">
    <a href="{{URL::to("aplicacion/administrar/encuestas")}}" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Volver</a>
    <a href="{{URL::to("aplicacion/administrar/encuestas/agregar")}}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Agregar nuevo</a>
</div>
<hr/>
<h2><span class="glyphicon {{Contenido_Encuestas::icono}}"></span> {{$encuesta->titulo}}</h2>
<hr/>
<div class="col-lg-12" style="padding: 5px;padding-left: 20px;">{{$encuesta->descripcion}}</div>

<div class="panel panel-default" style="clear: both;margin-bottom:50px;">
    <div class="panel-heading">
        <h3 class="panel-title">Respuestas</h3>
    </div>
    <div class="panel-body" style="padding: 5px;">
        <div class="col-lg-12">
            <div class="col-lg-12">
                <table class="table table-hover">
                    <?php
                    $total = 0;
                    for ($i = 0; $i < count($respuestas) / Contenido_Encuestas::configNumeroParametrosRespuesta; $i++):
                        $num = $i + 1;
                        ($total_votos > 0) ? $porcentaje = round(($respuestas["total_resp" . $num] / $total_votos) * 100, 2) : $porcentaje = 0;
                        ?>

                        <tr>
                            <td style="width: 2%;background: gainsboro;">{{$num}}</td>
                            <td style="width: 45%;">{{$respuestas["resp".$num]}}</td>
                            <td style="width: 51%" class="progress">
                                <div class="progress-bar tooltip-top" title="{{$porcentaje}}%" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:{{$porcentaje}}%;">{{$porcentaje}}%</div>
                            </td>
                            <td style="width: 3%;text-align: center;">{{$respuestas["total_resp".$num]}}</td>
                        </tr>


                    <?php endfor; ?>
                    <tr><td colspan="3" style="width: 98%;text-align: right">Total</td><td style="width: 2%;">{{$total_votos}}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="col-lg-6 h3"><b>Publicado el:</b> {{$encuesta->created_at}}</div>
        <div class="col-lg-6 h3"><b>Finalizado el:</b> {{$encuesta->updated_at}}</div>
    </div>
</div>

@stop

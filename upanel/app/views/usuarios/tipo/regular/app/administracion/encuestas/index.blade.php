<?php
$tipoContenido = Contenido_Encuestas::nombre;
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, $tipoContenido);
//Obtiene las respuestas y los resultados de la encuesta vigente
$resps_vig = Contenido_Encuestas::obtenerRespuestas($encuesta_vigente->id);
//Total de votos del a encuesta vigente
$total_votos = intval(ContenidoApp::obtenerValorMetadato($encuesta_vigente->id, "total"));
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | Administrar {{$nombreContenido}} @stop

@section("contenido") 

<h2><span class="glyphicon {{Contenido_Encuestas::icono}}"></span> ADMINISTRAR {{strtoupper($nombreContenido)}}</h2>
<hr/>
@include("interfaz/mensaje/index",array("id_mensaje"=>2))

<div class="well well-sm" style="margin-top:10px;">
    <a href="{{URL::to("aplicacion/administrar/".$tipoContenido."/agregar")}}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Agregar nuevo</a>
</div>


<div class="panel panel-default" style="clear: both;">
    <div class="panel-heading">
        <h3 class="panel-title">ENCUESTA VIGENTE</h3>
    </div>
    <div class="panel-body" style="padding: 5px;">
        <div class="col-lg-12">
            <div class="col-lg-12" style="font-size: 25px;">{{$encuesta_vigente->titulo}}</div>
            <div class="col-lg-12" style="padding: 5px;padding-left: 20px;">{{$encuesta_vigente->descripcion}}</div>
            <div class="col-lg-12">
                <table class="table table-hover">
                    <?php
                    $total = 0;
                    for ($i = 0; $i < count($resps_vig) / 2; $i++):
                        $num = $i + 1;
                        ($total_votos > 0) ? $porcentaje = round(($resps_vig["total_resp" . $i] / $total_votos) * 100, 2) : $porcentaje = 0;
                        ?>

                        <tr>
                            <td style="width: 2%;background: gainsboro;">{{$num}}</td>
                            <td style="width: 45%;">{{$resps_vig["resp".$i]}}</td>
                            <td style="width: 51%" class="progress">
                                <div class="progress-bar tooltip-top" title="{{$porcentaje}}%" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:{{$porcentaje}}%;">{{$porcentaje}}%</div>
                            </td>
                            <td style="width: 3%;text-align: center;">{{$resps_vig["total_resp".$i]}}</td>
                        </tr>


                    <?php endfor; ?>
                    <tr><td colspan="3" style="width: 98%;text-align: right">Total</td><td style="width: 2%;">{{$total_votos}}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>



@stop

@section("script")

{{ HTML::script('assets/js/bootstrap-tooltip.js') }}

<script>
    jQuery(document).ready(function () {

        jQuery(".tooltip-left").tooltip({placement: "left"});
        jQuery(".tooltip-top").tooltip({placement: "top"});
    });</script>


@stop

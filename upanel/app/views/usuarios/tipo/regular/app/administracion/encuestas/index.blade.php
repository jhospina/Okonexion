<?php
$tipoContenido = Contenido_Encuestas::nombre;
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, $tipoContenido);
$singNombre = Util::eliminarPluralidad(strtolower($nombreContenido));
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


<div class="panel panel-default" style="clear: both;margin-bottom:50px;">
    <div class="panel-heading">
        <h3 class="panel-title"><span style="width: 50%;display: inline-block;">ENCUESTA VIGENTE</span><span style="width: 50%;display: inline-block;" class="text-right">{{$encuesta_vigente->created_at}}</span></h3>
      
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


@if(count($guardados)>0)

<div class="col-lg-12" style="margin-bottom: 50px;">
    <div class="col-lg-12" style="background: gainsboro;"><h3>{{ucfirst(strtolower($nombreContenido))}} guardadas</h3></div>
    <div class="col-lg-12">
        <table class="table table-striped">
            <tr><th>{{strtoupper($singNombre)}}</th><th>FECHA CREACIÓN</th><th></th></tr>
            @foreach($guardados as $encuesta)
            <tr id="encuesta-{{$encuesta->id}}"><td>{{$encuesta->titulo}}</td><td>{{$encuesta->created_at}}</td>
                <td>
                    <a href="{{URL::to("aplicacion/administrar/encuestas/editar/".$encuesta->id)}}" class="btn-sm btn-warning" style="cursor: pointer;" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                    <span class="btn-sm btn-danger" onClick="eliminarEncuesta({{$encuesta->id}},'{{str_replace("'","\"",$encuesta->titulo);}}');" style="cursor: pointer;" title="Eliminar"><span class="glyphicon glyphicon-remove-circle"></span></span>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

@endif


<div class="col-lg-12" style="margin-bottom: 20px;">
    <div class="col-lg-12" style="background: gainsboro;"><h3>Historicos</h3></div>
    <div class="col-lg-12">
        @if(count($historial)>0)
        {{$historial->links()}}
        <table class="table table-striped">
            <tr><th>{{strtoupper($singNombre)}}</th><th>VOTOS</th><th></th></tr>
            @foreach($historial as $encuesta)
            <?php $total_votos = intval(ContenidoApp::obtenerValorMetadato($encuesta->id, "total")); ?>
            <tr><td>{{$encuesta->titulo}}</td><td>{{$total_votos}}</td><td><a href="{{URL::to("aplicacion/administrar/encuestas/historico/".$encuesta->id)}}" class="btn-sm btn-info" style="cursor: pointer;" title="Ver {{strtolower($singNombre)}}"><span class="glyphicon glyphicon-eye-open"></span></a</td></tr>
            @endforeach
        </table>

        {{$historial->links()}}

        @else
        <p>No hay {{strtolower($nombreContenido)}} archivadas</p>
        @endif
    </div>
</div>

<div id="modal-eliminacion" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titulo-modal">PREACUCIÓN</h4>
            </div>
            <div class="modal-body" id="contenido-modal" style='text-align: center;'>
                ¿Estas seguro de eliminar <span id="nombre-encuesta" style="font-weight: bold;"></span>?
            </div>
            <div class="modal-footer">
                <div id="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btn-confirmar-eliminacion">Aceptar</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


@stop

@section("script")

{{ HTML::script('assets/js/bootstrap-tooltip.js') }}

<script>
    jQuery(document).ready(function () {

        jQuery(".tooltip-left").tooltip({placement: "left"});
        jQuery(".tooltip-top").tooltip({placement: "top"});
    });</script>


<script>

    function eliminarEncuesta(id_encuesta, titulo) {
        $("#titulo-modal").html("PRECAUCIÓN");
        $("#contenido-modal").html('¿Estas seguro que quieres eliminar "<span id="encuesta-titulo-modal" data-encuesta="' + id_encuesta + '" style="font-weight: bold;">' + titulo + '</span>"?');
        $('#modal-eliminacion').modal('show');
        $("#modal-footer").show();
    }

    //Envia los datos confirmados para eliminar la encuesta
    jQuery("#btn-confirmar-eliminacion").click(function () {
        var id_encuesta = $("#encuesta-titulo-modal").attr("data-encuesta");
        $("#titulo-modal").html("PROCESANDO...");
        $("#contenido-modal").html("<div class='block' style='text-align:center;'><img src='{{URL::to('assets/img/loaders/gears.gif')}}'/></div>");
        $("#modal-footer").hide();
        jQuery.ajax({
            type: "POST",
            url: "{{URL::to('aplicacion/administrar/encuesta/ajax/eliminar/encuesta')}}",
            data: {id_encuesta: id_encuesta},
            success: function (response) {
                $("#encuesta-" + id_encuesta).fadeOut(function () {
                    $("#encuesta-" + id_encuesta).remove();
                });
                setTimeout(function () {
                    $("#titulo-modal").html("¡REALIZADO CON EXITO!");
                    $("#contenido-modal").html("{{ucwords(strtolower($singNombre))}} eliminada.");
                    setTimeout(function () {
                        $('#modal-eliminacion').modal('hide');
                    }, 2000);
                }, 2000);
            }}, "html");
    });

</script>


@stop

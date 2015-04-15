<?php
$tipoContenido = Contenido_Encuestas::nombre;
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, $tipoContenido);
$singNombre = Util::eliminarPluralidad(strtolower($nombreContenido));
if(!is_null($encuesta_vigente))
{
//Obtiene las respuestas y los resultados de la encuesta vigente
$resps_vig = Contenido_Encuestas::obtenerRespuestas($encuesta_vigente->id);
//Total de votos del a encuesta vigente
$total_votos = intval(ContenidoApp::obtenerValorMetadato($encuesta_vigente->id, "total"));
}

?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | {{trans("otros.info.administrar")}} {{$nombreContenido}} @stop

@section("contenido") 

<h2><span class="glyphicon {{Contenido_Encuestas::icono}}"></span> {{Util::convertirMayusculas(trans("otros.info.administrar"))}} {{strtoupper($nombreContenido)}}</h2>
<hr/>
@include("interfaz/mensaje/index",array("id_mensaje"=>2))

<div class="well well-sm" style="margin-top:10px;">
    <a href="{{URL::to("aplicacion/administrar/".$tipoContenido."/agregar")}}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> {{trans("app.admin.btn.info.agregar_nuevo")}}</a>
</div>


<div class="panel panel-default" style="clear: both;margin-bottom:50px;">
    <div class="panel-heading">
        @if($encuesta_vigente!=null)
        <h3 class="panel-title"><span style="width: 50%;display: inline-block;">{{Util::convertirMayusculas(trans("app.admin.encuestas.info.encuesta_vigente"))}}</span><span style="width: 50%;display: inline-block;" class="text-right">{{$encuesta_vigente->created_at}}</span></h3>
        @else
        <h3 class="panel-title">{{trans("app.admin.encuestas.info.sin_encuestas",array("encuestas"=>$nombreContenido))}} <a class="link" href="{{URL::to("aplicacion/administrar/".$tipoContenido."/agregar")}}">{{trans("otros.info.quieres_crear_una")}}</a></h3>
        @endif
    </div>
    <div class="panel-body" style="padding: 5px;">
        <div class="col-lg-12">
            @if($encuesta_vigente!=null)
            <div class="col-lg-12" style="font-size: 25px;">{{$encuesta_vigente->titulo}}</div>
            <div class="col-lg-12" style="padding: 5px;padding-left: 20px;">{{$encuesta_vigente->descripcion}}</div>
            <div class="col-lg-12">
                <table class="table table-hover">
                    <?php
                    $total = 0;
                    for ($i = 0; $i < count($resps_vig) / Contenido_Encuestas::configNumeroParametrosRespuesta; $i++):
                        $num = $i + 1;
                        ($total_votos > 0) ? $porcentaje = round(($resps_vig["total_" . Contenido_Encuestas::configRespuesta . $num] / $total_votos) * 100, 2) : $porcentaje = 0;
                        ?>

                        <tr>
                            <td style="width: 2%;background: gainsboro;">{{$num}}</td>
                            <td style="width: 45%;">{{$resps_vig[Contenido_Encuestas::configRespuesta.$num]}}</td>
                            <td style="width: 51%" class="progress">
                                <div class="progress-bar tooltip-top" title="{{$porcentaje}}%" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:{{$porcentaje}}%;">{{$porcentaje}}%</div>
                            </td>
                            <td style="width: 3%;text-align: center;">{{$resps_vig["total_".Contenido_Encuestas::configRespuesta.$num]}}</td>
                        </tr>


                    <?php endfor; ?>
                    <tr><td colspan="3" style="width: 98%;text-align: right">{{trans("otros.info.total")}}</td><td style="width: 2%;">{{$total_votos}}</td></tr>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>


@if(count($guardados)>0)

<div class="col-lg-12" style="margin-bottom: 50px;">
    <div class="col-lg-12 header-list"><h3>{{ucfirst(strtolower($nombreContenido))}} {{trans("otros.info.guardadas")}}</h3></div>
    <div class="col-lg-12">
        <table class="table table-striped">
            <tr><th>{{Util::convertirMayusculas($singNombre)}}</th><th>{{Util::convertirMayusculas(trans("otros.info.fecha_creacion"))}}</th><th></th></tr>
            @foreach($guardados as $encuesta)
            <tr id="encuesta-{{$encuesta->id}}"><td>{{$encuesta->titulo}}</td><td>{{$encuesta->created_at}}</td>
                <td>
                    <a href="{{URL::to("aplicacion/administrar/encuestas/editar/".$encuesta->id)}}" class="btn-sm btn-warning" style="cursor: pointer;" title="{{trans("otros.info.editar")}}"><span class="glyphicon glyphicon-edit"></span></a>
                    <span class="btn-sm btn-danger" onClick="eliminarEncuesta({{$encuesta->id}},'{{str_replace("'","\"",$encuesta->titulo);}}');" style="cursor: pointer;" title="{{trans("otros.info.eliminar")}}"><span class="glyphicon glyphicon-remove-circle"></span></span>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

@endif


<div class="col-lg-12" style="margin-bottom: 20px;">
    <div class="col-lg-12 header-list"><h3>{{trans("otros.info.historicos")}}</h3></div>
    <div class="col-lg-12">
        @if(count($historial)>0)
        {{$historial->links()}}
        <table class="table table-striped">
            <tr><th>{{strtoupper($singNombre)}}</th><th>{{Util::convertirMayusculas(trans("otros.info.votos"))}}</th><th></th></tr>
            @foreach($historial as $encuesta)
            <?php $total_votos = intval(ContenidoApp::obtenerValorMetadato($encuesta->id, "total")); ?>
            <tr><td>{{$encuesta->titulo}}</td><td>{{$total_votos}}</td><td><a href="{{URL::to("aplicacion/administrar/encuestas/historico/".$encuesta->id)}}" class="btn-sm btn-info" style="cursor: pointer;" title="{{trans("otros.info.ver")}} {{strtolower($singNombre)}}"><span class="glyphicon glyphicon-eye-open"></span></a></td></tr>
            @endforeach
        </table>

        {{$historial->links()}}

        @else
        <p style="padding:5px;">{{trans("app.admin.encuestas.info.no_hay_archivados",array("encuestas"=>$nombreContenido))}}</p>
        @endif
    </div>
</div>

<div id="modal-eliminacion" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titulo-modal"></h4>
            </div>
            <div class="modal-body" id="contenido-modal" style='text-align: center;'>
            </div>
            <div class="modal-footer">
                <div id="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans("otros.info.cancelar")}}</button>
                    <button type="button" class="btn btn-primary" id="btn-confirmar-eliminacion">{{trans("otros.info.aceptar")}}</button>
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
            $("#titulo-modal").html("{{Util::convertirMayusculas(trans('otros.info.precaucion'))}}");
                    $("#contenido-modal").html("{{trans('app.admin.msj.eliminar',array('nombre'=>"<span id='encuesta-titulo-modal' data-encuesta='\"+id_encuesta+\"' style='font-weight: bold;'>\"+titulo+\"</span>"))}}");
                    $('#modal-eliminacion').modal('show');
                    $("#modal-footer").show();
            }

    //Envia los datos confirmados para eliminar la encuesta
    jQuery("#btn-confirmar-eliminacion").click(function () {
    var id_encuesta = $("#encuesta-titulo-modal").attr("data-encuesta");
            $("#titulo-modal").html("{{Util::convertirMayusculas(trans('otros.info.procesando'))}}...");
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
                            $("#titulo-modal").html("{{Util::convertirMayusculas(trans('otros.info.realizado_exito'))}}");
                                    $("#contenido-modal").html("<h4>{{trans('otros.info.msj.cont.eliminado',array('tipo'=>ucwords(strtolower($singNombre))))}}</h4>");
                                    setTimeout(function () {
                                    $('#modal-eliminacion').modal('hide');
                                    }, 2000);
                            }, 2000);
                    }}, "html");
    });

</script>


@stop

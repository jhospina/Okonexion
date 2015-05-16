<?php
$tipoContenido = Contenido_Institucional::nombre;
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, $tipoContenido);
$singNombre = Util::eliminarPluralidad(strtolower($nombreContenido));
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | {{trans("otros.info.administrar")}} {{$nombreContenido}} @stop

@section("css")
<style>
    #listado-institucional .{{ContenidoApp::ESTADO_GUARDADO}}{
    color:gray;
}

#listado-institucional .{{ContenidoApp::ESTADO_PUBLICO}}{
    color:green;
}
    </style>
@stop

@section("contenido") 

<h2><span class="glyphicon {{Contenido_Institucional::icono}}"></span> {{Util::convertirMayusculas(trans("otros.info.administrar")." ".$nombreContenido)}}</h2>
<hr/>
@include("interfaz/mensaje/index",array("id_mensaje"=>2))

<div class="well well-sm" style="margin-top:10px;">
    <a href="{{URL::to("aplicacion/administrar/".$tipoContenido."/agregar")}}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> {{trans("app.admin.btn.info.agregar_nuevo")}}</a>
</div>

@if(count($insts)>0)
{{$insts->links()}}

<div class="col-lg-12" id="listado-institucional">
    <table class="table table-striped">
        <tr><th>{{Util::convertirMayusculas(trans("otros.info.titulo"))}}</th>
            <th>{{Util::convertirMayusculas(trans("otros.info.fecha_creacion"))}}</th>
            <th>{{Util::convertirMayusculas(trans("otros.info.ultima_actualizacion"))}}</th>
            <th>{{Util::convertirMayusculas(trans("otros.info.estado"))}}</th><th>
            </th></tr>
        @foreach($insts as $inst)
        <tr id="inst-{{$inst->id}}"><td>{{$inst->titulo}}</td><td>{{$inst->created_at}}</td><td>{{$inst->updated_at}}</td><td class="{{$inst->estado}}">{{ContenidoApp::obtenerNombreEstado($inst->estado)}}</td>
            <td>
                <a title="{{trans('otros.info.editar')}}" href="{{URL::to("aplicacion/administrar/institucional/editar/".$inst->id)}}" class="btn-sm btn-warning"><span class="glyphicon glyphicon-edit"></span></a>
                <span title="{{trans('otros.info.eliminar')}}" class="btn-sm btn-danger" onclick="eliminarInst({{$inst->id}},'{{str_replace("'","\"",$inst->titulo);}}');"><span class="glyphicon glyphicon-remove-circle"></span></span>
            </td>
        </tr>
        @endforeach
    </table>
</div>

<div class="block">
    {{$insts->links()}}
</div>



<div class="col-lg-12" style="margin-top: 40px;margin-bottom: 10px">
    <div class="col-lg-6" style="font-size: 24px;padding: 0px;">
        <span class="glyphicon glyphicon-sort"></span> {{trans("app.admin.inst.info.ordenar")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans("app.admin.inst.info.ordenar.ayuda")))
        <span id="msj-respuesta">{{trans("app.admin.inst.info.orden_guardado")}}</span>
    </div>
    <div class="col-lg-6 text-right" style="padding-right: 0px;">
        <button onClick="guardarOrden();" id="btn-guardar-orden" class="btn btn-info"><span class="glyphicon glyphicon-save"></span> {{trans("app.admin.inst.btn.info.guardar_orden")}}</button>
    </div>
</div>

<div class="col-lg-12" id="content-posicionar-inst">
    <ul id="posicionar-inst">
        <?php foreach ($orden_insts as $meta): $inst = ContenidoApp::find(intval($meta->id_contenido)); ?>
            
        @if($inst->esPublico())
            <li data-id-inst="{{$inst->id}}" id="orden-{{$inst->id}}"><span class="glyphicon glyphicon-resize-vertical"></span> {{$inst->titulo}}</li>
        @endif
        <?php endforeach ?>
    </ul>
</div>




@else
<div class="col-lg-12 text-center h3">{{Util::convertirMayusculas(trans("app.admin.info.no_hay_informacion"))}}</div>
@endif




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

{{HTML::script("assets/plugins/html5sortable/jquery.sortable.js")}}

<script>
            jQuery(document).ready(function(){
    $('#posicionar-inst').sortable();
    });</script>

<script>



            function eliminarInst(id_inst, titulo){
            $("#titulo-modal").html("{{Util::convertirMayusculas(trans('otros.info.precaucion'))}}");
                    $("#contenido-modal").html("{{trans('app.admin.msj.eliminar',array('nombre'=>"<span id='inst-titulo-modal' data-inst='\"+id_inst+\"' style='font-weight: bold;'>\"+titulo+\"</span>"))}}");
                                    
                    $('#modal-eliminacion').modal('show');
                    $("#modal-footer").show();
            }

    //Envia los datos confirmados para eliminar la inst
    jQuery("#btn-confirmar-eliminacion").click(function(){
    var id_inst = $("#inst-titulo-modal").attr("data-inst");
            $("#titulo-modal").html("{{Util::convertirMayusculas(trans('otros.info.procesando'))}}...");
            $("#contenido-modal").html("<div class='block' style='text-align:center;'><img src='{{URL::to('assets/img/loaders/gears.gif')}}'/></div>");
            $("#modal-footer").hide();
            jQuery.ajax({
            type: "POST",
                    url: "{{URL::to('aplicacion/administrar/institucional/ajax/eliminar/institucional')}}",
                    data: {id_inst:id_inst},
                    success: function (response) {
                    $("#orden-" + id_inst).fadeOut();
                            $("#inst-" + id_inst).fadeOut(function(){
                    $("#inst-" + id_inst).remove();
                            $("#orden-" + id_inst).remove();
                    });
                            setTimeout(function(){
                            $("#titulo-modal").html("{{Util::convertirMayusculas(trans('otros.info.realizado_exito'))}}");
                                    $("#contenido-modal").html("<h4>{{trans('otros.info.msj.cont.eliminado',array('tipo'=>ucwords(strtolower($singNombre))))}}</h4>");
                                    
                                    setTimeout(function(){
                                    $('#modal-eliminacion').modal('hide');
                                    }, 2000);
                            }, 2000);
                    }}, "html");
    });
            function guardarOrden(){
            var orden = "";
                    jQuery("#btn-guardar-orden").attr("disabled", "disabled");
                    jQuery("#btn-guardar-orden").html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.guardando')}}...");
                    $("#posicionar-inst li").each(function(){
            orden += $(this).attr("data-id-inst") + ",";
            });
            
            orden=orden.substring(0,orden.length-1);
            
                    jQuery.ajax({
                    type: "POST",
                            url: "{{URL::to('aplicacion/administrar/institucional/ajax/guardar/orden')}}",
                            data: {orden:orden},
                            success: function (response) {
                                jQuery("#msj-respuesta").fadeIn(function(){
                                    setTimeout(function(){
                                        jQuery("#msj-respuesta").fadeOut();
                                    },2000);
                                });
                            jQuery("#btn-guardar-orden").removeAttr("disabled");
                                    jQuery("#btn-guardar-orden").html("<span class='glyphicon glyphicon-save'></span> {{trans('app.admin.inst.btn.info.guardar_orden')}}");
                            }}, "html");
            }


</script>

@stop

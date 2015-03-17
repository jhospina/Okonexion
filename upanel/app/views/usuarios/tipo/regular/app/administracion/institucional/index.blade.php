<?php
$tipoContenido = Contenido_Institucional::nombre;
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, $tipoContenido);
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | Administrar {{$nombreContenido}} @stop

@section("css")
{{ HTML::style('assets/css/upanel/insts.css', array('media' => 'screen')) }}
@stop

@section("contenido") 

<h2><span class="glyphicon glyphicon-education"></span> ADMINISTRAR {{strtoupper($nombreContenido)}}</h2>
<hr/>
@include("interfaz/mensaje/index",array("id_mensaje"=>2))

<div class="well well-sm" style="margin-top:10px;">
    <a href="{{URL::to("aplicacion/administrar/".$tipoContenido."/agregar")}}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Agregar nuevo</a>
</div>

@if(count($insts)>0)
{{$insts->links()}}

<div class="col-lg-12" id="listado-institucional">
    <table class="table table-striped">
        <tr><th>TITULO</th><th>FECHA DE CREACIÓN</th><th>ULTIMA ACTUALIZACIÓN</th><th>ESTADO</th><th></th></tr>
        @foreach($insts as $inst)
        <tr id="inst-{{$inst->id}}"><td>{{$inst->titulo}}</td><td>{{$inst->created_at}}</td><td>{{$inst->updated_at}}</td><td class="{{$inst->estado}}">{{ucfirst($inst->estado)}}</td>
            <td>
                <a title="Editar" href="{{URL::to("aplicacion/administrar/institucional/editar/".$inst->id)}}" class="btn-sm btn-warning"><span class="glyphicon glyphicon-edit"></span></a>
                <span title="Eliminar" class="btn-sm btn-danger" onclick="eliminarInst({{$inst->id}},'{{str_replace("'","\"",$inst->titulo);}}');"><span class="glyphicon glyphicon-remove-circle"></span></span>
            </td>
        </tr>
        @endforeach
    </table>
</div>

<div class="block">
    {{$insts->links()}}
</div>

@else
<div class="col-lg-12 text-center h3">NO INFORMACIÓN PARA MOSTRAR</div>
@endif




<div id="modal-eliminacion" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titulo-modal">PREACUCIÓN</h4>
            </div>
            <div class="modal-body" id="contenido-modal" style='text-align: center;'>
                ¿Estas seguro de eliminar <span id="nombre-inst" style="font-weight: bold;"></span>?
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

<script>

            function eliminarInst(id_inst, titulo){
            $("#titulo-modal").html("PRECAUCIÓN");
                    $("#contenido-modal").html('¿Estas seguro que quieres eliminar <span id="inst-titulo-modal" data-inst="' + id_inst + '" style="font-weight: bold;">' + titulo + '</span>?');
                    $('#modal-eliminacion').modal('show');
                    $("#modal-footer").show();
            }



    //Envia los datos confirmados para eliminar la inst
    jQuery("#btn-confirmar-eliminacion").click(function(){
    var id_inst = $("#inst-titulo-modal").attr("data-inst");
            $("#titulo-modal").html("PROCESANDO...");
            $("#contenido-modal").html("<div class='block' style='text-align:center;'><img src='{{URL::to('assets/img/loaders/gears.gif')}}'/></div>");
            $("#modal-footer").hide();
            jQuery.ajax({
            type: "POST",
                    url: "{{URL::to('aplicacion/administrar/institucional/ajax/eliminar/institucional')}}",
                    data: {id_inst:id_inst},
                    success: function (response) {
                    $("#inst-" + id_inst).fadeOut(function(){
                    $("#inst-" + id_inst).remove();
                    });
                            setTimeout(function(){
                            $("#titulo-modal").html("¡REALIZADO CON EXITO!");
                                    $("#contenido-modal").html("{{ucwords(strtolower($nombreContenido))}} eliminada.");
                                    setTimeout(function(){
                                    $('#modal-eliminacion').modal('hide');
                                    }, 2000);
                            }, 2000);
                    }}, "html");
    });

</script>

@stop

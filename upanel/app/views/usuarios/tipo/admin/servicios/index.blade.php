<?php ?>
@extends('interfaz/plantilla')

@section("titulo") {{trans("interfaz.menu.principal.servicios")}} @stop


@section("contenido") 

<h1><span class="glyphicon glyphicon-flash"></span> {{trans("interfaz.menu.principal.servicios")}}</h1>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))


<table class="table table-striped">

    <tr>
        <th>{{trans("fact.col.numero.factura")}}</th>
        <th>{{trans("otros.info.fecha")}}</th>
        @if(User::esSuperAdmin())
        <th>{{trans("otros.info.instancia")}}</th>
        @endif
        <th>{{trans("otros.info.cliente")}}</th>
        <th>{{trans("otros.info.servicio")}}</th>
        <th>{{trans("otros.pregunta.procesado")}}</th>
        <th></th>

    </tr>

    @foreach($factServicios as $fact)
    <?php
    $cliente = User::find($fact->id_usuario);
    $id_servicio = intval(str_replace(Servicio::CONFIG_NOMBRE, "", $fact->valor));
    $servicio = Servicio::find($id_servicio);
    $procesado = Util::convertirIntToBoolean(Facturacion::obtenerValorMetadato(MetaFacturacion::PRODUCTO_PROCESADO . $id_servicio, $fact->id));
    ?>
    <tr>
        <td><a target="_blank" href="{{URL::to('fact/factura/'.$fact->id)}}">{{$fact->id}}</a></td>
        <td>{{Fecha::formatear(Facturacion::obtenerValorMetadato(MetaFacturacion::FECHA_PAGO, $fact->id));}}</td>
        @if(User::esSuperAdmin())
        <td>
            @if($fact->instancia==User::PARAM_INSTANCIA_SUPER_ADMIN)
            {{trans("instancias.default.super")}}
            @else
            <?php $instancia = Instancia::find($fact->instancia); ?>
            {{$instancia->empresa}}  
            @endif
        </td>
        @endif
        <td><a target="_blank" href="{{Route('usuario.show',$cliente->id)}}">{{$cliente->nombres}} {{$cliente->apellidos}}</a></td>
        <td>{{$servicio->getNombre()}}</td>
        <td id='estado-td{{$fact->id}}{{$id_servicio}}'>{{($procesado) ? trans("otros.info.si") : trans("otros.info.no");}}</td>
        <td id='btn-td{{$fact->id}}{{$id_servicio}}'>
            @if(!$procesado)
            <button class="btn btn-sm btn-primary" onclick="modalProcesar({{$fact->id}},{{$servicio->id}},'{{$servicio->getNombre()}}')"><span class="glyphicon glyphicon-cog"></span> {{trans("otros.info.procesar")}}</button>
            @else
            <button class="btn btn-sm btn-default" onclick="modalActualizar({{$fact->id}},{{$servicio->id}},'{{$servicio->getNombre()}}')"><span class="glyphicon glyphicon-edit"></span> {{trans("otros.info.actualizar")}}</button>
            @endif
        </td>
    </tr>

    @endforeach

</table>

{{$factServicios->links()}}




<div id="modal-procesar" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id='titulo-modal'></h4>
            </div>
            <div class="modal-body" style="clear:both;">
                <label>{{trans("otros.info.observaciones")}}</label>
                <textarea id='observaciones' style='height:100px;' class="form-control"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{trans("otros.info.cancelar")}}</button>
                <button type="button" class="btn btn-primary" id='btn-terminar' data-dismiss="modal">{{trans("otros.info.terminar")}}</button>
            </div>
        </div>
    </div>
</div>



@stop


@section("script")

<script>

            function modalProcesar(id_factura, id_servicio, nombre_servicio){
            $("#titulo-modal").html(nombre_servicio + " #" + id_factura);
                    $("#btn-terminar").attr("data-id_servicio", id_servicio);
                    $("#btn-terminar").attr("data-id_factura", id_factura);
                    $("#modal-procesar").modal("show");
            }



    function modalActualizar(id_factura, id_servicio, nombre_servicio){
    $("#observaciones").val("");
            $("#titulo-modal").html("{{trans('otros.info.actualizar')}} - " + nombre_servicio + " #" + id_factura);
            $("#btn-terminar").attr("data-id_servicio", id_servicio);
            $("#btn-terminar").attr("data-id_factura", id_factura);
            $("#modal-procesar").modal("show");
            jQuery.ajax({
            type: "POST",
                    url: "{{URL::to('servicios/ajax/obtener/observacion')}}",
                    data:{id_factura:id_factura, id_servicio:id_servicio},
                    success: function (data) {
                    data = jQuery.parseJSON(data);
                            $("#observaciones").val(data.observacion);
                    }}, "html");
    }



    $("#btn-terminar").click(function(){

    var btn = this;
            jQuery(btn).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.procesando')}}...");
            jQuery(btn).attr("disabled", "disabled");
            var id_factura = $(btn).attr("data-id_factura");
            var id_servicio = $(btn).attr("data-id_servicio");
            jQuery.ajax({
            type: "POST",
                    url: "{{URL::to('servicios/ajax/procesar')}}",
                    data:{id_factura:id_factura, id_servicio:id_servicio, observaciones:$("#observaciones").val()},
                    success: function (data) {
                    data = jQuery.parseJSON(data);
                            jQuery(btn).html("{{trans('otros.info.terminar')}}");
                            jQuery(btn).removeAttr("disabled");
                            observaciones:$("#observaciones").val("");
                            $("#modal-procesar").modal("hide");
                            $("#estado-td" + id_factura + id_servicio).html("{{trans('otros.info.si')}}");
                            $("#btn-td" + id_factura + id_servicio).html("<button class='btn btn-sm btn-default' onclick='modalActualizar(" + id_factura + "," + id_servicio + ",\"\")'> <span class='glyphicon glyphicon-edit'> </span> {{trans('otros.info.actualizar')}}</button>");
                    }}, "html");
    });


</script>

@stop
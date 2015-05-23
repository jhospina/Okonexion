<?php
$moneda = Instancia::obtenerValorMetadato(ConfigInstancia::info_moneda);
list($decimales, $sep_millar, $sep_decimal) = Monedas::formato($moneda);
?>

@extends('interfaz/plantilla')

@section("titulo") {{trans("interfaz.menu.principal.config")}}/{{trans("interfaz.menu.principal.config.servicios")}} @stop


@include("usuarios/tipo/admin/config/secciones/css")

@section("css2")

<style>
    .estado-{{Servicio::ESTADO_ACTIVO}}{
        color:green;
        text-align: center;
    }

    .estado-{{Servicio::ESTADO_INACTIVO}}{
        color:red;
        text-align: center;
    }

    #tabla-servicios{
        font-size:12pt;
    }

</style>

@stop


@section("contenido") 

<h1><span class="glyphicon glyphicon-cog"></span> {{trans("interfaz.menu.principal.config")}}</h1>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))

@include("usuarios/tipo/admin/config/secciones/nav")

<div class="col-lg-9" id="content-config">

    <h2 class="text-right"><span class="glyphicon glyphicon-flash"></span> {{trans("interfaz.menu.principal.config.servicios")}}</h2>

    <div class='text-right' style="margin-bottom: 10px;">
        <button class='btn btn-success' id='btn-agregar-servicio'><span class='glyphicon glyphicon-plus-sign'></span> {{trans("config.servicios.seccion.agregar.servicio")}}</button>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading"></div>
        <div class="panel-body">

            <table id="tabla-servicios" class="table table-striped">
                <tr><th>{{trans("otros.info.nombre")}}</th><th>{{trans("config.servicios.seccion.agregar.servicio.op.costo")}}</th><th style="text-align: center;">{{trans("otros.info.estado")}}</th><th></th></tr>
               @if(count($servicios)>0)
                @foreach($servicios as $servicio)
                <tr><td>{{$servicio->nombre}}</td>
                    <td>{{Monedas::nomenclatura($moneda,Monedas::formatearNumero($moneda,$servicio->costo))}}</td>
                    <td id="estado-{{$servicio->id}}" class="estado-{{$servicio->estado}}">
                        @if($servicio->estado==Servicio::ESTADO_ACTIVO)
                        <span title="{{trans("atributos.estado.servicio.activo")}}" class="tooltip-top glyphicon glyphicon-ok-circle"></span>
                        @endif
                        @if($servicio->estado==Servicio::ESTADO_INACTIVO)
                        <span title="{{trans("atributos.estado.servicio.inactivo")}}" class="tooltip-top glyphicon glyphicon-remove-sign"></span>
                        @endif
                    </td>
                    <td>
                        @if($servicio->estado==Servicio::ESTADO_ACTIVO)
                        <button type="button" onClick="cambiarEstado(this,{{$servicio->id}},'{{$servicio::ESTADO_INACTIVO}}')" class="btn-sm btn-danger tooltip-top" title='{{trans("otros.info.desactivar")}}'> <span  class="glyphicon glyphicon-remove-sign"></span> </button>
                        @endif
                        @if($servicio->estado==Servicio::ESTADO_INACTIVO)
                        <button type="button" onClick="cambiarEstado(this,{{$servicio->id}},'{{$servicio::ESTADO_ACTIVO}}')" class="btn-sm btn-success tooltip-top" title='{{trans("otros.info.activar")}}'> <span  class="glyphicon glyphicon glyphicon-ok-circle"></span></button> 
                        @endif
                    </td>
                </tr>
                @endforeach
                @else
                <tr><td id="msj-no-datos" cols="6">{{trans("otros.info.no_hay_datos")}}</td></tr>
                @endif
            </table>

        </div>

    </div>



</div>



<div id="modal-crear" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{trans("config.servicios.seccion.agregar.servicio")}}</h4>
            </div>
            <div class="modal-body" style="clea:both;height:200px;">
                <div class="col-lg-6">{{trans("config.servicios.seccion.agregar.servicio.op.nombre")}}</div>
                <div class="col-lg-6"><input type="text" id="{{Servicio::COL_NOMBRE}}" class="form-control" /></div>

                <div class="col-lg-6" style="margin-top:10px;">
                    {{--COSTO--}}
                    {{trans("config.servicios.seccion.agregar.servicio.op.costo")}}
                </div>
                <div class="col-lg-6" style="margin-top:10px;">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{Monedas::simbolo($moneda)}}</div>
                            <input type="text"id="{{Servicio::COL_COSTO}}" class="form-control" onkeyup="formatoMoneda(this)" onkeypress="return soloNumeros(this,'{{$sep_decimal}}');" value=""/>
                            <div class="input-group-addon">{{$moneda}}</div></div>
                    </div>
                </div>
                <div class="col-lg-6">{{trans("config.servicios.seccion.agregar.servicio.op.descripcion")}}</div>
                <div class="col-lg-6" style="margin-bottom: 10px;"><textarea id="{{Servicio::COL_DESCRIPCION}}" class="form-control" ></textarea></div>
                <div id="msj-error-modal" style="clear:both;color:red;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{trans("otros.info.cancelar")}}</button>
                <button type="button" class="btn btn-primary" id="btn-guardar">{{trans("otros.info.guardar")}}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



@stop

@include("usuarios/tipo/admin/config/secciones/script")

@section("script2")
<script>
                    $("#btn-guardar").click(function(){

            var btn = this;
                    var nombre = $("#{{Servicio::COL_NOMBRE}}").val();
                    var costo = $("#{{Servicio::COL_COSTO}}").val();
                    var descripcion = $("#{{Servicio::COL_DESCRIPCION}}").val();
                    $("#{{Servicio::COL_NOMBRE}}").attr("disabled", "disabled");
                    $("#{{Servicio::COL_DESCRIPCION}}").attr("disabled", "disabled");
                    $("#{{Servicio::COL_COSTO}}").attr("disabled", "disabled");
                    jQuery(btn).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.guardando')}}...");
                    jQuery(btn).attr("disabled", "disabled");
                    jQuery.ajax({
                    type: "POST",
                            url: "{{URL::to('config/ajax/agregar/servicio')}}",
                            data: {"{{Servicio::COL_NOMBRE}}":nombre, "{{Servicio::COL_COSTO}}":costo, "{{Servicio::COL_DESCRIPCION}}":descripcion},
                            success: function (data) {

                            data = jQuery.parseJSON(data);
                                    if (data.error){
                            $("#msj-error-modal").html("{{trans('config.general.seccion.error.validacion')}}");
                            } else{
                                if($("#msj-no-datos"))
                                    $("#msj-no-datos").remove();
                            $("#modal-crear").modal("hide");
                                    $("#{{Servicio::COL_NOMBRE}}").val("");
                                    $("#{{Servicio::COL_COSTO}}").val("");
                                    $("#{{Servicio::COL_DESCRIPCION}}").val("");
                                    agregarHtmlServicio(data.{{Servicio::COL_ID}}, data.{{Servicio::COL_NOMBRE}}, data.{{Servicio::COL_COSTO}}, data.{{Servicio::COL_ESTADO}});
                            }

                            $("#{{Servicio::COL_NOMBRE}}").removeAttr("disabled");
                                    $("#{{Servicio::COL_DESCRIPCION}}").removeAttr("disabled");
                                    $("#{{Servicio::COL_COSTO}}").removeAttr("disabled");
                                    jQuery(btn).removeAttr("disabled");
                                    jQuery(btn).html("{{trans('otros.info.guardar')}}");
                            }}, "html");
            });
                    function agregarHtmlServicio(id, nombre, costo, estado){
                    var html = "<tr><td>" + nombre + "</td>";
                            html += "<td>" + costo + "</td>";
                            html += "<td id='estado-" + id + "' class='estado-" + estado + "'>";
                            html += " <span title='{{trans('atributos.estado.servicio.inactivo')}}' class='tooltip-top glyphicon glyphicon-remove-sign'></span></td>";
                            html += "<td><button type='button' onclick='cambiarEstado(this," + id + ",\"{{Servicio::ESTADO_ACTIVO}}\")' class='btn-sm btn-success tooltip-top' title='{{trans('otros.info.activar')}}'> <span  class='glyphicon glyphicon glyphicon-ok-circle'></span></button></td></tr>";
                            $("#tabla-servicios").append(html);
                    }

</script>


<script>
            function cambiarEstado(btn, id_servicio, estado){

            jQuery(btn).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span>");
                    jQuery(btn).attr("disabled", "disabled");
                    jQuery.ajax({
                    type: "POST",
                            url: "{{URL::to('config/ajax/estado/servicio')}}",
                            data: {"{{Servicio::COL_ID}}":id_servicio, "{{Servicio::COL_ESTADO}}":estado},
                            success: function (data) {

                            data = jQuery.parseJSON(data);
                                    if (data.error){
                            }
                            else{
                            if (estado != "{{Servicio::ESTADO_ACTIVO}}"){
                            $(btn).removeClass("btn-danger");
                                    $(btn).addClass("btn-success");
                                    $(btn).html("<span  class='glyphicon glyphicon glyphicon-ok-circle'></span>");
                                    $(btn).attr("title", "{{trans('otros.info.activar')}}");
                                    $(btn).attr("onClick", "cambiarEstado(this," + id_servicio + ",'{{Servicio::ESTADO_INACTIVO}}')");
                                    $("#estado-" + id_servicio).html('<span title="{{trans("atributos.estado.servicio.inactivo")}}" class="tooltip-top glyphicon glyphicon-remove-sign"></span>');
                                    $("#estado-" + id_servicio).attr("class", "estado-" + estado);
                            } else{
                            $(btn).removeClass("btn-sucess");
                                    $(btn).addClass("btn-danger");
                                    $(btn).html("<span  class='glyphicon glyphicon glyphicon-remove-circle'></span>");
                                    $(btn).attr("title", "{{trans('otros.info.desactivar')}}");
                                    $(btn).attr("onClick", "cambiarEstado(this," + id_servicio + ",'{{Servicio::ESTADO_ACTIVO}}')");
                                    $("#estado-" + id_servicio).html('<span title="{{trans("atributos.estado.servicio.activo")}}" class="tooltip-top glyphicon glyphicon-ok-circle"></span>');
                                    $("#estado-" + id_servicio).attr("class", "estado-" + estado);
                            }
                            }

                            }}, "html");
            }
</script>


<script>
            $("#btn-agregar-servicio").click(function(){
            $("#modal-crear").modal("show");
            })
</script>

<script>
                    function formatoMoneda(obj) {
                    var numero = $(obj).val();
                            $(obj).val(formatearNumero(numero, "{{$sep_millar}}", "{{$sep_decimal}}"));
                    }
</script>

@stop
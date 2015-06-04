<?php
$moneda = Instancia::obtenerValorMetadato(ConfigInstancia::info_moneda);
$monedas = Monedas::listado();
$idiomas = array_reverse(Idioma::listado());
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
                <tr><th>{{trans("otros.info.nombre")}}</th>
                    <th>{{trans("config.servicios.seccion.agregar.servicio.op.costo")}}</th>
                    <th>{{trans("otros.info.editar")}}</th>
                    <th style="text-align: center;">{{trans("otros.info.estado")}}</th>
                    <th></th></tr>
                @if(count($servicios)>0)
                @foreach($servicios as $servicio)
                <tr>
                    <td>{{$servicio->getNombre()}}</td>
                    <td>{{Monedas::nomenclatura($moneda,Monedas::formatearNumero($moneda,$servicio->getCosto()))}}</td>
                    <td> 
                        <button type="button" onClick="cargarEdicion({{$servicio->id}})" class="btn-sm btn-warning"><span class="glyphicon glyphicon-edit"></span></button>
                    </td>
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
                <h4 class="modal-title"><span class="glyphicon glyphicon-flash"></span> {{trans("config.servicios.seccion.agregar.servicio")}}</h4>
            </div>
            <div class="modal-body" style="clear:both;">

                <nav class="navbar navbar-default">
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            @foreach($idiomas as $index => $codigo_idioma)
                            <li class="@if(key($idiomas)==$index) active @endif nav-idiomas" onClick="pasarIdioma(this,'{{$codigo_idioma}}');" style="text-transform: uppercase;"><a>{{$codigo_idioma}}</a></li>
                            @endforeach
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </nav>

                @foreach($idiomas as $index => $codigo_idioma)
                <div id="content-details-{{$codigo_idioma}}" class="content-details" style="display:{{(key($idiomas)==$index)?"block":"none"}};">

                    <div class="col-lg-6" style="margin-bottom: 10px;">{{trans("config.servicios.seccion.agregar.servicio.op.nombre")}}</div>
                    <div class="col-lg-6" style="margin-bottom: 10px;"><input type="text" id="{{Servicio::CONFIG_NOMBRE}}{{$codigo_idioma}}" class="form-control {{Servicio::CONFIG_NOMBRE}}" /></div>

                    <div class="col-lg-6">{{trans("config.servicios.seccion.agregar.servicio.op.descripcion")}}</div>
                    <div class="col-lg-6" style="margin-bottom: 10px;"><textarea id="{{Servicio::CONFIG_DESCRIPCION}}{{$codigo_idioma}}" class="form-control {{Servicio::CONFIG_DESCRIPCION}}" ></textarea></div>
                </div>
                @endforeach

                @foreach($monedas as $codigo_moneda => $nom_moneda)
                <?php list($decimales, $sep_millar, $sep_decimal) = Monedas::formato($codigo_moneda); ?>
                <div class="col-lg-6">
                    {{--COSTO--}}
                    {{trans("config.servicios.seccion.agregar.servicio.op.costo")}}
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{Monedas::simbolo($codigo_moneda)}}</div>
                            <input type="text" id="{{Servicio::CONFIG_COSTO}}{{$codigo_moneda}}" class="form-control {{Servicio::CONFIG_COSTO}}" onkeyup="formatoMoneda(this,'{{$sep_millar}}','{{$sep_decimal}}')" onkeypress="return soloNumeros(this,'{{$sep_decimal}}');" value=""/>
                            <div class="input-group-addon">{{$codigo_moneda}}</div></div>
                    </div>
                </div>
                @endforeach
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
                                    var nombre = {};
                                    var costo = {};
                                    var descripcion = {};
                                    $(".{{Servicio::CONFIG_NOMBRE}}").attr("disabled", "disabled");
                                    $(".{{Servicio::CONFIG_DESCRIPCION}}").attr("disabled", "disabled");
                                    $(".{{Servicio::CONFIG_COSTO}}").attr("disabled", "disabled");
                                    jQuery(btn).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.guardando')}}...");
                                    jQuery(btn).attr("disabled", "disabled");
                                    //Obtiene el nombre del servicio en los distintos idiomas
                                    $(".{{Servicio::CONFIG_NOMBRE}}").each(function(){
                            nombre[$(this).attr("id")] = $(this).val();
                            });
                                    //Obtiene la descripcion en los difentes idiomas
                                    $(".{{Servicio::CONFIG_DESCRIPCION}}").each(function(){
                            descripcion[$(this).attr("id")] = $(this).val();
                            });
                                    $(".{{Servicio::CONFIG_COSTO}}").each(function(){
                            costo[$(this).attr("id")] = $(this).val();
                            });
                                    jQuery.ajax({
                                    type: "POST",
                                            url: "{{URL::to('config/ajax/agregar/servicio')}}",
                                            data: {"{{Servicio::CONFIG_NOMBRE}}":JSON.stringify(nombre), "{{Servicio::CONFIG_DESCRIPCION}}":JSON.stringify(descripcion), "{{Servicio::CONFIG_COSTO}}":JSON.stringify(costo)},
                                            success: function (data) {

                                            data = jQuery.parseJSON(data);
                                                    if (data.error){
                                            $("#msj-error-modal").html("{{trans('config.general.seccion.error.validacion')}}");
                                            } else{
                                            resetearForm();
                                                    agregarHtmlServicio(data["{{Servicio::COL_ID}}"], data["{{Servicio::CONFIG_NOMBRE}}"], data["{{Servicio::CONFIG_COSTO}}"], data["{{Servicio::COL_ESTADO}}"]);
                                            }

                                            $(".{{Servicio::CONFIG_NOMBRE}}").removeAttr("disabled");
                                                    $(".{{Servicio::CONFIG_DESCRIPCION}}").removeAttr("disabled");
                                                    $(".{{Servicio::CONFIG_COSTO}}").removeAttr("disabled");
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

                            function pasarIdioma(nav, idioma){
                            $(".nav-idiomas").removeClass("active");
                                    $(nav).addClass("active");
                                    $(".content-details").hide(0, function(){
                            $("#content-details-" + idioma).show();
                            });
                            }

                            $("#btn-agregar-servicio").click(function(){
                            resetearForm();
                                    $("#modal-crear").modal("show");
                            });
                                    function resetearForm(){
                                    if ($("#msj-no-datos"))
                                            $("#msj-no-datos").remove();
                                            $("#modal-crear").modal("hide");
                                            $(".{{Servicio::CONFIG_NOMBRE}}").val("");
                                            $(".{{Servicio::CONFIG_COSTO}}").val("");
                                            $(".{{Servicio::CONFIG_DESCRIPCION}}").val("");
                                    }

</script>


<script>

                            function cargarEdicion(id_servicio){
                           // $("#modal-crear").modal("show");
                                    jQuery.ajax({
                                    type: "POST",
                                            url: "{{URL::to('config/ajax/obtener/servicio')}}",
                                            data: {"{{Servicio::COL_ID}}":id_servicio},
                                            success: function (data) {

                                            data = jQuery.parseJSON(data);
                                                    console.log(data);
                                            }}, "html");
                            }

</script>




<script>
                            function formatoMoneda(obj, millar, decimal) {
                            var numero = $(obj).val();
                                    $(obj).val(formatearNumero(numero, millar, decimal));
                            }
</script>

@stop

<?php
/*
 *  
 */
?>
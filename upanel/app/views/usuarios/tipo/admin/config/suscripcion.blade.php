<?php
$moneda = Instancia::obtenerValorMetadato(ConfigInstancia::info_moneda);
list($decimales, $sep_millar, $sep_decimal) = Monedas::formato($moneda);
?>

@extends('interfaz/plantilla')

@section("titulo") {{trans("interfaz.menu.principal.config")}}/{{trans("interfaz.menu.principal.config.suscripcion")}} @stop


@include("usuarios/tipo/admin/config/secciones/css")


@section("contenido") 

<h1><span class="glyphicon glyphicon-cog"></span> {{trans("interfaz.menu.principal.config")}}</h1>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))

@include("usuarios/tipo/admin/config/secciones/nav")

<div class="col-lg-9" id="content-config">

    <h2 class="text-right"><span class="glyphicon glyphicon-piggy-bank"></span> {{trans("interfaz.menu.principal.config.suscripcion")}}</h2>

    <form id="form-config" action="{{URL::to("config/post/guardar")}}" method="POST">

        <!--**************************************************************************************-->
        <!--PLAN BRONCE***************************************************************************-->
        <!--**************************************************************************************-->

        <div class="panel panel-primary">
            <div class="panel-heading"><span class="glyphicon glyphicon-certificate" style="color:burlywood;"></span> {{trans("config.suscripcion.seccion.plan_bronce.titulo")}}</div>
            <div class="panel-body">
                <div class="col-lg-6">
                    {{--MENSUAL--}}
                    <label> {{trans("config.suscripcion.seccion.planes.op.valor.1mensual")}}</label>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{Monedas::simbolo($moneda)}}</div>
                            <input type="text" class="form-control" onblur="calcularValores()" onkeyup="formatoMoneda(this)" onkeypress="return soloNumeros(this,'{{$sep_decimal}}');" id="{{ConfigInstancia::suscripcion_valor_1mes_bronce}}" name="{{ConfigInstancia::suscripcion_valor_1mes_bronce}}" value="{{Monedas::formatearNumero($moneda,Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_1mes_bronce))}}"/>
                            <div class="input-group-addon">{{$moneda}}</div></div>
                    </div>
                </div>
                {{--3 MESES--}}
                <div class="col-lg-6">
                    <label>  {{trans("config.suscripcion.seccion.planes.op.valor.3mensual")}}</label> <span id="label-descuento-{{ConfigInstancia::suscripcion_valor_3mes_bronce}}"></span>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{Monedas::simbolo($moneda)}}</div>
                            <input type="text" readonly="readonly" class="form-control" id="{{ConfigInstancia::suscripcion_valor_3mes_bronce}}"  name="{{ConfigInstancia::suscripcion_valor_3mes_bronce}}" value="{{Monedas::formatearNumero($moneda,Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_3mes_bronce))}}"/>
                            <div class="input-group-addon">{{$moneda}}</div></div>
                    </div>
                </div>
                {{--6 MESES--}}
                <div class="col-lg-6">
                    <label> {{trans("config.suscripcion.seccion.planes.op.valor.6mensual")}}</label> <span id="label-descuento-{{ConfigInstancia::suscripcion_valor_6mes_bronce}}"></span>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{Monedas::simbolo($moneda)}}</div>
                            <input type="text" readonly="readonly" class="form-control" id="{{ConfigInstancia::suscripcion_valor_6mes_bronce}}"  name="{{ConfigInstancia::suscripcion_valor_6mes_bronce}}" value="{{Monedas::formatearNumero($moneda,Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_6mes_bronce))}}"/>
                            <div class="input-group-addon">{{$moneda}}</div></div>
                    </div>
                </div>
                {{--ANUAL--}}
                <div class="col-lg-6">
                    <label> {{trans("config.suscripcion.seccion.planes.op.valor.12mensual")}}</label> <span id="label-descuento-{{ConfigInstancia::suscripcion_valor_12mes_bronce}}"></span>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{Monedas::simbolo($moneda)}}</div>
                            <input type="text" readonly="readonly" class="form-control" id="{{ConfigInstancia::suscripcion_valor_12mes_bronce}}"  name="{{ConfigInstancia::suscripcion_valor_12mes_bronce}}" value="{{Monedas::formatearNumero($moneda,Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_12mes_bronce))}}"/>
                            <div class="input-group-addon">{{$moneda}}</div></div>
                    </div>
                </div>
            </div>
        </div>


        <!--**************************************************************************************-->
        <!--PLAN PLATA***************************************************************************-->
        <!--**************************************************************************************-->

        <div class="panel panel-primary">
            <div class="panel-heading"><span class="glyphicon glyphicon-certificate" style="color:silver;"></span> {{trans("config.suscripcion.seccion.plan_plata.titulo")}}</div>
            <div class="panel-body">
                <div class="col-lg-6">
                    {{--MENSUAL--}}
                    <label> {{trans("config.suscripcion.seccion.planes.op.valor.1mensual")}}</label>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{Monedas::simbolo($moneda)}}</div>
                            <input type="text" readonly="readonly" class="form-control" onblur="calcularValores()" onkeyup="formatoMoneda(this)" onkeypress="return soloNumeros(this,'{{$sep_decimal}}');" id="{{ConfigInstancia::suscripcion_valor_1mes_plata}}" name="{{ConfigInstancia::suscripcion_valor_1mes_plata}}" value="{{Monedas::formatearNumero($moneda,Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_1mes_plata))}}"/>
                            <div class="input-group-addon">{{$moneda}}</div></div>
                    </div>
                </div>
                {{--3 MESES--}}
                <div class="col-lg-6">
                    <label> {{trans("config.suscripcion.seccion.planes.op.valor.3mensual")}}</label> <span id="label-descuento-{{ConfigInstancia::suscripcion_valor_3mes_plata}}"></span>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{Monedas::simbolo($moneda)}}</div>
                            <input type="text" readonly="readonly" class="form-control" id="{{ConfigInstancia::suscripcion_valor_3mes_plata}}"  name="{{ConfigInstancia::suscripcion_valor_3mes_plata}}" value="{{Monedas::formatearNumero($moneda,Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_3mes_plata))}}"/>
                            <div class="input-group-addon">{{$moneda}}</div></div>
                    </div>
                </div>
                {{--6 MESES--}}
                <div class="col-lg-6">
                    <label> {{trans("config.suscripcion.seccion.planes.op.valor.6mensual")}}</label> <span id="label-descuento-{{ConfigInstancia::suscripcion_valor_6mes_plata}}"></span>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{Monedas::simbolo($moneda)}}</div>
                            <input type="text" readonly="readonly" class="form-control" id="{{ConfigInstancia::suscripcion_valor_6mes_plata}}"  name="{{ConfigInstancia::suscripcion_valor_6mes_plata}}" value="{{Monedas::formatearNumero($moneda,Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_6mes_plata))}}"/>
                            <div class="input-group-addon">{{$moneda}}</div></div>
                    </div>
                </div>
                {{--ANUAL--}}
                <div class="col-lg-6">
                    <label> {{trans("config.suscripcion.seccion.planes.op.valor.12mensual")}}</label> <span id="label-descuento-{{ConfigInstancia::suscripcion_valor_12mes_plata}}"></span>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{Monedas::simbolo($moneda)}}</div>
                            <input type="text" readonly="readonly" class="form-control" id="{{ConfigInstancia::suscripcion_valor_12mes_plata}}"  name="{{ConfigInstancia::suscripcion_valor_12mes_plata}}" value="{{Monedas::formatearNumero($moneda,Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_12mes_plata))}}"/>
                            <div class="input-group-addon">{{$moneda}}</div></div>
                    </div>
                </div>
            </div>
        </div>

        <!--**************************************************************************************-->
        <!--PLAN ORO***************************************************************************-->
        <!--**************************************************************************************-->

        <div class="panel panel-primary">
            <div class="panel-heading"><span class="glyphicon glyphicon-certificate" style="color:gold;"></span> {{trans("config.suscripcion.seccion.plan_oro.titulo")}}</div>
            <div class="panel-body">
                <div class="col-lg-6">
                    {{--MENSUAL--}}
                    <label> {{trans("config.suscripcion.seccion.planes.op.valor.1mensual")}}</label>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{Monedas::simbolo($moneda)}}</div>
                            <input type="text" readonly="readonly" class="form-control" onblur="calcularValores()" onkeyup="formatoMoneda(this)" onkeypress="return soloNumeros(this,'{{$sep_decimal}}');" id="{{ConfigInstancia::suscripcion_valor_1mes_oro}}" name="{{ConfigInstancia::suscripcion_valor_1mes_oro}}" value="{{Monedas::formatearNumero($moneda,Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_1mes_oro))}}"/>
                            <div class="input-group-addon">{{$moneda}}</div></div>
                    </div>
                </div>
                {{--3 MESES--}}
                <div class="col-lg-6">
                    <label> {{trans("config.suscripcion.seccion.planes.op.valor.3mensual")}}</label> <span id="label-descuento-{{ConfigInstancia::suscripcion_valor_3mes_oro}}"></span>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{Monedas::simbolo($moneda)}}</div>
                            <input type="text" readonly="readonly" class="form-control" id="{{ConfigInstancia::suscripcion_valor_3mes_oro}}"  name="{{ConfigInstancia::suscripcion_valor_3mes_oro}}" value="{{Monedas::formatearNumero($moneda,Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_3mes_oro))}}"/>
                            <div class="input-group-addon">{{$moneda}}</div></div>
                    </div>
                </div>
                {{--6 MESES--}}
                <div class="col-lg-6">
                    <label> {{trans("config.suscripcion.seccion.planes.op.valor.6mensual")}}</label> <span id="label-descuento-{{ConfigInstancia::suscripcion_valor_6mes_oro}}"></span>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{Monedas::simbolo($moneda)}}</div>
                            <input type="text" readonly="readonly" class="form-control" id="{{ConfigInstancia::suscripcion_valor_6mes_oro}}"  name="{{ConfigInstancia::suscripcion_valor_6mes_oro}}" value="{{Monedas::formatearNumero($moneda,Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_6mes_oro))}}"/>
                            <div class="input-group-addon">{{$moneda}}</div></div>
                    </div>
                </div>
                {{--ANUAL--}}
                <div class="col-lg-6">
                    <label> {{trans("config.suscripcion.seccion.planes.op.valor.12mensual")}}</label> <span id="label-descuento-{{ConfigInstancia::suscripcion_valor_12mes_oro}}"></span>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{Monedas::simbolo($moneda)}}</div>
                            <input type="text" readonly="readonly" class="form-control" id="{{ConfigInstancia::suscripcion_valor_12mes_oro}}"  name="{{ConfigInstancia::suscripcion_valor_12mes_oro}}" value="{{Monedas::formatearNumero($moneda,Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_12mes_oro))}}"/>
                            <div class="input-group-addon">{{$moneda}}</div></div>
                    </div>
                </div>
            </div>
        </div>


        <!--**************************************************************************************-->
        <!--DESCUENTOS***************************************************************************-->
        <!--**************************************************************************************-->


        {{--DESCUESTOS--}}
        <div class="panel panel-primary">
            <div class="panel-heading"><span class="glyphicon glyphicon-tag"></span> {{trans("config.suscripcion.seccion.descuestos.titulo")}}</div>
            <div class="panel-body">
                <div class="col-lg-6">
                    <label> {{trans("config.suscripcion.seccion.descuestos.op.3meses")}}</label>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">%</div>
                            <input type="text"  onblur="calcularValores()" placeholder="{{trans("config.general.seccion.moneda_principal.op.descuento")}}" class="form-control" id="{{ConfigInstancia::suscripcion_valor_3mes_descuento}}"  name="{{ConfigInstancia::suscripcion_valor_3mes_descuento}}" value="{{Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_3mes_descuento)}}"/>
                        </div>
                    </div>
                </div> 
                <div class="col-lg-6">
                    <label> {{trans("config.suscripcion.seccion.descuestos.op.6meses")}}</label>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">%</div>
                            <input type="text" onblur="calcularValores()" placeholder="{{trans("config.general.seccion.moneda_principal.op.descuento")}}" class="form-control" id="{{ConfigInstancia::suscripcion_valor_6mes_descuento}}"  name="{{ConfigInstancia::suscripcion_valor_6mes_descuento}}" value="{{Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_6mes_descuento)}}"/>
                        </div>
                    </div>
                </div> 
                <div class="col-lg-6">
                    <label> {{trans("config.suscripcion.seccion.descuestos.op.12meses")}}</label>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">%</div>
                            <input type="text" onblur="calcularValores()" placeholder="{{trans("config.general.seccion.moneda_principal.op.descuento")}}" class="form-control" id="{{ConfigInstancia::suscripcion_valor_12mes_descuento}}"  name="{{ConfigInstancia::suscripcion_valor_12mes_descuento}}" value="{{Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_12mes_descuento)}}"/>
                        </div>
                    </div>
                </div> 
            </div>
        </div>

        <div class="text-right" style="margin-bottom:20px;">
            <button class="btn btn-info" type="button" id="btn-guardar" type="button"><span class="glyphicon glyphicon-save"></span> {{trans("otros.info.guardar")}}</button>
        </div>
    </form>




    @stop

    @include("usuarios/tipo/admin/config/secciones/script")

    @section("script2")

    <script>


                                        function formatoMoneda(obj) {
                                        var numero = $(obj).val();
                                                $(obj).val(formatearNumero(numero, "{{$sep_millar}}", "{{$sep_decimal}}"));
                                        }


                                function calcularValores() {

                                var descuento3 = 0;
                                        var descuento6 = 0;
                                        var descuento12 = 0;
                                        var precio = "" + $("#{{ConfigInstancia::suscripcion_valor_1mes_bronce}}").val();
                                        var parts = precio.split("{{$sep_decimal}}");
                                        precio = (parts[0]).replace(/\./g, "");
                                        precio = (precio).replace(/\,/g, "");
                                        precio = parseFloat(precio + "." + parts[1]);
                                        if ($("#{{ConfigInstancia::suscripcion_valor_3mes_descuento}}").val().length > 0 && parseInt($("#{{ConfigInstancia::suscripcion_valor_3mes_descuento}}").val()) > 0){
                                descuento3 = parseInt($("#{{ConfigInstancia::suscripcion_valor_3mes_descuento}}").val());
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_3mes_bronce}}").html("<span class='label label-info'> - " + descuento3 + "%</span>");
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_3mes_plata}}").html("<span class='label label-info'> - " + descuento3 + "%</span>");
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_3mes_oro}}").html("<span class='label label-info'> - " + descuento3 + "%</span>");
                                } else{
                                descuento3 = 0;
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_3mes_bronce}}").html("");
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_3mes_plata}}").html("");
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_3mes_oro}}").html("");
                                }
                                if ($("#{{ConfigInstancia::suscripcion_valor_6mes_descuento}}").val().length > 0 && parseInt($("#{{ConfigInstancia::suscripcion_valor_6mes_descuento}}").val()) > 0){
                                descuento6 = parseInt($("#{{ConfigInstancia::suscripcion_valor_6mes_descuento}}").val());
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_6mes_bronce}}").html("<span class='label label-info'> - " + descuento6 + "%</span>");
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_6mes_plata}}").html("<span class='label label-info'> - " + descuento6 + "%</span>");
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_6mes_oro}}").html("<span class='label label-info'> - " + descuento6 + "%</span>");
                                } else{
                                descuento6 = 0;
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_6mes_bronce}}").html("");
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_6mes_plata}}").html("");
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_6mes_oro}}").html("");
                                }if ($("#{{ConfigInstancia::suscripcion_valor_12mes_descuento}}").val().length > 0 && parseInt($("#{{ConfigInstancia::suscripcion_valor_12mes_descuento}}").val()) > 0){
                                descuento12 = parseInt($("#{{ConfigInstancia::suscripcion_valor_12mes_descuento}}").val());
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_12mes_bronce}}").html("<span class='label label-info'> - " + descuento12 + "%</span>");
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_12mes_plata}}").html("<span class='label label-info'> - " + descuento12 + "%</span>");
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_12mes_oro}}").html("<span class='label label-info'> - " + descuento12 + "%</span>");
                                } else{
                                descuento12 = 0;
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_12mes_bronce}}").html("");
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_12mes_plata}}").html("");
                                        $("#label-descuento-{{ConfigInstancia::suscripcion_valor_12mes_oro}}").html("");
                                }//VALORES DE BRONCE
                                $("#{{ConfigInstancia::suscripcion_valor_3mes_bronce}}").val(formatearNumero((precio * 3) - ((descuento3 / 100) * (precio * 3)), "{{$sep_millar}}", "{{$sep_decimal}}"));
                                        $("#{{ConfigInstancia::suscripcion_valor_6mes_bronce}}").val(formatearNumero((precio * 6) - ((descuento6 / 100) * (precio * 6)), "{{$sep_millar}}", "{{$sep_decimal}}"));
                                        $("#{{ConfigInstancia::suscripcion_valor_12mes_bronce}}").val(formatearNumero((precio * 12) - ((descuento12 / 100) * (precio * 12)), "{{$sep_millar}}", "{{$sep_decimal}}"));
                                        //VALORES DE PLATA
                                        precio *= 2;
                                        $("#{{ConfigInstancia::suscripcion_valor_1mes_plata}}").val(formatearNumero(precio, "{{$sep_millar}}", "{{$sep_decimal}}"));
                                        $("#{{ConfigInstancia::suscripcion_valor_3mes_plata}}").val(formatearNumero((precio * 3) - ((descuento3 / 100) * (precio * 3)), "{{$sep_millar}}", "{{$sep_decimal}}"));
                                        $("#{{ConfigInstancia::suscripcion_valor_6mes_plata}}").val(formatearNumero((precio * 6) - ((descuento6 / 100) * (precio * 6)), "{{$sep_millar}}", "{{$sep_decimal}}"));
                                        $("#{{ConfigInstancia::suscripcion_valor_12mes_plata}}").val(formatearNumero((precio * 12) - ((descuento12 / 100) * (precio * 12)), "{{$sep_millar}}", "{{$sep_decimal}}"));
                                        //VALORES DE ORO
                                        precio /= 2;
                                        precio *= 3;
                                        $("#{{ConfigInstancia::suscripcion_valor_1mes_oro}}").val(formatearNumero(precio, "{{$sep_millar}}", "{{$sep_decimal}}"));
                                        $("#{{ConfigInstancia::suscripcion_valor_3mes_oro}}").val(formatearNumero((precio * 3) - ((descuento3 / 100) * (precio * 3)), "{{$sep_millar}}", "{{$sep_decimal}}"));
                                        $("#{{ConfigInstancia::suscripcion_valor_6mes_oro}}").val(formatearNumero((precio * 6) - ((descuento6 / 100) * (precio * 6)), "{{$sep_millar}}", "{{$sep_decimal}}"));
                                        $("#{{ConfigInstancia::suscripcion_valor_12mes_oro}}").val(formatearNumero((precio * 12) - ((descuento12 / 100) * (precio * 12)), "{{$sep_millar}}", "{{$sep_decimal}}"));
                                }

    </script>




    @stop
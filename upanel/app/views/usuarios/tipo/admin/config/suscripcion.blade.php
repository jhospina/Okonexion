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
                
    <div class="panel panel-primary">
        <div class="panel-heading">{{trans("config.suscripcion.seccion.costos.titulo")}}</div>
        <div class="panel-body">
            <div class="col-lg-6">
                {{--MENSUAL--}}
                <label> {{trans("config.suscripcion.seccion.costos.op.valor.mensual")}}</label>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">{{Monedas::simbolo($moneda)}}</div>
                        <input type="text" class="form-control" onblur="calcularValores()" onkeyup="formatoMoneda(this)" onkeypress="return soloNumeros(this,'{{$sep_decimal}}');" id="{{ConfigInstancia::suscripcion_valor_1mes}}" name="{{ConfigInstancia::suscripcion_valor_1mes}}" value="{{Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_1mes)}}"/>
                        <div class="input-group-addon">{{$moneda}}</div></div>
                </div>
            </div>
            {{--3 MESES--}}
            <div class="col-lg-6">
                <label> {{trans("config.suscripcion.seccion.costos.op.valor.3mensual")}}</label>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">{{Monedas::simbolo($moneda)}}</div>
                        <input type="text" class="form-control" id="{{ConfigInstancia::suscripcion_valor_3mes}}"  name="{{ConfigInstancia::suscripcion_valor_3mes}}" value="{{Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_3mes)}}"/>
                        <div class="input-group-addon">{{$moneda}}</div></div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">%</div>
                        <input type="text" onblur="calcularValores()" placeholder="{{trans("config.general.seccion.moneda_principal.op.descuento")}}" class="form-control" id="{{ConfigInstancia::suscripcion_valor_3mes_descuento}}"  name="{{ConfigInstancia::suscripcion_valor_3mes_descuento}}" value="{{Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_3mes_descuento)}}"/>
                    </div>
                </div>
            </div>
            {{--6 MESES--}}
            <div class="col-lg-6">
                <label> {{trans("config.suscripcion.seccion.costos.op.valor.6mensual")}}</label>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">{{Monedas::simbolo($moneda)}}</div>
                        <input type="text" class="form-control" id="{{ConfigInstancia::suscripcion_valor_6mes}}"  name="{{ConfigInstancia::suscripcion_valor_6mes}}" value="{{Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_6mes)}}"/>
                        <div class="input-group-addon">{{$moneda}}</div></div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">%</div>
                        <input type="text" onblur="calcularValores()" placeholder="{{trans("config.general.seccion.moneda_principal.op.descuento")}}" class="form-control" id="{{ConfigInstancia::suscripcion_valor_6mes_descuento}}"  name="{{ConfigInstancia::suscripcion_valor_6mes_descuento}}" value="{{Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_6mes_descuento)}}"/>
                    </div>
                </div>
            </div>
            {{--ANUAL--}}
            <div class="col-lg-6">
                <label> {{trans("config.suscripcion.seccion.costos.op.valor.anual")}}</label>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">{{Monedas::simbolo($moneda)}}</div>
                        <input type="text" class="form-control" id="{{ConfigInstancia::suscripcion_valor_12mes}}"  name="{{ConfigInstancia::suscripcion_valor_12mes}}" value="{{Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_12mes)}}"/>
                        <div class="input-group-addon">{{$moneda}}</div></div>
                </div>
            </div>
            <div class="col-lg-2">
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

        var precio = "" + $("#{{ConfigInstancia::suscripcion_valor_1mes}}").val();
        var parts = precio.split("{{$sep_decimal}}");

        precio = (parts[0]).replace(/\./g, "");
        precio = (precio).replace(/\,/g, "");

        precio = parseFloat(precio + "." + parts[1]);

        if ($("#{{ConfigInstancia::suscripcion_valor_3mes_descuento}}").val().length > 0)
            descuento3 = parseInt($("#{{ConfigInstancia::suscripcion_valor_3mes_descuento}}").val());
        if ($("#{{ConfigInstancia::suscripcion_valor_6mes_descuento}}").val().length > 0)
            descuento6 = parseInt($("#{{ConfigInstancia::suscripcion_valor_6mes_descuento}}").val());
        if ($("#{{ConfigInstancia::suscripcion_valor_12mes_descuento}}").val().length > 0)
            descuento12 = parseInt($("#{{ConfigInstancia::suscripcion_valor_12mes_descuento}}").val());


        $("#{{ConfigInstancia::suscripcion_valor_3mes}}").val(formatearNumero((precio * 3)-((descuento3/100)*(precio * 3)), "{{$sep_millar}}", "{{$sep_decimal}}"));
        $("#{{ConfigInstancia::suscripcion_valor_6mes}}").val(formatearNumero((precio * 6)-((descuento6/100)*(precio * 6)), "{{$sep_millar}}", "{{$sep_decimal}}"));
        $("#{{ConfigInstancia::suscripcion_valor_12mes}}").val(formatearNumero((precio * 12)-((descuento12/100)*(precio * 12)), "{{$sep_millar}}", "{{$sep_decimal}}"));
    }

</script>




@stop
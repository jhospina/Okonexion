<?php
$moneda_instancia = Instancia::obtenerValorMetadato(ConfigInstancia::info_moneda);
?>
@extends('interfaz/plantilla')

@section("titulo") {{trans("interfaz.menu.principal.facturacion")}}|{{trans("interfaz.menu.principal.facturacion.facturas")}} @stop

@section("css")
<style>
    .factura-{{Facturacion::ESTADO_SIN_PAGAR}} span,.factura-{{Facturacion::ESTADO_PAGADO}} span,.factura-{{Facturacion::ESTADO_VENCIDA}} span{
        padding: 5px;
        color: white;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
    }

    .factura-{{Facturacion::ESTADO_PAGADO}} span{
        background: green;
    }

    .factura-{{Facturacion::ESTADO_SIN_PAGAR}} span{
        background: red;
    }

    .factura-{{Facturacion::ESTADO_VENCIDA}} span{
        background: orange;
    }

</style>
@stop

@section("contenido") 

<h1><span class="glyphicon glyphicon-copy"></span> {{trans("interfaz.menu.principal.facturacion.facturas")}}</h1>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))

<table class="table table-striped">
    <tr>
        <th>{{trans("fact.col.numero.factura")}}</th>
        @if(Auth::user()->instancia==User::PARAM_INSTANCIA_SUPER_ADMIN)
        <th>{{trans("otros.info.instancia")}}</th>
        @endif
        <th>{{trans("otros.info.cliente")}}</th>
        <th>{{trans("fact.col.fecha.creacion")}}</th>
        <th>{{trans("fact.col.fecha.vencimiento")}}</th>
        <th>{{trans("otros.info.estado")}}</th>
        <th>{{trans("fact.info.total")}}</th>
    </tr>
    @foreach($facturas as $factura)
    <?php
    $moneda = Facturacion::obtenerValorMetadato(MetaFacturacion::MONEDA_ID, $factura->id);
    $cliente = User::find($factura->id_usuario);
    ?>
    <tr>
        <td><a target="_blank" href="{{URL::to("fact/factura/".$factura->id)}}">{{$factura->id}}</a></td>
        @if(Auth::user()->instancia==User::PARAM_INSTANCIA_SUPER_ADMIN)
        <?php $instancia = Instancia::find($factura->id_instancia); ?>
        <td>{{(is_null($instancia))?trans("instancias.default.super"):$instancia->empresa}}</td>
        @endif
        <td>{{$cliente->nombres}}</td>
        <td>{{Fecha::formatear($factura->fecha_creacion)}}</td>
        <td>{{Fecha::formatear($factura->fecha_vencimiento)}}</td>
        <td class="factura-{{$factura->estado}}"><span>{{Facturacion::estado($factura->estado)}}</span></td>
        <td>{{Monedas::simbolo($moneda_instancia)}}{{Monedas::formatearNumero($moneda_instancia,Monedas::convertir($moneda,$moneda_instancia,$factura->total))}} {{$moneda_instancia}}</td>
    </tr>
    @endforeach
</table>

{{$facturas->links()}}

@stop
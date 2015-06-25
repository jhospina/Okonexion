@extends('interfaz/plantilla')

@section("titulo") {{trans("interfaz.menu.principal.facturacion")}}|{{trans("interfaz.menu.principal.facturacion.mis.facturas")}} @stop

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

<h1><span class="glyphicon glyphicon-copy"></span> {{trans("interfaz.menu.principal.facturacion.mis.facturas")}}</h1>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))

<table class="table table-striped">
    <tr>
        <th>{{trans("fact.col.numero.factura")}}</th>
        <th>{{trans("fact.col.fecha.creacion")}}</th>
        <th>{{trans("fact.col.fecha.vencimiento")}}</th>
        <th>{{trans("otros.info.estado")}}</th>
        <th>{{trans("fact.info.total")}}</th>
    </tr>
    @foreach($facturas as $factura)
    <?php
    $moneda = Facturacion::obtenerValorMetadato(MetaFacturacion::MONEDA_ID, $factura->id);
    ?>
    <tr>
        <td><a target="_blank" href="{{URL::to("fact/factura/".$factura->id)}}">{{$factura->id}}</a></td>
        <td>{{Fecha::formatear($factura->fecha_creacion)}}</td>
        <td>{{Fecha::formatear($factura->fecha_vencimiento)}}</td>
        <td class="factura-{{$factura->estado}}"><span>{{Facturacion::estado($factura->estado)}}</span></td>
        <td>{{Monedas::simbolo($moneda)}}{{Monedas::formatearNumero($moneda,$factura->total)}} {{$moneda}}</td>
    </tr>
    @endforeach
</table>

{{$facturas->links()}}

@stop
<?php
$moneda = Facturacion::obtenerValorMetadato(MetaFacturacion::MONEDA_ID, $factura->id);
$cliente = json_decode(Facturacion::obtenerValorMetadato(MetaFacturacion::CLIENTE_INFO, $factura->id));
$productos = Facturacion::obtenerProductos($factura->id);
$subtotal = 0; //Almacenara el subtotal de los productos
$iva = $factura->iva;
?>

@extends('usuarios/general/facturacion/facturas/plantilla')

@section("titulo") {{trans("fact.col.numero.factura")}} {{$factura->id}} @stop

@section("numero") {{trans("fact.col.numero.factura")}} {{$factura->id}} @stop

@section("estado") <span id="estado-factura" class="{{$factura->estado}}"> {{Facturacion::estado($factura->estado)}} </span> @stop

@section("contenido")

<div class="col-lg-12" style="padding:5px;" id="fechas">
    <div class="col-lg-6"><b>{{trans("fact.col.fecha.creacion")}}:</b> {{Fecha::formatear($factura->fecha_creacion)}}</div>
    <div class="col-lg-6"><b>{{trans("fact.col.fecha.vencimiento")}}:</b> {{Fecha::formatear($factura->fecha_vencimiento)}}</div>
</div>


<div class="col-lg-12" id="info-fact">
    <div class="col-lg-6">
        <div class="col-lg-12">{{trans("fact.factura.info.a.cliente")}}</div>
        <div class="block" style="padding: 10px;clear: both;">
            @foreach($cliente as $indice =>$info)
            <span style="display: block;">{{$info}}</span>
            @endforeach
        </div>
    </div>
    <div class="col-lg-6">
        <div class="col-lg-12">{{trans("fact.factura.info.pagar.a")}}</div>
        <div class="block" style="padding: 10px;clear: both;">
            {{trans("interfaz.nombre")}} (Appsthergo.com)
        </div>
    </div>
</div>

<div class="col-lg-12">

    <table class="table table-striped table-bordered" style="  margin-bottom: 0px;">
        <tr><th>{{Util::convertirMayusculas(trans("fact.orden.tu.comprar.descripcion"))}}</th><th class="text-right">{{Util::convertirMayusculas(trans("fact.orden.tu.comprar.precio"))}}</th></tr>

        @foreach($productos as $producto)
        <?php
        $id_producto = $producto[MetaFacturacion::PRODUCTO_ID];
        $valor_producto = $producto[MetaFacturacion::PRODUCTO_VALOR];
        $descuento_producto = $producto[MetaFacturacion::PRODUCTO_DESCUENTO];
//Calcula el valor descontado del producto
        $valor_real = ($valor_producto * 100) / (100 - $descuento_producto);
        $valor_descontado = $valor_real - $valor_producto;

        $subtotal+=$valor_producto;
        ?>

        <tr>
            <td>
                <span class="glyphicon glyphicon-ok"></span> {{(strpos($id_producto,Servicio::CONFIG_NOMBRE)!==false)?Servicio::obtenerNombre($id_producto):trans("fact.producto.id.".$id_producto)}}
            </td>
            <td class="text-right">
                {{Monedas::simbolo($moneda)}}{{$valor_real}} {{$moneda}}
            </td>
        </tr>

        @if($descuento_producto>0)

        <tr>
            <td>
                <i><span class="glyphicon glyphicon-ok"></span> <b>{{trans("otros.info.descuento")}} {{$descuento_producto}}%</b> - {{(strpos($id_producto,Servicio::CONFIG_NOMBRE)!==false)?Servicio::obtenerNombre($id_producto):trans("fact.producto.id.".$id_producto)}}</i>
            </td>
            <td class="text-right">
                -{{Monedas::simbolo($moneda)}}{{$valor_descontado}} {{$moneda}}
            </td>
        </tr>

        @endif

        @endforeach

        <tr>
            <td class="text-right">{{trans("fact.orden.tu.comprar.subtotal")}}</td><td class="text-right">{{Monedas::simbolo($moneda)}}{{$subtotal}} {{$moneda}}</td>
        </tr>
        <tr>
            <?php
            $valor_iva = ($iva / 100) * $subtotal;
            $valor_total = $valor_iva + $subtotal;
            ?>
            @if($iva>0)
            <td class="text-right">{{trans("fact.orden.tu.compra.iva")}} ({{$iva}}%)</td><td class="text-right">{{Monedas::simbolo($moneda)}}{{$valor_iva}} {{$moneda}}</td>
            @endif
        </tr>
        <tr><td class="text-right" style="font-size: 13pt;"><b>{{trans("fact.info.total")}}</b></td><td class="text-right" style="font-size: 13pt;"><b>{{Monedas::simbolo($moneda)}} {{$valor_total}} {{$moneda}}</b></div> 
    </table>

</div>


<div class="col-md-12" style="margin: 20px 0px;">
    <p class="label label-sm label-grey arrowed arrowed-right">{{trans("fact.factura.transaccion.titulo")}}</p>
</div>


<div class="col-lg-12" style="margin-bottom: 50px;">

    <table class="table table-striped table-bordered" style="  margin-bottom: 0px;">
        <tr>
        <th>{{Util::convertirMayusculas(trans("fact.factura.transaccion.fecha"))}}</th>
        <th>{{Util::convertirMayusculas(trans("fact.factura.transaccion.metodo"))}}</th>
        <th>{{Util::convertirMayusculas(trans("fact.factura.transaccion.id"))}}</th>
        <th>{{trans("fact.info.total")}}</th>
        </tr>

        @if(!is_null($factura->tipo_pago))
        <tr>
            <td>{{Fecha::formatear(Facturacion::obtenerValorMetadato(MetaFacturacion::FECHA_PAGO, $factura->id))}}</td>
            <td>{{Facturacion::tipo($factura->tipo_pago)}}</td>
            <td>{{Facturacion::obtenerValorMetadato(MetaFacturacion::TRANSACCION_ID, $factura->id)}}</td>
            <td>{{Monedas::simbolo($moneda)}} {{$valor_total}} {{$moneda}}</td>
        </tr>
        @endif
    </table>

</div>


@stop


@section("btns")


<a class="btn btn-danger" target="_blank"  href="{{URL::to("pdf/ver/factura/".$factura->id)}}"><span class="glyphicon glyphicon-file"></span> PDF</a>
<a class="btn btn-default" href="{{URL::to("pdf/descargar/factura/".$factura->id)}}"><span class="glyphicon glyphicon-print"></span> {{trans("otros.info.imprimir")}}</a>

@stop
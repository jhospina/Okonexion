<?php
$moneda = Facturacion::obtenerValorMetadato(MetaFacturacion::MONEDA_ID, $factura->id);
$productos = Facturacion::obtenerProductos($factura->id);
$subtotal = 0; //Almacenara el subtotal de los productos
$iva = $factura->iva;
?>

@extends('interfaz/plantilla')

@section("titulo") {{trans("fact.ordenPago.titulo")}} #{{$factura->id}} @stop


@section("css")

<style>

</style>


@stop

@section("contenido") 

<h1><span class="glyphicon glyphicon-list-alt"></span> {{trans("fact.ordenPago.titulo")}} #{{$factura->id}}</h1>

<div style="border: 1px black solid;
     -webkit-border-radius: 5px;
     -moz-border-radius: 5px;
     border-radius: 5px;padding:1px;">
    <table class="table table-striped table-hover" style="  margin-bottom: 0px;">
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
                <span class="glyphicon glyphicon-ok"></span> {{trans("fact.producto.id.".$id_producto)}}
            </td>
            <td class="text-right">
                {{Monedas::simbolo($moneda)}}{{$valor_real}} {{$moneda}}
            </td>
        </tr>

        @if($descuento_producto>0)

        <tr>
            <td>
                <i><span class="glyphicon glyphicon-ok"></span> <b>{{trans("otros.info.descuento")}} {{$descuento_producto}}%</b> - {{trans("fact.producto.id.".$id_producto)}}</i>
            </td>
            <td class="text-right">
                -{{Monedas::simbolo($moneda)}}{{$valor_descontado}} {{$moneda}}
            </td>
        </tr>

        @endif

        @endforeach

        <tr>
            <td class="text-right"><u>{{trans("fact.orden.tu.comprar.subtotal")}}</u></td><td class="text-right">{{Monedas::simbolo($moneda)}}{{$subtotal}} {{$moneda}}</td>
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
        <tr><td class="text-right" style="font-size: 13pt;"><b>{{trans("fact.info.total")}}</b></td><td class="text-right" style="font-size: 13pt;"><b>{{Monedas::simbolo($moneda)}} {{$valor_total}} {{$moneda}}</b></td></tr>
    </table>

</div>

<hr/>

<h2><span class="glyphicon glyphicon-credit-card"></span> {{trans("fact.orden.pago.informacion")}}</h2>

<form id="CCForm" action="{{URL::to("fact/orden/pago/procesar/")}}" method="post">
    <div class="col-lg-12" style="margin-bottom: 30px;">
        <div class="col-lg-6 input-lg" style="margin-bottom: 10px;"></div>
        <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">
            <img style="height: 30px;" src="{{URL::to('assets/img/icons/mastercard.png')}}"/>
            <img style="height: 30px;" src="{{URL::to('assets/img/icons/visa.png')}}"/>
            <img style="height: 30px;" src="{{URL::to('assets/img/icons/americanexpress.png')}}"/>
            <img style="height: 30px;" src="{{URL::to('assets/img/icons/dinersclub.png')}}"/>
            <img style="height: 30px;" src="{{URL::to('assets/img/icons/discover.png')}}"/>
        </div>
        <div class="col-lg-6 input-lg"> 
            {{trans("fact.orden.pago.tc.titulo")}}
        </div>
        <div class="col-lg-6">
            <input id="ccNo" class="form-control input-lg" maxlength="20" onkeydown="return soloNumeros(this, '');" type="text" size="20" value="" autocomplete="off" required />
        </div>
        <div class="col-lg-6 input-lg"> 
            {{trans("fact.orden.pago.tc.fecha.vencimiento")}}
        </div>
        <div class="col-lg-6 input-lg">
            <select id="mes-exp" class="form-control" style="width: 20%;display:initial;  font-size: 12pt;">
                <option value=""></option>
                <option value="01">01</option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
                <option value="05">05</option>
                <option value="06">06</option>
                <option value="07">07</option>
                <option value="08">08</option>
                <option value="09">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </select>
            <span> / </span>
            <select id="ano-exp" class="form-control" style="width: 20%;display:initial; font-size: 12pt;">
                <option value=""></option>
                <?php for ($i = date("Y"); $i < date("Y") + 12; $i++): ?>
                    <option value="{{$i}}">{{$i}}</option>
                <?php endfor; ?>
            </select>
            <input type="hidden" size="2" id="expMonth" value="" required />
            <input type="hidden" size="2" id="expYear" value="" required /></div>
        <div class="col-lg-6 input-lg"> 
            {{trans("fact.orden.pago.tc.cvv")}}
        </div>
        <div class="col-lg-6 input-lg"> <input id="cvv" class="form-control input-lg" maxlength="4" size="4" type="text" value="" autocomplete="off" required /></div>

        <div class="col-lg-12" style="margin-top: 20px;"><input type="submit" class="btn btn-primary" value="Submit Payment"></div>
    </div>
</form>



@stop


@section("script")
{{ HTML::script('assets/jscode/util.js') }}
<script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>


<script>
// Called when token created successfully.
                var successCallback = function (data) {
                    var myForm = document.getElementById('#CCForm');

                    // Set the token as the value for the token input
                    myForm.token.value = data.response.token.token;

                    // IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
                    myForm.submit();
                };

// Called when token creation fails.
                var errorCallback = function (data) {
                    if (data.errorCode === 200) {
                        tokenRequest();
                    } else {
                        alert(data.errorMsg);
                    }
                };

                var tokenRequest = function () {
                    // Setup token request arguments
                    var args = {
                        sellerId: "{{Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_idSeller)}}",
                        publishableKey: "{{Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_publicKey)}}",
                        ccNo: $("#ccNo").val(),
                        cvv: $("#cvv").val(),
                        expMonth: $("#expMonth").val(),
                        expYear: $("#expYear").val()
                    };

                    // Make the token request
                    TCO.requestToken(successCallback, errorCallback, args);
                };

                $(function () {
                    // Pull in the public encryption key for our environment
                    TCO.loadPubKey('sandbox');

                    $("#CCForm").submit(function (e) {
                        // Call our token request function
                        tokenRequest();

                        // Prevent form from submitting
                        return false;
                    });
                });
</script>


<script>
    $("#mes-exp").change(function () {
        $("#expMonth").val($(this).val());
    });
    $("#ano-exp").change(function () {
        $("#expYear").val($(this).val());
    });
</script>

@stop
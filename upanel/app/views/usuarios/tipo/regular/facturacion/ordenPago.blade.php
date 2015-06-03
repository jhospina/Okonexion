<?php
$moneda = Facturacion::obtenerValorMetadato(MetaFacturacion::MONEDA_ID, $factura->id);
$productos = Facturacion::obtenerProductos($factura->id);
$subtotal = 0; //Almacenara el subtotal de los productos
$iva = $factura->iva;
$paises = Paises::listado();
array_unshift($paises, trans("otros.info.elegir"));
?>

@extends('interfaz/plantilla')

@section("titulo") {{trans("fact.ordenPago.titulo")}} #{{$factura->id}} @stop


@section("css")

<style>

</style>


@stop

@section("contenido") 

<h1><span class="glyphicon glyphicon-list-alt"></span> {{trans("fact.ordenPago.titulo")}} #{{$factura->id}}</h1>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))

<div style="border: 1px black solid;
     -webkit-border-radius: 5px;
     -moz-border-radius: 5px;
     border-radius: 5px;padding:1px;">
    <table class="table table-striped table-hover" style="  margin-bottom: 0px;">
        <th>{{Util::convertirMayusculas(trans("fact.orden.tu.comprar.descripcion"))}}</th><th class="text-right">{{Util::convertirMayusculas(trans("fact.orden.tu.comprar.precio"))}}</th></tr>

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

<hr/>
<hr/>

<h2><span class="glyphicon glyphicon-credit-card"></span> {{trans("fact.orden.pago.informacion.facturacion.titulo")}}</h2>

<div id="msj-error-fact" style="display: none;" class="alert alert-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> {{trans('fact.orden.pago.informacion.facturacion.error')}}</div>
<div class="col-lg-12" style="margin-bottom: 30px;">
    <div class="col-lg-6 input-lg">  {{trans("menu_usuario.mi_perfil.info.nombre")}} </div><div class="col-lg-6 input-lg">      {{ Form::text('nombres', Auth::user()->nombres, array('readonly'=>'readonly','placeholder' => trans("menu_usuario.mi_perfil.info.nombre.placeholder"), 'class' => 'form-control',"id"=>"nombres")) }}</div> 
    <div class="col-lg-6 input-lg">  {{trans("menu_usuario.mi_perfil.info.apellidos")}} </div><div class="col-lg-6 input-lg">  {{ Form::text('apellidos', Auth::user()->apellidos, array('readonly'=>'readonly','placeholder' => trans("menu_usuario.mi_perfil.info.apellidos.placeholder"), 'class' => 'form-control')) }}</div> 
    <div class="col-lg-6 input-lg">  {{trans("menu_usuario.mi_perfil.info.email")}}</div><div class="col-lg-6 input-lg">  {{ Form::text('email', Auth::user()->email, array('readonly'=>'readonly','placeholder' => trans("menu_usuario.mi_perfil.info.email.placeholder"), 'class' => 'form-control')) }}</div> 
    <div class="col-lg-6 input-lg">{{trans("menu_usuario.mi_perfil.info.dni")}}</div><div class="col-lg-6 input-lg">{{ Form::text('dni', Auth::user()->dni, array('placeholder' => trans("menu_usuario.mi_perfil.info.dni.placeholder"), 'class' => 'form-control',"id"=>"dni")) }}</div> 
    <div class="col-lg-6 input-lg">{{trans("menu_usuario.mi_perfil.info.empresa")}} </div><div class="col-lg-6 input-lg">{{ Form::text('empresa', Auth::user()->empresa, array('placeholder' => trans("menu_usuario.mi_perfil.info.empresa.placeholder"), 'class' => 'form-control',"id"=>"empresa")) }}</div> 
    <div class="col-lg-6 input-lg">  {{trans("menu_usuario.mi_perfil.info.pais")}}</div><div class="col-lg-6 input-lg">  {{ Form::select('pais',$paises, Auth::user()->pais, array('placeholder' => trans("menu_usuario.mi_perfil.info.pais.placeholder"), 'class' => 'form-control',"id"=>"pais")) }}</div> 
    <div class="col-lg-6 input-lg">  {{trans("menu_usuario.mi_perfil.info.region")}}</div><div class="col-lg-6 input-lg">  {{ Form::text('region', Auth::user()->region, array('placeholder' => trans("menu_usuario.mi_perfil.info.region.placeholder"), 'class' => 'form-control',"id"=>"region")) }}</div> 
    <div class="col-lg-6 input-lg">  {{trans("menu_usuario.mi_perfil.info.ciudad")}}</div><div class="col-lg-6 input-lg">  {{ Form::text('ciudad', Auth::user()->ciudad, array('placeholder' => trans("menu_usuario.mi_perfil.info.ciudad.placeholder"), 'class' => 'form-control',"id"=>"ciudad")) }}</div> 
    <div class="col-lg-6 input-lg">  {{trans("menu_usuario.mi_perfil.info.direccion")}}</div><div class="col-lg-6 input-lg">  {{ Form::text('direccion', Auth::user()->direccion, array('placeholder' => trans("menu_usuario.mi_perfil.info.direccion.placeholder"), 'class' => 'form-control',"id"=>"direccion")) }}</div> 
    <div class="col-lg-6 input-lg">  {{trans("menu_usuario.mi_perfil.info.telefono")}}</div><div class="col-lg-6 input-lg">  {{ Form::text('telefono', Auth::user()->telefono, array('placeholder' => trans("menu_usuario.mi_perfil.info.telefono.placeholder"), 'class' => 'form-control',"id"=>"telefono")) }}</div> 
</div>

<div style="clear: both;"></div>

<hr/>
<hr/>

<h2><span class="glyphicon glyphicon-credit-card"></span> {{trans("fact.orden.pago.informacion.pago.titulo")}}</h2>

<div id="msj-error-tc" style="display: none;" class="alert alert-danger"></div>

<form id="CCForm" action="{{URL::to("fact/orden/pago/procesar/")}}" method="post">
    <input name="token" id="token" type="hidden" value="" />
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
        <div class="col-lg-6 input-lg"> <input id="cvv" onkeydown="return soloNumeros(this, '');" class="form-control input-lg" maxlength="4" size="4" type="text" value="" autocomplete="off" required /></div>

        <div class="col-lg-12 text-center" style="margin-top: 50px;">
            <button type="button" id="btn-pagar" class="btn btn-lg btn-primary text-uppercase"><span class="glyphicon glyphicon-ok-circle"></span> {{trans("fact.btn.realizar.pago")}}</button>
        </div>
    </div>
</form>



<div id="modal-proceso" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{trans("fact.orden.pago.modal.titulo")}}</h4>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img style="margin-bottom: 10px;" src="{{URL::to('assets/img/loaders/gears.gif')}}"/><br/>
                    <span>{{trans("otros.info.espere.por_favor")}}</span>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-lg-1 text-center" style="font-size: 30pt;"><span class="glyphicon glyphicon-exclamation-sign"></span></div>
                <div class="col-lg-11 text-left" style="font-size: 12pt;">{{trans("fact.orden.pago.modal.pie")}}</div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



@stop


@section("script")

<script>
    var idSeller = "{{Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_idSeller)}}";
    var publicKeyUser = "{{Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_publicKey)}}";
    var sandbox = <?php echo (Util::convertirIntToBoolean(Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_sandbox))) ? "true" : "false"; ?>;
    var btn_msj_inicial = '<span class="glyphicon glyphicon-ok-circle"></span> {{trans("fact.btn.realizar.pago")}}';
    var btn_msj_verificando = "<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.verificando')}}...";
    var btn_msj_procesando = "<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.procesando')}}...";
    var msj_error_tc = "<span class='glyphicon glyphicon-exclamation-sign'></span> {{trans('fact.orden.pago.informacion.tc.error')}}";
    var msj_error_tc_invalido = "<span class='glyphicon glyphicon-exclamation-sign'></span> {{trans('fact.orden.pago.informacion.tc.error.invalido')}}";
    var msj_error_tc_descon = "<span class='glyphicon glyphicon-exclamation-sign'></span> {{trans('fact.orden.pago.informacion.tc.error.desconocido',array('link'=>URL::to('soporte')))}}";
    var url_postInfo = "{{URL::to('usuario/ajax/actualizar/')}}";
</script>


<script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
{{ HTML::script('assets/jscode/util.js') }}
{{ HTML::script('assets/jscode/checkoutpay.js') }}
@stop
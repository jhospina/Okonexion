<?php
$moneda = Facturacion::obtenerValorMetadato(MetaFacturacion::MONEDA_ID, $factura->id);
$productos = Facturacion::obtenerProductos($factura->id);
$subtotal = 0; //Almacenara el subtotal de los productos
$iva = $factura->iva;
$paises = Paises::listado();
$tipos_documento=User::obtenerTiposDocumento();
array_unshift($paises, trans("otros.info.elegir"));
?>

@extends('interfaz/plantilla')

@section("titulo") {{trans("fact.ordenPago.titulo")}} #{{$factura->id}} @stop


@section("css")
{{ HTML::style('assets/plugins/switchery/switchery.css', array('media' => 'screen')) }}
{{ HTML::style('assets/plugins/pretty-checkbox/checkbox.css', array('media' => 'screen')) }}
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
                <span class="glyphicon glyphicon-ok"></span> {{(strpos($id_producto,Servicio::CONFIG_NOMBRE)!==false)?Servicio::obtenerNombre($id_producto):trans("fact.producto.id.".$id_producto)}} 
                @if(strpos($id_producto,"actualizacion")!==false)
                <?php $dias_suscripcion = round(Fecha::difSec(Util::obtenerTiempoActual(), Auth::user()->fin_suscripcion) / (60 * 60 * 24)); ?>
                ({{$dias_suscripcion." ".trans("otros.time.dias")}})
                @endif
            </td>
            <td class="text-right">
                {{Monedas::simbolo($moneda)}}{{Monedas::formatearNumero($moneda,$valor_real)}} {{$moneda}}
            </td>
        </tr>

        @if($descuento_producto>0)

        <tr>
            <td>
                <i><span class="glyphicon glyphicon-ok"></span> <b>{{trans("otros.info.descuento")}} {{$descuento_producto}}%</b> - {{trans("fact.producto.id.".$id_producto)}}</i>
            </td>
            <td class="text-right">
                -{{Monedas::simbolo($moneda)}}{{Monedas::formatearNumero($moneda,$valor_descontado)}} {{$moneda}}
            </td>
        </tr>

        @endif

        @endforeach

        <tr>
            <td class="text-right">{{trans("fact.orden.tu.comprar.subtotal")}}</td><td class="text-right">{{Monedas::simbolo($moneda)}}{{Monedas::formatearNumero($moneda,$subtotal)}} {{$moneda}}</td>
        </tr>
        <tr>
            <?php
            $valor_iva = ($iva / 100) * $subtotal;
            $valor_total = $valor_iva + $subtotal;
            ?>
            @if($iva>0)
            <td class="text-right">{{trans("fact.orden.tu.compra.iva")}} ({{$iva}}%)</td><td class="text-right">{{Monedas::simbolo($moneda)}}{{Monedas::formatearNumero($moneda,$valor_iva)}} {{$moneda}}</td>
            @endif
        </tr>
        <tr><td class="text-right" style="font-size: 13pt;"><b>{{trans("fact.info.total")}}</b></td><td class="text-right" style="font-size: 13pt;"><b>{{Monedas::simbolo($moneda)}} {{Monedas::formatearNumero($moneda,$valor_total)}} {{$moneda}}</b></div> 
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
    <div class="col-lg-4 input-lg">{{trans("menu_usuario.mi_perfil.info.dni")}}</div><div class="col-lg-4 input-lg">{{ Form::select('tipo_documento',$tipos_documento, null, array('placeholder' => trans("menu_usuario.mi_perfil.info.pais.placeholder"), 'class' => 'form-control')) }}</div><div class="col-lg-4 input-lg">{{ Form::text('numero_identificacion', null, array('placeholder' => trans("menu_usuario.mi_perfil.info.dni.placeholder"), 'class' => 'form-control')) }}</div>
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


{{ HTML::script('assets/jscode/verInfoFact.js') }}
<script>
    var btn_msj_inicial = '<span class="glyphicon glyphicon-ok-circle"></span> {{trans("fact.btn.realizar.pago")}}';
    var btn_msj_verificando = "<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.verificando')}}...";
    var btn_msj_procesando = "<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.procesando')}}...";
    var msj_error_tc = "<span class='glyphicon glyphicon-exclamation-sign'></span> {{trans('fact.orden.pago.informacion.tc.error')}}";
    var msj_error_tc_invalido = "<span class='glyphicon glyphicon-exclamation-sign'></span> {{trans('fact.orden.pago.informacion.tc.error.invalido')}}";
    var msj_error_tc_descon = "<span class='glyphicon glyphicon-exclamation-sign'></span> {{trans('fact.orden.pago.informacion.tc.error.desconocido',array('link'=>URL::to('soporte')))}}";
    var msj_error_infoPagador = "<span class='glyphicon glyphicon-exclamation-sign'></span> {{trans('fact.orden.pago.payu.tcredito.infoPagador.error')}}";
    var msj_error_pse = "<span class='glyphicon glyphicon-exclamation-sign'></span> {{trans('fact.orden.pago.payu.pse.error')}}";
    var msj_error_efect = "<span class='glyphicon glyphicon-exclamation-sign'></span> {{trans('fact.orden.pago.payu.efectivo.error')}}";
    var url_postInfo = "{{URL::to('usuario/ajax/actualizar/')}}";
</script>

@if($moneda==Monedas::COP)
@include("usuarios/general/facturacion/gateway/payu",array("valor_total"=>$valor_total,"moneda"=>$moneda))
@else
@include("usuarios/general/facturacion/gateway/2checkout")
@endif


@stop


@section("script")

{{ HTML::script('assets/plugins/switchery/switchery.js') }}
<script>

    var btn_pagar = "#btn-pagar";
    var id_form = "#formPay";

    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function (html) {
        var switchery = new Switchery(html, {secondaryColor: '#FF5656', className: "switchery switchery-small"});

    });
</script>

@stop
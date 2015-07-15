<?php
$payu = new MetPayU();
//Obtiene un array de metodos de pagos activos
$metPagosActivos = $payu->obtenerMetodosPagosActivos();
?>
<h2><span class="glyphicon glyphicon-piggy-bank"></span> {{trans("fact.orden.pago.payu.titulo")}}</h2>

<div id="msj-error-tc" style="display: none;" class="alert alert-danger"></div>

<!-- Nav tabs -->
<ul id="metodos-pagos-tab-payu" class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#tcredito-content" class="metpay" aria-controls="tcredito-content" role="tcredito" data-toggle="tab"><img src='{{URL::to("assets/img/icons/tcredito.png")}}'>{{trans("fact.orden.pago.payu.metodo.tcredito.titulo")}}</a></li>
    @if(in_array(MetPayU::METODO_PAGO_PSE,$metPagosActivos)) 
    <li role="presentation"><a href="#tbancaria-content" class="metpay" aria-controls="tbancaria-content" role="tbancaria" data-toggle="tab"><img src='{{URL::to("assets/img/icons/banco.png")}}'>{{trans("fact.orden.pago.payu.metodo.tbancaria.titulo")}}</a></li> 
    @endif
    @if(in_array(MetPayU::METODO_PAGO_BALOTO,$metPagosActivos) || in_array(MetPayU::METODO_PAGO_EFECTY,$metPagosActivos)) 
    <li role="presentation"><a href="#efectivo-content" class="metpay" aria-controls="efectivo-content" role="efectivo" data-toggle="tab"><img src='{{URL::to("assets/img/icons/efectivo.png")}}'>{{trans("fact.orden.pago.payu.metodo.efectivo.titulo")}}</a></li> 
    @endif
</ul>

<!-- Tab panes -->
<div id="pagos-payou-tab-content" class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="tcredito-content">
        @include("usuarios/general/facturacion/gateway/payu/tcredito",array("metPagosActivos"=>$metPagosActivos))
    </div>
    @if(in_array(MetPayU::METODO_PAGO_PSE,$metPagosActivos))
    <div role="tabpanel" class="tab-pane fade" id="tbancaria-content">
        @include("usuarios/general/facturacion/gateway/payu/tbancaria",array("metPagosActivos"=>$metPagosActivos,"payu"=>$payu))
    </div>
    @endif
    @if(in_array(MetPayU::METODO_PAGO_BALOTO,$metPagosActivos) || in_array(MetPayU::METODO_PAGO_EFECTY,$metPagosActivos)) 
    <div role="tabpanel" class="tab-pane fade" id="efectivo-content">
        @include("usuarios/general/facturacion/gateway/payu/efectivo",array("metPagosActivos"=>$metPagosActivos))
    </div>
    @endif
</div>


<div class='col-lg-12 text-center' id="logos-cert">
    <a target="_blank" herf="http://payu.com.co/"><img src='{{URL::to("assets/img/icons/pay-u.png")}}'></a>
    <a target="_blank" href='https://sslanalyzer.comodoca.com/?url=appsthergo.com'><img src='{{URL::to("assets/img/icons/comodo-ssl.png")}}'></a>
    <a target="_blank" href='https://www.digicert.com/help/index.htm?host=appsthergo.com'><img src='{{URL::to("assets/img/icons/ssl.png")}}'></a>
</div>

{{ HTML::script('assets/jscode/util.js') }}
{{ HTML::script('assets/jscode/payupay.js') }}

<script>
    $("#metodos-pagos-tab a").click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    })
</script>
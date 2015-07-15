<div class="col-lg-12" style="margin-bottom: 30px;border: 1px gainsboro solid;border-top: 0px;padding: 10px;">    
    <div class="col-lg-12 text-center" style="margin-bottom: 20px;margin-top: 20px;">
        <h2>{{trans("fact.orden.pago.payu.efectivo.titulo")}}</h2>
        <div class="list-group text-left" style="margin-top:30px;margin-bottom:40px;">
            <a href="#" class="list-group-item"><span class="glyphicon glyphicon-chevron-right"></span> {{trans("fact.orden.pago.payu.efectivo.paso.1")}}</a>
            <a href="#" class="list-group-item"><span class="glyphicon glyphicon-chevron-right"></span> {{trans("fact.orden.pago.payu.efectivo.paso.2")}}</a>
            <a href="#" class="list-group-item"><span class="glyphicon glyphicon-chevron-right"></span> {{trans("fact.orden.pago.payu.efectivo.paso.3")}}</a>
            <a href="#" class="list-group-item"><span class="glyphicon glyphicon-chevron-right"></span> {{trans("fact.orden.pago.payu.efectivo.paso.4")}}</a>
            <a href="#" class="list-group-item"><span class="glyphicon glyphicon-chevron-right"></span> {{trans("fact.orden.pago.payu.efectivo.paso.5")}}</a>
            <a href="#" class="list-group-item"><span class="glyphicon glyphicon-chevron-right"></span> {{trans("fact.orden.pago.payu.efectivo.paso.6")}}</a>
        </div>
        <h4>{{trans("fact.orden.pago.pay.efectivo.seleccionar")}}</h4>
        <div class="btn-icon-plat-efectivo">
            <img style="height: 100px;max-width: 100%;" src='{{URL::to("assets/img/icons/baloto.png")}}'>
        </div>
        <div class="btn-icon-plat-efectivo">
            <img style="height: 50px;margin-top: 30px;max-width: 100%;" src='{{URL::to("assets/img/icons/efecty.png")}}'>
        </div>
    </div>
    <div class="col-lg-12 text-center" style="margin-top: 40px;margin-bottom: 50px;">
        <form id="formPay-efectivo" action="{{URL::to("fact/orden/pago/procesar/efectivo/payu")}}" method="post">
            <button type="button" id="btn-pagar-pse" class="btn btn-lg btn-primary text-uppercase"><span class="glyphicon glyphicon-list-alt"></span> {{trans("fact.orden.pago.pay.efectio.submit")}}</button>
        </form>
    </div>
</div>
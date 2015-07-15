
<h2><span class="glyphicon glyphicon-credit-card"></span> {{trans("fact.orden.pago.informacion.pago.titulo")}}</h2>



<div id="msj-error-tc" style="display: none;" class="alert alert-danger"></div>



<form id="CCForm" action="{{URL::to("fact/orden/pago/procesar/tcredito/2checkout")}}" method="post">
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
        <div class="col-lg-6 input-lg"> <input id="cvv" onkeydown="return soloNumeros(this, '');" class="form-control input-lg" maxlength="4" size="4" type="password" value="" autocomplete="off" required /></div>

        <div class="col-lg-12 text-center" style="margin-top: 50px;">
            <button type="button" id="btn-pagar" class="btn btn-lg btn-primary text-uppercase"><span class="glyphicon glyphicon-ok-circle"></span> {{trans("fact.btn.realizar.pago")}}</button>
        </div>
    </div>
</form>


<script>
    var idSeller = "{{Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_idSeller)}}";
    var publicKeyUser = "{{Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_publicKey)}}";
    var sandbox = <?php echo (Util::convertirIntToBoolean(Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_sandbox))) ? "true" : "false"; ?>;
 </script>


<script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
{{ HTML::script('assets/jscode/util.js') }}
{{ HTML::script('assets/jscode/checkoutpay.js')}}


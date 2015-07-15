
<?php $cantMaxCuotas = Facturacion::calcularNumeroCuotasMaxima($valor_total);?>

<form id="formPay-tcredito" action="{{URL::to("fact/orden/pago/procesar/tcredito/payu")}}" method="post">
    <div class="col-lg-12" style="margin-bottom: 30px;border: 1px gainsboro solid;border-top: 0px;padding: 10px;">    
        <div class="col-lg-6 input-lg" style="margin-bottom: 10px;padding-top: 35px;">
            <img style="height: 30px;" src="{{URL::to('assets/img/icons/mastercard.png')}}"/>
            <img style="height: 30px;" src="{{URL::to('assets/img/icons/visa.png')}}"/>
            <img style="height: 30px;" src="{{URL::to('assets/img/icons/americanexpress.png')}}"/>
            <img style="height: 30px;" src="{{URL::to('assets/img/icons/dinersclub.png')}}"/>
        </div>
        <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">
            <div class="radio radio-primary" style="width: 49%;display: inline-block;">
                <input @if(!in_array(MetPayU::METODO_PAGO_MASTERCARD,$metPagosActivos)){{"disabled='disabled'"}}@endif type="radio" id="master-tc" name="fraqTC" value="master" checked="checked"> <label for="master-tc">Mastercard</label>
            </div>
            <div class="radio radio-primary" style="width: 49%;display: inline-block;">
                <input @if(!in_array(MetPayU::METODO_PAGO_VISA,$metPagosActivos)){{"disabled='disabled'"}}@endif type="radio" id="visa-tc" name="fraqTC" value="visa"> <label for="visa-tc">Visa</label>
            </div>
            <div class="radio radio-primary" style="width: 49%;display: inline-block;">
                <input @if(!in_array(MetPayU::METODO_PAGO_AMEX,$metPagosActivos)){{"disabled='disabled'"}}@endif type="radio" id="america-tc" name="fraqTC" value="amex"> <label for="america-tc">American Express</label>
            </div>
            <div class="radio radio-primary" style="width: 49%;display: inline-block;">
                <input @if(!in_array(MetPayU::METODO_PAGO_DINERS,$metPagosActivos)){{"disabled='disabled'"}}@endif type="radio" id="diners-tc" name="fraqTC" value="diners"><label for="diners-tc">Diners Club</label>
            </div>
        </div>
        <div class="col-lg-6 input-lg" style="margin-top: 30px;"> 
            {{trans("fact.orden.pago.tc.titulo")}}
        </div>
        <div class="col-lg-6" style="margin-top: 30px;">
            <input id="ccNo" name="ccNo" class="form-control input-lg" maxlength="20" onkeydown="return soloNumeros(this, '');" type="text" size="20" value="" autocomplete="off" required />
        </div>
        <div class="col-lg-6 input-lg"> 
            {{trans("fact.orden.pago.tc.fecha.vencimiento")}}
        </div>
        <div class="col-lg-6 input-lg">
            <select id="mes-exp" name="mes-exp" class="form-control" style="width: 20%;display:initial;  font-size: 12pt;">
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
            <select id="ano-exp" name="ano-exp" class="form-control" style="width: 20%;display:initial; font-size: 12pt;">
                <option value=""></option>
                <?php for ($i = date("Y"); $i < date("Y") + 12; $i++): ?>
                    <option value="{{$i}}">{{$i}}</option>
<?php endfor; ?>
            </select>
            <input type="hidden" size="2" id="expMonth" value="" required />
            <input type="hidden" size="2" id="expYear" value="" required /></div>
        <div class="col-lg-6 input-lg" style="margin-bottom: 10px;"> 
            {{trans("fact.orden.pago.tc.cvv")}}
        </div>
        <div class="col-lg-6 input-lg" style="margin-bottom: 10px;"> 
            <input id="cvv" name="cvv" onkeydown="return soloNumeros(this, '');" class="form-control input-lg" style="width:30%" maxlength="4" size="4" type="password" value="" autocomplete="off" required />
        </div>
        {{-- NUMERO DE CUOTAS--}}
        <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">{{trans("fact.orden.pago.tc.numero.cuotas")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans('fact.orden.pago.tc.numero.cuotas.ayuda',array("num"=>$cantMaxCuotas))))</div>
        <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">

            <select class="form-control" name="num_cuotas">
                @for($i=1;$i<=$cantMaxCuotas;$i++)
                <option value="{{$i}}">{{$i}} x {{Monedas::nomenclatura($moneda,round($valor_total/$i))}}</option>
                @endfor
            </select>

        </div>
        <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">{{trans("fact.orden.pago.payu.tcredito.igual.datos.facturacion")}}</div>
        <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">
            {{trans("otros.info.no")}}
            <input type="checkbox" class="js-switch"  data-for="check_datos_fact">
            {{trans("otros.info.si")}}
            <input type="hidden" id="check_datos_fact" name="check_datos_fact" value="0"/>
        </div>
        <div class="col-lg-12" id="info-pagador">
            <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">{{trans("fact.orden.pago.payu.nombre.tarjetahabiente")}}</div>
            <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">
                <input id="cp_nombre" name="cp_nombre" class="form-control input-lg" maxlength="80"  type="text" size="20" value="" autocomplete="off" required />
            </div>
            <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">{{trans("menu_usuario.mi_perfil.info.email")}}</div>
            <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">
                <input id="cp_email" name="cp_email" class="form-control input-lg" maxlength="80"  type="text" size="20" value="" autocomplete="off" required />
            </div>
            <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">{{trans("menu_usuario.mi_perfil.info.dni")}}</div>
            <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">
                <input id="cp_dni" name="cp_dni" class="form-control input-lg" maxlength="80"  type="text" size="20" value="" autocomplete="off" required />
            </div>
            <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">{{trans("menu_usuario.mi_perfil.info.direccion")}}</div>
            <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">
                <input id="cp_direccion" name="cp_direccion" class="form-control input-lg" maxlength="80"  type="text" size="20" value="" autocomplete="off" required />
            </div>
            <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">{{trans("menu_usuario.mi_perfil.info.ciudad")}}</div>
            <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">
                <input id="cp_ciudad" name="cp_ciudad" class="form-control input-lg" maxlength="80"  type="text" size="20" value="" autocomplete="off" required />
            </div>
            <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">{{trans("menu_usuario.mi_perfil.info.region")}}</div>
            <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">
                <input id="cp_region" name="cp_region" class="form-control input-lg" maxlength="80"  type="text" size="20" value="" autocomplete="off" required />
            </div>
            <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">{{trans("menu_usuario.mi_perfil.info.telefono")}}</div>
            <div class="col-lg-6 input-lg" style="margin-bottom: 10px;">
                <input id="cp_telefono" name="cp_telefono" class="form-control input-lg" onkeydown="return soloNumeros(this, '');" maxlength="10"  type="text" size="20" value="" autocomplete="off" required />
            </div>
        </div>

        <div class="col-lg-12 text-center" style="margin-top: 20px;">
            <button type="button" id="btn-pagar-tcredito" class="btn btn-lg btn-primary text-uppercase"><span class="glyphicon glyphicon-ok-circle"></span> {{trans("fact.btn.realizar.pago")}}</button>
        </div>
    </div>
</form>



<script>
    jQuery(".js-switch").change(function () {
        var config = "#" + $(this).attr("data-for");
        if ($(this).is(':checked')) {
            $(config).val("{{Util::convertirBooleanToInt(true)}}");
            $("#info-pagador").slideToggle();
        }
        else {
            $(config).val("{{Util::convertirBooleanToInt(false)}}");
            $("#info-pagador").slideToggle();
        }
    });
</script>

<?php
$bancos = $payu->obtenerBancosActivos();
$docs = MetPayU::getTiposDocumentos();
?>

<form id="formPay-pse" action="{{URL::to("fact/orden/pago/procesar/tbancaria/payu")}}" method="post">
    <div class="col-lg-12" style="margin-bottom: 30px;border: 1px gainsboro solid;border-top: 0px;padding: 10px;">    
        <div class="col-lg-12 text-center" style="margin-bottom: 20px;margin-top: 50px;">
            <h4><span class="glyphicon glyphicon-lock"></span> {{trans("fact.orden.pago.payu.pse.info.seguro")}}</h4>
            <img src='{{URL::to("assets/img/icons/PSE.png")}}'>
        </div>
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div class="col-lg-12"><div id="msj-error-pse" style="display: none;" class="alert alert-danger"></div></div>

            <div class="col-lg-6 input-lg" style="">
                {{trans("fact.orden.pago.payu.pse.seleccionar.banco")}}
            </div>
            <div class="col-lg-6 input-lg" style="">
                <select name="pse_banco" class="form-control">
                    @foreach ($bancos as $banco)
                    <option value="{{$banco->pseCode}}">{{$banco->description}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-6 input-lg" style="">
                {{trans("fact.orden.pago.payu.pse.seleccionar.tipo.persona")}}
            </div>
            <div class="col-lg-6 input-lg" style="">
                <select name="pse_persona" class="form-control">
                    <option value="N">{{trans("fact.orden.pago.payu.pse.seleccionar.tipo.persona.natural")}}</option>
                    <option value="J">{{trans("fact.orden.pago.payu.pse.seleccionar.tipo.persona.juridica")}}</option>
                </select>
            </div>
            <div class="col-lg-6 input-lg" style="">
                {{trans("fact.orden.pago.payu.pse.seleccionar.tipo.documento")}}
            </div>
            <div class="col-lg-6 input-lg" style="">
                <select name="pse_tipoDocumento" class="form-control">
                    @foreach($docs as $const => $codigo)
                    <option value="{{$codigo}}">{{MetPayU::getDescripcionTipoDocumento($codigo)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-6 input-lg">
                {{trans("fact.orden.pago.payu.pse.numero.documento")}}
            </div>
            <div class="col-lg-6 input-lg">
                <input class="form-control input-lg" maxlength="20" onkeydown="return soloNumeros(this, '');" type="text" name="pse_numDoc" id="pse_numDoc"/>
            </div>
            <div class="col-lg-6 input-lg" style="margin-top: 10px;">
                {{trans("fact.orden.pago.payu.pse.nombres")}}
            </div>
            <div class="col-lg-6 input-lg" style="margin-top: 10px;">
                <input class="form-control input-lg" maxlength="100" type="text" maxlength="20" name="pse_nombre" id="pse_nombre"/>
            </div>
            <div class="col-lg-6 input-lg" style="margin-top: 10px;">
                {{trans("menu_usuario.mi_perfil.info.telefono")}}(*)
            </div>
            <div class="col-lg-6 input-lg" style="margin-top: 10px;">
                <input class="form-control input-lg" maxlength="20" onkeydown="return soloNumeros(this, '');" type="text" name="pse_telefono" id="pse_telefono"/>
            </div>
            <div class="col-lg-6 input-lg" style="margin-top: 10px;">
                {{trans("menu_usuario.mi_perfil.info.email")}}(*)
            </div>
            <div class="col-lg-6 input-lg" style="margin-top: 10px;">
                <input class="form-control input-lg" maxlength="80" type="text" name="pse_email" id="pse_email"/>
            </div>
        </div>
        <div class="col-lg-2"></div>
        <div class="col-lg-12 text-center" style="margin-top: 70px;margin-bottom: 70px;">
            <button type="button" id="btn-pagar-pse" class="btn btn-lg btn-primary text-uppercase"><span class="glyphicon glyphicon-ok-circle"></span> {{trans("fact.btn.realizar.pago")}}</button>
        </div>

    </div>
</form>
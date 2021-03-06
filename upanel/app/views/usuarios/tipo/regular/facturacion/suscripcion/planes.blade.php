<?php
$moneda = Auth::user()->getMoneda();
$hab_android = true;
$hab_ios = false;
$hab_windows = false;
$tipo_suscripcion = User::obtenerValorMetadato(UsuarioMetadato::SUSCRIPCION_TIPO);
$dias_suscripcion = round(Fecha::difSec(Util::obtenerTiempoActual(), Auth::user()->fin_suscripcion) / (60 * 60 * 24));

$valor_bronce = Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_1mes_bronce . "-" . $moneda);
$valor_plata = Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_1mes_plata . "-" . $moneda);
$valor_oro = Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_1mes_oro . "-" . $moneda);

//*************************************
$precioDia_bronce = $valor_bronce / 30;
$precioDia_plata = $valor_plata / 30;
$precioDia_oro = $valor_oro / 30;
?>


@extends('interfaz/plantilla')

@section("titulo") {{trans("fact.suscripcion.plan.titulo")}} @stop

@section("css")
<style>

    #plan-bronce,#plan-plata,#plan-oro{
        border: 1px rgb(144, 144, 144) solid;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        background-color: ghostwhite;
        margin: 0px 1%;
        width: 30%;
        padding-bottom: 20px;
        margin-bottom: 50px;
    }

    #plan-bronce .description-item,#plan-plata .description-item,#plan-oro .description-item{
        display: inline-block;
        max-width: 265px;
        vertical-align: top;
    }

    #plan-bronce .glyphicon-certificate,#plan-bronce h2{
        color:rgb(202, 161, 107);
    }

    #plan-plata .glyphicon-certificate,#plan-plata h2{
        color:rgb(147, 147, 147);
    }

    #plan-oro .glyphicon-certificate,#plan-oro h2{
        color:rgb(166, 140, 0);
    }

    #plan-bronce h2,#plan-plata h2,#plan-oro h2{
        padding: 5px;
        border: 1px rgb(161, 161, 161) solid;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        text-shadow: 1px 1px #000000;
    }

    #plan-bronce h2{
        background-color: rgb(255, 235, 209);
    }

    #plan-plata h2{
        background: #eeeeee; /* Old browsers */
        background: -moz-linear-gradient(left,  #eeeeee 0%, #cccccc 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, right top, color-stop(0%,#eeeeee), color-stop(100%,#cccccc)); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(left,  #eeeeee 0%,#cccccc 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(left,  #eeeeee 0%,#cccccc 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(left,  #eeeeee 0%,#cccccc 100%); /* IE10+ */
        background: linear-gradient(to right,  #eeeeee 0%,#cccccc 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eeeeee', endColorstr='#cccccc',GradientType=1 ); /* IE6-9 */

    }

    #plan-oro h2{
        background: #ffd65e; /* Old browsers */
        background: -moz-linear-gradient(left,  #ffd65e 0%, #febf04 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, right top, color-stop(0%,#ffd65e), color-stop(100%,#febf04)); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(left,  #ffd65e 0%,#febf04 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(left,  #ffd65e 0%,#febf04 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(left,  #ffd65e 0%,#febf04 100%); /* IE10+ */
        background: linear-gradient(to right,  #ffd65e 0%,#febf04 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffd65e', endColorstr='#febf04',GradientType=1 ); /* IE6-9 */
    }

    #plan-bronce ul.list-items,#plan-plata ul.list-items,#plan-oro ul.list-items{
        list-style: none;
        padding: 5px;
        min-height: 183px;
    }

    #plan-bronce ul.list-subitems,#plan-plata ul.list-subitems,#plan-oro ul.list-subitems{
        list-style: none;
        padding-left: 22px;
        border: 1px rgb(144, 144, 144) solid;
        background-color: cornsilk;
        margin-top: 10px;
        margin-bottom: 10px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        padding-top: 10px;
    }

    #plan-bronce ul.list-subitems li,#plan-plata ul.list-subitems li,#plan-oro ul.list-subitems li{
        font-size: 10pt;
    }

    #plan-bronce .platform,#plan-plata .platform,#plan-oro .platform{
        width:30px;
        margin:0px 5px;
    }

    #plan-bronce .well-sm,#plan-plata .well-sm,#plan-oro .well-sm{
        text-align: center;
        margin-bottom: 0px;
        background: currentColor;
    }

    #plan-bronce ul li,#plan-plata ul li,#plan-oro ul li {
        margin-bottom: 10px;
        font-size: 13pt;
    }


    #plan-bronce .precio,#plan-plata .precio,#plan-oro .precio{
        padding: 10px;
        text-align: center;
        font-size: 16pt;
        font-weight: bold;
        margin-top: 10px;
    }

    #plan-bronce .precio .mark-mes,#plan-plata .precio .mark-mes,#plan-oro .precio .mark-mes{
        font-size: 10pt;
        font-weight: 100;
        color: gray;
    }

    #plan-bronce .btn,#plan-plata .btn,#plan-oro .btn{
        width: 100%;
        padding: 10px;
        font-size: 13pt;
    }

    #plan-bronce hr,#plan-plata hr,#plan-oro hr{
        border-color: rgb(195, 195, 195);
    }

</style>
@stop

@section("contenido") 

<h1><span class="glyphicon glyphicon-gift"></span> {{trans("fact.suscripcion.plan.titulo")}}</h1>


<h3 style="margin-top:50px;">{{trans("fact.suscripcion.plan.info.escoger")}}:</h3>

<div class="col-lg-12" style="margin-top:10px;">

    {{--PLAN DE BRONCE--}}

    <div id="plan-bronce" class="col-lg-4">
        <h2>
            <span class="glyphicon glyphicon-certificate"></span> {{trans("fact.suscripcion.plan.bronce.titulo")}}
        </h2>
        <div class="block">
            <ul class="list-items">
                <li><span class="glyphicon glyphicon-ok"></span> <span class="description-item">{{trans("fact.suscripcion.plan.item.plataformas.1")}}</span></li>
                <li><span class="glyphicon glyphicon-ok"></span> <span class="description-item">{{trans("fact.suscripcion.plan.item.atencion.personalizada")}}</span></li>
                <li><span class="glyphicon glyphicon-ok"></span> <span class="description-item">{{trans("fact.suscripcion.plan.item.disenos.apps")}}</span>
                    <ul class="list-subitems">
                        <li><span class="glyphicon glyphicon-plus"></span> {{trans("fact.suscripcion.plan.item.disenos.apps.soluciones.noticias")}}</li>
                        <li><span class="glyphicon glyphicon-plus"></span> {{trans("fact.suscripcion.plan.item.disenos.apps.soluciones.institucional")}}</li>
                        <li><span class="glyphicon glyphicon-plus"></span> {{trans("fact.suscripcion.plan.item.disenos.apps.soluciones.encuestas")}}</li>
                        <li><span class="glyphicon glyphicon-plus"></span> {{trans("fact.suscripcion.plan.item.disenos.apps.soluciones.pqr")}}</li>
                    </ul>
                </li>
            </ul>
            <div class="well well-sm">
                @if($hab_android) <img title="{{trans("fact.suscripcion.plan.item.aplicacion.android")}}" class="platform tooltip-top" src="{{URL::to("assets/img/android.png")}}" /> @endif
                @if($hab_windows)<img title="{{trans("fact.suscripcion.plan.item.aplicacion.windows")}}" class="platform tooltip-top" src="{{URL::to("assets/img/windows.png")}}" /> @endif
                @if($hab_ios)<img title="{{trans("fact.suscripcion.plan.item.aplicacion.iphone")}}" class="platform tooltip-top" src="{{URL::to("assets/img/ios.png")}}" /> @endif
            </div>
            <div class="precio">
                {{Monedas::simbolo($moneda)}}{{Monedas::formatearNumero($moneda,$valor_bronce)}} {{$moneda}}<span class="mark-mes">/{{trans("otros.time.mes")}}</span>
            </div>
            <hr/>
            <div class="block text-center">  
                @if($tipo_suscripcion==ConfigInstancia::suscripcion_tipo_bronce && Auth::user()->estado==User::ESTADO_SUSCRIPCION_VIGENTE)
                <a href="{{URL::to("fact/suscripcion/ciclo/".ConfigInstancia::suscripcion_tipo_bronce)}}" class="btn btn-primary"><span class="glyphicon glyphicon-shopping-cart"></span> {{Util::convertirMayusculas(trans("fact.btn.renovar"))}}</a>
                @elseif(($tipo_suscripcion==ConfigInstancia::suscripcion_tipo_plata || $tipo_suscripcion==ConfigInstancia::suscripcion_tipo_oro) && Auth::user()->estado==User::ESTADO_SUSCRIPCION_VIGENTE)
                <a disabled="disabled" href="{{URL::to("fact/suscripcion/ciclo/".ConfigInstancia::suscripcion_tipo_bronce)}}" class="btn btn-default"><span class="glyphicon glyphicon-ok"></span>  {{Util::convertirMayusculas(trans("fact.btn.adquirido"))}}</a>
                @else
                <a href="{{URL::to("fact/suscripcion/ciclo/".ConfigInstancia::suscripcion_tipo_bronce)}}" class="btn btn-primary"><span class="glyphicon glyphicon-shopping-cart"></span>  {{Util::convertirMayusculas(trans("fact.btn.ordenar.ahora"))}}</a>
                @endif
            </div>

        </div>
    </div>   

    {{--PLAN DE PLATA--}}

    <div id="plan-plata" class="col-lg-4">
        <h2>
            <span class="glyphicon glyphicon-certificate"></span> {{trans("fact.suscripcion.plan.plata.titulo")}}
        </h2>
        <div class="block">
            <ul class="list-items">
                <li><span class="glyphicon glyphicon-ok"></span> <span class="description-item">{{trans("fact.suscripcion.plan.item.plataformas.2")}}</span></li>
                <li><span class="glyphicon glyphicon-ok"></span> <span class="description-item">{{trans("fact.suscripcion.plan.item.atencion.personalizada")}}</span></li>
                <li><span class="glyphicon glyphicon-ok"></span> <span class="description-item">{{trans("fact.suscripcion.plan.item.disenos.apps")}}</span>
                    <ul class="list-subitems">
                        <li><span class="glyphicon glyphicon-plus"></span> {{trans("fact.suscripcion.plan.item.disenos.apps.soluciones.noticias")}}</li>
                        <li><span class="glyphicon glyphicon-plus"></span> {{trans("fact.suscripcion.plan.item.disenos.apps.soluciones.institucional")}}</li>
                        <li><span class="glyphicon glyphicon-plus"></span> {{trans("fact.suscripcion.plan.item.disenos.apps.soluciones.encuestas")}}</li>
                        <li><span class="glyphicon glyphicon-plus"></span> {{trans("fact.suscripcion.plan.item.disenos.apps.soluciones.pqr")}}</li>
                    </ul>
                </li>
            </ul>
            <div class="well well-sm">
                @if($hab_android) <img title="{{trans("fact.suscripcion.plan.item.aplicacion.android")}}" class="platform tooltip-top" src="{{URL::to("assets/img/android.png")}}" /> @endif
                @if($hab_windows)<img title="{{trans("fact.suscripcion.plan.item.aplicacion.windows")}}" class="platform tooltip-top" src="{{URL::to("assets/img/windows.png")}}" /> @endif
                @if($hab_ios)<img title="{{trans("fact.suscripcion.plan.item.aplicacion.iphone")}}" class="platform tooltip-top" src="{{URL::to("assets/img/ios.png")}}" /> @endif
            </div>
            <div class="precio">
                @if($tipo_suscripcion==ConfigInstancia::suscripcion_tipo_bronce && Auth::user()->estado==User::ESTADO_SUSCRIPCION_VIGENTE)
                {{Monedas::nomenclatura($moneda,round($dias_suscripcion*($precioDia_plata-$precioDia_bronce),($moneda==Monedas::COP)?-3:0))}}
                @else
                {{Monedas::nomenclatura($moneda,$valor_plata)}} <span class="mark-mes">/{{trans("otros.time.mes")}}</span>
                @endif
            </div>
            <hr/>
            <div class="block text-center">
                @if($tipo_suscripcion==ConfigInstancia::suscripcion_tipo_plata && Auth::user()->estado==User::ESTADO_SUSCRIPCION_VIGENTE)
                <a href="{{URL::to("fact/suscripcion/ciclo/".ConfigInstancia::suscripcion_tipo_plata)}}" class="btn btn-primary"><span class="glyphicon glyphicon-shopping-cart"></span> {{Util::convertirMayusculas(trans("fact.btn.renovar"))}}</a>
                @elseif($tipo_suscripcion==ConfigInstancia::suscripcion_tipo_oro && Auth::user()->estado==User::ESTADO_SUSCRIPCION_VIGENTE)
                <a disabled="disabled" href="{{URL::to("fact/suscripcion/ciclo/".ConfigInstancia::suscripcion_tipo_plata)}}" class="btn btn-default"><span class="glyphicon glyphicon-ok"></span>  {{Util::convertirMayusculas(trans("fact.btn.adquirido"))}}</a>
                @elseif($tipo_suscripcion==ConfigInstancia::suscripcion_tipo_bronce && Auth::user()->estado==User::ESTADO_SUSCRIPCION_VIGENTE)
                <form action="{{URL::to("fact/orden/pago")}}" method="POST">
                    <input type="hidden" name="{{UsuarioMetadato::HASH_CREAR_FACTURA}}" value="{{$hash}}"/>
                    <input type="hidden" name="{{MetaFacturacion::PRODUCTO_ID}}" value="{{ConfigInstancia::producto_suscripcion_plata_actualizacion}}"/>
                    <input type="hidden" name="{{MetaFacturacion::PRODUCTO_VALOR}}" value="{{round($dias_suscripcion*($precioDia_plata-$precioDia_bronce),($moneda==Monedas::COP)?-3:0)}}"/>
                    <input type="hidden" name="{{MetaFacturacion::PRODUCTO_DESCUENTO}}" value="0"/>
                    <input type="hidden" name="{{MetaFacturacion::MONEDA_ID}}" value="{{$moneda}}"/>
                    <button class="btn btn-success"><span class="glyphicon glyphicon-circle-arrow-up"></span>  {{Util::convertirMayusculas(trans("fact.btn.mejorar"))}}</button>
                </form>
                @else
                <a href="{{URL::to("fact/suscripcion/ciclo/".ConfigInstancia::suscripcion_tipo_plata)}}" class="btn btn-primary"><span class="glyphicon glyphicon-shopping-cart"></span>  {{Util::convertirMayusculas(trans("fact.btn.ordenar.ahora"))}}</a>
                @endif
            </div>

        </div>
    </div>   


    {{--PLAN DE ORO--}}

    <div id="plan-oro" class="col-lg-4">
        <h2>
            <span class="glyphicon glyphicon-certificate"></span> {{trans("fact.suscripcion.plan.oro.titulo")}}
        </h2>
        <div class="block">
            <ul class="list-items">
                <li><span class="glyphicon glyphicon-ok"></span> <span class="description-item">{{trans("fact.suscripcion.plan.item.plataformas.3")}}</span></li>
                <li><span class="glyphicon glyphicon-ok"></span> <span class="description-item">{{trans("fact.suscripcion.plan.item.atencion.personalizada")}}</span></li>
                <li><span class="glyphicon glyphicon-ok"></span> <span class="description-item">{{trans("fact.suscripcion.plan.item.disenos.apps")}}</span>
                    <ul class="list-subitems">
                        <li><span class="glyphicon glyphicon-plus"></span> {{trans("fact.suscripcion.plan.item.disenos.apps.soluciones.noticias")}}</li>
                        <li><span class="glyphicon glyphicon-plus"></span> {{trans("fact.suscripcion.plan.item.disenos.apps.soluciones.institucional")}}</li>
                        <li><span class="glyphicon glyphicon-plus"></span> {{trans("fact.suscripcion.plan.item.disenos.apps.soluciones.encuestas")}}</li>
                        <li><span class="glyphicon glyphicon-plus"></span> {{trans("fact.suscripcion.plan.item.disenos.apps.soluciones.pqr")}}</li>
                    </ul>
                </li>
            </ul>
            <div class="well well-sm">
                @if($hab_android) <img title="{{trans("fact.suscripcion.plan.item.aplicacion.android")}}" class="platform tooltip-top" src="{{URL::to("assets/img/android.png")}}" /> @endif
                @if($hab_windows)<img title="{{trans("fact.suscripcion.plan.item.aplicacion.windows")}}" class="platform tooltip-top" src="{{URL::to("assets/img/windows.png")}}" /> @endif
                @if($hab_ios)<img title="{{trans("fact.suscripcion.plan.item.aplicacion.iphone")}}" class="platform tooltip-top" src="{{URL::to("assets/img/ios.png")}}" /> @endif
            </div>
            <div class="precio">
                @if($tipo_suscripcion==ConfigInstancia::suscripcion_tipo_bronce && Auth::user()->estado==User::ESTADO_SUSCRIPCION_VIGENTE)
                {{Monedas::nomenclatura($moneda,round($dias_suscripcion*($precioDia_oro-$precioDia_bronce),($moneda==Monedas::COP)?-3:0))}}
                @elseif($tipo_suscripcion==ConfigInstancia::suscripcion_tipo_plata && Auth::user()->estado==User::ESTADO_SUSCRIPCION_VIGENTE)
                {{Monedas::nomenclatura($moneda,round($dias_suscripcion*($precioDia_oro-$precioDia_plata),($moneda==Monedas::COP)?-3:0))}}
                @else
                {{Monedas::nomenclatura($moneda,$valor_oro)}} <span class="mark-mes">/{{trans("otros.time.mes")}}</span>
                @endif   
            </div>
            <hr/>
            <div class="block text-center">
                @if($tipo_suscripcion==ConfigInstancia::suscripcion_tipo_oro && Auth::user()->estado==User::ESTADO_SUSCRIPCION_VIGENTE)
                <a href="{{URL::to("fact/suscripcion/ciclo/".ConfigInstancia::suscripcion_tipo_oro)}}" class="btn btn-primary"><span class="glyphicon glyphicon-shopping-cart"></span> {{Util::convertirMayusculas(trans("fact.btn.renovar"))}}</a>
                @elseif(($tipo_suscripcion==ConfigInstancia::suscripcion_tipo_bronce || $tipo_suscripcion==ConfigInstancia::suscripcion_tipo_plata) && Auth::user()->estado==User::ESTADO_SUSCRIPCION_VIGENTE)
                <form action="{{URL::to("fact/orden/pago")}}" method="POST">
                    <input type="hidden" name="{{UsuarioMetadato::HASH_CREAR_FACTURA}}" value="{{$hash}}"/>
                    <input type="hidden" name="{{MetaFacturacion::PRODUCTO_ID}}" value="{{ConfigInstancia::producto_suscripcion_oro_actualizacion}}"/>
                    @if($tipo_suscripcion==ConfigInstancia::suscripcion_tipo_bronce)
                    <input type="hidden" name="{{MetaFacturacion::PRODUCTO_VALOR}}" value="{{round($dias_suscripcion*($precioDia_oro-$precioDia_bronce),($moneda==Monedas::COP)?-3:0)}}"/>
                    @elseif($tipo_suscripcion==ConfigInstancia::suscripcion_tipo_plata)
                    <input type="hidden" name="{{MetaFacturacion::PRODUCTO_VALOR}}" value="{{round($dias_suscripcion*($precioDia_oro-$precioDia_plata),($moneda==Monedas::COP)?-3:0)}}"/>
                    @endif
                    <input type="hidden" name="{{MetaFacturacion::PRODUCTO_DESCUENTO}}" value="0"/>
                    <input type="hidden" name="{{MetaFacturacion::MONEDA_ID}}" value="{{$moneda}}"/>
                    <button class="btn btn-success"><span class="glyphicon glyphicon-circle-arrow-up"></span>  {{Util::convertirMayusculas(trans("fact.btn.mejorar"))}}</button>
                </form>
                @else
                <a href="{{URL::to("fact/suscripcion/ciclo/".ConfigInstancia::suscripcion_tipo_oro)}}" class="btn btn-primary"><span class="glyphicon glyphicon-shopping-cart"></span>  {{Util::convertirMayusculas(trans("fact.btn.ordenar.ahora"))}}</a>
                @endif </div>

        </div>
    </div>   

</div>

@stop
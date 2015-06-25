<?php
$moneda = Auth::user()->getMoneda();
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

    #plan-bronce ul,#plan-plata ul,#plan-oro ul{
        list-style: none;
        padding: 5px;
        min-height: 183px;
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
        font-family: cursive;
    }

    #plan-bronce .precio .mark-mes,#plan-plata .precio .mark-mes,#plan-oro .precio .mark-mes{
        font-size: 10pt;
        font-weight: 100;
        color: gray;
    }

    #plan-bronce .btn-primary,#plan-plata .btn-primary,#plan-oro .btn-primary{
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
            <ul>
                <li><span class="glyphicon glyphicon-ok"></span> {{trans("fact.suscripcion.plan.item.aplicacion.android")}}</li>
                <li><span class="glyphicon glyphicon-ok"></span> {{trans("fact.suscripcion.plan.item.todas.plantillas")}}</li>
                <li><span class="glyphicon glyphicon-ok"></span> {{trans("fact.suscripcion.plan.item.atencion.personalizada")}}</li>
            </ul>
            <div class="well well-sm"><img title="{{trans("fact.suscripcion.plan.item.aplicacion.android")}}" class="platform tooltip-top" src="{{URL::to("assets/img/android.png")}}" /></div>
            <div class="precio">{{Monedas::simbolo($moneda)}}{{Monedas::formatearNumero($moneda,Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_1mes_bronce."-".$moneda))}} {{$moneda}}<span class="mark-mes">/{{trans("otros.time.mes")}}</span></div>
            <hr/>
            <div class="block text-center">
                <a href="{{URL::to("fact/suscripcion/ciclo/".ConfigInstancia::suscripcion_tipo_bronce)}}" class="btn btn-primary"><span class="glyphicon glyphicon-shopping-cart"></span>  {{Util::convertirMayusculas(trans("fact.btn.ordenar.ahora"))}}</a>
            </div>

        </div>
    </div>   

    {{--PLAN DE PLATA--}}

    <div id="plan-plata" class="col-lg-4">
        <h2>
            <span class="glyphicon glyphicon-certificate"></span> {{trans("fact.suscripcion.plan.plata.titulo")}}
        </h2>
        <div class="block">
            <ul>
                <li><span class="glyphicon glyphicon-ok"></span> {{trans("fact.suscripcion.plan.item.aplicacion.android")}}</li>
                <li><span class="glyphicon glyphicon-ok"></span> {{trans("fact.suscripcion.plan.item.aplicacion.windows")}}</li>
                <li><span class="glyphicon glyphicon-ok"></span> {{trans("fact.suscripcion.plan.item.todas.plantillas")}}</li>
                <li><span class="glyphicon glyphicon-ok"></span> {{trans("fact.suscripcion.plan.item.atencion.personalizada")}}</li>
            </ul>
            <div class="well well-sm">
                <img title="{{trans("fact.suscripcion.plan.item.aplicacion.android")}}" class="platform tooltip-top" src="{{URL::to("assets/img/android.png")}}" />
                <img title="{{trans("fact.suscripcion.plan.item.aplicacion.windows")}}" class="platform tooltip-top" src="{{URL::to("assets/img/windows.png")}}" />
            </div>
            <div class="precio">{{Monedas::simbolo($moneda)}}{{Monedas::formatearNumero($moneda,Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_1mes_plata."-".$moneda))}} {{$moneda}}<span class="mark-mes">/{{trans("otros.time.mes")}}</span></div>
            <hr/>
            <div class="block text-center">
                <a disabled="disabled" href="{{URL::to("fact/suscripcion/ciclo/".ConfigInstancia::suscripcion_tipo_plata)}}" class="btn btn-primary"><span class="glyphicon glyphicon-shopping-cart"></span>  {{Util::convertirMayusculas(trans("fact.btn.ordenar.ahora"))}}</a>
            </div>

        </div>
    </div>   


    {{--PLAN DE ORO--}}

    <div id="plan-oro" class="col-lg-4">
        <h2>
            <span class="glyphicon glyphicon-certificate"></span> {{trans("fact.suscripcion.plan.oro.titulo")}}
        </h2>
        <div class="block">
            <ul>
                <li><span class="glyphicon glyphicon-ok"></span> {{trans("fact.suscripcion.plan.item.aplicacion.android")}}</li>
                <li><span class="glyphicon glyphicon-ok"></span> {{trans("fact.suscripcion.plan.item.aplicacion.windows")}}</li>
                <li><span class="glyphicon glyphicon-ok"></span> {{trans("fact.suscripcion.plan.item.aplicacion.iphone")}}</li>
                <li><span class="glyphicon glyphicon-ok"></span> {{trans("fact.suscripcion.plan.item.todas.plantillas")}}</li>
                <li><span class="glyphicon glyphicon-ok"></span> {{trans("fact.suscripcion.plan.item.atencion.personalizada")}}</li>
            </ul>
            <div class="well well-sm">
                <img title="{{trans("fact.suscripcion.plan.item.aplicacion.android")}}" class="platform tooltip-top" src="{{URL::to("assets/img/android.png")}}" />
                <img title="{{trans("fact.suscripcion.plan.item.aplicacion.windows")}}" class="platform tooltip-top" src="{{URL::to("assets/img/windows.png")}}" />
                <img title="{{trans("fact.suscripcion.plan.item.aplicacion.iphone")}}" class="platform tooltip-top" src="{{URL::to("assets/img/ios.png")}}" />
            </div>
            <div class="precio">{{Monedas::simbolo($moneda)}}{{Monedas::formatearNumero($moneda,Instancia::obtenerValorMetadato(ConfigInstancia::suscripcion_valor_1mes_oro."-".$moneda))}} {{$moneda}}<span class="mark-mes">/{{trans("otros.time.mes")}}</span></div>
            <hr/>
            <div class="block text-center">
                <a disabled="disabled" href="{{URL::to("fact/suscripcion/ciclo/".ConfigInstancia::suscripcion_tipo_oro)}}" class="btn btn-primary"><span class="glyphicon glyphicon-shopping-cart"></span>  {{Util::convertirMayusculas(trans("fact.btn.ordenar.ahora"))}}</a>
            </div>

        </div>
    </div>   

</div>

@stop
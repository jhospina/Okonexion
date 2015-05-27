<?php
$ciclos = array(1, 3, 6, 12);
$prefijo = "suscripcion_valor_";
$moneda = Instancia::obtenerValorMetadato(ConfigInstancia::info_moneda);
?>

@extends('interfaz/plantilla')

@section("titulo") {{trans("fact.suscripcion.ciclo.titulo")}} @stop


@section("css")

<style>
    #susp-{{trans("fact.suscripcion.plan.".ConfigInstancia::suscripcion_tipo_bronce.".titulo")}}{
        background-color: rgb(255, 235, 209);
        color:rgb(202, 161, 107);
        padding: 5px;
        border: 1px black solid;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        text-shadow: 1px 1px #000000;
    }

    #susp-{{trans("fact.suscripcion.plan.".ConfigInstancia::suscripcion_tipo_plata.".titulo")}}{
        color:rgb(147, 147, 147);
        background: #eeeeee; /* Old browsers */
        background: -moz-linear-gradient(left,  #eeeeee 0%, #cccccc 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, right top, color-stop(0%,#eeeeee), color-stop(100%,#cccccc)); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(left,  #eeeeee 0%,#cccccc 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(left,  #eeeeee 0%,#cccccc 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(left,  #eeeeee 0%,#cccccc 100%); /* IE10+ */
        background: linear-gradient(to right,  #eeeeee 0%,#cccccc 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eeeeee', endColorstr='#cccccc',GradientType=1 ); /* IE6-9 */
        padding: 5px;
        border: 1px black solid;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        text-shadow: 1px 1px #000000;
    }

    #susp-{{trans("fact.suscripcion.plan.".ConfigInstancia::suscripcion_tipo_oro.".titulo")}}{
        background: #ffd65e;
        background: -moz-linear-gradient(left, #ffd65e 0%, #febf04 100%);
        background: -webkit-gradient(linear, left top, right top, color-stop(0%,#ffd65e), color-stop(100%,#febf04));
        background: -webkit-linear-gradient(left, #ffd65e 0%,#febf04 100%);
        background: -o-linear-gradient(left, #ffd65e 0%,#febf04 100%);
        background: -ms-linear-gradient(left, #ffd65e 0%,#febf04 100%);
        background: linear-gradient(to right, #ffd65e 0%,#febf04 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffd65e', endColorstr='#febf04',GradientType=1 );
        color:rgb(166, 140, 0);
        padding: 5px;
        border: 1px black solid;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        text-shadow: 1px 1px #000000;

    }


    #ciclos .col-lg-4{
        padding: 10px;
        font-size: 12pt;
        border-bottom: 1px gainsboro solid;
        min-height: 56px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
    }

</style>


@stop

@section("contenido") 

<h1><span class="glyphicon glyphicon-gift"></span> {{trans("fact.suscripcion.ciclo.titulo")}}</h1>


<h3 style="margin-top:50px;">{{trans("fact.suscripcion.ciclo.info.escoger")}}: <span id="susp-{{trans("fact.suscripcion.plan.".$plan.".titulo")}}"><span class="glyphicon glyphicon-certificate"></span> {{trans("fact.suscripcion.plan.".$plan.".titulo")}}</span></h3>

<div class="col-lg-12" style="margin-top:20px;" id="ciclos">

    @for($i=0;$i< count($ciclos);$i++)

    <div class="col-lg-12">
        <div class="col-lg-4"><span class="glyphicon glyphicon-calendar"></span> {{trans("config.suscripcion.seccion.planes.op.valor.".$ciclos[$i]."mensual")}}</div>
        <div class="col-lg-4">{{Monedas::simbolo($moneda)}}{{Monedas::formatearNumero($moneda,Instancia::obtenerValorMetadato($prefijo.$ciclos[$i]."mes_".$plan))}} {{$moneda}}</div>
        <div class="col-lg-4 text-right"><button class="btn btn-primary"><span class="glyphicon glyphicon-shopping-cart"></span> {{trans("fact.btn.proceder.pago")}}</button></div>
    </div>

    @endfor

</div>

@stop
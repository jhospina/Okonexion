<?php
$susc = array("infobox_div" => 4,
    "infobox_icon" => "glyphicon-time",
    "infobox_label" => trans("pres.ar.suscripcion"),
    "infobox_link_info" => URL::to("fact/suscripcion/plan"),
    "infobox_link_foot" => URL::to("fact/suscripcion/plan"));

if (Auth::user()->estado == User::ESTADO_PERIODO_PRUEBA) {
    $susc["infobox_cant"] = trans("pres.ar.suscripcion.prueba");
    $susc["infobox_color"] = "purple";
    $susc["infobox_descripcion"] = trans("pres.ar.suscripcion.foot") . " " . TIEMPO_SUSCRIPCION;
} elseif (Auth::user()->estado == User::ESTADO_SUSCRIPCION_VIGENTE) {
    $susc["infobox_cant"] = trans("atributos.tipo.suscripcion." . User::obtenerValorMetadato(UsuarioMetadato::SUSCRIPCION_TIPO));
    $susc["infobox_color"] = "#357ebd";
    $susc["infobox_descripcion"] = TIEMPO_SUSCRIPCION;
} elseif (Auth::user()->estado == User::ESTADO_PRUEBA_FINALIZADA || Auth::user()->estado == User::ESTADO_SUSCRIPCION_CADUCADA) {
    $susc["infobox_cant"] = "<span class='glyphicon glyphicon-shopping-cart'></span>";
    $susc["infobox_color"] = "red";
    $susc["infobox_descripcion"] = trans("pres.ar.suscripcion.no.suscrito");
    $susc["infobox_label"] = trans("pres.ar.suscripcion.suscribete");
}

$appExiste = Aplicacion::existe();

$app = ($appExiste) ? Aplicacion::obtener() : null;

$imgLoader = "<img src='" . URL::to("assets/img/loaders/barfb.gif") . "'/>";
?>

@extends('interfaz/plantilla')

@section("titulo") {{trans("interfaz.menu.principal.inicio")}} @stop


@section("css")

<style>
    #container{
        padding:0px;
    }

    #content-noticias,#content-soporte,#content-noticias .panel-heading,#content-soporte .panel-heading{
        -webkit-border-radius: 0px;
        -moz-border-radius: 0px;
        border-radius: 0px;
        margin: 0px;
        clear: both;
        border: 0px;
    }

    #msj-popup.alert.alert-danger{
        margin-top: 10px;
        padding: 10px;
        font-size: 13pt;
    }
</style>


<style>
    .ticket-{{trans("atributos.estado.ticket.abierto")}}{
        background-color: green;

    }

    .ticket-{{trans("atributos.estado.ticket.cerrado")}}{
        background-color: red;
    }
    .ticket-{{trans("atributos.estado.ticket.respondido")}}{
        background-color: darkcyan;

    }
    .ticket-{{trans("atributos.estado.ticket.procesando")}}{
        background-color: orangered;

    }

    .ticket-{{trans("atributos.estado.ticket.enviado")}}{
        background-color: slategray;

    }


    .factura-{{Facturacion::ESTADO_SIN_PAGAR}} span{
        background: red;
    }

    .factura-{{Facturacion::ESTADO_VENCIDA}} span{
        background: orange;
    }

    .factura-{{Facturacion::ESTADO_SIN_PAGAR}} span,.factura-{{Facturacion::ESTADO_VENCIDA}} span{
        padding: 5px;
        color: white;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
    }

    #msj-crear-app{
        background-image: -webkit-linear-gradient(top, #3c3c3c 0%, #222 100%);
        background-image: -o-linear-gradient(top, #3c3c3c 0%, #222 100%);
        background-image: -webkit-gradient(linear, left top, left bottom, from(#3c3c3c), to(#222));
        background-image: linear-gradient(to bottom, #3c3c3c 0%, #222 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff3c3c3c', endColorstr='#ff222222', GradientType=0);
        filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);
        background-repeat: repeat-x;
        margin-top: 10px;
        margin-bottom: 10px;
        padding: 0px;
        padding-top: 10px;
        margin-left: 10px;
        width: 98%;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        padding-bottom:10px;

    }

    #msj-crear-app .jumbotron{
        background: none;
        color: white;
        padding: 0px;
        margin: 0px;
    }

    #msj-crear-app .jumbotron h1{
        padding: 0px;
        margin: 0px;
        padding-bottom: 10px;
    }

</style>


<style>

    .col-lg-2.estadistica,.col-lg-10.cant{
        padding: 5px;
        font-size: 12pt;
        border-bottom:1px slategrey solid;
        height: 30px;
        cursor:pointer;
    }

    .col-lg-2.estadistica{
        background-color: slategrey;
        color: white;
        text-align: center;
    }

    .col-lg-2.estadistica:hover{
        background: rgb(165, 170, 174);
    }

    .col-lg-10.cant{
        font-family: fantasy;
        background-color: white;
        padding: 5px;
        font-size: 16px;
    }

    .col-lg-10.cant img{
        vertical-align: top;
        margin-top: 5px;
        margin-left: 5px;
    }

</style>

@stop

@section("contenido") 

@include("interfaz/mensaje/index",array("id_mensaje"=>2))



<div class="col-lg-12" style="padding: 0px;">
    <div class="col-lg-9" style="padding: 0px;">

        @if(!$appExiste)

        <div class="col-lg-12" id="msj-crear-app">
            <div class="col-lg-6 text-center"><img width="400" class="img-rounded" src="{{URL::to("assets/img/appsimg1.jpg")}}"></div>
            <div class="col-lg-6">
                <div class="jumbotron">
                    <h1>{{trans("pres.info.bienvenido")}}</h1>
                    <p class="text-justify">{{trans("pres.info.bienvenido.descripcion")}}</p>
                    <p class="text-center"><a class="btn btn-success btn-lg" href="{{trans("aplicacion/basico")}}" role="button"><span class="glyphicon glyphicon-phone"></span> {{trans("pres.info.bienvenido.btn")}}</a></p>
                </div>
            </div>
        </div>

        @endif


        @if($appExiste && $app->estado==Aplicacion::ESTADO_TERMINADA)

        <div class="col-lg-12" id="dashboard-tcontenido">
            <div class="col-lg-12 text-center" id="titulo-admin"><span class="glyphicon glyphicon-phone"></span> {{trans("pres.info.administrar.mi.aplicacion")}}</div>
            @include("interfaz/app/dashboard-tiposContenidos",array("app"=>$app))
        </div>

        @endif


        <div class="col-lg-12" style="margin-top: 10px;">

            {{-- INFO RAPIDO: TIEMPO SUSCRIPCION--}}


            @include("interfaz/util/infobox",$susc)


            {{-- INFO RAPIDO: SERVICIOS--}}

            @if($totalServicios>0)

            @include("interfaz/util/infobox",
            array("infobox_div"=>4,
            "infobox_color"=>"#468847",
            "infobox_icon"=>"glyphicon-flash",
            "infobox_cant"=>$serviciosProcesados."(".$totalServicios.")",
            "infobox_label"=>trans("pres.ar.servicios"),
            "infobox_descripcion"=>trans("pres.ar.servicios.foot"),
            "infobox_link_info"=>URL::to("servicios"),
            "infobox_link_foot"=>URL::to("servicios/agregar"),
            ))

            @else

            @include("interfaz/util/infobox",
            array("infobox_div"=>4,
            "infobox_color"=>"#468847",
            "infobox_icon"=>"glyphicon-flash",
            "infobox_cant"=>$serviciosProcesados."(".$totalServicios.")",
            "infobox_label"=>trans("pres.ar.servicios"),
            "infobox_descripcion"=>trans("pres.ar.servicios.foot"),
            "infobox_link_info"=>URL::to("servicios/agregar"),
            "infobox_link_foot"=>URL::to("servicios/agregar"),
            ))

            @endif


            {{-- INFO RAPIDO: TICKETS--}}

            @include("interfaz/util/infobox",
            array("infobox_div"=>4,
            "infobox_color"=>"#eea236",
            "infobox_icon"=>"glyphicon-comment",
            "infobox_cant"=>count($tickets)."(".$totalTickets.")",
            "infobox_label"=>trans("pres.ar.tickets"),
            "infobox_descripcion"=>trans("pres.ar.tickets.foot"),
            "infobox_link_info"=>URL::to("soporte")
            ))


        </div>




        <div class="col-lg-12">
            <h2><span class="glyphicon glyphicon-copy"></span> {{trans("otros.info.facturas")}}</h2>

            <table class="table table-striped table-hover">
                <tr>
                    <th>{{trans("fact.col.numero.factura")}}</th>
                    <th>{{trans("fact.col.fecha.creacion")}}</th>
                    <th>{{trans("fact.col.fecha.vencimiento")}}</th>
                    <th>{{trans("otros.info.estado")}}</th>
                    <th>{{trans("fact.info.total")}}</th>
                </tr>

                @if(count($facturas)>0)

                @foreach($facturas as $factura)
                <?php
                $moneda = Facturacion::obtenerValorMetadato(MetaFacturacion::MONEDA_ID, $factura->id);
                ?>
                <tr style="cursor:pointer;" onclick="irFactura({{$factura->id}});">
                    <td><a target="_blank" href="{{URL::to("fact/factura/".$factura->id)}}">{{$factura->id}}</a></td>
                    <td>{{Fecha::formatear($factura->fecha_creacion)}}</td>
                    <td>{{Fecha::formatear($factura->fecha_vencimiento)}}</td>
                    <td class="factura-{{$factura->estado}}"><span>{{Facturacion::estado($factura->estado)}}</span></td>
                    <td>{{Monedas::simbolo($moneda)}}{{Monedas::formatearNumero($moneda,$factura->total)}} {{$moneda}}</td>
                </tr>
                @endforeach

                @else

                <tr><td colspan="5" class="text-center" style="font-size: 12pt;"><span class="glyphicon glyphicon-thumbs-up"></span> {{trans("pres.facturas.no")}}</td></tr>

                @endif
            </table>

        </div>

    </div>
    <div class="col-lg-3" style="padding: 0px;border-left: 1px black solid;">

        @if($appExiste && $app->estado==Aplicacion::ESTADO_TERMINADA)

        <div class="col-lg-12" style="padding: 0px;margin:0px;" onclick="location.href ='{{URL::to("aplicacion/".$app->id."/estadisticas")}}';">
            <div class="col-lg-2 estadistica tooltip-left" title="{{trans("app.estadisticas.nombre.instalaciones.total")}}">
                <span class="glyphicon glyphicon-phone"></span></div>
            <div class="col-lg-10 cant" id="st-total-instalaciones"></div>
            <div class="col-lg-2 estadistica tooltip-left"  title="{{trans("app.estadisticas.nombre.instalaciones.hoy")}}">
                <span class="glyphicon glyphicon-phone"></span></div>
            <div class="col-lg-10 cant" id="st-instalaciones-hoy"></div>
            <div class="col-lg-2 estadistica tooltip-left" title="{{trans("app.estadisticas.nombre.actividad.hoy")}}">
                <span class="glyphicon glyphicon-magnet"></span></div>
            <div class="col-lg-10 cant" id="st-actividad-hoy"></div>
        </div>

        @endif


        <div class="panel panel-primary" id="content-noticias" style="border-bottom: 1px white solid;">
            <div class="panel-heading"><span class="glyphicon glyphicon-bullhorn"></span> {{trans("interfaz.menu.principal.ayuda.noticias")}}</div>
            <div class="panel-body" style="height:250px;overflow-y: auto;overflow-x: hidden;">

                <div class="text-center" style="  margin-top: 85px;">
                    <h3><span class="glyphicon glyphicon-bullhorn"></span> {{trans("pres.noticias.no")}}</h3>
                </div>

            </div>
        </div>

        <div class="panel panel-primary" id="content-soporte">
            <div class="panel-heading"><span class="glyphicon glyphicon-question-sign"></span> {{trans("interfaz.menu.principal.ayuda.soporte")}} - Tickets</div>
            <div class="panel-body" style="height:256px;overflow-y: auto;overflow-x: hidden;">

                @if(count($tickets)>0)

                @foreach($tickets as $ticket)

                <div class="list-group">
                    <a href="{{Route("soporte.show",$ticket->id)}}" class="list-group-item"><span class="glyphicon glyphicon-comment"></span> {{Util::recortarTexto($ticket->asunto,20)}} <span class="badge ticket-{{$ticket->estado}}">{{$ticket->estado}}</span></a>
                </div>

                @endforeach

                @else

                <div class="text-center" style="margin-top: 50px;">
                    <h3><span class="glyphicon glyphicon-thumbs-up"></span> {{trans("pres.tickets.no")}}</h3>
                    <a class="btn btn-success" href="{{Route("soporte.create")}}"><span class="glyphicon glyphicon-plus"></span> {{trans("menu_ayuda.soporte.tickets.btn.crear")}}</a></a>
                </div>
                @endif

            </div>
        </div>

    </div>
</div>

@stop



@section("script")



<script>
            function irFactura(id){
            window.open("{{URL::to('fact/factura')}}/" + id, '_blank');
            }
</script>

@if($appExiste)

<script>
    $(document).ready(function(){

    $("#st-total-instalaciones").html("{{$imgLoader}}");
            $("#st-instalaciones-hoy").html("{{$imgLoader}}");
            $("#st-actividad-hoy").html("{{$imgLoader}}");
            estadisticasApp();
    });</script>

<script>


            function estadisticasApp(){
            jQuery.ajax({
            type: "POST",
                    url: "{{URL::to('estadisticas/ajax/app/'.$app->id.'/instalaciones/total')}}",
                    data: {},
                    success: function (response) {
                    data = jQuery.parseJSON(response);
                            $("#st-total-instalaciones").html(data);
                            jQuery.ajax({
                            type: "POST",
                                    url: "{{URL::to('estadisticas/ajax/app/'.$app->id.'/instalaciones/hoy')}}",
                                    data: {},
                                    success: function (response) {
                                    data = jQuery.parseJSON(response);
                                            $("#st-instalaciones-hoy").html(data);
                                            jQuery.ajax({
                                            type: "POST",
                                                    url: "{{URL::to('estadisticas/ajax/app/'.$app->id.'/actividad/hoy')}}",
                                                    data: {},
                                                    success: function (response) {
                                                    data = jQuery.parseJSON(response);
                                                            $("#st-actividad-hoy").html(data);
                                                            setTimeout("estadisticasApp()", 60000);
                                                    }}, "html");
                                    }}, "html");
                    }}, "html");
            }

</script>

@endif

@stop
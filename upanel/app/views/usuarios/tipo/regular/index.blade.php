@extends('interfaz/plantilla')

@section("titulo") UPanel @stop


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

</style>


<style>

    #stat-servicios,#stat-tiempo,#stat-tickets{
        color:white;
        width:31%;
        margin:0 1%;
    }

    #stat-servicios .gly,#stat-tiempo .gly,#stat-tickets .gly{
        font-size: 40pt;
        margin-top: 10px;
    }

    #stat-servicios,#stat-tiempo,#stat-tickets{
        padding: 0px;
    }

    #stat-servicios .num-cant,#stat-tiempo .num-cant,#stat-tickets .num-cant{
        display: block;
        font-size: 25pt;
    }

    #stat-servicios label,#stat-tiempo label,#stat-tickets label{
        font-size: 17pt;
        font-style: italic;
        font-weight: 100;
    }

    #stat-servicios .foot ,#stat-tiempo .foot ,#stat-tickets .foot {
        padding:0px;
        opacity: 0.7;
        font-size: 11pt;
        padding: 2px;
        font-family: calibri;
        height: 27px;
    }

    #stat-servicios{
        border:1px #468847 solid;
    }

    #stat-servicios .info{
        background-color: #468847;
        padding:0px;
    }

    #stat-servicios .foot{
        background-color: #468847;
        border:1px #468847 solid;
    }

    #stat-tickets{
        border:1px #eea236 solid;
    }

    #stat-tickets .info{
        background-color: #eea236;
        padding:0px;
    }

    #stat-tickets .foot{
        background-color: #eea236;
        border:1px #eea236 solid;
    }

    #stat-tiempo{
        border:1px #357ebd solid;
    }

    #stat-tiempo .info{
        background-color: #357ebd;
        padding:0px;
    }

    #stat-tiempo .foot{
        background-color: #357ebd;
        border:1px #357ebd solid;
    }

</style>



@stop

@section("contenido") 

@include("interfaz/mensaje/index",array("id_mensaje"=>2))



<div class="col-lg-12" style="padding: 0px;">
    <div class="col-lg-9" style="padding: 0px;">




        <div class="col-lg-12" style="margin-top: 10px;">

            {{-- INFO RAPIDO: TIEMPO SUSCRIPCION--}}
            <div class="col-lg-4" id="stat-tiempo">

                <div class="col-lg-12 info">
                    <div class="col-lg-4"><span class="gly glyphicon glyphicon-time"></span></div>
                    <div class="col-lg-8 text-right">
                        <span class="num-cant">{{trans("atributos.tipo.suscripcion.".User::obtenerValorMetadato(UsuarioMetadato::SUSCRIPCION_TIPO))}}</span>
                        <label>{{trans("pres.ar.suscripcion")}}</label>
                    </div>
                </div>
                <div class="col-lg-12 foot">
                    <div class="col-xs-10">
                        {{TIEMPO_SUSCRIPCION}}</div>
                    <div class="col-xs-2 text-right">
                        <span class="glyphicon glyphicon-circle-arrow-right"></span>
                    </div>
                </div>

            </div>


            {{-- INFO RAPIDO: SERVICIOS--}}
            <div class="col-lg-4" id="stat-servicios">
                <div class="col-lg-12 info">
                    <div class="col-lg-4"><span class="gly glyphicon glyphicon-flash"></span></div>
                    <div class="col-lg-8 text-right">
                        <span class="num-cant">(0)</span>
                        <label>{{trans("pres.ar.servicios")}}</label>
                    </div>
                </div>
                <div class="col-lg-12 foot">
                    <div class="col-xs-10">
                        {{trans("pres.ar.servicios.foot")}}				</div>
                    <div class="col-xs-2 text-right">
                        <span class="glyphicon glyphicon-circle-arrow-right"></span>
                    </div>
                </div>
            </div>

            {{-- INFO RAPIDO: TICKETS--}}
            <div class="col-lg-4" id="stat-tickets">
                <div class="col-lg-12 info">
                    <div class="col-lg-4"><span class="gly glyphicon glyphicon-comment"></span></div>
                    <div class="col-lg-8 text-right"> 
                        <span class="num-cant">{{count($tickets)}}({{$totalTickets}})</span>
                        <label>{{trans("pres.ar.tickets")}}</label>
                    </div>
                </div>
                <div class="col-lg-12 foot">
                    <div class="col-xs-10">
                        {{trans("pres.ar.tickets.foot")}}				</div>
                    <div class="col-xs-2 text-right">
                        <span class="glyphicon glyphicon-circle-arrow-right"></span>
                    </div>
                </div>
            </div>


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
                    <td>{{Monedas::simbolo($moneda)}}{{$factura->total}} {{$moneda}}</td>
                </tr>
                @endforeach

                @else

                <tr><td colspan="5" class="text-center" style="font-size: 12pt;"><span class="glyphicon glyphicon-thumbs-up"></span> {{trans("pres.facturas.no")}}</td></tr>

                @endif
            </table>

        </div>

    </div>
    <div class="col-lg-3" style="padding: 0px;">

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
            <div class="panel-body" style="height:250px;overflow-y: auto;overflow-x: hidden;">

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




























{{--******************************************************--}}
{{--******************************************************--}}
{{--******************************************************--}}
{{--******************************************************--}}
{{--******************************************************--}}


@if(!IDCookies::existe(IDCookies::MSJ_INICIAL_PERIODO_PRUEBA))
@include(Util::RUTA_MENSAJE_MODAL,array("titulo"=>trans("msj.".IDCookies::MSJ_INICIAL_PERIODO_PRUEBA.".titulo",array("num"=>Instancia::obtenerValorMetadato(ConfigInstancia::periodoPrueba_numero_dias))),"mensaje"=>trans("msj.".IDCookies::MSJ_INICIAL_PERIODO_PRUEBA.".descripcion",array("tiempo"=>Util::calcularDiferenciaFechas(Util::obtenerTiempoActual(),Auth::user()->fin_suscripcion)))))

<script>
            $("#btn-entendido").click(function () {
    jQuery.ajax({
    type: "POST",
            url: "{{URL::to('cookies/set')}}",
            data: {IDCookie: "{{IDCookies::MSJ_INICIAL_PERIODO_PRUEBA}}", valor: false}, success: function (response) {

    }}, "html");
    });</script> 
@endif


@stop



@section("script")

<script>

            function irFactura(id){
            window.open("{{URL::to('fact/factura')}}/" + id, '_blank');
            }

</script>

@stop
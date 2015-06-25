<?php
$servicios = array();
$metaFacturas = array();
?>
@extends('interfaz/plantilla')

@section("titulo") {{trans("interfaz.menu.principal.servicios.agregar.servicio")}} @stop


@section("css")
<style>
    #content-mis-servicios{
        padding: 0px;
        margin-top: 20px;
        margin-bottom: 20px;
    }


    #content-mis-servicios #listado{
        padding:0px;
    }

    #content-mis-servicios #info-servicio{
        -webkit-border-radius: 5px;
        -webkit-border-top-left-radius: 0;
        -moz-border-radius: 5px;
        -moz-border-radius-topleft: 0;
        border-radius: 5px;
        border-top-left-radius: 0;
        height: 600px;
        overflow-y: auto;
        overflow-x: hidden;
        background-color: rgb(236, 236, 236);
    }

    .badge.procesado{
        background: green;
    }

    .badge.sin-procesar{
        background: red;
    }

    .list-group-item.observ{
        display:none;
        background-color: rgb(219, 219, 219);
        color: black;
        border-left: 1px rgb(207, 207, 207) solid;
        border-right: 1px rgb(207, 207, 207) solid;
    }

    .list-group-item.observ .content-observ{
        background: white;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        padding: 10px;
        color:black;
    }

    .list-group-item.item:hover{
        cursor:pointer;
        background: beige;
    }


</style>
@stop

@section("contenido") 

<h1><span class="glyphicon glyphicon-flash"></span> {{trans("interfaz.menu.principal.servicios.mis.servicios")}}</h1>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))

@if(count($consulta)>0)

<div class="col-lg-12" id="content-mis-servicios">
    <div class="col-lg-5" id="listado">
        <div class="list-group">
            @foreach($consulta as $meta)
            <?php
            $servicios[] = $servicio = Servicio::find(intval(str_replace(Servicio::CONFIG_NOMBRE, "", $meta->valor)));
            $total_serv = count($metaFacturas[] = MetaFacturacion::join('facturacion', 'facturacionMetadatos.id_factura', '=', 'facturacion.id')->where("facturacion.estado", Facturacion::ESTADO_PAGADO)->where("facturacionMetadatos.id_usuario", Auth::user()->id)->where("facturacionMetadatos.valor", Servicio::CONFIG_NOMBRE . $servicio->id)->orderBy('facturacionMetadatos.id', "DESC")->get());
            ?>
            <a class="list-group-item list-group-item-text" onclick="servicio({{count($servicios)-1}})"><span class="glyphicon glyphicon-ok"></span> {{$servicio->getNombre()}} <span class="badge">{{$total_serv}}</span></a>
            @endforeach
        </div>
    </div>
    <div class="col-lg-7" id="info-servicio">

        @foreach($metaFacturas as $index => $metas)
        <div style="display: none;" class="info-servicio" id="info-servicio-{{$index}}">

            <?php $servicio = $servicios[$index]; ?>

            <h2><span class="glyphicon glyphicon-flash"></span> {{$servicio->getNombre()}}</h2>

            <div class="well well-lg" style="background: #777;color:white;">
                {{$servicio->getDescripcion()}}
            </div>

            <h3>{{trans("otros.info.pagos")}}</h3>
            <div class="list-group">
                @foreach($metas as $meta)
                <?php
                $factura = Facturacion::find($meta->id_factura);
                $procesado = Util::convertirIntToBoolean(Facturacion::obtenerValorMetadato(MetaFacturacion::PRODUCTO_PROCESADO . $servicio->id, $factura->id));
                ?>
                @if($factura->estado==Facturacion::ESTADO_PAGADO)
                <div onClick='mostrarObserv({{$factura->id}},{{$servicio->id}});' class="list-group-item {{($procesado)?"item":""}}"><a href='{{URL::to("fact/factura/".$meta->id_factura)}}' target="_blank">{{trans("fact.col.numero.factura")}} {{$meta->id_factura}}</a> 
                    <span class="badge">{{Fecha::formatear(Facturacion::obtenerValorMetadato(MetaFacturacion::FECHA_PAGO, $factura->id))}}</span>
                    <span  class='badge {{($procesado)?"procesado":"sin-procesar"}}'>
                        {{trans("otros.info.servicio")}} <span style='text-transform: lowercase;'>{{($procesado)?trans("otros.info.procesado"):trans("otros.info.sin_procesar")}}</span>
                    </span>
                </div>

                @if($procesado)
                <?php $observacion = Facturacion::obtenerValorMetadato(MetaFacturacion::PRODUCTO_OBSERVACIONES . $servicio->id, $factura->id); ?>
                <div class='list-group-item observ' id='observ-{{$factura->id}}{{$servicio->id}}'>
                    <h4>{{trans("otros.info.observaciones")}}:</h4>
                    <div class='content-observ'>
                        {{HtmlControl::crearEnlacesDesdeTexto($observacion)}}
                    </div>

                </div>
                @endif

                @endif
                @endforeach
            </div>
        </div>

        @endforeach
        <div class="jumbotron" id="msj-seleccionar">
            <h1><span class="glyphicon glyphicon-exclamation-sign"></span> {{trans("serv.mis.selecciona.titulo")}}</h1>
            <p>{{trans("serv.mis.selecciona.descripcion")}}</p>
        </div>
    </div>
</div>

@else

<div class="col-lg-12 text-center" style="height: 400px;background-color: rgb(247, 247, 247);padding-top: 150px;border: 1px gainsboro solid;">
    <h2><span class="glyphicon glyphicon-exclamation-sign"></span> {{trans("otros.info.no_hay_datos")}}</h2>
    <a class="btn btn-success" href="{{URL::to("servicios/agregar")}}"><span class="glyphicon glyphicon-plus"></span> {{trans("pres.ar.servicios.foot")}}</a>
</div>

@endif

@stop

@section("script")

<script>

            function servicio(id){
            $("#msj-seleccionar").hide(0);
                    $(".info-servicio").hide(0, function(){
            $("#info-servicio-" + id).show(0);
            });
            }


    function mostrarObserv(id_factura, id_servicio){
    $("#observ-" + id_factura + id_servicio).slideToggle();
    }

</script>

@stop
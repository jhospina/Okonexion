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
</style>
@stop

@section("contenido") 

<h1><span class="glyphicon glyphicon-flash"></span> {{trans("interfaz.menu.principal.servicios.mis.servicios")}}</h1>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))

<div class="col-lg-12" id="content-mis-servicios">
    <div class="col-lg-5" id="listado">
        <div class="list-group">
            @foreach($consulta as $meta)
            <?php $servicios[] = $servicio = Servicio::find(intval(str_replace(Servicio::CONFIG_NOMBRE, "", $meta->valor))); ?>
            <a class="list-group-item list-group-item-text" onclick="servicio({{count($servicios)-1}})"><span class="glyphicon glyphicon-ok"></span> {{$servicio->getNombre()}} <span class="badge">{{count($metaFacturas[]=MetaFacturacion::where("id_usuario", Auth::user()->id)->where("valor", Servicio::CONFIG_NOMBRE . $servicio->id)->orderBy('id',"DESC")->get())}}</span></a>
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
                <?php $factura = Facturacion::find($meta->id_factura); ?>
                @if($factura->estado==Facturacion::ESTADO_PAGADO)
                <a class="list-group-item" target="_blank" href="{{URL::to("fact/factura/".$meta->id_factura)}}">{{trans("fact.col.numero.factura")}} {{$meta->id_factura}} <span class="badge">{{Fecha::formatear(Facturacion::obtenerValorMetadato(MetaFacturacion::FECHA_PAGO, $factura->id))}}</span></a>    
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

@stop

@section("script")

<script>

            function servicio(id){
            $("#msj-seleccionar").hide(0);
                    $(".info-servicio").hide(0, function(){
            $("#info-servicio-" + id).show(0);
            });
            }

</script>

@stop
<?php

$moneda=Auth::user()->getMoneda();

//Divide los servicios en un fragmentos de array de 3;
$cant = 3;
$divServicios = array();
$conj = array();
$n = 0;
foreach ($servicios as $servicio) {
    $n++;
    if (count($conj) < $cant) {
        $conj[] = $servicio;
        if (count($servicios) == $n)
            $divServicios[] = $conj;
    } else {
        $divServicios[] = $conj;
        $conj = array();
    }
}
?>
@extends('interfaz/plantilla')

@section("titulo") {{trans("interfaz.menu.principal.servicios.agregar.servicio")}} @stop


@section("contenido") 

<h1><span class="glyphicon glyphicon-plus-sign"></span> {{trans("interfaz.menu.principal.servicios.agregar.servicio")}}</h1>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))


<div class="well well-sm" style="margin-bottom: 20px;">
    {{trans("serv.agregar.descripcion")}}
</div>


<h3>1. {{trans("serv.agregar.seleccionar")}}</h3>

@foreach($divServicios as $servicios)

<div class="col-lg-12 servicios" >
    @foreach($servicios as $servicio)
    <?php $tam = 4; ?>

    <div class="col-lg-{{$tam}} servicio" id="servicio-{{$servicio->id}}">
        <div class="titulo">
            <span class="glyphicon glyphicon-flash"></span> {{$servicio->getNombre()}}
        </div>
        <div class="descripcion">
            {{$servicio->getDescripcion()}}
        </div>
        <div class="imagen">
            <img src="{{$servicio->getImagen()}}">
        </div>
        <div class="costo">
            {{Monedas::nomenclatura($moneda,$servicio->getCosto($moneda))}}
        </div>
        <div class="content-seleccion">
            <button class="btn btn-info seleccionar" onclick="seleccionar(this,{{$servicio->id}})"><span class="glyphicon glyphicon-ok"></span> {{trans("otros.info.seleccionar")}}</button>
        </div>
    </div>

    @endforeach

</div>

<div style="clear: both;"></div>
<hr/>
<hr/>

<h3>2. {{trans("serv.agregar.ordenar.titulo")}}</h3>

<div class="col-lg-12 text-center" style="margin-bottom: 20px;padding-top: 20px;">
    <form id="form" action="{{URL::to('servicios/post/agregar')}}" method="POST">
        <input type="hidden" name="{{UsuarioMetadato::HASH_CREAR_FACTURA}}" value="{{$hash}}"/>
        <button style="font-size: 18pt;width:50%;" class="btn btn-primary" id="btn-ordenar"><span class="glyphicon glyphicon-shopping-cart"></span> {{trans("otros.info.realizar.orden")}}</button>
    </form>
</div>

@endforeach

@stop


@section("script")


<script>

            function seleccionar(btn, id){

            $(btn).removeClass("btn-info");
                    $(btn).addClass("btn-danger");
                    $(btn).html("<span class='glyphicon glyphicon-remove'></span> {{trans('otros.info.quitar')}}");
                    $("#servicio-" + id).append("<div class='over-seleccion' id='over-select-" + id + "'></div>");
                    $("#form").prepend("<input type='hidden' id='{{Servicio::CONFIG_NOMBRE}}" + id + "' name='{{Servicio::CONFIG_NOMBRE}}" + id + "' value='{{Servicio::CONFIG_NOMBRE}}" + id + "'/>")
                    //Quitar
                    $("#over-select-" + id).bind("click", function(){
            $("#{{Servicio::CONFIG_NOMBRE}}" + id + "").remove();
                    $(this).remove();
                    $(btn).removeClass("btn-danger");
                    $(btn).addClass("btn-info");
                    $(btn).html("<span class='glyphicon glyphicon-ok'></span> {{trans('otros.info.seleccionar')}}");
            });
            }





</script>

@stop
<?php
$moneda = Instancia::obtenerValorMetadato(ConfigInstancia::info_moneda);
?>

@extends('interfaz/plantilla')

@section("titulo") {{trans("fact.ordenPago.titulo")}} #{{$factura->id}} @stop


@section("css")

<style>

</style>


@stop

@section("contenido") 

<h1><span class="glyphicon glyphicon-list-alt"></span> {{trans("fact.ordenPago.titulo")}} #{{$factura->id}}</h1>



@stop
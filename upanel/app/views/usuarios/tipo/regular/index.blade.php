@extends('interfaz/plantilla')

@section("titulo") UPanel @stop

@section("contenido") 


@include(Util::RUTA_MENSAJE_MODAL,array("titulo"=>"titulo","mensaje"=>"Mensaje"))

@stop
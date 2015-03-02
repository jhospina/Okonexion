<?php
if (Session::get('tipo_mensaje')) {
    $tipo_mensaje = Session::get('tipo_mensaje');
    $param_mensaje = Session::get('param_mensaje');
    $mensaje = Session::get('mensaje');
    $pos_mensaje = Session::get('pos_mensaje');
}
?>

{{--VERIFICA SI HAY UN MENSAJE EMERGENTE PARA MOSTRARLE AL USUARIO--}}
@if(isset($tipo_mensaje))
@if($pos_mensaje==$id_mensaje)
@include("interfaz/mensaje/".$tipo_mensaje)
@endif
@endif
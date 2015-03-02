<?php
$tipoContenido = Contenido_Noticias::nombre;
$nombreContenido = TipoContenido::obtenerNombre($app->diseno,$tipoContenido);
$singNombre = Util::eliminarPluralidad(strtolower($nombreContenido));
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | Administrar {{$nombreContenido}} @stop


@section("css")
{{ HTML::style('assets/plugins/font-awesome/css/font-awesome.css', array('rel' => 'stylesheet')) }}
{{ HTML::style('assets/plugins/wysiwyg/editor.css', array('media' => 'screen')) }}
@stop


@section("contenido") 



<h1> Agregar {{$singNombre}}</h1> 
<hr/>
<form method="POST">
    <div class="col-lg-8">
        <div class="col-lg-12"><input type="text"  placeholder="Introduce el titulo aquí" class="form-control input-lg"></div>
        <div class="col-lg-12">
            <textarea id="editor" name="contenido"></textarea>
        </div>
    </div>
    <div class="col-lg-4">

    </div>
</form>

@stop


@section("script")

{{ HTML::script('assets/plugins/wysiwyg/editor.js') }}
<script>
    jQuery(document).ready(function () {
        jQuery('#editor').Editor({"bold": true});
    });

</script>

@stop

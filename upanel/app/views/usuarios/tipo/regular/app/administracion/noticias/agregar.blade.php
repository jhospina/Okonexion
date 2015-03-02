<?php
$tipoContenido = Contenido_Noticias::nombre;
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, $tipoContenido);
$singNombre = Util::eliminarPluralidad(strtolower($nombreContenido));
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | Administrar {{$nombreContenido}} @stop


@section("css")
{{ HTML::style('assets/plugins/font-awesome/css/font-awesome.css', array('rel' => 'stylesheet')) }}
{{ HTML::style('assets/plugins/wysiwyg/editor.css', array('media' => 'screen')) }}
@stop


@section("contenido") 



<h2> Agregar {{$singNombre}}</h2> 
<hr/>
<form method="POST">
    <div class="col-lg-8">
        <div class="col-lg-12"><input type="text"  placeholder="Introduce el titulo aquí" class="form-control input-lg"></div>
        <div class="col-lg-12">
            <textarea id="editor" name="contenido"></textarea>
        </div>
    </div>
    <div class="col-lg-4">
        {{--CATEGORIAS DE LAS NOTICIAS--}}
        <div class="panel panel-default">
            <div class="panel-heading">Categorias <span class="label label-success" id="msj-agregar-cat" style="display: none;"></span></div>
            <div class="panel-body" id="content-cats">
                @foreach($cats as $cat)
                <div class="checkbox" style="margin: 0px;">
                    <label><input type="checkbox" name="cat-{{$cat->id}}" /> {{$cat->nombre}}</label>
                </div>
                @endforeach
            </div>
            {{--SECCION PARA AGREGAR CATEGORIA--}}
            <div class="panel-footer" id="content-agregar-categoria">
                <input style="display: none;" type="text" id="input-agregar-cat" class="form-control" placeholder="Escribe aquí la nueva categoria...">
                <button type="button" class="btn-sm btn-primary" id="btn-agregar-cat"><span class="glyphicon glyphicon-plus-sign"></span> Agregar nueva categoria</button>
            </div>
        </div>
    </div>
</form>

@stop


@section("script")

{{ HTML::script('assets/plugins/wysiwyg/editor.js') }}
<script>
    jQuery(document).ready(function () {
        jQuery('#editor').Editor({"bold": true});
    });</script>


<script>
    jQuery("#btn-agregar-cat").click(function () {

        if (jQuery("#input-agregar-cat").css("display") == "none")
        {
            jQuery("#input-agregar-cat").fadeIn();
            jQuery("#input-agregar-cat").focus();
        } else {
            agregarNuevaCategoria();
        }
    });
    jQuery("#input-agregar-cat").keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            agregarNuevaCategoria();
        }
    });</script>

<script>

    function agregarNuevaCategoria() {
        var idTax =<?php print($tax->id); ?>;
        var nuevaCat = jQuery("#input-agregar-cat").val();

        jQuery.ajax({
            type: "POST",
            url: "{{URL::to('aplicacion/administrar/noticias/ajax/agregar/categoria')}}",
            data: {id_tax: idTax, cat: nuevaCat},
            success: function (response) {

                jQuery("#content-cats").append('<div class="checkbox" style="margin: 0px;"><label><input type="checkbox" name="cat-' + response + '" /> ' + nuevaCat + '</label></div>');
                jQuery("#input-agregar-cat").fadeOut();
                jQuery("#msj-agregar-cat").html("Categoria agregada");
                jQuery("#msj-agregar-cat").fadeIn();
                setTimeout(function () {
                    jQuery("#msj-agregar-cat").fadeOut();
                }, 5000);
            }}, "html");

    }

</script>

@stop

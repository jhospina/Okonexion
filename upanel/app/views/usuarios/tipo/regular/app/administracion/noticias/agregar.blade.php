<?php
$tipoContenido = Contenido_Noticias::nombre;
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, $tipoContenido);
$singNombre = Util::eliminarPluralidad(strtolower($nombreContenido));
$tituloID = Contenido_Noticias::configTitulo;
$descripcionID = Contenido_Noticias::configDescripcion;
$imagenID = Contenido_Noticias::configImagen;
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | Administrar {{$nombreContenido}} @stop


@section("css")
{{ HTML::style('assets/plugins/fileinput/css/fileinput.css', array('media' => 'screen')) }}
{{ HTML::style('assets/plugins/font-awesome/css/font-awesome.css', array('rel' => 'stylesheet')) }}
{{ HTML::style('assets/plugins/wysiwyg/editor.css', array('media' => 'screen')) }}
@stop


@section("contenido") 



{{--NAVEGACION--}}
<div class="well well-sm">
    <a href="{{URL::to("aplicacion/administrar/noticias")}}" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Volver</a>
</div>
<hr/>
<h2> Agregar {{$singNombre}}</h2> 
<hr/>
@include("interfaz/mensaje/index",array("id_mensaje"=>2))
<div class="alert alert-danger" id="error-js"></div>
<div class="col-lg-12" style="margin-bottom: 30px;margin-top: 10px;">
    <form method="POST" id="form">
        <div class="col-lg-12" style="margin-bottom: 20px;">
            <div class="col-lg-12"><input name="{{$tituloID}}" id="{{$tituloID}}" type="text"  placeholder="Introduce el titulo aquí" class="form-control input-lg"></div>
            <div class="col-lg-12">
                <div id="editor"></div>
                <textarea style="display: none;" id="{{$descripcionID}}" name="{{$descripcionID}}"></textarea>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="col-lg-5">
                <div class="panel panel-default">
                    <div class="panel-heading">Imagen principal</div>
                    <div class="panel-body">
                        <div class="col-lg-12" style="text-align: center;">
                            <a class="tooltip-left" title="Extensiones permitidas: png, jpg, jpeg. Max: 1,5 Mb. ">
                                <input name="{{$imagenID}}" id="{{$imagenID}}" accept="image/*" type="file" multiple=false>
                            </a>
                        </div>
                        <input type="hidden" name="{{$imagenID}}_id" id="{{$imagenID}}_id"/>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                {{--CATEGORIAS DE LAS NOTICIAS--}}
                <div class="panel panel-default">
                    <div class="panel-heading">Categorias <span class="label label-success" id="msj-agregar-cat" style="display: none;"></span></div>
                    <div class="panel-body" id="content-cats">
                        @include("interfaz/app/listar_terms",array("terms"=>$cats))
                    </div>
                    {{--SECCION PARA AGREGAR CATEGORIA--}}
                    <div class="panel-footer" id="content-agregar-categoria">
                        <input style="display: none;" type="text" id="input-agregar-cat" class="form-control" placeholder="Escribe aquí la nueva categoria...">
                        <button type="button" class="btn-sm btn-primary" id="btn-agregar-cat"><span class="glyphicon glyphicon-plus-sign"></span> Agregar nueva categoria</button>
                    </div>
                </div>  
            </div>
            {{--PUBLICAR--}}
            <div class="col-lg-3">
                <div class="panel panel-default">
                    <div class="panel-heading">Acciones</div>
                    <div class="panel-body">
                        <div class="col-lg-12" style="text-align: center;">
                            <button id="btn-publicar" type="button" onClick="publicar(this);" class="btn btn-success col-lg-12 text-center" style="margin-bottom: 5px;"><span class="glyphicon glyphicon glyphicon-ok-circle"></span> PUBLICAR </button>
                            <button id="btn-guardar" type="button" onClick="guardar(this);" class="btn btn-default col-lg-12"><span class="glyphicon glyphicon glyphicon glyphicon-save"></span> Guardar</button>     
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@stop


@section("script")
{{ HTML::script('assets/js/bootstrap-filestyle.min.js') }}
{{ HTML::script('assets/js/bootstrap-tooltip.js') }}
{{ HTML::script('assets/plugins/wysiwyg/editor.js') }}
{{ HTML::script('assets/plugins/fileinput/js/fileinput.js') }}

<script>
    jQuery(document).ready(function () {

        jQuery(".tooltip-left").tooltip({placement: "left"});
        jQuery(".tooltip-top").tooltip({placement: "top"});
        jQuery('#editor').Editor({"bold": true});
        jQuery("#{{$imagenID}}").fileinput({
            multiple: false,
            showPreview: true,
            showRemove: true,
            showUpload: false,
            showCaption: false,
            initialPreview: false,
            maxFileCount: 1,
            previewFileType: "image",
            allowedFileExtensions: ['jpg', 'png'],
            browseLabel: "Seleccionar imagen",
            browseIcon: '<i class="glyphicon glyphicon-picture"></i> ',
            removeClass: "btn btn-danger",
            removeLabel: "Borrar",
            removeIcon: '<i class="glyphicon glyphicon-trash"></i> ',
            uploadClass: "btn btn-info",
            uploadLabel: "Subir",
            dropZoneEnabled: false,
            dropZoneTitle: "Arrastra imagen...",
            uploadIcon: '<i class="glyphicon glyphicon-upload"></i> ',
            msgSelected: '{n} imagen',
            maxFileSize: 1500,
            msgInvalidFileExtension: "El archivo que has escogido no es valido. Solo se permiten imagenes en formatos {extensions}.",
            msgInvalidFileType: "El archivo que has escogido no es valido. Solo se permiten imagenes en formatos {extensions}.",
            msgSizeTooLarge: "El tamaño de la imagen es demasiado grande. Maximo <b>{maxSize} KB</b>. Esta imagen pesa <b>{size} KB.</b>",
            uploadAsync: true,
            uploadUrl: "{{URL::to('aplicacion/administrar/noticias/ajax/subir/imagen')}}" // your upload server url
        });
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
    });
    $("#{{$imagenID}}").on('fileuploaded', function (event, data, previewId, index) {
        var data = data.response;
        $("#{{$imagenID}}_id").val(data["{{$imagenID}}_id"]);
    });

    //Sube la imagen una vez seleccionada
    $('#{{$imagenID}}').on('fileimageloaded', function (event, previewId) {
        $("#{{$imagenID}}").fileinput('upload');
    });


    //Eliminar imagen
    $('#{{$imagenID}}').on('fileclear', function (event) {

        $.ajax({
            type: "POST",
            url: "{{URL::to('aplicacion/administrar/noticias/ajax/eliminar/imagen')}}",
            data: {id_imagen: $("#{{$imagenID}}_id").val()},
            success: function (response) {
                $("#{{$imagenID}}_id").val("");
            }

        }, "json");
    });


</script>

<script>

    function agregarNuevaCategoria() {
        var idTax =<?php print($tax->id); ?>;
        var nuevaCat = jQuery("#input-agregar-cat").val();
        jQuery.ajax({
            type: "POST",
            url: "{{URL::to('aplicacion/administrar/noticias/ajax/agregar/categoria')}}",
            data: {id_tax: idTax, cat: nuevaCat},
            success: function (response) {

                jQuery("#content-cats").append('<div class="checkbox" style="margin: 0px;"><label><input type="checkbox" name="cat-' + response + '"  value="' + response + '"/> ' + nuevaCat + '</label></div>');
                jQuery("#input-agregar-cat").fadeOut();
                jQuery("#msj-agregar-cat").html("Categoria agregada");
                jQuery("#msj-agregar-cat").fadeIn();
                setTimeout(function () {
                    jQuery("#msj-agregar-cat").fadeOut();
                }, 5000);
            }}, "html");
    }

    function publicar(btn) {

        $("#{{$descripcionID}}").html($("#editor").Editor("getText"));

        if (!validar())
            return;
        $("#form").attr("action", "publicar");
        jQuery(btn).attr("disabled", "disabled");
        jQuery("#btn-guardar").attr("disabled", "disabled");
        jQuery(btn).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> Publicando...");
        setTimeout(function () {
            $("#form").submit();
        }, 2000);
    }

    function guardar(btn) {

        $("#{{$descripcionID}}").html($("#editor").Editor("getText"));

        if (!validar())
            return;
        $("#form").attr("action", "guardar");
        jQuery(btn).attr("disabled", "disabled");
        jQuery("#btn-publicar").attr("disabled", "disabled");
        jQuery(btn).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> Guardando...");
        setTimeout(function () {
            $("#form").submit();
            $("#{{$descripcionID}}").html($("#editor").Editor("getText"));
        }, 2000);
    }


    function validar() {
        var titulo = $("#{{$tituloID}}").val();
        var descripcion = $("#{{$descripcionID}}").val();
        var errores = "";
        if (titulo.length < 1) {
            errores += "<li>Debes escribir un titulo.</li>";
        }

        if (descripcion.length < 1) {
            errores += "<li>Debes escribir una descripción.</li>";
        }

        if (errores.length > 0) {
            $("#error-js").html("Debe corregir los siguientes errores: </br><lu>" + errores + "</lu>");
            $("#error-js").toggle();
            return false;
        } else {
            return true;
        }
    }

</script>

@stop

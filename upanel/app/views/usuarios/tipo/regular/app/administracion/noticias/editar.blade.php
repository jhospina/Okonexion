<?php
$tipoContenido = Contenido_Noticias::nombre;
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, $tipoContenido);
$singNombre = Util::eliminarPluralidad(strtolower($nombreContenido));
$tituloID = Contenido_Noticias::configTitulo;
$descripcionID = Contenido_Noticias::configDescripcion;
$imagenID = Contenido_Noticias::configImagen;

$categorias = $noticia->terminos;

//Obtiene la imagen principa de la noticia
$metaImagen = ContenidoApp::obtenerMetadato($noticia->id, Contenido_Noticias::configImagen . "_principal");

if (!is_null($metaImagen)) {
    $imagen = ContenidoApp::find($metaImagen->valor);
} else {
    $imagen = null;
}
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | {{trans("otros.info.administrar")}} {{$nombreContenido}} @stop


@section("css")
{{ HTML::style('assets/plugins/fileinput/css/fileinput.css', array('media' => 'screen')) }}
{{ HTML::style('assets/plugins/font-awesome/css/font-awesome.css', array('rel' => 'stylesheet')) }}
{{ HTML::style('assets/plugins/wysiwyg/editor.css', array('media' => 'screen')) }}
@stop


@section("contenido") 

{{--NAVEGACION--}}
<div class="well well-sm">
    <a href="{{URL::to("aplicacion/administrar/noticias")}}" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> {{trans("otros.info.volver")}}</a>
    <a href="{{URL::to("aplicacion/administrar/noticias/agregar")}}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> {{trans("app.admin.btn.info.agregar_nuevo")}}</a>
</div>
<hr/>
<h2> {{trans("otros.info.editar")}} {{$singNombre}} [{{$noticia->estado}}]</h2> 
<hr/>
@include("interfaz/mensaje/index",array("id_mensaje"=>2))
<div class="alert alert-danger" id="error-js"></div>
<div class="col-lg-12" style="margin-bottom: 30px;margin-top: 10px;">
    <form method="POST" id="form">

        <input type="hidden" name="id_noticia" value="{{$noticia->id}}">

        <div class="col-lg-12" style="margin-bottom: 20px;">
            <div class="col-lg-12"><input name="{{$tituloID}}" id="{{$tituloID}}" type="text"  placeholder="{{trans('app.admin.noticias.info.titulo.placeholder')}}" class="form-control input-lg" value="{{$noticia->titulo}}"></div>
            <div class="col-lg-12">
                <div id="editor"></div>
                <textarea style="display: none;" id="{{$descripcionID}}" name="{{$descripcionID}}"></textarea>    
            </div>
        </div>
        <div class="col-lg-12">
            <div class="col-lg-5">
                <div class="panel panel-default">
                    <div class="panel-heading">{{trans("app.admin.noticias.info.imagen_principal")}}</div>
                    <div class="panel-body">
                        <div class="col-lg-12" style="text-align: center;">
                            <a class="tooltip-left" title="{{trans('otros.extensiones_permitidas')}}: png, jpg, jpeg. Max: 1,5 Mb. ">
                                <input name="{{$imagenID}}" id="{{$imagenID}}" accept="image/*" type="file" multiple=false>
                            </a>
                        </div>
                        <input type="hidden" name="{{$imagenID}}_id" id="{{$imagenID}}_id" value="@if(!is_null($imagen)){{$imagen->id}}@endif"/>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                {{--CATEGORIAS DE LAS NOTICIAS--}}
                <div class="panel panel-default">
                    <div class="panel-heading">{{trans("app.admin.noticias.tax.categorias")}} <span class="label label-success" id="msj-agregar-cat" style="display: none;"></span></div>
                    <div class="panel-body" id="content-cats">
                        @include("interfaz/app/listar_terms",array("terms"=>$cats,"seleccionados"=>$categorias))
                    </div>
                    {{--SECCION PARA AGREGAR CATEGORIA--}}
                    <div class="panel-footer" id="content-agregar-categoria">
                        <input style="display: none;" type="text" id="input-agregar-cat" class="form-control" placeholder="{{trans("app.admin.noticias.info.categorias.placeholder")}}">
                        <button type="button" class="btn-sm btn-primary" id="btn-agregar-cat"><span class="glyphicon glyphicon-plus-sign"></span> {{trans("app.admin.noticias.btn.agregar_categoria")}}</button>
                    </div>
                </div>  
            </div>
            {{--PUBLICAR--}}
            <div class="col-lg-3">
                <div class="panel panel-default">
                    <div class="panel-heading">{{trans('otros.info.acciones')}}</div>
                    <div class="panel-body">
                        <div class="col-lg-12" style="text-align: center;">
                            @if($noticia->estado==ContenidoApp::ESTADO_PUBLICO)
                            <button id="btn-publicar" type="button" onClick="publicar(this);" class="btn btn-info col-lg-12 text-center" style="margin-bottom: 5px;"><span class="glyphicon glyphicon glyphicon-ok-circle"></span> {{Util::convertirMayusculas(trans("otros.info.editar"))}}</button>
                            @else
                            <button id="btn-publicar" type="button" onClick="publicar(this);" class="btn btn-success col-lg-12 text-center" style="margin-bottom: 5px;"><span class="glyphicon glyphicon glyphicon-ok-circle"></span> {{Util::convertirMayusculas(trans("otros.info.publicar"))}}</button>
                            @endif
                            <button id="btn-guardar" type="button" onClick="guardar(this);" class="btn btn-default col-lg-12"><span class="glyphicon glyphicon glyphicon glyphicon-save"></span> {{trans("otros.info.guardar")}}</button>     
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
            @include("interfaz/app/opciones_editor", array("id" => "editor"))

            $("#editor").Editor("setText", "{{$noticia->contenido}}");

            jQuery("#{{$imagenID}}").fileinput({
    multiple: false,
            showPreview: true,
            showRemove: true,
            showUpload: false,
            showCaption: false,
            initialPreview: <?php echo(!is_null($imagen)) ? "\"<img src='" . $imagen->contenido . "' class='file-preview-image' style='width:100%;'/>\"" : "false"; ?>,
            maxFileCount: 1,
            previewFileType: "image",
            allowedFileExtensions: ['jpg', 'png'],
            browseLabel: "{{trans('otros.info.seleccionar')}} {{trans('otros.info.imagen')}}",
            browseIcon: '<i class="glyphicon glyphicon-picture"></i> ',
            removeClass: "btn btn-danger",
            removeLabel: "{{trans('otros.info.borrar')}}",
            removeIcon: '<i class="glyphicon glyphicon-trash"></i> ',
            uploadClass: "btn btn-info",
            uploadLabel: "trans('otros.info.subir')",
            dropZoneEnabled: false,
            dropZoneTitle: "{{trans('otros.info.arrastrar_imagen')}}...",
            uploadIcon: '<i class="glyphicon glyphicon-upload"></i> ',
            msgSelected: "{n} {{trans('otros.info.imagen')}}",
            maxFileSize: 1500,
            msgInvalidFileExtension: "{{trans('app.config.info.imagen.error01')}}",
            msgInvalidFileType: "{{trans('app.config.info.imagen.error01')}}",
            msgSizeTooLarge: "{{trans('app.config.info.imagen.error02')}}",
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
            deshabilitarBotones();
    });
            $('#{{$imagenID}}').on('fileuploaded', function (event, data, previewId, index) {
    habilitarBotones();
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
    });</script>

<script>


            function deshabilitarBotones() {
            jQuery("#btn-publicar").attr("disabled", "disabled");
                    jQuery("#btn-guardar").attr("disabled", "disabled");
            }

    function habilitarBotones() {
    jQuery("#btn-publicar").removeAttr("disabled");
            jQuery("#btn-guardar").removeAttr("disabled");
    }

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
                            jQuery("#msj-agregar-cat").html("{{trans('otros.admin.noticias.tax.categorias.info.categoria_agregada')}}");
                            jQuery("#msj-agregar-cat").fadeIn();
                            setTimeout(function () {
                            jQuery("#msj-agregar-cat").fadeOut();
                            }, 5000);
                    }}, "html");
    }

    function publicar(btn) {

    $("#{{$descripcionID}}").html($("#editor").Editor("getText"));
            $("#{{$descripcionID}}").html($("#editor").Editor("getText"));
            if (!validar())
            return;
            $("#form").attr("action", "../publicar");
            deshabilitarBotones();
            jQuery(btn).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.publicando')}}...");
            setTimeout(function () {

            $("#form").submit();
            }, 2000);
    }

    function guardar(btn) {

    $("#{{$descripcionID}}").html($("#editor").Editor("getText"));
            if (!validar())
            return;
            $("#form").attr("action", "../guardar");
            deshabilitarBotones();
            jQuery(btn).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.guardando')}}...");
            setTimeout(function () {

            $("#form").submit();
            }, 2000);
    }


     function validar() {
        var titulo = $("#{{$tituloID}}").val();
        var descripcion = $("#{{$descripcionID}}").val();
        var errores = "";
        if (titulo.length < 1) {
            errores += "<li>{{trans('app.admin.noticias.info.titulo.error')}}</li>";
        }

        if (descripcion.length < 1) {
            errores += "<li>{{trans('app.admin.noticias.info.descripcion.error')}}</li>";
        }

        if (errores.length > 0) {
            $("#error-js").html("{{trans('app.config.info.verificar_errores')}} </br><lu>" + errores + "</lu>");
            $("#error-js").toggle();
            return false;
        } else {
            return true;
        }
    }

</script>

@stop

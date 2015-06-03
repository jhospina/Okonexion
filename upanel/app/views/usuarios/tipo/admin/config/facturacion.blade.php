<?php
$logo = Instancia::obtenerValorMetadato(ConfigInstancia::visual_logo_facturacion);
$moneda = Instancia::obtenerValorMetadato(ConfigInstancia::info_moneda);
list($decimales, $sep_millar, $sep_decimal) = Monedas::formato($moneda);
?>

@extends('interfaz/plantilla')

@section("titulo") {{trans("interfaz.menu.principal.config")}}/{{trans("interfaz.menu.principal.config.facturacion")}} @stop


@include("usuarios/tipo/admin/config/secciones/css")

@section("css2")

<style>
    .col-lg-6{
        margin-bottom: 10px;
    }
</style>

<style>
    #{{ConfigInstancia::visual_logo_facturacion}}-content .file-preview-frame{
        height: 50px;
    }
</style>



@stop

@section("contenido") 

<h1><span class="glyphicon glyphicon-cog"></span> {{trans("interfaz.menu.principal.config")}}</h1>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))

@include("usuarios/tipo/admin/config/secciones/nav")

<div class="col-lg-9" id="content-config">

    <h2 class="text-right"><span class="glyphicon glyphicon-piggy-bank"></span> {{trans("interfaz.menu.principal.config.facturacion")}}</h2>


    <div class="panel panel-primary">
        <div class="panel-heading">{{trans("config.facturacion.seccion.logo.titulo")}}</div>
        <div class="panel-body">
            <div class="well">
                {{trans("config.facturacion.seccion.logo.ayuda")}}
            </div>
            <span  href="#" class="tooltip-left" rel="tooltip" id='{{ConfigInstancia::visual_logo_facturacion}}-content' title="{{trans('otros.extensiones_permitidas')}}: png, jpeg. Max 500Kb"> 
                <input name="{{ConfigInstancia::visual_logo_facturacion}}" id="{{ConfigInstancia::visual_logo_facturacion}}" accept="image/*" type="file" multiple=true>
                </a> 
                </div>
                </div>



                <form id="form-config" action="{{URL::to("config/post/guardar")}}" method="POST">

                    <div class="panel panel-primary">
                        <div class="panel-heading">{{trans("config.facturacion.seccion.impuestos.titulo")}}</div>
                        <div class="panel-body">
                            <div class="col-lg-6">
                                <label> {{trans("config.facturacion.seccion.impuestos.op.iva")}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" placeholder="{{trans('config.facturacion.seccion.impuestos.op.iva.placeholder')}}" onkeypress="return soloNumeros(this, '');" name="{{ ConfigInstancia::fact_impuestos_iva; }}" value="{{ Instancia::obtenerValorMetadato(ConfigInstancia::fact_impuestos_iva); }}"/>
                            </div>

                        </div>
                    </div>


                    <div class="panel panel-primary">
                        <div class="panel-heading">{{trans("config.facturacion.seccion.2co.titulo")}}</div>
                        <div class="panel-body">
                            <div class="col-lg-6">
                                <label>{{trans("config.facturacion.seccion.2co.idSeller")}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="{{ ConfigInstancia::fact_2checkout_idSeller; }}" value="{{ Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_idSeller); }}"/>
                            </div>
                            <div class="col-lg-6">
                                <label>{{trans("config.facturacion.seccion.2co.publicKey")}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="{{ ConfigInstancia::fact_2checkout_publicKey; }}" value="{{ Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_publicKey); }}"/>
                            </div>
                            <div class="col-lg-6">
                                <label>{{trans("config.facturacion.seccion.2co.privateKey")}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="{{ ConfigInstancia::fact_2checkout_privateKey; }}" value="{{ Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_privateKey); }}"/>
                            </div>
                            <div class="col-lg-6">
                                <label>{{trans("config.facturacion.seccion.2co.sandbox")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans('config.facturacion.seccion.2co.sandbox.ayuda')))</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="checkbox" class="js-switch" data-for="{{ConfigInstancia::fact_2checkout_sandbox}}" {{HtmlControl::setCheck(Util::convertirIntToBoolean(Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_sandbox)));}}>
                                <input type="hidden" id="{{ConfigInstancia::fact_2checkout_sandbox}}" name="{{ConfigInstancia::fact_2checkout_sandbox}}" value="{{ Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_sandbox); }}"/>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button class="btn btn-info" type="button" id="btn-guardar" type="button"><span class="glyphicon glyphicon-save"></span> {{trans("otros.info.guardar")}}</button>
                    </div>
                </form>

        </div>




        @stop

        @include("usuarios/tipo/admin/config/secciones/script")

        @section("script2")
        <script>
            jQuery("#{{ConfigInstancia::visual_logo_facturacion}}").fileinput({
                multiple: false,
                showPreview: true,
                showRemove: true,
                showUpload: false,
                initialPreview: <?php echo(!is_null($logo)) ? "\"<img style='width:300px;height:50px;' src='" . $logo . "' class='file-preview-image'/>\"" : "false"; ?>,
                maxFileCount: 1,
                previewFileType: "image",
                allowedFileExtensions: ['jpg', 'png'],
                browseLabel: "{{trans('config.general.seccion.logotipo.op.seleccionar')}}",
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
                maxFileSize: 500,
                uploadExtraData: {idConfigLogo: "{{ConfigInstancia::visual_logo_facturacion}}"},
                msgInvalidFileExtension: "{{trans('app.config.info.imagen.error01')}}",
                msgInvalidFileType: "{{trans('app.config.info.imagen.error01')}}",
                msgSizeTooLarge: "{{trans('app.config.info.imagen.error02')}}",
                uploadAsync: true,
                uploadUrl: "{{URL::to('config/ajax/subir/logo')}}" // your upload server url
            });


            //Cuando se borra la imagen
            $('#{{ConfigInstancia::visual_logo_facturacion}}').on('fileclear', function (event) {
                $.ajax({
                    type: "POST",
                    url: "{{URL::to('config/ajax/eliminar/logo')}}",
                    data: {idConfigLogo: "{{ConfigInstancia::visual_logo_facturacion}}"},
                    success: function (response) {
                    }
                }, "json");

            });

            //Sube la imagen una vez seleccionada
            $('#{{ConfigInstancia::visual_logo_facturacion}}').on('fileimageloaded', function (event, previewId) {
                $('#{{ConfigInstancia::visual_logo_facturacion}}').fileinput('upload');
            });

        </script>

        @stop
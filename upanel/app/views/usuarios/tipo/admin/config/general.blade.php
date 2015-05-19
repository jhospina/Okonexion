<?php 
$logo=  Instancia::obtenerValorMetadato(ConfigInstancia::visual_logo);       
?>

@extends('interfaz/plantilla')

@section("titulo") {{trans("interfaz.menu.principal.config")}}/{{trans("interfaz.menu.principal.config.general")}} @stop


@include("usuarios/tipo/admin/config/secciones/css")

@section("css2")

<style>
#{{ConfigInstancia::visual_logo}}-content .file-preview-frame{
height: 50px;
}
</style>

@stop

@section("contenido") 

<h1><span class="glyphicon glyphicon-cog"></span> {{trans("interfaz.menu.principal.config")}}</h1>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))

@include("usuarios/tipo/admin/config/secciones/nav")

<div class="col-lg-9" id="content-config">

    <h2 class="text-right"><span class="glyphicon glyphicon-cog"></span> {{trans("interfaz.menu.principal.config.general")}}</h2>


    <div class="panel panel-primary">
        <div class="panel-heading">{{trans("config.general.seccion.logotipo")}}</div>
        <div class="panel-body">
            <div class="well">
                {{trans("config.general.seccion.logotipo.ayuda")}}
            </div>
            <span  href="#" class="tooltip-left" rel="tooltip" id='{{ConfigInstancia::visual_logo}}-content' title="{{trans('otros.extensiones_permitidas')}}: png, jpeg. Max 500Kb"> 
                <input name="{{ConfigInstancia::visual_logo}}" id="{{ConfigInstancia::visual_logo}}" accept="image/*" type="file" multiple=true>
            </a> 
        </div>
    </div>

    <form id="form-config" action="{{URL::to("config/post/guardar")}}" method="POST">
        <div class="panel panel-primary">
            <div class="panel-heading">{{trans("config.general.seccion.periodo_prueba")}}</div>
            <div class="panel-body">
                {{--ACTIVAR PERIODO DE PRUEBA--}}
                <div class="col-lg-6">
                    <label>{{trans("config.general.seccion.periodo_prueba.op.activar")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans('config.general.seccion.periodo_prueba.op.activar.ayuda')))</label>
                </div>
                <div class="col-lg-6">
                    <input type="checkbox" class="js-switch" data-for="{{ConfigInstancia::periodoPrueba_activado}}" <?php print HtmlControl::setCheck(Util::convertirIntToBoolean(Instancia::obtenerValorMetadato(ConfigInstancia::periodoPrueba_activado))); ?>>
                    <input type="hidden" id="{{ConfigInstancia::periodoPrueba_activado}}" name="{{ConfigInstancia::periodoPrueba_activado}}" value="<?php print Instancia::obtenerValorMetadato(ConfigInstancia::periodoPrueba_activado); ?>"/>
                </div>
                {{--NUMERO DE DIAS DEL PERIODO DE PRUEBA--}}
                <div class="col-lg-6">
                    <label> {{trans("config.general.seccion.periodo_prueba.op.cantidad")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans('config.general.seccion.periodo_prueba.op.cantidad.ayuda')))</label>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{trans("config.general.seccion.periodo_prueba.op.cantidad.addon.dias")}}</div>
                            <input type="text" class="form-control" name="<?php print ConfigInstancia::periodoPrueba_numero_dias; ?>" value="<?php print Instancia::obtenerValorMetadato(ConfigInstancia::periodoPrueba_numero_dias); ?>"/>
                        </div>
                    </div>
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
    jQuery("#{{ConfigInstancia::visual_logo}}").fileinput({
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
        msgInvalidFileExtension: "{{trans('app.config.info.imagen.error01')}}",
        msgInvalidFileType: "{{trans('app.config.info.imagen.error01')}}",
        msgSizeTooLarge: "{{trans('app.config.info.imagen.error02')}}",
        uploadAsync: true,
        uploadUrl: "{{URL::to('config/ajax/subir/logo')}}" // your upload server url
    });
    
    
    //Cuando se borra la imagen
    $('#{{ConfigInstancia::visual_logo}}').on('fileclear', function (event) {
        $.ajax({
            type: "POST",
            url: "{{URL::to('config/ajax/eliminar/logo')}}",
            data: {},
            success: function (response) {}
        }, "json");

    });

    //Sube la imagen una vez seleccionada
    $('#{{ConfigInstancia::visual_logo}}').on('fileimageloaded', function (event, previewId) {
        $('#{{ConfigInstancia::visual_logo}}').fileinput('upload');
    });
    
</script>

@stop
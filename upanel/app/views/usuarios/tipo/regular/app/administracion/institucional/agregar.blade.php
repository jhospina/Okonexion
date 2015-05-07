<?php
$tipoContenido = Contenido_Institucional::nombre;
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, $tipoContenido);
$singNombre = Util::eliminarPluralidad(strtolower($nombreContenido));
$tituloID = Contenido_Institucional::configTitulo;
$descripcionID = Contenido_Institucional::configDescripcion;
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | {{trans("otros.info.administrar")}} {{$nombreContenido}} @stop

@section("css")
{{ HTML::style('assets/plugins/font-awesome/css/font-awesome.css', array('rel' => 'stylesheet')) }}
{{ HTML::style('assets/plugins/wysiwyg/editor.css', array('media' => 'screen')) }}
@stop

@section("contenido") 


{{--NAVEGACION--}}
<div class="well well-sm">
    <a href="{{URL::to("aplicacion/administrar/institucional")}}" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> {{trans("otros.info.volver")}}</a>
</div>
<hr/>
<h2><span class="glyphicon glyphicon-education"></span> {{trans("otros.info.agregar")}} {{$singNombre}}</h2> 
<hr/>
@include("interfaz/mensaje/index",array("id_mensaje"=>2))
<div class="alert alert-danger" id="error-js"></div>
<div class="col-lg-12" style="margin-bottom: 30px;margin-top: 10px;">
    <form method="POST" id="form">
        <div class="col-lg-9" style="margin-bottom: 20px;">
            <div class="col-lg-12"><input name="{{$tituloID}}" id="{{$tituloID}}" type="text"  placeholder="{{trans('app.admin.inst.info.titulo.placeholder')}}" class="form-control input-lg"></div>
            <div class="col-lg-12">
                <div id="editor"></div>
                <textarea style="display: none;" id="{{$descripcionID}}" name="{{$descripcionID}}"></textarea>  
            </div>
        </div>      
        <div class="col-lg-3">
            {{--PUBLICAR--}}
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{trans('otros.info.acciones')}}</div>
                    <div class="panel-body">
                        <div class="col-lg-12" style="text-align: center;">
                            <button id="btn-publicar" type="button" onClick="publicar(this);" class="btn btn-success col-lg-12 text-center" style="margin-bottom: 5px;"><span class="glyphicon glyphicon glyphicon-ok-circle"></span> {{Util::convertirMayusculas(trans("otros.info.publicar"))}}</button>
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
{{ HTML::script('assets/js/bootstrap-tooltip.js') }}
{{ HTML::script('assets/plugins/wysiwyg/editor.js') }}
<script>
    jQuery(document).ready(function () {

        jQuery(".tooltip-left").tooltip({placement: "left"});
        jQuery(".tooltip-top").tooltip({placement: "top"});
        @include("interfaz/app/opciones_editor", array("id"=> "editor"))
    });</script>


<script>

    function publicar(btn) {
        $("#{{$descripcionID}}").html($("#editor").Editor("getText"));
        if (!validar())
            return;
        $("#form").attr("action", "publicar");
        jQuery(btn).attr("disabled", "disabled");
        jQuery("#btn-guardar").attr("disabled", "disabled");
        jQuery(btn).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.publicando')}}...");
        setTimeout(function () {
            $("#{{$descripcionID}}").html($("#editor").Editor("getText"));
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
            errores += "<li>{{trans('app.admin.inst.info.titulo.error')}}</li>";
        }

        if (descripcion.length < 1) {
            errores += "<li>{{trans('app.admin.inst.info.descripcion.error')}}</li>";
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

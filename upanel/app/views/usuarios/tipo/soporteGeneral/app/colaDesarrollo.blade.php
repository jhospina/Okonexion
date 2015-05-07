<?php
$html_loading_ajax = '<div id="loading-ajax" class="text-center"><img src="' . URL::to("assets/img/loaders/gears.gif") . '"/></div>';
?>
@extends('interfaz/plantilla')

@section("titulo"){{trans("app.coldep.titulo")}} @stop


@section("css")
{{ HTML::style('assets/plugins/fileinput/css/fileinput.css', array('media' => 'screen')) }}
{{ HTML::style('assets/plugins/switchery/switchery.css', array('media' => 'screen')) }}
@stop


@section("contenido")  

<h1>{{trans("app.coldep.titulo")}}</h1>

<hr/>

<div class="well well-sm">
    <a href="{{URL::to("aplicacion/desarrollo/historial")}}" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span> {{trans("app.historialDep.titulo")}}</a>
</div>
 
<table class="table table-striped">
    <tr><th>{{Util::convertirMayusculas(trans("otros.info.fecha_solicitud"))}}</th>
        <th>{{Util::convertirMayusculas(trans("otros.info.aplicacion"))}}</th>
        <th>{{Util::convertirMayusculas(trans("otros.info.atendido_por"))}}</th>
        <th>{{Util::convertirMayusculas(trans("otros.info.fecha_inicio"))}}</th>
        <th>{{Util::convertirMayusculas(trans("otros.info.informe"))}}</th>
        <th>{{Util::convertirMayusculas(trans("otros.info.control"))}}</th></tr>
    @foreach($colaDesarrollo as $proceso)
    <?php
    $app = Aplicacion::find($proceso->id_aplicacion);
    $atendido_por = $proceso->user;
    ?>
    @if(is_null($proceso->fecha_finalizacion))
    
    <tr id="proceso-{{$proceso->id}}">
        <td>{{$proceso->fecha_creacion}}</td>
        <td id="nombre-app-{{$proceso->id}}">{{$app->nombre}}</td>
        <td id="atendido-{{$proceso->id}}">@if(is_null($atendido_por))Sin atender @else {{$atendido_por->nombres}} @endif</td>
        <td id="inicio-{{$proceso->id}}">@if(is_null($proceso->fecha_inicio))Sin iniciar @else {{$proceso->fecha_inicio}} @endif</td>
        <td>
            @if(is_null($proceso->fecha_inicio))
            <button id="btn-informe-{{$proceso->id}}" class="btn btn-default disabled">{{trans("app.coldep.info.ver_diseno")}}</button>
            @else
            <button id="btn-informe-{{$proceso->id}}" class="btn btn-info" onClick="verDiseno({{$proceso->id}});">{{trans("app.coldep.info.ver_diseno")}}</button>
            @endif
        </td>
        <td>
            @if(is_null($proceso->fecha_inicio))
            <button class="btn btn-primary" id="btn-control-{{$proceso->id}}" data-app="{{$app->id}}" data-proceso="{{$proceso->id}}" onClick="iniciarDesarrollo({{$proceso->id}});">{{trans("app.coldep.btn.iniciar")}}</button>
            @else
            <button class="btn btn-danger" id="btn-control-{{$proceso->id}}" data-app="{{$app->id}}" data-proceso="{{$proceso->id}}" onClick="terminarDesarrollo({{$proceso->id}});">{{trans("app.coldep.btn.terminar")}}</button>
            @endif
        </td>
    </tr>
    @endif
    @endforeach

    @if(count($colaDesarrollo)==0)

    <tr><td colspan="6" class="text-center">{{trans("app.coldep.info.no_hay_ordenes")}}</td></tr>

    @endif

</table>


{{$colaDesarrollo->links()}}

{{--*************************************************************************--}}
{{--*************************************************************************--}}
{{--*************************************************************************--}}
{{--*************************************************************************--}}
{{--*************************************************************************--}}




{{--*************MODAL DE INFORME DE DISEÑO**********************--}}

<div id="modal-informe" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{trans("app.coldep.info.informe_diseno")}} - <span id="app-informe"></span></h4>
            </div>
            <div class="modal-body" id="content-informe">
                <div id="loading-ajax" class="text-center">
                    <img src="{{URL::to("assets/img/loaders/gears.gif")}}"/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{trans("otros.info.cerrar")}}</button>
                <button type="button" class="btn btn-danger disabled" id="btn-confirmar"><span class="glyphicon glyphicon-save-file"></span> {{trans("otros.descargar.pdf")}}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


{{--*************FORMULARIO DE SUBIDA DE ARCHIVOS DE APLICACION**********************--}}

<div id="upload-app-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{trans("app.coldep.info.subir.archivos")}}</h4>
            </div>
            <div class="modal-body">
                <form id="form-upload-app" method="Post" action="">


                    <input type="checkbox" class="js-switch upload-app-check" data-app="android" name="hab-android" checked> Android 
                    <input type="checkbox" class="js-switch upload-app-check" data-app="windows" name="hab-windows" disabled="disabled"> Windows Phone 
                    <input type="checkbox" class="js-switch upload-app-check" data-app="ios" name="hab-ios" disabled="disabled"> IOS
                    <hr/>

                    {{--APLICACION EN ANDROID--}}
                    <div class="well well-lg" style="padding: 10px;" id="content-upload-android">
                        <table class="table upload-table" style="margin-bottom:0px;">
                            <tr>
                                <td style="vertical-align:middle;border:0px;" id="upload-android">
                                    <a class="tooltip-top" rel="tooltip" title="{{trans("otros.info.subir.archivo")}} .apk"> 
                                        <input name="android" id="android" class="upload_archivo" type="file" multiple=true>
                                    </a>
                                </td>
                                <td>
                                    <a class="tooltip-right" rel="tooltip" title="Android"> 
                                        <img src="{{URL::to("assets/img/android.png")}}"/>
                                    </a>
                                </td>
                            </tr>
                        </table>
                        <table class="table uploaded-table" style="margin-bottom:0px;display:none;"><tr><td style="vertical-align:middle;border:0px;"><h3><span class="glyphicon glyphicon-ok"></span> <span class="upload-nombreApp"></span> {{trans("otros.info.subido")}}</h3></td><td><a class="tooltip-right" rel="tooltip" title="Android"><img src="{{URL::to("assets/img/android.png")}}"/></a></td></tr></table>
                    </div>
                    {{--APLICACION EN WINDOWS--}}
                    <div class="well well-lg" style="padding: 10px;display:none;" id="content-upload-windows">
                        <table class="table" style="margin-bottom:0px;">
                            <tr>
                                <td style="vertical-align:middle;border:0px;">
                                    <a  class="tooltip-top" rel="tooltip" title="{{trans("otros.info.subir.archivo")}} .xap"> 
                                        <input name="windows" id="windows" class="upload_archivo" type="file" multiple=true>
                                    </a>
                                </td>
                                <td>
                                    <a class="tooltip-right" rel="tooltip" title="Windows Phone"> 
                                        <img src="{{URL::to("assets/img/windows.png")}}"/>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    {{--APLICACION EN IOS--}}
                    <div class="well well-lg" style="padding: 10px;display:none;" id="content-upload-ios">
                        <table class="table" style="margin-bottom:0px;">
                            <tr>
                                <td style="vertical-align:middle;border:0px;">
                                    <a  class="tooltip-top" rel="tooltip" title="{{trans("otros.info.subir.archivo")}} .ipa"> 
                                        <input name="iphone" id="iphone" class="upload_archivo" type="file" multiple=true>
                                    </a>
                                </td>
                                <td>
                                    <a class="tooltip-right" rel="tooltip" title="IOS (Iphone)"> 
                                        <img src="{{URL::to("assets/img/ios.png")}}"/>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    

                    <input name="archivo_android" id="archivo_android" type="hidden" value="">
                    <input name="archivo_windows" id="archivo_windows" type="hidden" value="">
                    <input name="archivo_iphone" id="archivo_iphone" type="hidden" value="">

                    <div id="msj-sin-plataformas" style="color: red;display:none;">{{trans("app.coldep.info.error.sin_plataformas")}}</div>
                    
                    <div class="well well-sm" style="padding: 2px;">
                        <table class="table" style="margin-bottom:0px;">
                            <tr>
                                <td style="vertical-align:middle;border:0px;">
                                    <textarea id="observaciones" class="form-control" style="height:80px;" placeholder="{{trans("app.coldep.info.observaciones.placeholder")}}"></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{trans("otros.info.cerrar")}}</button>
                <button type="button" class="btn btn-primary" onClick="enviarTerminar(this);" id="btn-enviar-terminar">{{trans("otros.info.enviar")}}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




@stop


@section("script")


{{ HTML::script('assets/js/bootstrap-filestyle.min.js') }}
{{ HTML::script('assets/js/bootstrap-tooltip.js') }}
{{ HTML::script('assets/plugins/fileinput/js/fileinput.js') }}
{{ HTML::script('assets/plugins/switchery/switchery.js') }}


<script>
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            elems.forEach(function(html) {
            var switchery = new Switchery(html);
            });
            jQuery(".tooltip-left").tooltip({placement: "left"});
            jQuery(".tooltip-top").tooltip({placement: "top"});
            jQuery(".tooltip-right").tooltip({placement: "right"});</script>

<script>

            var num_plats = 1; //Indica el numero de plataformas habilitadas para subir la aplicación.

            //CHECKBOX DE UPLOADS DE APLICACIONES
            jQuery(".upload-app-check").change(function(){

    jQuery("#content-upload-" + jQuery(this).attr("data-app")).fadeToggle(function(){
    //Va indicando cuantas plataformas esta disponibles para subir
    if ($(this).css("display") == "none")
            num_plats--;
            else
            num_plats++;
            if (num_plats == 0){
    $("#msj-sin-plataformas").show();
            $("#btn-enviar-terminar").attr("disabled", "disabled");
    }
    else{
    $("#msj-sin-plataformas").hide();
            $("#btn-enviar-terminar").removeAttr("disabled");
    }

    });
    });
            //ACCION DEL BOTON VER DISEÑO
                    function verDiseno(id_proceso){

                    var informe = jQuery("#content-informe");
                            var btn = jQuery("#btn-control-" + id_proceso);
                            var id_aplicacion = btn.attr("data-app");
                            jQuery("#app-informe").html(jQuery("#nombre-app-" + id_proceso).html());
                            informe.html('{{$html_loading_ajax}}');
                            jQuery("#btn-informe-" + id_proceso).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> ...");
                            //OBTIENE LOS DATOS DE LA APLICACION TRAIDOS EN JSON POR AJAx
                            jQuery.ajax({
                            type: "POST",
                                    url: "{{URL::to('aplicacion/ajax/desarrollo/informe/diseno')}}",
                                    data: {id_aplicacion: id_aplicacion, id_proceso: id_proceso},
                                    success: function (data) {
                                    data = jQuery.parseJSON(data);
                                            //Carga el contenido del informe en una tabla html
                                            setTimeout(function(){
                                            var html = "";
                                                    jQuery.each(data, function(propiedad, valor){
                                                    html += "<tr><th>" + propiedad + "</th><td>" + valor + "</td></tr>";
                                                    });
                                                    var tabla = "<table class='table table-striped'><tr><th>{{Util::convertirMayusculas(trans('otros.info.caracteristica'))}}</th><th>{{Util::convertirMayusculas(trans('otros.info.valor'))}}</th></tr>" + html + "</table>";
                                                    var btn_descargar_android = "<a style='width:50%;margin-bottom:5px;' class='btn btn-primary' target='_blank' href='{{URL::to('aplicacion/ajax/desarrollo/descargar/disenoApp/android')}}?id_p=" + id_proceso + "&id_app=" + id_aplicacion + "'><span class='glyphicon glyphicon-download-alt'></span> {{trans('app.coldep.info.descargar_diseno_android')}}</a></br>";
                                                    var btn_descargar_iphone = "<a style='width:50%;margin-bottom:5px;' disabled='disabled' class='btn btn-primary' target='_blank' href='{{URL::to('aplicacion/ajax/desarrollo/descargar/disenoApp/iphone')}}?id_p=" + id_proceso + "&id_app=" + id_aplicacion + "'><span class='glyphicon glyphicon-download-alt'></span> {{trans('app.coldep.info.descargar_diseno_iphone')}}</a></br>";
                                                    var btn_descargar_windows = "<a style='width:50%;margin-bottom:5px;' disabled='disabled' class='btn btn-primary' target='_blank' href='{{URL::to('aplicacion/ajax/desarrollo/descargar/disenoApp/windows')}}?id_p=" + id_proceso + "&id_app=" + id_aplicacion + "'><span class='glyphicon glyphicon-download-alt'></span> {{trans('app.coldep.info.descargar_diseno_windows')}}</a></br>";
                                                    informe.html(tabla + "<div class='text-center'>" + btn_descargar_android + btn_descargar_iphone + btn_descargar_windows + "</div>");
                                                    jQuery("#modal-informe").modal("show");
                                                    jQuery("#btn-informe-" + id_proceso).html("{{trans('app.coldep.info.ver_diseno')}}");
                                            }, 1000);
                                    }
                            }, "json");
                    }


            function iniciarDesarrollo(id_proceso) {

            var btn = jQuery("#btn-control-" + id_proceso);
                    var id_aplicacion = btn.attr("data-app");
                    btn.html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> ...");
                    btn.attr("disabled", "disabled");
                    jQuery.ajax({
                    type: "POST",
                            url: "{{URL::to('aplicacion/ajax/desarrollo/estado/iniciar')}}",
                            data: {id_aplicacion: id_aplicacion, id_proceso: id_proceso},
                            success: function (data) {

                            data = jQuery.parseJSON(data);
                                    jQuery("#atendido-" + id_proceso).html(data.atendido_por);
                                    jQuery("#inicio-" + id_proceso).html(data.fecha_inicio);
                                    btn.html("{{trans('app.coldep.btn.terminar')}}");
                                    btn.removeAttr("disabled");
                                    btn.removeClass("btn-primary");
                                    btn.addClass("btn-danger");
                                    btn.attr("onClick", "terminarDesarrollo(" + id_proceso + ");");
                                    //REORGANIZA EL BOTON DE VER DISEÑO
                                    jQuery("#btn-informe-" + id_proceso).removeClass("btn-default");
                                    jQuery("#btn-informe-" + id_proceso).removeClass("disabled");
                                    jQuery("#btn-informe-" + id_proceso).addClass("btn-info");
                                    jQuery("#btn-informe-" + id_proceso).attr("onClick", "verDiseno(" + id_proceso + ")");
                            }
                    }, "json");
            }


            var proceso = 0; // Indica el id del proceso


                    //FUNCIONES DE ACCION
                            function terminarDesarrollo(id_proceso) {

                            proceso=id_proceso;
                            $("#observaciones").val("");

                            $("#archivo_android").val("");
                                    $("#archivo_windows").val("");
                                    $("#archivo_iphone").val("");
                                    jQuery("#upload-app-modal").modal("show");
                                    $(".upload-table").show();
                                    $(".uploaded-table").hide();
                                    jQuery("#android").fileinput({
                            multiple: false,
                                    showPreview: false,
                                    showRemove: false,
                                    showUpload:false,
                                    maxFileCount: 1,
                                    allowedFileExtensions: ['apk'],
                                    browseLabel: "{{trans('otros.info.buscar')}} Apk",
                                    browseIcon: '<i class="glyphicon glyphicon-phone"></i> ',
                                    removeClass: "btn btn-danger",
                                    removeLabel: "{{trans('otros.info.borrar')}}",
                                    removeIcon: '<i class="glyphicon glyphicon-trash"></i> ',
                                    uploadClass: "btn btn-info",
                                    uploadLabel: "Subir",
                                    uploadIcon: '<i class="glyphicon glyphicon-upload"></i> ',
                                    msgSelected: '{n} imagen',
                                    dropZoneEnabled: false,
                                    maxFileSize: 10000,
                                    msgInvalidFileExtension: "{{trans('app.coldep.info.subir.archivos.error01',array('archivos'=>'.apk'))}}",
                                    msgInvalidFileType: "{{trans('app.coldep.info.subir.archivos.error01',array('archivos'=>'.apk'))}}",
                                    msgValidationError:"{{trans('app.coldep.info.subir.archivos.error01',array('archivos'=>'.apk'))}}",
                                    msgSizeTooLarge: "{{trans('app.coldep.info.subir.archivos.error02')}}",
                                    uploadAsync: true,
                                    uploadExtraData:{id_proceso:id_proceso,plataforma:"android"},
                                    uploadUrl: "{{URL::to('aplicacion/ajax/upload/app')}}" // your upload server url
                            });
                            }



                    function enviarTerminar(btn){

                    var android = $("#archivo_android").val();
                            var windows = $("#archivo_windows").val();
                            var iphone = $("#archivo_iphone").val();
                            
                            if(android.length==0 && windows.length==0 && iphone.length==0)
                                return;
                            
                            
                            $(btn).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> ...");
                            $(btn).attr("disabled", "disabled");
                            
                            jQuery.ajax({
                            type: "POST",
                                    url: "{{URL::to('aplicacion/ajax/desarrollo/estado/terminar')}}",
                                    data: {id_proceso: proceso,android:android,windows:windows,iphone:iphone,observacion:$("#observaciones").val()},
                                    success: function (response) {
                                            
                                      //Oculta el modal
                                      jQuery("#upload-app-modal").modal("hide");
                                      
                                    $(btn).html("{{trans('otros.info.enviar')}}");
                                            $(btn).removeAttr("disabled");
                                            jQuery("#proceso-" + proceso).fadeOut(function () {
                                    jQuery(this).remove();
                                    });
                                    }}, "html");
                    }


</script>



<script>

                    //ANDROID
                    $("#android").on('filebatchuploadsuccess', function(event, data) {
                    var data = data.response;
                            $("#archivo_android").val(data["path"]);
                            $("#content-upload-android .upload-table").hide();
                            $("#content-upload-android .uploaded-table").show();
                            $(".upload-nombreApp").html(data["nombreApp"]);
                            $("#btn-enviar-terminar").removeAttr("disabled");
                    });
                            //Windows
                            $("#windows").on('filebatchuploadsuccess', function(event, data) {
                    var data = data.response;
                            $("#archivo_windows").val(data["path"]);
                            $("#content-upload-android .upload-table").hide();
                            $("#content-upload-android .uploaded-table").show();
                            $(".upload-nombreApp").html(data["nombreApp"]);
                            $("#btn-enviar-terminar").removeAttr("disabled");
                    });
                            //Iphone
                            $("#iphone").on('filebatchuploadsuccess', function(event, data) {
                    var data = data.response;
                            $("#archivo_iphone").val(data["path"]);
                            $("#content-upload-android .upload-table").hide();
                            $("#content-upload-android .uploaded-table").show();
                            $(".upload-nombreApp").html(data["nombreApp"]);
                            $("#btn-enviar-terminar").removeAttr("disabled");
                    });
                            //Sube el archivo inmediamente cuando es seleccionado
                            $('.upload_archivo').on('fileloaded', function (event, previewId) {
                    $(this).fileinput('upload');
                            $("#btn-enviar-terminar").attr("disabled", "disabled");
                    });





</script>



@stop

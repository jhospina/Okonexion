<?php
$html_loading_ajax = '<div id="loading-ajax" class="text-center"><img src="' . URL::to("assets/img/loaders/gears.gif") . '"/></div>';
?>
@extends('interfaz/plantilla')

@section("titulo")Cola de desarrollo @stop


@section("css")
{{ HTML::style('assets/plugins/fileinput/css/fileinput.css', array('media' => 'screen')) }}
{{ HTML::style('assets/plugins/switchery/switchery.css', array('media' => 'screen')) }}
@stop


@section("contenido")  

<h1>Cola de desarrollo</h1>

<hr/>


<table class="table table-striped">
    <tr><th>FECHA DE SOLICITUD</th><th>APLICACIÓN</th><th>ATENDIDO POR</th><th>FECHA DE INICIO</th><th>INFORME</th><th>CONTROL</th></tr>
    @foreach($colaDesarrollo as $proceso)
    <?php
    $app = Aplicacion::find($proceso->id_aplicacion);
    $atendido_por = $proceso->user;
    ?>
    <tr id="proceso-{{$proceso->id}}}}">
        <td>{{$proceso->fecha_creacion}}</td>
        <td id="nombre-app-{{$proceso->id}}">{{$app->nombre}}</td>
        <td id="atendido-{{$proceso->id}}">@if(is_null($atendido_por))Sin atender @else {{$atendido_por->nombres}} @endif</td>
        <td id="inicio-{{$proceso->id}}">@if(is_null($proceso->fecha_inicio))Sin iniciar @else {{$proceso->fecha_inicio}} @endif</td>
        <td>
            @if(is_null($proceso->fecha_inicio))
            <button id="btn-informe-{{$proceso->id}}" class="btn btn-default disabled">Ver diseño </button>
            @else
            <button id="btn-informe-{{$proceso->id}}" class="btn btn-info" onClick="verDiseno({{$proceso->id}});">Ver diseño </button>
            @endif
        </td>
        <td>
            @if(is_null($proceso->fecha_inicio))
            <button class="btn btn-primary" id="btn-control-{{$proceso->id}}" data-app="{{$app->id}}" data-proceso="{{$proceso->id}}" onClick="iniciarDesarrollo({{$proceso->id}});">Iniciar</button>
            @else
            <button class="btn btn-danger" id="btn-control-{{$proceso->id}}" data-app="{{$app->id}}" data-proceso="{{$proceso->id}}" onClick="terminarDesarrollo({{$proceso->id}});">Terminar</button>
            @endif
        </td>
    </tr>
    @endforeach

    @if(count($colaDesarrollo)==0)

    <tr><td colspan="6" class="text-center">No hay ordenes de desarrollo en cola para mostrar</td></tr>

    @endif

</table>


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
                <h4 class="modal-title">Informe de diseño - <span id="app-informe"></span></h4>
            </div>
            <div class="modal-body" id="content-informe">
                <div id="loading-ajax" class="text-center">
                    <img src="{{URL::to("assets/img/loaders/gears.gif")}}"/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-danger disabled" id="btn-confirmar"><span class="glyphicon glyphicon-save-file"></span> Descargar en PDF</button>
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
                <h4 class="modal-title">Subir archivos de aplicación</h4>
            </div>
            <div class="modal-body">
                <form id="form-upload-app" method="Post" action="">


                    <input type="checkbox" class="js-switch upload-app-check" data-app="android" name="hab-android" checked> Android 
                    <input type="checkbox" class="js-switch upload-app-check" data-app="windows" name="hab-windows" checked> Windows Phone 
                    <input type="checkbox" class="js-switch upload-app-check" data-app="ios" name="hab-ios" checked> IOS
                    <hr/>

                    {{--APLICACION EN ANDROID--}}
                    <div class="well well-lg" style="padding: 10px;" id="content-upload-android">
                        <table class="table" style="margin-bottom:0px;">
                            <tr>
                                <td style="vertical-align:middle;border:0px;">
                                    <a class="tooltip-top" rel="tooltip" title="Suba un archivo .apk"> 
                                        <input name="android" id="android" class="iconoMenu" type="file" multiple=true>
                                    </a>
                                </td>
                                <td>
                                    <a class="tooltip-right" rel="tooltip" title="Android"> 
                                        <img src="{{URL::to("assets/img/android.png")}}"/>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    {{--APLICACION EN WINDOWS--}}
                    <div class="well well-lg" style="padding: 10px;" id="content-upload-windows">
                        <table class="table" style="margin-bottom:0px;">
                            <tr>
                                <td style="vertical-align:middle;border:0px;">
                                    <a  class="tooltip-top" rel="tooltip" title="Suba un archivo .xap"> 
                                        <input name="windows" id="windows" class="iconoMenu" type="file" multiple=true>
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
                    <div class="well well-lg" style="padding: 10px;" id="content-upload-ios">
                        <table class="table" style="margin-bottom:0px;">
                            <tr>
                                <td style="vertical-align:middle;border:0px;">
                                    <a  class="tooltip-top" rel="tooltip" title="Suba un archivo .ipa"> 
                                        <input name="ios" id="ios" class="iconoMenu" type="file" multiple=true>
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




                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btn-confirmar">Enviar</button>
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
//*************TOOLTIP

            jQuery(".tooltip-left").tooltip({placement: "left"});
            jQuery(".tooltip-top").tooltip({placement: "top"});
            jQuery(".tooltip-right").tooltip({placement: "right"});
            //********************************************************************

            jQuery("#android").fileinput({
    multiple: false,
            showPreview: true,
            showRemove: true,
            maxFileCount: 1,
            allowedFileExtensions: ['apk'],
            browseLabel: "Buscar Apk",
            browseIcon: '<i class="glyphicon glyphicon-phone"></i> ',
            removeClass: "btn btn-danger",
            removeLabel: "Borrar",
            removeIcon: '<i class="glyphicon glyphicon-trash"></i> ',
            uploadClass: "btn btn-info",
            uploadLabel: "Subir",
            dropZoneEnabled: false,
            dropZoneTitle: "Arrastra tu imagen aquí...",
            uploadIcon: '<i class="glyphicon glyphicon-upload"></i> ',
            msgSelected: '{n} imagen',
            maxFileSize: 10000,
            msgInvalidFileExtension: "El archivo que has escogido no es valido. Solo se permite archivos .apk",
            msgInvalidFileType: "El archivo que has escogido no es valido. Solo se permite archivos .apk",
            msgSizeTooLarge: "El tamaño del archivo es demasiado grande. Maximo <b>{maxSize} KB</b>. Este archivo pesa <b>{size} KB.</b>",
            uploadAsync: true,
            uploadUrl: "{{URL::to('aplicacion/ajax/upload/android')}}" // your upload server url
    });
    {{--WINDOWS * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * --}}

    jQuery("#windows").fileinput({
    multiple: false,
            showPreview: true,
            showRemove: true,
            maxFileCount: 1,
            allowedFileExtensions: ['xap'],
            browseLabel: "Buscar Xap",
            browseIcon: '<i class="glyphicon glyphicon-phone"></i> ',
            removeClass: "btn btn-danger",
            removeLabel: "Borrar",
            removeIcon: '<i class="glyphicon glyphicon-trash"></i> ',
            uploadClass: "btn btn-info",
            uploadLabel: "Subir",
            dropZoneEnabled: false,
            dropZoneTitle: "Arrastra tu imagen aquí...",
            uploadIcon: '<i class="glyphicon glyphicon-upload"></i> ',
            msgSelected: '{n} imagen',
            maxFileSize: 10000,
            msgInvalidFileExtension: "El archivo que has escogido no es valido. Solo se permite archivos .xap",
            msgInvalidFileType: "El archivo que has escogido no es valido. Solo se permite archivos .xap",
            msgSizeTooLarge: "El tamaño del archivo es demasiado grande. Maximo <b>{maxSize} KB</b>. Este archivo pesa <b>{size} KB.</b>",
            uploadAsync: true,
            uploadUrl: "{{URL::to('aplicacion/ajax/upload/windows')}}" // your upload server url
    });
    {{--IOS IPHONE * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * --}}

    jQuery("#ios").fileinput({
    multiple: false,
            showPreview: true,
            showRemove: true,
            maxFileCount: 1,
            allowedFileExtensions: ['ipa'],
            browseLabel: "Buscar Ipa",
            browseIcon: '<i class="glyphicon glyphicon-phone"></i> ',
            removeClass: "btn btn-danger",
            removeLabel: "Borrar",
            removeIcon: '<i class="glyphicon glyphicon-trash"></i> ',
            uploadClass: "btn btn-info",
            uploadLabel: "Subir",
            dropZoneEnabled: false,
            dropZoneTitle: "Arrastra tu imagen aquí...",
            uploadIcon: '<i class="glyphicon glyphicon-upload"></i> ',
            msgSelected: '{n} imagen',
            maxFileSize: 10000,
            msgInvalidFileExtension: "El archivo que has escogido no es valido. Solo se permite archivos .ipa",
            msgInvalidFileType: "El archivo que has escogido no es valido. Solo se permite archivos .ipa",
            msgSizeTooLarge: "El tamaño del archivo es demasiado grande. Maximo <b>{maxSize} KB</b>. Este archivo pesa <b>{size} KB.</b>",
            uploadAsync: true,
            uploadUrl: "{{URL::to('aplicacion/ajax/upload/ios')}}" // your upload server url
    });</script>

<script>


            //CHECKBOX DE UPLOADS DE APLICACIONES
            jQuery(".upload-app-check").change(function(){
    jQuery("#content-upload-" + jQuery(this).attr("data-app")).fadeToggle();
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
                                                    if (/logoApp/.test(propiedad))
                                                            html += "<tr><th>" + propiedad + "</th><td> <a target='_blank' href='{{URL::to('aplicacion/ajax/desarrollo/descargar/logoApp/')}}?id_p=" + id_proceso + "&id_app=" + id_aplicacion + "&imagen=" + valor + "'><span class='glyphicon glyphicon-save-file'></span> Descargar Logo</a></td></tr>";
                                                            else
                                                            html += "<tr><th>" + propiedad + "</th><td>" + valor + "</td></tr>";
                                                    });
                                                    informe.html("<table class='table table-striped'><tr><th>CARACTERISTICA</th><th>VALOR</th></tr>" + html + "</table>");
                                                    jQuery("#modal-informe").modal("show");
                                                    jQuery("#btn-informe-" + id_proceso).html("Ver diseño");
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
                                    btn.html("Terminar");
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



            function terminarDesarrollo(id_proceso) {

            jQuery("#upload-app-modal").modal("show");
            {{--
                    var btn = jQuery("#btn-control-" + id_proceso);
                    var id_aplicacion = btn.attr("data-app");
                    btn.html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> ...");
                    btn.attr("disabled", "disabled");
                    jQuery.ajax({
                    type: "POST",
                            url: "{{URL::to('aplicacion/ajax/desarrollo/estado/terminar')}}",
                            data: {id_aplicacion: id_aplicacion, id_proceso: id_proceso},
                            success: function (response) {

                            console.log("TERMINADO");
                                    jQuery("#proceso-" + id_proceso).fadeOut(function () {
                            jQuery(this).remove();
                            });
                            }}, "html"); --}}
            }



</script>


<script>



</script>

@stop

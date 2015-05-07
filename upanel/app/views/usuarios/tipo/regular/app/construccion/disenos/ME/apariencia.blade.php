<?php

$logo = $app->url_logo;

//ID de las claves de las configuraciones de los iconos del menu principal
$iconosMenuID = array(App_Metro::iconoMenu1, App_Metro::iconoMenu2, App_Metro::iconoMenu3, App_Metro::iconoMenu4);

//Color de barra de la app
if (ConfiguracionApp::existeConfig(App_Metro::colorBarraApp))
    $colorBarraApp = ConfiguracionApp::obtenerValorConfig(App_Metro::colorBarraApp);
else
    $colorBarraApp = "#000000";

//MOSTRAR NOMBRE
if (ConfiguracionApp::existeConfig(App_Metro::mostrarNombre))
    $mostrarNombre = ConfiguracionApp::obtenerValorConfig(App_Metro::mostrarNombre);
else
    $mostrarNombre = "textoLogo";

//ALINEACION NOMBRE
if (ConfiguracionApp::existeConfig(App_Metro::alineacionNombre))
    $alineacionNombre = ConfiguracionApp::obtenerValorConfig(App_Metro::alineacionNombre);
else
    $alineacionNombre = "izquierda";

//Color del texto del nombre de la APP
if (ConfiguracionApp::existeConfig(App_Metro::colorNombreApp))
    $colorNombreApp = ConfiguracionApp::obtenerValorConfig(App_Metro::colorNombreApp);
else
    $colorNombreApp = "#FFFFFF";


//Texto del boton del menu  #1
if (ConfiguracionApp::existeConfig(App_Metro::txt_menuBtn_1))
    $txt_menuBtn_1 = ConfiguracionApp::obtenerValorConfig(App_Metro::txt_menuBtn_1);
else
    $txt_menuBtn_1 = Contenido_Institucional::nombreDefecto();

//Texto del boton del menu  #2
if (ConfiguracionApp::existeConfig(App_Metro::txt_menuBtn_2))
    $txt_menuBtn_2 = ConfiguracionApp::obtenerValorConfig(App_Metro::txt_menuBtn_2);
else
    $txt_menuBtn_2 = Contenido_Noticias::nombreDefecto();

//Texto del boton del menu  #3
if (ConfiguracionApp::existeConfig(App_Metro::txt_menuBtn_3))
    $txt_menuBtn_3 = ConfiguracionApp::obtenerValorConfig(App_Metro::txt_menuBtn_3);
else
    $txt_menuBtn_3 = Contenido_Encuestas::nombreDefecto();

//Texto del boton del menu  #4
if (ConfiguracionApp::existeConfig(App_Metro::txt_menuBtn_4))
    $txt_menuBtn_4 = ConfiguracionApp::obtenerValorConfig(App_Metro::txt_menuBtn_4);
else
    $txt_menuBtn_4 = Contenido_PQR::nombreDefecto();


//COLOR DE FONDO DE LA OPCION #1
if (ConfiguracionApp::existeConfig(App_Metro::colorFondoMenuBt_1))
    $colorFondoMenuBt_1 = ConfiguracionApp::obtenerValorConfig(App_Metro::colorFondoMenuBt_1);
else
    $colorFondoMenuBt_1 = "#FFFFFF";

//COLOR DE FONDO DE LA OPCION #2
if (ConfiguracionApp::existeConfig(App_Metro::colorFondoMenuBt_2))
    $colorFondoMenuBt_2 = ConfiguracionApp::obtenerValorConfig(App_Metro::colorFondoMenuBt_2);
else
    $colorFondoMenuBt_2 = "#FFFFFF";

//COLOR DE FONDO DE LA OPCION #3
if (ConfiguracionApp::existeConfig(App_Metro::colorFondoMenuBt_3))
    $colorFondoMenuBt_3 = ConfiguracionApp::obtenerValorConfig(App_Metro::colorFondoMenuBt_3);
else
    $colorFondoMenuBt_3 = "#FFFFFF";

//COLOR DE FONDO DE LA OPCION #4
if (ConfiguracionApp::existeConfig(App_Metro::colorFondoMenuBt_4))
    $colorFondoMenuBt_4 = ConfiguracionApp::obtenerValorConfig(App_Metro::colorFondoMenuBt_4);
else
    $colorFondoMenuBt_4 = "#FFFFFF";


//COLOR DEL TEXTO DE LA OPCION #1
if (ConfiguracionApp::existeConfig(App_Metro::txt_menuBtn_1_color))
    $txt_menuBtn_1_color = ConfiguracionApp::obtenerValorConfig(App_Metro::txt_menuBtn_1_color);
else
    $txt_menuBtn_1_color = "rgb(0,0,0)";

//COLOR DEL TEXTO DE LA OPCION #2
if (ConfiguracionApp::existeConfig(App_Metro::txt_menuBtn_2_color))
    $txt_menuBtn_2_color = ConfiguracionApp::obtenerValorConfig(App_Metro::txt_menuBtn_2_color);
else
    $txt_menuBtn_2_color = "rgb(0,0,0)";

//COLOR DEL TEXTO DE LA OPCION #3
if (ConfiguracionApp::existeConfig(App_Metro::txt_menuBtn_3_color))
    $txt_menuBtn_3_color = ConfiguracionApp::obtenerValorConfig(App_Metro::txt_menuBtn_3_color);
else
    $txt_menuBtn_3_color = "rgb(0,0,0)";

//COLOR DEL TEXTO DE LA OPCION #4
if (ConfiguracionApp::existeConfig(App_Metro::txt_menuBtn_4_color))
    $txt_menuBtn_4_color = ConfiguracionApp::obtenerValorConfig(App_Metro::txt_menuBtn_4_color);
else
    $txt_menuBtn_4_color = "rgb(0,0,0)";


//******************************************************************************
//******************************************************************************
//******************************************************************************
//******************************************************************************
//******************************************************************************
//******************************************************************************
//******************************************************************************
//******************************************************************************
//******************************************************************************
//******************************************************************************
//******************************************************************************
//******************************************************************************
//******************************************************************************
//******************************************************************************
?>
@extends('interfaz/plantilla')

@section("titulo"){{trans("app.hd.mi_aplicacion")}} @stop


@section("css")
{{ HTML::style('assets/plugins/colorpicket/css/colorpicker.css', array('media' => 'screen')) }}
{{ HTML::style('assets/plugins/colorpicket/css/layout.css', array('media' => 'screen')) }}
{{ HTML::style('assets/plugins/fileinput/css/fileinput.css', array('media' => 'screen')) }}
{{ HTML::style('assets/plugins/plunk/jquery.simplecolorpicker.css', array('media' => 'screen')) }}

@stop


@section("contenido") 

{{--CABECERA--}}
@include("usuarios/tipo/regular/app/construccion/secciones/cabecera")

{{--BARRA DE PROGRESO--}}
@include("usuarios/tipo/regular/app/construccion/secciones/barra-progreso") 

{{--MENU DE NAVEGACIÓN ENTRE SECCCION--}}
@include("usuarios/tipo/regular/app/construccion/secciones/nav")

<div class="col-lg-9" id="content-config">

    <h2 class="text-right">{{Util::convertirMayusculas(trans("interfaz.menu.principal.mi_aplicacion.configuracion.apariencia"))}}</h2>

    <hr/>

    @include("interfaz/mensaje/index",array("id_mensaje"=>3))


    @if($app->estado==Aplicacion::ESTADO_LISTA_PARA_ENVIAR || $app->estado==Aplicacion::ESTADO_ESTABLECIENTO_TEXTOS || $app->estado==Aplicacion::ESTADO_EN_DISENO || $app->estado==Aplicacion::ESTADO_TERMINADA)

    <form action="" method="POST" enctype="multipart/form-data" id="form">

        {{--**************************************************************************--}}
        {{--OPCIONES GENERALES***********************************************--}}
        {{--**************************************************************************--}}

        <div class="block">

            <h3 class="text-right col-lg-12">{{trans("app.config.info.titulo.general")}}</h3>
            <div class="col-lg-12">

                @include("usuarios/tipo/regular/app/construccion/secciones/logoApp")


                <div class="panel panel-primary" style="clear: both;">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{trans("app.config.di.me.panel.titulo.barra_aplicacion")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans("app.config.di.me.panel.titulo.barra_aplicacion.ayuda"))) </h3>
                    </div>
                    <div class="panel-body">
                        {{--COLOR DEL FONDO DE LA BARRA (1)--}}
                        <div class="col-lg-4 text-default input-lg">{{trans("app.config.di.me.info.colorbarraapp")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans('app.config.di.me.info.colorbarraapp.ayuda')))</div>
                       <div class="col-lg-8 input-lg">
                            <div class="colorSelector" id="colorSelector_{{App_Metro::colorBarraApp}}"><div style="background-color:{{$colorBarraApp}}"></div></div>
                        </div>
                         <input type="hidden" name="{{App_Metro::colorBarraApp}}" id="{{App_Metro::colorBarraApp}}" value="{{$colorBarraApp}}" />
                        

                        {{--PROPIEDADES DEL NOMBRE--}}
                        <div class="col-lg-4 input-lg">
                            {{trans("app.config.di.me.info.mostrar_nombre")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans("app.config.di.me.info.mostrar_nombre.ayuda"))) 
                        </div>
                        <div class="col-lg-3 input-lg"><input type="radio" name="{{App_Metro::mostrarNombre}}" value="soloTexto" class="radio-inline" @if($mostrarNombre=="soloTexto") checked @endif> <span class="radio-value">{{trans("app.config.di.me.info.mostrar_nombre.op.solo_texto")}}</span></div>
                        <div class="col-lg-3 input-lg"><input type="radio" name="{{App_Metro::mostrarNombre}}" value="textoLogo" class="radio-inline" @if($mostrarNombre=="textoLogo") checked @endif> <span class="radio-value">{{trans("app.config.di.me.info.mostrar_nombre.op.texto_logo")}}</span></div>
                        <div class="col-lg-2 input-lg"><input type="radio" name="{{App_Metro::mostrarNombre}}" value="soloLogo" class="radio-inline" @if($mostrarNombre=="soloLogo") checked @endif> <span class="radio-value">{{trans("app.config.di.me.info.mostrar_nombre.op.solo_logo")}}</span></div>


                        {{--ALINEACIÓN--}}
                        <div class="col-lg-4 input-lg">
                            {{trans("app.config.di.me.info.alineacion_nombre")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans("app.config.di.me.info.alineacion_nombre.ayuda"))) 
                        </div>
                        <div class="col-lg-3 input-lg"><input type="radio" name="{{App_Metro::alineacionNombre}}" value="izquierda" class="radio-inline" @if($alineacionNombre=="izquierda") checked @endif> <span class="radio-value">{{trans("otros.info.izquierda")}}</span></div>
                        <div class="col-lg-3 input-lg"><input type="radio" name="{{App_Metro::alineacionNombre}}" value="centro" class="radio-inline" @if($alineacionNombre=="centro") checked @endif> <span class="radio-value">{{trans("otros.info.centro")}}</span></div>
                        <div class="col-lg-2 input-lg"><input type="radio" name="{{App_Metro::alineacionNombre}}" value="derecha" class="radio-inline" @if($alineacionNombre=="derecha") checked @endif> <span class="radio-value">{{trans("otros.info.derecha")}}</span></div>


                        {{--COLOR DEL NOMBRE DE LA APLICACIÒN--}}
                        <div class="col-lg-4 text-default input-lg">{{trans("app.config.di.me.info.color_nombre")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans("app.config.di.me.info.color_nombre.ayuda"))) </div>
                        <div class="col-lg-8 input-lg">
                            <div class="colorSelector" id="colorSelector_{{App_Metro::colorNombreApp}}"><div style="background-color:{{$colorNombreApp}}"></div></div>
                        </div>

                        <input type="hidden" name="{{App_Metro::colorNombreApp}}" id="{{App_Metro::colorNombreApp}}" value="{{$colorNombreApp}}" />

                    </div>
                </div>
            </div>
        </div>

        {{--**************************************************************************--}}
        {{--OPCIONES DEL MENU PRINCIPAL***********************************************--}}
        {{--**************************************************************************--}}


        <div class="block">

            <h3 class="text-right col-lg-12">{{trans("app.config.info.titulo.menu_principal")}}</h3>

            {{--OPCION #1 (UNO) DEL MENU***************************************************--}}

            <div class="col-lg-12">

                <div class="panel panel-primary" style="clear: both;">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{trans("app.config.di.me.panel.titulo.primera_opcion")}} <img width="20" src="{{URL::to("assets/img/icons/cuadricula-1.PNG")}}"/></h3>
                    </div>
                    <div class="panel-body">
                        
                         <div class="well well-sm">
                            {{trans("app.tipo.contenido.institucional.descripcion")}}
                         </div>
                        
                        <div class="col-lg-4 text-default input-lg">{{trans("otros.info.titulo")}}</div> <div class="col-lg-8"><input type="text" name="{{App_Metro::txt_menuBtn_1}}" id="{{App_Metro::txt_menuBtn_1}}" class="form-control input-lg" value="{{$txt_menuBtn_1}}"/></div>

                        {{--COLOR DEL FONDO (1)--}}
                        <div class="col-lg-4 text-default input-lg">{{trans("otros.info.color_fondo")}}</div> 
                        <div class="col-lg-8 input-lg">
                            <div class="colorSelector" id="colorSelector_{{App_Metro::colorFondoMenuBt_1}}"><div style="background-color:{{$colorFondoMenuBt_1}}"></div></div>
                        </div>
                         <input type="hidden" name="{{App_Metro::colorFondoMenuBt_1}}" id="{{App_Metro::colorFondoMenuBt_1}}" value="{{$colorFondoMenuBt_1}}" />

                        {{--COLOR DEL TEXTO (1)--}}
                        <div class="col-lg-4 text-default input-lg">{{trans("otros.info.color_texto")}}</div>
                        <div class="col-lg-8 input-lg">
                            <div class="colorSelector" id="colorSelector_{{App_Metro::txt_menuBtn_1_color}}"><div style="background-color:{{$txt_menuBtn_1_color}}"></div></div>
                        </div>

                        <input type="hidden" name="{{App_Metro::txt_menuBtn_1_color}}" id="{{App_Metro::txt_menuBtn_1_color}}" value="{{$txt_menuBtn_1_color}}" />

                        {{--ICONO DE LA OPCIÓN (1)--}}
                        <div class="col-lg-12 uploadIconMenu">
                            <a  href="#" class="tooltip-left" rel="tooltip" title="{{trans('otros.extensiones_permitidas')}}: png, jpeg. Max 500Kb."> 
                                <input name="{{App_Metro::iconoMenu1}}" id="{{App_Metro::iconoMenu1}}" class="iconoMenu" accept="image/*" type="file" multiple=true>
                            </a>
                        </div>
                    </div>
                </div>

            </div>


            {{--OPCION #2 (UNO) DEL MENU***************************************************--}}

            <div class="col-lg-12">

                <div class="panel panel-primary" style="clear: both;">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{trans("app.config.di.me.panel.titulo.segunda_opcion")}} <img width="20" src="{{URL::to("assets/img/icons/cuadricula-2.PNG")}}"/></h3>
                    </div>
                    <div class="panel-body">
                        
                        <div class="well well-sm">
                            {{trans("app.tipo.contenido.noticias.descripcion")}}
                         </div>
                        
                        <div class="col-lg-4 text-default input-lg">{{trans("otros.info.titulo")}}</div> <div class="col-lg-8"><input type="text" name="{{App_Metro::txt_menuBtn_2}}" id="{{App_Metro::txt_menuBtn_2}}" class="form-control input-lg" value="{{$txt_menuBtn_2}}"/></div>

                        {{--COLOR DEL FONDO (2)--}}
                        <div class="col-lg-4 text-default input-lg">{{trans("otros.info.color_fondo")}}</div>
                        <div class="col-lg-8 input-lg">
                            <div class="colorSelector" id="colorSelector_{{App_Metro::colorFondoMenuBt_2}}"><div style="background-color:{{$colorFondoMenuBt_2}}"></div></div>
                        </div>
                         <input type="hidden" name="{{App_Metro::colorFondoMenuBt_2}}" id="{{App_Metro::colorFondoMenuBt_2}}" value="{{$colorFondoMenuBt_2}}" />

                        {{--COLOR DEL TEXTO (2)--}}
                        <div class="col-lg-4 text-default input-lg">{{trans("otros.info.color_texto")}}</div>
                        <div class="col-lg-8 input-lg">
                            <div class="colorSelector" id="colorSelector_{{App_Metro::txt_menuBtn_2_color}}"><div style="background-color:{{$txt_menuBtn_2_color}}"></div></div>
                        </div>

                        <input type="hidden" name="{{App_Metro::txt_menuBtn_2_color}}" id="{{App_Metro::txt_menuBtn_2_color}}" value="{{$txt_menuBtn_2_color}}" />

                        {{--ICONO DE LA OPCIÓN (2)--}}
                        <div class="col-lg-12 uploadIconMenu">
                            <a  href="#" class="tooltip-left" rel="tooltip" title="{{trans('otros.extensiones_permitidas')}}: png, jpeg. Max 500Kb."> 
                                <input name="{{App_Metro::iconoMenu2}}" id="{{App_Metro::iconoMenu2}}" class="iconoMenu" accept="image/*" type="file" multiple=true>
                            </a>

                        </div>
                    </div>
                </div>

            </div>


            {{--OPCION #3 (UNO) DEL MENU***************************************************--}}

            <div class="col-lg-12">

                <div class="panel panel-primary" style="clear: both;">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{trans("app.config.di.me.panel.titulo.tercera_opcion")}} <img width="20" src="{{URL::to("assets/img/icons/cuadricula-3.PNG")}}"/></h3>
                    </div>
                    <div class="panel-body">
                        
                        <div class="well well-sm">
                            {{trans("app.tipo.contenido.encuestas.descripcion")}}
                         </div>
                        
                        <div class="col-lg-4 text-default input-lg">{{trans("otros.info.titulo")}}</div> <div class="col-lg-8"><input type="text" name="{{App_Metro::txt_menuBtn_3}}" id="{{App_Metro::txt_menuBtn_3}}" class="form-control input-lg" value="{{$txt_menuBtn_3}}"/></div>

                        {{--COLOR DEL FONDO (3)--}}
                        <div class="col-lg-4 text-default input-lg">{{trans("otros.info.color_fondo")}}</div> 
                         <div class="col-lg-8 input-lg">
                            <div class="colorSelector" id="colorSelector_{{App_Metro::colorFondoMenuBt_3}}"><div style="background-color:{{$colorFondoMenuBt_3}}"></div></div>
                        </div>
                         <input type="hidden" name="{{App_Metro::colorFondoMenuBt_3}}" id="{{App_Metro::colorFondoMenuBt_3}}" value="{{$colorFondoMenuBt_3}}" />

                        {{--COLOR DEL TEXTO (3)--}}
                        <div class="col-lg-4 text-default input-lg">{{trans("otros.info.color_texto")}}</div>
                        <div class="col-lg-8 input-lg">
                            <div class="colorSelector" id="colorSelector_{{App_Metro::txt_menuBtn_3_color}}"><div style="background-color:{{$txt_menuBtn_3_color}}"></div></div>
                        </div>

                        <input type="hidden" name="{{App_Metro::txt_menuBtn_3_color}}" id="{{App_Metro::txt_menuBtn_3_color}}" value="{{$txt_menuBtn_3_color}}" />

                        {{--ICONO DE LA OPCIÓN (3)--}}
                        <div class="col-lg-12 uploadIconMenu">
                            <a  href="#" class="tooltip-left" rel="tooltip" title="{{trans('otros.extensiones_permitidas')}}: png, jpeg. Max 500Kb."> 
                                <input name="{{App_Metro::iconoMenu3}}" id="{{App_Metro::iconoMenu3}}" class="iconoMenu" accept="image/*" type="file" multiple=true>
                            </a>

                        </div>
                    </div>
                </div>

            </div>


            {{--OPCION #4 (UNO) DEL MENU***************************************************--}}

            <div class="col-lg-12">

                <div class="panel panel-primary" style="clear: both;">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{trans("app.config.di.me.panel.titulo.cuarta_opcion")}} <img width="20" src="{{URL::to("assets/img/icons/cuadricula-4.PNG")}}"/></h3>
                    </div>
                    <div class="panel-body">
                        
                        <div class="well well-sm">
                            {{trans("app.tipo.contenido.pqr.descripcion")}}
                         </div>
                        
                        <div class="col-lg-4 text-default input-lg">{{trans("otros.info.titulo")}}</div> <div class="col-lg-8"><input type="text" name="{{App_Metro::txt_menuBtn_4}}" id="{{App_Metro::txt_menuBtn_4}}" class="form-control input-lg" value="{{$txt_menuBtn_4}}"/></div>

                        {{--COLOR DEL FONDO (4)--}}
                        <div class="col-lg-4 text-default input-lg">{{trans("otros.info.color_fondo")}}</div> 
                         <div class="col-lg-8 input-lg">
                            <div class="colorSelector" id="colorSelector_{{App_Metro::colorFondoMenuBt_4}}"><div style="background-color:{{$colorFondoMenuBt_4}}"></div></div>
                        </div>
                         <input type="hidden" name="{{App_Metro::colorFondoMenuBt_4}}" id="{{App_Metro::colorFondoMenuBt_4}}" value="{{$colorFondoMenuBt_4}}" />

                        {{--COLOR DEL TEXTO (4)--}}
                        <div class="col-lg-4 text-default input-lg">{{trans("otros.info.color_texto")}}</div>
                        <div class="col-lg-8 input-lg">
                            <div class="colorSelector" id="colorSelector_{{App_Metro::txt_menuBtn_4_color}}"><div style="background-color:{{$txt_menuBtn_4_color}}"></div></div>
                        </div>

                        <input type="hidden" name="{{App_Metro::txt_menuBtn_4_color}}" id="{{App_Metro::txt_menuBtn_4_color}}" value="{{$txt_menuBtn_4_color}}" />

                        {{--ICONO DE LA OPCIÓN (4)--}}
                        <div class="col-lg-12 uploadIconMenu">
                            <a  href="#" class="tooltip-left" rel="tooltip" title="{{trans('otros.extensiones_permitidas')}}: png, jpeg. Max 500Kb."> 
                                <input name="{{App_Metro::iconoMenu4}}" id="{{App_Metro::iconoMenu4}}" class="iconoMenu" accept="image/*" type="file" multiple=true>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-12 text-center" style="margin-bottom:20px;"> {{ Form::button("<span class='glyphicon glyphicon-star'></span> ".trans("app.config.info.btn.establecer_apariencia"), array('type' => 'button', 'class' => 'btn btn-success btn-large',"style"=>"font-size:20px;","id"=>"btn-guardar")) }}    </div>
    </form>

    @else

    @include("usuarios/tipo/regular/app/construccion/disenos/".$app->diseno."/apariencia-lock")

    @endif

</div>

@stop 



@section("script")
{{ HTML::script('assets/js/bootstrap-filestyle.min.js') }}
{{ HTML::script('assets/js/bootstrap-tooltip.js') }}
{{ HTML::script('assets/plugins/colorpicket/js/colorpicker.js') }}
{{ HTML::script('assets/plugins/plunk/jquery.simplecolorpicker.js') }}
{{ HTML::script('assets/plugins/fileinput/js/fileinput.js') }}


<script>


    jQuery(document).ready(function () {

        jQuery(".tooltip-left").tooltip({placement: "left"});
        jQuery(".tooltip-top").tooltip({placement: "top"});



        //ACTIVA EL SELECTOR DE COLOR PARA EL COLOR DEL TEXTO
        jQuery('.colorSelector').each(function () {

            var idSelector = jQuery(this).attr("id");

            jQuery(this).ColorPicker({
                color: '#ffffff',
                onShow: function (colpkr) {
                    $(colpkr).fadeIn(500);
                    return false;
                },
                onHide: function (colpkr) {
                    $(colpkr).fadeOut(500);
                    return false;
                },
                onChange: function (hsb, hex, rgb) {
                    $('#' + idSelector + ' div').css('backgroundColor', '#' + hex);
                    var id = idSelector.replace("colorSelector_", "");
                        jQuery("#" + id + "").val("rgb(" + rgb.r + "," + rgb.g + "," + rgb.b + ")");
                },
                onSubmit: function (hsb, hex, rgb, el) {
                   $('#' + idSelector + ' div').css('backgroundColor', '#' + hex);
                    var id = idSelector.replace("colorSelector_", "");
                        jQuery("#" + id + "").val("rgb(" + rgb.r + "," + rgb.g + "," + rgb.b + ")");
                }
            });

        });


        //ACTIVA EL SELECTOR DE COLOR PARA EL COLOR DEL FONDO
        $('.colorSelect').simplecolorpicker({picker: true, theme: 'glyphicons'});


      jQuery("#logoApp").fileinput({
            multiple: false,
            showPreview: true,
            showRemove: true,
            showUpload: false,
            initialPreview: <?php echo(!is_null($logo)) ? "\"<img src='" . $logo . "' class='file-preview-image'/>\"" : "false"; ?>,
            maxFileCount: 1,
            previewFileType: "image",
            allowedFileExtensions: ['jpg', 'png'],
            browseLabel: "{{trans('otros.info.seleccionar')}} {{trans('app.config.info.logo_app')}}",
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
            uploadUrl: "{{URL::to('aplicacion/ajax/guardarLogo')}}" // your upload server url
        });


//CONTROLADOR DE LOS ICONOS DEL MENU PRINCIPAL
<?php foreach ($iconosMenuID as $ID): $configIcono = ConfiguracionApp::existeConfig($ID); ?>

            jQuery("#<?php print($ID); ?>").fileinput({
                multiple: false,
                showPreview: true,
                showRemove: true,
                showUpload: false,
                initialPreview: <?php echo($configIcono && !ConfiguracionApp::esPredeterminado($ID)) ? "\"<img src='" . ConfiguracionApp::obtenerValorConfig($ID) . "' class='file-preview-image'/>\"" : "false"; ?>,
                maxFileCount: 1,
                previewFileType: "image",
                allowedFileExtensions: ['jpg', 'png'],
                browseLabel: "{{trans('otros.info.seleccionar')}} {{trans('otros.info.icono')}}",
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
                uploadUrl: "{{URL::to('aplicacion/ajax/guardarIconoMenu')}}" // your upload server url
            });

<?php endforeach; ?>

    });

    //Cuando se borra la imagen
    $('.iconoMenu').on('fileclear', function (event) {
        var idIcono = jQuery(this).attr("id");

        $.ajax({
            type: "POST",
            url: "{{URL::to('aplicacion/ajax/eliminarIconoMenu')}}",
            data: {idIcono: idIcono},
            success: function (response) {

            }

        }, "json");

    });

    //Sube la imagen una vez seleccionada
    $('.iconoMenu').on('fileimageloaded', function (event, previewId) {
        $('.iconoMenu').fileinput('upload');
    });


    $('#logoApp').on('fileclear', function (event) {

        $.ajax({
            type: "POST",
            url: "{{URL::to('aplicacion/ajax/eliminarLogo')}}",
            data: {},
            success: function (response) {

            }

        }, "json");
    });


    //Sube la imagen una vez seleccionada
    $('#logoApp').on('fileimageloaded', function (event, previewId) {
        $("#logoApp").fileinput('upload');
    });


    jQuery("#btn-guardar").click(function () {

        jQuery(this).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.procesando')}}...");
        jQuery(this).attr("disabled", "disabled");

        setTimeout(function () {
            $("#form").submit();
        }, 2500);
    });


</script>
@stop
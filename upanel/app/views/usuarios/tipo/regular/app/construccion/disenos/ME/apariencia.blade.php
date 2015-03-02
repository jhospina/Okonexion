<?php
$logo = $app->url_logo;


//ID de las claves de las configuraciones de los iconos del menu principal
$iconosMenuID = array(App_Metro::iconoMenu1, App_Metro::iconoMenu2, App_Metro::iconoMenu3, App_Metro::iconoMenu4);

//Color de barra de la app
if (ConfiguracionApp::existeConfig(App_Metro::colorBarraApp))
    $colorBarraApp = ConfiguracionApp::obtenerValorConfig(App_Metro::colorBarraApp);
else
    $colorBarraApp = "#808080";

//MOSTRAR NOMBRE
if (ConfiguracionApp::existeConfig(App_Metro::mostrarNombre))
    $mostrarNombre = ConfiguracionApp::obtenerValorConfig(App_Metro::mostrarNombre);
else
    $mostrarNombre = "soloTexto";

//ALINEACION NOMBRE
if (ConfiguracionApp::existeConfig(App_Metro::alineacionNombre))
    $alineacionNombre = ConfiguracionApp::obtenerValorConfig(App_Metro::alineacionNombre);
else
    $alineacionNombre = "izquierda";

//Color del texto del nombre de la APP
if (ConfiguracionApp::existeConfig(App_Metro::colorNombreApp))
    $colorNombreApp = ConfiguracionApp::obtenerValorConfig(App_Metro::colorNombreApp);
else
    $colorNombreApp = "rgb(0,0,0)";


//Texto del boton del menu  #1
if (ConfiguracionApp::existeConfig(App_Metro::txt_menuBtn_1))
    $txt_menuBtn_1 = ConfiguracionApp::obtenerValorConfig(App_Metro::txt_menuBtn_1);
else
    $txt_menuBtn_1 = "Institucional";

//Texto del boton del menu  #2
if (ConfiguracionApp::existeConfig(App_Metro::txt_menuBtn_2))
    $txt_menuBtn_2 = ConfiguracionApp::obtenerValorConfig(App_Metro::txt_menuBtn_2);
else
    $txt_menuBtn_2 = "Noticias";

//Texto del boton del menu  #3
if (ConfiguracionApp::existeConfig(App_Metro::txt_menuBtn_3))
    $txt_menuBtn_3 = ConfiguracionApp::obtenerValorConfig(App_Metro::txt_menuBtn_3);
else
    $txt_menuBtn_3 = "Encuestas";

//Texto del boton del menu  #4
if (ConfiguracionApp::existeConfig(App_Metro::txt_menuBtn_4))
    $txt_menuBtn_4 = ConfiguracionApp::obtenerValorConfig(App_Metro::txt_menuBtn_4);
else
    $txt_menuBtn_4 = "PQR";


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

@section("titulo")Mi aplicación @stop


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

    <h2 class="text-right">APARIENCIA</h2>

    <hr/>

    @include("interfaz/mensaje/index",array("id_mensaje"=>3))


    @if($app->estado==Aplicacion::ESTADO_LISTA_PARA_ENVIAR || $app->estado==Aplicacion::ESTADO_EN_DISENO || $app->estado==Aplicacion::ESTADO_TERMINADA)

    <form action="" method="POST" enctype="multipart/form-data" id="form">

        {{--**************************************************************************--}}
        {{--OPCIONES GENERALES***********************************************--}}
        {{--**************************************************************************--}}

        <div class="block">

            <h3 class="text-right col-lg-12">General</h3>
            <div class="col-lg-12">

                @include("usuarios/tipo/regular/app/construccion/secciones/logoApp")


                <div class="panel panel-primary" style="clear: both;">
                    <div class="panel-heading">
                        <h3 class="panel-title">Barra de aplicación</h3>
                    </div>
                    <div class="panel-body">
                        {{--COLOR DEL FONDO DE LA BARRA (1)--}}
                        <div class="col-lg-4 text-default input-lg">Color de la barra @include("interfaz/util/tooltip-ayuda",array("descripcion"=>"Selecciona el color barra que aparacera en la parte superior de la pantalla de tu aplicación."))  </div>
                        <div class="col-lg-8 input-lg">
                            @include("interfaz/app/select_colores",array("name"=>"colorBarraApp","class"=>"colorSelect","colorDefecto"=>$colorBarraApp))
                        </div>

                        {{--PROPIEDADES DEL NOMBRE--}}
                        <div class="col-lg-4 input-lg">
                            Mostrar nombre @include("interfaz/util/tooltip-ayuda",array("descripcion"=>"Indica como quieres que se muestre el nombre de tu aplicación en la barra superior de la pantalla.")) 
                        </div>
                        <div class="col-lg-3 input-lg"><input type="radio" name="{{App_Metro::mostrarNombre}}" value="soloTexto" class="radio-inline" @if($mostrarNombre=="soloTexto") checked @endif> <span class="radio-value">Solo Texto</span></div>
                        <div class="col-lg-3 input-lg"><input type="radio" name="{{App_Metro::mostrarNombre}}" value="textoLogo" class="radio-inline" @if($mostrarNombre=="textoLogo") checked @endif> <span class="radio-value">Texto y Logo</span></div>
                        <div class="col-lg-2 input-lg"><input type="radio" name="{{App_Metro::mostrarNombre}}" value="soloLogo" class="radio-inline" @if($mostrarNombre=="soloLogo") checked @endif> <span class="radio-value">Solo Logo</span></div>


                        {{--ALINEACIÓN--}}
                        <div class="col-lg-4 input-lg">
                            Alineación del nombre @include("interfaz/util/tooltip-ayuda",array("descripcion"=>"Indica la alineación en donde se posicionara el nombre de tu aplicaciòn en la barra superior de la pantalla.")) 
                        </div>
                        <div class="col-lg-3 input-lg"><input type="radio" name="{{App_Metro::alineacionNombre}}" value="izquierda" class="radio-inline" @if($alineacionNombre=="izquierda") checked @endif> <span class="radio-value">Izquierda</span></div>
                        <div class="col-lg-3 input-lg"><input type="radio" name="{{App_Metro::alineacionNombre}}" value="centro" class="radio-inline" @if($alineacionNombre=="centro") checked @endif> <span class="radio-value">Centro</span></div>
                        <div class="col-lg-2 input-lg"><input type="radio" name="{{App_Metro::alineacionNombre}}" value="derecha" class="radio-inline" @if($alineacionNombre=="derecha") checked @endif> <span class="radio-value">Derecha</span></div>


                        {{--COLOR DEL NOMBRE DE LA APLICACIÒN--}}
                        <div class="col-lg-4 text-default input-lg">Color del nombre @include("interfaz/util/tooltip-ayuda",array("descripcion"=>"Selecciona el color del texto que tendra el nombre de la aplicación en la barra superior de la pantalla.")) </div>
                        <div class="col-lg-8 input-lg">
                            <div class="colorSelector" id="colorNombreAppSelector"><div style="background-color:{{$colorNombreApp}}"></div></div>
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

            <h3 class="text-right col-lg-12">Menú principal</h3>

            {{--OPCION #1 (UNO) DEL MENU***************************************************--}}

            <div class="col-lg-12">

                <div class="panel panel-primary" style="clear: both;">
                    <div class="panel-heading">
                        <h3 class="panel-title">Primera Opción</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-4 text-default input-lg">Titulo</div> <div class="col-lg-8"><input type="text" name="{{App_Metro::txt_menuBtn_1}}" id="{{App_Metro::txt_menuBtn_1}}" class="form-control input-lg" value="{{$txt_menuBtn_1}}"/></div>

                        {{--COLOR DEL FONDO (1)--}}
                        <div class="col-lg-4 text-default input-lg">Color de fondo</div> 
                        <div class="col-lg-8 input-lg">
                            @include("interfaz/app/select_colores",array("name"=>App_Metro::colorFondoMenuBt_1,"class"=>"colorSelect","colorDefecto"=>$colorFondoMenuBt_1))
                        </div>

                        {{--COLOR DEL TEXTO (1)--}}
                        <div class="col-lg-4 text-default input-lg">Color del texto</div>
                        <div class="col-lg-8 input-lg">
                            <div class="colorSelector" id="colorSelector_txtMenu1"><div style="background-color:{{$txt_menuBtn_1_color}}"></div></div>
                        </div>

                        <input type="hidden" name="{{App_Metro::txt_menuBtn_1_color}}" id="{{App_Metro::txt_menuBtn_1_color}}" value="{{$txt_menuBtn_1_color}}" />

                        {{--ICONO DE LA OPCIÓN (1)--}}
                        <div class="col-lg-12 uploadIconMenu">
                            <a  href="#" class="tooltip-left" rel="tooltip" title="Extensiones permitidas: png, jpeg. Max 500Kb."> 
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
                        <h3 class="panel-title">Primera Opción</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-4 text-default input-lg">Titulo</div> <div class="col-lg-8"><input type="text" name="{{App_Metro::txt_menuBtn_2}}" id="{{App_Metro::txt_menuBtn_2}}" class="form-control input-lg" value="{{$txt_menuBtn_2}}"/></div>

                        {{--COLOR DEL FONDO (1)--}}
                        <div class="col-lg-4 text-default input-lg">Color de fondo</div> 
                        <div class="col-lg-8 input-lg">
                            @include("interfaz/app/select_colores",array("name"=>App_Metro::colorFondoMenuBt_2,"class"=>"colorSelect","colorDefecto"=>$colorFondoMenuBt_2))
                        </div>

                        {{--COLOR DEL TEXTO (2)--}}
                        <div class="col-lg-4 text-default input-lg">Color del texto</div>
                        <div class="col-lg-8 input-lg">
                            <div class="colorSelector" id="colorSelector_txtMenu2"><div style="background-color:{{$txt_menuBtn_2_color}}"></div></div>
                        </div>

                        <input type="hidden" name="{{App_Metro::txt_menuBtn_2_color}}" id="{{App_Metro::txt_menuBtn_2_color}}" value="{{$txt_menuBtn_2_color}}" />

                        {{--ICONO DE LA OPCIÓN (2)--}}
                        <div class="col-lg-12 uploadIconMenu">
                            <a  href="#" class="tooltip-left" rel="tooltip" title="Extensiones permitidas: png, jpeg. Max 500Kb."> 
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
                        <h3 class="panel-title">Primera Opción</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-4 text-default input-lg">Titulo</div> <div class="col-lg-8"><input type="text" name="{{App_Metro::txt_menuBtn_3}}" id="{{App_Metro::txt_menuBtn_3}}" class="form-control input-lg" value="{{$txt_menuBtn_3}}"/></div>

                        {{--COLOR DEL FONDO (3)--}}
                        <div class="col-lg-4 text-default input-lg">Color de fondo</div> 
                        <div class="col-lg-8 input-lg">
                            @include("interfaz/app/select_colores",array("name"=>App_Metro::colorFondoMenuBt_3,"class"=>"colorSelect","colorDefecto"=>$colorFondoMenuBt_3))
                        </div>

                        {{--COLOR DEL TEXTO (3)--}}
                        <div class="col-lg-4 text-default input-lg">Color del texto</div>
                        <div class="col-lg-8 input-lg">
                            <div class="colorSelector" id="colorSelector_txtMenu3"><div style="background-color:{{$txt_menuBtn_3_color}}"></div></div>
                        </div>

                        <input type="hidden" name="{{App_Metro::txt_menuBtn_3_color}}" id="{{App_Metro::txt_menuBtn_3_color}}" value="{{$txt_menuBtn_3_color}}" />

                        {{--ICONO DE LA OPCIÓN (3)--}}
                        <div class="col-lg-12 uploadIconMenu">
                            <a  href="#" class="tooltip-left" rel="tooltip" title="Extensiones permitidas: png, jpeg. Max 500Kb."> 
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
                        <h3 class="panel-title">Primera Opción</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-4 text-default input-lg">Titulo</div> <div class="col-lg-8"><input type="text" name="{{App_Metro::txt_menuBtn_4}}" id="{{App_Metro::txt_menuBtn_4}}" class="form-control input-lg" value="{{$txt_menuBtn_4}}"/></div>

                        {{--COLOR DEL FONDO (4)--}}
                        <div class="col-lg-4 text-default input-lg">Color de fondo</div> 
                        <div class="col-lg-8 input-lg">
                            @include("interfaz/app/select_colores",array("name"=>App_Metro::colorFondoMenuBt_4,"class"=>"colorSelect","colorDefecto"=>$colorFondoMenuBt_4))
                        </div>

                        {{--COLOR DEL TEXTO (4)--}}
                        <div class="col-lg-4 text-default input-lg">Color del texto</div>
                        <div class="col-lg-8 input-lg">
                            <div class="colorSelector" id="colorSelector_txtMenu4"><div style="background-color:{{$txt_menuBtn_4_color}}"></div></div>
                        </div>

                        <input type="hidden" name="{{App_Metro::txt_menuBtn_4_color}}" id="{{App_Metro::txt_menuBtn_4_color}}" value="{{$txt_menuBtn_4_color}}" />

                        {{--ICONO DE LA OPCIÓN (4)--}}
                        <div class="col-lg-12 uploadIconMenu">
                            <a  href="#" class="tooltip-left" rel="tooltip" title="Extensiones permitidas: png, jpeg. Max 500Kb."> 
                                <input name="{{App_Metro::iconoMenu4}}" id="{{App_Metro::iconoMenu4}}" class="iconoMenu" accept="image/*" type="file" multiple=true>
                            </a>

                        </div>
                    </div>
                </div>

            </div>





            <div class="col-lg-12 text-center" style="margin-bottom:20px;"> {{ Form::button("<span class='glyphicon glyphicon-star'></span> Establecer apariencia", array('type' => 'button', 'class' => 'btn btn-success btn-large',"style"=>"font-size:20px;","id"=>"btn-guardar")) }}    </div>

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
                    var id = idSelector.replace("colorSelector_txtMenu", "");

                    if (isNaN(parseInt(id)))
                        jQuery("#" + idSelector.replace("Selector", "")).val("rgb(" + rgb.r + "," + rgb.g + "," + rgb.b + ")");
                    else
                        jQuery("#txt_menuBtn_" + id + "_color").val("rgb(" + rgb.r + "," + rgb.g + "," + rgb.b + ")");
                },
                onSubmit: function (hsb, hex, rgb, el) {
                    var id = idSelector.replace("colorSelector_txtMenu", "");
                    if (isNaN(parseInt(id)))
                        jQuery("#" + idSelector.replace("Selector", "")).val("rgb(" + rgb.r + "," + rgb.g + "," + rgb.b + ")");
                    else
                        jQuery("#txt_menuBtn_" + id + "_color").val("rgb(" + rgb.r + "," + rgb.g + "," + rgb.b + ")");
                }
            });

        });


        //ACTIVA EL SELECTOR DE COLOR PARA EL COLOR DEL FONDO
        $('.colorSelect').simplecolorpicker({picker: true, theme: 'glyphicons'});



        jQuery("#logoApp").fileinput({
            multiple: false,
            showPreview: true,
            showRemove: true,
            showUpload:<?php echo(!is_null($logo)) ? "false" : "true" ?>,
            initialPreview: <?php echo(!is_null($logo)) ? "\"<img src='" . $logo . "' class='file-preview-image'/>\"" : "false"; ?>,
            maxFileCount: 1,
            previewFileType: "image",
            allowedFileExtensions: ['jpg', 'png'],
            browseLabel: "Seleccionar Logo App",
            browseIcon: '<i class="glyphicon glyphicon-picture"></i> ',
            removeClass: "btn btn-danger",
            removeLabel: "Borrar",
            removeIcon: '<i class="glyphicon glyphicon-trash"></i> ',
            uploadClass: "btn btn-info",
            uploadLabel: "Subir",
            dropZoneEnabled: false,
            dropZoneTitle: "Arrastra tu imagen aquí...",
            uploadIcon: '<i class="glyphicon glyphicon-upload"></i> ',
            msgSelected: '{n} imagen',
            maxFileSize: 500,
            msgInvalidFileExtension: "El archivo que has escogido no es valido. Solo se permiten imagenes en formatos {extensions}.",
            msgInvalidFileType: "El archivo que has escogido no es valido. Solo se permiten imagenes en formatos {extensions}.",
            msgSizeTooLarge: "El tamaño de la imagen es demasiado grande. Maximo <b>{maxSize} KB</b>. Esta imagen pesa <b>{size} KB.</b>",
            uploadAsync: true,
            uploadUrl: "{{URL::to('aplicacion/ajax/guardarLogo')}}" // your upload server url
        });

//CONTROLADOR DE LOS ICONOS DEL MENU PRINCIPAL
<?php foreach ($iconosMenuID as $ID): $configIcono = ConfiguracionApp::existeConfig($ID); ?>

            jQuery("#<?php print($ID); ?>").fileinput({
                multiple: false,
                showPreview: true,
                showRemove: true,
                showUpload:<?php echo($configIcono && !ConfiguracionApp::esPredeterminado($ID)) ? "false" : "true" ?>,
                initialPreview: <?php echo($configIcono && !ConfiguracionApp::esPredeterminado($ID)) ? "\"<img src='" . ConfiguracionApp::obtenerValorConfig($ID) . "' class='file-preview-image'/>\"" : "false"; ?>,
                maxFileCount: 1,
                previewFileType: "image",
                allowedFileExtensions: ['jpg', 'png'],
                browseLabel: "Seleccionar Icono",
                browseIcon: '<i class="glyphicon glyphicon-picture"></i> ',
                removeClass: "btn btn-danger",
                removeLabel: "Borrar",
                removeIcon: '<i class="glyphicon glyphicon-trash"></i> ',
                uploadClass: "btn btn-info",
                uploadLabel: "Subir",
                dropZoneEnabled: false,
                dropZoneTitle: "Arrastra tu imagen aquí...",
                uploadIcon: '<i class="glyphicon glyphicon-upload"></i> ',
                msgSelected: '{n} imagen',
                maxFileSize: 500,
                msgInvalidFileExtension: "El archivo que has escogido no es valido. Solo se permiten imagenes en formatos {extensions}.",
                msgInvalidFileType: "El archivo que has escogido no es valido. Solo se permiten imagenes en formatos {extensions}.",
                msgSizeTooLarge: "El tamaño de la imagen es demasiado grande. Maximo <b>{maxSize} KB</b>. Esta imagen pesa <b>{size} KB.</b>",
                uploadAsync: true,
                uploadUrl: "{{URL::to('aplicacion/ajax/guardarIconoMenu')}}" // your upload server url
            });

<?php endforeach; ?>

    });


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


    $('#logoApp').on('fileclear', function (event) {

        $.ajax({
            type: "POST",
            url: "{{URL::to('aplicacion/ajax/eliminarLogo')}}",
            data: {},
            success: function (response) {

            }

        }, "json");
    });


    jQuery("#btn-guardar").click(function () {

        jQuery(this).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> Procesando...");
        jQuery(this).attr("disabled", "disabled");

        setTimeout(function () {
            $("#form").submit();
        }, 2500);
    });


</script>
@stop
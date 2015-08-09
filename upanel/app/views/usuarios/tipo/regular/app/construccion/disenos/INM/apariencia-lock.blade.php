{{--**************************************************************************--}}
{{--OPCIONES GENERALES***********************************************--}}
{{--**************************************************************************--}}

<div class="block">

    <h3 class="text-right col-lg-12">{{trans("app.config.info.titulo.general")}}</h3>
    <div class="col-lg-12">

        {{--LOGO DE LA APLICACION--}}
        @include("usuarios/tipo/regular/app/construccion/secciones/logoApp-lock")

        <div class="panel panel-primary" style="clear: both;">
            <div class="panel-heading">
                <h3 class="panel-title">{{trans("app.config.di.inm.panel.titulo.barra_aplicacion")}}</h3>
            </div>
            <div class="panel-body">
                {{--COLOR DEL FONDO DE LA BARRA (1)--}}
                <div class="col-lg-4 text-default input-lg">{{trans("app.config.di.inm.info.colorbarraapp")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans('app.config.di.inm.info.colorbarraapp.ayuda')))  </div>
                <div class="col-lg-8 input-lg" style="background:{{$colorBarraApp}};border: 1px black solid;">

                </div>

                {{--PROPIEDADES DEL NOMBRE--}}
                <div class="col-lg-4 input-lg">
                    {{trans("app.config.di.inm.info.mostrar_nombre")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans("app.config.di.inm.info.mostrar_nombre.ayuda"))) 
                </div>
                <div class="col-lg-8 input-lg"> 
                    @if($mostrarNombre=="soloTexto") <span class="radio-value">{{trans("app.config.di.inm.info.mostrar_nombre.op.solo_texto")}}</span> @endif
                    @if($mostrarNombre=="textoLogo") <span class="radio-value">{{trans("app.config.di.inm.info.mostrar_nombre.op.texto_logo")}}</span> @endif
                    @if($mostrarNombre=="soloLogo") <span class="radio-value">{{trans("app.config.di.inm.info.mostrar_nombre.op.solo_logo")}}</span> @endif
                </div>


                {{--ALINEACIÓN--}}
                <div class="col-lg-4 input-lg">
                    {{trans("app.config.di.inm.info.alineacion_nombre")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans("app.config.di.inm.info.alineacion_nombre.ayuda"))) 
                </div>
                <div class="col-lg-8 input-lg">
                    @if($alineacionNombre=="izquierda")  <span class="radio-value">{{trans("otros.info.izquierda")}}</span> @endif
                    @if($alineacionNombre=="centro")  <span class="radio-value">{{trans("otros.info.centro")}}</span> @endif
                    @if($alineacionNombre=="derecha")  <span class="radio-value">{{trans("otros.info.derecha")}}</span> @endif
                </div>


                {{--COLOR DEL NOMBRE DE LA APLICACIÒN--}}
                <div class="col-lg-4 text-default input-lg">{{trans("app.config.di.inm.info.color_nombre")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans("app.config.di.inm.info.color_nombre.ayuda"))) </div>
                <div class="col-lg-8 input-lg" style="background: {{$colorNombreApp}};border: 1px black solid;">

                </div>

            </div>
        </div>
    </div>
</div>



<div class="block">

    <div class="col-lg-12">
        <div class="panel panel-primary" style="clear: both;">
            <div class="panel-heading">
                <h3 class="panel-title">{{trans("app.config.di.panel.titulo.modulos")}}</h3>
            </div>
            <div class="panel-body">
                {{--INSTITUCIONAL--}}
                <div class="col-lg-1 input-lg">
                    <input disabled="disabled" type="checkbox" class="js-switch" data-for="{{AppDesing::modulo_institucional}}" {{HtmlControl::setCheck($modulo_institucional)}}> 
                </div>
                <div class="col-lg-11 input-lg">
                    {{$txt_menuBtn_1}}
                </div>
                {{--NOTICIAS--}}
                <div class="col-lg-1 input-lg">
                    <input disabled="disabled" type="checkbox" class="js-switch" data-for="{{AppDesing::modulo_noticias}}" {{HtmlControl::setCheck($modulo_noticias)}}> 
                </div>
                <div class="col-lg-11 input-lg">
                    {{$txt_menuBtn_2}}
                </div>
                {{--ENCUESTAS--}}
                <div class="col-lg-1 input-lg">
                    <input disabled="disabled" type="checkbox" class="js-switch" data-for="{{AppDesing::modulo_encuestas}}" {{HtmlControl::setCheck($modulo_encuestas)}}> 
                </div>
                <div class="col-lg-11 input-lg">
                    {{$txt_menuBtn_3}}
                </div>
                {{--PQR--}}
                <div class="col-lg-1 input-lg">
                    <input disabled="disabled" type="checkbox" class="js-switch" data-for="{{AppDesing::modulo_pqr}}" {{HtmlControl::setCheck($modulo_pqr)}}> 
                </div>
                <div class="col-lg-11 input-lg">
                    {{$txt_menuBtn_4}}
                </div>

            </div>
        </div>
    </div>
</div>




{{--**************************************************************************--}}
{{--OPCIONES DEL MENU PRINCIPAL***********************************************--}}
{{--**************************************************************************--}}




<div class="block">

    <h3 class="text-right col-lg-12">{{trans("app.config.info.titulo.personalizacion.modulos")}}</h3>

    {{--OPCION #1 (UNO) DEL MENU***************************************************--}}

    @if($modulo_institucional)

    <div class="col-lg-12">

        <div class="panel panel-primary" style="clear: both;">
            <div class="panel-heading">
                <h3 class="panel-title">{{trans("app.config.di.modulo")}} {{$txt_menuBtn_1}} <img width="20" src="{{URL::to("assets/img/icons/cuadricula-1.PNG")}}"/></h3>
            </div>
            <div class="panel-body">
                <div class="col-lg-4 text-default input-lg">{{trans("otros.info.titulo")}}</div> <div class="col-lg-8 input-lg">{{$txt_menuBtn_1}}</div>

                {{--COLOR DEL FONDO (1)--}}
                <div class="col-lg-4 text-default input-lg">{{trans("otros.info.color_fondo")}}</div> 
                <div class="col-lg-8 input-lg" style="border: 1px black solid;background:{{$colorFondoMenuBt_1}};"></div>

                {{--COLOR DEL TEXTO (1)--}}
                <div class="col-lg-4 text-default input-lg">{{trans("otros.info.color_texto")}}</div>
                <div class="col-lg-8 input-lg" style="background: {{$txt_menuBtn_1_color}};border:1px black solid;">

                </div>

                <input type="hidden" name="txt_menuBtn_1_color" id="txt_menuBtn_1_color" value="{{$txt_menuBtn_1_color}}" />

                {{--ICONO DE LA OPCIÓN (1)--}}
                <div class="col-lg-12 uploadIconMenu">
                    <?php echo(!ConfiguracionApp::esPredeterminado("iconoMenu1")) ? "\"<img src='" . ConfiguracionApp::obtenerValorConfig("iconoMenu1") . "' class='file-preview-image'/>\"" : "<h3>" . trans("app.config.info.icono_predeterminado") . "</h3>"; ?>
                </div>
            </div>
        </div>

    </div>

    @endif


    {{--OPCION #2 (DOS) DEL MENU***************************************************--}}

    @if($modulo_noticias)
    <div class="col-lg-12">

        <div class="panel panel-primary" style="clear: both;">
            <div class="panel-heading">
                <h3 class="panel-title">{{trans("app.config.di.modulo")}} {{$txt_menuBtn_2}} <img width="20" src="{{URL::to("assets/img/icons/cuadricula-2.PNG")}}"/></h3>
            </div>
            <div class="panel-body">
                <div class="col-lg-4 text-default input-lg">{{trans("otros.info.titulo")}}</div> <div class="col-lg-8 input-lg">{{$txt_menuBtn_2}}</div>

                {{--COLOR DEL FONDO (2)--}}
                <div class="col-lg-4 text-default input-lg">{{trans("otros.info.color_fondo")}}</div> 
                <div class="col-lg-8 input-lg" style="border: 1px black solid;background:{{$colorFondoMenuBt_2}};"></div>

                {{--COLOR DEL TEXTO (2)--}}
                <div class="col-lg-4 text-default input-lg">{{trans("otros.info.color_texto")}}</div>
                <div class="col-lg-8 input-lg" style="background: {{$txt_menuBtn_2_color}};border:1px black solid;">

                </div>

                <input type="hidden" name="txt_menuBtn_2_color" id="txt_menuBtn_2_color" value="{{$txt_menuBtn_2_color}}" />

                {{--ICONO DE LA OPCIÓN (2)--}}
                <div class="col-lg-12 uploadIconMenu">
                    <?php echo(!ConfiguracionApp::esPredeterminado("iconoMenu2")) ? "\"<img src='" . ConfiguracionApp::obtenerValorConfig("iconoMenu2") . "' class='file-preview-image'/>\"" : "<h3>" . trans("app.config.info.icono_predeterminado") . "</h3>"; ?>
                </div>
            </div>
        </div>

    </div>

    @endif

    {{--OPCION #3 (TRES) DEL MENU***************************************************--}}

    @if($modulo_encuestas)
    <div class="col-lg-12">

        <div class="panel panel-primary" style="clear: both;">
            <div class="panel-heading">
                <h3 class="panel-title">{{trans("app.config.di.modulo")}} {{$txt_menuBtn_3}} <img width="20" src="{{URL::to("assets/img/icons/cuadricula-3.PNG")}}"/></h3>
            </div>
            <div class="panel-body">
                <div class="col-lg-4 text-default input-lg">{{trans("otros.info.titulo")}}</div> <div class="col-lg-8 input-lg">{{$txt_menuBtn_3}}</div>

                {{--COLOR DEL FONDO (3)--}}
                <div class="col-lg-4 text-default input-lg">{{trans("otros.info.color_fondo")}}</div> 
                <div class="col-lg-8 input-lg" style="border: 1px black solid;background:{{$colorFondoMenuBt_3}};"></div>

                {{--COLOR DEL TEXTO (3)--}}
                <div class="col-lg-4 text-default input-lg">{{trans("otros.info.color_texto")}}</div>
                <div class="col-lg-8 input-lg" style="background: {{$txt_menuBtn_3_color}};border:1px black solid;">

                </div>

                <input type="hidden" name="txt_menuBtn_3_color" id="txt_menuBtn_3_color" value="{{$txt_menuBtn_3_color}}" />

                {{--ICONO DE LA OPCIÓN (3)--}}
                <div class="col-lg-12 uploadIconMenu">
                    <?php echo(!ConfiguracionApp::esPredeterminado("iconoMenu3")) ? "\"<img src='" . ConfiguracionApp::obtenerValorConfig("iconoMenu3") . "' class='file-preview-image'/>\"" : "<h3>" . trans("app.config.info.icono_predeterminado") . "</h3>"; ?>
                </div>
            </div>
        </div>

    </div>

    @endif

    {{--OPCION #4 (CUATRO) DEL MENU***************************************************--}}

    @if($modulo_pqr)
    <div class="col-lg-12">

        <div class="panel panel-primary" style="clear: both;">
            <div class="panel-heading">
                <h3 class="panel-title">{{trans("app.config.di.modulo")}} {{$txt_menuBtn_4}} <img width="20" src="{{URL::to("assets/img/icons/cuadricula-4.PNG")}}"/></h3>
            </div>
            <div class="panel-body">
                <div class="col-lg-4 text-default input-lg">{{trans("otros.info.titulo")}}</div> <div class="col-lg-8 input-lg">{{$txt_menuBtn_4}}</div>

                {{--COLOR DEL FONDO (4)--}}
                <div class="col-lg-4 text-default input-lg">{{trans("otros.info.color_fondo")}}</div> 
                <div class="col-lg-8 input-lg" style="border: 1px black solid;background:{{$colorFondoMenuBt_4}};"></div>

                {{--COLOR DEL TEXTO (4)--}}
                <div class="col-lg-4 text-default input-lg">{{trans("otros.info.color_texto")}}</div>
                <div class="col-lg-8 input-lg" style="background: {{$txt_menuBtn_4_color}};border:1px black solid;">

                </div>

                <input type="hidden" name="txt_menuBtn_4_color" id="txt_menuBtn_4_color" value="{{$txt_menuBtn_4_color}}" />

                {{--ICONO DE LA OPCIÓN (4)--}}
                <div class="col-lg-12 uploadIconMenu">
                    <?php echo(!ConfiguracionApp::esPredeterminado("iconoMenu4")) ? "\"<img src='" . ConfiguracionApp::obtenerValorConfig("iconoMenu4") . "' class='file-preview-image'/>\"" : "<h3>" . trans("app.config.info.icono_predeterminado") . "</h3>"; ?>
                </div>
            </div>
        </div>

    </div>

    @endif



</div>

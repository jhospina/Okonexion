{{--**************************************************************************--}}
{{--OPCIONES GENERALES***********************************************--}}
{{--**************************************************************************--}}

<div class="block">

    <h3 class="text-right col-lg-12">General</h3>
    <div class="col-lg-12">

        {{--LOGO DE LA APLICACION--}}
        @include("usuarios/tipo/regular/app/construccion/secciones/logoApp-lock")

        <div class="panel panel-primary" style="clear: both;">
            <div class="panel-heading">
                <h3 class="panel-title">Barra de aplicación</h3>
            </div>
            <div class="panel-body">
                {{--COLOR DEL FONDO DE LA BARRA (1)--}}
                <div class="col-lg-4 text-default input-lg">Color de la barra @include("interfaz/util/tooltip-ayuda",array("descripcion"=>"Selecciona el color barra que aparacera en la parte superior de la pantalla de tu aplicación."))  </div>
                <div class="col-lg-8 input-lg" style="background:{{$colorBarraApp}};border: 1px black solid;">

                </div>

                {{--PROPIEDADES DEL NOMBRE--}}
                <div class="col-lg-4 input-lg">
                    Mostrar nombre @include("interfaz/util/tooltip-ayuda",array("descripcion"=>"Indica como quieres que se muestre el nombre de tu aplicación en la barra superior de la pantalla.")) 
                </div>
                <div class="col-lg-8 input-lg"> 
                    @if($mostrarNombre=="soloTexto") <span class="radio-value">Solo Texto</span> @endif
                    @if($mostrarNombre=="textoLogo") <span class="radio-value">Texto y Logo</span> @endif
                    @if($mostrarNombre=="soloLogo") <span class="radio-value">Solo Logo</span> @endif
                </div>


                {{--ALINEACIÓN--}}
                <div class="col-lg-4 input-lg">
                    Alineación del nombre @include("interfaz/util/tooltip-ayuda",array("descripcion"=>"Indica la alineación en donde se posicionara el nombre de tu aplicaciòn en la barra superior de la pantalla.")) 
                </div>
                <div class="col-lg-8 input-lg">
                    @if($alineacionNombre=="izquierda")  <span class="radio-value">Izquierda</span> @endif
                    @if($alineacionNombre=="centro")  <span class="radio-value">Centro</span> @endif
                    @if($alineacionNombre=="derecha")  <span class="radio-value">Derecha</span> @endif
                </div>


                {{--COLOR DEL NOMBRE DE LA APLICACIÒN--}}
                <div class="col-lg-4 text-default input-lg">Color del nombre @include("interfaz/util/tooltip-ayuda",array("descripcion"=>"Selecciona el color del texto que tendra el nombre de la aplicación en la barra superior de la pantalla.")) </div>
                <div class="col-lg-8 input-lg" style="background: {{$colorNombreApp}};border: 1px black solid;">

                </div>

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
                <div class="col-lg-4 text-default input-lg">Titulo</div> <div class="col-lg-8 input-lg">{{$txt_menuBtn_1}}</div>

                {{--COLOR DEL FONDO (1)--}}
                <div class="col-lg-4 text-default input-lg">Color de fondo</div> 
                <div class="col-lg-8 input-lg" style="border: 1px black solid;background:{{$colorFondoMenuBt_1}};"></div>

                {{--COLOR DEL TEXTO (1)--}}
                <div class="col-lg-4 text-default input-lg">Color del texto</div>
                <div class="col-lg-8 input-lg" style="background: {{$txt_menuBtn_1_color}};border:1px black solid;">

                </div>

                <input type="hidden" name="txt_menuBtn_1_color" id="txt_menuBtn_1_color" value="{{$txt_menuBtn_1_color}}" />

                {{--ICONO DE LA OPCIÓN (1)--}}
                <div class="col-lg-12 uploadIconMenu">
                    <?php echo(!ConfiguracionApp::esPredeterminado("iconoMenu1")) ? "\"<img src='" . ConfiguracionApp::obtenerValorConfig("iconoMenu1") . "' class='file-preview-image'/>\"" : "<h3>Icono predeterminado</h3>"; ?>
                </div>
            </div>
        </div>

    </div>


    {{--OPCION #2 (DOS) DEL MENU***************************************************--}}

    <div class="col-lg-12">

        <div class="panel panel-primary" style="clear: both;">
            <div class="panel-heading">
                <h3 class="panel-title">segunda Opción</h3>
            </div>
            <div class="panel-body">
                <div class="col-lg-4 text-default input-lg">Titulo</div> <div class="col-lg-8 input-lg">{{$txt_menuBtn_2}}</div>

                {{--COLOR DEL FONDO (2)--}}
                <div class="col-lg-4 text-default input-lg">Color de fondo</div> 
                <div class="col-lg-8 input-lg" style="border: 1px black solid;background:{{$colorFondoMenuBt_2}};"></div>

                {{--COLOR DEL TEXTO (2)--}}
                <div class="col-lg-4 text-default input-lg">Color del texto</div>
                <div class="col-lg-8 input-lg" style="background: {{$txt_menuBtn_2_color}};border:1px black solid;">

                </div>

                <input type="hidden" name="txt_menuBtn_2_color" id="txt_menuBtn_2_color" value="{{$txt_menuBtn_2_color}}" />

                {{--ICONO DE LA OPCIÓN (2)--}}
                <div class="col-lg-12 uploadIconMenu">
                    <?php echo(!ConfiguracionApp::esPredeterminado("iconoMenu2")) ? "\"<img src='" . ConfiguracionApp::obtenerValorConfig("iconoMenu2") . "' class='file-preview-image'/>\"" : "<h3>Icono predeterminado</h3>"; ?>
                </div>
            </div>
        </div>

    </div>

    {{--OPCION #3 (TRES) DEL MENU***************************************************--}}

    <div class="col-lg-12">

        <div class="panel panel-primary" style="clear: both;">
            <div class="panel-heading">
                <h3 class="panel-title">Tercera Opción</h3>
            </div>
            <div class="panel-body">
                <div class="col-lg-4 text-default input-lg">Titulo</div> <div class="col-lg-8 input-lg">{{$txt_menuBtn_3}}</div>

                {{--COLOR DEL FONDO (3)--}}
                <div class="col-lg-4 text-default input-lg">Color de fondo</div> 
                <div class="col-lg-8 input-lg" style="border: 1px black solid;background:{{$colorFondoMenuBt_3}};"></div>

                {{--COLOR DEL TEXTO (3)--}}
                <div class="col-lg-4 text-default input-lg">Color del texto</div>
                <div class="col-lg-8 input-lg" style="background: {{$txt_menuBtn_3_color}};border:1px black solid;">

                </div>

                <input type="hidden" name="txt_menuBtn_3_color" id="txt_menuBtn_3_color" value="{{$txt_menuBtn_3_color}}" />

                {{--ICONO DE LA OPCIÓN (3)--}}
                <div class="col-lg-12 uploadIconMenu">
                    <?php echo(!ConfiguracionApp::esPredeterminado("iconoMenu3")) ? "\"<img src='" . ConfiguracionApp::obtenerValorConfig("iconoMenu3") . "' class='file-preview-image'/>\"" : "<h3>Icono predeterminado</h3>"; ?>
                </div>
            </div>
        </div>

    </div>

    {{--OPCION #4 (CUATRO) DEL MENU***************************************************--}}

    <div class="col-lg-12">

        <div class="panel panel-primary" style="clear: both;">
            <div class="panel-heading">
                <h3 class="panel-title">Cuarta Opción</h3>
            </div>
            <div class="panel-body">
                <div class="col-lg-4 text-default input-lg">Titulo</div> <div class="col-lg-8 input-lg">{{$txt_menuBtn_4}}</div>

                {{--COLOR DEL FONDO (4)--}}
                <div class="col-lg-4 text-default input-lg">Color de fondo</div> 
                <div class="col-lg-8 input-lg" style="border: 1px black solid;background:{{$colorFondoMenuBt_4}};"></div>

                {{--COLOR DEL TEXTO (4)--}}
                <div class="col-lg-4 text-default input-lg">Color del texto</div>
                <div class="col-lg-8 input-lg" style="background: {{$txt_menuBtn_4_color}};border:1px black solid;">

                </div>

                <input type="hidden" name="txt_menuBtn_4_color" id="txt_menuBtn_4_color" value="{{$txt_menuBtn_4_color}}" />

                {{--ICONO DE LA OPCIÓN (4)--}}
                <div class="col-lg-12 uploadIconMenu">
                    <?php echo(!ConfiguracionApp::esPredeterminado("iconoMenu4")) ? "\"<img src='" . ConfiguracionApp::obtenerValorConfig("iconoMenu4") . "' class='file-preview-image'/>\"" : "<h3>Icono predeterminado</h3>"; ?>
                </div>
            </div>
        </div>

    </div>



</div>

<?php
if (is_null($logoPlat = Instancia::obtenerValorMetadato(ConfigInstancia::visual_logo))) {
    $logoPlat = URL::to("/assets/img/logo.png");
    $esLogoPlat = true;
} else {
    $esLogoPlat = false;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>@yield('titulo', 'UPanel - Appthergo') @include("interfaz/titulo-pagina")</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {{-- Bootstrap --}}
        {{ HTML::style('assets/plugins/bootstrap/css/bootstrap.css', array('media' => 'screen')) }}
        {{ HTML::style('assets/plugins/bootstrap/css/bootstrap-theme.css', array('media' => 'screen')) }}
        {{ HTML::style('assets/plugins/bootstrap-submenu/css/bootstrap-submenu.css', array('media' => 'screen')) }}

        {{--CSS PERSONALIZADO--}}
        {{ HTML::style('assets/css/upanel/plantilla.css', array('media' => 'screen')) }}

        @yield('css')
        @yield('css2')

        {{-- jQuery (necessary for Bootstraps JavaScript plugins) --}}
        {{ HTML::script('assets/js/jquery.js') }}
        {{ HTML::script('assets/js/jquery-1.10.2.js') }}


        {{-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries --}}
        <!--[if lt IE 9]>
            {{ HTML::script('assets/js/html5shiv.js') }}
            {{ HTML::script('assets/js/respond.min.js') }}
        <![endif]-->

        <link rel="shortcut icon" href="{{URL::to("/assets/img/favicon.png")}}">

    </head>
    <body>

        {{--MENSAJES EMERGENTES PARA EL USUARIO--}}
        @include("interfaz/mensaje/global")

        <nav class="navbar navbar-inverse" id="menu">
            <div class="container-fluid container">
                <div class="navbar-header" style="width: 170px;">
                    <a class="navbar-brand" href="{{URL::to("/")}}">
                        <img class="img-rounded" style='width: 150px;height:25px;' id="logo-okonexion" src="{{$logoPlat}}"/>
                    </a>
                    @if(!$esLogoPlat)
                    <img id='img-powered' src='{{URL::to("/assets/img/powered.png")}}'/>
                    @endif
                </div>
                <div>
                    <ul class="nav navbar-nav">
                        <li @if(Request::is('/')) class="active" @endif ><a href="{{URL::to("/")}}"><span class="glyphicon glyphicon-home"></span></a></li>
                        {{--Define el contenido del menu dependiendo del tipo de usuario--}} 

                        {{--MENU PRINCIPAL--}}
                        @include("interfaz/menu/".Auth::user()->tipo."-principal")


                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        @include("interfaz/menu/secciones/notificaciones")
                        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> {{Auth::user()->nombres}}<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                {{--SELECCION DE IDIOMA--}}
                                <li><div id="seleccion-idioma">
                                        <span data-lang="{{Idioma::LANG_ES}}" @if(OP_IDIOMA==Idioma::LANG_ES)class="select"@endif><img src="{{URL::to(Idioma::PATH_ICON.Idioma::LANG_ES.".png")}}"/></span>
                                        <span data-lang="{{Idioma::LANG_EN}}" @if(OP_IDIOMA==Idioma::LANG_EN)class="select"@endif><img src="{{URL::to(Idioma::PATH_ICON.Idioma::LANG_EN.".png")}}"/></span></div>
                                </li>
                                <li><a href="{{Route("usuario.index")}}"><span class="glyphicon glyphicon-user"></span> {{trans("interfaz.menu.usuario.mi_perfil")}}</a></li>
                                <li><a href="{{URL::to("cambiar-contrasena")}}"><span class="glyphicon glyphicon-lock"></span> {{trans("interfaz.menu.usuario.cambiar_contrasena")}}</a></li>
                                {{--MENU DE USUARIO--}}
                                @include("interfaz/menu/".Auth::user()->tipo."-usuario")
                            </ul>
                        </li>
                        <li><a href="{{URL::to("logout")}}"><span class="glyphicon glyphicon-log-in"></span> {{trans("interfaz.menu.comp.cerrar_sesion")}}</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div id="wrap">
            <div class="container" id="container">
                @yield('contenido')

            </div>
        </div>
        <div class="container navbar-inverse" id="footer" > 
            <div class="col-lg-6">&copy; {{trans("interfaz.nombre")}} {{date("Y")}} - {{trans('interfaz.pie_pagina')}}</div>
            <div class="col-lg-6 text-right"> 
                @if(User::enPrueba()) 
                <span style="color:white;"> <span class="glyphicon glyphicon-time"></span> {{trans("interfaz.pie_pagina.tiempo.prueba")}} {{Util::calcularDiferenciaFechas(Util::obtenerTiempoActual(), Auth::user()->fin_suscripcion);}} </span>
                @endif 
            </div>

        </div>

        {{-- Include all compiled plugins (below), or include individual files as needed --}}
        {{ HTML::script('assets/plugins/bootstrap/js/bootstrap.js') }}
        {{ HTML::script('assets/plugins/bootstrap-submenu/js/bootstrap-submenu.js') }}
        {{ HTML::script('assets/js/bootstrap-tooltip.js') }}


        {{--OTROS SCRIPTS--}}
        @yield("script")
        @yield("script2")

        <script>
            jQuery(".tooltip-left").tooltip({placement: "left"});
            jQuery(".tooltip-top").tooltip({placement: "top"});
            jQuery(".tooltip-right").tooltip({placement: "right"});
            jQuery(".tooltip-bottom").tooltip({placement: "bottom"});
        </script>

        <script>
            jQuery(document).ready(function () {
                jQuery('.dropdown').hover(function () {
                    //jQuery(this).toggleClass('open');
                });

                $('.dropdown-submenu > a').submenupicker();


                $("#seleccion-idioma span").click(function () {
                    $("#seleccion-idioma span").removeClass("select");
                    $(this).addClass("select");

                    var lang = $(this).attr("data-lang");

                    jQuery.ajax({
                        type: "POST",
                        url: "{{URL::to('usuario/opciones/idioma/set')}}",
                        data: {idioma: lang},
                        success: function (response) {
                            location.reload();
                        }}, "html");
                });

            });
        </script>
        
        <script>
            $(".nots-active").click(function(){
                
                $("#nots-num").remove();
                $(".nots-active").removeClass("nots-active");
                
               jQuery.ajax({
                        type: "POST",
                        url: "{{URL::to('nots/ajax/set/visto')}}",
                        data: {},
                        success: function (response) {
                        }}, "html"); 
            });
        </script>

    </body>

</html>
<?php
if (is_null($logoPlat = Instancia::obtenerValorMetadato(ConfigInstancia::visual_logo_facturacion))) {
    $logoPlat = URL::to("/assets/img/logo-factura.png");
    $esLogoPlat = true;
} else {
    $esLogoPlat = false;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>@yield('titulo', 'UPanel - Okonexion') @include("interfaz/titulo-pagina")</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {{-- Bootstrap --}}
        {{ HTML::style('assets/plugins/bootstrap/css/bootstrap.css', array('media' => 'screen')) }}
        {{ HTML::style('assets/plugins/bootstrap/css/bootstrap-theme.css', array('media' => 'screen')) }}
        {{ HTML::style('assets/plugins/bootstrap-submenu/css/bootstrap-submenu.css', array('media' => 'screen')) }}

        {{--CSS PERSONALIZADO--}}
        {{ HTML::style('assets/css/upanel/factura.css', array('media' => 'screen')) }}

        <style>
            #estado-factura.{{Facturacion::ESTADO_PAGADO}}{
                background: green;
            }

            #estado-factura.{{Facturacion::ESTADO_SIN_PAGAR}}{
                background: red;
            }

            #estado-factura.{{Facturacion::ESTADO_VENCIDA}}{
                background: orange;
            }


            #btn-pagar{
                width: 50%;
                -webkit-border-bottom-right-radius: 20px;
                -webkit-border-bottom-left-radius: 20px;
                -moz-border-radius-bottomright: 20px;
                -moz-border-radius-bottomleft: 20px;
                border-bottom-right-radius: 20px;
                border-bottom-left-radius: 20px;
                background-color: lemonchiffon;
                font-size: 12pt;
            }
        </style>

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

        @if($factura->estado!=Facturacion::ESTADO_PAGADO)
        <div class="container text-center">
            <form action="{{URL::to("fact/orden/pago")}}" method="POST">
                <input type="hidden" name="UsuarioMetadato::FACTURACION_ID_PROCESO" value="{{$factura->id}}"/>
                <button id="btn-pagar" type="submit"><span class="glyphicon glyphicon-shopping-cart"></span> {{trans("fact.factura.pulsa.aqui.pagar")}}</button>
            </form>
        </div>
        @endif

        <header class="container">
            <div class="col-lg-6" style="margin-top: 5px;">
                <img class="img-rounded" src="{{$logoPlat}}"/>
            </div>
            <div class="col-lg-6 text-right">
                <span id="num-factura">@yield("numero")</span>
                @yield("estado")
            </div>
        </header>
        <div id="wrap">
            <div class="container" id="container">
                @yield('contenido')
            </div>
        </div>

        <div class="col-lg-12 text-center">
            @yield("btns")
        </div>

        {{-- Include all compiled plugins (below), or include individual files as needed --}}
        {{ HTML::script('assets/plugins/bootstrap/js/bootstrap.js') }}

        @yield("script")
    </body>

</html>
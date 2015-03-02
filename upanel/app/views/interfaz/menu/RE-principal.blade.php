<?php if (Aplicacion::existe()) $app = Aplicacion::obtener(); ?>
<li class="dropdown @if(Request::is('aplicacion/*')) active @endif"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-phone"></span> Mi Aplicación<span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li class="dropdown-submenu"><a tabindex="0" data-toggle="dropdown" aria-expanded="false"><span class="glyphicon glyphicon-cog"></span> Configuración</a>
            <ul class="dropdown-menu">
                <li><a href="{{URL::to("aplicacion/basico")}}"><span class="glyphicon glyphicon-pencil"></span> Datos basicos</a></li>
                <li @if(!Aplicacion::existe()) class="disabled" @endif><a @if(Aplicacion::existe())href="{{URL::to("aplicacion/apariencia")}}"@endif><span class="glyphicon glyphicon-star"></span> Apariencia</a></li>
            </ul>
        </li>
        @if(Aplicacion::existe())
        @include("interfaz/menu/app/administrar/index")
        @endif
        <li role="presentation" class="divider"></li>
        <li @if(!Aplicacion::existe()) class="disabled" @endif>
             <a @if(Aplicacion::existe())href="{{URL::to("aplicacion/desarrollo")}}"@endif>
            @if(Aplicacion::existe())
            @if(Aplicacion::estaTerminada($app->estado)) <span class="glyphicon glyphicon-download-alt"></span> Descargas @else
                <span class="glyphicon glyphicon-time"></span> Desarrollo
                @endif
                @else
                <span class="glyphicon glyphicon-time"></span> Desarrollo
                @endif
            </a>
        </li>
    </ul>
</li>
<li><a href="#"><span class="glyphicon glyphicon-list-alt"></span> Facturas</a></li>
<li class="dropdown @if(Request::is('soporte/*')) active @endif "><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-question-sign"></span> Ayuda<span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li><a href="{{Route("soporte.index")}}"><span class="glyphicon glyphicon-question-sign"></span> Soporte</a></li>
        <li><a href="#"><span class="glyphicon glyphicon-bullhorn"></span> Noticias</a></li>
        <li><a href="#"><span class="glyphicon glyphicon-book"></span> Base de conocimientos</a></li>
    </ul>
</li>

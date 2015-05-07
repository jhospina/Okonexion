<?php
$tieneApp = Aplicacion::existe();
?>


<div class="col-lg-3" id="menu-crear-app">
    <ul class="nav nav-pills nav-stacked">
        <li class="@if(Request::is('aplicacion/basico')) active @endif"><a href="{{URL::to("aplicacion/basico")}}">1. {{trans("interfaz.menu.principal.mi_aplicacion.configuracion.datos_basicos")}}</a></li>
        <li class="@if(Request::is('aplicacion/apariencia')) active @endif @if(!$tieneApp) disabled @endif">
            @if(!$tieneApp) <a>2. {{trans("interfaz.menu.principal.mi_aplicacion.configuracion.apariencia")}}</a> @else 
            <a href="{{URL::to("aplicacion/apariencia")}}">2. {{trans("interfaz.menu.principal.mi_aplicacion.configuracion.apariencia")}}</a>
            @endif
        </li>
        <li class="@if(Request::is('aplicacion/textos')) active @endif @if(!$tieneApp) disabled @endif">
            @if(!$tieneApp) <a>3. {{trans("interfaz.menu.principal.mi_aplicacion.configuracion.textos")}}</a> @else 
            <a href="{{URL::to("aplicacion/textos")}}">3. {{trans("interfaz.menu.principal.mi_aplicacion.configuracion.textos")}}</a>
            @endif
        </li>
        <li class="@if(Request::is('aplicacion/desarrollo')) active @endif @if($tieneApp)@if(Aplicacion::validarEstadoParaEntrarSeccionDesarrollo($app->estado)) @else disabled @endif@else disable @endif">

            @if($tieneApp)
            @if(Aplicacion::validarEstadoParaEntrarSeccionDesarrollo($app->estado))
            <a href="{{URL::to("aplicacion/desarrollo")}}">4. {{trans("interfaz.menu.principal.mi_aplicacion.desarrollo")}}</a>
            @else<a>4. {{trans("interfaz.menu.principal.mi_aplicacion.desarrollo")}}</a> 
            @endif
            @else<a>4. {{trans("interfaz.menu.principal.mi_aplicacion.desarrollo")}}</a> 
            @endif
        </li>
    </ul>
</div>


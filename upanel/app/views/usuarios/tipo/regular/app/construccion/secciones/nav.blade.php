<?php
$tieneApp = Aplicacion::existe();
?>


<div class="col-lg-3" id="menu-crear-app">
    <ul class="nav nav-pills nav-stacked">
        <li class="@if(Request::is('aplicacion/basico')) active @endif"><a href="{{URL::to("aplicacion/basico")}}">1. Datos Básicos</a></li>
        <li class="@if(Request::is('aplicacion/apariencia')) active @endif @if(!$tieneApp) disabled @endif">
            @if(!$tieneApp) <a>Apariencia</a> @else 
            <a href="{{URL::to("aplicacion/apariencia")}}">2. Apariencia</a>
            @endif
        </li>
        <li class="@if(Request::is('aplicacion/desarrollo')) active @endif @if($tieneApp)@if(Aplicacion::validarEstadoParaEntrarSeccionDesarrollo($app->estado)) @else disabled @endif@else disable @endif">

            @if($tieneApp)
            @if(Aplicacion::validarEstadoParaEntrarSeccionDesarrollo($app->estado))
            <a href="{{URL::to("aplicacion/desarrollo")}}">3. Desarollo</a>
            @else<a>Desarrollo</a> 
            @endif
            @else<a>Desarrollo</a> 
            @endif
        </li>
    </ul>
</div>


<?php
if (Aplicacion::existe()) {
    $app = Aplicacion::obtener();
    $version = ProcesoApp::obtenerNumeroVersion($app->id);
} else {
    $version = 0;
}
?>


@if(Auth::user()->estado==User::ESTADO_PERIODO_PRUEBA || Auth::user()->estado==User::ESTADO_SUSCRIPCION_VIGENTE)

<li class="dropdown @if(Request::is('aplicacion/*')) active @endif"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-phone"></span> {{trans("interfaz.menu.principal.mi_aplicacion")}}<span class="caret"></span></a>
    <ul class="dropdown-menu">
        @if(!Aplicacion::existe())
            <li><a href="{{URL::to("aplicacion/basico")}}"><span class="glyphicon glyphicon-plus-sign"></span> {{trans("otros.info.crear")}}</a></li>
        @else
        <li class="dropdown-submenu"><a tabindex="0" data-toggle="dropdown" aria-expanded="false"><span class="glyphicon glyphicon-cog"></span> {{trans("interfaz.menu.principal.mi_aplicacion.configuracion")}}</a>
            <ul class="dropdown-menu">
                <li><a href="{{URL::to("aplicacion/basico")}}"><span class="glyphicon glyphicon-pencil"></span> {{trans("interfaz.menu.principal.mi_aplicacion.configuracion.datos_basicos")}}</a></li>
                <li @if(!Aplicacion::existe()) class="disabled" @endif><a @if(Aplicacion::existe())href="{{URL::to("aplicacion/apariencia")}}"@endif><span class="glyphicon glyphicon-star"></span> {{trans("interfaz.menu.principal.mi_aplicacion.configuracion.apariencia")}}</a></li>
                <li @if(!Aplicacion::existe()) class="disabled" @endif><a @if(Aplicacion::existe())href="{{URL::to("aplicacion/textos")}}"@endif><span class="glyphicon glyphicon-font"></span> {{trans("interfaz.menu.principal.mi_aplicacion.configuracion.textos")}}</a></li>
            </ul>
        </li>
        @endif
        @if(Aplicacion::existe())
        @include("interfaz/menu/app/administrar/index")
        @endif
        <li @if(!Aplicacion::existe()) class="disabled" @endif>
             <a @if(Aplicacion::existe())href="{{URL::to("aplicacion/desarrollo")}}"@endif>
            @if(Aplicacion::existe())
            @if(Aplicacion::estaTerminada($app->estado)) <span class="glyphicon glyphicon-download-alt"></span> {{trans("interfaz.menu.principal.mi_aplicacion.descargas")}} @else
                <span class="glyphicon glyphicon-time"></span> {{trans("interfaz.menu.principal.mi_aplicacion.desarrollo")}}
                @endif
                @else
                <span class="glyphicon glyphicon-time"></span> {{trans("interfaz.menu.principal.mi_aplicacion.desarrollo")}}
                @endif
            </a>
        </li>
        <li @if(intval($version)==0) class="disabled" @endif><a @if(intval($version)>0) href="{{URL::to("aplicacion/".$app->id."/versiones")}}" @endif><span class="glyphicon glyphicon-th"></span> {{trans("interfaz.menu.principal.mi_aplicacion.versiones")}}</a></li>
        <li @if(intval($version)==0) class="disabled" @endif><a @if(intval($version)>0) href="{{URL::to("aplicacion/".$app->id."/estadisticas")}}" @endif><span class="glyphicon glyphicon-stats"></span> {{trans("interfaz.menu.principal.mi_aplicacion.estadisticas")}}</a></li>
        

    </ul>
</li>

@else


<li class="disabled @if(Request::is('aplicacion/*')) active @endif"><a  href="#"><span class="glyphicon glyphicon-phone"></span> {{trans("interfaz.menu.principal.mi_aplicacion")}}<span class="caret"></span></a>

</li>

@endif

{{--FACTURACION--}}
<li class="dropdown @if(Request::is('fact/mis-facturas')) active @endif "><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-list-alt"></span> {{trans("interfaz.menu.principal.facturacion")}}<span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li><a href="{{URL::to("fact/mis-facturas")}}"><span class="glyphicon glyphicon-copy"></span> {{trans("interfaz.menu.principal.facturacion.mis.facturas")}}</a></li>
    </ul>
</li>
{{--SERVICIOS--}}
<li class="dropdown @if(Request::is('servicios/*')) active @endif "><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-flash"></span> {{trans("interfaz.menu.principal.servicios")}}<span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li><a href="{{URL::to("servicios")}}"><span class="glyphicon glyphicon-flash"></span> {{trans("interfaz.menu.principal.servicios.mis.servicios")}}</a></li>
        <li role="presentation" class="divider"></li>
        <li><a href="{{URL::to("servicios/agregar")}}"><span class="glyphicon glyphicon-plus-sign"></span> {{trans("interfaz.menu.principal.servicios.agregar.servicio")}}</a></li>
    </ul>
</li>
{{--AYUDA--}}
<li class="dropdown @if(Request::is('soporte/*')) active @endif "><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-question-sign"></span> {{trans("interfaz.menu.principal.ayuda")}}<span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li><a href="{{Route("soporte.index")}}"><span class="glyphicon glyphicon-question-sign"></span> {{trans("interfaz.menu.principal.ayuda.soporte")}}</a></li>
        <li><a href="#"><span class="glyphicon glyphicon-bullhorn"></span> {{trans("interfaz.menu.principal.ayuda.noticias")}}</a></li>
        <li><a href="#"><span class="glyphicon glyphicon-book"></span> {{trans("interfaz.menu.principal.ayuda.base_conocimientos")}}</a></li>
    </ul>
</li>

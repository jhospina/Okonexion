
<li class="dropdown @if(Request::is('aplicacion/*')) active @endif"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-phone"></span> {{trans("interfaz.menu.principal.aplicaciones")}}<span class="caret"></span></a>
    <ul class="dropdown-menu">
        @if(User::esSuperAdmin() || Auth::user()->instancia==User::PARAM_INSTANCIA_SUPER_ADMIN)
        <li class="dropdown-submenu"><a tabindex="0" data-toggle="dropdown" aria-expanded="false"><span class="glyphicon glyphicon-th-large"></span> {{trans("interfaz.menu.principal.mi_aplicacion.desarrollo")}}</a>
            <ul class="dropdown-menu">
                <li><a href="{{URL::to("aplicacion/desarrollo/cola")}}"><span class="glyphicon glyphicon-road"></span> {{trans("interfaz.menu.principal.aplicaciones.cola_desarrollo")}}</a></li> 
                <li><a href="{{URL::to("aplicacion/desarrollo/historial")}}"><span class="glyphicon glyphicon-time"></span> {{trans("interfaz.menu.principal.aplicaciones.historial")}}</a></li> 
            </ul>
        </li>
        @else
        <li><a href="{{URL::to("aplicacion/desarrollo/historial")}}"><span class="glyphicon glyphicon-time"></span> {{trans("interfaz.menu.principal.aplicaciones.historial")}}</a></li> 
        @endif
    </ul>
</li>
<li @if(Request::is('soporte')) class="active" @endif><a href="{{Route("soporte.index")}}"><span class="glyphicon glyphicon-question-sign"></span> {{trans("interfaz.menu.principal.ayuda.soporte")}}</a></li>
<li><a href="#"><span class="glyphicon glyphicon-list-alt"></span> {{trans("interfaz.menu.principal.facturas")}}</a></li>
<li class="dropdown @if(Request::is('usuario/*') || Request::is('control/usuarios')) active @endif"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> {{trans("interfaz.menu.principal.usuarios")}}<span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li><a href="{{URL::to("control/usuarios")}}"><span class="glyphicon glyphicon-th-list"></span> {{trans("interfaz.menu.principal.usuarios.indice")}}</a></li>
        <li role="presentation" class="divider"></li>
        <li><a href="{{Route("usuario.create")}}"><span class="glyphicon glyphicon-plus-sign"></span> {{trans("interfaz.menu.principal.usuarios.agregar_usuario")}}</a></li>
    </ul>
</li>
{{--INSTANCIAS--}}
@if(User::esSuperAdmin())
<li class="dropdown @if(Request::is('instancias/*')) active @endif"><a class="dropdown-toggle" title="{{trans("interfaz.menu.principal.instancias")}}" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-inbox"></span> <span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li><a href="{{URL::to("instancias/")}}"><span class="glyphicon glyphicon-th-list"></span> {{trans("interfaz.menu.principal.instancias.indice")}}</a></li>
        <li role="presentation" class="divider"></li>
        <li><a href="{{URL::to("instancias/crear")}}"><span class="glyphicon glyphicon-new-window"></span> {{trans("interfaz.menu.principal.instancias.agregar")}}</a></li>
    </ul>
</li>
@endif
{{--INSTANCIAS--}}
@if(!User::esSuperAdmin())
<li class="dropdown @if(Request::is('soporte/*')) active @endif"><a class="dropdown-toggle"  data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-phone-alt"></span> {{trans("interfaz.menu.principal.ayuda")}}<span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li><a href="{{URL::to("soporte?ref=assist")}}"><span class="glyphicon glyphicon-blackboard"></span> {{trans("interfaz.menu.principal.ayuda.asistencia")}}</a></li>
    </ul>
</li>
@endif

<li class="dropdown @if(Request::is('config/*')) active @endif"><a class="dropdown-toggle" title="{{trans("interfaz.menu.principal.config")}}" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-wrench"></span><span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li><a href="{{URL::to("config/general")}}"><span class="glyphicon glyphicon-cog"></span> {{trans("interfaz.menu.principal.config.general")}}</a></li>
    </ul>
</li>

<li class="dropdown @if(Request::is('aplicacion/*') || Request::is('aplicaciones')) active @endif"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-phone"></span> {{trans("interfaz.menu.principal.aplicaciones")}}<span class="caret"></span></a>
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
        <li><a href="{{URL::to("aplicaciones")}}"><span class="glyphicon glyphicon-phone"></span> {{trans("interfaz.menu.principal.aplicaciones.listado")}}</a></li>
    </ul>
</li>

<li class="dropdown @if(Request::is('fact/*')) active @endif "><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-list-alt"></span> {{trans("interfaz.menu.principal.facturacion")}}<span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li><a href="{{URL::to("fact/facturas")}}"><span class="glyphicon glyphicon-copy"></span> {{trans("interfaz.menu.principal.facturacion.facturas")}}</a></li>
    </ul>
</li>


<li @if(Request::is('control/servicios')) class="active" @endif><a title="{{trans("interfaz.menu.principal.servicios")}}" href="{{URL::to("control/servicios")}}"><span class="glyphicon glyphicon-flash"></span> {{trans("interfaz.menu.principal.servicios")}}</a></li>


<li class="dropdown @if(Request::is('usuario/*') || Request::is('control/usuarios')) active @endif"><a class="dropdown-toggle" title="{{trans("interfaz.menu.principal.usuarios")}}" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span><span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li><a href="{{URL::to("control/usuarios")}}"><span class="glyphicon glyphicon-th-list"></span> {{trans("interfaz.menu.principal.usuarios.indice")}}</a></li>
        <li role="presentation" class="divider"></li>
        <li><a href="{{Route("usuario.create")}}"><span class="glyphicon glyphicon-plus-sign"></span> {{trans("interfaz.menu.principal.usuarios.agregar_usuario")}}</a></li>
    </ul>
</li>

<li @if(Request::is('soporte')) class="active" @endif><a title="{{trans("interfaz.menu.principal.ayuda.soporte")}}" href="{{Route("soporte.index")}}"><span class="glyphicon glyphicon-question-sign"></span></a></li>
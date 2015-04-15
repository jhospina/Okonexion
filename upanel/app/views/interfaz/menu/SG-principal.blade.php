
<li class="dropdown @if(Request::is('aplicacion/*')) active @endif"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-phone"></span> {{trans("interfaz.menu.principal.aplicaciones")}}<span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li><a href="{{URL::to("aplicacion/cola-desarrollo")}}"><span class="glyphicon glyphicon-time"></span> {{trans("interfaz.menu.principal.aplicaciones.cola_desarrollo")}}</a></li>
    </ul>
</li>
<li @if(Request::is('soporte')) class="active" @endif><a href="{{Route("soporte.index")}}"><span class="glyphicon glyphicon-question-sign"></span> {{trans("interfaz.menu.principal.ayuda.soporte")}}</a></li>
<li><a href="#"><span class="glyphicon glyphicon-list-alt"></span> {{trans("interfaz.menu.principal.facturas")}}</a></li>
<li><a href="#"><span class="glyphicon glyphicon-user"></span> {{trans("interfaz.menu.principal.usuarios")}}</a></li>
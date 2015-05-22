
<div class="col-lg-3" id="menu-crear-app">
    <ul class="nav nav-pills nav-stacked">
        <li @if(Request::is('config/general')) class="active" @endif>
             <a href="{{URL::to("config/general")}}"><span class="glyphicon glyphicon-cog"></span> 
                {{trans("interfaz.menu.principal.config.general")}}</a>
        </li>
        <li @if(Request::is('config/suscripcion')) class="active" @endif>
             <a href="{{URL::to("config/suscripcion")}}"><span class="glyphicon glyphicon-gift"></span> 
                {{trans("interfaz.menu.principal.config.suscripcion")}}</a>
        </li>
        <li @if(Request::is('config/servicios')) class="active" @endif>
             <a href="{{URL::to("config/servicios")}}"><span class="glyphicon glyphicon glyphicon-flash"></span> 
                {{trans("interfaz.menu.principal.config.servicios")}}</a>
        </li>
        <li @if(Request::is('config/facturacion')) class="active" @endif>
             <a href="{{URL::to("config/facturacion")}}"><span class="glyphicon glyphicon-piggy-bank"></span> 
                {{trans("interfaz.menu.principal.config.facturacion")}}</a>
        </li>
    </ul>
</div>
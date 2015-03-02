
<li class="dropdown @if(Request::is('aplicacion/*')) active @endif"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-phone"></span> Aplicaciones<span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li><a href="{{URL::to("aplicacion/cola-desarrollo")}}"><span class="glyphicon glyphicon-time"></span> Cola de desarrollo</a></li>
    </ul>
</li>
<li @if(Request::is('soporte')) class="active" @endif><a href="{{Route("soporte.index")}}"><span class="glyphicon glyphicon-question-sign"></span> Soporte</a></li>
<li><a href="#"><span class="glyphicon glyphicon-list-alt"></span> Facturas</a></li>
<li><a href="#"><span class="glyphicon glyphicon-user"></span> Usuarios</a></li>
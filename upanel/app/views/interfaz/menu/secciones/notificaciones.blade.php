<?php
$nots = Notificacion::noVistas();
?>
<li class="dropdown" id="menu-nots"><a class="dropdown-toggle @if(!is_null($nots)) nots-active  @endif" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-bell"></span></a>
    @if(!is_null($nots))
    <div id="nots-num">{{count($nots)}}</div>
    @endif
    <ul class="dropdown-menu" id="notifications">
        @if(!is_null($nots))
        @foreach($nots as $not)
        {{Notificacion::plantilla($not)}}
        @endforeach
        @else
        <li class="not-item"><a>{{trans("nots.info.sin_notificacion")}}</a></li>
        @endif
    </ul>
</li>
<?php
$tiposContenidos = TipoContenido::obtenerTiposContenidoDelDiseno($app->diseno);
?>

{{--ESTA SECCION DEL MENU SOLO SE MUESTRA SI LA APLICACION YA HA SIDO TERMINADA--}}
@if(Aplicacion::estaTerminada($app->estado))
<li class="dropdown-submenu"><a tabindex="0" data-toggle="dropdown" aria-expanded="false"><span class="glyphicon glyphicon-wrench"></span> Administrar</a>
    <ul class="dropdown-menu">
        @foreach($tiposContenidos as $tipo)
        <li><a href="{{URL::to("aplicacion/administrar/".$tipo)}}"><span class="glyphicon {{TipoContenido::obtenerIcono($tipo)}}"></span> {{TipoContenido::obtenerNombre($app->diseno,$tipo)}}</a></li>
        @endforeach
    </ul>
</li>
@endif
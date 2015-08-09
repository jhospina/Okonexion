<?php
$tiposContenidos = TipoContenido::obtenerTiposContenidoDelDiseno($app->diseno);
?>

{{--ESTA SECCION DEL MENU SOLO SE MUESTRA SI LA APLICACION YA HA SIDO TERMINADA--}}
@if(Aplicacion::estaTerminada($app->estado))
<li class="dropdown-submenu"><a tabindex="0" data-toggle="dropdown" aria-expanded="false"><span class="glyphicon glyphicon-wrench"></span> {{trans("interfaz.menu.principal.mi_aplicacion.administrar")}}</a>
    <ul class="dropdown-menu">
        <?php foreach ($tiposContenidos as $tipo): ?>
            <?php if (!TipoContenido::estaActivo($tipo)) continue; ?>
            <li><a href="{{URL::to("aplicacion/administrar/".$tipo)}}"><span class="glyphicon {{TipoContenido::obtenerIcono($tipo)}}"></span> {{TipoContenido::obtenerNombre($app->diseno,$tipo)}}</a></li>
            <?php endforeach; ?>
    </ul>
</li>
@endif
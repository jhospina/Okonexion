<?php
$tiposContenidos = TipoContenido::obtenerTiposContenidoDelDiseno($app->diseno);
?>

@foreach($tiposContenidos as $tipo)
<div class="col-lg-12 content" onclick="location.href='{{URL::to("aplicacion/administrar/".$tipo)}}'">
    <div class="col-lg-2 icon"><span class="glyphicon {{TipoContenido::obtenerIcono($tipo)}}"></div>
    <div class="col-lg-6 nombre">{{TipoContenido::obtenerNombre($app->diseno,$tipo)}}</div>
    <div class="col-lg-4 info text-right">
        @if($tipo!=Contenido_PQR::nombre)
        <a href='{{URL::to("aplicacion/administrar/".$tipo."/agregar")}}' class="btn-large btn btn-info"><span class="glyphicon glyphicon-plus-sign"></span> {{trans("otros.info.agregar")}}</a>
        @endif
    </div>
</div>
@endforeach

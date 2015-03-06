<?php
$tipoContenido = "noticias";
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, $tipoContenido);
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | Administrar {{$nombreContenido}} @stop

@section("css")
{{ HTML::style('assets/css/upanel/noticias.css', array('media' => 'screen')) }}
@stop

@section("contenido") 

<h1>ADMINISTRAR {{strtoupper($nombreContenido)}}</h1>
<hr/>
@include("interfaz/mensaje/index",array("id_mensaje"=>2))

<div class="well well-sm" style="margin-top:10px;">
    <a href="{{URL::to("aplicacion/administrar/".$tipoContenido."/agregar")}}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>Agregar nuevo</a>
</div>


<div class="col-lg-12" id="listado-noticias">
    @foreach($noticias as $noticia)
    <div class="col-lg-12 content-noticia"> 
        <?php
        $cats = $noticia->terminos;
        $imagen = Contenido_Noticias::obtenerImagen($noticia->id, Contenido_Noticias::IMAGEN_URL);
        ?>
        <div class="col-lg-10 titulo-noticia"><span class="glyphicon glyphicon-globe"></span>  {{$noticia->titulo}}</div>
        <div class="col-lg-2 estado-noticia {{$noticia->estado}}">
            @if($noticia->estado==ContenidoApp::ESTADO_PUBLICO)
            <span class="glyphicon glyphicon-flag"></span> 
            @endif
            @if($noticia->estado==ContenidoApp::ESTADO_GUARDADO)
            <span class="glyphicon glyphicon-save"></span> 
            @endif
            {{$noticia->estado}}

        </div>
        <div class="col-lg-12 descripcion-noticia">
            {{Util::recortarTexto($noticia->contenido,400)}}
        </div>
        <div class="col-lg-10"><span class="glyphicon glyphicon-tags"></span>&nbsp; 
            {{Util::formatearResultadosObjetos($noticia->terminos,"nombre")}}
        </div>
        <div class="col-lg-2">
            <span class="glyphicon glyphicon-calendar"></span> {{$noticia->created_at}}
        </div>
        {{--
            <a href="{{URL::to("aplicacion/administrar/noticias/editar/".$noticia->id)}}" class="btn btn-warning">Editar</a>
        <a class="btn btn-danger">Eliminar</a>
        --}}
    </div> 
    @endforeach
</div>

{{$noticias->links()}}


@stop

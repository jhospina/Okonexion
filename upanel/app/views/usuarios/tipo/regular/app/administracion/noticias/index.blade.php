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
    <a href="{{URL::to("aplicacion/administrar/".$tipoContenido."/agregar")}}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Agregar nuevo</a>
    <a href="{{URL::to("aplicacion/administrar/".$tipoContenido."/categorias")}}" class="btn btn-info"><span class="glyphicon glyphicon-tags"></span>&nbsp; Categorias</a>
</div>

{{$noticias->links()}}

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
        {{--SI LA NOTICIA TIENE UNA IMAGEN PRINCIPAL--}}
        @if(!is_null($imagen))
        <div class="col-lg-2 imagen-noticia">
            <img class="img-rounded img-thumbnail" width="{{Contenido_Noticias::IMAGEN_ANCHO_MINIATURA_SM/2}}" height="{{Contenido_Noticias::IMAGEN_ALTURA_MINIATURA_SM/2}}" src="{{Contenido_Noticias::obtenerUrlMiniaturaImagen($imagen,Contenido_Noticias::IMAGEN_NOMBRE_MINIATURA_SM)}}"/>
        </div>
        @endif
        <div class="@if(!is_null($imagen)) col-lg-8 @else col-lg-10 @endif descripcion-noticia">
            {{Util::recortarTexto($noticia->contenido,410)}}
        </div>
        <div class="col-lg-2 acciones-noticia">
            <a href="{{URL::to("aplicacion/administrar/noticias/editar/".$noticia->id)}}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Editar</a>
            <a class="btn btn-danger"><span class="glyphicon glyphicon-remove-circle"></span> Eliminar</a>
        </div>
        <div class="col-lg-10 categorias"><span title="Categorias" class="glyphicon glyphicon-tags"></span>&nbsp; 
            {{Util::formatearResultadosObjetos($noticia->terminos,"nombre")}}
        </div>
        <div class="col-lg-2 creacion">
            <span title="Fecha de creación" class="glyphicon glyphicon-calendar"></span> {{$noticia->created_at}}
        </div>

    </div> 
    @endforeach
</div>

<div class="block">
    {{$noticias->links()}}
</div>

@stop

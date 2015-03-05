<?php
$tipoContenido = "noticias";
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, $tipoContenido);
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | Administrar {{$nombreContenido}} @stop

@section("contenido") 

<h1>ADMINISTRAR {{strtoupper($nombreContenido)}}</h1>
<hr/>
@include("interfaz/mensaje/index",array("id_mensaje"=>2))

<div class="well well-sm" style="margin-top:10px;">
    <a href="{{URL::to("aplicacion/administrar/".$tipoContenido."/agregar")}}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>Agregar nuevo</a>
</div>


<table class="table table-striped">
    <tr><th>TITULO</th><th>ESTADO</th><th>CATEGORIAS</th><th>FECHA CREACIÓN</th><th></th></tr>

    @foreach($noticias as $noticia)
    <tr>
        <?php $cats = $noticia->terminos; ?>
        <td>{{$noticia->titulo}}</td><td>{{$noticia->estado}}</td>
        <td>
            @foreach($cats as $cat)
            <a href="">{{$cat->nombre}}</a>, 
            @endforeach
        </td>

        <td>{{$noticia->created_at}}</td>
        <td>
            <a class="btn btn-primary">Ver</a>
            <a href="{{URL::to("aplicacion/administrar/noticias/editar/".$noticia->id)}}" class="btn btn-warning">Editar</a>
            <a class="btn btn-danger">Eliminar</a>
        </td>
    </tr>
    @endforeach

</table>


{{$noticias->links()}}


@stop

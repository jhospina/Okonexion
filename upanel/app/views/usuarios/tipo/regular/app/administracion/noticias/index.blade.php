<?php
$tipoContenido = Contenido_Noticias::nombre;
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, $tipoContenido);
$singNombre=Util::eliminarPluralidad($nombreContenido);
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | Administrar {{$nombreContenido}} @stop

@section("css")
{{ HTML::style('assets/css/upanel/noticias.css', array('media' => 'screen')) }}
@stop

@section("contenido") 

<h2>ADMINISTRAR {{strtoupper($nombreContenido)}}</h2>
<hr/>
@include("interfaz/mensaje/index",array("id_mensaje"=>2))

<div class="well well-sm" style="margin-top:10px;">
    <a href="{{URL::to("aplicacion/administrar/".$tipoContenido."/agregar")}}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Agregar nuevo</a>
    <a href="{{URL::to("aplicacion/administrar/".$tipoContenido."/categorias")}}" class="btn btn-info"><span class="glyphicon glyphicon-tags"></span>&nbsp; Categorias</a>
</div>

{{$noticias->links()}}

<div class="col-lg-12" id="listado-noticias">
    @foreach($noticias as $noticia)
    <div class="col-lg-12 content-noticia" id='noticia-{{$noticia->id}}'> 
        <?php
        $cats = $noticia->terminos;
        $imagen = Contenido_Noticias::obtenerImagen($noticia->id);
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
        <?php
        $imagen_url = $imagen->contenido;
        $imagen_id = $imagen->id;
        ?>
        <div class="col-lg-2 imagen-noticia">
            <img id="imagen-{{$noticia->id}}" class="img-rounded img-thumbnail" data-id-imagen="{{$imagen_id}}" width="{{Contenido_Noticias::IMAGEN_ANCHO_MINIATURA_SM/2}}" height="{{Contenido_Noticias::IMAGEN_ALTURA_MINIATURA_SM/2}}" src="{{Contenido_Noticias::obtenerUrlMiniaturaImagen($imagen_url,Contenido_Noticias::IMAGEN_NOMBRE_MINIATURA_SM)}}"/>
        </div>
        @endif
        <div class="@if(!is_null($imagen)) col-lg-8 @else col-lg-10 @endif descripcion-noticia">
            {{Util::recortarTexto($noticia->contenido,410)}}
        </div>
        <div class="col-lg-2 acciones-noticia">
            <a href="{{URL::to("aplicacion/administrar/noticias/editar/".$noticia->id)}}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Editar</a>
            <button class="btn btn-danger" onclick="eliminarNoticia({{$noticia->id}},'{{str_replace("'","\"",$noticia->titulo);}}');"><span class="glyphicon glyphicon-remove-circle"></span> Eliminar</button>
        </div>
        <div class="col-lg-10 categorias"><span title="Categorias" class="glyphicon glyphicon-tags"></span>&nbsp; 
            {{Util::formatearResultadosObjetos($noticia->terminos,"nombre")}}
        </div>
        <div class="col-lg-2 creacion">
            <span title="Fecha creación" class="glyphicon glyphicon-calendar"></span> {{$noticia->created_at}}
        </div>

    </div> 
    @endforeach
</div>

<div class="block">
    {{$noticias->links()}}
</div>




<div id="modal-eliminacion" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titulo-modal">PREACUCIÓN</h4>
            </div>
            <div class="modal-body" id="contenido-modal" style='text-align: center;'>
                ¿Estas seguro de eliminar <span id="nombre-noticia" style="font-weight: bold;"></span>?
            </div>
            <div class="modal-footer">
                <div id="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btn-confirmar-eliminacion">Aceptar</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@stop


@section("script")

<script>

            function eliminarNoticia(id_noticia, titulo){
            $("#titulo-modal").html("PRECAUCIÓN");
                    $("#contenido-modal").html('¿Estas seguro que quieres eliminar <span id="noticia-titulo-modal" data-noticia="' + id_noticia + '" style="font-weight: bold;">' + titulo + '</span>?');
                    $('#modal-eliminacion').modal('show');
                    $("#modal-footer").show();
            }



    //Envia los datos confirmados para eliminar la noticia
    jQuery("#btn-confirmar-eliminacion").click(function(){
    var id_noticia = $("#noticia-titulo-modal").attr("data-noticia");
            $("#titulo-modal").html("PROCESANDO...");
            $("#contenido-modal").html("<div class='block' style='text-align:center;'><img src='{{URL::to('assets/img/loaders/gears.gif')}}'/></div>");
            $("#modal-footer").hide();
            if ($("#imagen-"+id_noticia).length)
            var id_imagen = $("#imagen-"+id_noticia).attr("data-id-imagen");
            else
            var id_imagen = "";
            jQuery.ajax({
            type: "POST",
                    url: "{{URL::to('aplicacion/administrar/noticias/ajax/eliminar/noticia')}}",
                    data: {id_noticia:id_noticia, id_imagen:id_imagen},
                    success: function (response) {
                    $("#noticia-" + id_noticia).fadeOut(function(){
                    $("#noticia-" + id_noticia).remove();
                    });
                            setTimeout(function(){
                            $("#titulo-modal").html("¡REALIZADO CON EXITO!");
                                    $("#contenido-modal").html("{{ucwords(strtolower($singNombre))}} eliminada");
                                    setTimeout(function(){
                                    $('#modal-eliminacion').modal('hide');
                                    }, 2000);
                            }, 2000);
                    }}, "html");
    });

</script>

@stop

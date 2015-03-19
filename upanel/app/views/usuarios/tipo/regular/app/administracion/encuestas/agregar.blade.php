<?php
$tipoContenido = Contenido_Encuestas::nombre;
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, $tipoContenido);
$singNombre = Util::eliminarPluralidad(strtolower($nombreContenido));
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | Administrar {{$nombreContenido}} @stop


@section("contenido") 

<h2><span class="glyphicon {{Contenido_Encuestas::icono}}"></span> Agregar {{$singNombre}}</h2>
<hr/>
@include("interfaz/mensaje/index",array("id_mensaje"=>2))


<div class="col-lg-12">
    <input class="form-control input-lg" name="{{Contenido_Encuestas::configTitulo}}" placeholder="Titulo o pregunta...">
</div>

<div class="col-lg-10">
    <textarea class="form-control" style="display:none;margin-top: 5px;" name="{{Contenido_Encuestas::configDescripcion}}" placeholder="Si deseas escribir una descripción, hazlo aquí..."></textarea>
</div>

<div class="col-lg-2 text-right" style="margin-top: 5px;">
    <button class="btn btn-primary" onclick="$('textarea').toggle();">Agregar descripción</button>
</div>


<div class="col-lg-12" style="margin-top: 5px;margin-bottom: 10px;">
    <div class="col-lg-12"> 
        <h3>Respuestas</h3>
    </div>
    <div class="col-lg-12"> 
        <div class="col-lg-9" id="content-respuestas"> 
            <div class="col-lg-12 item" id="item-1"> 
                <div class="col-lg-1 div-item">1</div>
                <div class="col-lg-10 div-resp"><input class="form-control resp" data-item="1" name="{{Contenido_Encuestas::configRespuesta}}1" placeholder="Inserte respuesta..." value=""></div>
                <div class="col-lg-1 div-ctrl"></div>
            </div> 
        </div>
        <div class="col-lg-3">
            <div class="panel panel-default" style="clear: both;">
                <div class="panel-heading">Acciones</div>
                <div class="panel-body">
                    <div class="col-lg-12" style="text-align: center;">
                        <button id="btn-publicar" type="button" onClick="publicar(this);" class="btn btn-success col-lg-12 text-center" style="margin-bottom: 5px;"><span class="glyphicon glyphicon glyphicon-ok-circle"></span> PUBLICAR </button>
                        <button id="btn-guardar" type="button" onClick="guardar(this);" class="btn btn-default col-lg-12"><span class="glyphicon glyphicon glyphicon glyphicon-save"></span> Guardar</button>     
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop


@section("script")
<script>

    var n_resps = 1; //Numero de respuestas

    $(document).ready(function () {
        focalizarItem();
    });
</script>

<script>

    function focalizarItem() {
        $(".resp").focusin(function () {
            agregarRespuesta(this);
        });
    }

    function agregarRespuesta(resp) {
        var id_item = $(resp).attr("data-item");
        var nuevo_id_item = parseInt(id_item) + 1;

        var nuevo_html_item = '<div class="col-lg-12 item nuevo" id="item-' + nuevo_id_item + '"><div class="col-lg-1 div-item">' + nuevo_id_item + '</div>' +
                '<div class="col-lg-10 div-resp"><input class="form-control resp" data-item="' + nuevo_id_item + '" name="{{Contenido_Encuestas::configRespuesta}}' + nuevo_id_item + '" placeholder="Inserte respuesta..." value=""></div>' +
                '<div class="col-lg-1 div-ctrl"><span class="glyphicon glyphicon-remove-circle"> </span></div></div>';

        if (id_item == n_resps)
        {
            var clase = $("#item-" + id_item).attr("class");
            if (/nuevo/.test(clase))
                $("#item-" + id_item).removeClass("nuevo");


            $("#content-respuestas").append(nuevo_html_item);
            n_resps++;
            focalizarItem();
        }
    }
</script>

@stop
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
<div class="well well-lg">
    <p><b>ATENCIÓN:</b> Al crear una nueva {{$singNombre}} y publicarla, esto ocasionara que cualquier otra {{$singNombre}} en vigencia sera archivara y pasara a ser parte los históricos. Ten en cuenta que en tu aplicación móvil solo puede haber una encuesta en vigencia. Cualquier {{$singNombre}} publicada no se podra editar despues.</p>
</div>
@include("interfaz/mensaje/index",array("id_mensaje"=>2))


<form id="form" method="POST">

    <div class="col-lg-12">
        <input class="form-control input-lg" id="{{Contenido_Encuestas::configTitulo}}" name="{{Contenido_Encuestas::configTitulo}}" placeholder="Titulo o pregunta...">
    </div>

    <div class="col-lg-10">
        <textarea class="form-control" style="display:none;margin-top: 5px;" name="{{Contenido_Encuestas::configDescripcion}}" placeholder="Si deseas escribir una descripción, hazlo aquí..."></textarea>
    </div>

    <div class="col-lg-2 text-right" style="margin-top: 5px;">
        <button class="btn btn-primary" type="button" onclick="$('textarea').toggle();">Agregar descripción</button>
    </div>


    <div class="col-lg-12" style="margin-top: 5px;margin-bottom: 10px;">
        <div class="col-lg-12"> 
            <h3>Respuestas</h3>
        </div>
        <div class="col-lg-12"> 
            <div class="col-lg-9" id="content-respuestas"> 
                <div class="col-lg-12 item set" id="item-1"> 
                    <div class="col-lg-1 div-item" id="div-item-1">1</div>
                    <div class="col-lg-10 div-resp"><input id="{{Contenido_Encuestas::configRespuesta}}1" class="form-control resp" data-item="1" name="{{Contenido_Encuestas::configRespuesta}}[]" placeholder="Inserte respuesta..." value=""></div>
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
</form>
@stop


@section("script")
<script>

    var n_resps = 1; //Numero de respuestas
    var tooltip_template = '<div class="tooltip tooltip-error" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>';


    $(document).ready(function () {
        jQuery("#btn-publicar").tooltip({placement: "top", trigger: "manual", title: "Para conservar la integridad de los datos de la {{$singNombre}}, una vez publicada no se podra editar. ¿Quieres continuar?"});
        focalizarItem();
        init_errores_tooltip();
    });
</script>

<script>

    function init_errores_tooltip() {
        jQuery("#{{Contenido_Encuestas::configTitulo}}").tooltip({placement: "left", trigger: "manual", title: "Debes escribir un titulo o pregunta mayor o igual a 5 caracteres.", template: tooltip_template});
        jQuery(".item.set").tooltip({placement: "left", trigger: "manual", title: "Escribe una respuesta aquí", template: tooltip_template});
        jQuery("#{{Contenido_Encuestas::configTitulo}}").focusin(function () {
            $(this).tooltip("hide");
        });

        jQuery(".item.set").focusin(function () {
            $(this).tooltip("hide");
        })
    }


    function focalizarItem() {
        $(".resp").focusin(function () {
            agregarRespuesta(this);
        });
    }

    function agregarRespuesta(resp) {

        var id_item = $(resp).attr("data-item");
        var nuevo_id_item = parseInt(id_item) + 1;

        var nuevo_html_item = '<div class="col-lg-12 item nuevo" id="item-' + nuevo_id_item + '"><div class="col-lg-1 div-item" id="div-item-' + nuevo_id_item + '">' + nuevo_id_item + '</div>' +
                '<div class="col-lg-10 div-resp"><input id="{{Contenido_Encuestas::configRespuesta}}' + nuevo_id_item + '" class="form-control resp" data-item="' + nuevo_id_item + '" name="{{Contenido_Encuestas::configRespuesta}}[]" placeholder="Inserte respuesta..." value=""></div>' +
                '<div class="col-lg-1 div-ctrl"><span title="Eliminar respuesta" onClick="eliminarRespuesta(' + nuevo_id_item + ');" class="glyphicon glyphicon-remove-circle"> </span></div></div>';

        if (id_item == n_resps)
        {
            var clase = $("#item-" + id_item).attr("class");

            if (/nuevo/.test(clase))
            {
                $("#item-" + id_item).removeClass("nuevo");
                $("#item-" + id_item).addClass("set");
                init_errores_tooltip();
            }


            $("#content-respuestas").append(nuevo_html_item);

            $("#div-item-" + nuevo_id_item).html(parseInt($("#div-item-" + id_item).html()) + 1);


            n_resps++;
            focalizarItem();
        }
    }


    function eliminarRespuesta(id_item) {
        $("#item-" + id_item).fadeOut(function () {
            $(this).remove();
            var total = $(".div-item").length;

            $(".div-item").each(function (index) {
                $(this).html(index + 1);
            });
        });
    }


</script>


<script>


    function validar() {
        var titulo = $("#{{Contenido_Encuestas::configTitulo}}").val();
        var val = true;

        console.log("TITULO => " + titulo.length);

        if (titulo.length < 5)
        {
            jQuery("#{{Contenido_Encuestas::configTitulo}}").tooltip("show");
            val = false;
        }

        var total = jQuery(".resp").length;

        //Recorre el valor de las respuestas para verificar si el usuario a escrito en ellas, si no mostrara un mensaje de error
        jQuery(".resp").each(function (index) {
            if (total > 1) {
                if (index != total - 1)
                {
                    if ($(this).val().length == 0)
                    {
                        $($($(this).parent()).parent()).tooltip("show");
                        val = false;
                    }
                }
            } else {
                if ($(this).val().length == 0)
                {
                    $($($(this).parent()).parent()).tooltip("show");
                    val = false;
                }
            }
        });

        return val;
    }


    function publicar(btn) {

        if ($(btn).html() != "CONFIRMAR") {
            $(btn).tooltip("show");
            $(btn).html("CONFIRMAR");
            return;
        }

        if (!validar())
            return;


        $(btn).tooltip("hide");

        $("#form").attr("action", "publicar");
        jQuery(btn).attr("disabled", "disabled");
        jQuery("#btn-guardar").attr("disabled", "disabled");
        jQuery(btn).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> Publicando...");
        setTimeout(function () {
            $("#form").submit();
        }, 2000);
    }

    function guardar(btn) {

        if (!validar())
            return;
        $("#form").attr("action", "guardar");
        jQuery(btn).attr("disabled", "disabled");
        jQuery("#btn-publicar").attr("disabled", "disabled");
        jQuery(btn).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> Guardando...");
        setTimeout(function () {
            $("#form").submit();
        }, 2000);
    }

</script>

@stop
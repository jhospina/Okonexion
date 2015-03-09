<?php
$tipoContenido = "noticias";
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, $tipoContenido);
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | Administrar {{$nombreContenido}} (Categorias) @stop

@section("css")
{{ HTML::style('assets/css/upanel/noticias.css', array('media' => 'screen')) }}
@stop

@section("contenido") 

{{--NAVEGACION--}}
<div class="well well-sm">
    <a href="{{URL::to("aplicacion/administrar/noticias")}}" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Volver</a>
</div>

<h1>Categorias de {{$nombreContenido}}</h1>
<hr/>
<div class="col-lg-12 text-right" style="margin-bottom: 10px;padding: 0px;"> 
    <div class="col-lg-10"><input  type="text" id="input-agregar-cat" class="form-control" placeholder="Escribe aquí para agregar una nueva categoría..."/></div>
    <div class="col-lg-2"><button class="btn btn-default" style="width: 100%;" id="btn-agregar-cat"><span class="glyphicon glyphicon-plus-sign"></span> Agregar categoría</button></div></div>
<table class="table table-striped" id="tb-categorias">
    <tr><th>NOMBRE DE LA CATEGORIA</th><th>CANTIDAD DE NOTICIAS</th><th>ACCIONES</th></tr>
    @foreach($cats as $cat)
    <tr id="cat-{{$cat->id}}"><td id="cat-nombre-{{$cat->id}}">{{$cat->nombre}}</td>
        <td>{{$cat->contador}}</td>
        <td>
            @if($cat->nombre!="Sin categoria")
            <button class="btn btn-sm btn-warning" onClick="editarCategoria({{$cat->id}})"><span class="glyphicon glyphicon-edit"></span> Editar</button>
            <button class="btn btn-sm btn-danger" onClick="eliminarCategoria({{$cat->id}},'{{$cat->nombre}}')"><span class="glyphicon glyphicon-remove-circle"></span> Eliminar</button>
            @endif
        </td>
    </tr>
    @endforeach
</table>



<div id="modal-eliminacion" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titulo-modal">PREACUCIÓN</h4>
            </div>
            <div class="modal-body" id="contenido-modal">
                ¿Estas seguro de eliminar la categoría <span id="cat-nombre" style="font-weight: bold;"></span>?
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
            jQuery("#btn-agregar-cat").click(function () {

    if (jQuery("#input-agregar-cat").css("display") == "none")
    {
    jQuery("#input-agregar-cat").fadeIn();
            jQuery("#input-agregar-cat").focus();
    } else {
    agregarNuevaCategoria();
    }
    });
            //Envia los datos confirmados para eliminar la categoria
            jQuery("#btn-confirmar-eliminacion").click(function(){
    var id_cat = $("#cat-nombre").attr("data-cat");
            $("#titulo-modal").html("PROCESANDO...");
            $("#contenido-modal").html("<div class='block' style='text-align:center;'><img src='{{URL::to('assets/img/loaders/gears.gif')}}'/></div>");
            $("#modal-footer").hide();
            jQuery.ajax({
            type: "POST",
                    url: "{{URL::to('aplicacion/administrar/noticias/ajax/eliminar/categoria')}}",
                    data: {id_cat: id_cat},
                    success: function (response) {
                        $("#cat-" + id_cat).fadeOut(function(){
                            $("#cat-" + id_cat).remove();
                        });
                    
                            setTimeout(function(){
                            $("#titulo-modal").html("¡REALIZADO CON EXITO!");
                                    $("#contenido-modal").html("La categoria ha sido eliminada");
                                    setTimeout(function(){
                                    $('#modal-eliminacion').modal('hide');
                                    }, 2000);
                            }, 2000);
                    }}, "html");
    });
            jQuery("#input-agregar-cat").keypress(function (event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
    agregarNuevaCategoria();
    }
    });</script>


<script>

            function eliminarCategoria(id_cat, nombre){
            $("#titulo-modal").html("PRECAUCIÓN");
                    $("#contenido-modal").html('¿Estas seguro de eliminar la categoría <span id="cat-nombre" data-cat="' + id_cat + '" style="font-weight: bold;">' + nombre + '</span>?');
                    $('#modal-eliminacion').modal('show');
                    $("#modal-footer").show();
            }


    function editarCategoria(id_cat){
    var nombre = jQuery("#cat-nombre-" + id_cat).html();
            jQuery("#cat-nombre-" + id_cat).html("<input id='input-edit-cat-" + id_cat + "' type='text' on onBlur='enviarEditarCategoria(this," + id_cat + ")'  class='form-control' value='" + nombre + "' />");
            //Establece el cursor al final del campo
            jQuery("#input-edit-cat-" + id_cat).focus().val("").val(nombre);
            //Pierde el foco al presionar enter y envia el form
            jQuery("#input-edit-cat-" + id_cat).keypress(function (event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
    jQuery("#input-edit-cat-" + id_cat).blur();
    }
    });
    }

    function enviarEditarCategoria(input, id_cat){

    var nombreCat = jQuery(input).val();
            jQuery.ajax({
            type: "POST",
                    url: "{{URL::to('aplicacion/administrar/noticias/ajax/editar/categoria')}}",
                    data: {id_cat: id_cat, cat:nombreCat},
                    success: function (response) {
                    jQuery("#cat-nombre-" + id_cat).html(nombreCat);
                    }}, "html");
    }

    function agregarNuevaCategoria() {
    var idTax =<?php print($tax->id); ?>;
            var nuevaCat = jQuery("#input-agregar-cat").val();
            if (nuevaCat.length < 1){
    jQuery("#input-agregar-cat").focus();
            jQuery("#input-agregar-cat").addClass("input-red");
            return true;
    }

    jQuery("#input-agregar-cat").removeClass("input-red");
            jQuery("#btn-agregar-cat").attr("disabled", "disabled");
            jQuery("#input-agregar-cat").attr("disabled", "disabled");
            jQuery("#btn-agregar-cat").html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> Procesando...");
            jQuery.ajax({
            type: "POST",
                    url: "{{URL::to('aplicacion/administrar/noticias/ajax/agregar/categoria')}}",
                    data: {id_tax: idTax, cat: nuevaCat},
                    success: function (response) {

                    jQuery("#input-agregar-cat").val("");
                            var html_cat = '<tr id="cat-' + response + '"><td id="cat-nombre-' + response + '">' + nuevaCat + '</td>' +
                            '<td>0</td>' +
                            ' <td>  <button class="btn btn-sm btn-warning" onClick="editarCategoria(' + response + ')"><span class="glyphicon glyphicon-edit"></span> Editar</button> ' +
                            '<button class="btn btn-sm btn-danger" onClick="eliminarCategoria(' + response + ',\'' + nuevaCat + '\')"><span class="glyphicon glyphicon-remove-circle"></span> Eliminar</button>' +
                            '</td>' +
                            '</tr>';
                            $("#tb-categorias").append(html_cat);
                            jQuery("#btn-agregar-cat").html("<span class='glyphicon glyphicon-ok'></span> Agregado");
                            jQuery("#btn-agregar-cat").removeClass("btn-default");
                            jQuery("#btn-agregar-cat").addClass("btn-success");
                            setTimeout(function(){
                            jQuery("#btn-agregar-cat").removeClass("btn-success");
                                    jQuery("#btn-agregar-cat").addClass("btn-default");
                                    jQuery("#btn-agregar-cat").removeAttr("disabled");
                                    jQuery("#input-agregar-cat").removeAttr("disabled");
                                    jQuery("#btn-agregar-cat").html("<span class='glyphicon glyphicon-plus-sign'></span> Agregar categoría");
                            }, 2000);
                    }}, "html");
    }

</script>

@stop

<?php
$form_data = array('route' => 'soporte.store', 'method' => 'post', 'enctype' => 'multipart/form-data', "id" => "form");
//Obtiene todos los tipos existes de soporte
$tipos = array(trans("otros.elegir") => trans("otros.elegir"));
foreach (Ticket::obtenerTipos() as $index => $valor)
    $tipos[$index] = $valor;
?>
@extends('interfaz/plantilla')

@section("titulo") {{trans("interfaz.menu.principal.ayuda.soporte")}} | {{trans("menu_ayuda.soporte.tickets.crear.titulo")}} @stop

@section("contenido") 

<h1>{{trans("menu_ayuda.soporte.tickets.crear.titulo")}}</h1>
<hr/>
{{-- MAPA DE NAVEGACION --}}
<ol class="breadcrumb">
    <li><a href="{{URL::to("/")}}">{{trans("interfaz.menu.principal.inicio")}}</a></li>
    <li><a href="{{URL::to("soporte?ref=assist")}}">{{trans("interfaz.menu.principal.ayuda.asistencia")}}</a></li>
    <li class="active">{{trans("menu_ayuda.soporte.tickets.crear.titulo")}}</li>
</ol>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))

{{Form::model($ticket, $form_data, array('role' => 'form')) }}

<table class="table table-striped">
    {{Form::hidden('tipo', Ticket::TIPO_ESPECIAL, array('class' => 'form-control')) }}
    <tr><th>{{trans("menu_ayuda.soporte.tickets.crear.info.asunto")}}</th><td>{{ Form::text('asunto', null, array('placeholder' => trans("menu_ayuda.soporte.tickets.crear.info.asunto.placeholder"), 'class' => 'form-control')) }}</td></tr>
    <tr><th>{{trans("menu_ayuda.soporte.tickets.crear.info.mensaje")}}</th><td>{{ Form::textarea('mensaje', null, array('placeholder' => trans("menu_ayuda.soporte.tickets.crear.info.mensaje.placeholder"), 'class' => 'form-control',"maxlength"=>1000)) }}</td></tr>
    <tr><th></th><td>
            <a  href="#" id="tooltip" rel="tooltip" title="{{trans("otros.extensiones_permitidas")}}: png, jpeg, gif, pdf, zip"> {{Form::file('adjunto',array("id"=>"adjunto","rel"=>"tooltip","accept"=>"image/*,application/pdf,application/zip","class"=>"filestyle","data-input"=>"false","data-buttonText"=>trans("otros.adjuntar_archivo")));}}</a></td></tr>
</table> 

<div class="well-lg text-right">
    {{ Form::button("<span class='glyphicon glyphicon-plus glyphicon-ok-circle'></span> ".trans("menu_ayuda.soporte.tickets.crear.submit"), array('type' => 'submit', 'class' => 'btn btn-primary',"id"=>"btn-crear")) }} 
    {{ Form::close() }}
</div>
@stop


@section("script")
{{ HTML::script('assets/js/bootstrap-filestyle.min.js') }}
<script>
    jQuery(document).ready(function () {
        jQuery("#tooltip").tooltip({placement: "left"});
        jQuery("#btn-crear").click(function () {
            jQuery(this).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans("otros.creando")}}...");
            jQuery(this).attr("disabled", "disabled");
            $("#form").submit();
        });
    });
</script>
@stop
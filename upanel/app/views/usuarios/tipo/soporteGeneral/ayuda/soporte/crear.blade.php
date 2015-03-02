<?php
$form_data = array('route' => 'soporte.store', 'method' => 'post','enctype' => 'multipart/form-data');
?>
@extends('interfaz/plantilla')

@section("titulo") Soporte @stop

@section("contenido") 

<h1>Nuevo ticket</h1>
<hr/>
{{-- MAPA DE NAVEGACION --}}
<ol class="breadcrumb">
    <li><a href="{{URL::to("/")}}">Inicio</a></li>
    <li><a href="{{Route("soporte.index")}}">Soporte</a></li>
    <li class="active">Nuevo Ticket</li>
</ol>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))

{{Form::model($ticket, $form_data, array('role' => 'form')) }}

<table class="table table-striped">
    <tr><th>Tipo de soporte</th><td>{{ Form::select('tipo',array("Elegir"=>"Elegir","SG"=>"General","SC"=>"Comercial","SF"=>"Facturación","ST"=>"Técnico"), null, array('class' => 'form-control')) }}</td></tr>
    <tr><th>Asunto</th><td>{{ Form::text('asunto', null, array('placeholder' => '¿Cuáles el interes de tu consulta?', 'class' => 'form-control')) }}</td></tr>
    <tr><th>Mensaje</th><td>{{ Form::textarea('mensaje', null, array('placeholder' => 'Describe detalladamente tu inquietud o problema', 'class' => 'form-control',"maxlength"=>1000)) }}</td></tr>
    <tr><th></th><td>
            <a  href="#" id="tooltip" rel="tooltip" title="Extensiones permitidas: png, jpeg, gif, pdf, zip"> {{Form::file('adjunto',array("id"=>"adjunto","rel"=>"tooltip","accept"=>"image/*,application/pdf,application/zip","class"=>"filestyle","data-input"=>"false","data-buttonText"=>"Adjuntar archivo"));}}</a></td></tr>
</table> 

<div class="well-lg text-right">
    {{ Form::button("<span class='glyphicon glyphicon-plus glyphicon-ok-circle'></span> Crear ticket", array('type' => 'submit', 'class' => 'btn btn-primary')) }} 
    {{ Form::close() }}
</div>
@stop


@section("script")
{{ HTML::script('assets/js/bootstrap-filestyle.min.js') }}
{{ HTML::script('assets/js/bootstrap-tooltip.js') }}
<script>
    jQuery(document).ready(function () {
        jQuery("#tooltip").tooltip({placement: "left"});

    });
</script>
@stop
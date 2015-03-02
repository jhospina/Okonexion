@extends('interfaz/plantilla')

@section("titulo") Soporte @stop

@section("contenido") 

<h1>¿Necesitas soporte?</h1>
<hr/>
{{-- MAPA DE NAVEGACION --}}
<ol class="breadcrumb">
    <li><a href="{{URL::to("/")}}">Inicio</a></li>
     <li class="active">Soporte</li>
</ol>

<div class="well">
    <p class="text-justify">
        El equipo de Okonexion siempre estará disponible para responder cualquier inquietud de nuestros clientes, 
        sin embargo, estaremos agradecidos si pudieras chequear la <a href="#">Base de Conocimiento</a>  antes de contactarnos. 
        La pregunta ya puede encontrarse respondida allí. Si no encuentras la respuesta que necesitas o si necesitas ayuda o asistencia, siéntete 
        libre de abrir un ticket de soporte y con gusto te ayudaremos.
    </p>
</div>

<div class="col-lg-6" style="font-size: 24px;">
<span class="glyphicon glyphicon-globe"></span> Tickets
</div>
<div class="col-lg-6 text-right" style="padding-right: 0px;"><a href="{{Route("soporte.create")}}" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span>Abrir nuevo Ticket</a></div>
<hr style="clear: both;">
@include("interfaz/mensaje/index",array("id_mensaje"=>2))
<br/>
<table id="tabla-tickets" class="table table-bordered table-striped">
    <tr><th>Asunto</th><th>Tipo</th><th>Fecha</th><th>Estado</th></tr>
    @foreach($tickets as $ticket)
    <tr><td><a href="{{Route("soporte.show",$ticket->id)}}">{{$ticket->asunto}}</a></td><td>{{$ticket->tipo}}</td><td>{{$ticket->fecha}}</td><td class="ticket-{{$ticket->estado}}">{{$ticket->estado}}</td></tr>
    @endforeach
</table>

{{$tickets->links()}}
@stop



@extends('interfaz/plantilla')

@section("titulo") {{trans("menu_ayuda.soporte.titulo")}} @stop

@section("css")
 @include("usuarios/tipo/regular/ayuda/soporte/comp/css-tipos")
@stop

@section("contenido") 

<h1>{{trans("menu_ayuda.soporte.titulo")}}</h1>
<hr/>
{{-- MAPA DE NAVEGACION --}}
<ol class="breadcrumb">
    <li><a href="{{URL::to("/")}}">{{trans("interfaz.menu.principal.inicio")}}</a></li>
     <li class="active">{{trans("interfaz.menu.principal.ayuda.soporte")}}</li>
</ol>

<div class="well">
    <p class="text-justify">
        {{trans("menu_ayuda.soporte.regular.descripcion",array("link"=>"<a href='#'>".trans("interfaz.menu.principal.ayuda.base_conocimientos")."</a>"))}}
    </p>
</div>

<div class="col-lg-6" style="font-size: 24px;">
<span class="glyphicon glyphicon-globe"></span> Tickets
</div>
<div class="col-lg-6 text-right" style="padding-right: 0px;"><a href="{{Route("soporte.create")}}" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span>{{trans("menu_ayuda.soporte.tickets.btn.crear")}}</a></div>
<hr style="clear: both;">
@include("interfaz/mensaje/index",array("id_mensaje"=>2))
<br/>
<table id="tabla-tickets" class="table table-bordered table-striped">
    <tr><th>{{trans("menu_ayuda.soporte.tickets.col.asunto")}}</th>
        <th>{{trans("menu_ayuda.soporte.tickets.col.tipo")}}</th>
        <th>{{trans("menu_ayuda.soporte.tickets.col.fecha")}}</th>
        <th>{{trans("menu_ayuda.soporte.tickets.col.estado")}}</th>
    </tr>
    @foreach($tickets as $ticket)
    <tr><td><a href="{{Route("soporte.show",$ticket->id)}}">{{$ticket->asunto}}</a></td><td>{{$ticket->tipo}}</td><td>{{$ticket->fecha}}</td><td class="ticket-{{$ticket->estado}}">{{$ticket->estado}}</td></tr>
    @endforeach
</table>

{{$tickets->links()}}
@stop



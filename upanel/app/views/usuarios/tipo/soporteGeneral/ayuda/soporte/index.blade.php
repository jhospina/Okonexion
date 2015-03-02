<?php
if (isset($_GET["estado"]))
    $estado = $_GET["estado"];
else
    $estado = null;

if (isset($_GET["buscar"]))
    $buscar = $_GET["buscar"];
else
    $buscar = null;

if (isset($_GET["tipo"]))
    $tipo = $_GET["tipo"];
else
    $tipo = null;
?>

@extends('interfaz/plantilla')

@section("titulo") Soporte @stop

@section("contenido") 

<h1>Soporte: Atención al cliente</h1>
<hr/>
{{-- MAPA DE NAVEGACION --}}
<ol class="breadcrumb">
    <li><a href="{{URL::to("/")}}">Inicio</a></li>
    <li class="active">Soporte</li>
</ol>

<div class="well">
    <p class="text-justify">
        Aquí encontraras un listado de tickets de soporte de atención al cliente que debes atender. 
        Todos los que se no encuentren en estado "Cerrado" son tickets que requieren atención. 
        Trata de no dejar a ningún cliente esperando mucho tiempo, es de vital importancia atender a los clientes en el menor tiempo posible, esto ayudara a la reputación de nuestra organización.
    </p>
</div>

<div class="col-lg-12" style="font-size: 28px;margin-bottom: 20px;">
    <span class="glyphicon glyphicon-globe"></span> Tickets
</div>

<hr style="clear: both;margin-bottom: 10px;">
<form  action="" method="get">
    <div class="col-lg-12" style="margin-bottom: 10px;">
        <div class="col-lg-3">
            <select name="estado" class="form-control">
                <optgroup label="Estados de Ticket">
                    <option value="TO" @if($estado=="TO"){{"selected"}}@endif>Todos</option>
                    <option value="{{Ticket::ESTADO_ABIERTO}}"@if($estado==Ticket::ESTADO_ABIERTO){{"selected"}}@endif>{{Ticket::obtenerNombreEstado(Ticket::ESTADO_ABIERTO)}}</option>
                    <option value="{{Ticket::ESTADO_RESPONDIDO}}"@if($estado==Ticket::ESTADO_RESPONDIDO){{"selected"}}@endif>{{Ticket::obtenerNombreEstado(Ticket::ESTADO_RESPONDIDO)}}</option>
                    <option value="{{Ticket::ESTADO_ENVIADO}}"@if($estado==Ticket::ESTADO_ENVIADO){{"selected"}}@endif>{{Ticket::obtenerNombreEstado(Ticket::ESTADO_ENVIADO)}}</option>
                    <option value="{{Ticket::ESTADO_PROCESANDO}}"@if($estado==Ticket::ESTADO_PROCESANDO){{"selected"}}@endif>{{Ticket::obtenerNombreEstado(Ticket::ESTADO_PROCESANDO)}}</option>
                    <option value="{{Ticket::ESTADO_CERRADO}}"@if($estado==Ticket::ESTADO_CERRADO){{"selected"}}@endif>{{Ticket::obtenerNombreEstado(Ticket::ESTADO_CERRADO)}}</option>
                </optgroup>
            </select>
        </div>
        <div class="col-lg-4">
            <select name="tipo" class="form-control">
                <optgroup label="Tipo de soporte">
                    <option value="TO"@if($tipo=="TO"){{"selected"}}@endif>Todos</option>
                    <option value="{{Ticket::TIPO_GENERAL}}"@if($tipo==Ticket::TIPO_GENERAL){{"selected"}}@endif>{{Ticket::obtenerNombreTipo(Ticket::TIPO_GENERAL)}}</option>
                    <option value="{{Ticket::TIPO_COMERCIAL}}"@if($tipo==Ticket::TIPO_COMERCIAL){{"selected"}}@endif>{{Ticket::obtenerNombreTipo(Ticket::TIPO_COMERCIAL)}}</option>
                    <option value="{{Ticket::TIPO_FACTURACION}}"@if($tipo==Ticket::TIPO_FACTURACION){{"selected"}}@endif>{{Ticket::obtenerNombreTipo(Ticket::TIPO_FACTURACION)}}</option>
                    <option value="{{Ticket::TIPO_TECNICO}}"@if($tipo==Ticket::TIPO_TECNICO){{"selected"}}@endif>{{Ticket::obtenerNombreTipo(Ticket::TIPO_TECNICO)}}</option>
                </optgroup>
            </select>
        </div>
        <div class="col-lg-4">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar... (Nombre cliente, TicketID)" value="@if(!is_null($buscar)){{$buscar}}@endif"> 
        </div>
        <div class="col-lg-1">
            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Filtrar</button>
        </div>
    </div>
</form>



<hr style="clear: both;">
@include("interfaz/mensaje/index",array("id_mensaje"=>2))
<br/>
<table id="tabla-tickets" class="table table-bordered table-striped">
    <tr><th>TicketID</th><th>Cliente</th><th>Atendido</th><th>Asunto</th><th>Tipo</th><th>Fecha</th><th>Estado</th><th></th></tr>
    @foreach($tickets as $ticket)
    <?php
    $cliente = $ticket->user;
    if (!is_numeric($ticket->usuario_soporte))
        $soporte = "No";
    else {
        $user_soporte = User::find($ticket->usuario_soporte);
        $soporte = $user_soporte->nombres;
    }
    ?>
    <tr>
        <td>{{$ticket->id}}</td>
        <td>{{$cliente->nombres}}</td>
        <td>{{$soporte}}</td>
        <td>{{$ticket->asunto}}</td>
        <td>{{$ticket->tipo}}</td>
        <td>{{$ticket->fecha}}</td>
        <td class="ticket-{{$ticket->estado}}">{{$ticket->estado}}</td>
        <td class="text-center"><a href="{{Route("soporte.show",$ticket->id)}}"><button class="btn-xs btn-info"><span class="glyphicon glyphicon-exclamation-sign"></span> Ver/Atender</button></a></td>
    </tr>
    @endforeach
</table>


{{$tickets->links()}}

@stop



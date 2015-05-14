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

@section("titulo") {{trans("interfaz.menu.principal.ayuda.soporte")}}: {{trans("menu_ayuda.soporte.tickets.info.atencion_cliente")}} @stop

@section("css")
@include("usuarios/tipo/regular/ayuda/soporte/comp/css-tipos")
@stop

@section("contenido") 

<h1>{{trans("interfaz.menu.principal.ayuda.soporte")}}: {{trans("menu_ayuda.soporte.tickets.info.atencion_cliente")}}</h1>
<hr/>
{{-- MAPA DE NAVEGACION --}}
<ol class="breadcrumb">
    <li><a href="{{URL::to("/")}}">{{trans("interfaz.menu.principal.inicio")}}</a></li>
    <li class="active">{{trans("interfaz.menu.principal.ayuda.soporte")}}</li>
</ol>

<div class="well">
    <p class="text-justify">
        {{trans("menu_ayuda.soporte.general.descripcion")}}
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
                <optgroup label="{{trans("menu_ayuda.soporte.tickets.sel.estados")}}">
                    <option value="TO" @if($estado=="TO"){{"selected"}}@endif>{{trans("otros.info.todos")}}</option>
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
                <optgroup label="{{trans("menu_ayuda.soporte.tickets.sel.tipo")}}">
                    <option value="TO"@if($tipo=="TO"){{"selected"}}@endif>{{trans("otros.info.todos")}}</option>
                    <option value="{{Ticket::TIPO_GENERAL}}"@if($tipo==Ticket::TIPO_GENERAL){{"selected"}}@endif>{{Ticket::obtenerNombreTipo(Ticket::TIPO_GENERAL)}}</option>
                    <option value="{{Ticket::TIPO_COMERCIAL}}"@if($tipo==Ticket::TIPO_COMERCIAL){{"selected"}}@endif>{{Ticket::obtenerNombreTipo(Ticket::TIPO_COMERCIAL)}}</option>
                    <option value="{{Ticket::TIPO_FACTURACION}}"@if($tipo==Ticket::TIPO_FACTURACION){{"selected"}}@endif>{{Ticket::obtenerNombreTipo(Ticket::TIPO_FACTURACION)}}</option>
                    <option value="{{Ticket::TIPO_TECNICO}}"@if($tipo==Ticket::TIPO_TECNICO){{"selected"}}@endif>{{Ticket::obtenerNombreTipo(Ticket::TIPO_TECNICO)}}</option>
                    @if (User::esSuperAdmin() || Auth::user()->instancia == User::PARAM_INSTANCIA_SUPER_ADMIN)
                    <option value="{{Ticket::TIPO_ESPECIAL}}"@if($tipo==Ticket::TIPO_ESPECIAL){{"selected"}}@endif>{{Ticket::obtenerNombreTipo(Ticket::TIPO_ESPECIAL)}}</option>
                    @endif
                </optgroup>
            </select>
        </div>
        <div class="col-lg-4">
            <input type="text" name="buscar" class="form-control" placeholder="{{trans("menu_ayuda.soporte.tickets.info.buscar.placeholder")}}" value="@if(!is_null($buscar)){{$buscar}}@endif"> 
        </div>
        <div class="col-lg-1">
            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> {{trans("menu_ayuda.soporte.tickets.btn.filtrar")}}</button>
        </div>
    </div>
</form>



<hr style="clear: both;">
@include("interfaz/mensaje/index",array("id_mensaje"=>2))
<br/>
<table id="tabla-tickets" class="table table-bordered table-striped">
    <tr>
        <th>{{trans("menu_ayuda.soporte.tickets.col.ticket_id")}}</th>
        <th>{{trans("menu_ayuda.soporte.tickets.col.cliente")}}</th>
        <th>{{trans("menu_ayuda.soporte.tickets.col.atendido")}}</th>
        <th>{{trans("menu_ayuda.soporte.tickets.col.asunto")}}</th>
        <th>{{trans("menu_ayuda.soporte.tickets.col.tipo")}}</th>
        <th>{{trans("otros.info.ultima_actualizacion")}}</th>
        @if (User::esSuperAdmin() || Auth::user()->instancia == User::PARAM_INSTANCIA_SUPER_ADMIN)
        <th>{{trans("otros.info.instancia")}}</th>           
        @endif
        <th>{{trans("menu_ayuda.soporte.tickets.col.estado")}}</th>
        <th></th>
    </tr>
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
        <td>{{trans("otros.info.hace")}} {{Util::calcularDiferenciaFechas($ticket->updated_at,Util::obtenerTiempoActual());}}</td>

        @if (User::esSuperAdmin() || Auth::user()->instancia == User::PARAM_INSTANCIA_SUPER_ADMIN)
        <td> 
            @if($cliente->instancia==User::PARAM_INSTANCIA_SUPER_ADMIN)
            {{trans("instancias.default.super")}}
            @else
            <?php $instancia = Instancia::find($cliente->instancia); ?>
            {{$instancia->empresa}}  
            @endif
        </td>
        @endif
        <td class="ticket-{{$ticket->estado}}">{{$ticket->estado}}</td>
        <td class="text-center"><a href="{{Route("soporte.show",$ticket->id)}}"><button class="btn-xs btn-info"><span class="glyphicon glyphicon-exclamation-sign"></span> {{trans("menu_ayuda.soporte.tickets.btn.ver_atender")}}</button></a></td>
    </tr>
    @endforeach
</table>


{{$tickets->links()}}

@stop



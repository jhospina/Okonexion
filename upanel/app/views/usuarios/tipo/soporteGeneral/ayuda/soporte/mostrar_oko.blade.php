<?php
$usuario_cliente = User::find($ticket->usuario_cliente);

if (is_numeric($ticket->usuario_soporte)) {
    $usuario_soporte = User::find($ticket->usuario_soporte)->nombres;
} else {
    $usuario_soporte = "";
}
?>


@extends('interfaz/plantilla')

@section("titulo") {{trans("menu_ayuda.soporte.tickets.titulo.sing")}} #{{$ticket->id}}@stop


@section("css")
 @include("usuarios/tipo/regular/ayuda/soporte/comp/css-tipos")
@stop

@section("contenido") 


<h1>{{trans("menu_ayuda.soporte.tickets.titulo.sing")}} #{{$ticket->id}}</h1>
<hr/>



{{-- MAPA DE NAVEGACION --}}
<ol class="breadcrumb">
    <li><a href="{{URL::to("/")}}">{{trans("interfaz.menu.principal.inicio")}}</a></li>
    <li><a href="{{Route("soporte.index")}}">{{trans("interfaz.menu.principal.ayuda.soporte")}}</a></li>
    <li class="active">{{trans("menu_ayuda.soporte.tickets.titulo.sing")}} #{{$ticket->id}}</li>
</ol>


<h2><span class="label label-primary">{{trans("menu_ayuda.soporte.tickets.col.asunto")}}</span> <span style="top: 5px;position: relative;">{{$ticket->asunto}}</span></h2>
<hr/>


<div class="col-lg-4">

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">{{Util::convertirMayusculas(trans("menu_ayuda.soporte.tickets.info.fecha_creacion"))}}</h3>
        </div>
        <div class="panel-body">
            {{$ticket->fecha}}
        </div>
    </div>
</div>
<div class="col-lg-4">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">{{Util::convertirMayusculas(trans("menu_ayuda.soporte.tickets.crear.info.tipo_soporte"))}}</h3>
        </div>
        <div class="panel-body">
            {{$ticket->tipo}}
        </div>
    </div>
</div>
<div class="col-lg-4">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">{{Util::convertirMayusculas(trans("menu_ayuda.soporte.tickets.col.estado"))}}</h3>
        </div>
        <div class="panel-body ticket-{{$ticket->estado}}">
            {{$ticket->estado}}
        </div>
    </div>
</div>
<div class="clear" style="clear:both;"></div>
@include("interfaz/mensaje/index",array("id_mensaje"=>2))
<hr/>

<div class="block">

    @if($ticket->estado!="Cerrado")

    {{--BOTON RESPONDER--}}
    <div class="col-lg-6" style="margin-bottom:10px;"><button class="btn btn-success" id="bt-responder"><span class="glyphicon glyphicon-comment" style="color:white;"></span> {{trans("menu_ayuda.soporte.tickets.btn.responder")}}</button></div> 

    {{Form::model(null,array('route' => array('soporte.update', $ticket->id),'method' => 'put','rol' => 'form'),array('role' => 'form')) }}
    {{--BOTON CERRAR TICKET--}}
    <div class="col-lg-6 text-right" style="margin-bottom:10px;"><button class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-ban-circle" style="color:white;"></span> {{trans("menu_ayuda.soporte.tickets.btn.cerrar_ticket")}}</button>
        {{Form::hidden('action',"cerrar")}}
    </div>
    {{Form::close()}}

    <div class="container" id="content-form">
        {{Form::model(null,array('route' => array('soporte.update', $ticket->id),'method' => 'put','enctype' => 'multipart/form-data','rol' => 'form',"id"=>"respuesta-form"),array('role' => 'form')) }}
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            {{ Form::textarea('mensaje', null, array('placeholder' => trans("otros.msj_responder.placeholder"), 'class' => 'form-control',"maxlength"=>1000)) }}
            {{ Form::hidden('action',"mensaje",null)}}
            <div class="col-lg-6" style="padding: 0px;margin-top: 10px;"> <a  href="#" id="tooltip" rel="tooltip" title="{{trans("otros.extensiones_permitidas")}}: png, jpeg, gif, pdf, zip"> {{Form::file('adjunto',array("id"=>"adjunto","rel"=>"tooltip","accept"=>"image/*,application/pdf,application/zip","class"=>"filestyle","data-input"=>"false","data-buttonText"=>trans("otros.adjuntar_archivo")));}}</a> </div>
            <div class="col-lg-6 text-right" style="padding: 0px;margin-top: 10px;">{{ Form::button("<span class='glyphicon glyphicon-plus glyphicon-ok-circle'></span> ".trans("menu_ayuda.soporte.tickets.btn.enviar"), array('type' => 'submit', 'class' => 'btn btn-primary',"id"=>"btn-enviar")) }} </div>
        </div>

        <div class="col-lg-2"></div>
        {{Form::hidden('ref',"assist")}}
        {{Form::close()}}
    </div>

    @endif

    @foreach($mensajes as  $mensaje)
    <?php $user = $mensaje->user; ?>

    <div class="col-lg-6">
        @if($user->instancia!=User::PARAM_INSTANCIA_SUPER_ADMIN)
        <span class="glyphicon glyphicon-comment"></span>  {{$usuario_cliente->nombres}} // {{trans("otros.info.cliente")}} 
        @else  
        <span class="glyphicon glyphicon-comment" style="color:cornflowerblue;"></span>  <span style="color:cornflowerblue">{{$usuario_soporte}} // {{trans("otros.info.soporte.okonexion")}}</span>
        @endif 
    </div>
    <div class="col-lg-6 text-muted text-primary text-right">{{$mensaje->fecha}}</div>
    <div class="well" style="clear: both;">
        <p>{{$mensaje->mensaje}}</p>
        @if(!is_null($mensaje->url_adjunto))
        <hr style="border-style: dashed;border-color:black;"/>
        <a target="_blank" href="{{$mensaje->url_adjunto}}"><span class='glyphicon glyphicon-plus glyphicon-file'></span> {{trans("otros.archivo_adjunto")}}</a>

        @endif
    </div>

    @endforeach


    <div class="col-lg-6"> <span class="glyphicon glyphicon-comment"></span> {{$usuario_cliente->nombres}} // {{trans("otros.info.cliente")}}</div>
    <div class="col-lg-6 text-muted text-primary text-right">{{$ticket->fecha}}</div>
    <div class="well" style="clear: both;">
        <p>{{$ticket->mensaje}}</p>
        @if(!is_null($ticket->url_adjunto))
        <hr style="border-style: dashed;border-color:black;"/>
        <a target="_blank" href="{{$ticket->url_adjunto}}"><span class='glyphicon glyphicon-plus glyphicon-file'></span> {{trans("otros.archivo_adjunto")}}</a>

        @endif
    </div>
</div>

@stop



@section("script")
{{ HTML::script('assets/js/bootstrap-filestyle.min.js') }}
{{ HTML::script('assets/js/bootstrap-tooltip.js') }}
<script>
    jQuery(document).ready(function () {
        jQuery("#tooltip").tooltip({placement: "left"});
        jQuery("#bt-responder").click(function () {
            jQuery("#content-form").slideToggle();
        });
        
        jQuery("#btn-enviar").click(function(){
           jQuery(this).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans("otros.info.enviando")}}..."); 
           jQuery(this).attr("disabled","disabled");
           $( "#respuesta-form" ).submit();
        });
    });
    
    
    
</script>
@stop
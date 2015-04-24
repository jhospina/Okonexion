<?php
$tipoContenido = Contenido_PQR::nombre;
$nombreContenido = TipoContenido::obtenerNombre($app->diseno, Contenido_PQR::nombre);
?>

@extends('interfaz/plantilla')

@section("titulo") {{$app->nombre}} | {{trans("otros.info.administrar")}} {{$nombreContenido}} @stop

@section("css")


<style>
    span.{{trans("otros.user.soporte")}}{
        color:red;
    }
    
    span.{{trans("otros.user.usuario")}}{
        color:blue;
    }
</style>

@stop

@section("contenido") 


{{--NAVEGACION--}}
<div class="well well-sm">
    <a href="{{URL::to("aplicacion/administrar/pqr")}}" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> {{trans("otros.info.volver")}}</a>
</div>


<h2>{{Contenido_PQR::obtenerNombreTipo($pqr->tipo)}} #{{$pqr->id}}</h2>

@include("interfaz/mensaje/index",array("id_mensaje"=>2))

<hr/>

{{--DATOS BASICOS DEL PQR--}}

<div style="font-size:20pt;"><b>{{$pqr->titulo}}</b></div>

<div style="clear:both;"></div>
<hr/>
<div class="col-lg-12">
    <div class="col-lg-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><b>{{trans("otros.user.usuario")}}</b></h3>
            </div>
            <div class="panel-body">
                {{Contenido_PQR::cliente_obtenerNombre($pqr->id)}}
            </div>
        </div>

    </div>
    <div class="col-lg-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><b>{{trans("otros.info.email")}}</b></h3>
            </div>
            <div class="panel-body">
                {{Contenido_PQR::cliente_obtenerEmail($pqr->id)}}
            </div>
        </div>
    </div>
    
     <div class="col-lg-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><b>{{trans("otros.info.ultima_actualizacion")}}</b></h3>
            </div>
            <div class="panel-body">
                {{trans("otros.info.hace")}} {{Contenido_PQR::obtenerUltimaActualizacion($pqr->id,$pqr->created_at)}}
            </div>
        </div>
    </div>
</div>

{{-- ((FIN - DATOS BASICOS DEL PQR)) --}}



@if(!is_null($discusion))

@foreach($discusion as $mensaje)

<span class="{{Contenido_PQR::obtenerUsuarioPqr($mensaje->id)}}"><span class="glyphicon glyphicon-comment"></span> {{Contenido_PQR::obtenerUsuarioPqr($mensaje->id)}} // {{$mensaje->created_at}}</span>
<div class="well well-lg">{{$mensaje->contenido}}</div>

@endforeach

@endif


<span class="{{Contenido_PQR::obtenerUsuarioPqr($pqr->id)}}"><span class="glyphicon glyphicon-comment"></span> {{Contenido_PQR::obtenerUsuarioPqr($pqr->id)}} // {{$pqr->created_at}}</span>
<div class="well well-lg">{{$pqr->contenido}}</div>



<div class="well well-sm" style="margin: auto;width:80%;margin-bottom: 10px;display: none;" id='cont-respuesta'>
    <div style='width: 100%;text-align: center;font-size: 25pt;'><span class="glyphicon glyphicon-comment"></span></div>
    <form id='form' action='' method='POST'>
        <input type='hidden' name='id_pqr' value='{{$pqr->id}}' />
    <textarea id='respuesta' name='respuesta' class="form-control" style="width: 60%;margin: auto;height: 150px;" placeholder="{{trans("otros.msj_responder.placeholder")}}"></textarea>
    </form>
</div>

@if($pqr->tipo!=Contenido_PQR::tipo(Contenido_PQR::nombre_sugerencia))

<div class="col-lg-12 text-center" style="margin-bottom: 10px;"><button id="btn-responder" class="btn btn-success" style="width:40%;font-size: 16pt;text-transform: uppercase;"><span class="glyphicon glyphicon-comment"></span> {{trans("otros.info.responder")}}</button></div>

@endif

@stop

@section("script")

<script>
    var respuesta=false;
    $("#btn-responder").click(function(){
       
        if(!respuesta)
        {
            $(this).html("<span class='glyphicon glyphicon-ok'></span> {{trans('otros.info.enviar')}}");
             $("#cont-respuesta").slideToggle();
             respuesta=true;
        }
        
        if(respuesta==true && $("#respuesta").val().length>0){
            $(this).attr("disabled","disabled");
            $(this).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.enviando')}}...");
            $("#form").submit();
        }
        
    });
 </script>

@stop
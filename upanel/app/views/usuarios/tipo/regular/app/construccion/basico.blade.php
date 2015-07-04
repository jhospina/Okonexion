<?php
$num_plats = Auth::user()->getNumeroPlataformas();
$mockups = Aplicacion::mockups();
$form_data = array('action' => 'UPanelControladorAplicacion@guardarBasico', 'method' => 'post', 'enctype' => 'multipart/form-data', "id" => "form", "style" => "clear: both;margin-top: 15px;");

if (Aplicacion::existe()) {
    $estado = $app->estado;
    if (!isset($app))
        $app = Aplicacion::obtener();
    if (!isset($version))
        $version = ProcesoApp::obtenerNumeroVersion($app->id);

    $plataformas_seleccionadas = json_decode(User::obtenerValorMetadato(UsuarioMetadato::PLATAFORMAS_SELECCIONADAS));
    list($android, $ios, $windows) = AppDesing::obtenerDisponibilidadPlataformas($app->diseno);

    $plataformas = "";

    if ($android == true && !in_array(AppDesing::PLATAFORMA_ANDROID, $plataformas_seleccionadas))
        $plataformas.="<span data-select='false' data-plataforma='" . AppDesing::PLATAFORMA_ANDROID . "' class='img-plataform tooltip-top' rel='tooltip' title='Android'><img id='plat-android' src='" . URL::to("assets/img/android.png") . "' /></span>";
    if ($ios == true && !in_array(AppDesing::PLATAFORMA_IOS, $plataformas_seleccionadas))
        $plataformas.="<span data-select='false' data-plataforma='" . AppDesing::PLATAFORMA_IOS . "' class='img-plataform tooltip-top' rel='tooltip' title='IOS Iphone'><img id='plat-ios' src='" . URL::to("assets/img/ios.png") . "' /></span>";
    if ($windows == true && !in_array(AppDesing::PLATAFORMA_WINDOW, $plataformas_seleccionadas))
        $plataformas.="<span data-select='false' data-plataforma='" . AppDesing::PLATAFORMA_WINDOW . "' class='img-plataform tooltip-top' rel='tooltip' title='Windows Phone'><img id='plat-windows' src='" . URL::to("assets/img/windows.png") . "' /></span>";
} else {
    $estado = null;
    $version = 0;
}
?>

@extends('interfaz/plantilla')

@section("titulo"){{trans("app.hd.mi_aplicacion")}}@stop

@section("css")

<style>
    #num-plat{
        background-color: black;
        padding: 5px;
        color: white;
        font-weight: bold;
        font-family: calibri;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
    }

    #load-plataform .img-plataform{
        padding: 10px;
        border: 1px rgb(61, 99, 247) solid;
        display: inline-block;
        width: 100px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        background-color: rgb(55, 55, 55);
        max-height: 100px;
        text-align: center;
        margin: 0px 10px;
        -webkit-box-shadow: 3px 3px 5px 0px rgba(0,0,0,0.75);
        -moz-box-shadow: 3px 3px 5px 0px rgba(0,0,0,0.75);
        box-shadow: 3px 3px 5px 0px rgba(0,0,0,0.75);
        border-bottom-width: 2px;
        border-right-width: 2px;
        cursor:pointer;
    }

    #load-plataform .img-plataform:hover{
        background: rgb(102, 102, 102);
    }

    #load-plataform .img-plataform img{
        height: 80px;
    }

</style>
@stop

@section("contenido")  

{{--CABECERA--}}
@include("usuarios/tipo/regular/app/construccion/secciones/cabecera")

{{--BARRA DE PROGRESO--}}
@include("usuarios/tipo/regular/app/construccion/secciones/barra-progreso") 

{{--MENU DE NAVEGACIÓN ENTRE SECCCION--}}
@include("usuarios/tipo/regular/app/construccion/secciones/nav")


<div class="col-lg-9" id="content-config">

    <h2 class="text-right">{{Util::convertirMayusculas(trans("interfaz.menu.principal.mi_aplicacion.configuracion.datos_basicos"))}}</h2>

    <hr/>


    @if(is_null($estado) || $app->estado==Aplicacion::ESTADO_LISTA_PARA_ENVIAR || $app->estado==Aplicacion::ESTADO_ESTABLECIENTO_TEXTOS || $app->estado==Aplicacion::ESTADO_EN_DISENO || $app->estado==Aplicacion::ESTADO_EN_PROCESO_DE_ACTUALIZACION)

    @include("interfaz/mensaje/index",array("id_mensaje"=>3))

    {{Form::model($app, $form_data, array('role' => 'form')) }}
    <div class="col-lg-4 text-default input-lg">{{trans("app.config.info.nombre")}}</div> <div class="col-lg-8">{{ Form::text('nombre', null, array('placeholder' => trans("app.config.info.nombre.placeholder"), 'class' => 'form-control input-lg')) }}</div>
    <div class="col-lg-12" style="margin-top: 10px;">
        <div class="panel panel-primary" style="clear: both;margin-top: 10px;">
            <div class="panel-heading">
                <h3 class="panel-title">{{trans("app.config.info.diseno")}}</h3>
            </div>
            <div class="panel-body">
                <div class="well well-sm">{{trans("app.config.info.diseno.descripcion")}}</div>
                @foreach($mockups as $nombre => $url)

                <span class="tooltip-mockup" rel="tooltip" title="{{AppDesing::obtenerDescripcion($nombre)}}">
                    <img style="cursor: pointer;" id="mockup-{{$nombre}}" onClick="seleccionarMockup('{{$nombre}}','{{$url}}');" src="@if(!is_null($app))@if($app->diseno==$nombre){{URL::to("assets/img/app/".$nombre."_select.png")}}@endif @else{{$url}}@endif"/>
                </span>
                @endforeach

                <input type="hidden" name="mockup" id="mockup" value="@if(!is_null($app)){{$app->diseno}}@endif">
            </div>
        </div>
    </div>

    <div class="col-lg-12" style="margin-top: 10px;">
        <div class="panel panel-primary" style="clear: both;margin-top: 10px;">
            <div class="panel-heading">
                <h3 class="panel-title">{{trans("app.config.info.plataformas")}}</h3>
            </div>
            <div class="panel-body">
                <div class="well well-sm">
                    {{trans("app.config.info.plataformas.descripcion")}}
                </div>

                <div id="load-plataform" class="text-center">

                    @if(Aplicacion::existe())

                    @foreach($plataformas_seleccionadas as $index => $plat)

                    <span data-select='true' @if($version>0){{"data-block='block'"}}@endif style="background-color: rgb(61, 99, 247);" data-plataforma='{{$plat}}' class='img-plataform tooltip-top' rel='tooltip' title='Android'><img id='plat-android' src="{{URL::to('assets/img/'.$plat.'.png')}}" /></span>      

                    @endforeach

                    {{$plataformas}}

                    @else                  
                    <div class="text-center" style="width: 50%;margin:auto;border:1px rgb(178, 178, 178) solid;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;background-color: rgb(236, 236, 236);">
                        <h4><span class="glyphicon glyphicon-exclamation-sign"></span> {{trans("app.config.info.plataformas.seleccionar")}}</h4>
                    </div>                
                    @endif

                </div>
                <input type="hidden" id="{{UsuarioMetadato::PLATAFORMAS_SELECCIONADAS}}" name="{{UsuarioMetadato::PLATAFORMAS_SELECCIONADAS}}" value="@if(isset($plataformas_seleccionadas))<?php echo Util::formatearResultadosArray($plataformas_seleccionadas, null, "|", "|"); ?>@endif"/>
            </div>
            <div class="panel-footer panel-primary">
                <b>{{trans("app.config.info.plataformas.conteo")}}</b><span id="num-plat">{{$num_plats}}</span>
            </div>
        </div>
    </div>


    <div class="col-lg-12 text-right" style="margin-bottom:20px;"> {{ Form::button(trans("otros.btn.guardar"), array('type' => 'button', 'class' => 'btn btn-primary','id'=>"btn-guardar")) }}    </div>

    {{Form::close()}}

    @else
    {{--CUANDO LOS DATOS NO ESTAN DISPONIBLES PARA MODIFICARSE--}}
    @include("usuarios/tipo/regular/app/construccion/secciones/form-basico-lock") 
    @endif

</div>



@stop

@if(is_null($estado) || $app->estado==Aplicacion::ESTADO_LISTA_PARA_ENVIAR || $app->estado==Aplicacion::ESTADO_ESTABLECIENTO_TEXTOS || $app->estado==Aplicacion::ESTADO_EN_DISENO || $app->estado==Aplicacion::ESTADO_EN_PROCESO_DE_ACTUALIZACION)


@section("script")

{{ HTML::script('assets/js/bootstrap-filestyle.min.js') }}

<script>

            $(".img-plataform").click(function(){

    if ($(this).attr("data-block") == "block")
            return;
            if ($(this).attr("data-select") == "false")
            $(this).attr("data-select", "true");
            else
            $(this).attr("data-select", "false");
            procesarPlataformas();
    });</script>


<script>

            var num_plataformas_permitidas = {{$num_plats}};
            jQuery(document).ready(function () {

    jQuery(".tooltip-mockup").tooltip({placement: "left"});
            jQuery("#btn-guardar").click(function () {

    jQuery(this).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.procesando')}}...");
            jQuery(this).attr("disabled", "disabled");
            @if ($version == 0)
            $("#progress-bar").animate({width:"15%"}, 2000, function(){
    $("#text-progress").html("15% ({{Aplicacion::obtenerNombreEstado(Aplicacion::ESTADO_EN_DISENO)}})");
            $(this).removeClass("progress-bar-danger");
            $(this).addClass("progress-bar-default");
    });
            @endif
            setTimeout(function(){
            $("#form").submit();
            }, 2500);
    });
    });
            function seleccionarMockup(nombre, url) {
            jQuery("#mockup-" + nombre).attr("src", "" + url.replace(".png", "_select.png"));
                    jQuery("#mockup").val(nombre);
                    $("#load-plataform").html("<div class='text-center'><img src='{{URL::to('assets/img/loaders/gears.gif')}}'/></div>");
                    jQuery.ajax({
                    type: "POST",
                            url: "{{URL::to('aplicacion/basico/ajax/plataformas')}}",
                            data: {mockup:nombre},
                            success: function (response) {

                            data = jQuery.parseJSON(response);
                                    $("#load-plataform").html(data.plataformas);
                                    $(".img-plataform").click(function(){

                            if ($(this).attr("data-select") == "block")
                                    return;
                                    if ($(this).attr("data-select") == "false")
                                    $(this).attr("data-select", "true");
                                    else
                                    $(this).attr("data-select", "false");
                                    procesarPlataformas();
                            });
                            }}, "html");
            }


</script>



<script>

    function procesarPlataformas(){

    var control = 0;
            var seleccionados = $("#{{UsuarioMetadato::PLATAFORMAS_SELECCIONADAS}}").val();
            $(".img-plataform").each(function(){

    if ($(this).attr("data-select") == "true"){
    if (control < num_plataformas_permitidas){
    $(this).css("background-color", "rgb(61, 99, 247)");
            control++;
            seleccionados = seleccionados.replace("|" + $(this).attr("data-plataforma") + "|", "");
            seleccionados += "|" + $(this).attr("data-plataforma") + "|";
            $("#{{UsuarioMetadato::PLATAFORMAS_SELECCIONADAS}}").val(seleccionados);
    } else{
        if ($(this).attr("data-block") == "block")
            return;
    seleccionados = seleccionados.replace("|" + $(this).attr("data-plataforma") + "|", "");
            $("#{{UsuarioMetadato::PLATAFORMAS_SELECCIONADAS}}").val(seleccionados);
            $(this).attr("data-select", "false");
            $(this).css("background-color", "rgb(55, 55, 55)");
    }
    } else{
        if ($(this).attr("data-block") == "block")
            return;
        
    $(this).css("background-color", "rgb(55, 55, 55)");
            seleccionados = seleccionados.replace("|" + $(this).attr("data-plataforma") + "|", "");
            $("#{{UsuarioMetadato::PLATAFORMAS_SELECCIONADAS}}").val(seleccionados);
    }

    });
    }

</script>
@stop

@endif
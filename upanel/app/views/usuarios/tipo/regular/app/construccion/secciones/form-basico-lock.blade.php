<div class="col-lg-4 text-default input-lg">{{trans("app.config.info.nombre")}}</div> <div class="col-lg-8 input-lg">{{ $app->nombre }}</div>
<div class="col-lg-12" style="margin-top: 10px;">
    <div class="panel panel-primary" style="clear: both;margin-top: 10px;">
        <div class="panel-heading">
            <h3 class="panel-title">{{trans("app.config.info.diseno")}}</h3>
        </div>
        <div class="panel-body">
            <div class="well well-sm">{{trans("app.config.info.info.plantilla_escogida")}}</div>
            @foreach($mockups as $nombre => $url)

            <span class="tooltip-mockup" rel="tooltip" title="{{AppDesing::obtenerDescripcion($nombre)}}">
                <img style="cursor: pointer;" id="mockup-{{$nombre}}" src="@if(!is_null($app))@if($app->diseno==$nombre){{URL::to("assets/img/app/".$nombre."_select.png")}}@endif @else{{$url}}@endif"/>
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
                <div id="load-plataform" class="text-center">

                    @if(Aplicacion::existe())

                    @foreach($plataformas_seleccionadas as $index => $plat)

                    <span data-select='true' style="background-color: rgb(61, 99, 247);" data-plataforma='{{$plat}}' class='img-plataform tooltip-top' rel='tooltip' title='Android'><img id='plat-android' src="{{URL::to('assets/img/'.$plat.'.png')}}" /></span>      

                    @endforeach

                    {{$plataformas}}

                    @else                  
                    <div class="text-center" style="width: 50%;margin:auto;border:1px rgb(178, 178, 178) solid;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;background-color: rgb(236, 236, 236);">
                        <h4><span class="glyphicon glyphicon-exclamation-sign"></span> {{trans("app.config.info.plataformas.seleccionar")}}</h4>
                    </div>                
                    @endif

                </div>
                <input type="hidden" id="{{UsuarioMetadato::PLATAFORMAS_SELECCIONADAS}}" name="{{UsuarioMetadato::PLATAFORMAS_SELECCIONADAS}}" value="@if(isset($plataformas_seleccionadas))<?php echo Util::formatearResultadosArray($plataformas_seleccionadas,null,"|","|"); ?>@endif"/>
            </div>
            <div class="panel-footer panel-primary">
                <b>{{trans("app.config.info.plataformas.conteo")}}</b><span id="num-plat">{{$num_plats}}</span>
            </div>
        </div>
    </div>
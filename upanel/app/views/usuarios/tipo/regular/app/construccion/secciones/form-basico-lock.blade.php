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
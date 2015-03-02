<div class="col-lg-4 text-default input-lg">Nombre de la aplicación</div> <div class="col-lg-8 input-lg">{{ $app->nombre }}</div>
<div class="col-lg-12" style="margin-top: 10px;">
    <div class="panel panel-primary" style="clear: both;margin-top: 10px;">
        <div class="panel-heading">
            <h3 class="panel-title">Diseño</h3>
        </div>
        <div class="panel-body">
            <div class="well well-sm">Plantilla de diseño escogida</div>
            @foreach($mockups as $nombre => $url)

            <span class="tooltip-mockup" rel="tooltip" title="Diseño en cuadricula para navegar entre cada sección de la aplicación.">
                <img style="cursor: pointer;" id="mockup-{{$nombre}}" src="@if(!is_null($app))@if($app->diseno==$nombre){{URL::to("assets/img/app/".$nombre."_select.png")}}@endif @else{{$url}}@endif"/>
            </span>
            @endforeach

            <input type="hidden" name="mockup" id="mockup" value="@if(!is_null($app)){{$app->diseno}}@endif">
        </div>
    </div>
</div>
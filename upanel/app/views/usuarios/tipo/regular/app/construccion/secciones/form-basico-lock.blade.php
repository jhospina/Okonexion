<div class="col-lg-4 text-default input-lg">{{trans("app.config.info.nombre")}}</div> <div class="col-lg-8 input-lg">{{ $app->nombre }}</div>
    <div class="col-lg-12" style="margin-top: 10px;">
        <div class="panel panel-primary" style="clear: both;margin-top: 10px;">
            <div class="panel-heading">
                <h3 class="panel-title">{{trans("app.config.info.diseno")}}</h3>
            </div>
            <div class="panel-body" id="seleccion-apps-basico">
                <div class="well well-sm">{{trans("app.config.info.info.plantilla_escogida")}}</div>
                @foreach($mockups as $index => $nombre)
                <?php
                $imagenesApp = array_reverse(ArchivosCTR::obtenerListadoArchivos("assets/img/app/" . $nombre . "/"));
                list($android, $ios, $windows) = AppDesing::obtenerDisponibilidadPlataformas($nombre);
                ?>
                <div class="content-app-info">
                    <div class="content-1">
                        <div class="slide-imagenes">
                            <?php for ($i = 0; $i < count($imagenesApp); $i++): ?>
                                <img class="img-rounded" style="{{($i==0)?"display:block":"display:none"}}" src="{{URL::to($imagenesApp[$i])}}">
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="content-2">
                        <div class="titulo-app">{{trans("diseno/".$nombre.".titulo")}}</div>
                        <div class="descripcion-app">{{trans("diseno/".$nombre.".descripcion")}}</div>
                        <div class="plataformas-app">
                            @if($android)
                            <img class="tooltip-top" title="Android" src="{{URL::to("assets/img/android.png")}}"/>
                            @endif
                            @if($ios)
                            <img class="tooltip-top" title="IOS" src="{{URL::to("assets/img/ios.png")}}"/>
                            @endif
                            @if($windows)
                            <img class="tooltip-top" title="Windows" src="{{URL::to("assets/img/windows.png")}}"/>
                            @endif
                        </div>
                        <div class="seleccion-app">
                            <button class="btn {{(!is_null($app) && $app->diseno==$nombre)?'btn-danger disabled':'btn-success'}}" type="button">@if(!is_null($app) && $app->diseno==$nombre) <span class="glyphicon glyphicon-ok"></span> {{trans("otros.info.seleccionado")}} @else <span class="glyphicon glyphicon-phone"></span> {{trans("otros.info.seleccionar")}} @endif</button>
                        </div>
                    </div>
                </div>

                @endforeach

                <input type="hidden" name="mockup" id="mockup" value="@if(!is_null($app)){{$app->diseno}}@endif">
            </div>
        </div>
    </div>

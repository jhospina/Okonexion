<div class="panel panel-primary" style="clear: both;">
    <div class="panel-heading">
        <h3 class="panel-title">{{trans("app.config.info.panel.titulo.logo_aplicacion")}}</h3>
    </div>
    <div class="panel-body">
        <div class="well well-lg">
            {{trans("app.config.info.panel.titulo.logo_aplicacion.descripcion")}}
        </div>
        {{--ICONO DE LA APLICACION--}}
        <div class="col-lg-12 uploadIconMenu">
            <a  href="#" class="tooltip-left" rel="tooltip" title="{{trans('otros.extensiones_permitidas')}}: png, jpeg. Max 500Kb"> 
                <input name="{{Aplicacion::configLogoApp}}" id="{{Aplicacion::configLogoApp}}" accept="image/*" type="file" multiple=true>
            </a>
        </div>

    </div>
</div>
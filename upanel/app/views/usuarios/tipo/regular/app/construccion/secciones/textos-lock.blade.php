    <div class="panel panel-primary" style="clear: both;">
        <div class="panel-heading">
            <h3 class="panel-title">{{trans("app.config.txt.info.textos_aplicacion")}} @include("interfaz/util/tooltip-ayuda",array("descripcion"=>trans("app.config.txt.info.textos_aplicacion.ayuda"))) </h3>
        </div>
        <div class="panel-body">
            
            <form id="form" action="" method="POST"> 
            <?php $n=0; ?>
            @foreach($textos as $clave => $valor)
            <?php $n++; ?>
            <div class="col-lg-4 text-default input-lg">{{Util::eliminarPluralidad(trans("interfaz.menu.principal.mi_aplicacion.configuracion.textos"))}} {{$n}}</div> <div class="col-lg-8"><input type="text" disabled="disabled" class="form-control input-lg" value="{{$valor}}"/></div>
            @endforeach
            </form>
                
        </div>
    </div>
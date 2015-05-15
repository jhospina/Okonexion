

<h3>{{trans("otros.info.detalles")}}</h3>

<table class="table table-condensed table-bordered" style="-webkit-border-radius: 5px;
       -moz-border-radius: 5px;
       border-radius: 5px;">
    <tr><th class="detalles_col">{{trans("app.info.numero_version")}}</th><td class="detalles_num">{{$version}}</td></tr>
    <tr><th class="detalles_col">{{trans("app.info.fecha.finalizacion.version")}}</th><td class="detalles_num">{{$proceso->fecha_finalizacion}}</td></tr>
</table>
<form id="form-nueva-version" method="POST" action="{{URL::to("aplicacion/desarrollo/iniciar/actualizacion")}}">
    <button type="button" id="btn-crear-version" class="btn btn-danger" style="width: 100%;font-size: 13pt;"><span class="glyphicon glyphicon-new-window"></span> {{trans("app.btn.crear.version")}}</button>
</form>



<div id="modal-version" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{trans("app.dep.crear.version.descripcion.titulo")}}</h4>
            </div>
            <div class="modal-body">
                <p>{{trans("app.dep.crear.version.descripcion")}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{trans("otros.info.cancelar")}}</button>
                <button type="button" class="btn btn-primary" id="btn-continuar">{{trans("otros.info.continuar")}}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>

    jQuery("#btn-crear-version").click(function () {
        $('#modal-version').modal('show');
    });

    jQuery("#btn-continuar").click(function () {

        $('#modal-version').modal('hide');

        jQuery("#btn-crear-version").html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('app.info.post.creando.version')}}...");
        jQuery("#btn-crear-version").attr("disabled", "disabled");
      
        setTimeout(function () {
            $("#form-nueva-version").submit();
        }, 2500);
    });
</script>
<!-- Modal -->
<div class="modal fade" id="modal-mensaje" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{$titulo}}</h4>
            </div>
            <div class="modal-body">
                {{$mensaje}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-entendido" data-dismiss="modal">{{trans("otros.info.entendido")}}</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $("#modal-mensaje").modal("show");
    });
</script>
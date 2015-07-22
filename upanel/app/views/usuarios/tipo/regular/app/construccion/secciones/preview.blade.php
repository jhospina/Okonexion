
<div id="preview" class="pos-bottom">
    <div id="drag-bar" >{{trans("app.info.previsualizacion")}}</div>
    <div id="content-silueta">
        <img id="silueta" src="{{URL::to('assets/img/phone.png')}}"/>
        <div id="preview-app">
            @include("usuarios/tipo/regular/app/construccion/disenos/".$app->diseno."/preview",array("app"=>$app))
        </div>
    </div>
</div>

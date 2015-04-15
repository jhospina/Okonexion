<div class="panel panel-primary" style="clear: both;">
    <div class="panel-heading">
        <h3 class="panel-title">{{trans("app.config.info.panel.titulo.logo_aplicacion")}}</h3>
    </div>
    <div class="panel-body">      
        <?php echo(!is_null($logo)) ? "<img src='" . $logo . "' class='file-preview-image'/>" : "<h3>".trans("app.config.info.sin_logo")."</h3>"; ?>
    </div>
</div>
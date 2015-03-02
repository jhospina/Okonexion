<div class="panel panel-primary" style="clear: both;">
    <div class="panel-heading">
        <h3 class="panel-title">Logo de la aplicación</h3>
    </div>
    <div class="panel-body">      
        <?php echo(!is_null($logo)) ? "<img src='" . $logo . "' class='file-preview-image'/>" : "<h3>Sin logo</h3>"; ?>
    </div>
</div>
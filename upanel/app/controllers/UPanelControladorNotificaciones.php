<?php

class UPanelControladorNotificaciones extends Controller {

    function ajax_establecerVisto() {
        if (!Request::ajax())
            return;

        Notificacion::visto();

        return json_encode(array());
    }

}

<?php

class UPanelControladorFacturacion extends \BaseController {

    function vista_suscripcion_planes() {

        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        return View::make("usuarios/tipo/regular/facturacion/suscripcion/planes");
    }

    function vista_suscripcion_ciclos($plan) {
        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        return View::make("usuarios/tipo/regular/facturacion/suscripcion/ciclos")->with("plan", $plan);
    }

}

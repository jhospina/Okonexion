<?php

class UPanelControladorPresentacion extends Controller {

    function index() {

        if (!Auth::check()) {
            return User::login();
        }

        if (Auth::user()->tipo == User::USUARIO_REGULAR)
        //USUARIO REGULAR
            return $this->indexRegular();
        //USUARIO SOPORTE GENERAL
        if (Auth::user()->tipo == User::USUARIO_SOPORTE_GENERAL)
            return $this->indexSoporteGeneral();
        //USUARIO ADMIN
        if (Auth::user()->tipo == User::USUARIO_ADMIN)
            return $this->indexAdministrador();
    }

    //Panel principal para el usuario regular
    function indexRegular() {
        return View::make("usuarios/tipo/regular/index");
    }

    function indexSoporteGeneral() {
        return View::make("usuarios/tipo/soporteGeneral/index");
    }

    function indexAdministrador() {
        return View::make("usuarios/tipo/admin/index");
    }

}

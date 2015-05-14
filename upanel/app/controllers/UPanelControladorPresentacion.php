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
        //Verifica los datos del perfil
        $data = $this->verificarPerfil();
        return View::make("usuarios/tipo/regular/index", $data);
    }

    function indexSoporteGeneral() {
        return View::make("usuarios/tipo/soporteGeneral/index");
    }

    function indexAdministrador() {
        return View::make("usuarios/tipo/admin/index");
    }

    function verificarPerfil() {
        $user = Auth::user();
        if ($user->dni == null || $user->pais == null || $user->region == null || $user->ciudad == null || $user->direccion == null)
            return User::mensaje("advertencia", "text-center", trans("interfaz.msj.ad.completar.perfil", array("nombre" => $user->nombres, "link" => Route("usuario.edit", Auth::user()->id))));
        elseif ($user->telefono == null && $user->celular == null)
            return User::mensaje("advertencia", "text-center", trans("msj.ad.completar.perfil.telefono.celular", array("nombre" => $user->nombes, "link" => Route("usuario.edit", Auth::user()->id))));
        else
            return array();
    }

}

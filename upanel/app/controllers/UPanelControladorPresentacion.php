<?php

class UPanelControladorPresentacion extends Controller {

    function index() {
        
        // Verificamos que el usuario esté autenticado
        if (Auth::check()) {
            if (Auth::user()->tipo == User::USUARIO_REGULAR)
            //USUARIO REGULAR
                return $this->indexRegular();
            //USUARIO SOPORTE GENERAL
            if (Auth::user()->tipo == User::USUARIO_SOPORTE_GENERAL)
                return $this->indexSoporteGeneral();
        } else {
            return Redirect::to("http://" . $_SERVER["SERVER_NAME"] . "/ingresar");
        }
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

    function verificarPerfil() {
        $user = Auth::user();
        if ($user->dni == null || $user->pais == null || $user->region == null || $user->ciudad == null || $user->direccion == null)
            return User::mensaje("advertencia", "text-center",trans("interfaz.msj.ad.completar.perfil",array("nombre"=>$user->nombres,"link"=>Route("usuario.edit", Auth::user()->id))));
        elseif ($user->telefono == null && $user->celular == null)
            return User::mensaje("advertencia", "text-center",trans("msj.ad.completar.perfil.telefono.celular",array("nombre"=>$user->nombes,"link"=>Route("usuario.edit", Auth::user()->id))));
        else
            return array();
    }

}

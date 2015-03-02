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
            return User::mensaje("advertencia", "text-center", "¡Hola " . $user->nombres . " necesitamos saber más de ti! Por favor completa tu perfil haciendo clic <a href='" . Route("usuario.edit", Auth::user()->id) . "'>Aquí.<a/>");
        elseif ($user->telefono == null && $user->celular == null)
            return User::mensaje("advertencia", "text-center", "" . $user->nombres . " necesitamos saber un número de teléfono o celular para comunicarnos contigo. Has clic <a href='" . Route("usuario.edit", Auth::user()->id) . "'>Aquí.<a/>");
        else
            return array();
    }

}

<?php

class UPanelControladorConfiguracion extends \BaseController {

    function vista_general() {
        if (!Auth::check()) {
            return User::login();
        }

        if (!is_null($acceso = User::validarAcceso(User::USUARIO_ADMIN)))
            return $acceso;

        return View::make("usuarios/tipo/admin/config/general");
    }

    function post_guardar() {
        $data = Input::all();

        if (!ConfigInstancia::validar($data)) {
            return Redirect::back()->withInput()->with(User::mensaje("error", null, trans("config.general.seccion.error.validacion"), 2));
        }

        ConfigInstancia::registrar($data);

        return Redirect::back()->withInput()->with(User::mensaje("exito", null, trans("config.general.post.exito"), 2));
    }

}

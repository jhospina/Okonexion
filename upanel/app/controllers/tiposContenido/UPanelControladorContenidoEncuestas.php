<?php

class UPanelControladorContenidoEncuestas extends Controller {

    function index() {
        if (!Aplicacion::existe())
            return Redirect::to("/");

        $app = Aplicacion::obtener();

        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");

        return View::make("usuarios/tipo/regular/app/administracion/encuestas/index")->with("app", $app);
    }

    function vista_agregar() {
        if (!Aplicacion::existe())
            return Redirect::to("/");

        $app = Aplicacion::obtener();

        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");

        return View::make("usuarios/tipo/regular/app/administracion/encuestas/agregar")->with("app", $app);
    }

}

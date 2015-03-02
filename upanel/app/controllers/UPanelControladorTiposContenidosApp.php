<?php

class UPanelControladorTiposContenidosApp extends Controller {

    public function institucional() {
        
    }

    public function noticias() {
        if (!Aplicacion::existe())
            return Redirect::to("/");
        $app = Aplicacion::obtener();
        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");
        return View::make("usuarios/tipo/regular/app/administracion/noticias/index")->with("app", $app);
    }

    public function noticias_agregar() {
        if (!Aplicacion::existe())
            return Redirect::to("/");
        $app = Aplicacion::obtener();
        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");
        return View::make("usuarios/tipo/regular/app/administracion/noticias/agregar")->with("app", $app);
    }

    public function encuestas() {
        
    }

    public function pqr() {
        
    }

}

<?php

class UPanelControladorContenidoNoticias extends Controller {

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


        $tax = TaxonomiaContenidoApp::obtener(Contenido_Noticias::taxonomia);
        //Obtiene las categorias de las noticias de la taxonomia
        $cats = $tax->terminocontenidoapps;


        return View::make("usuarios/tipo/regular/app/administracion/noticias/agregar")->with("app", $app)->with("cats", $cats)->with("tax", $tax);
    }

    function ajax_noticias_agregarCategoria() {
        $data = Input::all();
        $cat = new TerminoContenidoApp;
        $cat->id_taxonomia = $data["id_tax"];
        $cat->nombre = $data["cat"];
        $cat->save();

        return $cat->id;
    }

}

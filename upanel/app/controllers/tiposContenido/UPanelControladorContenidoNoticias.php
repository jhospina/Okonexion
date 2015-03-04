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

    public function noticias_agregarPublicar() {
        $data = Input::all();
        $app = Aplicacion::obtener();
        $nombreTC = TipoContenido::obtenerNombre($app->diseno, Contenido_Noticias::nombre);

        $contenido = new ContenidoApp;
        $contenido->id_aplicacion = Aplicacion::ID();
        $contenido->id_usuario = Auth::user()->id;
        $contenido->tipo = Contenido_Noticias::nombre;
        $contenido->titulo = $data[Contenido_Noticias::configTitulo];
        $contenido->contenido = $data[Contenido_Noticias::configDescripcion];
        $contenido->estado = ContenidoApp::ESTADO_PUBLICO;

        @$contenido->save();

        if (is_int((int) $data[Contenido_Noticias::configImagen . "_id"]))
            ContenidoApp::agregarMetaDato($contenido->id, Contenido_Noticias::configImagen . "_principal", $data[Contenido_Noticias::configImagen . "_id"]);

        $cats = array(); //almacenara los ID de las categorias

        foreach ($data as $indice => $valor) {
            if (strpos($indice, "cat-") !== false) {
                $cats[] = $valor;
            }
        }

        ContenidoApp::establecerTerminosTaxonomia($contenido->id, $cats, Contenido_Noticias::taxonomia);

        return Redirect::to("aplicacion/administrar/noticias")->with(User::mensaje("exito", null, "¡" . Util::eliminarPluralidad($nombreTC) . " publicada con exito!", 2));
    }

    function ajax_noticias_agregarCategoria() {
        $data = Input::all();
        $cat = new TerminoContenidoApp;
        $cat->id_taxonomia = $data["id_tax"];
        $cat->nombre = $data["cat"];
        $cat->save();

        return $cat->id;
    }

    function ajax_noticias_subirImagen() {

        $output = [];


        $imagenID = Contenido_Noticias::configImagen;

        $extension = strtolower(Input::file($imagenID)->getClientOriginalExtension());
        $size = Input::file($imagenID)->getSize();
        $path = 'usuarios/uploads/' . Auth::user()->id . "/";
        $imagen = Util::obtenerTimeStamp() . "." . $extension;
        Input::file($imagenID)->move($path, $imagen);

        if (!is_null($imagen_id = ContenidoApp::agregarImagen($imagen, URL::to($path . $imagen))))
            $output[$imagenID . "_id"] = $imagen_id;
        else
            $output["error"] = "Hubo un error al tratar de procesar su solicitud. Intentelo de nuevo más tarde";


        return json_encode($output);
    }

}

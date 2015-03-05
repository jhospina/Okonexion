<?php

class UPanelControladorContenidoNoticias extends Controller {

    public function noticias() {
        if (!Aplicacion::existe())
            return Redirect::to("/");
        $app = Aplicacion::obtener();
        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");

        $noticias = ContenidoApp::where("tipo", Contenido_Noticias::nombre)->where("id_usuario", Auth::user()->id)->paginate(30);

        return View::make("usuarios/tipo/regular/app/administracion/noticias/index")->with("app", $app)->with("noticias", $noticias);
    }

    public function noticias_agregar() {
        if (!Aplicacion::existe())
            return Redirect::to("/");
        $app = Aplicacion::obtener();
        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");


        $tax = TaxonomiaContenidoApp::obtener(Contenido_Noticias::taxonomia);
        //Obtiene las categorias de las noticias de la taxonomia
        $cats = $tax->terminos;

        return View::make("usuarios/tipo/regular/app/administracion/noticias/agregar")->with("app", $app)->with("cats", $cats)->with("tax", $tax);
    }

    public function noticias_agregarPublicar() {

        $data = Input::all();
        $app = Aplicacion::obtener();
        $nombreTC = TipoContenido::obtenerNombre($app->diseno, Contenido_Noticias::nombre);

        //Valida los datos enviados
        if (!is_null($valid = Contenido_Noticias::validar($data)))
            return $valid;

        Contenido_Noticias::agregar($data, ContenidoApp::ESTADO_PUBLICO);

        return Redirect::to("aplicacion/administrar/noticias")->with(User::mensaje("exito", null, "¡" . Util::eliminarPluralidad($nombreTC) . " publicada con exito!", 2));
    }

    public function noticias_agregarGuardar() {

        $data = Input::all();
        $app = Aplicacion::obtener();
        $nombreTC = TipoContenido::obtenerNombre($app->diseno, Contenido_Noticias::nombre);

        //Valida los datos enviados
        if (!is_null($valid = Contenido_Noticias::validar($data)))
            return $valid;

        Contenido_Noticias::agregar($data, ContenidoApp::ESTADO_GUARDADO);

        return Redirect::to("aplicacion/administrar/noticias")->with(User::mensaje("exito", null, "¡" . Util::eliminarPluralidad($nombreTC) . " guardada con exito!", 2));
    }

    public function noticias_editar($id_noticia) {
        if (!Aplicacion::existe())
            return Redirect::to("/");
        $app = Aplicacion::obtener();
        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");

        $noticia = ContenidoApp::find($id_noticia);

        if (is_null($noticia))
            return Redirect::to("/");

        //Evita que se puedan editar las noticias de otros usuarios
        if ($noticia->id_usuario != Auth::user()->id)
            return Redirect::to("/");

        $tax = TaxonomiaContenidoApp::obtener(Contenido_Noticias::taxonomia);
        //Obtiene las categorias de las noticias de la taxonomia
        $cats = $tax->terminos;

        return View::make("usuarios/tipo/regular/app/administracion/noticias/editar")->with("app", $app)->with("cats", $cats)->with("tax", $tax)->with("noticia", $noticia);
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

    function ajax_noticias_eliminarImagen() {
        $id_imagen = Input::get("id_imagen");

        if (ContenidoApp::eliminarImagen($id_imagen))
            MetaContenidoApp::where("clave", Contenido_Noticias::nombre . "_principal")->where("valor", $id_imagen)->delete();
    }

}

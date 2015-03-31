<?php

class UPanelControladorContenidoNoticias extends Controller {

    public function noticias() {
        if (!Aplicacion::existe())
            return Redirect::to("/");
        $app = Aplicacion::obtener();
        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");

        $noticias = ContenidoApp::where("tipo", Contenido_Noticias::nombre)->where("id_usuario", Auth::user()->id)->orderBy("id", "DESC")->paginate(10);
      
        return View::make("usuarios/tipo/regular/app/administracion/noticias/index")->with("app", $app)->with(Contenido_Noticias::nombre, $noticias);
    }

    public function noticias_vistaAgregar() {
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

    public function noticias_vistaEditar($id_noticia) {
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

    public function noticias_categorias() {

        if (!Aplicacion::existe())
            return Redirect::to("/");
        $app = Aplicacion::obtener();
        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");

        $tax = TaxonomiaContenidoApp::obtener(Contenido_Noticias::taxonomia);

        //Obtiene las categorias de las noticias de la taxonomia
        $cats = $tax->terminos;

        return View::make("usuarios/tipo/regular/app/administracion/noticias/categorias")->with("app", $app)->with("cats", $cats)->with("tax", $tax);
    }

    public function noticias_publicar() {

        $data = Input::all();
        $app = Aplicacion::obtener();
        $nombreTC = TipoContenido::obtenerNombre($app->diseno, Contenido_Noticias::nombre);

        //Valida los datos enviados
        if (!is_null($valid = Contenido_Noticias::validar($data)))
            return $valid;

        Aplicacion::aumentarNumeroBaseInfo();

        //Agrega una nueva noticia
        if (!isset($data["id_noticia"])) {
            Contenido_Noticias::agregar($data, ContenidoApp::ESTADO_PUBLICO);
            return Redirect::to("aplicacion/administrar/noticias")->with(User::mensaje("exito", null, "¡" . Util::eliminarPluralidad($nombreTC) . " publicada con exito!", 2));
        } else {
            //Edita una noticia
            Contenido_Noticias::editar($data["id_noticia"], $data, ContenidoApp::ESTADO_PUBLICO);
            return Redirect::to("aplicacion/administrar/noticias")->with(User::mensaje("info", null, "¡" . Util::eliminarPluralidad($nombreTC) . " editada y publicada con exito!", 2));
        }
    }

    public function noticias_guardar() {

        $data = Input::all();
        $app = Aplicacion::obtener();
        $nombreTC = TipoContenido::obtenerNombre($app->diseno, Contenido_Noticias::nombre);

        //Valida los datos enviados
        if (!is_null($valid = Contenido_Noticias::validar($data)))
            return $valid;


        Aplicacion::aumentarNumeroBaseInfo();

        //Agrega una nueva noticia
        if (!isset($data["id_noticia"])) {
            Contenido_Noticias::agregar($data, ContenidoApp::ESTADO_GUARDADO);
            return Redirect::to("aplicacion/administrar/noticias")->with(User::mensaje("exito", null, "¡" . Util::eliminarPluralidad($nombreTC) . " guardada con exito!", 2));
        } else {
            //Edita una noticia
            Contenido_Noticias::editar($data["id_noticia"], $data, ContenidoApp::ESTADO_GUARDADO);
            return Redirect::to("aplicacion/administrar/noticias")->with(User::mensaje("info", null, "¡" . Util::eliminarPluralidad($nombreTC) . " editada y guardada!", 2));
        }
    }

    function ajax_noticias_agregarCategoria() {
        $data = Input::all();
        $cat = new TerminoContenidoApp;
        $cat->id_taxonomia = $data["id_tax"];
        $cat->nombre = $data["cat"];
        $cat->save();

        return $cat->id;
    }

    function ajax_noticias_editarCategoria() {
        $data = Input::all();
        $cat = TerminoContenidoApp::find($data["id_cat"]);
        $cat->nombre = $data["cat"];
        $cat->save();
    }

    function ajax_noticias_eliminarCategoria() {
        $data = Input::all();
        $cat = TerminoContenidoApp::find($data["id_cat"]);
        $cat->delete();

        //Elimina cualquier relacion de los contenidos con el termino
        DB::table("relacion_contenidos_terminos_App")->where("id_termino", $data["id_cat"])->delete();
    }

    function ajax_noticias_eliminarNoticia() {
        $data = Input::all();
        $noticia = ContenidoApp::find($data["id_noticia"]);
        $noticia->delete();
        //Elimina cualquier relacion del contenido con los terminos
        DB::table("relacion_contenidos_terminos_App")->where("id_contenido", $data["id_noticia"])->delete();

        $id_imagen = $data["id_imagen"];

        if (strlen($id_imagen) < 1)
            return;

        Contenido_Noticias::eliminarImagen($id_imagen);
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

        if (strlen($id_imagen) < 1)
            return;

        Contenido_Noticias::eliminarImagen($id_imagen);
    }

}

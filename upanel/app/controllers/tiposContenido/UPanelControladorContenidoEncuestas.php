<?php

class UPanelControladorContenidoEncuestas extends Controller {

    function index() {
        if (!Aplicacion::existe())
            return Redirect::to("/");

        $app = Aplicacion::obtener();

        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");

        $consulta = ContenidoApp::where("tipo", Contenido_Encuestas::nombre)->where("id_usuario", Auth::user()->id)->where("estado", ContenidoApp::ESTADO_PUBLICO)->take(1)->get();

        foreach ($consulta as $encuesta_vigente)
            break;

        $guardados = ContenidoApp::where("tipo", Contenido_Encuestas::nombre)->where("id_usuario", Auth::user()->id)->where("estado", ContenidoApp::ESTADO_GUARDADO)->get();
        $historial = ContenidoApp::where("tipo", Contenido_Encuestas::nombre)->where("id_usuario", Auth::user()->id)->where("estado", ContenidoApp::ESTADO_ARCHIVADO)->paginate(20);


        return View::make("usuarios/tipo/regular/app/administracion/encuestas/index")->with("app", $app)->with("encuesta_vigente", $encuesta_vigente)->with("guardados", $guardados)->with("historial", $historial);
    }

    function vista_historico($id_encuesta) {
        if (!Aplicacion::existe())
            return Redirect::to("/");

        $app = Aplicacion::obtener();

        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");

        $encuesta = ContenidoApp::find($id_encuesta);

        //Evita que se puedan editar las encuestas de otros usuarios
        if ($encuesta->id_usuario != Auth::user()->id)
            return Redirect::to("/");

        if ($encuesta->estado != ContenidoApp::ESTADO_ARCHIVADO)
            return Redirect::to("/");

        return View::make("usuarios/tipo/regular/app/administracion/encuestas/historico")->with("app", $app)->with("encuesta", $encuesta);
    }

    function vista_agregar() {
        if (!Aplicacion::existe())
            return Redirect::to("/");

        $app = Aplicacion::obtener();

        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");

        return View::make("usuarios/tipo/regular/app/administracion/encuestas/agregar")->with("app", $app);
    }

    function vista_editar($id_encuesta) {
        if (!Aplicacion::existe())
            return Redirect::to("/");

        $app = Aplicacion::obtener();

        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");

        $encuesta = ContenidoApp::find($id_encuesta);

        if (is_null($encuesta))
            return Redirect::to("/");

        //Evita que se puedan editar las encuestas de otros usuarios
        if ($encuesta->id_usuario != Auth::user()->id)
            return Redirect::to("/");

        if ($encuesta->estado != ContenidoApp::ESTADO_GUARDADO)
            return Redirect::to("/");


        return View::make("usuarios/tipo/regular/app/administracion/encuestas/editar")->with("app", $app)->with("encuesta", $encuesta);
    }

    function publicar() {
        $data = Input::all();
        $app = Aplicacion::obtener();
        $nombreTC = TipoContenido::obtenerNombre($app->diseno, Contenido_Encuestas::nombre);

        Aplicacion::aumentarNumeroBaseInfo();

        //Archiva la encuesta publicada vigente
        DB::update("UPDATE " . ContenidoApp::nombreTabla() . " SET estado='" . ContenidoApp::ESTADO_ARCHIVADO . "' WHERE id_usuario='" . Auth::user()->id . "' and tipo='" . Contenido_Encuestas::nombre . "' and estado='" . ContenidoApp::ESTADO_PUBLICO . "'");

        Contenido_Encuestas::agregar($data, ContenidoApp::ESTADO_PUBLICO);
        return Redirect::to("aplicacion/administrar/encuestas")->with(User::mensaje("exito", null, "¡" . Util::eliminarPluralidad($nombreTC) . " publicada con exito!", 2));
    }

    function guardar() {
        $data = Input::all();
        $app = Aplicacion::obtener();
        $nombreTC = TipoContenido::obtenerNombre($app->diseno, Contenido_Encuestas::nombre);

        Aplicacion::aumentarNumeroBaseInfo();

        //Agrega
        if (!isset($data["id_encuesta"])) {
            Contenido_Encuestas::agregar($data, ContenidoApp::ESTADO_GUARDADO);
            return Redirect::to("aplicacion/administrar/encuestas")->with(User::mensaje("exito", null, "¡" . Util::eliminarPluralidad($nombreTC) . " guardada con exito!", 2));
        } else {
            //Edita 
            Contenido_Encuestas::editar($data["id_encuesta"], $data, ContenidoApp::ESTADO_GUARDADO);
            return Redirect::to("aplicacion/administrar/encuestas")->with(User::mensaje("info", null, "¡" . Util::eliminarPluralidad($nombreTC) . " editada y guardada con exito!", 2));
        }
    }

    function ajax_eliminar_encuesta() {
        $data = Input::all();
        $encuesta = ContenidoApp::find($data["id_encuesta"]);
        if ($encuesta->estado == ContenidoApp::ESTADO_GUARDADO) {
            $encuesta->delete();
            ContenidoApp::vaciarMetadatos($data["id_encuesta"]);
        }
    }

}

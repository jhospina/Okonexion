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

        return View::make("usuarios/tipo/regular/app/administracion/encuestas/index")->with("app", $app)->with("encuesta_vigente", $encuesta_vigente);
    }

    function vista_agregar() {
        if (!Aplicacion::existe())
            return Redirect::to("/");

        $app = Aplicacion::obtener();

        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");

        return View::make("usuarios/tipo/regular/app/administracion/encuestas/agregar")->with("app", $app);
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
            return Redirect::to("aplicacion/administrar/encuestas")->with(User::mensaje("exito", null, "¡" . Util::eliminarPluralidad($nombreTC) . " publicada con exito!", 2));
        } else {
            //Edita 
            Contenido_Encuestas::editar($data["id_encuesta"], $data, ContenidoApp::ESTADO_GUARDADO);
            return Redirect::to("aplicacion/administrar/encuestas")->with(User::mensaje("info", null, "¡" . Util::eliminarPluralidad($nombreTC) . " editada y publicada con exito!", 2));
        }
    }

}

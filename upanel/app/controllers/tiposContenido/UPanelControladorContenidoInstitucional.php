<?php

class UPanelControladorContenidoInstitucional extends Controller {

    public function institucional() {
        if (!Aplicacion::existe())
            return Redirect::to("/");
        $app = Aplicacion::obtener();
        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");

        $insts = ContenidoApp::where("tipo", Contenido_Institucional::nombre)->where("id_usuario", Auth::user()->id)->orderBy("id", "DESC")->paginate(20);

        return View::make("usuarios/tipo/regular/app/administracion/institucional/index")->with("app", $app)->with("insts", $insts);
    }

    public function institucional_vistaAgregar() {
        if (!Aplicacion::existe())
            return Redirect::to("/");
        $app = Aplicacion::obtener();
        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");

        return View::make("usuarios/tipo/regular/app/administracion/institucional/agregar")->with("app", $app);
    }

    public function institucional_vistaEditar($id_inst) {
        if (!Aplicacion::existe())
            return Redirect::to("/");
        $app = Aplicacion::obtener();
        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");

        $inst = ContenidoApp::find($id_inst);

        if (is_null($inst))
            return Redirect::to("/");

        //Evita que se puedan editar las insts de otros usuarios
        if ($inst->id_usuario != Auth::user()->id)
            return Redirect::to("/");

        return View::make("usuarios/tipo/regular/app/administracion/institucional/editar")->with("app", $app)->with("inst", $inst);
    }

    public function institucional_guardar() {
        $data = Input::all();
        $app = Aplicacion::obtener();
        $nombreTC = TipoContenido::obtenerNombre($app->diseno, Contenido_Institucional::nombre);

        //Valida los datos enviados
        if (!is_null($valid = Contenido_Institucional::validar($data)))
            return $valid;


        Aplicacion::aumentarNumeroBaseInfo();

        //Agrega una nueva informacion institucional
        if (!isset($data["id_inst"])) {
            Contenido_Institucional::agregar($data, ContenidoApp::ESTADO_GUARDADO);
            return Redirect::to("aplicacion/administrar/institucional")->with(User::mensaje("exito", null, "¡" . Util::eliminarPluralidad($nombreTC) . " guardada con exito!", 2));
        } else {
            //Edita una informacion institucional
            Contenido_Institucional::editar($data["id_inst"], $data, ContenidoApp::ESTADO_GUARDADO);
            return Redirect::to("aplicacion/administrar/institucional")->with(User::mensaje("info", null, "¡" . Util::eliminarPluralidad($nombreTC) . " editada y guardada!", 2));
        }
    }

    public function institucional_publicar() {
        $data = Input::all();
        $app = Aplicacion::obtener();
        $nombreTC = TipoContenido::obtenerNombre($app->diseno, Contenido_Institucional::nombre);

        //Valida los datos enviados
        if (!is_null($valid = Contenido_Institucional::validar($data)))
            return $valid;

        Aplicacion::aumentarNumeroBaseInfo();

        //Agrega una nueva informacion institucional
        if (!isset($data["id_inst"])) {
            Contenido_Institucional::agregar($data, ContenidoApp::ESTADO_PUBLICO);
            return Redirect::to("aplicacion/administrar/institucional")->with(User::mensaje("exito", null, "¡" . Util::eliminarPluralidad($nombreTC) . " publicada con exito!", 2));
        } else {
            //Edita una informacion institucional
            Contenido_Institucional::editar($data["id_inst"], $data, ContenidoApp::ESTADO_PUBLICO);
            return Redirect::to("aplicacion/administrar/institucional")->with(User::mensaje("info", null, "¡" . Util::eliminarPluralidad($nombreTC) . " editada y publicada con exito!", 2));
        }
    }

    function ajax_institucional_eliminarInstitucional() {
        $data = Input::all();
        $inst = ContenidoApp::find($data["id_inst"]);
        $inst->delete();
    }

}

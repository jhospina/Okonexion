<?php

class Contenido_Institucional {

    const nombre = "institucional";
    const icono = "glyphicon-education";
    //********************************************************
    //DATOS DE CONFIGURACION EN APP***************************
    //********************************************************

    const configTitulo = "titulo"; //Indica el titulo de la inst
    const configDescripcion = "descripcion"; //Indica la descripcion de la imagen

    static function validar($data) {
        if (strlen($data[Contenido_Institucional::configTitulo]) < 1) {
            return Redirect::back()->withInput()->with(User::mensaje("error", null, "Debes escribir un titulo.", 2));
        }

        if (strlen($data[Contenido_Institucional::configDescripcion]) < 5) {
            return Redirect::back()->withInput()->with(User::mensaje("error", null, "Debes escribir una descripción.", 2));
        }

        return null;
    }

    /** Agrega un nuevo contenido de informacion institucional
     * 
     * @param type $data Un array con los datos de la informacion institucional a agregar. 
     * @param type $estado El estado en el que debe quedar la informacion institucional
     */
    static function agregar($data, $estado) {
        $contenido = new ContenidoApp;
        $contenido->id_aplicacion = Aplicacion::ID();
        $contenido->id_usuario = Auth::user()->id;
        $contenido->tipo = Contenido_Institucional::nombre;
        $contenido->titulo = $data[Contenido_Institucional::configTitulo];
        $contenido->contenido = Util::descodificarTexto($data[Contenido_Institucional::configDescripcion]);
        $contenido->estado = $estado;

        @$contenido->save();
    }

    /** Edita el contenido de una informacion institucional
     * 
     * @param type $id_inst El id de la informacion institucional a editar
     * @param type $data Los datos a editar en un array
     * @param type $estado El estado en que debe quedar la informacion institucional
     */
    static function editar($id_inst, $data, $estado) {
        $contenido = ContenidoApp::find($id_inst);
        $contenido->tipo = Contenido_Institucional::nombre;
        $contenido->titulo = $data[Contenido_Institucional::configTitulo];
        $contenido->contenido = Util::descodificarTexto($data[Contenido_Institucional::configDescripcion]);
        $contenido->estado = $estado;
        @$contenido->save();
    }

    /** Obtiene un JSON preparado de las informacion institucional para el envio a la aplicaciòn movil
     * 
     * @param Int $id_app id de la aplicacion
     * @return String JSON
     */
    static function cargarDatosJson($id_app) {
        $insts = ContenidoApp::where("tipo", Contenido_Institucional::nombre)->where("id_aplicacion", $id_app)->where("estado", ContenidoApp::ESTADO_PUBLICO)->orderBy("id", "DESC")->get();

        $data_inst = array();
        $n = 1;

        foreach ($insts as $inst) {
            $data_inst["titulo" . $n] = $inst->titulo;
            $data_inst["descripcion" . $n] = $inst->contenido; //str_replace("\n", "<br/>", Input::get("mensaje"));
            $n++;
        }
        //Construct 2
        //return "@" . Contenido_Institucional::nombre . "@" . Aplicacion::prepararDatosParaApp($data_inst) . "@" . Contenido_Institucional::nombre . "@";
        return Aplicacion::prepararDatosParaApp($data_inst);
    }

}

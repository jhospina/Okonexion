<?php

class Contenido_Institucional {

    const nombre = "institucional";
    const icono = "glyphicon-education";
    //********************************************************
    //DATOS DE CONFIGURACION EN APP***************************
    //********************************************************

    const configTitulo = "titulo"; //Indica el titulo de la inst
    const configDescripcion = "descripcion"; //Indica la descripcion de la imagen

    /** Obtiene el nombre por defecto de este tipo de contenido
     * 
     * @return type
     */

    static function nombreDefecto() {
        return trans("app.tipo.contenido.institucional");
    }

    static function validar($data) {
        if (strlen($data[Contenido_Institucional::configTitulo]) < 1) {
            return Redirect::back()->withInput()->with(User::mensaje("error", null, trans("app.admin.inst.info.titulo.error"), 2));
        }

        if (strlen($data[Contenido_Institucional::configDescripcion]) < 5) {
            return Redirect::back()->withInput()->with(User::mensaje("error", null, trans("app.admin.inst.info.descripcion.error"), 2));
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

        if ($estado == ContenidoApp::ESTADO_PUBLICO) {
            //Obtiene la ultima posicion indicada del orden de ubicacion para el contenido insittucional
            $orden_pos = MetaContenidoApp::where("id_usuario", Auth::user()->id)->where("clave", Contenido_Institucional::nombre . "_pos")->orderBy("valor", "DESC")->take(1)->get();
            if (count($orden_pos) > 0) {
                foreach ($orden_pos as $meta_orden)
                    break;
                //Agrega un metadato con la ultima posicion en el orden indicando
                ContenidoApp::agregarMetaDato($contenido->id, Contenido_Institucional::nombre . "_pos", (($meta_orden->valor) + 1));
            }else{
                     ContenidoApp::agregarMetaDato($contenido->id, Contenido_Institucional::nombre . "_pos",1);
            }
        }
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

    static function obtenerOrden($id_usuario) {
        return MetaContenidoApp::where("id_usuario", $id_usuario)->where("clave", Contenido_Institucional::nombre . "_pos")->orderBy("valor", "ASC")->get();
    }

    /** Obtiene un JSON informacion institucional
     *
     * @param Int $id_app id de la aplicacion
     * @param Int $take Indica el numero de registros a obtener
     * @param Int $skip Indica el numero de registros a omitir
     * @return String JSON
     */
    static function cargarDatosJson($id_app, $id_usuario) {

        $orden_insts = Contenido_Institucional::obtenerOrden($id_usuario);
        $data_inst = array();
        $n = 1;

        foreach ($orden_insts as $meta) {

            $inst = ContenidoApp::find(intval($meta->id_contenido));
            if ($inst->estado == ContenidoApp::ESTADO_PUBLICO) {
                $data_inst["id" . $n] = $inst->id;
                $data_inst["titulo" . $n] = $inst->titulo;
                $data_inst["descripcion" . $n] = $inst->contenido; //str_replace("\n", "<br/>", Input::get("mensaje"));
                $data_inst["fecha" . $n] = $inst->updated_at;
                $n++;
            }
        }
        if (count($data_inst) > 0)
            return Aplicacion::prepararDatosParaApp($data_inst);
        return null;
    }

}

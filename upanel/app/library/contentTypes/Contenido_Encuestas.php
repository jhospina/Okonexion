<?php

class Contenido_Encuestas {

    const nombre = "encuestas";
    const icono = "glyphicon-stats";
    //********************************************************
    //DATOS DE CONFIGURACION EN APP***************************
    //********************************************************
    const configId = "id";
    const configTitulo = "titulo";
    const configDescripcion = "descripcion";
    const configRespuesta = "resp";
    const configFecha = "fecha";
    const configRespuestaUsuario = "solUser_";
    const configNumeroParametrosRespuesta = 3;
    const NUMERO_REGISTROS_DESCARGAR = 8; // Indica la tanda de registros a descargar por parte de la app

    
     /** Obtiene el nombre por defecto de este tipo de contenido
     * 
     * @return type
     */
    static function nombreDefecto(){
        return trans("app.tipo.contenido.encuestas");
    }
    
    
    static function agregar($data, $estado) {
        $contenido = new ContenidoApp;
        $contenido->id_aplicacion = Aplicacion::ID();
        $contenido->id_usuario = Auth::user()->id;
        $contenido->tipo = Contenido_Encuestas::nombre;
        $contenido->titulo = $data[Contenido_Encuestas::configTitulo];
        $contenido->contenido = strip_tags($data[Contenido_Encuestas::configDescripcion]);
        $contenido->estado = $estado;

        @$contenido->save();

        $respuestas = $data[Contenido_Encuestas::configRespuesta];

        for ($i = 0; $i < count($respuestas); $i++) {
            if (count($respuestas) - 1 != $i) {
                ContenidoApp::agregarMetaDato($contenido->id, Contenido_Encuestas::configRespuesta . "" . ($i + 1), $respuestas[$i]);
                ContenidoApp::agregarMetaDato($contenido->id, "total_" . Contenido_Encuestas::configRespuesta . "" . ($i + 1), 0);
            }
        }

        ContenidoApp::agregarMetaDato($contenido->id, "total", 0);
    }

    static function editar($id_encuesta, $data, $estado) {
        $contenido = ContenidoApp::find($id_encuesta);

        //La encuesta solo se puede editar si esta guardada
        if ($contenido->estado == ContenidoApp::ESTADO_GUARDADO) {

            $contenido->id_aplicacion = Aplicacion::ID();
            $contenido->id_usuario = Auth::user()->id;
            $contenido->tipo = Contenido_Encuestas::nombre;
            $contenido->titulo = $data[Contenido_Encuestas::configTitulo];
            $contenido->contenido = strip_tags($data[Contenido_Encuestas::configDescripcion]);
            $contenido->estado = $estado;

            @$contenido->save();

            $respuestas = $data[Contenido_Encuestas::configRespuesta];

            //Elimina todas las respuestas de la encuesta
            ContenidoApp::vaciarMetadatos($contenido->id);

            for ($i = 0; $i < count($respuestas); $i++) {
                if (count($respuestas) - 1 != $i) {
                    ContenidoApp::agregarMetaDato($contenido->id, Contenido_Encuestas::configRespuesta . "" . ($i + 1), $respuestas[$i]);
                }
            }
        }
    }

    /** Obtiene un array con las respuestas y el total de una encuesta
     * 
     * @param Int $id_encuesta El id de la encuesta
     * @return Array Respuestas
     */
    static function obtenerRespuestas($id_encuesta) {
        $qry_resps = MetaContenidoApp::where("id_contenido", $id_encuesta)->where("clave", "like", "resp%")->get();
        $respuestas = [];
        $n = 1;
        foreach ($qry_resps as $resp) {
            $respuestas[Contenido_Encuestas::configId . $n] = $resp->id;
            $respuestas[$resp->clave] = $resp->valor;
            $respuestas["total_" . $resp->clave] = ContenidoApp::obtenerValorMetadato($id_encuesta, "total_" . $resp->clave);
            $n++;
        }

        return $respuestas;
    }

    /** Obtiene la encuesta vigente de un usuario
     * 
     * @param type $id_usuario El id del usuario
     * @return ContenidoApp Encuesta
     */
    static function obtenerEncuestaVigente($id_usuario) {
        $consulta = ContenidoApp::where("tipo", Contenido_Encuestas::nombre)->where("id_usuario", $id_usuario)->where("estado", ContenidoApp::ESTADO_PUBLICO)->take(1)->get();
        if (count($consulta) > 0) {
            foreach ($consulta as $encuesta_vigente)
                return $encuesta_vigente;
        } else {
            return null;
        }
    }

    /** Ingresa un registro que indica una respuesta de usuario desde un dispositivo movil a una encuesta
     * 
     * @param type $id_respuesta Id de la respuesta
     * @param type $id_encuesta Id de la encuesta
     * @param type $id_dispositivo Id del dispositivo donde se realizo la encuesta
     * @param type $id_usuario Id del usuario al que pertenece la encuesta
     */
    static function contestar($id_respuesta, $id_encuesta, $id_dispositivo, $id_usuario) {
        //Verifica si el usuario no ha contestado la encuesta y si la encuesta continua en vigencia
        if (!Contenido_Encuestas::verificarRespuestaUsuario($id_encuesta, $id_dispositivo) && ContenidoApp::verificarEstado($id_encuesta,  ContenidoApp::ESTADO_PUBLICO)) {
            //Ingresa la respuesta del usuario a la encuesta en la base de datos
            ContenidoApp::agregarMetaDato($id_encuesta, Contenido_Encuestas::configRespuestaUsuario . $id_dispositivo, $id_respuesta, $id_usuario);
            Contenido_Encuestas::actualizarResultados($id_encuesta);
            return false;
        } else {
            return true;
        }
    }
    
 

    /** Verifica si un usuario de dispositivo ya ha contestado una encuesta
     * 
     * @param Int $id_encuesta El id de la encuesta
     * @param String $id_dispositivo El id del dispositivo
     * @return boolean
     */
    static function verificarRespuestaUsuario($id_encuesta, $id_dispositivo) {
        return ContenidoApp::existeMetadato($id_encuesta, Contenido_Encuestas::configRespuestaUsuario . $id_dispositivo);
    }

    /** Actualiza todos los totales de las respuestas hasta el momento constestada por los usuarios de una encuesta
     * 
     * @param type $id_encuesta
     */
    static function actualizarResultados($id_encuesta) {
        $respuestas = Contenido_Encuestas::obtenerRespuestas($id_encuesta);

        $total_respuestas = array();
        for ($i = 0; $i < count($respuestas) / Contenido_Encuestas::configNumeroParametrosRespuesta; $i++) {
            $num = $i + 1;
            //Obtiene todas respuestas de los usuarios sobre una respuesta posible de la encuesta
            $respuestas_usuarios = MetaContenidoApp::where("id_contenido", $id_encuesta)->where("clave", "like", Contenido_Encuestas::configRespuestaUsuario . "%")->where("valor", $num)->get();
            $total_respuestas["total_" . Contenido_Encuestas::configRespuesta . $num] = count($respuestas_usuarios);
        }

        $total = 0;

        //Actualiza los totales de las respeustas de las respuestas de la encuesta
        foreach ($total_respuestas as $clave => $valor) {
            ContenidoApp::actualizarMetadato($id_encuesta, $clave, $valor);
            $total+=intval($valor);
        }

        ContenidoApp::actualizarMetadato($id_encuesta, "total", $total);
    }

    static function obtenerJSON_encuesta_vigente($id_usuario) {
        $encuesta = Contenido_Encuestas::obtenerEncuestaVigente($id_usuario);

        if (is_null($encuesta))
            return null;

        $respuestas = Contenido_Encuestas::obtenerRespuestas($encuesta->id);
        $data_encuesta = array();

        $data_encuesta[Contenido_Encuestas::configId] = $encuesta->id;
        $data_encuesta[Contenido_Encuestas::configTitulo] = $encuesta->titulo;
        $data_encuesta[Contenido_Encuestas::configDescripcion] = $encuesta->contenido;
        $data_encuesta[Contenido_Encuestas::configFecha] = $encuesta->updated_at;

        $data_encuesta[Contenido_Encuestas::configRespuesta] = $respuestas;

        return Aplicacion::prepararDatosParaApp($data_encuesta);
    }

    static function obtenerJSON_encuestas_archivadas($id_app, $take = 8, $skip = 0) {
        $consulta = ContenidoApp::where("tipo", Contenido_Encuestas::nombre)->where("id_aplicacion", $id_app)->where("estado", ContenidoApp::ESTADO_ARCHIVADO)->orderBy("updated_at", "DESC")->take($take)->skip($skip)->get();
        if (count($consulta) > 0) {
            
            $conjunto_encuestas = array();
            
            $n=0;
            foreach ($consulta as $encuesta) {
                
                $data_encuesta = array();

                $respuestas = Contenido_Encuestas::obtenerRespuestas($encuesta->id);

                $data_encuesta[Contenido_Encuestas::configId] = $encuesta->id;
                $data_encuesta[Contenido_Encuestas::configTitulo] = $encuesta->titulo;
                $data_encuesta[Contenido_Encuestas::configDescripcion] = $encuesta->contenido;
                $data_encuesta[Contenido_Encuestas::configFecha] = $encuesta->updated_at;
                
                $data_encuesta[Contenido_Encuestas::configRespuesta] = $respuestas;
                
                $conjunto_encuestas[Contenido_Encuestas::nombre.$n]=$data_encuesta;
                $n++;
            }

            return Aplicacion::prepararDatosParaApp( $conjunto_encuestas);
        } else {
            return null;
        }
    }

}

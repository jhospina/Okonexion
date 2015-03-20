<?php

class Contenido_Encuestas {

    const nombre = "encuestas";
    const icono = "glyphicon-stats";
    //********************************************************
    //DATOS DE CONFIGURACION EN APP***************************
    //********************************************************
    const configTitulo = "titulo";
    const configDescripcion = "descripcion";
    const configRespuesta = "resp";

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
                ContenidoApp::agregarMetaDato($contenido->id, Contenido_Encuestas::configRespuesta . "" . $i, $respuestas[$i]);
                ContenidoApp::agregarMetaDato($contenido->id, "total_" . Contenido_Encuestas::configRespuesta . "" . $i, 0);
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
                    ContenidoApp::agregarMetaDato($contenido->id, Contenido_Encuestas::configRespuesta . "" . $i, $respuestas[$i]);
                }
            }
        }
    }

    static function obtenerRespuestas($id_encuesta) {
        $qry_resps = MetaContenidoApp::where("id_contenido", $id_encuesta)->where("clave", "like", "resp%")->get();
        $respuestas = [];
        foreach ($qry_resps as $resp) {
            $respuestas[$resp->clave] = $resp->valor;
            $respuestas["total_" . $resp->clave] = ContenidoApp::obtenerValorMetadato($id_encuesta, "total_" . $resp->clave);
        }

        return $respuestas;
    }

}
